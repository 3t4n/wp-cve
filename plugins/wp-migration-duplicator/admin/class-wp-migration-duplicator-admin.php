<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webtoffee.com/
 * @since      1.0.0
 *
 * @package    Wp_Migration_Duplicator
 * @subpackage Wp_Migration_Duplicator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Migration_Duplicator
 * @subpackage Wp_Migration_Duplicator/admin
 * @author     WebToffee <support@webtoffee.com>
 */
class Wp_Migration_Duplicator_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/*
	 * module list, Module folder and main file must be same as that of module name
	 * Please check the `register_modules` method for more details
	 */
	public static $modules = array(
		'logger',
		'export',
		'import',
		'backups',
		'uninstall-feedback',
		'ftp',
		'googledrive',
		's3'
	);

	public static $existing_modules = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('wp_ajax_wp_mgdp_check_authentication', array($this, 'wt_migrator_check_authentication'));
		add_action('wp_ajax_wp_mgdp_populate_cloud_files', array($this, 'wt_migrator_populate_cloud_files'));
                add_action('wp_ajax_wp_mgdp_populate_popup', array($this, 'wp_mgdp_populate_popup'));
                add_action('wp_ajax_wp_mgdp_populate_feedback', array($this, 'wp_mgdp_populate_feedback'));
                add_action('wp_ajax_mgdp_plugin_file_tree', array($this, 'mgdp_plugin_file_tree'));
                /* Download log file via nonce URL */
		add_action('admin_init', array($this, 'log_download_file'), 11);
                add_action('admin_init', array($this, 'change_ftp_table_structure'), 11);
                register_shutdown_function( array( $this, 'wt_log_errors' ),11 );
	}

	/**
	* @since 1.1.2
	* Admin page
	*/
	public function admin_settings_page()
	{
		// save settings
		include WT_MGDP_PLUGIN_PATH . '/admin/partials/wp-migration-duplicator-admin-display.php';
	}

	/**
	 * @since 1.1.8
	 * Admin page
	 */
	public function admin_storage_settings()
	{
		// save settings
		include WT_MGDP_PLUGIN_PATH . '/admin/partials/wp-migration-duplicator-admin-storage-display.php';
	}
        /**
	 * @since 1.1.8
	 * Admin log page
	 */
	public function admin_log_settings()
	{
            ?><div class="wt_mgdp_view_log wt_mgdp_popup">
                    <div class="wt_mgdp_popup_hd">
                            <span style="line-height:40px;" class="dashicons dashicons-media-text"></span>
                            <span class="wt_mgdp_popup_hd_label"><?php _e('View log');?></span>
                            <div class="wt_mgdps_popup_close">X</div>
                    </div>
                    <div class="wt_mgdp_log_container">

                    </div>
            </div><?php
               $download_url=wp_nonce_url(admin_url('admin.php?wt_mgdp_log_download=true&file=_log_file_'), WT_MGDP_POST_TYPE);
               $delete_url =wp_nonce_url(admin_url('admin.php?wt_mgdp_log_delete=true&file=_log_file_'), WT_MGDP_POST_TYPE);
		// save settings
		include WT_MGDP_PLUGIN_PATH . '/admin/partials/wp-migration-duplicator-admin-log-list.php';
	}
        
        public function admin_upgrade_premium_settings()
	{
            ?> <script>
                window.location='https://www.webtoffee.com/product/wordpress-backup-and-migration/?utm_source=dashboard_menu&utm_medium=Migration_free&utm_campaign=WordPress_Backup&utm_content=1.3.6';
              </script> 
            <?php
	}
        

	/**
	 * Generate tab head for settings page.
	 * method will translate the string to current language
	 * @since     1.1.2
	 */
	public static function generate_settings_tabhead($title_arr, $type = "plugin")
	{
		$out_arr = apply_filters("wt_mgdp_" . $type . "_settings_tabhead", $title_arr);
		foreach ($out_arr as $k => $v) {
			if (is_array($v)) {
				$v = (isset($v[2]) ? $v[2] : '') . $v[0] . ' ' . (isset($v[1]) ? $v[1] : '');
			}
?>
<a class="nav-tab nav-tab-custom " href="#<?php echo esc_attr($k); ?>" style="margin-left: 0;margin-right: 0;"><?php echo esc_html($v); ?></a>
		<?php
		}
	}

	/**
	* @since 1.1.2
	* Admin menu hook
	*/
	public function admin_menu()
	{
		/*$menus = array(
			array(
				'menu',
				__('WordPress Migration', 'wp-migration-duplicator'),
				__('WordPress Migration', 'wp-migration-duplicator'),
				'manage_options',
				$this->plugin_name,
				array($this, 'admin_settings_page'),
				'dashicons-image-rotate-left',
				56
			)
		);*/
             $menus=array(
			array(
				'menu',
				__('Backup & Migration', 'wp-migration-duplicator'),
				__('WordPress Migration', 'wp-migration-duplicator'),
                                'manage_options',
                                $this->plugin_name,
                            	array($this,'admin_settings_page'),
				'data:image/svg+xml;base64,' . base64_encode('<svg width="300" height="193" viewBox="0 0 300 193" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M245.191 87.9837C242.893 43.5611 205.897 8 161.087 8C119.84 8 85.2565 38.1521 78.133 77.5111H63.6561C34.128 77.5111 10 101.679 10 131.256C10 160.832 34.128 185 63.6561 185H105.248H127.768H161.087H241.514C268.17 185 290 163.134 290 136.434C290 110.886 270.123 89.9402 245.191 87.9837Z" stroke="#9EA3A8" stroke-width="8" stroke-miterlimit="10"/>
                                <path d="M201.162 127.081H147.716C146.817 127.081 146.007 126.9 145.197 126.538C144.387 126.175 143.667 125.722 143.038 125.088C142.408 124.453 141.958 123.728 141.598 122.913C141.238 122.097 141.058 121.191 141.058 120.376V45.7058C141.058 43.8934 141.778 42.2623 143.038 40.9936C144.297 39.7249 146.007 39 147.716 39H178.668C180.647 39 182.447 39.8156 183.797 41.1748L205.931 64.1014C207.28 65.4607 208 67.273 208 69.176V120.285C208 121.191 207.82 122.007 207.46 122.822C207.1 123.638 206.65 124.363 206.02 124.997C205.391 125.631 204.671 126.085 203.861 126.447C202.961 126.9 202.062 127.081 201.162 127.081Z" stroke="#9EA3A8" stroke-width="6" stroke-miterlimit="10"/>
                                <path d="M168.158 164.663H113.775C112.859 164.663 112.035 164.482 111.211 164.119C110.388 163.757 109.655 163.303 109.014 162.669C108.373 162.035 107.916 161.31 107.549 160.494C107.183 159.679 107 158.773 107 157.957V83.2872C107 81.4748 107.732 79.8437 109.014 78.575C110.296 77.3063 112.035 76.5814 113.775 76.5814H145.27C147.284 76.5814 149.115 77.397 150.488 78.7562L173.011 101.683C174.384 103.042 175.116 104.854 175.116 106.757V157.866C175.116 158.773 174.933 159.588 174.567 160.404C174.201 161.219 173.743 161.944 173.102 162.579C172.461 163.213 171.729 163.666 170.905 164.028C169.989 164.482 169.074 164.663 168.158 164.663Z" fill="#23282D" stroke="#9EA3A8" stroke-width="6" stroke-miterlimit="10"/>
                                <path d="M148.105 76.5814V97.401C148.105 99.0163 148.752 100.632 149.955 101.798C151.157 102.965 152.822 103.593 154.488 103.593H175.116L148.105 76.5814Z" fill="#9EA3A8"/>
                                <path d="M182.163 39V59.8196C182.163 61.4349 182.782 63.0502 183.932 64.2168C185.083 65.3834 186.675 66.0116 188.268 66.0116H208L182.163 39Z" fill="#9EA3A8"/>
                                <path d="M131.043 98L117 109.994H125.477V127H130.7H131.3H136.523V109.994H145L131.043 98Z" fill="#9EA3A8"/>
                                <path d="M148.04 148L161 136.386H153.176V120H148.356H147.802H142.903V136.466H135L148.04 148Z" fill="#9EA3A8"/>
                                <line x1="141" y1="77" x2="197" y2="77" stroke="#9EA3A8" stroke-width="4"/>
                                <line x1="154" y1="91" x2="197" y2="91" stroke="#9EA3A8" stroke-width="4"/>
                                <line x1="173" y1="105" x2="197" y2="105" stroke="#9EA3A8" stroke-width="4"/>
                                </svg>
                                '),
				56
			),
			array(
				'submenu',
				$this->plugin_name,
				__('Backup & Migration', 'wp-migration-duplicator'),
				__('Backup & Migration', 'wp-migration-duplicator'), 
				'manage_options',
				$this->plugin_name,
				array($this, 'admin_settings_page')
			),
		);
		$menus = apply_filters('wt_mgdp_admin_menu', $menus);
		if (count($menus) > 0) {
			foreach ($menus as $menu) {
				if ($menu[0] == 'submenu') {
					add_submenu_page($menu[1], $menu[2], $menu[3], $menu[4], $menu[5], $menu[6]);
				} else if ($menu[0] == 'menu') {
					add_menu_page($menu[1], $menu[2], $menu[3], $menu[4], $menu[5], $menu[6], $menu[7]);
				}
			}
		}

		if (function_exists('remove_submenu_page')) {
			//remove_submenu_page(WF_PKLIST_POST_TYPE,WF_PKLIST_POST_TYPE);
		}
	}

	/**
	 * Addding submenu items 
	 * @since 1.1.8
	 */

	function add_sub_menu_items()
	{

		add_submenu_page(
			$this->plugin_name,
			__('WordPress Migration Settings', 'wp-migration-duplicator'),
			__('Settings', 'wp-migration-duplicator'),
			'manage_options',
			$this->plugin_name . '-settings',
			array($this, 'admin_storage_settings')
		);
                add_submenu_page(
			$this->plugin_name,
			__('WordPress Migration Settings', 'wp-migration-duplicator'),
			__('Logs', 'wp-migration-duplicator'),
			'manage_options',
			$this->plugin_name . '-log',
			array($this, 'admin_log_settings')
		);
                add_submenu_page(
			$this->plugin_name,
			__('WordPress Migration Settings', 'wp-migration-duplicator'),
			__('Upgrade to premium', 'wp-migration-duplicator'),
			'manage_options',
			$this->plugin_name . '-premium',
			array($this, 'admin_upgrade_premium_settings')
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Migration_Duplicator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Migration_Duplicator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
             if(Wp_Migration_Duplicator_Security_Helper::wt_mgdp_is_screen_allowed()){
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-migration-duplicator-admin.css', array(), $this->version, 'all');
		//wp_register_style( 'select2css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', false, '1.0', 'all' );
		//wp_enqueue_style( 'select2css' );
            }
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Migration_Duplicator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Migration_Duplicator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if(Wp_Migration_Duplicator_Security_Helper::wt_mgdp_is_screen_allowed()){
		//wp_register_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js', array( 'jquery' ), '1.0', true );
		//wp_enqueue_script( 'select2' );
		$params = array(
			'nonce'    => wp_create_nonce(WT_MGDP_PLUGIN_FILENAME),
			'ajax_url'  => admin_url('admin-ajax.php'),
			'messages' => array(
				'auth_error' => __('Authentication failed', 'wp-migration-duplicator'),
				'no_backups' => __('No backups found', 'wp-migration-duplicator'),
				'select_backup' => __('Please select a backup', 'wp-migration-duplicator'),
                                'loading'=>__('Loading...', 'wp-migration-duplicator'),
                                'mail_msg' => __('Thanks for submitting your feedback!', 'wp-migration-duplicator'),
                                'mail_empty_msg' => __('Email or message content empty', 'wp-migration-duplicator'),
                                'term_error' => __('Terms and conditions not accepted', 'wp-migration-duplicator'),
                                'error' => sprintf(__('An unknown error has occurred! Refer to our %stroubleshooting guide%s for assistance.'), '<a href="'.WT_MGDP_PLUGIN_DEBUG_BASIC_TROUBLESHOOT.'" target="_blank">', '</a>'),
			),
			
		);
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-migration-duplicator-admin.js', array('jquery','select2'), $this->version, false);
		wp_localize_script($this->plugin_name, 'wtMigratorObject', $params);
            }
	}

	/**
	* @since 1.1.2
	* Registers modules: public+admin	 
	*/
	public function admin_modules()
	{
		$wt_mgdp_admin_modules = get_option('wt_mgdp_admin_modules');
		if ($wt_mgdp_admin_modules === false) {
			$wt_mgdp_admin_modules = array();
		}
		foreach (self::$modules as $module) //loop through module list and include its file
		{
			$is_active = 1;
			if (isset($wt_mgdp_admin_modules[$module])) {
				$is_active = $wt_mgdp_admin_modules[$module]; //checking module status
			} else {
				$wt_mgdp_admin_modules[$module] = 1; //default status is active
			}
			$module_file = plugin_dir_path(__FILE__) . "modules/$module/$module.php";
			if (file_exists($module_file) && $is_active == 1) {
				self::$existing_modules[] = $module; //this is for module_exits checking
				require_once $module_file;
			} else {
				$wt_mgdp_admin_modules[$module] = 0;
			}
		}
		$out = array();
		foreach ($wt_mgdp_admin_modules as $k => $m) {
			if (in_array($k, self::$modules)) {
				$out[$k] = $m;
			}
		}
		update_option('wt_mgdp_admin_modules', $out);
	}

	/**
	* @since 1.1.2
	* Sanitize input data 
	*/
	public static function sanitize_array($arr)
	{
		foreach ($arr as $key => $value) {
			$arr[$key] = sanitize_text_field($value);
		}
		return $arr;
	}

	/**
	 * @since 1.1.2 
	 * Envelope settings tab content with tab div.
	 * relative path is not acceptable in view file
	 */
	public static function envelope_settings_tabcontent($target_id, $view_file = "", $html = "", $variables = array(), $need_submit_btn = 0)
	{
		extract($variables);
		?>
		<div class="wf-tab-content" data-id="<?php echo esc_html($target_id); ?>">
			<?php
			if ($view_file != "" && file_exists($view_file)) {
				include_once $view_file;
				do_action('wt_migrator_after_setting_page_content_' . $target_id);
			} else {
				echo wp_kses_post($html);
				do_action('wt_migrator_after_setting_page_content_' . $target_id);
			}
			?>
			<?php
			if ($need_submit_btn == 1) {
				include WT_MGDP_PLUGIN_PATH . "admin/views/admin-settings-save-button.php";
			}
			?>
		</div>
<?php
	}


	/**
	 * helper function for getting storage options for import/export.
	 * @since 1.1.8
	 * @return Array $storage_options
	 */
	public function get_storage_options()
	{
		$storage_options = array(
			'local' => __('Local', 'wp-migration-duplicator')
		);

		return apply_filters('wt_migrator_storage_options', $storage_options);
	}
	/**
	 * Check if cloud storage is authenticated silently
	 * @since 1.1.8
	 * 
	 */
	public function wt_migrator_check_authentication()
	{
            if(strstr($_POST['cloud_storage'], "_schedule")){
               $_POST['cloud_storage'] =  str_replace('_schedule', '', $_POST['cloud_storage']);
            }
		if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME, WT_MGDP_PLUGIN_FILENAME)) {
			wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
		}
		$cloud_storage_id = (isset($_POST['cloud_storage']) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['cloud_storage']) : '');
		$authenticated = apply_filters("wt_migrator_{$cloud_storage_id}_is_authenticated", false);
		if ($authenticated) {
			wp_send_json_success(__('Authentication success!', 'wp-migration-duplicator'));
		}
		wp_send_json_error(__('Authentication failed', 'wp-migration-duplicator'));
	}
	public function wt_migrator_populate_cloud_files() {
		if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME, WT_MGDP_PLUGIN_FILENAME)) {
			wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
		}
		$cloud_storage_id = (isset($_POST['cloud_storage']) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['cloud_storage']) : '');
		$backup_files = apply_filters("wt_migrator_{$cloud_storage_id}_load_backups", false);
		
		if ( $backup_files ) {
			wp_send_json_success( $backup_files );
		}
		wp_send_json_error(__('No backups found', 'wp-migration-duplicator'));
	}
        public function wp_mgdp_populate_popup() {

            $log_file_name=(isset($_POST['log_file']) ? sanitize_text_field($_POST['log_file']) : '');
			if($log_file_name!="")
			{
				$ext_arr=explode(".", $log_file_name);
				$ext=end($ext_arr);
				if($ext=='log')
				{
					$log_file_path=Wp_Migration_Duplicator::$backup_dir . "/logs/".$log_file_name;

					if(file_exists($log_file_path))
					{
                                                $file_pointer=@fopen($log_file_path, 'r');

                                                if(!is_resource($file_pointer))
                                                {
                                                        return $out;
                                                }
                                                $data=fread($file_pointer, filesize($log_file_path));

                                                fclose($file_pointer);

						$out['status']=1;
						$out['html']='<div class="wt_iew_raw_log">'.nl2br(esc_html__($data)).'</div>';
					}
				}
			}

            wp_send_json_success( $out ); 
	}
        
        public function wp_mgdp_populate_feedback() {
            
            $email = 'support@webtoffee.com';
            $subject = 'WordPress Backup & Migration (Basic) - customer issue';
            $message = 'Customer Email :'. $_POST['email'].' - Issue explanation :'.sanitize_text_field($_POST['message']);
            $headers = '';
            $mail_attachment = get_attached_file(sanitize_text_field($_POST['file']));
            wp_mail( $email, $subject, $message,$headers,$mail_attachment );
            $out['status']=1;
            wp_send_json_success( $out );
            
        }
        
                public function mgdp_plugin_file_tree() {
                                  $cron_settings = get_option('wt_mgdp_cron_settings', null);
                                   $ftree_data =  !empty($cron_settings)&& isset($cron_settings['data'])&& !empty($cron_settings['data'])? unserialize($cron_settings['data']):'';
                                    if($ftree_data && isset($ftree_data['exclude'])&& !empty($ftree_data['exclude'])){
                                      $out['data']=$ftree_data['exclude'];  
                                    }
            $out['status']=1;
            wp_send_json_success( $out );
            
        }
        
        
        
	/**
	 *  	Download file via a nonce URL
	 *	@since 1.1.6
	 */
	public function download_file()
	{
		if (isset($_GET['wt_mgdp_download'])) {
			if (Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_POST_TYPE)) { /* check nonce and role */ 
				$file_name = (isset($_GET['file']) ? sanitize_text_field($_GET['file']) : '');
				if ($file_name != "") {
					$file_arr = explode(".", $file_name);
					$file_ext = end($file_arr);
					if ($file_ext == 'zip') /* only zip files */ {
						$file_path = Wp_Migration_Duplicator::$backup_dir . '/' . $file_name;
						if (file_exists($file_path)) /* check existence of file */ {
                                                        ob_clean();
                                                        @set_time_limit(16000);
                                                        @ini_set('max_execution_time', '259200');
                                                        @ini_set('max_input_time', '259200');
                                                        @ini_set('session.gc_maxlifetime', '1200');
                                                        @ini_set('memory_limit', '-1');
                                                        if (strlen(session_id()) > 0) session_write_close();

                                                        if (@ini_get('zlib.output_compression'))
                                                            @ini_set('zlib.output_compression', 'Off');
							 ob_end_clean();
                                                        $fp = @fopen($file_path, 'rb');

							header('Pragma: public');
							header('Expires: 0');
							header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
							header('Cache-Control: private', false);
							header('Content-Transfer-Encoding: binary');
							header('Content-Disposition: attachment; filename="' . $file_name . '";');
							header('Content-Type: application/zip');
							header('Content-Length: ' . filesize($file_path));
                                                        http_response_code(200);

                                                        if (ob_get_level()) ob_end_clean();

                                                        fpassthru($fp);
                                                        fclose($fp);

							/*$chunk_size = 1024 * 1024;
							$handle = @fopen($file_path, 'rb');
							while (!feof($handle)) {
								$buffer = fread($handle, $chunk_size);
								echo $buffer;
								ob_flush();
								flush();
							}
							fclose($handle);*/
							exit();
						}
					}
				}
			}
			else {
				wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
			}
		}
	}
        
        /**
	*  	Download log file via a nonce URL
	*/
	public function log_download_file()
	{
		if(isset($_GET['wt_mgdp_log_download']))
		{ 
			if (Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_POST_TYPE)) { /* check nonce and role */ 
				$file_name=(isset($_GET['file']) ? sanitize_text_field($_GET['file']) : '');
				if($file_name!="")
				{
					$file_arr=explode(".", $file_name);
					$file_ext=end($file_arr);
					if($file_ext=='log') /* Only allowed files. */
					{
						$file_path=Wp_Migration_Duplicator::$backup_dir . "/logs/".$file_name;
						if(file_exists($file_path) && is_file($file_path))
						{	
                                                    $file_name = str_replace(".log",".txt",$file_name);
                                                    header('Pragma: public');
						    header('Expires: 0');
						    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						    header('Cache-Control: private', false);
						    header('Content-Transfer-Encoding: binary');
						    header('Content-Disposition: attachment; filename="'.$file_name.'";');
						    header('Content-Description: File Transfer');
						    header('Content-Type: application/octet-stream');
						    //header('Content-Length: '.filesize($file_path));

						    $chunk_size=1024 * 1024;
						    $handle=@fopen($file_path, 'rb');
						    while(!feof($handle))
						    {
						        $buffer = fread($handle, $chunk_size);
						        echo esc_attr($buffer);
						        ob_flush();
						        flush();
						    }
						    fclose($handle);
						    exit();

						}
					}
				}	
			}
		}
                
                /* delete action */
		if(isset($_GET['wt_mgdp_log_delete'])) 
		{
			if (Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_POST_TYPE)) { /* check nonce and role */ 
				$log_file_name=(isset($_GET['file']) ? sanitize_text_field($_GET['file']) : '');
				if($log_file_name!="")
				{
					$ext_arr=explode(".", $log_file_name);
					$ext=end($ext_arr);
					if($ext=='log')
					{
						$log_file_path=Wp_Migration_Duplicator::$backup_dir . "/logs/".$log_file_name;
						if(file_exists($log_file_path) && is_file($log_file_path))
						{
							@unlink($log_file_path);
						}
					}
				}			
			}
                        wp_redirect(admin_url('admin.php?page=wp-migration-duplicator-log'));
		}
	}

	/**
	 *  	Generate nonce URL for backup file
	 *	@since 1.1.6
	 *	@param string $file_name name of the file to be downloaded
	 */
	public static function generate_backup_file_url($file_name)
	{
		return wp_nonce_url(admin_url('admin.php?wt_mgdp_download=true&file=' . $file_name), WT_MGDP_POST_TYPE);
	}
        
        /**
	 * Ensures fatal errors are logged so they can be picked up in the status report.
	 *
	 * @since 1.2.3
	 */
	public function wt_log_errors() {
		$error = error_get_last();
		//if ( $error && in_array( $error['type'], array( E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR, E_CORE_ERROR,E_DEPRECATED,E_USER_DEPRECATED ), true )  ) {
                if ( $error && in_array( $error['type'], array( E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR ), true ) ) {   
                    $msg = $error['message'].' in '.$error['file'].' on line '. $error['line'];
                    Webtoffe_logger::write_log( 'Fatal',$msg );
		
		}
	}
        
        public function change_ftp_table_structure()
        {
            global $wpdb;
            if(floatval(WP_MIGRATION_DUPLICATOR_VERSION) == floatval('1.3.0')){
                $tb='wt_mgdp_ftp';
                $table_name = $wpdb->prefix.$tb;
                if(!$this->wt_table_column_exists($table_name,'ftps')){
                       $wpdb->query("ALTER TABLE `{$table_name}` ADD `ftps` INT(11) NOT NULL DEFAULT 0 AFTER `port`; ");
                }
                if(!$this->wt_table_column_exists($table_name,'is_sftp')){
                    $wpdb->query("ALTER TABLE `{$table_name}` ADD `is_sftp` INT(11) NOT NULL DEFAULT 0 AFTER `ftps`; ");
                }
                if(!$this->wt_table_column_exists($table_name,'passive_mode')){
                    $wpdb->query("ALTER TABLE `{$table_name}` ADD `passive_mode` INT(11) NOT NULL DEFAULT 0 AFTER `is_sftp`; ");
                }
            }
            
        }
        
        public function wt_table_column_exists($table_name, $column_name)
        {
            global $wpdb;

            $column = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
                DB_NAME,
                $table_name,
                $column_name
            ));

            if (!empty($column)) {
                return true;
            }

            return false;
        }
}
