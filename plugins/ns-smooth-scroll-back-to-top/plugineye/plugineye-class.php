<?php
/** 
 * @author 		PluginEye
 * @link		https://www.plugineye.com/
 * @version 	1.1.1	
 * 
 * 	>>> INDEX:
 * 	1.0 Class properties
 * 	2.0 Class constructor
 *		2.1 Constructor __construrctor(array());
 * 	3.0 Getters
 *		3.1 getData() method
 *		3.2 getPluginInitValueOption() method
 *	4.0 Setters
 *		4.1 setPluginInitValueOption() method
 *	5.0 pluginEyeStart() method
 *	6.0 addHiddenMenu() method
 *	7.0 Enqueue styles
 *		7.1 Enqueue css
 *		7.2 Enqueue js
 *	8.0 redirectOnActivationu() method
 *	9.0 displayCatchData() method
 * 10.0 pluginSandData($user) method
 * 11.0 plugineyePrintForm($user) method
 * 12.0 plugineyeUserData($user) method 
 * 13.0 plugineyeSiteData() method 
 * 14.0 plugineyeSdkData() method
 * 15.0 pluginEyeOnDeactivationFunction( $links, $file ) method
 * 16.0 requireAjaxFunction() method
 * 17.0 printModalForDeactivation() method
 * 18.0 reactivationPluginEye() method
*/
if(!class_exists ('pluginEye')) {
	class pluginEye{
		/**
		 * 1.0 Class properties
		 * @var array $plugineye_data	Collection of plugin data.
		*/
		private $plugineye_data;

		/**
		 * 2.0 Class constructor
		 * 		2.1 Constructor __construrctor(array());
		 * 		@param array 
		*/
		public function __construct($plugineye_data){
			$this->plugineye_data = $plugineye_data;
		}
		/**
		* 3.0 Getters
		*		3.1 getData() method
		*		@return array
		*/
		public function getData() { 
			return $this->plugineye_data; 
		}
		/**
		* 
		*		3.2 getPluginInitValueOption() method
		*		@return  string|false
		*		string can be: activated|skipped
		*/
		public function getPluginInitValueOption(){
			$data = $this->getData();
			return get_option('plugineye_init'.$data['plugin_id'], false);
		}
		/**
		* 4.0 Setters
		*		4.1 setPluginInitValueOption() method
		*		@param string $value
		*		$value must be activated|skipped	
		*/
		private function setPluginInitValueOption($value){
			$data = $this->getData();
			update_option('plugineye_init'.$data['plugin_id'], $value);
		}
		/**
		* 5.0 pluginEyeStart() method 
		*		is the entry point to PluginEye class: calls main private methods.
		*		there are method callable only one time a the activation
		*		and others used for deactivation.
		*/
		public function pluginEyeStart(){
			//PluginEye works only in wp backend.
			if(!is_admin())
				return;
			$pluginInitValueOption = $this->getPluginInitValueOption();
			if(!$pluginInitValueOption){
				$this->addHiddenMenu();
				$this->enqueueStyle();
				$this->redirectOnActivation();
				return;
			}else if($pluginInitValueOption == 'activated'){
				//in a filter we can specify a specific class method;
				//i do this in array with (object, method) parameters.
				if(add_filter( 'plugin_action_links', array( $this, 'pluginEyeOnDeactivationFunction' ), 10, 2 )){
					$this->enqueueStyle();
					$this->requireAjaxFunction();
					$this->requireJsFileForDeactivation();
					$this->printModalForDeactivation();
				}
				$this->reactivationPluginEye();
			}
			
		}

		/**
		* 6.0 addHiddenMenu() method 
		*		@return $this->displayCatchData()
		*		Create new hidden menu where we can show our section.
		*/
		private function addHiddenMenu () {
			add_action( 'admin_menu', function()  {
				$data = $this->getData();
				add_submenu_page( '_doesnt_exist', 'PulginEye', '', 'manage_options', 'plugineye'.$data['plugin_id'], function(){
					$this->displayCatchData();
				});
			});
		}
		/**
		* 7.0 Enqueue styles
		*		7.1 Enqueue css
		*/
		private function enqueueStyle () {
			add_action('admin_enqueue_scripts', function()  {
				$data = $this->getData();
				$dir = $data['plugin_dir_url'].'plugineye/assets/css/plugineye_style.css';
				wp_enqueue_style('plugineye-style'.$data['plugin_id'], $dir, array(), '1.0.0', 'all');
			});
		}
		/**
		* 		7.2 Enqueue js
		*/
		private function requireJsFileForDeactivation(){
			
			add_action( 'admin_enqueue_scripts', function(){
				$data = $this->getData();
				wp_enqueue_script( 'JsFileForDeactivation', $data['plugin_dir_url'] . 'plugineye/assets/js/plugineye_scripts.js', array( 'jquery' ) );
				wp_localize_script( 'JsFileForDeactivation', 'pe_api_on_deactivation', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
			});
		}
		/**
		* 8.0 redirectOnActivationu() method 
		*		check if is this plugin activation and redirect, do nothing otherwise
		*/
		private function redirectOnActivation () {
			add_action( 'activated_plugin', function ( $plugin ) {
				$data = $this->getData();
				if( $plugin == $data['main_directory_name'].'/'.$data['main_file_name']) {      
					exit( wp_redirect( admin_url( 'options.php?page=plugineye'.$data['plugin_id'] ) ) );
				}	
			});
		}
		/**
		* 9.0 displayCatchData() method 
		*/
		private function displayCatchData(){
			$user = wp_get_current_user();
			$this->pluginSandData($user);
			$this->plugineyePrintForm($user);

		}
		/**
		* 10.0 pluginSandData($user) method 
		* @param object -> WP_User $user
		* if user click "Allow and continue" button we take and save data 
		* thanks to plugineye API.
		*/
		private function pluginSandData($user){
			if(isset($_POST['ns-response'])){  
				$data = $this->getData();
				if($_POST['ns-response']=='Allow and continue'){
					$user_data = $this->plugineyeUserData($user);
					$site_data = $this->plugineyeSiteData();
					$sdk_data = $this->plugineyeSdkData();
					$body = json_encode($user_data + $site_data + $sdk_data);
					$header = array(
						'Content-Type' => 'application/json; charset=utf-8',
						'Authorization' => $data['plugin_token']
					);
					$args = array(
						'body'          => $body,
						'timeout'       => '5',
						'redirection'   => '5',
						'httpversion'   => '1.0',
						'blocking'      => true,
						'headers'       => $header,
						'cookies'       => array()
					);
					$response = wp_remote_post( 'http://api.plugineye.com/public/api/v1/saveData', $args );
					if(!is_wp_error($response)){
						$this->setPluginInitValueOption('activated');
						$response = json_decode($response['body'], true);
						add_option('pe-plugin-id-response-'.$data['plugin_id'], $response['id_plugin_eye']);
					}
					
				}else{
					$this->setPluginInitValueOption('skipped');
				}
				if(isset($data['redirect_after_confirm']))
					//exit( wp_redirect( admin_url($data['redirect_after_confirm'] ) ) );
					echo '<script>window.location.replace("'.admin_url($data['redirect_after_confirm']).'");</script>';
				//exit( wp_redirect( admin_url( 'plugins.php' ) ) );
				echo '<script>window.location.replace("'.admin_url( 'plugins.php' ).'");</script>';
				
			}
			
		}
		/**
		* 11.0 plugineyePrintForm($user) method 
		* @param object -> WP_User $user
		* Print pluginEye request form 
		* get authorization to take data.
		*/
		private function plugineyePrintForm($user){
			$data = $this->getData();
			?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						jQuery('.pe-show-list').click(function() {
							jQuery('.pe-hidden-container').slideToggle('slow');
						});
					})
				</script>
				<div class="ns-marketing-container">
					<!-- <div class="ns-logo-container">
						<img src="<?php echo $data['plugin_dir_url']; ?>plugineye/img/logo-plugineye.png">
					</div> -->
					<div class="ns-text-container">
						Hi <b><?php echo $user->user_nicename; ?></b>, <br>
						we continuously improve our plugin compatibility.<br>
						Never miss an important update and allow<br> our diagnostic tracking with <a href="https://www.plugineye.com/" target="_blank">plugineye.com</a>.
					</div>
					<div class="ns-button-container">
						<form action="<?php echo admin_url( 'options.php?page=plugineye'.$data['plugin_id'] ) ?>" method="post">
							<input type="submit" class="button-primary ns-primary" name="ns-response" value="Allow and continue">
							<input type="submit" class="button-secondary ns-secondary" name="ns-response" value="Skip">
						</form>
					</div>
					<div class="pe-show-list-container">
						<span class="pe-show-list">What does we store?</span>
					</div>
					<div class="pe-hidden-container">
						<div class="pe-info-container">
							<div class="pe-img">
								<span class="dashicons dashicons-admin-users pe-dashicon-size"></span>
							</div>
							<div class="pe-info">
								<p class="pe-p-info"><b>YOUR PROFILE OVERVIEW</b></p>
								<p class="pe-p-info">Name and email address</p>
							</div>
						</div>
						<div class="pe-info-container">
							<div class="pe-img">
								<span class="dashicons dashicons-wordpress pe-dashicon-size"></span>
							</div>
							<div class="pe-info">
								<p class="pe-p-info"><b>YOUR SITE OVERVIEW</b></p>
								<p class="pe-p-info">Site address and WordPress version</p>
							</div>
						</div>
						<div class="pe-info-container">
							<div class="pe-img">
								<span class="dashicons dashicons-admin-plugins pe-dashicon-size"></span>
							</div>
							<div class="pe-info">
								<p class="pe-p-info"><b>CURRENT PLUGIN EVENTS</b></p>
								<p class="pe-p-info">Plugin activation</p>
							</div>
						</div>
					</div>
					<div class="ns-privacy-container">
						<div class="ns-link-container">
							<a href="https://www.plugineye.com/privacy-policy/" target="_blank">Privacy Policy</a>
							<span>-</span>
							<a href="https://www.plugineye.com/terms-of-service/" target="_blank">Terms of Service</a>
						</div>
					</div>

				</div>
			<?php

		}
		/**
		* 12.0 plugineyeUserData($user) method 
		* @param object -> WP_User $user <user details>
		* @return array
		* Insert into this section user details to return on API.
		*/
		private function plugineyeUserData($user){
			$user_data = array(
                'member_name'      => $user->user_firstname.' '.$user->user_lastname,
				'member_email'     => $user->user_email,  
				'member_username'  => $user->user_nicename          
            );
    		return $user_data;
		}
		/**
		* 13.0 plugineyeSiteData() method 
		* @return array
		* Insert into this section site details to return on API.
		*/
		private function plugineyeSiteData(){
			$data = $this->getData();
			$plugin = get_plugin_data($data['plugin_dir_path'].$data['main_file_name'], false, false);
			$theme = wp_get_theme();
			$woo_version = 'WOO_NOT_INSTALLED';
			$inactive_plugins = array();
			$plugins_ = get_plugins();
			foreach ( $plugins_ as $key=>$plugin_ ) {
				if(!is_plugin_active($key)){
					array_push($inactive_plugins, $key);
				}
				if($key == 'woocommerce/woocommerce.php'){
					$woo_version = $plugin_['Version'];
				}
			}
			$site_data = array(
				'plugin_name'               => $plugin['Name'],
				'plugin_slug'               => $data['main_directory_name'],
				'plugin_version'            => $plugin['Version'],
				'theme_name'                => $theme->get( 'Name' ),
				'theme_version'             => (string) $theme->get( 'Version' ),
				'theme_author_url'          => $theme->get( 'AuthorURI' ),
				'theme_directory'           => get_stylesheet_directory_uri(),
				'plugin_active_list'        => serialize(get_option('active_plugins')),
				'plugin_inactive_list'      => serialize($inactive_plugins),
				'php_version'               => PHP_VERSION,
				'wp_version'                => get_bloginfo( 'version' ),
				'woo_version'               => $woo_version,
				'site_url'                  => site_url(),
				'site_uri'                  => get_home_path(),
				'site_ip'                   => $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'],
				'plugin_status'             => 1,
				'language'                  => get_locale()
			);
			return $site_data;
		}
		/**
		* 14.0 plugineyeSdkData() method 
		* @return array
		* Insert into this section sdk details to return on API.
		*/
		private function plugineyeSdkData(){
			$data = $this->getData();
			return array(
				'id_plugin' => (int) $data['plugin_id']
			);
		}
		/**
		* 15.0 pluginEyeOnDeactivationFunction( $links, $file ) method
		* @param array $links
		* @param string $file <plugin name ex. plugin/plugin.php>
		* @return array
		* Create a custom "deactivate" link in plugins.php page
		* only for this plugin.
		*/
		public function pluginEyeOnDeactivationFunction( $links, $file ) {
			$data = $this->getData();
			// Check if is our plugin.
			if ( plugin_basename( $data['main_directory_name'].'/'.$data['main_file_name'] ) !== $file ) {
				return $links;
			}
			if ( ! isset( $links['deactivate'] ) ) {
				return $links;
			}
			if ( is_network_admin() ) {
				return $links;
			}
			if ( is_plugin_active_for_network( $file ) ) {
				return $links;
			}
			// Check if the user can Deactivate the plugin
			preg_match_all( '/<a[^>]+href="(.+?)"[^>]*>/i', $links['deactivate'], $matches );
			if ( empty( $matches ) ) {
				return $links;
			}
			$links['deactivate'] = sprintf(
				/* translators: 1: a URL, 2: command name (Deactivate) */
				'<a id="plugineye-deactivate-'.$data['plugin_id'].'" onClick="pe_deactivate_modal('.$data['plugin_id'].');" class="plugineye-deactivate" href="%1$s">%2$s</a>',
				$matches[1][0], // @codingStandardsIgnoreLine
				esc_html( _x( 'Deactivate', 'command (plugins)' ) )
			);
			return $links;
		}

		/**
		* 16.0 requireAjaxFunction() method
		* require ajax function page.
		*/
		private function requireAjaxFunction(){
			$data = $this->getData();
			require_once($data['plugin_dir_path'].'plugineye/plugineye-ajax/plugineye_on_deactivation_function.php');
		}
		
		/**
		* 17.0 printModalForDeactivation() method
		* here is the modal shown in the deactivation.
		*/
		private function printModalForDeactivation(){
			add_action('in_admin_footer', function(){
				$data = $this->getData();
				$id = $data['plugin_id'];
				$token = $data['plugin_token'];
				//if you add a new reason go to "plugineye_on_deactivation_function.php" and change the $reason value checker.
				echo '
				<div id="pe-modal-layer-'.$id.'" class="pe-modal-layer">
					<div class="pe-modal-container pe-center">
						<div class="pe-modal-text-div pe-title">
							<span class="pe-span-title">Deactivation<span>
						</div>
						<div class="pe-modal-text-div pe-question">
							<span class="pe-span-question">We are sorry that you want to deactivate this plugin!<br>
							If you want let us know why:<span>
						</div>
						<div class="pe-div-answer-container">
							<div class="pe-answer">
								<input type="radio" name="answer'.$id.'" value="1" checked> I found a better plugin.<br>
								<input type="radio" name="answer'.$id.'" value="2"> Installed it for a mistake.<br>
								<input type="radio" name="answer'.$id.'" value="3"> I don’t need this plugin.<br>
								<input type="radio" name="answer'.$id.'" value="4"> Incompatibility with my theme.<br>
								<input type="radio" name="answer'.$id.'" value="5"> Incompatibility with my other plugins.<br>
								<input type="radio" name="answer'.$id.'" value="6"> Other reasons.<br>
							</div>
							<div class="pe-modal-text-div pe-modal-buttons">
								<input type="button" class="button-secondary pe-modal-cancel" value="Cancel">
								<input type="submit" class="button-primary" value="Deactivate" onClick="pe_api_on_deactivation_func('.$id.', \''.$token.'\');">
							</div>	
						</div>
					</div>
				</div>';
			});
		}
		/**
		* 18.0 reactivationPluginEye() method
		* works when plugins reactivation.
		* @since 1.1.0 < First time this was introduced. 2019-06-13 >
		*/
		private function reactivationPluginEye(){
			add_action( 'activated_plugin', function ( $plugin ) {
				$data = $this->getData();
				if( $plugin != $data['main_directory_name'].'/'.$data['main_file_name'])   
					return;
				$id = $data['plugin_id'];
				$row_id = get_option('pe-plugin-id-response-'.$id, false);
				if(!$row_id)
					return;
				$token = $data['plugin_token'];
				$body = json_encode(array(
					'plugin_status'     => 1,
					'deactivate_reason' => 0
				));
				$args = array(
					'body'          => $body,
					'method'     => 'PUT',
					'timeout'       => '5',
					'redirection'   => '5',
					'httpversion'   => '1.0',
					'blocking'      => true,
					'headers'       => array('Content-Type' => 'application/json; charset=utf-8',
											'Authorization' => $token
											),
					'cookies'       => array()
				);
				$response = wp_remote_request( 'http://api.plugineye.com/public/api/v1/updateStatus/'.$row_id, $args );
					
			});
			
		}
	}
}
?>