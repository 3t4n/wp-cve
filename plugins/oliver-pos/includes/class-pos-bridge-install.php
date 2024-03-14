<?php
defined( 'ABSPATH' ) || exit;
/**
 * All about installation process of oliver pos bridge plugin
 */
class Pos_Bridge_Install {
	/**
	 * Initiate AJAX routes.
	 */

	public static function oliver_pos_init() {
		add_action( 'init', array( __CLASS__, 'oliver_pos_install' ), 5 );
		/* Register AJAX routes*/
		add_action('wp_ajax_oliver_pos_init_connection', array(__CLASS__, 'oliver_pos_init_connection'));
		add_action('wp_ajax_oliver_pos_disconnect_subscription', array(__CLASS__, 'oliver_pos_disconnect_subscription'));
		add_action('wp_ajax_oliver_pos_remove_subscription', array(__CLASS__, 'oliver_pos_remove_subscription'));
		add_action('wp_ajax_oliver_pos_connect_site', array(__CLASS__, 'oliver_pos_connect_site'));
		add_action('wp_ajax_oliver_pos_delete_subscription', array(__CLASS__, 'oliver_pos_delete_subscription'));
		add_action('admin_menu', array(__CLASS__, 'oliver_pos_create_admin_menu'));
		add_action('wp_ajax_oliver_pos_deactivate_plugin', array(__CLASS__, 'oliver_pos_deactivate_plugin'));
		//Since 2.3.9.3
		add_action('wp_ajax_oliver_pos_system_check', array(__CLASS__, 'oliver_pos_system_check'));
		add_action('wp_ajax_oliver_pos_register_url', array(__CLASS__, 'oliver_pos_register_url'));
		add_action('wp_ajax_oliver_pos_getWebsiteSpeed', array(__CLASS__, 'oliver_pos_getWebsiteSpeed'));
		add_action('wp_ajax_oliver_pos_syncing_status', array(__CLASS__, 'oliver_pos_syncing_status'));
		/* Register AJAX routes*/
		/* Add for points and rewards*/
		add_action('check_oliver_points_and_rewards_connected', array(__CLASS__, 'oliver_pos_check_oliver_pr_connected'));
	}

	/**
	 * Install Oliver POS.
	 */
	public static function oliver_pos_install() {
		self::oliver_pos_create_options();
	}
	public static function oliver_pos_create_options() {
		# used for create options
	}

	/**
	 * Create menu page in wordpress admin panel.
	 */

	public static function oliver_pos_create_admin_menu() {
		// add menu
		add_menu_page('Oliver POS Bridge', 'Oliver POS Bridge', 'manage_options', 'oliver-pos', array( __CLASS__, 'oliver_pos_load_menu_view' ), plugins_url('public/resource/img/oliver_icon_121.png', dirname(__FILE__)),32);
		// add submenu for menu
		add_submenu_page('oliver-pos' ,'Dashboard', 'Dashboard', 'manage_options', 'oliver-pos',array( __CLASS__, 'oliver_pos_load_menu_view' ));
	}

	public static function oliver_pos_load_menu_view() {
		pos_bridge_miscellaneous::oliver_pos_add_payment_method_to_old_order();
		return require(dirname(__FILE__) . '/views/backend/create-subscription-new.php');
	}
	public static function oliver_pos_init_connection() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		$site_address = HOME_URL;
		// data to be sent
		if (!get_option('oliver_pos_authorization_token')) {
			self::oliver_pos_set_authorization_token($site_address);
		}
		$token = get_option('oliver_pos_authorization_token');
		$version = OLIVER_POS_PLUGIN_VERSION_NUMBER;
		$clientGuid = get_option('oliver_pos_subscription_client_id');

		//url to call
		$url = INIT_CONNECTION;
		$esc_url =esc_url_raw("{$url}?sourceUrl={$site_address}&clientGuid={$clientGuid}&clientToken={$token}&version={$version}");
		oliver_log("init connection url = " . $esc_url);
		// Get cURL resource
		$wp_remote_get = wp_remote_get($esc_url, array(
			'timeout'     => 120,
			'redirection' => 1,
		));

