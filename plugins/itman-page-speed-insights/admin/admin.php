<?php
/*
* Everything related to Wordpress administration.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
* Load admin functionality only when in administration
*/
if(is_admin()) {
	add_action('init', 'itps_admin_init');
	add_action( 'admin_enqueue_scripts', 'itps_admin_enqueue_styles' ); //Register styles for Admin
	add_action( 'admin_enqueue_scripts', 'itps_admin_enqueue_scripts' ); //Register scripts for Admin
	add_action( 'admin_print_scripts-tools_page_itman-page-speed-insights', 'itps_load_dyn_script' ); //Register scripts for Tools page

	//Load widget functionality
	include (ITPS_PLUGIN_PATH . 'includes/dashboardWidget.php');
	
	//If this option exists, then DB table is already created and it is safe to call this function
	if (get_option('itps_status') != false) itps_load_widget_data();
}

function itps_admin_init() {

	add_action( 'admin_menu', 'itps_admin_menu' );
}

/*
* Register admin styles
*/
function itps_admin_enqueue_styles() {
	wp_enqueue_style('Page Speed Insights', plugin_dir_url( __FILE__ ) . '../css/itman-page-speed-admin.css', array(),'1.0.3', 'all' );	
}

/*
* Register admin scripts
*/
function itps_admin_enqueue_scripts() {
	wp_register_script('googlecharts', 'https://www.gstatic.com/charts/loader.js', array(), null );
	wp_enqueue_script('googlecharts');
    wp_enqueue_script('Page Speed Insights JS', plugin_dir_url( __FILE__ ) . '../js/itman-page-speed-admin.js', array(),'1.0.0', 'all' );
}

/*
* Register menu under Tools
*/
function itps_admin_menu() { 
	add_management_page(
		__( 'ITMan Page Speed Insighths', 'itman-page-speed-insights' ),
		__( 'Page Speed Insights', 'itman-page-speed-insights' ),
		'manage_options',
		'itman-page-speed-insights',
		'itps_settings_page'
	);
}


/*
* Load settings page
*/
function itps_settings_page() {
	include (ITPS_PLUGIN_PATH . "admin/settings-page.php");
}

/*
* Register dynamic script
*/
function itps_load_dyn_script() {
    global $wpdb;

    $table_name = $wpdb->prefix . "itman_page_speed_insights";

    $page_speed_history = $wpdb->get_results("
        SELECT measure_date as measure_date,
        SUM(ROUND(performance_score,0)*(1-abs(sign(strategy-1)))) as desktop,
        SUM(ROUND(performance_score,0)*(1-abs(sign(strategy-2)))) as mobile
        FROM (
            SELECT DATE(measure_date) AS measure_date, strategy, AVG(performance_score) AS performance_score 
            FROM " . $table_name . "
            WHERE measure_date >= CURDATE() - INTERVAL 30 DAY
            GROUP BY DATE(measure_date), strategy
        ) AS T
        GROUP BY measure_date
        LIMIT 30
    ");

    $chart_data = array();
    
    // DEBUG $$page_speed_history array
    // error_log('Page Speed History: ' . print_r($page_speed_history, true));

    foreach ($page_speed_history as $row) {
        $chart_data[] = [strtotime($row->measure_date) * 1000, intval($row->desktop), intval($row->mobile), 90, 50, 0];
    }
    
    // DEBUG Chart data
    //error_log('Chart Data: ' . print_r($chart_data, true));
    
    $inline_script = "
        var chart_data = " . json_encode($chart_data) . ";
        
        //Convert JSON string to date
        for (var i=0; i<chart_data.length; i++) {
            chart_data[i][0] = new Date(chart_data[i][0]);
        }

        google.charts.load('current', {'packages':['corechart']});

        google.charts.setOnLoadCallback(function() {
        
        // But is it?
        //console.log('Google Charts library loaded.');
        
            drawChart(chart_data);
        });
    ";

    wp_add_inline_script('Page Speed Insights JS', $inline_script);
}
