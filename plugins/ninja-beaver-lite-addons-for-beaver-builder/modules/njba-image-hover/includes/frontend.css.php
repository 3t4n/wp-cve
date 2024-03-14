<?php if( $settings->style == 1 ) { ?>

.fl-node-<?php echo $id; ?> .njba-image-hover-box-main .njba-image-box-content h1 {
<?php if( $settings->heading_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_font ); ?><?php } ?> 
<?php if( !empty($settings->font_size['desktop'] )) { ?> font-size: <?php echo $settings->font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->font_line_height['desktop'] )) { ?> line-height: <?php echo $settings->font_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->font_color ) { ?> color: <?php echo '#'.$settings->font_color; ?>;
<?php } ?> padding: <?php echo $settings->caption_padding.'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-hover-box-main .njba-square-hover .njba-box-border-line {
<?php if( $settings->inside_primary_border_color ) { ?> border-color: <?php echo '#'.$settings->inside_primary_border_color; ?>;
<?php } ?> border-width: <?php echo (empty($settings->border_size)? 1 : $settings->border_size).'px'; ?>;
    margin: <?php echo $settings->content_box_margin1.'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-square-hover .njba-box-line-top {
    height:<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    left: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    top: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    width: 10%;
}

.fl-node-<?php echo $id; ?> .njba-square-hover .njba-box-line-right {
    height:10%;
    right: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    top: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    width: <?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
}

.fl-node-<?php echo $id; ?> .njba-square-hover .njba-box-line-bottom {
    height:<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    bottom: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    right: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    width: 10%;
}

