<style>
<?php
global $a3_rslider_template_1;
global $a3_rslider_template1_global_settings;

$list_templates = array( 'template-1' => get_option( 'a3_rslider_template_1' , __( 'Default Skin', 'a3-responsive-slider' ) ) );
$templateid = 'template1';
foreach ( $list_templates as $template_key => $template_name ) {
		
	global ${'a3_rslider_'.$templateid.'_dimensions_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	global ${'a3_rslider_'.$templateid.'_slider_styles_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	global ${'a3_rslider_'.$templateid.'_control_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	global ${'a3_rslider_'.$templateid.'_pager_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	global ${'a3_rslider_'.$templateid.'_title_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	global ${'a3_rslider_'.$templateid.'_caption_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	global ${'a3_rslider_'.$templateid.'_readmore_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	
		global ${'a3_rslider_'.$templateid.'_shortcode_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
	
?>

/* ------ START : <?php echo $template_name; ?> -- Template Style ------ */

/* Slider Dimensions */
<?php extract( ${'a3_rslider_'.$templateid.'_dimensions_settings'} ); ?>
<?php
	
		if ( $is_slider_responsive == 0 ) {
			$slider_container_wide = $slider_width.'px';
			$slider_container_tall = $slider_height.'px';
		} else {
			$slider_container_wide = $slider_wide_responsive.'%';
			$slider_container_tall = 'auto';
			if ( $is_slider_tall_dynamic == 0 ) {
				$slider_container_tall = $slider_height_fixed.'px';
			}
		}
?>
.a3-rslider-<?php echo $template_key; ?>.a3-rslider-container {
	width: <?php echo $slider_container_wide; ?>;
	max-width: 100%;
}
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-slideshow {
	height: <?php echo $slider_container_tall; ?>;
}

/* Container */
<?php extract( ${'a3_rslider_'.$templateid.'_slider_styles_settings'} ); ?>
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-slideshow {
	/*Background*/
	background-color: <?php echo $slider_background_colour; ?> !important;
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $slider_border ); ?>
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $slider_shadow ); ?>
}

/* Slider Controls */
<?php extract( ${'a3_rslider_'.$templateid.'_control_settings'} ); ?>
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-controls {
<?php if ( $enable_slider_control != 0 ) { ?>
	display: inline !important;
<?php } ?>
<?php if ( $slider_control_transition == 'alway' ) { ?>
	opacity:1;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=1);
	-moz-opacity: 1;
	-khtml-opacity: 1;
<?php } ?>
	margin-top:-<?php echo ( (int) $slider_control_icons_size / 2 ) ?>px;
}
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-controls .cycle-prev svg,
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-controls .cycle-next svg {
	width: <?php echo $slider_control_icons_size; ?>px;
	height: <?php echo $slider_control_icons_size; ?>px;
	fill: <?php echo $slider_control_icons_color; ?>;
	.opacity( <?php echo ( (int) $slider_control_icons_opacity / 100 ); ?> );
}
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-controls .cycle-prev{
	left: <?php echo $control_previous_icon_margin_left ?>px;
}
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-controls .cycle-next {
	right: <?php echo $control_next_icon_margin_right ?>px;
}
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-pauseplay {
<?php if ( $pauseplay_icon_transition == 'alway' ) { ?>
	opacity:1;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=1);
	-moz-opacity: 1;
	-khtml-opacity: 1;
<?php } ?>
<?php if ( 'top' == $pauseplay_icon_vertical_position ) { ?>
top: 0 !important;
margin-top: 0px;
<?php } elseif ( 'bottom' == $pauseplay_icon_vertical_position ) { ?>
top: auto !important;
bottom: 0 !important;
margin-top: 0px;
<?php } else { ?>
	margin-top:-<?php echo ( ( (int) $pauseplay_icon_size / 2 ) + 5 ) ?>px;
<?php } ?>

<?php if ( 'left' == $pauseplay_icon_horizontal_position ) { ?>
left: 0 !important;
margin-left: 0px;
<?php } elseif ( 'right' == $pauseplay_icon_horizontal_position ) { ?>
left: auto !important;
right: 0 !important;
margin-left: 0px;
<?php } else { ?>
	margin-left:-<?php echo ( ( (int) $pauseplay_icon_size / 2 ) + 5 ) ?>px;
<?php } ?>
}
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-pauseplay .cycle-pause svg,
.a3-rslider-<?php echo $template_key; ?> .a3-cycle-pauseplay .cycle-play svg {
	width: <?php echo $pauseplay_icon_size; ?>px;
	height: <?php echo $pauseplay_icon_size; ?>px;
	fill: <?php echo $pauseplay_icon_color; ?>;
	.opacity( <?php echo ( (int) $pauseplay_icon_opacity / 100 ); ?> );
}

/* Slider Pager */
<?php extract( ${'a3_rslider_'.$templateid.'_pager_settings'} ); ?>
.a3-rslider-<?php echo $template_key; ?> .cycle-pager-container {
<?php if ( $enable_slider_pager != 0 ) { ?>
	display: inline !important;
<?php } ?>
<?php if ( $slider_pager_transition == 'alway' ) { ?>
	opacity:1;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=1);
	-moz-opacity: 1;
	-khtml-opacity: 1;
<?php } ?>
<?php if ( $slider_pager_position == 'top-left' ) { ?>
	top: 10px;
	right: auto;
	bottom: auto;
	left: 10px;
<?php } elseif ( $slider_pager_position == 'top-center' ) { ?>
	top: 10px;
	right: auto;
	bottom: auto;
	left: auto;
	width:100%;
<?php } elseif ( $slider_pager_position == 'top-right' ) { ?>
	top: 10px;
	right: 10px;
	bottom: auto;
	left: auto;
<?php } elseif ( $slider_pager_position == 'bottom-left' ) { ?>
	top: auto;
	right: auto;
	bottom: 10px;
	left: 10px;
<?php } elseif ( $slider_pager_position == 'bottom-center' ) { ?>
	top: auto;
	right: auto;
	bottom: 10px;
	left: auto;
	width:100%;
<?php } else { ?>
	top: auto;
	right: 10px;
	bottom: 10px;
	left: auto;
<?php } ?>
}
.a3-rslider-<?php echo $template_key; ?> .cycle-pager-overlay {
	/*Background*/
	background-color: <?php echo $pager_background_colour; ?> !important;
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $pager_shadow ); ?>
	/*Border Corner*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_corner_css( $pager_border ); ?>
	
	/* Transparency */
	opacity:<?php echo $pager_background_transparency / 100; ?>;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $pager_background_transparency; ?>)";
	filter: alpha(opacity=<?php echo $pager_background_transparency / 100; ?>);
	-moz-opacity: <?php echo $pager_background_transparency / 100; ?>;
	-khtml-opacity: <?php echo $pager_background_transparency / 100; ?>;
}
.a3-rslider-<?php echo $template_key; ?> .cycle-pager {
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $pager_border ); ?> 
}
.a3-rslider-<?php echo $template_key; ?> .cycle-pager span {
<?php if ( $slider_pager_direction == 'vertical' ) { ?>
	float: none !important;
<?php } ?>
	/*Background*/
	background-color: <?php echo $pager_item_background_colour; ?> !important;
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $pager_item_border ); ?>
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $pager_item_shadow ); ?>
}
.a3-rslider-<?php echo $template_key; ?> .cycle-pager span.cycle-pager-active {
	/*Background*/
	background-color: <?php echo $pager_activate_item_background_colour; ?> !important;
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $pager_activate_item_border ); ?>
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $pager_activate_item_shadow ); ?>
}

