<?php
class BeRocket_compare_products_grey_gradient extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'compare_products';
    public $css_file_name = 'berocket-grey-gradient';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Grey Gradient',
            'image'         => plugins_url( '/grey-gradient.png', __FILE__ ),
            'class'         => 'grey-gradient'
        ) );
    }
}

new BeRocket_compare_products_grey_gradient();
