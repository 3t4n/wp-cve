<?php

// get an array of all campaign monitor subscription lists
function pmc_get_lists() {
		
	global $pmc_options;
	
	if(strlen(trim($pmc_options['api_key'])) > 0 && strlen(trim($pmc_options['api_key'])) > 0 ) {
		
		$lists = array();
		
		if( ! class_exists('MCAPI' ) ) {
			require_once(PMC_PLUGIN_DIR . '/mailchimp/MCAPI.class.php');
		}		
		$api = new MCAPI($pmc_options['api_key']);
		$list_data = $api->lists();
		if($list_data) :
			foreach($list_data['data'] as $key => $list) :
				$lists[$list['id']] = $list['name'];
			endforeach;
		endif;
		return $lists;
			
	}
	return false;
}

// process the subscribe to list form
function pmc_check_for_email_signup() {
	// only proceed with this function if we are posting from our email subscribe form
	if(isset($_POST['action']) && $_POST['action'] == 'pmc_signup') {
		
		// setup the email and name varaibles
		$email = strip_tags($_POST['pmc_email']);
		$fname = isset($_POST['pmc_fname']) ? strip_tags($_POST['pmc_fname']) : '';
		$lname = isset($_POST['pmc_lname']) ? strip_tags($_POST['pmc_lname']) : '';
		
		// check for a valid email
		if(!is_email($email)) {
			wp_die(__('Your email address is invalid. Click back and enter a valid email address.', 'pmc'), __('Invalid Email', 'pmc'));
		}
		
		// check for a name
		if(isset($_POST['pmc_fname']) && strlen(trim($fname)) <= 0) {
			wp_die(__('Enter your name. Click back and enter your name.', 'pmc'), __('No Name', 'pmc'));
		}
		
		// check for a last name
		if(strlen(trim($lname)) <= 0) {
			$lname = '';
		}
		
		// send this email to campaign_monitor
		pmc_subscribe_email($email, $fname, $lname, $_POST['pmc_list_id']);
		
		// send user to the confirmation page
		wp_redirect(add_query_arg('submitted', '1', $_POST['redirect'])); exit;
	}
}
add_action('init', 'pmc_check_for_email_signup');

// adds an email to the campaign_monitor subscription list
function pmc_subscribe_email($email, $fname, $lname, $list_id) {
	global $pmc_options;
	
	if(strlen(trim($pmc_options['api_key'])) > 0 ) {
		
		if( ! class_exists('MCAPI' ) ) {
			require_once(PMC_PLUGIN_DIR . '/mailchimp/MCAPI.class.php');
		}
		$api = new MCAPI($pmc_options['api_key']);
		$opt_in = isset($pmc_options['double_opt_in']) ? true : false;
		if($api->listSubscribe($list_id, $email, array('FNAME' => $fname, 'LNAME' => $lname), 'html', $opt_in) === true) {
			return true;
		}
	
	}
	return false;
}


?>