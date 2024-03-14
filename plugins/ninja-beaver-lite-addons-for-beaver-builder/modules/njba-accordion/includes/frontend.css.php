
<?php $settings->item_spacing = $settings->item_spacing === '' ? 10 : $settings->item_spacing; ?>
.fl-node-<?php echo $id; ?> .njba-accordion-item {
<?php if($settings->item_spacing == 0) { ?> border-bottom: none;
<?php } else {  ?> margin-bottom: <?php echo $settings->item_spacing.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button {
<?php if($settings->label_background_color){ ?> background-color: <?php echo '#'.$settings->label_background_color; ?>;
<?php } ?> <?php if($settings->label_text_color){ ?> color: <?php echo '#'.$settings->label_text_color; ?>;
<?php } ?> <?php if($settings->label_border_style){ ?> border-style: <?php echo $settings->label_border_style; ?>;
<?php } ?> <?php if( $settings->label_border_style !== 'none' ) {  ?> <?php if(isset($settings->label_border_width['top'] ) ){ ?> border-top-width: <?php echo $settings->label_border_width['top'].'px'; ?>;
<?php  } ?> <?php if(isset($settings->label_border_width['right'] )  ){ ?> border-right-width: <?php echo $settings->label_border_width['right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_border_width['bottom'] ) ){ ?> border-bottom-width: <?php echo $settings->label_border_width['bottom'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_border_width['left'] ) ){ ?> border-left-width: <?php echo $settings->label_border_width['left'].'px'; ?>;
<?php } ?> <?php } ?> <?php if($settings->label_border_color){ ?> border-color: <?php echo '#'.$settings->label_border_color; ?>;
<?php } ?> <?php if($settings->item_spacing == 0) { ?> border-bottom-width: 0;
<?php } ?> <?php if(isset($settings->label_padding['top'] ) ){ ?> padding-top: <?php echo $settings->label_padding['top'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_padding['right']) ){ ?> padding-right: <?php echo $settings->label_padding['right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_padding['bottom'] ) ){ ?> padding-bottom: <?php echo $settings->label_padding['bottom'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_padding['left'] ) ){ ?> padding-left: <?php echo $settings->label_padding['left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_border_radius['top_left'] ) ){ ?> border-top-left-radius: <?php echo $settings->label_border_radius['top_left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_border_radius['top_right'] ) ){ ?> border-top-right-radius: <?php echo $settings->label_border_radius['top_right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_border_radius['bottom_left'] ) ){ ?> border-bottom-left-radius: <?php echo $settings->label_border_radius['bottom_left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_border_radius['bottom_right'] ) ){ ?> border-bottom-right-radius: <?php echo $settings->label_border_radius['bottom_right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button:hover, .fl-node-<?php echo $id; ?> .njba-accordion-item.njba-accordion-item-active .njba-accordion-button {
<?php if($settings->label_bg_active_color){ ?> background-color: <?php echo '#'.$settings->label_bg_active_color; ?>;
<?php } ?> <?php if($settings->label_text_active_color){ ?> color: <?php echo '#'.$settings->label_text_active_color; ?>;
<?php } ?>
}

<?php if( $settings->item_spacing == 0 ) { ?>
.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button:last-child {
<?php if(isset($settings->label_border_width['bottom'] ) ){ ?> border-bottom-width: <?php echo $settings->label_border_width['bottom'].'px'; ?>;
<?php } ?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button .njba-accordion-button-label {
<?php if( $settings->label_font['family'] !== 'Default' ) { ?> <?php FLBuilderFonts::font_css( $settings->label_font ); ?> <?php } ?> <?php if(isset($settings->label_custom_font_size['desktop'] )  ) { ?> font-size: <?php echo $settings->label_custom_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if(isset($settings->label_line_height['desktop'] ) ){ ?> line-height: <?php echo $settings->label_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if($settings->icon_title_alignment){ ?> text-align: <?php echo $settings->icon_title_alignment;?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-content {
<?php if( $settings->content_font['family'] !== 'Default' ) { ?> <?php FLBuilderFonts::font_css( $settings->content_font ); ?> <?php } ?> <?php if(isset($settings->content_custom_font_size['desktop'] ) ) { ?> font-size: <?php echo $settings->content_custom_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_line_height['desktop'] ) ){ ?> line-height: <?php echo $settings->content_line_height['desktop'].'px'; ?>;
<?php if($settings->content_bg_color){ ?> background-color: <?php echo ( $settings->content_bg_color ) ? '#' . $settings->content_bg_color : 'transparent'; ?>;
<?php } ?> <?php if($settings->content_text_color){ ?> color: <?php echo '#'.$settings->content_text_color; ?>;
<?php } ?> <?php if($settings->content_border_style){ ?> border-style: <?php echo $settings->content_border_style; ?>;
<?php } ?> <?php if( $settings->content_border_style !== 'none' ) { ?> <?php if(isset($settings->content_border_width['top'] ) ){ ?> border-top-width: <?php echo $settings->content_border_width['top'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_border_width['right'] ) ){ ?> border-right-width: <?php echo $settings->content_border_width['right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_border_width['bottom'] ) ){ ?> border-bottom-width: <?php echo $settings->content_border_width['bottom'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_border_width['left'] ) ){ ?> border-left-width: <?php echo $settings->content_border_width['left'].'px'; ?>;
<?php } ?> <?php } ?> <?php } ?> <?php if($settings->content_border_color){ ?> border-color: <?php echo '#'.$settings->content_border_color; ?>;
<?php } ?> <?php if($settings->content_alignment){ ?> text-align: <?php echo $settings->content_alignment; ?>;
<?php } ?> <?php if(isset($settings->content_padding['top'] ) ){ ?> padding-top: <?php echo $settings->content_padding['top'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_padding['right'] ) ){ ?> padding-right: <?php echo $settings->content_padding['right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_padding['bottom'] ) ){ ?> padding-bottom: <?php echo $settings->content_padding['bottom'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_padding['left'] ) ){ ?> padding-left: <?php echo $settings->content_padding['left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_border_radius['top_left'] ) ){ ?> border-top-left-radius: <?php echo $settings->content_border_radius['top_left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_border_radius['top_right'] ) ){ ?> border-top-right-radius: <?php echo $settings->content_border_radius['top_right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_border_radius['bottom_left'] ) ){ ?> border-bottom-left-radius: <?php echo $settings->content_border_radius['bottom_left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->content_border_radius['bottom_right'] ) ){ ?> border-bottom-right-radius: <?php echo $settings->content_border_radius['bottom_right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button-icon {
<?php if(isset($settings->accordion_toggle_icon_size['desktop'] ) ){ ?> font-size: <?php echo $settings->accordion_toggle_icon_size['desktop'].'px'; ?>;
<?php } ?> <?php if($settings->accordion_toggle_icon_color){ ?> color: <?php echo '#'.$settings->accordion_toggle_icon_color; ?>;
<?php } ?> <?php if($settings->accordion_toggle_border_style){ ?> border-style: <?php echo $settings->accordion_toggle_border_style; ?>;
<?php } ?> <?php if( $settings->accordion_toggle_border_style !== 'none' ) {  ?> <?php if(isset($settings->accordion_toggle_border_width['top'] ) ){ ?> border-top-width: <?php echo $settings->accordion_toggle_border_width['top'].'px'; ?>;
<?php  } ?> <?php if(isset($settings->accordion_toggle_border_width['right'] )  ){ ?> border-right-width: <?php echo $settings->accordion_toggle_border_width['right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->accordion_toggle_border_width['bottom'] ) ){ ?> border-bottom-width: <?php echo $settings->accordion_toggle_border_width['bottom'].'px !important'; ?>;
<?php } ?> <?php if(isset($settings->accordion_toggle_border_width['left'] ) ){ ?> border-left-width: <?php echo $settings->accordion_toggle_border_width['left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->accordion_toggle_border_color)){ ?> border-color: <?php echo '#'.$settings->accordion_toggle_border_color; ?>;
<?php } ?> <?php if(isset($settings->accordion_toggle_border_radius['top_left'] ) ){ ?> border-top-left-radius: <?php echo $settings->accordion_toggle_border_radius['top_left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->accordion_toggle_border_radius['top_right'] ) ){ ?> border-top-right-radius: <?php echo $settings->accordion_toggle_border_radius['top_right'].'px'; ?>;
<?php } ?> <?php if(isset($settings->accordion_toggle_border_radius['bottom_left'] ) ){ ?> border-bottom-left-radius: <?php echo $settings->accordion_toggle_border_radius['bottom_left'].'px'; ?>;
<?php } ?> <?php if(isset($settings->accordion_toggle_border_radius['bottom_right'] ) ){ ?> border-bottom-right-radius: <?php echo $settings->accordion_toggle_border_radius['bottom_right'].'px'; ?>;
<?php } ?> <?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button:hover .njba-accordion-button-icon, .fl-node-<?php echo $id; ?> .njba-accordion-item.njba-accordion-item-active .njba-accordion-button-icon {
<?php if($settings->accordion_toggle_hover_icon_color){ ?> color: <?php echo '#'.$settings->accordion_toggle_hover_icon_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button-icon:before {
<?php if(isset($settings->accordion_toggle_icon_size['desktop'] ) ){ ?> font-size: <?php echo $settings->accordion_toggle_icon_size['desktop'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-icon {
<?php if(isset($settings->accordion_icon_size['desktop'] ) ){ ?> font-size: <?php echo $settings->accordion_icon_size['desktop'].'px'; ?>;
<?php } ?> <?php if(isset($settings->accordion_icon_size['desktop'] ) ){ ?> width: <?php echo ($settings->accordion_icon_size['desktop'] * 1.25).'px'; ?>;
<?php } ?> <?php if($settings->label_text_color){ ?> color: <?php echo '#'.$settings->label_text_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button:hover .njba-accordion-icon, .fl-node-<?php echo $id; ?> .njba-accordion-item.njba-accordion-item-active .njba-accordion-icon {
<?php if($settings->label_text_active_color){ ?> color: <?php echo '#'.$settings->label_text_active_color; ?>;
<?php } ?>
}

@media only screen and (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button .njba-accordion-button-label {
    <?php if( isset($settings->label_custom_font_size['medium'] ) ) { ?> font-size: <?php echo $settings->label_custom_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( isset($settings->label_line_height['medium'] ) ) { ?> line-height: <?php echo $settings->label_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-content {
    <?php if( isset($settings->content_custom_font_size['medium'] ) ) { ?> font-size: <?php echo $settings->content_custom_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( isset($settings->content_line_height['medium'] ) ) { ?> line-height: <?php echo $settings->content_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button-icon {
    <?php if( isset($settings->accordion_toggle_icon_size['medium'] ) ) { ?> font-size: <?php echo $settings->accordion_toggle_icon_size['medium'].'px'; ?>;
    <?php } ?>

    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button-icon:before {
    <?php if( isset($settings->accordion_toggle_icon_size['medium'] ) ) { ?> font-size: <?php echo $settings->accordion_toggle_icon_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-icon {
    <?php if( isset($settings->accordion_icon_size['medium'] ) ) { ?> font-size: <?php echo $settings->accordion_icon_size['medium'].'px'; ?>;
    <?php } ?> <?php if( isset($settings->accordion_icon_size['medium'] ) ) { ?> width: <?php echo ($settings->accordion_icon_size['medium'] * 1.25).'px'; ?>;
    <?php } ?>
    }

}

@media only screen and (max-width: 480px) {
    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button .njba-accordion-button-label {
    <?php if( isset($settings->label_custom_font_size['small'] ) ) { ?> font-size: <?php echo $settings->label_custom_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( isset($settings->label_line_height['small'] ) ) { ?> line-height: <?php echo $settings->label_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-content {
    <?php if( isset($settings->content_custom_font_size['small'] ) ) { ?> font-size: <?php echo $settings->content_custom_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( isset($settings->content_line_height['small'] ) ) { ?> line-height: <?php echo $settings->content_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button-icon {
    <?php if( isset($settings->accordion_toggle_icon_size['small'] ) ) { ?> font-size: <?php echo $settings->accordion_toggle_icon_size['small'].'px'; ?>;
    <?php } ?>

    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-button-icon:before {
    <?php if( isset($settings->accordion_toggle_icon_size['small'] ) ) { ?> font-size: <?php echo $settings->accordion_toggle_icon_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-accordion-item .njba-accordion-icon {
    <?php if( isset($settings->accordion_icon_size['small'] ) ) { ?> font-size: <?php echo $settings->accordion_icon_size['small'].'px'; ?>;
    <?php } ?> <?php if( isset($settings->accordion_icon_size['small'] ) ) { ?> width: <?php echo ($settings->accordion_icon_size['small'] * 1.25).'px'; ?>;
    <?php } ?>
    }

}
