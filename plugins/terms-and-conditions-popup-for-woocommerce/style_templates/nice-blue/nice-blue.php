<?php
class BeRocket_terms_cond_nice_blue extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'terms_cond_popup';
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

new BeRocket_terms_cond_nice_blue();
