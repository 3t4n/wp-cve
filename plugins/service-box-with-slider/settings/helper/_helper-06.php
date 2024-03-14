<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_radius,
      #sbs_6310_box_border_size,
      #sbs_6310_box_background_color,
      #sbs_6310_box_background_hover_color,  
      #sbs_6310_box_shadow_blur,
      #sbs_6310_box_shadow_width,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_shadow_hover_color, 
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_icon_margin_top,
      #sbs_6310_icon_margin_bottom,     
      #sbs_6310_box_border_hover_color,
      #sbs_6310_box_border_color,
      #sbs_6310_icon_border_size,
      #sbs_6310_icon_box_border_color,
      #sbs_6310_icon_background_color,
      #sbs_6310_icon_background_hover_color,
      #sbs_6310_icon_box_size_number,
      #sbs_6310_icon_left_number
     
      `).on('change', function() {
        var sbs_6310_box_radius = parseInt(jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();       
        var sbs_6310_box_shadow_blur = jQuery('#sbs_6310_box_shadow_blur').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_margin_top = jQuery('#sbs_6310_icon_margin_top').val();
        var sbs_6310_icon_margin_bottom = parseInt(jQuery('#sbs_6310_icon_margin_bottom').val());
        var sbs_6310_box_border_size = parseInt(jQuery('#sbs_6310_box_border_size').val());
        var sbs_6310_box_border_color = jQuery('#sbs_6310_box_border_color').val();
        var sbs_6310_box_shadow_width = parseInt(jQuery('#sbs_6310_box_shadow_width').val());        
        var sbs_6310_box_border_hover_color = jQuery('#sbs_6310_box_border_hover_color').val();
        var sbs_6310_icon_border_size = parseInt(jQuery('#sbs_6310_icon_border_size').val());
        var sbs_6310_icon_box_border_color = jQuery('#sbs_6310_icon_box_border_color').val();
        var sbs_6310_icon_background_color = jQuery('#sbs_6310_icon_background_color').val();
        var sbs_6310_icon_background_hover_color = jQuery('#sbs_6310_icon_background_hover_color').val();
        var sbs_6310_icon_box_size_number = parseInt(jQuery('#sbs_6310_icon_box_size_number').val());
        var sbs_6310_icon_left_number = parseInt(jQuery('#sbs_6310_icon_left_number').val());


        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {    
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_color}!important;
           background: \${sbs_6310_box_background_color} !important;  
           border-radius: \${sbs_6310_box_radius}px !important; 
           border: \${sbs_6310_box_border_size}px solid \${sbs_6310_box_border_color} !important;     
        }</style>`).appendTo('.sbs-6310-preview'); 

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {        
          font-size: \${sbs_6310_icon_font_size}px !important;         
          color: \${sbs_6310_icon_color} !important; 
          border: \${sbs_6310_icon_border_size}px solid \${sbs_6310_icon_box_border_color} !important;
          background: \${sbs_6310_icon_background_color} !important; 
          width: \${sbs_6310_icon_box_size_number}px !important;
          height: \${sbs_6310_icon_box_size_number}px !important;
          line-height: \${sbs_6310_icon_box_size_number}px !important;
          left: - \${sbs_6310_icon_left_number}px !important;       
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {        
          border-color: \${sbs_6310_box_border_hover_color} !important; 
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_hover_color} !important;       
        }</style>`).appendTo('.sbs-6310-preview');
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-content-box {        
          width: calc(100% - \${sbs_6310_icon_left_number}px) !important;
          margin-left: \${sbs_6310_icon_left_number}px;  
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon i{        
          line-height: calc(\${sbs_6310_icon_box_size_number}px - \${sbs_6310_icon_border_size}px) !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."::before {        
          background: \${sbs_6310_box_background_hover_color} !important;        
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {        
          color: \${sbs_6310_icon_hover_color} !important;
          background: \${sbs_6310_icon_background_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {  
          margin-bottom: \${sbs_6310_icon_margin_bottom}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );