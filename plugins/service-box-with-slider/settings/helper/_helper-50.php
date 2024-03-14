<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      
      #sbs_6310_box_background_color,
      #sbs_6310_border_radius,
      #sbs_6310_box_shadow_width,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_box_hover_shadow_color,
      #sbs_6310_icon_width,
      #sbs_6310_icon_background_color,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_hover_color
     
      `).on('change', function() {
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();
        var sbs_6310_box_hover_shadow_color = jQuery('#sbs_6310_box_hover_shadow_color').val();
        var sbs_6310_icon_background_color = jQuery('#sbs_6310_icon_background_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_border_radius = parseInt (jQuery('#sbs_6310_border_radius').val());
        var sbs_6310_box_shadow_width = parseInt (jQuery('#sbs_6310_box_shadow_width').val());
        var sbs_6310_icon_width = parseInt (jQuery('#sbs_6310_icon_width').val());
        var sbs_6310_icon_font_size = parseInt (jQuery('#sbs_6310_icon_font_size').val());       


        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {
          background: \${sbs_6310_box_background_color} !important;
          border-radius: \${sbs_6310_border_radius}px !important;
          box-shadow: 0 0 \${sbs_6310_box_shadow_width}px 5px \${sbs_6310_box_shadow_color} !important;
      }</style>`).appendTo('.sbs-6310-preview');

      jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {
          background: \${sbs_6310_box_background_hover_color} !important; 
          box-shadow: 0 0 \${sbs_6310_box_shadow_width}px 5px \${sbs_6310_box_hover_shadow_color} !important;
      }</style>`).appendTo('.sbs-6310-preview');

      jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {
          width: \${sbs_6310_icon_width}px !important;
          height: \${sbs_6310_icon_width}px !important;
          line-height: \${sbs_6310_icon_width}px !important;
          background: \${sbs_6310_icon_background_color} !important;
          font-size: \${sbs_6310_icon_font_size}px !important;
      }</style>`).appendTo('.sbs-6310-preview');

      jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
        line-height: \${sbs_6310_icon_width}px !important;
      }</style>`).appendTo('.sbs-6310-preview');

      jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          background: \${sbs_6310_icon_background_color} !important;
          color: \${sbs_6310_icon_hover_color} !important;
      }</style>`).appendTo('.sbs-6310-preview');

        
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );