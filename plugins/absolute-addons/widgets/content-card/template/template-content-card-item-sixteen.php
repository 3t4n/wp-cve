<?php
/**
 * Template Style Sixteen for Content Card
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var array $settings
 * @var array $regular_price_array
 * @var array $price_array
 * @var string $cart_image_class
 */

$uid = wp_unique_id( 'SVG_ID_' );
?>
<div class="content-card-box">
	<div class="content-card-box-inner">
		<div class="content-card-box-content">
			<?php if ( '' !== $settings['content_card_box_sale_price'] ) { ?>
				<span class="content-card-box-sale-price">
				<?php echo esc_html( $settings['content_card_price_currency_symbol'] . $price_array['only_int'] . $price_array['decimal_point'] . $price_array['after_decimal_point'] ); ?>
			</span>
			<?php } ?>
			<?php if ( ! empty( $settings['content_card_box_title'] ) ) { ?>
				<h2 <?php $this->print_render_attribute_string( 'content_card_box_title' ); ?> ><?php absp_render_title( $settings['content_card_box_title'] ); ?></h2>
			<?php } ?>
			<?php absp_render_content( $settings['content_card_box_content'] ); ?>
			<?php $this->render_card_button( $settings ); ?>
		</div>
		<div class="<?php echo esc_attr( $cart_image_class ); ?>">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 380 422.78" style="enable-background:new 0 0 380 422.78;" xml:space="preserve">
				<g>
					<defs>
						<path id="<?php esc_attr( $uid ); ?>_1" d="M190,422.78L190,422.78c104.93,0,190-85.07,190-190V42.17C339.04,15.51,290.16,0,237.64,0
							C132.14,0,41.25,62.54,0,152.57v80.21C0,337.72,85.07,422.78,190,422.78z"/>
					</defs>
					<clipPath id="<?php esc_attr( $uid ); ?>_2">
						<use xlink:href="#<?php esc_attr( $uid ); ?>_1" style="overflow:visible;"/>
					</clipPath>
					<g transform="matrix(1 0 0 1 0 -1.525879e-05)" style="clip-path:url(#<?php esc_attr( $uid ); ?>_2);">
						<?php if ( ! empty( $settings['content_card_box_image'] ) ) { ?>
						<image style="overflow:visible;" width="2149" height="3079" xlink:href="<?php echo esc_url( $settings['content_card_box_image']['url'] ); ?>" transform="matrix(0.2563 0 0 0.2563 -86.9337 -163.1174)"></image>
						<?php } ?>
					</g>
				</g>
			</svg>
		</div>
	</div>
</div>
