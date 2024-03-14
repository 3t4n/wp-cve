<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_title_font_color,
      #sbs_6310_title_font_size,
      #sbs_6310_title_line_heigh,
      #sbs_6310_title_font_weight,
      #sbs_6310_title_text_transform,
      #sbs_6310_box_radius,
      #sbs_6310_box_background_color,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_box_shadow_hover_blur,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_shadow_hover_color,
      #sbs_6310_box_shadow_blur,
      #sbs_6310_box_shadow_width,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_icon_margin_top,
      #sbs_6310_icon_margin_bottom
      `).on('change', function() {
        
        var sbs_6310_box_radius = parseInt (jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();
        var sbs_6310_box_shadow_hover_blur = jQuery('#sbs_6310_box_shadow_hover_blur').val();
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_margin_top = jQuery('#sbs_6310_icon_margin_top').val();
        var sbs_6310_icon_margin_bottom = jQuery('#sbs_6310_icon_margin_bottom').val();
        var sbs_6310_box_shadow_blur = jQuery('#sbs_6310_box_shadow_blur').val();
        var sbs_6310_box_shadow_width = jQuery('#sbs_6310_box_shadow_width').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();


        var sbs_6310_title_font_color = jQuery('#sbs_6310_title_font_color').val();
        var sbs_6310_title_font_size = jQuery('#sbs_6310_title_font_size').val();
        var sbs_6310_title_line_heigh = jQuery('#sbs_6310_title_line_heigh').val();
        var sbs_6310_title_font_weight = jQuery('#sbs_6310_title_font_weight').val();
        var sbs_6310_title_text_transform = jQuery('#sbs_6310_title_text_transform').val();




        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {        
          border-radius: \${sbs_6310_box_radius}px !important;
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_color} !important;
          background-color: \${sbs_6310_box_background_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');


        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {        
          background-color: \${sbs_6310_box_background_hover_color} !important;
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
    
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {        
          font-size: \${sbs_6310_icon_font_size}px !important;         
          color: \${sbs_6310_icon_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {        
          color: \${sbs_6310_icon_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {        
          margin-top: \${sbs_6310_icon_margin_top}px !important;
      margin-bottom: \${sbs_6310_icon_margin_bottom}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>  .sbs-6310-template-".esc_attr($templateId)."-box:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          top: 20px !important;
          left: calc(50% - (\${sbs_6310_icon_font_size}px + 20px) / 2 ) !important;
          width: calc(\${sbs_6310_icon_font_size}px + 20px) !important;
          height: calc(\${sbs_6310_icon_font_size}px + 20px) !important;
          border-radius: 50% !important;
          color: \${sbs_6310_icon_hover_color} !important;
          background: \${sbs_6310_box_background_color} !important;
          font-size: calc(\${sbs_6310_icon_font_size}px / 2);
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-title-extra {
          font-size:\${sbs_6310_title_font_size}px !important;
          line-height:\${sbs_6310_title_line_heigh}px !important;
          font-weight: \${sbs_6310_title_font_weight} !important;
          text-transform: \${sbs_6310_title_text_transform} !important;
          font-family: \${$titleFontFamily} !important; 
          color: \${sbs_6310_title_font_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>  .sbs-6310-template-".esc_attr($templateId)."-box:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          left: calc(50% - (\${sbs_6310_icon_font_size}px + 20px) / 2 ) !important;
          width: calc(\${sbs_6310_icon_font_size}px + 20px) !important;
          height: calc(\${sbs_6310_icon_font_size}px + 20px) !important;
          color: \${sbs_6310_icon_hover_color} !important;
          background: \${sbs_6310_box_background_color} !important;
          font-size: calc(\${sbs_6310_icon_font_size}px / 2) !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon img{
          width: \${sbs_6310_icon_font_size}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );