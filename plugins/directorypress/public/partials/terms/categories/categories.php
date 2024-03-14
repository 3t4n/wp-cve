<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$slider_arrow_position = (isset($instance->slider_arrow_position))? $instance->slider_arrow_position: 'absolute';
	$slider_arrow_icon_pre = (!empty($instance->slider_arrow_icon_pre))? $instance->slider_arrow_icon_pre : 'fas fa-angle-left';
	$slider_arrow_icon_next = (!empty($instance->slider_arrow_icon_next))? $instance->slider_arrow_icon_next : 'fas fa-angle-right';

	$slick_attrs = 'data-items="'. $instance->desktop_items .'"';
	$slick_attrs .= 'data-items-tablet="'. $instance->tab_items .'"';
	$slick_attrs .= 'data-items-mobile="'. $instance->mobile_items .'"';
	$slick_attrs .= 'data-slide-to-scroll="1"';
	$slick_attrs .= 'data-slide-speed="1000"';
	$slick_attrs .= ($instance->autoplay)? 'data-autoplay="true"' : 'data-autoplay="false"';
	$slick_attrs .= 'data-center-padding=""';
	$slick_attrs .= 'data-center="false"';
	$slick_attrs .= 'data-autoplay-speed="'. $instance->autoplay_speed .'"';
	$slick_attrs .= ($instance->loop)? 'data-loop="true"' : 'data-loop="false"';
	$slick_attrs .= 'data-list-margin="'. $instance->gutter .'"';
	$slick_attrs .= ($instance->owl_nav)? 'data-arrow="true"': 'data-arrow="false"';
	$slick_attrs .= 'data-prev-arrow="'. $slider_arrow_icon_pre .'"';
	$slick_attrs .= 'data-next-arrow="'. $slider_arrow_icon_next .'"';
	$slick_attrs .= 'data-arrow-postion ="'. $slider_arrow_position .'"';
	$instance->slick_attrs = $slick_attrs;
	
	directorypress_display_template('partials/terms/categories/parts/category-style-default.php', array('instance' => $instance, 'terms' => $terms));

		
	/* custom styles */
		$directorypress_styles = '';
		$category_id = '#directorypress-category-'.$instance->args['id'];
		$id = $instance->args['id'];
		
		$cat_font_size = (isset($instance->cat_font_size) && !empty($instance->cat_font_size))? ('font-size:' . $instance->cat_font_size . 'px;') : '';
		$cat_font_weight = (isset($instance->cat_font_weight) && !empty($instance->cat_font_weight))? ('font-weight:' . $instance->cat_font_weight . ';') : '';
		$cat_font_line_height = (isset($instance->cat_font_line_height) && !empty($instance->cat_font_line_height))? ('line-height:' . $instance->cat_font_line_height . 'px;') : '';
		$cat_font_transform = (isset($instance->cat_font_transform) && !empty($instance->cat_font_transform)) ? ('text-transform: ' . $instance->cat_font_transform . ';') : '';
		
		$parent_cat_title_color = (isset($instance->parent_cat_title_color) && !empty($instance->parent_cat_title_color))? ('color:' . $instance->parent_cat_title_color . ' !important;') : '';
		$parent_cat_title_color_hover = (isset($instance->parent_cat_title_color_hover)  && !empty($instance->parent_cat_title_color_hover))? ('color:' . $instance->parent_cat_title_color_hover . ' !important;') : '';
		
		$child_cat_font_size = (isset($instance->child_cat_font_size) && !empty($instance->child_cat_font_size))? ('font-size:' . $instance->child_cat_font_size . 'px;') : '';
		$child_cat_font_weight = (isset($instance->child_cat_font_weight) && !empty($instance->child_cat_font_weight))? ('font-weight:' . $instance->child_cat_font_weight . ';') : '';
		$child_cat_font_line_height = (isset($instance->child_cat_font_line_height) && !empty($instance->child_cat_font_line_height))? ('line-height:' . $instance->child_cat_font_line_height . 'px;') : '';
		$child_cat_font_transform = (isset($instance->child_cat_font_transform) && !empty($instance->child_cat_font_transform)) ? ('text-transform: ' . $instance->child_cat_font_transform . ';') : '';
		
		$subcategory_title_color = (isset($instance->subcategory_title_color) && !empty($instance->subcategory_title_color))? ('color:' . $instance->subcategory_title_color . ' !important;') : '';
		$subcategory_title_color_hover = (isset($instance->subcategory_title_color_hover) && !empty($instance->subcategory_title_color_hover))? ('color:' . $instance->subcategory_title_color_hover . ' !important;') : '';
		
		DirectoryPress_Static_Files::addCSS('
			'.$category_id.' .directorypress-parent-category a{
				'.$parent_cat_title_color.'
				'.$cat_font_size.'
				'.$cat_font_weight.'
				'.$cat_font_line_height.'
				'.$cat_font_transform.'
			}
			'.$category_id.' .directorypress-parent-category a:hover{
				'.$parent_cat_title_color_hover.'
			}
			'.$category_id.' .subcategories ul li a,
			'.$category_id.' .subcategories ul li a span{
				'.$subcategory_title_color.'
				'.$child_cat_font_size.'
				'.$child_cat_font_weight.'
				'.$child_cat_font_line_height.'
				'.$child_cat_font_transform.'
			}
			'.$category_id.' .subcategories ul li a:hover,
			'.$category_id.' .subcategories ul li a:hover span{
				'.$subcategory_title_color_hover.'
			}
		', $id);