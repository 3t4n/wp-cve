<?php 
// Stylish List Elements
if(!class_exists("ThePlus_stylish_list")){
	class ThePlus_stylish_list{
		function __construct(){
			add_action( 'init', array($this, 'init_tp_stylish_list') );
			add_shortcode( 'tp_stylish_list',array($this,'tp_stylish_list_shortcode'));
		}
		function tp_stylish_list_shortcode($atts,$content = null){
			extract( shortcode_atts( array(
				  'el_class' =>'',
				  'vertical_center' =>'',
				  'stylish_content' =>'',
				  
				), $atts ) );
				
				$list_title = $description= $list_img =  $list_center  = $m_l='';
				if($vertical_center == 'true'){
					$list_center = 'vertical-center';
				}
				
				$stylish_loop='';
				if(isset($stylish_content) && !empty($stylish_content) && function_exists('vc_param_group_parse_atts')) {
					$stylish_content= (array) vc_param_group_parse_atts( $stylish_content);
					
					foreach($stylish_content as $item) {
					
						$title_color=$title=$title_line=$title_size=$title_color=$image_icon=$icon_size=$icon_color=$icon_line='';

						if(!empty($item['title'])){
							$title= $item['title'];

								$title_css = ' style="';
									if(!empty($item['title_color'])) {
									$title_color= $item['title_color'];
										$title_css .= 'color: '.esc_attr($title_color).';';
									}	
									if(!empty($item['title_size'])){
										$title_size= $item['title_size'];
										if($title_size != "") {
											$title_css .= 'font-size: '.esc_attr($title_size).';';
										}
									}
									$title_use_theme_fonts='custom-font-family';
									$title_font_family='';
									if(!empty($item['title_use_theme_fonts'])){
										$title_use_theme_fonts= $item['title_use_theme_fonts'];
										if($title_use_theme_fonts=='custom-font-family'){
											if(!empty($item['title_font_family'])){
												$title_font_family .='font-family:'.$item['title_font_family'].';';
											}
											if(!empty($item['title_font_weight'])){
												$title_font_family .='font-weight:'.$item['title_font_weight'].';';
											}
										}else{
											$title_font_family ='';
										}
									}
									$title_css .= $title_font_family;
									if(!empty($item['title_line'])){
										$title_line= $item['title_line'];
										if($title_line != "") {
											$title_css .='line-height:'.esc_attr($title_line).';';
										}
									}									
									$title_css .= '";';				
									$list_title = '<div class="stylish-title" '.$title_css.'> '.html_entity_decode($title).' </div>';
						}
						if(!empty($item['image_icon'])){
							
							
							$type= $item['type'];
							
							$icon_fontawesome= $item['icon_fontawesome'];
							$icon_openiconic= $item['icon_openiconic'];
							$icon_typicons= $item['icon_typicons'];
							$icon_entypo= $item['icon_entypo'];
							$icon_linecons= $item['icon_linecons'];
							$icon_monosocial= $item['icon_monosocial'];
							
							
							if(!empty($item['icon_size'])){
								$icon_size= $item['icon_size'];	
							}
							if(!empty($item['icon_color'])){
								$icon_color= $item['icon_color'];	
							}
							if(!empty($item['icon_line'])){
								$icon_line= $item['icon_line'];	
							}
							
							
							if(isset($item['image_icon']) && $item['image_icon'] != ''){
								$m_l = 'm-l-10';
							}
				
								if(isset($item['image_icon']) && $item['image_icon'] == 'icon'){		
									$icon_css = ' style="';
									if($icon_color != "") {
									$icon_css .= 'color: '.esc_attr($icon_color).';';
									}	
									if($icon_size != "") {
									$icon_css .= 'font-size: '.esc_attr($icon_size).';';
									}
									if($icon_line != "") {
									$icon_css .= 'line-height: '.esc_attr($icon_line).';';
									}
									
									$icon_css .= '";'; 
									vc_icon_element_fonts_enqueue( $type );
									$type12= $type; 
									$icon_class = isset( ${'icon_' . $type} ) ? esc_attr( ${'icon_' . $type} ) : 'fa fa-adjust';
									$list_img = '<i class=" '.esc_attr($icon_class).' stylish-icon" '.$icon_css.'></i>';
								}
								
						}
						
						
						
						
						$stylish_loop .='<div class="stylish-item-wrap "  >
							<div class="service-media service-left '.esc_attr($list_center).'">
									'.$list_img.'
							<div class="service-content '.esc_attr($m_l).'">
									'.$list_title.'
							</div>
							</div>
						</div>';
					}
				}
				
				
				$uid=uniqid('stylish_list');
				
				$stylish_list ='<div class="pt_plus_stylish_list '.esc_attr($el_class).' '.esc_attr($uid).'"  data-uid="'.esc_attr($uid).'" >';
					$stylish_list .= $stylish_loop;				
				$stylish_list .='</div>';				
			return $stylish_list;
		}
		function init_tp_stylish_list(){
			if(function_exists("vc_map"))
			{
		require(THEPLUS_PLUGIN_PATH.'vc_elements/vc_param/vc_arrays.php');
				vc_map(array(
					"name" => __("Stylish List", "pt_theplus"),
					"base" => "tp_stylish_list",
					'icon'	=> 'tp-stylish-list',
					"description" => esc_html__('Smart List for your Data', 'pt_theplus'),
					"category" => __("The Plus", "pt_theplus"),
					"params" => array(
						
						array(
							'type' => 'param_group',
							'heading' => esc_html__('Content', 'pt_theplus'),
							'param_name' => 'stylish_content',
							'description' => '',
							'params' => array(
								
								array(
									"type" => "textarea",
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add title of stylish list using this option.','pt_theplus').'</span></span>'.esc_html__('Title', 'pt_theplus')), 
									"param_name" => "title",
									"value" => 'The Plus',
									'admin_label' => true,
									"description" => ""
								),
								array(
									"type" => "colorpicker",
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for title using this option','pt_theplus').'</span></span>'.esc_html__('Title Color', 'pt_theplus')), 
									"param_name" => "title_color",
									"value" => '#0099CB',
									"description" => ""
								),
								array(
									"type" => "textfield",
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Title Size', 'pt_theplus')),
									"param_name" => "title_size",
									"value" => '24px',
									"edit_field_class" => "vc_col-xs-6",
									"description" => ""
								),                
								array(
									"type" => "textfield",
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Title Line height', 'pt_theplus')),
									"param_name" => "title_line",
									'value' => '1.2',
									"description" => __(" ", 'pt_theplus'),
									"edit_field_class" => "vc_col-xs-6"
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
								'group' => esc_attr__('Styling', 'pt_theplus'),	
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
							'param_name' => 'title_font_family',
							'value' => "",
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styling', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'title_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
						array(
							'type' => 'textfield',
							'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font weight using this Option. E.g. 200,400,700,900 etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
							'param_name' => 'title_font_weight',
							'value' => __('400','pt_theplus'),
							'edit_field_class' => 'vc_col-xs-6',
							'description' => '',
							'group' => esc_attr__('Styling', 'pt_theplus'),	
							'dependency' => array(
									'element' => 'title_use_theme_fonts',
									'value' => 'custom-font-family',
								),
						),
								array(
									"type" => "dropdown",
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select Icon/Image/Svg as a Symbol of Pin Start from these options.','pt_theplus').'</span></span>'.esc_html__('Icon Option', 'pt_theplus')),
									"param_name" => "image_icon",
									"value" => array(
										__('Select Image or Icon or SVG', 'pt_theplus') => '',
										__('Icon', 'pt_theplus') => 'icon',
										__('Image (Premium)', 'pt_theplus') => 'image',
										__('Svg (Premium)', 'pt_theplus') => 'svg'
									),
									"std" => ""
								),
								
								array(
									'type' => 'dropdown',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' We have given options of icons from Font Awesome, Open Iconic, Linecons, Typicons, Entypo, and Mono Social.','pt_theplus').'</span></span>'.esc_html__('Icon Library', 'pt_theplus')),
									'value' => array(
										__('Font Awesome', 'pt_theplus') => 'fontawesome',
										__('Open Iconic', 'pt_theplus') => 'openiconic',
										__('Typicons', 'pt_theplus') => 'typicons',
										__('Entypo', 'pt_theplus') => 'entypo',
										__('Linecons', 'pt_theplus') => 'linecons',
										__('Mono Social', 'pt_theplus') => 'monosocial'
									),
									'admin_label' => false,
									'param_name' => 'type',
									'dependency' => array(
										'element' => 'image_icon',
										'value' => 'icon'
									),
									'description' => ""
								),
								array(
									'type' => 'iconpicker',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
									'param_name' => 'icon_fontawesome',
									'value' => 'fa fa-adjust',
									'settings' => array(
										'emptyIcon' => false,
										'iconsPerPage' => 100
									),
									'dependency' => array(
										'element' => 'type',
										'value' => 'fontawesome'
									),
									'description' => ""
								),
								array(
									'type' => 'iconpicker',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
									'param_name' => 'icon_openiconic',
									'value' => 'vc-oi vc-oi-dial',
									'settings' => array(
										'emptyIcon' => false,
										'type' => 'openiconic',
										'iconsPerPage' => 100
									),
									'dependency' => array(
										'element' => 'type',
										'value' => 'openiconic'
									),
									'description' => ""
								),
								array(
									'type' => 'iconpicker',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
									'param_name' => 'icon_typicons',
									'value' => 'typcn typcn-adjust-brightness',
									'settings' => array(
										'emptyIcon' => false,
										'type' => 'typicons',
										'iconsPerPage' => 100
									),
									'dependency' => array(
										'element' => 'type',
										'value' => 'typicons'
									),
									'description' => ""
								),
								array(
									'type' => 'iconpicker',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
									'param_name' => 'icon_entypo',
									'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
									'settings' => array(
										'emptyIcon' => false, // default true, display an "EMPTY" icon?
										'type' => 'entypo',
										'iconsPerPage' => 100 // default 100, how many icons per/page to display
									),
									'dependency' => array(
										'element' => 'type',
										'value' => 'entypo'
									)
								),
								array(
									'type' => 'iconpicker',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
									'param_name' => 'icon_linecons',
									'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
									'settings' => array(
										'emptyIcon' => false, // default true, display an "EMPTY" icon?
										'type' => 'linecons',
										'iconsPerPage' => 100 // default 100, how many icons per/page to display
									),
									'dependency' => array(
										'element' => 'type',
										'value' => 'linecons'
									),
									'description' => ""
								),
								array(
									'type' => 'iconpicker',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Your selected icon from selected Icon Library.','pt_theplus').'</span></span>'.esc_html__('Icon', 'pt_theplus')),
									'param_name' => 'icon_monosocial',
									'value' => 'vc-mono vc-mono-fivehundredpx', // default value to backend editor admin_label
									'settings' => array(
										'emptyIcon' => false, // default true, display an "EMPTY" icon?
										'type' => 'monosocial',
										'iconsPerPage' => 100 // default 100, how many icons per/page to display
									),
									'dependency' => array(
										'element' => 'type',
										'value' => 'monosocial'
									),
									'description' => ""
								),
								array(
									'type' => 'textfield',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add icon size in Pixels using this option. E.g. 14px, 20px, etc','pt_theplus').'</span></span>'.esc_html__('Icon Size', 'pt_theplus')),
									'param_name' => 'icon_size',
									'value' => '20px',
									'description' => "",
									'dependency' => array(
										'element' => 'image_icon',
										'value' => 'icon'
									)
								),
								array(
									'type' => 'colorpicker',
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for icon using this option.','pt_theplus').'</span></span>'.esc_html__('Icon Color', 'pt_theplus')),
									'param_name' => 'icon_color',
									'value' => '#0099CB',
									'description' => "",
									'dependency' => array(
										'element' => 'image_icon',
										'value' => 'icon'
									)
								),
								array(
									"type" => "textfield",
									'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Icon Line Height', 'pt_theplus')),
									"param_name" => "icon_line",
									'value' => '1.2',
									"description" => __(" ", 'pt_theplus'),
									"edit_field_class" => "vc_col-xs-6",
									'dependency' => array(
										'element' => 'image_icon',
										'value' => 'icon'
									)
								),
							)
						),
						array(
						'type' => 'pt_theplus_heading_param',
						'text' => esc_html__('Extra Settings', 'pt_theplus'),
						'param_name' => 'extra_effect',
						'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
						),	
						array(
							'param_name' => 'vertical_center',
							'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' By checking this option You can make stylish list content vertical center.','pt_theplus').'</span></span>'.esc_html__('Vertical Center', 'pt_theplus')), 
							'description' => '',
							'type' => 'checkbox',
							  "edit_field_class" => "vc_col-xs-6",
							'value' => array(
								'Vertical Center' => 'true'
							)
						),
						array(
							"type" => "textfield",
							  "edit_field_class" => "vc_col-xs-6",
							"class" => "",
							"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can add Extra Class here to use for Customisation Purpose.','pt_theplus').'</span></span>'.esc_html__('Extra Class', 'pt_theplus')),
							"param_name" => "el_class",
							"value" => '',
							"description" => ""
						)
					)
				));
			}
		}
	}
	new ThePlus_stylish_list;

	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_tp_stylish_list'))
	{
		class WPBakeryShortCode_tp_stylish_list extends WPBakeryShortCode
		{
			protected function contentInline($atts, $content = null)
			{
				
			}
		}
	}
}