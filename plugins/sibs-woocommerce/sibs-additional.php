<?php
/**
 * Sibs Plugin Additional
 *
 * This file is used for add payment status, extra customer info.
 * Copyright (c) SIBS
 *
 * @package Sibs
 * @located at  /
 */

/**
 * Register new order statuses
 *  ( Pending and Payment Accepted and Captured )
 */
function sibs_register_payment_status() {

    register_post_status(
        'wc-sibs-processing', array(
            'label'                     => _x( 'Sibs Processing', 'WooCommerce Order Status', 'wc-sibs' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            /* translators: %s: https://developer.wordpress.org/rest-api/  */
            'label_count'               => _n_noop( 'Sibs Processing ( %s )', 'Sibs Processing ( %s )', 'wc-sibs' ),
        )
    );

	register_post_status(
		'wc-pending', array(
			'label'                     => _x( 'Pending', 'WooCommerce Order Status', 'wc-sibs' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: https://developer.wordpress.org/rest-api/  */
			'label_count'               => _n_noop( 'Pending ( %s )', 'Pending ( %s )', 'wc-sibs' ),
		)
	);

	register_post_status(
		'wc-payment-accepted', array(
			'label'                     => _x( 'Payment Accepted', 'WooCommerce Order Status', 'wc-sibs' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: https://developer.wordpress.org/rest-api/  */
			'label_count'               => _n_noop( 'Payment Accepted ( %s )', 'Payment Accepted ( %s )', 'wc-sibs' ),
		)
	);

	register_post_status(
		'wc-payment-captured', array(
			'label'                     => _x( 'Payment Captured', 'WooCommerce Order Status', 'wc-sibs' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: https://developer.wordpress.org/rest-api/  */
			'label_count'               => _n_noop( 'Payment Captured ( %s )', 'Payment Captured ( %s )', 'wc-sibs' ),
		)
	);

}
add_filter( 'init', 'sibs_register_payment_status' );

/**
 * Add new order statuses to woocommerce
 *  ( In Review, Pre-Authorization and Payment Accepted )
 *
 * @param array $order_status order status.
 */
function sibs_add_order_status( $order_status ) {

    $order_status['wc-sibs-processing']	  = _x( 'Sibs Processing', 'Payment Processing Order Status', 'wc-sibs');
	$order_status['wc-pending']			  = _x( 'Pending', 'Payment Pending Order Status', 'wc-sibs');
	$order_status['wc-payment-accepted']  = _x( 'Payment Accepted', 'Payment Accepted Order Status', 'wc-sibs' );
	$order_status['wc-payment-captured']  = _x( 'Payment Captured', 'Payment Captured Order Status', 'wc-sibs' );

	return $order_status;
}
add_filter( 'wc_order_statuses', 'sibs_add_order_status' );

/**
 * Add extra profile info at user profile
 *  ( Date of birth and Gender )
 *
 * @param array $user user.
 */
function sibs_add_extra_user_profile_fields( $user ) {
	$bod    = esc_attr( get_the_author_meta( 'billing_bod', $user->ID ) );
	$gender = esc_attr( get_the_author_meta( 'billing_gender', $user->ID ) );

	$label = array(
		'costumer'      => __( 'BACKEND_GENERAL_CUSTOMER', 'wc-sibs' ),
		'bod'           => __( 'BACKEND_GENERAL_BOD', 'wc-sibs' ),
		'gender'        => __( 'BACKEND_GENERAL_GENDER', 'wc-sibs' ),
		'gender_male'   => __( 'BACKEND_GENERAL_GENDER_MALE', 'wc-sibs' ),
		'gender_female' => __( 'BACKEND_GENERAL_GENDER_FEMALE', 'wc-sibs' ),
	);

	$args = array(
		'bod'    => $bod,
		'gender' => $gender,
		'label'  => $label,
	);
	Sibs_General_Functions::sibs_include_template( dirname( __FILE__ ) . '/templates/admin/user/template-edit-user.php', $args );
}
if ( get_option( 'sibs_general_dob_gender' ) ) {
	add_action( 'show_user_profile', 'sibs_add_extra_user_profile_fields' );
	add_action( 'edit_user_profile', 'sibs_add_extra_user_profile_fields' );
}

/**
 * Save extra profile info at user profile
 *  ( Date of birth and Gender )
 *
 * @param array $uid user id.
 * @return bool
 */
function sibs_save_extra_user_profile_fields( $uid ) {
	$saved = false;
	if ( current_user_can( 'edit_user', $uid ) ) {
		$billing_bod = Sibs_General_Functions::sibs_get_request_value( 'billing_bod', '00-00-0000' );
		update_user_meta( $uid, 'billing_bod', $billing_bod );
		$billing_gender = Sibs_General_Functions::sibs_get_request_value( 'billing_gender', 'Male' );
		update_user_meta( $uid, 'billing_gender', $billing_gender );
		$saved = true;
	}
	return true;
}
if ( get_option( 'sibs_general_dob_gender' ) ) {
	add_action( 'personal_options_update', 'sibs_save_extra_user_profile_fields' );
	add_action( 'edit_user_profile_update', 'sibs_save_extra_user_profile_fields' );
}

/**
 * Add date of birth & gender at user profile
 *
 * @param array $fields fields.
 * @return bool
 */
function sibs_custom_woocommerce_billing_fields( $fields ) {

	$label = array(
		'bod'    => __( 'BACKEND_GENERAL_BOD', 'wc-sibs' ),
		'gender' => __( 'BACKEND_GENERAL_GENDER', 'wc-sibs' ),
	);

	$fields['billing_bod'] = array(
		'type'        => 'text',
		'label'       => $label['bod'],
		'required'    => true,
		'placeholder' => 'DD-MM-YYYY',
		'class'       => array( 'form-row-first', 'address-field' ),
	);

	$fields['billing_gender'] = array(
		'type'     => 'select',
		'label'    => $label['gender'],
		'options'  => array(
			'Male'   => __( 'BACKEND_GENERAL_GENDER_MALE', 'wc-sibs' ),
			'Female' => __( 'BACKEND_GENERAL_GENDER_FEMALE', 'wc-sibs' ),
		),
		'required' => true,
		'class'    => array( 'form-row-last', 'address-field' ),
		'clear'    => true,
	);

	return $fields;
}
if ( get_option( 'sibs_general_dob_gender' ) ) {
	add_filter( 'woocommerce_billing_fields', 'sibs_custom_woocommerce_billing_fields' );
}

/**
 * Save date of birth and gender after ajax load payment method list
 *
 * @param  string $post_data post data.
 */
function sibs_action_checkout_update_order_review( $post_data ) {
	parse_str( $post_data, $output );
	$customer                   = WC()->session->get( 'customer' );
	$customer['billing_bod']    = $output['billing_bod'];
	$customer['billing_gender'] = $output['billing_gender'];
	WC()->session->set( 'customer', $customer );
}
if ( get_option( 'sibs_general_dob_gender' ) ) {
	add_action( 'woocommerce_checkout_update_order_review', 'sibs_action_checkout_update_order_review' );
}

/**
 * Add sibs custom order status icon
 */
function sibs_add_custom_order_status_icon() {
	if ( ! is_admin() ) {
		return;
	}
	?>
		<style>
			.column-order_status mark.pre-authorization:after, .column-order_status mark.payment-accepted:after, .column-order_status mark.in-review:after {
				background-size:100%;
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				text-align: center;
				content: '';
				background-repeat: no-repeat;
			}
			.column-order_status mark.pre-authorization:after {
				background-image: url(<?php echo esc_attr( plugins_url( 'assets/images/pre-authorization.png', __FILE__ ) ); ?>);
			}
			.column-order_status mark.payment-accepted:after {
				background-image: url(<?php echo esc_attr( plugins_url( 'assets/images/payment-accepted.png', __FILE__ ) ); ?>);
			}
			.column-order_status mark.in-review:after {
				background-image: url(<?php echo esc_attr( plugins_url( 'assets/images/in-review.png', __FILE__ ) ); ?>);
			}
		</style>
	<?php
}

add_action( 'wp_print_scripts', 'sibs_add_custom_order_status_icon' );

/**
 *  Add a custom email to the list of emails WooCommerce should load
 *
 * @since 0.1
 * @param array $email_classes available email classes
 * @return array filtered available email classes
 */
function payment_processed_woocommerce_email( $email_classes ) {

    // include our custom email class
    require( 'includes/class-wc-payment-processed-email.php' );

    // add the email class to the list of email classes that WooCommerce loads
    $email_classes['WC_Payment_Processed_Email'] = new Sibs_WC_Payment_Processed_Email();

    return $email_classes;

}
add_filter( 'woocommerce_email_classes', 'payment_processed_woocommerce_email' );
