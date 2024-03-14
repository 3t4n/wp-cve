<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used for Order Bump Reports and Order Bump Sales by Funnel - Stats.
 *
 * @link       https://wpswings.com/?utm_source=wpswings-official&utm_medium=order-bump-org-backend&utm_campaign=official
 * @since      1.4.0
 *
 * @package    Upsell_Order_Bump_Offer_For_Woocommerce
 * @subpackage Upsell_Order_Bump_Offer_For_Woocommerce/admin/reporting/templates
 */

/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get all Order Bumps.
$order_bumps = get_option( 'wps_ubo_bump_list' );

?>

<div class="wps_upsell_bumps_list" >

	<div class="wps_ubo_reporting_heading" >
		<h2><?php esc_html_e( 'Order Bump Sales - Reports', 'upsell-order-bump-offer-for-woocommerce' ); ?></h2>
		<a target="_blank" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-reports&tab=wps_order_bump' ) ); ?>"><?php esc_html_e( 'Visit here &rarr;', 'upsell-order-bump-offer-for-woocommerce' ); ?></a>
	</div>

	<hr class="wps_ubo_reporting_funnel_stats_hr">

	<div class="wps_ubo_stats_heading" ><h2><?php esc_html_e( 'Order Bump - Behavioral Analytics', 'upsell-order-bump-offer-for-woocommerce' ); ?></h2></div>

	<?php if ( empty( $order_bumps ) ) : ?>

		<p class="wps_upsell_bump_no_bump"><?php esc_html_e( 'No Order Bumps added', 'upsell-order-bump-offer-for-woocommerce' ); ?></p>

	<?php endif; ?>

	<?php if ( ! empty( $order_bumps ) ) : ?>
		<table>
			<tr>
				<th><?php esc_html_e( 'Name', 'upsell-order-bump-offer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'View Count', 'upsell-order-bump-offer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Success Count', 'upsell-order-bump-offer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Offer Accept Count', 'upsell-order-bump-offer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Offer Remove Count', 'upsell-order-bump-offer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Conversion Rate', 'upsell-order-bump-offer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Total Sales', 'upsell-order-bump-offer-for-woocommerce' ); ?></th>
			</tr>

			<!-- Foreach Funnel start -->
			<?php
			foreach ( $order_bumps as $key => $value ) :

				?>

				<tr>		
					<!-- Funnel Name -->
					<td>
						<a class="wps_upsell_bump_list_name" href="?page=upsell-order-bump-offer-for-woocommerce-setting&tab=creation-setting&bump_id=<?php echo esc_html( $key ); ?>"><?php echo esc_html( $value['wps_upsell_bump_name'] ); ?></a>
					</td>

					<!-- View Count - Offer viewed on the Checkout page -->
					<td>

						<?php

						$offer_view_count = ! empty( $value['offer_view_count'] ) ? $value['offer_view_count'] : 0;

						echo esc_html( $offer_view_count );

						?>

					</td>

					<!-- Success Count - Offer accepted, Order Placed and reached Thankyou page -->
					<td>

						<?php

						$bump_success_count = ! empty( $value['bump_success_count'] ) ? $value['bump_success_count'] : 0;

						echo esc_html( $bump_success_count );

						?>
					</td>

					<!-- Offer Accepted - Offer added to cart -->
					<td>

						<?php

						$offer_accept_count = ! empty( $value['offer_accept_count'] ) ? $value['offer_accept_count'] : 0;

						echo esc_html( $offer_accept_count );

						?>
					</td>

					<!-- Offer Removed - Offer removed after being added -->
					<td>

						<?php

						$offer_remove_count = ! empty( $value['offer_remove_count'] ) ? $value['offer_remove_count'] : 0;

						echo esc_html( $offer_remove_count );

						?>

					</td>

					<!-- Conversion Rate - % ratio of View vs Success count -->
					<td>

						<?php

						if ( ! empty( $offer_view_count ) ) {

							$conversion_rate = ( $bump_success_count * 100 ) / $offer_view_count;
						} else {

							$conversion_rate = 0;
						}

						$conversion_rate = number_format( (float) $conversion_rate, 2 );

						echo '<div class="wps_ubo_stats_conversion_rate"><p>' . esc_html( $conversion_rate ) . esc_html__( '%', 'upsell-order-bump-offer-for-woocommerce' ) . '</p><div>';

						?>
					</td>

					<!-- Total Sales - Total price amount of offers checked out -->
					<td>

						<?php

						$bump_total_sales = ! empty( $value['bump_total_sales'] ) ? $value['bump_total_sales'] : 0;

						$bump_total_sales = number_format( (float) $bump_total_sales, 2 );
						?>
						<div class="wps_ubo_stats_total_sales"><p>
						<?php
						echo esc_html( get_woocommerce_currency_symbol() );
						echo esc_html( $bump_total_sales );
						?>
						</p><div>
					</td>

				</tr>
			<?php endforeach; ?>
			<!-- Foreach Funnel end -->
		</table>
	<?php endif; ?>
</div>
