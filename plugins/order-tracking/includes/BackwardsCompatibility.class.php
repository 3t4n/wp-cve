<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdotpBackwardsCompatibility' ) ) {
/**
 * Class to handle transforming the plugin settings from the 
 * previous style (individual options) to the new one (options array)
 *
 * @since 3.0.0
 */
class ewdotpBackwardsCompatibility {

	public function __construct() {
		
		if ( empty( get_option( 'ewd-otp-settings' ) ) and get_option( 'EWD_OTP_Full_Version' ) ) { $this->run_backwards_compat(); }
		elseif ( ! get_option( 'ewd-otp-permission-level' ) ) { update_option( 'ewd-otp-permission-level', 1 ); }
	}

	public function run_backwards_compat() {

		$this->convert_custom_fields();

		$settings = array(
			'custom-css' 								=> get_option( 'EWD_OTP_Custom_CSS' ),
			'disable-ajax-loading'						=> get_option( 'EWD_OTP_AJAX_Reload' ) == 'Yes' ? false : true,
			'new-window'								=> get_option( 'EWD_OTP_New_Window' ) == 'Yes' ? true : false,
			'order-information'							=> is_array( get_option( 'EWD_OTP_Order_Information' ) ) ? array_map( 'strtolower', get_option( 'EWD_OTP_Order_Information' ) ) : array(),
			'hide-blank-fields'							=> get_option( 'EWD_OTP_Hide_Blank_Fields' ) == 'Yes' ? true : false,
			'form-instructions'							=> get_option( 'EWD_OTP_Form_Instructions' ),
			'date-format'								=> get_option( 'EWD_OTP_Localize_Date_Time' ) == 'North_American' ? 'y-m-d H:i:s' : 'd-m-y H:i:s',
			'email-verification'						=> get_option( 'EWD_OTP_Email_Confirmation' ) == 'Order_Email' ? true : false,
			'display-print-button'						=> get_option( 'EWD_OTP_Display_Print_Button' ) == 'Yes' ? true : false,
			'email-frequency'							=> strtolower( get_option( 'EWD_OTP_Order_Email' ) ),
			'tracking-page-url'							=> get_option( 'EWD_OTP_Tracking_Page' ),

			'statuses'									=> $this->convert_statuses(),

			'email-messages'							=> $this->convert_emails(),
			'admin-email'								=> get_option( 'EWD_OTP_Admin_Email' ),
			
			'access-role'								=> get_option( 'EWD_OTP_Access_Role' ),
			'tracking-graphic'							=> strtolower( get_option( 'EWD_OTP_Display_Graphic' ) ),
			'customer-notes-email'						=> $this->get_corresponding_email( get_option( 'EWD_OTP_Customer_Notes_Email' ) ),
			'customer-order-email'						=> $this->get_corresponding_email( get_option( 'EWD_OTP_Customer_Order_Email' ) ),
			'allow-customer-downloads'					=> get_option( 'EWD_OTP_Allow_Customer_Downloads' ) == 'Yes' ? true : false,
			'allow-sales-rep-downloads'					=> get_option( 'EWD_OTP_Allow_Sales_Rep_Downloads' ) == 'Yes' ? true : false,
			'allow-sales-rep-selection'					=> get_option( 'EWD_OTP_Allow_Sales_Rep_Selection' ) == 'Yes' ? true : false,
			'allow-assign-orders-to-customers'			=> get_option( 'EWD_OTP_Auto_Assign_Orders_To_Customers' ) == 'Yes' ? true : false,
			'customer-order-number-prefix'				=> get_option( 'EWD_OTP_Customer_Order_Number_Prefix' ),
			'customer-order-number-suffix'				=> get_option( 'EWD_OTP_Customer_Order_Number_Suffix' ),
			'default-sales-rep'							=> intval( get_option( 'EWD_OTP_Default_Sales_Rep' ) ),

			'locations'									=> $this->convert_locations(),

			'allow-order-payments'						=> get_option( 'EWD_OTP_Allow_Order_Payments' ) == 'Yes' ? true : false,
			'default-payment-status'					=> get_option( 'EWD_OTP_Default_Payment_Status' ),
			'paypal-email-address'						=> get_option( 'EWD_OTP_PayPal_Email_Address' ),
			'pricing-currency-code'						=> get_option( 'EWD_OTP_Pricing_Currency_Code' ),
			'thank-you-url'								=> get_option( 'EWD_OTP_Thank_You_URL' ),

			'woocommerce-integration'					=> get_option( 'EWD_OTP_WooCommerce_Integration' ) == 'Yes' ? true : false,
			'woocommerce-prefix'						=> get_option( 'EWD_OTP_WooCommerce_Prefix' ),
			'woocommerce-disable-random-suffix'			=> get_option( 'EWD_OTP_WooCommerce_Random_Suffix' ) == 'Yes' ? false : true,
			'woocommerce-show-on-order-page'			=> get_option( 'EWD_OTP_WooCommerce_Show_On_Order_Page' ) == 'Yes' ? true : false,
			'woocommerce-locations-enabled'				=> get_option( 'EWD_OTP_Enabled_Locations_For_WooCommerce' ) == 'Yes' ? true : false,
			'woocommerce-replace-statuses'				=> get_option( 'EWD_OTP_Replace_WooCommerce_Statuses' ) == 'Yes' ? true : false,
			'woocommerce-revert-statuses'				=> get_option( 'EWD_OTP_WooCommerce_Revert_Statuses' ) == 'Yes' ? true : false,
			'woocommerce-paid-status'					=> get_option( 'EWD_OTP_WooCommerce_Paid_Order_Status' ),
			'woocommerce-unpaid-status'					=> get_option( 'EWD_OTP_WooCommerce_Unpaid_Order_Status' ),
			'woocommerce-processing-status'				=> get_option( 'EWD_OTP_WooCommerce_Processing_Order_Status' ),
			'woocommerce-cancelled-status'				=> get_option( 'EWD_OTP_WooCommerce_Cancelled_Order_Status' ),
			'woocommerce-onhold-status'					=> get_option( 'EWD_OTP_WooCommerce_OnHold_Order_Status' ),
			'woocommerce-failed-status'					=> get_option( 'EWD_OTP_WooCommerce_Failed_Order_Status' ),
			'woocommerce-refunded-status'				=> get_option( 'EWD_OTP_WooCommerce_Refunded_Order_Status' ),

			'zendesk-integration'						=> get_option( 'EWD_OTP_Zendesk_Integration' ) == 'Yes' ? true : false,
			'zendesk-api-key'							=> get_option( 'EWD_OTP_Zendesk_API_Key' ),

			'label-order-form-title'					=> get_option( 'EWD_OTP_Tracking_Title_Label' ),
			'label-order-form-number'					=> get_option( 'EWD_OTP_Tracking_Ordernumber_Label' ),
			'label-order-form-number-placeholder'		=> get_option( 'EWD_OTP_Tracking_Ordernumber_Placeholder_Label' ),
			'label-order-form-email'					=> get_option( 'EWD_OTP_Tracking_Email_Label' ),
			'label-order-form-email-placeholder'		=> get_option( 'EWD_OTP_Tracking_Email_Placeholder_Label' ),
			'label-order-form-button'					=> get_option( 'EWD_OTP_Tracking_Button_Label' ),
			'label-retrieving-results'					=> get_option( 'EWD_OTP_Retrieving_Results_Label' ),
			'label-customer-form-title'					=> get_option( 'EWD_OTP_Customer_Form_Title_Label' ),
			'label-customer-form-instructions'			=> get_option( 'EWD_OTP_Customer_Form_Description_Label' ),
			'label-customer-form-number'				=> get_option( 'EWD_OTP_Customer_Form_Number_Label' ),
			'label-customer-form-number-placeholder'	=> get_option( 'EWD_OTP_Customer_Form_Number_Placeholder_Label' ),
			'label-customer-form-email'					=> get_option( 'EWD_OTP_Customer_Form_Email_Label' ),
			'label-customer-form-email-placeholder'		=> get_option( 'EWD_OTP_Customer_Form_Email_Placeholder_Label' ),
			'label-customer-form-button'				=> get_option( 'EWD_OTP_Customer_Form_Button_Label' ),
			'label-sales-rep-form-title'				=> get_option( 'EWD_OTP_Sales_Rep_Form_Title_Label' ),
			'label-sales-rep-form-instructions'			=> get_option( 'EWD_OTP_Sales_Rep_Form_Description_Label' ),
			'label-sales-rep-form-number'				=> get_option( 'EWD_OTP_Sales_Rep_Form_Number_Label' ),
			'label-sales-rep-form-number-placeholder'	=> get_option( 'EWD_OTP_Sales_Rep_Form_Number_Placeholder_Label' ),
			'label-sales-rep-form-email'				=> get_option( 'EWD_OTP_Sales_Rep_Form_Email_Label' ),
			'label-sales-rep-form-email-placeholder'	=> get_option( 'EWD_OTP_Sales_Rep_Form_Email_Placeholder_Label' ),
			'label-sales-rep-form-button'				=> get_option( 'EWD_OTP_Sales_Rep_Form_Button_Label' ),
			'label-order-information'					=> get_option( 'EWD_OTP_Order_Information_Label' ),
			'label-order-number'						=> get_option( 'EWD_OTP_Order_Number_Label' ),
			'label-order-name'							=> get_option( 'EWD_OTP_Order_Name_Label' ),
			'label-order-notes'							=> get_option( 'EWD_OTP_Order_Notes_Label' ),
			'label-customer-notes'						=> get_option( 'EWD_OTP_Customer_Notes_Label' ),
			'label-order-status'						=> get_option( 'EWD_OTP_Order_Status_Label' ),
			'label-order-location'						=> get_option( 'EWD_OTP_Order_Location_Label' ),
			'label-order-updated'						=> get_option( 'EWD_OTP_Order_Updated_Label' ),
			'label-order-current-location'				=> get_option( 'EWD_OTP_Order_Current_Location_Label' ),
			'label-order-print-button'					=> get_option( 'EWD_OTP_Order_Print_Button_Label' ),
			'label-order-add-note-button'				=> get_option( 'EWD_OTP_Order_Add_Note_Button_Label' ),
			'label-order-update-status'					=> get_option( 'EWD_OTP_Order_Update_Status_Button_Label' ),
			'label-customer-display-name'				=> get_option( 'EWD_OTP_Customer_Display_Name_Label' ),
			'label-customer-display-email'				=> get_option( 'EWD_OTP_Customer_Display_Email_Label' ),
			'label-customer-display-download'			=> get_option( 'EWD_OTP_Customer_Display_Download_Label' ),
			'label-sales-rep-display-first-name'		=> get_option( 'EWD_OTP_Sales_Rep_Display_First_Name_Label' ),
			'label-sales-rep-display-last-name'			=> get_option( 'EWD_OTP_Sales_Rep_Display_Last_Name_Label' ),
			'label-customer-order-name'					=> get_option( 'EWD_OTP_Customer_Order_Name_Label' ),
			'label-customer-order-email'				=> get_option( 'EWD_OTP_Customer_Order_Email_Label' ),
			'label-customer-order-notes'				=> get_option( 'EWD_OTP_Customer_Order_Notes_Label' ),
			'label-customer-order-button'				=> get_option( 'EWD_OTP_Customer_Order_Button_Label' ),
			'label-customer-order-thank-you'			=> get_option( 'EWD_OTP_Customer_Order_Thank_You_Label' ),
			'label-customer-order-email-instructions'	=> get_option( 'EWD_OTP_Customer_Order_Email_Instructions_Label' ),
			
			'styling-title-font'						=> get_option( 'EWD_OTP_Styling_Title_Font' ),
			'styling-title-font-size'					=> get_option( 'EWD_OTP_Styling_Title_Font_Size' ),
			'styling-title-font-color'					=> get_option( 'EWD_OTP_Styling_Title_Font_Color' ),
			'styling-title-margin'						=> get_option( 'EWD_OTP_Styling_Title_Margin' ),
			'styling-title-padding'						=> get_option( 'EWD_OTP_Styling_Title_Padding' ),
			'styling-label-font'						=> get_option( 'EWD_OTP_Styling_Label_Font' ),
			'styling-label-font-size'					=> get_option( 'EWD_OTP_Styling_Label_Font_Size' ),
			'styling-label-font-color'					=> get_option( 'EWD_OTP_Styling_Label_Font_Color' ),
			'styling-label-margin'						=> get_option( 'EWD_OTP_Styling_Body_Margin' ),
			'styling-label-padding'						=> get_option( 'EWD_OTP_Styling_Body_Padding' ),
			'styling-content-font'						=> get_option( 'EWD_OTP_Styling_Content_Font' ),
			'styling-content-font-size'					=> get_option( 'EWD_OTP_Styling_Content_Font_Size' ),
			'styling-content-font-color'				=> get_option( 'EWD_OTP_Styling_Content_Font_Color' ),
			'styling-content-margin'					=> get_option( 'EWD_OTP_Styling_Body_Margin' ),
			'styling-content-padding'					=> get_option( 'EWD_OTP_Styling_Body_Padding' ),
			'styling-button-font-color'					=> get_option( 'EWD_OTP_Styling_Button_Font_Color' ),
			'styling-button-background-color'			=> get_option( 'EWD_OTP_Styling_Button_Bg_Color' ),
			'styling-button-border'						=> get_option( 'EWD_OTP_Styling_Button_Border' ),
			'styling-button-margin'						=> get_option( 'EWD_OTP_Styling_Button_Margin' ),
			'styling-button-padding'					=> get_option( 'EWD_OTP_Styling_Button_Padding' ),

		);

		add_option( 'ewd-otp-review-ask-time', get_option( 'EWD_OTP_Ask_Review_Date' ) );
		add_option( 'ewd-otp-installation-time', get_option( 'EWD_OTP_Install_Time' ) );

		update_option( 'ewd-otp-permission-level', get_option( 'EWD_OTP_Full_Version' ) == 'Yes' ? 2 : 1 );
		
		update_option( 'ewd-otp-settings', $settings );
	}

