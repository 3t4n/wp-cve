<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`      
        #sbs_6310_box_border_width,
        #sbs_6310_box_border_color,
        #sbs_6310_box_shadow_blur,
        #sbs_6310_box_shadow_width,
        #sbs_6310_box_shadow_color,
        #sbs_6310_box_radius,
        #sbs_6310_border_hover_color,
        #sbs_6310_box_shadow_hover_color,
        #sbs_6310_box_background1_color,
        #sbs_6310_box_background2_color,
        #sbs_6310_box_background2_hover_color,
        #sbs_6310_box_background1_hover_color,

        #sbs_6310_icon_font_size,
        #sbs_6310_icon_color,
        #sbs_6310_icon_margin_top,
        #sbs_6310_icon_margin_bottom,
        #sbs_6310_icon_hover_color
     
      `).on('change', function() {
        var sbs_6310_box_border_width = parseInt(jQuery('#sbs_6310_box_border_width').val());
        var sbs_6310_box_border_color = jQuery('#sbs_6310_box_border_color').val();
        var sbs_6310_box_shadow_blur = jQuery('#sbs_6310_box_shadow_blur').val();
        var sbs_6310_box_shadow_width = parseInt(jQuery('#sbs_6310_box_shadow_width').val());
        var sbs_6310_box_shadow_color = jQuery('#sbs_6310_box_shadow_color').val();
        var sbs_6310_box_radius = parseInt(jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_border_hover_color = jQuery('#sbs_6310_border_hover_color').val();
        var sbs_6310_box_shadow_hover_color = jQuery('#sbs_6310_box_shadow_hover_color').val();
        var sbs_6310_box_background1_color = jQuery('#sbs_6310_box_background1_color').val();
        var sbs_6310_box_background2_color = jQuery('#sbs_6310_box_background2_color').val();
        var sbs_6310_box_background2_hover_color = jQuery('#sbs_6310_box_background2_hover_color').val();
        var sbs_6310_box_background1_hover_color = jQuery('#sbs_6310_box_background1_hover_color').val();

        var sbs_6310_icon_font_size = parseInt(jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_margin_top = parseInt(jQuery('#sbs_6310_icon_margin_top').val());
        var sbs_6310_icon_margin_bottom = parseInt(jQuery('#sbs_6310_icon_margin_bottom').val());
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();


        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." {
          border: \${sbs_6310_box_border_width}px solid \${sbs_6310_box_border_color} !important;
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_color} !important;
          border-radius: \${sbs_6310_box_radius}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover {
          border: \${sbs_6310_box_border_width}px solid \${sbs_6310_border_hover_color} !important;
          box-shadow: 0px 0px \${sbs_6310_box_shadow_blur}px \${sbs_6310_box_shadow_width}px \${sbs_6310_box_shadow_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":before, .sbs-6310-template-".esc_attr($templateId).":after {
          background: \${sbs_6310_box_background1_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":before {
          background: \${sbs_6310_box_background2_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover:before {
          background: \${sbs_6310_box_background2_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover:after {
          background: \${sbs_6310_box_background1_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon {
          font-size: \${sbs_6310_icon_font_size}px !important;
          color: \${sbs_6310_icon_color} !important;
          margin-top: \${sbs_6310_icon_margin_top}px !important;
          margin-bottom: \${sbs_6310_icon_margin_bottom}px !important;
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