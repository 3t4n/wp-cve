<?php 

use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_Flatsome as ADMINZ_Flatsome;
use Adminz\Admin\ADMINZ_Woocommerce as ADMINZ_Woocommerce;
if(!class_exists( 'WooCommerce' ) ) return;
add_action('ux_builder_setup', 'adminz_woocommerce_taxonomy_list');
function adminz_woocommerce_taxonomy_list(){	
	if(!Adminz::is_woocommerce()) return;    
	$tax_arr = ADMINZ_Woocommerce::get_arr_tax();
	// echo "<pre>";print_r($tax_arr);echo "</pre>";die;
	$repeater_col_spacing = 'normal';
    $repeater_columns = '4';
    $repeater_type = 'slider'; 
    $default_text_align = "center";
    $repeater_col_spacing = "normal";
	$options = array(
		'style_options' => array(
		    'type' => 'group',
		    'heading' => __( 'Style' ),
		    'options' => array(
		         'style' => array(
		            'type' => 'select',
		            'heading' => __( 'Style' ),
		            'default' => 'badge',
		            'options' => require( get_template_directory().'/inc/builder/shortcodes/values/box-layouts.php' )
		        )
		    ),
		),
		'layout_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-options.php' ),
		'layout_options_slider' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-slider.php' ),
		'cat_meta' => array(
		    'type' => 'group',
		    'heading' => __( 'Meta' ),
		    'options' => array(
		    	'taxonomies' => array(
			        'type' => 'select',
			        'heading' => 'Select taxonomies', 
			        'default' => 'product_cat',
			        'options' => $tax_arr
			    ),

			    // 'taxonomies' => array(
			    //     'type' => 'select',
                //     'param_name' => 'slug',
                //     'default'=> 'product_cat',
                //     'heading' => 'Select taxonomies',                    
                //     'config' => array(
                //         'multiple' => false,
                //         'placeholder' => 'Select..',
                //         'options' => $tax_arr
                //     ),
			    // ),
			    
		  	),
		)
	);

	$__cat_meta = [];
	if(!empty($tax_arr) and is_array($tax_arr)){
	    foreach ($tax_arr as $key => $value) {
	        if($key){
	        	$__cat_meta['terms__'.$key] = [
			        'type' => 'select',
			        'heading' => 'Terms '.$key,
			        'conditions' => 'taxonomies ==="'.$key.'"',
			        'param_name' => 'ids',
			        'config' => array(
			            'multiple' => true,
			            'placeholder' => 'Select..',
			            'termSelect' => array(
			                // 'post_type' => 'product_cat',
			                'taxonomies' => $key
			            ),
			        )
			    ];
	        }
	    }
	}


	$__cat_meta2 = [
		
	    'number' => array(
	        'type' => 'textfield',
	        'heading' => 'Total',			        
	        'default' => '',
	    ),

	    'offset' => array(
	        'type' => 'textfield',
	        'heading' => 'Offset',			        
	        'default' => '',
	    ),

	    'orderby' => array(
	        'type' => 'select',
	        'heading' => __( 'Order By' ),
	        'default' => 'menu_order',
	        'options' => array(
	            'name' => 'Name',
	            'date' => 'Date',
	            'menu_order' => 'Menu Order',
	            'count'=> 'Product count',
	        )
	    ),
	    'order' => array(
	        'type' => 'select',
	        'heading' => __( 'Order' ),
	        'default' => 'asc',
	        'options' => array(
	            'asc' => 'ASC',
	            'desc' => 'DESC',
	        )
	    ),
	   	'show_count' => array(
	        'type' => 'checkbox',
	        'heading' => 'Show Count',
	        'default' => 'true'
	    ),
	   	'show_rest' => array(
	        'type' => 'checkbox',
	        'heading' => 'Show Rest ',
	        'default' => ''
	    ),
   	];

	$options['cat_meta']['options'] = array_merge($options['cat_meta']['options'], $__cat_meta, $__cat_meta2);

	$box_styles = require( get_template_directory().'/inc/builder/shortcodes/commons/box-styles.php' );
	$options = array_merge($options, $box_styles);

	$advanced = array('advanced_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/advanced.php'));
	$options = array_merge($options, $advanced);



	add_ux_builder_shortcode('adminz_woocommerce_taxonomy_list', array(
        'name'      => "Taxonomies of ".__('Product','administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'categories' . '.svg',
        'info'      => '{{ id }}',
        'options' => $options
    ));
}

