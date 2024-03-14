<?php
class BeRocket_terms_cond_nice_green extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'terms_cond_popup';
    public $css_file_name = 'berocket-nice-green';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Nice Green',
            'image'         => plugins_url( '/nice-green.png', __FILE__ ),
            'class'         => 'nice-green'
        ) );
    }
}

new BeRocket_terms_cond_nice_green();
