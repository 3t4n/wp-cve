<?php
class BeRocket_terms_cond_simple_and_nice extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'terms_cond_popup';
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

new BeRocket_terms_cond_simple_and_nice();
