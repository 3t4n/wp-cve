<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function famisp_auto_complete_search_data_via_ajax() {
	$response = array(
		'array'   => '',
		'message' => '',
		'success' => 'no',
	);
	$args     = array(
		'post_type'      => 'product',
		'posts_per_page' => - 1,
		'post_status'    => 'publish',
		//		'tax_query'      => array(
		//			array(
		//				'taxonomy' => 'product_type',
		//				'field'    => 'slug',
		//				'terms'    => array( 'simple' ),
		//				'operator' => 'IN',
		//			)
		//		)
	);
	
	$posts = new WP_Query( $args );
	
	if ( $posts->have_posts() ) { ?>
		
		<?php while ( $posts->have_posts() ) { ?>
			<?php
			$posts->the_post();
			$product = wc_get_product( get_the_ID() );
			if ( ! $product ) {
				continue;
			}
			$min_price   = '';
			$max_price   = '';
			$childs_html = '';
			
			if ( $product->is_type( 'variable' ) ) {
				$min_price = $product->get_variation_price( 'min' );
				$max_price = $product->get_variation_price( 'max' );
				// show all childs
				$childs = $product->get_children();
				if ( is_array( $childs ) && count( $childs ) > 0 ) {
					ob_start();
					foreach ( $childs as $child_id ) {
						$product_child = wc_get_product( $child_id );
						?>
                        <div data-product_id="<?php echo esc_attr( $child_id ); ?>"
                             data-min_price="<?php echo esc_attr( $product_child->get_price() ); ?>"
                             data-max_price="<?php echo esc_attr( $product_child->get_price() ); ?>" <?php post_class( 'product-item product-item-child' ); ?>>
                            <div class="product-inner">
                                <div class="post-thumb">
									<?php
									$image = famisp_resize_image( get_post_thumbnail_id(), null, 60, 60, true, true, false );
									?>
                                    <img width="<?php echo esc_attr( $image['width'] ); ?>"
                                         height="<?php echo esc_attr( $image['height'] ); ?>"
                                         class="attachment-post-thumbnail wp-post-image"
                                         src="<?php echo esc_url( $image['url'] ); ?>"
                                         alt="<?php echo esc_attr( $product_child->get_name() ); ?>"/>
                                </div>
                                <div class="product-info">
                                    <span class="product-title"><?php echo $product_child->get_name(); ?></span>
                                    <span>(<?php echo '#' . $product_child->get_id() . ' - <span class="famisp-price-wrap">' . $product_child->get_price_html() . '</span>'; ?>
                                        )</span>
									<?php
									$term_list = wp_get_post_terms( $product_child->get_id(), 'product_cat' );
									$arg_term  = array();
									if ( is_wp_error( $term_list ) ) {
										return $term_list;
									}
									foreach ( $term_list as $term ) {
										$arg_term[] = $term->slug;
									}
									$arg_term = implode( ',', $arg_term );
									?>
                                </div>
                            </div>
                        </div>
						<?php
					}
					$childs_html .= ob_get_clean();
				}
			} else {
				$min_price = $product->get_price();
				$max_price = $min_price;
			}
			ob_start(); ?>
            <div data-product_id="<?php echo esc_attr( get_the_ID() ); ?>"
                 data-min_price="<?php echo esc_attr( $min_price ); ?>"
                 data-max_price="<?php echo esc_attr( $max_price ); ?>" <?php post_class( 'product-item' ); ?>>
                <div class="product-inner">
                    <div class="post-thumb">
						<?php
						$image = famisp_resize_image( get_post_thumbnail_id(), null, 60, 60, true, true, false );
						?>
                        <img width="<?php echo esc_attr( $image['width'] ); ?>"
                             height="<?php echo esc_attr( $image['height'] ); ?>"
                             class="attachment-post-thumbnail wp-post-image"
                             src="<?php echo esc_url( $image['url'] ); ?>"
                             alt="<?php echo esc_attr( get_the_title() ); ?>"/>
                    </div>
                    <div class="product-info">
                        <span class="product-title"><?php the_title(); ?></span>
                        <span>(<?php echo '#' . get_the_ID() . ' - <span class="famisp-price-wrap">' . $product->get_price_html() . '</span>'; ?>
                            )</span>
						<?php
						$term_list = wp_get_post_terms( $product->get_id(), 'product_cat' );
						$arg_term  = array();
						if ( is_wp_error( $term_list ) ) {
							return $term_list;
						}
						foreach ( $term_list as $term ) {
							$arg_term[] = $term->slug;
						}
						$arg_term = implode( ',', $arg_term );
						?>
                    </div>
                </div>
            </div>
			<?php
			echo $childs_html;
			
			$post_html   = ob_get_clean();
			$cat_slugs   = $arg_term;
			$post_data[] = array(
				'post_id'    => get_the_ID(),
				'post_title' => esc_html( get_the_title() ),
				'post_link'  => esc_url( get_permalink() ),
				'thumb'      => $image,
				'post_html'  => $post_html,
				'cat_slugs'  => $cat_slugs,
			);
		}
	}
	
	wp_reset_postdata();
	$response['array']   = $post_data;
	$response['success'] = 'yes';
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famisp_auto_complete_search_data_via_ajax', 'famisp_auto_complete_search_data_via_ajax' );