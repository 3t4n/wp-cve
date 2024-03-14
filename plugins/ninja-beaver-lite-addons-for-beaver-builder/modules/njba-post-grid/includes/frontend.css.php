<?php
        $btn_css_array = array(
            //Button Style
            /*'button_style'      => $settings->button_style,*/
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
            'alignment'                     => $settings->alignment,
            //Button Typography
            'button_font_family'            => $settings->button_font_family,
            'button_font_size'              => $settings->button_font_size,
        );
        FLBuilder::render_module_css('njba-button' , $id, $btn_css_array);
?>
/***** For Carousel  css *****/
.fl-node-<?php echo $id; ?> .njba-style-1 .njba-content-grid:hover .njba-btn-main,
.fl-node-<?php echo $id; ?> .njba-style-1 .njba-content-grid:hover p {
    text-align: center;
}

.fl-node-<?php echo $id; ?> .njba-style-1 .njba-content-grid:hover p {
<?php if( $settings->back_text_color ) { ?> color: <?php echo '#'.$settings->back_text_color; ?>;
<?php } ?>
}

/*****For Column******/
.fl-node-<?php echo $id; ?> .njba-<?php echo $settings->post_grid_style_select; ?> .njba-content-grid {
<?php if( $settings->col_bg_color ) { ?> background: <?php echo '#'.$settings->col_bg_color; ?>;
<?php } ?> box-shadow: <?php if ($settings->col_box_shadow['left_right'] !== ''){ echo $settings->col_box_shadow['left_right'].'px ';} if ($settings->col_box_shadow['top_bottom'] !== ''){  echo $settings->col_box_shadow['top_bottom'].'px ';} if ($settings->col_box_shadow['blur'] !== ''){ echo $settings->col_box_shadow['blur'].'px ';} if ($settings->col_box_shadow['spread'] !== ''){ echo $settings->col_box_shadow['spread'].'px ';} echo '#'.$settings->col_box_shadow_color;?>;
<?php if($settings->col_border_width >= '0') { ?> border-width: <?php echo $settings->col_border_width.'px'; ?>;
<?php } ?> <?php if($settings->col_border_style) { ?> border-style: <?php echo $settings->col_border_style;?>;
<?php } ?> <?php if($settings->col_border_color) {?> border-color: <?php echo '#'.$settings->col_border_color;?>;
<?php } ?><?php if($settings->col_border_radius !== ''){?> border-radius: <?php echo $settings->col_border_radius .'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-<?php echo $settings->post_grid_style_select; ?> .njba-content-grid:hover {
    box-shadow: <?php if ($settings->col_box_shadow['left_right'] !== ''){ echo $settings->col_box_shadow['left_right'].'px ';} if ($settings->col_box_shadow['top_bottom'] !== ''){  echo $settings->col_box_shadow['top_bottom'].'px ';} if ($settings->col_box_shadow['blur'] !== ''){ echo $settings->col_box_shadow['blur'].'px ';} if ($settings->col_box_shadow['spread'] !== ''){ echo $settings->col_box_shadow['spread'].'px ';} echo '#'.$settings->col_box_shadow_hover_color;?>;
<?php if($settings->col_border_hover_color) {?> border-color: <?php echo '#'.$settings->col_border_hover_color;?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-blog-posts-grid .njba-post-wrapper {
<?php if( $settings->post_spacing !== '' ) { ?> padding: <?php echo $settings->post_spacing.'px'; ?>;
<?php } ?>
}

/***** For Pagination   css *****/
.fl-node-<?php echo $id; ?> .njba-pagination li a.page-numbers {
<?php if( $settings->pagi_color ) { ?> color: <?php echo '#'.$settings->pagi_color; ?>;
<?php } ?> <?php if( $settings->pagi_bg_color ) { ?> background: <?php echo '#'.$settings->pagi_bg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pagination li span.page-numbers,
.fl-node-<?php echo $id; ?> .njba-pagination li a.page-numbers:hover {
<?php if( $settings->pagi_active_color ) { ?> color: <?php echo '#'.$settings->pagi_active_color; ?>;
<?php } ?> <?php if( $settings->pagi_activebg_color ) { ?> background: <?php echo '#'.$settings->pagi_activebg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pagination li a.page-numbers,
.fl-node-<?php echo $id; ?> .njba-pagination li span.page-numbers {
<?php if($settings->pagination_border !== 'none'){?> border: <?php echo $settings->pagination_border_width.'px '; echo $settings->pagination_border.' ' ; echo '#'.$settings->pagination_border_color; ?>;
<?php } ?><?php if($settings->pagination_border_radius){?> border-radius: <?php echo $settings->pagination_border_radius .'px'; ?>;
<?php } ?> <?php if( $settings->pagination_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->pagination_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->pagination_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->pagination_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->pagination_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->pagination_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->pagination_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->pagination_padding['left'].'px'; ?>;
<?php } ?><?php if( $settings->pagination_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->pagination_font_family ); ?><?php } ?> <?php if( $settings->pagination_font_size['desktop'] != '' ) { ?> font-size: <?php echo $settings->pagination_font_size['desktop'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pagination ul.page-numbers li {
    margin-left: <?php echo $settings->pagination_spacing.'px'; ?>;
    margin-right: <?php echo $settings->pagination_spacing.'px'; ?>;
    padding-top: <?php echo $settings->pagination_spacing_v.'px'; ?>;
    padding-bottom: <?php echo $settings->pagination_spacing_v.'px'; ?>;
}

/***** For Post Title  css *****/
.fl-node-<?php echo $id; ?> .njba-content-grid-contant <?php echo $settings->post_title_tag; ?> {
<?php if( $settings->post_title_color ) { ?> color: <?php echo '#'.$settings->post_title_color; ?>;
<?php } ?><?php if( $settings->post_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_title_font ); ?><?php } ?> <?php if( $settings->post_title_font_size['desktop'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_alignment ) { ?> text-align: <?php echo $settings->post_title_alignment; ?>;
<?php } ?> <?php if( $settings->post_title_height['desktop'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->post_title_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->post_title_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->post_title_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->post_title_padding['left'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-content-grid-contant <?php echo $settings->post_title_tag; ?> a {
<?php if( $settings->post_title_color ) { ?> color: <?php echo '#'.$settings->post_title_color; ?>;
<?php } ?><?php if( $settings->post_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_title_font ); ?><?php } ?> <?php if( $settings->post_title_font_size['desktop'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_alignment ) { ?> text-align: <?php echo $settings->post_title_alignment; ?>;
<?php } ?> <?php if( $settings->post_title_height['desktop'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['desktop'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-content-grid-contant <?php echo $settings->post_title_tag; ?> a:hover {
<?php if( $settings->post_title_hover_color ) { ?> color: <?php echo '#'.$settings->post_title_hover_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> <?php echo $settings->post_title_tag; ?>.horizontal_title {
<?php if( $settings->post_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_title_font ); ?><?php } ?> <?php if( $settings->post_title_font_size['desktop'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_height['desktop'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['desktop'].'px'; ?>;
<?php } ?>
}

<?php //die();?>
/***** For Post Content  css *****/
.fl-node-<?php echo $id; ?> .njba-content-grid-contant p {
<?php if( $settings->post_content_color ) { ?> color: <?php echo '#'.$settings->post_content_color; ?>;
<?php } ?><?php if( $settings->post_content_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_content_font ); ?><?php } ?> <?php if( $settings->post_content_font_size['desktop'] !== ''  ) { ?> font-size: <?php echo $settings->post_content_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_alignment ) { ?> text-align: <?php echo $settings->post_content_alignment; ?>;
<?php } ?> <?php if( $settings->post_content_height['desktop']!== ''  ) { ?> line-height: <?php echo $settings->post_content_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->post_content_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->post_content_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->post_content_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->post_content_padding['left'].'px'; ?>;
<?php } ?>
}

/***** For Post Date  css *****/
.fl-node-<?php echo $id; ?> .njba-content-grid-section-wrapper ul {
<?php if( $settings->post_date_alignment ) { ?> text-align: <?php echo $settings->post_date_alignment; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-content-grid-section-wrapper ul li a,
.fl-node-<?php echo $id; ?> .njba-content-grid-section-wrapper ul li,
.fl-node-<?php echo $id; ?> .njba-content-grid-section-wrapper ul li span {

<?php if( $settings->post_date_color ) { ?> color: <?php echo '#'.$settings->post_date_color; ?>;
<?php } ?><?php if( $settings->post_date_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_date_font ); ?><?php } ?> <?php if( $settings->post_date_font_size['desktop'] !== '' ) { ?> font-size: <?php echo $settings->post_date_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_height['desktop'] !== '' ) { ?> line-height: <?php echo $settings->post_date_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->post_date_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->post_date_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->post_date_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->post_date_padding['left'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .vertical_title {
<?php if( $settings->front_title_bc_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->front_title_bc_color)) ?>, <?php echo $settings->front_title_bc_color_opc/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .vertical_title {
<?php if( $settings->front_title_color ) { ?> color: <?php echo '#'.$settings->front_title_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba_front_inner {
<?php if( $settings->front_date_bc_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->front_date_bc_color)) ?>, <?php echo $settings->front_date_bc_color_opc/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba_front_inner span {
<?php if( $settings->front_date_color ) { ?> color: <?php echo '#'.$settings->front_date_color; ?>;
<?php } ?>
}

@media ( max-width: 991px ) {
    .fl-node-<?php echo $id; ?> .njba-content-grid-contant <?php echo $settings->post_title_tag; ?> {
    <?php if( $settings->post_title_font_size['medium'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_title_height['medium'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-content-grid-contant <?php echo $settings->post_title_tag; ?> a {
    <?php if( $settings->post_title_font_size['medium'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_title_height['medium'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-content-grid-contant p {
    <?php if( $settings->post_content_font_size['medium'] !== ''  ) { ?> font-size: <?php echo $settings->post_content_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_content_height['medium'] !== ''  ) { ?> line-height: <?php echo $settings->post_content_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li a,
    .fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li,
    .fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li span {
    <?php if( $settings->post_date_font_size['medium'] !== '' ) { ?> font-size: <?php echo $settings->post_date_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_date_height['medium'] !== '' ) { ?> line-height: <?php echo $settings->post_date_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-pagination li a.page-numbers,
    .fl-node-<?php echo $id; ?> .njba-pagination li span.page-numbers {
    <?php if( $settings->pagination_font_size['medium'] !== '' ) { ?> font-size: <?php echo $settings->pagination_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> <?php echo $settings->post_title_tag; ?>.horizontal_title, {
    <?php if( $settings->post_title_font_size['medium'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_title_height['medium'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media ( max-width: 767px ) {
    .fl-node-<?php echo $id; ?> .njba-content-grid-contant <?php echo $settings->post_title_tag; ?> {
    <?php if( $settings->post_title_font_size['small'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_title_height['small'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-content-grid-contant <?php echo $settings->post_title_tag; ?> a {
    <?php if( $settings->post_title_font_size['small'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_title_height['small'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-content-grid-contant p {
    <?php if( $settings->post_content_font_size['small'] !== ''  ) { ?> font-size: <?php echo $settings->post_content_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_content_height['small'] !== ''  ) { ?> line-height: <?php echo $settings->post_content_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-content-grid-section-wrapper ul li a,
    .fl-node-<?php echo $id; ?> .njba-content-grid-section-wrapper ul li,
    .fl-node-<?php echo $id; ?> .njba-content-grid-section-wrapper ul li span {
    <?php if( $settings->post_date_font_size['small'] !== '' ) { ?> font-size: <?php echo $settings->post_date_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_date_height['small'] !== '' ) { ?> line-height: <?php echo $settings->post_date_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-pagination li a.page-numbers,
    .fl-node-<?php echo $id; ?> .njba-pagination li span.page-numbers {
    <?php if( $settings->pagination_font_size['small'] !== '' ) { ?> font-size: <?php echo $settings->pagination_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> <?php echo $settings->post_title_tag; ?>.horizontal_title, {
    <?php if( $settings->post_title_font_size['small'] !== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_title_height['small'] !== '' ) { ?> line-height: <?php echo $settings->post_title_height['small'].'px'; ?>;
    <?php } ?>
    }
}
