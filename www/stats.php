<?php
define(LANGUAGE, "english");

//Get Temperature
$temp = shell_exec('cat /sys/class/thermal/thermal_zone*/temp');
$temp = round($temp / 1000, 1);

//Get CPU frequency
$clock = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq');
$clock = round($clock / 1000);

//Get Voltage
$voltage = shell_exec('sudo /opt/vc/bin/vcgencmd measure_volts');
$voltage = explode("=", $voltage);
$voltage = $voltage[1];
$voltage = substr($voltage, 0, -2);

//Get CPU Usage
$cpuusage = 100 - shell_exec("vmstat | tail -1 | awk '{print $15}'");

//Get Uptime Data
$uptimedata = shell_exec('uptime');
$uptime = explode(' up ', $uptimedata);
$uptime = explode(',', $uptime[1]);
$uptime = $uptime[0] . ', ' . $uptime[1];



//Get the memory info
$meminfo = file("/proc/meminfo");
for ($i = 0; $i < count($meminfo); $i++) {
    list($item, $data) = split(":", $meminfo[$i], 2);
    $item = chop($item);
    $data = chop($data);
    if ($item == "MemTotal") {
        $total_mem = $data;
    }
    if ($item == "MemFree") {
        $free_mem = $data;
    }
    if ($item == "SwapTotal") {
        $total_swap = $data;
    }
    if ($item == "SwapFree") {
        $free_swap = $data;
    }
    if ($item == "Buffers") {
        $buffer_mem = $data;
    }
    if ($item == "Cached") {
        $cache_mem = $data;
    }
    if ($item == "MemShared") {
        $shared_mem = $data;
    }
}
$used_mem = ( $total_mem - $free_mem . ' kB');
$used_swap = ( $total_swap - $free_swap . ' kB' );
$percent_free = round($free_mem / $total_mem * 100);
$percent_used = round($used_mem / $total_mem * 100);
$percent_swap = round(( $total_swap - $free_swap ) / $total_swap * 100);
$percent_swap_free = round($free_swap / $total_swap * 100);
$percent_buff = round($buffer_mem / $total_mem * 100);
$percent_cach = round($cache_mem / $total_mem * 100);
$percent_shar = round($shared_mem / $total_mem * 100);

include 'localization/' . LANGUAGE . '.lang.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/favicon.ico">
        <title>light pis</title>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="assets/css/theme.css" rel="stylesheet">
        
        <link rel="stylesheet" href="stylesheets/main.css">
        <link rel="stylesheet" href="stylesheets/button.css">
        <script src="javascript/raphael.2.1.0.min.js"></script>
        <script src="javascript/justgage.1.0.1.min.js"></script>

        <script>
            function checkAction(action) {
                if (confirm('<?php echo TXT_CONFIRM; ?> ' + action + '?'))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }

            window.onload = doLoad;

            function doLoad()
            {
                setTimeout("refresh()", 30 * 1000);
            }

            function refresh()
            {
                window.location.reload(false);
            }
        </script>
        
    </head>
    <body role="document">
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">lights</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="/">home</a></li>
                        <li class="active"><a href="/stats.php">stats</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container theme-showcase text-center" role="main">
            <div id="container">
            <img id="logo" src="images/raspberry.png">
            <div id="title">Raspberry Pi Control Panel</div>
            <div id="uptime"><b><?php echo TXT_RUNTIME; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $uptime; ?></div>
            <div id="tempgauge"></div>
            <div id="voltgauge"></div>
            <div id="clockgauge"></div>
            <div id="cpugauge"></div>
            <div id="FREEMemory"></div>
            <div id="USEDMemory"></div>
            <div id="controls">
                <a class="button_orange" href="modules/shutdown.php?action=0" onclick="return checkAction('<?php echo TXT_RESTART_1; ?>');"><?php echo TXT_RESTART_2; ?></a><br/>
                <a class="button_red" href="modules/shutdown.php?action=1" onclick="return checkAction('<?php echo TXT_SHUTDOWN_1; ?>');"><?php echo TXT_SHUTDOWN_2; ?></a>
            </div>
        </div>
        </div> 
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/lights.js"></script>
        <script>
            var t = new JustGage({
                id: "tempgauge",
                value: <?php echo $temp; ?>,
                min: 0,
                max: 100,
                title: "<?php echo TXT_TEMPERATURE; ?>",
                label: "°C"
            });
        </script>

        <script>
            var g = new JustGage({
                id: "voltgauge",
                value: <?php echo $voltage; ?>,
                min: 0.8,
                max: 1.4,
                title: "<?php echo TXT_VOLTAGE; ?>",
                label: "V"
            });
        </script>


        <script>
            var c = new JustGage({
                id: "clockgauge",
                value: <?php echo $clock; ?>,
                min: 0,
                max: 1000,
                title: "<?php echo TXT_CLOCK; ?>",
                label: "MHz"
            });
        </script>

        <script>
            var c = new JustGage({
                id: "cpugauge",
                value: <?php echo $cpuusage; ?>,
                min: 0,
                max: 100,
                title: "<?php echo TXT_USAGE; ?>",
                label: "%"
            });
        </script>

        <script>
            var a = new JustGage({
                id: "FREEMemory",
                value: <?php echo $percent_free ?>,
                min: 0,
                max: 100,
                title: "Free Memory",
                label: "%"
            });
        </script>

        <script>
            var a = new JustGage({
                id: "USEDMemory",
                value: <?php echo $percent_used ?>,
                min: 0,
                max: 100,
                title: "Used Memory",
                label: "%"
            });
        </script>
    </body>
</html>
