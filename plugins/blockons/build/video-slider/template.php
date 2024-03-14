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
$custom_classes = 'align-' . $attributes['alignment'] . ' style-' . $attributes['sliderStyle'] . ' rn-' . $attributes['sliderRoundNess'];

$sliderOptions = array(
	"autoHeight" => true,
	"effect" => $attributes['transition'],
	"slidesPerView" => 1,
	"spaceBetween" => 0,
	"loop" => $attributes['mode'] === "loop" ? true : false,
	"rewind" => $attributes['mode'] === "rewind" ? true : false,
	"simulateTouch" => false,
	"navigation" => isset($attributes['navigation']) && $attributes['navigation'] == true ? array(
		"prevEl" => ".swiper-button-prev",
		"nextEl" => ".swiper-button-next",
	) : false,
	"pagination" => isset($attributes['pagination']) && $attributes['pagination'] == true ? array(
		"el" => ".swiper-pagination",
		"type" => $attributes['paginationStyle'] == "fraction" ? "fraction" : "bullets",
		"dynamicBullets" => $attributes['paginationStyle'] == "dynamicBullets" ? true : false,
		"clickable" => false,
	) : false,
);
?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes(['class' => $custom_classes]) ); ?>>
	<div class="blockons-slider video-slider <?php echo isset($attributes['showOnHover']) && $attributes['showOnHover'] == true ? sanitize_html_class("controlsOnHover") : ""; ?> navigation-<?php echo sanitize_html_class($attributes['navigationStyle']); ?> navigation-<?php echo sanitize_html_class($attributes['navigationColor']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationStyle']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationColor']); ?> <?php echo isset($attributes['navigationArrow']) && $attributes['navigationArrow'] == "ban" ? sanitize_html_class("default-icon") : sanitize_html_class("custom-icon"); ?> arrows-<?php echo sanitize_html_class($attributes['navigationArrow']); ?>" id="<?php echo esc_attr($attributes['uniqueId']); ?>" data-settings="<?php echo esc_attr(json_encode((object)$sliderOptions)); ?>" style="<?php echo isset($attributes['sliderStyle']) && $attributes['sliderStyle'] == "three" ? "padding: " . $attributes['sliderBorderWidth'] . "px; border-radius: " . $attributes['sliderOuterRound'] . "px; background-color: " . $attributes['sliderBorderColor'] . ";" : ""; ?>">
		<div class="video-swiper-<?php echo esc_attr($attributes['uniqueId']); ?> swiper">
			<div class="swiper-wrapper">
				<?php foreach ($attributes['sliderSlides'] as $i => $slide) { ?>
					<div class="swiper-slide">
						<div class="swiper-slide-inner blockons-videos">
							<div class="swiper-slide-video">
								<?php if ((isset($slide['videoType']) && $slide['videoType'] == "youtube") && (isset($slide['videoId']) && $slide['videoId'] != "")) : ?>
									<iframe
										class="blockons-video youtube"
										id="vid-<?php echo esc_attr($slide['videoId']); ?>"
										width="560"
										height="315"
										src="https://www.youtube.com/embed/<?php echo esc_attr($slide['videoId']); ?>?enablejsapi=1"
										title="YouTube video player"
										frameborder="0"
										allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
										allowfullscreen
									></iframe>
								<?php endif; ?>

								<?php if ((isset($slide['videoType']) && $slide['videoType'] == "vimeo") && (isset($slide['videoId']) && $slide['videoId'] != "")) : ?>
									<iframe
										class="blockons-video vimeo"
										id="vid-<?php echo esc_attr($slide['videoId']); ?>"
										src="https://player.vimeo.com/video/<?php echo esc_attr($slide['videoId']); ?>?h=55b3242b2e&title=0&byline=0&portrait=0"
										frameborder="0"
										allow="autoplay; fullscreen; picture-in-picture"
									></iframe>
								<?php endif; ?>

								<?php if ((isset($slide['videoType']) && $slide['videoType'] == "custom") && (isset($slide['customVideo']) && isset($slide['customVideo']['url']) && $slide['customVideo']['url'] != "")) : ?>
									<video class="blockons-video custom" controls>
										<source src="<?php echo esc_url($slide['customVideo']['url']); ?>" type="video/mp4">
										<!-- <source src="movie.ogg" type="video/ogg"> -->
										<?php esc_html_e("Your browser does not support the video tag.", "blockons"); ?>
									</video>
								<?php endif; ?>

								<img src="<?php echo esc_url(BLOCKONS_PLUGIN_URL . 'assets/images/169panoramic.png'); ?>" alt="no image" />
							</div>

							<div class="swiper-slide-img" style="<?php echo (isset($slide['coverImage']) && (isset($slide['coverImage']['url']) && $slide['coverImage']['url'] != "")) ? "background-image: url(" . esc_url($slide['coverImage']['url']) . ");" : "background-image: url(" . esc_url(BLOCKONS_PLUGIN_URL . 'assets/images/placeholder.png') . ");"; ?>">
								<?php if (((isset($slide['videoType']) && $slide['videoType'] == "youtube" || $slide['videoType'] == "vimeo") && (isset($slide['videoId']) && $slide['videoId'] != "")) || (isset($slide['videoType']) && $slide['videoType'] == "custom") && isset($slide['customVideo']['url']) && $slide['customVideo']['url'] != "") : ?>
									<div class="play-button" title="<?php esc_html_e("The video only plays on the frontend", "blockons"); ?>"></div>
								<?php endif; ?>
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
