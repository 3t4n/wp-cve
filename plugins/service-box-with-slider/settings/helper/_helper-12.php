<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`      
      #sbs_6310_box_radius,
      #sbs_6310_border_size,
      #sbs_6310_border_color,
      #sbs_6310_box_background_color,
      #sbs_6310_box_background_hover_color,  
      #sbs_6310_box_shadow_blur,
      #sbs_6310_box_shadow_width,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_shadow_hover_color, 
      #sbs_6310_hover_effect_color,

      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_icon_background_color,
      #sbs_6310_icon_background_hover_color,
      #sbs_6310_icon_margin_top,
      #sbs_6310_icon_margin_bottom,
      #sbs_6310_icon_border_width,
      #sbs_6310_border_top_width_number,
      #sbs_6310_box_border_hover_color,
      #sbs_6310_border_hover_color,
      #sbs_6310_icon_box_size_number,
      #sbs_6310_icon_border_color    
     
      `).on('change', function() {
        var sbs_6310_box_radius = parseInt(jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();       
        var sbs_6310_box_shadow_blur = jQuery('#sbs_6310_box_shadow_blur').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();
        var sbs_6310_hover_effect_color = jQuery('#sbs_6310_hover_effect_color').val();

        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_background_color = jQuery('#sbs_6310_icon_background_color').val();
        var sbs_6310_icon_background_hover_color = jQuery('#sbs_6310_icon_background_hover_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_margin_top = parseInt(jQuery('#sbs_6310_icon_margin_top').val());
        var sbs_6310_icon_margin_bottom = parseInt(jQuery('#sbs_6310_icon_margin_bottom').val());
        var sbs_6310_border_top_width_number = parseInt(jQuery('#sbs_6310_border_top_width_number').val());
        var sbs_6310_border_size = parseInt(jQuery('#sbs_6310_border_size').val());
        var sbs_6310_box_shadow_width = parseInt(jQuery('#sbs_6310_box_shadow_width').val());        
        var sbs_6310_box_border_hover_color = jQuery('#sbs_6310_box_border_hover_color').val(); 
        var sbs_6310_border_color = jQuery('#sbs_6310_border_color').val(); 
        var sbs_6310_border_hover_color = jQuery('#sbs_6310_border_hover_color').val();     
        var sbs_6310_icon_box_size_number = jQuery('#sbs_6310_icon_box_size_number').val(); 
        var sbs_6310_icon_border_width = parseInt(jQuery('#sbs_6310_icon_border_width').val());     
        var sbs_6310_icon_border_color = jQuery('#sbs_6310_icon_border_color').val();
        var sbs_6310_icon_outline_effect_color = jQuery('#sbs_6310_icon_outline_effect_color').val(); 
        var sbs_6310_icon_hover_effect_color = jQuery('#sbs_6310_icon_hover_effect_color').val(); 

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {          
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_color} !important;
           background: \${sbs_6310_box_background_color} !important;  
           border: \${sbs_6310_border_size}px solid \${sbs_6310_border_color} !important; 
           border-radius: \${sbs_6310_box_radius}% !important;
        }</style>`).appendTo('.sbs-6310-preview'); 

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover{   
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_hover_color} !important; 
          border-color: \${sbs_6310_border_hover_color} !important;
          background: \${sbs_6310_box_background_hover_color} !important; 
        }</style>`).appendTo('.sbs-6310-preview');


        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {
          width: calc(\${sbs_6310_icon_font_size}px * 2) !important;
          height: calc(\${sbs_6310_icon_font_size}px * 2) !important;
          line-height: calc(\${sbs_6310_icon_font_size}px * 2) !important;
          background: \${sbs_6310_icon_background_color} !important;
          font-size: \${sbs_6310_icon_font_size}px !important;
          color:\${sbs_6310_icon_color} !important;
          margin-top: \${sbs_6310_icon_margin_top}px !important;
          margin-bottom: \${sbs_6310_icon_margin_bottom}px !important;   
        }</style>`).appendTo('.sbs-6310-preview');     

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon i {
          line-height: calc(\${sbs_6310_icon_font_size}px * 2) !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
          color:\${sbs_6310_icon_hover_color} !important;
          background:\${sbs_6310_icon_background_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":before,
        .sbs-6310-template-".esc_attr($templateId).":after {
          background: \${sbs_6310_hover_effect_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
      
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );