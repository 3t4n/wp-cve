<?php
    add_action('init','cfafwr_init_save');
    function cfafwr_init_save() {
        global $cfafwr_comman;
        $optionget = array(
            'cfafwr_enable_plugin' => 'yes',
            'cfafwr_user_email_sent' => 'yes',
            // 'cfafwr_user_email_subject_msg' => 'Your account has been created succefully.',
            // 'cfafwr_user_email_body_msg' => 'Thanks for creating an account on {site_name}.',
            'cfafwr_hide_field_labels' => 'no',
            'cfafwr_show_field_register' => 'register_form_start',
            'cfafwr_login_reg_change_text' => 'yes',
            'cfafwr_login_change_text' => 'Login',
            'cfafwr_reg_change_text' => 'Register',
            'cfafwr_field_label_require_text' => '{field_label} is required!',
            'cfafwr_state_field' => '',
            'cfafwr_state_field_required' => '',
            'cfafwr_state_field_label' => 'Your State',
            'cfafwr_state_field_slug' => 'your_state',
            'state_field_size' => 'full_width',
            'cfafwr_myac_tab_title' => 'Registration Fields',
            'cfafwr_myac_tab_form_head' => 'Registration Fields',
        );

        foreach ($optionget as $key_optionget => $value_optionget) {
            $cfafwr_comman[$key_optionget] = get_option( $key_optionget,$value_optionget );
        }
    }