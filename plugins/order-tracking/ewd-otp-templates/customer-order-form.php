<div <?php echo ewd_format_classes( $this->classes ); ?> >

	<?php $this->maybe_print_update_message(); ?>

	<div class='ewd-otp-order-tracking-form-div'>

		<h3>
			<?php echo esc_html( $this->customer_order_form_title ); ?>
		</h3>

		<div class='ewd-otp-customer-order-form-instructions'>
			<?php echo esc_html( $this->customer_order_form_instructions ); ?>
		</div>

		<form action='#' method='post' class='ewd-otp-customer-order-form'>
	
			<input type='hidden' name='ewd_otp_form_type' value='customer_order_form' />

			<input type='hidden' name='ewd_otp_nonce' value='<?php echo esc_attr( $this->nonce ); ?>' />

			<input type='hidden' name='ewd_otp_location' value='<?php echo esc_attr( $this->location ); ?>' />
	
			<div class='ewd-otp-customer-order-form-field'>

				<div class='ewd-otp-customer-order-form-label'>
					<?php echo esc_html( $this->customer_name_field_text ); ?>:
				</div>
	
				<div class='ewd-otp-customer-order-form-value'>
					<input name='ewd_otp_order_name' type='text' required />
				</div>
	
			</div>
	
			<div class='ewd-otp-customer-order-form-field'>

				<div class='ewd-otp-customer-order-form-label'>
					<?php echo esc_html( $this->customer_email_field_text ); ?>:
				</div>
	
				<div class='ewd-otp-customer-order-form-value'>
					<input name='ewd_otp_order_email' type='text' required />
				</div>

				<div class='ewd-otp-customer-order-form-instructions'>
					<?php echo esc_html( $this->get_label( 'label-customer-order-email-instructions' ) ); ?>
				</div>
	
			</div>

			<?php do_action( 'ewd_otp_post_customer_order_information_fields', $this ); ?>

			<div class='ewd-otp-customer-order-form-field'>

				<div class='ewd-otp-customer-order-form-label'>
					<?php echo esc_html( $this->customer_notes_field_text ); ?>:
				</div>
	
				<div class='ewd-otp-customer-order-form-value'>
					<input name='ewd_otp_customer_notes' type='text' required />
				</div>
	
			</div>

			<?php if ( $this->get_option( 'allow-sales-rep-selection' ) ) { ?>

				<div class='ewd-otp-customer-order-form-field'>

					<div class='ewd-otp-customer-order-form-label'>
						<?php _e( 'Sales Rep', 'order-tracking' ); ?>:
					</div>
		
					<div class='ewd-otp-customer-order-form-value'>

						<select name='ewd_otp_sales_rep'>

							<?php foreach ( $this->get_sales_reps() as $sales_rep ) { ?>
								<option value='<?php echo esc_attr( $sales_rep->id ); ?>'><?php echo esc_html( $sales_rep->first_name ) . ' ' . esc_html( $sales_rep->last_name ); ?></option>
							<?php } ?>

						</select>

					</div>
		
				</div>

			<?php } ?>

			<?php foreach ( $this->get_order_fields() as $custom_field ) { ?>

				<?php $this->print_customer_order_field( $custom_field ); ?>

			<?php } ?>

			<?php $this->maybe_display_captcha_field(); ?>
	
			<?php $this->print_customer_order_form_submit(); ?>
	
		</form>

	</div>

</div>