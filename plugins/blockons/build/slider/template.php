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
<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
	<div class="blockons-slider slider adv-slider <?php echo isset($attributes['showOnHover']) && $attributes['showOnHover'] == true ? sanitize_html_class("controlsOnHover") : ""; ?> navigation-<?php echo sanitize_html_class($attributes['navigationStyle']); ?> navigation-<?php echo sanitize_html_class($attributes['navigationColor']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationStyle']); ?> pagination-<?php echo sanitize_html_class($attributes['paginationColor']); ?> <?php echo isset($attributes['navigationArrow']) && $attributes['navigationArrow'] == "ban" ? sanitize_html_class("default-icon") : sanitize_html_class("custom-icon"); ?> arrows-<?php echo sanitize_html_class($attributes['navigationArrow']); ?>" id="<?php echo esc_attr($attributes['uniqueId']); ?>" data-settings="<?php echo esc_attr(json_encode((object)$sliderOptions)); ?>">
		<div class="adv-swiper-<?php echo esc_attr($attributes['uniqueId']); ?> swiper">
			<div class="swiper-wrapper">
				<?php foreach ($attributes['sliderSlides'] as $i => $slide) { ?>
					<div class="swiper-slide">

						<div class="swiper-slide-inner <?php echo isset($slide['style']['position']) && $slide['style']['position'] != "" ? sanitize_html_class($slide['style']['position']) : sanitize_html_class($attributes['position']); ?> <?php echo (isset($attributes['forceFullWidth']) && $attributes['forceFullWidth'] == true) || (isset($attributes['imageProportion']) && $attributes['imageProportion'] != 'actual') ? 'imgfull' : ''; ?>">
							<div class="blockons-slider-image" style="background-image: url(<?php echo (isset($slide['image']) && isset($slide['image']['url']) && $slide['image']['url'] != "") ? esc_url($slide['image']['url']) : esc_url(BLOCKONS_PLUGIN_URL . 'assets/images/placeholder.png'); ?>);">
								<?php if (isset($attributes['imageOverlay']) && $attributes['imageOverlay'] == true) : ?>
									<div class="blockons-slider-imgoverlay" style="<?php echo isset($slide['style']['bgOverlayColor']) && $slide['style']['bgOverlayColor'] != "" ? 'background-color:' . esc_attr($slide['style']['bgOverlayColor']) . '; ' : 'background-color:' . esc_attr($attributes['imageOverlayColor']) . '; '; ?> <?php echo isset($slide['style']['bgOverlayOpacity']) && $slide['style']['bgOverlayOpacity'] != "" ? 'opacity:' . esc_attr($slide['style']['bgOverlayOpacity']) . '; ' : 'opacity:' . esc_attr($attributes['imageOverlayOpacity']) . '; '; ?>"></div>
								<?php endif; ?>
								
								<?php if (isset($attributes['imageProportion']) && $attributes['imageProportion'] == "actual") : ?>

									<?php if (isset($slide['image']) && isset($slide['image']['url']) && $slide['image']['url'] != "") : ?>
										<img src="<?php echo esc_url($slide['image']['url']); ?>" alt="<?php echo isset($slide['image']) && isset($slide['image']['alt']) ? esc_attr($slide['image']['alt']) : ''; ?>" />
									<?php else : ?>
										<img src="<?php echo esc_url(BLOCKONS_PLUGIN_URL . 'assets/images/placeholder.png'); ?>" />
									<?php endif; ?>

								<?php else : ?>

									<img src="<?php echo esc_url(BLOCKONS_PLUGIN_URL . 'assets/images/' . $attributes['imageProportion'] . '.png'); ?>" />

								<?php endif; ?>
							</div><!-- .blockons-slider-image -->
							
							<?php if (isset($slide['link']) && isset($slide['link']['type']) && $slide['link']['type'] == "full") : ?>
								<a <?php echo isset($slide['link']['value']) && isset($slide['link']['value']['url']) && $slide['link']['value']['url'] != "" ? 'href="' . esc_url($slide['link']['value']['url']) . '"' : ''; ?> <?php echo isset($slide['link']['value']) && isset($slide['link']['value']['opensInNewTab']) && $slide['link']['value']['opensInNewTab'] == true ? 'target="_blank"' : ''; ?> class="blockons-slider-inner align-<?php echo isset($slide['style']['alignment']) && $slide['style']['alignment'] != "" ? sanitize_html_class($slide['style']['alignment']) : sanitize_html_class($attributes['alignment']); ?>" <?php echo isset($slide['style']['outerPadding']) && $slide['style']['outerPadding'] != "" ? 'style="padding: ' . esc_attr($slide['style']['outerPadding']) . 'px;"' : 'style="padding: ' . esc_attr($attributes['outerPadding']) . 'px;"'; ?>>
							<?php else : ?>
								<div class="blockons-slider-inner align-<?php echo isset($slide['style']['alignment']) && $slide['style']['alignment'] != "" ? sanitize_html_class($slide['style']['alignment']) : sanitize_html_class($attributes['alignment']); ?>" <?php echo isset($slide['style']['outerPadding']) && $slide['style']['outerPadding'] != "" ? 'style="padding: ' . esc_attr($slide['style']['outerPadding']) . 'px;"' : 'style="padding: ' . esc_attr($attributes['outerPadding']) . 'px;"'; ?>>
							<?php endif; ?>
								
								<div class="blockons-slider-inner-slide <?php echo isset($slide['style']['textBoxFull']) && $slide['style']['textBoxFull'] == true ? 'textboxfull' : ''; ?>">
									<?php if (isset($attributes['infoBg']) && $attributes['infoBg'] == true) : ?>
										<div class="blockons-slider-content-bg" style="<?php echo isset($slide['style']['txtBgColor']) && $slide['style']['txtBgColor'] != "" ? 'background-color:' . esc_attr($slide['style']['txtBgColor']) . '; ' : 'background-color:' . esc_attr($attributes['infoBgColor']) . '; '; ?> <?php echo isset($slide['style']['txtBgOpacity']) && $slide['style']['txtBgOpacity'] !== "" ? 'opacity:' . esc_attr($slide['style']['txtBgOpacity']) . '; ' : 'opacity:' . esc_attr($attributes['infoBgOpacity']) . '; '; ?>"></div>
									<?php endif; ?>
									
									<?php if ((isset($attributes['showTitle']) && $attributes['showTitle'] == true) || (isset($attributes['showDesc']) && $attributes['showDesc'] == true)) : ?>
										<div class="blockons-slider-content" <?php echo isset($slide['style']['innerPadding']) && $slide['style']['innerPadding'] !== "" ? 'style="padding: ' . esc_attr($slide['style']['innerPadding']) . 'px;"' : 'style="padding: ' . esc_attr($attributes['innerPadding']) . 'px;"'; ?>>
											
											<?php if (isset($attributes['showTitle']) && $attributes['showTitle'] == true) : ?>
												<h4 class="slider-title" style="<?php echo isset($slide['style']['titleSize']) && $slide['style']['titleSize'] != "" ? 'font-size:' . esc_attr($slide['style']['titleSize']) . 'px; ' : 'font-size:' . esc_attr($attributes['defaultTitleSize']) . 'px; '; ?> <?php echo isset($slide['style']['titleColor']) && $slide['style']['titleColor'] != "" ? 'color:' . esc_attr($slide['style']['titleColor']) . '; ' : 'color:' . esc_attr($attributes['defaultTitleColor']) . '; '; ?>">
													<?php esc_html_e($slide['title']); ?>
												</h4>
											<?php endif; ?>
											
											<?php if (isset($attributes['showDesc']) && $attributes['showDesc'] == true) : ?>
												<p class="slider-desc" style="<?php echo isset($slide['style']['descSize']) && $slide['style']['descSize'] != "" ? 'font-size:' . esc_attr($slide['style']['descSize']) . 'px; ' : 'font-size:' . esc_attr($attributes['defaultDescSize']) . 'px; '; ?> <?php echo isset($slide['style']['descColor']) && $slide['style']['descColor'] != "" ? 'color:' . esc_attr($slide['style']['descColor']) . '; ' : 'color:' . esc_attr($attributes['defaultDescColor']) . '; '; ?>">
													<?php esc_html_e($slide['subtitle']); ?>
												</p>
											<?php endif; ?>
											
											<?php if (isset($slide['link']) && isset($slide['link']['type']) && $slide['link']['type'] == "button") : ?>
												<div class="slider-btns">
													
													<?php
													if (isset($slide['buttons']) && isset($slide['buttons']['buttons'])) :
														foreach ($slide['buttons']['buttons'] as $button) : ?>
															
															<?php if (isset($button['link']) && isset($button['link']['url']) && $button['link']['url'] !== "") : ?>
																<a href="<?php echo esc_url($button['link']['url']); ?>" class="slider-btn" <?php echo isset($button['link']) && isset($button['link']['opensInNewTab']) && $button['link']['opensInNewTab'] == true ? esc_attr('target="_blank"') : ''; ?> style="<?php echo isset($button['color']) ? 'background-color: ' . esc_attr($button['color']) . '; ' : ''; ?> <?php echo isset($button['fcolor']) ? 'color: ' . esc_attr($button['fcolor']) . '; ' : ''; ?>">
																	<?php esc_html_e($button['text']); ?>
																</a>
															<?php endif; ?>

														<?php
														endforeach;
													endif; ?>

												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div><!-- .blockons-slider-inner-slide -->
							
							<?php if (isset($slide['link']) && isset($slide['link']['type']) && $slide['link']['type'] == "full") : ?>
								</a><!-- .blockons-slider-inner -->
							<?php else : ?>
								</div><!-- .blockons-slider-inner -->
							<?php endif; ?>

						</div><!-- .swiper-slide-inner -->

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
