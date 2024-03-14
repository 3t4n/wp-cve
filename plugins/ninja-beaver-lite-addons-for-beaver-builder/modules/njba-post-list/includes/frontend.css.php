<?php
        $btn_css_array = array(
        'button_background_color'           => $settings->button_background_color,
        'button_background_hover_color'     => $settings->button_background_hover_color,
        'button_text_color'                 =>$settings->button_text_color,
        'button_text_hover_color'           =>$settings->button_text_hover_color,
        'button_border_style'               =>$settings->button_border_style,
        'button_border_width'               =>$settings->button_border_width,
        'button_border_radius'              =>$settings->button_border_radius,
        'button_border_color'               =>$settings->button_border_color,
        'button_border_hover_color'         =>$settings->button_border_hover_color,
        'button_box_shadow'                 =>$settings->button_box_shadow,
        'button_box_shadow_color'           =>$settings->button_box_shadow_color,
        'button_padding'                    =>$settings->button_padding,
        'alignment'                         =>$settings->alignment,
        'button_font_family'                =>$settings->button_font_family,
        'button_font_size'                  =>$settings->button_font_size,
        
        );
        FLBuilder::render_module_css('njba-button' , $id, $btn_css_array);
?>
/*****For Column******/
.fl-node-<?php echo $id; ?> .njba-blog-posts-list .njba-post-wrapper {
<?php if( $settings->post_spacing['top'] !== '' ) { ?> padding-top: <?php echo $settings->post_spacing['top'].'px'; ?>;
<?php } ?> <?php if( $settings->post_spacing['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->post_spacing['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->post_spacing['left'] !== '' ) { ?> padding-left: <?php echo $settings->post_spacing['left'].'px'; ?>;
<?php } ?> <?php if( $settings->post_spacing['right'] !== '' ) { ?> padding-right: <?php echo $settings->post_spacing['right'].'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-content-grid-image {
<?php if( $settings->image_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->image_padding['top'].'px' ?>;
<?php } ?> <?php if( $settings->image_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->image_padding['bottom'].'px' ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-blog-separator {
    <?php if( $settings->separator_alignment ) { ?> text-align: <?php echo $settings->separator_alignment; ?>;
    <?php } ?>
} 

.fl-node-<?php echo $id; ?> .njba-blog-separator span {
<?php if($settings->separator_border_style !== 'none'){?> border-bottom: <?php echo $settings->separator_border_width .'px ' ; echo $settings->separator_border_style .' ' ; echo '#'.$settings->separator_border_color; ?>;
<?php } ?><?php if($settings->separator_border_style === 'none'){?> border-bottom: none;
<?php } ?><?php if($settings->separator_border_style !== 'none'){ if($settings->separator_size !== 'none'){?> width: <?php echo $settings->separator_size ; ?>%;
<?php }?><?php } ?>
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
<?php if($settings->pagination_border !== 'none'){?> border: <?php echo $settings->pagination_border_width .'px ' ; echo $settings->pagination_border .' ' ; echo '#'.$settings->pagination_border_color; ?>;
<?php } ?><?php if($settings->pagination_border !== 'none'){?> border-radius: <?php echo $settings->pagination_border_radius .'px'; ?>;
<?php } ?><?php if( $settings->pagination_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->pagination_font_family ); } if( $settings->pagination_font_size['desktop']!== '' ) { ?> font-size: <?php echo $settings->pagination_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->pagination_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->pagination_padding['top'].'px' ?>;
<?php } ?> <?php if( $settings->pagination_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->pagination_padding['right'].'px' ?>;
<?php } ?> <?php if( $settings->pagination_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->pagination_padding['bottom'].'px' ?>;
<?php } ?> <?php if( $settings->pagination_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->pagination_padding['left'].'px' ?>;
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
<?php } ?><?php if( $settings->post_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_title_font ); } if( $settings->post_title_font_size['desktop']!== '' ) { ?> font-size: <?php echo $settings->post_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_alignment ) { ?> text-align: <?php echo $settings->post_title_alignment; ?>;
<?php } ?> <?php if( $settings->post_title_height['desktop']!== '' ) { ?> line-height: <?php echo $settings->post_title_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_title_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->post_title_padding['top'].'px' ?>;
<?php } ?> <?php if( $settings->post_title_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->post_title_padding['right'].'px' ?>;
<?php } ?> <?php if( $settings->post_title_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->post_title_padding['bottom'].'px' ?>;
<?php } ?> <?php if( $settings->post_title_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->post_title_padding['left'].'px' ?>;
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

/***** For Post Content  css *****/
.fl-node-<?php echo $id; ?> .njba-content-grid-contant p {
<?php if( $settings->post_content_color ) { ?> color: <?php echo '#'.$settings->post_content_color; ?>;
<?php } ?><?php if( $settings->post_content_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_content_font ); ?><?php } ?> <?php if( $settings->post_content_font_size['desktop'] != ''  ) { ?> font-size: <?php echo $settings->post_content_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_alignment ) { ?> text-align: <?php echo $settings->post_content_alignment; ?>;
<?php } ?> <?php if( $settings->post_content_height['desktop'] !== ''  ) { ?> line-height: <?php echo $settings->post_content_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->post_content_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->post_content_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->post_content_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->post_content_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->post_content_padding['left'].'px'; ?>;
<?php } ?>
}

/***** For Post Date  css *****/
.fl-node-<?php echo $id; ?> .njba-content-grid-contant ul {
<?php if( $settings->post_date_alignment ) { ?> text-align: <?php echo $settings->post_date_alignment; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li a,
.fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li,
.fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li span {

<?php if( $settings->post_date_color ) { ?> color: <?php echo '#'.$settings->post_date_color; ?>;
<?php } ?><?php if( $settings->post_date_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->post_date_font ); ?><?php } ?> <?php if( $settings->post_date_font_size['desktop'] != '' ) { ?> font-size: <?php echo $settings->post_date_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_height['desktop'] !== '' ) { ?> line-height: <?php echo $settings->post_date_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->post_date_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->post_date_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->post_date_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->post_date_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->post_date_padding['left'].'px'; ?>;
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

    .fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li a,
    .fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li,
    .fl-node-<?php echo $id; ?> .njba-content-grid-contant ul li span {
    <?php if( $settings->post_date_font_size['small'] !== '' ) { ?> font-size: <?php echo $settings->post_date_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->post_date_height['small'] !== '' ) { ?> line-height: <?php echo $settings->post_date_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-pagination li a.page-numbers,
    .fl-node-<?php echo $id; ?> .njba-pagination li span.page-numbers {
    <?php if( $settings->pagination_font_size['small'] !== '' ) { ?> font-size: <?php echo $settings->pagination_font_size['small'].'px'; ?>;
    <?php } ?>
    }
}
