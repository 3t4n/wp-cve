<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdotpAdminCustomFields' ) ) {
/**
 * Class to handle the admin custom fields page for Order Tracking
 *
 * @since 3.0.0
 */
class ewdotpAdminCustomFields {

	public function __construct() {

		// Add the admin menu
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 12 );
	}

	/**
	 * Add the top-level admin menu page
	 * @since 3.0.0
	 */
	public function add_menu_page() {
		global $ewd_otp_controller;

		add_submenu_page( 
			'ewd-otp-orders', 
			_x( 'Custom Fields', 'Title of admin page that lets you view and edit all custom fields', 'order-tracking' ),
			_x( 'Custom Fields', 'Title of the custom fields admin menu item', 'order-tracking' ), 
			$ewd_otp_controller->settings->get_setting( 'access-role' ), 
			'ewd-otp-custom-fields', 
			array( $this, 'show_admin_custom_fields_page' )
		);
	}

	/**
	 * Display the admin custom fields page
	 * @since 3.0.0
	 */
	public function show_admin_custom_fields_page() {
		global $ewd_otp_controller;

		$permission = ( $ewd_otp_controller->permissions->check_permission( 'custom_fields' ) or get_option( 'ewd-otp-installation-time' ) < 1664742505 ) ? true : false;

		if ( ! empty( $_POST['ewd-otp-custom-fields-submit'] ) ) {

			$this->save_custom_fields();
		}

		$custom_fields = get_option( 'ewd-otp-custom-fields' );

		$custom_fields[] = (object) array(
			'id'				=> 0,
			'name'				=> '',
			'slug'				=> '',
			'type'				=> '',
			'options'			=> '',
			'function'			=> '',
			'required'			=> '',
			'display'			=> '',
			'front_end_display'	=> '',
			'equivalent'		=> ''
		);

		?>

		<div class="wrap">
			<h1>
				<?php _e( 'Custom Fields', 'order-tracking' ); ?>
			</h1>

			<?php if ( $permission ) { ?> 

				<?php do_action( 'ewd_otp_custom_fields_table_top' ); ?>
	
				<form id="ewd-otp-custom-fields-table" method="POST" action="">
	
					<fieldset class="ewd-otp-warning-tip">
						<div class="ewd-otp-shortcode-reminder">
							<strong><?php _e( 'FILE AND IMAGE FIELDS', 'order-tracking' ); ?>:</strong> <?php _e( 'For security reasons, file and image type fields are not available for use in the customer order form.', 'order-tracking' ); ?>
						</div>
					</fieldset>
	
					<div id='ewd-otp-custom-fields-table-div'>
	
						<input type='hidden' name='ewd-otp-custom-field-save-values' />
	
						<div class='ewd-otp-custom-field-heading-row'>
							<div class='ewd-otp-custom-field-heading-cell'><?php _e( 'Name', 'order-tracking' ); ?></div>
							<div class='ewd-otp-custom-field-heading-cell'><?php _e( 'Slug', 'order-tracking' ); ?></div>
							<div class='ewd-otp-custom-field-heading-cell'><?php _e( 'Type', 'order-tracking' ); ?></div>
							<div class='ewd-otp-custom-field-heading-cell'><?php _e( 'Input Values', 'order-tracking' ); ?></div>
							<div class='ewd-otp-custom-field-heading-cell'><?php _e( 'Applicable to', 'order-tracking' ); ?></div>
							<div class='ewd-otp-custom-field-heading-cell'><?php _e( 'Options', 'order-tracking' ); ?></div>
							<div class='ewd-otp-custom-field-heading-cell'><?php _e( 'WooCommerce Equivalent', 'order-tracking' ); ?></div>
							<div class='ewd-otp-custom-field-heading-cell'></div>
						</div>
	
						<?php foreach ( $custom_fields as $custom_field ) { ?>
	
							<div class='ewd-otp-custom-field <?php echo ( empty( $custom_field->id ) ? 'ewd-otp-hidden ewd-otp-custom-field-template' : '' ); ?>'>
								<input type='hidden' name='ewd_otp_custom_field_id' value='<?php echo esc_attr( $custom_field->id ); ?>' />
	
								<div class='ewd-otp-custom-field-cell'>
									<label><?php _e( 'Name', 'order-tracking' ); ?></label>
									<input type='text' name='ewd_otp_custom_field_name' value='<?php echo esc_attr( $custom_field->name ); ?>' />
								</div>
	
								<div class='ewd-otp-custom-field-cell'>
									<label><?php _e( 'Slug', 'order-tracking' ); ?></label>
									<input type='text' name='ewd_otp_custom_field_slug' value='<?php echo esc_attr( $custom_field->slug ); ?>' />
								</div>
	
								<div class='ewd-otp-custom-field-cell'>
	
									<label><?php _e( 'Type', 'order-tracking' ); ?></label>
	
									<select name='ewd_otp_custom_field_type'>
	
										<option value='text' <?php echo ( $custom_field->type == 'text' ? 'selected' : '' ); ?>><?php _e( 'Text', 'order-tracking' ); ?></option>
										<option value='number' <?php echo ( $custom_field->type == 'number' ? 'selected' : '' ); ?>><?php _e( 'Number', 'order-tracking' ); ?></option>
										<option value='textarea' <?php echo ( $custom_field->type == 'textarea' ? 'selected' : '' ); ?>><?php _e( 'Textarea', 'order-tracking' ); ?></option>
										<option value='select' <?php echo ( $custom_field->type == 'select' ? 'selected' : '' ); ?>><?php _e( 'Dropdown', 'order-tracking' ); ?></option>
										<option value='radio' <?php echo ( $custom_field->type == 'radio' ? 'selected' : '' ); ?>><?php _e( 'Radio', 'order-tracking' ); ?></option>
										<option value='checkbox' <?php echo ( $custom_field->type == 'checkbox' ? 'selected' : '' ); ?>><?php _e( 'Checkbox', 'order-tracking' ); ?></option>
										<option value='file' <?php echo ( $custom_field->type == 'file' ? 'selected' : '' ); ?>><?php _e( 'File', 'order-tracking' ); ?></option>
										<option value='link' <?php echo ( $custom_field->type == 'link' ? 'selected' : '' ); ?>><?php _e( 'Link', 'order-tracking' ); ?></option>
										<option value='image' <?php echo ( $custom_field->type == 'image' ? 'selected' : '' ); ?>><?php _e( 'Image', 'order-tracking' ); ?></option>
										<option value='date' <?php echo ( $custom_field->type == 'date' ? 'selected' : '' ); ?>><?php _e( 'Date', 'order-tracking' ); ?></option>
										<option value='datetime' <?php echo ( $custom_field->type == 'datetime' ? 'selected' : '' ); ?>><?php _e( 'Datetime', 'order-tracking' ); ?></option>
	
									</select>
	
								</div>
	
								<div class='ewd-otp-custom-field-cell'>
									<label><?php _e( 'Input Values', 'order-tracking' ); ?></label>
									<input type='text' name='ewd_otp_custom_field_options' value='<?php echo esc_attr( $custom_field->options ); ?>' />
								</div>
	
								<div class='ewd-otp-custom-field-cell'>
	
								<label><?php _e( 'Applicable to', 'order-tracking' ); ?></label>
	
								<select name='ewd_otp_custom_field_function'>
	
										<option value='orders' <?php echo ( $custom_field->function == 'orders' ? 'selected' : '' ); ?>><?php _e( 'Orders', 'order-tracking' ); ?></option>
										<option value='customers' <?php echo ( $custom_field->function == 'customers' ? 'selected' : '' ); ?>><?php _e( 'Customers', 'order-tracking' ); ?></option>
										<option value='sales_reps' <?php echo ( $custom_field->function == 'sales_reps' ? 'selected' : '' ); ?>><?php _e( 'Sales Reps', 'order-tracking' ); ?></option>
	
									</select>
	
								</div>
	
								<div class='ewd-otp-custom-field-cell'>
	
									<label><?php _e( 'Options', 'order-tracking' ); ?></label>
	
									<div class='ewd-otp-custom-field-cell-checkbox-container'>
										<input type='checkbox' name='ewd_otp_custom_field_required' value='1' <?php echo ( ! empty( $custom_field->required ) ? 'checked' : '' ); ?> /><?php _e( 'Required', 'order-tracking' ); ?><br />
										<input type='checkbox' name='ewd_otp_custom_field_display' value='1' <?php echo ( ! empty( $custom_field->display ) ? 'checked' : '' ); ?> /><?php _e( 'Admin Display?', 'order-tracking' ); ?><br />
										<input type='checkbox' name='ewd_otp_custom_field_front_end_display' value='1' <?php echo ( ! empty( $custom_field->front_end_display ) ? 'checked' : '' ); ?> /><?php _e( 'Front-End Display?', 'order-tracking' ); ?><br />
									</div>
	
								</div>
	
								<div class='ewd-otp-custom-field-cell'>
	
									<label><?php _e( 'WooCommerce Equivalent', 'order-tracking' ); ?></label>
	
									<select name='ewd_otp_custom_field_equivalent'>
	
										<option value='none' <?php echo ( $custom_field->equivalent == 'none' ? 'selected' : '' ); ?>><?php _e( 'None', 'order-tracking' ); ?></option>
										<option value='_order_total' <?php echo ( $custom_field->equivalent == '_order_total' ? 'selected' : '' ); ?>><?php _e( 'Order Total', 'order-tracking' ); ?></option>
										<option value='_order_currency' <?php echo ( $custom_field->equivalent == '_order_currency' ? 'selected' : '' ); ?>><?php _e( 'Order Currency', 'order-tracking' ); ?></option>
										<option value='_billing_first_name' <?php echo ( $custom_field->equivalent == '_billing_first_name' ? 'selected' : '' ); ?>><?php _e( 'Billing First Name', 'order-tracking' ); ?></option>
										<option value='_billing_last_name' <?php echo ( $custom_field->equivalent == '_billing_last_name' ? 'selected' : '' ); ?>><?php _e( 'Billing Last Name', 'order-tracking' ); ?></option>
										<option value='_billing_company' <?php echo ( $custom_field->equivalent == '_billing_company' ? 'selected' : '' ); ?>><?php _e( 'Billing Company', 'order-tracking' ); ?></option>
										<option value='_billing_city' <?php echo ( $custom_field->equivalent == '_billing_city' ? 'selected' : '' ); ?>><?php _e( 'Billing City', 'order-tracking' ); ?></option>
										<option value='_billing_country' <?php echo ( $custom_field->equivalent == '_billing_country' ? 'selected' : '' ); ?>><?php _e( 'Billing Country', 'order-tracking' ); ?></option>
										<option value='_billing_email' <?php echo ( $custom_field->equivalent == '_billing_email' ? 'selected' : '' ); ?>><?php _e( 'Billing Email', 'order-tracking' ); ?></option>
										<option value='_billing_phone' <?php echo ( $custom_field->equivalent == '_billing_phone' ? 'selected' : '' ); ?>><?php _e( 'Billing Phone', 'order-tracking' ); ?></option>
										<option value='_shipping_first_name' <?php echo ( $custom_field->equivalent == '_shipping_first_name' ? 'selected' : '' ); ?>><?php _e( 'Shipping First Name', 'order-tracking' ); ?></option>
										<option value='_shipping_last_name' <?php echo ( $custom_field->equivalent == '_shipping_last_name' ? 'selected' : '' ); ?>><?php _e( 'Shipping Last Name', 'order-tracking' ); ?></option>
										<option value='_shipping_company' <?php echo ( $custom_field->equivalent == '_shipping_company' ? 'selected' : '' ); ?>><?php _e( 'Shipping Company', 'order-tracking' ); ?></option>
										<option value='_shipping_city' <?php echo ( $custom_field->equivalent == '_shipping_city' ? 'selected' : '' ); ?>><?php _e( 'Shipping City', 'order-tracking' ); ?></option>
										<option value='_shipping_country' <?php echo ( $custom_field->equivalent == '_shipping_country' ? 'selected' : '' ); ?>><?php _e( 'Shipping Country', 'order-tracking' ); ?></option>
										<option value='_shipping_email' <?php echo ( $custom_field->equivalent == '_shipping_email' ? 'selected' : '' ); ?>><?php _e( 'Shipping Email', 'order-tracking' ); ?></option>
										<option value='_shipping_phone' <?php echo ( $custom_field->equivalent == '_shipping_phone' ? 'selected' : '' ); ?>><?php _e( 'Shipping Phone', 'order-tracking' ); ?></option>
	
									</select>
	
								</div>
	
								<div class='ewd-otp-custom-field-cell ewd-otp-custom-field-delete'>
									<?php _e( 'Delete', 'order-tracking' ); ?>
								</div>
	
							</div>
	
						<?php } ?>
	
						<div class='ewd-otp-custom-fields-add'>
							<?php _e( '+ ADD', 'order-tracking' ); ?>
						</div>
	
					</div>
	
					<input type='submit' class='button button-primary' name='ewd-otp-custom-fields-submit' value='<?php _e( 'Update Fields', 'order-tracking' ); ?>' />
					
				</form>
				<?php do_action( 'ewd_otp_custom_fields_table_bottom' ); ?>

				<?php } else { ?>

					<div class='ewd-otp-premium-locked'>
						<a href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1" target="_blank">Upgrade</a> to the premium version to use this feature
					</div>
				<?php } ?>
		</div>

		<?php
	}

	/**
	 * Save the custom fields when the form is submitted
	 * @since 3.0.0
	 */
	public function save_custom_fields() {

		$custom_fields = json_decode( stripslashes( sanitize_text_field( $_POST['ewd-otp-custom-field-save-values'] ) ) );

		if ( ! empty( $custom_fields ) ) {

			update_option( 'ewd-otp-custom-fields', $custom_fields );
		}
	}
}
} // endif;
