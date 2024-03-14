<?php
/**
 * Template Style Fifteen for Content Card
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
		<div class="content-card-box-img-inner">
			<?php
			$this->render_box_image( $settings, $cart_image_class );
			$this->render_box_label( $settings );
			$this->render_box_border_shape( $settings );
			?>
		</div>
	</div>
	<div class="content-card-box-inner">
		<div class="content-card-box-content">
			<?php $this->render_card_box_title( $settings ); ?>
			<?php if ( ! empty( $settings['content_card_box_sub_title_fifteen'] ) ) { ?>
				<span <?php $this->print_render_attribute_string( 'content_card_box_sub_title_fifteen' ); ?>><?php absp_render_title( $settings['content_card_box_sub_title_fifteen'] ); ?></span>
			<?php } ?>
			<?php absp_render_content( $settings['content_card_box_content'] ); ?>
			<div class="content-card-box-product-price">
				<?php if ( '' !== $settings['content_card_box_regular_price'] ) { ?>
				<span class="content-card-box-regular-price">
					<s><?php echo esc_html( $settings['content_card_price_currency_symbol'] . $regular_price_array['price_only_int'] ); ?></s>
				</span>
				<?php } ?>
				<?php if ( '' !== $settings['content_card_box_sale_price'] ) { ?>
				<span class="content-card-box-sale-price"><?php echo esc_html( $settings['content_card_price_currency_symbol'] . $price_array['only_int'] ); ?></span>
				<?php } ?>
				<?php $this->render_card_button( $settings ); ?>
			</div>
		</div>
	</div>
</div>



