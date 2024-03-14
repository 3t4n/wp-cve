<?php
/**
 * Dynamic style.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/public
 */

$sptpro_tabs_horizontal_alignment = isset( $sptpro_shortcode_options['sptpro_tabs_horizontal_alignment'] ) ? $sptpro_shortcode_options['sptpro_tabs_horizontal_alignment'] : 'tab-horizontal-alignment-left';
$sptpro_set_small_screen          = isset( $sptpro_shortcode_options['sptpro_set_small_screen'] ) ? $sptpro_shortcode_options['sptpro_set_small_screen'] : array(
	'all' => '480',
);
$sptpro_tabs_animation            = isset( $sptpro_shortcode_options['sptpro_tabs_animation'] ) ? $sptpro_shortcode_options['sptpro_tabs_animation'] : false;
$sptpro_animation_time            = isset( $sptpro_shortcode_options['sptpro_animation_time'] ) ? $sptpro_shortcode_options['sptpro_animation_time'] : '';
$sptpro_title_hover_bg_color      = isset( $sptpro_shortcode_options['sptpro_title_bg_color']['title-hover-bg-color'] ) ? $sptpro_shortcode_options['sptpro_title_bg_color']['title-hover-bg-color'] : '';
$sptpro_title_bg_color            = isset( $sptpro_shortcode_options['sptpro_title_bg_color']['title-bg-color'] ) ? $sptpro_shortcode_options['sptpro_title_bg_color']['title-bg-color'] : '';
$sptpro_title_active_bg_color     = isset( $sptpro_shortcode_options['sptpro_title_bg_color']['title-active-bg-color'] ) ? $sptpro_shortcode_options['sptpro_title_bg_color']['title-active-bg-color'] : '';
$sptpro_tabs_border               = isset( $sptpro_shortcode_options['sptpro_tabs_border'] ) ? $sptpro_shortcode_options['sptpro_tabs_border'] : array(
	'all'           => 1,
	'style'         => 'solid',
	'color'         => '#cccccc',
	'border_radius' => '2',
);

$sptpro_margin_between_tabs = isset( $sptpro_shortcode_options['sptpro_margin_between_tabs']['all'] ) ? $sptpro_shortcode_options['sptpro_margin_between_tabs']['all'] : '10';
$sptpro_tabs_border_radius  = isset( $sptpro_tabs_border['border_radius'] ) ? $sptpro_tabs_border['border_radius'] : '2';

$sptpro_title_padding     = isset( $sptpro_shortcode_options['sptpro_title_padding'] ) ? $sptpro_shortcode_options['sptpro_title_padding'] : array(
	'left'   => '15',
	'top'    => '15',
	'bottom' => '15',
	'right'  => '15',
);
$sptpro_desc_border       = isset( $sptpro_shortcode_options['sptpro_desc_border'] ) ? $sptpro_shortcode_options['sptpro_desc_border'] : array(
	'all'   => 1,
	'style' => 'solid',
	'color' => '#cccccc',
);
$sptpro_desc_border_style = isset( $sptpro_desc_border['style'] ) ? $sptpro_desc_border['style'] : 'solid';
$sptpro_desc_padding      = isset( $sptpro_shortcode_options['sptpro_desc_padding'] ) ? $sptpro_shortcode_options['sptpro_desc_padding'] : array(
	'left'   => '20',
	'top'    => '20',
	'bottom' => '20',
	'right'  => '20',
);
$sptpro_desc_bg_color     = isset( $sptpro_shortcode_options['sptpro_desc_bg_color'] ) ? $sptpro_shortcode_options['sptpro_desc_bg_color'] : '#ffffff';

$sptpro_section_title            = isset( $sptpro_shortcode_options['sptpro_section_title'] ) ? $sptpro_shortcode_options['sptpro_section_title'] : false;
$sptpro_tabs_on_small_screen     = isset( $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] ) ? $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] : 'full_widht';
$sptpro_expand_and_collapse_icon = isset( $sptpro_shortcode_options['sptpro_expand_and_collapse_icon'] ) ? $sptpro_shortcode_options['sptpro_expand_and_collapse_icon'] : true;


