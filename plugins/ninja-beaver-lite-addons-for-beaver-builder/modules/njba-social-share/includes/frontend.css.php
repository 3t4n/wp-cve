<?php 
	$settings->icon_size = ( $settings->icon_size !== '' ) ? $settings->icon_size : '30';
	$settings->icon_spacing = ( $settings->icon_spacing !== '' ) ? $settings->icon_spacing : '20';
?>
<?php if ( $settings->share_icon_pos === 'horizontal' ) { ?>
.fl-node-<?php echo $id; ?> .njba-social-share-horizontal .njba-social-share-inner {
    margin-bottom: <?php echo $settings->icon_spacing.'px'; ?>;
<?php
if( $settings->overall_alignment === 'left' ) {
?> margin-right: <?php echo $settings->icon_spacing.'px'; ?>;
<?php
} else if( $settings->overall_alignment === 'right' ) {
?> margin-left: <?php echo $settings->icon_spacing.'px'; ?>;
<?php
} else {
?> margin-left: <?php echo intval($settings->icon_spacing)/2 .'px'; ?>;
    margin-right: <?php echo intval($settings->icon_spacing)/2 .'px'; ?>;
<?php
}
?>
}

<?php } ?>
<?php if ( $settings->share_icon_pos === 'vertical' ) { ?>

.fl-node-<?php echo $id; ?> .njba-social-share-vertical .njba-social-share-inner {
    margin-bottom: <?php echo $settings->icon_spacing.'px'; ?>;
}

<?php } ?>
<?php
	$icon_count = 1;
	foreach ( $settings->social_icons as $i => $icon ) :
		$icon_css = array(
			'image_type'                  => 'icon',
			'overall_alignment'           => $settings->overall_alignment,
			'icon_size'                   => $settings->icon_size,
			'icon_line_height'            => $settings->icon_line_height,
			'img_icon_bg_color'           => $icon->icon_bg_color,
			'icon_transition'             => $icon->icon_transition,
			'img_icon_show_border'        => $icon->img_icon_show_border,
			'img_icon_border_width'       => $icon->icon_border_width,
			'icon_img_border_radius_njba' => $icon->img_icon_border_radius,
			'img_icon_border_style'       => $icon->img_icon_border_style,
			'img_icon_border_color'       => $icon->icon_border_color,
			'img_icon_border_hover_color' => $icon->icon_border_hover_color,
			'img_icon_bg_hover_color'     => $icon->icon_bg_hover_color,
			'icon_color'                  => $icon->icon_color,
			'icon_hover_color'            => $icon->icon_hover_color,
		);
		FLBuilder::render_module_css('njba-icon-img', $id.' .njba-social-share-list_'.$icon_count, $icon_css);
		$icon_count = $icon_count + 1;
	endforeach;
?>
.fl-node-<?php echo $id; ?> .njba-social-share {
    text-align: <?php echo $settings->overall_alignment; ?>;
}
