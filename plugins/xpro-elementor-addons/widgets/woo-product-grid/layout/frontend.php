<?php
global $post, $product;

$limit   = $settings['content_length'] ? $settings['content_length'] : 15;
$content = explode( ' ', get_the_excerpt(), $limit );

if ( count( $content ) >= $limit ) {
	array_pop( $content );
	$content = implode( ' ', $content ) . '...';
} else {
	$content = implode( ' ', $content );
}
$content = preg_replace( '`[[^]]*]`', '', $content );

$product_id = $post->ID;

if ( isset( $product_id ) && '' !== $product_id ) {

	$product = wc_get_product( $product_id );
	if ( $product ) :
		$product_data = $product->get_data();
		$post         = get_post( $product_id, OBJECT ); //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		setup_postdata( $post );
		do_action( 'xpro_elementor_woo_before_product' );
		?>

		<div id="xpro-woo-product-grid-id-<?php echo esc_attr( $product_id ); ?>" class="cbp-item xpro-woo-product-grid-item">
			<!-- image wrapper -->
			<a class="xpro-woo-product-grid-title-wrapper" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
				<div class="xpro-woo-product-grid-img">
					<div class="xpro-woo-product-img-section">
						<?php
						$product        = wc_get_product( $product_id );
						$attachment_ids = $product->get_gallery_image_ids();
						if ( is_array( $attachment_ids ) && ! empty( $attachment_ids ) ) {

							$img_url         = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), $settings['thumbnail_size'], true );
							$placeholder_url = WC()->plugin_url() . '/assets/images/placeholder.png';
							if ( has_post_thumbnail( $product_id ) ) {
								$img_src = $img_url[0];
							} else {
								$img_src = $placeholder_url;
							}

							?>
							<img class="xpro-woo-product-grid-img xpro-gallery-first-img-url" src="<?php echo esc_url( $img_src ); ?>" alt="product-image">
							<?php
							if ( isset( $attachment_ids[0] ) ) {
								$second_image_url = wp_get_attachment_image_src( $attachment_ids[0], $settings['thumbnail_size'], true );
								?>
								<!-- second img url -->
								<img class="xpro-woo-product-grid-img xpro-gallery-second-img-url" src="<?php echo esc_url( $second_image_url[0] ); ?>" alt="product-image">
								<?php
							}
						} else {
							$img_url         = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), $settings['thumbnail_size'], true );
							$placeholder_url = WC()->plugin_url() . '/assets/images/placeholder.png';
							if ( has_post_thumbnail( $product_id ) ) {
								$img_src = $img_url[0];
							} else {
								$img_src = $placeholder_url;
							}
							?>
							<img class="xpro-woo-product-grid-img xpro-product-img-url" src="<?php echo esc_url( $img_src ); ?>" alt="product-image">
							<?php
						}
						if ( 'yes' === $settings['show_badges'] ) {
							?>
							<div class="xpro-woo-product-grid-badges-wrapper">
								<div class="xpro-woo-product-grid-badges-innner-wrapper">
									<?php
									if ( $product->is_in_stock() ) {
										if ( 'yes' === $settings['show_sale_badge'] ) {
											if ( 'text' === $settings['sale_badge_type'] ) {
												$sale_text = __( 'Sale!', 'xpro-elementor-addons' );
											} else {
												if ( 'variable' === $product->get_type() ) {
													$regular_price = $product->get_variation_regular_price();
												} else {
													$regular_price = $product->get_regular_price();
												}

												if ( 'variable' === $product->get_type() ) {
													$sale_price = $product->get_variation_sale_price();
												} else {
													$sale_price = $product->get_sale_price();
												}

												if ( 'grouped' === $product->get_type() ) {
													$sale_text = __( 'Sale!', 'xpro-elementor-addons' );
												}

												if ( $sale_price ) {
													$percent_sale = round( ( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ), 0 );
													$sale_text    = '-[value]%';
													$sale_text    = str_replace( '[value]', $percent_sale, $sale_text );
												}
											}
											//sale flash
											if ( $product->is_on_sale() ) :
												xpro_elementor_kses( apply_filters( 'xpro-woo-product_sale_flash_inner', '<div class="xpro-sale-flash-wrap xpro-woo-sale-flash-btn xpro-woo-badges-btn"><span class="xpro-onsale xpro-woo-sale-flash-btn-inner">' . $sale_text . '</span></div>', $post, $product ) ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
											endif;
										}

										if ( 'yes' === $settings['show_featured_badge'] ) {
											//featured flash
											$featured_text = __( 'New', 'xpro-elementor-addons' );
											if ( $product->is_featured() ) :
												xpro_elementor_kses( apply_filters( 'xpro-woo-product_featured_flash_inner', '<div class="xpro-featured-flash-wrap xpro-woo-featured-flash-btn xpro-woo-badges-btn"><span class="xpro-featured xpro-woo-featured-flash-btn-inner">' . $featured_text . '</span></div>', $post, $product ) ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
											endif;
										}
									} else {
										$out_stock_text = __( 'Out of Stock', 'xpro-elementor-addons' );
										?>
										<div class="xpro-woo-out-of-stock-btn xpro-woo-badges-btn"><span><?php xpro_elementor_kses( $out_stock_text ); ?></span></div>
										<?php
									}
									?>
								</div>
							</div>
							<?php
						}
						if ( 'yes' === $settings['show_qv_action'] && '10' !== $settings['layout'] ) {
							?>
							<!-- qv call to action -->
							<div class="xpro-product-grid-hv-cta-section">
								<?php if ( 'yes' === $settings['show_qv_icon'] ) { ?>
									<div id="<?php echo esc_attr( $product_id ); ?>" class="xpro-hv-qv-btn xpro-cta-btn">
										<i class="xi xi-eye"></i>
									</div>
									<?php
								}
								if ( 'yes' === $settings['show_cart_icon'] ) {
									?>
									<div class="xpro-hv-cart-btn xpro-cta-btn">
										<div class="xpro-qv-cart-btn">
											<?php
											do_action( 'xpro_elementor_woo_products_add_to_cart_before', $product_id, $settings );
											woocommerce_template_loop_add_to_cart();
											do_action( 'xpro_elementor_woo_products_add_to_cart_after', $product_id, $settings );
											?>
										</div>
									</div>
								<?php } ?>
							</div>
							<!--qv call to action end -->
						<?php } ?>

						<?php if ( 'yes' === $settings['show_cta'] && ( '1' === $settings['layout'] || '4' === $settings['layout'] || '5' === $settings['layout'] ) ) { ?>
							<!-- product actions -->
							<div class="xpro-woo-product-grid-btn-section">
								<div class="xpro-woo-product-grid-add-to-cart-btn">
									<?php
									do_action( 'xpro_elementor_woo_products_add_to_cart_before', $product_id, $settings );
									woocommerce_template_loop_add_to_cart();
									do_action( 'xpro_elementor_woo_products_add_to_cart_after', $product_id, $settings );
									?>
								</div>
							</div>
							<!-- product actions end-->
						<?php } ?>

					</div>
				</div>
			</a>
			<!-- image wrapper end -->

			<!-- content sec -->
			<div class="xpro-woo-product-grid-content-sec">
				<?php
				if ( 'yes' === $settings['show_category'] ) {
					?>
					<!-- category -->
					<h4 class="xpro-woo-product-grid-category-wrapper">
						<?php
						$terms_data = get_the_terms( $product_id, 'product_cat' );
						foreach ( $terms_data as $t ) {
							?>
							<a href="<?php echo esc_url( get_term_link( $t ) ); ?>">
								<span class="xpro_elementor_category_term_name">
									<?php echo esc_html( $t->name ); ?>
								</span>
							</a>
						<?php } ?>
					</h4>
					<!-- category end-->
				<?php } ?>

				<?php
				if ( 'yes' === $settings['show_title'] ) {
					?>
					<!-- title -->
					<a class="xpro-woo-product-grid-title-wrapper" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
						<h2 class="xpro-woo-product-grid-title"><?php the_title(); ?></h2>
					</a>
					<!-- title end-->
				<?php } ?>

				<?php
				if ( 'yes' === $settings['show_rating'] ) {
					?>
					<!-- rating -->
					<div class="xpro-woo-product-grid-star-rating-wrapper">
						<?php
						do_action( 'xpro_elementor_woo_products_rating_before', $product_id, $settings );
						woocommerce_template_loop_rating();
						do_action( 'xpro_elementor_woo_products_rating_after', $product_id, $settings );
						?>
					</div>
					<!-- rating end-->
				<?php } ?>

				<?php if ( 'yes' === $settings['show_price'] ) { ?>
					<!-- price -->
					<div class="xpro-woo-product-grid-price-wrapper">
						<?php
						woocommerce_template_loop_price();
						?>
					</div>
					<!-- price end -->
				<?php } ?>

				<?php if ( 'yes' === $settings['show_content'] ) { ?>
					<!-- content -->
					<p class="xpro-woo-product-grid-excerpt"><?php echo wp_kses_post( $content ); ?></p>
					<!-- content end -->
				<?php } ?>

				<?php if ( 'yes' === $settings['show_qv_action'] && '10' === $settings['layout'] ) { ?>
					<!-- qv call to action -->
					<div class="xpro-product-grid-hv-cta-section">
						<?php if ( 'yes' === $settings['show_qv_icon'] ) { ?>
							<div id="<?php echo esc_attr( $product_id ); ?>" class="xpro-hv-qv-btn xpro-cta-btn">
								<i class="xi xi-eye"></i>
							</div>
							<?php
						}
						if ( 'yes' === $settings['show_cart_icon'] ) {
							?>
							<div class="xpro-hv-cart-btn xpro-cta-btn">
								<div class="xpro-qv-cart-btn">
									<?php
									do_action( 'xpro_elementor_woo_products_add_to_cart_before', $product_id, $settings );
									woocommerce_template_loop_add_to_cart();
									do_action( 'xpro_elementor_woo_products_add_to_cart_after', $product_id, $settings );
									?>
								</div>
							</div>
						<?php } ?>
					</div>
					<!--qv call to action end -->
				<?php } ?>

				<?php if ( 'yes' === $settings['show_cta'] && ( '1' !== $settings['layout'] && '4' !== $settings['layout'] && '5' !== $settings['layout'] ) ) { ?>
					<!-- product actions -->
					<div class="xpro-woo-product-grid-btn-section">
						<div class="xpro-woo-product-grid-add-to-cart-btn">
							<?php
							do_action( 'xpro_elementor_woo_products_add_to_cart_before', $product_id, $settings );
							woocommerce_template_loop_add_to_cart();
							do_action( 'xpro_elementor_woo_products_add_to_cart_after', $product_id, $settings );
							?>
						</div>
					</div>
					<!-- product actions end-->
				<?php } ?>

			</div>
			<!-- content sec end -->
		</div>
		<?php

		do_action( 'xpro_elementor_woo_after_product' );
		wp_reset_postdata();
	endif;
}
?>
