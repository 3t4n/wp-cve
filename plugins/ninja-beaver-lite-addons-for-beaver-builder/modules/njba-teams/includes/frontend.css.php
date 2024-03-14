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
.fl-node-<?php echo $id; ?> .njba-teams-main.njba-teams-loaded a.bx-pager-link {
<?php if( $settings->dot_color ) { ?> background: <?php echo '#'.$settings->dot_color; ?><?php } ?>;
    opacity: 0.5;
}

.fl-node-<?php echo $id; ?> .njba-teams-main.njba-teams-loaded a.bx-pager-link.active {
<?php if( $settings->active_dot_color ) { ?> background: <?php echo '#'.$settings->active_dot_color; ?>;
<?php } ?> opacity: 1;
}

.fl-node-<?php echo $id; ?> .njba-teams-main .bx-wrapper .bx-controls-direction a.bx-next,
.fl-node-<?php echo $id; ?> .njba-teams-main .bx-wrapper .bx-controls-direction a.bx-prev {
<?php if( $settings->arrow_background ) { ?> background: <?php echo '#'.$settings->arrow_background; ?>;
<?php } ?> <?php if( $settings->arrow_color ) { ?> color: <?php echo '#'.$settings->arrow_color; ?>;
<?php } ?> <?php if( $settings->arrow_border_radius ) { ?> border-radius: <?php echo $settings->arrow_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-main .bx-wrapper .bx-controls-direction a.bx-next i,
.fl-node-<?php echo $id; ?> .njba-teams-main .bx-wrapper .bx-controls-direction a.bx-prev i {
<?php if( $settings->arrows_size ) { ?> font-size: <?php echo $settings->arrows_size.'px'; ?>;
<?php } ?>
}

/***** For Member Name css *****/
.fl-node-<?php echo $id; ?> .njba-team-content h4 {
    text-transform: capitalize;
<?php if( $settings->name_alignment ) { ?> text-align: <?php echo $settings->name_alignment; ?>;
<?php } ?> <?php if( $settings->name_color ) { ?> color: <?php echo '#'.$settings->name_color; ?>;
<?php } ?> <?php if( $settings->name_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->name_font ); ?><?php } ?> <?php if( !empty($settings->name_font_size['desktop'] )) { ?> font-size: <?php echo $settings->name_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->name_line_height['desktop'] ) ) { ?> line-height: <?php echo $settings->name_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->name_margin['top'] ) ) { ?> margin-top: <?php echo $settings->name_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->name_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->name_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->name_margin['left'] ) ) { ?> margin-left: <?php echo $settings->name_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->name_margin['right'] ) ) { ?> margin-right: <?php echo $settings->name_margin['right'].'px'; ?>;
<?php } ?>
}

/***** For Member Name css *****/
/***** For Member Designation css *****/
.fl-node-<?php echo $id; ?> .njba-team-content h5 {
    text-transform: capitalize;
<?php if( $settings->designation_alignment ) { ?> text-align: <?php echo $settings->designation_alignment; ?>;
<?php } ?> <?php if( $settings->designation_color ) { ?> color: <?php echo '#'.$settings->designation_color; ?>;
<?php } ?> <?php if( $settings->designation_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->designation_font ); ?><?php } ?> <?php if( !empty($settings->designation_font_size['desktop'] ) ) { ?> font-size: <?php echo $settings->designation_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->designation_line_height['desktop'] )) { ?> line-height: <?php echo $settings->designation_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->designation_margin['top'] ) ) { ?> margin-top: <?php echo $settings->designation_margin['top'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->designation_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->designation_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->designation_margin['left'] ) ) { ?> margin-left: <?php echo $settings->designation_margin['left'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->designation_margin['right'] ) ) { ?> margin-right: <?php echo $settings->designation_margin['right'].'px'; ?>;
<?php } ?>
}

/***** For Member Designation css *****/
/***** For Member Bio Text css *****/
.fl-node-<?php echo $id; ?> .njba-teams-main p,
.fl-node-<?php echo $id; ?> .njba-teams-main h6 {
<?php if( $settings->content_alignment ) { ?> text-align: <?php echo $settings->content_alignment; ?>;
<?php } ?> <?php if( $settings->text_color ) { ?> color: <?php echo '#'.$settings->text_color; ?><?php } ?>;
<?php if( $settings->text_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->text_font ); ?><?php } ?> <?php if( !empty($settings->text_font_size['desktop'] ) ){ ?> font-size: <?php echo $settings->text_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( !empty($settings->text_line_height['desktop'] )) { ?> line-height: <?php echo $settings->text_line_height['desktop'].'px'; ?>;
<?php } ?>
}

