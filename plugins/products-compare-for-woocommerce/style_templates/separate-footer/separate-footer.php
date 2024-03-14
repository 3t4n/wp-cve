<?php
class BeRocket_compare_products_separate_footer extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'compare_products';
    public $css_file_name = 'berocket-separate-footer';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Separate Footer',
            'image'         => plugins_url( '/separate-footer.png', __FILE__ ),
            'class'         => 'separate-footer'
        ) );
    }
}

new BeRocket_compare_products_separate_footer();