	public function convert_statuses() {

		$old_statuses = is_array( get_option( 'EWD_OTP_Statuses_Array' ) ) ? get_option( 'EWD_OTP_Statuses_Array' ) : array();
		$new_statuses = array();

		foreach ( $old_statuses as $old_status ) {

			$new_status = array(
				'status'		=> $old_status['Status'],
				'percentage'	=> $old_status['Percentage'],
				'email'			=> $this->get_corresponding_email( $old_status['Message'] ),
				'internal'		=> strtolower( $old_status['Internal'] ),
			);

			$new_statuses[] = $new_status;
		}

		return json_encode( $new_statuses );
	}

	public function convert_emails() {

		$subject = get_option( 'EWD_OTP_Subject_Line' );

		$old_emails = is_array( get_option( 'EWD_OTP_Email_Messages_Array' ) ) ? get_option( 'EWD_OTP_Email_Messages_Array' ) : array();
		$new_emails = array();

		foreach ( $old_emails as $count => $old_email ) {

			$new_email = array(
				'id'			=> ( $count + 1 ),
				'name'			=> $old_email['Name'],
				'subject'		=> $subject,
				'message'		=> $old_email['Message']
			);

			$new_emails[] = $new_email;
		}

		return json_encode( $new_emails );
	}

	public function get_corresponding_email( $email_name ) {

		$old_emails = is_array( get_option( 'EWD_OTP_Email_Messages_Array' ) ) ? get_option( 'EWD_OTP_Email_Messages_Array' ) : array();

		foreach ( $old_emails as $count => $old_email ) {

			if ( $email_name == $old_email['Name'] ) { return ( $count + 1 ); }
		}

		return 0;
	}

