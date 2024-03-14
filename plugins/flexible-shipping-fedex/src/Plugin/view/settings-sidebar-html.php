<?php
/**
 * Settings sidebar.
 *
 * @package WPDesk\FlexibleShippingFedex
 */

/**
 * Params.
 *
 * @var $pro_url string .
 */
?>
<div class="wpdesk-metabox">
	<div class="wpdesk-stuffbox">
		<h3 class="title"><?php esc_html_e( 'Get FedEx WooCommerce Live Rates PRO!', 'flexible-shipping-fedex' ); ?></h3>
		<div class="inside">
			<div class="main">
				<ul>
					<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Different ways of packing products', 'flexible-shipping-fedex' ); ?></li>
					<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Premium Support', 'flexible-shipping-fedex' ); ?></li>
					<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Custom Origin', 'flexible-shipping-fedex' ); ?></li>
					<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Handling Fees', 'flexible-shipping-fedex' ); ?></li>
					<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Multicurrency Support', 'flexible-shipping-fedex' ); ?></li>
					<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Delivery Dates', 'flexible-shipping-fedex' ); ?></li>
				</ul>

				<a class="button button-primary" href="<?php echo esc_attr( $pro_url ); ?>" target="_blank"><?php esc_html_e( 'Upgrade Now &rarr;', 'flexible-shipping-fedex' ); ?></a>
			</div>
		</div>
	</div>
</div>
