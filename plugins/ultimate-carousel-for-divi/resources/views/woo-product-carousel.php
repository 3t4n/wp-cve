<?php if (class_exists('\\WC_Product') && !empty($products)): ?>
	<div class="wpdt-image-card-carousel"
		data-show_arrow="<?php echo esc_attr($show_arrow); ?>"
		data-show_arrow_on_hover="<?php echo esc_attr($show_arrow_on_hover); ?>"
	>
		<div class="swiper-container"
		data-order_class="<?php echo '.' . esc_attr($module_class); ?>"
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
			<?php foreach ($products as $product): ?>
<?php $wc_product = new WC_Product($product);?>
				<div class='et_pb_wpdt_post_type_carousel_item wpt-image-card-slide swiper-slide'>
					<div class="et_pb_module_inner">

						<div class="wpt-image-card-wrapper">
                        <?php if (($show_badge == 'on') && $wc_product->is_on_sale()): ?>
                            <span class='onsale'><?php
    $regular_price = $wc_product->get_regular_price();
    $sale_price    = $wc_product->get_sale_price();

    $discount_percent = round((($regular_price - $sale_price) / $regular_price) * 100);

    echo sprintf(
        '%s%s',
        // phpcs:ignore
        $show_disc_text_in_badge == 'on' ? $discount_percent . '% ' : '',
        // phpcs:ignore
    $badge_text ? et_core_intentionally_unescaped($badge_text, 'html') : ''
);?></span>
                        <?php endif?>

                        <?php if ($show_image == 'on'): ?>
							<div class="wpt-image-card-image-wrapper">
								<?php
                                    // phpcs:ignore
                                    echo $multi_view->render_element(
                                        [
                                            'tag'          => 'img',
                                            'attrs'        => [
                                                'src'   => '{{image}}',
                                                'class' => 'wpt-image-card-image',
                                                'alt'   => esc_html($product->post_title),
                                            ],
                                            'custom_props' => [
                                                'image' => et_core_intentionally_unescaped($this->container['post_types']->get_featured_image($product->ID), 'html'),
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
                                        $product_title_text = apply_filters('uc_carousel_product_title', $product->post_title);

                                        // phpcs:ignore
                                        $product_title = $multi_view->render_element(
                                            [
                                                'tag'          => et_core_intentionally_unescaped($processed_title_level, 'html'),
                                                'content'      => '{{title}}',
                                                'attrs'        => [
                                                    'class' => 'wpt-image-card-title',
                                                ],
                                                'custom_props' => [
                                                    'title' => et_core_intentionally_unescaped($product_title_text, 'html'),
                                                ],
                                                'required'     => 'title',
                                            ]
                                        );

                                        $product_title = apply_filters('udc_after_woo_product_title', $product_title, $wc_product, $this->props, $multi_view);

                                        // phpcs:ignore
                                        echo $product_title;
                                    }

                                    // show price
                                    if ($show_price == 'on') {
                                        $price = $wc_product->get_price_html();
                                        if (substr($price, 0, 5) == '<span') {
                                            $price = '<ins>' . et_core_intentionally_unescaped($price, 'html') . '</ins>';
                                        }
                                        // phpcs:ignore
                                        echo $multi_view->render_element(
                                            [
                                                'tag'          => 'div',
                                                'content'      => '{{price}}',
                                                'attrs'        => [
                                                    'class' => 'wpt-image-card-price',
                                                ],
                                                'custom_props' => [
                                                    // phpcs:ignore
                                                    'price' => $price,
                                                ],
                                                'required'     => 'price',
                                            ]
                                        );

                                    }

                                    // show rating

                                    if ($show_ratings == 'on') {
                                        $avg_rating  = $wc_product->get_average_rating();
                                        $rating      = ($avg_rating / 5) * 100;
                                        $rating_html = sprintf('<div class="woocommerce-product-rating"><div class="star-rating" role="img" aria-label="Rated %s out of 5.00"><span style="width: %s%%;"></span></div><span class="total-rating">(%s)</span></div>', $avg_rating, $rating, $wc_product->get_rating_count());
                                        // phpcs:ignore
                                        echo $multi_view->render_element(
                                            [
                                                'tag'          => 'div',
                                                'content'      => '{{rating}}',
                                                'attrs'        => [
                                                    'class' => 'wpt-image-card-rating woocommerce',
                                                ],
                                                'custom_props' => [
                                                    'rating' => et_core_intentionally_unescaped($rating_html, 'html'),
                                                ],
                                                'required'     => 'rating',
                                            ]
                                        );
                                    }

                                    // content
                                    if ($show_content == 'on') {
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
                                                    'content' => apply_filters('woocommerce_short_description', $wc_product->get_short_description() ? $wc_product->get_short_description() : wc_trim_string($wc_product->get_description(), 400)),
                                                ],
                                                'required'     => 'content',
                                            ]
                                        );
                                    }

                                    if ($show_button == 'on') {
                                        // phpcs:ignore
                                        echo $this->render_button(
                                            [
                                                'display_button'      => $show_button == 'on' ? true : false,
                                                'button_text'         => esc_html__($button_text, 'ultimate-carousel-for-divi'),
                                                'button_text_escaped' => true,
                                                'has_wrapper'         => true,
                                                'button_url'          => et_core_intentionally_unescaped(get_the_permalink($product), 'html'),
                                                'url_new_window'      => esc_attr($button_url_new_window),
                                                // phpcs:ignore
                                                'button_custom'       => isset($this->props['custom_button']) ? esc_attr($this->props['custom_button']) : 'off',
                                                // phpcs:ignore
                                                'custom_icon'         => isset($this->props['button_icon']) ? $this->props['button_icon'] : '',
                                                // phpcs:ignore
                                                'button_rel'          => isset($this->props['button_rel']) ? esc_attr($this->props['button_rel']) : '',
                                            ]
                                        );
                                    }

                                ?>

							</div> <!-- card inner content wrapper -->
						</div> <!-- card content wrapper -->
                        <?php if ($show_button != 'on' && $open_url == 'on'): ?>
                            <a href="<?php echo get_the_permalink($product); // phpcs:ignore              ?>" target='<?php echo $card_url_new_window == 'on' ? 'blank' : ''; ?>' class='woo-product-overlay'></a>

                        <?php endif?>

				</div>
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
		<h1><?php echo esc_html__('No Products Found!', 'ultimate-carousel-for-divi'); ?></h1>
		<p><?php echo esc_html__('Please check if you have product entries for the selected "Criteria".', 'ultimate-carousel-for-divi'); ?></p>
	</div>
<?php endif?>
