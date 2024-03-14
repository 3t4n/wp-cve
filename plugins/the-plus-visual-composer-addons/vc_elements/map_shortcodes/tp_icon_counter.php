<?php 
// Icon Counter Elements
if(!class_exists("ThePlus_icon_counter")){
	class ThePlus_icon_counter{
		function __construct(){
			add_action( 'init', array($this, 'init_tp_icon_counter') );
			add_shortcode( 'tp_icon_counter',array($this,'tp_icon_counter_shortcode'));
		}
		function tp_icon_counter_shortcode($atts,$content = null){
			extract( shortcode_atts( array(
				  'icn_layout' =>'single_layout',
				  'icn_style' =>'style_1',
				  "subject" => 'Title',
				  'symbol' => '',
				  'symbol_position' => 'after',
				   'no_size_tag'=> '30px',
				   'no_line' =>'1',
				   'no_letter' =>'1px',
				   'digit_use_theme_fonts'=>'custom-font-family',
					'digit_font_family'=>'',
					'digit_font_weight'=>'400',
				
				   'title_size'=> '30px',
				   'title_line' =>'1',
				   'title_use_theme_fonts'=>'custom-font-family',
					'title_font_family'=>'',
					'title_font_weight'=>'600',
				
				   'subtitle_size'=> '22px',
				   'subtitle_line' =>'1',
				   'subtitle_use_theme_fonts'=>'custom-font-family',
					'subtitle_font_family'=>'',
					'subtitle_font_weight'=>'400',
					
				  'number' => '99',
				  'numbers_font_family' =>'',
				  'style_color' => '',
				  'style_hover_color' => '',
				  'sub_style_color' =>'',
				  'title_hv_color'=>'#000',
				  'extra_class'=>'',
				  'type'=> 'fontawesome',
				  'icon_fontawesome'=> 'fa fa-adjust',
				  'icon_openiconic'=> 'vc-oi vc-oi-dial',
				  'icon_typicons'=> 'typcn typcn-adjust-brightness',
				  'icon_entypo'=> 'entypo-icon entypo-icon-note',    
				  'icon_linecons'=> 'vc_li vc_li-heart',
				  'icon_monosocial'=> 'vc-mono vc-mono-fivehundredpx',
				  'icon_custom_color'=>'',
				  'icon_2custom_color'=>'#ccc',
				  'icon_background_shape'=>'#abcaea',
				  'icon_background_color'=>'',
				  'icon_custom_background_color'=>'',
				  'icon_font_size'=> '1.2',
				  'icon_link' => '',
				  'icon_align'=> 'left',
				  'icon_border_color'=> '',
				  'icon_border_width'=> '',
				  'background_color_counter'=> '',
				  'bg_hv_clr_ctr'=>'',
				  'icon_hover_style_counter'=>'style-1',
				  'icon_counter_style_counter'=> 'style-2',
				 
				  'icon_2custom_hover_color'=>'#000',
				  'sub_subject'=>'',
				  'sub_title_clr'=>'#555',
				  'imge'=>'', 
				  'icon_imge' =>'icon_',
				  'icon_image' => '',
				  'sub_title_hv_clr' => '',
				  'box_border' => '',	
				  'box_border_clr' =>'#4d4d4d',
				  'bd_width' => '',
				  'bd_rad' => '',
				  'bd_clr' => '#4d4d4d',
				  'border_width' =>'10%',
				  'bd_height' =>'2px',
				  'cont_bg' =>'#F9B701',
				  'btn_text' =>'',
				  'btn_link' =>'',
				  'btn_bg' =>'',
				  'btn_bg_hvr' =>'',
				  'btn_clr_hvr' =>'',
				  'btn_clr' => '',
				 
				  'btn_font' => '',
				  'btn_bd' => '',
				  'btn_wid' => '',
				  'btn_rad' => '',
				  'btn_bd_clr' => '',
				  'btn_h_cr' => '',
				
			   ), $atts ) );
			   
				$button_link = $pd0 = $icon_img_ic =$icon_border_box= $number_markup =$subject_markup=$subject_markup1 =$border_box_css ='';
				
				if($icn_style == 'style_2'){
					$pd0="pad-0";
				}elseif ($icn_style == 'style_1'){
					$pd0="pad-5";
				}else{
					$pd0="pad-30";
				}
				if($box_border=='true'){
					$icon_border_box='border-pd';
					
					$border_box_css = 'style="';
						if($box_border_clr!= "") {
							$border_box_css .='border-color: '.esc_attr($box_border_clr).';';
						}
						
					$border_box_css .= '";';
					
				}

				
			   $btn_link = ( '||' === $btn_link ) ? '' : $btn_link;
				$btn_link= vc_build_link( $btn_link);

				$a_href = $btn_link['url'];
				$a_title = $btn_link['title'];
				$a_target = $btn_link['target'];
				$a_rel = $btn_link['rel'];
				if ( ! empty( $a_rel ) ) {
				$a_rel = ' rel="' . esc_attr( trim( $a_rel ) ) . '"';
				}

				   $img = wp_get_attachment_image_src($icon_image, "full");
			 
					$imgSrc = $img[0];

			  if(!empty($symbol)) {
				  if($symbol_position=="after"){
					$symbol2 = '<span class="theserivce-milestone-number icon-milestone" data-counterup-nums="'.esc_attr($number).'">'.esc_html($number).'</span><span>'.esc_html($symbol).'</span>';
					}elseif($symbol_position=="before"){
						$symbol2 = '<span>'.esc_html($symbol).'</span><span class="theserivce-milestone-number" data-counterup-nums="'.esc_attr($number).'">'.esc_html($number).'</span>';
					}
				} else {
					$symbol2 = '<span class="theserivce-milestone-number icon-milestone" data-counterup-nums="'.esc_attr($number).'">'.esc_html($number).'</span>';
				}
			if($icon_align=='left'){
				$alignment_no='text-left';
			}elseif($icon_align=='center'){
				$alignment_no='text-center';
			}elseif($icon_align=='right'){
				$alignment_no='text-right';
			}
			if($icon_align=='left'){
				$alignment_no_num='numtext-left';
			}elseif($icon_align=='center'){
				$alignment_no_num='numtext-center';
			}elseif($icon_align=='right'){
				$alignment_no_num='numtext-right';
			}
			if($icon_align=='left'){
				$icon_alignment_no='icon-left';
			}elseif($icon_align=='center'){
				$icon_alignment_no='icon-center';
			}elseif($icon_align=='right'){
				$icon_alignment_no='icon-right';
			}
			$rand_no=rand(1000000, 1500000);
				if($icn_style == "style_2") {
					$footer_css= 'style="';
						if($cont_bg!= "") {
							$footer_css.='background: '.esc_attr($cont_bg).';';
						}
					$footer_css.= '";';
				}
				
				$border_bottom = 'style="';
					if($bd_height!= "") {
						$border_bottom .='border-width: '.esc_attr($bd_height).';';
					}
					if($bd_clr!= "") {
						$border_bottom .='border-color: '.esc_attr($bd_clr).';';
					}
					if($border_width!= "") {
						$border_bottom .='width: '.esc_attr($border_width).';';
					}
				$border_bottom .= '";';
				
			if($subtitle_use_theme_fonts=='custom-font-family'){
				$subtitle_font_family='font-family:'.$subtitle_font_family.';font-weight:'.$subtitle_font_weight.';';
			}else{
				$subtitle_font_family='';
			}	
				$subtitle_css = ' style="';
				   if($sub_title_clr!= "") {
					$subtitle_css .= 'color: '.esc_attr($sub_title_clr).';';
					}
					if($subtitle_size!= "") {
					$subtitle_css .='font-size:'.esc_attr($subtitle_size).';';
					}
					if($subtitle_line!= "") {
					$subtitle_css .='line-height:'.esc_attr($subtitle_line).';';
					}
					
					$subtitle_css .= $subtitle_font_family;
				$subtitle_css .= '";';

			if($digit_use_theme_fonts=='custom-font-family'){
				$digit_font_family='font-family:'.$digit_font_family.';font-weight:'.$digit_font_weight.';';
			}else{
				$digit_font_family='';
			}
				$title_css = ' style="';
				   if($style_color != "") {
					$title_css .= 'color: '.esc_attr($style_color).';';
					}
					if($no_size_tag != "") {
					$title_css .='font-size:'.esc_attr($no_size_tag).';';
					}
					if($no_line!= "") {
					$title_css .='line-height:'.esc_attr($no_line).';';
					}
					if($no_letter!= "") {
					$title_css .='letter-spacing:'.esc_attr($no_letter).';';
					} 
					$title_css .= $digit_font_family;
				$title_css .= '";';
			   
				$icon_style = 'style="';
				if($icon_2custom_color != "") {
					$icon_style .='color: '.esc_attr($icon_2custom_color).';';
				}

						if($icon_font_size != "") {
					$icon_style .='font-size : '.esc_attr($icon_font_size).'em;';
				}
				$icon_style .= '";';
				
				
				
				$icon_background_style = 'style="';
					if($icon_custom_background_color != "") {
					$icon_background_style .='background : '.esc_attr($icon_custom_background_color).';';
					}
					if($icon_border_width != "") {
					$icon_background_style .='border : '.esc_attr($icon_border_width).' solid;';
					}
					if($icon_border_color != "") {
					$icon_background_style .='border-color : '.esc_attr($icon_border_color).';';
					}

				$icon_background_style .= '";';	

				$icon_img_background_style = 'style="';
					if($icon_custom_background_color != "") {
					$icon_img_background_style  .='background : '.esc_attr($icon_custom_background_color).';';
					}
					if($icon_border_width != "") {
					$icon_img_background_style .='border : '.esc_attr($icon_border_width).' solid;';
					}
					if($icon_border_color != "") {
					$icon_img_background_style .='border-color : '.esc_attr($icon_border_color).';';
					}
				$icon_img_background_style .= '";';	



					$icon_counter_background_style = 'style="';
				
					if($background_color_counter != "") {
					$icon_counter_background_style .='background : '.esc_attr($background_color_counter).';';
					}	
						if($bd_width != "") {
					$icon_counter_background_style .='border : '.esc_attr($bd_width).' '.esc_attr($box_border).' '.esc_attr($bd_clr).';';
					}
					if($bd_rad != "") {
					$icon_counter_background_style .='-moz-border-radius:'.esc_attr($bd_rad).';-webkit-border-radius: '.esc_attr($bd_rad).';border-radius : '.esc_attr($bd_rad).';';
					}
				$icon_counter_background_style .= '";';

			  if($icn_style=='style_1'){
				$icn_style_class='icn-style-1';
			}elseif($icn_style=='center'){
				$icn_style_class='icon-center';
			}elseif($icn_style=='right'){
				$icn_style_class='icon-right';
			}else{
				$icn_style_class='';
			}
			
			if($title_use_theme_fonts=='custom-font-family'){
				$title_font_family='font-family:'.$title_font_family.';font-weight:'.$title_font_weight.';';
			}else{
				$title_font_family='';
			}	
				$title_font = 'style="';
					if($sub_style_color!=''){
						$title_font .=' color : '.esc_attr($sub_style_color).';';
					}
					if($title_size!=''){
						$title_font .=' font-size : '.esc_attr($title_size).';';
					}
					
					if($title_line!=''){
						$title_font .=' line-height : '.esc_attr($title_line).';';
					}
					
					$title_font .=$title_font_family;
				$title_font .= '";';
			vc_icon_element_fonts_enqueue( $type );
			//echo $icon_fontawesome;
			$type12= $type; 
			$iconClass = isset( ${'icon_' . $type} ) ? esc_attr( ${'icon_' . $type} ) : 'fa fa-adjust';
			
			
				if($icon_imge =="image_"){
					$icon_img_ic ='<div class="ts-icon-img icon-img-b '.esc_attr($icon_alignment_no).'" '.$icon_img_background_style.'>';
							$icon_img_ic .='<img class="" src='.esc_url($imgSrc).' alt="" />';
					$icon_img_ic .='</div>';
				}else if($icon_imge =="icon_"){		 
					$icon_img_ic .='<div class="ts-icon icon-img-b '.esc_attr($icon_alignment_no).'" " '.$icon_background_style.'>';
						$icon_img_ic .='<div class="ts-icon-1" >';
							$icon_img_ic .='<span '.$icon_style.' class="counter-icon counter-'.esc_attr($rand_no).' '.$iconClass.'"></span>';
						$icon_img_ic .='</div>';	
					$icon_img_ic .='</div>';	
				}	
				if($number!= ''){
				$number_markup = '<h5 class="counter-number  color-'.esc_attr($rand_no).' '.esc_attr($alignment_no_num).'" '.$title_css.' >'.$symbol2.'</h5>';
				}
				if($subject!= ''){
				$subject_markup = '<h6 class="subject-color subject-color-'.esc_attr($rand_no).' '.esc_attr($alignment_no).'"  '.$title_font.'>'.esc_html($subject).'</h6>';
				}
				if($sub_subject!= ''){
				$subject_markup1 = '<h6 class="sub-subject-color sub-subject-color-'.esc_attr($rand_no).' '.esc_attr($alignment_no).'" '.$subtitle_css.'>'.esc_html($sub_subject).'</h6>';
				}

				if($btn_text != ''){
				$button_link = '<div class="icon-btn" ><a class="read-more" href="'.esc_url( $a_href ).'" title="'.esc_attr( $a_title ).'" target="'.esc_attr( $a_target ).'" '.$a_rel.' '.$btn_style.'>'.esc_html($btn_text).'</a></div>';
				}
				$data_attr ='';
				
					$data_attr .=' data-bg_hover_clr="'.esc_attr($bg_hv_clr_ctr).'" ';
					$data_attr .=' data-icon_2custom_hover_color="'.esc_attr($icon_2custom_hover_color).'" '; 
					$data_attr .=' data-style_hover_color="'.esc_attr($style_hover_color).'" '; 
					
					$data_attr .=' data-title_hv_color="'.esc_attr($title_hv_color).'" '; 
					$data_attr .=' data-sub_title_hv_clr="'.esc_attr($sub_title_hv_clr).'" '; 
					
					
				
				$icon_single ='';
				if ($icn_layout == 'single_layout'){
					
					$icon_single = '<div class="icon-counte-inner '.esc_attr($pd0).'" '.$icon_counter_background_style.' >';
						if($icn_style == 'style_1'){
						$icon_single .='<div class="border-icon '.esc_attr($icon_border_box).'" '.$border_box_css .'>';
							$icon_single .= $icon_img_ic;
							$icon_single .='<div class="ts-milestone-'.esc_attr($rand_no).' icn-txt '.esc_attr($alignment_no).' '.esc_attr($extra_class).'" >';
							$icon_single .= $number_markup;
							$icon_single .= '<hr class="hr-border" '.$border_bottom.'>';
							$icon_single .= $subject_markup;
							$icon_single .= $subject_markup1;
							$icon_single .= '</div>';
						$icon_single .= '</div>';	
						}elseif($icn_style == 'style_2'){
							$icon_single .='<div class="ts-milestone-'.esc_attr($rand_no).' '.esc_attr($alignment_no).' '.esc_attr($extra_class).'" >';
								$icon_single .= '<div class="icn-header">';
									$icon_single .= $icon_img_ic;
									$icon_single .= $number_markup;
								$icon_single .= '</div>';	
								
								$icon_single .= '<div class="icn-content" '.$footer_css.'>';
									$icon_single .= $subject_markup;
									$icon_single .= $subject_markup1;
								$icon_single .= '</div>';
							$icon_single .= '</div>';
						}elseif($icn_style == 'style_3'){
							$icon_single .='<div class="ts-milestone-'.esc_attr($rand_no).' '.esc_attr($alignment_no).' '.esc_attr($extra_class).'" >';
								$icon_single .= '<div class="icn-top service-media">';
									$icon_single .= $icon_img_ic;
									$icon_single .= '<div class="content-center service-content">';
										$icon_single .= $number_markup;
									$icon_single .= '</div>';	
								$icon_single .= '</div>';	
								
								$icon_single .= '<div class="icn-bottom">';
									
									$icon_single .= $subject_markup;
									$icon_single .= $subject_markup1;
								$icon_single .= '</div>';
							$icon_single .= '</div>';
						}elseif($icn_style == 'style_4'){
							$icon_single .='<div class="ts-milestone-'.esc_attr($rand_no).' '.esc_attr($alignment_no).' '.esc_attr($extra_class).'" >';
								$icon_single .= '<div class="icn-top service-media">';
									$icon_single .= $icon_img_ic;
									$icon_single .= '<div class="content-center service-content">';
										$icon_single .= $number_markup;
										$icon_single .= $subject_markup;
										$icon_single .= $subject_markup1;
									$icon_single .= '</div>';	
								$icon_single .= '</div>';
							$icon_single .= '</div>';
						}else{
							$icon_single .='<div class="ts-milestone-'.esc_attr($rand_no).' icn-txt '.esc_attr($alignment_no).' '.esc_attr($extra_class).'" >'.$number_markup.' '.$subject_markup.' '.$subject_markup1.' </div>';
						}
					$icon_single .= '</div>';
				}
								
				$uid=uniqid('counter');
				$icon_counter  = '<div class=" content_hover_effect " >';
				$icon_counter .='<div class="ts-icon-cunter service-icon-'.esc_attr($icn_style).'  ts-icon-cunter-'.esc_attr($rand_no).' '.esc_attr($alignment_no).' '.esc_attr($icn_style_class).' '.esc_attr($uid).' " data-id="'.esc_attr($uid).'" '.$data_attr.' >';
				$icon_counter .= '<div class="post-inner-loop">';
					$icon_counter .= $icon_single;	
				$icon_counter .='</div>';	
				$icon_counter .='</div>';
				$icon_counter .='</div>';
				
				$css_rule='';
				$css_rule .= '<style type="text/css">';
				$css_rule .= '.'.esc_js($uid).'.ts-icon-cunter .icon-counte-inner:hover{background : '.esc_js($bg_hv_clr_ctr).' !important;}.'.esc_js($uid).'.ts-icon-cunter .icon-counte-inner:hover .counter-icon {color : '.esc_js($icon_2custom_hover_color).' !important;}.'.esc_js($uid).'.ts-icon-cunter .icon-counte-inner:hover .sub-subject-color{color: '.esc_js($sub_title_hv_clr).' !important;}.'.esc_js($uid).'.ts-icon-cunter .icon-counte-inner:hover .subject-color{color: '.esc_js($title_hv_color).' !important;}.'.esc_js($uid).'.ts-icon-cunter .icon-counte-inner:hover .counter-number{color: '.esc_js($style_hover_color).' !important;}';
				$css_rule .= '</style>';
			return $css_rule.$icon_counter;
		}
		function init_tp_icon_counter(){
			if(function_exists("vc_map"))
			{
			require(THEPLUS_PLUGIN_PATH.'/vc_elements/vc_param/vc_arrays.php');
				vc_map( array(
					  "name" => __( "Icon Counter", "pt_theplus" ),
					  "base" => "tp_icon_counter",
					  "icon" => "tp-icon-counter",
					  "category" => __( "The Plus", "pt_theplus"),
					  "description" => esc_html__('Show Numbers and Icons', 'pt_theplus'),
					  "params" => array(
					array(
						  "type"        => "dropdown",
						  'heading' =>  esc_html__('Select Layout', 'pt_theplus'), 
						  "param_name"  => "icn_layout",
						  "admin_label" => true,
						  "value"       => array(
						__( 'Individual Layout ', 'pt_theplus' ) => 'single_layout',
						__( 'Carousel Layout (premium)', 'pt_theplus' ) => 'carousel_layout',
						  ),
						  "std" => 'single_layout',
						  "description" => '',
						   ),
				array(
					'type'        => 'radio_select_image',
					'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Icon Counter Styles using this option.','pt_theplus').'</span></span>'.esc_html__('Styles', 'pt_theplus')), 
					'param_name'  => 'icn_style',
					'simple_mode' => false,
					'value'  => 'style_1',
					'options'     => array(
					 'style_1' => array(
					  'tooltip' => esc_attr__('Style 1','pt_theplus'),
					  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/icon-counter/ts-counter-style-1.jpg'
					 ),
					 'style_2' => array(
					  'tooltip' => esc_attr__('Style 2','pt_theplus'),
					  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/icon-counter/ts-counter-style-2.jpg'
					 ),
					 'style_3' => array(
					  'tooltip' => esc_attr__('Style 3','pt_theplus'),
					  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/icon-counter/ts-counter-style-3.jpg'
					 ),
					 'style_4' => array(
					  'tooltip' => esc_attr__('Style 4','pt_theplus'),
					  'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/icon-counter/ts-counter-style-4.jpg'
					 ),
					),
				   ),	
				  array(
					  "type" => "textfield",
					  'heading' =>  esc_html__('Title', 'pt_theplus'), 
					  "param_name" => "subject",
					  "value" => "Title",
					  "admin_label" => true,
						"dependency" => Array('element' => "icn_layout", 'value' => 'single_layout'),
					  "description" => ""
					),
				  array(
					  "type" => "textfield",
					  'heading' =>  esc_html__('Sub Title', 'pt_theplus'), 
					  "param_name" => "sub_subject",		
					  "admin_label" => true,
					  "dependency" => Array('element' => "icn_layout", 'value' => 'single_layout'),
					  "description" => ""
					),	
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Enter value of digits/numbers you want to showcase in icon counter. e.g. 200,300.','pt_theplus').'</span></span>'.esc_html__('Digits', 'pt_theplus')), 
					  "param_name" => "number",
					  "value" =>'99',
					  "dependency" => Array('element' => "icn_layout", 'value' => 'single_layout'),
					  "description" => ""
					),
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add any value in this option which will be setup as prefix or postfix on Digits. e.g. +,%,etc.','pt_theplus').'</span></span>'.esc_html__('Symbol Meta Value', 'pt_theplus')), 
					  "param_name" => "symbol",
					  "dependency" => Array('element' => "icn_layout", 'value' => 'single_layout'),
					  "description" => ""
					),
					 array(
					  "type" => "dropdown",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Symbol position using this option.','pt_theplus').'</span></span>'.esc_html__('Symbol Position', 'pt_theplus')),
					  "param_name" => "symbol_position",
					  "value" => array(
						 esc_attr__("After Number", 'pt_theplus') => "after",
						 esc_attr__("Before Number", 'pt_theplus') => "before",
					   ),
					  "description" => "",
					 
					  "dependency" => Array('element' => "icn_layout", 'value' => 'single_layout'),
					   "dependency" => Array('element' => "symbol", 'not_empty' => true),
					),
					
							array(
								'type' => 'dropdown',
								'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select Icon, Custom Image or SVG using this option.','pt_theplus').'</span></span>'.esc_html__('Select Icon ', 'pt_theplus')),
								'param_name' => 'icon_imge',
								'value' => array(
									__( 'None', 'pt_theplus' ) => '',
									__( 'Icon', 'pt_theplus' ) => 'icon_',
									__( 'Image', 'pt_theplus' ) => 'image_',
									__( 'Svg (premium)', 'pt_theplus' ) => 'svg',
								),
								'std' => 'icon_',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
						
						array(
									"type" => "attach_image",
									"heading" => esc_html__("Use Image As icon", 'pt_theplus') ,
										"param_name" => "imge",
										"value" => "",
										"description" => '',  
								'param_name' => 'icon_image',
								'dependency' => array(
									'element' => 'icon_imge',
									'value' => 'image_',
								),
								'group' => __( 'Icon Option', 'pt_theplus' ),
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
									'element' => 'icon_imge',
									'value' => 'icon_',
								),
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
							array(
								'type' => 'iconpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
								'param_name' => 'icon_fontawesome',
								'value' => 'fa fa-adjust', // default value to backend editor admin_label
								'settings' => array(
									'emptyIcon' => false,
									// default true, display an "EMPTY" icon?
									'iconsPerPage' => 4000,
									// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
								),
								'dependency' => array(
									'element' => 'type',
									'value' => 'fontawesome',
								),
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
							array(
								'type' => 'iconpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
								'param_name' => 'icon_openiconic',
								'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
								'settings' => array(
									'emptyIcon' => false, // default true, display an "EMPTY" icon?
									'type' => 'openiconic',
									'iconsPerPage' => 4000, // default 100, how many icons per/page to display
								),
								'dependency' => array(
									'element' => 'type',
									'value' => 'openiconic',
								),
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
							array(
								'type' => 'iconpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
								'param_name' => 'icon_typicons',
								'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
								'settings' => array(
									'emptyIcon' => false, // default true, display an "EMPTY" icon?
									'type' => 'typicons',
									'iconsPerPage' => 4000, // default 100, how many icons per/page to display
								),
								'dependency' => array(
									'element' => 'type',
									'value' => 'typicons',
								),
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
							array(
								'type' => 'iconpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
								'param_name' => 'icon_entypo',
								'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
								'settings' => array(
									'emptyIcon' => false, // default true, display an "EMPTY" icon?
									'type' => 'entypo',
									'iconsPerPage' => 4000, // default 100, how many icons per/page to display
								),
								'dependency' => array(
									'element' => 'type',
									'value' => 'entypo',
								),
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
							array(
								'type' => 'iconpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
								'param_name' => 'icon_linecons',
								'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
								'settings' => array(
									'emptyIcon' => false, // default true, display an "EMPTY" icon?
									'type' => 'linecons',
									'iconsPerPage' => 4000, // default 100, how many icons per/page to display
								),
								'dependency' => array(
									'element' => 'type',
									'value' => 'linecons',
								),
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
							array(
								'type' => 'iconpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
								'param_name' => 'icon_monosocial',
								'value' => 'vc-mono vc-mono-fivehundredpx', // default value to backend editor admin_label
								'settings' => array(
									'emptyIcon' => false, // default true, display an "EMPTY" icon?
									'type' => 'monosocial',
									'iconsPerPage' => 4000, // default 100, how many icons per/page to display
								),
								'dependency' => array(
									'element' => 'type',
									'value' => 'monosocial',
								),
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),
							array(
								'type' => 'colorpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Color', 'pt_theplus')),
								'param_name' => 'icon_2custom_color',
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
								'dependency' => array(
									'element' => 'icon_imge',
									'value' => 'icon_',
								),
							),
							array(
								'type' => 'colorpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Hover Color', 'pt_theplus')),
								'param_name' => 'icon_2custom_hover_color',
								'value' =>'#000',
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
								'dependency' => array(
									'element' => 'icon_imge',
									'value' => 'icon_',
								),
							),
							array(
								'type' => 'dropdown',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select Icon Size for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Size', 'pt_theplus')),
								'param_name' => 'icon_font_size',
								'value' => array( 'Min' => '1',
												  'Small' => '1.6',
												  'Normal' => '2.15',
												  'Large' => '2.85',
												  'Extra Large' => '5',
								) ,
								
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
								'dependency' => array(
									'element' => 'icon_imge',
									'value' => 'icon_',
								),
							),
							array(
								'type' => 'dropdown',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select Box Alignment for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Box Alignment', 'pt_theplus')),
								'param_name' => 'icon_align',
								'value' => array(
									__( 'Left', 'pt_theplus' ) => 'left',
									__( 'Right', 'pt_theplus' ) => 'right',
									__( 'Center', 'pt_theplus' ) => 'center',
								),
								'description' => '',		
							),
							array(
								'type' => 'vc_link',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Add Icon Counter URL, Link Open Option and Follow-No Follow Option from this option.','pt_theplus').'</span></span>'.esc_html__('Icon Counter URL', 'pt_theplus')),
								'param_name' => 'icon_link',
								'description' => '',
								'group' => __( 'Icon Option', 'pt_theplus' ),
							),

					array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Digits Options', 'pt_theplus'),
							'param_name'		=> 'letter_option',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
								  "group" =>'Style',
						), 
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')), 
					  "param_name" => "no_size_tag",
					  "value" =>"30px",
					  "description" => '',
					   "group" => "Style",
					   "edit_field_class" => "vc_col-xs-6",
					),
					
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
					  "param_name" => "no_line",
					  "value" => "1",
					  "description" => '',
					   "group" => "Style",
					   "edit_field_class" => "vc_col-xs-6",
					),
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Letter Spacing in Pixels using this Option. E.g. 1px, 2px, etc.','pt_theplus').'</span></span>'.esc_html__('Letter Spacing', 'pt_theplus')),
					  "param_name" => "no_letter",
					  "value" => "1px",
					  "description" => '',
					   "group" => "Style",
					   "edit_field_class" => "vc_col-xs-4",
					),  
					
						 array(
							"type" => "colorpicker",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							"param_name" => "style_color",
							"value" => "",
							"group" => "Style",
							"description" => "" ,
							   "edit_field_class" => "vc_col-xs-4",
						),
						array(
							"type" => "colorpicker",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Hover Color', 'pt_theplus')),
							"param_name" => "style_hover_color",
							"value" => "",
							"group" => "Style",
							   "edit_field_class" => "vc_col-xs-4",
							"description" => ""  
						),
						array(
								'type' => 'dropdown',
								'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Digit Custom font family', 'pt_theplus'),
								'param_name' => 'digit_use_theme_fonts',
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
							'param_name' => 'digit_font_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'digit_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font weight using this Option. E.g. 200,400,700,900 etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
							'param_name' => 'digit_font_weight',
							'value' => __('400','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Style', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'digit_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
					
					array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Title Options', 'pt_theplus'),
							'param_name'		=> 'title_option',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
								  "group" =>'Style',
						), 
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')), 
					  "param_name" => "title_size",
					  "value" =>"30px",
					  "description" => '',
					   "group" => "Style",
					   "edit_field_class" => "vc_col-xs-6",
					),
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
					  "param_name" => "title_line",
					  "value" => "1",
					  "description" => '',
					   "group" => "Style",
					   "edit_field_class" => "vc_col-xs-6",
					),
					 array(
							"type" => "colorpicker",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							"param_name" => "sub_style_color",
							"value" => "",
							"group" => "Style",
							"edit_field_class" => "vc_col-xs-4",
							"description" => ""  
						),
						 array(
							"type" => "colorpicker",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Hover Color', 'pt_theplus')),
							"param_name" => "title_hv_color",
							"value" => "",
							"edit_field_class" => "vc_col-xs-4",
							"group" => "Style",
							"description" => ""  
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
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Sub Title Options', 'pt_theplus'),
							'param_name'		=> 'subtitle_option',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
								  "group" =>'Style',
						),
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
					  "param_name" => "subtitle_size",
					  "value" =>"22px",
					  "description" => '',
					   "group" => "Style",
					   "edit_field_class" => "vc_col-xs-6",
					),
					
					array(
					  "type" => "textfield",
					  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
					  "param_name" => "subtitle_line",
					  "value" => "1",
					  "description" => '',
					   "group" => "Style",
					   "edit_field_class" => "vc_col-xs-6",
					),	
						
							 array(
							"type" => "colorpicker",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Color', 'pt_theplus')),
							"param_name" => "sub_title_clr",
							"value" => "",
							"edit_field_class" => "vc_col-xs-4",
							"description" => '',
							"group" => "Style",
						),

					 array(
							"type" => "colorpicker",
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for font using this option.','pt_theplus').'</span></span>'.esc_html__('Font Hover Color', 'pt_theplus')),
							"param_name" => "sub_title_hv_clr",
							"value" => "",
							"edit_field_class" => "vc_col-xs-4",
							"description" => '',
							"group" => "Style",
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
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Background Options', 'pt_theplus'),
							'param_name'		=> 'background_option',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
								  "group" =>'Style',
						), 
						array(
								'type' => 'colorpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for background using this option.','pt_theplus').'</span></span>'.esc_html__('Background Color', 'pt_theplus')),
								'param_name' => 'background_color_counter',
								'description' => '',	
								'group' => __( 'Style', 'pt_theplus' ),
								"edit_field_class" => "vc_col-xs-6",
							),	
						array(
								'type' => 'colorpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for background hover using this option.','pt_theplus').'</span></span>'.esc_html__('Background Hover Color', 'pt_theplus')),
								'param_name' => 'bg_hv_clr_ctr',
								"edit_field_class" => "vc_col-xs-6",
								'description' => '',	
								'group' => __( 'Style', 'pt_theplus' ),
							),
						 array(
								  "type"        => "checkbox",
								  "heading"     => __("Box Border" , "pt_theplus"),
								  "param_name"  => "box_border",
								  "edit_field_class" => "vc_col-xs-6",
								  "admin_label" => false,
								  "value"       => array(
										'True' => 'true',
									),
									'dependency' => array(
										'element' => 'icn_style',
										'value' => 'style_1',
									),
								  "description" => '',
								  "group" => "Style",
						),	
						array(
								'type' => 'colorpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for Box Borde using this option.','pt_theplus').'</span></span>'.esc_html__('Box Borde Color', 'pt_theplus')),
								'param_name' => 'box_border_clr',
								'value'=>'#4d4d4d',
								"edit_field_class" => "vc_col-xs-6",
								'description' => '',	
								'group' => __( 'Style', 'pt_theplus' ),
								'dependency' => array(
										'element' => 'box_border',
										'value' => 'true',
									),
							),
							
						array(
								'type' => 'textfield',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can choose radius for border using this option. E.g. 1px, 2px, etc.','pt_theplus').'</span></span>'.esc_html__('Box Borde Radius', 'pt_theplus')),
								'param_name' => 'bd_rad',
								'description' => '',	
								'group' => __( 'Style', 'pt_theplus' ),
								"edit_field_class" => "vc_col-xs-6",
							),
							array(
						'type' => 'pt_theplus_heading_param',
						'text' => esc_html__('Seprator Settings', 'pt_theplus'),
						'param_name' => 'sep_effect',
						'dependency' => array(
										'element' => 'icn_style',
										'value' => 'style_1',
									),
						'group' => __( 'Style', 'pt_theplus' ),
						'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
						),	
						array(
							'param_name'  => 'border_width',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Seprator Width using this option.','pt_theplus').'</span></span>'.esc_html__('Seprator Width', 'pt_theplus')),
							'description' => '',
							'type'        => 'dropdown',
							'group' => __( 'Style', 'pt_theplus' ),
							"edit_field_class" => "vc_col-xs-4",
							"value"       => array(
								__( 'Select width', 'pt_theplus' ) => '',
								__( '10%', 'pt_theplus' ) => '10%',
								__( '20%', 'pt_theplus' ) => '20%',
								__( '30%', 'pt_theplus' ) => '30%',
								__( '40%', 'pt_theplus' ) => '40%',
								__( '50%', 'pt_theplus' ) => '50%',
								__( '60%', 'pt_theplus' ) => '60%',
								__( '70%', 'pt_theplus' ) => '70%',
								__( '80%', 'pt_theplus' ) => '80%',
								__( '90%', 'pt_theplus' ) => '90%',
								__( '100%', 'pt_theplus' ) => '100%',
							),
							'std' => '10%',
							'dependency' => array(
										'element' => 'icn_style',
										'value' => 'style_1',
									),
						),	
						array(
								'type' => 'textfield',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Seprator height using this option. E.g. 1px, 2px, 3px, etc.','pt_theplus').'</span></span>'.esc_html__('Seprator height', 'pt_theplus')),
								'heading' => __( 'Seprator height ', 'pt_theplus' ),
								'param_name' => 'bd_height',
								"value" => '2px',
								'group' => __( 'Style', 'pt_theplus' ),
								"edit_field_class" => "vc_col-xs-4",
								'dependency' => array(
										'element' => 'icn_style',
										'value' => 'style_1',
								),
							),
						array(
								'type' => 'colorpicker',
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for Seprator using this option.','pt_theplus').'</span></span>'.esc_html__('Seprator Color', 'pt_theplus')),
								'param_name' => 'bd_clr',
								"edit_field_class" => "vc_col-xs-4",
								'value'=>'#4d4d4d',				
								'dependency' => array(
										'element' => 'icn_style',
										'value' => 'style_1',
									),
								'description' => '',	
								'group' => __( 'Style', 'pt_theplus' ),
							),	
						array(
								'type' => 'colorpicker',
								'heading' => __( 'Content background Color', 'pt_theplus' ),
								'param_name' => 'cont_bg',
								'value'=>'#F9B701',
								"edit_field_class" => "vc_col-xs-6",
								'description' => '',	
								'group' => __( 'Style', 'pt_theplus' ),
								'dependency' => array(
										'element' => 'icn_style',
										'value' => 'style_2',
									),
						),	
						array(
							'type'				=> 'pt_theplus_heading_param',
							'text'				=> esc_html__('Box Shadow Setting', 'pt_theplus'),
							'param_name'		=> 'boxshadow_setting',
							'edit_field_class'	=> 'pt_theplus-heading-param-style vc_col-sm-12',
							'group' => esc_attr__('Style', 'pt_theplus'), 
							"class" =>'pt_plus_disabled',
							'premium'=>'Premium',
						),	
						array(
								'type' => 'textfield',
								"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Box Shadow ', 'pt_theplus')),
								'param_name' => 'box_shadow',
								"value" => '-1px 1px 3px 3px #c6c6c6',
								'description' => '',	
								'group' => __( 'Style', 'pt_theplus' ),
								"edit_field_class" => "vc_col-xs-6",
								"class" =>'pt_plus_disabled',
							),
						
							array(
							'type' => 'textfield',
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can set Box Shadow Value here with all options. E.g. 0px 1px 7px 0 outset/inset #212121','pt_theplus').'</br><a target="_blank" class="tootip-link" href="https://www.cssmatic.com/box-shadow">'.esc_html__(' Check link','pt_theplus').'</a></span></span>'.esc_html__('Hover Box Shadow ', 'pt_theplus')),
							'param_name' => 'hover_box_shadow',
							"value" => '9px 5px 20px 4px #c6c6c6',
							'description' => '',	
							'group' => __( 'Style', 'pt_theplus' ),
							"edit_field_class" => "vc_col-xs-6",
							"class" =>'pt_plus_disabled',
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
								"param_name" => "extra_class",
								"value" => '',
								"description" => "",
						),
						array(
						'type' => 'pt_theplus_heading_param',
						'text' => esc_html__('Animation Settings', 'pt_theplus'),
						'param_name' => 'annimation_effect',
						'premium'=>'Premium',
						"class" =>'pt_plus_disabled',
						'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
					),
								 array(
							"type" => "dropdown",
							"heading" =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('This Effects will be applied when you hover on this section.','pt_theplus').'</span></span>'.esc_html__('Content Hover Effects (premium)', 'pt_theplus')),
							"param_name" => "content_hover_effects",
							"class" =>'pt_plus_opacity',
							"value" => array(
								__('Select Hover Effect', 'pt_theplus') => '',
								__('Grow', 'pt_theplus') => 'grow',
								__('Push', 'pt_theplus') => 'push',
								__('Bounce In', 'pt_theplus') => 'bounce-in',
								__('Float', 'pt_theplus') => 'float',
								__('wobble horizontal', 'pt_theplus') => 'wobble_horizontal',
								__('Wobble Vertical', 'pt_theplus') => 'wobble_vertical',
								__('Float Shadow', 'pt_theplus') => 'float_shadow',
								__('Grow Shadow', 'pt_theplus') => 'grow_shadow',
								__('Shadow Radial', 'pt_theplus') => 'shadow_radial'
							),
							"description" => '',
						),
						array(
							'type' => 'colorpicker',
							'heading' => __('Shadow Color', 'pt_theplus'),
							'param_name' => 'hover_shadow_color',
							"class" =>'pt_plus_opacity',
							'value' => 'rgba(0, 0, 0, 0.6)',
							'edit_field_class' => 'vc_col-sm-6',
							'description' => '',
							'dependency' => array(
								'element' => 'content_hover_effects',
								'value' => array(
									'float_shadow',
									'grow_shadow',
									'shadow_radial'
								)
							)
						),
						
						 array(
							  "type"        => "dropdown",
							  "heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Choose Animation Effect When This Element will be load on scroll. It have many modern options for you to choose from. ','pt_theplus').'</span></span>'.esc_html__('Choose Animation Effect', 'pt_theplus')),
							  "param_name"  => "animation_effects",
							  "class" =>'pt_plus_opacity',
							'edit_field_class' => 'vc_col-sm-6',
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
							  'std' =>'no-animation',
						),		
						array(
							  "type"        => "textfield",
							  "heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Add value of delay in transition on scroll in millisecond. 1 sec = 1000 Millisecond ','pt_theplus').'</span></span>'.esc_html__('Animation Delay', 'pt_theplus')),
							  "param_name"  => "animation_delay",
							  "class" =>'pt_plus_disabled',
							  "value"       => '50',
							'edit_field_class' => 'vc_col-sm-6',
							  "description" => "",
						),
						
						array(
							'type' => 'pt_theplus_checkbox',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Turn Off/On whole Meta Section of Blog Post using this option.','pt_theplus').'</span></span>'.esc_html__('Desktop Hide', 'pt_theplus')),
							'param_name' => 'desktop_hide',
							"class" =>'pt_plus_disabled',
							'value' => 'off',
							'options' => array(
								'on' => array(
									'label' => '',
									'on' => 'Yes',
									'off' => 'No',
							),
							),
							"edit_field_class" => "vc_col-xs-4",
						),
						array(
							'type' => 'pt_theplus_checkbox',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Turn Off/On whole Meta Section of Blog Post using this option.','pt_theplus').'</span></span>'.esc_html__('Tablet Hide', 'pt_theplus')),
							'param_name' => 'tablet_hide',
							"class" =>'pt_plus_disabled',
							'value' => 'off',
							'options' => array(
								'on' => array(
									'label' => '',
									'on' => 'Yes',
									'off' => 'No',
							),
							),
							"edit_field_class" => "vc_col-xs-4",
						),
						array(
							'type' => 'pt_theplus_checkbox',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Turn Off/On whole Meta Section of Blog Post using this option.','pt_theplus').'</span></span>'.esc_html__('Mobile Hide', 'pt_theplus')),
							'param_name' => 'mobile_hide',
							"class" =>'pt_plus_disabled',
							'value' => 'off',
							'options' => array(
								'on' => array(
									'label' => '',
									'on' => 'Yes',
									'off' => 'No',
							),
							),
							"edit_field_class" => "vc_col-xs-4",
						),			
					)
				));
			}
		}
	}
	new ThePlus_icon_counter;

	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_tp_icon_counter'))
	{
		class WPBakeryShortCode_tp_icon_counter extends WPBakeryShortCode {
			protected function contentInline( $atts, $content = null ) {
			 
			}
		}
	}
}