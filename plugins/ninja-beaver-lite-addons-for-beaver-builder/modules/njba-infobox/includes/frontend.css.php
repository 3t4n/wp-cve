<?php
        $settings->heading_margin_top = ( $settings->heading_margin_top !== '' ) ? $settings->heading_margin_top : '0';
?>
<?php
        $icon_img_css = array(
            'image_type'                  => $settings->image_type,
            'overall_alignment_img_icon'  => $settings->overall_alignment,
            'img_icon_show_border'        => $settings->img_icon_show_border,
            'img_icon_border_width'       => $settings->img_icon_border_width,
            'icon_img_border_radius_njba' => $settings->img_icon_border_radius,
            'img_icon_border_style'       => $settings->img_icon_border_style,
            'img_icon_border_color'       => $settings->img_icon_border_color,
            'icon_transition'             => '0.3',
            'img_icon_border_hover_color' => $settings->img_icon_border_hover_color,
            'img_icon_bg_color'           => $settings->img_icon_bg_color,
            'img_icon_bg_color_opc'       => $settings->img_icon_bg_color_opc,
            'img_icon_bg_hover_color_opc' => $settings->img_icon_bg_hover_color_opc,
            'img_icon_bg_hover_color'     => $settings->img_icon_bg_hover_color,
            'img_size'                    => $settings->img_size,
            'icon_size'                   => $settings->icon_size,
            'icon_line_height'            => $settings->icon_line_height,
            'icon_color'                  => $settings->icon_color,
            'icon_hover_color'            => $settings->icon_hover_color
        );
        FLBuilder::render_module_css('njba-icon-img' , $id, $icon_img_css);
        if($settings->cta_type === 'complete_box') { ?>
.fl-node-<?php echo $id; ?> .njba-infobox-sub-main {
    position: relative;
}

.fl-node-<?php echo $id; ?> .njba-link-infobox-module {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.fl-node-<?php echo $id ?> .njba-link-infobox-module:hover ~ .njba-icon-img-main .njba-icon-img {
    transition: all 0.3s ease;
<?php if($settings->img_icon_border_hover_color !== ''){ echo 'border-color: #'.$settings->img_icon_border_hover_color.';'; } else { echo 'border: #ffffff;'; }?><?php $settings->img_icon_bg_hover_color_opc = ( $settings->img_icon_bg_hover_color_opc !== '' ) ? $settings->img_icon_bg_hover_color_opc : '100'; ?> <?php if( $settings->img_icon_bg_hover_color ) { ?> background: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->img_icon_bg_hover_color )) ?>, <?php echo $settings->img_icon_bg_hover_color_opc/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-link-infobox-module:hover ~ .njba-icon-img-main .njba-icon-img i {
<?php if($settings->icon_hover_color){?> color: <?php echo '#'.$settings->icon_hover_color; ?>;
<?php } ?>
}

<?php }
?>
.fl-node-<?php echo $id; ?> .njba-icon-img {

<?php if($settings->img_icon_position !== 'center'){ ?><?php if($settings->icon_margin['top'] ){ ?> margin-top: <?php echo $settings->icon_margin['top'].'px'; ?>;
<?php } ?><?php if($settings->icon_margin['right'] ){ ?> margin-right: <?php echo $settings->icon_margin['right'].'px'; ?>;
<?php } ?><?php if($settings->icon_margin['bottom'] ){ ?> margin-bottom: <?php echo $settings->icon_margin['bottom'].'px'; ?>;
<?php } ?><?php if($settings->icon_margin['left'] ){ ?> margin-left: <?php echo $settings->icon_margin['left'].'px'; ?>;
<?php } ?><?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-icon-img img {
<?php if($settings->img_size ){ ?> width: <?php echo $settings->img_size.'px'; ?>;
    max-width: <?php echo $settings->img_size.'px'; ?>;;
<?php } ?>
}

<?php if($settings->img_icon_position === 'center') { ?>
.fl-node-<?php echo $id; ?> .njba-infobox-sub-main .position_right, .fl-node-<?php echo $id; ?> .njba-infobox-sub-main .position_left {
    width: 100%;
}

<?php } ?>
<?php if($settings->img_icon_position !== 'center') { ?>
<?php if($settings->img_icon_position === 'left'){
		$float = 'left';
} else {
		$float = 'right';
} ?>
.fl-node-<?php echo $id; ?> .njba-infobox-sub-main .position_center {
    float: <?php echo $float; ?>;
    width: auto;
}

.fl-node-<?php echo $id; ?> .njba-infobox-sub-main .position_left {
    float: <?php echo $float; ?>;
    width: auto;
}

.fl-node-<?php echo $id; ?> .njba-infobox-sub-main .position_right {
    float: <?php echo $float; ?>;
    width: auto;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-infobox-contant {
    overflow: hidden;
<?php if($settings->overall_alignment ){ ?> text-align: <?php echo $settings->overall_alignment; ?>;
<?php } ?><?php if($settings->content_box_padding['top'] !== '' ){ ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?><?php if($settings->content_box_padding['bottom'] !== '' ){ ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?><?php if($settings->content_box_padding['right'] !== '' ){ ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?><?php if($settings->content_box_padding['left'] !== '' ){ ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?><?php if($settings->img_icon_position === 'center') { ?><?php if($settings->overall_alignment !== 'center'){ ?> float: <?php echo $settings->overall_alignment; ?>;
    width: 100%;
<?php } ?><?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infobox-sub-main {
<?php if(($settings->bg_type !== '') && $settings->bg_color){ ?> background-color: #<?php echo $settings->bg_color; ?>;
<?php } ?><?php if($settings->infobox_padding['top'] !== '' ){ ?> padding-top: <?php echo $settings->infobox_padding['top'].'px'; ?>;
<?php } ?><?php if($settings->infobox_padding['bottom'] !== '' ){ ?> padding-bottom: <?php echo $settings->infobox_padding['bottom'].'px'; ?>;
<?php } ?><?php if($settings->infobox_padding['right'] !== '' ){ ?> padding-right: <?php echo $settings->infobox_padding['right'].'px'; ?>;
<?php } ?><?php if($settings->infobox_padding['left'] !== '' ){ ?> padding-left: <?php echo $settings->infobox_padding['left'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infobox-contant <?php echo $settings->prefix_tag_selection; ?>.heading_prefix {
<?php if($settings->prefix_color){?> color: <?php echo '#'.$settings->prefix_color; ?>;
<?php } ?><?php if($settings->prefix_font_size['desktop'] ) { ?> font-size: <?php echo $settings->prefix_font_size['desktop'].'px'; ?>;
<?php } ?><?php if($settings->prefix_line_height['desktop'] ) { ?> line-height: <?php echo $settings->prefix_line_height['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->prefix_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->prefix_font_family ); ?><?php } ?><?php if($settings->prefix_margin_top !== '' ){ ?> margin-top: <?php echo $settings->prefix_margin_top.'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-infobox-contant <?php echo $settings->title_tag_selection; ?>.heading {
<?php if($settings->title_color){?> color: <?php echo '#'.$settings->title_color; ?>;
<?php } ?><?php if($settings->title_font_size['desktop'] ) { ?> font-size: <?php echo $settings->title_font_size['desktop'].'px'; ?>;
<?php } ?><?php if($settings->title_line_height['desktop'] ) { ?> line-height: <?php echo $settings->title_line_height['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->title_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->title_font_family ); ?><?php } ?><?php if($settings->heading_margin_top !== '' ){ ?> margin-top: <?php echo $settings->heading_margin_top.'px'; ?>;
<?php } ?><?php if($settings->heading_margin_bottom !== '' ){ ?> margin-bottom: <?php echo $settings->heading_margin_bottom.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infobox-contant p {
<?php if($settings->subhead_color){?> color: #<?php echo $settings->subhead_color; ?>;
<?php } ?><?php if($settings->subhead_font_size['desktop'] ) { ?> font-size: <?php echo $settings->subhead_font_size['desktop'].'px'; ?>;
<?php } ?><?php if($settings->subhead_line_height['desktop'] ) { ?> line-height: <?php echo $settings->subhead_line_height['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->subhead_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->subhead_font_family ); ?><?php } ?><?php if($settings->content_margin_top !== '' ){ ?> margin-top: <?php echo $settings->content_margin_top.'px'; ?>;
<?php } ?><?php if($settings->content_margin_bottom !== '' ){ ?> margin-bottom: <?php echo $settings->content_margin_bottom.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infobox-contant .njba-infobox-link {
<?php if($settings->btn_link_color){?> color: #<?php echo $settings->btn_link_color; ?>;
<?php } ?><?php if($settings->btn_link_font_size['desktop'] ) { ?> font-size: <?php echo $settings->btn_link_font_size['desktop'].'px'; ?>;
<?php } ?><?php if($settings->btn_link_line_height['desktop'] ) { ?> line-height: <?php echo $settings->btn_link_line_height['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->btn_link_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->btn_link_font_family ); ?><?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infobox-contant .njba-infobox-link:hover {
<?php if($settings->btn_link_hover_color){?> color: <?php echo '#'.$settings->btn_link_hover_color; ?>;
<?php } ?>
}

<?php
        $btn_css_array = array(
            //Button Style
            'button_style'                  => $settings->button_style,
            'button_background_color'       => $settings->button_background_color,
            'button_background_hover_color' => $settings->button_background_hover_color,
            'button_text_color'             => $settings->button_text_color,
            'button_text_hover_color'       => $settings->button_text_hover_color,
            'button_border_style'           => $settings->button_border_style,
            'button_border_width'           => $settings->button_border_width,
            'button_border_radius'          => $settings->button_border_radius,
            'button_border_color'           => $settings->button_border_color,
            'button_border_hover_color'     => $settings->button_border_hover_color,
            'button_box_shadow'             => $settings->button_box_shadow,
            'button_box_shadow_color'       => $settings->button_box_shadow_color,
            'button_padding'                => $settings->button_padding,
            'transition'                    => $settings->transition,
            'width'                         => $settings->width,
            'custom_width'                  => $settings->custom_width,
            'custom_height'                 => $settings->custom_height,
            'alignment'                     => $settings->overall_alignment,
            //Button Typography
            'button_font_family'            => $settings->button_font_family,
            'button_font_size'              => $settings->button_font_size,
        );
        FLBuilder::render_module_css('njba-button' , $id, $btn_css_array);
        //  print_r($btn_css_array);
        // die();
?>
/*Responsive Css*/
<?php if( $global_settings->responsive_enabled ) { // Global Setting If started ?>

@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-infobox-contant <?php echo $settings->prefix_tag_selection; ?>.heading_prefix {
    <?php if($settings->prefix_font_size['medium'] ) { ?> font-size: <?php echo $settings->prefix_font_size['medium'].'px'; ?>;
    <?php } ?><?php if($settings->prefix_line_height['medium'] ) { ?> line-height: <?php echo $settings->prefix_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-contant <?php echo $settings->title_tag_selection; ?>.heading {
    <?php if($settings->title_font_size['medium'] ) { ?> font-size: <?php echo $settings->title_font_size['medium'].'px'; ?>;
    <?php } ?><?php if($settings->title_line_height['medium'] ) { ?> line-height: <?php echo $settings->title_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-contant p {
    <?php if($settings->subhead_font_size['medium'] ) { ?> font-size: <?php echo $settings->subhead_font_size['medium'].'px'; ?>;
    <?php } ?><?php if($settings->subhead_line_height['medium'] ) { ?> line-height: <?php echo $settings->subhead_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-contant .njba-infobox-link {
    <?php if($settings->btn_link_font_size['medium'] ) { ?> font-size: <?php echo $settings->btn_link_font_size['medium'].'px'; ?>;
    <?php } ?><?php if($settings->btn_link_line_height['medium'] ) { ?> line-height: <?php echo $settings->btn_link_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-infobox-contant <?php echo $settings->prefix_tag_selection; ?>.heading_prefix {
    <?php if($settings->prefix_font_size['small'] ) { ?> font-size: <?php echo $settings->prefix_font_size['small'].'px'; ?>;
    <?php } ?><?php if($settings->prefix_line_height['small'] ) { ?> line-height: <?php echo $settings->prefix_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-contant <?php echo $settings->title_tag_selection; ?>.heading {
    <?php if($settings->title_font_size['small'] ) { ?> font-size: <?php echo $settings->title_font_size['small'].'px'; ?>;
    <?php } ?><?php if($settings->title_line_height['small'] ) { ?> line-height: <?php echo $settings->title_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-contant p {
    <?php if($settings->subhead_font_size['small'] ) { ?> font-size: <?php echo $settings->subhead_font_size['small'].'px'; ?>;
    <?php } ?><?php if($settings->subhead_line_height['small'] ) { ?> line-height: <?php echo $settings->subhead_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infobox-contant .njba-infobox-link {
    <?php if($settings->btn_link_font_size['small'] ) { ?> font-size: <?php echo $settings->btn_link_font_size['small'].'px'; ?>;
    <?php } ?><?php if($settings->btn_link_line_height['small'] ) { ?> line-height: <?php echo $settings->btn_link_line_height['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php } ?>
