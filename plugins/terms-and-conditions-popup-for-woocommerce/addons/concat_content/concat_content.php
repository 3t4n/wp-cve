<?php
class BeRocket_terms_POPUPfilter_concat_content_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'terms_cond_popup';
    public $php_file_name   = 'concat_content_include';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Concat Content', 'terms-and-conditions-popup-for-woocommerce'),
            'tooltip'       => __('Use one popup for both "Terms and Conditions" and "Privacy Policy".<br>Text of both popup will be in one popup one after other', 'terms-and-conditions-popup-for-woocommerce'),
            'image'         => plugins_url( '/concat-content.png', __FILE__ )
        ));
    }
}
new BeRocket_terms_POPUPfilter_concat_content_addon();
