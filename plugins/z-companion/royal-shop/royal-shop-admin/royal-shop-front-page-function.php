<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
/******************/
//Banner Function
/******************/
function z_companion_front_banner(){
$royal_shop_banner_layout     = get_theme_mod( 'royal_shop_banner_layout','bnr-three');
// first
$royal_shop_bnr_1_img     = get_theme_mod( 'royal_shop_bnr_1_img','');
$royal_shop_bnr_1_url     = get_theme_mod( 'royal_shop_bnr_1_url','');
// second
$royal_shop_bnr_2_img     = get_theme_mod( 'royal_shop_bnr_2_img','');
$royal_shop_bnr_2_url     = get_theme_mod( 'royal_shop_bnr_2_url','');
// third
$royal_shop_bnr_3_img     = get_theme_mod( 'royal_shop_bnr_3_img','');
$royal_shop_bnr_3_url     = get_theme_mod( 'royal_shop_bnr_3_url','');
// fouth
$royal_shop_bnr_4_img     = get_theme_mod( 'royal_shop_bnr_4_img','');
$royal_shop_bnr_4_url     = get_theme_mod( 'royal_shop_bnr_4_url','');
// fifth
$royal_shop_bnr_5_img     = get_theme_mod( 'royal_shop_bnr_5_img','');
$royal_shop_bnr_5_url     = get_theme_mod( 'royal_shop_bnr_5_url','');

if($royal_shop_banner_layout=='bnr-one'){?>
<div class="wzta-banner-wrap bnr-layout-1 thnk-col-1">
 	 <div class="wzta-banner-col1">
 	 	<div class="wzta-banner-col1-content"><a href="<?php echo esc_url($royal_shop_bnr_1_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_1_img );?>"></a>
 	 	</div>
 	 </div>
  </div>
<?php }elseif($royal_shop_banner_layout=='bnr-two'){?>
<div class="wzta-banner-wrap bnr-layout-2 thnk-col-2">
 	 <div class="wzta-banner-col1">
 	 	<div class="wzta-banner-col1-content"><a href="<?php echo esc_url($royal_shop_bnr_1_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_1_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col2">
 	 	<div class="wzta-banner-col2-content"><a href="<?php echo esc_url($royal_shop_bnr_2_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_2_img );?>"></a></div>
 	 </div>
  </div>

<?php }elseif($royal_shop_banner_layout=='bnr-three'){?>
<div class="wzta-banner-wrap bnr-layout-3 thnk-col-3">
 	 <div class="wzta-banner-col1">
 	 	<div class="wzta-banner-col1-content"><a href="<?php echo esc_url($royal_shop_bnr_1_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_1_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col2">
 	 	<div class="wzta-banner-col2-content"><a href="<?php echo esc_url($royal_shop_bnr_2_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_2_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col3">
 	 	<div class="wzta-banner-col3-content"><a href="<?php echo esc_url($royal_shop_bnr_3_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_3_img );?>"></a></div>
 	 </div>
  </div>
<?php }elseif($royal_shop_banner_layout=='bnr-four'){?>
 <div class="wzta-banner-wrap bnr-layout-4 thnk-col-5">
 	 <div class="wzta-banner-col">
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_1_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_1_img );?>"></a></div>
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_2_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_2_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col">
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_3_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_3_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col">
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_4_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_4_img );?>"></a></div>
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_5_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_5_img );?>"></a></div>
 	 </div>
  </div>
<?php }elseif($royal_shop_banner_layout=='bnr-five'){?>
 <div class="wzta-banner-wrap bnr-layout-5 thnk-col-4">
 	 <div class="wzta-banner-col">
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_1_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_1_img );?>"></a></div>
 	 	
 	 </div>
 	 <div class="wzta-banner-col">
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_2_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_2_img );?>"></a></div>
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_3_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_3_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col">
 	 	<div class="wzta-banner-item"><a href="<?php echo esc_url($royal_shop_bnr_4_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_4_img );?>"></a></div>
 	 </div>
  </div>
