<div <?php echo ewd_format_classes( $this->classes ); ?> >

	<div class='ewd-otp-tracking-results'>

		<?php $this->maybe_print_order_tracking(); ?>

		<?php $this->maybe_print_customer_results(); ?>

	</div>

	<?php $this->maybe_print_update_message(); ?>

	<div class='ewd-otp-order-tracking-form-div'>

		<h3>
			<?php echo esc_html( $this->customer_form_title ); ?>
		</h3>

		<div class='ewd-otp-tracking-form-instructions'>
			<?php echo esc_html( $this->customer_form_instructions ); ?>
		</div>

		<form action='#' method='post' <?php echo $this->get_form_target(); ?> class='ewd-otp-customer-form'>

			<input type='hidden' name='ewd_otp_form_type' value='customer_form'>

			<?php $this->print_customer_id_input(); ?>

			<?php $this->maybe_print_customer_email_input(); ?>

			<?php $this->print_customer_form_submit(); ?>

		</form>

	</div>

</div>