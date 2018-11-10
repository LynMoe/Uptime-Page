<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: XiaoLin
 * Date: 2018-11-10
 * Time: 12:43 PM
 */

if (file_exists(__DIR__ . '/config.php'))
    require_once __DIR__ . '/config.php';
else
{
    header("HTTP/1.1 500 Internal Server Error");
    die;
}
=======
 * User: xiaolin
 * Date: 2018/11/2
 * Time: 下午4:03
 */

define('UPTIMEROBOT_API_KEY',"u34284738-fjwieuydyr4d");
define('SSL_CHECKER',false);
define('SSL_CHECKER_DATA',"https://api.xxx.xx/xxxx/data.json");

>>>>>>> 8bb7e3b47167a6f2aeaa2634258949ebb3958cbc

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
<<<<<<< HEAD

$response = json_decode(curl_exec($curl),true);
$count = 0;

while((curl_error($curl) != "" || !is_array($response) || $response['stat'] != 'ok') && ($count < 3))
{
    error_log(curl_error($curl));
    sleep(3);
    $response = json_decode(curl_exec($curl),true);
    $count++;
}
unset($count);

if (!is_array($response) || $response['stat'] != 'ok')
=======
$response = json_decode(curl_exec($curl),true);
if (curl_error($curl) || !is_array($response) || $response['stat'] != 'ok')
>>>>>>> 8bb7e3b47167a6f2aeaa2634258949ebb3958cbc
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

<<<<<<< HEAD
unset($tmp,$data,$item,$i,$sslStatus,$ssl,$temp,$count,$key);

date_default_timezone_set('Asia/Shanghai');

