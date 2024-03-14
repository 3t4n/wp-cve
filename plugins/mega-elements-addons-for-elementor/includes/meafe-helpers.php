<?php  
/**
 * Get image size.
 *
 * @param string $size
 * @return void
 */
function meafe_get_image_sizes( $size = '' ) {
     
    global $_wp_additional_image_sizes;
 
    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
 
    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size ) {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array( 
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    } 
    // Get only 1 size if found
    if ( $size ) {
        if( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }
    return $sizes;
}

function meafe_get_fallback_svg( $post_thumbnail ) {
    if( ! $post_thumbnail ){
        return;
    }
    
    $image_size = meafe_get_image_sizes( $post_thumbnail );
     
    if( $image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#f2f2f2;"></rect>
            </svg>
        </div>
        <?php
    }
}

/** Add a form field in the new category page
 * @since 1.0.0
*/
function meafe_add_category_image( $taxonomy ) { ?>
	<div class="form-field term-group">
		<label for="ba_category_image_id"><?php _e( 'Mega Elements - Addons for Elementor Image', 'mega-elements-addons-for-elementor' ); ?></label>
		<input type="hidden" id="ba_category_image_id" name="ba_category_image_id" class="custom_media_url" value="">
		<div id="category-image-wrapper"></div>
		<p>
			<input type="button" class="button button-secondary ba_tax_media_button" id="ba_tax_media_button" name="ba_tax_media_button" value="<?php _e( 'Add Image', 'mega-elements-addons-for-elementor' ); ?>" />
			<input type="button" class="button button-secondary ba_tax_media_remove" id="ba_tax_media_remove" name="ba_tax_media_remove" value="<?php _e( 'Remove Image', 'mega-elements-addons-for-elementor' ); ?>" />
		</p>
	</div>
<?php
}
	 
/*
* Save the form field
* @since 1.0.0
*/
function meafe_save_category_image( $term_id ) {
	if( isset( $_POST['ba_category_image_id'] ) && '' !== $_POST['ba_category_image_id'] ){
		$image = absint( $_POST['ba_category_image_id'] );
		add_term_meta( $term_id, 'ba_category_image_id', $image, true );
	}
}

/*
* Edit the form field
* @since 1.0.0
*/
function meafe_update_category_image( $term, $taxonomy='' ) { ?>
	<tr class="form-field term-group-wrap">
		<th scope="row">
			<label for="ba_category_image_id"><?php _e( 'Mega Elements Addons For Elementor Image', 'mega-elements-addons-for-elementor' ); ?></label>
		</th>
		<td>
			<?php $image_id = get_term_meta ( $term->term_id, 'ba_category_image_id', true ); ?>
			<input type="hidden" id="ba_category_image_id" name="ba_category_image_id" value="<?php echo esc_attr( $image_id ); ?>">
			<div id="category-image-wrapper">
				<?php if ( isset( $image_id ) && $image_id!='' ) { ?>
				<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
				<?php } ?>
			</div>
			<p>
				<input type="button" class="button button-secondary ba_tax_media_button" id="ba_tax_media_button" name="ba_tax_media_button" value="<?php _e( 'Add Image', 'mega-elements-addons-for-elementor' ); ?>" />
				<input type="button" class="button button-secondary ba_tax_media_remove" id="ba_tax_media_remove" name="ba_tax_media_remove" value="<?php _e( 'Remove Image', 'mega-elements-addons-for-elementor' ); ?>" />
			</p>
		</td>
	</tr>
<?php
}

/*
* Update the form field value
* @since 1.0.0
*/
function meafe_updated_category_image( $term_id ) {
	if( isset( $_POST['ba_category_image_id'] ) && '' !== $_POST['ba_category_image_id'] ) {
		$image = absint( $_POST['ba_category_image_id'] );
		update_term_meta ( $term_id, 'ba_category_image_id', $image );
	} else {
		update_term_meta ( $term_id, 'ba_category_image_id', '' );
	}
}

/**
 * Column Header
 *
 * @param [type] $columns
 * @return void
 */
function meafe_custom_column_header( $columns ){
  $columns['header_name'] = 'Thumbnail'; 
  return $columns;
}


/**
 * Column value
 *
 * @param [type] $value
 * @param [type] $column_name
 * @param [type] $tax_id
 * @return void
 */
function meafe_custom_column_content( $value, $column_name, $tax_id ){
   	$img = get_term_meta( $tax_id, 'ba_category_image_id', false );
   	$ret = '';
   	if(isset($img[0]) && $img[0]!='')
	{
		$url = wp_get_attachment_image_url($img[0],'thumbnail');
		$ret = '<img src="'.esc_url($url).'" class="tax-img">';
	}
   	return $ret;
}

/*
* Add script
* @since 1.0.0
*/
function meafe_add_script() { ?>
	<script>
		jQuery(document).ready( function($) {
			function ct_media_upload(button_class) {
				var _custom_media = true,
				_orig_send_attachment = wp.media.editor.send.attachment;
				
				$('body').on('click', button_class, function(e) {
					var button_id = '#'+$(this).attr('id');
					var send_attachment_bkp = wp.media.editor.send.attachment;
					var button = $(button_id);
					_custom_media = true;
					wp.media.editor.send.attachment = function(props, attachment){
						if ( _custom_media ) {
							$('#ba_category_image_id').val(attachment.id);
							$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
							$('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
						} else {
							return _orig_send_attachment.apply( button_id, [props, attachment] );
						}
					}
					wp.media.editor.open(button);
					return false;
				});
			}
			
			ct_media_upload('.ba_tax_media_button.button'); 
			$('body').on('click','.ba_tax_media_remove',function(){
				$('#ba_category_image_id').val('');
				$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
			});

			// Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
			$(document).ajaxComplete(function(event, xhr, settings) {
				var queryStringArr = settings.data.split('&');
				if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
					var xml = xhr.responseXML;
					$response = $(xml).find('term_id').text();
					if($response!=""){
					// Clear the thumb image
					$('#category-image-wrapper').html('');
				}
			}
		});
	});
	</script>
<?php 
}

/**
 * Get base64 icon
 *
 * @return void
 */
function meafe_get_b64_icon() {
	return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSIyMC4xMjMiIHZpZXdCb3g9IjAgMCA0MCAyMC4xMjMiPg0KICA8ZyBpZD0iR3JvdXBfMTQ3NSIgZGF0YS1uYW1lPSJHcm91cCAxNDc1IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjQwLjkzNCAtNDkwKSI+DQogICAgPGcgaWQ9Ikdyb3VwXzE0NjkiIGRhdGEtbmFtZT0iR3JvdXAgMTQ2OSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMjY1LjYxNCA0OTApIj4NCiAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGVfMTQ2NSIgZGF0YS1uYW1lPSJSZWN0YW5nbGUgMTQ2NSIgd2lkdGg9IjE1LjMyIiBoZWlnaHQ9IjQuMzEyIiByeD0iMi4xNTYiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDApIiBmaWxsPSIjZmZmIi8+DQogICAgICA8cmVjdCBpZD0iUmVjdGFuZ2xlXzE0NjYiIGRhdGEtbmFtZT0iUmVjdGFuZ2xlIDE0NjYiIHdpZHRoPSI5LjU3MSIgaGVpZ2h0PSI0LjMxMiIgcng9IjIuMTU2IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDcuOTA1KSIgZmlsbD0iI2ZmZiIvPg0KICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZV8xNDY3IiBkYXRhLW5hbWU9IlJlY3RhbmdsZSAxNDY3IiB3aWR0aD0iMTUuMzIiIGhlaWdodD0iNC4zMTIiIHJ4PSIyLjE1NiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCAxNS44MTEpIiBmaWxsPSIjZmZmIi8+DQogICAgPC9nPg0KICAgIDxyZWN0IGlkPSJSZWN0YW5nbGVfMTQ2NS0yIiBkYXRhLW5hbWU9IlJlY3RhbmdsZSAxNDY1IiB3aWR0aD0iMTkuNDA0IiBoZWlnaHQ9IjQuMzEyIiByeD0iMi4xNTYiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDI2MS4wNTYgNDkwKSByb3RhdGUoOTApIiBmaWxsPSIjZmZmIi8+DQogICAgPHJlY3QgaWQ9IlJlY3RhbmdsZV8xNDY2LTIiIGRhdGEtbmFtZT0iUmVjdGFuZ2xlIDE0NjYiIHdpZHRoPSI5LjU3MSIgaGVpZ2h0PSI0LjMxMiIgcng9IjIuMTU2IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyNTMuMTUxIDQ5MCkgcm90YXRlKDkwKSIgZmlsbD0iI2ZmZiIvPg0KICAgIDxyZWN0IGlkPSJSZWN0YW5nbGVfMTQ2Ny0yIiBkYXRhLW5hbWU9IlJlY3RhbmdsZSAxNDY3IiB3aWR0aD0iMTkuNDA0IiBoZWlnaHQ9IjQuMzEyIiByeD0iMi4xNTYiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDI0NS4yNDYgNDkwKSByb3RhdGUoOTApIiBmaWxsPSIjZmZmIi8+DQogIDwvZz4NCjwvc3ZnPg0K';
}

function meafe_get_dashboard_link( $suffix = '#mega-elements-general' ) {
	return add_query_arg( [ 'page' => 'mega-elements' . $suffix ], admin_url( 'admin.php' ) );
}

/**
 * Get a list of all the allowed html tags.
 *
 * @param string $level Allowed levels are basic and intermediate
 * @return array
 */
function meafe_get_allowed_html_tags( $level = 'basic' ) {
	$allowed_html = [
		'b' => [],
		'i' => [],
		'u' => [],
		's' => [],
		'br' => [],
		'em' => [],
		'del' => [],
		'ins' => [],
		'sub' => [],
		'sup' => [],
		'code' => [],
		'mark' => [],
		'small' => [],
		'strike' => [],
		'abbr' => [
			'title' => [],
		],
		'span' => [
			'class' => [],
		],
		'strong' => [],
	];

	if ( $level === 'intermediate' ) {
		$tags = [
			'a' => [
				'href' => [],
				'title' => [],
				'class' => [],
				'id' => [],
			],
			'q' => [
				'cite' => [],
			],
			'img' => [
				'src' => [],
				'alt' => [],
				'height' => [],
				'width' => [],
			],
			'dfn' => [
				'title' => [],
			],
			'time' => [
				'datetime' => [],
			],
			'cite' => [
				'title' => [],
			],
			'acronym' => [
				'title' => [],
			],
			'hr' => [],
		];

		$allowed_html = array_merge( $allowed_html, $tags );
	}

	return $allowed_html;
}

/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param string $string
 * @return string
 */
function meafe_kses_intermediate( $string = '' ) {
	return wp_kses( $string, meafe_get_allowed_html_tags( 'intermediate' ) );
}


add_action( 'category_add_form_fields', 'meafe_add_category_image' );
add_action( 'created_category', 'meafe_save_category_image' );
add_action( 'category_edit_form_fields', 'meafe_update_category_image' );
add_action( 'edited_category', 'meafe_updated_category_image' );
add_filter( 'manage_edit-category_columns', 'meafe_custom_column_header', 10 );
add_action( 'manage_category_custom_column', 'meafe_custom_column_content', 10, 3 );
add_action( 'admin_footer', 'meafe_add_script' );

// adding container for BTEN
if( ! function_exists( 'meafe_shortcode_add_inner_div' ) ) :
    function meafe_shortcode_add_inner_div(){
    	$meafe_theme_data = wp_get_theme();
    	$meafe_theme_array = array( 
    		'Blossom Coach Pro', 
    		'Blossom Coach',
    		'Blossom Consulting',
    		'Life Coach',
    		'Blossom Health Coach',
    		'Blossom Speaker',
    		'Perfect Coach',
    		'Personal Coach'
    	);
        return ( ! in_array( $meafe_theme_data->get( 'Name' ), $meafe_theme_array ) );
    }
endif;
add_filter( 'bt_newsletter_shortcode_inner_wrap_display', 'meafe_shortcode_add_inner_div' );

if( ! function_exists( 'meafe_shortcode_start_inner_div' ) ) :
    function meafe_shortcode_start_inner_div(){
        echo '<div class="container">';
    }
endif;
add_action( 'bt_newsletter_shortcode_inner_wrap_start', 'meafe_shortcode_start_inner_div' );

if( ! function_exists( 'meafe_shortcode_end_inner_div' ) ) :
    function meafe_shortcode_end_inner_div(){
        echo '</div>';
    }
endif;
add_action( 'bt_newsletter_shortcode_inner_wrap_close', 'meafe_shortcode_end_inner_div' );

if( ! function_exists( 'meafe_products_tab_content' ) ) :
	function meafe_products_tab_content(){
		ob_start();
		$data 			= json_decode(file_get_contents('php://input'), true);
		$cat_id         = isset( $data['ajaxData']['cat_id'] ) ? $data['ajaxData']['cat_id'] : '';
		$ed_cat         = isset( $data['ajaxData']['edCat'] ) ? $data['ajaxData']['edCat'] : '';
		$ed_title       = isset( $data['ajaxData']['edTitle'] ) ? $data['ajaxData']['edTitle'] : '';
		$ed_price       = isset( $data['ajaxData']['edPrice'] ) ? $data['ajaxData']['edPrice'] : '';
		$ed_cart        = isset( $data['ajaxData']['edCart'] ) ? $data['ajaxData']['edCart'] : '';
		$ed_quick_view  = isset( $data['ajaxData']['edQuickView'] ) ? $data['ajaxData']['edQuickView'] : '';
		$edWishlist  	= isset( $data['ajaxData']['edWishlist'] ) ? $data['ajaxData']['edWishlist'] : '';
		$ed_excerpt     = isset( $data['ajaxData']['edExcerpt'] ) ? $data['ajaxData']['edExcerpt'] : '';
		$excerpt_no     = isset( $data['ajaxData']['excerptNo'] ) ? $data['ajaxData']['excerptNo'] : '';
		$post_no		= isset( $data['ajaxData']['postNo'] ) ? $data['ajaxData']['postNo'] : '';
		$layout         = isset( $data['ajaxData']['layout'] ) ? $data['ajaxData']['layout'] : '';
		$ed_badge       = isset( $data['ajaxData']['edBadge'] ) ? $data['ajaxData']['edBadge'] : '';
		$prodType       = isset( $data['ajaxData']['prodType'] ) ? $data['ajaxData']['prodType'] : '';
		$prodSelect     = isset( $data['ajaxData']['prodSelect'] ) ? $data['ajaxData']['prodSelect'] : '';
		$catText        = isset( $data['ajaxData']['catText'] ) ? $data['ajaxData']['catText'] : '';
		$edCarousel     = isset( $data['ajaxData']['edCarousel'] ) ? $data['ajaxData']['edCarousel'] : '';
		$edDots         = isset( $data['ajaxData']['edDots'] ) ? $data['ajaxData']['edDots'] : '';
		$navDetails     = isset( $data['ajaxData']['navDetails'] ) ? $data['ajaxData']['navDetails'] : '';

        $swiper_class = ( $edCarousel == true ) ? ' swiper-slide' : '';
		$swiper_enabled = '';
        if( $edCarousel == true ){
            $swiper_enabled = ' swiper-enabled';
        }

		if( $catText == 'All' && $prodSelect ){
			$categories = $prodSelect;
		}elseif( $catText != 'All' && $cat_id ){
			$categories = $cat_id;
		}
		
		$args = [
			'post_type' 	  => 'product',
			'post_status'     => 'publish',
			'posts_per_page'  => $post_no,
		];

		if( $cat_id || $prodSelect ){
			$args['tax_query'] = [
				[
					'taxonomy' 	=> 'product_cat',
					'terms'		=> $categories,
					'operator'  => 'IN',
				]
			];
		}

		if( $prodType == 'sales' ){
            $args['meta_query'] = WC()->query->get_meta_query();
            $args['post__in']   = wc_get_product_ids_on_sale(); 
        }elseif( $prodType == 'popular' ){
            $args['meta_key'] = 'total_sales';
            $args['order_by'] = 'meta_value_num';
        };

		$cat_query = new WP_Query( $args );

		if( $cat_query->have_posts() && meafe_is_woocommerce_activated() ) {
			echo '<div class="meafe-products ' . esc_attr( $swiper_enabled ) . '">';
				if( $edCarousel == true ) echo '<div class="swiper-container"><div class="swiper-wrapper">';
					while( $cat_query->have_posts() ) {
						$cat_query->the_post();
						echo '<div class="meafe-products-inner' . esc_attr($swiper_class) .'">';
							if ( has_post_thumbnail() ) {
								echo '<figure class="meafe-entry-media image-wrapper">';
									if( $ed_badge == 'true' ) woocommerce_show_product_sale_flash(); 
									if( $ed_cart == 'true' || ( is_yith_quickview_activated() && $ed_quick_view == 'true' ) || ( is_yith_whislist_activated() && $edWishlist == 'true' ) ){ 
										echo '<div class="product-meta">';
											if( $ed_cart == 'true' && $layout != 3 ){
												echo '<span class="add-to-cart">';
													woocommerce_template_loop_add_to_cart();
												echo '</span>';
											}
											if( $layout == 2 && ( ( is_yith_quickview_activated() && $ed_quick_view ) || ( is_yith_whislist_activated() && $edWishlist ) ) ) echo '<div class="product-icon-wrapper">';
												if( is_yith_quickview_activated() && $ed_quick_view ){
													echo '<span class="quickview-icon">';
														echo do_shortcode( '[yith_quick_view]' );
													echo '</span>';
												}
												if( is_yith_whislist_activated() && $edWishlist ){
													echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
												}
											if( $layout == 2 && ( ( is_yith_quickview_activated() && $ed_quick_view ) || ( is_yith_whislist_activated() && $edWishlist ) ) ) echo '</div>';
										echo '</div>';
									}
									echo '<a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '">
										<img src="' . esc_url( wp_get_attachment_image_url( get_post_thumbnail_id(), 'meafe-category-tab' ) ) . '" alt="' . esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) . '">
									</a>';
								echo '</figure>';
							}
							echo '<div class="meafe-entry-wrapper category--main">';
								if ( 'product' === get_post_type() && $ed_cat == 'true' ) {
									$categories_list = get_the_terms( get_the_ID(), 'product_cat' );
									if ( $categories_list ) {
										foreach( $categories_list as $product_cat ){
											echo '<span class="category--wrapper" itemprop="about">' . esc_html( $product_cat->name ) . '</span>';
										}
									}
								}
								if ( $ed_title ) {
									echo '<h2 class="meafe-entry-title"><a class="meafe-grid-post-link" href="' . esc_url( get_the_permalink() ). '" title="' . esc_html( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</a></h2>';
								}
								if( $layout == 3 && $ed_excerpt ) {
									echo '<div class="meafe-entry-content meafe-content">
										<p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $excerpt_no ) . '</p>';
									echo '</div>';
								}
								$stock = get_post_meta( get_the_ID(), '_stock_status', true );
								if( $ed_price || $stock == 'outofstock' ){
									echo '<div class="product-footer">';
										if( $ed_price ) woocommerce_template_single_price(); //price
										if( $stock == 'outofstock' ){
											echo '<span class="outofstock">' . esc_html__( 'Sold Out', 'mega-elements-addons-for-elementor' ) . '</span>';
										}
									echo '</div>';
								}
								if( $ed_cart && $layout == 3 ){
									echo '<span class="add-to-cart">';
										woocommerce_template_loop_add_to_cart();
									echo '</span>';
								}
							echo '</div>';
						echo '</div>';
					}
                if( $edCarousel == true ) echo '</div></div>';

				if($edCarousel == true && $edDots == true) { ?>
                    <!-- If we need pagination -->
                    <?php if( ($post_no > 3 && wp_is_mobile()) || ($post_no > 4 && !wp_is_mobile())) { ?>
                        <div class="prod-tab meafa-swiper-pagination"></div>
                    <?php }
                }

                if($edCarousel == true) { ?>
                    <!-- If we need navigation buttons -->
                    <?php if( ($post_no > 3 && wp_is_mobile()) || ($post_no > 4 && !wp_is_mobile())) { ?>
                        <div class="meafa-navigation-wrap">
                            <div class="prod-tab meafa-navigation-prev nav">
                                <?php echo $navDetails[0]; ?>
                            </div>
                            <div class="prod-tab meafa-navigation-next nav">
								<?php echo $navDetails[1]; ?>  
                            </div>
                        </div>
                    <?php } 
                }

			echo '</div>';
        }else{
			echo '<p class="no-posts-found">' . esc_html__( 'No Products found!', 'mega-elements-addons-for-elementor' ) . '</p>';
		}

		$output = ob_get_clean();

		wp_send_json_success($output);
		wp_die();
	}
endif;
add_action( 'wp_ajax_meafe_products_tab_content', 'meafe_products_tab_content' );
add_action( 'wp_ajax_nopriv_meafe_products_tab_content', 'meafe_products_tab_content' );


function is_yith_quickview_activated() {
    return class_exists( 'YITH_WCQV' ) ? true : false;
}

/**
 * Query Yith activation
 */
function is_yith_whislist_activated() {
    return class_exists( 'YITH_WCWL' ) ? true : false;
}

/**
 * Query WooCommerce activation
 */
function meafe_is_woocommerce_activated()
{
	return class_exists('woocommerce') ? true : false;
}