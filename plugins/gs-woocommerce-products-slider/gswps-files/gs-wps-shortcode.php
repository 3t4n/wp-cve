<?php
//--------- Getting values from setting panel ---------------- //

function gs_wps_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}

add_image_size('gswps_product_thumb', 350);

if( ! function_exists( 'gs_wpml_get_translated_id' ) ) {
    /**
     * Get the id of the current translation of the post/custom type
     *
     * @since  2.0.0
     * @author Andrea Frascaspata <andrea.frascaspata@yithemes.com>
     */
    function gs_wpml_get_translated_id( $id, $post_type ) {

        if ( function_exists( 'gs_object_id' ) ) {

            $id = gs_object_id( $id, $post_type, true );

        }

        return $id;
    }
}


// ---------- Shortcode [gs_wps] -------------
function gswps_latest_prod_shortcode() {
	add_shortcode( 'gs_wps', 'gs_woo_product_shortcode' );
}
add_action( 'init', 'gswps_latest_prod_shortcode' );

function gs_woo_product_shortcode( $atts ) {

	$gs_wps_products    = gs_wps_get_option( 'gs_wps_products', 'gs_wps_general', 10 );
	$gs_wps_prod_tit    = gs_wps_get_option( 'gs_wps_prod_tit', 'gs_wps_style', 15 );
	$gs_wps_theme       = gs_wps_get_option( 'gs_wps_theme', 'gs_wps_style', 'gs-effect-1' );
	$gs_wps_cols        = gs_wps_get_option( 'gs_wps_cols', 'gs_wps_general', 4 );
	$gs_wps_autoplay    = gs_wps_get_option( 'gs_wps_autoplay', 'gs_wps_general', 'on' );
	$gs_wps_autoplay    = ($gs_wps_autoplay === 'off' ? 'false' : 'true');
	$gs_wps_stp_hover   = gs_wps_get_option( 'gs_wps_stp_hover', 'gs_wps_general', 'on' );
	$gs_wps_stp_hover   = ($gs_wps_stp_hover === 'off' ? 'false' : 'true');
	$gs_wps_inf_loop    = gs_wps_get_option( 'gs_wps_inf_loop', 'gs_wps_general', 'on' );
	$gs_wps_inf_loop    = ($gs_wps_inf_loop === 'off' ? 'false' : 'true');
	$gs_wps_autop_speed = gs_wps_get_option( 'gs_wps_autop_speed', 'gs_wps_general', 1000 );
	$gs_wps_autop_tmout = gs_wps_get_option( 'gs_wps_autop_tmout', 'gs_wps_general', 2500 );
	$gs_wps_nav_spd     = gs_wps_get_option( 'gs_wps_nav_spd', 'gs_wps_general', 1000 );
	$gs_wps_nav_nxt     = gs_wps_get_option( 'gs_wps_nav_nxt', 'gs_wps_general', 'on' );
	$gs_wps_nav_nxt     = ($gs_wps_nav_nxt === 'off' ? 'none' : 'initial');
	$gs_wps_dots_nav    = gs_wps_get_option( 'gs_wps_dots_nav', 'gs_wps_general', 'on' );
	$gs_wps_dots_nav    = ($gs_wps_dots_nav === 'off' ? 'false' : 'true');
	$gs_wps_margin      = gs_wps_get_option( 'gs_wps_margin', 'gs_wps_general', 4 );
	$gs_wps_dot_each    = gs_wps_get_option( 'gs_wps_dot_each', 'gs_wps_general', 'on' );
	$gs_wps_dot_each    = ($gs_wps_dot_each === 'off' ? 'false' : 'true');

	extract(shortcode_atts( 
		array(
		'id'    	=> '',
		'posts' 	=> $gs_wps_products,
		'order'		=> 'DESC',
		'orderby'   => 'date',
		'product_cat' => '',
		'theme'		=> $gs_wps_theme,
		'columns'	=> $gs_wps_cols,
		'autoplay'	=> $gs_wps_autoplay,
		'pause' 	=> $gs_wps_stp_hover,
		'inf_loop'	=> $gs_wps_inf_loop,
		'speed'		=> $gs_wps_autop_speed,
		'timeout' 	=> $gs_wps_autop_tmout,
		'nav_speed' => $gs_wps_nav_spd,
		'dots_nav'	=> $gs_wps_dots_nav,
		'prod_tit_limit' => $gs_wps_prod_tit
		), $atts 
	));
	$id  =   intval( gs_wpml_get_translated_id( $id, 'gs_wps_cpt' ) );

	$extra_params    =   array(
			'title'               =>   get_the_title( $id ),
           	'how_category'        =>   get_post_meta( $id, 'gs_product_category_type', true ),
            'product_type'        =>   get_post_meta( $id, 'gs_product_type', true ),
		);

	$atts = array_merge( $extra_params, $atts );
	
	
	$pro_category=$atts ['how_category'];
	$product_type=$atts ['product_type'];

	$categories='';

	if ( !empty( $pro_category ) && 'select_category' == $pro_category ){
		
		
		$categories =  get_post_meta( $id, 'gswps_select2_cats' ,true );
		
	    	
	    if( is_array($categories )){

	        $categories = implode(',', $categories );
	    }

	    if( !empty( $categories ) ) {
	        $categories = $categories;
	    }
	}

		$query_args = array(
		'post_type'			=> 'product',
		'posts_per_page' 	=> $posts,
		'order'				=> $order,
		'orderby'			=> $orderby,
		'product_cat'  		=> $categories,
		'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'exclude-from-catalog',
                'operator' => 'NOT IN',
            ),
		),
	);


	if ( !empty( $pro_category ) && 'select_category' == $pro_category ){
	$exclude_categores= get_post_meta( $id, 'gswps_select2_cats_exclude' ,true );
	if(!empty($exclude_categores)){

		$tax_query[] = array(
           'taxonomy' 	=> 'product_cat',
           'field' 		=> 'slug',
           'terms' 		=> $exclude_categores,
           'operator' 	=> 'NOT IN'
    	);

		$query_args['tax_query'] = $tax_query;
	}
}  

	$gs_wps_loop = new WP_Query($query_args);
	
	
	$output = '<div class="wrap gs_wps_area" style="overflow:hidden;">';
	$output .= '<div class="gs-container">';
	$output .= '<div class="gs-row clearfix gs_wps gs_grid '. esc_attr($theme) .'" id="">';
		if ( $gs_wps_loop->have_posts() ) {
			
			while ( $gs_wps_loop->have_posts() ) {
				$gs_wps_loop->the_post();				
				
				$gs_wps_title = get_the_title();
				$gs_wps_title = (strlen($gs_wps_title) > 15) ? substr($gs_wps_title,0,$prod_tit_limit).'..' : $gs_wps_title;

					$output .= gswps_style_swither( $theme, $gs_wps_title, $gs_wps_loop );

					
			} // end while loop

		} else {
			$output .= "No Product Added!";
		}
		

		wp_reset_postdata();
		wp_reset_query();
	$output .= '</div>'; // end row
	$output .= '</div>'; // end container
	$output .= '<script>
		jQuery(document).ready(function(){
		    jQuery(".gs_wps").owlCarousel({
		        autoplay: '. $autoplay.',
				autoplayHoverPause: '. $pause.',
				loop: '. $inf_loop.',
				margin: '. $gs_wps_margin.',
				autoplaySpeed: '. $speed.',
				autoplayTimeout: '. $timeout.',
				navSpeed: '. $nav_speed.',
				dots: '. $dots_nav.',
			    dotsEach: '. $gs_wps_dot_each.', 
			    responsiveClass:true,
			    lazyLoad: true,
			    responsive:{
			        0:{
			            items:1,
			            nav:false
			        },
			        600:{
			            items:3,
			            nav:false
			        },
			        1000:{
			            items: '. $columns.',
			            nav:true
			        }
			    }    
		    })
		});
	</script>';
	$output .= '</div>'; // end wrap

	return $output;
}

