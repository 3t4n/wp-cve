<?php
/**
 * All of the parameters passed to the function where this file is being required are accessible in this scope:
 *
 * @param array    $attributes     The array of attributes for this block.
 * @param string   $content        Rendered block output. ie. <InnerBlocks.Content />.
 * @param WP_Block $block_instance The instance of the WP_Block class that represents the block being rendered.
 *
 * @package blockons
 */
$product_id = isset($attributes['selectedProduct']) ? $attributes['selectedProduct']['value'] : '';

if (!$product_id) return null;

$product = wc_get_product( $product_id );
$custom_classes = 'align-' . $attributes['alignment'] . " layout-" . $attributes['layout'];
$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id($product_id), 'full' );
$featured_image_alt = get_post_meta($product_id, '_wp_attachment_image_alt', true);
?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes(['class' => $custom_classes]) ); ?>>
	<div class="blockons-wc-featured-product-block <?php echo ($attributes['layoutSwitch'] == "one" && isset($attributes['layoutSwitch'])) ? sanitize_html_class('switch') : sanitize_html_class('noswitch'); ?>" style="background-color: <?php echo isset($attributes['blockBgColor']) ? esc_attr($attributes['blockBgColor']) : '#FFF'; ?>; padding: <?php echo isset($attributes['padding']) ? esc_attr($attributes['padding'] . "px") : '15px'; ?>">
		<div class="blockons-wc-featured-product-detail" style="background-color: <?php echo isset($attributes['blockDetailColor']) ? esc_attr($attributes['blockDetailColor']) : '#FFF'; ?>; color: <?php echo ($attributes['layout'] == "two" && $attributes['blockFontColor'] == "inherit") ? esc_attr('#FFF') : esc_attr($attributes['blockFontColor']); ?>; width: <?php echo $attributes['layout'] == "one" ? esc_attr($attributes['detailWidth'] . "%") : esc_attr("auto");?>; padding: <?php echo isset($attributes['innerPadding']) ? esc_attr(floor((int)$attributes['innerPadding'] / 2) . "px " . (int)$attributes['innerPadding'] . "px") : '30px'; ?>;">
			<h2 class="blockons-wc-featured-product-title" style="<?php echo isset($attributes['titleColor']) ? esc_attr('color: ' . $attributes['titleColor']) . ';' : ''; ?> <?php echo isset($attributes['titleSize']) ? esc_attr('font-size: ' . $attributes['titleSize'] . 'px;') : ''; ?>">
				<?php echo esc_html( $product->get_title() ); ?>
			</h2>

			<?php if ($attributes['showPrice']) : ?>
				<div class="blockons-wc-featured-product-price" style="<?php echo isset($attributes['priceColor']) ? esc_attr('color: ' . $attributes['priceColor']) . ';' : ''; ?> <?php echo isset($attributes['priceSize']) ? esc_attr('font-size: ' . $attributes['priceSize'] . 'px;') : ''; ?>">
					<?php echo $product->get_price_html(); ?>
				</div>
			<?php endif; ?>

			<?php if ($attributes['showDesc']) : ?>
				<p><?php echo esc_html( $product->get_short_description() ); ?></p>
			<?php endif; ?>

			<?php if ($attributes['showButton']) : ?>
				<div class="blockons-wc-featured-product-btn">

					<?php if ($attributes['buttonType'] == 'atc') : ?>
						<?php echo do_shortcode("[add_to_cart id='" . $product_id . "' show_price='false' class='wc-fproduct-button']"); ?>
					<?php else : ?>
						<a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="wc-fproduct-btn" <?php echo isset($attributes['buttonTarget']) && $attributes['buttonTarget'] == true ? 'target="_blank"' : ''; ?>>
							<?php echo esc_html($attributes['buttonText']); ?>
						</a>
					<?php endif; ?>

				</div>
			<?php endif; ?>
		</div>
		<div class="blockons-wc-featured-product-image" style="background-image: url('<?php echo esc_url($featured_image_url); ?>');">
			
			<?php if ($attributes['layout'] == "two" && isset($attributes['layoutTwoOverlay'])) : ?>
				<div class="img-overlay" <?php echo isset($attributes['overlayOpacity']) ? 'style="opacity: ' . esc_attr($attributes['overlayOpacity']) . '"' : ''; ?>></div>
			<?php endif; ?>

			<img src="<?php echo esc_url($featured_image_url); ?>" <?php echo esc_attr($featured_image_alt) ? 'alt="' . esc_attr($featured_image_alt) . '"' : ''; ?> style="height: <?php echo $attributes['imgHeight'] != "auto" ? esc_attr($attributes['imgHeight'] . "px") : esc_attr("auto"); ?>" />
		</div>
	</div>
</div>
