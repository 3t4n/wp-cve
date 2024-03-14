<?php
$njbaRgbaColors = new NJBA_Rgba_Colors();
if( $settings->show_button === 'yes' ) {
    FLBuilder::render_module_css( 'njba-button', $id, $settings->button );
}
if( $settings->title_icon !== '') {
    $image_icon_css = array(
        'image_type'                  => $settings->title_icon->image_type,
        'overall_alignment_img_icon'  => 'center',
        'icon_size'                   => $settings->title_icon->icon_size,
        'icon_line_height'            => $settings->title_icon->icon_line_height,
        'img_size'                    => $settings->title_icon->img_size,
        'img_icon_show_border'        => $settings->title_icon->img_icon_show_border,
        'img_icon_border_width'       => $settings->title_icon->img_icon_border_width,
        'icon_img_border_radius_njba' => $settings->title_icon->icon_img_border_radius_njba,
        'img_icon_border_style'       => $settings->title_icon->img_icon_border_style,
        'img_icon_border_color'       => $settings->title_icon->img_icon_border_color,
        'icon_color'                  => $settings->title_icon->icon_color,
        'img_icon_padding'            => $settings->title_icon->img_icon_padding,
        'img_icon_margin'             => $settings->title_icon->img_icon_margin,
        'img_icon_bg_color'           => $settings->title_icon->img_icon_bg_color,
        'img_icon_bg_color_opc'       => $settings->title_icon->img_icon_bg_color_opc,
    );
    FLBuilder::render_module_css( 'njba-icon-img', $id, $image_icon_css );
}
        $front_heading_css = array(
            'heading_title_color'           => $settings->front_title_typography_color,
            'heading_sub_title_color'       => $settings->front_desc_typography_color,
            'heading_title_font'            => $settings->front_title_typography_font_family,
            'heading_title_font_size'       => $settings->front_title_typography_font_size,
            'heading_title_line_height'     => $settings->front_title_typography_line_height,
            'heading_sub_title_font'        => $settings->front_desc_typography_font_family,
            'heading_sub_title_font_size'   => $settings->front_desc_typography_font_size,
            'heading_sub_title_line_height' => $settings->front_desc_typography_line_height,
            'heading_margin'                => $settings->front_title_typography_margin,
            'heading_subtitle_margin'       => $settings->front_desc_typography_margin
        );
        FLBuilder::render_module_css('njba-heading' , $id.' .njba-front', $front_heading_css);
        $back_heading_css = array(
            'heading_title_color'           => $settings->back_title_typography_color,
            'heading_sub_title_color'       => $settings->back_desc_typography_color,
            'heading_title_font'            => $settings->back_title_typography_font_family,
            'heading_title_font_size'       => $settings->back_title_typography_font_size,
            'heading_title_line_height'     => $settings->back_title_typography_line_height,
            'heading_sub_title_font'        => $settings->back_desc_typography_font_family,
            'heading_sub_title_font_size'   => $settings->back_desc_typography_font_size,
            'heading_sub_title_line_height' => $settings->back_desc_typography_line_height,
            'heading_margin'                => $settings->back_title_typography_margin,
            'heading_subtitle_margin'       => $settings->back_desc_typography_margin
        );
        FLBuilder::render_module_css('njba-heading' , $id.' .njba-back', $back_heading_css);
