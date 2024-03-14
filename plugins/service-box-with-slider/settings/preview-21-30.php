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
    if ($_POST['style'] == 'template-21') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=220||##||220||##||3||##||1||##||1||##||2||##||transparent||##||rgba(227, 227, 227, 0.79)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgba(0, 107, 161, 1)||##||rgba(4, 91, 111, 1)||##||25||##||15||##||rgb(255, 255, 255)||##||rgb(8, 8, 8)||##||200||##||capitalize||##||center||##||Anton||##||10||##||30||##||45||##||rgb(255, 255, 255)||##||10||##||10||##||1||##||15||##||25||##||100||##||15||##||rgb(0, 0, 0)||##||rgb(255, 0, 0)||##||1px||##||rgb(255, 255, 255)||##||rgb(255, 13, 0)||##||30||##||rgb(255, 255, 255)||##||rgb(245, 245, 245)||##||200||##||capitalize||##||center||##||Anton||##||0||##||10||##||1000||##||1||##||fas fa-angle||##||15||##||1||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 255, 255, 0.82)||##||rgba(23, 23, 23, 1)||##||rgba(230, 205, 16, 0.82)||##||1||##||10||##||10||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 0, 0, 0.82)||##||1||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||21||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Arimo||##||5||##||5||##||Save";
      
    } else if ($_POST['style'] == 'template-22') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_icon_border_size,sbs_6310_icon_border_color,sbs_6310_icon_border_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=20||##||20||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||rgba(235, 238, 240, 1)||##||rgba(235, 238, 240, 1)||##||0||##||4||##||rgba(0, 0, 0, 1)||##||rgba(0, 102, 255, 1)||##||20||##||15||##||rgb(6, 18, 43)||##||rgb(255, 169, 0)||##||100||##||capitalize||##||center||##||Anton||##||10||##||10||##||35||##||rgb(255, 169, 0)||##||rgb(255, 169, 0)||##||25||##||25||##||1||##||rgba(122, 122, 122, 0.83)||##||rgba(255, 169, 0, 1)||##||1||##||20||##||100||##||15||##||rgb(0, 0, 0)||##||rgb(240, 0, 0)||##||1px||##||rgb(0, 0, 0)||##||rgb(255, 13, 0)||##||15||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Allerta||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||30||##||rgba(26, 72, 199, 0.81)||##||rgba(230, 0, 0, 0.81)||##||rgba(56, 35, 35, 0.81)||##||rgba(0, 219, 175, 0.81)||##||1||##||11||##||11||##||rgba(255, 0, 132, 0.82)||##||rgba(240, 176, 0, 0.82)||##||32||##||4||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(89, 89, 89)||##||rgb(89, 89, 89)||##||100||##||capitalize||##||center||##||Arimo||##||10||##||10||##||Save";
    }
    else if ($_POST['style'] == 'template-23') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_color,sbs_6310_icon_border_width,sbs_6310_icon_border_color,sbs_6310_icon_border_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=21||##||21||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||10||##||rgba(58, 125, 145, 1)||##||rgba(10, 52, 69, 1)||##||2||##||3||##||rgba(0, 0, 0, 0.81)||##||rgba(0, 0, 0, 1)||##||20||##||15||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||200||##||capitalize||##||left||##||Anton||##||10||##||10||##||26||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||rgba(255, 255, 255, 0.01)||##||2||##||rgba(255, 255, 255, 1)||##||rgba(0, 0, 0, 1)||##||36||##||100||##||15||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||0||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||10||##||rgb(0, 0, 0)||##||rgb(23, 29, 79)||##||100||##||capitalize||##||left||##||Arimo||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||10||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 255, 255, 0.82)||##||rgba(0, 0, 0, 1)||##||rgba(255, 3, 3, 1)||##||1||##||10||##||10||##||rgba(0, 0, 0, 0.81)||##||rgba(255, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||left||##||Arimo||##||10||##||10||##||Save";
    }
    else if ($_POST['style'] == 'template-24') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_bottom_border_width,sbs_6310_border_bottom_color,sbs_6310_border_bottom_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=22||##||22||##||3||##||1||##||1||##||2||##||transparent||##||rgba(0, 71, 158, 0.43)||##||http://localhost/wordpress/wp-content/uploads/2021/06/1280x1280-small-memory-4k_1540749683.jpg||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||30||##||1||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 255, 255, 0.83)||##||rgba(158, 158, 158, 0.39)||##||rgba(18, 202, 243, 0.81)||##||4||##||rgba(7, 33, 56, 1)||##||rgba(18, 202, 243, 0.81)||##||3||##||2||##||rgba(255, 255, 255, 0.82)||##||rgba(0, 0, 0, 0.15)||##||20||##||15||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||200||##||capitalize||##||center||##||Aclonica||##||10||##||10||##||40||##||rgb(255, 255, 255)||##||rgb(18, 202, 243)||##||15||##||15||##||rgba(18, 202, 243, 0.81)||##||rgba(255, 255, 255, 1)||##||35||##||100||##||15||##||rgb(0, 0, 0)||##||rgb(255, 0, 0)||##||0||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||50||##||rgb(255, 255, 255)||##||rgb(255, 189, 189)||##||100||##||capitalize||##||center||##||Cabin||##||10||##||10||##||1000||##||1||##||fas fa-angle||##||15||##||50||##||rgba(79, 79, 79, 0.38)||##||rgba(0, 0, 0, 0.81)||##||rgba(255, 0, 0, 0.81)||##||rgba(255, 255, 255, 0.81)||##||1||##||10||##||10||##||rgba(0, 0, 0, 0.81)||##||rgba(0, 162, 255, 1)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Arimo||##||10||##||10||##||Save";
    } else if ($_POST['style'] == 'template-25') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background1_color,sbs_6310_box_background2_color,sbs_6310_box_background1_hover_color,sbs_6310_box_background2_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=13||##||13||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||http://localhost/wordpress/wp-content/uploads/2021/06/1280x1280-small-memory-4k_1540749683.jpg||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||30||##||rgba(122, 79, 15, 1)||##||rgba(25, 26, 28, 1)||##||rgba(127, 76, 123, 1)||##||rgba(58, 47, 84, 1)||##||0||##||rgba(0, 162, 255, 0.81)||##||rgba(110, 19, 98, 1)||##||5||##||5||##||rgba(0, 0, 0, 0.81)||##||rgba(46, 0, 40, 1)||##||20||##||15||##||rgb(0, 0, 0)||##||rgb(194, 184, 45)||##||200||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||45||##||rgb(0, 0, 0)||##||rgb(6, 4, 31)||##||10||##||20||##||34||##||100||##||15||##||rgb(163, 99, 16)||##||rgb(58, 47, 84)||##||0||##||rgb(28, 28, 28)||##||rgb(26, 26, 26)||##||0||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Arimo||##||10||##||8||##||1000||##||1||##||fas fa-angle||##||15||##||50||##||rgba(0, 0, 0, 0.83)||##||rgba(255, 255, 255, 0.81)||##||rgba(0, 0, 0, 0.81)||##||rgba(255, 255, 255, 0.81)||##||1||##||10||##||10||##||rgba(0, 0, 0, 0.81)||##||rgba(255, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Arimo||##||10||##||10||##||Save";
      } 
      else if ($_POST['style'] == 'template-26') {
        $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=25||##||25||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||12||##||rgba(233, 30, 99, 1)||##||0||##||rgba(0, 0, 0, 1)||##||rgba(0, 0, 0, 1)||##||2||##||4||##||rgba(204, 204, 204, 1)||##||rgba(204, 126, 157, 1)||##||30||##||15||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||35||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||20||##||20||##||32||##||100||##||15||##||rgb(219, 28, 28)||##||rgb(0, 0, 0)||##||1px||##||rgb(7, 10, 56)||##||rgb(255, 255, 255)||##||5||##||rgb(255, 255, 255)||##||rgb(233, 30, 99)||##||100||##||capitalize||##||center||##||Arimo||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||30||##||rgba(0, 0, 0, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(28, 28, 28, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||15||##||25||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Arimo||##||10||##||10||##||Save";
        }
        else if ($_POST['style'] == 'template-27') {
          $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=26||##||26||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||1||##||rgba(0, 0, 0, 0.84)||##||rgba(0, 181, 222, 1)||##||0||##||12||##||rgba(77, 77, 77, 1)||##||rgba(194, 184, 184, 1)||##||20||##||25||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Anton||##||10||##||10||##||50||##||rgb(5, 5, 5)||##||rgb(0, 0, 0)||##||25||##||0||##||rgba(214, 214, 214, 1)||##||rgba(214, 214, 214, 1)||##||34||##||100||##||15||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(181, 181, 181)||##||rgb(0, 0, 0)||##||15||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Allan||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||50||##||rgba(0, 0, 0, 0.5)||##||rgba(255, 255, 255, 1)||##||rgba(28, 28, 28, 1)||##||rgba(255, 0, 0, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||15||##||25||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Cabin||##||5||##||5||##||Save";
          }
          else if ($_POST['style'] == 'template-28') {
            $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_front_background_color,sbs_6310_backside_background_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_border_bottom_color,sbs_6310_border_bottom_width,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=227||##||227||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||15||##||rgb(131, 199, 222)||##||rgb(1, 135, 134)||##||5||##||rgba(0, 129, 184, 1)||##||rgba(0, 0, 0, 1)||##||3||##||4||##||rgba(255, 255, 255, 0.01)||##||rgba(3, 86, 89, 1)||##||rgba(0, 0, 0, 1)||##||45||##||20||##||25||##||rgb(0, 0, 0)||##||rgb(255, 0, 0)||##||100||##||capitalize||##||center||##||Anton||##||10||##||10||##||50||##||rgb(0, 0, 0)||##||40||##||25||##||1||##||read more||##||20||##||100||##||15||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(13, 214, 144)||##||rgb(255, 255, 255)||##||15||##||rgb(31, 159, 209)||##||rgb(8, 116, 158)||##||100||##||capitalize||##||center||##||Arimo||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||50||##||rgba(0, 0, 0, 0.81)||##||rgba(209, 209, 209, 0.82)||##||rgba(214, 214, 214, 0.82)||##||rgba(255, 0, 0, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||20||##||rgb(0, 0, 0)||##||rgb(255, 0, 0)||##||100||##||capitalize||##||center||##||Arimo||##||10||##||10||##||Save";
            }
            else if ($_POST['style'] == 'template-29') {
              $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color1,sbs_6310_box_border_color2,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_bottom,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=28||##||28||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||30||##||rgba(229, 184, 145, 1)||##||rgba(194, 119, 123, 1)||##||0||##||rgba(168, 0, 42, 1)||##||rgba(168, 0, 42, 1)||##||2||##||3||##||rgba(5, 5, 5, 0.81)||##||rgba(0, 183, 255, 0.82)||##||25||##||28||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Chewy||##||5||##||5||##||30||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||22||##||rgb(48, 15, 26)||##||rgb(48, 15, 26)||##||34||##||100||##||15||##||rgb(217, 204, 21)||##||rgb(0, 0, 0)||##||1px||##||rgb(255, 255, 255)||##||rgb(204, 38, 85)||##||10||##||rgb(48, 15, 26)||##||rgb(204, 38, 85)||##||200||##||lowercase||##||center||##||Amaranth||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||50||##||rgba(0, 0, 0, 0.81)||##||rgba(209, 209, 209, 0.81)||##||rgba(209, 209, 209, 0.81)||##||rgba(255, 0, 0, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||20||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Arimo||##||0||##||0||##||Save";
              }
              else if ($_POST['style'] == 'template-30') {
                $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_border_size,sbs_6310_border_color,sbs_6310_border_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-21-30&styleid=229||##||229||##||2||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||rgba(0, 0, 0, 0.84)||##||rgba(0, 0, 0, 1)||##||rgba(25, 25, 112, 0.84)||##||rgb(70, 130, 180)||##||3||##||5||##||rgba(0, 0, 0, 0.01)||##||rgba(0, 0, 0, 0.82)||##||25||##||15||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Chewy||##||10||##||10||##||50||##||rgb(255, 218, 148)||##||10||##||10||##||1||##||read||##||30||##||100||##||15||##||rgb(23, 23, 23)||##||rgb(0, 0, 0)||##||1px||##||rgb(0, 0, 0)||##||rgb(255, 166, 0)||##||10||##||rgb(255, 235, 205)||##||rgb(255, 166, 0)||##||100||##||capitalize||##||center||##||Arimo||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||15||##||15||##||rgba(0, 0, 0, 0.81)||##||rgba(194, 194, 194, 0.81)||##||rgba(217, 217, 217, 0.81)||##||rgba(255, 0, 0, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||18||##||25||##||rgb(240, 226, 149)||##||rgb(255, 255, 255)||##||200||##||capitalize||##||center||##||Arimo||##||5||##||5||##||Save";
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
      $url = admin_url("admin.php?page=sbs-6310-template-21-30&styleid=$redirect_id");
    }
    wp_register_script('cnvb-6310-redirect-script', '');
    wp_enqueue_script('cnvb-6310-redirect-script');
    wp_add_inline_script('cnvb-6310-redirect-script', "document.location.href = '" . $url . "';");
  }
} else {
?>
  <div class="sbs-6310">
    <h1>Select Template</h1>

    <!-- ******************* template 21 start ************ -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-21-parallax">
          <div class="sbs-6310-template-preview-21-common-overlay">
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-21">
                <div class="sbs-6310-template-preview-21-font-side">
                  <div class="sbs-6310-template-preview-21-icon-wrapper">
                    <div class="sbs-6310-template-preview-21-icon">
                      <i class="fas fa-network-wired"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-21-title">
                    Global Network
                  </div>
                </div>
                <div class="sbs-6310-template-preview-21-backside">
                  <div class="sbs-6310-template-preview-21-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-21-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-21">
                <div class="sbs-6310-template-preview-21-font-side">
                  <div class="sbs-6310-template-preview-21-icon-wrapper">
                    <div class="sbs-6310-template-preview-21-icon">
                      <i class="fas fa-book-reader"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-21-title">
                    Project Diagram
                  </div>
                </div>
                <div class="sbs-6310-template-preview-21-backside">
                  <div class="sbs-6310-template-preview-21-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-21-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-21">
                <div class="sbs-6310-template-preview-21-font-side">
                  <div class="sbs-6310-template-preview-21-icon-wrapper">
                    <div class="sbs-6310-template-preview-21-icon">
                      <i class="service-icon fa fa-globe"></i>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-21-title">
                    Web Services
                  </div>
                </div>
                <div class="sbs-6310-template-preview-21-backside">
                  <div class="sbs-6310-template-preview-21-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-21-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 21
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-21">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ********* template 22 start ****************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-22-parallax">
          <div class="sbs-6310-template-preview-22-common-overlay">
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-22">
                <div class="sbs-6310-template-preview-22-icon-wrapper">
                  <div class="sbs-6310-template-preview-22-icon">
                    <i class="fa fa-laptop"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-22-title">
                  Web Design
                </div>
                <div class="sbs-6310-template-preview-22-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam blanditiis debitis, harum minima
                  mollitia sunt totam. 
                </div>
                <div class="sbs-6310-template-preview-21-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-22">
                <div class="sbs-6310-template-preview-22-icon-wrapper">
                  <div class="sbs-6310-template-preview-22-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-22-title">
                  Global Network
                </div>
                <div class="sbs-6310-template-preview-22-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam blanditiis debitis, harum minima
                  mollitia sunt totam
                </div>
                <div class="sbs-6310-template-preview-21-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-22">
                <div class="sbs-6310-template-preview-22-icon-wrapper">
                  <div class="sbs-6310-template-preview-22-icon">
                    <i class="service-icon fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-22-title">
                  Web Services
                </div>
                <div class="sbs-6310-template-preview-22-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam blanditiis debitis, harum minima
                  mollitia sunt totam
                </div>
                <div class="sbs-6310-template-preview-21-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-22">
                <div class="sbs-6310-template-preview-22-icon-wrapper">
                  <div class="sbs-6310-template-preview-22-icon">
                    <i class="fas fa-book-reader"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-22-title">
                  Article Writing
                </div>
                <div class="sbs-6310-template-preview-22-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam blanditiis debitis, harum minima
                  mollitia sunt totam
                </div>
                <div class="sbs-6310-template-preview-21-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="sbs-6310-template-preview-list">
        Template 22
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-22">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ******************** template 23 Start ************************ -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-23-parallax">
          <div class="sbs-6310-template-preview-23-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=ZWT6qRWoWgg&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-23">
                <div class="sbs-6310-template-preview-23-icon">
                  <i class="fa fa-laptop"></i>
                </div>
                <div class="sbs-6310-template-preview-23-title">
                  Web Design
                </div>
                <div class="sbs-6310-template-preview-23-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur, deleniti eaque
                  excepturi.
                </div>
                <div class="sbs-6310-template-preview-23-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-23">
                <div class="sbs-6310-template-preview-23-icon">
                  <i class="fas fa-book-reader"></i>
                </div>
                <div class="sbs-6310-template-preview-23-title">
                  Aricle Writing
                </div>
                <div class="sbs-6310-template-preview-23-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur, deleniti eaque
                  excepturi.
                </div>
                <div class="sbs-6310-template-preview-23-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-23">
                <div class="sbs-6310-template-preview-23-icon">
                  <i class="fas fa-network-wired"></i>
                </div>
                <div class="sbs-6310-template-preview-23-title">
                  Global Network
                </div>
                <div class="sbs-6310-template-preview-23-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur, deleniti eaque
                  excepturi.
                </div>
                <div class="sbs-6310-template-preview-23-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 23
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-23">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- **************** template 24 Start ************************ -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-24-parallax">
          <div class="sbs-6310-template-preview-24-common-overlay">            
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-24">
                <div class="sbs-6310-template-preview-24-icon">
                  <i class="fa fa-laptop"></i>
                </div>
                <div class="sbs-6310-template-preview-24-title">
                  Web Design
                </div>
                <div class="sbs-6310-template-preview-24-description">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque placerat accumsan sapien, vel
                  tempor augue accumsan sit amet. 
                </div>
                <div class="sbs-6310-template-preview-24-read-more">
                  <a href="#" class="read">Read More</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-24">
                <div class="sbs-6310-template-preview-24-icon">
                  <i class="fas fa-book-reader"></i>
                </div>
                <div class="sbs-6310-template-preview-24-title">
                  Project Diagram
                </div>
                <div class="sbs-6310-template-preview-24-description">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque placerat accumsan sapien, vel
                  tempor augue accumsan sit amet. 
                </div>
                <div class="sbs-6310-template-preview-24-read-more">
                  <a href="#" class="read">Read More</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-24">
                <div class="sbs-6310-template-preview-24-icon">
                  <i class="fas fa-network-wired"></i>
                </div>
                <div class="sbs-6310-template-preview-24-title">
                  Global Network
                </div>
                <div class="sbs-6310-template-preview-24-description">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque placerat accumsan sapien, vel
                  tempor augue accumsan sit amet. 
                </div>
                <div class="sbs-6310-template-preview-24-read-more">
                  <a href="#" class="read">Read More</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 24
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-24">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ************************* template 25 start ********************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-25-parallax">
          <div class="sbs-6310-template-preview-25-common-overlay">            
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-25">
                <div class="sbs-6310-template-preview-25-icon">
                  <i class="service-icon fa fa-globe"></i>
                </div>
                <div class="sbs-6310-template-preview-25-content">
                  <div class="sbs-6310-template-preview-25-title">
                    Web Services
                  </div>
                  <div class="sbs-6310-template-preview-25-description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas non risus a sem hendrerit.
                  </div>
                </div>
                <div class="sbs-6310-template-preview-25-read-more"><a href="#" class="read">Read More</a></div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-25">
                <div class="sbs-6310-template-preview-25-icon">
                  <i class="fas fa-book-reader"></i>
                </div>
                <div class="sbs-6310-template-preview-25-content">
                  <div class="sbs-6310-template-preview-25-title">
                    Project Diagram
                  </div>
                  <div class="sbs-6310-template-preview-25-description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas non risus a sem hendrerit.
                  </div>
                </div>
                <div class="sbs-6310-template-preview-25-read-more"><a href="#" class="read">Read More</a></div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-25">
                <div class="sbs-6310-template-preview-25-icon">
                  <i class="fas fa-network-wired"></i>
                </div>
                <div class="sbs-6310-template-preview-25-content">
                  <div class="sbs-6310-template-preview-25-title">
                    Global Network
                  </div>
                  <div class="sbs-6310-template-preview-25-description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas non risus a sem hendrerit.
                  </div>
                </div>
                <div class="sbs-6310-template-preview-25-read-more"><a href="#" class="read">Read More</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 25
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-25">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ******************** template 26 start ************************ -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-26-parallax">
          <div class="sbs-6310-template-preview-26-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=jo_mNhGxriQ&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-26-container">
                <div class="sbs-6310-template-preview-26-box">
                  <div class="sbs-6310-template-preview-26-icon">
                    <i class="fa fa-laptop"></i>
                  </div>
                  <div class="sbs-6310-template-preview-26-content">
                    <div class="sbs-6310-template-preview-26-title">
                      Web Design
                    </div>
                    <div class="sbs-6310-template-preview-26-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum nihil minus, repellat sit numquam modi.</div>
                    <div class="sbs-6310-template-preview-26-read-more">
                      <a href="#">Read More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-26-container">
                <div class="sbs-6310-template-preview-26-box">
                  <div class="sbs-6310-template-preview-26-icon">
                    <i class="fas fa-book-reader"></i>
                  </div>
                  <div class="sbs-6310-template-preview-26-content">
                    <div class="sbs-6310-template-preview-26-title">
                      Project Diagram
                    </div>
                    <div class="sbs-6310-template-preview-26-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum nihil minus, repellat sit numquam modi.</div>
                    <div class="sbs-6310-template-preview-26-read-more">
                      <a href="#">Read More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-26-container">
                <div class="sbs-6310-template-preview-26-box">
                  <div class="sbs-6310-template-preview-26-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                  <div class="sbs-6310-template-preview-26-content">
                    <div class="sbs-6310-template-preview-26-title">
                      Global Network
                    </div>
                    <div class="sbs-6310-template-preview-26-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum nihil minus, repellat sit numquam modi.</div>
                    <div class="sbs-6310-template-preview-26-read-more">
                      <a href="#">Read More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 26
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-26">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- **************** template 27 start *************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-27-parallax">
          <div class="sbs-6310-template-preview-27-common-overlay">           
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-27_box">
                <div class="sbs-6310-template-preview-27_icon">
                  <i class="fa fa-code"></i>
                </div>
                <div class="sbs-6310-template-preview-27-title">
                  Developer
                </div>
                <div class="sbs-6310-template-preview-27-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti, ducimus Lorem ipsum dolor sit
                  amet.</div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-27_box">
                <div class="sbs-6310-template-preview-27_icon">
                  <i class="fas fa-network-wired"></i>
                </div>
                <div class="sbs-6310-template-preview-27-title">
                  Global Network
                </div>
                <div class="sbs-6310-template-preview-27-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti, ducimus Lorem ipsum dolor sit
                  amet.</div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-27_box">
                <div class="sbs-6310-template-preview-27_icon">
                  <i class="fas fa-book-reader"></i>
                </div>
                <div class="sbs-6310-template-preview-27-title">
                  Project Diagram
                </div>
                <div class="sbs-6310-template-preview-27-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti, ducimus Lorem ipsum dolor sit
                  amet.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 27
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-27">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ****************  template 28 start ********************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-28-parallax">
          <div class="sbs-6310-template-preview-28-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs_6310_team_style_28_background">
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-28-wrapper">
                  <div class="sbs-6310-template-preview-28-container">
                    <div class="sbs-6310-template-preview-28-front">
                      <div class="sbs-6310-template-preview-28-inner">
                        <div class="sbs-6310-template-preview-28-title">
                          Global Network
                        </div>
                        <div class="sbs-6310-template-preview-28-icon">
                          <i class="fas fa-network-wired"></i>
                        </div>

                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-28-back">
                      <div class="sbs-6310-template-preview-28-inner">
                        <div class="sbs-6310-template-preview-28-description">
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias cum repellat velit quae suscipitc.
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-28-wrapper">
                  <div class="sbs-6310-template-preview-28-container">
                    <div class="sbs-6310-template-preview-28-front">
                      <div class="sbs-6310-template-preview-28-inner">
                        <div class="sbs-6310-template-preview-28-title">
                          Project Diagram
                        </div>
                        <div class="sbs-6310-template-preview-28-icon">
                          <i class="fas fa-book-reader"></i>
                        </div> 
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-28-back">
                      <div class="sbs-6310-template-preview-28-inner">
                        <div class="sbs-6310-template-preview-28-description">
                          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias cum repellat velit quae suscipitc.
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-28-wrapper">
                  <div class="sbs-6310-template-preview-28-container">
                    <div class="sbs-6310-template-preview-28-front">
                      <div class="sbs-6310-template-preview-28-inner">
                        <div class="sbs-6310-template-preview-28-title">
                          Web Services
                        </div>
                        <div class="sbs-6310-template-preview-28-icon">
                          <i class="service-icon fa fa-globe"></i>
                        </div>
                      </div>
                    </div>
                    <div class="sbs-6310-template-preview-28-back">
                      <div class="sbs-6310-template-preview-28-inner">
                        <div class="sbs-6310-template-preview-28-description">
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias cum repellat velit quae suscipitc.
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 28
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-28">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ************** template 29 start ************* -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-29-parallax">
          <div class="sbs-6310-template-preview-29-common-overlay">            
            <div class="sbs_6310_team_style_19_background">
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-29-wrapper">
                  <div class="sbs-6310-template-preview-29">
                    <div class="sbs-6310-template-preview-29-icon">
                      <i class="service-icon fa fa-globe"></i>
                    </div>
                    <div class="sbs-6310-template-preview-29-title">
                      Web Services
                    </div>
                    <div class="sbs-6310-template-preview-29-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.</div>
                    <div class="sbs-6310-template-preview-29-read-more">
                      <a href="#">Read More</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-29-wrapper">
                  <div class="sbs-6310-template-preview-29">
                    <div class="sbs-6310-template-preview-29-icon">
                      <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="sbs-6310-template-preview-29-title">
                      Project Diagram
                    </div>
                    <div class="sbs-6310-template-preview-29-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.</div>
                    <div class="sbs-6310-template-preview-29-read-more">
                      <a href="#">Read More</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-29-wrapper">
                  <div class="sbs-6310-template-preview-29">
                    <div class="sbs-6310-template-preview-29-icon">
                      <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="sbs-6310-template-preview-29-title">
                      Global Network
                    </div>
                    <div class="sbs-6310-template-preview-29-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quaerat fugit quas veniam perferendis repudiandae sequi, dolore quisquam illum.</div>
                    <div class="sbs-6310-template-preview-29-read-more">
                      <a href="#">Read More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 29
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-29">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ********************* template 30 start ****************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-30-parallax">
          <div class="sbs-6310-template-preview-30-common-overlay">
           <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=-UHi9Dsvatc&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs_6310_team_style_30_background">
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-30">
                  <div class="sbs-6310-template-preview-30-font-side">
                    <div class="sbs-6310-template-preview-30-icon">
                      <i class="fa fa-laptop"></i>
                    </div>
                    <div class="sbs-6310-template-preview-30-title">
                      web designer
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-30-back-side">
                    <div class="sbs-6310-template-preview-30-discription">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod aliquam ut laoreet dolore magna tincidunt.
                    </div>
                    <div class="sbs-6310-template-preview-30-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-30">
                  <div class="sbs-6310-template-preview-30-font-side">
                    <div class="sbs-6310-template-preview-30-icon">
                      <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="sbs-6310-template-preview-30-title">
                      Global Network
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-30-back-side">
                    <div class="sbs-6310-template-preview-30-discription">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod aliquam ut laoreet dolore magna tincidunt.
                    </div>
                    <div class="sbs-6310-template-preview-30-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sbs-6310-col-3">
                <div class="sbs-6310-template-preview-30">
                  <div class="sbs-6310-template-preview-30-font-side">
                    <div class="sbs-6310-template-preview-30-icon">
                      <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="sbs-6310-template-preview-30-title">
                      Project Diagram
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-30-back-side">
                    <div class="sbs-6310-template-preview-30-discription">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod aliquam ut laoreet dolore magna tincidunt.
                    </div>
                    <div class="sbs-6310-template-preview-30-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 30
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-30">Create  Item</button>
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