ob_start();
?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo SITE_NAME; ?></title>
        <link rel="icon" href="data:image/x-icon;base64,AAABAAEAEBAAAAAAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAQAQAAAAAAAAAAAAAAAAAAAAAAAD///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wEtLS2XKysr7SgoKO0nJyftJSUl7SMjI+kiIiKTIiIikSIiIukiIiLtIiIi7SIiIu0iIiLtIiIin////wEyMjI1MDAw/y0tLf8rKyv/KSkp/ycnJ/8mJiaP////Af///wEiIiKFIiIi/yIiIv8iIiL/IiIi/yIiIv8iIiI9NTU1kTMzM/8wMDD/Li4u/ywsLP8qKir/KCgoYf///wH///8BIyMjWSIiIv8iIiL/IiIi/yIiIv8iIiL/IiIimzg4OM01NTX9MzMz5TExMf8uLi7/LCws/yoqKtcpKSk7JycnCyUlJdMjIyP/IiIi/yIiIv8iIiLnIiIi/SIiItM7OzvzOTk5hzY2NgU0NDS5MjIy/y8vL/8tLS3/Kysr+SkpKScnJyfNJSUl/yQkJP8iIiLBIiIiByIiIn0iIiL1Pj4+7Tw8PKU6Ojo1Nzc3zTU1Nf8yMjL/MDAw/y4uLv8rKytZKSkpfSgoKP8mJib/JCQk1SMjIzUiIiKdIiIi70FBQcU/Pz//PDw8/zo6Ov84ODj/NTU1/zMzM/8wMDD/Li4unywsLDsqKir/KCgo/yYmJv8lJSX/IyMj/yIiIs1DQ0OBQUFB/z8/P/89PT3vOjo66zg4OP82Njb/NDQ0/zExMd8vLy8fLS0t7SsrK/EpKSnvJycn/yUlJf8jIyOLRUVFIURERPVCQkL9QEBAKz09PRs7OzvxOTk5/zc3N/80NDT/MjIywzAwMO8tLS0nKysrIykpKfknJyf7JiYmKf///wFGRkZ1RERE/0NDQ19AQEBPPj4+9zw8PP85OTmxNzc3rTU1Nf8zMzP7MTExVy4uLlUsLCz/Kioqf////wH///8B////AUZGRpVFRUX/Q0ND/0FBQf8/Pz/3PT09Cf///wE4ODjxNjY2/zMzM/8xMTH/Li4unf///wH///8B////Af///wH///8BR0dHb0VFRelERET/QUFB/0BAQLE+Pj6tOzs7/zk5Of82NjbtNDQ0d////wH///8B////Af///wH///8B////Af///wFGRkYZRkZGcURERK9CQkLjQEBA5T4+PrE7OztzODg4Hf///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8BAAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//w==">
        <link rel="shortcut icon" href="data:image/x-icon;base64,AAABAAEAEBAAAAAAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAQAQAAAAAAAAAAAAAAAAAAAAAAAD///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wEtLS2XKysr7SgoKO0nJyftJSUl7SMjI+kiIiKTIiIikSIiIukiIiLtIiIi7SIiIu0iIiLtIiIin////wEyMjI1MDAw/y0tLf8rKyv/KSkp/ycnJ/8mJiaP////Af///wEiIiKFIiIi/yIiIv8iIiL/IiIi/yIiIv8iIiI9NTU1kTMzM/8wMDD/Li4u/ywsLP8qKir/KCgoYf///wH///8BIyMjWSIiIv8iIiL/IiIi/yIiIv8iIiL/IiIimzg4OM01NTX9MzMz5TExMf8uLi7/LCws/yoqKtcpKSk7JycnCyUlJdMjIyP/IiIi/yIiIv8iIiLnIiIi/SIiItM7OzvzOTk5hzY2NgU0NDS5MjIy/y8vL/8tLS3/Kysr+SkpKScnJyfNJSUl/yQkJP8iIiLBIiIiByIiIn0iIiL1Pj4+7Tw8PKU6Ojo1Nzc3zTU1Nf8yMjL/MDAw/y4uLv8rKytZKSkpfSgoKP8mJib/JCQk1SMjIzUiIiKdIiIi70FBQcU/Pz//PDw8/zo6Ov84ODj/NTU1/zMzM/8wMDD/Li4unywsLDsqKir/KCgo/yYmJv8lJSX/IyMj/yIiIs1DQ0OBQUFB/z8/P/89PT3vOjo66zg4OP82Njb/NDQ0/zExMd8vLy8fLS0t7SsrK/EpKSnvJycn/yUlJf8jIyOLRUVFIURERPVCQkL9QEBAKz09PRs7OzvxOTk5/zc3N/80NDT/MjIywzAwMO8tLS0nKysrIykpKfknJyf7JiYmKf///wFGRkZ1RERE/0NDQ19AQEBPPj4+9zw8PP85OTmxNzc3rTU1Nf8zMzP7MTExVy4uLlUsLCz/Kioqf////wH///8B////AUZGRpVFRUX/Q0ND/0FBQf8/Pz/3PT09Cf///wE4ODjxNjY2/zMzM/8xMTH/Li4unf///wH///8B////Af///wH///8BR0dHb0VFRelERET/QUFB/0BAQLE+Pj6tOzs7/zk5Of82NjbtNDQ0d////wH///8B////Af///wH///8B////Af///wFGRkYZRkZGcURERK9CQkLjQEBA5T4+PrE7OztzODg4Hf///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8B////Af///wH///8BAAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//w==">
        <link rel="stylesheet" href="app.css">
        <style>
            p {
                margin-block-end: unset;
                margin-block-start: unset;
            }
        </style>
    </head>
    <body>
    <div id="app">
        <section class="header">
            <div class="container">
                <h1 class="header-title"><?php echo SITE_NAME; ?></h1>
                <div class="card">
                    <div class="summary">
                        <?php
                        $count = 0;
                        foreach ($list as $tmp1)
                        {
                            foreach ($tmp1['items'] as $tmp2)
                            {
                                if ($tmp2['status'] != "Up" && $tmp2['status'] != "Paused") $count++;
                            }
                        }
                        unset($tmp1,$tmp2);
                        echo '<div class="icon icon-status-sum ' . (($count == 0) ? "up" : "down") . '">';
                        echo '</div><div style="letter-spacing:-0.5px;"><div class="summary-detail">';

                        if ($count == 0) echo "All systems are operational.";
                        else if ($count == 1) echo "1 system is outage.";
                        else echo "{$count} systems are outage.";
                        ?>
                    </div>
                    <div class="summary-checktime">
                        <?php echo "Last check at " . date('F dS Y, H:i:s'); ?>
                    </div>
                </div>
            </div>
    </div>
    </div>
    </section><section class="content">

        <?php

        foreach ($list as $value)
        {
            $icon = 'up';
            foreach ($value['items'] as $item)
            {
                switch ($item['status'])
                {
                    case 'Up':
                        $icon = 'up';
                        break;
                    case 'Paused':
                    case 'Not checked yet':
                    case 'Down':
                        $icon = 'seem-down';
                        break;
                }
                if ($icon != 'up') break;
            }
            echo '<div class="container"><div class="card monitors has-children"><div class="monitors-header"><div class="monitors-header-title">' .
                $value['name'] .
                '</div><div class="icon icon-status ' . $icon . '"></div></div><div class="monitors-content-wrap"><div class="monitors-content">';

            foreach ($value['items'] as $item)
            {
                switch ($item['type'])
                {
                    case "uptime":
                        $icon = 'up';
                        switch ($item['status'])
                        {
                            case 'Up':
                                $icon = 'up';
                                break;
                            case 'Down':
                                $icon = 'down';
                                break;
                            case 'Paused':
                            case 'Not checked yet':
                                $icon = 'pause';
                                break;
                        }
                        echo '<div class="monitor"><div class="monitor-header"><div class="monitor-name">' .
                            $item['name'] .
                            '</div><div class="icon icon-status ' . $icon . '" title="' . $item['status'] . '"></div></div><div class="monitor-content"><div class="monitor-uptime-range"><strong>' .
                            (float)$item['all_ratio'] .
                            '%</strong><span> uptime for the last 45 days.</span></div><div class="monitor-uptimes">';

                        foreach (array_reverse($item['ratios']) as $days => $tmp)
                        {
                            $icon = ((float)$tmp > 99.500) ? "up" : (((float)$tmp > 90.000) ? "seem-down" : (((float)$tmp > 65.000) ? "down" : "down"));
                            $time = date('M jS, Y',time() - ((44 - $days) * 3600 * 24));
                            echo '<div class="icon-uptime ' . $icon . '" title="&lt;small&gt;' . $time . '&lt;br&gt;' . $tmp . '%&lt;/small&gt;"></div>';
                        }

                        echo '</div></div></div>';
                        break;

                    case "ssl":
                        $icon = 'up';
                        switch ($item['status'])
                        {
                            case 'Up':
                                $icon = 'up';
                                break;
                            case 'Down':
                                $icon = 'down';
                                break;
                        }
                        echo '<div class="monitor"><div class="monitor-header"><div class="monitor-name">' .
                            $item['name'] .
                            '</div><div class="icon icon-status ' . $icon . '" title="' . $item['status'] . '"></div></div><div class="monitor-content"><div class="monitor-uptime-range">';

                        unset($item['name'],$item['isValid'],$item['status'],$item['type']);
                        $num = 0;
                        foreach ($item as $key => $temp)
                        {
                            echo "<p>{$key}: <strong>{$temp}</strong></p>";
                            if ($num < (count($item) - 1)) echo "<br>";
                            $num++;
                        }


                        echo '</div></div></div>';
                        break;
                }
            }
            echo '</div></div></div></div>';
        }

        ?>

    </section>
    <section class="footer">
        <div class="container">
            <div class="footer-content">
                <nav class="links">
                    <?php foreach (FOOTER as $siteName => $url) echo "<a href=\"{$url}\">{$siteName}</a>"; ?>
                </nav>
                <div class="copyright">
                    &copy; <?php echo COPYRIGHT; ?>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/tippy.js@2.2.0/dist/tippy.all.min.js"></script>
    <script>!function(){for(var e=document.querySelectorAll(".monitors.has-children > .monitors-header"),t=0;t<e.length;++t)e[t].addEventListener("click",function(e){var t=this.parentNode,n=t.classList,o=t.querySelector(".monitors-content-wrap");n.contains("open")?(n.remove("open"),o.style.maxHeight=0):(n.add("open"),o.style.maxHeight=t.querySelector(".monitors-content").clientHeight+"px")})}();tippy("[title]",{arrow:!0,size:"small"});</script>
    </div>
    </body>
    </html>

<?php

$data = ob_get_clean();

file_put_contents(__DIR__ . '/index.html',$data);
=======
file_put_contents(__DIR__ . '/data.json',json_encode($list));
unset($tmp);
>>>>>>> 8bb7e3b47167a6f2aeaa2634258949ebb3958cbc
