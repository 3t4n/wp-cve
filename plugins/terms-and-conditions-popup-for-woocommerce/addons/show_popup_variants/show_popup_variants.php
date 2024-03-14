<?php
class BeRocket_terms_show_popup_variants_addon extends BeRocket_framework_addon_lib {
    public $addon_file      = __FILE__;
    public $plugin_name     = 'terms_cond_popup';
    public $php_file_name   = 'variants_add';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Popup Show Variants', 'terms-and-conditions-popup-for-woocommerce'),
            'image'         => plugins_url('/popup-variants.png', __FILE__),
            'tooltip'       => __('Additional variant of showing popup: 
            <ol style="text-align:left;">
                <li>Open popup on page load</li>
                <li>Open popup after scrolling by some amount of px</li>
                <li>Open popup after scrolling to some block on checkout</li>
            </ol>', 'terms-and-conditions-popup-for-woocommerce')
        ));
    }
}
new BeRocket_terms_show_popup_variants_addon();
