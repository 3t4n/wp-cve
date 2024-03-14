<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$completed_order_with_tracking = $this->completed_order_with_tracking();
$completed_order_with_zero_balance = $this->completed_order_with_zero_balance();
$completed_order_with_do_connection = $this->completed_order_with_do_connection();
$total_orders = $completed_order_with_tracking + $completed_order_with_zero_balance + $completed_order_with_do_connection;
?>
<div class="tools_tab_ts4wc tools_tab">
	<div class="trackship-notice p15 inner_div">
		<?php /* translators: %s: search for a orders */ ?>
		<p><?php printf( esc_html__( 'We detected %1$s Shipped orders from the last 30 days that were not sent to TrackShip, you can bulk send them to TrackShip', 'trackship-for-woocommerce'), esc_html( $total_orders ) ) ; ?><button class="button-primary button-trackship bulk_shipment_status_button tools-ts-button" <?php echo 0 == $total_orders ? 'disabled' : ''; ?>><?php esc_html_e( 'Get Shipment Status', 'trackship-for-woocommerce' ); ?></button></p>
	</div>
	<div class="tracking_notification_log_delete p15 inner_div">
		<p><?php esc_html_e( 'Delete notifications logs more than 30 days', 'trackship-for-woocommerce' ); ?></p>
		<button class="button-primary button-trackship-red delete_notification tools-ts-button" style="line-height:35px;"><?php esc_html_e( 'Delete notifications logs', 'trackship-for-woocommerce' ); ?></button>
		<?php $nonce = wp_create_nonce( 'wc_ast_tools'); ?>
		<input type="hidden" id="wc_ast_tools" name="wc_ast_tools" value="<?php echo esc_attr( $nonce ); ?>" />
	</div>
	<div class="trackship-verify-table p15 inner_div">
		<p>
			<?php esc_html_e( 'Verify if all TrackShip database tables are present.', 'trackship-for-woocommerce' ); ?>
			<button class="button-primary button-trackship verify_database_table tools-ts-button"><?php esc_html_e( 'Verify tables', 'trackship-for-woocommerce' ); ?></button>
		</p>
	</div>
	<?php if ( get_trackship_settings( 'old_user' ) ) { ?>
		<?php $auto = isset( $_GET['auto'] ) ? sanitize_text_field( $_GET['auto'] ) : ''; ?>
		<div class="trackship-update-tracking-info p15 inner_div">
			<p>
				<?php esc_html_e( 'Updating tracking details during migration from older to newer version.', 'trackship-for-woocommerce' ); ?>
				<button class="button-primary button-trackship bulk_migration tools-ts-button" data-auto="<?php echo esc_html($auto); ?>"><?php esc_html_e( 'Migration', 'trackship-for-woocommerce' ); ?></button>
			</p>
		</div>
	<?php } ?>
</div>
