<?php

global $wpdb;
global $joanTable;

// Get the desired action from the POST parameter
$action =  $_POST['crud-action'];

if ($action == 'delete' && current_user_can( 'activate_plugins' )){

	//Check to ensure that this request is legit
	if (wp_verify_nonce( $_POST['delete_entries_nonce_field'],'delete_joan_entry')){
		$id = intval( $_POST['id'] );
        if ( ! $id ) {
          _e('Id not valid','joan');
            return;
        }
		$wpdb->query( $wpdb->prepare("DELETE FROM {$joanTable} WHERE id = %d", $id	));
		
		if (function_exists('wp_cache_clear_cache')) {  
			wp_cache_clear_cache(); 
		}

		_e('good delete');

	} 

} else if ($action == 'create' && current_user_can( 'activate_plugins' )) {
	 
	//Check to ensure that this request is legit
	if( wp_verify_nonce($_POST['joan_nonce_field'],'add_joan_entry') ){
        
		//Set the timezone appropriately
		//$tz = get_option('timezone_string');
		//date_default_timezone_set($tz);

		//extract ($_POST);
			
		$en_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

		$startDay 	= $_POST['sday'];
		$endDay 	= $_POST['eday'];
		$startTime 	= $_POST['startTime'];
		$endTime	= $_POST['endTime'];
		$imageUrl 	= esc_url($_POST['imageUrl']);
		$linkUrl 	= esc_url($_POST['linkUrl']);
		$showname   = htmlentities(stripslashes(($_POST['showname'])));
			
		$st_key = array_search($startDay, $en_days);
		$ed_key = array_search($endDay, $en_days);
		$dif_day = ($ed_key - $st_key);

		//Check the $linkURL to make sure it's valid
		if ($linkUrl && filter_var($linkUrl, FILTER_VALIDATE_URL) == false) {
		    _e('bad linkURL','joan');
		    return;
		}

		//Check the show name to make sure it's there
		if ($showname == ''){
			_e('bad name','joan');
			return;
		}

		//Format the start and end times, then convert them to a UNIX timestamp set in the early 80s
		//This allows us to recycle the schedule every week

		//$showstart 		 = strtotime($startDay.", ".$startTime." August 1, 1982");
		//$showstart 		 = $showstart + (1);
		//$showend 		 = strtotime($endDay.", ".$endTime." August 1, 1982");
			
		$showstart = strtotime( $startDay . ", " . $startTime . " August 1, 1982");
		$showstart = $showstart + 59;
		$showend = strtotime( $endDay . ", " . $endTime . " August 1, 1982");

		//Check to make sure the start time is before the end time
		if ($showstart >= $showend){
			_e('too soon','joan');
			return;
		}

		//Create the start and end clock times
		$showstart = day_to_string($startDay, $startTime) + 59;
		$startClock = date('g:i a', $showstart);
		$endClock = date('g:i a', $showend);
		$showend = day_to_string($endDay, $endTime);

		//Check to see if that slot is already taken by an existing show
		if ( ! $wpdb->query('SELECT id, startTime, endTime, showName FROM ' . $joanTable . ' WHERE startTime <= ' . $showstart . ' AND endTime >= ' . $showend . ' ORDER BY startTime') ) {
			$wpdb->query(
				$wpdb->prepare("INSERT INTO {$joanTable}
					(
						dayOfTheWeek,
						startTime,
						endTime,
						startClock,
						endClock,
						showName,
						imageURL,
						linkURL
					)
					VALUES
					(
						%s,
						%d,
						%d,
						%s,
						%s,
						%s,
						%s,
						%s
					)
				",
					$startDay,
					$showstart,
					$showend,
					$startClock,
					$endClock,
					$showname,
					$imageUrl,
					$linkUrl
				)
			);

			//Send the object back
			$newShow = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$joanTable} WHERE id = %d", $wpdb->insert_id));
			echo json_encode($newShow);
			if (function_exists('wp_cache_clear_cache')) {
				wp_cache_clear_cache();
			}
		}
		else {
			_e('scheduling conflict','joan');
		}
	}

} else if ($action == 'update' && current_user_can( 'activate_plugins' )) {

	//Check to ensure this request is legit
	if(wp_verify_nonce( $_POST['joan_entries_nonce_field'],'save_joan_entries')){

		//Go through each parameter and update it if necessary
		
		$showname 	= $_POST['showName'];
		$linkUrl 	= $_POST['linkURL'];
		$imageUrl 	= $_POST['imageURL'];

		if(isset($_POST['showName'])){
			foreach ($showname as $key => $value) {
				$wpdb->query( $wpdb->prepare("UPDATE {$joanTable} SET showname = %s WHERE id= %d ", htmlentities(stripslashes($value)), $key ));
			}
		}
		
		if(isset($_POST['linkURL'])){
			foreach ($linkUrl as $key => $value) {
				$link = esc_url($value);
		 		$wpdb->query( $wpdb->prepare("UPDATE {$joanTable} SET linkURL = %s WHERE id= %d ", $link, $key ));
			}
		}

		if(isset($_POST['imageURL'])){

			foreach ($imageUrl as $key => $value) {
		 		$link = esc_url($value);
				$wpdb->query( $wpdb->prepare("UPDATE $joanTable SET imageURL = %s WHERE id= %d ", $link, $key ));
			}
		}

		if (function_exists('wp_cache_clear_cache')) {
			wp_cache_clear_cache(); 
		}

		_e('good updates','joan');
	}

} else if ($action == 'read'){

	if ($_POST['read-type'] == "current"){
		$today = get_joan_day_name( date_i18n('w') );
		//$currentTimestamp = strtotime( date('l').", ".date('g:i:s a')." August 1, 1982");
		$currentTimestamp = day_to_string( get_joan_day_name( date_i18n('w') ), date_i18n('g:i:s a') );

		$results = array();
		$currentShowEndTime = $currentTimestamp;

		//Get the currently playing show
        $shutItDown = get_option('joan_shutitdown');
        //var_dump($shutItDown);
		$currentShow = $wpdb->get_row($wpdb->prepare ("SELECT * FROM {$joanTable} WHERE startTime < %d AND endTime > %d", $currentTimestamp, $currentTimestamp));
		if ($currentShow && $shutItDown !== 'yes'){
				//Check to see if images are being displayed
				if (get_option('joan_use_images') == 'no'){
					$currentShow->imageURL = false;
				}

			$results['current-show'] = $currentShow;
			$currentShowEndTime = $currentShow->endTime;
		}
		else {
			$results['current-show'] = get_option('off_air_message');
		}

		if (get_option('joan_upcoming') == 'yes'){
			//Get the next scheduled show as well
			$nextShow = $wpdb->get_row($wpdb->prepare ("SELECT * FROM {$joanTable} WHERE startTime >= %d ORDER BY startTime LIMIT 1", $currentShowEndTime));

			//If there are no more shows, then the next show will be the very first one on Sundays
			if (! $nextShow) {
				$nextShow = $wpdb->get_row($wpdb->prepare ("SELECT * FROM {$joanTable} ORDER BY startTime LIMIT %d", 1));
			}

			$results['upcoming-show'] = $nextShow;
		}

		wp_send_json($results);

	}
	else {

		$daysOfTheWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

		$output = array();

		foreach ($daysOfTheWeek as $day) {
			//Add this day's shows to the $output array
			$output[$day] = $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM {$joanTable} WHERE dayOfTheWeek = '%s' ORDER BY startTime", $day));
			
		}

		wp_send_json($output);
	}
}