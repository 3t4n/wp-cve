<?php
/* Main Plugin Class*/
class cas_woocommerce {

    private static $plugin_url;
    private static $plugin_dir;
    public function __construct()
    {
       global $cas_plugin_dir, $cas_plugin_url;
        /* plugin url and directory variable */
        self::$plugin_dir = $cas_plugin_dir;
        self::$plugin_url = $cas_plugin_url;
		
		add_action( 'init', array($this,'cas_rkc_init'));
	}
	public function cas_rkc_init(){
		if (class_exists( 'WooCommerce' )) {
			if( get_option( 'cas_google_key' ) ) {
				add_action('wp_footer', array($this,'cas_rkc_add_scripts'));
			}
			else{
				  add_action( 'admin_notices', array($this,'cas_rkc_missing_key_notice' ));
			}
			/* displaying menu under woocommerce menu */
			add_action( 'admin_menu',  array($this,'cas_rkc_show_menu') );
		} else{
			add_action( 'admin_notices', array($this,'cas_rkc_missing_woocommerce_notice' ));
		}
	}
	
	/*
		Load Google Api Javascript and Sugession javascript
	*/
	
	public function cas_rkc_add_scripts() {
		if(is_checkout() || is_account_page()){
			wp_enqueue_script('cas-google-autocomplete', 'https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key='.get_option( 'cas_google_key' ));
			wp_enqueue_script('cas-autocomplete', self::$plugin_url . 'assets/checkout-address-sugessions.js');
		}
	}

	/*
		Admin Side Error Message
	*/
	
	public function cas_rkc_missing_woocommerce_notice() {
	  ?>
	  <div class="error notice">
		  <p><?php _e( 'You need to install and activate WooCommerce in order to use Checkout Address Autocomplete WooCommerce!', 'checkout-address-sugessions-for-woocommerce' ); ?></p>
	  </div>
	  <?php
	}
	public function cas_rkc_missing_key_notice() {
	  ?>
	  <div class="update-nag notice">
		  <p><?php _e( 'Please <a href="admin.php?page=cas_rkc_googlemap">enter your Google Maps Javascript API Key</a> in order to use Checkout Address Autocomplete for WooCommerce!', 'checkout-address-autocomplete-for-woocommerce' ); ?></p>
	  </div>
	  <?php
	}

	/* 
		Admin Settings Menu
	*/
	public function cas_rkc_show_menu(){
	  add_submenu_page( 'woocommerce', 'Checkout Address Sugessions for WooCommerce',
					'Checkout Address Sugessions', 
					'manage_options', 
					'cas_rkc_googlemap', 
					array($this,'cas_rkc_googlemap_page'), 
					'dashicons-location', 
					1000 );
	  add_action( 'admin_init',  array($this,'cas_rkc_menu') );
	}


	/*
		Plugins Admin Settings Page
	*/
	public function cas_rkc_googlemap_page(){
	?>
	<div class="wrap">
	  <h1>Checkout Address Sugessions for WooCommerce</h1>
	  <p>Paste/Type your API key below and click "Save Changes" to enable the Address Sugessions on the WooCommerce checkout page.</p>
	  <p><a href="https://developers.google.com/maps/documentation/javascript/places" target="_blank">Click here to get your API Key &raquo;</a></p>
	  <form method="post" action="options.php">
		<?php settings_fields( 'cas-rkc-settings' ); ?>
		<?php do_settings_sections( 'cas-rkc-settings' ); ?>
		<table class="form-table">
		  <tr valign="top">
		  <th scope="row">Google Maps Javascript<br />API Key:</th>
		  <td><input type="text" name="cas_google_key" value="<?php echo get_option( 'cas_google_key' ); ?>"/></td>
		  </tr>
		</table>
		<?php submit_button(); ?>
	  </form>
	</div>
	<?php
	}
	/*
		Save API Key of Google Maps
	*/
	public function cas_rkc_menu() {
	  register_setting( 'cas-rkc-settings', 'cas_google_key' );
	}
}
$casWoocommerce = new cas_woocommerce();