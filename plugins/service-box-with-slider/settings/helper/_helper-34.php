<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_background_color,
      #sbs_6310_left_border_width,
      #sbs_6310_left_border_color,

      #sbs_6310_box_radius,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_box_shadow_hover_blur,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_shadow_hover_color,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color    
      `).on('change', function() {
        
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_left_border_width = jQuery('#sbs_6310_left_border_width').val();
        var sbs_6310_left_border_color = jQuery('#sbs_6310_left_border_color').val();


        var sbs_6310_box_radius = parseInt (jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();
        var sbs_6310_box_shadow_hover_blur = jQuery('#sbs_6310_box_shadow_hover_blur').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();     


        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." { 
          background-color: \${sbs_6310_box_background_color} !important;        
        }</style>`).appendTo('.sbs-6310-preview');
        
        jQuery(`<style type='text/css'>
        .sbs-6310-template-".esc_attr($templateId).":after{
          width: \${sbs_6310_left_border_width}px !important;
          background: \${sbs_6310_left_border_color} !important;    
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":before{
          background: \${sbs_6310_box_background_hover_color} !important; 
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon i{ 
          font-size: \${sbs_6310_icon_font_size}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'> .sbs-6310-template-".esc_attr($templateId)."-icon{
          color: \${sbs_6310_icon_color} !important;   
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          color: \${sbs_6310_icon_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
       
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );