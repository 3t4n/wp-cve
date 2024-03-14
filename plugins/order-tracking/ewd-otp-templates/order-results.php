<div class='ewd-otp-order-results'>

	<?php if ( $this->get_option( 'display-print-button' ) ) { ?>

		<div class='ewd-otp-tracking-results-field'>

			<button class='ewd-otp-print-results' data-cssurl='<?php echo EWD_OTP_PLUGIN_URL . '/css/ewd-otp-print.css'; ?>'>
				<?php echo esc_html( $this->get_label( 'label-order-print-button' ) ); ?>
			</button>

		</div>

	<?php } ?>

	<?php $this->maybe_print_order_graphic(); ?>

	<?php if ( $this->get_order_number_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-order-number' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value'>
				<?php echo esc_html( $this->order->number ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_name_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-order-name' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value'>
				<?php echo esc_html( $this->order->name ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_notes_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-order-notes' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value'>
				<?php echo esc_html( $this->order->notes_public ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_customer_notes_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-customer-notes' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value ewd-otp-customer-notes-value'>
				<?php echo esc_html( $this->order->customer_notes ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_customer_name_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-customer-display-name' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value ewd-otp-customer-name-value'>
				<?php echo esc_html( $this->get_customer_name() ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_customer_email_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-customer-display-email' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value ewd-otp-customer-email-value'>
				<?php echo esc_html( $this->get_customer_email() ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_sales_rep_first_name_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-sales-rep-display-first-name' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value ewd-otp-sales-rep-first-name-value'>
				<?php echo esc_html( $this->get_sales_rep_first_name() ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_sales_rep_last_name_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-sales-rep-display-last-name' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value ewd-otp-sales-rep-last-name-value'>
				<?php echo esc_html( $this->get_sales_rep_last_name() ); ?>
			</div>

		</div>

	<?php } ?>

	<?php if ( $this->get_order_sales_rep_email_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-sales-rep-display-email' ) ); ?>:
			</div>

			<div class='ewd-otp-tracking-results-value ewd-otp-sales-rep-email-value'>
				<?php echo esc_html( $this->get_sales_rep_email() ); ?>
			</div>

		</div>

	<?php } ?>

	<div class='ewd-otp-tracking-results-custom-fields'>
		<?php $this->print_order_custom_fields(); ?>
	</div>

	<?php $this->maybe_print_order_payment(); ?>

	<?php $this->maybe_print_order_map(); ?>

	<?php $this->print_order_statuses_header(); ?>

	<?php $this->print_order_statuses(); ?>

	<?php $this->maybe_print_order_update_location_and_status(); ?>

	<?php if ( $this->get_order_customer_notes_display() ) { ?>
		
		<div class='ewd-otp-tracking-results-field'>

			<div class='ewd-otp-tracking-results-label'>
				<?php echo esc_html( $this->get_label( 'label-customer-notes' ) ); ?>:
			</div>

			<div id='ewd-otp-customer-notes' class='ewd-otp-tracking-results-value'>
				
				<form action='#' method='post' id='ewd-otp-customer-notes-form'>

					<input type='hidden' name='ewd_otp_order_id' value='<?php echo esc_attr( $this->order->id ); ?>' />
					<input type='hidden' name='ewd_otp_order_number' value='<?php echo esc_attr( $this->order->number ); ?>' />

					<textarea name='ewd_otp_customer_notes'><?php echo esc_html( $this->order->customer_notes ); ?></textarea>
					
					<input class='ewd-otp-submit' type='submit' name='ewd_otp_customer_notes_submit' value='<?php echo esc_attr( $this->customer_notes_submit ); ?>' />

				</form>

			</div>

		</div>

	<?php } ?>


</div>