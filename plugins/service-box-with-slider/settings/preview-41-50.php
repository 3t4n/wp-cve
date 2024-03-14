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
    if ($_POST['style'] == 'template-41') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_hover_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=49||##||49||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||rgba(168, 207, 189, 0.82)||##||rgba(0, 145, 121, 0.82)||##||0||##||9||##||rgba(8, 5, 5, 0.84)||##||rgba(0, 145, 121, 0.82)||##||20||##||24||##||rgb(74, 67, 67)||##||rgb(0, 0, 0)||##||700||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||40||##||rgb(0, 0, 0)||##||rgb(186, 255, 226)||##||1||##||Read More||##||40||##||100||##||14||##||rgb(31, 27, 27)||##||rgb(26, 22, 22)||##||1px||##||rgb(230, 23, 12)||##||rgb(245, 22, 22)||##||5||##||rgb(242, 239, 239)||##||rgb(168, 255, 219)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||23||##||23||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(255, 153, 0, 0.82)||##||rgba(247, 0, 255, 0.82)||##||40||##||2||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(31, 28, 28)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-42') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color_1,sbs_6310_box_border_color_2,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=42||##||42||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgba(17, 50, 61, 0.84)||##||rgba(242, 254, 220, 0.82)||##||5||##||rgba(0, 0, 0, 1)||##||rgba(0, 0, 0, 1)||##||30||##||36||##||rgb(255, 255, 255)||##||rgb(61, 55, 55)||##||800||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||40||##||rgb(255, 255, 255)||##||rgb(235, 0, 47)||##||40||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(99, 75, 75)||##||rgb(71, 62, 62)||##||5||##||rgb(10, 10, 10)||##||rgb(71, 144, 222)||##||300||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(99, 88, 88, 0.81)||##||rgba(247, 247, 247, 1)||##||rgba(186, 145, 145, 0.92)||##||rgba(255, 255, 255, 1)||##||1||##||10||##||10||##||rgba(140, 101, 101, 0.81)||##||rgba(0, 0, 0, 0.81)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(255, 255, 255)||##||rgb(41, 37, 37)||##||300||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-43') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_title_bottom_border,sbs_6310_title_border_bottom_color,sbs_6310_title_border_bottom_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=43||##||43||##||2||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||1||##||rgba(0, 0, 0, 1)||##||rgba(0, 0, 0, 0.82)||##||rgba(222, 222, 222, 0.85)||##||rgba(143, 143, 143, 1)||##||2||##||rgba(0, 0, 0, 1)||##||20||##||25||##||rgb(56, 56, 56)||##||rgb(255, 255, 255)||##||800||##||capitalize||##||Droid+Sans||##||5||##||5||##||40||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||5||##||1||##||40||##||120||##||16||##||rgb(51, 43, 43)||##||rgb(255, 255, 255)||##||1px||##||rgb(230, 23, 12)||##||rgb(28, 28, 28)||##||5||##||rgb(255, 255, 255)||##||rgb(176, 102, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||0||##||rgba(230, 190, 78, 0.82)||##||rgba(255, 255, 255, 1)||##||rgba(120, 112, 112, 0.92)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(186, 175, 175, 0.81)||##||rgba(153, 147, 147, 0.89)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(84, 84, 84)||##||rgb(255, 255, 255)||##||400||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-44') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_hover_blur,sbs_6310_box_shadow_width,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_width,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=56||##||56||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(129, 237, 247, 0.88)||##||rgb(3, 80, 87)||##||4||##||1||##||rgba(255, 255, 255, 1)||##||rgba(255, 255, 255, 0.84)||##||20||##||25||##||rgb(0, 68, 79)||##||rgb(255, 255, 0)||##||600||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||30||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||80||##||1||##||35||##||100||##||16||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(209, 116, 111)||##||rgb(122, 93, 93)||##||0||##||rgb(0, 107, 111)||##||rgb(48, 179, 191)||##||300||##||capitalize||##||center||##||Cousine||##||5||##||5||##||1000||##||1||##||fas fa-chevron||##||30||##||5||##||rgba(255, 255, 255, 1)||##||rgba(0, 0, 0, 1)||##||rgba(166, 116, 116, 0.81)||##||rgba(89, 66, 66, 0.81)||##||1||##||10||##||10||##||rgba(128, 112, 112, 0.81)||##||rgba(207, 195, 195, 0.81)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(0, 0, 0)||##||rgb(237, 210, 5)||##||300||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-45') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_color,sbs_6310_box_border_width,sbs_6310_box_shadow_hover_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_hover_background_color,sbs_6310_icon_width,sbs_6310_icon_border_width,sbs_6310_icon_border_radius,sbs_6310_icon_border_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=45||##||45||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||10||##||rgba(224, 224, 224, 0.85)||##||rgba(196, 63, 83, 1)||##||rgb(0, 0, 0)||##||1||##||0||##||rgba(255, 255, 255, 0.85)||##||rgba(255, 255, 255, 1)||##||24||##||30||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||600||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||33||##||rgb(13, 12, 12)||##||rgb(0, 0, 0)||##||rgb(204, 204, 204)||##||80||##||3||##||50||##||rgb(0, 0, 0)||##||35||##||100||##||16||##||rgb(255, 0, 0)||##||rgb(0, 0, 0)||##||1px||##||rgb(5, 5, 5)||##||rgb(255, 13, 0)||##||0||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||25||##||20||##||rgba(9, 224, 167, 0.81)||##||rgba(12, 134, 158, 0.81)||##||rgba(0, 123, 224, 0.45)||##||rgba(250, 45, 127, 0.81)||##||1||##||10||##||10||##||rgba(167, 250, 0, 0.81)||##||rgba(232, 0, 213, 0.81)||##||30||##||2||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-46') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_border_radius,sbs_6310_box_shadow_width,sbs_6310_box_shadow_color,sbs_6310_box_hover_shadow_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_width,sbs_6310_icon_background_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=46||##||46||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(221, 221, 221, 1)||##||rgba(196, 255, 208, 0.84)||##||20||##||5||##||rgba(227, 124, 118, 1)||##||rgba(124, 0, 0, 1)||##||24||##||30||##||rgb(166, 0, 0)||##||rgb(0, 0, 0)||##||600||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||40||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||rgb(255, 0, 0)||##||30||##||100||##||16||##||rgb(255, 0, 0)||##||rgb(0, 0, 0)||##||1px||##||rgb(5, 5, 5)||##||rgb(255, 13, 0)||##||5||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||30||##||rgba(255, 174, 0, 0.81)||##||rgba(0, 0, 0, 1)||##||rgba(222, 165, 165, 0.81)||##||rgba(184, 31, 31, 0.81)||##||1||##||10||##||10||##||rgba(240, 224, 0, 0.81)||##||rgba(205, 11, 219, 0.81)||##||30||##||2||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(130, 0, 0)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-47') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_border_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_width,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=29||##||29||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgba(1, 2, 54, 0.18)||##||rgba(119, 136, 153, 1)||##||8||##||2||##||rgba(163, 163, 163, 1)||##||rgba(163, 163, 163, 1)||##||24||##||30||##||rgb(26, 23, 23)||##||rgb(26, 23, 23)||##||600||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||50||##||rgb(245, 245, 245)||##||rgb(209, 30, 30)||##||35||##||120||##||16||##||rgb(255, 0, 0)||##||rgb(0, 0, 0)||##||1px||##||rgb(0, 0, 0)||##||rgb(255, 13, 0)||##||5||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||30||##||rgba(255, 174, 0, 0.81)||##||rgba(0, 0, 0, 1)||##||rgba(222, 165, 165, 0.81)||##||rgba(184, 31, 31, 0.81)||##||1||##||10||##||10||##||rgba(194, 169, 169, 0.81)||##||rgba(153, 139, 139, 0.92)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(18, 17, 17)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-48') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_border_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_color,sbs_6310_box_hover_shadow_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_width,sbs_6310_icon_background_color,sbs_6310_icon_box_shadow_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=48||##||48||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgba(173, 173, 173, 0.83)||##||rgba(0, 143, 122, 1)||##||5||##||rgba(255, 255, 255, 1)||##||rgba(255, 255, 255, 1)||##||24||##||30||##||rgb(26, 23, 23)||##||rgb(255, 255, 255)||##||600||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||50||##||rgb(0, 142, 155)||##||rgb(0, 0, 0)||##||100||##||rgb(216, 239, 248)||##||rgb(249, 248, 113)||##||40||##||120||##||16||##||rgb(255, 255, 255)||##||rgb(99, 71, 69)||##||1px||##||rgb(99, 75, 75)||##||rgb(153, 122, 122)||##||5||##||rgb(105, 27, 222)||##||rgb(18, 150, 45)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||30||##||rgba(255, 174, 0, 0.81)||##||rgba(0, 0, 0, 1)||##||rgba(222, 165, 165, 0.81)||##||rgba(184, 31, 31, 0.81)||##||1||##||10||##||10||##||rgba(194, 169, 169, 0.81)||##||rgba(153, 139, 139, 0.92)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(18, 17, 17)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-49') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_border_radius,sbs_6310_border_width,sbs_6310_box_border_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_color,sbs_6310_box_hover_shadow_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=35||##||35||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||5||##||rgba(255, 255, 255, 1)||##||rgba(97, 53, 72, 1)||##||rgba(97, 53, 72, 1)||##||5||##||rgba(0, 0, 0, 0.87)||##||rgba(0, 0, 0, 1)||##||20||##||25||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||600||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||40||##||rgb(252, 252, 252)||##||rgb(255, 255, 255)||##||30||##||120||##||16||##||rgb(255, 13, 0)||##||rgb(0, 0, 0)||##||1px||##||rgb(26, 26, 26)||##||rgb(255, 13, 0)||##||5||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(163, 137, 137, 0.81)||##||rgba(255, 255, 255, 1)||##||rgba(176, 113, 113, 0.81)||##||rgba(153, 83, 83, 0.81)||##||1||##||10||##||10||##||rgba(255, 238, 0, 1)||##||rgba(230, 14, 219, 1)||##||40||##||2||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||bold||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-50') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_border_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_color,sbs_6310_box_hover_shadow_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_hover_color,sbs_6310_icon_width,sbs_6310_icon_background_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-41-50&styleid=50||##||50||##||3||##||1||##||1||##||2||##||transparent||##||rgba(214, 224, 255, 1)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||10||##||rgba(72, 57, 42, 0.82)||##||rgba(235, 81, 74, 0.7)||##||6||##||rgba(158, 158, 158, 0.01)||##||rgba(0, 0, 0, 1)||##||20||##||25||##||rgb(237, 228, 228)||##||rgb(255, 255, 255)||##||600||##||capitalize||##||center||##||PT+Sans||##||5||##||5||##||35||##||rgb(0, 0, 0)||##||75||##||rgb(235, 81, 74)||##||30||##||120||##||16||##||rgb(255, 13, 0)||##||rgb(0, 0, 0)||##||1px||##||rgb(0, 0, 0)||##||rgb(255, 13, 0)||##||5||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(163, 137, 137, 0.81)||##||rgba(255, 255, 255, 1)||##||rgba(176, 113, 113, 0.81)||##||rgba(153, 83, 83, 0.81)||##||1||##||10||##||10||##||rgba(125, 75, 75, 0.81)||##||rgba(255, 0, 0, 0.81)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(252, 252, 252)||##||rgb(61, 45, 45)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
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
      $url = admin_url("admin.php?page=sbs-6310-template-41-50&styleid=$redirect_id");
    }
    wp_register_script('cnvb-6310-redirect-script', '');
    wp_enqueue_script('cnvb-6310-redirect-script');
    wp_add_inline_script('cnvb-6310-redirect-script', "document.location.href = '" . $url . "';");
  }
} else {

?>
  <div class="sbs-6310">
    <h1>Select Template</h1>

    <!-- ********************* template 41 start ******************* -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-41-parallax">
          <div class="sbs-6310-template-preview-41-common-overlay">          
              <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=ZWT6qRWoWgg&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-41">
                <div class="sbs-6310-template-preview-41-icon-wrapper">
                  <div class="sbs-6310-template-preview-41-icon">
                    <i class="fab fa-acquisitions-incorporated"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-41-title">
                Incorporated
                </div>
                <div class="sbs-6310-template-preview-41-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius
                  natoque
                  penatibus.
                </div>
                <div class="sbs-6310-template-preview-41-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-41">
                <div class="sbs-6310-template-preview-41-icon-wrapper">
                  <div class="sbs-6310-template-preview-41-icon">
                  <i class="fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-41-title">
                Globe
                </div>
                <div class="sbs-6310-template-preview-41-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius
                  natoque
                  penatibus.
                </div>
                <div class="sbs-6310-template-preview-41-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-41">
                <div class="sbs-6310-template-preview-41-icon-wrapper">
                  <div class="sbs-6310-template-preview-41-icon">
                  <i class="fas fa-award"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-41-title">
                Award
                </div>
                <div class="sbs-6310-template-preview-41-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius
                  natoque
                  penatibus.
                </div>
                <div class="sbs-6310-template-preview-41-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 41
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-41">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ********************** template 42 start************************ -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-42-parallax">
          <div class="sbs-6310-template-preview-42-common-overlay">            
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-42">
                <div class="sbs-6310-template-preview-42-wrapper">
                  <div class="sbs-6310-template-preview-42-title">anchor</div>
                  <div class="sbs-6310-template-preview-42-icon">
                    <i class="fas fa-anchor"></i>
                  </div>
                  <div class="sbs-6310-template-preview-42-description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quod nesciunt repudiandae animi nulla maxime. Sunt soluta ipsam ullam assumenda.</div>
                  <div class="sbs-6310-template-preview-42-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-42">
                <div class="sbs-6310-template-preview-42-wrapper">
                  <div class="sbs-6310-template-preview-42-title">cake</div>
                  <div class="sbs-6310-template-preview-42-icon">
                  <i class="fas fa-birthday-cake"></i>
                  </div>
                  <div class="sbs-6310-template-preview-42-description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quod nesciunt repudiandae animi nulla maxime. Sunt soluta ipsam ullam assumenda.</div>
                  <div class="sbs-6310-template-preview-42-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-42">
                <div class="sbs-6310-template-preview-42-wrapper">
                  <div class="sbs-6310-template-preview-42-title">avianex</div>
                  <div class="sbs-6310-template-preview-42-icon">
                  <i class="fab fa-avianex"></i>
                  </div>
                  <div class="sbs-6310-template-preview-42-description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quod nesciunt repudiandae animi nulla maxime. Sunt soluta ipsam ullam assumenda.</div>
                  <div class="sbs-6310-template-preview-42-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 42
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-42">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ************** template 43 end *********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-43-parallax">
          <div class="sbs-6310-template-preview-43-common-overlay">
          <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=i3IuGdepp4o&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-43">
                <div class="sbs-6310-template-preview-43-wrapper">
                  <div class="sbs-6310-template-preview-43-inner">
                    <div class="sbs-6310-template-preview-43-title-wrapper">
                      <div class="sbs-6310-template-preview-43-title">service 1
                        <div class="sbs-6310-template-preview-43-icon"><i class="fab fa-accessible-icon"></i></div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-43-description">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor
                      sit amet, ante. </div>
                    <div class="sbs-6310-template-preview-43-read-more">
                      <a href="#">Read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-43">
                <div class="sbs-6310-template-preview-43-wrapper">
                  <div class="sbs-6310-template-preview-43-inner">
                    <div class="sbs-6310-template-preview-43-title-wrapper">
                      <div class="sbs-6310-template-preview-43-title">service 2
                        <div class="sbs-6310-template-preview-43-icon"><i class="fa fa-globe"></i></div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-43-description">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor
                      sit amet, ante. </div>
                    <div class="sbs-6310-template-preview-43-read-more">
                      <a href="#">Read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-43">
                <div class="sbs-6310-template-preview-43-wrapper">
                  <div class="sbs-6310-template-preview-43-inner">
                    <div class="sbs-6310-template-preview-43-title-wrapper">
                      <div class="sbs-6310-template-preview-43-title">service 3
                        <div class="sbs-6310-template-preview-43-icon"><i class="fa fa-flask"></i></div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-43-description">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor
                      sit amet, ante. </div>
                    <div class="sbs-6310-template-preview-43-read-more">
                      <a href="#">Read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list"> Template 43
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-43">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ***************** template 44 end *********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-44-parallax">
          <div class="sbs-6310-template-preview-44-common-overlay">           
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-44">
                <div class="sbs-6310-template-preview-44-container">
                  <div class="sbs-6310-template-preview-44-icon">
                    <i class="fa fa-flask"></i>
                  </div>
                  <div class="sbs-6310-template-preview-44-wrapper">
                    <div class="sbs-6310-template-preview-44-title">section 1</div>
                    <div class="sbs-6310-template-preview-44-description">
                      Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis.
                    </div>
                    <div class="sbs-6310-template-preview-44-read-more">
                      <a href="#">Read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-44">
                <div class="sbs-6310-template-preview-44-container">
                  <div class="sbs-6310-template-preview-44-icon">
                  <i class="fa fa-globe"></i>
                  </div>
                  <div class="sbs-6310-template-preview-44-wrapper">
                    <div class="sbs-6310-template-preview-44-title">section 2</div>
                    <div class="sbs-6310-template-preview-44-description">
                      Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis.
                    </div>
                    <div class="sbs-6310-template-preview-44-read-more">
                      <a href="#">Read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-44">
                <div class="sbs-6310-template-preview-44-container">
                  <div class="sbs-6310-template-preview-44-icon">
                  <i class="fas fa-award"></i>
                  </div>
                  <div class="sbs-6310-template-preview-44-wrapper">
                    <div class="sbs-6310-template-preview-44-title">section 3</div>
                    <div class="sbs-6310-template-preview-44-description">
                      Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis.
                    </div>
                    <div class="sbs-6310-template-preview-44-read-more">
                      <a href="#">Read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 44
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-44">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ************* template 45 start ******************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-45-parallax">
          <div class="sbs-6310-template-preview-45-common-overlay">           
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-45">
                <div class="sbs-6310-template-preview-45-title">
                  section 1
                </div>
                <div class="sbs-6310-template-preview-45-icon-wrapper">
                  <div class="sbs-6310-template-preview-45-icon">
                    01
                  </div>
                </div>
                <div class="sbs-6310-template-preview-45-description">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni illo ad modi ullam tempore dolores obcaecati quidem iste, porro maiores facere, aut, ut numquam mollitia eaque libero! Aliquam, delectus corrupti?
                </div>
                <div class="sbs-6310-template-preview-45-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-45">
                <div class="sbs-6310-template-preview-45-title">
                  section 2
                </div>
                <div class="sbs-6310-template-preview-45-icon-wrapper">
                  <div class="sbs-6310-template-preview-45-icon">
                    02
                  </div>
                </div>
                <div class="sbs-6310-template-preview-45-description">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni illo ad modi ullam tempore dolores obcaecati quidem iste, porro maiores facere, aut, ut numquam mollitia eaque libero! Aliquam, delectus corrupti?
                </div>
                <div class="sbs-6310-template-preview-45-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-45">
                <div class="sbs-6310-template-preview-45-title">
                  section 3
                </div>
                <div class="sbs-6310-template-preview-45-icon-wrapper">
                  <div class="sbs-6310-template-preview-45-icon">
                    03
                  </div>
                </div>
                <div class="sbs-6310-template-preview-45-description">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni illo ad modi ullam tempore dolores obcaecati quidem iste, porro maiores facere, aut, ut numquam mollitia eaque libero! Aliquam, delectus corrupti?
                </div>
                <div class="sbs-6310-template-preview-45-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 45
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-45">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ****************** template 46 start ********************* -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-46-parallax">
          <div class="sbs-6310-template-preview-46-common-overlay">          
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-46">
                <div class="sbs-6310-template-preview-46-icon-wrapper">
                  <div class="sbs-6310-template-preview-46-icon">
                  <i class="fas fa-birthday-cake"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-46-title">cake</div>
                <div class="sbs-6310-template-preview-46-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur.
                </div>
                <div class="sbs-6310-template-preview-45-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-46">
                <div class="sbs-6310-template-preview-46-icon-wrapper">
                  <div class="sbs-6310-template-preview-46-icon">
                  <i class="fab fa-avianex"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-46-title">avianex</div>
                <div class="sbs-6310-template-preview-46-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur.
                </div>
                <div class="sbs-6310-template-preview-45-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-46">
                <div class="sbs-6310-template-preview-46-icon-wrapper">
                  <div class="sbs-6310-template-preview-46-icon">
                    <i class="fas fa-address-card"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-46-title">Web Design</div>
                <div class="sbs-6310-template-preview-46-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur.
                </div>
                <div class="sbs-6310-template-preview-45-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 46
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-46">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ********************* template 47 start *********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-47-parallax">
          <div class="sbs-6310-template-preview-47-common-overlay">          
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-47">
                <div class="sbs-6310-template-preview-47-font-container">
                  <div class="sbs-6310-template-preview-47-title">
                    section 01
                  </div>
                  <div class="sbs-6310-template-preview-47-icon-wrapper">
                    <div class="sbs-6310-template-preview-47-icon">
                      <i class="fas fa-address-card"></i>
                    </div>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-47-content">
                  <div class="sbs-6310-template-preview-47-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos labore, iste culpa dicta eaque officia quam similique quia sed temporibus at quo, ratione, itaque quibusdam hic nesciunt voluptates aspernatur optio?
                  </div>
                  <div class="sbs-6310-template-preview-47-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-47">
                <div class="sbs-6310-template-preview-47-font-container">
                  <div class="sbs-6310-template-preview-47-title">
                    section 02
                  </div>
                  <div class="sbs-6310-template-preview-47-icon-wrapper">
                    <div class="sbs-6310-template-preview-47-icon">
                    <i class="fas fa-project-diagram"></i>
                    </div>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-47-content">
                  <div class="sbs-6310-template-preview-47-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos labore, iste culpa dicta eaque officia quam similique quia sed temporibus at quo, ratione, itaque quibusdam hic nesciunt voluptates aspernatur optio?
                  </div>
                  <div class="sbs-6310-template-preview-47-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-47">
                <div class="sbs-6310-template-preview-47-font-container">
                  <div class="sbs-6310-template-preview-47-title">
                    section 03
                  </div>
                  <div class="sbs-6310-template-preview-47-icon-wrapper">
                    <div class="sbs-6310-template-preview-47-icon">
                    <i class="fa fa-globe"></i>
                    </div>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-47-content">
                  <div class="sbs-6310-template-preview-47-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos labore, iste culpa dicta eaque officia quam similique quia sed temporibus at quo, ratione, itaque quibusdam hic nesciunt voluptates aspernatur optio?
                  </div>
                  <div class="sbs-6310-template-preview-47-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 47
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-47">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ***************** template 48 start ********************* -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-48-parallax">
          <div class="sbs-6310-template-preview-48-common-overlay">          
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-48">
                <div class="sbs-6310-template-preview-48-icon">
                  <i class="fa fa-globe"></i>
                </div>
                <div class="sbs-6310-template-preview-48-title">Web Design</div>
                <div class="sbs-6310-template-preview-48-description">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-48">
                <div class="sbs-6310-template-preview-48-icon">
                <i class="fas fa-award"></i>
                </div>
                <div class="sbs-6310-template-preview-48-title">award</div>
                <div class="sbs-6310-template-preview-48-description">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-48">
                <div class="sbs-6310-template-preview-48-icon">
                <i class="fas fa-baby-carriage"></i>
                </div>
                <div class="sbs-6310-template-preview-48-title">Carriage</div>
                <div class="sbs-6310-template-preview-48-description">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 48
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-48">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ********************** template 49 start ********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-49-parallax">
          <div class="sbs-6310-template-preview-49-common-overlay">         
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-49">
                <div class="sbs-6310-template-preview-49-content">
                  <div class="sbs-6310-template-preview-49-icon">
                    <i class="fa fa-rocket"></i>
                  </div>
                  <div class="sbs-6310-template-preview-49-title">Rocket</div>
                  <div class="sbs-6310-template-preview-49-description">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium cupiditate delectus deserunt dolores eum ipsa molestiae officiis quasi ratione voluptatum.
                  </div>
                  <div class="sbs-6310-template-preview-49-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-49-icon-bg">
                  <i class="fa fa-rocket"></i>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-49">
                <div class="sbs-6310-template-preview-49-content">
                  <div class="sbs-6310-template-preview-49-icon">
                  <i class="fas fa-balance-scale"></i>
                  </div>
                  <div class="sbs-6310-template-preview-49-title">Balance scale</div>
                  <div class="sbs-6310-template-preview-49-description">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium cupiditate delectus deserunt dolores eum ipsa molestiae officiis quasi ratione voluptatum.
                  </div>
                  <div class="sbs-6310-template-preview-49-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-49-icon-bg">
                <i class="fas fa-balance-scale"></i>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-49">
                <div class="sbs-6310-template-preview-49-content">
                  <div class="sbs-6310-template-preview-49-icon">
                  <i class="fas fa-project-diagram"></i>
                  </div>
                  <div class="sbs-6310-template-preview-49-title">Diagram</div>
                  <div class="sbs-6310-template-preview-49-description">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium cupiditate delectus deserunt dolores eum ipsa molestiae officiis quasi ratione voluptatum.
                  </div>
                  <div class="sbs-6310-template-preview-49-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-49-icon-bg">
                <i class="fas fa-project-diagram"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 49
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-49">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- **********************template 50 start ******************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-50-parallax">
          <div class="sbs-6310-template-preview-50-common-overlay">          
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-50">
                <div class="sbs-6310-template-preview-50-icon">
                  <i class="fa fa-globe"></i>
                </div>
                <div class="sbs-6310-template-preview-50-title">Web Design</div>
                <div class="sbs-6310-template-preview-50-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam cumque, cupiditate debitis dolorem et
                  fugit hic iusto maxime numquam officia.
                </div>
                <div class="sbs-6310-template-preview-50-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-50">
                <div class="sbs-6310-template-preview-50-icon">
                <i class="fas fa-award"></i>
                </div>
                <div class="sbs-6310-template-preview-50-title">award</div>
                <div class="sbs-6310-template-preview-50-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam cumque, cupiditate debitis dolorem et
                  fugit hic iusto maxime numquam officia.
                </div>
                <div class="sbs-6310-template-preview-50-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-50">
                <div class="sbs-6310-template-preview-50-icon">
                <i class="fas fa-network-wired"></i>
                </div>
                <div class="sbs-6310-template-preview-50-title">network</div>
                <div class="sbs-6310-template-preview-50-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam cumque, cupiditate debitis dolorem et
                  fugit hic iusto maxime numquam officia.
                </div>
                <div class="sbs-6310-template-preview-50-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 50
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-50">Create  Item</button>
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