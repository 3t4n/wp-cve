<div <?php echo ewd_format_classes( $this->classes ); ?> >

	<div class='ewd-otp-tracking-results'>
		<?php $this->maybe_print_order_results(); ?>
	</div>

	<?php $this->maybe_print_update_message(); ?>

	<div class='ewd-otp-order-tracking-form-div'>

		<h3>
			<?php echo esc_html( $this->order_form_title ); ?>
		</h3>

		<div class='ewd-otp-tracking-form-instructions'>
			<?php echo esc_html( $this->order_form_instructions ); ?>
		</div>

		<?php if ( empty( $this->show_orders ) ) { ?>

			<form action='#' method='post' <?php echo $this->get_form_target(); ?> class='ewd-otp-tracking-form'>
	
				<input type='hidden' name='ewd_otp_form_type' value='order_form' />
	
				<?php $this->print_order_number_input(); ?>
	
				<?php $this->maybe_print_order_email_input(); ?>
	
				<?php $this->print_order_form_submit(); ?>
	
			</form>

		<?php } ?>

		<?php if ( ! empty( $this->show_orders ) ) { ?>

			<?php $this->print_all_available_order_links(); ?>

		<?php } ?>

	</div>

</div>