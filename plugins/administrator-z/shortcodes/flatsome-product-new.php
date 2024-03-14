<?php 

use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_Woocommerce as ADMINZ_Woocommerce;
use Adminz\Admin\ADMINZ_Flatsome as ADMINZ_Flatsome;
if(!class_exists( 'WooCommerce' ) ) return;
add_action('ux_builder_setup', function(){
	$repeater_columns = '4';
	$repeater_type = 'slider';
	$repeater_col_spacing = 'small';

	$repeater_posts = 'products';
	$repeater_post_type = 'product';
	$repeater_post_cat = 'product_cat';
	$default_text_align = 'center';	
	$options = array(
		'style_options' => array(
		    'type' => 'group',
		    'heading' => __( 'Style' ),
		    'options' => array(
		         'style' => array(
		            'type' => 'select',
		            'heading' => __( 'Style' ),
		            'default' => 'default',
		            'options' => require( get_template_directory().'/inc/builder/shortcodes/values/box-layouts.php' )
		        )
		    ),
		),
		'layout_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-options.php' ),
		'layout_options_slider' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-slider.php' ),
		'box_options' => array(
			'type'    => 'group',
			'heading' => __( 'Box' ),
			'options' => array(
				'show_cat' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Category' ),
					'default' => 'true',
				),
				'show_title' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Title' ),
					'default' => 'true',
				),
				'show_rating' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Rating' ),
					'default' => 'true',
				),
				'show_price' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Price' ),
					'default' => 'true',
				),
				'show_add_to_cart' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Add To Cart' ),
					'default' => 'true',
				),
				'show_quick_view' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Quick View' ),
					'default' => 'true',
				),
				'equalize_box' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Equalize Items' ),
					'default' => 'false',
				),
			),
		),
		'post_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-posts.php' ),
		'filter_posts' => array(
		    'type' => 'group',
		    'heading' => __( 'Filter Posts' ),
		    'conditions' => 'ids == ""',
		    'options' => array(
		         'orderby' => array(
		            'type' => 'select',
		            'heading' => __( 'Order By' ),
		            'default' => 'normal',
		            'options' => array(
		                'normal' => 'Normal',
		                'title' => 'Title',
		                'sales' => 'Sales',
		                'rand' => 'Random',
		                'date' => 'Date'
		            )
		        ),
		        'order' => array(
		            'type' => 'select',
		            'heading' => __( 'Order' ),
		            'default' => 'desc',
		            'options' => array(
		                'asc' => 'ASC',
		                'desc' => 'DESC',
		            )
		        ),
		        'show' => array(
		            'type' => 'select',
		            'heading' => __( 'Show' ),
		            'default' => '',
		            'options' => array(
		                '' => 'All',
		                'featured' => 'Featured',
		                'onsale' => 'On Sale',
		            )
		        ),
		         'out_of_stock' => array(
			         	'type'    => 'select',
			         	'heading' => __( 'Out Of Stock' ),
			         	'default' => '',
			         	'options' => array(
			         	''        => 'Include',
			         	'exclude' => 'Exclude',
			         ),
		         ),
		    )
		)
	);

	$options['attributes_options'] = array(
		'type'    => 'group',
		'heading' => __( 'Filter Attributes ' ),
		'options'=> array(
		)
	); 

	$custom_tax = ADMINZ_Woocommerce::get_arr_tax(true);
    if(!empty($custom_tax) and is_array($custom_tax)){
        foreach ($custom_tax as $key => $value) {
        	$key = str_replace('filter','pa',$key);
            $options['attributes_options']['options'][$key] = array(
                'type' => 'select',
                'heading' => "Fixed ". $value,  
                'default'=> '',
                //'param_name' => 'slug',              
                //'conditions' => 'ids == ""',
                'config' => array(
                	'multiple' => true,
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'product',
                        'taxonomies' => $key
                    ),
                )
            );
        }        
    }
   	



	$box_styles = require( get_template_directory().'/inc/builder/shortcodes/commons/box-styles.php' );
	$options = array_merge($options, $box_styles);

	$options['image_options']['conditions'] = 'style !== "default"';
	$options['text_options']['conditions'] = 'style !== "default"';
	$options['layout_options']['options']['depth']['conditions'] = 'style !== "default"';
	$options['layout_options']['options']['depth_hover']['conditions'] = 'style !== "default"';

	$options['post_options']['options']['tags'] = array(
	  	'type' => 'select',
	  	'heading' => 'Tag',
	  	'conditions' => 'ids == ""',
	  	'default' => '',
	  	'config' => array(
	      	'placeholder' => 'Select...',
	      	'termSelect' => array(
          	'post_type' => 'product',
          	'taxonomies' => 'product_tag',
	      ),
	  )
	);
	add_ux_builder_shortcode('adminz_flatsome_products_new', array(
        'name'      => "Custom ". __("Products",'administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'products' . '.svg',
        'scripts' => array(
            'flatsome-masonry-js' => get_template_directory_uri() .'/assets/libs/packery.pkgd.min.js',
            'flatsome-isotope-js' => get_template_directory_uri() .'/assets/libs/isotope.pkgd.min.js',
        ),
        'info'      => '{{ id }}',
        'options' => $options
    ));
});










