<?php 

defined( 'ABSPATH' ) || exit;


function tims_nso_throw_error($error){
	wp_redirect(wp_login_url().'?nc-sso-error='.$error);
  	exit;
}


function tims_nso_log($txt){
	if(get_option('tims_nso_debug_log')){
		$wp_upload_dir = wp_upload_dir();
	    file_put_contents($wp_upload_dir['basedir'].'/tims-nextcloud-sso-oauth2-log.txt', '['.date('Y-m-d H:i:s').'] '.$txt.PHP_EOL , FILE_APPEND | LOCK_EX);
	}
}


function tims_nso_authorize_url(){
 	// Generate a random state parameter for CSRF security
 	$state = bin2hex(random_bytes(10));

 	if(get_option('tims_persistent_data_type') == 'cookie'){

 		if(isset($_COOKIE['tims_nso_state'])){

 			$states = json_decode(base64_decode($_COOKIE['tims_nso_state']),true);

 			if(is_array($states)){
 				$states[] = $state;
 			}else{
 				$states = array($state);
 			}
	 	}else{
	 		$states = array($state);
	 	
	 	}
	 	setcookie('tims_nso_state', base64_encode(json_encode($states)), time()+HOUR_IN_SECONDS*2, '/');

	 	tims_nso_log('State Set as Cookie: '.$state);

 	}else{
 		if(session_status() === PHP_SESSION_NONE) {
	        session_start();
	    }
 		// session
 		if(isset($_SESSION['tims_nso_state']) && is_array($_SESSION['tims_nso_state'])){
	 		$_SESSION['tims_nso_state'][] = $state;
	 	}else{
	 		$_SESSION['tims_nso_state'] = array($state);
	 	}

	 	tims_nso_log('State Set as Session: '.$state);
 	}

 	
  	// Build the authorization URL by starting with the authorization endpoint
  	// and adding a few query string parameters identifying this application
  	$nextcloud_url = esc_url_raw(get_option('tims_nso_address'));
  	$authorize_url = $nextcloud_url.'apps/oauth2/authorize'.'?'.http_build_query([
    	'response_type' => 'code',
    	'client_id' => esc_attr(get_option('tims_nso_client_id')),
    	'redirect_uri' => get_site_url(),
    	'state' => $state,
    	'scope' => 'openid',
  	]);

  	$authorize_url = apply_filters('tims_nso_authorize_url', $authorize_url);

  	// save page to redirect if enabled

  
  	if(get_option('tims_nso_login_type') == 'redirect_back' && isset($_GET['redirect_to'])){

  	
		if(get_option('tims_persistent_data_type') == 'cookie'){
			setcookie('tims_nso_original_page', esc_url_raw(urldecode($_GET['redirect_to'])), time()+HOUR_IN_SECONDS*2, '/');
		}else{
			$_SESSION['tims_nso_original_page'] = esc_url_raw(urldecode($_GET['redirect_to']));
		}
	}

  	tims_nso_log('Authorize URL: '.$authorize_url); 
  	return $authorize_url;
}