/***** For Member Bio Text css *****/
/***** For Social Media css *****/
.fl-node-<?php echo $id; ?> .njba-team-social {
<?php if( $settings->social_alignment ) { ?> text-align: <?php echo $settings->social_alignment; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-social i {
<?php if( $settings->social_color ) { ?> color: <?php echo '#'.$settings->social_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-social a:hover i {
<?php if( $settings->hover_social_color ) { ?> color: <?php echo '#'.$settings->hover_social_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-social li a {
<?php if( $settings->border_radius !== '' ) { ?> border-radius: <?php echo $settings->border_radius; ?>%;
<?php } ?>
}

/* For Social Media css */
<?php if( $settings->team_layout == 1 ) { ?>
.fl-node-<?php echo $id; ?> .njba-teams-layout-1 .njba-team-section:hover .njba-overlay {
<?php if( $settings->overly_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->overly_color)) ?>, <?php echo $settings->overly_color_opacity/100; ?>);
<?php } ?>
}


.fl-node-<?php echo $id; ?> .njba-teams-layout-1 .njba-team-social-vertical ul li a {
<?php if( $settings->social_background_color ) { ?> background-color: <?php echo '#'.$settings->social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-2 .njba-team-social ul li  {
<?php if( $settings->social_background_color ) { ?> background-color: <?php echo '#'.$settings->social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-1 .njba-team-social-vertical ul li a:hover {
<?php if( $settings->hover_social_background_color ) { ?> background-color: <?php echo '#'.$settings->hover_social_background_color; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-teams-layout-1 .njba-team-section {
<?php if( $settings->col_bg_color ) { ?> background-color: <?php echo '#'.$settings->col_bg_color; ?>;
<?php } ?> <?php if($settings->col_border_width >= '0') { ?> border-width: <?php echo $settings->col_border_width.'px';?>;
<?php } ?> <?php if($settings->col_border_style) { ?> border-style: <?php echo $settings->col_border_style;?>;
<?php } ?> <?php if($settings->col_border_color) {?> border-color: <?php echo '#'.$settings->col_border_color;?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->col_border_radius['top'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['right']) ){ ?> border-top-right-radius: <?php echo $settings->col_border_radius['right'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['bottom']) ){ ?> border-bottom-left-radius: <?php echo $settings->col_border_radius['bottom'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->col_border_radius['left'].'px';?>;
<?php } ?> box-shadow: <?php if ($settings->col_box_shadow['left_right'] !== ''){ echo $settings->col_box_shadow['left_right'].'px '; } if ($settings->col_box_shadow['top_bottom'] !== ''){  echo $settings->col_box_shadow['top_bottom'].'px '; } if ($settings->col_box_shadow['blur'] !== ''){ echo $settings->col_box_shadow['blur'].'px '; } if ($settings->col_box_shadow['spread'] !== ''){ echo $settings->col_box_shadow['spread'].'px '; } echo '#'.$settings->col_box_shadow_color;?>;
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-1 .njba-team-img img {
<?php if(!empty($settings->col_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->col_border_radius['top'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['right']) ){ ?> border-top-right-radius: <?php echo $settings->col_border_radius['right'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['bottom']) ){ ?> border-bottom-left-radius: <?php echo $settings->col_border_radius['bottom'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->col_border_radius['left'].'px';?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-1 .njba-team-section:hover {
<?php if($settings->col_border_hover_color) {?> border-color: <?php echo '#'.$settings->col_border_hover_color;?>;
<?php } ?> box-shadow: <?php if ($settings->col_box_shadow['left_right'] !== ''){ echo $settings->col_box_shadow['left_right'].'px '; } if ($settings->col_box_shadow['top_bottom'] !== ''){  echo $settings->col_box_shadow['top_bottom'].'px '; } if ($settings->col_box_shadow['blur'] !== ''){ echo $settings->col_box_shadow['blur'].'px '; } if ($settings->col_box_shadow['spread'] !== ''){ echo $settings->col_box_shadow['spread'].'px '; } echo '#'.$settings->col_box_shadow_color;?>;

}

<?php }?>
<?php if($settings->show_col == 12){ ?>
.fl-node-<?php echo $id; ?> .njba-teams-body .njba-team-content {
    margin-bottom: 30px;
    border-top: none;
<?php if($settings->content_border_width >= '0') { ?> border-width: <?php echo $settings->content_border_width.'px';?>;
<?php } ?> <?php if($settings->content_border_style) { ?> border-style: <?php echo $settings->content_border_style;?>;
<?php } ?> <?php if($settings->content_border_color) {?> border-color: <?php echo '#'.$settings->content_border_color;?>;
<?php } ?> <?php if(!empty($settings->content_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->content_border_radius['top'].'px';?>;
<?php } ?> <?php if(!empty($settings->content_border_radius['right']) ){ ?> border-top-right-radius: <?php echo $settings->content_border_radius['right'].'px';?>;
<?php } ?> <?php if(!empty($settings->content_border_radius['bottom']) ){ ?> border-bottom-left-radius: <?php echo $settings->content_border_radius['bottom'].'px';?>;
<?php } ?> <?php if(!empty($settings->content_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->content_border_radius['left'].'px';?>;
<?php } ?>
 <?php if( !empty($settings->content_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_padding['top'].'px'; ?>;<?php } ?>
 <?php if( !empty($settings->content_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_padding['bottom'].'px'; ?>;<?php } ?>
 <?php if( !empty($settings->content_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_padding['left'].'px'; ?>;<?php } ?>
 <?php if( !empty($settings->content_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_padding['right'].'px'; ?>;<?php } ?>
}

<?php } ?>
<?php if( $settings->team_layout == 2 ) { ?>
.fl-node-<?php echo $id; ?> .njba-teams-layout-2 .njba-team-content h4 a {
<?php if( $settings->name_alignment ) { ?> text-align: <?php echo $settings->name_alignment; ?>;
<?php } ?> <?php if( $settings->name_color ) { ?> color: <?php echo '#'.$settings->name_color; ?>;
<?php } ?> <?php if( $settings->name_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->name_font ); ?><?php } ?> <?php if( !empty($settings->name_font_size['desktop'] )) { ?> font-size: <?php echo $settings->name_font_size['desktop'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-content h4 a:hover {
<?php if( $settings->button_text_hover_color ) { ?> color: <?php echo '#'.$settings->button_text_hover_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-2 .njba-team-content {
<?php if( $settings->col_bg_color ) { ?> background-color: <?php echo '#'.$settings->col_bg_color; ?>;
<?php } ?>
}

<?php }?>
<?php if( $settings->team_layout == 3 ) { ?>
.fl-node-<?php echo $id; ?> .njba-team-section:hover .njba-overlay {
<?php if( $settings->overly_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->overly_color)) ?>, <?php echo $settings->overly_color_opacity/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-3 .njba-team-social li a {
<?php if( $settings->social_background_color ) { ?> background-color: <?php echo '#'.$settings->social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-3 .njba-team-social li a:hover {
<?php if( $settings->hover_social_background_color ) { ?> background-color: <?php echo '#'.$settings->hover_social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-3 .njba-team-social li a {
<?php if( $settings->border_radius !== '' ) { ?> border-radius: <?php echo $settings->border_radius; ?>%;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-content h4 a {
<?php if( $settings->name_color ) { ?> color: <?php echo '#'.$settings->name_color; ?>;
<?php } ?>
}

<?php }?>
<?php if( $settings->team_layout == 4 ) { ?>
.fl-node-<?php echo $id; ?> .njba-team-section:hover .njba-overlay {
<?php if( $settings->overly_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->overly_color)) ?>, <?php echo $settings->overly_color_opacity/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-4 .njba-team-section .njba-team-social li a {
<?php if( $settings->social_background_color ) { ?> background-color: <?php echo '#'.$settings->social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-4 .njba-team-social li a:hover {
<?php if( $settings->hover_social_background_color ) { ?> background-color: <?php echo '#'.$settings->hover_social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-content h4 a {
<?php if( $settings->name_color ) { ?> color: <?php echo '#'.$settings->name_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-4 .njba-team-section .njba-team-social li a {
<?php if( $settings->border_radius !== '' ) { ?> border-radius: <?php echo $settings->border_radius; ?>%;
<?php } ?>
}

<?php }?>
<?php if( $settings->team_layout == 5 ) { ?>
.fl-node-<?php echo $id; ?> .njba-team-section:hover .njba-overlay {
<?php if( $settings->overly_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->overly_color)) ?>, <?php echo $settings->overly_color_opacity/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-5 .njba-team-social-aminate ul li a {
<?php if( $settings->social_background_color ) { ?> background-color: <?php echo '#'.$settings->social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-5 .njba-team-social-aminate ul li a:hover {
<?php if( $settings->hover_social_background_color ) { ?> background-color: <?php echo '#'.$settings->hover_social_background_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-content h4 a {
<?php if( $settings->name_color ) { ?> color: <?php echo '#'.$settings->name_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-team-content h4 a:hover {
<?php if( $settings->button_text_hover_color ) { ?> color: <?php echo '#'.$settings->button_text_hover_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-5 .njba-team-section {
<?php if( $settings->col_bg_color ) { ?> background-color: <?php echo '#'.$settings->col_bg_color; ?>;
<?php } ?> <?php if($settings->col_border_width >= '0') { ?> border-width: <?php echo $settings->col_border_width.'px';?>;
<?php } ?> <?php if($settings->col_border_style) { ?> border-style: <?php echo $settings->col_border_style;?>;
<?php } ?> <?php if($settings->col_border_color) {?> border-color: <?php echo '#'.$settings->col_border_color;?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['top'])) { ?> border-top-left-radius: <?php echo $settings->col_border_radius['top'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['right']) ){ ?> border-top-right-radius: <?php echo $settings->col_border_radius['right'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['bottom'])) { ?> border-bottom-left-radius: <?php echo $settings->col_border_radius['bottom'].'px';?>;
<?php } ?> <?php if(!empty($settings->col_border_radius['left'])) { ?> border-bottom-right-radius: <?php echo $settings->col_border_radius['left'].'px';?>;
<?php } ?> box-shadow: <?php if ($settings->col_box_shadow['left_right'] !== ''){ echo $settings->col_box_shadow['left_right'].'px '; } if ($settings->col_box_shadow['top_bottom'] !== ''){  echo $settings->col_box_shadow['top_bottom'].'px '; } if ($settings->col_box_shadow['blur'] !== ''){ echo $settings->col_box_shadow['blur'].'px '; } if ($settings->col_box_shadow['spread'] !== ''){ echo $settings->col_box_shadow['spread'].'px '; } echo '#'.$settings->col_box_shadow_color;?>;
}

.fl-node-<?php echo $id; ?> .njba-teams-layout-2 .njba-team-section:hover {
<?php if($settings->col_border_hover_color) {?> border-color: <?php echo '#'.$settings->col_border_hover_color;?>;
<?php } ?> box-shadow: <?php if ($settings->col_box_shadow['left_right'] !== ''){ echo $settings->col_box_shadow['left_right'].'px '; } if ($settings->col_box_shadow['top_bottom'] !== ''){  echo $settings->col_box_shadow['top_bottom'].'px '; } if ($settings->col_box_shadow['blur'] !== ''){ echo $settings->col_box_shadow['blur'].'px '; } if ($settings->col_box_shadow['spread'] !== ''){ echo $settings->col_box_shadow['spread'].'px '; } echo '#'.$settings->col_box_shadow_color;?>;

}

<?php }?>
<?php if($global_settings->responsive_enabled) { // Global Setting If started ?>
@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-team-content h4 {
    <?php if( !empty($settings->name_font_size['medium'] ) ){ ?> font-size: <?php echo $settings->name_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->name_line_height['medium'] )) { ?> line-height: <?php echo $settings->name_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-team-content h5 {
    <?php if( !empty($settings->designation_font_size['medium'] )) { ?> font-size: <?php echo $settings->designation_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->designation_line_height['medium'] )) { ?> line-height: <?php echo $settings->designation_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-teams-main p,
    .fl-node-<?php echo $id; ?> .njba-teams-main h6 {
    <?php if( !empty($settings->text_font_size['medium'] )) { ?> font-size: <?php echo $settings->text_font_size['medium'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->text_line_height['medium'] )) { ?> line-height: <?php echo $settings->text_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-teams-layout-2 .njba-team-content h4 a {
    <?php if( !empty($settings->name_font_size['medium'] )) { ?> font-size: <?php echo $settings->name_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-team-content h4 {
    <?php if( !empty($settings->name_font_size['small'] )) { ?> font-size: <?php echo $settings->name_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->name_line_height['small'] ) ){ ?> line-height: <?php echo $settings->name_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-team-content h5 {
    <?php if( !empty($settings->designation_font_size['small'] )) { ?> font-size: <?php echo $settings->designation_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->designation_line_height['small'] )) { ?> line-height: <?php echo $settings->designation_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-teams-main p,
    .fl-node-<?php echo $id; ?> .njba-teams-main h6 {
    <?php if( !empty($settings->text_font_size['small'] )) { ?> font-size: <?php echo $settings->text_font_size['small'].'px'; ?>;
    <?php } ?> <?php if( !empty($settings->text_line_height['small'] )) { ?> line-height: <?php echo $settings->text_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-teams-layout-2 .njba-team-content h4 a {
    <?php if( !empty($settings->name_font_size['small'] ) ){ ?> font-size: <?php echo $settings->name_font_size['small'].'px'; ?>;
    <?php } ?>
    }

}

<?php } //die();?>
