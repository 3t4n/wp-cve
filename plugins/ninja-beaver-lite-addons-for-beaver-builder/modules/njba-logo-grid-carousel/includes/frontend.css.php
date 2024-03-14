.fl-node-<?php echo $id; ?> .bx-wrapper .bx-pager.bx-default-pager a {
<?php if( $settings->dot_color ) { ?> background: <?php echo '#'.$settings->dot_color; ?><?php } ?>;
    opacity: 0.5;
}

.fl-node-<?php echo $id; ?> .bx-wrapper .bx-pager.bx-default-pager a.active {
<?php if( $settings->active_dot_color ) { ?> background: <?php echo '#'.$settings->active_dot_color; ?>;
<?php } ?> opacity: 1;
}

.fl-node-<?php echo $id; ?> .bx-wrapper .bx-controls-direction a.bx-next,
.fl-node-<?php echo $id; ?> .bx-wrapper .bx-controls-direction a.bx-prev {
<?php if( $settings->arrow_background ) { ?> background: <?php echo '#'.$settings->arrow_background; ?>;
<?php } ?> <?php if( $settings->arrow_color ) { ?> color: <?php echo '#'.$settings->arrow_color; ?>;
<?php } ?> <?php if( $settings->arrow_border_radius ) { ?> border-radius: <?php echo $settings->arrow_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->arrows_size ) { ?> font-size: <?php echo $settings->arrows_size.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-logo-grid-main img.njba-logo-image-responsive,
.fl-node-<?php echo $id; ?> .njba-logo-carousel-main img.njba-logo-image-responsive {
<?php if( $settings->img_max_width['desktop'] !== '' ) { ?> max-width: <?php echo $settings->img_max_width['desktop'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-logo-grid-main .njba-out-side,
.fl-node-<?php echo $id; ?> .njba-logo-carousel-main .njba-out-side {
<?php if( $settings->col_out_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->col_out_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->col_out_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->col_out_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->col_out_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->col_out_padding['left'].'px'; ?>;
<?php } ?> <?php if( $settings->col_out_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->col_out_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-logo-grid-main .njba-logo-inner,
.fl-node-<?php echo $id; ?> .njba-logo-carousel-main .njba-logo-inner {
<?php if( $settings->col_height['desktop'] !== '' ) { ?> height: <?php echo $settings->col_height['desktop'].'px'; ?>;
<?php } ?> <?php $settings->col_bg_opc = ( $settings->col_bg_opc !== '' ) ? $settings->col_bg_opc : '100'; ?>
<?php if( $settings->col_bg_color !== '' &&  $settings->col_bg_opc !== '') { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->col_bg_color)) ?>, <?php echo $settings->col_bg_opc/100; ?>);
<?php } ?> <?php if( $settings->col_inner_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->col_inner_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->col_inner_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->col_inner_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->col_inner_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->col_inner_padding['left'].'px'; ?>;
<?php } ?> <?php if( $settings->col_inner_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->col_inner_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->col_border_style !== 'none' && $settings->col_border_width !== '' ) { ?> border-width: <?php echo $settings->col_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->col_border_style !== 'none' && $settings->col_border_color !== '') { ?> border-color: #<?php echo $settings->col_border_color; ?>;
<?php } ?> <?php if( $settings->col_border_style !== 'none' && $settings->col_border_style !== '') { ?> border-style: <?php echo $settings->col_border_style; ?>;
<?php } ?> <?php if( $settings->col_border_style !== 'none' &&  $settings->col_border_radius !== '') { ?> border-radius: <?php echo $settings->col_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-logo-grid-main .njba-logo-inner:hover,
.fl-node-<?php echo $id; ?> .njba-logo-carousel-main .njba-logo-inner:hover {
<?php $settings->col_hover_bg_opc = ( $settings->col_hover_bg_opc !== '' ) ? $settings->col_hover_bg_opc : '100'; ?>
<?php if( $settings->col_hover_bg_color !== '' &&  $settings->col_hover_bg_opc !== '') { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->col_hover_bg_color)) ?>, <?php echo $settings->col_hover_bg_opc/100; ?>);
<?php } ?> <?php if( $settings->col_hover_border_style !== 'none' && $settings->col_hover_border_width !== '' ) { ?> border-width: <?php echo $settings->col_hover_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->col_hover_border_style !== 'none' && $settings->col_hover_border_color !== '') { ?> border-color: <?php echo '#'.$settings->col_hover_border_color; ?>;
<?php } ?> <?php if( $settings->col_hover_border_style !== 'none' && $settings->col_hover_border_style !== '') { ?> border-style: <?php echo $settings->col_hover_border_style; ?>;
<?php } ?> <?php if( $settings->col_hover_border_style !== 'none' &&  $settings->col_hover_border_radius !== '') { ?> border-radius: <?php echo $settings->col_hover_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag; ?>.njba-logo-title {
<?php if( $settings->title_alignment ) { ?> text-align: <?php echo $settings->title_alignment; ?>;
<?php } ?> <?php if( $settings->title_color  !== '') { ?> color: <?php echo '#'.$settings->title_color; ?>;
<?php } ?> <?php if( $settings->title_font_size['desktop'] !== '') { ?> font-size: <?php echo $settings->title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->title_line_height['desktop'] !== '') { ?> line-height: <?php echo $settings->title_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->title_font ); ?>
<?php } ?> <?php if( $settings->title_margin !== '' ) { ?> margin-top: <?php echo $settings->title_margin.'px'; ?>;
<?php } ?>
}

/*****Logo Color Inverse color*******/
<?php if($settings->logo_grid_grayscale === 'grayscale'){  ?>
.fl-node-<?php echo $id; ?> .njba-logo-inner.njba-grayscale img {
    -moz-filter-webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    filter: grayscale(100%);
    filter: gray;
    opacity: 0.5;
}

<?php }else if($settings->logo_grid_grayscale === 'original'){ ?>
.fl-node-<?php echo $id; ?> .njba-logo-inner.njba-original img {
    -webkit-filter: none;
    -moz-filter: none;
    -ms-filter: none;
    filter: none;
    opacity: 1;
}

<?php } ?>
<?php if($settings->logo_grid_grayscale_hover === 'grayscale'){ ?>
.fl-node-<?php echo $id; ?> .njba-logo-inner.njba-grayscale-hover img:hover {
    -moz-filter-webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    filter: grayscale(100%);
    filter: gray;
    opacity: 0.5;
}

<?php }else if($settings->logo_grid_grayscale_hover === 'original'){ ?>
.fl-node-<?php echo $id; ?> .njba-logo-inner.njba-original-hover img:hover {
    -webkit-filter: none;
    -moz-filter: none;
    -ms-filter: none;
    filter: none;
    opacity: 1;
}

<?php } ?>
@media (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-logo-grid-main img.njba-logo-image-responsive,
    .fl-node-<?php echo $id; ?> .njba-logo-carousel-main img.njba-logo-image-responsive {
    <?php if( $settings->img_max_width['medium'] !== '' ) { ?> max-width: <?php echo $settings->img_max_width['medium'].'px'; ?>;
    <?php } ?> <?php if( $settings->title_font_size['medium'] !== '') { ?> font-size: <?php echo $settings->title_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( $settings->title_line_height['medium'] !== '') { ?> line-height: <?php echo $settings->title_line_height['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media (max-width: 767px) {
    .fl-node-<?php echo $id; ?> .njba-logo-grid-main img.njba-logo-image-responsive,
    .fl-node-<?php echo $id; ?> .njba-logo-carousel-main img.njba-logo-image-responsive {
    <?php if( $settings->img_max_width['small'] !== '' ) { ?> max-width: <?php echo $settings->img_max_width['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->title_font_size['small'] !== '') { ?> font-size: <?php echo $settings->title_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( $settings->title_line_height['small'] !== '') { ?> line-height: <?php echo $settings->title_line_height['small'].'px'; ?>;
    <?php } ?>
    }
}
