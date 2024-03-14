<?php
/**
 * Skrill Payments Update Order
 *
 * The file is for displaying button update order at order detail (admin)
 * Copyright (c) Skrill
 *
 * @package Skrill/Templates
 * @located at  /template/admin/order
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<style>
	.skrill-warning {
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		border: 3px solid red;
		background-color: #d7cad2;
		color: #444 !important;
	}
</style>

<?php if ( $is_show_warning_message ) { ?>
	<p class="form-field form-field-wide skrill-warning" style="padding: 10px !important;">
	<?php echo esc_html( $warning_message ); ?>
	</p>
<?php } ?>

<?php if ( $is_show_update_order ) { ?>
	<p class="form-field form-field-wide" style="text-align:right">
		<label for="order_status">&nbsp;</label>
		<a href="<?php echo esc_html( $update_order_url ); ?>" class="button save_order button-primary" >
	<?php echo esc_html( __( 'Update Order', 'wc-skrill' ) ); ?>
		</a>
	</p>
<?php } ?>
