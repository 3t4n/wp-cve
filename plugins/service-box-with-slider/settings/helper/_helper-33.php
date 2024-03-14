<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`      
      #sbs_6310_box_border_radius,
      #sbs_6310_box_border_width,
      #sbs_6310_box_border_color,
      #sbs_6310_box_background_color,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,  
      #sbs_6310_title_font_hover_color,  
      #sbs_6310_details_font_hover_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_icon_background_color

      `).on('change', function() {
        var sbs_6310_box_border_radius = parseInt(jQuery('#sbs_6310_box_border_radius').val());
        var sbs_6310_box_border_width = parseInt(jQuery('#sbs_6310_box_border_width').val());
        var sbs_6310_box_border_color = jQuery('#sbs_6310_box_border_color').val();         
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();        
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val(); 
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_background_color = jQuery('#sbs_6310_icon_background_color').val();

        var sbs_6310_title_font_hover_color = jQuery('#sbs_6310_title_font_hover_color').val();
        var sbs_6310_details_font_hover_color = jQuery('#sbs_6310_details_font_hover_color').val();

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon1:before {
          border-top: \${sbs_6310_box_border_width}px solid \${sbs_6310_box_border_color} !important;
          border-left: \${sbs_6310_box_border_width}px solid \${sbs_6310_box_border_color} !important;
        }</style>`).appendTo('.sbs-6310-preview')
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon1:after {
          border-bottom: \${sbs_6310_box_border_width}px solid \${sbs_6310_box_border_color} !important;
          border-right: \${sbs_6310_box_border_width}px solid \${sbs_6310_box_border_color} !important;
        }</style>`).appendTo('.sbs-6310-preview')
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon1 i {
          font-size: \${sbs_6310_icon_font_size}px !important;
          width: \${sbs_6310_icon_width}px !important;
          height: \${sbs_6310_icon_width}px !important;          
          line-height: \${sbs_6310_icon_width}px !important;
          background-color: \${sbs_6310_icon_background_color} !important;
          color: \${sbs_6310_icon_color} !important;
        }</style>`).appendTo('.sbs-6310-preview')
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon1 i {
          color: \${sbs_6310_icon_hover_color} !important;
          width: \${sbs_6310_icon_width}px !important;
          height: \${sbs_6310_icon_width}px !important;          
          line-height: \${sbs_6310_icon_width}px !important;
        }</style>`).appendTo('.sbs-6310-preview')
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-icon2 {
          background-color: \${sbs_6310_box_background_color} !important;
        }</style>`).appendTo('.sbs-6310-preview')
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-icon2 {
          background-color: \${sbs_6310_box_background_hover_color} !important;  
        }</style>`).appendTo('.sbs-6310-preview')        

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-title{
          color: \${sbs_6310_title_font_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-description{
          color: \${sbs_6310_details_font_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

      
       
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );