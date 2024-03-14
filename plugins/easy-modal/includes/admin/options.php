<?php
add_filter('emodal_existing_addon_images', 'emodal_core_addon_images', 10);
function emodal_core_addon_images($array)
{
	return array_merge($array, array(
		'premium-support',
		'pro-developer',
		'pro-bundle',
		'unlimited-themes',
		'scroll-pops',
		'force-user-action',
		'age-verification',
		'advanced-theme-editor',
		'exit-modals',
		'auto-open',
		'login-modals',
	));
}



add_filter('emodal_model_modal_meta_defaults', 'emodal_model_modal_meta_core_defaults', 10);
function emodal_model_modal_meta_core_defaults($options){
	if(empty($options['display']['overlay_disabled'] )) $options['display']['overlay_disabled'] = 0;
	if(empty($options['display']['size'])) $options['display']['size'] = 'medium';
	if(empty($options['display']['custom_width'])) $options['display']['custom_width'] = '';
	if(empty($options['display']['custom_width_unit'])) $options['display']['custom_width_unit'] = '%';
	if(empty($options['display']['custom_height'])) $options['display']['custom_height'] = '';
	if(empty($options['display']['custom_height_unit'])) $options['display']['custom_height_unit'] = 'em';
	if(empty($options['display']['custom_height_auto'])) $options['display']['custom_height_auto'] = 1;

	if(empty($options['display']['location'])) $options['display']['location'] = 'center top';
	if(empty($options['display']['position']['top'])) $options['display']['position']['top'] = 100;
	if(empty($options['display']['position']['left'])) $options['display']['position']['left'] = 0;
	if(empty($options['display']['position']['bottom'])) $options['display']['position']['bottom'] = 0;
	if(empty($options['display']['position']['right'])) $options['display']['position']['right'] = 0;
	if(empty($options['display']['position']['fixed'])) $options['display']['position']['fixed'] = 0;

	if(empty($options['display']['animation']['type'])) $options['display']['animation']['type'] = 'fade';
	if(empty($options['display']['animation']['speed'])) $options['display']['animation']['speed'] = 350;
	if(empty($options['display']['animation']['origin'])) $options['display']['animation']['origin'] = 'center top';

	if(empty($options['close']['overlay_click'])) $options['close']['overlay_click'] = 0;
	if(empty($options['close']['esc_press'])) $options['close']['esc_press'] = 1;
	return $options;
}