$sptpro_section_title_typo = isset( $sptpro_shortcode_options['sptpro_section_title_typo'] ) ? $sptpro_shortcode_options['sptpro_section_title_typo'] : array(
	'color'          => '#444444',
	'font-family'    => '',
	'font-style'     => '600',
	'font-size'      => '28',
	'line-height'    => '28',
	'letter-spacing' => '0',
	'text-align'     => 'left',
	'text-transform' => 'none',
	'type'           => 'google',
	'unit'           => 'px',
	'margin-bottom'  => '30',
);

$section_title_margin_bottom = isset( $sptpro_section_title_typo['margin-bottom'] ) ? $sptpro_section_title_typo['margin-bottom'] : '30';

$sptpro_tabs_title_typo = isset( $sptpro_shortcode_options['sptpro_tabs_title_typo'] ) ? $sptpro_shortcode_options['sptpro_tabs_title_typo'] : array(
	'font-family'    => '',
	'font-weight'    => '600',
	'font-style'     => 'normal',
	'font-size'      => '16',
	'line-height'    => '22',
	'letter-spacing' => '0',
	'text-align'     => 'center',
	'text-transform' => 'none',
	'color'          => '#444',
	'hover_color'    => '#444',
	'active_color'   => '#444',
	'type'           => 'google',
);
$sptpro_desc_typo       = isset( $sptpro_shortcode_options['sptpro_desc_typo'] ) ? $sptpro_shortcode_options['sptpro_desc_typo'] : array(
	'color'          => '#444',
	'font-family'    => '',
	'font-style'     => '400',
	'font-size'      => '16',
	'line-height'    => '24',
	'letter-spacing' => '0',
	'text-align'     => 'left',
	'text-transform' => 'none',
	'type'           => 'google',
);

// Animation.
if ( $sptpro_tabs_animation ) {
	$sptpro_dynamic_style .= '#sp-tabpro-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content {
		width: 100%;
	}
	#sp-tabpro-wrapper_' . $post_id . ' .animated {
		-webkit-animation-duration: ' . $sptpro_animation_time . 'ms;
		animation-duration: ' . $sptpro_animation_time . 'ms;
	}';
}


// Tabs horizontal left-right alignment.
switch ( $sptpro_tabs_horizontal_alignment ) {
	case 'tab-horizontal-alignment-right':
		$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__nav {justify-content: flex-end;}';
		break;
	case 'tab-horizontal-alignment-left':
		$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__nav {justify-content: start;}';
		break;
	default:
		$sptpro_dynamic_style .= '';
		break;
}

