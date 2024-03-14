<?php
/*
  Plugin Name: Woocommerce autocomplete checkout address
  Description: Autocomplete address field on checkout page using the Google Maps API.
  Author: Acespritech Solutions Pvt. Ltd.
  Author URI: https://acespritech.com/
  Version: 1.1.0
  Domain Path: /languages/
 */
/* Load plugin if WooCommerce plugin is activated, then check if API key has been saved */

function ace_waca_auto_complete_init () {
    if (class_exists( 'WooCommerce' )) {
        if( get_option( 'api_key' ) ) {
            add_action('wp_footer', 'ace_waca_auto_complete_scripts');
        }else{
            add_action( 'admin_notices', 'ace_waca_auto_complete_missing_key_notice' );
        }
    }else{
        add_action( 'admin_notices', 'ace_waca_auto_complete_missing_wc_notice' );
    }
}
add_action( 'init', 'ace_waca_auto_complete_init' );

function ace_waca_auto_complete_missing_key_notice() {
    ?>
    <div class="update-nag notice">
        <p><?php _e( 'For Enable Autocomplete Checkout Address for WooCommerce Please <a href="options-general.php?page=auto_complete">enter your Google Maps API Key</a>', 'checkout-address-autocomplete-for-woocommerce' ); ?></p>
    </div>
    <?php
}

/* Load Frontend Javascripts */

function ace_waca_auto_complete_scripts() {
    if(is_checkout() || is_account_page()){
        if(get_option('enqueue_map_js')==true){
            wp_enqueue_script('google-autocomplete', 'https://maps.googleapis.com/maps/api/js?libraries=places&key='.get_option( 'api_key' ));
            wp_enqueue_script('auto-complete', plugin_dir_url( __FILE__ ) . 'autocomplete.js');
        }else{
            ace_waca_auto_complete_google_maps_script_loader();
        }
    }
}

function ace_waca_auto_complete_google_maps_script_loader() {
    global $wp_scripts; $gmapsenqueued = false;
    foreach ($wp_scripts->queue as $key) {
        if(array_key_exists($key, $wp_scripts->registered)) {
            $script = $wp_scripts->registered[$key];
            if (preg_match('#maps\.google(?:\w+)?\.com/maps/api/js#', $script->src)) {
                $gmapsenqueued = true;
            }
        }
    }

    if (!$gmapsenqueued) {
        wp_enqueue_script('google-autocomplete', 'https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key='.get_option( 'api_key' ));
    }
    wp_enqueue_script('auto-complete', plugin_dir_url( __FILE__ ) . 'autocomplete.js');
}

/* Admin Error Messages */
function ace_waca_auto_complete_missing_wc_notice() {
    ?>
    <div class="error notice">
        <p><?php _e( 'You need to install and activate WooCommerce in order to use Autocomplete Checkout Address WooCommerce!', 'checkout-address-autocomplete-for-woocommerce' ); ?></p>
    </div>
    <?php
}

/* Admin Settings Menu */
function ace_waca_auto_complete_menu(){
    add_submenu_page( 'woocommerce', 'Autocomplete Checkout Address', 'Autocomplete Checkout Address', 'manage_options', 'ace_waca_auto_complete_page', 'ace_waca_auto_complete_page' ); 
    add_action( 'admin_init', 'ace_waca_update_auto_complete' );
}
add_action( 'admin_menu', 'ace_waca_auto_complete_menu' , 99);

/* Admin Settings Page */
function ace_waca_auto_complete_page(){
    ?>
    <div class="wrap">
        <h1>Autocomplete Address for WooCommerce checkout fields</h1>
        <p>For enable autocomplete address on checkout page paste your API key below and click "Save Changes".</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'auto-complete-settings' ); ?>
            <?php do_settings_sections( 'auto-complete-settings' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Google Maps API Key:</th>
                    <td><input type="text" name="api_key" style="width: 25em; padding: 3px 5px;" placeholder="Please enter your Goole map API key" value="<?php echo get_option( 'api_key' ); ?>"/>&nbsp;<a href="https://cloud.google.com/maps-platform/#get-started" target="_blank">Click here to get your "Places" API Key</a></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Force Enqueue Google Maps JS:</th>
                    <td><input type="checkbox" name="enqueue_map_js" value="true" <?php if(get_option('enqueue_map_js')==true)echo 'checked'; ?>/></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/* Save Plugin Settings (API Key) */
add_filter( 'woocommerce_get_settings_checkout', 'ace_waca_checkout_settings', 10, 2 );
function ace_waca_checkout_settings( $settings) {
    $updated_settings = array();
    foreach ($settings as $section) {   
        if (isset($section['id']) && 'woocommerce_enable_coupons' == $section['id']) {
            $updated_settings[] = array(
                    'name' => __('Gift Wrap Charges', 'Gift Wrap Charges'),
                    'id' => 'giftproduct_charge',
                    'type' => 'text',
                    'css' => 'min-width:300px;',
                    'desc' => __(''),
                );
        }
        $updated_settings[] = $section;
    }
    return $updated_settings;
}

function ace_waca_update_auto_complete() {
    register_setting( 'auto-complete-settings', 'api_key' );
    register_setting( 'auto-complete-settings', 'enqueue_map_js' );
}
