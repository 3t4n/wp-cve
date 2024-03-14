<?php
class BeRocket_terms_cond_simple_shadow extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'terms_cond_popup';
    public $css_file_name = 'berocket-simple-shadow';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Simple Shadow',
            'image'         => plugins_url( '/simple-shadow.png', __FILE__ ),
            'class'         => 'simple-shadow'
        ) );
    }
}

new BeRocket_terms_cond_simple_shadow();
