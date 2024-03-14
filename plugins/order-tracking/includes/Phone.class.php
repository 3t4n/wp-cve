<?php
/**
 * Class to handle phone numbers, when needed
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpPhone' ) ) {
class ewdotpPhone {

    public function __construct() {

        add_action( 'ewd_otp_post_order_information_fields',            array( $this, 'add_admin_phone_number_field' ) );
        add_action( 'ewd_otp_post_sales_rep_information_fields',        array( $this, 'add_admin_phone_number_field' ) );
        add_action( 'ewd_otp_post_customer_order_information_fields',   array( $this, 'add_front_end_phone_number_field' ) );

        add_action( 'ewd_otp_validate_order_submission' ,           array( $this, 'save_phone_field' ) );
        add_action( 'ewd_otp_validate_sales_rep_submission' ,       array( $this, 'save_phone_field' ) );
    }

    /**
     * Adds a phone number field to the admin order and sales rep forms
     *
     * @since 3.3.0
     */
    public function add_admin_phone_number_field( $role ) { 
        global $ewd_otp_controller;

        if ( empty( $ewd_otp_controller->permissions->check_permission( 'sms' ) ) ) { return; }

        ?>

        <div class='ewd-otp-field'>

            <div class='ewd-otp-admin-label'>

                <label for="ewd_otp_phone_number">
                    <?php _e( 'Phone Number', 'order-tracking' ); ?>
                </label>

            </div>

            <div class='ewd-otp-admin-input'>
                <input type='text' name="ewd_otp_phone_number" value="<?php echo ( ! empty( $role->phone_number ) ? esc_attr( $role->phone_number ) : '' ); ?>" />
            </div>

        </div>

        <?php
    }

    /**
     * Adds a phone number field to the customer order form
     *
     * @since 3.3.0
     */
    public function add_front_end_phone_number_field( $view ) { 
        global $ewd_otp_controller;

        if ( empty( $ewd_otp_controller->permissions->check_permission( 'sms' ) ) ) { return; }

        ?>

        <div class='ewd-otp-customer-order-form-field'>

            <div class='ewd-otp-customer-order-form-label'>
                <?php echo esc_html( $view->get_label( 'label-customer-order-phone' ) ); ?>:
            </div>
    
            <div class='ewd-otp-customer-order-form-value'>
                <input name='ewd_otp_phone_number' type='text' required />
            </div>

            <div class='ewd-otp-customer-order-form-instructions'>
                <?php echo esc_html( $view->get_label( 'label-customer-order-phone-instructions' ) ); ?>
            </div>
    
        </div>

        <?php

    }

    /**
     * Saves the phone number field to the order or sales rep object
     *
     * @since 3.3.0
     */
    public function save_phone_field( $role ) {
        global $ewd_otp_controller;

        if ( empty( $ewd_otp_controller->permissions->check_permission( 'sms' ) ) ) { return; }

        $role->phone_number = empty( $_POST['ewd_otp_phone_number'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_phone_number'] );
    }
}
}