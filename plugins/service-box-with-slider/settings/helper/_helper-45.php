<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_border_width,
      #sbs_6310_box_border_color,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_background_color,
      #sbs_6310_box_radius,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_box_shadow_hover_blur,
      #sbs_6310_box_shadow_hover_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_icon_hover_background_color,
      #sbs_6310_icon_border_width,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_border_color,
      #sbs_6310_icon_border_radius,
      #sbs_6310_icon_width      
      `).on('change', function() {
        
        var sbs_6310_box_border_width = parseInt (jQuery('#sbs_6310_box_border_width').val());
        var sbs_6310_box_border_color = jQuery('#sbs_6310_box_border_color').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_radius = parseInt (jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();
        var sbs_6310_box_shadow_hover_blur = parseInt (jQuery('#sbs_6310_box_shadow_hover_blur').val());
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_hover_background_color = jQuery('#sbs_6310_icon_hover_background_color').val();
        var sbs_6310_icon_border_width = parseInt (jQuery('#sbs_6310_icon_border_width').val());
        var sbs_6310_icon_font_size = parseInt (jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_border_color = jQuery('#sbs_6310_icon_border_color').val();
        var sbs_6310_icon_border_radius = jQuery('#sbs_6310_icon_border_radius').val();
        var sbs_6310_icon_width = jQuery('#sbs_6310_icon_width').val();

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {
          border: \${sbs_6310_box_border_width}px solid \${sbs_6310_box_border_color} !important;
          box-shadow: 0 0 \${sbs_6310_box_shadow_hover_blur}px 5px \${sbs_6310_box_shadow_color} !important;
          background-color: \${sbs_6310_box_background_color} !important;
          border-radius: \${sbs_6310_box_radius}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {
          background-color: \${sbs_6310_box_background_hover_color} !important;
          box-shadow: 0 0 \${sbs_6310_box_shadow_hover_blur}px 0 \${sbs_6310_box_shadow_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          color: \${sbs_6310_icon_hover_color} !important;
          background: \${sbs_6310_icon_hover_background_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {          
          width: calc(\${sbs_6310_icon_width}px - (\${sbs_6310_icon_border_width} * 2)px) !important;
          height: calc(\${sbs_6310_icon_width}px - (\${sbs_6310_icon_border_width} * 2)px) !important;
          line-height: calc(\${sbs_6310_icon_width}px - (\${sbs_6310_icon_border_width} * 2)px)!important;
          font-size: \${sbs_6310_icon_font_size}px !important;
          color: \${sbs_6310_icon_color} !important;
          border: \${sbs_6310_icon_border_width}px solid \${sbs_6310_icon_border_color} !important;
          border-radius: \${sbs_6310_icon_border_radius}% !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          border: \${sbs_6310_icon_border_width}px solid \${sbs_6310_icon_border_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon i {
          width: \${sbs_6310_icon_width}px !important;
          height: \${sbs_6310_icon_width}px !important;    
          line-height: \${sbs_6310_icon_width}px !important;
        }</style>`).appendTo('.sbs-6310-preview');        
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );