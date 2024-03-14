<?php
	
	global $mollaUtility,$mo_lla_dirName;

	if(current_user_can( 'manage_options' )  && isset($_POST['option']) )
	{
		switch(sanitize_text_field($_POST['option']))
		{
			case "mo_lla_block_ip_range":
				lla_handle_range_blocking($_POST);												break;
			case "mo_lla_browser_blocking":
				lla_handle_browser_blocking($_POST);											break;
			case "mo_lla_enable_user_agent_blocking":
				lla_handle_user_agent_blocking($_POST);										    break;
			case "mo_lla_block_countries":
				lla_handle_country_block($_POST);											    break;
			case "mo_lla_block_referrer":
				lla_handle_block_referrer($_POST);												break;
			
		}
	}

	$range_count 	= is_numeric(get_option('mo_lla_iprange_count'))
					&& intval(get_option('mo_lla_iprange_count')) !=0   ? intval(get_option('mo_lla_iprange_count')) : 1;
	$user_agent 	= get_option('mo_lla_enable_user_agent_blocking') 	? "checked" : "";
	$block_chrome 	= get_option('mo_lla_block_chrome') 			   	? "checked" : "";
	$block_ie 		= get_option('mo_lla_block_ie')			   	   	    ? "checked" : "";
	$block_firefox 	= get_option('mo_lla_block_firefox') 			   	? "checked" : "";
	$block_safari	= get_option('mo_lla_block_safari') 			   	? "checked" : "";
	$block_opera	= get_option('mo_lla_block_opera') 			     	? "checked" : "";
	$block_edge		= get_option('mo_lla_block_edge') 			   	   	? "checked" : "";
	$country		= Mo_lla_MoWpnsConstants::$country;
	$codes			= get_option( "mo_lla_countrycodes");
	$referrers 		= get_option( 'mo_lla_referrers');
	$referrers 		= explode(";",$referrers);
	$current_browser= $mollaUtility->getCurrentBrowser();

	switch($current_browser)
	{
		case "chrome":
			$block_chrome = 'disabled';		
		break;
		case "ie":
			$block_ie 	  = 'disabled';		
		break;
		case "firefox":
			$block_firefox= 'disabled';		
		break;
		case "safari":
			$block_safari = 'disabled';		
		break;
		case "edge":
			$block_edge	  = 'disabled';	
		break;
		case "opera":
			$block_opera  = 'disabled';		
		break;	
	}

	include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'advanced-blocking.php';


	/* ADVANCD BLOCKING FUNCTIONS */

	//Function to save range of ips
	function lla_handle_range_blocking($postedValue)
	{
        
		$flag=0;
		$max_allowed_ranges = 100;
		$added_mappings_ranges  = 0 ;
		for($i=1;$i<=$max_allowed_ranges;$i++)
		{
			if(isset($postedValue['range_'.$i]) && !empty($postedValue['range_'.$i]))
			{
				$range_array = explode("-",sanitize_text_field($postedValue['range_'.$i]));
				if(sizeof($range_array) == 2){
					$lowerIP = trim($range_array[0]);
					$higherIP = trim($range_array[1]);
					if(filter_var($lowerIP, FILTER_VALIDATE_IP) && filter_var($higherIP, FILTER_VALIDATE_IP)){
						$added_mappings_ranges++;
						update_option( 'mo_lla_iprange_range_'.$added_mappings_ranges, $postedValue['range_'.$i]);
					}else{
						//error message of invalid IP
						$flag=1;
						do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('INVALID_IP'),'ERROR');
						break;
					}
				}else{
				//error message of invalid format
					$flag=1;
					do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('INVALID_IP_FORMAT'),'ERROR');
					break;
				}
			}
		}
		if($added_mappings_ranges==0)
			update_option( 'mo_lla_iprange_range_1','');
		update_option( 'mo_lla_iprange_count', $added_mappings_ranges);
		if($flag == 0){
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('IP_PERMANENTLY_BLOCKED'),'SUCCESS');
		}		
	}

	//Function to handle browser blocking
	function lla_handle_browser_blocking($postedValue)
	{
		isset($postedValue['mo_lla_block_chrome'])		? update_option( 'mo_lla_block_chrome' 	,  sanitize_text_field($postedValue['mo_lla_block_chrome'] ))	: update_option( 'mo_lla_block_chrome'  ,  false );
		isset($postedValue['mo_lla_block_firefox'])	? update_option( 'mo_lla_block_firefox' 	,  sanitize_text_field($postedValue['mo_lla_block_firefox'] ))	: update_option( 'mo_lla_block_firefox' ,  false );
		isset($postedValue['mo_lla_block_ie'])			? update_option( 'mo_lla_block_ie' 		,  sanitize_text_field($postedValue['mo_lla_block_ie'] ))		: update_option( 'mo_lla_block_ie' 	 ,  false );
		isset($postedValue['mo_lla_block_safari'])		? update_option( 'mo_lla_block_safari' 	,  sanitize_text_field($postedValue['mo_lla_block_safari'] ))	: update_option( 'mo_lla_block_safari'  ,  false );
		isset($postedValue['mo_lla_block_opera'])		? update_option( 'mo_lla_block_opera' 		,  sanitize_text_field($postedValue['mo_lla_block_opera'] ))	: update_option( 'mo_lla_block_opera' 	 ,  false );
		isset($postedValue['mo_lla_block_edge'])		? update_option( 'mo_lla_block_edge' 		,  sanitize_text_field($postedValue['mo_lla_block_edge'] )	)	: update_option( 'mo_lla_block_edge' 	 ,  false );
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONFIG_SAVED'),'SUCCESS');
	}

	//Function to handle user agent blocking
	function lla_handle_user_agent_blocking($postvalue)
	{
		$user_agent = isset($postvalue['mo_lla_enable_user_agent_blocking']) ? true : false;
		update_option( 'mo_lla_enable_user_agent_blocking',  $user_agent);
		if($user_agent)
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('USER_AGENT_BLOCK_ENABLED'),'SUCCESS');
		else
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('USER_AGENT_BLOCK_DISABLED'),'ERROR');
	}


	//Function to handle country block
	function lla_handle_country_block($post)
	{

		$countrycodes = "";
		foreach($post as $countrycode=>$value){
			if($countrycode!="option")
				$countrycodes .= sanitize_text_field($countrycode).";";
		}
		update_option( 'mo_lla_countrycodes', $countrycodes);
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONFIG_SAVED'),'SUCCESS');
	}


	//Function to handle block referrer
	function lla_handle_block_referrer($post)
	{
		$referrers = "";
		foreach($post as $key => $value)
		{
			if(strpos($key, 'referrer_') !== false)
				if(!empty($value))
					$referrers .= sanitize_url($value).";";
		}
		update_option( 'mo_lla_referrers', $referrers);
	}
