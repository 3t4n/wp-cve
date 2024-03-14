.fl-node-<?php echo $id; ?> .alert-box-main {
<?php if( $settings->box_background ) { ?> background-color: <?php echo '#'.$settings->box_background; ?>;
<?php } ?><?php if( $settings->box_border_color ) { ?> border-color: <?php echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->border_radius ) { ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->box_border_style ) { ?> border-style: <?php echo $settings->box_border_style; ?>;
<?php } ?> <?php if( $settings->box_border_width ) { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->box_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->box_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->box_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->box_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->box_padding['left'].'px'; ?>;
<?php } ?> <?php if( $settings->box_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->box_padding['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_margin['top']  )) { ?> margin-top: <?php echo $settings->box_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_margin['bottom']  )) { ?> margin-bottom: <?php echo $settings->box_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_margin['left']  )) { ?> margin-left: <?php echo $settings->box_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_margin['right']  )) { ?> margin-right: <?php echo $settings->box_margin['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .alert-box-main .njba-alert-box-icon span {
<?php if( $settings->icon_color ) { ?> color: <?php echo '#'.$settings->icon_color; ?>;
<?php } ?><?php echo ( $settings->icon_size !== '' ) ? 'font-size: ' . $settings->icon_size.'px;' : 'font-size:24px;'; ?>
<?php if( $settings->icon_margin['top'] !== '' ) { ?> margin-top: <?php echo $settings->icon_margin['top'].'px'; ?>;
<?php } ?> <?php if( $settings->icon_margin['bottom'] !== '' ) { ?> margin-bottom: <?php echo $settings->icon_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->icon_margin['left'] !== '' ) { ?> margin-left: <?php echo $settings->icon_margin['left'].'px'; ?>;
<?php } ?> <?php if( $settings->icon_margin['right'] !==  '' ) { ?> margin-right: <?php echo $settings->icon_margin['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-alert-content .alert-title {
<?php if( $settings->title_color ) { ?> color: <?php echo '#'.$settings->title_color; ?>;
<?php } ?><?php if( !empty($settings->title_padding['top'] ) ) { ?> padding-top: <?php echo $settings->title_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_padding['bottom'])) {?> padding-bottom: <?php echo $settings->title_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_padding['left'])) { ?> padding-left: <?php echo $settings->title_padding['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_padding['right'] )) { ?> padding-right: <?php echo $settings->title_padding['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_margin['top'])) { ?> margin-top: <?php echo $settings->title_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_margin['bottom']) ) { ?> margin-bottom: <?php echo $settings->title_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_margin['left']) ) { ?> margin-left: <?php echo $settings->title_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_margin['right'])) { ?> margin-right: <?php echo $settings->title_margin['right'].'px'; ?>;
<?php } ?> <?php if( $settings->title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->title_font ); ?><?php } ?>
<?php if( $settings->title_font_size['desktop'] ) { ?> font-size: <?php echo $settings->title_font_size['desktop'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-alert-content .alert-subtitle {
<?php if( $settings->subtitle_color ) { ?> color: <?php echo '#'.$settings->subtitle_color; ?>;
<?php } ?><?php if( !empty($settings->subtitle_padding['top'] ) ) { ?> padding-top: <?php echo $settings->subtitle_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->subtitle_padding['bottom'])) {?> padding-bottom: <?php echo $settings->subtitle_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->subtitle_padding['left'] )) { ?> padding-left: <?php echo $settings->subtitle_padding['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->subtitle_padding['right'] )) { ?> padding-right: <?php echo $settings->subtitle_padding['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->subtitle_margin['top'] ) ) { ?> margin-top: <?php echo $settings->subtitle_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->subtitle_margin['bottom'] ) ) {?> margin-bottom: <?php echo $settings->subtitle_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->subtitle_margin['left'] ) ) { ?> margin-left: <?php echo $settings->subtitle_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->subtitle_margin['right'] ) ) { ?> margin-right: <?php echo $settings->subtitle_margin['right'].'px'; ?>;
<?php } ?> <?php if( $settings->subtitle_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->subtitle_font ); ?><?php } ?> <?php if( $settings->subtitle_font_size['desktop'] ) { ?> font-size: <?php echo $settings->subtitle_font_size['desktop'].'px'; ?>;
<?php } ?>
}

@media only screen and (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-alert-content .alert-subtitle {
    <?php if( $settings->subtitle_font_size['medium'] ) { ?> font-size: <?php echo $settings->subtitle_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-alert-content .alert-title {
    <?php if( $settings->title_font_size['medium']) { ?> font-size: <?php echo $settings->title_font_size['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 480px) {
    .fl-node-<?php echo $id; ?> .njba-alert-content .alert-subtitle {
    <?php if( $settings->subtitle_font_size['small'] ) { ?> font-size: <?php echo $settings->subtitle_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-alert-content .alert-title {
    <?php if( $settings->title_font_size['small']) { ?> font-size: <?php echo $settings->title_font_size['small'].'px'; ?>;
    <?php } ?>
    }
}
