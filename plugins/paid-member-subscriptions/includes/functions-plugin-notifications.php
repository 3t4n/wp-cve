<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 *
 *
 */
function pms_dismiss_admin_notifications() {

	if( ! empty( $_GET['pms_dismiss_admin_notification'] ) ) {

		$notifications = PMS_Plugin_Notifications::get_instance();
		$notifications->dismiss_notification( sanitize_text_field( $_GET['pms_dismiss_admin_notification'] ) );

	}

}
add_action( 'admin_init', 'pms_dismiss_admin_notifications' );


/**
 *
 *
 */
function pms_add_admin_menu_notification_counts() {

	global $menu, $submenu;

	$notifications = PMS_Plugin_Notifications::get_instance();

	/**
	 *
	 *
	 */
	if( ! empty( $menu ) ) {

		foreach( $menu as $menu_position => $menu_data ) {

			if( ! empty( $menu_data[2] ) && $menu_data[2] == 'paid-member-subscriptions' ) {

				$menu_count = $notifications->get_count_in_menu();

				if( ! empty( $menu_count ) )
					$menu[$menu_position][0] .= '<span class="update-plugins pms-update-plugins"><span class="plugin-count">' . $menu_count . '</span></span>';

			}

		}

	}


	/**
	 *
	 *
	 */
	if( ! empty( $submenu['paid-member-subscriptions'] ) ) {

		foreach( $submenu['paid-member-subscriptions'] as $menu_position => $menu_data ) {

			$menu_count = $notifications->get_count_in_submenu( $menu_data[2] );

			if( ! empty( $menu_count ) )
				$submenu['paid-member-subscriptions'][$menu_position][0] .= '<span class="update-plugins pms-update-plugins"><span class="plugin-count">' . $menu_count . '</span></span>';

		}

	}

}
add_action( 'admin_init', 'pms_add_admin_menu_notification_counts', 1000 );


/**
 *
 *
 */
function pms_add_plugin_notification( $notification_id = '', $notification_message = '', $notification_class = 'update-nag', $count_in_menu = true, $count_in_submenu = array() ) {

	$notifications = PMS_Plugin_Notifications::get_instance();
	$notifications->add_notification( $notification_id, $notification_message, $notification_class, $count_in_menu, $count_in_submenu );

}


function pms_add_plugin_notification_new_add_on() {

	if( pms_get_active_stripe_gateway() == false ){
		$notification_id = 'pms_free_stripe_connect';
		$message = '<img style="float: right; margin: 20px 12px 10px 0; max-width: 80px;" src="' . PMS_PLUGIN_DIR_URL . 'assets/images/pms-stripe.png" />';
		$message .= '<p style="margin-top: 16px;">' . wp_kses_post( __( '<strong>New payment gateway!</strong><br><br><strong>Stripe</strong> payment gateway is now available in the free version. <br>Your users can pay using credit and debit cards without leaving your website and you can also offer them additional payment methods like Bancontact, iDeal, Giropay and more. <br><br>Get started now by going to <strong>Paid Member Subscriptions -> Settings -> Payments</strong>!', 'paid-member-subscriptions' ) ) . '</p>';
		$message .= '<p><a href="https://www.cozmoslabs.com/docs/paid-member-subscriptions/payment-gateways/stripe-connect/?utm_source=wp-backend&utm_medium=addon-notification&utm_campaign=PMSFree" class="button-primary" target="_blank">' . esc_html__( 'Learn More', 'paid-member-subscriptions' ) . '</a></p>';
		$message .= '<a href="' . esc_url( add_query_arg( array( 'pms_dismiss_admin_notification' => $notification_id ) ) ) . '#pms-addons-title" type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__( 'Dismiss this notice.', 'paid-member-subscriptions' ) . '</span></a>';
	
		pms_add_plugin_notification( $notification_id, $message, 'pms-notice pms-narrow notice notice-info', true, array( 'pms-settings-page' ) );
	}

	/**
	 * LearnDash integration notices
	 */
	if( is_plugin_active( 'sfwd-lms/sfwd_lms.php' ) ){
		// paid version
		if( defined( 'PMS_PAID_PLUGIN_DIR' ) ){

			$notification_id = 'pms_learndash_addon2';
			$message = '<img style="float: left; margin: 20px 12px 10px 0; max-width: 100px;" src="' . PMS_PLUGIN_DIR_URL . 'assets/images/addons/pms-add-on-learndash.png" />';
			$message .= '<p style="margin-top: 16px;">' . wp_kses_post( '<strong>LearnDash Integration</strong> for <strong>Paid Member Subscriptions</strong> is now available!<br><br>Sell access to courses, create beautiful front-end register, login and reset password forms and restrict access to your Courses, Lessons and Quizzes.<br>Activate from the <a href="'.admin_url( 'admin.php?page=pms-addons-page' ).'">add-ons</a> page.' ) . '</p>';
			$message .= '<p><a href="https://www.cozmoslabs.com/add-ons/learndash/?utm_source=wpbackend&utm_medium=addon-notification&utm_campaign=PMSPaid" class="button-primary" target="_blank">' . esc_html__( 'Learn More', 'paid-member-subscriptions' ) . '</a></p>';
			$message .= '<a href="' . esc_url( add_query_arg( array( 'pms_dismiss_admin_notification' => $notification_id ) ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text" target="_blank">' . esc_html__( 'Dismiss this notice.', 'paid-member-subscriptions' ) . '</span></a>';
		
			pms_add_plugin_notification( $notification_id, $message, 'pms-notice pms-narrow notice notice-success', true, array( 'pms-addons-page' ) );
			
		// free version
		} else {

			$notification_id = 'pms_learndash_addon2';
			$message = '<img style="float: left; margin: 20px 12px 10px 0; max-width: 100px;" src="' . PMS_PLUGIN_DIR_URL . 'assets/images/addons/pms-add-on-learndash.png" />';
			$message .= '<p style="margin-top: 16px;">' . wp_kses_post( '<strong>LearnDash Integration</strong> for <strong>Paid Member Subscriptions</strong> is now available!<br><br>Sell access to courses, create beautiful front-end register, login and reset password forms and restrict access to your Courses, Lessons and Quizzes.<br><strong>Buy a license to continue</strong>.' ) . '</p>';
			$message .= '<p><a style="min-width: auto !important;height:30px; margin-right: 12px;" href="https://www.cozmoslabs.com/wordpress-paid-member-subscriptions/?utm_source=wpbackend&utm_medium=addon-notification&utm_campaign=PMSFree#pricing" class="button-primary" target="_blank">' . esc_html__( 'Buy now', 'paid-member-subscriptions' ) . '</a>';
			$message .= '<a href="https://www.cozmoslabs.com/add-ons/learndash/?utm_source=wpbackend&utm_medium=addon-notification&utm_campaign=PMSFree" class="button-secondary"  target="_blank">' . esc_html__( 'Learn More', 'paid-member-subscriptions' ) . '</a></p>';
			$message .= '<a href="' . esc_url( add_query_arg( array( 'pms_dismiss_admin_notification' => $notification_id ) ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__( 'Dismiss this notice.', 'paid-member-subscriptions' ) . '</span></a>';
		
			pms_add_plugin_notification( $notification_id, $message, 'pms-notice pms-narrow notice notice-success', true, array( 'pms-addons-page' ) );

		}
	}

}
add_action( 'admin_init', 'pms_add_plugin_notification_new_add_on' );
