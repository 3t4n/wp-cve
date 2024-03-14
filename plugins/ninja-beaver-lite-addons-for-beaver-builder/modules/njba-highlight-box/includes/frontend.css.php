<?php 
        $text_css = array(
            'heading_title_alignment'       => $settings->heading_title_alignment,
            'heading_title_font'            => $settings->heading_title_font,
            'heading_title_font_size'       => $settings->heading_title_font_size,
            'heading_title_line_height'     => $settings->heading_title_line_height,
            'heading_title_color'           => $settings->heading_title_color,
            'heading_margin'                => $settings->heading_margin,
            'heading_sub_title_alignment'   => $settings->heading_sub_title_alignment,
            'heading_sub_title_font'        => $settings->heading_sub_title_font,
            'heading_sub_title_font_size'   => $settings->heading_sub_title_font_size,
            'heading_sub_title_line_height' => $settings->heading_sub_title_line_height,
            'heading_sub_title_color'       => $settings->heading_sub_title_color,
            'heading_subtitle_margin'       => $settings->heading_subtitle_margin,
        );
        FLBuilder::render_module_css('njba-heading' , $id.' .njba-image-box', $text_css);
        if( $settings->hover_effect != 6 ) {
        $cap_css = array(
            'heading_title_alignment'   => $settings->caption_title_alignment,
            'heading_title_font'        => $settings->caption_title_font,
            'heading_title_font_size'   => $settings->caption_title_font_size,
            'heading_title_line_height' => $settings->caption_title_line_height,
            'heading_title_color'       => $settings->caption_title_color,
            'heading_margin'            => $settings->caption_margin,
        ); 
        FLBuilder::render_module_css('njba-heading' , $id.' .njba-hover-box', $cap_css);
        $image_icon_css = array(
            'image_type'                  => $settings->image_type,
            'overall_alignment_img_icon'  => $settings->overall_alignment_img_icon,
            'icon_size'                   => $settings->icon_size,
            'icon_line_height'            => $settings->icon_line_height,
            'img_size'                    => $settings->img_size,
            'img_icon_show_border'        => $settings->img_icon_show_border,
            'img_icon_border_width'       => $settings->img_icon_border_width,
            'icon_img_border_radius_njba' => $settings->icon_img_border_radius_njba,
            'img_icon_border_style'       => $settings->img_icon_border_style,
            'img_icon_border_color'       => $settings->img_icon_border_color,
            'img_icon_border_hover_color' => $settings->img_icon_border_hover_color,
            'icon_color'                  => $settings->icon_color,
            'icon_hover_color'            => $settings->icon_hover_color,
            'icon_transition'             => $settings->icon_transition,
            'img_icon_padding'            => $settings->img_icon_padding,
            'img_icon_bg_color'           => $settings->img_icon_bg_color,
            'img_icon_bg_color_opc'       => $settings->img_icon_bg_color_opc,
            'img_icon_bg_hover_color'     => $settings->img_icon_bg_hover_color,
            'img_icon_bg_hover_color_opc' => $settings->img_icon_bg_hover_color_opc
        );
        FLBuilder::render_module_css('njba-icon-img' , $id, $image_icon_css); }
?>
.fl-node-<?php echo $id; ?> .njba-highlight-box-main {
<?php if( $settings->box_bg_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_bg_color)) ?>, <?php echo $settings->box_bg_opacity/100; ?>);
<?php } ?><?php if ( !empty($settings->padding['top']) ) { ?> padding-top: <?php echo $settings->padding['top'].'px'; ?>;
<?php } ?><?php if ( !empty($settings->padding['right']) ) { ?> padding-right: <?php echo $settings->padding['right'].'px'; ?>;
<?php }  ?><?php if ( !empty($settings->padding['bottom']) ) { ?> padding-bottom: <?php echo $settings->padding['bottom'].'px'; ?>;
<?php } ?><?php if ( !empty($settings->padding['left']) ) { ?> padding-left: <?php echo $settings->padding['left'].'px'; ?>;
<?php }  ?> <?php if( $settings->box_icon_transition_duration ) { ?> transition: <?php echo 'all ease '.$settings->box_icon_transition_duration;?>s;
<?php } ?>
}

<?php   if( $settings->hover_effect != 6 ) { ?>
.fl-node-<?php echo $id; ?> .njba-highlight-box-main:hover .njba-hover-box {
<?php if( $settings->box_icon_transition_duration ) { ?> transition: <?php echo 'all ease '.$settings->box_icon_transition_duration;?>s;
<?php } ?> <?php if( $settings->box_bg_hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_bg_hover_color)) ?>, <?php echo $settings->box_bg_hover_opacity/100; ?>);
<?php } ?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-highlight-box-main:hover {
<?php if( $settings->box_hover_bg_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_hover_bg_color)) ?>, <?php echo $settings->box_hover_bg_opacity/100; ?>);
<?php } ?>
}
