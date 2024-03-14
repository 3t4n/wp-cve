jQuery(document).ready(function($){
	var pt_plusCustomDependencies = function() {
	$('[data-vc-shortcode="tp_accordion"]').each(function() {
		var value=$('select[name="select_act_bg_option"] option[value="gradient"],select[name="select_deac_bg_option"] option[value="gradient"]');
		if($('select[name="styles"],select[name="select_act_bg_option"],select[name="select_deac_bg_option"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_heading_animation"]').each(function() {
		var value=$('select[name="anim_styles"] option[value="style-2"],select[name="anim_styles"] option[value="style-4"],select[name="anim_styles"] option[value="style-5"]');
		if($('select[name="styles"],select[name="anim_styles"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_info_box"]').each(function() {
		var value=$('select[name="info_box_layout"] option[value="carousel_layout"],select[name="title_color_o"] option[value="gradient"],select[name="icon_style"] option[value="rounded"],select[name="icon_style"] option[value="hexagon"],select[name="icon_style"] option[value="pentagon"],select[name="icon_style"] option[value="square-rotate"]');
		if($('select[name="info_box_layout"],select[name="title_color_o"],select[name="icon_style"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_button"]').each(function() {
		var value=$('select[name="select_bg_option"] option[value="gradient"],select[name="select_bg_option"] option[value="image"],select[name="select_bg_hover_option"] option[value="gradient"],select[name="select_bg_hover_option"] option[value="image"]');
		if($('select[name="select_bg_option"],select[name="select_bg_hover_option"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_animated_svg"]').each(function() {
		var value=$('select[name="type"] option[value="sync"],select[name="type"] option[value="oneByOne"],select[name="type"] option[value="scenario-sync"]');
		if($('select[name="type"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_stylish_list"]').each(function() {
		var value=$('select[name="stylish_content_image_icon"] option[value="image"],select[name="stylish_content_image_icon"] option[value="svg"]');
		if($('select[name="stylish_content_image_icon"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_heading_title"]').each(function() {
		var value=$('select[name="title_color_o"] option[value="gradient"],select[name="title_s_color_o"] option[value="gradient"],select[name="sub_color_o"] option[value="gradient"]');
		if($('select[name="styles"],select[name="title_color_o"],select[name="title_s_color_o"],select[name="sub_color_o"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	
	$('[data-vc-shortcode="tp_icon_counter"]').each(function() {
		var value=$('select[name="icn_layout"] option[value="carousel_layout"],select[name="icon_imge"] option[value="svg"]');
		if($('select[name="icn_layout"],select[name="icon_imge"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_pricing_table"]').each(function() {
		var value=$('select[name="pricing_size"] option[value="small"],select[name="pricing_size"] option[value="large"],select[name="pricing_content"] option[value="custom"],select[name="bg_color"] option[value="gradient"],select[name="bg_color_img"] option[value="bg_img"],select[name="to_bg_color_img"] option[value="top_bg_img"]');
		if($('select[name="pricing_size"],select[name="pricing_content"],select[name="bg_color"],select[name="bg_color_img"],select[name="to_bg_color_img"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_progressbar"]').each(function() {
		var value=$('select[name="pie_fill"] option[value="gradient"],select[name="image_icon"] option[value="svg"]');
		if($('select[name="pie_fill"],select[name="image_icon"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
	});
	$('[data-vc-shortcode="tp_social_share"]').each(function() {
		if($('[data-vc-shortcode-param-name="styles"] .image_picker_selector').length > 0) {
		var i=6;
		while(i<=15){
			$('[data-vc-shortcode-param-name="styles"] .image_picker_selector >li:nth-child('+i+')').addClass("pt_plus_disabled");
			i++;
		}
		}
	});

		$('[data-vc-shortcode="tp_tours"]').each(function() {
		var value=$('select[name="styles"] option[value="tour-style-4"],select[name="styles"] option[value="tour-style-5"]');
		if($('select[name="styles"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
		});
		$('[data-vc-shortcode="tp_tabs"],[data-vc-shortcode="tp_tours"]').each(function() {
		var value=$('select[name="select_act_bg_option"] option[value="gradient"],select[name="select_deac_bg_option"] option[value="gradient"]');
		if($('select[name="select_act_bg_option"],select[name="select_deac_bg_option"]').find('option').length > 0) {
			value.attr("disabled","disabled");
		}
		});
	$('[data-vc-shortcode-param-name="animation_effects"],[data-vc-shortcode-param-name="content_hover_effects"],[data-vc-shortcode-param-name="hover_shadow_color"],[data-vc-shortcode-param-name="price_animation_efect"]').each(function() {
		var value=$(this);
		value.addClass("pt_plus_opacity");
	});
	$('[data-vc-shortcode-param-name="animation_delay"], [data-vc-shortcode-param-name="act_gradient_color1"], [data-vc-shortcode-param-name="deac_gradient_color1"], [data-vc-shortcode-param-name="act_gradient_color2"], [data-vc-shortcode-param-name="deac_gradient_color2"], [data-vc-shortcode-param-name="act_gradient_style"], [data-vc-shortcode-param-name="deac_gradient_style"], [data-vc-shortcode-param-name="duration"], [data-vc-shortcode-param-name="desktop_hide"], [data-vc-shortcode-param-name="tablet_hide"], [data-vc-shortcode-param-name="mobile_hide"], [data-vc-shortcode-param-name="box_shadow"], [data-vc-shortcode-param-name="hover_box_shadow"], [data-vc-shortcode-param-name="gradient_color1"], [data-vc-shortcode-param-name="gradient_color2"], [data-vc-shortcode-param-name="gradient_hover_style"], [data-vc-shortcode-param-name="hvr_gradient_color1"], [data-vc-shortcode-param-name="hvr_gradient_color2"], [data-vc-shortcode-param-name="hvr_gradient_hover_style"], [data-vc-shortcode-param-name="title_color1"], [data-vc-shortcode-param-name="title_color2"], [data-vc-shortcode-param-name="title_hover_style"], [data-vc-shortcode-param-name="sub_color1"], [data-vc-shortcode-param-name="sub_color2"], [data-vc-shortcode-param-name="sub_hover_style"], [data-vc-shortcode-param-name="title_s_color1"], [data-vc-shortcode-param-name="title_s_color2"], [data-vc-shortcode-param-name="title_s_hover_style"], [data-vc-shortcode-param-name="gradient_style"], [data-vc-shortcode-param-name="gradient_hover_color1"], [data-vc-shortcode-param-name="gradient_hover_color2"], [data-vc-shortcode-param-name="gradient_hover_style"]').each(function() {
		var value=$(this);
		value.addClass("pt_plus_disabled");
	});
	$('[data-vc-shortcode-param-name="progressbar_style"] .image_picker_selector >li:last-child,[data-vc-shortcode-param-name="pie_chart_style"] .image_picker_selector >li:last-child,[data-vc-shortcode-param-name="pie_chart_style"] .image_picker_selector >li:nth-last-child(2),[data-vc-shortcode-param-name="menu_style"] .image_picker_selector >li:last-child,[data-vc-shortcode-param-name="menu_style"] .image_picker_selector >li:nth-last-child(2)').each(function() {
	var value=$(this);
		value.addClass("pt_plus_disabled");
	});
	};
	$(window).load(function() {
		$('.vc_ui-panel-window').on('vcPanel.shown',pt_plusCustomDependencies);
	});
	$('body').on('change', '[data-vc-shortcode-param-name="items"]', pt_plusCustomDependencies);
	$('body').on('change', '[name="styles"]', pt_plusCustomDependencies);
});