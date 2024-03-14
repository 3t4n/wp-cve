<?php
/**
 * Template Style Two Content Card
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
<div class="content-card-box content-card-style2-shadow">

	<div class="content-card-box-wrapper-style2">
		<div class="content-card-box-inner">
			<div class="content-card-box-content">
				<?php if ( ! empty( $settings['content_card_box_title'] ) ) { ?>
					<h2 <?php $this->print_render_attribute_string( 'content_card_box_title' ); ?> ><?php absp_render_title( $settings['content_card_box_title'] ); ?></h2>
				<?php } ?>
				<?php if ( ! empty( $settings['content_card_box_sub_title_two'] ) ) { ?>
					<span <?php $this->print_render_attribute_string( 'content_card_box_sub_title_two' ); ?>><?php absp_render_title( $settings['content_card_box_sub_title_two'] ); ?></span>
				<?php } ?>
				<?php absp_render_content( $settings['content_card_box_content'] ); ?>
				<div class="content-card-box-product-price">
					<?php if ( '' !== $settings['content_card_box_regular_price'] ) { ?>
						<span class="content-card-box-regular-price"><s><?php echo esc_html( $settings['content_card_price_currency_symbol'] . $regular_price_array['price_only_int'] ); ?></s></span>
					<?php } ?>
					<?php if ( '' !== $settings['content_card_box_sale_price'] ) { ?>
						<span class="content-card-box-sale-price"><?php echo esc_html( $settings['content_card_price_currency_symbol'] . $price_array['only_int'] ); ?></span>
					<?php } ?>
					<?php $this->render_card_button( $settings ); ?>
				</div>
			</div>
		</div>
		<?php if ( ! empty( $settings['content_card_box_image'] ) ) { ?>
			<div class="content-card-box-inner">
				<div class="<?php echo esc_attr( $cart_image_class ); ?>">
					<img src="<?php echo esc_url( $settings['content_card_box_image']['url'] ); ?>" alt=""/>
					<?php if ( ! empty( $settings['content_card_box_label'] ) ) { ?>
						<span <?php $this->print_render_attribute_string( 'content_card_box_label' ); ?>><?php absp_render_title( $settings['content_card_box_label'] ); ?></span>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