<?php 
 }elseif($royal_shop_banner_layout=='bnr-six'){?>
<div class="wzta-banner-wrap bnr-layout-6 thnk-col-3">
 	 <div class="wzta-banner-col1">
 	 	<div class="wzta-banner-col1-content"><a href="<?php echo esc_url($royal_shop_bnr_1_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_1_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col2">
 	 	<div class="wzta-banner-col2-content"><a href="<?php echo esc_url($royal_shop_bnr_2_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_2_img );?>"></a></div>
 	 </div>
 	 <div class="wzta-banner-col3">
 	 	<div class="wzta-banner-col3-content"><a href="<?php echo esc_url($royal_shop_bnr_1_url);?>"><img src="<?php echo esc_url($royal_shop_bnr_1_img );?>"></a></div>
 	 </div>
  </div>

<?php }
}
/**********************/
// Brand Function
/**********************/
//brand content output function
function z_companion_brand_content( $royal_shop_brand_content_id, $default ) {
//passing the seeting ID and Default Values
	$royal_shop_brand_content= get_theme_mod( $royal_shop_brand_content_id, $default );
		if ( ! empty( $royal_shop_brand_content ) ) :
			$royal_shop_brand_content = json_decode( $royal_shop_brand_content );
			if ( ! empty( $royal_shop_brand_content ) ) {
				foreach ( $royal_shop_brand_content as $brand_item ) :
					$icon   = ! empty( $brand_item->icon_value ) ? apply_filters( 'royal_shop_translate_single_string', $brand_item->icon_value, 'Brand section' ) : '';

					$image = ! empty( $brand_item->image_url ) ? apply_filters( 'royal_shop_translate_single_string', $brand_item->image_url, 'Brand section' ) : '';

					$title  = ! empty( $brand_item->title ) ? apply_filters( 'royal_shop_translate_single_string', $brand_item->title, 'Brand section' ) : '';
					$text   = ! empty( $brand_item->text ) ? apply_filters( 'royal_shop_translate_single_string', $brand_item->text, 'Brand section' ) : '';
					$link   = ! empty( $brand_item->link ) ? apply_filters( 'royal_shop_translate_single_string', $brand_item->link, 'Brand section' ) : '';
					$color  = ! empty( $brand_item->color ) ? $brand_item->color : '';
			?>	
		<div class="wzta-brands">
         	<a target="_blank" href="<?php echo esc_url($link); ?>">
        		<img src="<?php echo esc_url($image); ?>" class="cate-img">
            </a>
        </div> <!-- wzta-brands End -->
	
			<?php	
				
				endforeach;			
			} // End if().
		
	endif;	
}

//*********************//
// Highlight feature
//*********************//
function z_companion_highlight_content($royal_shop_highlight_content_id,$default){
	$royal_shop_highlight_content= get_theme_mod( $royal_shop_highlight_content_id, $default );
//passing the seeting ID and Default Values

	if ( ! empty( $royal_shop_highlight_content ) ) :

		$royal_shop_highlight_content = json_decode( $royal_shop_highlight_content );
		if ( ! empty( $royal_shop_highlight_content ) ) {
			foreach ( $royal_shop_highlight_content as $ship_item ) :
               $icon   = ! empty( $ship_item->icon_value ) ? apply_filters( 'royal_shop_translate_single_string', $ship_item->icon_value, '' ) : '';
				$title    = ! empty( $ship_item->title ) ? apply_filters( 'royal_shop_translate_single_string', $ship_item->title, '' ) : '';
				$subtitle    = ! empty( $ship_item->subtitle ) ? apply_filters( 'royal_shop_translate_single_string', $ship_item->subtitle, '' ) : '';
					?>
         <div class="wzta-highlight-col">
          	<div class="wzta-hglt-box">
          		<div class="wzta-hglt-icon"><i class="<?php echo "fa ".esc_attr($icon); ?>"></i></div>
          		<div class="content">
          			<h6><?php echo esc_html($title);?></h6>
          			<p><?php echo esc_html($subtitle);?></p>
          		</div>
          	</div>
          </div>
    			<?php
			endforeach;
		}
	endif;
}


// section is_customize_preview
/**
 * This function display a shortcut to a customizer control.
 *
 * @param string $class_name        The name of control we want to link this shortcut with.
 */
function z_companion_display_customizer_shortcut( $class_name ){
	if ( ! is_customize_preview() ) {
		return;
	}
	$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path>
        </svg>';
	echo'<span class="royal-shop-section customize-partial-edit-shortcut customize-partial-edit-shortcut-' . esc_attr( $class_name ) . '">
            <button class="customize-partial-edit-shortcut-button">
                ' . $icon . '
            </button>
        </span>';
}
// section is_customize_preview
/**
 * This function display a shortcut to a customizer control.
 *
 * @param string $class_name        The name of control we want to link this shortcut with.
 */