// content shortcode
add_shortcode('adminz_woocommerce_taxonomy_list','adminz_woocommerce_taxonomy_list_f');
function adminz_woocommerce_taxonomy_list_f($atts, $content = null, $tag = '' ) {
	if(!Adminz::is_woocommerce()) return;    

	$default = [
		// Meta
		'number'     => null,
		'_id' => 'cats-'.rand(),
		'taxonomies'=> 'product_cat',
		//'ids' => false, // Custom IDs
		'title' => '',
		'cat' => '',
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
		'hide_empty' => 1,
		'parent'     => 'false',
		'offset' => '',
		'show_count' => 'true',
		'show_rest' => '',
		'class' => '',
		'visibility' => '',

		// Layout
		'style' => 'badge',
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
		'slider_nav_color' => '',
		'slider_nav_position' => '',
		'slider_bullets' => 'false',
		'slider_arrows' => 'true',
		'auto_slide' => 'false',
		'infinitive' => 'true',
		'depth' => '',
		'depth_hover' => '',

		// Box styles
		'animate' => '',
		'text_pos' => '',
		'text_padding' => '',
		'text_bg' => '',
		'text_color' => '',
		'text_hover' => '',
		'text_align' => 'center',
		'text_size' => '',

		'image_size' => '',
		'image_mask' => '',
		'image_width' => '',
		'image_hover' => '',
		'image_hover_alt' => '',
		'image_radius' => '',
		'image_height' => '',
		'image_overlay' => '',

		// depricated
		'bg_overlay' => '#000',
	];

	$tax_arr = ADMINZ_Woocommerce::get_arr_tax();
	if(!empty($tax_arr) and is_array($tax_arr)){
	    foreach ($tax_arr as $key => $value) {
	        if($key){
	        	$default['terms__'.$key] = '';
	        }
	    }
	}

  	extract( shortcode_atts( $default, $atts ) );

  	
	if($tag == 'ux_product_attributes_grid'){
		$type = 'grid';
	}

    $hide_empty = ( $hide_empty == true || $hide_empty == 1 ) ? 1 : 0;    
    
    
    // get terms and workaround WP bug with parents/pad counts
	$args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
		//'include'    => $ids,
		'pad_counts' => true,
		'child_of'   => 0,
		'offset' => $offset,
	);
	// if Ids
	
    if ( $taxonomies ) {
      	$parent = '';
      	$orderby = 'include';
      	$args['taxonomy'] = $taxonomies;
    } else {
      	$args['taxonomy'] = 'product_cat';
    }
    // var_dump($taxonomies);
    if(isset(${'terms__'.$taxonomies})){
    	$__terms = ${'terms__'.$taxonomies};
    	$args['include'] = $__terms;
    }

	
	ob_start();

    $product_attributes = get_terms( $args );
    // echo "<pre>";print_r($atts);echo "</pre>";
    

	if ( ! empty( $parent ) ) $product_attributes = wp_list_filter( $product_attributes, array( 'parent' => $parent === 'false' ? 0 : $parent ) );


	$small_attributes = [];
    if ( !empty($number) ) {    	
    	// nếu có giới hạn thì cắt bớt chuỗi
    	//$product_attributes = array_slice( $product_attributes, 0, $number );
    	$tmp = [];
		$tmp2 = [];
    	if(!empty($product_attributes) and is_array($product_attributes)){    		
    		foreach ($product_attributes as $key => $value) {
    			if(($key+1)<=$number){
    				$tmp[] = $value;
    			}else{
    				// và lấy số còn lại vào small_attributes
    				$tmp2[] = $value;
    			}    			
    		}
    		$product_attributes = $tmp;
    	}
    	if($show_rest ){
    		$small_attributes = $tmp2;
    	}
    	// nếu bật option hiện số còn lại thì gán vào biến $small_attributes
    }

    $classes_box = array('box','box-category','has-hover');
    $classes_image = array();
    $classes_text = array();




    // Create Grid
    if($type == 'grid'){
		$columns = 0;
		$current_grid = 0;
		
		// Set image size 
	  	if($image_size){
		    add_filter('adminz_flatsome_get_grid',function($grid){
				if(!empty($grid) and is_array($grid)){
				    foreach ($grid as $key => $value) {
				        $grid[$key]['size'] = 'full';
				    }
				}
				return $grid;
			});
		}

		// $grid = flatsome_get_grid($grid);
        $grid = apply_filters('adminz_flatsome_get_grid',flatsome_get_grid($grid),$grid);
		$grid_total = count($grid);
		flatsome_get_grid_height($grid_height, $_id);
    }

    // Add Animations
    if($animate) {$animate = 'data-animate="'.$animate.'"';}

    // Set box style
    if($style) $classes_box[] = 'box-'.$style;
    if($style == 'overlay') $classes_box[] = 'dark';
    if($style == 'shade') $classes_box[] = 'dark';
    if($style == 'badge') $classes_box[] = 'hover-dark';
    if($text_pos) $classes_box[] = 'box-text-'.$text_pos;
    if($style == 'overlay' && !$image_overlay) $image_overlay = true;

    // Set image styles
    if($image_hover)  $classes_image[] = 'image-'.$image_hover;
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

    // Repeater options
    $repeater['id'] = $_id;
    $repeater['class'] = $class;
    $repeater['visibility'] = $visibility;
    $repeater['tag'] = $tag;
    $repeater['type'] = $type;
    $repeater['style'] = $style;
    $repeater['format'] = $image_height;
    $repeater['slider_style'] = $slider_nav_style;
    $repeater['slider_nav_color'] = $slider_nav_color;
    $repeater['slider_nav_position'] = $slider_nav_position;
    $repeater['slider_bullets'] = $slider_bullets;
    $repeater['auto_slide'] = $auto_slide;
	$repeater['infinitive'] = $infinitive;
    $repeater['row_spacing'] = $col_spacing;
    $repeater['row_width'] = $width;
    $repeater['columns'] = $columns;
    $repeater['columns__sm'] = $columns__sm;
    $repeater['columns__md'] = $columns__md;
    $repeater['depth'] = $depth;
    $repeater['depth_hover'] = $depth_hover;


    get_flatsome_repeater_start($repeater);

    if ( $product_attributes ) {
      	foreach ( $product_attributes as $category ) {

	        $classes_col = array('product-category','col');

	        $thumbnail_size   = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );

	        if($image_size) $thumbnail_size = $image_size;

	        if($type == 'grid'){
	            if($grid_total > $current_grid) $current_grid++;
	            $current = $current_grid-1;
	            $classes_col[] = 'grid-col';
	            if($grid[$current]['height']) $classes_col[] = 'grid-col-'.$grid[$current]['height'];
	            if($grid[$current]['span']) $classes_col[] = 'large-'.$grid[$current]['span'];
	            if($grid[$current]['md']) $classes_col[] = 'medium-'.$grid[$current]['md'];

	            // Set image size
	            if($grid[$current]['size'] == 'large') $thumbnail_size = 'large';
	            if($grid[$current]['size'] == 'medium') $thumbnail_size = 'medium';
	        }

	        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true  );
	        
	        if ( $thumbnail_id ) {
	          	$image = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size);
	          	$image = $image ? $image[0] : wc_placeholder_img_src();
	        } else {
	          	$image = wc_placeholder_img_src();
	        }	
	         
	        if(isset(ADMINZ_Flatsome::$options['adminz_flatsome_portfolio_product_tax'])){   
		        $portfolio_tax = ADMINZ_Flatsome::$options['adminz_flatsome_portfolio_product_tax'];

		        if($portfolio_tax and ($portfolio_tax == $category->taxonomy)){
					$tax_featured_item = get_posts(
						[
							'name'=> sanitize_title($category->name),
							'post_type'=> 'featured_item',
							'posts_per_page' => 1, 
						]
					);	
					if(isset($tax_featured_item[0])){
						$image = get_the_post_thumbnail_url($tax_featured_item[0]->ID,'large');
					}				
		        }
	        }
	        
	        // for portfolio sync product tax 
	        

	        ?>
	        <div class="<?php echo esc_attr(implode(' ', $classes_col)); ?>" <?php echo esc_attr($animate);?>>
	            <div class="col-inner">
	            	<?php 
	            	$taxslug = $category->taxonomy;
	            	if(substr($category->taxonomy,0,3) == "pa_"){
						$taxslug = str_replace('pa_','filter_',$taxslug);
					}
	            	$link_arg = [
	            		$taxslug=> $category->slug,
	            		'post_type'=> 'product',	            		
	            	];
	            	$link = add_query_arg($link_arg, wc_get_page_permalink( 'shop' ));
	            	?>
					<a href="<?php echo esc_attr($link); ?>">
	                <div class="<?php echo esc_attr(implode(' ', $classes_box)); ?> ">
		                <div class="box-image" <?php echo get_shortcode_inline_css($css_args_img); ?>>
		                  	<div class="<?php echo esc_attr(implode(' ', $classes_image)); ?>" <?php echo get_shortcode_inline_css($css_image_height); ?>>
		                  	<?php echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="300" height="300" />'; ?>
		                  	<?php if($image_overlay){ ?><div class="overlay" style="background-color: <?php echo esc_attr($image_overlay);?>"></div><?php } ?>
		                  	<?php if($style == 'shade'){ ?><div class="shade"></div><?php } ?>
		                  	</div>
		                </div>
		                <div class="box-text <?php echo esc_attr(implode(' ', $classes_text)); ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
		                  	<div class="box-text-inner">
		                      	<h5 class="uppercase header-title">
		                      		<?php echo esc_attr($category->name); ?>
		                      	</h5>
		                      		<?php if($show_count) { ?>
		                      	<p class="is-xsmall uppercase count <?php if($style == 'overlay') echo 'show-on-hover hover-reveal reveal-small'; ?>">
									<?php if ( $category->count > 0 ) {
										echo apply_filters( 'woocommerce_subcategory_count_html', $category->count . ' ' . ( $category->count > 1 ? __( 'Products', 'administrator-z' ) : __( 'Product', 'administrator-z' ) ), $category );
			                      	}
			                      	?>
		                      	</p>
		                      	<?php } ?>
		                      	<?php
		                        	/** * woocommerce_after_subcategory_title hook */
		                        	do_action( 'woocommerce_after_subcategory_title', $category );
		                      	?>
		                  </div>
		                </div>
	                </div>
	            	</a>
	            </div>
	            </div>
	        <?php
      	}
    }
    woocommerce_reset_loop();

    get_flatsome_repeater_end($repeater);

    // nếu có $small_attributes thì gọi ra tại đây
    if(!empty($small_attributes) and is_array($small_attributes)){

    	echo '<div class="tagcloud">';
    	foreach ($small_attributes as $key => $value) {
    		$taxslug = $value->taxonomy;
        	if(substr($value->taxonomy,0,3) == "pa_"){
				$taxslug = str_replace('pa_','filter_',$taxslug);
			}
        	$link_arg = [
        		$taxslug=> $value->slug,
        		'post_type'=> 'product',	            		
        	];
        	$link = add_query_arg($link_arg, wc_get_page_permalink( 'shop' ));
    		echo '<a href="'.esc_attr($link).'" class="tag-cloud-link" aria-label="'.esc_attr($value->name).'">'.esc_attr($value->name).' ('.esc_attr($value->count).')</a>';
    	}
    	echo '</div>';
    }
    $content = ob_get_contents();
    ob_end_clean();    
    return $content;
}