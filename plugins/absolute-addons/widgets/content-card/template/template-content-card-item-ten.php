<?php
/**
 * Template Style Ten for Content Card
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

$uid = wp_unique_id( 'CONTENT_CARD_TEN_ID_' );
?>

<div class="content-card-box">
	<div class="content-card-box-inner">
		<?php if ( ! empty( $settings['content_card_box_image'] ) ) {?>
			<div class="content-card-box-img">
				<svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 310 310" style="enable-background:new 0 0 310 310;" xml:space="preserve">
					<style type="text/css">
						.st0<?php echo esc_attr( $uid ); ?>{fill:url(#SVGID_1_<?php echo esc_attr( $uid ); ?>);}
					</style>
					<g>
						<radialGradient id="SVGID_1_<?php echo esc_attr( $uid ); ?>" cx="155" cy="155" r="155" gradientUnits="userSpaceOnUse">
							<stop  offset="0" style="stop-color:#FFFFFF"/>
							<stop  offset="1" style="stop-color:#F6F6F6"/>
						</radialGradient>
						<circle class="st0<?php echo esc_attr( $uid ); ?>" cx="155" cy="155" r="155"/>
						<g>
							<defs>
								<circle id="SVGID_2_<?php echo esc_attr( $uid ); ?>" cx="155" cy="155" r="155"/>
							</defs>
							<clipPath id="SVGID_3_<?php echo esc_attr( $uid ); ?>">
								<use xlink:href="#SVGID_2_<?php echo esc_attr( $uid ); ?>"  style="overflow:visible;"/>
							</clipPath>
							<g style="clip-path:url(#SVGID_3_<?php echo esc_attr( $uid ); ?>);">

								<image style="overflow:visible;" width="798" height="778" xlink:href="<?php echo esc_url( $settings['content_card_box_image']['url'] ); ?>" transform="matrix(0.5849 0 0 0.5849 -71.0683 -120.2695)">
								</image>
							</g>
						</g>
					</g>
				</svg>
			</div>
		<?php } ?>
		<div class="content-card-box-content">
			<?php if ( ! empty( $settings['content_card_box_title'] ) ) {?>
			<h2 <?php $this->print_render_attribute_string( 'content_card_box_title' ); ?> ><?php absp_render_title( $settings['content_card_box_title'] ); ?></h2>
			<?php } ?>
			<div class="content-card-box-meta">
				<?php if ( '' !== $settings['content_card_box_sale_price'] ) {?>
				<span class="content-card-box-sale-price">
					<?php echo esc_html( $settings['content_card_price_currency_symbol'] . $price_array['only_int'] .$price_array['decimal_point'] . $price_array['after_decimal_point']); ?>
				</span>
				<?php } ?>
				<div class="content-card-box-card-button">
					<?php $this->render_card_button( $settings, true ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
