<?php
class BeRocket_compare_products_nice_blue extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'compare_products';
    public $css_file_name = 'berocket-nice-blue';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Nice Blue',
            'image'         => plugins_url( '/nice-blue.png', __FILE__ ),
            'class'         => 'nice-blue',
        ) );
    }
}

new BeRocket_compare_products_nice_blue();
