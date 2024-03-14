<?php 
// Advertisement Banner Elements
if(!class_exists("ThePlus_advertisement_banner")){
	class ThePlus_advertisement_banner{
		function __construct(){
			add_action( 'init', array($this, 'init_tp_advertisement_banner') );
			add_shortcode( 'tp_advertisement_banner',array($this,'tp_advertisement_banner_shortcode'));
			add_action( 'wp_enqueue_scripts', array( $this, 'tp_advertisement_banner_scripts' ), 1 );
		}
		function tp_advertisement_banner_scripts() {
			wp_register_style( 'theplus-addbanner-style', THEPLUS_PLUGIN_URL . 'vc_elements/css/main/theplus-addbanner-style.css', false, '1.0.0' );
		}
		function tp_advertisement_banner_shortcode($atts,$content = null){
			extract( shortcode_atts( array(
				'add_style' => 'style-1',
				'banner_img' => '',
				'hover_color' => 'rgba(0,0,0,.7)',
				'subtitle' => 'This Is Subtitle',
				'title' => 'This Is Title',
				'title_size' => '40px',
				'title_lineheight' => '45px',
				'subtitle_size' => '14px',
				'subtitle_lineheight' => '30px',
				'subtitle_use_theme_fonts'=>'custom-font-family',
				'subtitle_font_family'=>'',
				'subtitle_font_weight'=>'400',
				
				'title_color' => '#252525',
				'title_hover_color' =>'#ff214f',
				'use_theme_fonts'=>'custom-font-family',
				'title_font_family'=>'',
				'title_font_weight'=>'600',
				
				'subtitle_color' =>'#ff214f',
				'subtitle_hover_color'=> '#252525',
				'bg_banner'=>'solid',
				
				'hov_styles' => 'addbanner-image-blur',
					"style" => 'style_2',
				'btn_hover_style'=>'hover-left',
				'icon_hover_style'=>'hover-top',
				'btn_padding'=>'15px 30px',
				"btn_text" => 'The Plus',
				'btn_hover_text'=>'',
				"btn_icon" => 'fontawesome',
			  'icon_fontawesome'=>'fa fa-arrow-right',
			   'icon_openiconic'=> 'vc-oi vc-oi-dial',
				 'icon_typicons'=> 'typcn typcn-adjust-brightness',
			   'icon_entypo'=> 'entypo-icon entypo-icon-note',    
				 'icon_linecons'=> 'vc_li vc_li-heart',
				 'icon_monosocial'=> 'vc-mono vc-mono-fivehundredpx',
				"before_after" => 'after',
				"btn_url" => '',
				'btn_align' =>'text-left',
				'select_bg_option'=>'normal',
				'normal_bg_color'=>'#252525',
				'gradient_color1'=>'#1e73be',
				'gradient_color2'=>'#2fcbce',
				'gradient_style'=>'horizontal',
				'bg_image'=>'',
				//'gradient_opacity'=>'1',
				'select_bg_hover_option'=>'normal',
				
				'normal_bg_hover_color'=>'#ff214f',
				'normal_bg_hover_color1'=>'#d3d3d3',
				'gradient_hover_color1'=>'#2fcbce',
				'gradient_hover_color2'=>'#1e73be',
				'gradient_hover_style'=>'horizontal',
				'bg_hover_image'=>'',
				
				'box_shadow' =>'7px 11px 20px 3px #676565',
				'hov_box_sadow' => '0 22px 43px rgba(0, 0, 0, 0.15)',
				
			
				'font_size'=>'20px',
				'line_height'=>'25px',
				'text_color'=>'#8a8a8a',
				'text_hover_color'=>'#252525',
				'border_color'=>'#252525',
				'border_hover_color'=>'#252525',
				'border_radius'=>'30px',
				
				'full_width_btn'=>'',
				'transition_hover'=>'',

				'animation_effects'=>'no-animation',
				'animation_delay'=>'50',
				'el_class' =>'',
				
					'show_btn' =>'on'
			), $atts ) );
			wp_enqueue_style( 'theplus-addbanner-style');
			
				$banner_subtitle=$banner_title=$text_alignment=$content_alignment=$parralex_attr=$hover_clss='';

				if($hov_styles == "addbanner-image-blur") {
					$hover_clss = 'addbanner-image-blur';
				  }else if($hov_styles == "addbanner-image-vertical"){
					$hover_clss = 'addbanner-image-vertical';
				  }
			/*-------------------------------------------------------------------*/
			if($hover_color) {
				$bg_color_hover = $hover_color;
			}
			/*-------------------------------------------------------------------*/
			/*--------------------------Sub Title---------------------------- */
			if($subtitle_use_theme_fonts=='custom-font-family'){
				$subtitle_style='font-family:'.$subtitle_font_family.';font-weight:'.$subtitle_font_weight.';';
			}else{
				$subtitle_style='';
			}
			if($subtitle!= "") {
				$addsubtitle_css =' style="';
				  
				if($subtitle_size!= "") {
				  $addsubtitle_css .= 'font-size: '.esc_attr($subtitle_size).';';
				  }
				if($subtitle_lineheight!= "") {
				  $addsubtitle_css .= 'line-height: '.esc_attr($subtitle_lineheight).';';
				  }
				
				 if($subtitle_style!= "") {
				  $addsubtitle_css .=$subtitle_style;
				  }
			$addsubtitle_css .= '"';
			 $banner_subtitle = '<h4 class="addbanner_subtitle"'.$addsubtitle_css.'>'.esc_html($subtitle).'</h4>';
			}
			/*--------------------------Title---------------------------- */
			if($use_theme_fonts=='custom-font-family'){
				$title_style='font-family:'.$title_font_family.';font-weight:'.$title_font_weight.';';
			}else{
				$title_style='';
			}

				
			if($title!=""){
				$addtitle_css = ' style="';
				if($title_size!= "") {
				  $addtitle_css .= 'font-size: '.esc_attr($title_size).';';
				  }
				if($title_lineheight!= "") {
				  $addtitle_css .= 'line-height: '.esc_attr($title_lineheight).';';
				  }
								
				  if($title_style!=''){
					$addtitle_css .=$title_style;
				}
			$addtitle_css .= '"';
			 $banner_title = '<h3 class="addbanner_title"'.$addtitle_css.'>'.esc_html($title).'</h3>';
			}
			/*-------------------------------------------------------------------*/
			if($add_style== "style-1"){
				$text_alignment .= 'text-left';

			}
			if($add_style== "style-5"){
				$text_alignment .= 'text-right';
			}
			
			/*-------------------------------------------------------------------*/
			if($add_style== "style-1"){
				$content_alignment .= 'top-left';
			}
			
			if($add_style== "style-5"){
				$content_alignment .= 'center-right';
			}
			
				$rand_no=rand(1000000, 1500000);
				$data_class=$data_attr=$a_href=$a_title=$a_target=$a_rel=$style_content=$icons_before=$icons_after=$button_text=$button_hover_text=$gradient_color=$gradient_hover_color='';
				
				$data_class=' button-'.esc_attr($rand_no).' ';
				$data_class .=' button-'.esc_attr($style).' ';
				
				if($full_width_btn=='yes'){
					$data_class .=' full-button ';
				}
				if($transition_hover=='yes'){
					$data_class .=' trnasition_hover ';
				}
				
				
				if($select_bg_option=='normal'){
					$bg_color = $normal_bg_color;
				}else{
					$bg_color = '';
				}
			
				if($select_bg_hover_option=='normal'){
					$bg_hover_color = $normal_bg_hover_color;
				}else{
						$bg_hover_color='';
				}
				$btn_url = ( '||' === $btn_url ) ? '' : $btn_url;
					$btn_url_a= vc_build_link( $btn_url);
					
					$a_href = $btn_url_a['url'];
					$a_title = $btn_url_a['title'];
					$a_target = $btn_url_a['target'];
					$a_rel = $btn_url_a['rel'];
					if ( ! empty( $a_rel ) ) {
						$a_rel = ' rel="' . esc_attr( trim( $a_rel ) ) . '"';
					}
					
				if(!empty($btn_icon)){
			  vc_icon_element_fonts_enqueue( $btn_icon );
			  $icon_class = isset( ${'icon_' . $btn_icon} ) ? esc_attr( ${'icon_' . $btn_icon} ) : 'fa fa-arrow-right';
			  
			  if($before_after=='before'){
			   $icons_before = '<i class="btn-icon button-'.esc_attr($before_after).' '.esc_attr($icon_class).'"></i>';
			  }else{
			   $icons_after = '<i class="btn-icon button-'.esc_attr($before_after).' '.esc_attr($icon_class).'"></i>';
			  }
			 }
				
				if($style=='style-2' || $style=='style-8' || $style=='style-10'){
					$button_text =$icons_before . $btn_text . $icons_after;
				}
				
				if($style=='style-9'){
					$button_text =$btn_text.'<span class="btn-arrow"><i class="fa-show fa fa-chevron-right" aria-hidden="true"></i><i class="fa-hide fa fa-chevron-right" aria-hidden="true"></i></span>';
				}
				if($style=='style-11'){
					$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
					if(!empty($btn_hover_text)){
						$button_hover_text =' data-hover="'.$btn_hover_text.'" ';
					}else{
						$button_hover_text =' data-hover="'.$btn_text.'" ';
					}
					$data_class .=' '.$btn_hover_style.' ';
				}
				if($style=='style-12' || $style=='style-16'){
					$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
				}
				if($style=='style-17'){
					$icons_before=$icons_after;
					$button_text =$icons_before .'<span>'. $btn_text .'</span>';
					$data_class .=' '.$icon_hover_style.' ';
				}
				
				
				$the_button ='<div class="'.$btn_align.' ts-button">';
					$the_button .='<div class="pt_plus_button '.$data_class.' " '.$data_attr.' >';
						$the_button .='<a class="button-link-wrap" href="'.esc_url( $a_href ).'" title="'.esc_attr( $a_title ).'" target="'.esc_attr( $a_target ).'" '.$a_rel.' '.$button_hover_text.'>';
							$the_button .=$button_text;
							$the_button .=$style_content;
						$the_button .='</a>';
					$the_button .='</div>';
				$the_button .='</div>';	

								
				 $add_image_1 = wp_get_attachment_image_src( $banner_img,true);
			$add_img_url = $add_image_1[0];
				$add_image = '';
					if ( $banner_img != '' ) {
							$full_image=wp_get_attachment_image_src( $banner_img, 'full' );
							
								$add_image .='<img class="info_img " src="'.esc_url($full_image[0]).'"  alt="'.esc_attr(get_the_title()).'">';
							}else{ 
								$add_image .= pt_plus_loading_image_grid(get_the_ID());
					}
							

				$uid=uniqid('add-banner');
					
			$add_banner = '<div class="pt_plus_addbanner_wrap ">';
			$add_banner .='<div class="pt_plus_addbanner add-banner-'.$add_style.' '.$hover_clss.' addbanner-fade-out image-loaded box_saddow_addbanner '.esc_attr($uid).' '.esc_attr($el_class).' " > ';

			
				$add_banner .='<div class="addbanner-block" >';  
					$add_banner .='<div class="addbanner_inner '.esc_attr($text_alignment).'">'; 
						$add_banner .='<div class="'.esc_attr($content_alignment).'">';
							$add_banner .='<div class="content-level2">';
								$add_banner .='<div class="content-level3">';
									$add_banner .=$banner_subtitle;
							            $add_banner .=$banner_title;
										if($show_btn == 'on'){
											$add_banner .=$the_button;
										};
						$add_banner .='</div>';
							$add_banner .='</div>';
						$add_banner .='</div>';
						$add_banner .='<div class="addbanner_inner_img ">';
							$add_banner .=$add_image; 
						$add_banner .='</div>';
					$add_banner .='<div class="entry-thumb">'; 
						$add_banner .='<div class="entry-hover">';
						$add_banner .='</div>';
					$add_banner .='</div>';      
				 $add_banner .='</div>';
				$add_banner .='</div>';

			$add_banner .='</div>';
			$add_banner .='</div>'; 
			
			
			$css_rule='';
				$css_rule='<style >';
				
				$css_rule .='.'.esc_js($uid).'.pt_plus_addbanner .entry-thumb .entry-hover:before{background: '.esc_js($bg_color_hover).';}';
				$css_rule .='.'.esc_js($uid).'.pt_plus_addbanner .addbanner_title{color:'.esc_js($title_color).';}';
				$css_rule .='.'.esc_js($uid).'.pt_plus_addbanner:hover .addbanner_title{color:'.esc_js($title_hover_color).';}';
				$css_rule .='.'.esc_js($uid).'.pt_plus_addbanner .addbanner_subtitle{color:'.esc_js($subtitle_color).';}';
				$css_rule .='.'.esc_js($uid).'.pt_plus_addbanner:hover .addbanner_subtitle{color:'.esc_js($subtitle_hover_color).';}';
				if($show_btn == 'on'){
					$css_rule .= include THEPLUS_PLUGIN_PATH.'vc_elements/vc_param/button_css.php';
				}
				$css_rule .='</style>';
			return $css_rule.$add_banner;
		}
		
		function init_tp_advertisement_banner(){
			if(function_exists("vc_map"))
			{
				vc_map(array(
					"name" => esc_html__('Advertisement Banner', 'pt_theplus'),
					"base" => "tp_advertisement_banner",
					"icon" => "tp-advertise-banner",
					"category" => esc_html__('The Plus', 'pt_theplus'),
					'description' => esc_html__('Sell with Style', 'pt_theplus'),
					"params" => array(

						array(
							"type" => "dropdown",
							'heading' =>  esc_html__('Styles', 'pt_theplus'),
							"param_name" => "add_style",
							"value" => array(
								esc_html__("Style 1", 'pt_theplus') => "style-1",
								esc_html__("Style 5", 'pt_theplus') => "style-5",
						),
							'admin_label' =>true,
							"description" => '',
							"std" =>'style-1',            
						),
						array(
							'type' => 'attach_image',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Upload image of Advertisement banner using this option. .jpg, .png, .gif images supported.','pt_theplus').'</span></span>'.esc_html__('Advertisement Banner Image', 'pt_theplus')),
							'param_name' => 'banner_img',
							'value' => '',
							'edit_field_class' => 'vc_col-xs-12',
							'description' => '',	
						),

						array(
							"type"        => "dropdown",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Hover Color using this option.','pt_theplus').'</span></span>'.esc_html__('Hover Color Option', 'pt_theplus')),
							"param_name"  => "bg_banner",
							"admin_label" => false,
							"value"       => array(
								esc_html__( 'Solid', 'pt_theplus' ) => 'solid',
								esc_html__( 'Gradient (Premium)', 'pt_theplus' ) => 'gradient',
							),
							"std" => "solid",
							"description" => '',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array(
											'element' => 'add_style',
											'value' => array('style-1','style-5'),
									),
						),
						array(  
							'type' => 'colorpicker',
							'heading' => esc_html__( 'Hover Color', 'pt_theplus' ),
							'param_name' => 'hover_color',	
							'value' =>'rgba(0,0,0,.7)',		
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'),
							'dependency' => array('element' => 'bg_banner','value' => 'solid'),
						),
						
						array(
							'type' => 'textfield',
							'heading' =>  esc_html__('Advertisement Banner Title', 'pt_theplus'),
							'param_name' => 'title',
							'admin_label' => true,
							'value' => esc_html__('This Is Title','pt_theplus'),
							'description' => '',								
						),
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Title Setting', 'pt_theplus'),
							'param_name'		=> 'title_setting',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'), 
						),	
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							'param_name' => 'title_color',	
							'value' =>'#252525',		
							'description' => '',
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'),
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for hover font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Hover Color', 'pt_theplus')),
							'param_name' => 'title_hover_color',	
							'value' =>'#ff214f',
							'description' => '',
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'),
						),		
						array(
							'edit_field_class' => 'vc_col-xs-6',
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
							'param_name' => 'title_size',
							'value' => __('40px','pt_theplus'),
							'description' => "",
							'group' => esc_attr__('Style', 'pt_theplus'),	
						),	
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							'param_name' => 'title_lineheight',
							'value' => __('45px','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'),	
						),
						
						array(
								'type' => 'dropdown',
								'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Title Custom font family', 'pt_theplus'),
								'param_name' => 'use_theme_fonts',
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
									'element' => 'use_theme_fonts',
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
									'element' => 'use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can add sub title of advertisement banner using this option','pt_theplus').'</span></span>'.esc_html__('Advertisement Banner Sub Title', 'pt_theplus')),
							'param_name' => 'subtitle',
							'value' => esc_html__('This Is Subtitle','pt_theplus'),
							'description' => '',
						),	
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Sub Title Setting', 'pt_theplus'),
							'param_name'		=> 'subtitle_setting',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'),
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							'param_name' => 'subtitle_color',	
							'value' =>'#ff214f',		
							'description' => '',
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'), 
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for hover font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Hover Color', 'pt_theplus')),
							'param_name' => 'subtitle_hover_color',	
							'value' =>'#252525',
							'description' => '',
							'edit_field_class' => 'vc_col-xs-6',
							'group' => esc_attr__('Style', 'pt_theplus'), 
						),		
						array(
							'edit_field_class' => 'vc_col-xs-6',
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
							'param_name' => 'subtitle_size',
							'value' => __('14px','pt_theplus'),
							'description' => "",
							'group' => esc_attr__('Style', 'pt_theplus'), 									
						),		
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							'param_name' => 'subtitle_lineheight',
							'value' => __('30px','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'), 									
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
							"type" => "dropdown",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Hover Styles using this option.','pt_theplus').'</span></span>'.esc_html__('Hover Styles', 'pt_theplus')),
							"param_name" => "hov_styles",
							"value" => array(
								esc_html__("Blur Effect", 'pt_theplus') => "addbanner-image-blur",
								esc_html__("Simple", 'pt_theplus') => "simple",
								esc_html__("Vertical", 'pt_theplus') => "addbanner-image-vertical",
								
						),
							"description" => '',
							"std" =>'addbanner-image-blur',
							'dependency' => array(
											'element' => 'add_style',
											'value' => array('style-1','style-5'),
									),
						),
							
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Box Shadow Setting (Premium)', 'pt_theplus'),
							'param_name'		=> 'boxshadow_setting',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'), 
						),	
						array(
						'type' => 'textfield',
						"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Box Shadow ', 'pt_theplus')),
						'param_name' => 'box_shadow',
						"value" => '7px 11px 20px 3px #676565',
						'description' => '',	
						'group' => esc_attr__('Style', 'pt_theplus'),
						"edit_field_class" => "vc_col-xs-6",
						),
						array(
							'type' => 'textfield',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Hover Box Shadow ', 'pt_theplus')),
							'param_name' => 'hov_box_sadow',
							"value" => '0 22px 43px rgba(0, 0, 0, 0.15)',
							'description' => '',	
							'group' => esc_attr__('Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-6",
						),
						array(
							'type' => 'pt_theplus_checkbox',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can display or hide Button using this option.','pt_theplus').'</span></span>'.esc_html__('Button', 'pt_theplus')),
							'param_name' => 'show_btn',
							'description' => '',
							'value' => 'on',
							'options' => array(
								'on' => array(
										'label' => '',
										'on' => 'Yes',
										'off' => 'No',
									),
								),
								"edit_field_class" => "vc_col-xs-4",
							'group' => esc_attr__('Button', 'pt_theplus'), 
							
						),
		
						array(
							'type'        => 'radio_select_image',
							'heading' =>  __(esc_html__('Button Style', 'pt_theplus')), 
							'param_name'  => 'style',
							'admin_label' => false,
							'simple_mode' => false,
							'value'  => 'style_2',
							'options'     => array(
							
							 'style-2' => array(
							  'tooltip' => esc_attr__('Style 2','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-2.png'
							 ),
							
							 'style-8' => array(
							  'tooltip' => esc_attr__('Style 8','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-8.png'
							 ),
							 'style-9' => array(
							  'tooltip' => esc_attr__('Style 9','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-9.png'
							 ),
							 'style-10' => array(
							  'tooltip' => esc_attr__('Style 10','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-10.png'
							 ),
							 'style-11' => array(
							  'tooltip' => esc_attr__('Style 11','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-11.png'
							 ),
							 'style-12' => array(
							  'tooltip' => esc_attr__('Style 12','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-12.png'
							 ),
							
							 'style-16' => array(
							  'tooltip' => esc_attr__('Style 16','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-16.png'
							 ),
							 'style-17' => array(
							  'tooltip' => esc_attr__('Style 17','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-17.png'
							 ),
							),
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							'group' => esc_attr__('Button', 'pt_theplus'),
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Hover Style", 'pt_theplus'),
							"param_name" => "btn_hover_style",
							"value" => array(
								esc_html__("On Left", 'pt_theplus') => "hover-left",
								esc_html__("On Right", 'pt_theplus') => "hover-right",
								esc_html__("On Top", 'pt_theplus') => "hover-top",
								esc_html__("On Bottom", 'pt_theplus') => "hover-bottom"
							),
							'group' => esc_attr__('Button', 'pt_theplus'),
							"description" => '',
							"std" => 'hover-left',
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-11',
								)
							)
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Hover Style", 'pt_theplus'),
							"param_name" => "icon_hover_style",
							"value" => array(
								esc_html__("On Top", 'pt_theplus') => "hover-top",
								esc_html__("On Bottom", 'pt_theplus') => "hover-bottom"
							),
							'group' => esc_attr__('Button', 'pt_theplus'),
							"description" => '',
							"std" => 'hover-top',
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-17'
								)
							)
						),
						
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can write title of button from here.','pt_theplus').'</span></span>'.esc_html__('Button Text', 'pt_theplus')), 
							"param_name" => "btn_text",
							'group' => esc_attr__('Button', 'pt_theplus'),
							"value" => 'The Plus',
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							'description' => '',
						),
						
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can write on hover  title of button from here.','pt_theplus').'</span></span>'.esc_html__('Button Hover Text', 'pt_theplus')),
							"param_name" => "btn_hover_text",
							"value" => '',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'description' => '',
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-11',
								)
							)
						),
						array(
							"type" => "textfield",
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Setup Inner Padding top-bottom and right-left to Button from this option. E.g. 15px 20px, 30px 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Inner Padding ', 'pt_theplus')),
							"param_name" => "btn_padding",
							"value" => '15px 30px',
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							'description' => '',
						),
						array(
							'type' => 'dropdown',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('We have given options of icons from Font Awesome, Open Iconic, Typicons, Entypo, and Mono Social.','pt_theplus').'</span></span>'.esc_html__('Icon Library', 'pt_theplus')),
							'value' => array(
								esc_html__('Select Icon', 'pt_theplus') => '',
								esc_html__('Font Awesome', 'pt_theplus') => 'fontawesome',
								esc_html__('Open Iconic', 'pt_theplus') => 'openiconic',
								esc_html__('Typicons', 'pt_theplus') => 'typicons',
								esc_html__('Entypo', 'pt_theplus') => 'entypo',
								esc_html__('Mono Social', 'pt_theplus') => 'monosocial'
							),
							'admin_label' => false,
							'std' => 'fontawesome',
							'param_name' => 'btn_icon',
							'description' => '',
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-2',
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							'type' => 'iconpicker',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_fontawesome',
							'value' => 'fa fa-arrow-right', // default value to backend editor admin_label
							'settings' => array(
								'emptyIcon' => false,
								'iconsPerPage' => 4000
							),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'fontawesome'
							),
							
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_openiconic',
							'value' => 'vc-oi vc-oi-dial',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'openiconic',
								'iconsPerPage' => 4000
							),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'openiconic'
							),
							
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_typicons',
							'value' => 'typcn typcn-adjust-brightness',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'typicons',
								'iconsPerPage' => 4000
							),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'typicons'
							),
							
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_entypo',
							'value' => 'entypo-icon entypo-icon-note',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'entypo',
								'iconsPerPage' => 4000
							),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'entypo'
							)
							
						),
						array(
							'type' => 'iconpicker',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_linecons',
							'value' => 'vc_li vc_li-heart',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'linecons',
								'iconsPerPage' => 4000
							),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'linecons'
							),
							
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_monosocial',
							'value' => 'vc-mono vc-mono-fivehundredpx',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'monosocial',
								'iconsPerPage' => 4000
							),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'monosocial'
							),
							'description' => '',
						),
						array(
							"type" => "dropdown",
							'group' => esc_attr__('Button', 'pt_theplus'),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Select Position of Icon before or after content from this option.','pt_theplus').'</span></span>'.esc_html__('Icon Position', 'pt_theplus')),
							"param_name" => "before_after",
							"value" => array(
								__("After", 'pt_theplus') => "after",
								__("Before", 'pt_theplus') => "before"
							),
							"description" => '',
							"std" => 'after',
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-2',
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
								)
							)
						),
						array(
							'type' => 'vc_link',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('dd Button URL, Link Open Option and Follow-No Follow Option from this option.','pt_theplus').'</span></span>'.esc_html__('Button URL', 'pt_theplus')),
							'param_name' => 'btn_url',
							'group' => esc_attr__('Button', 'pt_theplus'),
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							'description' => '',
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Button Text Style', 'pt_theplus'),
							'param_name' => 'text_style',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							"group" => esc_attr__('Button Style', 'pt_theplus')
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')), 
							"param_name" => "font_size",
							"value" => '20px',
							'description' => '',
							"edit_field_class" => "vc_col-xs-6",
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							"group" => esc_attr__('Button Style', 'pt_theplus')
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							"param_name" => "line_height",
							"value" => '25px',
							'description' => '',
							"edit_field_class" => "vc_col-xs-6",
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							"group" => esc_attr__('Button Style', 'pt_theplus')
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Font Weight using this Option. E.g. 400, 700, etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
							"param_name" => "font_weight",
							"value" => '400',
							'description' => '',
							"edit_field_class" => "vc_col-xs-6",
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							"group" => esc_attr__('Button Style', 'pt_theplus')
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button text color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Color', 'pt_theplus')),
							'param_name' => 'text_color',
							"value" => '#8a8a8a',
							"description" => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							"edit_field_class" => "vc_col-xs-6"
							
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button hover text color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Hover Color', 'pt_theplus')),
							'param_name' => 'text_hover_color',
							"value" => '#252525',
							"description" => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							"edit_field_class" => "vc_col-xs-6"
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Border Style', 'pt_theplus'),
							'param_name' => 'border_style',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							"group" => esc_attr__('Button Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button border color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Border Color', 'pt_theplus')),
							'param_name' => 'border_color',
							"value" => '#252525',
							"description" => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-4",
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button border hover color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Border Hover Color', 'pt_theplus')),
							'param_name' => 'border_hover_color',
							"value" => '#252525',
							"description" => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-4",
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can choose radius for border using this option. E.g. 1px, 2px, etc.','pt_theplus').'</span></span>'.esc_html__('Border Radius', 'pt_theplus')),
							"param_name" => "border_radius",
							"value" => "30px",
							"description" => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-4",
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-10',
									'style-11',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Background Style', 'pt_theplus'),
							'param_name' => 'background_style_heading',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							"group" => esc_attr__('Button Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							"type" => "dropdown",
							"heading" => __("Select Background Option", 'pt_theplus'),
							"param_name" => "select_bg_option",
							"value" => array(
								esc_html__("Normal color", 'pt_theplus') => "normal",
								esc_html__("Gradient color (Premium)", 'pt_theplus') => "gradient",
								esc_html__("Background Image (Premium)", 'pt_theplus') => "image"
							),
							"description" => '',
							"std" => 'normal',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' => esc_html__('color', 'pt_theplus'),
							'param_name' => 'normal_bg_color',
							"description" => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"value" => '#252525',
							'dependency' => array(
								'element' => 'select_bg_option',
								'value' => 'normal'
							)
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Background Hover Style', 'pt_theplus'),
							'param_name' => 'background_style_hover_heading',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							"group" => esc_attr__('Button Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Background Option", 'pt_theplus'),
							"param_name" => "select_bg_hover_option",
							"value" => array(
								esc_html__("Normal color", 'pt_theplus') => "normal",
								esc_html__("Gradient color (Premium)", 'pt_theplus') => "gradient",
								esc_html__("Background Image (Premium)", 'pt_theplus') => "image"
							),
							"description" => '',
							"std" => 'normal',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' => esc_html__('Background color', 'pt_theplus'),
							'param_name' => 'normal_bg_hover_color',
							"description" => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"value" => '#ff214f',
							'dependency' => array(
								'element' => 'select_bg_hover_option',
								'value' => 'normal'
							)
						),
						
						
						array(
							'type' => 'checkbox',
							'heading' => esc_html__('Full Width Button', 'pt_theplus'),
							'param_name' => 'full_width_btn',
							'value' => array(
								__('Yes', 'pt_theplus') => 'yes'
							),
							"group" => esc_attr__('Button', 'pt_theplus'),
							'description' => '',
							'std' => '',
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-12',
									'style-16',
									'style-17',
								)
							)
						),
						array(
							'type' => 'dropdown',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Choose button alignment from Right, Left or Center.','pt_theplus').'</span></span>'.esc_html__('Alignment', 'pt_theplus')), 
							'param_name' => 'btn_align',
							"group" => esc_attr__('Button', 'pt_theplus'),
							'value' => array(
								esc_html__('Left', 'pt_theplus') => 'text-left',
								esc_html__('Center', 'pt_theplus') => 'text-center',
								esc_html__('Right', 'pt_theplus') => 'text-right'
							),
							'std' => 'text-left',
							"dependency" => array(
									"element" => "show_btn",
									"value" => array("on"),
							), 
							"description" => '',
						),
						  array(
						'type' => 'pt_theplus_heading_param',
						'text' => esc_html__('Animation Settings', 'pt_theplus'),
						'param_name' => 'annimation_effect',
						'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
					),
						 
						
						array(
							  "type"        => "dropdown",
							  "heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Choose Animation Effect When This Element will be load on scroll. It have many modern options for you to choose from. ','pt_theplus').'</span></span>'.esc_html__('Choose Animation Effect', 'pt_theplus')),
							  "param_name"  => "animation_effects",
							  "admin_label" => false,
							  "value"       => array(
								esc_html__( 'No-animation', 'pt_theplus' )             => 'no-animation',
								esc_html__( 'FadeIn', 'pt_theplus' )             => 'transition.fadeIn',
								esc_html__( 'FlipXIn', 'pt_theplus' )            => 'transition.flipXIn',
							   esc_html__( 'FlipYIn', 'pt_theplus' )            => 'transition.flipYIn',
							   esc_html__( 'FlipBounceXIn', 'pt_theplus' )      => 'transition.flipBounceXIn',
							   esc_html__( 'FlipBounceYIn', 'pt_theplus' )      => 'transition.flipBounceYIn',
							   esc_html__( 'SwoopIn', 'pt_theplus' )            => 'transition.swoopIn',
							   esc_html__( 'WhirlIn', 'pt_theplus' )            => 'transition.whirlIn',
							   esc_html__( 'ShrinkIn', 'pt_theplus' )           => 'transition.shrinkIn',
							   esc_html__( 'ExpandIn', 'pt_theplus' )           => 'transition.expandIn',
							   esc_html__( 'BounceIn', 'pt_theplus' )           => 'transition.bounceIn',
							   esc_html__( 'BounceUpIn', 'pt_theplus' )         => 'transition.bounceUpIn',
							   esc_html__( 'BounceDownIn', 'pt_theplus' )       => 'transition.bounceDownIn',
							   esc_html__( 'BounceLeftIn', 'pt_theplus' )       => 'transition.bounceLeftIn',
							   esc_html__( 'BounceRightIn', 'pt_theplus' )      => 'transition.bounceRightIn',
							   esc_html__( 'SlideUpIn', 'pt_theplus' )          => 'transition.slideUpIn',
							   esc_html__( 'SlideDownIn', 'pt_theplus' )        => 'transition.slideDownIn',
							   esc_html__( 'SlideLeftIn', 'pt_theplus' )        => 'transition.slideLeftIn',
							   esc_html__( 'SlideRightIn', 'pt_theplus' )       => 'transition.slideRightIn',
							   esc_html__( 'SlideUpBigIn', 'pt_theplus' )       => 'transition.slideUpBigIn',
							   esc_html__( 'SlideDownBigIn', 'pt_theplus' )     => 'transition.slideDownBigIn',
							   esc_html__( 'SlideLeftBigIn', 'pt_theplus' )     => 'transition.slideLeftBigIn',
							   esc_html__( 'SlideRightBigIn', 'pt_theplus' )    => 'transition.slideRightBigIn',
							   esc_html__( 'PerspectiveUpIn', 'pt_theplus' )    => 'transition.perspectiveUpIn',
							   esc_html__( 'PerspectiveDownIn', 'pt_theplus' )  => 'transition.perspectiveDownIn',
							   esc_html__( 'PerspectiveLeftIn', 'pt_theplus' )  => 'transition.perspectiveLeftIn',
							   esc_html__( 'PerspectiveRightIn', 'pt_theplus' ) => 'transition.perspectiveRightIn',
							  ),
							  'edit_field_class' => 'vc_col-sm-6',
							  'std' =>'no-animation',
							),		
							array(
								  "type"        => "textfield",
								  "heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Add value of delay in transition on scroll in millisecond. 1 sec = 1000 Millisecond ','pt_theplus').'</span></span>'.esc_html__('Animation Delay', 'pt_theplus')),
								  "param_name"  => "animation_delay",
								  "value"       => '50',
								  'edit_field_class' => 'vc_col-sm-6',
								  "description" => "", 
								  ),
							array(
								'type' => 'pt_theplus_heading_param',
								'text' => esc_html__('Extra Settings', 'pt_theplus'),
								'param_name' => 'extra_effect',
								'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
								),	
							array(
								  "type"        => "textfield",
								  "heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can add Extra Class here to use for Customisation Purpose.','pt_theplus').'</span></span>'.esc_html__('Extra Class', 'pt_theplus')),
								  'edit_field_class' => 'vc_col-sm-6',
								  "param_name"  => "el_class",
								  "description" => "",
								  ),
						
						),
					)
				);
			}
		}
	}
	new ThePlus_advertisement_banner;

	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_tp_advertisement_banner'))
	{
		class WPBakeryShortCode_tp_advertisement_banner extends WPBakeryShortCode
		{
			protected function contentInline($atts, $content = null)
			{
				
			}
		}
	}
}