		if ( is_wp_error( $wp_remote_get ) ) {
			$response = json_encode(array("Message" => $wp_remote_get->get_error_message()));
			oliver_log("Something went wrong: $response");
		} else {
			$response = wp_remote_retrieve_body($wp_remote_get);
			oliver_log("Not occur wp_error");

			if (wp_remote_retrieve_response_code($wp_remote_get) == 200) {
				$decode_response = json_decode( wp_remote_retrieve_body($wp_remote_get) );
				if ( $decode_response->is_success ) {
					if ( ! empty($decode_response->content) && is_object($decode_response->content)) {
						$content = $decode_response->content;

						/*
						* client id and token used for base 64 authorization
						* oliver_pos_subscription_client_id = clientId (super admin)
						* oliver_pos_subscription_udid = clientId (super admin) for all API
						* oliver_pos_subscription_token = server_token (super admin)
						*/
						update_option('oliver_pos_subscription_client_id', sanitize_text_field($content->client_id), false); //it is client id
						update_option('oliver_pos_subscription_udid',  sanitize_text_field($content->udid), false);  //it is client id
						update_option('oliver_pos_subscription_token', sanitize_text_field($content->server_token), false); //it is client token

						if (isset($content->auth_token) && ! empty($content->auth_token)) {
							update_option('oliver_pos_subscription_autologin_token', sanitize_text_field($content->auth_token), false); //This token used for auto login get from super admin
							oliver_log("oliver_pos_subscription_autologin_token = " . get_option("oliver_pos_subscription_autologin_token"));
						}

						oliver_log("oliver_pos_subscription_client_id = " . get_option("oliver_pos_subscription_client_id"));
						oliver_log("oliver_pos_subscription_token = " . get_option("oliver_pos_subscription_token"));
						oliver_log("oliver_pos_subscription_udid = " . get_option("oliver_pos_subscription_udid"));

						/**
						 * Trigger the service if plugin version chenged
						 * @since 2.3.0.9
						 */
						if( ! get_option( 'oliver_pos_previouse_version' ) ){
							add_option( 'oliver_pos_previouse_version', OLIVER_POS_PLUGIN_VERSION_NUMBER );
							self::oliver_pos_trigger_update_version_number();
						} else {
							if (get_option( 'oliver_pos_previouse_version' ) != OLIVER_POS_PLUGIN_VERSION_NUMBER) {
								update_option( 'oliver_pos_previouse_version', OLIVER_POS_PLUGIN_VERSION_NUMBER );
								self::oliver_pos_trigger_update_version_number();
							}
						}

						/**
						 * Trigger the service after try connect
						 * @since 2.3.3.1
						 */
						self::oliver_pos_trigger_bridge_details();
					}
				}
			}
		}
		print_r($response);
		exit();
	}

	public static function oliver_pos_remove_subscription() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		$exceptions='';
		update_option('oliver_pos_sync_data', false);
		$url = esc_url_raw( REMOVESUBSCRIPTION );
		$wp_remote_get = wp_remote_get($url, array(
			'sslverify' => true,
			'headers' => array(
				'Authorization' => AUTHORIZATION,
			),
		));
		if ( is_wp_error( $wp_remote_get ) ) {
			echo json_encode(array("exceptions"=>$exceptions, "Message" => $wp_remote_get->get_error_message()));
		} else {

			if (wp_remote_retrieve_response_code($wp_remote_get) == 200) {
				$decode_response = json_decode( wp_remote_retrieve_body($wp_remote_get) );
				if ( $decode_response->is_success ) {

					$exceptions = $decode_response->exceptions;
					$message = $decode_response->message;
					echo json_encode(array("exceptions"=>$exceptions, "Message"=>$message));
				}
				else{
					$exceptions = $decode_response->exceptions;
					$message = $decode_response->message;
					echo json_encode(array("exceptions"=>$exceptions, "Message"=>$message));
				}
			}
		}

		exit();
	}
	public static function oliver_pos_connect_site() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
        $site_address = urlencode(HOME_URL);
		$version = OLIVER_POS_PLUGIN_VERSION_NUMBER;
		$subscription_key = urlencode(sanitize_text_field($_POST['subscription_key']));
		$token = urlencode(get_option('oliver_pos_authorization_token'));
		$url = esc_url_raw( ASSIGNSUBSCRIPTION );
		$esc_url =esc_url_raw("{$url}?sourceUrl={$site_address}&clientToken={$token}&subscriptionCode={$subscription_key}&version={$version}");
		$opConnect='noResponse';
		$Message=null;
		$exceptions=null;
		$wp_remote_get = wp_remote_get($esc_url, array(
			'sslverify' => true,
		));

		if ( is_wp_error( $wp_remote_get ) ) {
			$response = json_encode(array("Message" => $wp_remote_get->get_error_message()));
			oliver_log("connect with key:$subscription_key error: $response");
		} else {
			if (wp_remote_retrieve_response_code($wp_remote_get) == 200) {
				$decode_response = json_decode( wp_remote_retrieve_body($wp_remote_get) );
				$Message = $decode_response->message;
				$exceptions = $decode_response->exceptions;
				$opConnect='yes';
			}
		}
		echo json_encode(array("exceptions"=>$exceptions, "Message"=>$Message, "opConnect"=>$opConnect));
		exit();
	}

	public static function oliver_pos_disconnect_subscription() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		$url = esc_url_raw( ASP_TRY_DISCONNECT );
		oliver_log("disconnect_subscription = {$url}");

		// Get cURL resource
		wp_remote_get($url, array(
			'timeout'   => 0.01,
			'blocking'  => false,
			'sslverify' => false,
			'headers' => array(
				'Authorization' => AUTHORIZATION,
			),
		));
		exit();
	}

	public static function oliver_pos_delete_subscription() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		oliver_log("delete_subscription");
		// bridge
		delete_option( 'oliver_pos_authorization_token' );

		// super admin
		delete_option( 'oliver_pos_subscription_udid' );
		delete_option( 'oliver_pos_subscription_client_id' );
		delete_option( 'oliver_pos_subscription_token' );
		delete_option( 'oliver_pos_subscription_autologin_token' );

		echo json_encode( array(
			'status'   => true,
			'message'  => 'success',
		) );

		exit();
	}

	public static function oliver_pos_deactivate_plugin() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		//@since 2.3.8.3
		//First check points and rewards and disconnect it then deactivate
		$all_installed_plugins = get_plugins();
		$op_plugin_slug = 'oliver-pos-points-and-rewards/oliver-pos-points-and-rewards.php';
		if(array_key_exists( $op_plugin_slug, $all_installed_plugins ))
		{
			do_action( 'oliver_points_and_rewards_deactivate_plugin' );
			deactivate_plugins( '/oliver-pos-points-and-rewards/oliver-pos-points-and-rewards.php' );
		}
		delete_option( 'pos_bridge_plugin_do_deactivation_redirection' );
		deactivate_plugins( '/oliver-pos/oliver-pos.php' );
		echo json_encode(array( 'status'   => true, 'message'  => 'success' ));
		exit;
	}

	public static function oliver_pos_set_authorization_token($url) {
		$rand = $url . mt_rand(); //generates a random integer using the Mersenne Twister algorithm.
		$token = md5($rand); //calculates the MD5 hash of a string.
		update_option( 'oliver_pos_authorization_token', sanitize_text_field($token));
	}

	/**
	 * Trigger the service if plugin version chenged
	 * @since 2.3.0.9
	 * @return void call ASP.Net API's
	 */
	public static function oliver_pos_trigger_update_version_number() {
		$method = ASP_TRIGGER_CHANGE_BRIDGE_VERSION;
		$version = OLIVER_POS_PLUGIN_VERSION_NUMBER;
		$client_id = get_option("oliver_pos_subscription_client_id");
		$url = esc_url_raw("{$method}?clientId={$client_id}&version={$version}");
		oliver_log("trigger_update_version_number = {$url}");

		// Get cURL resource
		wp_remote_get($url, array(
			'timeout'   => 0.01,
			'blocking'  => false,
			'sslverify' => false,
			'headers' => array(
				'Authorization' => AUTHORIZATION,
			),
		));
	}

	/**
	 * Trigger the service after try connect
	 * @since 2.3.3.1
	 * @return void call ASP.Net API's
	 */
	public static function oliver_pos_trigger_bridge_details() {
		$method = ASP_TRIGGER_BRIDGE_DETAILS;
		$client_id = get_option("oliver_pos_subscription_client_id");
		$url = esc_url_raw("{$method}?clientId={$client_id}");
		oliver_log("trigger_bridge_details = {$url}");

		// Get cURL resource
		wp_remote_get($url, array(
			'timeout'   => 0.01,
			'blocking'  => false,
			'sslverify' => false,
			'headers' => array(
				'Authorization' => AUTHORIZATION,
			),
		));
	}

	public static function get_authorization_token() {
		return get_option( 'oliver_pos_authorization_token' );
	}
	//Since 2.3.9.3
	public static function oliver_pos_system_check() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		$validateVersionUrl = ASP_VALIDATE_VERSION;
		$noErrors ='';
		$issueCount =0;
		$noPermalink='yes';
		$localhost= 'no';
		$redError='no';
		$plugin_issue= 'no';
        $plugin_name='';
		$op_speed='hide';
		//Add check for plugins that can issue with oliver
		if ( in_array( 'members/members.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$members_settings = get_option('members_settings');
			if(isset($members_settings['private_rest_api']) && !empty($members_settings['private_rest_api'])){
				$issueCount =$issueCount + 1;
				$noErrors='yes';
				$plugin_issue= 'yes';
				$plugin_name='Members';
			}
		}
		if ( in_array( 'woocommerce-point-of-sale/woocommerce-point-of-sale.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$issueCount =$issueCount + 1;
			$noErrors='yes';
			$plugin_issue= 'yes';
			$plugin_name='Woocommerce point of sale is install';
		}
		//points and rewards check
		if ( in_array( 'oliver-pos-points-and-rewards/oliver-pos-points-and-rewards.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
		{
			$oliver_points =  get_option('oliver_points_and_rewards_status');
			if($oliver_points=='disconnected'){
				do_action( 'oliver_points_and_rewards_activate_plugin' );
			}
			else{
				do_action( 'check_oliver_points_and_rewards_connected' );
			}
		}
		//permalinks check
		$permalinksSettings =  get_option('permalink_structure');
		if($permalinksSettings=='')
		{
			$noPermalink = 'notset';
			$issueCount = $issueCount + 1;
			$noErrors='yes';
			$redError='yes';
		}
		//SSL check
		$sslResult = is_ssl();
		if( empty ( $sslResult ) ) {
			$issueCount = $issueCount + 1;
			$noErrors = 'yes';
			$redError = 'yes';
		}
		//Localhost check
		$pri_addrs = array (
			'10.0.0.0|10.255.255.255', // single class A network
			'172.16.0.0|172.31.255.255', // 16 contiguous class B network
			'192.168.0.0|192.168.255.255', // 256 contiguous class C network
			'169.254.0.0|169.254.255.255', // Link-local address also refered to as Automatic Private IP Addressing
			'127.0.0.1|127.255.255.255','::1' //Localhost
		);
		if(in_array( $_SERVER['REMOTE_ADDR'], $pri_addrs))
		{
			$localhost= 'localhost';
			$issueCount = $issueCount + 1;
			$noErrors='yes';
			$redError='yes';
		}
		//hard bloker and soft bloker version and plugins api
		$SoftBlokerPlugins = array();
		$HardBlokerPlugins = array();
		$SoftVersions = array();
		$HardVersions = array();
		$SoftBloker='';
		$HardBloker='';
		$responceHub = wp_remote_get( $validateVersionUrl, array(
			'headers' => array(
				'Authorization' => AUTHORIZATION,
			),
		));
		if ( is_wp_error($responceHub)) {
			$responceHubData = json_encode(array("Message" => $responceHub->get_error_message()));
			oliver_log("Something went wrong: $responceHubData");
		}
		else
		{
			if (wp_remote_retrieve_response_code($responceHub) == 200)
			{
				$responceHubData = json_decode(wp_remote_retrieve_body($responceHub));
				if ($responceHubData->is_success )
				{
					if (!empty($responceHubData->content) && is_object($responceHubData->content))
					{
						$responceContent = $responceHubData->content;
						$SoftBlokerPlugins = $responceContent->SoftBlokerPlugins;
						$HardBlokerPlugins = $responceContent->HardBlokerPlugins;
						$SoftVersions = $responceContent->SoftVersion;
						$HardVersions = $responceContent->HardVersion;
						if(!empty($HardBlokerPlugins)){
							$HardBlokerPluginsCount = count($HardBlokerPlugins);
							if(!empty($HardBlokerPluginsCount)){
								$issueCount =$issueCount + $HardBlokerPluginsCount;
								$noErrors='yes';
							}
						}
						if(!empty($SoftBlokerPlugins)){
							$noErrors='yes';
						}
						if(!empty($HardVersions)){
							$HardVersionsCount = count($HardVersions);
							if(!empty($HardVersionsCount)){
								$issueCount =$issueCount + $HardVersionsCount;
								$noErrors='yes';
							}
						}
						if(!empty($SoftVersions)){
							$noErrors='yes';
						}
						$SoftBlokerPlugins = array_merge($SoftBlokerPlugins,$SoftVersions);
						if(!empty($SoftBlokerPlugins))
						{
							foreach($SoftBlokerPlugins as $SoftBlokerPlugin)
							{
								$SoftBloker .= '<div class="op-streached-card op-error-warning"><div class="op-streached-card-container"><div class="op-streached-card-content"><div class="op-streached-card-img-group"> <img src="'.plugins_url('public/resource/img/tri.svg', dirname(__FILE__)).'" alt="" /></div><span>'. $SoftBlokerPlugin->Messages. '</span></div>';

								if(!empty($SoftBlokerPlugin->Link))
								{
									$SoftBloker .= '<a href="'. $SoftBlokerPlugin->Link .'" target="_blank">Learn More</a>';
								}
								$SoftBloker .= '</div></div>';
							}
						}
						$HardBlokerPlugins = array_merge($HardBlokerPlugins,$HardVersions);
						if(!empty($HardBlokerPlugins))
						{
							foreach($HardBlokerPlugins as $HardBlokerPlugin)
							{

								$HardBloker .= '<div class="op-streached-card op-hard-error-warning"><div class="op-streached-card-container"><div class="op-streached-card-content"><div class="op-streached-card-img-group"> <img src="'.plugins_url('public/resource/img/hex.svg', dirname(__FILE__)).'" alt="" /></div><span>'. $HardBlokerPlugin->Messages. '</span></div>';

								if(!empty($HardBlokerPlugin->Link))
								{
									$HardBloker .= '<a href="'. $HardBlokerPlugin->Link .'" target="_blank">Learn More</a>';
								}
								$HardBloker .= '</div></div>';
							}
						}
					}
				}
			}
		}
		$op_speed_check = get_option('op_speed_check');
		if($op_speed_check==true){
			$issueCount=$issueCount+1;
			$op_speed='show';
		}
		echo json_encode(array("permalink"=>$noPermalink, "ssl_result"=>$sslResult, "localhost"=>$localhost, "issue_count"=>$issueCount, "SoftBloker"=>$SoftBloker, "HardBloker"=>$HardBloker, "no_errors"=>$noErrors, "plugin_issue"=>$plugin_issue, "redError"=>$redError, "op_speed"=>$op_speed, "plugin_name"=>$plugin_name));
		exit();
	}
	//Since 2.3.9.3
	public static function oliver_pos_register_url() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
        $register_url='not_register';
        $hub_url='';
		$registerApiUrl = ASP_LAUNCH_REGISTER;
		$responceReg = wp_remote_get( $registerApiUrl, array(
			'headers' => array(
				'Authorization' => AUTHORIZATION,
			),
		));
		if ( is_wp_error($responceReg))
		{
			$responceRegData = json_encode(array("Message" => $responceReg->get_error_message()));
			oliver_log("Something went wrong: $responceRegData");
		}
		else
		{
			if (wp_remote_retrieve_response_code($responceReg) == 200)
			{
				$responceRegData = json_decode(wp_remote_retrieve_body($responceReg));
				if ( $responceRegData->is_success )
				{
					if (!empty($responceRegData->content))
					{
                        $register_url = $responceRegData->content->register_url;
                        $hub_url = $responceRegData->content->hub_url;
					}
				}
			}
		}
        echo json_encode(array("register_url"=>urldecode($register_url), "hub_url"=>urldecode($hub_url)));
        exit();
	}
	//Since 2.3.9.3
	public static function oliver_pos_getWebsiteSpeed() {
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		$opSpeed='noResponse';
		$url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url='.urlencode(HOME_URL);
		$speedInfoRes = wp_remote_get($url, array(
			'timeout'     => 120,
			'redirection' => 1,
		));
		if ( is_wp_error($speedInfoRes)) {
			$response = json_encode(array("Message" => $speedInfoRes->get_error_message()));
			oliver_log("Speed optimize error: $response");
		}
		else
		{
			if (wp_remote_retrieve_response_code($speedInfoRes) == 200)
			{
				$responseSpeed = json_decode(wp_remote_retrieve_body($speedInfoRes));
				if($responseSpeed->lighthouseResult->audits){
					$audits = (array)$responseSpeed->lighthouseResult->audits;
					$items = $audits['server-response-time']->details->items;
					if($items[0]->responseTime>=500){
						$opSpeed='show';
						update_option('op_speed_check', true);
					}
					else{
						update_option('op_speed_check', false);
					}
				}
			}
		}
		echo json_encode(array("opSpeed"=>$opSpeed));
		exit();
	}
	public static function oliver_pos_syncing_status(){
		if ( !wp_verify_nonce( $_REQUEST['security'], "oliver-pos-nonce") && !current_user_can( 'manage_options' )) {
			echo json_encode(array("exceptions"=>"Security validation failed"));
			exit();
		}
		$Synced= 'not_started';
		$SyncedPercent= 0;
		$interval=5000;
		$url = ASP_SYNCSTATUS;
		$wp_remote_get = wp_remote_get( $url, array(
			'headers' => array(
				'Authorization' => AUTHORIZATION,
			),
		));
		if (wp_remote_retrieve_response_code($wp_remote_get) == 200) {
			$decode_response = json_decode( wp_remote_retrieve_body($wp_remote_get) );
			if ( $decode_response->is_success ) {
				if ( ! empty($decode_response->content) && is_object($decode_response->content)) {
					$SyncedPercent = (int)($decode_response->content->SyncedPercent);
					$SyncStarted = $decode_response->content->SyncStarted;
					if($SyncedPercent>90){
						$interval=2000;
					}
					if( $decode_response->content->Synced == true || $SyncedPercent>=95 ){
						update_option('oliver_pos_sync_data', 'synced');
						$Synced= 'synced';
					}
					elseif($decode_response->content->Synced == false && $SyncStarted==true){
						$Synced= 'syncing';
						update_option('oliver_pos_sync_data', 'syncing');
					}
					elseif($SyncStarted==false){
						$Synced= 'not_started';
						update_option('oliver_pos_sync_data', 'not_started');
					}
				}
			}
		}
		echo json_encode(array("Synced"=>$Synced, "interval"=>$interval, "SyncedPercent"=>$SyncedPercent));
		exit;
	}
	/* check points and rewards connected or not in hub */
	public static function oliver_pos_check_oliver_pr_connected(){
		$code = get_option('op_points_rewards_code');
		$opr_endpoint 	= 'GetExtention';
		$server_url = get_option('op_points_rewards_extenstion_server_url');
		// Url to call
		$esc_url = esc_url_raw("{$server_url}/{$opr_endpoint}?code={$code}");
		$responceHub = wp_remote_get($esc_url, array(
				'headers' => array(
					'Authorization' => AUTHORIZATION,
				),
			)
		);
		if ( is_wp_error($responceHub)) {
			$responceHubData = json_encode(array("Message" => $responceHub->get_error_message()));
			oliver_log("Something went wrong: $responceHubData");
		}
		else
		{
			if (wp_remote_retrieve_response_code($responceHub) == 200)
			{
				$responceHubData = json_decode(wp_remote_retrieve_body($responceHub));
				if ($responceHubData->IsSuccess==false || $responceHubData->Content=="plugin_not_registered" || $responceHubData->Content=="plugin_deactivated" )
				{
					update_option('oliver_points_and_rewards_status', 'disconnected');
				}
			}
			else{
				update_option('oliver_points_and_rewards_status', 'disconnected');
			}
		}
	}
}
Pos_Bridge_Install::oliver_pos_init();