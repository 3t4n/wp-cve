<?php  $njbaRgbaColors = new NJBA_Rgba_Colors(); ?>
.fl-node-<?php echo $id; ?> .njba-tabs-panel .njba-tabs-panel-content,
.fl-node-<?php echo $id; ?> .accordion-section-content {
<?php
echo 'text-align: ' . $settings->content_alignment . ';';
?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-icon i,
.fl-node-<?php echo $id; ?> .njba-accordion-icon i {
    color: <?php echo '#'.$settings->icon_color; ?>;
<?php echo ( $settings->icon_size !== '' ) ? 'font-size: ' . $settings->icon_size . 'px;' : ''; ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs-panels .njba-tabs-panel .njba-tabs-panel-content,
.fl-node-<?php echo $id; ?> .accordion-section-content {
    color: <?php echo '#'.$settings->content_color ; ?>;
    background-color: <?php echo $njbaRgbaColors->njba_hex2rgba( $settings->content_background_color ,$settings->content_background_color_opc / 100) ?>;
}

.fl-node-<?php echo $id; ?> .njba-tab-title,
.fl-node-<?php echo $id; ?> .njba-acc-icon,
.fl-node-<?php echo $id; ?> a.accordion-section-title span.njba-accordion-label {
    color: <?php echo '#'.$settings->title_color; ?>;
}

<?php if( $settings->title_active_color !== '' ) { ?>
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tab-active .njba-tab-label-inner .njba-tab-title,
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tab-active .njba-tab-label-inner:hover .njba-tab-title,
.fl-node-<?php echo $id; ?> a.accordion-section-title.active span.njba-accordion-label {
    color: <?php echo '#'.$settings->title_active_color; ?>;
}

<?php } ?>
<?php if( $settings->icon_active_color !== '' ) { ?>
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tab-active .njba-tab-label-inner .njba-tabs-icon i,
.fl-node-<?php echo $id; ?> a.accordion-section-title.active .njba-accordion-icon i {
    color: <?php echo '#'.$settings->icon_active_color; ?>;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-icon i,
.fl-node-<?php echo $id; ?> .njba-accordion-icon i {
<?php if( $settings->icon_line_height['desktop'] !== '' ) : ?> line-height: <?php echo $settings->icon_line_height['desktop'].'px'; ?>;
<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tab-title, .fl-node-<?php echo $id; ?> span.njba-accordion-label {
<?php if( $settings->tab_label_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->tab_label_font ); ?><?php } ?><?php if( $settings->title_font_size['desktop'] ) { ?> font-size: <?php echo $settings->title_font_size['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->title_font_line_height['desktop'] ) { ?> line-height: <?php echo $settings->title_font_line_height['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->label_text_transform; ?>;
}

.fl-node-<?php echo $id; ?> .njba-tabs-panels .njba-content, .fl-node-<?php echo $id; ?> .accordion-section-content {
<?php if( $settings->tab_content_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->tab_content_font ); ?><?php } ?><?php if( $settings->content_font_size['desktop'] ) { ?> font-size: <?php echo $settings->content_font_size['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->content_line_height['desktop'] ) { ?> line-height: <?php echo $settings->content_line_height['desktop'].'px'; ?>;
<?php } ?>

}

<?php if( $settings->active_tab_background_color !== '' ) { ?>
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label.njba-tab-active .njba-tab-label-inner,
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label.njba-tab-active .njba-tab-label-inner:hover,
.fl-node-<?php echo $id; ?> a.accordion-section-title.active {
    background-color: <?php echo '#'.$settings->active_tab_background_color; ?>;
}

<?php } ?>

<?php if( $settings->title_background_color !== '' ) { ?>
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner,
.fl-node-<?php echo $id; ?> a.accordion-section-title {
    background-color: <?php echo '#'.$settings->title_background_color; ?>;
}

<?php } ?>
<?php if( $settings->tab_background_color !== '' ) { ?>
.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-1 .njba-tabs-nav,
.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-2 .njba-tabs-nav,
.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-4 .njba-tabs-nav,
.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-5 .njba-tabs-nav {
    background-color: <?php echo '#'.$settings->tab_background_color; ?>;
}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-6 .njba-tab-menu-main,
.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-3 .njba-tab-menu-main {
    background-color: <?php echo '#'.$settings->tab_background_color; ?>;
}

<?php } ?>
<?php if( $settings->title_background_hover_color !== '' ) {?>
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner:hover,
.fl-node-<?php echo $id; ?> a.accordion-section-title:hover {
    background-color: <?php echo '#'.$settings->title_background_hover_color; ?>;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner {
<?php if( $settings->tab_box_padding['top'] !== null ) { ?> padding-top: <?php echo $settings->tab_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->tab_box_padding['bottom'] !== null ) { ?> padding-bottom: <?php echo $settings->tab_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->tab_box_padding['left'] !== null ) { ?> padding-left: <?php echo $settings->tab_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( $settings->tab_box_padding['right'] !== null ) { ?> padding-right: <?php echo $settings->tab_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner {

<?php if( !empty($settings->title_active_border['top']) ) { ?> border-top: <?php echo $settings->title_active_border['top'].'px '; echo $settings->title_active_border_style.' transparent'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label.njba-tab-active .njba-tab-label-inner,
.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label:hover .njba-tab-label-inner {
<?php if( !empty($settings->title_active_border['top']) ) { ?> border-top: <?php echo $settings->title_active_border['top'].'px '; echo $settings->title_active_border_style; echo ' #'.$settings->title_active_border_color; ?>;
<?php } ?> <?php if( !empty($settings->title_active_border['bottom']) ) { ?> border-bottom: <?php echo $settings->title_active_border['bottom'].'px '; echo $settings->title_active_border_style; echo ' #'.$settings->title_active_border_color; ?>;
<?php } ?> <?php if( !empty($settings->title_active_border['left']) ) { ?> border-left: <?php echo $settings->title_active_border['left'].'px '; echo $settings->title_active_border_style; echo ' #'.$settings->title_active_border_color; ?>;
<?php } ?> <?php if( !empty($settings->title_active_border['right']) ) { ?> border-right: <?php echo $settings->title_active_border['right'].'px '; echo $settings->title_active_border_style; echo ' #'.$settings->title_active_border_color; ?>;
<?php } ?> <?php if( $settings->title_active_border_radius !== null )  { ?> border-radius: <?php echo $settings->title_active_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-7.njba-tab-section-main .njba-tabs-label.njba-tab-active .njba-tab-label-inner {
    border-bottom: <?php echo $settings->tab_border['bottom'].'px '; echo $settings->tab_border_style.' transparent'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-7 .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner,
.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-8 .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner {
<?php if( $settings->tab_border['top'] !== null ) { ?> border-top: <?php echo $settings->tab_border['top'].'px '; echo $settings->tab_border_style; echo ' #'.$settings->tab_border_color; ?>;
<?php } ?> <?php if( $settings->tab_border['bottom'] !== null ) { ?> border-bottom: <?php echo $settings->tab_border['bottom'].'px '; echo $settings->tab_border_style; echo ' #'.$settings->tab_border_color; ?>;
<?php } ?> <?php if( $settings->tab_border['left'] !== null ) { ?> border-left: <?php echo $settings->tab_border['left'].'px '; echo $settings->tab_border_style; echo ' #'.$settings->tab_border_color; ?>;
<?php } ?> <?php if( $settings->tab_border['right'] !== null ) { ?> border-right: <?php echo $settings->tab_border['right'].'px '; echo $settings->tab_border_style; echo ' #'.$settings->tab_border_color; ?>;
<?php } ?> <?php if( $settings->tab_border_radius !== null ) { ?> border-radius: <?php echo $settings->tab_border_radius.'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-7 .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner {
    margin-bottom: -<?php echo $settings->header_border_width['bottom'].'px'; ?>;

}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-7 .njba-tabs-nav {
<?php if( $settings->header_border_width['top'] !== null ) { ?> border-top: <?php echo $settings->header_border_width['top'].'px '; echo $settings->header_border_style; echo ' #'.$settings->header_border_color; ?>;
<?php } ?> <?php if( $settings->header_border_width['bottom'] !== null ) { ?> border-bottom: <?php echo $settings->header_border_width['bottom'].'px '; echo $settings->header_border_style; echo ' #'.$settings->header_border_color; ?>;
<?php } ?> <?php if( $settings->header_border_width['left'] !== null ) { ?> border-left: <?php echo $settings->header_border_width['left'].'px '; echo $settings->header_border_style; echo ' #'.$settings->header_border_color; ?>;
<?php } ?> <?php if( $settings->header_border_width['right'] !== null ) { ?> border-right: <?php echo $settings->header_border_width['right'].'px '; echo $settings->header_border_style; echo ' #'.$settings->header_border_color; ?>;
<?php } ?> <?php if( $settings->header_border_radius !== null ) { ?> border-radius: <?php echo $settings->header_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label .njba-tab-label-inner {
<?php if( $settings->title_active_border_radius !== null ) { ?> border-radius: <?php echo $settings->title_active_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label {
<?php echo 'text-align: ' . $settings->text_alignment . ';'; ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav<?php echo $id; ?> .njba-tabs-label {
<?php if( !empty($settings->title_margin['top']  ) ) { ?> margin-top: <?php echo $settings->title_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_margin['bottom']  ) ) { ?> margin-bottom: <?php echo $settings->title_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_margin['left']  ) ) { ?> margin-left: <?php echo $settings->title_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->title_margin['right']  ) ) { ?> margin-right: <?php echo $settings->title_margin['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav {
    -webkit-box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->box_shadow_color ,$settings->box_shadow_opacity / 100); ?>;
    -moz-box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->box_shadow_color ,$settings->box_shadow_opacity / 100); ?>;
    -o-box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->box_shadow_color ,$settings->box_shadow_opacity / 100); ?>;
    box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->box_shadow_color ,$settings->box_shadow_opacity / 100); ?>;
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tab-menu-main .njba-tab-label-inner {
    -webkit-box-shadow: <?php echo $settings->title_shadow['horizontal'].'px '; echo $settings->title_shadow['vertical'].'px '; echo $settings->title_shadow['blur'].'px '; echo $settings->title_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->title_shadow_color ,$settings->title_shadow_opacity / 100); ?>;
    -moz-box-shadow: <?php echo $settings->title_shadow['horizontal'].'px '; echo $settings->title_shadow['vertical'].'px '; echo $settings->title_shadow['blur'].'px '; echo $settings->title_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->title_shadow_color ,$settings->title_shadow_opacity / 100); ?>;
    -o-box-shadow: <?php echo $settings->title_shadow['horizontal'].'px '; echo $settings->title_shadow['vertical'].'px '; echo $settings->title_shadow['blur'].'px '; echo $settings->title_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->title_shadow_color ,$settings->title_shadow_opacity / 100); ?>;
    box-shadow: <?php echo $settings->title_shadow['horizontal'].'px '; echo $settings->title_shadow['vertical'].'px '; echo $settings->title_shadow['blur'].'px '; echo $settings->title_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->title_shadow_color ,$settings->title_shadow_opacity / 100); ?>;
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-nav {
<?php if( !empty($settings->tab_padding['top']  )) { ?> padding-top: <?php echo $settings->tab_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->tab_padding['bottom']  )) { ?> padding-bottom: <?php echo $settings->tab_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->tab_padding['left']  )) { ?> padding-left: <?php echo $settings->tab_padding['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->tab_padding['right']  )) { ?> padding-right: <?php echo $settings->tab_padding['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->header_margin['top']  ) ) { ?> margin-top: <?php echo $settings->header_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->header_margin['bottom'] )) { ?> margin-bottom: <?php echo $settings->header_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->header_margin['left']  )) { ?> margin-left: <?php echo $settings->header_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->header_margin['right']  )) { ?> margin-right: <?php echo $settings->header_margin['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-panel-content {
    -webkit-box-shadow: <?php echo $settings->content_shadow['horizontal'].'px '; echo $settings->content_shadow['vertical'].'px '; echo $settings->content_shadow['blur'].'px '; echo $settings->content_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->content_shadow_color ,$settings->content_shadow_opacity / 100); ?>;
    -moz-box-shadow: <?php echo $settings->content_shadow['horizontal'].'px '; echo $settings->content_shadow['vertical'].'px '; echo $settings->content_shadow['blur'].'px '; echo $settings->content_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->content_shadow_color ,$settings->content_shadow_opacity / 100); ?>;
    -o-box-shadow: <?php echo $settings->content_shadow['horizontal'].'px '; echo $settings->content_shadow['vertical'].'px '; echo $settings->content_shadow['blur'].'px '; echo $settings->content_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->content_shadow_color ,$settings->content_shadow_opacity / 100); ?>;
    box-shadow: <?php echo $settings->content_shadow['horizontal'].'px '; echo $settings->content_shadow['vertical'].'px '; echo $settings->content_shadow['blur'].'px '; echo $settings->content_shadow['spread'].'px '; echo $njbaRgbaColors->njba_hex2rgba( $settings->content_shadow_color ,$settings->content_shadow_opacity / 100); ?>;
}

.fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-panel-content {
<?php if( !empty($settings->content_box_padding['top'] )) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->content_box_padding['bottom'])) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->content_box_padding['left'])) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->content_box_padding['right'] )) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->content_margin['top'] ) ) { ?> margin-top: <?php echo $settings->content_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->content_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->content_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->content_margin['left'] )) { ?> margin-left: <?php echo $settings->content_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->content_margin['right'] ) ) { ?> margin-right: <?php echo $settings->content_margin['right'].'px'; ?>;
<?php } ?><?php if( !empty($settings->box_border_width['top'] ) ) { ?> border-top: <?php echo $settings->box_border_width['top'].'px '; echo $settings->box_border_style; echo ' #'.$settings->box_border_color; ?>;
<?php } ?> <?php if( !empty($settings->box_border_width['bottom']) ) { ?> border-bottom: <?php echo $settings->box_border_width['bottom'].'px '; echo $settings->box_border_style; echo ' #'.$settings->box_border_color; ?>;
<?php } ?> <?php if( !empty($settings->box_border_width['left'] ) ) { ?> border-left: <?php echo $settings->box_border_width['left'].'px '; echo $settings->box_border_style; echo ' #'.$settings->box_border_color; ?>;
<?php } ?> <?php if( !empty($settings->box_border_width['right'] ) ) { ?> border-right: <?php echo $settings->box_border_width['right'].'px '; echo $settings->box_border_style; echo ' #'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius !== null ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-7, .fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-8 {
<?php if( $settings->body_border_color ) { ?> border-color: <?php echo '#'.$settings->body_border_color; ?>;
<?php } ?> <?php if( $settings->body_border_radius ) { ?> border-radius: <?php echo $settings->body_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->body_border_style ) { ?> border-style: <?php echo $settings->body_border_style; ?>;
<?php } ?> <?php if( $settings->body_border_width ) { ?> border-width: <?php echo $settings->body_border_width.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-5 .njba-tab-active .njba-tab-label-inner .njba-icon-separator {
<?php if ( $settings->separator_color !== '' ){?> border-color: <?php echo '#'.$settings->separator_color; ?> transparent transparent;
<?php } ?><?php if ( $settings->separator_size !== '' ){?> border-width: <?php echo $settings->separator_size.'px';?>;
<?php } ?> display: block;
}

.fl-node-<?php echo $id; ?> .njba-tab-active .njba-tab-label-inner .njba-icon-separator {
<?php if ( $settings->separator_color !== '' ){?> border-color: transparent transparent transparent<?php echo ' #'.$settings->separator_color; ?>;
<?php } ?><?php if ( $settings->separator_size !== '' ){?> border-width: <?php echo $settings->separator_size.'px';?>;
<?php } ?> display: block;
}

.fl-node-<?php echo $id; ?> .njba-tabs.njba-tabs-style-4 .njba-tab-active .njba-tab-label-inner .njba-icon-separator {
<?php if ( $settings->separator_color !== '' ){?> border-color: transparent <?php echo ' # '.$settings->separator_color; ?> transparent transparent;
<?php } ?><?php if ( $settings->separator_size !== '' ){?> border-width: <?php echo $settings->separator_size.'px';?>;
<?php } ?> display: block;
}

.accordion {
    display: none;
}

@media screen and (max-width: 600px) {
    .fl-node-<?php echo $id; ?> .njba-tab-section-main {
        display: none;
    }

    .accordion {
        display: block;
    }

    .fl-node-<?php echo $id; ?> .njba-tabs-panels .njba-content {

    <?php if( $settings->content_font_size['medium'] ) { ?> font-size: <?php echo $settings->content_font_size['medium'].'px'; ?>;
    <?php } ?><?php if( $settings->content_line_height['medium'] ) { ?> line-height: <?php echo $settings->content_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-tabs .njba-tab-title {
    <?php if( $settings->title_font_size['medium']) { ?> font-size: <?php echo $settings->title_font_size['medium'].'px'; ?>;
    <?php } ?><?php if( $settings->title_font_line_height['medium']) { ?> line-height: <?php echo $settings->title_font_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-tabs .njba-tabs-icon i {
    <?php if( $settings->icon_line_height['medium'] !== '' ) : ?> line-height: <?php echo $settings->icon_line_height['medium'].'px'; ?>;
    <?php endif; ?>
    }
}

