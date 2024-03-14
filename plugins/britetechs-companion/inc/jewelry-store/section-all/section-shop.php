<?php
if ( !function_exists( 'bc_js_shop' ) ) :
	function bc_js_shop(){

		if( !class_exists('woocommerce') ){

			return;

		}else{

			$args = array(
				'post_type' => 'product',
			);
			
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'exclude-from-catalog',
					'operator' => 'NOT IN',

				),
			);
		}

		$option = wp_parse_args(  get_option( 'jewelrystore_option', array() ), jewelry_store_reset_data() );

		$containerClass = '';
		if($option['shop_container_width']!=''){
		    $containerClass = $option['shop_container_width'];
		}

		if($option['shop_enable']==true){
		?>
		<div id="shop" class="section shop_section">
	        <div class="<?php echo esc_attr( $containerClass ); ?>">
	        	<?php if( $option['shop_subtitle'] != '' || $option['shop_title'] != '' || $option['shop_desc'] != '' ){ ?>
	            <div class="row">
	                <div class="col-12">
	                	<div class="header_section wow animated fadeInUp">
	                        <div class="header_section_container">
	                            <div class="header_section_details">
	                            	<?php if( $option['shop_subtitle'] != '' || $option['shop_title'] != '' ){ ?>
	                                    <h2 class="section_title_wrap">
	                                        <?php if( $option['shop_subtitle'] != '' ){ ?>
	                                        <span class="section_subtitle"><?php echo wp_kses_post($option['shop_subtitle']); ?></span>
	                                        <?php } ?>
	                                        <?php if( $option['shop_title'] != '' ){ ?>
	                                        <span class="section_title"><?php echo wp_kses_post($option['shop_title']); ?></span>
	                                        <?php } ?>
	                                    </h2>
	                                <?php } ?>
	                                <?php if($option['shop_desc']!=''){ ?>
	                                    <p class="section_desc"><?php echo wp_kses_post($option['shop_desc']); ?></p>
	                                <?php } ?>
	                            </div>                          
	                        </div>
	                    </div>
	                </div>                    
	            </div>
	            <?php } ?>
	            <div class="row">
	                <div class="col-12">
	                	<div class="products">
		                    <div id="shop_slider" class="owl-carousel owl-theme" data-collg="<?php echo esc_attr( $option['shop_column'] ); ?>" data-colmd="3" data-colsm="2" data-colxs="1" data-itemspace="30" data-loop="true" data-autoplay="true" data-smartspeed="800" data-nav="true" data-dots="true">
		                        <?php

									$loop = new WP_Query( $args );

									while ( $loop->have_posts() ) : $loop->the_post();
									global $post;
									global $product;

										$terms = get_the_terms( get_the_ID(), 'product_cat' );
															
										if ( $terms && ! is_wp_error( $terms ) ) : 
											$links = array();

											foreach ( $terms as $term ) 
											{
												$links[] = $term->slug;
											}
											
											$tax = join( ' ', $links );		
										else :	
											$tax = '';	
										endif;
								?>
		                        <div class="item wow animated fadeInUp">
		                            <div class="product_single">
										<div class="product_bg"></div>
										<div class="product_thumbnail">
											<?php
											/**
											 * Hook: woocommerce_before_shop_loop_item.
											 *
											 * @hooked woocommerce_template_loop_product_link_open - 10
											 */
											do_action( 'woocommerce_before_shop_loop_item' );
											?>
											<a href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail(); ?>
											</a>
											<?php if ( $product->is_on_sale() ) : ?>

											<?php echo apply_filters( 'woocommerce_sale_flash', '<div class="sale_ribbon"><span class="tag_line">' . esc_html__( 'Sale', 'jewelry-store' ) . '</span></div>', $post, $product ); ?>
											<?php endif; ?>

											<?php 
											// Quick View Button
											if( function_exists('yith_wcqv_init') && function_exists('is_pro') ){
												$quickview_button = '<a href="#" class="yith-wcqv-button" data-product_id="' . esc_attr( $product->get_id() ) . '"><i class="fa fa-eye"></i></a>';
												echo wp_kses_post($quickview_button);
											}

											// attachment images
											$attachment_ids = $product->get_gallery_image_ids();
											if(!empty($attachment_ids)):
													foreach( $attachment_ids as $i=> $attachment_id ) {
													$image_url2 = wp_get_attachment_url( $attachment_id );
													if($i==0){
											?>
												<a href="<?php the_permalink(); ?>">
													<img width="800" height="800" src="<?php  echo esc_url($image_url2); ?>" class="product_single_effect_img" alt="" />
												</a>
											<?php }  
											} 
											else: ?>
												<a href="<?php the_permalink(); ?>">
													<img width="800" height="800" src="<?php the_post_thumbnail_url(); ?>" class="product_single_effect_img" alt="" />
												</a>
											<?php endif; ?>
										</div>
										<div class="product_content_outer">
											<div class="product_content">					
												<h3 class="product_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<div class="product_rating">
													<?php if ($average = $product->get_average_rating()) : ?>
													<?php echo '<div class="star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'jewelry-store' ), $average).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'jewelry-store' ).'</span></div>'; ?>
													<?php endif; ?>
												</div>
												<div class="product_price">
													<?php echo $product->get_price_html(); ?>
												</div>
											</div>
											<div class="product_actions">			
												<?php

												/**
												 * Hook: woocommerce_after_shop_loop_item.
												 *
												 * @hooked woocommerce_template_loop_product_link_close - 5
												 * @hooked woocommerce_template_loop_add_to_cart - 10
												 */
												if( class_exists( 'YITH_WCWL' ) && function_exists('is_pro') ) { 

													echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); 

												}

												do_action( 'woocommerce_after_shop_loop_item' );
												?>
											</div>
										</div>
									</div>
		                        </div>
		                        <?php 
		                            endwhile; 
		                            wp_reset_postdata();
		                        ?>
		                    </div>
	                    </div>                            
	                </div>
	            </div>
	        </div>
		</div>
		<?php }
	}
endif;
if ( function_exists( 'bc_js_shop' ) ) {
	$section_priority = apply_filters( 'jewelry_store_section_priority', 3, 'bc_js_shop' );
	add_action( 'jewelry_store_sections', 'bc_js_shop', absint( $section_priority ) );
}