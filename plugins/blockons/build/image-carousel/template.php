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
$custom_classes = 'align-' . $attributes['alignment'];

$sliderOptions = array(
	"autoHeight" => true,
	"effect" => $attributes['transition'],
	"slidesPerView" => $attributes['transition'] != "fade" ? $attributes['perView'] : 1,
	"spaceBetween" => $attributes['perView'] > 1 ? $attributes['spaceBetween'] : 0,
	"loop" => $attributes['mode'] === "loop" ? true : false,
	"rewind" => $attributes['mode'] === "rewind" ? true : false,
	"navigation" => isset($attributes['navigation']) && $attributes['navigation'] == true ? array(
		"prevEl" => ".swiper-button-prev",
		"nextEl" => ".swiper-button-next",
	) : false,
	"pagination" => isset($attributes['pagination']) && $attributes['pagination'] == true ? array(
		"el" => ".swiper-pagination",
		"type" => $attributes['paginationStyle'] == "fraction" ? "fraction" : "bullets",
		"dynamicBullets" => $attributes['paginationStyle'] == "dynamicBullets" ? true : false,
		"clickable" => true,
	) : false,
);
?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes(['class' => $custom_classes]) ); ?>>
	<div class="blockons-slider slider <?php echo isset($attributes['showOnHover']) && $attributes['showOnHover'] == true ? sanitize_html_class("controlsOnHover") : ""; ?> navigation-<?php echo sanitize_html_class($attributes['navigationStyle']); ?> navigation-<?php echo sanitize_html_class($attributes['navigationColor']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationStyle']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationColor']); ?> <?php echo isset($attributes['navigationArrow']) && $attributes['navigationArrow'] == "ban" ? sanitize_html_class("default-icon") : sanitize_html_class("custom-icon"); ?> arrows-<?php echo sanitize_html_class($attributes['navigationArrow']); ?> <?php echo isset($attributes['captionPosition']) && $attributes['captionPosition'] != "" ? sanitize_html_class("caption-" . $attributes['captionPosition']) : sanitize_html_class("nocaption"); ?> <?php echo ((isset($attributes['captionPosition']) && $attributes['captionPosition'] == "over") && (isset($attributes['captionOnHover']) && $attributes['captionOnHover'] == true)) ? sanitize_html_class("caption-hover") : ""; ?> <?php echo isset($attributes['imageRoundness']) ? sanitize_html_class("bradius-" . $attributes['imageRoundness']) : ""; ?>" id="<?php echo esc_attr($attributes['uniqueId']); ?>" data-settings="<?php echo esc_attr(json_encode((object)$sliderOptions)); ?>">
		<div class="ic-swiper-<?php echo esc_attr($attributes['uniqueId']); ?> swiper">
			<div class="swiper-wrapper">
				<?php foreach ($attributes['sliderSlides'] as $i => $slide) { ?>
					<div class="swiper-slide">
						<div class="swiper-slide-inner">
							<div class="blockons-slider-img <?php echo ((isset($attributes['forceFullWidth']) && $attributes['forceFullWidth'] == true) || (isset($attributes['imageProportion']) && $attributes['imageProportion'] != "actual")) ? sanitize_html_class("imgfull") : ""; ?>" <?php echo isset($attributes['imageProportion']) && $attributes['imageProportion'] != "actual" ? 'style="background-image: url(' . $slide['imageUrl'] . ');"' : ''; ?>>
								<?php if ((isset($attributes['imageProportion']) && $attributes['imageProportion'] == "actual") && $slide['imageUrl']) : ?>
									<img src="<?php echo esc_url($slide['imageUrl']); ?>" alt="<?php echo esc_attr($slide['imageAlt']); ?>" />
								<?php else : ?>
									<img src="<?php echo esc_url(BLOCKONS_PLUGIN_URL . 'assets/images/' . $attributes['imageProportion'] . '.png'); ?>" alt="<?php echo esc_attr($slide['imageAlt']); ?>" />
								<?php endif; ?>
							</div>

							<?php if ((isset($attributes['captionPosition']) && $attributes['captionPosition'] != "none") && (isset($slide['imageCaption']) && $slide['imageCaption'] != "")) : ?>
								<div class="blockons-caption" <?php echo isset($attributes['captionPosition']) && $attributes['captionPosition'] == "below" && isset($attributes['captionBgColor']) ? 'style="background-color: ' . esc_attr($attributes['captionBgColor']) . ';"' : ''; ?>>
									
									<?php if (isset($attributes['captionPosition']) && $attributes['captionPosition'] == "over") : ?>
										<div class="blockons-caption-bg" style="<?php echo isset($attributes['captionBgColor']) ? 'background-color: ' . esc_attr($attributes['captionBgColor']) . ';' : ''; ?> <?php echo isset($attributes['captionBgOpacity']) ? 'opacity: ' . esc_attr($attributes['captionBgOpacity']) . ';' : ''; ?>"></div>
									<?php endif; ?>

									<div class="blockons-caption-inner" style="<?php echo isset($attributes['captionFontSize']) ? 'font-size: ' . esc_attr($attributes['captionFontSize']) . 'px;' : ''; ?> <?php echo isset($attributes['captionFontColor']) ? 'color: ' . esc_attr($attributes['captionFontColor']) . ';' : ''; ?>">
										<?php echo esc_html($slide['imageCaption']); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php } ?>
			</div>
			
			<?php if (isset($attributes['navigation']) && $attributes['navigation'] == true) : ?>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			<?php endif; ?>
			
			<?php if (isset($attributes['pagination']) && $attributes['pagination'] == true) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
		</div>
	</div>
</div>