add_action( 'wp','tims_nso_run');
function tims_nso_run(){
	if(isset($_GET['state']) && isset($_GET['code'])){

		$code = sanitize_text_field($_GET['code']);

		tims_nso_log('Received State Back: '.$_GET['state']);

		$nextcloud_url = esc_url_raw(get_option('tims_nso_address'));

		if(get_option('tims_persistent_data_type') == 'cookie'){
			if(!isset($_COOKIE['tims_nso_state']) || !is_array(json_decode(base64_decode($_COOKIE['tims_nso_state']),true)) || !in_array(sanitize_text_field($_GET['state']), json_decode(base64_decode($_COOKIE['tims_nso_state']), true))){
	 			tims_nso_throw_error('returned-invalid');
		 	}

		 	// remove cookie as no longer needed
		 	setcookie('tims_nso_state', '', time()-HOUR_IN_SECONDS, '/');
		}else{
			//session
			if(session_status() === PHP_SESSION_NONE) {
		        session_start();
		    }

		    if(!isset($_SESSION['tims_nso_state']) || !is_array($_SESSION['tims_nso_state']) || !in_array(sanitize_text_field($_GET['state']),$_SESSION['tims_nso_state'])){
	 			tims_nso_throw_error('returned-invalid');
		 	}
		 	// No need to keep old states so remove  
	 		unset($_SESSION['tims_nso_state']);
		}
		

  		if(isset($_GET['error'])) {
  			tims_nso_throw_error('returned');
  		}

  		if(defined('NEXTCLOUD_SECRET')){
  			$client_secret = NEXTCLOUD_SECRET;
  		}elseif(get_option('tims_nso_client_secret')){
  			$client_secret = get_option('tims_nso_client_secret');
  		}else{
  			tims_nso_throw_error('missing-secret');
  		}

  		$post = array(
	    	'grant_type' => 'authorization_code',
    		'code' => $code,
    		'redirect_uri' => get_site_url(),
    		'client_id' => get_option('tims_nso_client_id'),
    		'client_secret' => $client_secret,
	  	);

  		$response_post = wp_remote_post($nextcloud_url.'apps/oauth2/api/v1/token',array('body'=>$post));


  		if(is_wp_error($response_post)){
		    tims_nso_throw_error('token-get');
		}

		tims_nso_log('URL: '.$nextcloud_url.'apps/oauth2/api/v1/token, Response: '.$response_post['body']);

		$response = json_decode($response_post['body']);


  		if(!isset($response->access_token)) {
  			tims_nso_throw_error('missing-token');
  		}

  		//strip /index.php/ from URL if we have it, not needed for ocs
  		if(str_ends_with($nextcloud_url, '/index.php/')){
		    $nextcloud_url = str_replace("/index.php/","/",$nextcloud_url);
		}

  		$nextcloud_user_get = wp_remote_get($nextcloud_url.'ocs/v2.php/cloud/user?format=json', array('headers' => array('Authorization' => 'Bearer '.sanitize_text_field($response->access_token))));

  		if(is_wp_error($nextcloud_user_get)){
		    tims_nso_throw_error('user-get');
		}

		tims_nso_log('URL: '.$nextcloud_url.'ocs/v2.php/cloud/user?format=json, Response: '.$nextcloud_user_get['body']);

		$nextcloud_user = json_decode($nextcloud_user_get['body']);

  		if(isset($nextcloud_user->ocs->meta->status) && $nextcloud_user->ocs->meta->status == 'ok'){

  			// enabled is only available in newer Nextcloud installs, so if it is not set allow though but if it is make sure its true 
  			if(isset($nextcloud_user->ocs->data->enabled)){
  				if(!$nextcloud_user->ocs->data->enabled){
		    		tims_nso_throw_error('user-add-disabled');
  				}
  			}

			// Always require user to have email address 
			$email = $nextcloud_user->ocs->data->email;
		    if(!trim($email)){
		    	tims_nso_throw_error('missing-email');
		    }

		    // Always require user
			$username = $nextcloud_user->ocs->data->id;
			if(!trim($username)){
		    	tims_nso_throw_error('missing-user');
		    }

		    if(get_option('tims_nso_match') == 'email'){
		    	$user = get_user_by( 'email', $email);
		    }

		    if(get_option('tims_nso_match') == 'username'){
		    	$user = get_user_by( 'login', $username);
		    }

		    if($user){
		    	// we have a match
		    	
		    }elseif(get_option('tims_nso_create_account') == 'yes'){
		    	if(get_option('tims_nso_default_role') == 'custom'){
		    		$groups = $nextcloud_user->ocs->data->groups;
		    		$roles = get_option('tims_nso_group_link');
		    		foreach ($roles as $role_name => $matching_groups) {
		    			if(array_intersect($groups,explode(",",$matching_groups))){
		    				if(!isset($roles_to_add)){
		    					$roles_to_add = array();
		    				}
		    				$roles_to_add[$role_name] = $role_name;
		    			}
		    		}

		    		if(!isset($roles_to_add) && get_option('tims_nso_default_group_link_role')){
		    			// see if we have fallback to add
		    			$fallback_role = get_option('tims_nso_default_group_link_role');
		    			$roles_to_add = array();
		    			$roles_to_add[$fallback_role] = $fallback_role;
		    		}
				}elseif(get_option('tims_nso_default_role')){
					// map to single role
					$roles_to_add = array(get_option('tims_nso_default_role'));
				}else{
					tims_nso_throw_error('no-role');
				}
				if(isset($roles_to_add)){
			    	// create user
			    	$random_password = wp_generate_password( $length = 20);
			    	$user_id = wp_create_user($username, $random_password, $email);
			    
			    	if(!is_wp_error($user_id)){
			    		$user = get_user_by( 'id', $user_id);

		    			// Remove any roles added by WordPress 
						if(isset($user->roles)){
		    				foreach ($user->roles as $role){
		    					$user->remove_role($role);
		    				}
		    			}
		    			// Now add roles that where selected  
		    			foreach ($roles_to_add as $role) {
		    				$user->add_role($role);
		    			}
			    	}else{
						tims_nso_throw_error('user-create');
			    	}
			    }else{
			    	tims_nso_throw_error('no-role');
			    }
		    }else{
		    	tims_nso_throw_error('user-add-disabled');
		    }

		    // we shoudl be all good to go now
			wp_set_auth_cookie($user->ID);

			apply_filters('tims_nso_nextcloud_user_matched', $user, $nextcloud_user->ocs->data);

			// now redirect off 

			// redirect to home page
			$redirect_url = esc_url_raw(get_site_url());

			// If we have a URL set in settings lets use that
			if(get_option('tims_nso_redirect_url')){
				$redirect_url =  esc_url_raw(get_option('tims_nso_redirect_url'));
			}


			// if return to previous page is on lets to that
			if(get_option('tims_nso_login_type') == 'redirect_back'){
				if(get_option('tims_persistent_data_type') == 'cookie'){
					if(isset($_COOKIE['tims_nso_original_page']) && filter_var($_COOKIE['tims_nso_original_page'], FILTER_VALIDATE_URL)){
						$redirect_url = esc_url_raw($_COOKIE['tims_nso_original_page']);
						setcookie('tims_nso_original_page', '', time()-HOUR_IN_SECONDS, '/');
					}
				}else{
					// php session
					if(session_status() === PHP_SESSION_NONE) {
					    session_start();
					}
					if(isset($_SESSION['tims_nso_original_page']) && filter_var($_SESSION['tims_nso_original_page'], FILTER_VALIDATE_URL)){
						$redirect_url = esc_url_raw($_SESSION['tims_nso_original_page']);
						unset($_SESSION['tims_nso_original_page']);
					}
				}
			}

			// Allow filtering the redirect URL
			$redirect_url = apply_filters('tims_nso_successful_login_redirect', $redirect_url);

			tims_nso_log('User logged in, User ID: '.$user->ID);
			tims_nso_log('User ID '.$user->ID.' Redirected to: '.$redirect_url);

			wp_safe_redirect($redirect_url);
			exit;
		}else{
			tims_nso_log('Unrecognised response when requesting Nextcloud user data, response: '.$nextcloud_user_get['body']);
			tims_nso_throw_error('unexpected');
		}
	}
}


