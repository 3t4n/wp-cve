<?php

class TPUL_Modal_Options {

    private $options = false;
    private $options_name = 'tpul_settings_term_modal_options';

    private $defaults = array(
        'terms_modal_pageid'           =>    '',
        'terms_modal_content'                  =>    '',
        'terms_modal_title'               =>    'Terms and Conditions',
        'terms_modal_subtitle'           =>    'You must read and scroll down to accept it.',
        'terms_modal_logout_text'       =>    'You are being Logged out..',
        'terms_modal_agreed_text'       =>    'You have agreed to terms.',
        'terms_modal_accept_button'       =>    'Accept',
        'terms_modal_decline_button'   =>    'Decline and Log Out',
        'terms_modal_decline_nologout' =>    0,
        'terms_modal_show_only_once' =>    0,
        'terms_modal_show_every_login' =>    0,
        'terms_modal_show_every_login_for_declined' =>    0,
        'terms_modal_accept_redirect'  =>    '',
        'terms_modal_decline_redirect' =>    '/',
        'terms_modal_disable_for_new'  =>    0,
        'terms_modal_for_roles'        =>  array(),
        'terms_modal_font_size' =>    '',
        'terms_modal_asset_placement' =>    '',
        'terms_modal_track_IP' =>    0,
        'terms_modal_track_location' =>    0,
        'terms_modal_txt_logger' =>    0,
        'terms_modal_designated_test_user' =>    false,
    );

    public function __construct() {
        $this->options = get_option($this->options_name);
    }

    public function default_options() {
        return $this->defaults;
    }

    public function get_accept_redirect_url() {
        if (!empty($this->options['terms_modal_accept_redirect'])) {
            return $this->options['terms_modal_accept_redirect'];
        }
        return '';
    }

    public function get_decline_redirect_url() {
        if (!empty($this->options['terms_modal_decline_redirect'])) {
            return $this->options['terms_modal_decline_redirect'];
        }
        return '';
    }

    public function get_show_only_once() {
        if (!empty($this->options['terms_modal_show_only_once'])) {
            return $this->options['terms_modal_show_only_once'];
        }
        return 0;
    }
    public function get_track_location() {
        if (!empty($this->options['terms_modal_track_location'])) {
            return $this->options['terms_modal_track_location'];
        }
        return 0;
    }
    public function get_txt_logger() {
        if (!empty($this->options['terms_modal_txt_logger'])) {
            return $this->options['terms_modal_txt_logger'];
        }
        return 0;
    }
    public function get_track_IP() {
        if (!empty($this->options['terms_modal_track_IP'])) {
            return $this->options['terms_modal_track_IP'];
        }
        return 0;
    }

    public function get_options() {
        if (false ==  $this->options) {
            return $this->default_options();
        }
        return $this->options;
    }

    public function is_designated_test_user() {
        $is_designated_test_user = false;

        if (!empty($this->options['terms_modal_designated_test_user'])) {
            $is_designated_test_user = (get_current_user_id() == $this->options['terms_modal_designated_test_user']) ? true : false;
        } else {
            $is_designated_test_user = false;
        }


        return $is_designated_test_user;
    }
}
