<?php
if (!defined('ABSPATH'))
  exit;
if (!current_user_can('edit_others_pages')) {
  wp_die(__('You do not have sufficient permissions to access this page.'));
}

if (!empty($_POST['submit']) && $_POST['submit'] == 'Save' && $_POST['style'] != '') {
  $nonce = $_REQUEST['_wpnonce'];
  if (!wp_verify_nonce($nonce, 'sbs-6310-nonce-field')) {
    die('You do not have sufficient permissions to access this page.');
  } else {
    $name = sanitize_text_field($_POST['style_name']);
    $style_name = sanitize_text_field($_POST['style']);
    if ($_POST['style'] == 'template-11') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,social_margin_top,social_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=320||##||320||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(209, 227, 255, 1)||##||rgba(156, 182, 214, 1)||##||20||##||25||##||rgb(255, 13, 0)||##||rgb(0, 9, 255)||##||100||##||uppercase||##||center||##||Cabin||##||4||##||10||##||49||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||10||##||10||##||1||##||read more||##||30||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(255, 255, 255)||##||rgb(12, 47, 59)||##||20||##||rgb(92, 89, 75)||##||rgb(101, 113, 130)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-angle||##||15||##||10||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 255, 255, 0.82)||##||rgba(255, 255, 255, 0.82)||##||rgba(0, 0, 0, 0.82)||##||1||##||10||##||10||##||rgba(255, 13, 0, 0.82)||##||rgba(0, 0, 0, 0.82)||##||0||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(11, 99, 117)||##||rgb(255, 255, 255)||##||300||##||capitalize||##||center||##||Arvo||##||5||##||2||##||Save";
    } else if ($_POST['style'] == 'template-12') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_hover_effect_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=10||##||10||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||3||##||rgba(255, 255, 255, 1)||##||rgba(204, 252, 255, 0.81)||##||1||##||5||##||rgba(0, 0, 0, 0.82)||##||rgba(45, 116, 196, 0.81)||##||0||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 170, 255, 1)||##||rgba(0, 183, 255, 1)||##||20||##||0||##||rgb(46, 42, 42)||##||800||##||uppercase||##||center||##||PT+Sans||##||15||##||5||##||50||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||15||##||10||##||rgb(66, 142, 248)||##||rgb(66, 142, 248)||##||1||##||35||##||100||##||14||##||rgb(247, 244, 244)||##||rgb(245, 245, 245)||##||1px||##||rgb(16, 110, 73)||##||rgb(138, 14, 14)||##||5||##||rgb(112, 101, 101)||##||rgb(140, 140, 140)||##||400||##||capitalize||##||center||##||Open+Sans||##||5||##||5||##||1000||##||1||##||fas fa-arrow||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(189, 144, 144, 0.81)||##||1||##||1||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||25||##||rgb(92, 85, 85)||##||rgb(69, 62, 62)||##||300||##||none||##||center||##||Open+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-13') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_color,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_hover_color,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_border_width,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=210||##||210||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||rgba(10, 82, 207, 1)||##||rgba(46, 112, 255, 1)||##||0||##||rgba(255, 255, 255, 1)||##||5||##||rgba(0, 0, 0, 1)||##||2||##||rgba(0, 0, 0, 1)||##||rgba(0, 0, 0, 1)||##||20||##||15||##||rgb(201, 214, 58)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Anton||##||0||##||0||##||50||##||rgb(255, 255, 255)||##||rgb(234, 255, 0)||##||5||##||Read More||##||30||##||100||##||16||##||rgb(252, 252, 252)||##||rgb(194, 180, 179)||##||1px||##||rgb(56, 39, 39)||##||rgb(36, 21, 21)||##||5||##||rgb(0, 0, 0)||##||rgb(224, 20, 20)||##||100||##||capitalize||##||center||##||Arimo||##||5||##||5||##||2000||##||1||##||fas fa-chevron||##||30||##||5||##||rgba(189, 134, 134, 0.81)||##||rgba(0, 0, 0, 1)||##||rgba(255, 255, 255, 0.91)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(138, 68, 68, 0.81)||##||rgba(77, 25, 25, 0.81)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(181, 102, 102)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Allerta+Stencil||##||0||##||15||##||Save";
    } else if ($_POST['style'] == 'template-14') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_icon_border_width,sbs_6310_icon_border_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=12||##||12||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||3||##||rgba(255, 255, 255, 1)||##||rgba(153, 0, 224, 1)||##||rgba(214, 214, 214, 0.83)||##||rgba(235, 208, 0, 0.81)||##||1||##||6||##||rgba(163, 127, 127, 1)||##||rgba(107, 153, 28, 0.82)||##||25||##||15||##||rgb(133, 102, 17)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Amaranth||##||20||##||5||##||40||##||rgb(188, 227, 32)||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||rgb(27, 193, 219)||##||5||##||5||##||5||##||rgb(0, 0, 0)||##||1||##||30||##||100||##||15||##||rgb(31, 31, 31)||##||rgb(255, 255, 255)||##||0||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||10||##||rgb(255, 255, 255)||##||rgb(65, 171, 93)||##||100||##||capitalize||##||center||##||Anonymous+Pro||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||29||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||15||##||14||##||rgba(255, 204, 0, 0.82)||##||rgba(189, 144, 144, 0.81)||##||47||##||1||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(123, 130, 163)||##||rgb(33, 43, 194)||##||100||##||capitalize||##||center||##||Amaranth||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-15') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_box_background_color,box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_hover_border_effect_width,sbs_6310_border_effect_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_icon_margin_bottom,sbs_6310_icon_border_radius,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=324||##||324||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||1||##||rgba(107, 107, 107, 0.82)||##||rgba(0, 0, 0, 0.82)||##||rgba(144, 173, 198, 0.81)||##||rgba(213, 221, 226, 0.81)||##||0||##||5||##||rgba(133, 68, 68, 0.81)||##||rgba(0, 68, 255, 0.81)||##||2||##||rgba(41, 41, 41, 0.82)||##||20||##||15||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Amaranth||##||8||##||14||##||44||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||rgb(51, 54, 82)||##||rgb(250, 208, 44)||##||61||##||10||##||1||##||read||##||30||##||100||##||18||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||2px||##||rgb(13, 214, 144)||##||rgb(121, 24, 196)||##||50||##||rgb(51, 54, 82)||##||rgb(230, 153, 9)||##||100||##||capitalize||##||center||##||Anonymous+Pro||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||10||##||rgba(0, 0, 0, 1)||##||rgba(255, 255, 255, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(0, 0, 0, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(50, 82, 168)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Anton||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-16') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=8||##||8||##||3||##||1||##||1||##||2||##||transparent||##||rgba(217, 217, 217, 1)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||0||##||rgba(46, 35, 35, 0.83)||##||rgba(27, 113, 163, 0.84)||##||rgba(255, 255, 255, 1)||##||rgba(255, 255, 255, 1)||##||3||##||3||##||rgba(130, 130, 130, 0.01)||##||rgba(0, 0, 0, 0.84)||##||26||##||28||##||rgb(0, 0, 0)||##||rgb(114, 124, 182)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||0||##||40||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||rgb(114, 124, 182)||##||rgb(62, 75, 199)||##||1||##||30||##||108||##||12||##||rgb(0, 0, 0)||##||rgb(166, 7, 7)||##||1px||##||rgb(31, 31, 31)||##||rgb(255, 13, 0)||##||10||##||rgb(255, 255, 255)||##||rgb(199, 206, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(189, 144, 144, 0.81)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||20||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||Save";
    } else if ($_POST['style'] == 'template-17') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_box_background_color,box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color1,sbs_6310_icon_color2,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=325||##||325||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||1||##||1||##||rgba(0, 0, 0, 0.84)||##||rgba(38, 32, 32, 0.83)||##||rgba(247, 247, 247, 0.82)||##||rgba(230, 230, 230, 0.82)||##||1||##||10||##||rgba(194, 174, 174, 0.81)||##||rgba(143, 142, 107, 0.82)||##||24||##||16||##||rgb(168, 0, 0)||##||rgb(0, 0, 0)||##||800||##||capitalize||##||center||##||Amaranth||##||20||##||10||##||40||##||rgb(210, 2, 0)||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||rgb(255, 0, 0)||##||5||##||5||##||1||##||read more||##||31||##||120||##||16||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||1px||##||rgb(189, 189, 189)||##||rgb(255, 255, 255)||##||10||##||rgb(184, 184, 184)||##||rgb(5, 220, 240)||##||100||##||capitalize||##||center||##||Amaranth||##||0||##||0||##||1000||##||1||##||fas fa-angle||##||15||##||50||##||rgba(0, 0, 0, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(0, 0, 0, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||22||##||rgb(0, 107, 161)||##||rgb(9, 28, 56)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||Save";
    } else if ($_POST['style'] == 'template-18') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_box_background_color,box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_icon_border_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=9||##||9||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||0||##||rgba(0, 0, 0, 0.82)||##||rgba(0, 255, 166, 0.82)||##||rgba(245, 238, 210, 1)||##||rgba(245, 238, 210, 1)||##||0||##||0||##||rgba(179, 157, 157, 0.81)||##||rgba(45, 116, 196, 0.81)||##||30||##||34||##||rgb(61, 57, 57)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Amaranth||##||0||##||0||##||40||##||rgb(255, 255, 255)||##||rgb(36, 32, 32)||##||rgb(204, 35, 26)||##||rgb(255, 13, 0)||##||rgb(0, 0, 0)||##||1||##||39||##||120||##||14||##||rgb(0, 191, 131)||##||rgb(3, 1, 1)||##||1px||##||rgb(255, 0, 0)||##||||##||0||##||rgb(255, 255, 255)||##||rgb(250, 250, 250)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-angle||##||30||##||1||##||rgba(133, 118, 117, 0.82)||##||rgba(255, 255, 255, 0.82)||##||rgba(97, 75, 75, 0.81)||##||rgba(255, 255, 255, 0.82)||##||1||##||10||##||10||##||rgba(107, 81, 79, 0.93)||##||rgba(189, 144, 144, 0.81)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||27||##||rgb(69, 69, 69)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Allan||##||10||##||10||##||Save";
    } else if ($_POST['style'] == 'template-19') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_box_background_color,box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_icon_border_width,sbs_6310_icon_border_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=39||##||39||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||10||##||5||##||rgba(0, 0, 0, 0.81)||##||rgba(0, 0, 0, 1)||##||rgba(255, 255, 204, 1)||##||rgba(242, 255, 0, 0.51)||##||3||##||1||##||rgba(0, 0, 0, 0.82)||##||rgba(0, 0, 0, 0.82)||##||20||##||25||##||rgb(0, 0, 0)||##||rgb(44, 127, 184)||##||200||##||uppercase||##||center||##||Allerta||##||10||##||10||##||40||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||50||##||30||##||5||##||rgb(0, 0, 0)||##||20||##||100||##||14||##||rgb(0, 0, 0)||##||rgb(255, 0, 0)||##||1px||##||rgb(0, 0, 0)||##||rgb(176, 102, 102)||##||13||##||rgb(255, 255, 255)||##||rgb(245, 245, 245)||##||200||##||capitalize||##||center||##||Anonymous+Pro||##||5||##||10||##||1000||##||1||##||fas fa-angle||##||30||##||0||##||rgba(0, 0, 0, 0.5)||##||rgba(255, 255, 255, 0.82)||##||rgba(0, 0, 0, 0.92)||##||rgba(255, 13, 0, 1)||##||1||##||10||##||10||##||rgba(0, 0, 0, 0.83)||##||rgba(255, 0, 0, 0.81)||##||0||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||15||##||20||##||rgb(0, 0, 0)||##||rgb(143, 110, 0)||##||100||##||capitalize||##||center||##||Amaranth||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-20') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_box_border_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=11||##||11||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||5||##||rgba(36, 33, 33, 0.82)||##||rgba(235, 23, 23, 1)||##||rgba(194, 194, 194, 1)||##||rgba(0, 28, 105, 0.9)||##||20||##||25||##||rgb(11, 19, 66)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Aclonica||##||5||##||5||##||50||##||rgb(9, 0, 107)||##||5||##||5||##||1||##||20||##||100||##||15||##||rgb(0, 0, 0)||##||rgb(255, 0, 0)||##||1px||##||rgb(0, 0, 0)||##||rgb(255, 13, 0)||##||6||##||rgb(255, 255, 255)||##||rgb(245, 245, 245)||##||200||##||uppercase||##||center||##||Amaranth||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||22||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 255, 255, 0.82)||##||rgba(10, 10, 10, 1)||##||rgba(173, 9, 0, 1)||##||1||##||10||##||10||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 13, 0, 0.82)||##||1||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(255, 255, 255)||##||rgb(33, 33, 33)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||Save";

    }

    $items = $wpdb->get_results('SELECT * FROM ' . $item_table . ' ORDER BY title ASC', ARRAY_A);
    $itemsId = "";
    foreach ($items as $item) {
      if ($itemsId) {
        $itemsId .= ",";
      }
      $itemsId .= $item['id'];
    }

    $wpdb->query($wpdb->prepare("INSERT INTO {$style_table} (name, style_name, css, itemids) VALUES ( %s, %s, %s, %s)", array($name, $style_name, $css, $itemsId)));
    $redirect_id = $wpdb->insert_id;

    if ($redirect_id == 0) {
      $url = admin_url("admin.php?page=sbs-6310-service-box");
    } else if ($redirect_id != 0) {
      $url = admin_url("admin.php?page=sbs-6310-template-11-20&styleid=$redirect_id");
    }
    wp_register_script('cnvb-6310-redirect-script', '');
    wp_enqueue_script('cnvb-6310-redirect-script');
    wp_add_inline_script('cnvb-6310-redirect-script', "document.location.href = '" . $url . "';");
  }
} else {

?>
  <div class="sbs-6310">
    <h1>Select Template</h1>

    <!-- ********************* template 11 start ***************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-11-parallax">
          <div class="sbs-6310-template-preview-11-common-overlay">
            
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-11">
                <div class="sbs-6310-template-preview-11-icon">
                  <i class="fas fa-balance-scale"></i>
                </div>
                <div class="sbs-6310-template-preview-11-title">Balance</div>
                <div class="sbs-6310-template-preview-11-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at sapien at erat hendrerit.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-11">
                <div class="sbs-6310-template-preview-11-icon">
                  <i class="fas fa-baby-carriage"></i>
                </div>
                <div class="sbs-6310-template-preview-11-title">Baby carriage</div>
                <div class="sbs-6310-template-preview-11-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at sapien at erat hendrerit.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-11">
                <div class="sbs-6310-template-preview-11-icon">
                  <i class="fas fa-project-diagram"></i>
                </div>
                <div class="sbs-6310-template-preview-11-title">Project diagram</div>
                <div class="sbs-6310-template-preview-11-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at sapien at erat hendrerit.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-11">
                <div class="sbs-6310-template-preview-11-icon">
                  <i class="fa fa-globe"></i>
                </div>
                <div class="sbs-6310-template-preview-11-title">web service</div>
                <div class="sbs-6310-template-preview-11-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at sapien at erat hendrerit.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 11
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-11">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ************** template 12 start ****************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-12-parallax">
          <div class="sbs-6310-template-preview-12-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-12">
                <div class="sbs-6310-template-preview-12-icon"><i class="fas fa-network-wired"></i>
                </div>
                <div class="sbs-6310-template-preview-12-title">Newtork</div>
                <div class="sbs-6310-template-preview-12-description">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ornare neque ut felis dignissim, id pretium enim auctor. Curabitur maximus.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-12">
                <div class="sbs-6310-template-preview-12-icon"><i class="service-icon fa fa-globe"></i></div>
                <div class="sbs-6310-template-preview-12-title">Global service </div>
                <div class="sbs-6310-template-preview-12-description">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ornare neque ut felis dignissim, id pretium enim auctor. Curabitur maximus.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-12">
                <div class="sbs-6310-template-preview-12-icon">
                  <i class="fas fa-book-reader"></i>
                </div>
                <div class="sbs-6310-template-preview-12-title">book reader</div>
                <div class="sbs-6310-template-preview-12-description">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ornare neque ut felis dignissim, id pretium enim auctor. Curabitur maximus.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-12">
                <div class="sbs-6310-template-preview-12-icon">
                  <i class="fas fa-birthday-cake"></i>
                </div>
                <div class="sbs-6310-template-preview-12-title">book reader</div>
                <div class="sbs-6310-template-preview-12-description">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ornare neque ut felis dignissim, id pretium enim auctor. Curabitur maximus.
                </div>
                <div class="sbs-6310-template-preview-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 12
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-12">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ************ template 13 end ***************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-13-parallax">
          <div class="sbs-6310-template-preview-13-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=2Gg6Seob5Mg&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>         
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-13">
                <div class="sbs-6310-template-preview-13-icon-wrapper">
                  <div class="sbs-6310-template-preview-13-icon">
                    <i class="fas fa-birthday-cake"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-13-title">
                  Unique Design
                </div>
                <div class="sbs-6310-template-preview-13-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-13-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-13">
                <div class="sbs-6310-template-preview-13-icon-wrapper">
                  <div class="sbs-6310-template-preview-13-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-13-title">
                  World Network
                </div>
                <div class="sbs-6310-template-preview-13-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-13-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-13">
                <div class="sbs-6310-template-preview-13-icon-wrapper">
                  <div class="sbs-6310-template-preview-13-icon">
                    <i class="service-icon fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-13-title">
                  Web Services
                </div>
                <div class="sbs-6310-template-preview-13-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-13-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-13">
                <div class="sbs-6310-template-preview-13-icon-wrapper">
                  <div class="sbs-6310-template-preview-13-icon">
                    <i class="fas fa-book-reader"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-13-title">
                  Project Diagram
                </div>
                <div class="sbs-6310-template-preview-13-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-13-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 13
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-13">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ********************* template 14 end **************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-14-parallax">
          <div class="sbs-6310-template-preview-14-common-overlay">            
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-14-wrapper">
                <div class="sbs-6310-template-preview-14">
                  <div class="sbs-6310-template-preview-14-icon-wrapper">
                    <div class="sbs-6310-template-preview-14-icon">
                      <i class="service-icon fa fa-globe"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-14-title">
                    Web Services
                  </div>
                  <div class="sbs-6310-template-preview-14-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-14-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-14-wrapper">
                <div class="sbs-6310-template-preview-14">
                  <div class="sbs-6310-template-preview-14-icon-wrapper">
                    <div class="sbs-6310-template-preview-14-icon">
                      <i class="fas fa-book-reader"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-14-title">
                    Project Diagram
                  </div>
                  <div class="sbs-6310-template-preview-14-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-14-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-14-wrapper">
                <div class="sbs-6310-template-preview-14">
                  <div class="sbs-6310-template-preview-14-icon-wrapper">
                    <div class="sbs-6310-template-preview-14-icon">
                      <i class="fas fa-network-wired"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-14-title">
                    Global Network
                  </div>
                  <div class="sbs-6310-template-preview-14-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-14-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list"> Template 14
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-14">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ******************** template 15 start *************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-15-parallax">
          <div class="sbs-6310-template-preview-15-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=snYu2JUqSWs&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-15">
                <div class="sbs-6310-template-preview-15-icon-wrapper">
                  <div class="sbs-6310-template-preview-15-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-15-title">
                  Global Network
                </div>
                <div class="sbs-6310-template-preview-15-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-15-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-15">
                <div class="sbs-6310-template-preview-15-icon-wrapper">
                  <div class="sbs-6310-template-preview-15-icon">
                    <i class="fas fa-book-reader"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-15-title">
                  Project Diagram
                </div>
                <div class="sbs-6310-template-preview-15-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-15-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-15">
                <div class="sbs-6310-template-preview-15-icon-wrapper">
                  <div class="sbs-6310-template-preview-15-icon">
                    <i class="service-icon fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-15-title">
                  Web Services
                </div>
                <div class="sbs-6310-template-preview-15-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-15-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 15
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-15">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ********* template 16 start ********* -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-16-parallax">
          <div class="sbs-6310-template-preview-16-common-overlay"> 
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=B0vFAahL5Cg&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-16">
                <div class="sbs-6310-template-preview-16-icon">
                  <div class="sbs-6310-template-preview-16-icon-i">
                    <i class="service-icon fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-16-content">
                  <div class="sbs-6310-template-preview-16-title">
                    Web Services
                  </div>
                  <div class="sbs-6310-template-preview-16-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean in volutpat elit. Class aptent taciti.</div>
                  <div class="sbs-6310-template-preview-16-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-16">
                <div class="sbs-6310-template-preview-16-icon">
                  <div class="sbs-6310-template-preview-16-icon-i">
                    <i class="fas fa-book-reader"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-16-content">
                  <div class="sbs-6310-template-preview-16-title">
                    Project Diagram
                  </div>
                  <div class="sbs-6310-template-preview-16-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean in volutpat elit. Class aptent taciti.</div>
                  <div class="sbs-6310-template-preview-16-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-16">
                <div class="sbs-6310-template-preview-16-icon">
                  <div class="sbs-6310-template-preview-16-icon-i">
                    <i class="fas fa-network-wired"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-16-content">
                  <div class="sbs-6310-template-preview-16-title">
                    Global Network
                  </div>
                  <div class="sbs-6310-template-preview-16-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean in volutpat elit. Class aptent taciti.</div>
                  <div class="sbs-6310-template-preview-16-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 16
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-16">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ****************** template 17 start **************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-17-parallax">
          <div class="sbs-6310-template-preview-17-common-overlay">
          <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=ZWT6qRWoWgg&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-17">
                <div class="sbs-6310-template-preview-17-icon1">
                  <div class="sbs-6310-template-preview-17-hover-1"></div>
                  <div class="sbs-6310-template-preview-17-hover-2"></div>
                  <div class="sbs-6310-template-preview-17-icons">
                    <i class="fa fa-laptop"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-17-content">
                  <div class="sbs-6310-template-preview-17-title">
                    Web Design
                  </div>
                  <div class="sbs-6310-template-preview-17-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ultrices sapien vel quam luctus pulvinar. Etiam at.
                  </div>
                  <div class="sbs-6310-template-preview-13-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-17">
                <div class="sbs-6310-template-preview-17-icon1">
                  <div class="sbs-6310-template-preview-17-hover-1"></div>
                  <div class="sbs-6310-template-preview-17-hover-2"></div>
                  <div class="sbs-6310-template-preview-17-icons">
                    <i class="fas fa-network-wired"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-17-content">
                  <div class="sbs-6310-template-preview-17-title">
                    Global Network
                  </div>
                  <div class="sbs-6310-template-preview-17-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ultrices sapien vel quam luctus pulvinar. Etiam at.
                  </div>
                  <div class="sbs-6310-template-preview-13-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-17">
                <div class="sbs-6310-template-preview-17-icon1">
                  <div class="sbs-6310-template-preview-17-hover-1"></div>
                  <div class="sbs-6310-template-preview-17-hover-2"></div>
                  <div class="sbs-6310-template-preview-17-icons">
                    <i class="service-icon fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-17-content">
                  <div class="sbs-6310-template-preview-17-title">
                    Web Services
                  </div>
                  <div class="sbs-6310-template-preview-17-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ultrices sapien vel quam luctus pulvinar. Etiam at.
                  </div>
                  <div class="sbs-6310-template-preview-13-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list"> Template 17
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-17">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ***************** template 18 start**************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-18-parallax">
          <div class="sbs-6310-template-preview-18-common-overlay">            
            <div class="sbs_6310_team_style_18_background">
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-18">
                  <div class="sbs-6310-template-preview-18-title">
                    Web Services
                  </div>
                  <div class="sbs-6310-template-preview-18-icon">
                    <i class="service-icon fa fa-globe"></i>
                  </div>
                  <div class="sbs-6310-template-preview-18-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.
                  </div>
                  <div class="sbs-6310-template-preview-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-18">
                  <div class="sbs-6310-template-preview-18-title">
                    Global Network
                  </div>
                  <div class="sbs-6310-template-preview-18-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                  <div class="sbs-6310-template-preview-18-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.
                  </div>
                  <div class="sbs-6310-template-preview-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-18">
                  <div class="sbs-6310-template-preview-18-title">
                    Project Diagram
                  </div>
                  <div class="sbs-6310-template-preview-18-icon">
                    <i class="fas fa-book-reader"></i>
                  </div>
                  <div class="sbs-6310-template-preview-18-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.
                  </div>
                  <div class="sbs-6310-template-preview-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-18">
                  <div class="sbs-6310-template-preview-18-title">
                    Web Design
                  </div>
                  <div class="sbs-6310-template-preview-18-icon">
                    <i class="fa fa-laptop"></i>
                  </div>
                  <div class="sbs-6310-template-preview-18-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.
                  </div>
                  <div class="sbs-6310-template-preview-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list"> Template 18
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-18">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- **************** template 19 start ******** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-19-parallax">
          <div class="sbs-6310-template-preview-19-common-overlay">            
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-19">
                  <div class="sbs-6310-template-preview-19-icon-wrapper">
                    <div class="sbs-6310-template-preview-19-icon">
                      <i class="service-icon fa fa-globe"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-19-title">
                    Web Services
                  </div>
                  <div class="sbs-6310-template-preview-19-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-19-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-19">
                  <div class="sbs-6310-template-preview-19-icon-wrapper">
                    <div class="sbs-6310-template-preview-19-icon">
                      <i class="fas fa-book-reader"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-19-title">
                    Project Diagram
                  </div>
                  <div class="sbs-6310-template-preview-19-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-19-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-19">
                  <div class="sbs-6310-template-preview-19-icon-wrapper">
                    <div class="sbs-6310-template-preview-19-icon">
                      <i class="fas fa-network-wired"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-19-title">
                    Global Network
                  </div>
                  <div class="sbs-6310-template-preview-19-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-19-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-19">
                  <div class="sbs-6310-template-preview-19-icon-wrapper">
                    <div class="sbs-6310-template-preview-19-icon">
                      <i class="fa fa-laptop"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-19-title">
                    Web Design
                  </div>
                  <div class="sbs-6310-template-preview-19-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-19-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list"> Template 19
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-19">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ******************* template 20 start ******* -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-20-parallax">
          <div class="sbs-6310-template-preview-20-common-overlay">            
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-20-f-box">
                  <div class="sbs-6310-template-preview-20-f-box-inner">
                    <div class="sbs-6310-template-preview-20-f-box-front">
                      <div class="sbs-6310-template-preview-20-front">
                        <div class="sbs-6310-template-preview-20-title">
                          Web design
                        </div>
                        <div class="sbs-6310-template-preview-20-icon"> 
                          <i class="fa fa-laptop"></i> 
                        </div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-20-f-box-back">
                      <div class="sbs-6310-template-preview-20-back">
                        <div class="sbs-6310-template-preview-20-description">
                          Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                        </div>
                        <div class="sbs-6310-template-preview-20-read-more">
                          <a href="#">read more</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-20-f-box">
                  <div class="sbs-6310-template-preview-20-f-box-inner">
                    <div class="sbs-6310-template-preview-20-f-box-front">
                      <div class="sbs-6310-template-preview-20-front">
                        <div class="sbs-6310-template-preview-20-title">
                          Project Diagram
                        </div>
                        <div class="sbs-6310-template-preview-20-icon"> 
                          <i class="fas fa-book-reader"></i> 
                        </div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-20-f-box-back">
                      <div class="sbs-6310-template-preview-20-back">
                        <div class="sbs-6310-template-preview-20-description">
                          Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                        </div>
                        <div class="sbs-6310-template-preview-20-read-more">
                          <a href="#">read more</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-20-f-box">
                  <div class="sbs-6310-template-preview-20-f-box-inner">
                    <div class="sbs-6310-template-preview-20-f-box-front">
                      <div class="sbs-6310-template-preview-20-front">
                        <div class="sbs-6310-template-preview-20-title">
                          Web Services
                        </div>
                        <div class="sbs-6310-template-preview-20-icon"> 
                          <i class="service-icon fa fa-globe"></i> 
                        </div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-20-f-box-back">
                      <div class="sbs-6310-template-preview-20-back">
                        <div class="sbs-6310-template-preview-20-description">
                          Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                        </div>
                        <div class="sbs-6310-template-preview-20-read-more">
                          <a href="#">read more</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-4">
                <div class="sbs-6310-template-preview-20-f-box">
                  <div class="sbs-6310-template-preview-20-f-box-inner">
                    <div class="sbs-6310-template-preview-20-f-box-front">
                      <div class="sbs-6310-template-preview-20-front">
                        <div class="sbs-6310-template-preview-20-title">
                          Global Network
                        </div>
                        <div class="sbs-6310-template-preview-20-icon"> 
                          <i class="fas fa-network-wired"></i> 
                        </div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-20-f-box-back">
                      <div class="sbs-6310-template-preview-20-back">
                        <div class="sbs-6310-template-preview-20-description">
                          Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                        </div>
                        <div class="sbs-6310-template-preview-20-read-more">
                          <a href="#">read more</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list"> Template 20
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-20">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <div id="sbs-6310-modal-add" class="sbs-6310-modal" style="display: none">
      <div class="sbs-6310-modal-content sbs-6310-modal-sm">
        <form action="" method="post">
          <div class="sbs-6310-modal-header">
            Create  Item
            <div class="sbs-6310-close">&times;</div>
          </div>
          <div class="sbs-6310-modal-body-form">
            <?php wp_nonce_field("sbs-6310-nonce-field") ?>
            <input type="hidden" name="style" id="sbs-6310-style-hidden" />
            <table border="0" width="100%" cellpadding="10" cellspacing="0">
              <tr>
                <td width="90"><label class="sbs-6310-form-label" for="icon_name">Service Name:</label></td>
                <td><input type="text" required="" name="style_name" id="style_name" value="" class="sbs-6310-form-input" placeholder="Service Name" style="width: 265px" /></td>
              </tr>
            </table>
          </div>
          <div class="sbs-6310-modal-form-footer">
            <button type="button" name="close" class="sbs-6310-btn-danger sbs-6310-pull-right">Close</button>
            <input type="submit" name="submit" class="sbs-6310-btn-primary sbs-6310-pull-right sbs-6310-margin-right-10" value="Save" />
          </div>
        </form>
        <br class="sbs-6310-clear" />
      </div>
    </div>

    <script>
      jQuery(document).ready(function() {
        jQuery("body").on("click", ".sbs_6310_choosen_style", function() {
          jQuery("#sbs-6310-modal-add").fadeIn(500);
          jQuery("#sbs-6310-style-hidden").val(jQuery(this).attr("id"));
          jQuery("body").css({
            "overflow": "hidden"
          });
          return false;
        });

        jQuery("body").on("click", ".sbs-6310-close, .sbs-6310-btn-danger", function() {
          jQuery("#sbs-6310-modal-add").fadeOut(500);
          jQuery("body").css({
            "overflow": "initial"
          });
        });
        jQuery(window).click(function(event) {
          if (event.target == document.getElementById('sbs-6310-modal-add')) {
            jQuery("#sbs-6310-modal-add").fadeOut(500);
            jQuery("body").css({
              "overflow": "initial"
            });
          }
        });
      });
    </script>

  <?php } ?>