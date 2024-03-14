<?php
//Info Box Elements
if(!class_exists("ThePlus_info_box")){
	class ThePlus_info_box{
		function __construct(){
			add_action( 'init', array($this, 'init_tp_info_box') );
			add_shortcode( 'tp_info_box',array($this,'tp_info_box_shortcode'));
			add_action( 'wp_enqueue_scripts', array( $this, 'tp_info_box_scripts' ), 1 );
		}
		function tp_info_box_scripts() {
			wp_register_style( 'theplus-info-box-style', THEPLUS_PLUGIN_URL . 'vc_elements/css/main/theplus-info-box-style.css', false, '1.0.0' );
		}
		function tp_info_box_shortcode($atts,$content = null){
			extract( shortcode_atts( array(
					  'info_box_layout' =>'single_layout',
					  'main_style' =>'style_1',
					  'title' => 'The Plus',
					  'title_color_o' =>'solid',
					  'title_color1' =>'#1e73be',
					  'title_color2' =>'#2fcbce',
					  'title_hover_style' =>'horizontal',
					  'title_color' =>'#ffffff',
					  'title_size' => '24px',
					  'title_line' =>'1.4',
					  'title_use_theme_fonts'=>'custom-font-family',
						'title_font_family'=>'',
						'title_font_weight'=>'600',
					  'title_btm_space' =>'',
					  'title_top_space' =>'',
					  'sub_title' => 'Creative Design',
					  'sub_title_color' => '#4d4d4d',
					  'sub_title_size' => '20px',
					  'sub_title_line' =>'1.4',
					  'subtitle_use_theme_fonts'=>'custom-font-family',
					'subtitle_font_family'=>'',
					'subtitle_font_weight'=>'400',
					  'sub_btm_space' =>'',					  

					  'image_icon' => 'icon',
					  'type'=> 'fontawesome',
					  'icon_fontawesome'=> 'fa fa-adjust',
					  'icon_openiconic'=> 'vc-oi vc-oi-dial',
					  'icon_typicons'=> 'typcn typcn-adjust-brightness',
					  'icon_entypo'=> 'entypo-icon entypo-icon-note',		  
					  'icon_linecons'=> 'vc_li vc_li-heart',
					  'icon_monosocial'=> 'vc-mono vc-mono-fivehundredpx',
					  'icon_size' =>'small',
					  'icon_color' => '#0099CB',
					  'icon_hvr_color' =>'#ffffff',
					  'icon_bg_color' =>'#ffffff',
					  'icon_bg_hvr_color' =>'#0099CB',
					  'icon_border_color' =>'#121212',
					  'icon_hvr_bdr_color' =>'#ffffff',
					  'desc_color' =>'#888888',
					  'desc_size' =>'14px',
					  'desc_use_theme_fonts'=>'custom-font-family',
					  'desc_family'=>'',
					  'desc_font_weight'=>'400',
					
					  'desc_line' =>'30px',
					  'button_check' => '',
					  'btn_bg' =>'#121212',
					  'btn_size' => '16px',
					  'btn_color' =>'#fff',
					  'btn_border_color' => '#fff',
					  'vertical_center' =>'',
					  'text_align' =>'center',
					  

					  'flip_height' => '300px',
					  'flip_style' =>'horizontal',
					  'front_color' =>'#121212',
					  'front_img' =>'',
					  'back_img' =>'',
					  'back_color' =>'#5aa1e3',
					  'box_bg_color' => '#ff004b',
					  'box_hover_color' =>'#0099cc',
					  'animation_effects'=>'no-animation',
					  'animation_delay'=>'50',
					  'box_shadow' => '1px 1px 3px 3px rgba(0, 0, 0, 0.15)',
					  'hvr_box_shadow' =>'0 22px 43px rgba(0, 0, 0, 0.15)',
					  'head_bg_color' =>'#ffffff',
					  'padding_top' =>'',
					  'padding_boottom' =>'',
					  'remove_padding' =>'',
					  'remove_cl_padding' =>'',
					  'el_class' =>'',
					  
					'alignment'=>'text-center',
					
					 "style" => 'style-1',
					'btn_hover_style'=>'hover-left',
					'btn_padding'=>'15px 30px',
					"btn_text" => 'The Plus',
					'btn_hover_text'=>'',
					"btn_icon" => 'fontawesome',
				  'btn_icon_fontawesome'=>'fa fa-arrow-right',
					  'btn_icon_openiconic'=> 'vc-oi vc-oi-dial',
					  'btn_icon_typicons'=> 'typcn typcn-adjust-brightness',
					  'btn_icon_entypo'=> 'entypo-icon entypo-icon-note',		  
					  'btn_icon_linecons'=> 'vc_li vc_li-heart',
					  'btn_icon_monosocial'=> 'vc-mono vc-mono-fivehundredpx',
					'btn_use_theme_fonts'=>'custom-font-family',
					'btn_font_family'=>'',
					'btn_font_weight'=>'400',
					
					"before_after" => 'after',
					"btn_url" => '',
					'btn_align' =>'text-left',
					'select_bg_option'=>'normal',
					'normal_bg_color'=>'#252525',
					
					'select_bg_hover_option'=>'normal',
					
					'normal_bg_hover_color'=>'#ff214f',
					'normal_bg_hover_color1'=>'#d3d3d3',
					
					
					'font_size'=>'20px',
					'line_height'=>'25px',
					'text_color'=>'#8a8a8a',
					'text_hover_color'=>'#252525',
					'border_color'=>'#252525',
					'border_hover_color'=>'#252525',
					'border_radius'=>'30px',
					
					'full_width_btn'=>'',
					'transition_hover'=>'',
					
					), $atts ) );
					wp_enqueue_style( 'theplus-info-box-style');
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
					
					if($btn_use_theme_fonts=='custom-font-family'){
					$btn_font_family='font-family:'.$btn_font_family.';font-weight:'.$btn_font_weight.';';
				}else{
					$btn_font_family='';
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
				  $btn_icon_class = isset( ${'btn_icon_' . $btn_icon} ) ? esc_attr( ${'btn_icon_' . $btn_icon} ) : 'fa fa-arrow-right';
				  
				  if($before_after=='before'){
				   $icons_before = '<i class="btn-icon button-'.esc_attr($before_after).' '.esc_attr($btn_icon_class).'"></i>';
				  }else{
				   $icons_after = '<i class="btn-icon button-'.esc_attr($before_after).' '.esc_attr($btn_icon_class).'"></i>';
				  }
				 }
					if($style=='style-1'){
						$button_text =$icons_before.$btn_text . $icons_after;
						$style_content='<div class="button_line"></div>';
					}
					if($style=='style-2' || $style=='style-5' || $style=='style-8' || $style=='style-10'){
						$button_text =$icons_before . $btn_text . $icons_after;
					}
					if($style=='style-3'){
						$button_text =$btn_text.'<svg class="arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="48" height="9" viewBox="0 0 48 9"><path d="M48.000,4.243 L43.757,8.485 L43.757,5.000 L0.000,5.000 L0.000,4.000 L43.757,4.000 L43.757,0.000 L48.000,4.243 Z" class="cls-1"></path></svg><svg class="arrow-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="48" height="9" viewBox="0 0 48 9"><path d="M48.000,4.243 L43.757,8.485 L43.757,5.000 L0.000,5.000 L0.000,4.000 L43.757,4.000 L43.757,0.000 L48.000,4.243 Z" class="cls-1"></path></svg>';
					}
					if($style=='style-4'){
						$button_text =$icons_before.$btn_text . $icons_after;
						if(!empty($btn_hover_text)){
							$button_hover_text =' data-hover="'.esc_attr($btn_hover_text).'" ';
						}else{
							$button_hover_text =' data-hover="'.esc_attr($btn_text).'" ';
						}
					}
					if($style=='style-6'){
						$button_text =$btn_text;
					}
					if($style=='style-7'){
						$button_text =$btn_text.'<span class="btn-arrow"></span>';
					}
					if($style=='style-9'){
						$button_text =$btn_text.'<span class="btn-arrow"><i class="fa-show fa fa-chevron-right" aria-hidden="true"></i><i class="fa-hide fa fa-chevron-right" aria-hidden="true"></i></span>';
					}
					if($style=='style-11'){
						$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
						if(!empty($btn_hover_text)){
							$button_hover_text =' data-hover="'.esc_attr($btn_hover_text).'" ';
						}else{
							$button_hover_text =' data-hover="'.esc_attr($btn_text).'" ';
						}
						$data_class .=' '.esc_attr($btn_hover_style).' ';
					}
					if($style=='style-12' || $style=='style-15' || $style=='style-16'){
						$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
					}
					if($style=='style-13'){
						$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
						$data_class .=' '.esc_attr($btn_hover_style).' ';
					}
					if($style=='style-14'){
						$button_text ='<span>'.$icons_before . $btn_text . $icons_after.'</span>';
						if(!empty($btn_hover_text)){
							$button_hover_text =' data-hover="'.esc_attr($btn_hover_text).'" ';
						}else{
							$button_hover_text =' data-hover="'.esc_attr($btn_text).'" ';
						}
					}
					if($style=='style-18' || $style=='style-19' || $style=='style-20' || $style=='style-21' || $style=='style-22'){
						$button_text =$icons_before .'<span>'. esc_html($btn_text) .'</span>'. $icons_after;
					}
					
					if($style=='style-23'){
						$button_text ='<span><div class="align-center">'. $icons_before . $btn_text . $icons_after .'</div></span>';
						if(!empty($btn_hover_text)){
							$button_text .='<span><div class="align-center">'. $icons_before . $btn_hover_text . $icons_after .'</div></span>';
						}else{
							$button_text .='<span><div class="align-center">'. $icons_before . $btn_text . $icons_after .'</div></span>';
						}
						$data_class .=' '.esc_attr($btn_hover_style).' ';
					}
					

					$the_button ='<div class="'.esc_attr($btn_align).' ts-button">';
						$the_button .='<div class="pt_plus_button '.$data_class.'" '.$data_attr.' >';
							$the_button .='<a class="button-link-wrap" href="'.esc_url( $a_href ).'" title="'.esc_attr( $a_title ).'" target="'.esc_attr( $a_target ).'" '.$a_rel.' '.$button_hover_text.'>';
								$the_button .=$button_text;
								$the_button .=$style_content;
							$the_button .='</a>';
						$the_button .='</div>';
					$the_button .='</div>';		
					
					
					
					$hover_attr = '';
					
					
					$service_title = $description= $service_img = $service_btn = $service_center= $service_align = $service_space = $pd=$pd0 =$imge_content=$the_service_main_css=$title_css=$subtitle_css=$output='';

					if($title_use_theme_fonts=='custom-font-family'){
					$title_font_family='font-family:'.$title_font_family.';font-weight:'.$title_font_weight.';';
				}else{
					$title_font_family='';
				}	


						$title_css = ' style="';							
							$title_css .= 'color: '.esc_attr($title_color).';';
							if($title_size != "") {
								$title_css .= 'font-size: '.esc_attr($title_size).';';
							}
							if($title_line != "") {
								$title_css .= 'line-height: '.esc_attr($title_line).';';
							}
							
							$title_css .= $title_font_family;
						$title_css .= '"';
					if($title !=''){
						if (!empty($a_href)){
							$service_title= '<a href="'.esc_url( $a_href ).'" title="'.esc_attr( $a_title ).'" target="'.esc_attr( $a_target ).'" '.$a_rel.' ><div class="service-title " '.$title_css.'> '.esc_html($title).' </div></a>';
						}else{
							$service_title= '<div class="service-title " '.$title_css.'> '.esc_html($title).' </div>';
						}
					}
				if($subtitle_use_theme_fonts=='custom-font-family'){
					$subtitle_font_family='font-family:'.$subtitle_font_family.';font-weight:'.$subtitle_font_weight.';';
				}else{
					$subtitle_font_family='';
				}

				
					$sub_title_css = ' style="';
							if($sub_title_color != "") {
								$sub_title_css .= 'color: '.esc_attr($sub_title_color).';';
							}	
							if($sub_title_size != "") {
								$sub_title_css .= 'font-size: '.esc_attr($sub_title_size).';';
							}
							if($sub_title_line != "") {
								$sub_title_css .= 'line-height: '.esc_attr($sub_title_line).';';
							}
							
							$sub_title_css .= $subtitle_font_family;
						$sub_title_css .= '"';
						
					
					if($remove_padding == 'true'){
						$service_space ='remove-padding';				
					}
					
					if($desc_use_theme_fonts=='custom-font-family'){
						$desc_font_family='font-family:'.$desc_family.';font-weight:'.$desc_font_weight.';';
					}else{
						$desc_font_family='';
					}
				 
				 
					$desc_css = ' style="';
						if($desc_color != "") {
							$desc_css .='color:'.esc_attr($desc_color).';';
						}	
						if($desc_size != "") {
							$desc_css .='font-size: '.esc_attr($desc_size).';';
						}
						if($desc_line != "") {
							$desc_css .='line-height: '.esc_attr($desc_line).';';
						}
												
							$desc_css .= $desc_font_family ;
					$desc_css .= '"';	
					if($content !=''){
						$content = wpb_js_remove_wpautop($content, true);
						 $description='<div class="service-desc" '.$desc_css.'> '.$content.' </div>';
					}
					
					if($main_style == 'style_6'){
					   $imge_content ="image-height";
					}
					
					if($main_style == 'style_8'){
						
						$icon_css = ' style="';
						if($icon_color != "") {
						$icon_css .= 'color: '.esc_attr($icon_color).';';
						}
						if($icon_bg_color != "") {
						$icon_css .= 'background-color: '.esc_attr($icon_bg_color).';';
						}
						if($icon_border_color != "") {
						$icon_css .= 'border-color: '.esc_attr($icon_border_color).';';
						}
						
						$icon_css .= '"'; 
					}	
					
						if($icon_size == 'small'){
							$service_icon_size = 'service-icon-small';
						}
						if($icon_size == 'medium'){
							$service_icon_size = 'service-icon-medium';
						}
						if($icon_size == 'large'){
							$service_icon_size = 'service-icon-large';
						}
						
					if($image_icon == 'icon'){
						
						
						$icon_css = ' style="';
						if($icon_color != "") {
						$icon_css .= 'color: '.esc_attr($icon_color).';';
						}
						if($icon_bg_color != "") {
						$icon_css .= 'background-color: '.esc_attr($icon_bg_color).' !Important;';
						}
						if($icon_border_color != "") {
						$icon_css .= 'border-color: '.esc_attr($icon_border_color).' !Important;';
						}
						
						$icon_css .= '"'; 
						vc_icon_element_fonts_enqueue( $type );
						$type12= $type; 
						$icon_class = isset( ${'icon_' . $type} ) ? esc_attr( ${'icon_' . $type} ) : 'fa fa-adjust';
						if($main_style == 'style_8'){
							$service_img = '<i class=" '.esc_attr($icon_class).' service-icon '.esc_attr($service_icon_size).'" ></i>';
						}else{	
							$service_img = '<i class=" '.esc_attr($icon_class).' service-icon '.esc_attr($service_icon_size).'" '.$icon_css.'></i>';
						}
					}
						
					if($button_check == 'true'){

						$service_btn = $the_button;
					}
					
					if($vertical_center == 'true'){
						$service_center = 'vertical-center';
					}
						
					if($text_align == 'left'){
						$service_align = 'text-left';
					}
					if($text_align == 'center'){
						$service_align = 'text-center';
					}
					if($text_align == 'right'){
						$service_align = 'text-right';
					} 
					if($main_style == 'style_5'){
						if($flip_style == 'horizontal'){
							$service_flip= "flip-horizontal";
						}
						if($flip_style == 'vertical'){
							$service_flip= "flip-vertical";
						}
						
						$service_flip_height = ' style="';
						if($flip_height != "") {
						$service_flip_height .= 'min-height: '.esc_attr($flip_height).';';
						}		
						$service_flip_height .= '"'; 
						$flip_front_img = wp_get_attachment_image_src("$front_img ", "full");
							$flip_front_img = $flip_front_img[0];
						$flip_back_img = wp_get_attachment_image_src("$back_img", "full");
							$flip_back_img = $flip_back_img[0];

						$service_front_css = ' style="';
						if($front_color != "") {
						$service_front_css .= 'background-color: '.esc_attr($front_color).';';
						}
						if($front_img != "") {
						$service_front_css .= 'background: url('.esc_attr($flip_front_img ).');';
						}	
						if($flip_height != "") {
						$service_front_css .= 'min-height: '.esc_attr($flip_height).';';
						}
						$service_front_css .= '"'; 
						
						$service_back_css = ' style="';
						if($back_color != "") {
						$service_back_css .= 'background-color: '.esc_attr($back_color).';';
						}
						if($flip_back_img!= "") {
						$service_back_css .= 'background: url('.esc_attr($flip_back_img).');';
						}	
						if($flip_height != "") {
						$service_back_css .= 'min-height: '.esc_attr($flip_height).';';
						}
						$service_back_css .= '"'; 
					}	
					
					if($main_style != 'style_5' ){
						$pd = 'pd-15';
					}
					if($main_style == 'style_8'  ){
						$pd = '';
						}
					
					if($remove_cl_padding == 'true'){
						$pd0 = 'pd-0';
					}
					if($main_style != 'style_8'  ){
					$the_service_main_css = ' style="';						
						if($main_style != 'style_5'){
						if($box_bg_color!= "") {
						$the_service_main_css .= 'background-color: '.esc_attr($box_bg_color).';';
						}
						}
						if($padding_top != "") {
							$the_service_main_css .='padding-top:'.esc_attr($padding_top).';';
						}	
						if($padding_boottom != "") {
							$the_service_main_css .='padding-bottom: '.esc_attr($padding_boottom).';';
						}
						
					$the_service_main_css .= '"';
					}
					
						$header_css = ' style="';
						if($head_bg_color!= "") {
						$header_css .= 'background-color: '.esc_attr($head_bg_color).';';
						}
					$header_css .= '"';
					
					$style8_css = ' style="';
					if($box_bg_color!= "") {
						$style8_css .= 'background-color: '.esc_attr($box_bg_color).';';
						}
					$style8_css .= '"';
					
					
					if($main_style != 'style_8'){
						$box_hover = ' data-box-hover="'.esc_attr($hvr_box_shadow).'"';
						$box_hadow  = ' data-box-hvr="'.esc_attr($box_shadow).'"';
					}else{
						$box_hover = '';
						$box_hvr = '';
						$box_hadow ='';
					}
					
					
					$hover_attr = ' data-icon_hvr_color="'.esc_attr($icon_hvr_color).'"';
					$hover_attr .= ' data-icon_bg_hvr_color="'.esc_attr($icon_bg_hvr_color).'"';
					$hover_attr .= ' data-icon_hvr_bdr_color="'.esc_attr($icon_hvr_bdr_color).'"';
				
						$uid=uniqid('info_box');
					

					
						$isotope ='';
						$data_slider ='';
					
					if ($info_box_layout == 'single_layout'){	
						$output = '<div class="info-box-inner content_hover_effect "  ' . $hover_attr . '>';	
						if($main_style == 'style_1'){
							$output .= '<div class="info-box-bg-box '.esc_attr($pd).' '.esc_attr($pd0).'" '.$the_service_main_css.'>';
								$output .= '<div class="service-media text-left '.esc_attr($service_center).' ">';	
								if($service_img != ''){				
									$output .= '<div class="m-r-16"> '.$service_img.' </div>';
								}
									$output .= '<div class="service-content ">';
										$output .= $service_title;
										$output .= $description;
										$output .= $service_btn;
									$output .= '</div>';
									$output .= '<a class="all-link"></a>';
								$output .= '</div>';
							$output .= '</div>';	
						}
						if($main_style == 'style_2'){
							$output .= '<div class="info-box-bg-box '.esc_attr($pd).' '.esc_attr($pd0).' " '.$the_service_main_css.'>';
								$output .= '<div class="service-media text-right '.esc_attr($service_center).' ">';
									$output .= '<div class="service-content">';
										$output .= $service_title;
										$output .= $description;
										$output .= $service_btn;
									$output .= '</div>';			
								if($service_img != ''){					
									$output .=  '<div class="m-l-16 ">'.$service_img.'</div>';
								}
								$output .= '</div>';
							$output .= '</div>';
						}
						if($main_style == 'style_3'){
							$output .= '<div class="info-box-bg-box '.esc_attr($pd).' '.esc_attr($pd0).' " '.$the_service_main_css.' >';
								$output .= '<div class="'.esc_attr($service_align).'">';
									$output .= '<div class="service-center  ">';
										$output .= $service_img;
										$output .= $service_title;
										$output .= $description;
										$output .= $service_btn;
										$output .= '</div>';				
								$output .= '</div>';
							$output .= '</div>';
						}
						if($main_style == 'style_5'){
							$output .= '<div class="info-box-bg-box '.esc_attr($pd).' '.esc_attr($pd0).'" '.$the_service_main_css.'>';
								$output .= '<div class="service-flipbox '.esc_attr($service_flip).' height-full" '.$service_flip_height.'>';
									$output .= '<div class="service-flipbox-holder height-full text-center perspective bezier-1"	>';
										$output .= '<div class="service-flipbox-front bezier-1 no-backface origin-center" '.$service_front_css.'>';
											$output .= '<div class="service-flipbox-content width-full">';
												$output .= $service_img;
												$output .= '<div class="service-content">';
													$output .= $service_title;
												$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';	
										$output .= '<div class="service-flipbox-back fold-back-horizontal no-backface bezier-1 origin-center" '.$service_back_css.'>';
											$output .= '<div class="service-flipbox-content width-full">';
												$output .= $description;
												$output .= $service_btn;
											$output .= '</div>';
										$output .= '</div>';	
									$output .= '</div>';				
								$output .= '</div>';
							$output .= '</div>';
						}
						if($main_style == 'style_6'){
							$output .= '<div class="info-box-bg-box '.esc_attr($pd).' '.esc_attr($pd0).'" '.$the_service_main_css.'>';
								$output .= '<div class=" text-left '.esc_attr($service_center).' ">';			
									$output .= '<div class="top-content"> '.$service_img.' </div>';
									$output .= '<div class="bottom-content">';
										$output .= $service_title;
										$output .= $description;
										$output .= $service_btn;
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						}
						
						if($main_style == 'style_8'){
							$output .= '<div class="info-box-bg-box '.esc_attr($pd).' '.esc_attr($pd0).'" '.$the_service_main_css.'>';
								$output .= '<div class="about-post  '.esc_attr($service_center).' " '.$style8_css.'>';	
									$output .= '<div class="about-post-content" '.$header_css.'>';
										if($service_img != ''){		
											$output .='<a href="#" class="demo icon-middle " '.$icon_css.'>';
											$output .= '<div class="service-bg-7"> '.$service_img.' </div>';
											$output .= '</a>';
										}
										$output .= $service_title;
									$output .= '</div>';	
									$output .= '<div class="hover-about">';	
										$output .= '<div classs="hiover-about-sub"><h3 class="service-sub-space" '.$sub_title_css.'> '.esc_html($sub_title).' </h3> </div>';
										$output .= '<div classs="about-hover-show">';
											$output .= $description;
											$output .= $service_btn;	
										$output .= '</div>';	
									$output .= '</div>';
									
								$output .= '</div>';
							$output .= '</div>';
						}
						
						if($main_style == 'style_11'){
							$output .= '<div class="info-box-bg-box '.esc_attr($pd).' '.esc_attr($pd0).'" '.$the_service_main_css.'>';	
								$output .= '<div class="info-box style-11 text-center">';	
									$output .= '<div class="info-box-all">';
										$output .= '<div class="info-box-wrapper ">';
											$output .= '<div class="info-box-conetnt">';
												$output .= '<div class="info-box-icon-img">';
													$output .= $service_img;
												$output .= '</div>';	
												$output .= $service_title;	
												$output .= '<div class="info-box-title-hide" '.$title_css.'> '.esc_html($title).' </div>';	
											
												$output .= $description;
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';
									$output .= '<div class="info-box-full color" '.$style8_css.'>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';	
						}
						$output .= '</div>';
					}
					
					
					$info_box ='<div class="pt_plus_info_box '.esc_attr($isotope).'  '.esc_attr($el_class).'  '.esc_attr($uid).' info-box-'.esc_attr($main_style).' '.esc_attr($service_space).'"  data-id="'.esc_attr($uid).'" '.$box_hover.' '.$box_hadow.' '.$hover_attr.' '.$data_slider.' >';
						$info_box .= '<div class="post-inner-loop">';
							$info_box .= $output;
						$info_box .='</div>';
					$info_box .='</div>';
				
				$css_rule='';
				$css_rule .= '<style >';
				
				
				$css_rule .='.'.esc_js($uid).'.pt_plus_info_box.info-box-style_1 .info-box-inner .service-title,.'.esc_js($uid).'.pt_plus_info_box.info-box-style_2 .info-box-inner .service-title,.'.esc_js($uid).'.pt_plus_info_box.info-box-style_3 .info-box-inner .service-title,.'.esc_js($uid).'.pt_plus_info_box.info-box-style_6 .info-box-inner .service-title{margin-bottom : '.esc_js($title_btm_space).'; }';

				$css_rule .='.'.esc_js($uid).'.pt_plus_info_box.info-box-style_8 .info-box-inner .service-sub-space{margin-bottom : '.esc_js($sub_btm_space).' ; }';

				$css_rule .='.'.esc_js($uid).'.pt_plus_info_box.info-box-style_3 .info-box-inner .service-title,.'.esc_js($uid).'.pt_plus_info_box.info-box-style_5 .info-box-inner .service-title,.'.esc_js($uid).'.pt_plus_info_box.info-box-style_6 .info-box-inner .service-sub-space{margin-top : '.esc_js($title_top_space).'; }';
			if($main_style != 'style_5' && $main_style !=  'style_8'){
				$css_rule .= '.'.esc_js($uid).'.pt_plus_info_box .info-box-inner .info-box-bg-box{-webkit-box-shadow: '.esc_js($box_shadow).';-moz-box-shadow: '.esc_js($box_shadow).';box-shadow: '.esc_js($box_shadow).';}.'.esc_js($uid).'.pt_plus_info_box .info-box-inner:hover .info-box-bg-box{-webkit-box-shadow: '.esc_js($hvr_box_shadow).';-moz-box-shadow: '.esc_js($hvr_box_shadow).';box-shadow: '.esc_js($hvr_box_shadow).';}';
			}
				$css_rule .= '.'.esc_js($uid).' .service-desc,.'.esc_js($uid).' .service-desc p{color: '.esc_js($desc_color).';font-size: '.esc_js($desc_size).';font-family: '.esc_js($desc_family).';line-height: '.esc_js($desc_line).';} .'.esc_js($uid).'.pt_plus_info_box.info-box-style_8 .about-post:hover .hover-about{background: '.esc_js($box_hover_color).';}.'.esc_js($uid).'.pt_plus_info_box.info-box-style_8 .about-post:hover .icon-middle{background: '.esc_js($icon_bg_hvr_color).' !important; color:'.esc_js($icon_hvr_color).' !important; border-color: '.esc_js($icon_hvr_bdr_color).' !important; }';
				
				if($button_check == 'true'){
					$css_rule .= include THEPLUS_PLUGIN_PATH.'vc_elements/vc_param/button_css.php';
				}
			
				$css_rule .= '</style>';
	
				return $css_rule.$info_box;
		}
		function init_tp_info_box(){
			if(function_exists("vc_map"))
			{
			require(THEPLUS_PLUGIN_PATH.'vc_elements/vc_param/vc_arrays.php');
				vc_map( array(
					  "name" => __( "Info Box", "pt_theplus" ),
					  "base" => "tp_info_box",
					  "icon" => 'tp-info-box',
					  "category" => __( "The Plus", "pt_theplus"),
					  "description" => esc_html__('Stunning Sections with Style', 'pt_theplus'),
					  "params" => array(
						array(
						  "type"        => "dropdown",
						  'heading' =>  esc_html__('Select Layout', 'pt_theplus'),
						  "param_name"  => "info_box_layout",
						  "admin_label" => true,
						  "value"       => array(
						__( 'Individual Layout ', 'pt_theplus' ) => 'single_layout',
						__( 'Carousel Layout (Premium)', 'pt_theplus' ) => 'carousel_layout',
						  ),
						  "std" => 'single_layout',
						  "description" => '',
						   ),
						  array(
							'type'        => 'radio_select_image',
							'heading' =>  esc_html__('Info Box Style', 'pt_theplus'), 
							'param_name'  => 'main_style',
							'admin_label' => true,
							'simple_mode' => false,
							'value' => 'style_1',
							'options'     => array(
								'style_1' => array(
								'tooltip' => esc_attr__('Style-1','pt_theplus'),
								'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-box/ts-info-box-style-1.jpg'
								),
								'style_2' => array(
								'tooltip' => esc_attr__('Style-2','pt_theplus'),
								'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-box/ts-info-box-style-2.jpg'
								),
								'style_3' => array(
								'tooltip' => esc_attr__('Style-3','pt_theplus'),
								'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-box/ts-info-box-style-3.jpg'
								),
								
								'style_5' => array(
								'tooltip' => esc_attr__('Style-5','pt_theplus'),
								'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-box/ts-info-box-style-5.jpg'
								),
								'style_6' => array(
								'tooltip' => esc_attr__('Style-6','pt_theplus'),
								'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-box/ts-info-box-style-6.jpg'
								),
								
								'style_8' => array(
								'tooltip' => esc_attr__('Style-8','pt_theplus'),
								'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-box/ts-info-box-style-8.jpg'
								),
								
								'style_11' => array(
								'tooltip' => esc_attr__('Style-11','pt_theplus'),
								'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/info-box/ts-info-box-style-11.jpg'
								),
							),
						),
						 array(
						  "type" => "textfield",
						  'heading' =>  esc_html__('Title Of Info Box', 'pt_theplus'),
						  "param_name" => "title",
						  "dependency" => Array('element' => "info_box_layout", 'value' => 'single_layout'),
						  "value" => 'The Plus', 
						  'admin_label' => true,
						  "description" => ""
						),
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Title Options', 'pt_theplus'),
							'param_name'		=> 'title_option',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => 'Styles',
						),
 						array(
						  "type"        => "dropdown",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Title Color Options using this option.','pt_theplus').'</span></span>'.esc_html__('Title Color Options', 'pt_theplus')),
						  "param_name"  => "title_color_o",
						  "admin_label" => true,
						  "value"       => array(
						  __( 'Solid', 'pt_theplus' ) => 'solid',
						  __( 'Gradient (Premium)', 'pt_theplus' ) => 'gradient',
						),
						"std" => "solid",
						"description" => "",
						'group' => esc_attr__('Styles', 'pt_theplus'),
							),
			
						 array(
						  "type" => "colorpicker",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
						  "param_name" => "title_color",
						  "value" => '#ffffff',
						   "edit_field_class" => "vc_col-xs-6",
						  'group' => 'Styles',
'dependency' => array('element' => 'title_color_o','value' => 'solid'),
						  "description" => ""
						),
array(
		   'type' => 'colorpicker',
		   'heading' => __( 'Color 1', 'pt_theplus' ),
		   'param_name' => 'title_color1',  
			'dependency' => array('element' => 'title_color_o','value' => 'gradient'),
		   "edit_field_class" => "vc_col-xs-6",
		   "value" => '#1e73be',
		   'group' => esc_attr__('Styles', 'pt_theplus'),
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Color 2', 'pt_theplus' ),
			'param_name' => 'title_color2',   
			'dependency' => array('element' => 'title_color_o','value' => 'gradient'),
			"edit_field_class" => "vc_col-xs-6",
			"value" => '#2fcbce',
			'group' => esc_attr__('Styles', 'pt_theplus'),
		),
		array(
				'type' => 'dropdown',
				'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select one gradient effect style from four beautiful options.','pt_theplus').'</span></span>'.esc_html__('Gradient Style', 'pt_theplus')),
				'param_name' => 'title_hover_style',
				'value' => array(
					__( 'Horizontal', 'pt_theplus' ) => 'horizontal',
					__( 'Vertical', 'pt_theplus' ) => 'vertical',
					__( 'Diagonal', 'pt_theplus' ) => 'diagonal',
					__( 'Radial', 'pt_theplus' ) => 'radial',                                
				),
			'std'=>'horizontal',
			'dependency' => array('element' => 'title_color_o','value' => 'gradient'),
			"description" => "",
			'group' => esc_attr__('Styles', 'pt_theplus'),
		),
						array(
						  "type" => "textfield",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
						  "param_name" => "title_size",
						  "value" => '24px',
						  "description" => '',
						   "edit_field_class" => "vc_col-xs-6",
						   'group' => 'Styles',
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							"param_name" => "title_line",
							'value' => '1.4',
						   "description" => "",
						   "edit_field_class" => "vc_col-xs-6",
						   'group' => 'Styles',
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
								'group' => esc_attr__('Styles', 'pt_theplus'),	
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
							'param_name' => 'title_font_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styles', 'pt_theplus'),	
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
							'group' => esc_attr__('Styles', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'title_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Title Bottom Space using this Option. E.g. 10px,20px,30px etc.','pt_theplus').'</span></span>'.esc_html__('Bottom Space', 'pt_theplus')),
							'param_name' => 'title_btm_space',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styles', 'pt_theplus'),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Title top Space using this Option. E.g. 10px,20px,30px etc.','pt_theplus').'</span></span>'.esc_html__('Top Space', 'pt_theplus')),
							'param_name' => 'title_top_space',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styles', 'pt_theplus'),
						),	
						array(
						  "type" => "textfield",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add sub title of info box using this option.','pt_theplus').'</span></span>'.esc_html__('Sub Title Of Info Box ', 'pt_theplus')),
						  "param_name" => "sub_title",
						  "value" => 'Creative Design',
						  "description" => '',
						  "dependency" => Array('element' => "info_box_layout", 'value' => 'single_layout'),
						  'dependency' => array(
								'element' => 'main_style',
								 'value' => array("style_8"),
							),
						),
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Sub Title Options', 'pt_theplus'),
							'param_name'		=> 'subtitle_option_on',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => 'Styles',
							"description" => '',
							 'dependency' => array(
								'element' => 'main_style',
								'value' => array("style_8"),
							),
						),
						 array(
						  "type" => "colorpicker",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
						  "param_name" => "sub_title_color",
						  "value" => '#4d4d4d',
						   "edit_field_class" => "vc_col-xs-6",
						   'group' => 'Styles',
						  "description" => '',
						   'dependency' => array(
								'element' => 'main_style',
								'value' => array("style_8"),
							),
						),
						array(
						  "type" => "textfield",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
						  "param_name" => "sub_title_size",
						  "value" => '20px',
						  "description" => '',
						   "edit_field_class" => "vc_col-xs-6",
						   'group' => 'Styles',
							'dependency' => array(
								'element' => 'main_style',
								'value' => array("style_8"),
							),
						),
						
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							"param_name" => "sub_title_line",
							'value' => '1.4',
						   "description" => "",
						   'group' => 'Styles',
							'dependency' => array(
								'element' => 'main_style',
								'value' => array("style_8"),
							),
						   "edit_field_class" => "vc_col-xs-6",
							),
						
							array(
								'type' => 'dropdown',
								'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Subtitle Custom font family', 'pt_theplus'),
								'param_name' => 'subtitle_use_theme_fonts',
								 "value" => array(
									esc_html__("Custom font family", 'pt_theplus') => "custom-font-family",
									esc_html__("Google fonts (Premium)", 'pt_theplus') => "google-fonts",
								),
								'dependency' => array(
									'element' => 'main_style',
									'value' => array("style_8"),
								),
								'std' =>  'custom-font-family',
								'group' => esc_attr__('Styles', 'pt_theplus'),	
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
							'param_name' => 'subtitle_font_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styles', 'pt_theplus'),	
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
							'group' => esc_attr__('Styles', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'subtitle_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Sub Title Bottom Space using this Option. E.g. 10px,20px,30px etc.','pt_theplus').'</span></span>'.esc_html__('Bottom Space', 'pt_theplus')),
							'param_name' => 'sub_btm_space',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
