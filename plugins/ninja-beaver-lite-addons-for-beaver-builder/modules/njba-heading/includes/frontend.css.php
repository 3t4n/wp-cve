<?php $settings->heading_margin = (array)$settings->heading_margin; ?>
<?php $settings->heading_subtitle_margin = (array)$settings->heading_subtitle_margin; ?>
<?php $settings->heading_title_font_size = (array)$settings->heading_title_font_size; ?>
<?php $settings->heading_title_line_height = (array)$settings->heading_title_line_height; ?>
<?php $settings->heading_sub_title_font_size = (array)$settings->heading_sub_title_font_size; ?>
<?php $settings->heading_sub_title_line_height = (array)$settings->heading_sub_title_line_height; ?>
<?php $settings->heading_title_font = (array)$settings->heading_title_font; ?>
<?php $settings->heading_sub_title_font = (array)$settings->heading_sub_title_font; ?>
<?php $settings->separator_margintb = (array)$settings->separator_margintb; ?>
.fl-node-<?php echo $id; ?> .njba-heading-title {
<?php if( $settings->heading_title_color ) { ?> color: <?php echo '#'.$settings->heading_title_color; ?>;
<?php } ?> <?php if( $settings->heading_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_title_font ); ?><?php } ?> <?php if( $settings->heading_title_font_size['desktop'] !== '' ) { ?> font-size: <?php echo $settings->heading_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->heading_title_line_height['desktop'] !== '') { ?> line-height: <?php echo $settings->heading_title_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->heading_title_alignment ) { ?> text-align: <?php echo $settings->heading_title_alignment; ?>;
<?php } ?> <?php if($settings->heading_margin['top'] !== '' ) { ?> margin-top: <?php echo $settings->heading_margin['top'].'px'; ?>;
<?php } else { echo 'margin-top:0px;'; } ?><?php if($settings->heading_margin['bottom'] !== '' )  {?> margin-bottom: <?php echo $settings->heading_margin['bottom'].'px'; ?>;
<?php } else { echo 'margin-bottom:0px;'; } ?><?php if($settings->heading_margin['left'] !== '' )  {?> margin-left: <?php echo $settings->heading_margin['left'].'px'; ?>;
<?php } else { echo 'margin-left:0px;'; } ?><?php if($settings->heading_margin['right'] !== '' )  {?> margin-right: <?php echo $settings->heading_margin['right'].'px'; ?>;
<?php } else { echo 'margin-right:0px;'; } ?>
}