/* Tabs active & inactive icon in mobile devices (Accordion mode) */
if ( $sptpro_expand_and_collapse_icon && 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
	$sptpro_dynamic_style .= '
	@media only screen and (max-width: ' . $sptpro_set_small_screen['all'] . 'px) {
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default a.sp-tab__link{
			position: relative;
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label .sp-tab__card-header{
			padding-right: 40px; 
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label:after,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default a.sp-tab__link:after,
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label[aria-expanded="false"]:after {
			font-family: "FontAwesome";
			content: "\002B";
			color: #1a1515;
			font-weight: bold;
			float: right;
			position: absolute;
			right: 15px;
			font-size: 25px;
			bottom: auto;
			top: 50%;
			transform: translateY(-50%);
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__card label[aria-expanded="true"]:after {
			content: "\2212";
		}
	}';
}

	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom {
		display: flex;
		flex-direction: column-reverse;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul {
		border-top: ' . $sptpro_tabs_border['all'] . 'px solid ' . $sptpro_tabs_border['color'] . ';
		border-bottom: 0;
		margin-top: 0;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > li label.sp-tab__active {
		border-color: transparent ' . $sptpro_tabs_border['color'] . $sptpro_tabs_border['color'] . ';
		margin-top: -' . $sptpro_tabs_border['all'] . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > li label,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > li a,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul > .sp-tab__nav-item {
		border-top: 0;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-bottom-left-radius: ' . $sptpro_tabs_border_radius . 'px;
		border-bottom-right-radius: ' . $sptpro_tabs_border_radius . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom > ul {
			border-bottom: none;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__horizontal-bottom .sp-tab__tab-content .sp-tab__tab-pane {
		border-top: ' . $sptpro_desc_border['all'] . 'px ' . $sptpro_desc_border_style . ' ' . $sptpro_desc_border['color'] . ';
		border-bottom: 0;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content .sp-tab-content > ul,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content .sp-tab-content > ol {
		border-bottom: none;
	}';

	/* Tabs Border Style */
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs .sp-tab__nav-link.sp-tab__active .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default label > .sp-tab__card-header {
			color: ' . $sptpro_tabs_title_typo['active_color'] . ';
	}
	#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs > .sp-tab__nav-item.show .sp-tab__nav-link,
	#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs > .sp-tab__nav-item .sp-tab__nav-link.sp-tab__active,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default label > .sp-tab__card-header {
		background-color: ' . $sptpro_title_active_bg_color . ';
	}
	#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__nav-tabs .sp-tab__nav-item.show .sp-tab__nav-link,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default ul li label.sp-tab__active {
		border-color: ' . $sptpro_tabs_border['color'] . $sptpro_tabs_border['color'] . ' transparent;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li > label,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li > a {
		cursor: pointer;
		border-color: ' . $sptpro_tabs_border['color'] . ';
		padding-top: ' . $sptpro_title_padding['top'] . 'px;
		padding-right: ' . $sptpro_title_padding['right'] . 'px;
		padding-bottom: ' . $sptpro_title_padding['bottom'] . 'px;
		padding-left: ' . $sptpro_title_padding['left'] . 'px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . ' > .sp-tab__nav-tabs .sp-tab__nav-link {
		border: ' . $sptpro_tabs_border['all'] . 'px ' . $sptpro_tabs_border['style'] . ' ' . $sptpro_tabs_border['color'] . ';
		height: 100%;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li label,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > li a,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul > .sp-tab__nav-item {
		border-top-left-radius: ' . $sptpro_tabs_border_radius . 'px;
		border-top-right-radius: ' . $sptpro_tabs_border_radius . 'px;
	}';
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__nav-tabs .sp-tab__nav-item {
		margin-bottom: -' . $sptpro_tabs_border['all'] . 'px; 
	}';
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default label.collapsed > .sp-tab__card-header {
		background-color: ' . $sptpro_title_bg_color . ';
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item {
		margin-right: ' . $sptpro_margin_between_tabs . 'px;
		margin-top: 5px;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item label:hover .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item a:hover .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default label.collapsed .sp-tab__card-header:hover {
		color: ' . $sptpro_tabs_title_typo['hover_color'] . ';
		transition: .3s;
	}
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item:hover,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default label.collapsed > .sp-tab__card-header:hover {
		background-color: ' . $sptpro_title_hover_bg_color . ';
	}';
	// Don't load the "margin-right" property in rtl site of the tabs.
if ( ! is_rtl() ) {
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item:last-child {
			margin-right: 0;
			}';
}
if ( is_rtl() ) {
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item:first-child {
			margin-right: 0;
			}';
}

	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane {
		border: ' . $sptpro_desc_border['all'] . 'px ' . $sptpro_desc_border_style . ' ' . $sptpro_desc_border['color'] . ';
		padding-top: ' . $sptpro_desc_padding['top'] . 'px;
		padding-right: ' . $sptpro_desc_padding['right'] . 'px;
		padding-bottom: ' . $sptpro_desc_padding['bottom'] . 'px;
		padding-left: ' . $sptpro_desc_padding['left'] . 'px;
			border-top: 0px;
			background-color: ' . $sptpro_desc_bg_color . ';
		}';
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul {
		border-bottom: ' . $sptpro_desc_border['all'] . 'px ' . $sptpro_desc_border_style . ' ' . $sptpro_desc_border['color'] . ';
	}';

if ( 'full_widht' === $sptpro_tabs_on_small_screen ) {
	$sptpro_dynamic_style .= '@media(max-width:' . $sptpro_set_small_screen['all'] . 'px) {
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul li.sp-tab__nav-item {
			width: 100%;
			margin-right: 0px;
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul li.sp-tab__nav-item:last-child {
			margin-bottom: -1px;
		}';
	$sptpro_dynamic_style .= '}'; // @media end.
}

if ( 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
		$sptpro_dynamic_style .= '.sp-tab__default-accordion > .sp-tab__nav-tabs {
			display: none;
		}

		.sp-tab__default-accordion .sp-tab__card {
			border-radius: 0;
		}

		.sp-tab__default-accordion .sp-tab__card-header {
			cursor: pointer;
		}

	@media(min-width:' . $sptpro_set_small_screen['all'] . 'px) {
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__default-accordion .sp-tab__nav-tabs {
			display: flex;
		}
		#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__card {
			border: none;
		}
		#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__card .sp-tab__card-header {
			display: none;
		}
		#sp-wp-tabs-wrapper_' . $post_id . ' .sp-tab__card .sp-tab__collapse {
			display: block;
		}
	}

	@media(max-width:' . $sptpro_set_small_screen['all'] . 'px) {
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__default-accordion > .sp-tab__tab-content > .sp-tab__tab-pane {
			border: 0;
			padding: 0;
			margin-bottom: 5px;
		}

		.sp-tab__collapse:not(.sp-tab__show) {
			display: none;
		}
		#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default.sp-tab__default-accordion > .sp-tab__tab-content > .sp-tab__tab-pane {
			display: block;
			opacity: 1;
		}
		.sp-tab__default-accordion .sp-tab__tab-content .sp-tab__tab-pane {
			border: none;
			padding: 0;
		}
		.sp-tab__default-accordion .sp-tab__card-header {
			border: 1px solid #ccc;
		}
		.sp-tab__default-accordion .sp-tab__card-body {
			border: 1px solid #ccc;
			border-top: none;
			-ms-flex: 1 1 auto;
			flex: 1 1 auto;
			padding: 1.25rem;
		}
	}';
}

// Typography.
if ( $sptpro_section_title ) {
	$sptpro_dynamic_style .= '#poststuff h2.sp-tab__section_title_' . $post_id . ', h2.sp-tab__section_title_' . $post_id . ' ,.editor-styles-wrapper .wp-block h2.sp-tab__section_title_' . $post_id . '{
		margin-bottom: ' . $section_title_margin_bottom . 'px !important;
		font-weight: 600;
		font-style: normal;
		font-size: 28px;
		line-height: 28px;
		letter-spacing: 0px;
		padding: 0;
		color: ' . $sptpro_section_title_typo['color'] . ';
	}';
}
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > ul .sp-tab__nav-item .sp-tab__tab_title,
	#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default label > .sp-tab__card-header {
		font-weight: 600;
		font-style: normal;
		font-size: 16px;
		line-height: 22px;
		letter-spacing: 0px;
		color: ' . $sptpro_tabs_title_typo['color'] . ';
		margin: 0px;
	}';
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane {
		font-weight: 400;
		font-style: normal;
		font-size: 16px;
		line-height: 24px;
		letter-spacing: 0px;
		color: ' . $sptpro_desc_typo['color'] . ';
	}';
	$sptpro_dynamic_style .= '#sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default > .sp-tab__tab-content .sp-tab__tab-pane ul li a, #sp-wp-tabs-wrapper_' . $post_id . '.sp-tab__lay-default .sp-tab__tab-content .sp-tab__tab-pane ol li a {
		color: ' . $sptpro_desc_typo['color'] . ';
	}';
