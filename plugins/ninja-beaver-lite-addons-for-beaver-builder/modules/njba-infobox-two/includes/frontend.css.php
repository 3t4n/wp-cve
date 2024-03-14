.fl-node-<?php echo $id; ?> .section-title-details {
    position: relative;
    overflow: hidden;
<?php if( $settings->infobox_two_position ) { ?> text-align: <?php echo $settings->infobox_two_position; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-heading {
<?php if( $settings->infobox_two_position ) { ?> text-align: <?php echo $settings->infobox_two_position; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .section-title-details <?php echo $settings->main_title_tag; ?> {
<?php if( $settings->infobox_two_position ) { ?> text-align: <?php echo $settings->infobox_two_position; ?>;
<?php } ?> <?php if( $settings->heading_title_color ) { ?> color: <?php echo '#'.$settings->heading_title_color; ?>;
<?php } ?> <?php if( $settings->heading_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_title_font ); ?>
<?php } ?> <?php if( $settings->heading_title_font_size['desktop'] ) { ?> font-size: <?php echo $settings->heading_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if($settings->heading_title_line_height['desktop'] ) { ?> line-height: <?php echo $settings->heading_title_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_margin['top'] !== '' ) { ?> margin-top: <?php echo $settings->infobox_two_heading_margin['top'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_margin['right'] !== '') { ?> margin-right: <?php echo $settings->infobox_two_heading_margin['right'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_margin['bottom'] !== '' ) { ?> margin-bottom: <?php echo $settings->infobox_two_heading_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_margin['left'] !== '' ) { ?> margin-left: <?php echo $settings->infobox_two_heading_margin['left'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .section-title-details p {
<?php if( $settings->infobox_two_position ) { ?> text-align: <?php echo $settings->infobox_two_position; ?>;
<?php } ?> <?php if( $settings->heading_sub_title_color ) { ?> color: <?php echo '#'.$settings->heading_sub_title_color; ?>;
<?php } ?> <?php if( $settings->heading_sub_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_sub_title_font ); ?>
<?php } ?> <?php if( $settings->heading_sub_title_font_size['desktop'] ) { ?> font-size: <?php echo $settings->heading_sub_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if($settings->heading_sub_title_line_height['desktop'] ) { ?> line-height: <?php echo $settings->heading_sub_title_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_subtitle_margin['top'] !== '' ) { ?> margin-top: <?php echo $settings->infobox_two_heading_subtitle_margin['top'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_subtitle_margin['right'] !== '' ) { ?> margin-right: <?php echo $settings->infobox_two_heading_subtitle_margin['right'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_subtitle_margin['bottom'] !== '' ) { ?> margin-bottom: <?php echo $settings->infobox_two_heading_subtitle_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_heading_subtitle_margin['left'] !== '' ) { ?> margin-left: <?php echo $settings->infobox_two_heading_subtitle_margin['left'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infobox-two {
<?php if($settings->infobox_two_type === 'icon' || $settings->infobox_two_type === 'text') : ?> 
    <?php if( $settings->infobox_two_font_color ) { ?> color: #<?php echo $settings->infobox_two_font_color; ?>;
    <?php } ?> <?php if( $settings->infobox_two_font_size['desktop'] ) { ?> font-size: <?php echo $settings->infobox_two_font_size['desktop'].'px'; ?>;
    <?php } ?> <?php if( $settings->infobox_two_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->infobox_two_font ); ?>
    <?php } ?>
<?php endif; ?> 
<?php if($settings->infobox_two_line_height['desktop'] ) { ?> line-height: <?php echo $settings->infobox_two_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->infobox_two_position ) { ?> float: <?php echo $settings->infobox_two_position; ?>;
<?php } ?> <?php if($settings->infobox_two_marginlr['right'])  {?> margin-right: <?php echo $settings->infobox_two_marginlr['right'].'px'; ?>;
<?php } ?> <?php if($settings->infobox_two_marginlr['left'])  {?> margin-left: <?php echo $settings->infobox_two_marginlr['left'].'px'; ?>;
<?php } ?>
}

<?php if( $global_settings->responsive_enabled ) { // Global Setting If started
?>

@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .section-title-details <?php echo $settings->main_title_tag; ?> {
    <?php if($settings->heading_title_font_size['medium'] ) { ?> font-size: <?php echo $settings->heading_title_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if($settings->heading_title_line_height['medium'] ) { ?> line-height: <?php echo $settings->heading_title_line_height['medium'].'px'; ?>;
    <?php } ?> 
    }

    .fl-node-<?php echo $id; ?> .section-title-details p {
    <?php if($settings->heading_sub_title_font_size['medium'] ) { ?> font-size: <?php echo $settings->heading_sub_title_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if($settings->heading_sub_title_line_height['medium'] ) { ?> line-height: <?php echo $settings->heading_sub_title_line_height['medium'].'px'; ?>;
    <?php } ?> 
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-two {
    <?php if( $settings->infobox_two_font_size['medium'] ) { ?> font-size: <?php echo $settings->infobox_two_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if($settings->infobox_two_line_height['medium'] ) { ?> line-height: <?php echo $settings->infobox_two_line_height['medium'].'px'; ?>;
    <?php } ?> 
    }
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .section-title-details <?php echo $settings->main_title_tag; ?> {
    <?php if($settings->heading_title_font_size['small'] ) { ?> font-size: <?php echo $settings->heading_title_font_size['small'].'px'; ?>;
    <?php } ?> <?php if($settings->heading_title_line_height['small'] ) { ?> line-height: <?php echo $settings->heading_title_line_height['small'].'px'; ?>;
    <?php } ?> 
    }

    .fl-node-<?php echo $id; ?> .section-title-details p {
    <?php if($settings->heading_sub_title_font_size['small'] ) { ?> font-size: <?php echo $settings->heading_sub_title_font_size['small'].'px'; ?>;
    <?php } ?> <?php if($settings->heading_sub_title_line_height['small'] ) { ?> line-height: <?php echo $settings->heading_sub_title_line_height['small'].'px'; ?>;
    <?php } ?> 
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-two {
    <?php if( $settings->infobox_two_font_size['small'] ) { ?> font-size: <?php echo $settings->infobox_two_font_size['small'].'px'; ?>;
    <?php } ?> <?php if($settings->infobox_two_line_height['small'] ) { ?> line-height: <?php echo $settings->infobox_two_line_height['small'].'px'; ?>;
    <?php } ?> 
    }
}

<?php
}
?>
