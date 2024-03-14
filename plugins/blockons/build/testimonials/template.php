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
$custom_classes = 'align-' . $attributes['alignment'] . ' layout-' . $attributes['testLayout'] . ' style-' . $attributes['testStyle'];

$sliderOptions = array(
	"autoHeight" => true,
	"effect" => $attributes['transition'],
	"slidesPerView" => $attributes['transition'] === "slide" ? $attributes['perView'] : 1,
	"spaceBetween" => $attributes['spaceBetween'],
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
	<div
		class="blockons-slider slider <?php echo isset($attributes['showOnHover']) && $attributes['showOnHover'] == true ? sanitize_html_class("controlsOnHover") : ""; ?> navigation-<?php echo sanitize_html_class($attributes['navigationStyle']); ?> navigation-<?php echo sanitize_html_class($attributes['navigationColor']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationStyle']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationColor']); ?> <?php echo isset($attributes['navigationArrow']) && $attributes['navigationArrow'] == "ban" ? sanitize_html_class("default-icon") : sanitize_html_class("custom-icon"); ?> arrows-<?php echo sanitize_html_class($attributes['navigationArrow']); ?>" id="<?php echo esc_attr($attributes['uniqueId']); ?>" data-settings="<?php echo esc_attr(json_encode((object)$sliderOptions)); ?>">
		<div class="testimonials-swiper-<?php echo esc_attr($attributes['uniqueId']); ?> swiper">
			<div class="swiper-wrapper">
				<?php foreach ($attributes['sliderSlides'] as $i => $slide) { ?>
					<div class="swiper-slide">
						<div class="swiper-slide-inner">

							<div class="blockons-slide-text" style="<?php echo isset($attributes['fontColor']) ? "color: " . esc_attr($attributes['fontColor']) . ";" : ""; ?> <?php echo isset($attributes['testStyle']) && $attributes['testStyle'] == "two" ? 'background-color: ' . esc_url($attributes['bgColor']) . ';' : ''; ?>">
								<?php if (isset($attributes['showStars']) && $attributes['showStars'] == true) : ?>
									<div class="blockons-star-ratings">
										<span class="fa-solid fa-star blockons-star <?php echo ($slide['rating'] && $slide['rating'] >= 1) ? sanitize_html_class("checked") : ""; ?>"></span>
										<span class="fa-solid fa-star blockons-star <?php echo ($slide['rating'] && $slide['rating'] >= 2) ? sanitize_html_class("checked") : ""; ?>"></span>
										<span class="fa-solid fa-star blockons-star <?php echo ($slide['rating'] && $slide['rating'] >= 3) ? sanitize_html_class("checked") : ""; ?>"></span>
										<span class="fa-solid fa-star blockons-star <?php echo ($slide['rating'] && $slide['rating'] >= 4) ? sanitize_html_class("checked") : ""; ?>"></span>
										<span class="fa-solid fa-star blockons-star <?php echo ($slide['rating'] && $slide['rating'] >= 5) ? sanitize_html_class("checked") : ""; ?>"></span>
									</div>
								<?php endif; ?>

								<?php if (isset($attributes['testStyle']) && $attributes['testStyle'] == "two") : ?>
									<span class="corner" style="<?php echo isset($attributes['bgColor']) ? "borderColor: " . esc_attr($attributes['bgColor']) . ";" : ""; ?>"></span>
								<?php endif; ?>

								<?php if (isset($attributes['showQuotes']) && $attributes['showQuotes'] == true) : ?>
									<span class="blockons-fontawesome fa-solid fa-quote-left" style="<?php echo isset($attributes['quoteSize']) ? "font-size: " . esc_attr($attributes['quoteSize']) . "px;" : ""; ?> <?php echo isset($attributes['quotesColor']) ? "color: " . esc_attr($attributes['quotesColor']) . ";" : ""; ?> <?php echo isset($attributes['quotesOpacity']) ? "opacity: " . esc_attr($attributes['quotesOpacity']) . ";" : ""; ?>"></span>
								<?php endif; ?>

								<div class="blockons-slide-text-txt">
									<?php echo esc_html($slide['title']); ?>
								</div>

								<?php if (isset($attributes['showQuotes']) && $attributes['showQuotes'] == true) : ?>
									<span class="blockons-fontawesome fa-solid fa-quote-right" style="<?php echo isset($attributes['quoteSize']) ? "font-size: " . esc_attr($attributes['quoteSize']) . "px;" : "jj"; ?> <?php echo isset($attributes['quotesColor']) ? "color: " . esc_attr($attributes['quotesColor']) . ";" : ""; ?> <?php echo isset($attributes['quotesOpacity']) ? "opacity: " . esc_attr($attributes['quotesOpacity']) . ";" : ""; ?>"></span>
								<?php endif; ?>
							</div>

							<div class="blockons-slide-author">

								<?php if (isset($attributes['showIcon']) && $attributes['showIcon'] == true) : ?>
									<div class="blockons-slide-author-img <?php echo isset($slide['authorImg']) && isset($slide['authorImg']['url']) ? "hasimg" : "noimg"; ?>" <?php echo isset($slide['authorImg']) && isset($slide['authorImg']['url']) ? 'style="background-image: url(' . esc_url($slide['authorImg']['url']) . ');"' : ''; ?>>
										<?php if (isset($slide['authorImg']) && isset($slide['authorImg']['url'])) : ?>
											<!-- Image -->
										<?php else : ?>
											<span class="blockons-fontawesome fa-solid fa-user"></span>
										<?php endif; ?>
									</div>
								<?php endif; ?>

								<div class="blockons-slide-author-txt">
									<div class="blockons-slide-author-txt-auth" style="<?php echo isset($attributes['nameColor']) ? "color: " . esc_attr($attributes['nameColor']) . ";" : ""; ?>">
										<?php echo esc_html($slide['author']); ?>
									</div>
									
									<?php if (isset($attributes['showPosition']) && $attributes['showPosition'] == true) : ?>
										<div class="blockons-slide-author-txt-pos" style="<?php echo isset($attributes['posColor']) ? "color: " . esc_attr($attributes['posColor']) . ";" : ""; ?>">
											<?php echo esc_html($slide['authorPos']); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>

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
