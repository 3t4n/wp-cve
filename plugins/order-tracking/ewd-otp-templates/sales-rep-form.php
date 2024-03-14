<div <?php echo ewd_format_classes( $this->classes ); ?> >

	<div class='ewd-otp-tracking-results'>

		<?php $this->maybe_print_order_tracking(); ?>

		<?php $this->maybe_print_sales_rep_results(); ?>

	</div>

	<?php $this->maybe_print_update_message(); ?>

	<div class='ewd-otp-order-tracking-form-div'>

		<h3>
			<?php echo esc_html( $this->sales_rep_form_title ); ?>
		</h3>

		<div class='ewd-otp-tracking-form-instructions'>
			<?php echo esc_html( $this->sales_rep_form_instructions ); ?>
		</div>

		<form action='#' method='post' <?php echo $this->get_form_target(); ?> class='ewd-otp-sales-rep-form'>

			<input type='hidden' name='ewd_otp_form_type' value='sales_rep_form'>

			<?php $this->print_sales_rep_identifier_input(); ?>

			<?php $this->maybe_print_sales_rep_email_input(); ?>

			<?php $this->print_sales_rep_form_submit(); ?>

		</form>

	</div>

</div>