<?php
/**
 * Created by PhpStorm.
 * User: xiaolin
 * Date: 2018/11/2
 * Time: 下午4:03
 */

define('UPTIMEROBOT_API_KEY',"u34284738-fjwieuydyr4d");
define('SSL_CHECKER',false);
define('SSL_CHECKER_DATA',"https://api.xxx.xx/xxxx/data.json");


$times = '';
for ($i = 1;$i <= 44;$i++) $times .= $i . '-';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.uptimerobot.com/v2/getMonitors",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "api_key=" . urlencode(UPTIMEROBOT_API_KEY) . "&format=json&custom_uptime_ranges=" . strtotime('today') . '_' . time() . "&all_time_uptime_ratio=1&custom_uptime_ratios=" . urlencode(substr($times,0,-1)),
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
    ),
));
$response = json_decode(curl_exec($curl),true);
if (curl_error($curl) || !is_array($response) || $response['stat'] != 'ok')
{
    header("HTTP/1.1 500 Internal Server Error");
    die;
}
curl_close($curl);

$list = [];
foreach ($response['monitors'] as $value)
{
    $tmp = explode('/',$value['friendly_name']);
    if (count($tmp) != 3) continue;
    $ratios = explode('-',$value['custom_uptime_ratio']);
    $ratios[] = $value['custom_uptime_ranges'];
    if (isset($list[$tmp[2]])) $list[$tmp[2]]['items'][] = [
        'name' => $tmp[0],
        'status' => ($value['status'] == 2) ? "Up" : (($value['status'] == 0) ? "Pause" : (($value['status'] == 1) ? "Not checked yet" : "Down")),
        'ratios' => $ratios,
        'all_ratio' => $value['all_time_uptime_ratio'],
        'type' => 'uptime',
    ];

    if (!isset($list[$tmp[2]])) $list[$tmp[2]] = [
        'name' => $tmp[1],
        'items' => [
            [
                'name' => $tmp[0],
                'status' => ($value['status'] == 2) ? "Up" : (($value['status'] == 0) ? "Pause" : (($value['status'] == 1) ? "Not checked yet" : "Down")),
                'ratios' => $ratios,
                'all_ratio' => $value['all_time_uptime_ratio'],
                'type' => 'uptime',
            ],
        ],
    ];
}

if (SSL_CHECKER)
{
    $ssl = json_decode(file_get_contents(SSL_CHECKER_DATA),true);

    $sslStatus = &$list[];
    $sslStatus['name'] = "SSL Certificates";

    foreach ($ssl as $item)
    {
        $sslStatus['items'][] = [
            'name' => strtoupper($item['domain']),
            'status' => ($item['isValid']) ? "Up" : "Down",
            'Is valid' => ($item['isValid']) ? "Yes" : 'False',
            'issuer' => $item['issuer'],
            'Signature algorithm' => $item['signatureAlgorithm'],
            'Additional domains' => $item['additionalDomains'],
            'Expiration date' => date('Y-m-d',$item['expirationDate']),
            'Fingerprint' => $item['fingerprint'],
            'type' => 'ssl'
        ];
    }
}

file_put_contents(__DIR__ . '/data.json',json_encode($list));
unset($tmp);