.fl-node-<?php echo $id; ?> .njba-square-hover .njba-box-line-left {
    height: 10%;
    bottom: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    left: -<?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
    width: <?php echo (empty($settings->border_size)? 1 : $settings->border_size); ?>px;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover:hover .njba-box-line-top,.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover:hover .njba-box-line-bottom {
    width: calc(100% + <?php echo $settings->border_size.'px'; ?>); 
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover:hover .njba-box-line-right,.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover:hover .njba-box-line-left {
    height: calc(100% + <?php echo $settings->border_size.'px'; ?>); 
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover .njba-image-box-content h1 span {
<?php if( !empty($settings->first_font_size['desktop'] )) { ?> font-size: <?php echo $settings->first_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->first_font_color ) { ?> color: #<?php echo $settings->first_font_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-box-line.njba-box-line-top, .fl-node-<?php echo $id; ?> .njba-box-line.njba-box-line-right, .fl-node-<?php echo $id; ?> .njba-box-line.njba-box-line-bottom, .fl-node-<?php echo $id; ?> .njba-box-line.njba-box-line-left {
<?php if( $settings->inside_secondary_border_color ) { ?> background-color: #<?php echo $settings->inside_secondary_border_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-square-hover .njba-box-line {
<?php if( !empty($settings->transition) ) { ?> transition: <?php echo 'all ease '.$settings->transition.'s';?>;
<?php } else { ?> transition: all ease 0.5s; <?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-square-hover .njba-image-box-overlay {
<?php if( $settings->hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->hover_color)) ?>, <?php echo $settings->hover_opacity/100; ?>);
<?php } ?>

}

@media only screen and (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-main .njba-image-box-content h1 {
    <?php if( !empty($settings->font_size['medium'] )) { ?> font-size: <?php echo $settings->font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['medium'] )) { ?> line-height: <?php echo $settings->font_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover .njba-image-box-content h1 span {
    <?php if( !empty($settings->first_font_size['medium'] )) { ?> font-size: <?php echo $settings->first_font_size['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 480px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-main .njba-image-box-content h1 {
    <?php if( !empty($settings->font_size['small'] )) { ?> font-size: <?php echo $settings->font_size['small'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['small'] )) { ?> line-height: <?php echo $settings->font_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover .njba-image-box-content h1 span {
    <?php if( !empty($settings->first_font_size['small'] )) { ?> font-size: <?php echo $settings->first_font_size['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
<?php if( $settings->style == 2 ) { ?>
.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-image-box-content h1 {
<?php if( $settings->heading_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_font ); ?><?php } ?> <?php if( !empty($settings->font_size['desktop'] )) { ?> font-size: <?php echo $settings->font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->font_line_height['desktop'] )) { ?> line-height: <?php echo $settings->font_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->font_color ) { ?> color: <?php echo '#'.$settings->font_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-image-box-content h1 {
    <?php if( $settings->caption_padding ) { ?> padding: <?php echo $settings->caption_padding.'px'; ?>; <?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-box-border-line-double {
<?php if( $settings->inside_primary_border_color ) { ?> border-color: <?php echo '#'.$settings->inside_primary_border_color; ?>;
<?php } ?> <?php if( $settings->inside_primary_border ) { ?> border-style: <?php echo $settings->inside_primary_border; ?>;
<?php } ?> border-width: <?php echo (empty($settings->border_size)? 1 : $settings->border_size).'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-box-border-line {
<?php if( $settings->inside_secondary_border_color ) { ?> border-color: <?php echo '#'.$settings->inside_secondary_border_color; ?>;
<?php } ?> <?php if( $settings->inside_secondary_border ) { ?> border-style: <?php echo $settings->inside_secondary_border; ?>;
<?php } ?> border-width: <?php echo (empty($settings->border_size)? 1 : $settings->border_size).'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-image-box-overlay {
<?php if( $settings->hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->hover_color)) ?>, <?php echo $settings->hover_opacity/100; ?>);
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } else { ?> transition: all ease 0.5s; <?php } ?>
}

<?php $multiple = ($settings->content_box_margin1 * 2);?>
.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-box-border-line-double {

    height: calc(100% - <?php echo $multiple.'px'; ?>);

    margin: <?php echo $settings->content_box_margin1.'px'; ?>;

    width: calc(100% - <?php echo $multiple.'px'; ?>);
}

@media only screen and (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-image-box-content h1 {

    <?php if( !empty($settings->font_size['medium'] )) { ?> font-size: <?php echo $settings->font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['medium'] )) { ?> line-height: <?php echo $settings->font_line_height['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 480px) {
    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-three .njba-image-box-content h1 {

    <?php if( !empty($settings->font_size['small'] )) { ?> font-size: <?php echo $settings->font_size['small'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['small'] )) { ?> line-height: <?php echo $settings->font_line_height['small'].'px'; ?>;
    <?php } ?>
    }
}
<?php } ?>

<?php if( $settings->style == 3 ) { ?>
.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two .njba-image-box-content h1 {
<?php if( $settings->heading_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_font ); ?><?php } ?> <?php if( !empty($settings->font_size['desktop'] )) { ?> font-size: <?php echo $settings->font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->font_line_height['desktop'] )) { ?> line-height: <?php echo $settings->font_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->font_color ) { ?> color: <?php echo '#'.$settings->font_color; ?>;
<?php } ?> padding: <?php echo $settings->caption_padding.'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two .njba-image-box-content {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } else { ?> transition: all ease 0.5s; <?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two:hover .njba-image-box-overlay {
<?php if( !empty($settings->before_padding['desktop'] )) { ?> padding: <?php echo $settings->before_padding['desktop'].'px'; }?>;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two .njba-image-box-overlay {
<?php if( !empty($settings->after_padding['desktop'] )) { ?> padding: <?php echo $settings->after_padding['desktop'].'px'; }?>;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two:hover .njba-image-box-content {
<?php if( $settings->hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->hover_color)) ?>, <?php echo $settings->hover_opacity/100; ?>);
<?php } ?> <?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } else { ?> transition: all ease 0.5s; <?php } ?>
}

@media only screen and (max-width: 991px) {
    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two .njba-image-box-content h1 {

    <?php if( !empty($settings->font_size['medium'] )) { ?> font-size: <?php echo $settings->font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['medium'] )) { ?> line-height: <?php echo $settings->font_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two:hover .njba-image-box-overlay {

    <?php if( !empty($settings->before_padding['medium'] )) { ?> padding: <?php echo $settings->before_padding['medium'].'px'; }?>;
    }

    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two .njba-image-box-overlay {

    <?php if( !empty($settings->after_padding['medium'] )) { ?> padding: <?php echo $settings->after_padding['medium'].'px'; }?>;
    }
}

@media only screen and (max-width: 767px) {
    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two .njba-image-box-content h1 {

    <?php if( !empty($settings->font_size['small'] )) { ?> font-size: <?php echo $settings->font_size['small'].'px'; ?>;
    <?php } ?>
    <?php if( !empty($settings->font_line_height['small'] )) { ?> line-height: <?php echo $settings->font_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two:hover .njba-image-box-overlay {

    <?php if( !empty($settings->before_padding['small'] )) { ?> padding: <?php echo $settings->before_padding['small'].'px'; }?>;
    }

    .fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-two .njba-image-box-overlay {

    <?php if( !empty($settings->after_padding['small'] )) { ?> padding: <?php echo $settings->after_padding['small'].'px'; }?>;
    }
}

<?php } ?>
<?php if( $settings->style == 4 ) { ?>

.fl-node-<?php echo $id; ?> .njba-image-hover-box-main .njba-image-box-content h1 {
<?php if( $settings->heading_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_font ); ?><?php } ?> <?php if( !empty($settings->font_size['desktop'] )) { ?> font-size: <?php echo $settings->font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->font_line_height['desktop'] )) { ?> line-height: <?php echo $settings->font_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->font_color ) { ?> color: <?php echo '#'.$settings->font_color; ?>;
<?php } ?> padding: <?php echo $settings->caption_padding.'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-four .njba-image-box-overlay, .fl-node-<?php echo $id; ?> .hover-one .njba-image-box.njba-square-hover-four .njba-image-box-img {
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } else { ?> transition: all ease 0.5s; <?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-four .njba-image-box-overlay {
<?php if( $settings->hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->hover_color)) ?>, <?php echo $settings->hover_opacity/100; ?>);
<?php } ?>
}

@media only screen and (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-main .njba-image-box-content h1 {
    <?php if( !empty($settings->font_size['medium'] )) { ?> font-size: <?php echo $settings->font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['medium'] )) { ?> line-height: <?php echo $settings->font_line_height['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 480px) {
    .fl-node-<?php echo $id; ?> .njba-image-hover-box-main .njba-image-box-content h1 {
    <?php if( !empty($settings->font_size['small'] )) { ?> font-size: <?php echo $settings->font_size['small'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['small'] )) { ?> line-height: <?php echo $settings->font_line_height['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
<?php if( $settings->style == 5 ) { ?>
.fl-node-<?php echo $id; ?> .njba-square-hover-five .njba-image-box-content h1 {
<?php if( $settings->heading_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_font ); ?><?php } ?> <?php if( !empty($settings->font_size['desktop'] )) { ?> font-size: <?php echo $settings->font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->font_line_height['desktop'] )) { ?> line-height: <?php echo $settings->font_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->font_color ) { ?> color: <?php echo '#'.$settings->font_color; ?>;
<?php } ?> padding: <?php echo $settings->caption_padding.'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-five:hover .njba-image-box-overlay {
<?php if( $settings->hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->hover_color)) ?>, <?php echo $settings->hover_opacity/100; ?>);
<?php } ?> transform: rotate(<?php echo $settings->rotate_hover;?>deg) scale(<?php echo $settings->scale;?>);
}

.fl-node-<?php echo $id; ?> .njba-image-box.njba-square-hover-five .njba-image-box-overlay {

<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } else { ?> transition: all ease 0.5s; <?php } ?> transform: rotate(<?php echo $settings->rotate;?>deg) scale(1);
}

@media only screen and (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-square-hover-five .njba-image-box-content h1 {

    <?php if( !empty($settings->font_size['medium'] )) { ?> font-size: <?php echo $settings->font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['medium'] )) { ?> line-height: <?php echo $settings->font_line_height['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media only screen and (max-width: 480px) {
    .fl-node-<?php echo $id; ?> .njba-square-hover-five .njba-image-box-content h1 {

    <?php if( !empty($settings->font_size['small'] )) { ?> font-size: <?php echo $settings->font_size['small'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->font_line_height['small'] )) { ?> line-height: <?php echo $settings->font_line_height['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
