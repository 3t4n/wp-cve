<?php
/**
 * Template Style One Content Card
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
		<div class="content-card-box-title-inner">
			<?php if ( ! empty( $settings['content_card_box_title'] ) ) { ?>
				<h2 <?php $this->print_render_attribute_string( 'content_card_box_title' ); ?> ><?php absp_render_title( $settings['content_card_box_title'] ); ?></h2>
			<?php } ?>
			<?php if ( ! empty( $settings['content_card_box_sub_title'] ) ) { ?>
				<span <?php $this->print_render_attribute_string( 'content_card_box_sub_title' ); ?>><?php absp_render_title( $settings['content_card_box_sub_title'] ); ?></span>
			<?php } ?>
		</div>
	</div>
	<?php if ( ! empty( $settings['content_card_box_image'] ) ) { ?>
		<div class="content-card-box-inner">
			<div class="<?php echo esc_attr( $cart_image_class ); ?>">
				<img src="<?php echo esc_url( $settings['content_card_box_image']['url'] ); ?>" alt=""/>
				<?php if ( '' !== $settings['content_card_box_sale_price'] ) { ?>
					<span class="content-card-box-sale-price"><?php echo esc_html( $settings['content_card_price_currency_symbol'] . $price_array['only_int'] . $price_array['decimal_point'] . $price_array['after_decimal_point'] ); ?></span>
				<?php } ?>

			</div>
		</div>
	<?php } ?>
	<div class="content-card-box-inner">
		<div class="content-card-box-content">
			<?php absp_render_content( $settings['content_card_box_content'] ); ?>
			<?php $this->render_card_button( $settings ); ?>
		</div>
	</div>
</div>
