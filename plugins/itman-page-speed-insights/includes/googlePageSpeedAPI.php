<?php
/*
 * Function to call Google PageSpeed API and store results inside DB.
 * Call by hourly CRON service.
*/

function itps_fetchPageSpeedData() {
	
	//If on localhost, do not call the Google API service
	if (itps_is_localhost()) return 0;

	//Google PageSpeed strategies 
	$strategy_arr = array(1 => 'desktop',2 => 'mobile');

	foreach($strategy_arr as $strategy_id => $strategy_text) {

		$site_url = get_home_url();

		$google_page_speed_call = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=" .  $site_url . "&strategy=" . $strategy_text;
		
		//Fetch data from Google PageSpeed API
		$response = wp_remote_get($google_page_speed_call, array('timeout' => 30));
		$google_ps = json_decode($response['body'], true);

		//Insert into plugin table
		global $wpdb;
		$table_name = $wpdb->prefix . "itman_page_speed_insights";

		$wpdb->query( $wpdb->prepare( 
			"
				INSERT INTO $table_name
				(strategy, measure_date, performance_score,first_contentful_paint,speed_index,interactive)
				VALUES ( %s, %s, %f, %f, %f, %f)
			", 
			$strategy_id, 
			current_time("Y-m-d H:i:s"), 
			($google_ps['lighthouseResult']['categories']['performance']['score']*100),
			$google_ps['lighthouseResult']['audits']['first-contentful-paint']['displayValue'],
			$google_ps['lighthouseResult']['audits']['speed-index']['displayValue'],
			$google_ps['lighthouseResult']['audits']['interactive']['displayValue']
		) );
	}

	update_option('itps_status', 2);
}