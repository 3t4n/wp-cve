<?php
$jsCode = "
jQuery(document).ready(function() {
  //General Setting
  jQuery(`  
      #sbs_6310_title_font_size,
      #sbs_6310_title_line_height,
      #sbs_6310_title_font_color,
      #sbs_6310_title_font_hover_color,
      #sbs_6310_title_font_weight,
      #sbs_6310_title_text_transform,
      #sbs_6310_title_text_align,
      #sbs_6310_title_font_family,
      #sbs_6310_title_padding_top,
      #sbs_6310_title_padding_bottom,
      #sbs_6310_details_font_size,
      #sbs_6310_details_line_height,
      #sbs_6310_details_font_color,
      #sbs_6310_details_font_hover_color,
      #sbs_6310_details_font_weight,
      #sbs_6310_details_text_transform,
      #sbs_6310_details_text_align,
      #sbs_6310_details_font_family,
      #sbs_6310_details_margin_top,
      #sbs_6310_details_margin_bottom,
      #sbs_6310_read_more_height,
      #sbs_6310_read_more_width,
      #sbs_6310_read_more_font_size,
      #sbs_6310_read_more_font_color,
      #sbs_6310_read_more_font_hover_color,
      #sbs_6310_read_more_border_width,
      #sbs_6310_read_more_box_border_color,
      #sbs_6310_read_more_border_hover_color,
      #sbs_6310_read_more_border_radius,
      #sbs_6310_read_more_background_color,
      #sbs_6310_read_more_font_weight,
      #sbs_6310_read_more_background_hover_color,
      #sbs_6310_read_more_font_weight,
      #sbs_6310_read_more_text_transform,
      #sbs_6310_read_more_text_align,
      #sbs_6310_read_more_font_family,
      #sbs_6310_read_more_margin_top,
      #sbs_6310_read_more_margin_bottom,

      #sbs_6310_search_align,
      #sbs_6310_search_border_width,
      #sbs_6310_search_border_color,
      #sbs_6310_search_border_radius,
      #sbs_6310_search_font_color,
      #sbs_6310_search_margin_bottom,
      #sbs_6310_search_placeholder_font_color,
      #sbs_6310_search_height
      
      `).on('change', function() {
        var sbs_6310_title_font_size = jQuery('#sbs_6310_title_font_size').val();
        var sbs_6310_title_line_height = parseInt (jQuery('#sbs_6310_title_line_height').val());
        var sbs_6310_title_font_color = jQuery('#sbs_6310_title_font_color').val();
        var sbs_6310_title_font_hover_color = jQuery('#sbs_6310_title_font_hover_color').val();
        var sbs_6310_title_font_weight = jQuery('#sbs_6310_title_font_weight').val();
        var sbs_6310_title_text_transform = jQuery('#sbs_6310_title_text_transform').val();
        var sbs_6310_title_text_align = jQuery('#sbs_6310_title_text_align').val();
        var sbs_6310_title_font_family = jQuery('#sbs_6310_title_font_family').val();
        var sbs_6310_title_padding_top = jQuery('#sbs_6310_title_padding_top').val();
        var sbs_6310_title_padding_bottom = jQuery('#sbs_6310_title_padding_bottom').val();

        var sbs_6310_details_font_size = parseInt (jQuery('#sbs_6310_details_font_size').val());
        var sbs_6310_details_line_height = jQuery('#sbs_6310_details_line_height').val();
        var sbs_6310_details_font_color = jQuery('#sbs_6310_details_font_color').val();
        var sbs_6310_details_font_hover_color = jQuery('#sbs_6310_details_font_hover_color').val();
        var sbs_6310_details_font_weight = jQuery('#sbs_6310_details_font_weight').val();
        var sbs_6310_details_text_transform = jQuery('#sbs_6310_details_text_transform').val();
        var sbs_6310_details_text_align = jQuery('#sbs_6310_details_text_align').val();
        var sbs_6310_details_font_family = jQuery('#sbs_6310_details_font_family').val();
        var sbs_6310_details_margin_top = jQuery('#sbs_6310_details_margin_top').val();
        var sbs_6310_details_margin_bottom = jQuery('#sbs_6310_details_margin_bottom').val();

        var sbs_6310_read_more_height = parseInt (jQuery('#sbs_6310_read_more_height').val());
        var sbs_6310_read_more_font_family = jQuery('#sbs_6310_read_more_font_family').val();        
        var sbs_6310_read_more_width = parseInt (jQuery('#sbs_6310_read_more_width').val());
        var sbs_6310_read_more_font_size = parseInt (jQuery('#sbs_6310_read_more_font_size').val());
        var sbs_6310_read_more_font_color = jQuery('#sbs_6310_read_more_font_color').val();
        var sbs_6310_read_more_font_hover_color = jQuery('#sbs_6310_read_more_font_hover_color').val();
        var sbs_6310_read_more_border_width = jQuery('#sbs_6310_read_more_border_width').val();
        var sbs_6310_read_more_box_border_color = jQuery('#sbs_6310_read_more_box_border_color').val();
        var sbs_6310_read_more_border_hover_color = jQuery('#sbs_6310_read_more_border_hover_color').val();
        var sbs_6310_read_more_border_radius = jQuery('#sbs_6310_read_more_border_radius').val();  
        var sbs_6310_read_more_background_color = jQuery('#sbs_6310_read_more_background_color').val(); 
        var sbs_6310_read_more_background_hover_color = jQuery('#sbs_6310_read_more_background_hover_color').val(); 
        var sbs_6310_read_more_font_weight = jQuery('#sbs_6310_read_more_font_weight').val(); 
        var sbs_6310_read_more_text_transform = jQuery('#sbs_6310_read_more_text_transform').val();         
        var sbs_6310_read_more_text_align = jQuery('#sbs_6310_read_more_text_align').val();   
        var sbs_6310_read_more_margin_top = jQuery('#sbs_6310_read_more_margin_top').val();   
        var sbs_6310_read_more_margin_bottom = jQuery('#sbs_6310_read_more_margin_bottom').val();

        var sbs_6310_search_align = jQuery('#sbs_6310_search_align').val();
        var sbs_6310_search_border_width = jQuery('#sbs_6310_search_border_width').val();
        var sbs_6310_search_border_color = jQuery('#sbs_6310_search_border_color').val();
        var sbs_6310_search_border_radius = jQuery('#sbs_6310_search_border_radius').val();
        var sbs_6310_search_font_color = jQuery('#sbs_6310_search_font_color').val();
        var sbs_6310_search_margin_bottom = parseInt(jQuery('#sbs_6310_search_margin_bottom').val());
        var sbs_6310_search_placeholder_font_color = jQuery('#sbs_6310_search_placeholder_font_color').val();
        var sbs_6310_search_height = jQuery('#sbs_6310_search_height').val();

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-title {   
          font-size:\${sbs_6310_title_font_size}px !important;
          line-height:\${sbs_6310_title_line_height}px !important; 
          color:\${sbs_6310_title_font_color} !important; 
          font-weight:\${sbs_6310_title_font_weight} !important;
          text-transform:\${sbs_6310_title_text_transform} !important;
          text-align:\${sbs_6310_title_text_align} !important;
          font-family:\${sbs_6310_title_font_family} !important;
          padding-top:\${sbs_6310_title_padding_top}px !important;
          padding-bottom:\${sbs_6310_title_padding_bottom}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title {
          color:\${sbs_6310_title_font_hover_color} !important; 
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-description {
          font-size:\${sbs_6310_details_font_size}px !important;
          line-height:\${sbs_6310_details_line_height}px !important; 
          color:\${sbs_6310_details_font_color} !important;
          font-weight:\${sbs_6310_details_font_weight} !important;
          text-transform:\${sbs_6310_details_text_transform} !important;
          text-align:\${sbs_6310_details_text_align} !important;
          font-family:\${sbs_6310_details_font_family} !important;
          padding-top:\${sbs_6310_details_margin_top}px !important;
          padding-bottom:\${sbs_6310_details_margin_bottom}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
        
        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-description {         
          color:\${sbs_6310_details_font_hover_color} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-read-more a {
          font-family:\${sbs_6310_read_more_font_family} !important;
          height:\${sbs_6310_read_more_height}px !important; 
          line-height:\${sbs_6310_read_more_height}px !important;
          width:\${sbs_6310_read_more_width}px !important;
          font-size:\${sbs_6310_read_more_font_size}px !important; 
          color:\${sbs_6310_read_more_font_color} !important; 
          border: \${sbs_6310_read_more_border_width} solid \${sbs_6310_read_more_box_border_color} !important;
          border-radius: \${sbs_6310_read_more_border_radius}px !important;
          background: \${sbs_6310_read_more_background_color} !important; 
          font-weight: \${sbs_6310_read_more_font_weight} !important; 
          text-transform: \${sbs_6310_read_more_text_transform} !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-read-more a:hover {        
          color: \${sbs_6310_read_more_font_hover_color} !important;
          border: \${sbs_6310_read_more_border_width} solid \${sbs_6310_read_more_border_hover_color} !important;
          background: \${sbs_6310_read_more_background_hover_color} !important; 
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-template-".esc_attr($templateId)."-read-more {        
          text-align: \${sbs_6310_read_more_text_align} !important;
          margin-top: \${sbs_6310_read_more_margin_top}px !important ;
          margin-bottom: \${sbs_6310_read_more_margin_bottom}px !important;
        }</style>`).appendTo('.sbs-6310-preview');
        
        jQuery(`<style type='text/css'>.sbs-6310-search-".esc_attr($templateId)." { 
          justify-content: \${sbs_6310_search_align} !important;} 
        </style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'> .sbs-6310-search-template-".esc_attr($templateId)." input::placeholder { 
          color: \${sbs_6310_search_placeholder_font_color} !important;} 
        </style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'>.sbs-6310-search-template-".esc_attr($templateId)." input{ 
          border-width: \${sbs_6310_search_border_width}px !important;
          border-color: \${sbs_6310_search_border_color} !important; 
          border-radius: \${sbs_6310_search_border_radius}px !important; 
          color: \${sbs_6310_search_font_color} !important; 
          height: \${sbs_6310_search_height}px !important;
        }</style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'> .sbs-6310-search-template-".esc_attr($templateId)." i.search-icon { 
          color: \${sbs_6310_search_border_color} !important;} 
        </style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'> .sbs-6310-search-template-".esc_attr($templateId)." input:focus { 
          border-color: \${sbs_6310_search_border_color} !important;} 
        </style>`).appendTo('.sbs-6310-preview');

        jQuery(`<style type='text/css'> .sbs-6310-search-template-".esc_attr($templateId)."  { 
          margin-bottom: \${sbs_6310_search_margin_bottom}px !important;} 
        </style>`).appendTo('.sbs-6310-preview');

        
    });

    jQuery('body').on('keyup', '#sbs_6310_search_placeholder', function () {
      var value = jQuery(this).val();
      jQuery('.sbs-6310-search-template-".esc_attr($templateId)." input').attr('placeholder', value);
    });
  });
"
;

wp_register_script( "sbs-6310-common-js", "" );
wp_enqueue_script( "sbs-6310-common-js" );
wp_add_inline_script( "sbs-6310-common-js", $jsCode );

?>
