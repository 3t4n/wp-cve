<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       views/info.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

?>
<div class="mpp-body_wrapper">
    <div class="mpp-container">
        <h2 class="mpp-border_bottom"><?php _e('Poll Result', FelixTzWPModernPollsTextdomain); ?></h2>

        <ul class="mpp-nav mpp-nav_tabs">
            <li class="mpp-nav_item">
                <a class="mpp-nav_link mpp-active" data-href="bar_chart">Bar Chart</a>
            </li>
            <li class="mpp-nav_item">
                <a class="mpp-nav_link" data-href="pie_chart">Pie Chart</a>
            </li>
            <li class="mpp-nav_item">
                <a class="mpp-nav_link" data-href="text">Text</a>
            </li>
        </ul>
        <div class="mpp-tab_content">
            <div class="mpp-tab_pane mpp-tab_pane_fade mpp-tab_pane_show mpp-active" id="mpp-bar_chart">
                <div id="barchart"></div>
            </div>
            <div class="mpp-tab_pane mpp-tab_pane_fade" id="mpp-pie_chart">
                <div id="piechart"></div>
            </div>
            <div class="mpp-tab_pane mpp-tab_pane_fade" id="mpp-text">...</div>
        </div>

    </div>
</div>

<script>
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawMultSeries);

    function drawMultSeries() {
        var data = google.visualization.arrayToDataTable([
            ['answers', 'votes'],

            <?php
            foreach ($pollAnswers as $answer) {
                echo "['" . $answer->answer . "', " . $answer->votes . "],";
            }
            ?>

        ]);

        var options = {
            title: '<?php _e('Poll Result', FelixTzWPModernPollsTextdomain); ?>',
            chartArea: {width: '50%'},
            legend: {position: "none"},
            height: 400,
            hAxis: {
                title: '<?php _e('Total Votes', FelixTzWPModernPollsTextdomain); ?>',
                minValue: 0
            },
            vAxis: {
                title: '<?php _e('Answers', FelixTzWPModernPollsTextdomain); ?>'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('barchart'));
        chart.draw(data, options);
    }

    //google.charts.load('current', {'packages':['corechart']});


    jQuery(document).on('drawPieChart', function () {
        drawChart();
    });

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['answers', 'Votes'],
            <?php
            foreach ($pollAnswers as $answer) {
                echo "['" . $answer->answer . "', " . $answer->votes . "],";
            }
            ?>
        ]);

        var options = {
            title: '<?php _e('Poll Result', FelixTzWPModernPollsTextdomain); ?>',
            chartArea: {width: '50%', height: '500px'},
            height: 500
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>
