<?php
class BeRocket_terms_cond_full_footer_buttons extends BeRocket_framework_template_lib {
    public $template_file = __FILE__;
    public $plugin_name   = 'terms_cond_popup';
    public $css_file_name = 'berocket-full-footer-buttons';

    function get_template_data() {
        $data = parent::get_template_data();

        return array_merge( $data, array(
            'template_name' => 'Full Footer Buttons',
            'image'         => plugins_url( '/full-footer-buttons.png', __FILE__ ),
            'class'         => 'full-footer-buttons'
        ) );
    }
}

new BeRocket_terms_cond_full_footer_buttons();