function tims_nso_redirect_wplogin(){
	global $GLOBALS;

	// if they are already logged in send back
	if(isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php' && isset($_GET['nc-sso']) && $_GET['nc-sso'] =='redirect'){
		if(is_user_logged_in()){
			// redirect to home page
			$redirect_url = esc_url_raw(get_site_url());

			// If we have a URL set in settings lets use that
			if(get_option('tims_nso_redirect_url')){
				$redirect_url =  esc_url_raw(get_option('tims_nso_redirect_url'));
			}

			// Allow filtering the redirect URL
			$redirect_url = apply_filters('tims_nso_successful_login_redirect', $redirect_url);

			wp_safe_redirect($redirect_url);
			exit;
		}else{
    		wp_redirect(tims_nso_authorize_url());
    		exit;
    	}
    }
}
add_action( 'wp_loaded', 'tims_nso_redirect_wplogin' );

function tims_nso_login_button(){
	if(get_option('tims_nso_login_button') == 'yes' && get_option('tims_nso_login_button_text')){
		echo do_shortcode('[nextcloud_login class="btn" style="text-decoration: none;display: block;line-height: 2.15384615;min-height: 30px;margin: 0;padding: 0 10px;cursor: pointer;border-width: 1px;border-style: solid;-webkit-appearance: none;border-radius: 3px;white-space: nowrap;box-sizing: border-box;color: #2271b1;border-color: #2271b1;background: #f6f7f7;margin-bottom:20px;"]'.esc_attr(get_option('tims_nso_login_button_text')).'[/nextcloud_login]');
	}
}
add_action( 'login_form', 'tims_nso_login_button' );



