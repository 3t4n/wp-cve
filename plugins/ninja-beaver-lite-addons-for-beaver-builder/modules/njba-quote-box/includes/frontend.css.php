.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-4 .njba-quote-image,
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-5 .njba-quote-image img,
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-8 .njba-quote-image,
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-6 .njba-quote-image img,
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-7 .njba-quote-image {
<?php if( $settings->image_border_style !== 'none' ) { ?> border: <?php echo $settings->image_border_style; ?> <?php echo $settings->image_border_width.'px '; echo '#'.$settings->image_border_color; ?>;
<?php } ?> <?php if( $settings->image_border_radius ) { ?> border-radius: <?php echo $settings->image_border_radius; ?>%;
<?php } //die(); ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main .njba-quote-icon i,
.fl-node-<?php echo $id; ?> .njba-quote-box-main .njba-quote-icon-two i {
<?php if( $settings->quote_sign_color ) { ?> color: <?php echo '#'.$settings->quote_sign_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-content p, .njba-quote-box p {
<?php if( isset($settings->content_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_padding['left'] )) { ?> padding-left: <?php echo $settings->content_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main h2 {
<?php if( $settings->name_alignment ) { ?> text-align: <?php echo $settings->name_alignment; ?>;
<?php } ?> <?php if( $settings->name_color ) { ?> color: <?php echo '#'.$settings->name_color; ?>;
<?php } ?> <?php if( $settings->name_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->name_font ); ?><?php } ?> <?php if( isset($settings->name_font_size['desktop'] )) { ?> font-size: <?php echo $settings->name_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( isset($settings->name_margin['top']) ) { ?> margin-top: <?php echo $settings->name_margin['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->name_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->name_margin['bottom'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main h3 {
<?php if( $settings->profile_alignment ) { ?> text-align: <?php echo $settings->profile_alignment; ?>;
<?php } ?> <?php if( $settings->profile_color ) { ?> color: <?php echo '#'.$settings->profile_color; ?>;
<?php } ?> <?php if( $settings->profile_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->profile_font ); ?><?php } ?> <?php if( isset($settings->profile_font_size['desktop'] ) ) { ?> font-size: <?php echo $settings->profile_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( isset($settings->profile_margin['top'] ) ) { ?> margin-top: <?php echo $settings->profile_margin['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->profile_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->profile_margin['bottom'].'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-quote-box-main h4,
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-3 .njba-quote-box-content h4 {
<?php if( $settings->content_alignment ) { ?> text-align: <?php echo $settings->content_alignment; ?>;
<?php } ?> <?php if( $settings->content_color ) { ?> color: <?php echo '#'.$settings->content_color; ?><?php } ?>;
<?php if( $settings->content_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->content_font ); ?><?php } ?> <?php if( isset($settings->content_font_size['desktop'] )) { ?> font-size: <?php echo $settings->content_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_margin['top'] ) ) { ?> margin-top: <?php echo $settings->content_margin['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->content_margin['bottom'].'px'; ?>;
<?php } ?>


}

<?php if( $settings->quotebox_layout == 1 ) { ?>
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-1 .njba-quote-box {
<?php if( $settings->box_border_style !== 'none' ) { ?> border: <?php echo $settings->box_border_style.' '; echo $settings->box_border_width.'px '; echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] )) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 2 ) { ?>
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-2 .njba-quote-box {
<?php if( $settings->box_border_style !== 'none' ) { ?> border: <?php echo $settings->box_border_style.' '; echo $settings->box_border_width.'px '; echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] )) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 3 ) { ?>
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-3 .njba-quote-box {
<?php if( $settings->box_border_style !== 'none' ) { ?> border: <?php echo $settings->box_border_style.' '; echo $settings->box_border_width.'px '; echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] )) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-3 .njba-quote-icon-two,
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-3 .njba-quote-icon {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 4 ) { ?>

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-4 .njba-quote-box {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->highlight_border ) { ?> border-right-color: <?php echo '#'.$settings->highlight_border; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 5 ) { ?>

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-5 .njba-quote-box {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->highlight_border ) { ?> border-right-color: <?php echo '#'.$settings->highlight_border; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-5 .njba-quote-icon-two {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?>

}

<?php } ?>
<?php if( $settings->quotebox_layout == 6 ) { ?>

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-6 .njba-quote-box-content {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-6 .njba-quote-box {
<?php if( $settings->image_bg ) { ?> background: <?php echo '#'.$settings->image_bg; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-6 .njba-quote-box-content {
<?php if( $settings->box_border_radius ) { ?> border-radius: 0 <?php echo $settings->box_border_radius.'px '; echo $settings->box_border_radius.'px '; ?> 0px;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main .njba-separator-arrow {
<?php if( $settings->content_bg ) { ?> border-right-color: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 7 ) { ?>
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-7 .njba-quote-box {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->highlight_border ) { ?> border-right-color: <?php echo '#'.$settings->highlight_border; ?>;
<?php } ?> <?php if( $settings->highlight_border ) { ?> border-left-color: <?php echo '#'.$settings->highlight_border; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-7 .njba-separator-arrow {
<?php if( $settings->content_bg ) { ?> border-bottom-color: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 8 ) { ?>

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-8 .njba-quote-box-content {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] )) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->highlight_border ) { ?> border-right-color: <?php echo '#'.$settings->highlight_border; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-8 .njba-quote-icon {
<?php if( $settings->image_border_color ) { ?> border-color: <?php echo '#'.$settings->image_border_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main .njba-separator-arrow {
<?php if( $settings->content_bg ) { ?> border-right-color: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 9 ) { ?>
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-9 .njba-quote-box {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-9 .njba-quote-box-content {
<?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] )) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-9 .njba-quote-icon {
<?php if( $settings->quote_sign_bg_color ) { ?> background: <?php echo '#'.$settings->quote_sign_bg_color; ?>;
<?php } ?> <?php if( $settings->quote_sign_padding !== '' ) { ?> padding: <?php echo $settings->quote_sign_padding.'px'; ?>;
<?php } ?>
}

.njba-quote-box-main.layout-9 .njba-separator-arrow {
<?php if( $settings->quote_sign_bg_color ) { ?> border-left-color: <?php echo '#'.$settings->quote_sign_bg_color; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 10 ) { ?>
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-10 .njba-quote-box {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?>
}


.fl-node-<?php echo $id; ?> .njba-quote-box-shep-main {
<?php if( $settings->content_bg ) { ?> border-left-color: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if($settings->quote_shape_height !== ''){ ?> border-top: <?php echo $settings->quote_shape_height.'px '; ?>solid transparent;
<?php } ?> <?php if($settings->quote_shape_height !== ''){ ?> border-bottom: <?php echo $settings->quote_shape_height.'px '; ?>solid transparent;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-10 .njba-quote-box-content {
<?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] )) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-10 .njba-quote-icon {
<?php if( $settings->quote_sign_bg_color ) { ?> background: <?php echo '#'.$settings->quote_sign_bg_color; ?>;
<?php } ?> <?php if( $settings->quote_sign_padding !== '' ) { ?> padding: <?php echo $settings->quote_sign_padding.'px'; ?>;
<?php } ?>
}

.njba-quote-box-main.layout-10 .njba-separator-arrow {
<?php if( $settings->quote_sign_bg_color ) { ?> border-left-color: <?php echo '#'.$settings->quote_sign_bg_color; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->quotebox_layout == 11 ) { ?>
.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-11 .njba-quote-box {
<?php if( $settings->content_bg ) { ?> background: <?php echo '#'.$settings->content_bg; ?>;
<?php } ?> <?php if( $settings->quote_box_rotate ) { ?> transform: rotate(<?php echo $settings->quote_box_rotate; ?>deg);
<?php } ?>
}


.fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-11 .njba-quote-box-content {
<?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] )) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->quote_sign_bg_color ) { ?> background: <?php echo '#'.$settings->quote_sign_bg_color; ?>;
<?php } ?> <?php if( $settings->quote_boxcontent_rotate ) { ?> transform: rotate(<?php echo $settings->quote_boxcontent_rotate; ?>deg);
<?php } ?>
}


<?php } ?>
<?php if($global_settings->responsive_enabled) { // Global Setting If started ?>
@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-quote-box-main h2 {
    <?php if( isset($settings->name_font_size['medium'] )) { ?> font-size: <?php echo $settings->name_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-quote-box-main h3 {
    <?php if( isset($settings->profile_font_size['medium'] )) { ?> font-size: <?php echo $settings->profile_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-quote-box-main h4,
    .fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-3 .njba-quote-box-content h4 {
    <?php if( isset($settings->content_font_size['medium'] )) { ?> font-size: <?php echo $settings->content_font_size['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-quote-box-main h2 {
    <?php if( isset($settings->name_font_size['small'] )) { ?> font-size: <?php echo $settings->name_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-quote-box-main h3 {
    <?php if( isset($settings->profile_font_size['small'] )) { ?> font-size: <?php echo $settings->profile_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-quote-box-main h4,
    .fl-node-<?php echo $id; ?> .njba-quote-box-main.layout-3 .njba-quote-box-content h4 {
    <?php if( isset($settings->content_font_size['small'] )) { ?> font-size: <?php echo $settings->content_font_size['small'].'px'; ?>;
    <?php } ?>
    }


}

<?php } //die();?>
