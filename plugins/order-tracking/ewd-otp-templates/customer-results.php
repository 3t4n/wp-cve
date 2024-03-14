<div class='ewd-otp-customer-information'>

	<h3>
		<?php _e( 'Customer Information', 'order-tracking' ); ?>
	</h3>

	<?php $this->maybe_print_customer_name(); ?>

	<?php $this->maybe_print_customer_email(); ?>

	<?php $this->print_customer_custom_fields(); ?>

	<table class='ewd-otp-matching-orders <?php echo ( $this->get_option( 'disable-ajax-loading' ) ? 'ewd-otp-disable-ajax' : '' ); ?>'>

		<thead>

			<?php $this->print_customer_orders_header(); ?>

		</thead>

		<tbody>

			<?php $this->print_customer_orders(); ?>

		</tbody>

	</table>

	<?php $this->maybe_print_customer_download_button(); ?>

</div>