/* Title & Caption */
<?php extract( ${'a3_rslider_'.$templateid.'_title_settings'} ); ?>
<?php extract( ${'a3_rslider_'.$templateid.'_caption_settings'} ); ?>
.a3-rslider-<?php echo $template_key; ?> .cycle-caption-title {
<?php if ( $title_position == $caption_position ) { ?>
	position:absolute;
	width:100%;
<?php if ( $title_position == 'bottom-left' ) { ?>
	top: auto;
	right: auto;
	bottom: 10px;
	left: 10px;
<?php } elseif ( $title_position == 'bottom-right' ) { ?>
	top: auto;
	right: 10px;
	bottom: 10px;
	left: auto;
<?php } elseif ( $title_position == 'top-left' ) { ?>
	top: 10px;
	right: auto;
	bottom: auto;
	left: 10px;
<?php } else { ?>
	top: 10px;
	right: 10px;
	bottom: auto;
	left: auto;
<?php } ?>
<?php } ?>
}

/* Title Style */
.a3-rslider-<?php echo $template_key; ?> .cycle-caption-container {
<?php if ( $enable_slider_title == 0 ) { ?>
	display: none !important;
<?php } ?>
<?php if ( $title_position == $caption_position ) { ?>
	position:relative !important;
	display:block;
	top: auto !important;
	right: auto !important;
	bottom: auto !important;
	left: auto !important;
	clear:both;
<?php if ( $title_position == 'bottom-left' || $title_position == 'top-left' ) { ?>
	float:left;
<?php } else { ?>
	float:right;
<?php } ?>
<?php } else { ?>
<?php if ( $title_position == 'bottom-left' ) { ?>
	top: auto !important;
	right: auto !important;
	bottom: 10px !important;
	left: 10px !important;
<?php } elseif ( $title_position == 'bottom-right' ) { ?>
	top: auto !important;
	right: 10px !important;
	bottom: 10px !important;
	left: auto !important;
<?php } elseif ( $title_position == 'top-left' ) { ?>
	top: 10px !important;
	right: auto !important;
	bottom: auto !important;
	left: 10px !important;
<?php } else { ?>
	top: 10px !important;
	right: 10px !important;
	bottom: auto !important;
	left: auto !important;
<?php } ?>
<?php } ?>

	max-width: <?php echo $title_wide.'%' ?>;
}
.a3-rslider-<?php echo $template_key; ?> .cycle-caption-bg {
	/*Background*/
	background-color: <?php echo $title_background_colour; ?> !important;
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $title_shadow ); ?>
	/*Border Corner*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_corner_css( $title_border ); ?>
	/* Transparency */
	opacity:<?php echo $title_background_transparency / 100; ?>;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $title_background_transparency; ?>)";
	filter: alpha(opacity=<?php echo $title_background_transparency / 100; ?>);
	-moz-opacity: <?php echo $title_background_transparency / 100; ?>;
	-khtml-opacity: <?php echo $title_background_transparency / 100; ?>;
}
.a3-rslider-<?php echo $template_key; ?> .cycle-caption-text {
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $title_border ); ?>
	/* Font */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'fonts_face']->generate_font_css( $title_font ); ?>
}
.a3-rslider-<?php echo $template_key; ?> .cycle-caption-text a {
	/* Font */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'fonts_face']->generate_font_css( $title_font ); ?>
}
.a3-rslider-<?php echo $template_key; ?> .cycle-caption-text a:hover {
	color: <?php echo $title_font_hover_color; ?> !important;
}

