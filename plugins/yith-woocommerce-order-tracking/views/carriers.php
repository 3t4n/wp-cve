<?php
/**
 * Carriers view
 *
 * @package YITH\OrderTracking\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
	<div class="yith-ywot-empty-state-container">
		<div class="yith-ywot-cta-container">
			<?php
				yith_plugin_fw_get_component(
					array(
						'type'     => 'list-table-blank-state',
						'icon_url' => YITH_YWOT_ASSETS_URL . '/images/carrier-blocked.svg',
						'message'  => __( 'The Carriers list feature is available only in the premium version.', 'yith-woocommerce-order-tracking' ),
						'cta'      => array(
							'title' => __( 'Get premium', 'yith-woocommerce-order-tracking' ),
							'url'   => add_query_arg(
								array(
									'page' => 'yith_woocommerce_order_tracking_panel',
									'tab'  => 'premium',
								),
								admin_url( 'admin.php' )
							),
						),
					)
				);
			?>
		</div>
	</div>
<?php