add_action("wp_ajax_tims_nso_test_connection", "tims_nso_test_connection");

function tims_nso_test_connection(){
	if(current_user_can('administrator')){

		$identifier = $_REQUEST['identifier'];
		$nextcloud_url = esc_url_raw($_REQUEST['url']);
		$state = bin2hex(random_bytes(10));


		
	  	$authorize_url = $nextcloud_url.'apps/oauth2/authorize'.'?'.http_build_query([
	    	'response_type' => 'code',
	    	'client_id' => esc_attr($identifier),
	    	'redirect_uri' => get_site_url(),
	    	'state' => $state,
	    	'scope' => 'openid',
	  	]);



		$response = wp_remote_head($authorize_url);
		if(!is_wp_error($response)){
        	$headers = wp_remote_retrieve_headers($response);
        	if(isset($headers['location'])){
        		$response = esc_attr($headers['location']);
        		$expected_response = esc_attr($nextcloud_url).'login/flow?clientIdentifier='.esc_attr($identifier);
        		if($response == $expected_response){
        			echo '<div class="status-success"><p>Looks Good!</p></div>';
        			die();
        		}
        	}
        }
	}
	$text = "<p><Strong>This doesn't look like a Nextcloud URL:</strong></p>
	         <p>If you have Nextcloud installed in a subfolder please include that in the URL.</p>
			 <p>If you haven't enabled Pretty Links in Nextcloud, make sure to add /index.php/ to the end of your URL.</p>
			 <p>Please make sure your ULR has a trailing slash at the end.</p>";
    echo '<div class="status-error">'.$text.'</div>';
   	die();
	
}









function tims_nso_errors() {
    global $error;
    $errors = array();
	$errors['returned-invalid']  = __('Nextcloud server returned but with an invalid state for this session', 'tims-nextcloud-sso-oauth2');
	$errors['returned']          = __('Nextcloud server returned with an error', 'tims-nextcloud-sso-oauth2');
	$errors['missing-email']     = __('No email address set for user in Nextcloud, please add an email address to your Nextcloud profile', 'tims-nextcloud-sso-oauth2');
	$errors['missing-user']      = __("Didn't receive username from Nextcloud", 'tims-nextcloud-sso-oauth2');
	$errors['missing-secret']    = __('Missing secret key, please add your secret key in the settings', 'tims-nextcloud-sso-oauth2');
	$errors['missing-token']     = __("Nextcloud didn't return a token to use", 'tims-nextcloud-sso-oauth2');
	$errors['user-disabled']     = __('User has been disabled in Nextcloud', 'tims-nextcloud-sso-oauth2');
	$errors['user-add-disabled'] = __('Creating a user has been disabled', 'tims-nextcloud-sso-oauth2');
	$errors['user-create']       = __('Failed to create user, the username or email address may already exist', 'tims-nextcloud-sso-oauth2');
	$errors['group-disabled']    = __("Your user group in Nextcloud isn't allowed to access this site", 'tims-nextcloud-sso-oauth2');
	$errors['no-role']           = __("Couldn't find a role to match your user group to", 'tims-nextcloud-sso-oauth2');
	$errors['token-get']		 = __("WordPress couldn't load the URL to retrieve the token", 'tims-nextcloud-sso-oauth2');
	$errors['user-get']          = __("WordPress couldn't load the URL to retrieve the user details in Nextcloud", 'tims-nextcloud-sso-oauth2');
	$errors['unexpected']        = __("Unrecognised response when requesting Nextcloud user data", 'tims-nextcloud-sso-oauth2');

    if(isset($_GET['nc-sso-error']) && isset($errors[$_GET['nc-sso-error']])){
    	// this should be something we are expecting
    	$tims_nso_sso_error = sanitize_text_field($_GET['nc-sso-error']);
    	if(array_key_exists($tims_nso_sso_error, $errors)){
    		$error = $errors[$tims_nso_sso_error];

    		tims_nso_log('Error: '.$errors[$tims_nso_sso_error]);
    	}
    }
}
add_action('login_head','tims_nso_errors');