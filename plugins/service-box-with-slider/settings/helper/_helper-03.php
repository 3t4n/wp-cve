<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_background_color,
      #sbs_6310_box_radius,
      #sbs_6310_box_background_hover_color,   
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_icon_margin_top,
      #sbs_6310_icon_margin_bottom,
      #sbs_6310_icon_background_size,
      #sbs_6310_icon_background_color,
      #sbs_6310_box_border_size,
      #sbs_6310_box_border_color,
      #sbs_6310_box_border_hover_color
      `).on('change', function() {
        
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_radius = parseInt(jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_background_size = parseInt(jQuery('#sbs_6310_icon_background_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_margin_top = parseInt(jQuery('#sbs_6310_icon_margin_top').val());
        var sbs_6310_icon_margin_bottom = parseInt(jQuery('#sbs_6310_icon_margin_bottom').val());
        var sbs_6310_box_border_size = parseInt(jQuery('#sbs_6310_box_border_size').val());
        var sbs_6310_box_border_color = jQuery('#sbs_6310_box_border_color').val();
        var sbs_6310_box_border_hover_color = jQuery('#sbs_6310_box_border_hover_color').val();
        var sbs_6310_icon_background_color = jQuery('#sbs_6310_icon_background_color').val();

        

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {    
          border: \${sbs_6310_box_border_size}px solid \${sbs_6310_box_border_color} !important;
          border-radius: \${sbs_6310_box_radius}px !important;     
        }</style>`).appendTo('.sbs-6310-preview'); 
         
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {        
          border-color: \${sbs_6310_box_border_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');   

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."::after {        
          background: \${sbs_6310_box_background_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."::before {        
          background: \${sbs_6310_box_background_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {        
          font-size: \${sbs_6310_icon_font_size}px !important;         
          color: \${sbs_6310_icon_color} !important;
          width: \${sbs_6310_icon_background_size}px !important;
          height: \${sbs_6310_icon_background_size}px !important;
          line-height: \${sbs_6310_icon_background_size}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {        
          color: \${sbs_6310_icon_hover_color} !important;
          background: \${sbs_6310_icon_background_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {        
          margin-top: \${sbs_6310_icon_margin_top}px !important;
          margin-bottom: \${sbs_6310_icon_margin_bottom}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
          line-height: \${sbs_6310_icon_background_size}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );