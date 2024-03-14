<?php
class BeRocket_compare_products_sweet_alert extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'compare_products';
    public $css_file_name = 'berocket-sweet-alert';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Sweet Alert',
            'image'         => plugins_url( '/sweet-alert.png', __FILE__ ),
            'class'         => 'sweet-alert',
        ) );
    }
}

new BeRocket_compare_products_sweet_alert();
