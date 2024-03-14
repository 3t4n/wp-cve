<?php
class BeRocket_compare_products_simple_and_nice extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'compare_products';
    public $css_file_name = 'berocket-simple-and-nice';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Simple &amp; Nice',
            'image'         => plugins_url( '/simple-and-nice.png', __FILE__ ),
            'class'         => 'simple-and-nice'
        ) );
    }
}

new BeRocket_compare_products_simple_and_nice();