function z_companion_color_display_customizer_shortcut( $class_name ){
	if ( ! is_customize_preview() ) {
		return;
	}
	$icon = '<i class="fa fa-paint-brush" aria-hidden="true"></i>';
	echo'<span class="royal-shop-section customize-partial-edit-shortcut customize-partial-edit-shortcut-' . esc_attr( $class_name ) . ' color-shortcut">
            <button class="customize-partial-edit-shortcut-button">
                ' . $icon . '
            </button>
        </span>';
}
function z_companion_widget_script_registers(){
//widget
wp_enqueue_script( 'z_companion_widget_js');
}
add_action('customize_controls_enqueue_scripts', 'z_companion_widget_script_registers' );
add_action('admin_enqueue_scripts', 'z_companion_widget_script_registers' );
function z_companion_localize_settings(){
	$royalshoplocalize = array(
				'royal_shop_top_slider_optn' => (bool) get_theme_mod('royal_shop_top_slider_optn',false),
				'royal_shop_sidebar_front_option' => esc_html(get_theme_mod('royal_shop_sidebar_front_option','active-sidebar')),
					//WOOJS
				'ajaxUrl'  => esc_url(admin_url( 'admin-ajax.php' )),
				//cat-tab-filter
				'royal_shop_single_row_slide_cat' => (bool) get_theme_mod('royal_shop_single_row_slide_cat',false),
				'royal_shop_cat_slider_optn' => (bool) get_theme_mod('royal_shop_cat_slider_optn',false),
				
				//product-slider
				'royal_shop_single_row_prdct_slide' => (bool) get_theme_mod('royal_shop_single_row_prdct_slide',false),
				'royal_shop_product_slider_optn' => (bool) get_theme_mod('royal_shop_product_slider_optn',false),
				//cat-slider
				'royal_shop_category_slider_optn' => (bool) get_theme_mod('royal_shop_category_slider_optn',false),
				//product-list
				'royal_shop_single_row_prdct_list' => (bool) get_theme_mod('royal_shop_single_row_prdct_list',false),
				'royal_shop_product_list_slide_optn' => (bool) get_theme_mod('royal_shop_product_list_slide_optn',false),
				//cat-tab-list-filter
				'royal_shop_single_row_slide_cat_tb_lst' => (bool) get_theme_mod('royal_shop_single_row_slide_cat_tb_lst',false),
				'royal_shop_cat_tb_lst_slider_optn' => (bool) get_theme_mod('royal_shop_cat_tb_lst_slider_optn',false),
				//brand
				'royal_shop_brand_slider_optn' => (bool) get_theme_mod('royal_shop_brand_slider_optn',false),
				//big-feature-product
				'royal_shop_feature_product_slider_optn' => (bool) get_theme_mod('royal_shop_feature_product_slider_optn',false),
				// speed
				'royal_shop_cat_slider_speed' => esc_html(get_theme_mod('royal_shop_cat_slider_speed','3000')),
				'royal_shop_product_slider_speed' => esc_html(get_theme_mod('royal_shop_product_slider_speed','3000')),
				'royal_shop_category_slider_speed' => esc_html(get_theme_mod('royal_shop_category_slider_speed','3000')),
				'royal_shop_product_list_slider_speed' => esc_html(get_theme_mod('royal_shop_product_list_slider_speed','3000')),
				'royal_shop_feature_product_slider_speed' => esc_html(get_theme_mod('royal_shop_feature_product_slider_speed','3000')),
				'royal_shop_cat_tb_lst_slider_speed' => esc_html(get_theme_mod('royal_shop_cat_tb_lst_slider_speed','3000')),
				'royal_shop_brand_slider_speed' => esc_html(get_theme_mod('royal_shop_brand_slider_speed','3000')),
				'royal_shop_sidebar_front_option' => esc_html(get_theme_mod('royal_shop_sidebar_front_option','active-sidebar')),
				//vert-tab-filter2
				'royal_shop_single_row_slide_cat_vt2' => (bool) get_theme_mod('royal_shop_single_row_slide_cat_vt2',false),
				'royal_shop_vt2_cat_slider_optn' => (bool) get_theme_mod('royal_shop_vt2_cat_slider_optn',false),
				//category-filter
				'royal_shop_cat_item_no'	=>	get_theme_mod('royal_shop_cat_item_no',9),

			);
	return $royalshoplocalize;
}