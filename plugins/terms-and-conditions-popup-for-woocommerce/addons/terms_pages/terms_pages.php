<?php
class BeRocket_terms_pages_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'terms_cond_popup';
    public $php_file_name   = 'custom_post_terms';
    function init_active() {
        $pluginpath = dirname(BeRocket_terms_cond_popup_file);
        require_once($pluginpath . '/libraries/post_conditions.php');
        require_once($pluginpath . '/libraries/custom_post_type.php');
        parent::init_active();
    }
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Terms Pages', 'terms-and-conditions-popup-for-woocommerce'),
            'image'         => plugins_url('/terms.png', __FILE__),
            'tooltip'       => __('Replace Terms and Conditions Page with other page using Conditions by:
            <ol style="text-align:left;">
                <li>User Role</li>
                <li>User Status</li>
                <li>Shipping Zone</li>
                <li>Customer Country</li>
                <li>Day of the Week</li>
                <li>Products in Cart</li>
            </ol>', 'terms-and-conditions-popup-for-woocommerce')
        ));
    }
}
new BeRocket_terms_pages_addon();