?>
.fl-node-<?php echo $id; ?> .njba-flip-box .njba-face {
<?php if($settings->box_border_radius) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-face.njba-front,
.fl-node-<?php echo $id; ?> .njba-face.njba-back {
<?php if($settings->box_minimum_height) { ?> min-height: <?php echo $settings->box_minimum_height.'px !important'; ?>;
<?php } ?>
}

<?php if( $settings->front_background_type === 'image' ) { ?>
.fl-node-<?php echo $id; ?> .njba-front:after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: -1;
<?php if( $settings->front_background_color ) {
	$settings->front_background_color_opc = $settings->front_background_color_opc ?: 1 ?> background: <?php echo $njbaRgbaColors->njba_hex2rgba($settings->front_background_color,$settings->front_background_color_opc);?>;
<?php }?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-front {
    overflow: hidden;
<?php
if( $settings->front_background_type === 'color' ) {  ?>

<?php if( $settings->front_background_color ) { ?> background-color: <?php echo $njbaRgbaColors->njba_hex2rgba($settings->front_background_color,$settings->front_background_color_opc); ?>;
<?php  } ?><?php } else { ?><?php if( $settings->front_bg_image ) { ?> background: url('<?php echo $settings->front_bg_image_src; ?>');
<?php } ?><?php if( $settings->front_bg_image_repeat ) { ?> background-repeat: <?php echo $settings->front_bg_image_repeat; ?>;
<?php } ?><?php if( $settings->front_bg_image_display ) { ?> background-size: <?php echo $settings->front_bg_image_display; ?>;
<?php } ?><?php if( $settings->front_bg_image_pos ) { ?> background-position: <?php echo $settings->front_bg_image_pos; ?>;
<?php } ?><?php } ?><?php if( $settings->front_border_color ) { ?> border-color: <?php echo '#'.$settings->front_border_color; ?>;
<?php } ?><?php if( $settings->front_border_size ) {  ?> border-width: <?php echo $settings->front_border_size.'px'; ?>;
<?php } ?><?php if( $settings->front_box_border_style ) { ?> border-style: <?php echo $settings->front_box_border_style; ?>;
<?php } ?>
}

<?php if( $settings->back_background_type === 'image' ) { ?>
.fl-node-<?php echo $id; ?> .njba-back:after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: -1;
<?php if( $settings->back_background_color ) {
	$settings->back_background_color_opc = $settings->back_background_color_opc ?: 1 ?> background: <?php echo $njbaRgbaColors->njba_hex2rgba($settings->back_background_color,$settings->back_background_color_opc);?>;
<?php } ?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-back {
    overflow: hidden;
<?php if( $settings->back_background_type === 'color' ) { ?><?php if( $settings->back_background_color ) { ?> background-color: <?php echo $njbaRgbaColors->njba_hex2rgba($settings->back_background_color,$settings->back_background_color_opc); ?>;
<?php } ?><?php } else { ?><?php if( $settings->back_bg_image ) { ?> background: url('<?php echo $settings->back_bg_image_src; ?>');
<?php } ?><?php if( $settings->back_bg_image_repeat ) { ?> background-repeat: <?php echo $settings->back_bg_image_repeat; ?>;
<?php } ?><?php if( $settings->back_bg_image_display ) { ?> background-size: <?php echo $settings->back_bg_image_display; ?>;
<?php } ?><?php if( $settings->back_bg_image_pos ) { ?> background-position: <?php echo $settings->back_bg_image_pos; ?>;
<?php } ?><?php } ?><?php if( $settings->back_border_color ) { ?> border-color: <?php echo '#'.$settings->back_border_color; ?>;
<?php } ?><?php if( $settings->back_border_size ) { ?> border-width: <?php echo $settings->back_border_size.'px'; ?>;
<?php } ?><?php if( $settings->back_box_border_style ) { ?> border-style: <?php echo $settings->back_box_border_style; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-flip-box-section {
    position: relative;
    z-index: 1024;
<?php if($settings->inner_padding['top']) { ?> padding-top: <?php echo $settings->inner_padding['top'].'px';?>;
<?php } ?> <?php if($settings->inner_padding['right']) { ?> padding-right: <?php echo $settings->inner_padding['right'].'px';?>;
<?php } ?> <?php if($settings->inner_padding['bottom']) { ?> padding-bottom: <?php echo $settings->inner_padding['bottom'].'px';?>;
<?php } ?> <?php if($settings->inner_padding['left']) { ?> padding-left: <?php echo $settings->inner_padding['left'].'px';?>;
<?php } ?>
}