/* Description Style */
.a3-rslider-<?php echo $template_key; ?> .cycle-overlay-container {
<?php if ( $enable_slider_caption == 0 ) { ?>
	display: none !important;
<?php } ?>
<?php if ( $title_position == $caption_position ) { ?>
	position:relative !important;
	display:block;
	top: auto !important;
	right: auto !important;
	bottom: auto !important;
	left: auto !important;
	margin-top:10px;
	clear:both;
<?php if ( $caption_position == 'bottom-left' || $caption_position == 'top-left' ) { ?>
	float:left;
<?php } else { ?>
	float:right;
<?php } ?>
<?php } else { ?>
<?php if ( $caption_position == 'bottom-left' ) { ?>
	top: auto !important;
	right: auto !important;
	bottom: 10px !important;
	left: 10px !important;
<?php } elseif ( $caption_position == 'bottom-right' ) { ?>
	top: auto !important;
	right: 10px !important;
	bottom: 10px !important;
	left: auto !important;
<?php } elseif ( $caption_position == 'top-left' ) { ?>
	top: 10px !important;
	right: auto !important;
	bottom: auto !important;
	left: 10px !important;
<?php } else { ?>
	top: 10px !important;
	right: 10px !important;
	bottom: auto !important;
	left: auto !important;
<?php } ?>
<?php } ?>

	max-width: <?php echo $caption_wide.'%' ?>;
}
.a3-rslider-<?php echo $template_key; ?> .cycle-overlay-bg {
	/*Background*/
	background-color: <?php echo $caption_background_colour; ?> !important;
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $caption_shadow ); ?>
	/*Border Corner*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_corner_css( $caption_border ); ?>
	/* Transparency */
	opacity:<?php echo $caption_background_transparency / 100; ?>;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $caption_background_transparency; ?>)";
	filter: alpha(opacity=<?php echo $caption_background_transparency / 100; ?>);
	-moz-opacity: <?php echo $caption_background_transparency / 100; ?>;
	-khtml-opacity: <?php echo $caption_background_transparency / 100; ?>;
}
.a3-rslider-<?php echo $template_key; ?> .cycle-description {
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $caption_border ); ?>
	/* Font */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'fonts_face']->generate_font_css( $caption_font ); ?>
}


