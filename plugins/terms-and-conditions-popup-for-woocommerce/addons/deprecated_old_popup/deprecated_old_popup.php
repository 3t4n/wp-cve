<?php
class BeRocket_terms_deprecated_old_popup_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'terms_cond_popup';
    public $php_file_name   = 'popup';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Old Popup<br><small style="font-size:14px;color:red;">DEPRECATED</small>', 'terms-and-conditions-popup-for-woocommerce'),
            'image'         => plugins_url('/old-popup.png', __FILE__),
            'deprecated'    => true,
            'tooltip'       => __('<span style="color: red;">DO NOT USE<br>IT WILL BE REMOVED IN THE FUTURE</span><br>Uses for compatibility with old style popup', 'terms-and-conditions-popup-for-woocommerce')
        ));
    }
}
new BeRocket_terms_deprecated_old_popup_addon();
