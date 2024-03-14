<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`
      #sbs_6310_box_border_width,
      #sbs_6310_box_border_color,
      #sbs_6310_box_background_color,
      #sbs_6310_box_radius,
      #sbs_6310_title_bottom_border,
      #sbs_6310_title_border_bottom_color,
      #sbs_6310_icon_font_size,
      #sbs_6310_icon_color,
      #sbs_6310_icon_hover_color,
      #sbs_6310_icon_margin_bottom,
      #sbs_6310_box_background_hover_color,
      #sbs_6310_title_font_hover_color,
      #sbs_6310_details_font_hover_color,
      #sbs_6310_title_border_bottom_hover_color

      `).on('change', function() {        
        var sbs_6310_box_border_width = parseInt (jQuery('#sbs_6310_box_border_width').val());
        var sbs_6310_box_border_color = jQuery('#sbs_6310_box_border_color').val();
        var sbs_6310_box_background_color = jQuery('#sbs_6310_box_background_color').val();
        var sbs_6310_box_radius = parseInt (jQuery('#sbs_6310_box_radius').val());
        var sbs_6310_title_bottom_border = parseInt (jQuery('#sbs_6310_title_bottom_border').val());
        var sbs_6310_title_border_bottom_color = jQuery('#sbs_6310_title_border_bottom_color').val();
        var sbs_6310_icon_font_size = parseInt (jQuery('#sbs_6310_icon_font_size').val());
        var sbs_6310_icon_margin_bottom = parseInt (jQuery('#sbs_6310_icon_margin_bottom').val());
        var sbs_6310_icon_color = jQuery('#sbs_6310_icon_color').val();
        var sbs_6310_icon_hover_color = jQuery('#sbs_6310_icon_hover_color').val();
        var sbs_6310_box_background_hover_color = jQuery('#sbs_6310_box_background_hover_color').val();
        var sbs_6310_title_font_hover_color = jQuery('#sbs_6310_title_font_hover_color').val();
        var sbs_6310_details_font_hover_color = jQuery('#sbs_6310_details_font_hover_color').val();
        var sbs_6310_title_border_bottom_hover_color = jQuery('#sbs_6310_title_border_bottom_hover_color').val();

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper {
          border: \${sbs_6310_box_border_width}px solid \${sbs_6310_box_border_color} !important;
          background-color: \${sbs_6310_box_background_color} !important;
          border-radius: \${sbs_6310_box_radius}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-title {
          border-bottom: \${sbs_6310_title_bottom_border}px solid \${sbs_6310_title_border_bottom_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-title, .sbs-6310-template-".esc_attr($templateId)."-wrapper:active .sbs-6310-template-".esc_attr($templateId)."-title  {
          color: \${sbs_6310_title_font_hover_color} !important;
          border-bottom-color: \${sbs_6310_title_border_bottom_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover {
          background-color: \${sbs_6310_box_background_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-icon {
          font-size: \${sbs_6310_icon_font_size}px !important;
          color: \${sbs_6310_icon_color} !important;
          margin-bottom: \${sbs_6310_icon_margin_bottom}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
          color: \${sbs_6310_icon_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

    });
  });
";

wp_register_script( "sbs-6310-template-".esc_attr($templateId)."-js", "" );
wp_enqueue_script( "sbs-6310-template-".esc_attr($templateId)."-js" );
wp_add_inline_script( "sbs-6310-template-".esc_attr($templateId)."-js", $jsCode );