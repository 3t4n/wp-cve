<div class='ewd-otp-sales-rep-results'>

	<h3>
		<?php _e( 'Sales Rep Information', 'order-tracking' ); ?>
	</h3>

	<?php $this->maybe_print_sales_rep_first_name(); ?>

	<?php $this->maybe_print_sales_rep_last_name(); ?>

	<?php $this->maybe_print_sales_rep_email(); ?>

	<?php $this->print_sales_rep_custom_fields(); ?>

	<table class='ewd-otp-matching-orders <?php echo ( $this->get_option( 'disable-ajax-loading' ) ? 'ewd-otp-disable-ajax' : '' ); ?>'>

		<thead>

			<?php $this->print_sales_rep_orders_header(); ?>

		</thead>

		<tbody>

			<?php $this->print_sales_rep_orders(); ?>

		</tbody>

	</table>

	<?php $this->maybe_print_sales_rep_download_button(); ?>

</div>