add_filter('emodal_model_theme_meta_defaults', 'emodal_model_theme_meta_core_defaults', 10);
function emodal_model_theme_meta_core_defaults($options){


	if(empty($options['overlay']['background']['color'])) $options['overlay']['background']['color'] = '#ffffff';
	if(empty($options['overlay']['background']['opacity'])) $options['overlay']['background']['opacity'] = 100;

	if(empty($options['container']['padding'])) $options['container']['padding'] = 18;
	if(empty($options['container']['background']['color'])) $options['container']['background']['color'] = '#f9f9f9';
	if(empty($options['container']['background']['opacity'])) $options['container']['background']['opacity'] = 100;
	if(empty($options['container']['border']['style'])) $options['container']['border']['style'] = 'none';
	if(empty($options['container']['border']['color'])) $options['container']['border']['color'] = '#000000';
	if(empty($options['container']['border']['width'])) $options['container']['border']['width'] = 1;
	if(empty($options['container']['border']['radius'])) $options['container']['border']['radius'] = 0;
	if(empty($options['container']['boxshadow']['inset'])) $options['container']['boxshadow']['inset'] = 'no';
	if(empty($options['container']['boxshadow']['horizontal'])) $options['container']['boxshadow']['horizontal'] = 1;
	if(empty($options['container']['boxshadow']['vertical'])) $options['container']['boxshadow']['vertical'] = 1;
	if(empty($options['container']['boxshadow']['blur'])) $options['container']['boxshadow']['blur'] = 3;
	if(empty($options['container']['boxshadow']['spread'])) $options['container']['boxshadow']['spread'] = 0;
	if(empty($options['container']['boxshadow']['color'])) $options['container']['boxshadow']['color'] = '#020202';
	if(empty($options['container']['boxshadow']['opacity'])) $options['container']['boxshadow']['opacity'] = 23;

	if(empty($options['title']['font']['color'])) $options['title']['font']['color'] = '#000000';
	if(empty($options['title']['font']['size'])) $options['title']['font']['size'] = 32;
	if(empty($options['title']['font']['family'])) $options['title']['font']['family'] = 'Tahoma';
	if(empty($options['title']['text']['align'])) $options['title']['text']['align'] = 'left';
	if(empty($options['title']['textshadow']['horizontal'])) $options['title']['textshadow']['horizontal'] = 0;
	if(empty($options['title']['textshadow']['vertical'])) $options['title']['textshadow']['vertical'] = 0;
	if(empty($options['title']['textshadow']['blur'])) $options['title']['textshadow']['blur'] = 0;
	if(empty($options['title']['textshadow']['color'])) $options['title']['textshadow']['color'] = '#020202';
	if(empty($options['title']['textshadow']['opacity'])) $options['title']['textshadow']['opacity'] = 23;

	if(empty($options['content']['font']['color'])) $options['content']['font']['color'] = '#8c8c8c';
	if(empty($options['content']['font']['family'])) $options['content']['font']['family'] = 'Times New Roman';

	if(empty($options['close']['text'])) $options['close']['text'] = __('CLOSE', 'easy-modal' );
	if(empty($options['close']['location'])) $options['close']['location'] = 'topright';
	if(empty($options['close']['position']['top'])) $options['close']['position']['top'] = 0;
	if(empty($options['close']['position']['left'])) $options['close']['position']['left'] = 0;
	if(empty($options['close']['position']['bottom'])) $options['close']['position']['bottom'] = 0;
	if(empty($options['close']['position']['right'])) $options['close']['position']['right'] = 0;
	if(empty($options['close']['padding'])) $options['close']['padding'] = 8;
	if(empty($options['close']['background']['color'])) $options['close']['background']['color'] = '#00b7cd';
	if(empty($options['close']['background']['opacity'])) $options['close']['background']['opacity'] = 100;
	if(empty($options['close']['font']['color'])) $options['close']['font']['color'] = '#ffffff';
	if(empty($options['close']['font']['size'])) $options['close']['font']['size'] = 12;
	if(empty($options['close']['font']['family'])) $options['close']['font']['family'] = 'Times New Roman';
	if(empty($options['close']['border']['style'])) $options['close']['border']['style'] = 'none';
	if(empty($options['close']['border']['color'])) $options['close']['border']['color'] = '#ffffff';
	if(empty($options['close']['border']['width'])) $options['close']['border']['width'] = 1;
	if(empty($options['close']['border']['radius'])) $options['close']['border']['radius'] = 0;
	if(empty($options['close']['boxshadow']['inset'])) $options['close']['boxshadow']['inset'] = 'no';
	if(empty($options['close']['boxshadow']['horizontal'])) $options['close']['boxshadow']['horizontal'] = 0;
	if(empty($options['close']['boxshadow']['vertical'])) $options['close']['boxshadow']['vertical'] = 0;
	if(empty($options['close']['boxshadow']['blur'])) $options['close']['boxshadow']['blur'] = 0;
	if(empty($options['close']['boxshadow']['spread'])) $options['close']['boxshadow']['spread'] = 0;
	if(empty($options['close']['boxshadow']['color'])) $options['close']['boxshadow']['color'] = '#020202';
	if(empty($options['close']['boxshadow']['opacity'])) $options['close']['boxshadow']['opacity'] = 23;
	if(empty($options['close']['textshadow']['horizontal'])) $options['close']['textshadow']['horizontal'] = 0;
	if(empty($options['close']['textshadow']['vertical'])) $options['close']['textshadow']['vertical'] = 0;
	if(empty($options['close']['textshadow']['blur'])) $options['close']['textshadow']['blur'] = 0;
	if(empty($options['close']['textshadow']['color'])) $options['close']['textshadow']['color'] = '#000000';
	if(empty($options['close']['textshadow']['opacity'])) $options['close']['textshadow']['opacity'] = 23;
	return $options;
}


add_filter('emodal_size_unit_options', 'emodal_core_size_unit_options',10);
function emodal_core_size_unit_options($options){
	return array_merge($options, array(
		// option => value
		__('PX', 'easy-modal' ) => 'px',
		__('%', 'easy-modal' ) => '%',
		__('EM', 'easy-modal' ) => 'em',
		__('REM', 'easy-modal' ) => 'rem',
	));
}

add_filter('emodal_border_style_options', 'emodal_core_border_style_options',10);
function emodal_core_border_style_options($options){
	return array_merge($options, array(
		// option => value
		__('None', 'easy-modal' ) => 'none',
		__('Solid', 'easy-modal' ) => 'solid',
		__('Dotted', 'easy-modal' ) => 'dotted',
		__('Dashed', 'easy-modal' ) => 'dashed',
		__('Double', 'easy-modal' ) => 'double',
		__('Groove', 'easy-modal' ) => 'groove',
		__('Inset', 'easy-modal' ) => 'inset',
		__('Outset', 'easy-modal' ) => 'outset',
		__('Ridge', 'easy-modal' ) => 'ridge',
	));
}

add_filter('emodal_font_family_options', 'emodal_core_font_family_options',10);
function emodal_core_font_family_options($options){
	return array_merge($options, array(
		// option => value
		__('Sans-Serif', 'easy-modal' ) => 'Sans-Serif',
		__('Tahoma', 'easy-modal' ) => 'Tahoma',
		__('Georgia', 'easy-modal' ) => 'Georgia',
		__('Comic Sans MS', 'easy-modal' ) => 'Comic Sans MS',
		__('Arial', 'easy-modal' ) => 'Arial',
		__('Lucida Grande', 'easy-modal' ) => 'Lucida Grande',
		__('Times New Roman', 'easy-modal' ) => 'Times New Roman',
	));
}

