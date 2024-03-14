<?php
class BeRocket_image_watermark_media_buttons_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'image_watermark';
    public $php_file_name   = 'media_buttons_include';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Media Library Buttons', 'product-watermark-for-woocommerce'),
            'tooltip'       => __('Buttons for WordPress Media Library to add watermark to image or restore image', 'product-watermark-for-woocommerce'),
            'image'         => plugins_url( '/media-buttons.png', __FILE__ )
        ));
    }
}
new BeRocket_image_watermark_media_buttons_addon();
