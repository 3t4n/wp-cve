<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/* Update for custom plugins by joomsky */
class MJTC_SUPPORTTICKETUpdater {

	private $api_key = '';
	private $addon_update_data = array();
	private $addon_update_data_errors = array();
	public $addon_installed_array = '';// it is public static bcz it is being used in extended class

	public $addon_installed_version_data = '';// it is public static bcz it is being used in extended class

	public function __construct() {
		$this->MJTC_updateIntilized();

		$transaction_key_array = array();
		$addon_installed_array = array();
		foreach (majesticsupport::$_active_addons AS $addon) {
			$addon_installed_array[] = 'majestic-support-'.$addon;
			$option_name = 'transaction_key_for_majestic-support-'.$addon;
			$transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);
			if(!in_array($transaction_key, $transaction_key_array)){
				$transaction_key_array[] = $transaction_key;
			}
		}
		$this->addon_installed_array = $addon_installed_array;
		$this->api_key = json_encode($transaction_key_array);
	}

	// class constructor triggers this function. sets up intail hooks and filters to be used.
	public function MJTC_updateIntilized(  ) {
		add_action( 'admin_init', array( $this, 'MJTC_adminIntilization' ) );
		include_once( 'class-ms-server-calls.php' );
	}

	// admin init hook triggers this fuction. sets up admin specific hooks and filter
	public function MJTC_adminIntilization() {

		add_filter( 'plugins_api', array( $this, 'MJTC_pluginsAPI' ), 10, 3 );

		if ( current_user_can( 'update_plugins' ) ) {
			$this->MJTC_checkTriggers();
			add_action( 'admin_notices', array( $this, 'MJTC_checkUpdateNotice' ) );
			add_action( 'after_plugin_row', array( $this, 'MJTC_keyInput' ) );
		}
	}

	public function MJTC_keyInput( $file ) {
		$file_array = MJTC_majesticsupportphplib::MJTC_explode('/', $file);
		$addon_slug = $file_array[0];
		if(MJTC_majesticsupportphplib::MJTC_strstr($addon_slug, 'majestic-support-')){
			$addon_name = MJTC_majesticsupportphplib::MJTC_str_replace('majestic-support-', '', $addon_slug);
			if(isset($this->addon_update_data[$file]) || !in_array($addon_name, majesticsupport::$_active_addons)){ // Only checking which addon have update version
				$option_name = 'transaction_key_for_majestic-support-'.$addon_name;
				$transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);
				$verify_results = MJTC_includer::MJTC_getModel('premiumplugin')->activate( array(
		            'token'    => $transaction_key,
		            'plugin_slug'    => $addon_name
		        ) );
		        if(isset($verify_results['verfication_status']) && $verify_results['verfication_status'] == 0){
		        	$updateaddon_slug = MJTC_majesticsupportphplib::MJTC_str_replace("-", " ", $addon_slug);
		        	$message = MJTC_majesticsupportphplib::MJTC_strtoupper( MJTC_majesticsupportphplib::MJTC_substr( $updateaddon_slug, 0, 2 ) ).MJTC_majesticsupportphplib::MJTC_substr(  MJTC_majesticsupportphplib::MJTC_ucwords($updateaddon_slug), 2 ) .' authentication failed. Please insert valid key for authentication.';
		        	if(isset($this->addon_update_data[$file])){
		        		$message = 'There is new version of '. wp_kses(MJTC_majesticsupportphplib::MJTC_strtoupper( MJTC_majesticsupportphplib::MJTC_substr( $updateaddon_slug, 0, 2 ) ), MJTC_ALLOWED_TAGS).wp_kses(MJTC_majesticsupportphplib::MJTC_substr(  MJTC_majesticsupportphplib::MJTC_ucwords($updateaddon_slug), 2 ), MJTC_ALLOWED_TAGS) .' avaible. Please insert valid activation key for updation.';
		        		remove_action('after_plugin_row_'.$file,'wp_plugin_update_row');
					}
		        	include( 'views/html-key-input.php' );
		        	$html = '
					<tr>
						<td class="plugin-update plugin-update colspanchange" colspan="3">
							<div class="update-message notice inline notice-error notice-alt"><p>'. esc_html($message) .'</p></div>
						</td>
					</tr>';
					echo wp_kses($html, MJTC_ALLOWED_TAGS) ;
		        }
			}
		}
	}

	public function MJTC_checkVersionUpdate( $update_data ) {
		if ( empty( $update_data->checked ) ) {
			return $update_data;
		}
		$response_version_data = get_transient('ms_addon_update_temp_data');
		$response_version_data_cdn = get_transient('ms_addon_update_temp_data_cdn');

		if(isset($_SERVER) &&  $_SERVER['REQUEST_URI'] !=''){
            if(MJTC_majesticsupportphplib::MJTC_strstr( $_SERVER['REQUEST_URI'], 'plugins.php')) {
				$response_version_data = get_transient('ms_addon_update_temp_data_plugins');
				$response_version_data_cdn = get_transient('ms_addon_update_temp_data_plugins_cdn');
			 }
        }

		if($response_version_data_cdn === false){
			$cdnversiondata = $this->MJTC_getPluginVersionDataFromCDN();
			set_transient('ms_addon_update_temp_data_cdn', $cdnversiondata, HOUR_IN_SECONDS * 6);
			set_transient('ms_addon_update_temp_data_plugins_cdn', $cdnversiondata, 15);
		}else{
			$cdnversiondata = $response_version_data_cdn;
		}
		$newversionfound = 0;
		if ( $cdnversiondata) {
			if(is_object($cdnversiondata) ){
				foreach ($update_data->checked AS $key => $value) {
					$c_key_array = MJTC_majesticsupportphplib::MJTC_explode('/', $key);
					$c_key = $c_key_array[0];
					if($c_key != ''){
						$c_key = MJTC_majesticsupportphplib::MJTC_str_replace("-","",$c_key);
					}
					$newversion = $this->MJTC_getVersionFromLiveData($cdnversiondata, $c_key);
					if($newversion){
						if(version_compare( $newversion, $value, '>' )){
							$newversionfound = 1;
						}
					}
				}
			}
		}

		if($newversionfound == 1){
			if($response_version_data === false){
				$response = $this->MJTC_getPluginVersionData();
				set_transient('ms_addon_update_temp_data', $response, HOUR_IN_SECONDS * 6);
				set_transient('ms_addon_update_temp_data_plugins', $response, 15);
			}else{
				$response = $response_version_data;
			}
			if ( $response) {
				if(is_object($response) ){
					if(isset($response->addon_response_type) && $response->addon_response_type == 'no_key'){
						foreach ($update_data->checked AS $key => $value) {
							$c_key_array = MJTC_majesticsupportphplib::MJTC_explode('/', $key);
							$c_key = $c_key_array[0];
							if(isset($response->addon_version_data->{$c_key})){
								if(version_compare( $response->addon_version_data->{$c_key}, $value, '>' )){
									$transient_val = get_transient('ms_addon_hide_update_notice');
									if($transient_val === false){
										set_transient('ms_addon_hide_update_notice', 1, DAY_IN_SECONDS );
									}
									$this->addon_update_data[$key] = $response->addon_version_data->{$c_key};
								}
							}
						}
					}else{// addon_response_type other than no_key
						foreach ($update_data->checked AS $key => $value) {
							$c_key_array = MJTC_majesticsupportphplib::MJTC_explode('/', $key);
							$c_key = $c_key_array[0];
							if(isset($response->addon_update_data) && !empty($response->addon_update_data) && isset( $response->addon_update_data->{$c_key})){
								if(version_compare( $response->addon_update_data->{$c_key}->new_version, $value, '>' )){
									$update_data->response[ $key ] = $response->addon_update_data->{$c_key};
									$this->addon_update_data[$key] = $response->addon_update_data->{$c_key};
								}
							}elseif(isset($response->addon_version_data->{$c_key})){
								if(version_compare( $response->addon_version_data->{$c_key}, $value, '>' )){
									$transient_val = get_transient('ms_addon_hide_update_expired_key_notice');
									if($transient_val === false){
										set_transient('ms_addon_hide_update_expired_key_notice', 1, DAY_IN_SECONDS );
									}
									$this->addon_update_data_errors[$key] = $response->addon_version_data->{$c_key};
									$this->addon_update_data[$key] = $response->addon_version_data->{$c_key};
								}
							}else{ // set latest version from cdn data
								if ( $cdnversiondata) {
									if(is_object($cdnversiondata) ){
										$c_key_plain = MJTC_majesticsupportphplib::MJTC_str_replace("-","",$c_key);
										$newversion = $this->MJTC_getVersionFromLiveData($cdnversiondata, $c_key_plain);
										if($newversion){
											if(version_compare( $newversion, $value, '>' )){

												$option_name = 'transaction_key_for_'.$c_key;
												$transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);
												$addon_json_array = array();
												$addon_json_array[] = MJTC_majesticsupportphplib::MJTC_str_replace('majestic-support-', '', $c_key);
												$url = 'https://majesticsupport.com/setup/index.php?token='.$transaction_key.'&productcode='. json_encode($addon_json_array).'&domain='. site_url();

												// prepping data for seamless update of allowed addons
												$plugin = new stdClass();
												$plugin->id = 'w.org/plugins/majestic-support';
												$addon_slug = $c_key;
												$plugin->name = $addon_slug;
												$plugin->plugin = $addon_slug.'/'.$addon_slug.'.php';
												$plugin->slug = $addon_slug;
												$plugin->version = '1.0.1';
												$addonwithoutslash = MJTC_majesticsupportphplib::MJTC_str_replace('-', '', $addon_slug);
												$plugin->new_version = $newversion; 
												$plugin->url = 'https://www.majesticsupport.com/';
												$plugin->download_url = $url;
												$plugin->package = $url;
												$plugin->trunk = $url;
												
												$update_data->response[ $key ] = $plugin;
												$this->addon_update_data[$key] = $plugin;
											}
										}

									}
								}
							}
						}
					}
				}
			}
		}// new version found	
		if(isset($update_data->checked)){
			$this->addon_installed_version_data = $update_data->checked;
		}
		return $update_data;
	}

	public function MJTC_pluginsAPI( $false, $action, $args ) {

		if (!isset( $args->slug )) {
			return false;
		}

		if(MJTC_majesticsupportphplib::MJTC_strstr($args->slug, 'majestic-support-')){
			$response = $this->MJTC_getPluginInfo($args->slug);
			if ($response) {
				$response->sections = json_decode(json_encode($response->sections),true);
				$response->banners = json_decode(json_encode($response->banners),true);
				$response->contributors = json_decode(json_encode($response->contributors),true);
				return $response;
			}
		}else{
			return false;// to handle the case of plugins that need to check version data from wordpress repositry.
		}
	}

	public function MJTC_getPluginInfo($addon_slug) {

		$option_name = 'transaction_key_for_'.$addon_slug;
		$transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);

		if(!$transaction_key){
			die('transient');
			return false;
		}

		$plugin_file_path = content_url().'/plugins/'.$addon_slug.'/'.$addon_slug.'.php';
		$plugin_data = get_plugin_data($plugin_file_path);

		$response = MJTC_SupportTicketServerCalls::MJTC_PluginInformation( array(
			'plugin_slug'    => $addon_slug,
			'version'        => $plugin_data['Version'],
			'token'    => $transaction_key,
			'domain'          => site_url()
		) );
		if ( isset( $response->errors ) ) {
			$this->handle_errors( $response->errors );
		}

		// If everything is okay return the $response
		if ( isset( $response ) && is_object( $response ) && $response !== false ) {
			return $response;
		}

		return false;
	}

	// does changes according to admin triggers.
	private function MJTC_checkTriggers() {
		// $nonce = $_POST['_wpnonce'];
        // if (! wp_verify_nonce( $nonce, 'update-plugins') ) {
        //     die( 'Security check Failed' );
        // }
		if ( isset($_POST['ms_addon_array_for_token']) && ! empty( $_POST[ 'ms_addon_array_for_token' ])){
			$transaction_key = '';
			$addon_name = '';
			foreach ($_POST['ms_addon_array_for_token'] as $key => $value) {
				if(isset($_POST[$value.'_transaction_key']) && $_POST[$value.'_transaction_key'] != ''){
					$transaction_key = majesticsupport::MJTC_sanitizeData($_POST[$value.'_transaction_key']);// MJTC_sanitizeData() function uses wordpress santize functions
					$addon_name = $value;
					break;
				}
			}

			if($transaction_key != ''){
				$token = $this->MJTC_getTokenFromTransactionKey( $transaction_key,$addon_name);
				if($token){
					foreach ($_POST['ms_addon_array_for_token'] as $key => $value) {
						update_option('transaction_key_for_'.$value,$token);
					}
				}else{
					update_option( 'ms-addon-key-error-message','Something went wrong');
				}
			}
		}else{
			foreach ($this->addon_installed_array as $key) {
				if ( ! empty( $_GET[ 'dismiss-ms-addon-update-notice-'.$key] ) ) {
					set_transient('dismiss-ms-addon-update-notice-'.$key, 1, DAY_IN_SECONDS );
				}
			}
		}
	}

	public function MJTC_checkUpdateNotice( ) {
		include_once( 'views/html-update-availble.php' );
	}

	public function MJTC_getPluginVersionData() {
			$response = MJTC_SupportTicketServerCalls::MJTC_PluginUpdateCheck($this->api_key);
			if ( isset( $response->errors ) ) {
				$this->msHandleErrors( $response->errors );
			}

			// Set version variables
			if ( isset( $response ) && is_object( $response ) && $response !== false ) {
				return $response;
			}
		return false;
	}

	public function MJTC_getPluginVersionDataFromCDN() {
			$response = MJTC_SupportTicketServerCalls::MJTC_PluginUpdateCheckFromCDN();
			if ( isset( $response->errors ) ) {
				$this->msHandleErrors( $response->errors );
			}

			// Set version variables
			if ( isset( $response ) && is_object( $response ) && $response !== false ) {
				return $response;
			}
		return false;
	}


	private function MJTC_getVersionFromLiveData($data, $addon_name){
		foreach ($data as $key => $value) {
			if($key == $addon_name){
				return $value;
			}
		}
		return;
	}
	public function MJTC_getPluginLatestVersionData() {
		$response = MJTC_SupportTicketServerCalls::MJTC_GetLatestVersions();
		// Set version variables
		if ( isset( $response ) && is_array( $response ) && $response !== false ) {
			return $response;
		}
		return false;
	}

	public function MJTC_getTokenFromTransactionKey($transaction_key,$addon_name) {
		$response = MJTC_SupportTicketServerCalls::MJTC_GenerateToken($transaction_key,$addon_name);
		// Set version variables
		if (is_array($response) && isset($response['verfication_status']) && $response['verfication_status'] == 1 ) {
			return $response['token'];
		}else{
			$error_message = esc_html(__('Something went wrong','majestic-support'));
			if(is_array($response) && isset($response['error'])){
				$error_message = $response['error'];
			}
			update_option( 'ms-addon-key-error-message',$error_message);
		}
		return false;
	}
}
?>
