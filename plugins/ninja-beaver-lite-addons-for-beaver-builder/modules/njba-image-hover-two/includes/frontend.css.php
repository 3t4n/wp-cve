<?php if( $settings->style == 1 ) { ?>
.fl-node-<?php echo $id; ?> .njba-image-hover-border h1 {
<?php if( $settings->caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->caption_font ); ?><?php } ?> <?php if(  !empty($settings->caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->caption_font_color ) { ?> color: <?php echo '#'.$settings->caption_font_color; ?>;
<?php } ?> <?php if($settings->caption_alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->caption_alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->caption_alignment === 'right'){?> text-align: right;
<?php }?> <?php if( !empty($settings->caption_padding['top'])) { ?> padding-top: <?php echo $settings->caption_padding['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->caption_padding['right'])) { ?> padding-right: <?php echo $settings->caption_padding['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->caption_padding['bottom'])) { ?> padding-bottom: <?php echo $settings->caption_padding['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->caption_padding['left'])) { ?> padding-left: <?php echo $settings->caption_padding['left'].'px';?>;
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-border h2 {
<?php if( $settings->sub_caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->sub_caption_font ); ?><?php } ?> <?php if(  !empty($settings->sub_caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->sub_caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->sub_caption_font_color ) { ?> color: <?php echo '#'.$settings->sub_caption_font_color; ?>;
<?php } ?> <?php if($settings->sub_caption_alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->sub_caption_alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->sub_caption_alignment === 'right'){?> text-align: right;
<?php }?> <?php if( !empty($settings->sub_caption_padding['top'])) { ?> padding-top: <?php echo $settings->sub_caption_padding['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->sub_caption_padding['right'])) { ?> padding-right: <?php echo $settings->sub_caption_padding['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->sub_caption_padding['bottom'])) { ?> padding-bottom: <?php echo $settings->sub_caption_padding['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->sub_caption_padding['left'])) { ?> padding-left: <?php echo $settings->sub_caption_padding['left'].'px';?>;
<?php } ?> <?php if(  !empty($settings->transition )) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-border {
<?php if( $settings->background_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->background_color)) ?>, <?php echo $settings->background_color_opacity/100; ?>);
<?php } ?> <?php if( $settings->margin1 ) { ?> margin: <?php echo $settings->margin1.'px'; ?>;
<?php }?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?> <?php if($settings->border_color) { ?> border-color: <?php echo '#'.$settings->border_color;?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_border_radius['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_border_radius['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_border_radius['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_border_radius['left'].'px';?>;
<?php } ?> <?php if($settings->box_border_width >= '0') { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if($settings->box_border_style) { ?> border-style: <?php echo $settings->box_border_style;?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box1:hover .njba-image-hover-border {
<?php if(!empty($settings->hover_border_color)) { ?> border-color: <?php echo '#'.$settings->hover_border_color;?>;
<?php } ?> <?php if( !empty($settings->background_hover_color ) ){ ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->background_hover_color)) ?>, <?php echo $settings->background_hover_color_opacity/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box1 {
    box-shadow: <?php if($settings->box_shadow['left_right'] !==''){ echo $settings->box_shadow['left_right'].'px '; } if($settings->box_shadow['top_bottom'] !==''){ echo $settings->box_shadow['top_bottom'].'px '; } if($settings->box_shadow['blur'] !==''){ echo $settings->box_shadow['blur'].'px '; } if($settings->box_shadow['spread'] !==''){ echo $settings->box_shadow['spread'].'px '; } echo '#'.$settings->box_shadow_color;  ?>;
}

@media only screen and (max-width: 991px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-border h1 {
    <?php if(  !empty($settings->caption_font_size['medium'] )) { ?> font-size: <?php echo $settings->caption_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-border h2 {
    <?php if(  !empty($settings->sub_caption_font['medium'] )) { ?> font-size: <?php echo $settings->sub_caption_font['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 767px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-border h1 {
    <?php if(  !empty($settings->caption_font_size['small'] )) { ?> font-size: <?php echo $settings->caption_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-border h2 {
    <?php if(  !empty($settings->sub_caption_font['small'] )) { ?> font-size: <?php echo $settings->sub_caption_font['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
<?php if( $settings->style == 2 ) { ?>
.fl-node-<?php echo $id; ?> .njba-image-hover-box-three h1 {
<?php if( $settings->caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->caption_font ); ?><?php } ?> <?php if(  !empty($settings->caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->caption_font_color ) { ?> color: <?php echo '#'.$settings->caption_font_color; ?>;
<?php } ?> <?php if($settings->caption_alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->caption_alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->caption_alignment === 'right'){?> text-align: right;
<?php }?> <?php if( !empty($settings->caption_padding['top'])) { ?> padding-top: <?php echo $settings->caption_padding['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->caption_padding['right'])) { ?> padding-right: <?php echo $settings->caption_padding['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->caption_padding['bottom'])) { ?> padding-bottom: <?php echo $settings->caption_padding['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->caption_padding['left'])) { ?> padding-left: <?php echo $settings->caption_padding['left'].'px';?>;
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box2 {
<?php if( $settings->background_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->background_color)) ?>, <?php echo $settings->background_color_opacity/100; ?>);
<?php }  ?> <?php if($settings->border_color) { ?> border-color: <?php echo '#'.$settings->border_color;?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_border_radius['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_border_radius['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_border_radius['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_border_radius['left'].'px';?>;
<?php } ?> <?php if($settings->box_border_width >= '0') { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if($settings->box_border_style) { ?> border-style: <?php echo $settings->box_border_style;?>;
<?php } ?> <?php if( $settings->padding1 ) { ?> padding: <?php echo $settings->padding1.'px'; ?>;
<?php }?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-image-hover-box2:hover {
<?php if($settings->hover_border_color) { ?> border-color: <?php echo '#'.$settings->hover_border_color;?>;
<?php } ?> padding: 0;
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-three:hover {

<?php if( $settings->background_hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->background_hover_color)) ?>, <?php echo $settings->background_hover_color_opacity/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-three {

<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box2:hover .njba-image-responsive {
<?php if($settings->hover_border_color) { ?> border-color: <?php echo '#'.$settings->hover_border_color;?>;
<?php } ?>
}

@media only screen and (max-width: 991px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-three h1 {
    <?php if(  !empty($settings->caption_font_size['medium'] )) { ?> font-size: <?php echo $settings->caption_font_size['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 767px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-three h1 {
    <?php if(  !empty($settings->caption_font_size['small'] )) { ?> font-size: <?php echo $settings->caption_font_size['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
<?php if( $settings->style == 3 ) { ?>
.fl-node-<?php echo $id; ?> .njba-image-hover-box-four:hover .njba-image-hover-img {
<?php if($settings->background_blur) { ?> filter: blur(<?php echo $settings->background_blur;?>px);
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php }  ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-name-one {
<?php if( $settings->caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->caption_font ); ?><?php } ?> <?php if(  !empty($settings->caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->caption_font_color ) { ?> color: <?php echo '#'.$settings->caption_font_color; ?>;
<?php } ?> <?php if($settings->caption_alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->caption_alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->caption_alignment === 'right'){?> text-align: right;
<?php }?> <?php if( !empty($settings->caption_margin['top'])) { ?> margin-top: <?php echo $settings->caption_margin['top'];?>%;
<?php } ?> <?php if( !empty($settings->caption_margin['right'])) { ?> margin-right: <?php echo $settings->caption_margin['right'];?>%;
<?php } ?> <?php if( !empty($settings->caption_margin['bottom'])) { ?> margin-bottom: <?php echo $settings->caption_margin['bottom'];?>%;
<?php } ?> <?php if( !empty($settings->caption_margin['left'])) { ?> margin-left: <?php echo $settings->caption_margin['left'];?>%;
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-name-two {
<?php if( $settings->sub_caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->sub_caption_font ); ?><?php } ?> <?php if(  !empty($settings->sub_caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->sub_caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->sub_caption_font_color ) { ?> color: <?php echo '#'.$settings->sub_caption_font_color; ?>;
<?php } ?> <?php if($settings->sub_caption_alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->sub_caption_alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->sub_caption_alignment === 'right'){?> text-align: right;
<?php }?> <?php if( !empty($settings->sub_caption_margin['top'])) { ?> margin-top: <?php echo $settings->sub_caption_margin['top'];?>%;
<?php } ?> <?php if( !empty($settings->sub_caption_margin['right'])) { ?> margin-right: <?php echo $settings->sub_caption_margin['right'];?>%;
<?php } ?> <?php if( !empty($settings->sub_caption_margin['bottom'])) { ?> margin-bottom: <?php echo $settings->sub_caption_margin['bottom'];?>%;
<?php } ?> <?php if( !empty($settings->sub_caption_margin['left'])) { ?> margin-left: <?php echo $settings->sub_caption_margin['left'];?>%;
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-four {
<?php if( $settings->margin1 ) { ?> margin: <?php echo $settings->margin1.'px'; ?>;
<?php }?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?> <?php if($settings->border_color) { ?> border-color: <?php echo '#'.$settings->border_color;?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_border_radius['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_border_radius['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_border_radius['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_border_radius['left'].'px';?>;
<?php } ?> <?php if($settings->box_border_width >= '0') { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if($settings->box_border_style) { ?> border-style: <?php echo $settings->box_border_style;?>;
<?php } ?> box-shadow: <?php if($settings->box_shadow['left_right'] !==''){ echo $settings->box_shadow['left_right'].'px '; } if($settings->box_shadow['top_bottom'] !==''){ echo $settings->box_shadow['top_bottom'].'px '; } if($settings->box_shadow['blur'] !==''){ echo $settings->box_shadow['blur'].'px ';  } if($settings->box_shadow['spread'] !==''){ echo $settings->box_shadow['spread'].'px ';  } echo '#'.$settings->box_shadow_color;  ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-four:hover {
<?php if($settings->hover_border_color) { ?> border-color: <?php echo '#'.$settings->hover_border_color;?>;
<?php } ?>
}

@media only screen and (max-width: 991px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-name-one {
    <?php if(  !empty($settings->caption_font_size['medium'] )) { ?> font-size: <?php echo $settings->caption_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-name-two {
    <?php if(  !empty($settings->sub_caption_font['medium'] )) { ?> font-size: <?php echo $settings->sub_caption_font['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 767px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-name-one {
    <?php if( !empty($settings->caption_font_size['small'] )) { ?> font-size: <?php echo $settings->caption_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-name-two {
    <?php if(  !empty($settings->sub_caption_font['small'] )) { ?> font-size: <?php echo $settings->sub_caption_font['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
<?php if( $settings->style == 4 ) { ?>
.fl-node-<?php echo $id; ?> .njba-image-hover-box-five {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?> <?php if($settings->border_color) { ?> border-color: <?php echo '#'.$settings->border_color;?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_border_radius['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_border_radius['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_border_radius['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_border_radius['left'].'px';?>;
<?php } ?> <?php if($settings->box_border_width >= '0') { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if($settings->box_border_style) { ?> border-style: <?php echo $settings->box_border_style;?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-five img.njba-image-responsive {
<?php if( !empty($settings->box_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_border_radius['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_border_radius['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_border_radius['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_border_radius['left'].'px';?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one:hover {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?> <?php if($settings->hover_border_color) { ?> border-color: <?php echo '#'.$settings->hover_border_color;?>;
<?php } ?> <?php if( !empty($settings->box_hover_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_hover_border_radius['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_hover_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_hover_border_radius['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_hover_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_hover_border_radius['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_hover_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_hover_border_radius['left'].'px'; ?>;
<?php } ?> <?php if($settings->box_hover_border_width >= '0') { ?> border-width: <?php echo $settings->box_hover_border_width.'px'; ?>;
<?php } ?> <?php if($settings->box_Hover_border_style) { ?> border-style: <?php echo $settings->box_Hover_border_style;?>;
<?php } ?> box-shadow: <?php if($settings->box_shadow['left_right'] !==''){ echo $settings->box_shadow['left_right'].'px '; } if($settings->box_shadow['top_bottom'] !==''){ echo $settings->box_shadow['top_bottom'].'px '; } if($settings->box_shadow['blur'] !==''){ echo $settings->box_shadow['blur'].'px '; } if($settings->box_shadow['spread'] !==''){ echo $settings->box_shadow['spread'].'px '; } echo '#'.$settings->box_shadow_color;  ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-five:hover img.njba-image-responsive {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?><?php if( !empty($settings->box_hover_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_hover_border_radius['top'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_hover_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_hover_border_radius['right'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_hover_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_hover_border_radius['bottom'].'px';?>;
<?php } ?> <?php if( !empty($settings->box_hover_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_hover_border_radius['left'].'px';?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one h1 {
<?php if( $settings->caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->caption_font ); ?><?php } ?> <?php if(  !empty($settings->caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->caption_font_color ) { ?> color: <?php echo '#'.$settings->caption_font_color; ?>;
<?php } ?> text-align: center;
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one h2 {
<?php if( $settings->sub_caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->sub_caption_font ); ?><?php } ?> <?php if(  !empty($settings->sub_caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->sub_caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->sub_caption_font_color ) { ?> color: <?php echo '#'.$settings->sub_caption_font_color; ?>;
<?php } ?> text-align: center;
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

@media only screen and (max-width: 991px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one h1 {
    <?php if(  !empty($settings->caption_font_size['medium'] )) { ?> font-size: <?php echo $settings->caption_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one h2 {
    <?php if(  !empty($settings->sub_caption_font['medium'] )) { ?> font-size: <?php echo $settings->sub_caption_font['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 767px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one h1 {
    <?php if(  !empty($settings->caption_font_size['small'] )) { ?> font-size: <?php echo $settings->caption_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-box-five-one h2 {
    <?php if(  !empty($settings->sub_caption_font['small'] )) { ?> font-size: <?php echo $settings->sub_caption_font['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
<?php if( $settings->style == 5 ) { ?>
.fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h1 {
<?php if( $settings->caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->caption_font ); ?><?php } ?> <?php if(  !empty($settings->caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->caption_font_color ) { ?> color: <?php echo '#'.$settings->caption_font_color; ?>;
<?php } ?> <?php if($settings->caption_alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->caption_alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->caption_alignment === 'right'){?> text-align: right;
<?php }?> padding: 0;
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h2 {
<?php if( $settings->sub_caption_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->sub_caption_font ); ?><?php } ?> <?php if(  !empty($settings->sub_caption_font_size['desktop'] )) { ?> font-size: <?php echo $settings->sub_caption_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->sub_caption_font_color ) { ?> color: <?php echo '#'.$settings->sub_caption_font_color; ?>;
<?php } ?> <?php if($settings->sub_caption_alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->sub_caption_alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->sub_caption_alignment === 'right'){?> text-align: right;
<?php }?> padding: 0;
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-six {
<?php if($settings->border_color) { ?> border-color: <?php echo '#'.$settings->border_color;?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->box_border_radius['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['right'])) { ?> border-top-right-radius: <?php echo $settings->box_border_radius['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->box_border_radius['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->box_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->box_border_radius['left'].'px'; ?>;
<?php } ?> <?php if($settings->box_border_width >= '0') { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if($settings->box_border_style) { ?> border-style: <?php echo $settings->box_border_style;?>;
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-six {
<?php if($settings->border_color) { ?> border-color: <?php echo '#'.$settings->border_color;?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-six:hover {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?> <?php if($settings->hover_border_color) { ?> border-color: <?php echo '#'.$settings->hover_border_color;?>;
<?php } ?> box-shadow: <?php if($settings->box_shadow['left_right'] !==''){ echo $settings->box_shadow['left_right'].'px '; } if($settings->box_shadow['top_bottom'] !==''){ echo $settings->box_shadow['top_bottom'].'px '; } if($settings->box_shadow['blur'] !==''){ echo $settings->box_shadow['blur'].'px '; } if($settings->box_shadow['spread'] !==''){ echo $settings->box_shadow['spread'].'px '; } echo '#'.$settings->box_shadow_color;  ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-six:hover .njba-image-hover-box-six-one {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?> <?php if( $settings->background_hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->background_hover_color)) ?>, <?php echo $settings->background_hover_color_opacity/100; ?>);
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h1.caption-selector {
<?php if( !empty($settings->caption_padding['top'])) { ?> padding-top: <?php echo $settings->caption_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->caption_padding['right'])) { ?> padding-right: <?php echo $settings->caption_padding['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->caption_padding['bottom'])) { ?> padding-bottom: <?php echo $settings->caption_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->caption_padding['left'])) { ?> padding-left: <?php echo $settings->caption_padding['left'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h2.sub-caption-selector {
<?php if( !empty($settings->sub_caption_padding['top'])) { ?> padding-top: <?php echo $settings->sub_caption_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->sub_caption_padding['right'])) { ?> padding-right: <?php echo $settings->sub_caption_padding['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->sub_caption_padding['bottom'])) { ?> padding-bottom: <?php echo $settings->sub_caption_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->sub_caption_padding['left'])) { ?> padding-left: <?php echo $settings->sub_caption_padding['left'].'px'; ?>;
<?php } ?>
}

@media only screen and (max-width: 991px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h1 {
    <?php if(  !empty($settings->caption_font_size['medium'] )) { ?> font-size: <?php echo $settings->caption_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h2 {
    <?php if(  !empty($settings->sub_caption_font['medium'] )) { ?> font-size: <?php echo $settings->sub_caption_font['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 767px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h1 {
    <?php if(  !empty($settings->caption_font_size['small'] )) { ?> font-size: <?php echo $settings->caption_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-hover-box-six-one h2 {
    <?php if(  !empty($settings->sub_caption_font['small'] )) { ?> font-size: <?php echo $settings->sub_caption_font['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
