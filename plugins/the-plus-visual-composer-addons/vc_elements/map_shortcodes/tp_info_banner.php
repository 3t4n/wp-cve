<?php 
// Info Banner Elements
if(!class_exists("ThePlus_info_banner")){
	class ThePlus_info_banner{
		function __construct(){
			add_action( 'init', array($this, 'init_tp_info_banner') );
			add_shortcode( 'tp_info_banner',array($this,'tp_info_banner_shortcode'));
			add_action( 'wp_enqueue_scripts', array( $this, 'tp_info_banner_scripts' ), 1 );
		}
		function tp_info_banner_scripts() {
			wp_register_style( 'theplus-info-banner-style', THEPLUS_PLUGIN_URL . 'vc_elements/css/main/theplus-info-banner-style.css', false, '1.0.0' );
		}
		function tp_info_banner_shortcode($atts,$content = null){
			extract( shortcode_atts( array(
					'banner_style' => 'style-1',
					'banner_img' =>'',
					'title' => 'The Plus',
					'desc' => '',
					'text_alignment' => 'text-left',
					'title_color' => '#ffffff',
					'title_hover_color' => '#ffffff',
					'title_size' => '40px',
					'title_lineheight' => '40px',
					'title_use_theme_fonts'=>'custom-font-family',
					'title_font_family'=>'',
					'title_font_weight'=>'600',
					
					'desc_color' => '#ffffff',
					'desc_size' => '14px',
					'desc_lineheight' => '30px',
					"btn_url" => '',

					'bg_color' => 'rgba(0, 0, 0, 0.33)',
					'background_hover_color' => 'rgba(0, 74, 245, 0.32)',
					'bg_banner'=>'solid',	
					'gradient_color1' => 'rgba(30,115,190,0.34)',
					'gradient_color2' => 'rgba(255,12,0,0.37)',
					'gradient_style' => 'horizontal',
					'hvr_gradient_color1' => 'rgba(255,12,0,0.37)',
					'hvr_gradient_color2' => 'rgba(30,115,190,0.34)',
					'gradient_hover_style' => 'horizontal',
					
										
					'subtitle' => 'Sub Title',
					'subtitle_color' => '#ffffff',
					'subtitle_hover_color' => '#ffffff',
					'subtitle_size' => '30px',
					'subtitle_lineheight' => '30px',
					'subtitle_use_theme_fonts'=>'custom-font-family',
					'subtitle_font_family'=>'',
					'subtitle_font_weight'=>'400',
					
					'hover_syl_col' => '#ff004b',
					'sep_col' => '#888888',
					'full_box' => '#888888',
					
					'box_sadow' => '3px 5px 20px #000',
					'hov_box_sadow'=> '0px 14px 20px #060606',
					
					
					'animation_effects'=>'no-animation',
					'animation_delay'=>'50',
					
					'el_class' =>'',
					
				), $atts ) );
wp_enqueue_style( 'theplus-info-banner-style');
					$rand_no=rand(1000000, 1500000);
					$banner_title=$banner_subtitle=$banner_desc='';
					
				/*--------------------------Title---------------------------- */
				if($title_use_theme_fonts=='custom-font-family'){
					$title_font_family='font-family:'.$title_font_family.';font-weight:'.$title_font_weight.';';
				}else{
					$title_font_family='';
				}	
				$infotitle_css = ' style="';
					  
					  if($title_size!= "") {
					  $infotitle_css .= 'font-size: '.esc_attr($title_size).';';
					  }
					  if($title_lineheight!= "") {
					  $infotitle_css .= 'line-height: '.esc_attr($title_lineheight).';';
					  }
					 
					$infotitle_css .= $title_font_family;
					 
				$infotitle_css .= '"';
				/*-------------------------------------------------------------------*/

				/*--------------------------Sub Title---------------------------- */
				if($subtitle_use_theme_fonts=='custom-font-family'){
					$subtitle_font_family='font-family:'.$subtitle_font_family.';font-weight:'.$subtitle_font_weight.';';
				}else{
					$subtitle_font_family='';
				}
				$infosubtitle_css = ' style="';
					  
					  if($subtitle_size!= "") {
					  $infosubtitle_css .= 'font-size: '.esc_attr($subtitle_size).';';
					  }
					  if($subtitle_lineheight!= "") {
					  $infosubtitle_css .= 'line-height: '.esc_attr($subtitle_lineheight).';';
					  }
					 
					 $infosubtitle_css .= $subtitle_font_family;
				$infosubtitle_css .= '"';
				/*-------------------------------------------------------------------*/

				/*--------------------------Description---------------------------- */
				 
				$infodesc_css = ' style="';
				  if($desc_size!= "") {
				  $infodesc_css .= 'font-size: '.esc_attr($desc_size).';';
				  }
				  if($desc_lineheight!= "") {
				  $infodesc_css .= 'line-height: '.esc_attr($desc_lineheight).';';
				  }
								  
				$infodesc_css .= '"';
				/*-------------------------------------------------------------------*/

				/*---------------------------gradient-----------------------------------*/
				if($bg_color){
					$bg_color1 = 'background: '.esc_attr($bg_color).';';
				}else{
					$bg_color1 ='';
				}
				if($background_hover_color){
					$bg_color_hover = 'background:'.esc_attr($background_hover_color).';';
				}else{
				$bg_color_hover ='';
				}
					
				/*-------------------------------------------------------------------*/	
				
				 
						$btn_url = ( '||' === $btn_url ) ? '' : $btn_url;
						$btn_url_a= vc_build_link( $btn_url);
						
						$a_href = $btn_url_a['url'];
						$a_title = $btn_url_a['title'];
						$a_target = $btn_url_a['target'];
						$a_rel = $btn_url_a['rel'];
						if ( ! empty( $a_rel ) ) {
							$a_rel = ' rel="' . esc_attr( trim( $a_rel ) ) . '"';
						}
				 
				if($title!= "") {
				$banner_title = '<h3 class="infobanner_title" '.$infotitle_css.'>'.esc_html($title).'</h3>';
				}
				if($desc!= "") {
				$banner_desc = '<p class="infobanner_desc" '.$infodesc_css.'>'.esc_html($desc).'</p>';
				}
if(!empty($subtitle)){
				if($banner_style == "style-3" || $banner_style == "style-5" || $banner_style == "style-7" || $banner_style == "style-10" || $banner_style == "style-11" || $banner_style == "style-12" || $banner_style == "style-13" || $banner_style == "style-14" || $banner_style == "style-16" || $banner_style == "style-15" || $banner_style == "style-17" || $banner_style == "style-18" || $banner_style == "style-19" || $banner_style == "style-20"){
				$banner_subtitle = '<h4 class="infobanner_subtitle" '.$infosubtitle_css.'>'.esc_html($subtitle).'</h4>';
				}
}
				if($animation_effects=='no-animation'){
						$animated_class='';
						$animation_effects='';
						$animation_delay='';
						$animation_delay_time='';
					}else{
						$animated_class='animate-general';
						$animation_effects=$animation_effects;
						$animation_delay_time=$animation_delay;
				}
				

				$banner_image = '';
						if ( $banner_img != '' ) {
								$full_image=wp_get_attachment_image_src( $banner_img, 'full' );
								
									$banner_image .='<img class="info_img" src="'.esc_url($full_image[0]).'" alt="">';
								}else{ 
									$banner_image .= pt_plus_loading_image_grid(get_the_ID());
						}
						
											$data_attr='';
							$data_attr .=' data-uid = "info-banner-'.esc_attr($rand_no).'"';
							$data_attr .=' data-banner_style = "'.esc_attr($banner_style).'"';
							$data_attr .=' data-title_color = "'.esc_attr($title_color).'"';
							$data_attr .=' data-title_hover_color = "'.esc_attr($title_hover_color).'"';
							$data_attr .=' data-desc_color = "'.esc_attr($desc_color).'"';
							$data_attr .=' data-bg_color1 = "'.esc_attr($bg_color1).'"';
							$data_attr .=' data-bg_color_hover = "'.esc_attr($bg_color_hover).'"';
							$data_attr .=' data-subtitle_color = "'.esc_attr($subtitle_color).'"';
							$data_attr .=' data-subtitle_hover_color = "'.esc_attr($subtitle_hover_color).'"';
							$data_attr .=' data-sep_col = "'.esc_attr($sep_col).'"';
							$data_attr .=' data-full_box = "'.esc_attr($full_box).'"'; 
							$data_attr .=' data-box_sadow = "'.esc_attr($box_sadow).'"';  
							$data_attr .=' data-hov_box_sadow = "'.esc_attr($hov_box_sadow).'"'; 
							
							
							
				   $loading_image_class='pt-plus-loading-image';
				   
				$info_banner = '<div class=" '.esc_attr($loading_image_class).'">';	
				$info_banner .='<div class="pt_plus_infobanner info-banner-'.esc_attr($banner_style).' info-banner-'.esc_attr($rand_no).' '.esc_attr($el_class).' '.esc_attr($animated_class).' box-saddow-info_banner grid-item" data-animate-type="'.esc_attr($animation_effects).'" data-animate-delay="'.esc_attr($animation_delay_time).'"> ';
					$info_banner .='<div class="grid">';
						$info_banner .='<figure class="infobanner_inner fi-gure reveal">';
							$info_banner .='<div class="infobanner_inner_img">';
								$info_banner .= $banner_image;
								
							$info_banner .='</div>';
							$info_banner .= '<figcaption class="fig-caption '.esc_attr($text_alignment).'">';
								$info_banner .='<div class="infobanner_content">';
									$info_banner .='<div class="infobanner_content_inner">';
										$info_banner .=$banner_title;
											$info_banner .=$banner_subtitle;
											   $info_banner .=$banner_desc;
									$info_banner .='</div>';
								$info_banner .='</div>';
								$info_banner .='<a  href="'.esc_url( $a_href ).'" title="'.esc_attr( $a_title ).'" target="'.esc_attr( $a_target ).'" '.$a_rel.' class="infobanner_link" ></a>';
							$info_banner .='</figcaption>';
						$info_banner .='</figure>';
					$info_banner .='</div>';
				$info_banner .='</div>';
				$info_banner .='</div>';
				
					$sep_bg_css='';
				if(!empty($sep_col)) {
						$sep_bg_css .= 'background: '.esc_attr($sep_col).';';
				}
		
				
$css_rule = '';
	$css_rule .= '<style >';
	
	if($banner_style == "style-4"){
		$css_rule .= '.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner.info-banner-style-4 .fi-gure.infobanner_inner .infobanner_title::after{'.esc_js($sep_bg_css).';}';
	}else if($banner_style == "style-6"){
		$css_rule .= '.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner.info-banner-style-6 .fi-gure.infobanner_inner .fig-caption::before, .info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner.info-banner-style-6 .fi-gure.infobanner_inner .fig-caption::after{'.esc_js($sep_bg_css).'}';
	}  
	
  $css_rule .= '.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner:hover.box-saddow-info_banner{-webkit-box-shadow:'.esc_js($hov_box_sadow).';-moz-box-shadow:'.esc_js($hov_box_sadow).';box-shadow:'.esc_js($hov_box_sadow).';}.info-banner-'.esc_attr($rand_no).'.box-saddow-info_banner{-webkit-box-shadow:'.esc_js($box_sadow).';-moz-box-shadow:'.esc_js($box_sadow).';box-shadow:'.esc_js($box_sadow).';}.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner .fi-gure.infobanner_inner .infobanner_title{color: '.esc_js($title_color).';}.info-banner-'.esc_attr($rand_no).' .infobanner_desc{color: '.esc_js($desc_color).';}.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner .fi-gure.infobanner_inner:hover .infobanner_title{color: '.esc_js($title_hover_color).';}.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner .fi-gure.infobanner_inner:hover .fig-caption{'.$bg_color_hover.'}.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner .fi-gure.infobanner_inner .fig-caption{'.esc_js($bg_color1).'}.info-banner-'.esc_attr($rand_no).'.pt_plus_infobanner .fi-gure.infobanner_inner .infobanner_subtitle{color: '.$subtitle_color.';}.info-banner-'.esc_attr($rand_no).' .pt_plus_infobanner .fi-gure.infobanner_inner:hover .infobanner_subtitle{color:'.esc_js($subtitle_hover_color).';}';

	$css_rule .= '</style>'; 
	
				return $css_rule.$info_banner;
		}
		function init_tp_info_banner(){
			if(function_exists("vc_map"))
			{
				vc_map(array(
					"name" => __("Info banner", "pt_theplus"),
					"base" => "tp_info_banner",
					"icon" => "tp-info-banner",
					"category" => __("The Plus", "pt_theplus"),
					"description" => esc_html__('Amazing Information Showcase', 'pt_theplus'),
					"params" => array(
						array(
								'type'        => 'radio_select_image',
								'heading' =>  esc_html__('Style', 'pt_theplus'), 
								'param_name'  => 'banner_style',				
								"admin_label" => true,
								'simple_mode' => false,
								'value'		=> 'style-1',
								'options'     => array(
									'style-1' => array(
										'tooltip' => esc_attr__('Style 1','pt_theplus'),
										'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-banner/style-1.jpg'
									),
									'style-2' => array(
										'tooltip' => esc_attr__('Style 2','pt_theplus'),
										'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-banner/style-2.jpg'
									),
									'style-3' => array(
										'tooltip' => esc_attr__('Style 3','pt_theplus'),
										'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-banner/style-3.jpg'
									),
									'style-6' => array(
										'tooltip' => esc_attr__('Style 6','pt_theplus'),
										'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-banner/style-6.jpg'
									),
									
								),
							),
						array(
							"type" => "dropdown",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Choose Info Banner alignment from Right, Left or Center.','pt_theplus').'</span></span>'.esc_html__('Alignment', 'pt_theplus')),
							"param_name" => "text_alignment",
							"value" => array(
								__("Left", "pt_theplus") => "text-left",
								__("Center", "pt_theplus") => "text-center",
								__("Right", "pt_theplus") => "text-right"
							),
							"std" => 'text-left'
						),
						array(
							'type' => 'attach_image',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Upload image of info banner using this option. .jpg, .png, .gif images supported.','pt_theplus').'</span></span>'.esc_html__('Banner Image', 'pt_theplus')),
							'param_name' => 'banner_img',
							'value' => '',
							'edit_field_class' => 'vc_col-xs-4',
						),
						array(
							'type' => 'textfield',
							'heading' =>  esc_html__('Title', 'pt_theplus'),
							"admin_label" => true,
							'param_name' => 'title',
							'value' => __('The Plus', 'pt_theplus'),
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Title Setting', 'pt_theplus'),
							'param_name' => 'title_setting',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							'param_name' => 'title_color',
							'value' => '#ffffff',
							'edit_field_class' => 'vc_col-xs-4',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font hover using this option.','pt_theplus').'</span></span>'.esc_html__('Font Hover Color', 'pt_theplus')),
							'param_name' => 'title_hover_color',
							'value' => '#ffffff',
							'edit_field_class' => 'vc_col-xs-4',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						
						array(
							'edit_field_class' => 'vc_col-xs-6',
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
							'param_name' => 'title_size',
							'value' => __('40px', 'pt_theplus'),
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							'param_name' => 'title_lineheight',
							'value' => __('40px', 'pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
								'type' => 'dropdown',
								'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Title Custom font family', 'pt_theplus'),
								'param_name' => 'title_use_theme_fonts',
								 "value" => array(
									esc_html__("Custom font family", 'pt_theplus') => "custom-font-family",
									esc_html__("Google fonts (Premium)", 'pt_theplus') => "google-fonts",
								),
								'std' =>  'custom-font-family',
								'group' => esc_attr__('Style', 'pt_theplus'),	
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
							'param_name' => 'title_font_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'title_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font weight using this Option. E.g. 200,400,700,900 etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
							'param_name' => 'title_font_weight',
							'value' => __('600','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'title_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						
						array(
							'type' => 'textfield',
							'heading' =>  esc_html__('Sub Title', 'pt_theplus'),
							'param_name' => 'subtitle',
							'value' => __('Sub Title', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-3',
								)
							)
							
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Sub Title Setting', 'pt_theplus'),
							'param_name' => 'subtitle_setting',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-3',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							'param_name' => 'subtitle_color',
							'value' => '#ffffff',
							'edit_field_class' => 'vc_col-xs-6',
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-3',
								)
							),
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font hover using this option.','pt_theplus').'</span></span>'.esc_html__('Font Hover Color', 'pt_theplus')),
							'param_name' => 'subtitle_hover_color',
							'value' => '#ffffff',
							'edit_field_class' => 'vc_col-xs-6',
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-3',
								)
							),
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'edit_field_class' => 'vc_col-xs-6',
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
							'param_name' => 'subtitle_size',
							'value' => __('30px', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-3',
								)
							),
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							'param_name' => 'subtitle_lineheight',
							'value' => __('30px', 'pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-3',
								)
							),
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
								'type' => 'dropdown',
								'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Subtitle Custom font family', 'pt_theplus'),
								'param_name' => 'subtitle_use_theme_fonts',
								 "value" => array(
									esc_html__("Custom font family", 'pt_theplus') => "custom-font-family",
									esc_html__("Google fonts (Premium)", 'pt_theplus') => "google-fonts",
								),
								'std' =>  'custom-font-family',
								'group' => esc_attr__('Style', 'pt_theplus'),	
								'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-3',
								)
							),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
							'param_name' => 'subtitle_font_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'subtitle_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font weight using this Option. E.g. 200,400,700,900 etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
							'param_name' => 'subtitle_font_weight',
							'value' => __('400','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'subtitle_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						
						array(
							'type' => 'textarea',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Description of info banner using this option.','pt_theplus').'</span></span>'.esc_html__('Description', 'pt_theplus')),
							'param_name' => 'desc',
							'value' => '',
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-1',
									'style-2',
									'style-6'
								)
							)
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Description Setting', 'pt_theplus'),
							'param_name' => 'description_setting',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-1',
									'style-2',
									'style-6',
								)
							)
							
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							'param_name' => 'desc_color',
							'value' => '#ffffff',
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-1',
									'style-2',
									'style-6'
								)
							)
						),
						array(
							'edit_field_class' => 'vc_col-xs-6',
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font size', 'pt_theplus')),
							'param_name' => 'desc_size',
							'value' => __('14px', 'pt_theplus'),
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-1',
									'style-2',
									'style-6'
								)
							)
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							'param_name' => 'desc_lineheight',
							'value' => __('30px', 'pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-1',
									'style-2',
									'style-6',
								)
							)
						),
						
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Background color', 'pt_theplus'),
							'param_name' => 'background_setting',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-1',
									'style-2',
									'style-3',
									'style-6',
								)
							)
						),
						array(
							"type" => "dropdown",
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select background Color using this option.','pt_theplus').'</span></span>'.esc_html__('Backgroud Color Option', 'pt_theplus')),
							"param_name" => "bg_banner",
							"value" => array(
								__('Solid', 'pt_theplus') => 'solid',
								__('Gradient (Premium)', 'pt_theplus') => 'gradient'
							),
							"std" => "solid",
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-1',
									'style-2',
									'style-3',
									'style-6',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Backgroud Color ', 'pt_theplus'),
							'param_name' => 'bg_color',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'solid'
							),
							"edit_field_class" => "vc_col-xs-6",
							"value" => 'rgba(0, 0, 0, 0.33)',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Hover Backgroud Color ', 'pt_theplus'),
							'param_name' => 'background_hover_color',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'solid'
							),
							"edit_field_class" => "vc_col-xs-6",
							"value" => 'rgba(0, 74, 245, 0.32)',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Color 1', 'pt_theplus'),
							'param_name' => 'gradient_color1',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'gradient'
							),
							"edit_field_class" => "vc_col-xs-6",
							"value" => 'rgba(30,115,190,0.34)',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Color 2', 'pt_theplus'),
							'param_name' => 'gradient_color2',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'gradient'
							),
							"edit_field_class" => "vc_col-xs-6",
							"value" => 'rgba(255,12,0,0.37)',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'dropdown',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select one gradient effect style from four beautiful options.','pt_theplus').'</span></span>'.esc_html__('Gradient Style', 'pt_theplus')),
							'param_name' => 'gradient_style',
							'value' => array(
								__('Horizontal', 'pt_theplus') => 'horizontal',
								__('Vertical', 'pt_theplus') => 'vertical',
								__('Diagonal', 'pt_theplus') => 'diagonal',
								__('Radial', 'pt_theplus') => 'radial'
							),
							'std' => 'horizontal',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'gradient'
							),
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Hover Color 1', 'pt_theplus'),
							'param_name' => 'hvr_gradient_color1',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'gradient'
							),
							"edit_field_class" => "vc_col-xs-6",
							"value" => 'rgba(255,12,0,0.37)',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Hover Color 2', 'pt_theplus'),
							'param_name' => 'hvr_gradient_color2',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'gradient'
							),
							"edit_field_class" => "vc_col-xs-6",
							"value" => 'rgba(30,115,190,0.34)',
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
						   'type' => 'dropdown',
						   'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select one gradient effect style from four beautiful options.','pt_theplus').'</span></span>'.esc_html__('Gradient Hover Style', 'pt_theplus')),
							'param_name' => 'gradient_hover_style',
							'value' => array(
								__('Horizontal', 'pt_theplus') => 'horizontal',
								__('Vertical', 'pt_theplus') => 'vertical',
								__('Diagonal', 'pt_theplus') => 'diagonal',
								__('Radial', 'pt_theplus') => 'radial'
							),
							'std' => 'horizontal',
							'dependency' => array(
								'element' => 'bg_banner',
								'value' => 'gradient'
							),
							'group' => esc_attr__('Style', 'pt_theplus')
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Separator Color', 'pt_theplus'),
							'param_name' => 'separator_col',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-6',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for separator using this option.','pt_theplus').'</span></span>'.esc_html__('Separator Color', 'pt_theplus')),
							'param_name' => 'sep_col',
							'value' => '#888888',
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'banner_style',
								'value' => array(
									'style-6',
								)
							)
						),						
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Box Shadow', 'pt_theplus'),
							'param_name' => 'box_sad',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'),
						),	
						array(
						'type' => 'textfield',
						"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Box Shadow ', 'pt_theplus')),
						'param_name' => 'box_sadow',
						"value" => '3px 5px 20px #000',
						'group' => __( 'Style', 'pt_theplus' ),
						"edit_field_class" => "vc_col-xs-6",
					),
					array(
						'type' => 'textfield',
						"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Hover Box Shadow ', 'pt_theplus')),
						'param_name' => 'hov_box_sadow',
						"value" => '0px 14px 20px #060606',
						'group' => __( 'Style', 'pt_theplus' ),
						"edit_field_class" => "vc_col-xs-6",
					),
						
						array(
						'type' => 'pt_theplus_heading_param',
						'text' => esc_html__('Animation Settings (Premium)', 'pt_theplus'),
						'param_name' => 'annimation_effect',
						'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
					),
					
				array(
							'type' => 'vc_link',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('dd Button URL, Link Open Option and Follow-No Follow Option from this option.','pt_theplus').'</span></span>'.esc_html__('Button URL', 'pt_theplus')),
							'param_name' => 'btn_url',
							'description' => '',
						),
						array(
							"type" => "dropdown",
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Choose Animation Effect When This Element will be load on scroll. It have many modern options for you to choose from. ','pt_theplus').'</span></span>'.esc_html__('Choose Animation Effect', 'pt_theplus')),
							"param_name" => "animation_effects",
							"value" => array(
								__('No-animation', 'pt_theplus') => 'no-animation',
								__('FadeIn', 'pt_theplus') => 'transition.fadeIn',
								__('FlipXIn', 'pt_theplus') => 'transition.flipXIn',
								__('FlipYIn', 'pt_theplus') => 'transition.flipYIn',
								__('FlipBounceXIn', 'pt_theplus') => 'transition.flipBounceXIn',
								__('FlipBounceYIn', 'pt_theplus') => 'transition.flipBounceYIn',
								__('SwoopIn', 'pt_theplus') => 'transition.swoopIn',
								__('WhirlIn', 'pt_theplus') => 'transition.whirlIn',
								__('ShrinkIn', 'pt_theplus') => 'transition.shrinkIn',
								__('ExpandIn', 'pt_theplus') => 'transition.expandIn',
								__('BounceIn', 'pt_theplus') => 'transition.bounceIn',
								__('BounceUpIn', 'pt_theplus') => 'transition.bounceUpIn',
								__('BounceDownIn', 'pt_theplus') => 'transition.bounceDownIn',
								__('BounceLeftIn', 'pt_theplus') => 'transition.bounceLeftIn',
								__('BounceRightIn', 'pt_theplus') => 'transition.bounceRightIn',
								__('SlideUpIn', 'pt_theplus') => 'transition.slideUpIn',
								__('SlideDownIn', 'pt_theplus') => 'transition.slideDownIn',
								__('SlideLeftIn', 'pt_theplus') => 'transition.slideLeftIn',
								__('SlideRightIn', 'pt_theplus') => 'transition.slideRightIn',
								__('SlideUpBigIn', 'pt_theplus') => 'transition.slideUpBigIn',
								__('SlideDownBigIn', 'pt_theplus') => 'transition.slideDownBigIn',
								__('SlideLeftBigIn', 'pt_theplus') => 'transition.slideLeftBigIn',
								__('SlideRightBigIn', 'pt_theplus') => 'transition.slideRightBigIn',
								__('PerspectiveUpIn', 'pt_theplus') => 'transition.perspectiveUpIn',
								__('PerspectiveDownIn', 'pt_theplus') => 'transition.perspectiveDownIn',
								__('PerspectiveLeftIn', 'pt_theplus') => 'transition.perspectiveLeftIn',
								__('PerspectiveRightIn', 'pt_theplus') => 'transition.perspectiveRightIn'
							),
							'std' => 'no-animation',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							"type" => "textfield",
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Add value of delay in transition on scroll in millisecond. 1 sec = 1000 Millisecond ','pt_theplus').'</span></span>'.esc_html__('Animation Delay', 'pt_theplus')),
							"param_name" => "animation_delay",
							"value" => '50',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
						'type' => 'pt_theplus_heading_param',
						'text' => esc_html__('Extra Settings', 'pt_theplus'),
						'param_name' => 'extra_effect',
						'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
				  ), 
						array(
							"type" => "textfield",
							 "heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can add Extra Class here to use for Customisation Purpose.','pt_theplus').'</span></span>'.esc_html__('Extra Class', 'pt_theplus')),
							"param_name" => "el_class",
							'edit_field_class' => 'vc_col-sm-6',
						),
						
					)
				));
			}
		}
	}
	new ThePlus_info_banner;

	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_tp_info_banner'))
	{
		class WPBakeryShortCode_tp_info_banner extends WPBakeryShortCode
		{
			protected function contentInline($atts, $content = null)
			{
				
			}
		}
	}
}