'dependency' => array(
								'element' => 'main_style',
								'value' => array("style_8"),
							),
							'group' => esc_attr__('Styles', 'pt_theplus'),
						),
						
						array(
						  "type" => "dropdown",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select Icon, Custom Image or SVG using this option.','pt_theplus').'</span></span>'.esc_html__('Select Icon ', 'pt_theplus')),
						  "param_name" => "image_icon",
						  "value" => array(
								__( 'None', 'pt_theplus' ) => '',
								__( 'Icon', 'pt_theplus' ) => 'icon',
								__( 'Image (Premium)', 'pt_theplus' ) => 'image',
								__( 'Svg (Premium)', 'pt_theplus' ) => 'svg',
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
						  "std" => "icon",
						  "dependency" => Array('element' => "info_box_layout", 'value' => 'single_layout'),
						),

						array(
							'type' => 'dropdown',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' We have given options of icons from Font Awesome, Open Iconic, Linecons, Typicons, Entypo, and Mono Social.','pt_theplus').'</span></span>'.esc_html__('Icon Library', 'pt_theplus')),
							'value' => array(
								__( 'Font Awesome', 'pt_theplus' ) => 'fontawesome',
								__( 'Open Iconic', 'pt_theplus' ) => 'openiconic',
								__( 'Typicons', 'pt_theplus' ) => 'typicons',
								__( 'Entypo', 'pt_theplus' ) => 'entypo',
								__( 'Linecons', 'pt_theplus' ) => 'linecons',
								__( 'Mono Social', 'pt_theplus' ) => 'monosocial',
							),
							'param_name' => 'type',
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_fontawesome',
							'value' => 'fa fa-adjust', 
							'settings' => array(
								'emptyIcon' => false,
								'iconsPerPage' => 100,
							),
							'dependency' => array(
								'element' => 'type',
								'value' => 'fontawesome',
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_openiconic',
							'value' => 'vc-oi vc-oi-dial', 
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'openiconic',
								'iconsPerPage' => 100,
							),
							'dependency' => array(
								'element' => 'type',
								'value' => 'openiconic',
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_typicons',
							'value' => 'typcn typcn-adjust-brightness',
							'settings' => array(
								'emptyIcon' => false, 
								'type' => 'typicons',
								'iconsPerPage' => 100,
							),
							'dependency' => array(
								'element' => 'type',
								'value' => 'typicons',
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_entypo',
							'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
							'settings' => array(
								'emptyIcon' => false, // default true, display an "EMPTY" icon?
								'type' => 'entypo',
								'iconsPerPage' => 100, // default 100, how many icons per/page to display
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'dependency' => array(
								'element' => 'type',
								'value' => 'entypo',
							),
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_linecons',
							'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
							'settings' => array(
								'emptyIcon' => false, // default true, display an "EMPTY" icon?
								'type' => 'linecons',
								'iconsPerPage' => 100, // default 100, how many icons per/page to display
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'dependency' => array(
								'element' => 'type',
								'value' => 'linecons',
							),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'icon_monosocial',
							'value' => 'vc-mono vc-mono-fivehundredpx', // default value to backend editor admin_label
							'settings' => array(
								'emptyIcon' => false, // default true, display an "EMPTY" icon?
								'type' => 'monosocial',
								'iconsPerPage' => 100, // default 100, how many icons per/page to display
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'dependency' => array(
								'element' => 'type',
								'value' => 'monosocial',
							),
							'description' => '',
						),
						array(
							"type" => "dropdown",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Icon Styles using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Styles (Premium)', 'pt_theplus')),
							"param_name" => "icon_style", 
							"value" => "",
							"description" => "",
							"value"       => array(
								__( 'None', 'pt_theplus' ) => '',
								__( 'Square', 'pt_theplus' ) => 'square',
								__( 'Rounded (Premium)', 'pt_theplus' ) => 'rounded',
								__( 'Hexagon (Premium)', 'pt_theplus' ) => 'hexagon',
								__( 'Pentagon (Premium)', 'pt_theplus' ) => 'pentagon',
								__( 'Square Rotate (Premium)', 'pt_theplus' ) => 'square-rotate',
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
							 "std" => "square",
							  "std" => "square",'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
						   "admin_label" => false,					
						),
						array(
							'type' => 'dropdown',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select Icon Size for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Size', 'pt_theplus')),
							'param_name' => 'icon_size',
							'value' => array( 'Small' => 'small',
											  'Medium' => 'medium',
											  'Large' => 'large',
							) ,		
							'group' => __( 'Icon Option', 'pt_theplus' ),
							"std" => "small",
							'description' => "",
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Color', 'pt_theplus')),
							'param_name' => 'icon_color',
							'value' => '#0099CB',
							'description' => "",
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							"edit_field_class" =>'vc_col-xs-6',
							'group' => __( 'Icon Option', 'pt_theplus' ),
							),
								
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Hover Color', 'pt_theplus')),
							'param_name' => 'icon_hvr_color',
							'value' => '#ffffff',
							'description' => "",
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							'dependency' => array(
								'element' => 'main_style',
								'value' => 'style_8',
							),
							"edit_field_class" =>'vc_col-xs-6',
							'group' => __( 'Icon Option', 'pt_theplus' ),
							),	
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon background using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Background Color', 'pt_theplus')),
							'param_name' => 'icon_bg_color',
							'value' => '#ffffff',
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							
							'group' => __( 'Icon Option', 'pt_theplus' ),
							'description' => "",
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon background hover using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Background Hover Color', 'pt_theplus')),
							'param_name' => 'icon_bg_hvr_color',
							'value' => '#0099CB',
							'description' => "",
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							'dependency' => array(
								'element' => 'main_style',
								'value' => 'style_8',
							),
							"edit_field_class" =>'vc_col-xs-6',
							'group' => __( 'Icon Option', 'pt_theplus' ),
							),	
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon border using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Border Color', 'pt_theplus')),
							'param_name' => 'icon_border_color',
							'value' => '#121212',
							'description' => "",
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							'group' => __( 'Icon Option', 'pt_theplus' ),
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon border hover using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Hover Border Color', 'pt_theplus')),
							'param_name' => 'icon_hvr_bdr_color',
							'value' => '#ffffff',
							'description' => "",
							'dependency' => array(
								'element' => 'image_icon',
								'value' => 'icon',
							),
							'dependency' => array(
								'element' => 'main_style',
								'value' => 'style_8',
							),
							"edit_field_class" =>'vc_col-xs-6',
							'group' => __( 'Icon Option', 'pt_theplus' ),
						),
						array(
							"type" => "textarea_html",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add description of Info Box using this option.','pt_theplus').'</span></span>'.esc_html__('Description Of Info Box', 'pt_theplus')),
							"param_name" => "content", 
							"value" => "",
							"description" => "",
							 "dependency" => Array('element' => "info_box_layout", 'value' => 'single_layout'),
						),
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Description Options', 'pt_theplus'),
							'param_name'		=> 'description_option',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => __( 'Styles', 'pt_theplus' ),
						),
						array(
							"type" => "colorpicker",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							"param_name" => "desc_color",
							'group' => __( 'Styles', 'pt_theplus' ),
							"value" => '#888888',
							"description" => '',
							 "edit_field_class" => "vc_col-xs-6",
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
							"param_name" => "desc_size",
							'group' => __( 'Styles', 'pt_theplus' ),
							"value" => '14px',
							"description" => '',
							 "edit_field_class" => "vc_col-xs-6",
						),
						array(
							"type" => "textfield",
							"class" => "",
							'group' => __( 'Styles', 'pt_theplus' ),
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							"param_name" => "desc_line",
							'value' => '30px',
						   "description" => "",
						   "edit_field_class" => "vc_col-xs-6",
							),
						array(
								'type' => 'dropdown',
								'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Description Custom font family', 'pt_theplus'),
								'param_name' => 'desc_use_theme_fonts',
								 "value" => array(
									esc_html__("Custom font family", 'pt_theplus') => "custom-font-family",
									esc_html__("Google fonts (Premium)", 'pt_theplus') => "google-fonts",
								),
								'std' =>  'custom-font-family',
								'group' => esc_attr__('Styles', 'pt_theplus'),	
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
							'param_name' => 'desc_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styles', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'desc_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font weight using this Option. E.g. 200,400,700,900 etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
							'param_name' => 'desc_font_weight',
							'value' => __('600','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styles', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'desc_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						
						array(
							'param_name'  => 'button_check',
							'heading'     => '',
							'description' => __( 'checkbox false no Button...', 'pt_theplus' ),
							'type'        => 'checkbox',
							'value'       => array(
							  'Button' => 'true'
							),
							"group" => esc_attr__('Button', 'pt_theplus'),
							"dependency" => array(
								 "element" => "main_style",
								 "value" => array("style_1","style_2","style_3","style_5","style_6"),
								)
						 ),
						
						
						array(
							'type' => 'vc_link',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('dd Button URL, Link Open Option and Follow-No Follow Option from this option.','pt_theplus').'</span></span>'.esc_html__('Button URL', 'pt_theplus')),
							'param_name' => 'btn_url',
							"group" => esc_attr__('Button', 'pt_theplus'),
							'description' => '',
							"dependency" => Array('element' => "info_box_layout", 'value' => 'single_layout'),
						),
						array(
							'type'        => 'radio_select_image',
							'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Button Styles using this option','pt_theplus').'</span></span>'.esc_html__('Button Style', 'pt_theplus')), 
							'param_name'  => 'style',
							'simple_mode' => false,
							'value'  => 'style-2',
							"group" => esc_attr__('Button', 'pt_theplus'),
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
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
							 'style-21' => array(
							  'tooltip' => esc_attr__('Style 21','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-21.png'
							 ),
							 'style-22' => array(
							  'tooltip' => esc_attr__('Style 22','pt_theplus'),
							  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/button/button-22.png'
							 ),
							),
						),
						array(
							"type" => "dropdown",
							"heading" => __("Hover Style", "pt_theplus"),
							"param_name" => "btn_hover_style",
							"value" => array(
								__("On Left", "pt_theplus") => "hover-left",
								__("On Right", "pt_theplus") => "hover-right",
								__("On Top", "pt_theplus") => "hover-top",
								__("On Bottom", "pt_theplus") => "hover-bottom"
							),
							"description" => "",
							"std" => 'hover-left',
							"group" => esc_attr__('Button', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-11',
								)
							)
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can write title of button from here.','pt_theplus').'</span></span>'.esc_html__('Button Text', 'pt_theplus')), 
							"param_name" => "btn_text",
							"value" => 'The Plus',
							'description' => '',
							"group" => esc_attr__('Button', 'pt_theplus'),
							 'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-2','style-8','style-9','style-10','style-11','style-21','style-22'
								)
							),
						),
						
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can write on hover title of button from here.','pt_theplus').'</span></span>'.esc_html__('Button Hover Text', 'pt_theplus')),
							"param_name" => "btn_hover_text",
							"value" => '',
							'description' => '',
							"group" => esc_attr__('Button', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-11',
								)
							)
						),
						
						array(
							'type' => 'dropdown',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('We have given options of icons from Font Awesome, Open Iconic, Typicons, Entypo, and Mono Social.','pt_theplus').'</span></span>'.esc_html__('Icon Library', 'pt_theplus')),
							'value' => array(
								__('Select Icon', 'pt_theplus') => '',
								__('Font Awesome', 'pt_theplus') => 'fontawesome',
								__('Open Iconic', 'pt_theplus') => 'openiconic',
								__('Typicons', 'pt_theplus') => 'typicons',
								__('Linecons', 'pt_theplus') => 'linecons',
								__('Entypo', 'pt_theplus') => 'entypo',
								__('Mono Social', 'pt_theplus') => 'monosocial'
							),
							'std' => 'fontawesome',
							"group" => esc_attr__('Button', 'pt_theplus'),
							'param_name' => 'btn_icon',
							 "dependency" => Array('element' => "info_box_layout", 'value' => 'single_layout'),
							'description' => '',
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-2',
									'style-8',
									'style-11',
									'style-21',
									'style-22',
								)
							)
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'btn_icon_fontawesome',
							'value' => 'fa fa-arrow-right', // default value to backend editor admin_label
							'settings' => array(
								'emptyIcon' => false,
								'iconsPerPage' => 4000
							),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'fontawesome'
							),
							"group" => esc_attr__('Button', 'pt_theplus'),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'btn_icon_openiconic',
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
							"group" => esc_attr__('Button', 'pt_theplus'),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'btn_icon_typicons',
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
							"group" => esc_attr__('Button', 'pt_theplus'),
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'btn_icon_entypo',
							'value' => 'entypo-icon entypo-icon-note',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'entypo',
								'iconsPerPage' => 4000
							),
							"group" => esc_attr__('Button', 'pt_theplus'),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'entypo'
							)
							
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'btn_icon_linecons',
							'value' => 'vc_li vc_li-heart',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'linecons',
								'iconsPerPage' => 4000
							),
							"group" => esc_attr__('Button', 'pt_theplus'),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'linecons'
							),
							
							'description' => '',
						),
						array(
							'type' => 'iconpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
							'param_name' => 'btn_icon_monosocial',
							'value' => 'vc-mono vc-mono-fivehundredpx',
							'settings' => array(
								'emptyIcon' => false,
								'type' => 'monosocial',
								'iconsPerPage' => 4000
							),
							"group" => esc_attr__('Button', 'pt_theplus'),
							'dependency' => array(
								'element' => 'btn_icon',
								'value' => 'monosocial'
							),
							
							'description' => '',
						),
						array(
							"type" => "dropdown",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Select Position of Icon before or after content from this option.','pt_theplus').'</span></span>'.esc_html__('Icon Position', 'pt_theplus')),
							"param_name" => "before_after",
							"value" => array(
								__("After Icon", "pt_theplus") => "after",
								__("Before Icon", "pt_theplus") => "before"
							),
							"description" => "",
							"std" => 'after',
							"group" => esc_attr__('Button', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-2',
									'style-8',
									'style-10',
									'style-11',
									'style-21',
									'style-22',
								)
							)
						),
						array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Button Text Style', 'pt_theplus'),
							'param_name' => 'text_style',
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							
							"group" => esc_attr__('Button Style', 'pt_theplus')
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
							"param_name" => "font_size",
							"value" => '20px',
							'description' => '',
							"edit_field_class" => "vc_col-xs-6",
							"group" => esc_attr__('Button Style', 'pt_theplus'),
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
							"param_name" => "line_height",
							"value" => '25px',
							'description' => '',
							"edit_field_class" => "vc_col-xs-6",
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
							"group" => esc_attr__('Button Style', 'pt_theplus')
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Setup Inner Padding top-bottom and right-left to Button from this option. E.g. 15px 20px, 30px 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Inner Padding ', 'pt_theplus')),
							"param_name" => "btn_padding",
							"value" => '15px 30px',
							"edit_field_class" => "vc_col-xs-6",
							"group" => esc_attr__('Button Style', 'pt_theplus'),
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
							'description' => '',
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button text color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Color', 'pt_theplus')),
							'param_name' => 'text_color',
							"value" => '#8a8a8a',
							"description" => "",
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
							"edit_field_class" => "vc_col-xs-6"
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button hover text color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Hover Color', 'pt_theplus')),
							'param_name' => 'text_hover_color',
							"value" => '#252525',
							"description" => "",
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-6"
						),
						array(
								'type' => 'dropdown',
								'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Button Custom font family', 'pt_theplus'),
								'param_name' => 'btn_use_theme_fonts',
								 "value" => array(
									esc_html__("Custom font family", 'pt_theplus') => "custom-font-family",
									esc_html__("Google fonts (Premium)", 'pt_theplus') => "google-fonts",
								),
								'std' =>  'custom-font-family',
								'group' => esc_attr__('Button Style', 'pt_theplus'),
								"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
							'param_name' => 'btn_font_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'btn_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font weight using this Option. E.g. 200,400,700,900 etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
							'param_name' => 'btn_font_weight',
							'value' => __('400','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Button Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'btn_use_theme_fonts',
									'value' => 'custom-font-family',
								),
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
									'style-21',
									'style-22',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button border color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Border Color', 'pt_theplus')),
							'param_name' => 'border_color',
							"value" => '#252525',
							"description" => "",
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-4",
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-21',
									'style-22',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select button border hover color and Opacity for button using this option.','pt_theplus').'</span></span>'.esc_html__('Border Hover Color', 'pt_theplus')),
							'param_name' => 'border_hover_color',
							"value" => '#252525',
							"description" => "",
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-4",
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-21',
									'style-22',
								)
							)
						),
						array(
							"type" => "textfield",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can choose radius for border using this option. E.g. 1px, 2px, etc.','pt_theplus').'</span></span>'.esc_html__('Border Radius', 'pt_theplus')),
							"param_name" => "border_radius",
							"value" => "30px",
							"description" => "",
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"edit_field_class" => "vc_col-xs-4",
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-10',
									'style-11',
									'style-21',
									'style-22'
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
									'style-21',
									'style-22',
									'style-23'
								)
							)
						),
						array(
							"type" => "dropdown",
							"heading" => __("Select Background Option", "pt_theplus"),
							"param_name" => "select_bg_option",
							"value" => array(
								__("Normal color", "pt_theplus") => "normal",
								__("Gradient color (Premium)", "pt_theplus") => "gradient",
								__("Bg Image (Premium)", "pt_theplus") => "image"
							),
							"description" => "",
							"std" => 'normal',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-22',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Color', 'pt_theplus'),
							'param_name' => 'normal_bg_color',
							
							"description" => "",
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"value" => '#252525',
							'dependency' => array(
								'element' => 'select_bg_option',
								'value' => 'normal'
							)
						),
						
						array(
							"type" => "dropdown",
							"heading" => __("Hover Background Option", "pt_theplus"),
							"param_name" => "select_bg_hover_option",
							"value" => array(
								__("Normal color", "pt_theplus") => "normal",
								__("Gradient color (Premium)", "pt_theplus") => "gradient",
								__("Bg Image (Premium)", "pt_theplus") => "image"
							),
							"description" => "",
							"std" => 'normal',
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-21',
									'style-22',
								)
							)
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Hover Bg color', 'pt_theplus'),
							'param_name' => 'normal_bg_hover_color',
							"description" => "",
							'group' => esc_attr__('Button Style', 'pt_theplus'),
							"value" => '#ff214f',
							'dependency' => array(
								'element' => 'select_bg_hover_option',
								'value' => 'normal'
							),
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Full Width Button', 'pt_theplus'),
							'param_name' => 'full_width_btn',
							'value' => array(
								__('Yes', 'pt_theplus') => 'yes'
							),
							'description' => '',
							'std' => '',
							"group" => esc_attr__('Button', 'pt_theplus'),
							'dependency' => array(
								'element' => 'style',
								'value' => array(
									'style-8',
									'style-10',
									'style-11',
									'style-21',
									'style-22',
								)
							)
						),
						array(
							'type' => 'dropdown',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Choose button alignment from Right, Left or Center.','pt_theplus').'</span></span>'.esc_html__('Alignment', 'pt_theplus')), 
							'param_name' => 'btn_align',
							'value' => array(
								__('Left', 'pt_theplus') => 'text-left',
								__('Center', 'pt_theplus') => 'text-center',
								__('Right', 'pt_theplus') => 'text-right'
							),
							'std' => 'text-left',
							"dependency" => array(
								 "element" => "button_check",
								 "value" => array("true"),
							),
							"group" => esc_attr__('Button', 'pt_theplus'),
							"description" => ""
						),
						
						
						array(
						  "type"        => "dropdown",
						  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Choose info box alignment from Right, Left or Center.','pt_theplus').'</span></span>'.esc_html__('Info Box Alignment', 'pt_theplus')),
						  "param_name"  => "text_align",
						  "value"       => array(
								__( 'Select Style', 'pt_theplus' ) => '',
								__( 'Left', 'pt_theplus' ) => 'left',
								__( 'Center', 'pt_theplus' ) => 'center',
								__( 'Right', 'pt_theplus' ) => 'right',
							),
						  "std" => "center",
						  "description" => '',
						  "dependency" => array(
							 "element" => "main_style",
							 "value" => array("style_3"),
							),
						), 
						
						 array(
							"type" => "textfield",
							"heading" => esc_html__("Flip Box Height", 'pt_theplus') ,
							"param_name" => "flip_height",
							"value" => "300px",
							"description" => '',  
							"dependency" => array(
									"element" => "main_style",
									"value" => "style_5",
									),	
							"group" => 'Flip Box',
						),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Select Flip Box Style', 'pt_theplus' ),
							'param_name' => 'flip_style',
							'value' => array(
								__( 'Horizontal', 'pt_theplus' ) => 'horizontal',
								__( 'Vertical ', 'pt_theplus' ) => 'vertical',
							),
							'std' => 'horizontal',
							"dependency" => array(
											"element" => "main_style",
											"value" => "style_5",
										),
							"group" => 'Flip Box',				
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Flip Box Front Color', 'pt_theplus' ),
							'param_name' => 'front_color',
							'value' => '#121212',
							"dependency" => array(
											"element" => "main_style",
											"value" => "style_5",
										),
							"group" => 'Flip Box',				
						),
						array(
							'type' => 'attach_image',
							'heading' => __( 'Flip Box Front Image', 'pt_theplus' ),
							'param_name' => 'front_img',
							'value' => '',
							"dependency" => array(
											"element" => "main_style",
											"value" => "style_5",
										),
							"group" => 'Flip Box',				
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Flip Box back Color', 'pt_theplus' ),
							'param_name' => 'back_color',
							'value' => '#5aa1e3',
							"dependency" => array(
											"element" => "main_style",
											"value" => "style_5",
										),
							"group" => 'Flip Box',				
						),
						array(
							'type' => 'attach_image',
							'heading' => __( 'Flip Box Back Image', 'pt_theplus' ),
							'param_name' => 'back_img',
							'value' => '',
							"dependency" => array(
											"element" => "main_style",
											"value" => "style_5",
										),
							"group" => 'Flip Box',				
						),
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Background Options', 'pt_theplus'),
							'param_name'		=> 'background_option',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => 'Styles',
								"dependency" => array(
								 "element" => "main_style",
								 "value" => array("style_2","style_8"),
								),
						),
						  array(
						   'type' => 'colorpicker',
						   'heading' => __( 'Background Color', 'pt_theplus' ),
						   'param_name' => 'box_bg_color',
						   'value' => '#ff004b',
						   'description' => '',
						   "dependency" => array(
							 "element" => "main_style",
							 "value" => array("style_2","style_8","style_11"),
							),
							'group' => 'Styles',
						   ),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Background Hover Color', 'pt_theplus' ),
							'param_name' => 'box_hover_color',
							'value' => '#0099cc',
							'description' => '',
							"dependency" => array(
								 "element" => "main_style",
								 "value" => array("style_8"),
								),
								'group' => 'Styles',
							),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Header Background Color', 'pt_theplus' ),
							'param_name' => 'head_bg_color',
							'value' => '#ffffff',
							'description' => '',
							"dependency" => array(
								 "element" => "main_style",
								 "value" => array("style_8"),
								),
								'group' => 'Styles',
							),	
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Box Shadow Setting', 'pt_theplus'),
							'param_name'		=> 'boxshadow_setting',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Styles', 'pt_theplus'), 
							
						),
						array(
							'type' => 'textfield',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Box Shadow ', 'pt_theplus')),
							'param_name' => 'box_shadow',
							'value' => '1px 1px 3px 3px rgba(0, 0, 0, 0.15)',
							'group' => 'Styles',
							'edit_field_class'	=> 'vc_col-sm-6',
							'description' => '',
							),
						array(
							'type' => 'textfield',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Hover Box Shadow ', 'pt_theplus')),
							'param_name' => 'hvr_box_shadow',
							'edit_field_class'	=> 'vc_col-sm-6',
							'value' => '0 22px 43px rgba(0, 0, 0, 0.15)',
							'group' => 'Styles',
							'description' => '',
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
												__( 'No-animation', 'pt_theplus' )             => 'no-animation',
								__( 'FadeIn', 'pt_theplus' )             => 'transition.fadeIn',
								__( 'FlipXIn', 'pt_theplus' )            => 'transition.flipXIn',
							   __( 'FlipYIn', 'pt_theplus' )            => 'transition.flipYIn',
							   __( 'FlipBounceXIn', 'pt_theplus' )      => 'transition.flipBounceXIn',
							   __( 'FlipBounceYIn', 'pt_theplus' )      => 'transition.flipBounceYIn',
							   __( 'SwoopIn', 'pt_theplus' )            => 'transition.swoopIn',
							   __( 'WhirlIn', 'pt_theplus' )            => 'transition.whirlIn',
							   __( 'ShrinkIn', 'pt_theplus' )           => 'transition.shrinkIn',
							   __( 'ExpandIn', 'pt_theplus' )           => 'transition.expandIn',
							   __( 'BounceIn', 'pt_theplus' )           => 'transition.bounceIn',
							   __( 'BounceUpIn', 'pt_theplus' )         => 'transition.bounceUpIn',
							   __( 'BounceDownIn', 'pt_theplus' )       => 'transition.bounceDownIn',
							   __( 'BounceLeftIn', 'pt_theplus' )       => 'transition.bounceLeftIn',
							   __( 'BounceRightIn', 'pt_theplus' )      => 'transition.bounceRightIn',
							   __( 'SlideUpIn', 'pt_theplus' )          => 'transition.slideUpIn',
							   __( 'SlideDownIn', 'pt_theplus' )        => 'transition.slideDownIn',
							   __( 'SlideLeftIn', 'pt_theplus' )        => 'transition.slideLeftIn',
							   __( 'SlideRightIn', 'pt_theplus' )       => 'transition.slideRightIn',
							   __( 'SlideUpBigIn', 'pt_theplus' )       => 'transition.slideUpBigIn',
							   __( 'SlideDownBigIn', 'pt_theplus' )     => 'transition.slideDownBigIn',
							   __( 'SlideLeftBigIn', 'pt_theplus' )     => 'transition.slideLeftBigIn',
							   __( 'SlideRightBigIn', 'pt_theplus' )    => 'transition.slideRightBigIn',
							   __( 'PerspectiveUpIn', 'pt_theplus' )    => 'transition.perspectiveUpIn',
							   __( 'PerspectiveDownIn', 'pt_theplus' )  => 'transition.perspectiveDownIn',
							   __( 'PerspectiveLeftIn', 'pt_theplus' )  => 'transition.perspectiveLeftIn',
							   __( 'PerspectiveRightIn', 'pt_theplus' ) => 'transition.perspectiveRightIn',
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
							'param_name'  => 'padding_top',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can set Padding Top using this option. E.g. 20px, 50px, 70px, etc.','pt_theplus').'</span></span>'.esc_html__('Padding Top', 'pt_theplus')),
							'description' => "",
							'type'        => 'textfield',          
							'edit_field_class' => 'vc_col-sm-6',
						 ),
						 array(
							'param_name'  => 'padding_boottom',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can set Padding Bottom using this option. E.g. 20px, 50px, 70px, etc.','pt_theplus').'</span></span>'.esc_html__('Padding Bottom', 'pt_theplus')),
							'description' => "",
							'type'        => 'textfield',     
							'edit_field_class' => 'vc_col-sm-6',
						 ),
						 array(
							'param_name'  => 'vertical_center',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' By checking this option up You can By checking this option You can make stylish list content vertical center.','pt_theplus').'</span></span>'.esc_html__('Vertical Center', 'pt_theplus')),
							'description' => '',
							'type'        => 'checkbox',
							'value'       => array(
							  'Vertical center alignment' => 'true'
							),
							'edit_field_class' => 'vc_col-sm-6',
							 "dependency" => array(
							 "element" => "main_style",
							 "value" => array("style_1","style_2"),
							),
						 ),
						array(
							'param_name'  => 'remove_padding',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' By checking this option up You can remove column padding of info boxes.','pt_theplus').'</span></span>'.esc_html__('Column Padding Remove', 'pt_theplus')),
							'type'        => 'checkbox',
							'edit_field_class' => 'vc_col-sm-6',
							'value'       => array(
							  'Remove' => 'true',
							),
						 ),
						 array(
							'param_name'  => 'remove_cl_padding',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('By checking this option up You can remove internal padding of info boxes.','pt_theplus').'</span></span>'.esc_html__('Internal Padding Remove', 'pt_theplus')),
							'description' => __( ' ', 'pt_theplus' ),
							'type'        => 'checkbox',
							'edit_field_class' => 'vc_col-sm-6',
							'value'       => array(
							  'Remove' => 'true',
							),
						 ),
						array(
							"type" => "textfield",
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can add Extra Class here to use for Customisation Purpose.','pt_theplus').'</span></span>'.esc_html__('Extra Class', 'pt_theplus')),
							"param_name" => "el_class",
							'edit_field_class' => 'vc_col-sm-6',
						),
						
					 )	
				   ) );
			}
		}
	}
	new ThePlus_info_box;

	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_tp_info_box'))
	{
		class WPBakeryShortCode_tp_info_box extends WPBakeryShortCode {
		   protected function contentInline( $atts, $content = null ) {
			 
		 }
		}
	}
}