<?php
if ( ! class_exists( 'SME_License_Manager_Client' ) ) {
 
    class SME_License_Manager_Client {
     /**
		 * The API endpoint. Configured through the class's constructor.
		 *
		 * @var String  The API endpoint.
		 */
		private $api_endpoint;
		 
		/**
		 * The product id (slug) used for this product on the License Manager site.
		 * Configured through the class's constructor.
		 *
		 * @var int     The product id of the related product in the license manager.
		 */
		private $product_id;
		 
		/**
		 * The name of the product using this class. Configured in the class's constructor.
		 *
		 * @var int     The name of the product (plugin / theme) using this class.
		 */
		private $product_name;
		 
		/**
		 * The type of the installation in which this class is being used.
		 *
		 * @var string  'theme' or 'plugin'.
		 */
		private $type;
		 
		/**
		 * The text domain of the plugin or theme using this class.
		 * Populated in the class's constructor.
		 *
		 * @var String  The text domain of the plugin / theme.
		 */
		private $text_domain;
		 
		/**
		 * @var string  The absolute path to the plugin's main file. Only applicable when using the
		 *              class with a plugin.
		 */
		private $plugin_file;
		
		/**
		 * Initializes the license manager client.
		 *
		 * @param $product_id   string  The text id (slug) of the product on the license manager site
		 * @param $product_name string  The name of the product, used for menus
		 * @param $text_domain  string  Theme / plugin text domain, used for localizing the settings screens.
		 * @param $api_url      string  The URL to the license manager API (your license server)
		 * @param $type         string  The type of project this class is being used in ('theme' or 'plugin')
		 * @param $plugin_file  string  The full path to the plugin's main file (only for plugins)
		 */
		public function __construct( $product_id, $product_name, $text_domain, $api_url,
									 $type = 'theme', $plugin_file = '' ) {
				// Store setup data
				$this->product_id = $product_id;
				$this->product_name = $product_name;
				$this->text_domain = $text_domain;
				$this->api_endpoint = $api_url;
				$this->type = $type;
				$this->plugin_file = $plugin_file;
				// Add a nag text for reminding the user to save the license information
				add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );		
				// Check for updates (for plugins)
				add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_update' ) );				
		}
		/**
 * @return string   The name of the settings field storing all license manager settings.
 */
protected function get_settings_field_name() {
    return 'SME_License';
}
 
/**
 * @return string   The slug id of the licenses settings page.
 */
protected function get_settings_page_slug() {
    return 'smugmug-embed-plugin-settings';
}
		/**
 * If the license has not been configured properly, display an admin notice.
 */
public function show_admin_notices() {
	$SME_api_progress = get_option('SME_api_progress');
	$verified = $SME_api_progress== "Verified" ?true :false;
	if (!$verified) {
        $msg = __( 'SmugMug Embed needs to be authorized to communicate with your SmugMug account. Please finish setup in the  <a href="'.admin_url( "options-general.php?page=" . $this->get_settings_page_slug() ) .'">settings page.');
        ?>
            <div  class="notice notice-error is-dismissible">
                <p>
                    <?php echo $msg; ?>
                </p>

            </div>
        <?php
	}
    $options = get_option( $this->get_settings_field_name() );
	
    if (false ===  ($value = get_transient("sme_license_reminder")) && ( !$options || ! isset( $options['license_email'] ) || ! isset( $options['LicenseExpiration'] )|| ! isset( $options['license_key'] ) || $options['license_email'] == '' || $options['license_key'] == '' || $options['LicenseExpiration'] == '' )) {
 
        $msg = __( 'We hope you are enjoying SmugMug Embed. To unlock the full version and receive updates and premium support, please visit the <a href="https://www.wicklundphotography.com/smugmug-embed-wordpress-plugin/">plugin homepage.</a>');
        $msg = sprintf( $msg, $this->product_name );
        ?>
            <div  class="notice notice-success is-dismissible">
                <p>
                    <?php echo $msg; ?>
                </p>

            </div>
        <?php
		set_transient('sme_license_reminder','license_reminder',60*60*24*3);
		
    }
}
//
// API HELPER FUNCTIONS
//
 
/**
 * Makes a call to the WP License Manager API.
 *
 * @param $method   String  The API action to invoke on the license manager site
 * @param $params   array   The parameters for the API call
 * @return          array   The API response
 */
private function call_api( $action, $params ) {
    $url = $this->api_endpoint . '/' . $action;
    // Append parameters for GET request
    $url .= '?' . http_build_query( $params );
 
    // Send the request
    $response = wp_remote_get( $url );
    if ( is_wp_error( $response ) ) {
        error_log(print_r($response, true));
        return false;
    }
         
    $response_body = wp_remote_retrieve_body( $response );
    $result = json_decode( $response_body );
     
    return $result;
}
/**
 * Checks the API response to see if there was an error.
 *
 * @param $response mixed|object    The API response to verify
 * @return bool     True if there was an error. Otherwise false.
 */
private function is_api_error( $response ) {
    if ( $response === false ) {
        return true;
    }
 
    if ( ! is_object( $response ) ) {
        return true;
    }
 
    if ( isset( $response->error ) ) {
        return true;
    }
 
    return false;
}
/**
 * Calls the License Manager API to get the license information for the
 * current product.
 *
 * @return object|bool   The product data, or false if API call fails.
 */
public function get_license_info() {
    $options = get_option( $this->get_settings_field_name() );
	$str = preg_replace('#^https?://#', '', get_home_url());
	$str = preg_replace('#^https?://#', '', $str);		
    if ( ! isset( $options['license_email'] ) || ! isset( $options['license_key'] ) ) {
		$str = preg_replace('#^https?://#', '', get_home_url());
		$str = preg_replace('#^https?://#', '', $str);		
		$this->call_api('demo',array('h' => $str));
        // User hasn't saved the license to settings yet. No use making the call.
        return false;
    }

    $info = $this->call_api(
        'info',
        array(
            'p' => $this->product_id,
            'e' => $options['license_email'],
            'l' => $options['license_key'],
			'h'=> $str
        )
    );
    return $info;
}
/**
 * Checks the license manager to see if there is an update available for this theme.
 *
 * @return object|bool  If there is an update, returns the license information.
 *                      Otherwise returns false.
 */
public function is_update_available() {
    $license_info = $this->get_license_info();
    if ( $this->is_api_error( $license_info ) ) {
        return false;
    }
 
    if ( version_compare( $license_info->version, $this->get_local_version(), '>' ) ) {
        return $license_info;
    }
 
    return false;
}
/**
 * @return string   The theme / plugin version of the local installation.
 */
private function get_local_version() {
        $plugin_data = get_plugin_data( $this->plugin_file, false );
        return $plugin_data['Version'];
}

/**
 * The filter that checks if there are updates to the theme or plugin
 * using the License Manager API.
 *
 * @param $transient    mixed   The transient used for WordPress theme updates.
 * @return mixed        The transient with our (possible) additions.
 */
public function check_for_update( $transient ) {
    if ( empty( $transient->checked ) ) {
        return $transient;
    }
 
    if ( $this->is_update_available() ) {
        $info = $this->get_license_info();
		// Plugin update
		$plugin_slug = plugin_basename( $this->plugin_file );

		$transient->response[$plugin_slug] = (object) array(
			'new_version' => $info->version,
			'package' => $info->package_url,
			'slug' => $plugin_slug
		);
    }
    return $transient;
}
	}
    
 
}