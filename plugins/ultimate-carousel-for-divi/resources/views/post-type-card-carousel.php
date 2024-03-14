<?php if (!empty($posts)): ?>
	<div class="wpdt-image-card-carousel"
		data-show_arrow="<?php echo esc_attr($show_arrow); ?>"
		data-show_arrow_on_hover="<?php echo esc_attr($show_arrow_on_hover); ?>"
	>
		<div class="swiper-container"
		data-order_class="<?php echo esc_attr('.' . $module_class); ?>"
		data-effect="<?php echo esc_attr($effect); ?>"
		data-show_control_dot="<?php echo esc_attr($show_control_dot); ?>"
		data-slider_loop="<?php echo esc_attr($slider_loop); ?>"
		data-autoplay="<?php echo esc_attr($autoplay); ?>"
		data-autoplay_speed="<?php echo esc_attr($autoplay_speed); ?>"
		data-transition_duration="<?php echo esc_attr($slide_transition_duration); ?>"
		data-pause_on_hover="<?php echo esc_attr($pause_on_hover); ?>"
		data-slides_per_view_desktop="<?php echo esc_attr($slides_per_view_values['desktop']); ?>"
		data-slides_per_view_tablet="<?php echo esc_attr($slides_per_view_values['tablet']); ?>"
		data-slides_per_view_phone="<?php echo esc_attr($slides_per_view_values['phone']); ?>"
		data-enable_coverflow_slide_shadow="<?php echo esc_attr($enable_coverflow_slide_shadow); ?>"
		data-coverflow_rotate="<?php echo esc_attr($coverflow_rotate); ?>"
		data-coverflow_depth="<?php echo esc_attr($coverflow_depth); ?>"
		data-space_between_desktop="<?php echo esc_attr($space_between_desktop); ?>"
		data-space_between_tablet="<?php echo esc_attr($space_between_tablet); ?>"
		data-space_between_phone="<?php echo esc_attr($space_between_phone); ?>"
		data-show_arrow="<?php echo esc_attr($show_arrow); ?>"
		data-show_arrow_on_hover="<?php echo esc_attr($show_arrow_on_hover); ?>"
        data-initial_slide="<?php echo esc_attr($initial_slide); ?>"
        data-centered_slides="<?php echo esc_attr($centered_slides); ?>"
		>
		<div class="swiper-wrapper">
			<?php foreach ($posts as $post_item): ?>

            <?php
                $post_title = $post_item->post_title;
                $post_title = apply_filters('uc_post_type_item_title', $post_title, $post_item);
            ?>

				<div class='et_pb_wpdt_post_type_carousel_item wpt-image-card-slide swiper-slide'>
					<div class="et_pb_module_inner">
						<div class="wpt-image-card-wrapper">
							<?php if ($show_image == 'on'): ?>
							<div class="wpt-image-card-image-wrapper">
								<?php

                                    $post_image_url = $this->container['post_types']->get_featured_image($post_item->ID);
                                    $post_image_url = apply_filters('uc_post_type_item_image_url', $post_image_url, $post_item);

                                    // phpcs:ignore
                                    echo $multi_view->render_element(
                                        [
                                            'tag'          => 'img',
                                            'attrs'        => [
                                                'src'   => '{{image}}',
                                                'class' => 'wpt-image-card-image',
                                                'alt'   => esc_html($post_title),
                                            ],
                                            'custom_props' => [
                                                // phpcs:ignore
                                                'image' => $post_image_url,
                                            ],
                                            'required'     => 'image',
                                        ]
                                    );
                                ?>
							</div> <!-- image wrapper -->
						<?php endif?>
						</div> <!-- image card wrapper -->

						<div class="wpt-image-card-content-wrapper">
							<div class="wpt-image-card-inner-content-wrapper">
								<?php
                                    if ($show_title == 'on') {
                                        // phpcs:ignore
                                        echo $multi_view->render_element(
                                            [
                                                'tag'          => $processed_title_level,
                                                'content'      => '{{title}}',
                                                'attrs'        => [
                                                    'class' => 'wpt-image-card-title',
                                                ],
                                                'custom_props' => [
                                                    // phpcs:ignore
                                                    'title' => $post_title,
                                                ],
                                                'required'     => 'title',
                                            ]
                                        );
                                    }

                                    // content
                                    if ($show_content == 'on') {
                                        $post_content = $content_type == 'post_excerpt' ? $post_item->post_excerpt : do_shortcode($post_item->post_content);
                                        $post_content = apply_filters('uc_post_type_item_content', $post_content, $post_item);
                                        $post_content = et_core_intentionally_unescaped($post_content, 'html');

                                        // phpcs:ignore
                                        echo $multi_view->render_element(
                                            [
                                                'tag'          => 'div',
                                                'content'      => '{{content}}',
                                                'attrs'        => [
                                                    'class' => 'wpt-image-card-content',
                                                ],
                                                'custom_props' => [
                                                    // phpcs:ignore
                                                    'content' => $post_content,
                                                ],
                                                'required'     => 'content',
                                            ]
                                        );
                                    }

                                    if ($show_button == 'on') {
                                        // phpcs:ignore
                                        echo $this->render_button(
                                            [
                                                'display_button'      => $show_button == 'on'?true: false,
                                                'button_text'         => esc_html__($button_text, 'ultimate-carousel-for-divi'),
                                                'button_text_escaped' => true,
                                                'has_wrapper'         => true,
                                                'button_url'          => esc_attr(get_the_permalink($post_item)),
                                                // phpcs:ignore
                                                'url_new_window'      => esc_attr($button_url_new_window),
                                                // phpcs:ignore
                                                'button_custom'       => isset($this->props['custom_button'])?esc_attr($this->props['custom_button']): 'off',
                                                // phpcs:ignore
                                                'custom_icon'         => isset($this->props['button_icon']) ? $this->props['button_icon'] : '',
                                                // phpcs:ignore
                                                'button_rel'          => isset($this->props['button_rel'])?esc_attr($this->props['button_rel']): '',
                                            ]
                                        );
                                    }

                                ?>
							</div> <!-- card inner content wrapper -->
						</div> <!-- card content wrapper -->

				</div>
                    <?php if ($show_button != 'on' && $open_url == 'on'): ?>
                        <a href="<?php echo get_the_permalink($post_item); // phpcs:ignore       ?>" target='<?php echo $card_url_new_window == 'on' ? 'blank' : ''; ?>' class='wpt-image-card-overlay'></a>

                    <?php endif?>

			</div>
			<?php endforeach?>

		</div> <!-- swiper-wrapper -->
	</div> <!-- swiper-container -->

	<?php if ('on' == $show_arrow): ?>
		<div class="swiper-buttton-container" data-vertical-position='<?php echo esc_attr($arrow_vertical_position); ?>' data-horizontal-position='<?php echo esc_attr($arrow_horizontal_position); ?>'>
			<?php
                echo et_core_intentionally_unescaped($this->container['carousel_nav']->previous_button_html(), 'html');
                echo et_core_intentionally_unescaped($this->container['carousel_nav']->next_button_html(), 'html');
            ?>
		</div>
	<?php endif?>
<?php if ('on' == $show_control_dot): ?>
		<div class="swiper-pagination" data-position="<?php echo esc_attr($pagination_position); ?>"></div>
	<?php endif?>
</div>
<?php else: ?>
	<div>
		<h1><?php echo esc_html__('No Post Type Carousel Cards Found!', 'ultimate-carousel-for-divi'); ?></h1>
		<p><?php echo esc_html__('Please select and setup the post type for this module', 'ultimate-carousel-for-divi'); ?></p>
	</div>
<?php endif?>
