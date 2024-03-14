<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class myCRED_Learndash_Buy_Pts {

    private $course;
    private $default_button;
    private $options;

    public function __construct() {
        $this->options = get_option('allow_buy_course_pts');
        add_action( 'wp_enqueue_scripts',       array( $this, 'register_scripts' ) );
        add_action( 'admin_enqueue_scripts',    array( $this, 'register_scripts' ) );
        // if ($this->options) {
            add_filter('learndash_payment_button', array($this, 'mycred_payment_button'), 10, 2);
            add_action('wp_ajax_pts_handler', array($this, 'pts_handler'));
        // }
    }

    public function register_scripts() {
        wp_register_script('learndash_mycred_pts_handler', plugin_dir_url(__FILE__) . 'assets/js/learndash-mycred-pts-handler.js', array('jquery'));
        wp_localize_script('learndash_mycred_pts_handler', 'LD_MYCRED_Handler', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('learndash_mycred_pts_handler');
    }

    public function pts_handler() {

        $user_id = get_current_user_id();
        $course_id = absint($_POST['course']);

        $course_meta = get_post_meta($course_id, '_sfwd-courses', true);
        if ($pt_type = get_option('learndash_point_type')) {
            if ($pt_value = get_option('learndash_mycred_exchange_rate')) {
                
                $pt_cost = $course_meta['sfwd-courses_course_price'] / $pt_value;

            } else {
                $pt_cost = $course_meta['sfwd-courses_course_price'] / 1;
            }
        }


        if (mycred_get_users_balance($user_id, $pt_type) >= $pt_cost) {
            $this->pay_by_mycred_pts($user_id, $pt_cost, $pt_type, $course_id);

        } else {

            wp_send_json_error(__('You don\'t have enough points to buy with', 'mycred-learndash'));
        }
    }

    public function pay_by_mycred_pts($user_id, $pt_cost, $pt_type, $course_id) {

        $mycred = new myCRED_Settings();
        //grab function 
        $mycred->add_creds('learndash_payment_pts', $user_id, 0 - $pt_cost, 'learndash Payment By Pts', '', '', $pt_type);
        ld_update_course_access($user_id, $course_id);
        $transaction = array(
            'user_id' => $user_id,
            'course_id' => $course_id,
            'course_title' => get_the_title($course_id),
        );
        $user = get_userdata($user_id);
        $user_email = ( '' != $user->user_email ) ? $user->user_email : '';

        // Log transaction
        $this->mycred_record_transaction($transaction, $course_id, $user_id, $user_email);
        wp_send_json_success(sprintf(__('Success Grabbing %d points', 'mycred-learndash'), $pt_cost));
    }

    /**
     * Record transaction in database
     * @param  array  $transaction  Transaction data passed through $_POST
     * @param  int    $course_id    Post ID of a course
     * @param  int    $user_id      ID of a user
     * @param  string $user_email   Email of the user
     */
    public function mycred_record_transaction($transaction, $course_id, $user_id, $user_email) {
      
        $transaction['user_id'] = $user_id;
        $transaction['course_id'] = $course_id;

        $course_title = $transaction['course_title'];


        $post_id = wp_insert_post(array('post_title' => "Course {$course_title} Purchased By {$user_email}", 'post_type' => 'sfwd-transactions', 'post_status' => 'publish', 'post_author' => $user_id));


        foreach ($transaction as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
    }

   public function mycred_payment_button($default_button, $params = null) {

        global $post;

        $course_id = learndash_get_course_id( $post->ID );

        //if this disable in general settings 
        // return default button $default_button
        if ((!isset($params['price']) ) || ( empty($params['price']) )) {
            return $default_button;
        }

        $this->default_button = $default_button;

        if (isset($params['post'])) {
             $course_id = $params['post'];
        }

        if (empty( $course_id)) {
            return;
        }

        // $course_id = $this->course->ID;
        if (get_post_meta($course_id, 'allow_buy_course_pts', true)) {
            return $default_button;
        }

        $user_id = get_current_user_id();

        if (0 != $user_id) {
            $mycred_button = '<form id="learndash-mycred-checkout-' . $course_id . '" class="learndash-mycred-pts" name="" action="" method="post">';
            $mycred_button_text = apply_filters('learndash_mycred_purchase_button_text', __('Pay by MyCred Points', 'mycred-learndash'));
            $mycred_button .= '<input id="learndash-mycred-checkout-button-' . $course_id . '" data-course="' . $course_id . '" class="learndash-mycred-pts-button btn-join button" type="button" data-course="' . $course_id . '" data- value="' . $mycred_button_text . '">';
            $mycred_button .= '</form>';

            return $mycred_button;
        } else {
            return $params['type'];
        }
    }

}

new myCRED_Learndash_Buy_Pts();