	public function convert_locations() {

		$old_locations = is_array( get_option( 'EWD_OTP_Locations_Array' ) ) ? get_option( 'EWD_OTP_Locations_Array' ) : array();
		$new_locations = array();

		foreach ( $old_locations as $old_location ) {

			$new_location = array(
				'name'			=> $old_location['Name'],
				'latitude'		=> $old_location['Latitude'],
				'longitude'		=> $old_location['Longitude']
			);

			$new_locations[] = $new_location;
		}

		return json_encode( $new_locations );
	}

	public function convert_custom_fields() {
		global $wpdb;

		$custom_fields_table_name = $wpdb->prefix . 'EWD_OTP_Custom_Fields';

		$old_fields = $wpdb->get_results( "SELECT * FROM $custom_fields_table_name ORDER BY Field_Order ASC" );

		$new_fields = array();

		$meta_table_name = $wpdb->prefix . 'EWD_OTP_Fields_Meta';

		$uploads_dir = site_url( '/wp-content/uploads/order-tracking-uploads/' );

		foreach ( $old_fields as $old_field ) {

			if ( $old_field->Field_Type == 'picture' or $old_field->Field_Type == 'file' ) {

				$wpdb->get_results( $wpdb->prepare( "UPDATE $meta_table_name SET Meta_Value = CONCAT( %s, Meta_Value ) WHERE Field_ID=%d", $uploads_dir, $old_field->Field_ID ) ); 
			}

			$new_field = array(
				'id'				=> $old_field->Field_ID,
				'name'				=> $old_field->Field_Name,
				'slug'				=> $old_field->Field_Slug,
				'function'			=> strtolower( $old_field->Field_Function ),
				'type'				=> $old_field->Field_Type == 'mediumint' ? 'number' : ( $old_field->Field_Type == 'picture' ? 'image' : $old_field->Field_Type ),
				'options'			=> $old_field->Field_Values,
				'display'			=> $old_field->Field_Display == 'Yes' ? true : false,
				'front_end_display'	=> $old_field->Field_Front_End_Display == 'Yes' ? true : false,
				'required'			=> strtolower( $old_field->Field_Required ),
				'equivalent'		=> ( empty( $old_field->Field_Equivalent ) or $old_field->Field_Equivalent == 'None' ) ? 'none' : $old_field->Field_Equivalent,
			);

			$new_fields[] = (object) $new_field;
		}

		update_option( 'ewd-otp-custom-fields', $new_fields );
	}
}

}