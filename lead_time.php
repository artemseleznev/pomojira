<html>
<head>
    <title>Жира-поможира: метрика времени выполнения</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
ini_set('xdebug.var_display_max_children', 1024);
require_once 'vendor/autoload.php';
\Pomojira\Helper::initDotenv();

$calculator = new \Pomojira\MetricsCalculator\MetricsCalculator();
$result = $calculator->calculate();
$dataForPlot = [];

foreach ($result as $key => $issueData) {
    $dataForPlot['in_progress_time'][] = [
        'year' => date('Y', strtotime($issueData['resolvedDateTime'])),
        'month' => date('n', strtotime($issueData['resolvedDateTime'])),
        'day' => date('j', strtotime($issueData['resolvedDateTime'])),
        'hour' => date('G', strtotime($issueData['resolvedDateTime'])),
        'value' => $issueData['inProgressTime']
    ];
    $dataForPlot['lead_time'][] = [
        'year' => date('Y', strtotime($issueData['resolvedDateTime'])),
        'month' => date('n', strtotime($issueData['resolvedDateTime'])),
        'day' => date('j', strtotime($issueData['resolvedDateTime'])),
        'hour' => date('G', strtotime($issueData['resolvedDateTime'])),
        'value' => $issueData['leadTime']
    ];
}

?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 700px; margin: 0 auto"></div>

<script type="text/javascript">
    Highcharts.chart('container', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'CF flow metrics'
        },
        subtitle: {
            text: 'Irregular time data in Highcharts JS'
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Date'
            },
        },
        yAxis: {
            title: {
                text: 'Time (days)'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
        },

        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            }
        },

        series: [{
            name: 'In Progress Time',
            data: [
                <?foreach ($dataForPlot['in_progress_time'] as $issueData):?>
                [Date.UTC(<?=$issueData['year']?>, <?=$issueData['month']?>, <?=$issueData['day']?>, <?=$issueData['hour']?>), <?=$issueData['value']?>],
                <?endforeach?>
            ]
        }, {
            name: 'Lead Time',
            data: [
                <?foreach ($dataForPlot['lead_time'] as $issueData):?>
                [Date.UTC(<?=$issueData['year']?>, <?=$issueData['month']?>, <?=$issueData['day']?>, <?=$issueData['hour']?>), <?=$issueData['value']?>],
                <?endforeach?>
            ]
        }]
    });
</script>
</body>
</html>