<?php
/**
 * Shipping cost settings
 *
 * @var string $tab_label   Current tab label.
 * @var array  $screenshots List of screenshots.
 *
 * @package NovaPosta\Templates\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>

<h2>
	<?php
	echo wp_kses(
		$tab_label,
		[
			'span' => [
				'class' => true,
			],
		]
	);
	?>
</h2>
<p class="shipping-nova-poshta-education-description">
	<?php esc_html_e( 'Do you want to make an accurate calculation of delivery? With the help of the "shipping cost" feature, the shipping cost will be automatically calculated according to the overall dimensions of the product.', 'shipping-nova-poshta-for-woocommerce' ); ?>
</p>
<p class="shipping-nova-poshta-education-description">
	<?php esc_html_e( 'Do you have missing dimensions? Never mind, you can use the formula to calculate the cost on the settings page, category page, or product page.', 'shipping-nova-poshta-for-woocommerce' ); ?>
</p>
<p class="shipping-nova-poshta-education-description">
	<?php esc_html_e( 'For example, you have two categories on your site with small and large goods, which have approximate dimensions. Specify formulas for their calculation and the calculation will become more accurate than ever.', 'shipping-nova-poshta-for-woocommerce' ); ?>
</p>
<div class="shipping-nova-poshta-education-screenshots-wrap">
	<div class="shipping-nova-poshta-education-screenshots">
		<?php
		foreach ( $screenshots as $screenshot_id => $screenshot_description ) {
			$full_img     = NOVA_POSHTA_URL . sprintf( 'assets/build/img/education/shipping-cost-%d.png', $screenshot_id );
			$thumb_img    = NOVA_POSHTA_URL . sprintf( 'assets/build/img/education/thumbnail-shipping-cost-%d.png', $screenshot_id );
			$thumb_img_x2 = NOVA_POSHTA_URL . sprintf( 'assets/build/img/education/thumbnail-shipping-cost-%d@2x.png', $screenshot_id );
			?>
			<figure class="shipping-nova-poshta-education-screenshot">
				<a
					href="<?php echo esc_url( $full_img ); ?>"
					data-lity
					data-lity-desc="<?php echo esc_html( $screenshot_description ); ?>"
				>
					<img
						src="<?php echo esc_url( $thumb_img ); ?>"
						srcset="<?php echo esc_url( $thumb_img_x2 ); ?> 2x"
						alt="<?php echo esc_html( $screenshot_description ); ?>"
					>
				</a>
				<figcaption><?php echo esc_html( $screenshot_description ); ?></figcaption>
			</figure>
		<?php } ?>
	</div>
</div>
<a href="https://wp-unit.com/product/nova-poshta-pro/" target="_blank" rel="noopener noreferrer" class="button button-primary shipping-nova-poshta-education-button">
	<?php esc_html_e( 'Upgrade Now', 'shipping-nova-poshta-for-woocommerce' ); ?>
</a>
