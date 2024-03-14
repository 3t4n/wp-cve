<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_radius,
      #sbs_6310_box_background_color,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_box_shadow_hover_blur,
      #sbs_6310_box_shadow_color,
      #sbs_6310_box_border_width,
      #sbs_6310_box_border_color_1,
      #sbs_6310_box_border_color_2,
      #sbs_6310_box_shadow_hover_color,
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
        var sbs_6310_box_border_color_1 = jQuery('#sbs_6310_box_border_color_1').val();
        var sbs_6310_box_border_color_2 = jQuery('#sbs_6310_box_border_color_2').val();
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_border_width = parseInt(jQuery('#sbs_6310_box_border_width').val());
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_margin_top = jQuery('#sbs_6310_icon_margin_top').val();
        var sbs_6310_icon_margin_bottom = jQuery('#sbs_6310_icon_margin_bottom').val();


        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {        
          border-radius: \${sbs_6310_box_radius}px !important;
          background-color: \${sbs_6310_box_background_color} !important;         
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {        
          background-color: \${sbs_6310_box_background_hover_color} !important;        
        }</style>`).appendTo('.sbs-6310-preview'); 
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:after {
          border-width: \${sbs_6310_box_border_width}px 0 !important;       
        }</style>`).appendTo('.sbs-6310-preview'); 

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:before {         
          border-width: 0 \${sbs_6310_box_border_width}px !important;         
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover:after {         
          border-color: \${sbs_6310_box_border_color_1} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover:before {
          border-color: \${sbs_6310_box_border_color_2} !important;
        }
      </style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {        
          font-size: \${sbs_6310_icon_font_size}px !important;         
          color: \${sbs_6310_icon_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon i {
          font-size: \${sbs_6310_icon_font_size}px !important;
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