.fl-node-<?php echo $id; ?> .njba-heading-title::before {
<?php if( $settings->heading_title_color ) { ?> background-color: <?php echo '#'.$settings->heading_title_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-heading-sub-title {
<?php if( $settings->heading_sub_title_color ) { ?> color: <?php echo '#'.$settings->heading_sub_title_color; ?>;
<?php } ?> <?php if( $settings->heading_sub_title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_sub_title_font ); ?><?php } ?> <?php if( $settings->heading_sub_title_font_size['desktop'] !== '' ) { ?> font-size: <?php echo $settings->heading_sub_title_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->heading_sub_title_line_height['desktop'] !== '' ) { ?> line-height: <?php echo $settings->heading_sub_title_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->heading_sub_title_alignment ) { ?> text-align: <?php echo $settings->heading_sub_title_alignment; ?>;
<?php } ?><?php if($settings->heading_subtitle_margin['top'] !=='' )  {?> margin-top: <?php echo $settings->heading_subtitle_margin['top'].'px'; ?>;
<?php }  else { echo 'margin-top:0px;'; } ?><?php if($settings->heading_subtitle_margin['bottom'] !== '' )  {?> margin-bottom: <?php echo $settings->heading_subtitle_margin['bottom'].'px'; ?>;
<?php }  else { echo 'margin-bottom:0px;'; } ?><?php if($settings->heading_subtitle_margin['left'] !== '' )  {?> margin-left: <?php echo $settings->heading_subtitle_margin['left'].'px'; ?>;
<?php }  else { echo 'margin-left:0px;'; } ?><?php if($settings->heading_subtitle_margin['right'] !== '' )  {?> margin-right: <?php echo $settings->heading_subtitle_margin['right'].'px'; ?>;
<?php }  else { echo 'margin-right:0px;'; } ?>
}

.fl-node-<?php echo $id; ?> .njba-heading-sub-title::before {
<?php if( $settings->heading_sub_title_color ) { ?> background-color: <?php echo '#'.$settings->heading_sub_title_color; ?>;
<?php } ?>
}

<?php
    $separator_settings_css = array(
		'icon_position'             => $settings->icon_position,
		'separator_type'            => $settings->separator_type,
		'separator_normal_width'    => $settings->separator_normal_width,
		'separator_border_width'    => $settings->separator_border_width,
		'separator_border_color'    => $settings->separator_border_color,
		'separator_border_style'    => $settings->separator_border_style,
		'separator_icon_font_size'  => $settings->separator_icon_font_size,
		'separator_icon_font_color' => $settings->separator_icon_font_color,
		'separator_text_font_size'  => $settings->separator_text_font_size,
		'separator_text_line_height'    => $settings->separator_text_line_height,
		'separator_text_font_color' => $settings->separator_text_font_color,
		'margin_top'                => $settings->separator_margintb,
		'margin_top_medium'         => $settings->separator_margintb,
		'margin_top_responsive'     => $settings->separator_margintb,
		'margin_bottom'             => $settings->separator_margintb,
		'margin_bottom_medium'      => $settings->separator_margintb,
		'margin_bottom_responsive'  => $settings->separator_margintb
	);
?>
<?php FLBuilder::render_module_css('njba-separator', $id, $separator_settings_css); ?>
.fl-node-<?php echo $id; ?> .njba-icon {
<?php if($settings->separator_margintb['top'])  {?> margin-top: <?php echo $settings->separator_margintb['top'].'px'; ?>;
<?php } ?><?php if($settings->separator_margintb['bottom'])  {?> margin-bottom: <?php echo $settings->separator_margintb['bottom'].'px'; ?>;
<?php } ?>
}

<?php if( $global_settings->responsive_enabled ) { // Global Setting If started
?>

@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-heading-title {
    <?php if( isset( $settings->heading_title_font_size['medium'] ) ) { ?> font-size: <?php echo $settings->heading_title_font_size['medium'].'px'; ?>;
    <?php } ?><?php if( isset( $settings->heading_title_line_height['medium'] ) ) { ?> line-height: <?php echo $settings->heading_title_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-heading-sub-title {
    <?php if( isset( $settings->heading_sub_title_font_size['medium'] ) ) { ?> font-size: <?php echo $settings->heading_sub_title_font_size['medium'].'px'; ?>;
    <?php } ?><?php if( isset( $settings->heading_sub_title_line_height['medium'] ) ) { ?> line-height: <?php echo $settings->heading_sub_title_line_height['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-heading-title {
    <?php if( isset( $settings->heading_title_font_size['small'] ) ) { ?> font-size: <?php echo $settings->heading_title_font_size['small'].'px'; ?>;
    <?php } ?><?php if( isset( $settings->heading_title_line_height['small'] ) ) { ?> line-height: <?php echo $settings->heading_title_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-heading-sub-title {
    <?php if( isset( $settings->heading_sub_title_font_size['small'] ) ) { ?> font-size: <?php echo $settings->heading_sub_title_font_size['small'].'px'; ?>;
    <?php } ?><?php if( isset( $settings->heading_sub_title_line_height['small'] ) ) { ?> line-height: <?php echo $settings->heading_sub_title_line_height['small'].'px'; ?>;
    <?php } ?>
    }
}

<?php
}
?>
