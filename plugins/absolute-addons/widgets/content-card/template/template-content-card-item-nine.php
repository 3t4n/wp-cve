<?php
/**
 * Template Style Nine for Content Card
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
?>
<div class="content-card-box">
	<div class="content-card-box-inner">
		<div class="<?php echo esc_attr( $cart_image_class ); ?>">
			<?php if ( ! empty( $settings['content_card_box_image'] ) ) { ?>
				<img src="<?php echo esc_url( $settings['content_card_box_image']['url'] ); ?>" alt=""/>
			<?php } ?>
			<?php if ( '' !== $settings['content_card_box_sale_price'] ) { ?>
				<span class="content-card-box-sale-price">
					<?php echo esc_html( $settings['content_card_price_currency_symbol'] . $price_array['only_int'] ); ?>
					<sup><?php echo esc_html( $price_array['after_decimal_point'] ); ?></sup>
				</span>
			<?php } ?>
		</div>
		<div class="content-card-box-content">
			<?php if ( ! empty( $settings['content_card_box_title'] ) ) { ?>
				<h2 <?php $this->print_render_attribute_string( 'content_card_box_title' ); ?> ><?php absp_render_title( $settings['content_card_box_title'] ); ?></h2>
			<?php } ?>
			<?php absp_render_content( $settings['content_card_box_content'] ); ?>
			<?php $this->render_card_button( $settings ); ?>
		</div>
	</div>
</div>
