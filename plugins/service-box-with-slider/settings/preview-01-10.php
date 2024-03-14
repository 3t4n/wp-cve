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

    if ($_POST['style'] == 'template-01') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_hover_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=12||##||12||##||3||##||1||##||1||##||1||##||transparent||##||rgba(102, 153, 204, 1)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||5||##||rgba(0, 107, 105, 0.91)||##||rgba(0, 150, 148, 1)||##||6||##||rgba(240, 233, 232, 0.83)||##||rgba(209, 208, 205, 0.97)||##||20||##||22||##||rgb(135, 235, 255)||##||rgb(0, 0, 0)||##||100||##||uppercase||##||center||##||Arimo||##||10||##||10||##||45||##||rgb(255, 255, 255)||##||rgb(70, 75, 94)||##||0||##||14||##||1||##||25||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(13, 4, 4)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||rgb(59, 187, 189)||##||rgb(245, 245, 245)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||2000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(0, 0, 0, 0.37)||##||rgba(255, 255, 255, 0.82)||##||rgba(0, 0, 0, 0.82)||##||rgba(0, 255, 238, 0.82)||##||1||##||10||##||10||##||rgba(0, 0, 0, 1)||##||rgba(237, 51, 5, 1)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||16||##||rgb(207, 207, 207)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Amaranth||##||2||##||1||##||Save";
    } else if ($_POST['style'] == 'template-02') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_hover_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,social_margin_top,social_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=199||##||199||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||rgba(255, 255, 255, 0.81)||##||rgba(47, 140, 171, 0.83)||##||10||##||rgba(40, 41, 34, 0.82)||##||rgba(0, 0, 0, 1)||##||20||##||22||##||rgb(0, 0, 0)||##||rgb(250, 243, 242)||##||lighter||##||uppercase||##||center||##||Anton||##||10||##||10||##||50||##||rgb(50, 37, 133)||##||rgb(255, 255, 255)||##||10||##||5||##||1||##||test||##||30||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(13, 4, 4)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||rgb(59, 187, 189)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(235, 235, 5, 0.82)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||16||##||rgb(92, 84, 84)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Arimo||##||0||##||0||##||Save";
    }else if ($_POST['style'] == 'template-03') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_size,sbs_6310_box_border_color,sbs_6310_box_border_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,social_margin_top,social_margin_bottom,sbs_6310_icon_background_size,sbs_6310_icon_background_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=200||##||200||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||rgba(201, 226, 233, 0.86)||##||rgba(47, 140, 171, 0.88)||##||2||##||rgba(8, 76, 115, 0.82)||##||rgba(0, 96, 120, 1)||##||20||##||25||##||rgb(0, 0, 0)||##||rgb(15, 7, 5)||##||bold||##||uppercase||##||center||##||Forum||##||10||##||10||##||40||##||rgb(254, 73, 2)||##||rgb(255, 255, 255)||##||1||##||10||##||75||##||rgb(34, 184, 201)||##||1||##||test||##||25||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(13, 4, 4)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||rgb(59, 187, 189)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(235, 235, 5, 0.82)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||16||##||rgb(92, 84, 84)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||0||##||0||##||Save";
    }else if ($_POST['style'] == 'template-04') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_icon_text_align,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=201||##||201||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||0||##||rgba(255, 255, 255, 0.87)||##||rgba(0, 100, 133, 1)||##||2||##||6||##||rgba(0, 0, 0, 1)||##||rgba(82, 1, 23, 0.99)||##||20||##||26||##||rgb(0, 0, 0)||##||rgb(255, 107, 33)||##||bold||##||capitalize||##||center||##||Allerta||##||0||##||5||##||40||##||rgb(250, 71, 0)||##||rgb(255, 255, 255)||##||3||##||9||##||center||##||1||##||read||##||25||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(13, 4, 4)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||rgb(59, 187, 189)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(235, 235, 5, 0.82)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||28||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||5||##||5||##||Save";
     }else if ($_POST['style'] == 'template-05') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_border_size,sbs_6310_box_border_color,sbs_6310_box_border_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_border_size,sbs_6310_icon_box_border_color,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=202||##||202||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||10||##||2||##||rgba(0, 0, 0, 0.83)||##||rgba(20, 38, 110, 0.84)||##||rgba(245, 245, 245, 0.87)||##||rgba(141, 143, 143, 1)||##||5||##||12||##||rgba(222, 206, 202, 0.86)||##||rgba(22, 23, 1, 0.98)||##||16||##||26||##||rgb(0, 0, 0)||##||rgb(250, 243, 242)||##||100||##||uppercase||##||center||##||Anton||##||12||##||14||##||30||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||2||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||rgb(255, 255, 255)||##||1||##||test||##||25||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(13, 4, 4)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||rgb(59, 187, 189)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||left||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(235, 235, 5, 0.82)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||15||##||18||##||rgb(92, 84, 84)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||left||##||Arimo||##||1||##||0||##||Save";
    } else if ($_POST['style'] == 'template-06') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_border_size,sbs_6310_box_border_color,sbs_6310_box_border_hover_color,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_box_size_number,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_bottom,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=203||##||203||##||3||##||1||##||1||##||1||##||transparent||##||rgba(67, 170, 196, 1)||##||http://localhost/wordpress/wp-content/uploads/2021/06/163-1.jpg||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||10||##||1||##||rgba(0, 0, 0, 1)||##||rgba(0, 0, 0, 0.83)||##||rgba(209, 234, 215, 1)||##||rgba(160, 212, 174, 1)||##||1||##||5||##||rgba(255, 255, 255, 0.81)||##||rgba(252, 252, 252, 0.81)||##||20||##||25||##||rgb(0, 0, 0)||##||rgb(0, 0, 0)||##||100||##||uppercase||##||center||##||Allerta+Stencil||##||10||##||10||##||80||##||30||##||rgb(255, 255, 255)||##||rgb(0, 0, 0)||##||15||##||rgb(84, 115, 136)||##||rgb(117, 159, 124)||##||1||##||test||##||30||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(13, 4, 4)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||rgb(59, 187, 189)||##||||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(235, 235, 5, 0.82)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||16||##||rgb(92, 84, 84)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Amaranth||##||0||##||0||##||Save";
    }else if ($_POST['style'] == 'template-07') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_border_top_width_number,sbs_6310_border_top_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=204||##||204||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||1||##||rgba(15, 47, 69, 0.86)||##||rgba(7, 65, 99, 0.82)||##||2||##||rgba(187, 240, 238, 0.81)||##||2||##||rgba(0, 0, 0, 1)||##||rgba(133, 109, 109, 0.81)||##||1||##||4||##||rgba(255, 255, 255, 0.01)||##||rgba(0, 0, 0, 1)||##||20||##||25||##||rgb(0, 0, 0)||##||rgb(250, 243, 242)||##||100||##||uppercase||##||center||##||Amaranth||##||10||##||10||##||41||##||rgb(255, 255, 255)||##||rgb(217, 215, 165)||##||10||##||10||##||test||##||40||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(13, 4, 4)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||rgb(59, 187, 189)||##||||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(235, 235, 5, 0.82)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||16||##||rgb(180, 189, 11)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||0||##||10||##||Save";
    }else if ($_POST['style'] == 'template-08') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_border_hover_width,sbs_6310_border_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_box_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_margin_top,sbs_6310_icon_margin_bottom,sbs_6310_fun_template_button,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=33||##||33||##||3||##||1||##||1||##||1||##||transparent||##||rgba(0, 0, 0, 1)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||10||##||rgba(20, 20, 4, 0.86)||##||rgba(20, 20, 4, 0.86)||##||5||##||rgba(220, 246, 249, 1)||##||0||##||rgba(255, 255, 255, 1)||##||rgba(255, 255, 255, 0.81)||##||1||##||5||##||rgba(255, 255, 255, 0.81)||##||rgba(255, 255, 255, 0.81)||##||20||##||22||##||rgb(167, 173, 54)||##||rgb(167, 173, 54)||##||100||##||uppercase||##||center||##||Amaranth||##||10||##||10||##||40||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||15||##||5||##||1||##||40||##||100||##||14||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||1px||##||rgb(219, 175, 175)||##||rgb(176, 102, 102)||##||10||##||||##||||##||100||##||capitalize||##||center||##||Amaranth||##||10||##||10||##||1000||##||1||##||fas fa-caret||##||30||##||1||##||rgba(105, 71, 71, 0.81)||##||rgba(84, 49, 49, 0.81)||##||rgba(97, 75, 75, 0.81)||##||rgba(143, 89, 89, 0.81)||##||1||##||10||##||10||##||rgba(168, 141, 141, 0.81)||##||rgba(235, 235, 5, 0.82)||##||50||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||16||##||rgb(189, 189, 189)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Amaranth||##||0||##||0||##||Save";
    }
    else if ($_POST['style'] == 'template-09') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_radius,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_box_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_box_size_number,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_color,sbs_6310_icon_border_width,sbs_6310_icon_border_color,sbs_6310_icon_outline_effect_color,sbs_6310_icon_hover_effect_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-01-10&styleid=318||##||318||##||3||##||1||##||1||##||1||##||transparent||##||rgba(5, 0, 0, 1)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||20||##||rgba(0, 24, 26, 0.82)||##||rgba(0, 57, 66, 0.83)||##||1||##||rgba(255, 255, 255, 0.82)||##||rgba(255, 255, 255, 0.81)||##||2||##||5||##||rgba(255, 255, 255, 0.81)||##||rgba(255, 255, 255, 0.81)||##||20||##||22||##||rgb(255, 255, 255)||##||rgb(255, 255, 255)||##||300||##||uppercase||##||center||##||Amaranth||##||5||##||5||##||35||##||80||##||rgb(0, 0, 0)||##||rgb(255, 13, 0)||##||rgb(255, 255, 255)||##||1||##||rgb(112, 167, 199)||##||rgb(0, 0, 0)||##||rgb(198, 198, 198)||##||1||##||read||##||30||##||100||##||15||##||rgb(255, 255, 255)||##||rgb(219, 0, 0)||##||3px||##||rgb(19, 109, 212)||##||rgb(0, 247, 140)||##||20||##||rgb(41, 65, 163)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Arimo||##||10||##||10||##||1000||##||1||##||fas fa-angle||##||15||##||30||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 0, 0, 0.81)||##||rgba(255, 255, 255, 0.81)||##||rgba(186, 9, 9, 0.81)||##||1||##||10||##||10||##||rgba(255, 0, 0, 0.81)||##||rgba(0, 0, 0, 0.81)||##||0||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||14||##||20||##||rgb(175, 179, 98)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Anonymous+Pro||##||5||##||5||##||Save";
    }
    else if ($_POST['style'] == 'template-10') {
      $css = "_wp_http_referer,id,desktop_item_per_row,tablet_item_per_row,mobile_item_per_row,background_type,background_transparent,template_background_color,box_background_image,image_opacity,youtube_video_url,video_opacity,video_opacity_color,item_margin,sbs_6310_box_background_color,sbs_6310_box_background_hover_color,sbs_6310_box_border_width,sbs_6310_box_border_color,sbs_6310_border_hover_color,sbs_6310_box_shadow_width,sbs_6310_box_shadow_blur,sbs_6310_box_shadow_color,sbs_6310_box_shadow_hover_color,sbs_6310_title_font_size,sbs_6310_title_line_height,sbs_6310_title_font_color,sbs_6310_title_font_hover_color,sbs_6310_title_font_weight,sbs_6310_title_text_transform,sbs_6310_title_text_align,sbs_6310_title_font_family,sbs_6310_title_padding_top,sbs_6310_title_padding_bottom,sbs_6310_icon_font_size,sbs_6310_icon_box_size_number,sbs_6310_icon_color,sbs_6310_icon_hover_color,sbs_6310_icon_background_color,sbs_6310_icon_background_hover_color,sbs_6310_icon_border_width,sbs_6310_icon_border_color,sbs_6310_icon_border_hover_color,sbs_6310_fun_template_button,sbs_6310_read_more_text,sbs_6310_read_more_height,sbs_6310_read_more_width,sbs_6310_read_more_font_size,sbs_6310_read_more_font_color,sbs_6310_read_more_font_hover_color,sbs_6310_read_more_border_width,sbs_6310_read_more_box_border_color,sbs_6310_read_more_border_hover_color,sbs_6310_read_more_border_radius,sbs_6310_read_more_background_color,sbs_6310_read_more_background_hover_color,sbs_6310_read_more_font_weight,sbs_6310_read_more_text_transform,sbs_6310_read_more_text_align,sbs_6310_read_more_font_family,sbs_6310_read_more_margin_top,sbs_6310_read_more_margin_bottom,effect_duration,prev_next_active,slider_icon_style,slider_prev_next_icon_size,slider_prev_next_icon_border_radius,slider_prev_next_bgcolor,slider_prev_next_color,slider_prev_next_hover_bgcolor,slider_prev_next_hover_color,indicator_activation,slider_indicator_width,slider_indicator_height,slider_indicator_active_color,slider_indicator_color,slider_indicator_border_radius,slider_indicator_margin,custom_css,search_placeholder,search_align,search_font_color,search_placeholder_font_color,search_height,search_border_width,search_border_color,search_border_radius,sbs_6310_search_margin_bottom,template_details_show_hide,sbs_6310_details_font_size,sbs_6310_details_line_height,sbs_6310_details_font_color,sbs_6310_details_font_hover_color,sbs_6310_details_font_weight,sbs_6310_details_text_transform,sbs_6310_details_text_align,sbs_6310_details_font_family,sbs_6310_details_margin_top,sbs_6310_details_margin_bottom,update_style_change!!##!!/wordpress/wp-admin/admin.php?page=sbs-6310-template-11-20&styleid=319||##||319||##||3||##||1||##||1||##||1||##||transparent||##||rgba(255, 255, 255, 0)||##||||##||rgba(255, 255, 255, 0)||##||||##||.7||##||rgba(255, 255, 255, 0)||##||15||##||rgba(15, 36, 64, 0.84)||##||rgba(15, 36, 64, 0.84)||##||2||##||rgba(0, 0, 0, 0.84)||##||rgba(249, 210, 25, 1)||##||2||##||5||##||rgba(176, 141, 141, 0.81)||##||rgba(130, 181, 27, 0.82)||##||24||##||26||##||rgb(201, 201, 201)||##||rgb(255, 255, 255)||##||300||##||uppercase||##||center||##||Amaranth||##||5||##||5||##||27||##||78||##||rgb(255, 255, 255)||##||rgb(249, 210, 25)||##||rgba(11, 23, 115, 0.08)||##||rgba(94, 94, 94, 0.01)||##||1||##||rgb(255, 255, 255)||##||rgb(249, 210, 25)||##||1||##||read||##||30||##||100||##||15||##||rgb(255, 255, 255)||##||rgb(219, 0, 0)||##||3px||##||rgb(19, 109, 212)||##||rgb(0, 247, 140)||##||20||##||rgb(41, 65, 163)||##||rgb(0, 0, 0)||##||100||##||capitalize||##||center||##||Cabin||##||10||##||10||##||1000||##||1||##||fas fa-angle||##||15||##||10||##||rgba(255, 255, 255, 0.82)||##||rgba(0, 0, 0, 0.81)||##||rgba(40, 50, 184, 0.81)||##||rgba(199, 162, 162, 0.81)||##||1||##||10||##||10||##||rgba(0, 0, 0, 0.82)||##||rgba(255, 13, 0, 0.82)||##||10||##||3||##||||##||Search by Name or Designation||##||flex-end||##||rgb(0, 0, 0)||##||rgb(128, 128, 128)||##||40||##||2||##||rgba(0, 0, 0, 1)||##||50||##||10||##||1||##||15||##||25||##||rgb(179, 179, 179)||##||rgb(255, 255, 255)||##||100||##||capitalize||##||center||##||Arimo||##||5||##||5||##||Save";


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
      $url = admin_url("admin.php?page=sbs-6310-template-01-10&styleid=$redirect_id");
    }
    wp_register_script('cnvb-6310-redirect-script', '');
    wp_enqueue_script('cnvb-6310-redirect-script');
    wp_add_inline_script('cnvb-6310-redirect-script', "document.location.href = '" . $url . "';");
  }
} else {
?>
  <div class="sbs-6310">
    <h1>Select Template</h1>

    <!-- ******************************************
      template 1 start
    ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
        <div class="sbs-6310-padding-15">
          <div class="sbs-6310-template-preview-01-parallax">
            <div class="sbs-6310-template-preview-01-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=8xjf-K-CVBA&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-01">
                <div class="sbs-6310-template-preview-01-icon-wrapper">
                  <div class="sbs-6310-template-preview-01-icon">
                    <i class="fab fa-acquisitions-incorporated"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-01-title">
                  Unique Design
                </div>
                <div class="sbs-6310-template-preview-01-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-01">
                <div class="sbs-6310-template-preview-01-icon-wrapper">
                  <div class="sbs-6310-template-preview-01-icon">
                    <i class="fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-01-title">
                  Web service
                </div>
                <div class="sbs-6310-template-preview-01-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-01">
                <div class="sbs-6310-template-preview-01-icon-wrapper">
                  <div class="sbs-6310-template-preview-01-icon">
                    <i class="fas fa-network-wired"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-01-title">
                  Newtork
                </div>
                <div class="sbs-6310-template-preview-01-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="sbs-6310-template-preview-list">
        Template 1
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-01">Create  Item</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
      template 1 end
    ****************************************** -->
    <!-- ******************************************
      template 2 start
    ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-02-parallax">
          <div class="sbs-6310-template-preview-02-common-overlay">
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-02">
                <div class="sbs-6310-template-preview-02-icon-wrapper">
                  <div class="sbs-6310-template-preview-02-icon">
                    <i class="far fa-snowflake"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-02-title">
                  snowflake
                </div>
                <div class="sbs-6310-template-preview-02-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-02">
                <div class="sbs-6310-template-preview-02-icon-wrapper">
                  <div class="sbs-6310-template-preview-02-icon">
                    <i class="fas fa-award"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-02-title">
                  award
                </div>
                <div class="sbs-6310-template-preview-02-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-02">
                <div class="sbs-6310-template-preview-02-icon-wrapper">
                  <div class="sbs-6310-template-preview-02-icon">
                    <i class="fas fa-project-diagram"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-02-title">
                  project diagram
                </div>
                <div class="sbs-6310-template-preview-02-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-02">
                <div class="sbs-6310-template-preview-02-icon-wrapper">
                  <div class="sbs-6310-template-preview-02-icon">
                    <i class="fas fa-user-friends"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-02-title">
                  Friends forever
                </div>
                <div class="sbs-6310-template-preview-02-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 2
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-02">Create  Item</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
    template 2 end
  ****************************************** -->


    <!-- ******************************************
      template 3 start
    ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-03-parallax">
          <div class="sbs-6310-template-preview-03-common-overlay">
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-03">
                <div class="sbs-6310-template-preview-03-icon-wrapper">
                  <div class="sbs-6310-template-preview-03-icon">
                    <i class="fas fa-universal-access"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-03-title">
                  Universal access
                </div>
                <div class="sbs-6310-template-preview-03-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-03">
                <div class="sbs-6310-template-preview-03-icon-wrapper">
                  <div class="sbs-6310-template-preview-03-icon">
                    <i class="fas fa-unlock-alt"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-03-title">
                  Detective
                </div>
                <div class="sbs-6310-template-preview-03-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-03">
                <div class="sbs-6310-template-preview-03-icon-wrapper">
                  <div class="sbs-6310-template-preview-03-icon">
                    <i class="fas fa-play"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-03-title">
                  Play song
                </div>
                <div class="sbs-6310-template-preview-03-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-03">
                <div class="sbs-6310-template-preview-03-icon-wrapper">
                  <div class="sbs-6310-template-preview-03-icon">
                    <i class="fab fa-acquisitions-incorporated"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-03-title">
                  Unique Design
                </div>
                <div class="sbs-6310-template-preview-03-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 3
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-03">Create  Item</button>
      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
      template 3 end
    ****************************************** -->


    <!-- ******************************************
       template 4 start
     ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-04-parallax">
          <div class="sbs-6310-template-preview-04-common-overlay">
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-04">
                <div class="sbs-6310-template-preview-04-icon-wrapper">
                  <div class="sbs-6310-template-preview-04-icon">
                    <i class="fas fa-plane-arrival"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-04-title">
                  Travel agency
                </div>
                <div class="sbs-6310-template-preview-04-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-04">
                <div class="sbs-6310-template-preview-04-icon-wrapper">
                  <div class="sbs-6310-template-preview-04-icon">
                    <i class="fas fa-box-open"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-04-title">
                  On stop service
                </div>
                <div class="sbs-6310-template-preview-04-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-04">
                <div class="sbs-6310-template-preview-04-icon-wrapper">
                  <div class="sbs-6310-template-preview-04-icon">
                    <i class="fas fa-barcode"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-04-title">
                  Barcode
                </div>
                <div class="sbs-6310-template-preview-04-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="sbs-6310-template-preview-list">
          Template 4
          <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-04">Create  Item</button>
        </div>
        <br class="sbs-6310-clear" />
      </div>
    </div>
    <!-- ******************************************
       template 4 end
     ****************************************** -->



    <!-- ******************************************
       template 5 start
     ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-05-parallax">
          <div class="sbs-6310-template-preview-05-common-overlay">
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-05">
                <div class="sbs-6310-template-preview-05-content-box">
                  <div class="sbs-6310-template-preview-05-icon">
                    <i class="far fa-snowflake"></i>
                  </div>
                  <div class="sbs-6310-template-preview-05-title">
                    Snowflake
                  </div>
                  <div class="sbs-6310-template-preview-05-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-01-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-05">
                <div class="sbs-6310-template-preview-05-content-box">
                  <div class="sbs-6310-template-preview-05-icon">
                    <i class="fab fa-acquisitions-incorporated"></i>
                  </div>
                  <div class="sbs-6310-template-preview-05-title">
                    Unique Design
                  </div>
                  <div class="sbs-6310-template-preview-05-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-01-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-05">
                <div class="sbs-6310-template-preview-05-content-box">
                  <div class="sbs-6310-template-preview-05-icon">
                    <i class="fas fa-award"></i>
                  </div>
                  <div class="sbs-6310-template-preview-05-title">
                    Award
                  </div>
                  <div class="sbs-6310-template-preview-05-description">
                    Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                  </div>
                  <div class="sbs-6310-template-preview-01-read-more">
                    <a href="#">read more</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
      <div class="sbs-6310-template-preview-list">
        Template 5
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-05">Create  Item</button>

      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
       template 5 end
     ****************************************** -->


    <!-- ******************************************
        template 6 start
      ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-06-parallax">
          <div class="sbs-6310-template-preview-06-common-overlay">
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-06">
                <div class="sbs-6310-template-preview-06-icon-wrapper">
                  <div class="sbs-6310-template-preview-06-icon">
                    <i class="fas fa-phone"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-06-title">
                  Call center
                </div>
                <div class="sbs-6310-template-preview-06-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-06">
                <div class="sbs-6310-template-preview-06-icon-wrapper">
                  <div class="sbs-6310-template-preview-06-icon">
                    <i class="fas fa-paint-brush"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-06-title">
                  Printing
                </div>
                <div class="sbs-6310-template-preview-06-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-06">
                <div class="sbs-6310-template-preview-06-icon-wrapper">
                  <div class="sbs-6310-template-preview-06-icon">
                    <i class="fab fa-avianex"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-06-title">
                  Travelling
                </div>
                <div class="sbs-6310-template-preview-06-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 6
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-06">Create  Item</button>

      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
        template 6 end
      ****************************************** -->

    <!-- ******************************************
      template 7 start
    ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-07-parallax">
          <div class="sbs-6310-template-preview-07-common-overlay">
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-07">
                <div class="sbs-6310-template-preview-07-icon-wrapper">
                  <div class="sbs-6310-template-preview-07-icon">
                    <i class="fas fa-mobile-alt"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-07-title">
                  Cell phone
                </div>
                <div class="sbs-6310-template-preview-07-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-07">
                <div class="sbs-6310-template-preview-07-icon-wrapper">
                  <div class="sbs-6310-template-preview-07-icon">
                    <i class="fas fa-laptop-code"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-07-title">
                  Web Design
                </div>
                <div class="sbs-6310-template-preview-07-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-07">
                <div class="sbs-6310-template-preview-07-icon-wrapper">
                  <div class="sbs-6310-template-preview-07-icon">
                    <i class="fas fa-tv"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-07-title">
                  Tv center
                </div>
                <div class="sbs-6310-template-preview-07-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-07">
                <div class="sbs-6310-template-preview-07-icon-wrapper">
                  <div class="sbs-6310-template-preview-07-icon">
                    <i class="fab fa-acquisitions-incorporated"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-07-title">
                  Unique Design
                </div>
                <div class="sbs-6310-template-preview-07-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 07
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-07">Create  Item</button>

      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
      template 7 end
    ****************************************** -->


    <!-- ******************************************
      template 8 start
    ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-08-parallax">
          <div class="sbs-6310-template-preview-08-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=i3IuGdepp4o&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-08">
                <div class="sbs-6310-template-preview-08-icon-wrapper">
                  <div class="sbs-6310-template-preview-08-icon">
                    <i class="fas fa-coffee"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-08-title">
                  Coffee Time
                </div>
                <div class="sbs-6310-template-preview-08-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-08">
                <div class="sbs-6310-template-preview-08-icon-wrapper">
                  <div class="sbs-6310-template-preview-08-icon">
                    <i class="fas fa-palette"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-08-title">
                  Printing Plate
                </div>
                <div class="sbs-6310-template-preview-08-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-08">
                <div class="sbs-6310-template-preview-08-icon-wrapper">
                  <div class="sbs-6310-template-preview-08-icon">
                    <i class="fas fa-stopwatch"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-08-title">
                  Stopwatch
                </div>
                <div class="sbs-6310-template-preview-08-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 08
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-08">Create  Item</button>

      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
      template 8 end
    ****************************************** -->

    <!-- ******************************************
      template 09 start
    ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-09-parallax">
          <div class="sbs-6310-template-preview-09-common-overlay">
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-09">
                <div class="sbs-6310-template-preview-09-icon-wrapper">
                  <div class="sbs-6310-template-preview-09-icon">
                    <i class="fas fa-phone"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-09-title">
                  Call center
                </div>
                <div class="sbs-6310-template-preview-09-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-09">
                <div class="sbs-6310-template-preview-09-icon-wrapper">
                  <div class="sbs-6310-template-preview-09-icon">
                    <i class="fas fa-chess-pawn"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-09-title">
                  Play chess
                </div>
                <div class="sbs-6310-template-preview-09-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-09">
                <div class="sbs-6310-template-preview-09-icon-wrapper">
                  <div class="sbs-6310-template-preview-09-icon">
                    <i class="fab fa-acquisitions-incorporated"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-09-title">
                  Unique Design
                </div>
                <div class="sbs-6310-template-preview-09-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-4">
              <div class="sbs-6310-template-preview-09">
                <div class="sbs-6310-template-preview-09-icon-wrapper">
                  <div class="sbs-6310-template-preview-09-icon">
                    <i class="fa fa-globe"></i>
                  </div>
                </div>
                <div class="sbs-6310-template-preview-09-title">
                  Globle service
                </div>
                <div class="sbs-6310-template-preview-09-description">
                  Aliquam eget pulvinar velit. Quisque dui diam, tincidunt id leo ut, commodo iaculis nulla. Orci varius natoque penatibus.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sbs-6310-template-preview-list">
        Template 09
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-09">Create  Item</button>

      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
      template 09 end
    ****************************************** -->


    <!-- ******************************************
      template 10 start
    ****************************************** -->

    <div class="sbs-6310-row sbs-6310_team-style-boxed">
      <div class="sbs-6310-padding-15">
        <div class="sbs-6310-template-preview-10-parallax">
          <div class="sbs-6310-template-preview-10-common-overlay">
            <iframe src='https://www.youtube.com/embed/?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=&mute=1&allowfullscreen=1&allow=accelerometer&autoplay=1&rel=0' frameborder='0' allowfullscreen></iframe>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-10">
                <div class="sbs-6310-template-preview-10-icon"><i class="far fa-snowflake"></i></div>
                <div class="sbs-6310-template-preview-10-title">snowflake</div>
                <div class="sbs-6310-template-preview-10-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur, deleniti eaque excepturi.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-10">
                <div class="sbs-6310-template-preview-10-icon"><i class="fas fa-award"></i></i></div>
                <div class="sbs-6310-template-preview-10-title">Award </div>
                <div class="sbs-6310-template-preview-10-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur, deleniti eaque excepturi.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
            <div class="sbs-6310-col-3">
              <div class="sbs-6310-template-preview-10">
                <div class="sbs-6310-template-preview-10-icon"><i class="fa fa-globe"></i></div>
                <div class="sbs-6310-template-preview-10-title">Web Design</div>
                <div class="sbs-6310-template-preview-10-description">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequuntur, deleniti eaque excepturi.
                </div>
                <div class="sbs-6310-template-preview-01-read-more">
                  <a href="#">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sbs-6310-template-preview-list">
        Template 10
        <button type="button" class="sbs-6310-btn-success sbs_6310_choosen_style" id="template-10">Create  Item</button>

      </div>
      <br class="sbs-6310-clear" />
    </div>
    <!-- ******************************************
      template 10 end
    ****************************************** -->

    <div id="sbs-6310-modal-add" class="sbs-6310-modal" style="display: none">
      <div class="sbs-6310-modal-content sbs-6310-modal-sm">
        <form action="" method="post">
          <div class="sbs-6310-modal-header">
            Create Service Box
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

  <?php } ?>