add_filter('emodal_text_align_options', 'emodal_core_text_align_options',10);
function emodal_core_text_align_options($options){
	return array_merge($options, array(
		// option => value
		__('Left', 'easy-modal' ) => 'left',
		__('Center', 'easy-modal' ) => 'center',
		__('Right', 'easy-modal' ) => 'right'
	));
}


add_filter('emodal_modal_display_size_options', 'emodal_dropdown_divider',20);
function emodal_dropdown_divider($options){
	return array_merge($options, array(
		// value => option
		__('-----------------------') => ''
	));
}

add_filter('emodal_modal_display_size_options', 'emodal_modal_display_size_options_responsive',10);
function emodal_modal_display_size_options_responsive($options){
	return array_merge($options, array(
		// option => value
		__('Auto', 'easy-modal' ) => 'auto',
		__('Responsive', 'easy-modal' ) => '',
		__('Normal', 'easy-modal' ) => 'normal',
		__('Nano', 'easy-modal' ) => 'nano',
		__('Tiny', 'easy-modal' ) => 'tiny',
		__('Small', 'easy-modal' ) => 'small',
		__('Medium', 'easy-modal' ) => 'medium',
		__('Large', 'easy-modal' ) => 'large',
		__('X Large', 'easy-modal' ) => 'x-large'
	));
}

add_filter('emodal_modal_display_size_options', 'emodal_modal_display_size_options_nonresponsive',30);
function emodal_modal_display_size_options_nonresponsive($options){
	return array_merge($options, array(
		// value => option
		'<strong>' . __('Non-Responsive', 'easy-modal' ) . '</strong>' => '',
		__('Custom', 'easy-modal' ) => 'custom',
	));
}


add_filter('emodal_modal_display_animation_type_options', 'emodal_core_modal_display_animation_type_options',10);
function emodal_core_modal_display_animation_type_options($options){
	return array_merge($options, array(
		// option => value
		__('None', 'easy-modal' ) => 'none',
		__('Slide', 'easy-modal' ) => 'slide',
		__('Fade', 'easy-modal' ) => 'fade',
		__('Fade and Slide', 'easy-modal' ) => 'fadeAndSlide',
		__('Grow', 'easy-modal' ) => 'grow',
		__('Grow and Slide', 'easy-modal' ) => 'growAndSlide',
	));
}


add_filter('emodal_modal_display_animation_origin_options', 'emodal_core_modal_display_animation_origins_options',10);
function emodal_core_modal_display_animation_origins_options($options){
	return array_merge($options, array(
		// option => value
		__('Top', 'easy-modal' ) => 'top',
		__('Left', 'easy-modal' ) => 'left',
		__('Bottom', 'easy-modal' ) => 'bottom',
		__('Right', 'easy-modal' ) => 'right',
		__('Top Left', 'easy-modal' ) => 'left top',
		__('Top Center', 'easy-modal' ) => 'center top',
		__('Top Right', 'easy-modal' ) => 'right top',
		__('Middle Left', 'easy-modal' ) => 'left center',
		__('Middle Center', 'easy-modal' ) => 'center center',
		__('Middle Right', 'easy-modal' ) => 'right center',
		__('Bottom Left', 'easy-modal' ) => 'left bottom',
		__('Bottom Center', 'easy-modal' ) => 'center bottom',
		__('Bottom Right', 'easy-modal' ) => 'right bottom',
		//__('Mouse', 'easy-modal' ) => 'mouse',
	));
}

add_filter('emodal_modal_display_location_options', 'emodal_core_modal_display_location_options',10);
function emodal_core_modal_display_location_options($options){
	return array_merge($options, array(
		// option => value
		__('Top Left', 'easy-modal' ) => 'left top',
		__('Top Center', 'easy-modal' ) => 'center top',
		__('Top Right', 'easy-modal' ) => 'right top',
		__('Middle Left', 'easy-modal' ) => 'left center',
		__('Middle Center', 'easy-modal' ) => 'center ',
		__('Middle Right', 'easy-modal' ) => 'right center',
		__('Bottom Left', 'easy-modal' ) => 'left bottom',
		__('Bottom Center', 'easy-modal' ) => 'center bottom',
		__('Bottom Right', 'easy-modal' ) => 'right bottom',
	));
}

add_filter('emodal_theme_close_location_options', 'emodal_core_theme_close_location_options',10);
function emodal_core_theme_close_location_options($options){
	return array_merge($options, array(
		// option => value
		__('Top Left', 'easy-modal' ) => 'topleft',
		__('Top Right', 'easy-modal' ) => 'topright',
		__('Bottom Left', 'easy-modal' ) => 'bottomleft',
		__('Bottom Right', 'easy-modal' ) => 'bottomright',
	));
}