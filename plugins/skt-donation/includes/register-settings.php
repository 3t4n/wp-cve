<?php
if ( ! function_exists ( 'register_skt_donation_settings' ) ) {
	function register_skt_donation_settings() {
		//register our settings
		register_setting( 'skt-donations-settings-group', 'skt_donation_active_tab' );
		/*******For PayPal Api Setting*******/
		register_setting('skt-donations-settings-group','skt_donation_paypal_active_show');
		register_setting( 'skt-donations-settings-group', 'skt_donation_paypal_mode_zero_one');
		register_setting( 'skt-donations-settings-group', 'skt_donation_paypal_test_api' );
		register_setting( 'skt-donations-settings-group', 'skt_donation_paypal_live_api' );

		register_setting( 'skt-donations-settings-group', 'skt_donation_test_paypal_business_email' );
		register_setting( 'skt-donations-settings-group', 'skt_donation_live_paypal_business_email' );
		/**********Donation Amount**********/
		register_setting('skt-donations-settings-group','skt_donation_amount_in_usd');
		/**********Form Fields**********/
		register_setting('skt-donations-settings-group','skt_donation_first_name');
		register_setting('skt-donations-settings-group','skt_donation_first_name_show');
		register_setting('skt-donations-settings-group','skt_donation_last_name');
		register_setting('skt-donations-settings-group','skt_donation_last_name_show');
		register_setting('skt-donations-settings-group','skt_donation_address');
		register_setting('skt-donations-settings-group','skt_donation_address_show');
		register_setting('skt-donations-settings-group','skt_donation_note');
		register_setting('skt-donations-settings-group','skt_donation_note_show');
		register_setting('skt-donations-settings-group','skt_donation_message');
		register_setting('skt-donations-settings-group','skt_donation_message_show');
		register_setting('skt-donations-settings-group','skt_donation_day');
		register_setting('skt-donations-settings-group','skt_donation_day_show');
		register_setting('skt-donations-settings-group','skt_donation_week');
		register_setting('skt-donations-settings-group','skt_donation_week_show');
		register_setting('skt-donations-settings-group','skt_donation_month');
		register_setting('skt-donations-settings-group','skt_donation_month_show');
		register_setting('skt-donations-settings-group','skt_donation_quaterly');
		register_setting('skt-donations-settings-group','skt_donation_quaterly_show');
		register_setting('skt-donations-settings-group','skt_donation_semiquaterly');
		register_setting('skt-donations-settings-group','skt_donation_semiquaterly_show');
		register_setting('skt-donations-settings-group','skt_donation_annual');
		register_setting('skt-donations-settings-group','skt_donation_annual_show');
		register_setting('skt-donations-settings-group','skt_donation_phone_show');
		register_setting('skt-donations-settings-group','skt_donation_email_show');
		
		// For admin background-color
		register_setting('skt-donations-settings-group','skt_donation_admin_backgroundcolor');
		register_setting('skt-donations-settings-group','skt_donation_admin_hover_backgroundcolor');
		register_setting('skt-donations-settings-group','skt_donation_admin_menu_backgroundcolor');
		register_setting('skt-donations-settings-group','skt_donation_admin_page_backgroundcolor');
		// For frontend background-color
		register_setting('skt-donations-settings-group','skt_donation_fend_backgroundcolor');
		register_setting('skt-donations-settings-group','skt_donation_fend_hover_backgroundcolor');
		register_setting('skt-donations-settings-group','skt_donation_fend_menu_backgroundcolor');
		register_setting('skt-donations-settings-group','skt_donation_fend_menu_hover_backgroundcolor');
		register_setting('skt-donations-settings-group','skt_donation_fend_form_backgroundcolor');
		// For Installation plugin date
		register_setting('skt-donations-settings-group','skt_donation_installation_date');
		// PayPal dynamic form step
		register_setting('skt-donations-settings-group','skt_donation_stripe_first_name');
		register_setting('skt-donations-settings-group','skt_donation_stripe_last_name');
		register_setting('skt-donations-settings-group','skt_donation_stripe_email');
		register_setting('skt-donations-settings-group','skt_donation_stripe_phone_name');
		register_setting('skt-donations-settings-group','skt_donation_stripe_amount');
		register_setting('skt-donations-settings-group','skt_donation_stripe_normal_payment');
		register_setting('skt-donations-settings-group','skt_donation_stripe_subscription_payment');
		register_setting('skt-donations-settings-group','skt_donation_stripe_card_no');
		// PayPal dynamic form step to use LABEL
		register_setting('skt-donations-settings-group','skt_donation_stripe_first_name_lable');
		register_setting('skt-donations-settings-group','skt_donation_stripe_last_name_lable');
		register_setting('skt-donations-settings-group','skt_donation_stripe_email_lable');
		register_setting('skt-donations-settings-group','skt_donation_stripe_phone_name_lable');
		register_setting('skt-donations-settings-group','skt_donation_stripe_amount_lable');
		register_setting('skt-donations-settings-group','skt_donation_stripe_type_of_payment_label');
		register_setting('skt-donations-settings-group','skt_donation_stripe_card_no_lable');
		// Manage email setting
		register_setting('skt-donations-settings-group','skt_donation_skt_email_address');
		register_setting('skt-donations-settings-group','skt_donation_skt_email_subject');
		register_setting('skt-donations-settings-group','skt_donation_skt_email_message');

		//Paypal checkout express
		register_setting('skt-donations-settings-group','skt_donation_paypalexp_active_show');
		register_setting('skt-donations-settings-group','skt_donation_paypalexp_mode_zero_one');
		register_setting('skt-donations-settings-group','skt_donation_paypalexp_secretkey');
		register_setting('skt-donations-settings-group','skt_donation_paypalexp_test_api');
		register_setting('skt-donations-settings-group','skt_donation_test_paypalexp_business_email');
		register_setting('skt-donations-settings-group','skt_donation_paypalexp_live_api');
		register_setting('skt-donations-settings-group','skt_donation_paypalexpIlive_secretkey');
		register_setting('skt-donations-settings-group','skt_donation_live_paypalexp_business_email');
		register_setting('skt-donations-settings-group','skt_donation_priceper');
	}
}
?>