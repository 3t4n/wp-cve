<?php
namespace PHPF\WP\Stats;

/**
 * Google charts
 *
 * @author  Petr Stastny <petr@stastny.eu>
 * @license GPLv3
 */
class GoogleCharts
{
    /**
     * Result JS code storage
     * @var string
     */
    private static $googleJs = '';

    /**
     * Chart ID counter
     * @var int
     */
    private static $chartId = 0;


    /**
     * Create new chart
     *
     * @param string $type chart type (ColumnChart, PieChart, LineChart)
     * @param array $data chart data
     * @param array $options char options
     * @param string $div element ID where to render the chart
     * @return void
     */
    public static function addChart($type, array $data, array $options, $div)
    {
        self::$chartId++;

        self::$googleJs .= "  var chartData".self::$chartId." = google.visualization.arrayToDataTable(".json_encode($data).");\n";
        self::$googleJs .= "  var chartOptions".self::$chartId." = ".json_encode($options).";\n";
        self::$googleJs .= "  var chart".self::$chartId." = new google.visualization.".$type."(document.getElementById('".$div."'));\n";
        self::$googleJs .= "  chart".self::$chartId.".draw(chartData".self::$chartId.", chartOptions".self::$chartId.");\n";
    }


    /**
     * Init Google Charts
     *
     * @return void
     */
    public static function init()
    {
        wp_enqueue_script('googlecharts', 'https://www.gstatic.com/charts/loader.js');
    }


    public static function drawCharts()
    {
        if (!self::$googleJs) {
            // no charts defined
            return;
        }

        echo "<script>\n";
        echo "google.charts.load('current', {'packages':['corechart','bar']});\n";
        echo "google.charts.setOnLoadCallback(drawGoogleCharts);\n";

        echo "function drawGoogleCharts() {\n";
        echo self::$googleJs."\n";
        echo "}\n";
        echo "</script>\n";
    }
}
