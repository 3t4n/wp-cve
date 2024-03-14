<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_shadow_hover_blur,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_shadow_width,
      #sbs_6310_box_background_color,    
      #sbs_6310_box_shadow_hover_color,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_width,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_box_background_hover_color

      `).on('change', function() {
        
        var sbs_6310_box_shadow_hover_blur = parseInt (jQuery('#sbs_6310_box_shadow_hover_blur').val());
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_shadow_width = parseInt(jQuery('#sbs_6310_box_shadow_width').val()); 
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();
        var sbs_6310_icon_font_size = parseInt (jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_width = parseInt (jQuery('#sbs_6310_icon_width').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-container {
          box-shadow: 0 0 \${sbs_6310_box_shadow_hover_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_color} !important;
          background-color: \${sbs_6310_box_background_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-container:hover {
          box-shadow: 0 0 \${sbs_6310_box_shadow_hover_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {
          font-size: \${sbs_6310_icon_font_size}px !important;
          color: \${sbs_6310_icon_color} !important;
          width: \${sbs_6310_icon_width}px !important;
          height: \${sbs_6310_icon_width}px !important;
          line-height: \${sbs_6310_icon_width}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          color: \${sbs_6310_icon_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon::after {
          background-color: \${sbs_6310_box_background_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );