<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_radius,
      #sbs_6310_box_gradient_color_1,
      #sbs_6310_box_gradient_color_2,
      #sbs_6310_box_gradient_hover_color_1,
      #sbs_6310_box_gradient_hover_color_2,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color
   
      `).on('change', function() {
        
        var sbs_6310_box_radius = parseInt (jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_box_gradient_color_1 = jQuery('#sbs_6310_box_gradient_color_1').val();
        var sbs_6310_box_gradient_color_2 = jQuery('#sbs_6310_box_gradient_color_2').val();
        var sbs_6310_box_gradient_hover_color_1 = jQuery('#sbs_6310_box_gradient_hover_color_1').val();
        var sbs_6310_box_gradient_hover_color_2 = jQuery('#sbs_6310_box_gradient_hover_color_2').val();      
        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());

        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_icon_margin_top = jQuery('#sbs_6310_icon_margin_top').val();
        var sbs_6310_icon_margin_bottom = jQuery('#sbs_6310_icon_margin_bottom').val();

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-card {
          background-image: linear-gradient(45deg, \${sbs_6310_box_gradient_color_1}, \${sbs_6310_box_gradient_color_2}) !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-overlay {
          background-image: linear-gradient(45deg, \${sbs_6310_box_gradient_hover_color_1}, \${sbs_6310_box_gradient_hover_color_2}) !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-pic {
          font-size: \${sbs_6310_icon_font_size}px !important;
          color: \${sbs_6310_icon_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-pic {
          color: \${sbs_6310_icon_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');
       
    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );