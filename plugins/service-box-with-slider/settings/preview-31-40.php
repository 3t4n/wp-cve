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
    if ($_POST['style'] == 'template-31') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_box_border_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=30||##||30||##||3||##||1||##||1||##||2||##||transparent||##||rgba(0, 0, 0, 1)||##||http://localhost/wordpress/wp-content/uploads/2021/07/9.jpg||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||1||##||rgb(252, 84, 94)||##||10||##||rgba(255, 255, 255, 0.86)||##||rgba(255, 84, 87, 0.91)||##||25||##||30||##||rgb(252, 84, 94)||##||rgb(252, 84, 94)||##||100||##||capitalize||##||left||##||Chewy||##||5||##||5||##||35||##||rgb(252, 84, 94)||##||1||##||35||##||120||##||16||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(36, 35, 35)||##||rgb(242, 12, 16)||##||10||##||rgb(255, 138, 140)||##||rgb(242, 12, 16)||##||500||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-chevron||##||30||##||5||##||rgba(171, 146, 146, 0.81)||##||rgba(255, 191, 0, 1)||##||rgba(173, 152, 152, 0.92)||##||rgba(71, 49, 49, 0.81)||##||1||##||10||##||10||##||rgba(120, 83, 83, 0.81)||##||rgba(230, 72, 64, 0.82)||##||10||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-32') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=31||##||31||##||3||##||1||##||1||##||2||##||transparent||##||rgba(0, 0, 0, 1)||##||http://localhost/wordpress/wp-content/uploads/2021/02/schweiz-1.jpg||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||10||##||rgba(5, 13, 36, 1)||##||rgba(3, 190, 242, 0.85)||##||2||##||3||##||rgba(0, 0, 0, 0.84)||##||rgba(0, 0, 0, 0.84)||##||20||##||25||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||600||##||capitalize||##||center||##||PT+Sans||##||0||##||5||##||46||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1||##||35||##||100||##||16||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(255, 255, 255)||##||rgb(34, 196, 204)||##||0||##||rgb(34, 196, 204)||##||rgb(9, 134, 148)||##||500||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-chevron||##||30||##||5||##||rgba(171, 146, 146, 0.81)||##||rgba(255, 191, 0, 1)||##||rgba(173, 152, 152, 0.92)||##||rgba(71, 49, 49, 0.81)||##||1||##||10||##||10||##||rgba(120, 83, 83, 0.81)||##||rgba(230, 72, 64, 0.82)||##||10||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||15||##||18||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Droid+Sans||##||0||##||5||##||Save";
    } else if ($_POST['style'] == 'template-33') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_color,sbs_6310_box_border_width,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_width,sbs_6310_icon_background_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=32||##||32||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||10||##||rgba(44, 58, 71, 1)||##||rgba(44, 58, 71, 1)||##||rgba(243, 71, 9, 1)||##||20||##||20||##||25||##||rgb(247, 247, 247)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||30||##||rgb(44, 58, 71)||##||rgb(49, 86, 128)||##||50||##||rgb(255, 255, 255)||##||40||##||120||##||14||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(36, 35, 35)||##||rgb(18, 18, 18)||##||50||##||rgb(227, 43, 34)||##||rgb(227, 210, 18)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(140, 135, 135, 0.94)||##||rgba(255, 255, 255, 1)||##||rgba(107, 97, 97, 0.91)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(212, 112, 112, 0.81)||##||rgba(153, 26, 26, 0.82)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||18||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-34') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_left_border_width,sbs_6310_left_border_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=71||##||71||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(16, 19, 26, 0.82)||##||rgba(8, 57, 82, 0.82)||##||5||##||rgba(194, 224, 0, 0.82)||##||20||##||25||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||30||##||rgb(255, 255, 255)||##||rgb(194, 224, 0)||##||1||##||Read More||##||40||##||120||##||14||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||0||##||rgb(36, 35, 35)||##||rgb(18, 18, 18)||##||5||##||rgb(91, 145, 163)||##||rgb(8, 5, 41)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(140, 135, 135, 0.94)||##||rgba(255, 255, 255, 1)||##||rgba(107, 97, 97, 0.91)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(212, 112, 112, 0.81)||##||rgba(153, 26, 26, 0.82)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||18||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-35') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=34||##||34||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgb(13, 8, 15)||##||rgba(15, 54, 54, 1)||##||20||##||25||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Francois+One||##||5||##||5||##||50||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||25||##||100||##||15||##||rgb(255, 0, 0)||##||rgb(0, 0, 0)||##||2px||##||rgb(13, 214, 144)||##||rgb(176, 102, 102)||##||15||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Anonymous+Pro||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(140, 135, 135, 0.94)||##||rgba(255, 255, 255, 1)||##||rgba(107, 97, 97, 0.91)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(212, 112, 112, 0.81)||##||rgba(153, 26, 26, 0.82)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||18||##||rgb(77, 69, 69)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-36') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=35||##||35||##||3||##||1||##||1||##||2||##||transparent||##||rgba(0, 0, 0, 1)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgb(186, 102, 6)||##||rgba(255, 255, 255, 1)||##||20||##||25||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||bold||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||50||##||rgb(255, 255, 255)||##||25||##||100||##||15||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||2px||##||rgb(13, 214, 144)||##||rgb(0, 168, 118)||##||15||##||rgb(138, 255, 220)||##||rgb(11, 179, 128)||##||100||##||capitalize||##||center||##||Anonymous+Pro||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(140, 135, 135, 0.94)||##||rgba(255, 255, 255, 1)||##||rgba(107, 97, 97, 0.91)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(212, 112, 112, 0.81)||##||rgba(153, 26, 26, 0.82)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||18||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-37') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_background_bottom_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=36||##||36||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(88, 91, 92, 0.87)||##||rgba(255, 0, 87, 0.85)||##||rgba(34, 56, 55, 0.87)||##||21||##||24||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||600||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||53||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1||##||40||##||100||##||16||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(204, 118, 118)||##||rgb(77, 77, 77)||##||5||##||rgb(94, 82, 82)||##||rgb(18, 150, 45)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||3000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(112, 100, 100, 0.95)||##||rgba(255, 255, 255, 1)||##||rgba(94, 87, 87, 1)||##||rgba(255, 255, 255, 1)||##||1||##||10||##||10||##||rgba(145, 129, 129, 0.92)||##||rgba(255, 22, 10, 1)||##||5||##||2||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||17||##||18||##||rgb(255, 255, 255)||##||rgb(237, 0, 8)||##||300||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-38') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_gradient_color_1,sbs_6310_box_gradient_color_2,sbs_6310_box_gradient_hover_color_1,sbs_6310_box_gradient_hover_color_2,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_align,sbs_6310_title_text_transform,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=37||##||37||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||https://www.youtube.com/watch?v=rUWxSEwctFU||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(204, 4, 177, 1)||##||rgba(125, 7, 133, 1)||##||rgba(204, 4, 177, 1)||##||rgba(204, 4, 177, 1)||##||20||##||24||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||center||##||capitalize||##||Droid+Sans||##||5||##||5||##||50||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1||##||40||##||100||##||18||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||0||##||rgb(99, 75, 75)||##||rgb(36, 35, 35)||##||5||##||rgb(209, 173, 170)||##||rgb(80, 207, 112)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||fas fa-angle||##||30||##||5||##||rgba(161, 143, 143, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(125, 106, 106, 0.92)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(204, 171, 171, 0.81)||##||rgba(191, 98, 94, 0.82)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-39') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=238||##||238||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||https://www.youtube.com/watch?v=rUWxSEwctFU||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgba(25, 13, 25, 1)||##||rgba(129, 34, 12, 1)||##||20||##||24||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||100||##||rgb(255, 255, 255)||##||rgb(255, 151, 76)||##||1||##||Read More||##||40||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||0||##||rgb(166, 51, 45)||##||rgb(36, 35, 35)||##||5||##||rgb(120, 79, 118)||##||rgb(255, 97, 5)||##||100||##||capitalize||##||center||##||Amaranth||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(161, 143, 143, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(125, 106, 106, 0.92)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(204, 171, 171, 0.81)||##||rgba(191, 98, 94, 0.82)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(255, 255, 255)||##||rgb(255, 245, 245)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||Save";
    } else if ($_POST['style'] == 'template-40') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-31-40&styleid=39||##||39||##||3||##||1||##||1||##||2||##||transparent||##||rgba(0, 0, 0, 1)||##||||##||rgba(255, 255, 255, 0)||##||https://www.youtube.com/watch?v=rUWxSEwctFU||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(224, 147, 139, 0.83)||##||20||##||24||##||rgb(0, 0, 0)||##||rgb(48, 47, 47)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||50||##||rgb(33, 15, 77)||##||rgb(0, 120, 168)||##||40||##||100||##||18||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||0||##||rgb(99, 75, 75)||##||rgb(36, 35, 35)||##||5||##||rgb(107, 102, 101)||##||rgb(118, 133, 123)||##||100||##||capitalize||##||center||##||Droid+Sans||##||5||##||5||##||1000||##||1||##||fas fa-angle||##||30||##||5||##||rgba(161, 143, 143, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(125, 106, 106, 0.92)||##||rgba(51, 0, 255, 1)||##||1||##||10||##||10||##||rgba(204, 171, 171, 0.81)||##||rgba(191, 98, 94, 0.82)||##||5||##||5||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||16||##||20||##||rgb(0, 0, 0)||##||rgb(26, 23, 23)||##||100||##||capitalize||##||left||##||Droid+Sans||##||5||##||5||##||Save";
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
      $url = admin_url("admin.php?page=sbs-6310-template-31-40&styleid=$redirect_id");
    }
    wp_register_script('cnvb-6310-redirect-script', '');
    wp_enqueue_script('cnvb-6310-redirect-script');
    wp_add_inline_script('cnvb-6310-redirect-script', "document.location.href = '" . $url . "';");
  }
} else {
?>
  <div class="sbs-6310">
    <h1>Select Template</h1>

    <!-- ********************* template 31 start ******************* -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-31-parallax">
          <div class="sbs-6310-template-preview-31-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-31-wrapper sbs-6310-template-preview-31-flip-right">
                <div class="sbs-6310-template-preview-31">
                  <div class="sbs-6310-template-preview-31-front">
                    <div class="sbs-6310-template-preview-31-icon">
                      <i class="fab fa-battle-net"></i>
                    </div>
                    <div class="sbs-6310-template-preview-31-title">
                    BATTEL
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-31-back">
                    <div class="sbs-6310-template-preview-31-description"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod aliquam ut laoreet dolore magna tincidunt.
                      <div class="sbs-6310-template-preview-31-read-more">
                        <a href="#"> read more</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-31-wrapper sbs-6310-template-preview-31-flip-right">
                <div class="sbs-6310-template-preview-31">
                  <div class="sbs-6310-template-preview-31-front">
                    <div class="sbs-6310-template-preview-31-icon">
                    <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="sbs-6310-template-preview-31-title">
                    DIAGRAM
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-31-back">
                    <div class="sbs-6310-template-preview-31-description"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod aliquam ut laoreet dolore magna tincidunt.
                      <div class="sbs-6310-template-preview-31-read-more">
                        <a href="#"> read more</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-31-wrapper sbs-6310-template-preview-31-flip-right">
                <div class="sbs-6310-template-preview-31">
                  <div class="sbs-6310-template-preview-31-front">
                    <div class="sbs-6310-template-preview-31-icon">
                    <i class="fas fa-baby-carriage"></i>
                    </div>
                    <div class="sbs-6310-template-preview-31-title">
                    CARRIAGE
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-31-back">
                    <div class="sbs-6310-template-preview-31-description"> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod aliquam ut laoreet dolore magna tincidunt.
                      <div class="sbs-6310-template-preview-31-read-more">
                        <a href="#"> read more</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 31
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-31">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ********************** template 32 start************************ -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-32-parallax">
          <div class="sbs-6310-template-preview-32-common-overlay">
          <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=jo_mNhGxriQ&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-32">
                <div class="sbs-6310-template-preview-32-box">
                  <div class="sbs-6310-template-preview-32-icon">
                      <i class="fa fa-laptop" aria-hidden="true"></i>
                    <div class="sbs-6310-template-preview-32-title-extra">Web Design</div>
                  </div>
                  <div class="sbs-6310-template-preview-32-content">
                    <div class="sbs-6310-template-preview-32-title">Web Design</div>
                    <div class="sbs-6310-template-preview-32-description">Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500</div>
                    <div class="sbs-6310-template-preview-32-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-32">
                <div class="sbs-6310-template-preview-32-box">
                  <div class="sbs-6310-template-preview-32-icon">
                    <i class="service-icon fa fa-globe"></i>
                    <div class="sbs-6310-template-preview-32-title-extra">Project Diagram</div>
                  </div>
                  <div class="sbs-6310-template-preview-32-content">
                    <div class="sbs-6310-template-preview-32-title">Project Diagram</div>
                    <div class="sbs-6310-template-preview-32-description">Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500</div>
                    <div class="sbs-6310-template-preview-32-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-32">
                <div class="sbs-6310-template-preview-32-box">
                  <div class="sbs-6310-template-preview-32-icon">
                      <i class="service-icon fa fa-birthday-cake"></i>
                    <div class="sbs-6310-template-preview-32-title-extra">Global Network</div>
                  </div>
                  <div class="sbs-6310-template-preview-32-content">
                    <div class="sbs-6310-template-preview-32-title">Global Network</div>
                    <div class="sbs-6310-template-preview-32-description">Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500</div>
                    <div class="sbs-6310-template-preview-32-read-more">
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
        Template 32
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-32">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ************** template 33 end *********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-33-parallax">
          <div class="sbs-6310-template-preview-33-common-overlay">           
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-33-wrapper">
                <div class="sbs-6310-template-preview-33-icon1">
                  <i class="fas fa-birthday-cake"></i>
                  <div class="sbs-6310-template-preview-33-icon2">
                    <i class="fas fa-birthday-cake"></i>
                    <div class="sbs-6310-template-preview-33-content-wrapper">
                      <div class="sbs-6310-template-preview-33-title">Happy Birthday</div>
                      <div class="sbs-6310-template-preview-33-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</div>
                      <div class="sbs-6310-template-preview-33-read-more">
                        <a href="#"> read more</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-33-wrapper">
                <div class="sbs-6310-template-preview-33-icon1">
                  <i class="service-icon fa fa-globe"></i>
                  <div class="sbs-6310-template-preview-33-icon2">
                    <i class="service-icon fa fa-globe"></i>
                    <div class="sbs-6310-template-preview-33-content-wrapper">
                      <div class="sbs-6310-template-preview-33-title">Web Services</div>
                      <div class="sbs-6310-template-preview-33-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</div>
                      <div class="sbs-6310-template-preview-33-read-more">
                        <a href="#"> read more</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-33-wrapper">
                <div class="sbs-6310-template-preview-33-icon1"><i class="fas fa-quote-left fa2"></i>
                  <div class="sbs-6310-template-preview-33-icon2"><i class="fas fa-quote-right fa1"></i>
                    <div class="sbs-6310-template-preview-33-content-wrapper">
                      <div class="sbs-6310-template-preview-33-title">Quote the day</div>
                      <div class="sbs-6310-template-preview-33-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</div>
                      <div class="sbs-6310-template-preview-33-read-more">
                        <a href="#"> read more</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list"> Template 33
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-33">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ***************** template 34 end *********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-34-parallax">
          <div class="sbs-6310-template-preview-34-common-overlay">           
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-34">
                <div class="sbs-6310-template-preview-34-icon">
                  <div class="sbs-6310-template-preview-34-title">globe</div>
                  <i class="fa fa-globe"></i>
                </div>
                <div class="sbs-6310-template-preview-34-description"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam cumque, cupiditate debitis dolorem et fugit hic iusto maxime numquam officia.
                </div>
                <div class="sbs-6310-template-preview-34-read-more">
                  <a href="#"> read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-34">
                <div class="sbs-6310-template-preview-34-icon">
                  <div class="sbs-6310-template-preview-34-title">flask</div>
                  <i class="fa fa-flask"></i>
                </div>
                <div class="sbs-6310-template-preview-34-description"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam cumque, cupiditate debitis dolorem et fugit hic iusto maxime numquam officia.
                </div>
                <div class="sbs-6310-template-preview-34-read-more">
                  <a href="#"> read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-34">
                <div class="sbs-6310-template-preview-34-icon">
                  <div class="sbs-6310-template-preview-34-title">network</div>
                  <i class="fas fa-network-wired"></i>
                </div>
                <div class="sbs-6310-template-preview-34-description"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam cumque, cupiditate debitis dolorem et fugit hic iusto maxime numquam officia.
                </div>
                <div class="sbs-6310-template-preview-34-read-more">
                  <a href="#"> read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 34
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-34">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ************* template 35 start ******************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-35-parallax">
          <div class="sbs-6310-template-preview-35-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-35">
                <div class="sbs-6310-template-preview-35-wrapper">
                  <div class="sbs-6310-template-preview-35-font sbs-6310-template-preview-35-font1">
                    <div class="sbs-6310-template-preview-35-content">
                      <span class="sbs-6310-template-preview-35-content-hov"></span>
                      <div class="sbs-6310-template-preview-35-title">Web Services</div>
                      <div class="sbs-6310-template-preview-35-description">Java is a class-based, object-oriented programming language that is designed to have as few implementation dependencies as possible.</div>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-35-font sbs-6310-template-preview-35-font2">
                    <div class="sbs-6310-template-preview-35-number">
                      <i class="service-icon fa fa-globe"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-35">
                <div class="sbs-6310-template-preview-35-wrapper">
                  <div class="sbs-6310-template-preview-35-font sbs-6310-template-preview-35-font1">
                    <div class="sbs-6310-template-preview-35-content">
                      <span class="sbs-6310-template-preview-35-content-hov"></span>
                      <div class="sbs-6310-template-preview-35-title">Project Diagram</div>
                      <div class="sbs-6310-template-preview-35-description">Java is a class-based, object-oriented programming language that is designed to have as few implementation dependencies as possible.</div>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-35-font sbs-6310-template-preview-35-font2">
                    <div class="sbs-6310-template-preview-35-number">
                      <i class="fas fa-book-reader"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-35">
                <div class="sbs-6310-template-preview-35-wrapper">
                  <div class="sbs-6310-template-preview-35-font sbs-6310-template-preview-35-font1">
                    <div class="sbs-6310-template-preview-35-content">
                      <span class="sbs-6310-template-preview-35-content-hov"></span>
                      <div class="sbs-6310-template-preview-35-title">Global Network</div>
                      <div class="sbs-6310-template-preview-35-description">Java is a class-based, object-oriented programming language that is designed to have as few implementation dependencies as possible.</div>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-35-font sbs-6310-template-preview-35-font2">
                    <div class="sbs-6310-template-preview-35-number">
                      <i class="fas fa-network-wired"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 35
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-35">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ****************** template 36 start ********************* -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-36-parallax">
          <div class="sbs-6310-template-preview-36-common-overlay">         
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-36-wrapper">
                <div class="sbs-6310-template-preview-36">
                  <div class="sbs-6310-template-preview-36-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                  <div class="sbs-6310-template-preview-36-content">
                    <div class="sbs-6310-template-preview-36-title">Global Network</div>
                    <div class="sbs-6310-template-preview-36-description">I love designing websites and keep things as simple as possible. My goals is to focus on minimalism and conveying the message that you want to send</div>
                    <div class="sbs-6310-template-preview-36-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-36-effect"></div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-36-wrapper">
                <div class="sbs-6310-template-preview-36">
                  <div class="sbs-6310-template-preview-36-icon">
                    <i class="fas fa-book-reader"></i>
                  </div>
                  <div class="sbs-6310-template-preview-36-content">
                    <div class="sbs-6310-template-preview-36-title">Project Diagram</div>
                    <div class="sbs-6310-template-preview-36-description">I love designing websites and keep things as simple as possible. My goals is to focus on minimalism and conveying the message that you want to send</div>
                    <div class="sbs-6310-template-preview-36-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-36-effect"></div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-36-wrapper">
                <div class="sbs-6310-template-preview-36">
                  <div class="sbs-6310-template-preview-36-icon">
                    <i class="fa fa-laptop"></i>
                  </div>
                  <div class="sbs-6310-template-preview-36-content">
                    <div class="sbs-6310-template-preview-36-title">Web Design</div>
                    <div class="sbs-6310-template-preview-36-description">I love designing websites and keep things as simple as possible. My goals is to focus on minimalism and conveying the message that you want to send</div>
                    <div class="sbs-6310-template-preview-36-read-more">
                      <a href="#"> read more</a>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-36-effect"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 36
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-36">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ********************* template 37 start *********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-37-parallax">
          <div class="sbs-6310-template-preview-37-common-overlay">          
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-37-container">
                <div class="sbs-6310-template-preview-37">
                  <div class="sbs-6310-template-preview-37-face sbs-6310-template-preview-37-face1">
                    <div class="sbs-6310-template-preview-37-content">
                      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTFvGPGTGjfhHbyi96VKW1Wk8xxo1S8r3aANA&usqp=CAU">
                      <div class="sbs-6310-template-preview-37-title">Web Design</div>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-37-face sbs-6310-template-preview-37-face2">
                    <div class="sbs-6310-template-preview-37-content">
                      <div class="sbs-6310-template-preview-37-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cum cumque minus iste veritatis provident at.</div>
                      <div class="sbs-6310-template-preview-37-read-more">
                        <a href="#">Read More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-37-container">
                <div class="sbs-6310-template-preview-37">
                  <div class="sbs-6310-template-preview-37-face sbs-6310-template-preview-37-face1">
                    <div class="sbs-6310-template-preview-37-content">
                      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTFvGPGTGjfhHbyi96VKW1Wk8xxo1S8r3aANA&usqp=CAU">
                      <div class="sbs-6310-template-preview-37-title">Project Diagram</div>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-37-face sbs-6310-template-preview-37-face2">
                    <div class="sbs-6310-template-preview-37-content">
                      <div class="sbs-6310-template-preview-37-description">Lorem ipsum dolor sit amet consectetur
                        adipisicing elit. Quas cum cumque minus iste veritatis provident at.</div>
                      <div class="sbs-6310-template-preview-37-read-more">
                        <a href="#">Read More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-37-container">
                <div class="sbs-6310-template-preview-37">
                  <div class="sbs-6310-template-preview-37-face sbs-6310-template-preview-37-face1">
                    <div class="sbs-6310-template-preview-37-content">
                      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTFvGPGTGjfhHbyi96VKW1Wk8xxo1S8r3aANA&usqp=CAU">
                      <div class="sbs-6310-template-preview-37-title">Global Network</div>
                    </div>
                  </div>
                  <div class="sbs-6310-template-preview-37-face sbs-6310-template-preview-37-face2">
                    <div class="sbs-6310-template-preview-37-content">
                      <div class="sbs-6310-template-preview-37-description">Lorem ipsum dolor sit amet consectetur pisicing elit. Quas cum cumque minus iste veritatis provident at.</div>
                      <div class="sbs-6310-template-preview-37-read-more">
                        <a href="#">Read More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 37
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-37">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- ***************** template 38 start ********************* -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-38-parallax">
          <div class="sbs-6310-template-preview-38-common-overlay">            
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-38-container">
                <div class="sbs-6310-template-preview-38">
                  <div class="sbs-6310-template-preview-38-card template-38-cards-item">
                    <div class="sbs-6310-template-preview-38-frame">
                      <div class="sbs-6310-template-preview-38-pic">
                        <img src="https://image.flaticon.com/icons/svg/1496/1496034.svg" alt="" width="120">
                      </div>
                      <div class="sbs-6310-template-preview-38-title">Painting</div>
                    </div>
                    <div class="sbs-6310-template-preview-38-overlay"></div>
                    <div class="sbs-6310-template-preview-38-content">
                      <div class="sbs-6310-template-preview-38-title-2">Painting</div>
                      <div class="sbs-6310-template-preview-38-description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Neque ipsum veritatis id quia cupiditate sed architecto aliquam nostrum unde nam minima voluptas dicta, beatae sint reprehenderit sit ducimus officiis ratione?</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-38-container">
                <div class="sbs-6310-template-preview-38">
                  <div class="sbs-6310-template-preview-38-card template-38-cards-item">
                    <div class="sbs-6310-template-preview-38-frame">
                      <div class="sbs-6310-template-preview-38-pic">
                        <img src="https://image.flaticon.com/icons/png/512/407/407538.png" alt="" width="120">
                      </div>
                      <div class="sbs-6310-template-preview-38-title">Project Diagram</div>
                    </div>
                    <div class="sbs-6310-template-preview-38-overlay"></div>
                    <div class="sbs-6310-template-preview-38-content">
                      <div class="sbs-6310-template-preview-38-title-2">Project Diagram</div>
                      <div class="sbs-6310-template-preview-38-description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Neque ipsum veritatis id quia cupiditate sed architecto aliquam nostrum unde nam minima voluptas dicta, beatae sint reprehenderit sit ducimus officiis ratione?</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-38-container">
                <div class="sbs-6310-template-preview-38">
                  <div class="sbs-6310-template-preview-38-card template-38-cards-item">
                    <div class="sbs-6310-template-preview-38-frame">
                      <div class="sbs-6310-template-preview-38-pic">
                        <img src="https://image.flaticon.com/icons/png/512/407/407514.png" alt="" width="120">
                      </div>
                      <div class="sbs-6310-template-preview-38-title">Lab Test</div>
                    </div>
                    <div class="sbs-6310-template-preview-38-overlay"></div>
                    <div class="sbs-6310-template-preview-38-content">
                      <div class="sbs-6310-template-preview-38-title-2">Lab Test</div>
                      <div class="sbs-6310-template-preview-38-description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Neque ipsum veritatis id quia cupiditate sed architecto aliquam nostrum unde nam minima voluptas dicta, beatae sint reprehenderit sit ducimus officiis ratione?</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 38
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-38">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>

    <!-- ********************** template 39 start ********************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-39-parallax">
          <div class="sbs-6310-template-preview-39-common-overlay">          
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-39">
                <div class="sbs-6310-template-preview-39-container">
                  <div class="sbs-6310-template-preview-39-box">
                    <div class="sbs-6310-template-preview-39-content">
                      <div class="sbs-6310-template-preview-39-icon">01</div>
                      <div class="sbs-6310-template-preview-39-title">Card One</div>
                      <div class="sbs-6310-template-preview-39-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, totam velit? Iure nemo labore inventore?

                      </div>
                      <div class="sbs-6310-template-preview-39-read-more">
                        <a href="#">Read More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-39">
                <div class="sbs-6310-template-preview-39-container">
                  <div class="sbs-6310-template-preview-39-box">
                    <div class="sbs-6310-template-preview-39-content">
                      <div class="sbs-6310-template-preview-39-icon">02</div>
                      <div class="sbs-6310-template-preview-39-title">Card Two</div>
                      <div class="sbs-6310-template-preview-39-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, totam velit? Iure nemo labore inventore?

                      </div>
                      <div class="sbs-6310-template-preview-39-read-more">
                        <a href="#">Read More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-39">
                <div class="sbs-6310-template-preview-39-container">
                  <div class="sbs-6310-template-preview-39-box">
                    <div class="sbs-6310-template-preview-39-content">
                      <div class="sbs-6310-template-preview-39-icon">03</div>
                      <div class="sbs-6310-template-preview-39-title">Card Three</div>
                      <div class="sbs-6310-template-preview-39-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, totam velit? Iure nemo labore inventore?

                      </div>
                      <div class="sbs-6310-template-preview-39-read-more">
                        <a href="#">Read More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 39
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-39">Create  Item</button>
        <button type="button" class="sbs-6310-pro-only">Pro Only</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>


    <!-- **********************template 40 start ******************** -->
    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-40-parallax">
          <div class="sbs-6310-template-preview-40-common-overlay">          
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-40">
                <div class="sbs-6310-template-preview-40-left-section">
                  <div class="sbs-6310-template-preview-40-icon">
                    <i class="fas fa-book-reader"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-40-right-section">
                  <div class="sbs-6310-template-preview-40-title">
                    Project Diagram
                  </div>
                  <div class="sbs-6310-template-preview-40-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit quae dolore officia, facere in fugit sit aliquam, veniam voluptatem, sed quo explicabo nemo!
                  </div>
                  <div class="sbs-6310-template-preview-40-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-40">
                <div class="sbs-6310-template-preview-40-left-section">
                  <div class="sbs-6310-template-preview-40-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-40-right-section">
                  <div class="sbs-6310-template-preview-40-title">
                    Global Network
                  </div>
                  <div class="sbs-6310-template-preview-40-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit quae dolore officia, facere in fugit sit aliquam, veniam voluptatem, sed quo explicabo nemo!
                  </div>
                  <div class="sbs-6310-template-preview-40-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-40">
                <div class="sbs-6310-template-preview-40-left-section">
                  <div class="sbs-6310-template-preview-40-icon">
                    <i class="fa fa-laptop"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-40-right-section">
                  <div class="sbs-6310-template-preview-40-title">
                    Web Design
                  </div>
                  <div class="sbs-6310-template-preview-40-description">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit quae dolore officia, facere in fugit sit aliquam, veniam voluptatem, sed quo explicabo nemo!
                  </div>
                  <div class="sbs-6310-template-preview-40-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">Template 40
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-40">Create  Item</button>
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