<?php
// Heading Title Elements
if(!class_exists("ThePlus_heading_title")){
	class ThePlus_heading_title{
		function __construct(){
			add_action( 'init', array($this, 'init_tp_heading_title') );
			add_shortcode( 'tp_heading_title',array($this,'tp_heading_title_shortcode'));
		}
		function tp_heading_title_shortcode($atts,$content = null){
			extract( shortcode_atts( array(
				'title'			 => 'Heading',
				'title_s'		 => 'Title',
				'title_s_postion' => 'text_after',
				'sub_title'		 => 'Sub Title',
				'title_color_o' =>'solid',
				'title_color1' =>'#1e73be',
				'title_color2' =>'#2fcbce',
				'title_hover_style' =>'horizontal',
				'title_color'	 => '#ccc',

				'sub_color_o' =>'solid',
				'sub_color1' =>'#1e73be',
				'sub_color2' =>'#2fcbce',
				'sub_hover_style' =>'horizontal',
				'sub_color'		 => '#ccc',
				'heading_style'  =>'style_1',
				'title_align' 	 =>'text-center',
				'sub_align'		 =>'text-center',
				'position'		 => 'before',
				'title_s_color_o' =>'solid',
				'title_s_color1' =>'#1e73be',
				'title_s_color2' =>'#2fcbce',
				'title_s_hover_style' =>'horizontal',
				'title_s_color'   =>'#ca2b2b', 
				'sep_clr'  		  =>'#4099c3',
				'sep_width'		  =>'100%',
				'dot_color' =>'#ca2b2b',
				'double_color'   => '#4d4d4d',
				'double_top'      => '6px',
				'double_bottom'      => '2px',
				'title_h'         =>'h2',				
				'title_use_theme_fonts'=>'custom-font-family',
				'title_font_family'=>'',
				'title_font_weight'=>'600',
				
				
				'subtitle_font'  => 'h3',				
				'subtitle_use_theme_fonts'=>'custom-font-family',
				'subtitle_font_family'=>'',
				'subtitle_font_weight'=>'400',
				
								
				'title_s_size'   => '',
				'title_s_line'    =>'1.2',
				'title_s_use_theme_fonts'=>'custom-font-family',
				'title_s_font_family'=>'',
				'title_s_font_weight'=>'600',
				
				
				'animation_effects'=>'no-animation',
				'animation_delay'=>'50',
				'el_class' => '',
				'css' =>'',
		   ), $atts ) );
		   
		   $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,$el_class . vc_shortcode_custom_css_class( $css, ' ' ), 'tp_heading_title', $atts );
		  
		 
		if($title_use_theme_fonts=='custom-font-family'){
			$title_font_family='font-family:'.$title_font_family.';font-weight:'.$title_font_weight.';';
		}else{
			$title_font_family='';
		}

			$titlle = ' style="';
			$titlle .= 'color: '.esc_attr($title_color).';';
			$titlle .= $title_font_family;
			$titlle .= '"';
			
		if($title_s_use_theme_fonts=='custom-font-family'){
			$title_s_font_family='font-family:'.$title_s_font_family.';font-weight:'.$title_s_font_weight.';';
		}else{
			$title_s_font_family='';
		}
		$title_2 = ' style="';			
				$title_2 .= 'color: '.esc_attr($title_s_color).';';
			
			if($title_s_size != "") {
				$title_2 .='font-size:'.esc_attr($title_s_size).';';
			}
			if($title_s_line != "") {
				$title_2 .='line-height:'.esc_attr($title_s_line).';';
			}
			$title_2 .=$title_s_font_family;
			$title_2 .= '"';


		if($subtitle_use_theme_fonts=='custom-font-family'){
			$subtitle_font_family='font-family:'.$subtitle_font_family.';font-weight:'.$subtitle_font_weight.';';
		}else{
			$subtitle_font_family='';
		}
			$sub = ' style="';

				$sub .= 'color: '.esc_attr($sub_color).';';
			$sub .=$subtitle_font_family;
			$sub .= '"';
			
			
			
			$sep_style = ' style="';
			if($sep_clr != "") {
				$sep_style .='border-color:'.esc_attr($sep_clr).';';
			}
			if($sep_width != "") {
				$sep_style .='width:'.esc_attr($sep_width).';';
			}			
			$sep_style .= '"';
			
			$sep_width_st = ' style="';
			if($sep_width != "") {
				$sep_width_st .='width:'.esc_attr($sep_width).';';
			}
			$sep_width_st .= '"';
			

			$dot_color_b = ' style="';
			if($dot_color != "") {
				$dot_color_b .='color:'.esc_attr($dot_color).';';
			}
			$dot_color_b .= '"';
		$style_class='';
			if($heading_style =="style_1"){
				$style_class = 'style-1';
			}else if($heading_style =="style_2"){
				$style_class = 'style-2';
			}else if($heading_style =="style_5"){
				$style_class = 'style-5';
			}else if($heading_style =="style_9"){
				$style_class = 'style-9';
			}
			
			$uid=uniqid('heading_style');
			
			$heading ='<div class="heading heading_style '.esc_attr($uid).'  '.esc_attr($css_class).'  '.esc_attr($style_class).'">';
			
			
				$heading .='<div class="sub-style" >';

					$title_con= $s_title_con = $title_s_before ='';
					
					if($heading_style =="style_1" ){
									$title_s_before .='<span class="title-s " '.$title_2.'> '.esc_html($title_s).' </span>';
					}
						
						if($title !=""){
						
							
							$title_con ='<div class="head-title '.esc_attr($title_align).'" > ';
								$title_con .='<'.esc_attr($title_h).' class="heading-title '.esc_attr($title_align).' " '.$titlle .'  data-hover="'.esc_attr($title).'">';
								if($title_s_postion =="text_before"){
									$title_con.= $title_s_before.$title;
								}else{
									$title_con.= $title.$title_s_before;
								}
								$title_con .='</'.esc_attr($title_h).'>';

								if ($heading_style =="style_9"){
									$title_con .='<div class="seprator sep-l" '.$sep_width_st.'>';
									$title_con .='<span class="title-sep sep-l" '.$sep_style.'></span>';									
										$title_con .='<div class="sep-dot" '.$dot_color_b.'>.</div>';
									$title_con .='<span class="title-sep sep-r" '.$sep_style.'></span>';
									$title_con .='</div>';
								}
							$title_con .='</div>';
						}
						if($sub_title !=""){
							$s_title_con ='<div class="sub-heading">';
							$s_title_con .='<'.esc_attr($subtitle_font).' class="heading-sub-title '.esc_attr($sub_align).' " '.$sub.'> '.esc_html($sub_title).' </'.esc_attr($subtitle_font).'>';
							$s_title_con .='</div>';
						}
						if($position =="before"){
							$heading.= $s_title_con.$title_con;
							
						}if($position =="after"){
							$heading.= $title_con.$s_title_con;
						}
				
				$heading.='</div>';
			$heading.='</div>';

			
			
		   return $heading;
		}
		function init_tp_heading_title(){
			if(function_exists("vc_map"))
			{
				vc_map(array(
						"name" => __("Heading Style", "pt_theplus"),
						"base" => "tp_heading_title",
						'icon'	=> 'tp-heading-style',
						"category" => __("The Plus", "pt_theplus"),
						"description" => esc_html__('Creative Heading Options', 'pt_theplus'),
						"params" => array(
							array(
									'type'        => 'radio_select_image',
									'heading' =>  esc_html__('Heading Style', 'pt_theplus'), 
									'param_name'  => 'heading_style',
									'admin_label' => true, 
									'simple_mode' => false,
									'value' => 'style_1',
									'options'     => array(
										'style_1' => array(
											'tooltip' => esc_attr__('Modern','pt_theplus'),
											'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/heading-style/ts-heading-style-1.jpg'
										),
										'style_2' => array(
											'tooltip' => esc_attr__('Simple','pt_theplus'),
											'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/heading-style/ts-heading-style-2.jpg'
										),
										'style_5' => array(
											'tooltip' => esc_attr__('Double Border','pt_theplus'),
											'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/heading-style/ts-heading-style-3.jpg'
										),										
										'style_9' => array(
											'tooltip' => esc_attr__('Stylish','pt_theplus'),
											'src' => THEPLUS_PLUGIN_URL. 'vc_elements/images/heading-style/ts-heading-style-4.jpg'
										),
									),
								),
							array(
								"type" => "textarea",
								'heading' =>  esc_html__('Title', 'pt_theplus'), 
								"param_name" => "title",
								"value" => 'Heading',
								"description" => "",
								"admin_label" => true
								
							),
							array(
								"type" => "textfield",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('This is the part of title and available for some heading styles.','pt_theplus').'</span></span>'.esc_html__('Extra Title', 'pt_theplus')), 
								"param_name" => "title_s",
								"value" => 'Title',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_1',
									)
								),
								"description" => '',
							),
							array(
								"type" => "dropdown",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select this as prefix or postfix to main title text.','pt_theplus').'</span></span>'.esc_html__('Extra Title Position', 'pt_theplus')),
								"param_name" => "title_s_postion",
								'value' => array(
									__('Prefix', 'pt_theplus') => 'text_after',
									__('Postfix', 'pt_theplus') => 'text_before'
								),
								'std' => "text_after",
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_1',
									)
								),
								"description" => '',
							),
							array(
								"type" => "textfield",
								'heading' =>  esc_html__('Sub Title', 'pt_theplus'), 
								"param_name" => "sub_title",
								"value" => 'Sub Title',
								"admin_label" => true,
								"description" => ""
							),
							array(
								'type' => 'pt_theplus_heading_param',
								'text' => esc_html__('Separator Settings', 'pt_theplus'),
								'param_name' => 'sep_effect',
								'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_5',
										'style_9',
									)
								),
								 'group' => esc_attr__('Styles', 'pt_theplus'),
							),
							array(
								"type" => "colorpicker",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can select color and Opacity for Separator using this option.','pt_theplus').'</span></span>'.esc_html__('Color', 'pt_theplus')),
								"param_name" => "double_color",
								"value" => '#4d4d4d',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_5'
									)
								),
								 "edit_field_class" => "vc_col-xs-3",
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							array(
								"type" => "textfield",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Separator height using this Option.','pt_theplus').'</span></span>'.esc_html__('Top Separator height', 'pt_theplus')), 
								"param_name" => "double_top",
								"value" => '6px',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_5'
									)
								),
								"edit_field_class" => "vc_col-xs-3",
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							array(
								"type" => "textfield",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Separator height using this Option.','pt_theplus').'</span></span>'.esc_html__('Bottom Separator Height', 'pt_theplus')), 
								"param_name" => "double_bottom",
								"value" => '2px',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_5'
									)
								),
								"edit_field_class" => "vc_col-xs-3",
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							array(
								"type" => "colorpicker",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select color and Opacity for Separator using this option.','pt_theplus').'</span></span>'.esc_html__('Separator color', 'pt_theplus')),
								"param_name" => "sep_clr",
								"value" => '#4099c3',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_9'
									)
								),
								"edit_field_class" => "vc_col-xs-6",
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							array(
								"type" => "dropdown",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Separator Width using this option.','pt_theplus').'</span></span>'.esc_html__('Separator Width', 'pt_theplus')),
								"param_name" => "sep_width",
								"edit_field_class" => "vc_col-xs-6",
								"value" => array(
									__('Select width', 'pt_theplus') => '',
									__('10%', 'pt_theplus') => '10%',
									__('20%', 'pt_theplus') => '20%',
									__('30%', 'pt_theplus') => '30%',
									__('40%', 'pt_theplus') => '40%',
									__('50%', 'pt_theplus') => '50%',
									__('60%', 'pt_theplus') => '60%',
									__('70%', 'pt_theplus') => '70%',
									__('80%', 'pt_theplus') => '80%',
									__('90%', 'pt_theplus') => '90%',
									__('100%', 'pt_theplus') => '100%'
								),
								"std" => '100%',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_9'
									)
								),
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								'edit_field_class' => 'vc_col-xs-6',
								"admin_label" => false
							),
							array(
								"type" => "colorpicker",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' You can select color and Opacity for Separator dot using this option.','pt_theplus').'</span></span>'.esc_html__('Separator Dot color', 'pt_theplus')),
								"param_name" => "dot_color",
								"value" => '#ca2b2b',
								"edit_field_class" => "vc_col-xs-6",
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_9',
									)
								),
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								'edit_field_class' => 'vc_col-xs-6'
							),
							array(
								'type' => 'pt_theplus_heading_param',
								'text' => esc_html__('Title Settings', 'pt_theplus'),
								'param_name' => 'title_effect',
								'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
								 'group' => esc_attr__('Styles', 'pt_theplus'),
							),
							array(
								"type" => "dropdown",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select font tag using this option.','pt_theplus').'</span></span>'.esc_html__('Font Tag', 'pt_theplus')),
								"param_name" => "title_h",
								'value' => array(
									__('H1', 'pt_theplus') => 'h1',
									__('H2', 'pt_theplus') => 'h2',
									__('H3', 'pt_theplus') => 'h3',
									__('H4', 'pt_theplus') => 'h4',
									__('H5', 'pt_theplus') => 'h5',
									__('H6', 'pt_theplus') => 'h6'
								),
								'std' => 'h2',
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
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
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select this as before or after to title text.','pt_theplus').'</span></span>'.esc_html__('Color', 'pt_theplus')), 
								"param_name" => "title_color",
								"value" => '#ccc',
								"edit_field_class" => "vc_col-xs-4",
								"description" => '',
								'dependency' => array('element' => 'title_color_o','value' => 'solid'),
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
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
				'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select one gradient effect style from four beautiful options.','pt_theplus').'</span></span>'.esc_html__('Gradient Style', 'pt_theplus')),
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
								"type" => "dropdown",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Choose Title alignment from Right, Left or Center.','pt_theplus').'</span></span>'.esc_html__('Alignment ', 'pt_theplus')), 
								"param_name" => "title_align",
								'value' => array(
									__('Left', 'pt_theplus') => 'text-left',
									__('Right', 'pt_theplus') => 'text-right',
									__('Center', 'pt_theplus') => 'text-center'
								),
								"edit_field_class" => "vc_col-xs-6",
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_2',
										'style_1',
										'style_5',
										'style_9'
									)
								),
								'std' => 'text-center',
								"description" => "",
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							
							
							array(
								'type' => 'pt_theplus_heading_param',
								'text' => esc_html__('Extra Title Settings', 'pt_theplus'),
								'param_name' => 'etxra_settings',
								 'group' => esc_attr__('Styles', 'pt_theplus'),
								'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
								 'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_1',
									)
								),
							),
							
							
							array(
								"type" => "textfield",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font size in Pixels using this option. E.g. 14px, 20px, etc.','pt_theplus').'</span></span>'.esc_html__('Font Size', 'pt_theplus')),
								"param_name" => "title_s_size",
								'value' => '',
								"description" => '',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_1',
									)
								),
								"edit_field_class" => "vc_col-xs-6",
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							array(
								"type" => "textfield",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Line Height in Pixels using this Option. E.g. 12px, 10px, etc.','pt_theplus').'</span></span>'.esc_html__('Line Height', 'pt_theplus')),
								"param_name" => "title_s_line",
								'value' => '1.2',
								"description" => '',
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_1',
									)
								),
								"edit_field_class" => "vc_col-xs-6",
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							
							array(
		  "type"        => "dropdown",
		  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Title Color Options using this option.','pt_theplus').'</span></span>'.esc_html__('Title Color Options', 'pt_theplus')),
		  "param_name"  => "title_s_color_o",
		  "admin_label" => true,
		  "value"       => array(
				__( 'Solid', 'pt_theplus' ) => 'solid',
				__( 'Gradient (Premium)', 'pt_theplus' ) => 'gradient',
			),
		  "std" => "solid",
		  "description" => "",
		  'group' => esc_attr__('Styles', 'pt_theplus'),
'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_1',
									)
								),
		),
							array(
								"type" => "colorpicker",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select this as before or after to title text.','pt_theplus').'</span></span>'.esc_html__('Color', 'pt_theplus')), 
								"param_name" => "title_s_color",
								"value" => '#ca2b2b',
								"edit_field_class" => "vc_col-xs-4",
								"description" => '',
								'dependency' => array('element' => 'title_s_color_o','value' => 'solid'),
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
array(
		   'type' => 'colorpicker',
		   'heading' => __( 'Color 1', 'pt_theplus' ),
		   'param_name' => 'title_s_color1',  
			'dependency' => array('element' => 'title_s_color_o','value' => 'gradient'),
		   "edit_field_class" => "vc_col-xs-6",
		   "value" => '#1e73be',
		   'group' => esc_attr__('Styles', 'pt_theplus'),
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Color 2', 'pt_theplus' ),
			'param_name' => 'title_s_color2',   
			'dependency' => array('element' => 'title_s_color_o','value' => 'gradient'),
			"edit_field_class" => "vc_col-xs-6",
			"value" => '#2fcbce',
			'group' => esc_attr__('Styles', 'pt_theplus'),
		),
		array(
				'type' => 'dropdown',
				'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select one gradient effect style from four beautiful options.','pt_theplus').'</span></span>'.esc_html__('Gradient Style', 'pt_theplus')),
				'param_name' => 'title_s_hover_style',
				'value' => array(
					__( 'Horizontal', 'pt_theplus' ) => 'horizontal',
					__( 'Vertical', 'pt_theplus' ) => 'vertical',
					__( 'Diagonal', 'pt_theplus' ) => 'diagonal',
					__( 'Radial', 'pt_theplus' ) => 'radial',                                
				),
			'std'=>'horizontal',
			'dependency' => array('element' => 'title_s_color_o','value' => 'gradient'),
			"description" => "",
			'group' => esc_attr__('Styles', 'pt_theplus'),
		),

							array(
									'type' => 'dropdown',
									'heading' => '<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Allows you to use custom Google font','pt_theplus').'</span></span>'.esc_html__('Extra Title Custom font family', 'pt_theplus'),
									'param_name' => 'title_s_use_theme_fonts',
									 "value" => array(
										esc_html__("Custom font family", 'pt_theplus') => "custom-font-family",
										esc_html__("Google fonts (Premium)", 'pt_theplus') => "google-fonts",
									),
									'group' => esc_attr__('Styles', 'pt_theplus'),
									'dependency' => array(
									'element' => 'heading_style',
										'value' => array(
											'style_1',
										)
									),
									'std' =>  'custom-font-family',
									'group' => esc_attr__('Styles', 'pt_theplus'),	
							),
							array(
								'type' => 'textfield',
								'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add Custom Font family using this Option. E.g. Arial,Open sans etc.','pt_theplus').'</span></span>'.esc_html__('Font Family', 'pt_theplus')),
								'param_name' => 'title_s_font_family',
								'value' => "",
								'edit_field_class' => 'vc_col-xs-6',
								'description' => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),	
								'dependency' => array(
										'element' => 'title_s_use_theme_fonts',
										'value' => 'custom-font-family',
								),
							),
							array(
								'type' => 'textfield',
								'heading' =>  __('<span class="pt_theplus-vc-toolip "><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can add font weight using this Option. E.g. 200,400,700,900 etc.','pt_theplus').'</span></span>'.esc_html__('Font Weight', 'pt_theplus')),
								'param_name' => 'title_s_font_weight',
								'value' => __('600','pt_theplus'),
								'edit_field_class' => 'vc_col-xs-6',
								'description' => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),	
								'dependency' => array(
										'element' => 'title_s_use_theme_fonts',
										'value' => 'custom-font-family',
								),
							),
							array(
								'type' => 'pt_theplus_heading_param',
								'text' => esc_html__('Sub Title Settings', 'pt_theplus'),
								'param_name' => 'subtitle_effect',
								 'group' => esc_attr__('Styles', 'pt_theplus'),
								'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							),
								
							array(
								"type" => "dropdown",
								"class" => "",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select font tag using this option.','pt_theplus').'</span></span>'.esc_html__('Font Tag', 'pt_theplus')),
								"heading" => __("Subtitle Font ", 'pt_theplus'),
								"param_name" => "subtitle_font",
								'value' => array(
									__('H1', 'pt_theplus') => 'h1',
									__('H2', 'pt_theplus') => 'h2',
									__('H3', 'pt_theplus') => 'h3',
									__('H4', 'pt_theplus') => 'h4',
									__('H5', 'pt_theplus') => 'h5',
									__('H6', 'pt_theplus') => 'h6'
								),
								'std' => 'h3',
								"description" => '',
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
array(
		  "type"        => "dropdown",
		  'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Select Title Color Options using this option.','pt_theplus').'</span></span>'.esc_html__('Title Color Options', 'pt_theplus')),
		  "param_name"  => "sub_color_o",
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
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select this as before or after to title text.','pt_theplus').'</span></span>'.esc_html__('Color', 'pt_theplus')), 
								"param_name" => "sub_color",
								"value" => '#ccc',
								"edit_field_class" => "vc_col-xs-4",
								"description" => '',
								'dependency' => array('element' => 'sub_color_o','value' => 'solid'),
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
array(
		   'type' => 'colorpicker',
		   'heading' => __( 'Color 1', 'pt_theplus' ),
		   'param_name' => 'sub_color1',  
			'dependency' => array('element' => 'sub_color_o','value' => 'gradient'),
		   "edit_field_class" => "vc_col-xs-6",
		   "value" => '#1e73be',
		   'group' => esc_attr__('Styles', 'pt_theplus'),
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Color 2', 'pt_theplus' ),
			'param_name' => 'sub_color2',   
			'dependency' => array('element' => 'sub_color_o','value' => 'gradient'),
			"edit_field_class" => "vc_col-xs-6",
			"value" => '#2fcbce',
			'group' => esc_attr__('Styles', 'pt_theplus'),
		),
		array(
				'type' => 'dropdown',
				'heading' =>  __('<span class="pt_theplus-vc-toolip tooltip-bottom"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select one gradient effect style from four beautiful options.','pt_theplus').'</span></span>'.esc_html__('Gradient Style', 'pt_theplus')),
				'param_name' => 'sub_hover_style',
				'value' => array(
					__( 'Horizontal', 'pt_theplus' ) => 'horizontal',
					__( 'Vertical', 'pt_theplus' ) => 'vertical',
					__( 'Diagonal', 'pt_theplus' ) => 'diagonal',
					__( 'Radial', 'pt_theplus' ) => 'radial',                                
				),
			'std'=>'horizontal',
			'dependency' => array('element' => 'sub_color_o','value' => 'gradient'),
			"description" => "",
			'group' => esc_attr__('Styles', 'pt_theplus'),
		),


							 array(
								"type" => "dropdown",            
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('Choose sub title alignment from Right, Left or Center.','pt_theplus').'</span></span>'.esc_html__('Alignment ', 'pt_theplus')), 
								"param_name" => "sub_align",
								'value' => array(
									__('Left', 'pt_theplus') => 'text-left',
									__('Right', 'pt_theplus') => 'text-right',
									__('Center', 'pt_theplus') => 'text-center'
								),
								"edit_field_class" => "vc_col-xs-6",
								'std' => 'text-center',
								"description" => "",
								'dependency' => array(
									'element' => 'heading_style',
									'value' => array(
										'style_2',
										'style_1',
										'style_5',
										'style_9'
									)
								),
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
							),
							array(
								"type" => "dropdown",
								'heading' =>  __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can select this as before or after to title text.','pt_theplus').'</span></span>'.esc_html__('Position', 'pt_theplus')), 
								"param_name" => "position",
								'value' => array(
									__('Before Title', 'pt_theplus') => 'before',
									__('After Title', 'pt_theplus') => 'after'
								),
								"edit_field_class" => "vc_col-xs-6",
								'std' => 'before',
								"description" => "",
								'group' => esc_attr__('Styles', 'pt_theplus'),
								"admin_label" => false
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
								'type' => 'css_editor',
								'heading' => __('CSS box', 'pt_theplus'),
								'param_name' => 'css',
								'group' => __('Design Options', 'pt_theplus')
							),
							array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Animation Settings', 'pt_theplus'),
							'param_name' => 'annimation_effect',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							),
							array(
								"type" => "dropdown",
								"heading" => __("Animated Effects", 'pt_theplus'),
								"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__('You can Choose Animation Effect When This Element will be load on scroll. It have many modern options for you to choose from. ','pt_theplus').'</span></span>'.esc_html__('Choose Animation Effect', 'pt_theplus')),
								"param_name" => "animation_effects",
								"edit_field_class" => "vc_col-xs-6",
								"admin_label" => false,
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
								'std' => 'no-animation'
							),
							array(
								"type" => "textfield",
								"heading" => __('<span class="pt_theplus-vc-toolip"><i class="fa fa-question" aria-hidden="true"></i><span class="pt_theplus-vc-tooltip-text">'.esc_html__(' Add value of delay in transition on scroll in millisecond. 1 sec = 1000 Millisecond ','pt_theplus').'</span></span>'.esc_html__('Animation Delay', 'pt_theplus')),	
								"param_name" => "animation_delay",
								"value" => '50',
								"edit_field_class" => "vc_col-xs-6",
								"description" => ""
							),
							array(
							'type' => 'pt_theplus_heading_param',
							'text' => esc_html__('Extra Settings', 'pt_theplus'),
							'param_name' => 'extra_effect',
							'edit_field_class' => 'pt_theplus-heading-param-style vc_col-sm-12',
							),	
							array(
								"type" => "textfield",
								"edit_field_class" => "vc_col-xs-6",
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
	new ThePlus_heading_title;

	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_tp_heading_title'))
	{
		class WPBakeryShortCode_tp_heading_title extends WPBakeryShortCode
		{
			protected function contentInline($atts, $content = null)
			{
			}
		}
	}
}