// content shortcode
add_shortcode('adminz_flatsome_products_new', function ($atts, $content = null, $tag = '' ) {
	
	  if ( ! is_array( $atts ) ) {
	    $atts = array();
	  }
	  $default_atts = array(
		'_id' => 'product-grid-'.rand(),
		'title' => '',
		'ids' => '',
		'style' => 'default',
		'class' => '',
		'visibility' => '',

		// Ooptions
		'back_image' => true,

		// Layout
		'columns' => '4',
		'columns__sm' => '',
		'columns__md' => '',
		'col_spacing' => 'small',
		'type' => 'slider', // slider, row, masonery, grid
		'width' => '',
		'grid' => '1',
		'grid_height' => '600px',
		'grid_height__md' => '500px',
		'grid_height__sm' => '400px',
		'slider_nav_style' => 'reveal',
		'slider_nav_position' => '',
		'slider_nav_color' => '',
		'slider_bullets' => 'false',
	 	'slider_arrows' => 'true',
		'auto_slide' => '',
		'infinitive' => 'true',
		'depth' => '',
   		'depth_hover' => '',
	 	'equalize_box' => 'false',
	 	// posts
	 	'products' => '8',
		'cat' => '',
		'excerpt' => 'visible',
		'offset' => '',
    	'filter' => '',
		// Posts Woo
		'orderby' => '', // normal, sales, rand, date
		'order' => '',
		'tags' => '',
		'show' => '', //featured, onsale
		'out_of_stock' => '', // exclude.
		// Box styles
		'animate' => '',
		'text_pos' => 'bottom',
	  	'text_padding' => '',
	  	'text_bg' => '',
		'text_color' => '',
		'text_hover' => '',
		'text_align' => 'center',
		'text_size' => '',
		'image_size' => '',
		'image_radius' => '',
		'image_width' => '',
		'image_height' => '',
	    'image_hover' => '',
	    'image_hover_alt' => '',
	    'image_overlay' => '',
		'show_cat' => 'true',
		'show_title' => 'true',
		'show_rating' => 'true',
		'show_price' => 'true',
		'show_add_to_cart' => 'true',
		'show_quick_view' => 'true',

	);
	$custom_tax = ADMINZ_Woocommerce::get_arr_tax(true);	
	$arr_doi_chieu = [];
  	if(!empty($custom_tax) and is_array($custom_tax)){
	    foreach ($custom_tax as $key => $value) {	        
	        // doi chieu 
	        $key2 = str_replace('-',"_",$key);
	        $default_atts[$key2] = "";
	        $arr_doi_chieu[$key2] = $key;

	        if(isset($atts[$key])){
	        	$atts[$key2] = $atts[$key]; 	
	        }
	    }
	}
	extract(shortcode_atts($default_atts, $atts));
	
	

	// Stop if visibility is hidden
  if($visibility == 'hidden') return;

	$items                             = flatsome_ux_product_box_items();
	$items['cat']['show']              = $show_cat;
	$items['title']['show']            = $show_title;
	$items['rating']['show']           = $show_rating;
	$items['price']['show']            = $show_price;
	$items['add_to_cart']['show']      = $show_add_to_cart;
	$items['add_to_cart_icon']['show'] = $show_add_to_cart;
	$items['quick_view']['show']       = $show_quick_view;
	$items                             = flatsome_box_item_toggle_start( $items );

	ob_start();

	// if no style is set
	if(!$style) $style = 'default';

	$classes_box = array('box');
	$classes_image = array();
	$classes_text = array();
	$classes_repeater = array( $class );

	if ( $equalize_box === 'true' ) {
		$classes_repeater[] = 'equalize-box';
	}

	// Fix product on small screens
	if($style == 'overlay' || $style == 'shade'){
		if(!$columns__sm) $columns__sm = 1;
	}

	if($tag == 'ux_bestseller_products') {
		if(!$orderby) $atts['orderby'] = 'sales';
	} else if($tag == 'ux_featured_products'){
		$atts['show'] = 'featured';
	} else if($tag == 'ux_sale_products'){
		$atts['show'] = 'onsale';
	} else if($tag == 'products_pinterest_style'){
		$type = 'masonry';
		$style = 'overlay';
		$text_align = 'center';
		$image_size = 'medium';
		$text_pos = 'middle';
		$text_hover = 'hover-slide';
		$image_hover = 'overlay-add';
		$class = 'featured-product';
		$back_image = false;
		$image_hover_alt = 'image-zoom-long';
	} else if($tag == 'product_lookbook'){
		$type = 'slider';
		$style = 'overlay';
		$col_spacing = 'collapse';
		$text_align = 'center';
		$image_size = 'medium';
		$slider_nav_style = 'circle';
		$text_pos = 'middle';
		$text_hover = 'hover-slide';
		$image_hover = 'overlay-add';
		$image_hover_alt = 'zoom-long';
		$class = 'featured-product';
		$back_image = false;
	}

	// Fix grids
	if($type == 'grid'){
	  if(!$text_pos) $text_pos = 'center';
	  if(!$text_color) $text_color = 'dark';
	  if($style !== 'shade') $style = 'overlay';
	  $columns = 0;
	  $current_grid = 0;
	  // $grid = flatsome_get_grid($grid);
    	$grid = apply_filters('adminz_flatsome_get_grid',flatsome_get_grid($grid),$grid);
	  $grid_total = count($grid);
	  flatsome_get_grid_height($grid_height, $_id);
	}

	// Fix image size
	if(!$image_size) $image_size = 'woocommerce_thumbnail';

   	// Add Animations
	if($animate) {$animate = 'data-animate="'.$animate.'"';}


	// Set box style
	if($class) $classes_box[] = $class;
	$classes_box[] = 'has-hover';
	if($style) $classes_box[] = 'box-'.$style;
	if($style == 'overlay') $classes_box[] = 'dark';
	if($style == 'shade') $classes_box[] = 'dark';
	if($style == 'badge') $classes_box[] = 'hover-dark';
	if($text_pos) $classes_box[] = 'box-text-'.$text_pos;
	if($style == 'overlay' && !$image_overlay) $image_overlay = true;

	if($image_hover) $classes_image[] = 'image-'.$image_hover;
	if($image_hover_alt)  $classes_image[] = 'image-'.$image_hover_alt;
	if($image_height)  $classes_image[] = 'image-cover';

	// Text classes
	if($text_hover) $classes_text[] = 'show-on-hover hover-'.$text_hover;
	if($text_align) $classes_text[] = 'text-'.$text_align;
	if($text_size) $classes_text[] = 'is-'.$text_size;
	if($text_color == 'dark') $classes_text[] = 'dark';

	$css_args_img = array(
	  array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
	  array( 'attribute' => 'width', 'value' => $image_width, 'unit' => '%' ),
	);

    $css_image_height = array(
      array( 'attribute' => 'padding-top', 'value' => $image_height),
    );

	$css_args = array(
        array( 'attribute' => 'background-color', 'value' => $text_bg ),
        array( 'attribute' => 'padding', 'value' => $text_padding ),
  	);

  	// If default style
  	if($style == 'default'){
  		$depth = get_theme_mod('category_shadow');
  		$depth_hover = get_theme_mod('category_shadow_hover');
  	}

	// Repeater styles
	$repeater['id'] = $_id;
	$repeater['title'] = $title;
	$repeater['tag'] = $tag;
	$repeater['class'] = implode( ' ', $classes_repeater );
	$repeater['visibility'] = $visibility;
	$repeater['type'] = $type;
	$repeater['style'] = $style;
	$repeater['slider_style'] = $slider_nav_style;
	$repeater['slider_nav_color'] = $slider_nav_color;
	$repeater['slider_nav_position'] = $slider_nav_position;
	$repeater['slider_bullets'] = $slider_bullets;
  	$repeater['auto_slide'] = $auto_slide;
	$repeater['infinitive'] = $infinitive;
	$repeater['row_spacing'] = $col_spacing;
	$repeater['row_width'] = $width;
	$repeater['columns'] = $columns;
	$repeater['columns__md'] = $columns__md;
	$repeater['columns__sm'] = $columns__sm;
	$repeater['filter'] = $filter;
	$repeater['depth'] = $depth;
	$repeater['depth_hover'] = $depth_hover;

	get_flatsome_repeater_start($repeater);

	?>
	<?php

		if(empty($ids)){
			$products;
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => $products,
				'ignore_sticky_posts' => true,
				'tax_query' => [
		            'relation'=> 'AND',            
		        ],
		        'meta_query' => [
		            'relation'=> 'AND',            
		        ],
			);

			switch ( $show ) {
				case 'featured':
					$args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',
					);
					break;
				case 'onsale':
					$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
					break;
			}

			if($order){
		        $args['order'] = $order;
		    }

		    switch ( $orderby ) {
				case 'menu_order':
					$args['orderby'] = 'menu_order';
					break;
				case 'title':
					$args['orderby'] = 'name';
					break;
				case 'date':
					$args['orderby'] = 'date';
					break;
				case 'price':
					$args['meta_key'] = '_price'; // @codingStandardsIgnoreLine
					$args['orderby']  = 'meta_value_num';
					break;
				case 'rand':
					$args['orderby'] = 'rand'; // @codingStandardsIgnoreLine
					break;
				case 'sales':
					$args['meta_key'] = 'total_sales'; // @codingStandardsIgnoreLine
					$args['orderby']  = 'meta_value_num';
					break;
				default:
					$args['orderby'] = 'date';
			}

		    if($offset){
		        $args['offset'] = $offset;
		    }

		    if($cat){
		        $args['tax_query'][] = [
		            'taxonomy' => "product_cat",
		            'field' => 'id',
		            'terms' => explode(",",$cat),
		            'include_children' => true,
		            'operator' => 'IN'
		        ];
		    }

		    if($tags){
		        $args['tax_query'][] = [
		            'taxonomy' => "product_tag",
		            'field' => 'id',
		            'terms' => explode(",",$tag),
		            'include_children' => true,
		            'operator' => 'IN'
		        ];
		    }

		    if ( $out_of_stock === 'exclude' ) {

				$product_visibility_term_ids = wc_get_product_visibility_term_ids();
				$args['tax_query'][]   = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['outofstock'],
					'operator' => 'NOT IN',
				);
			}

		    if(!empty($arr_doi_chieu) and is_array($arr_doi_chieu)){
		        foreach ($arr_doi_chieu as $key => $value) {		            
		            if($$key){		            	
		                $args['tax_query'][] = [
		                    'taxonomy' => $value,
		                    'field' => 'id',
		                    'terms' => explode(",",$$key),
		                    'include_children' => true,
		                    'operator' => 'IN'
		                ];
		            }
		        }        
		    }			    
			$products = new WP_Query( $args );

		} else {
			// Get custom ids
			$ids = explode( ',', $ids );
			$ids = array_map( 'trim', $ids );

			$args = array(
				'post__in' => $ids,
				'post_type' => 'product',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'post__in',
				'ignore_sticky_posts' => true,
			);

			$products = new WP_Query( $args );
		}

	    if ( $products->have_posts() ) : ?>

	     <?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php
          global $product;

          if($style == 'default'){
					 	 wc_get_template_part( 'content', 'product' );
					} else { ?>
	            	<?php

	            	$classes_col = array('col');

      					$out_of_stock = ! $product->is_in_stock();
      					if($out_of_stock) $classes[] = 'out-of-stock';

	            	if($type == 'grid'){
				        if($grid_total > $current_grid) $current_grid++;
				        $current = $current_grid-1;
				        $classes_col[] = 'grid-col';
				        if($grid[$current]['height']) $classes_col[] = 'grid-col-'.$grid[$current]['height'];

				        if($grid[$current]['span']) $classes_col[] = 'large-'.$grid[$current]['span'];
       					 if($grid[$current]['md']) $classes_col[] = 'medium-'.$grid[$current]['md'];
				        // Set image size
				        if($grid[$current]['size']) $image_size = $grid[$current]['size'];
				    }
	            	?>

	            	<div class="<?php echo esc_attr(implode(' ', $classes_col)); ?>" <?php echo esc_attr($animate);?>>
						<div class="col-inner">
						<?php woocommerce_show_product_loop_sale_flash(); ?>
						<div class="product-small <?php echo esc_attr(implode(' ', $classes_box)); ?>">
							<div class="box-image" <?php echo get_shortcode_inline_css($css_args_img); ?>>
								<div class="<?php echo esc_attr(implode(' ', $classes_image)); ?>" <?php echo get_shortcode_inline_css($css_image_height); ?>>
									<a href="<?php echo get_the_permalink(); ?>" aria-label="<?php echo esc_attr( $product->get_title() ); ?>">
										<?php
											if($back_image) flatsome_woocommerce_get_alt_product_thumbnail($image_size);
											echo woocommerce_get_product_thumbnail($image_size);
										?>
									</a>
									<?php if($image_overlay){ ?><div class="overlay fill" style="background-color: <?php echo esc_attr($image_overlay);?>"></div><?php } ?>
									 <?php if($style == 'shade'){ ?><div class="shade"></div><?php } ?>
								</div>
								<div class="image-tools top right show-on-hover">
									<?php do_action('flatsome_product_box_tools_top'); ?>
								</div>
								<?php if($style !== 'shade' && $style !== 'overlay') { ?>
									<div class="image-tools <?php echo flatsome_product_box_actions_class(); ?>">
										<?php  do_action('flatsome_product_box_actions'); ?>
									</div>
								<?php } ?>
								<?php if($out_of_stock) { ?><div class="out-of-stock-label"><?php _e( 'Out of stock', 'administrator-z' ); ?></div><?php }?>
							</div>

							<div class="box-text <?php echo esc_attr(implode(' ', $classes_text)); ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
								<?php
									do_action( 'woocommerce_before_shop_loop_item_title' );

									echo '<div class="title-wrapper">';
									do_action( 'woocommerce_shop_loop_item_title' );
									echo '</div>';

									echo '<div class="price-wrapper">';
									do_action( 'woocommerce_after_shop_loop_item_title' );
									echo '</div>';

									if($style == 'shade' || $style == 'overlay') {
									echo '<div class="overlay-tools">';
										do_action('flatsome_product_box_actions');
									echo '</div>';
									}

									do_action( 'flatsome_product_box_after' );

								?>
							</div>
						</div>
						</div>
					</div>
					<?php } ?>
	            <?php endwhile; // end of the loop. ?>
	        <?php

	        endif;
	        wp_reset_query();

	get_flatsome_repeater_end($repeater);
	flatsome_box_item_toggle_end( $items );

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
});