/* Read More */
<?php extract( ${'a3_rslider_'.$templateid.'_readmore_settings'} ); ?>
.a3-rslider-<?php echo $template_key; ?> .a3-rslider-read-more {
	display:inline-block !important;
	margin-bottom: <?php echo $readmore_bt_margin_bottom; ?>px !important;
	margin-top: <?php echo $readmore_bt_margin_top; ?>px !important;
	margin-left: <?php echo $readmore_bt_margin_left; ?>px !important;
	margin-right: <?php echo $readmore_bt_margin_right; ?>px !important;
}
.a3-rslider-<?php echo $template_key; ?> .a3-rslider-read-more-link {
	text-decoration:underline;
	/* Font */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'fonts_face']->generate_font_css( $readmore_link_font ); ?>
}
.a3-rslider-<?php echo $template_key; ?> .a3-rslider-read-more-link:hover {
	color: <?php echo $readmore_link_font_hover_color ; ?> !important;
}
.a3-rslider-<?php echo $template_key; ?> .a3-rslider-read-more-bt {
	text-decoration:none !important;
	position: relative !important;
	
	padding: <?php echo $readmore_bt_padding_tb; ?>px <?php echo $readmore_bt_padding_lr; ?>px !important;
	margin:0;
	
	/*Background*/
	background-color: <?php echo $readmore_bt_bg; ?> !important;
	background: -webkit-gradient(
					linear,
					left top,
					left bottom,
					color-stop(.2, <?php echo $readmore_bt_bg_from; ?>),
					color-stop(1, <?php echo $readmore_bt_bg_to; ?>)
				) !important;;
	background: -moz-linear-gradient(
					center top,
					<?php echo $readmore_bt_bg_from; ?> 20%,
					<?php echo $readmore_bt_bg_to; ?> 100%
				) !important;;
	
		
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $readmore_bt_border ); ?>
	
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $readmore_bt_shadow ); ?>
	
	/* Font */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'fonts_face']->generate_font_css( $readmore_bt_font ); ?>
	
	text-align: center !important;
	text-shadow: 0 -1px 0 hsla(0,0%,0%,.3);
	text-decoration: none !important;
}
.a3-rslider-<?php echo $template_key; ?> .a3-rslider-read-more-bt:hover {
	text-decoration:none !important;
	color: <?php echo $readmore_bt_font['color'] ; ?> !important;
}

/* Shortcode Description */
<?php extract( ${'a3_rslider_'.$templateid.'_shortcode_settings'} ); ?>
.a3-rslider-description-container-<?php echo $template_key; ?> {
	position:relative;
	display:block;
}
.a3-rslider-description-container-<?php echo $template_key; ?> .a3-rslider-shortcode-description {
	position: relative;
	padding: <?php echo $shortcode_description_padding_top; ?>px <?php echo $shortcode_description_padding_right; ?>px <?php echo $shortcode_description_padding_bottom; ?>px <?php echo $shortcode_description_padding_left; ?>px !important;
	/*Border*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_css( $shortcode_description_border ); ?>
	/* Font */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'fonts_face']->generate_font_css( $shortcode_description_font ); ?>
	text-align: <?php echo $shortcode_description_position; ?>
}
.a3-rslider-description-container-<?php echo $template_key; ?> .a3-rslider-description-container-bg {
	position:absolute;
	width:100%;
	height:100%;
	top:0;
	left:0;
	/*Background*/
	background-color: <?php echo $shortcode_description_background_colour; ?> !important;
	/* Transparency */
	opacity:<?php echo $shortcode_description_background_transparency / 100; ?>;
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $shortcode_description_background_transparency; ?>)";
	filter: alpha(opacity=<?php echo $shortcode_description_background_transparency / 100; ?>);
	-moz-opacity: <?php echo $shortcode_description_background_transparency / 100; ?>;
	-khtml-opacity: <?php echo $shortcode_description_background_transparency / 100; ?>;
	/* Shadow */
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_shadow_css( $shortcode_description_shadow ); ?>
	/*Border Corner*/
	<?php echo $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface']->generate_border_corner_css( $shortcode_description_border ); ?>
}

/* ------ END : <?php echo $template_name; ?> -- Template Style -------- */

<?php
}
?>


</style>
