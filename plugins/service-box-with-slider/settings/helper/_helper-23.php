<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_background_color,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_shadow_hover_color,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_icon_color,
      #sbs_6310_icon_border_color,
      #sbs_6310_icon_background_color,
      #sbs_6310_icon_border_hover_color,
      #sbs_6310_icon_hover_color,


      #sbs_6310_box_shadow_blur,
      #sbs_6310_box_shadow_width,
      #sbs_6310_box_radius,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_border_width
      
     
      `).on('change', function() {        
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_border_color = jQuery('#sbs_6310_icon_border_color').val();
        var sbs_6310_icon_background_color = jQuery('#sbs_6310_icon_background_color').val();
        var sbs_6310_icon_border_hover_color = jQuery('#sbs_6310_icon_border_hover_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();

        var sbs_6310_box_shadow_blur = parseInt(jQuery('#sbs_6310_box_shadow_blur').val());
        var sbs_6310_box_shadow_width = parseInt(jQuery('#sbs_6310_box_shadow_width').val());
        var sbs_6310_box_radius = parseInt(jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_border_width = parseInt(jQuery('#sbs_6310_icon_border_width').val());
         

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."{
          background: \${sbs_6310_box_background_color} !important;
          padding: 35px 20px 50px calc((\${sbs_6310_icon_font_size}px) + \${sbs_6310_icon_font_size}px * 2 / 2 + 30px) !important;
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_color} !important;
          border-radius: \${sbs_6310_box_radius}px !important;
        }</style>`).appendTo('.sbs-6310-preview'); 

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview'); 

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":before {
          background: \${sbs_6310_box_background_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon {
          width: calc(\${sbs_6310_icon_font_size}px * 2) !important;
          height: calc(\${sbs_6310_icon_font_size}px * 2) !important;
          line-height: calc(\${sbs_6310_icon_font_size}px * 2) !important;
          font-size: \${sbs_6310_icon_font_size}px !important;
          color: \${sbs_6310_icon_color} !important;
          border: \${sbs_6310_icon_border_width}px solid \${sbs_6310_icon_border_color} !important;
          background: \${sbs_6310_icon_background_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          border-color: \${sbs_6310_icon_border_hover_color} !important;
          color: \${sbs_6310_icon_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon:after {
          height: \${sbs_6310_icon_border_width}px !important;
          width: calc(\${sbs_6310_icon_border_width}px + 20px) !important;
          background: \${sbs_6310_icon_border_color} !important;
          left:  calc((\${sbs_6310_icon_border_width}px + 20px) - 20px - \${sbs_6310_icon_border_width}) !important;
          right:  calc((\${sbs_6310_icon_border_width}px + 20px) - 20px - \${sbs_6310_icon_border_width}) !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon:before {
          height: \${sbs_6310_icon_border_width}px !important;
          width: calc(\${sbs_6310_icon_border_width}px + 20px) !important;
          background: \${sbs_6310_icon_border_color} !important;
          left:  calc((\${sbs_6310_icon_border_width}px + 20px) - 20px - \${sbs_6310_icon_border_width}) !important;
          right:  calc((\${sbs_6310_icon_border_width}px + 20px) - 20px - \${sbs_6310_icon_border_width}) !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:before, 
        .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:after {
          background: \${sbs_6310_icon_border_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon:after {
          left:  calc((\${sbs_6310_icon_border_width}px + 20px) - 20px - \${sbs_6310_icon_border_width}) !important;
          right:  calc((\${sbs_6310_icon_border_width}px + 20px) - 20px - \${sbs_6310_icon_border_width}) !important;
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