function gs_wps_setting_styles($nav) { 
	$gs_wps_title = gs_wps_get_option( 'gs_wps_title', 'gs_wps_style', '#fff' );
	$gs_wps_btn = gs_wps_get_option( 'gs_wps_btn', 'gs_wps_style', '#ed4e6e' );
	$gs_wps_btn_hvr = gs_wps_get_option( 'gs_wps_btn_hvr', 'gs_wps_style', '#ed90a1' );
	$gs_wps_prod_price = gs_wps_get_option( 'gs_wps_prod_price', 'gs_wps_style', '#fff' );
	$gs_wps_nv_bg = gs_wps_get_option( 'gs_wps_nv_bg', 'gs_wps_style', '#3783a7' );
	$gs_wps_nv_hv = gs_wps_get_option( 'gs_wps_nv_hv', 'gs_wps_style', '#0fb9da' );
	$gs_wps_dot_nv_bg = gs_wps_get_option( 'gs_wps_dot_nv_bg', 'gs_wps_style', '#3783a7' );
	$gs_wps_dot_nv_ac = gs_wps_get_option( 'gs_wps_dot_nv_ac', 'gs_wps_style', '#0fb9da' );
	$gs_wps_nav_nxt = gs_wps_get_option( 'gs_wps_nav_nxt', 'gs_wps_general', 'on' );
	$gs_wps_nav_nxt = ($gs_wps_nav_nxt === 'off' ? 'none' : 'initial');
?>		
	<style>
		.gs_wps .gs_wps_title a {
			color: <?php echo $gs_wps_title; ?>;
			transition: .5s;
		}
		.gs_wps .gs_wps_title a:hover {
			color: <?php echo $gs_wps_title; ?>;
			opacity: .9;
			text-decoration: none;
		}
		.gs_wps .gs_wps_price .add_to_cart_button {
			background: <?php echo $gs_wps_btn; ?>;
			transition: .5s;
			color: #fff;
		}
		.gs_wps .gs_wps_price .add_to_cart_button:hover {
			background: <?php echo $gs_wps_btn_hvr; ?>;
			text-decoration: none;
		}
		.gs_wps .gs_wps_price .amount {
			color: <?php echo $gs_wps_prod_price; ?>;
		}
		.gs_wps .woocommerce ins {
			background: transparent;
		}
		.gs_wps .owl-controls .owl-dots .owl-dot span {
			background: <?php echo $gs_wps_dot_nv_bg; ?>;
		}
		.gs_wps .owl-controls .owl-dots .owl-dot.active span {
			background: <?php echo $gs_wps_dot_nv_ac; ?>;
		}
		.gs_wps .owl-controls .owl-nav {
			display: <?php echo $gs_wps_nav_nxt; ?>;
		}
		.gs_wps .owl-controls .owl-nav .owl-prev,
		.gs_wps .owl-controls .owl-nav .owl-next {
			background: <?php echo $gs_wps_nv_bg; ?>;
			transition: .5s;
		}
		.gs_wps .owl-controls .owl-nav .owl-prev:hover,
		.gs_wps .owl-controls .owl-nav .owl-next:hover {
			background: <?php echo $gs_wps_nv_hv; ?>;
		}
	</style>		
<?php
}

add_action('wp_head', 'gs_wps_setting_styles');



function gs_wps_gutenberg_boilerplate_block() {

	if( !function_exists('register_block_type')){
		return;
	}
    wp_enqueue_script(
        'gs-wps-gutenberg-editor_scripts',
        GSWPS_FILES_URI . '/gswps-admin/js/wpslide-block.js',
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' )
    );

    register_block_type( 'gs-wps/shortcode-script', array(
        'editor_script' => 'gs-wps-gutenberg-editor_scripts',
    ) );
    register_block_type( 'gs-wps/wpsshortcodeblock', array(
    'render_callback' => 'gs_wps_gutenberg_render'
) );
  
}
 add_action( 'enqueue_block_assets', 'gs_wps_gutenberg_boilerplate_block' );

function gs_wps_gutenberg_render( $attributes ) {

    $themes =isset( $attributes['themes'] )? $attributes['themes']: 'gs-effect-1';
    $count = isset( $attributes['numb']) ? $attributes['numb']:10;
    $cols = isset( $attributes['columns']) ? $attributes['columns']:'4';
    $orders = isset( $attributes['orders']) ? $attributes['orders']:'DESC';
    $dotnav = isset( $attributes['dotnav']) && $attributes['dotnav']==1 ? 'true' : 'false';
    $autoplay = isset( $attributes['autoplay']) && $attributes['autoplay']==1 ? 'true' : 'false';
    //$owlnav = isset( $attributes['owlnav']) && $attributes['owlnav'] !=1 ? 'none':'initial';
    $product_cat = isset( $attributes['product_cat']) ? $attributes['product_cat']:'';

    return '[gs_wps theme="'.$themes.'" posts="'.$count.'" columns="'.$cols.'" dots_nav="'.$dotnav.'"  product_cat="'.$product_cat.'" order="'.$orders.'" autoplay="'.$autoplay.'"]';
}