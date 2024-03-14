<?php

namespace WP_Rplg_Google_Reviews\Includes\Admin;

class Admin_Rateus_Ajax {

    public function __construct() {
        add_action('wp_ajax_grw_rateus_ajax', array($this, 'rateus_ajax'));
        add_action('wp_ajax_grw_rateus_ajax_feedback', array($this, 'rateus_ajax_feedback'));
    }

    public function rateus_ajax() {
        $rate = trim(sanitize_text_field(wp_unslash($_POST['rate'])));
        update_option('grw_rate_us', time() . ':' . $rate);
        echo json_encode(array('rate' => $rate));

        die();
    }

    public function rateus_ajax_feedback() {
        $rate  = trim(sanitize_text_field(wp_unslash($_POST['rate'])));
        $email = trim(sanitize_text_field(wp_unslash($_POST['email'])));
        $msg   = trim(sanitize_text_field(wp_unslash($_POST['msg'])));
        update_option('grw_rate_us', time() . ':' . $rate);

        $request = wp_remote_post('https://admin.richplugins.com/plugins/feedback', array(
            'timeout'   => 15,
            'sslverify' => false,
            'body'      => array(
                'rate'  => $rate,
                'email' => $email,
                'msg'   => $msg
            )
        ));
        echo json_encode(array('rate' => $rate, 'email' => $email, 'msg' => $msg));

        die();
    }
}
