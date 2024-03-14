<?php
$line_width_unit = isset( $settings->line_width_unit ) ? $settings->line_width_unit : 'px';
?>
<?php
// Prefix Typography
FLBuilderCSS::typography_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'prefix_typography',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading-prefix",
) );
?>

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix {
	<?php if ( isset( $settings->prefix_text_color ) && ! empty( $settings->prefix_text_color ) ) { ?>
	color: <?php echo pp_get_color_value( $settings->prefix_text_color ); ?>;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content {
	text-align: <?php echo $settings->heading_alignment; ?>;
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading {
	<?php if ( in_array( $settings->heading_separator, array( 'icon_only', 'line_only', 'line_with_icon' ) ) && in_array( $settings->heading_separator_postion, array( 'left', 'right' ) ) ) { ?>
		display: inline-flex;
		align-items: center;
	<?php } ?>
	<?php if ( '' == $settings->heading_title && ! FLBuilderModel::is_builder_active() ) { ?>
	display: none;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title span.title-text {
	display: inline;
	<?php if ( isset( $settings->heading_style ) && 'block' == $settings->heading_style ) { ?>
		display: block;
	<?php } ?>
}

<?php
// Title - Border Width
FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'heading_border',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title span.pp-primary-title",
	'unit'			=> 'px',
	'props'			=> array(
		'border-top-width' 		=> 'heading_border_top',
		'border-right-width' 	=> 'heading_border_right',
		'border-bottom-width' 	=> 'heading_border_bottom',
		'border-left-width' 	=> 'heading_border_left',
	),
) );

// Title - Padding
FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'heading_padding',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title span.pp-primary-title",
	'unit'			=> 'px',
	'props'			=> array(
		'padding-top' 		=> 'heading_padding_top',
		'padding-right' 	=> 'heading_padding_right',
		'padding-bottom' 	=> 'heading_padding_bottom',
		'padding-left' 		=> 'heading_padding_left',
	),
) );

// Title - Gradient Color
FLBuilderCSS::rule( array(
	'selector' 	=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title span.pp-primary-title",
	'enabled'	=> ( isset( $settings->heading_color_type ) && 'gradient' == $settings->heading_color_type ),
	'props' 	=> array(
		'background-image' => FLBuilderColor::gradient( $settings->heading_gradient_setting ),
		'-webkit-background-clip'	=> 'text',
		'-webkit-text-fill-color'	=> 'rgba(0,0,0,0)',
	),
) );
?>

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title span.pp-primary-title {
	<?php if ( ! empty( $settings->heading_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->heading_color ); ?>;
	<?php } ?>
	<?php if ( ! empty( $settings->heading_bg_color ) ) { ?>
		background-color: <?php echo pp_get_color_value( $settings->heading_bg_color ); ?>;
	<?php } ?>
	<?php if ( 'none' != $settings->heading_border_style ) { ?>
		border-style: <?php echo $settings->heading_border_style; ?>;
		border-color: <?php echo pp_get_color_value( $settings->heading_border_color ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->title_typography ) ) { ?>
		<?php if ( isset( $settings->title_typography['text_transform'] ) && ! empty( $settings->title_typography['text_transform'] ) ) { ?>
		text-transform: <?php echo $settings->title_typography['text_transform']; ?>;
		<?php } ?>
	<?php } ?>
	<?php if ( 'block' !== $settings->heading_style ) { ?>
	display: <?php echo ( '' === $settings->heading_bg_color ) ? 'inline' : 'inline-block'; ?>;
	<?php } ?>
}
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title:hover span.pp-primary-title {
	<?php if ( isset( $settings->heading_hover_color ) && ! empty( $settings->heading_hover_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->heading_hover_color ); ?>;
		-webkit-text-fill-color: <?php echo pp_get_color_value( $settings->heading_hover_color ); ?>;
		background: none;
	<?php } ?>
}

<?php
// Secondary Title - Border Width
FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'heading2_border',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title span.pp-secondary-title",
	'unit'			=> 'px',
	'props'			=> array(
		'border-top-width' 		=> 'heading2_border_top',
		'border-right-width' 	=> 'heading2_border_right',
		'border-bottom-width' 	=> 'heading2_border_bottom',
		'border-left-width' 	=> 'heading2_border_left',
	),
) );

// Secondary Title - Padding
FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'heading2_padding',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title span.pp-secondary-title",
	'unit'			=> 'px',
	'props'			=> array(
		'padding-top' 		=> 'heading2_padding_top',
		'padding-right' 	=> 'heading2_padding_right',
		'padding-bottom' 	=> 'heading2_padding_bottom',
		'padding-left' 		=> 'heading2_padding_left',
	),
) );

// Secondary Title - Gradient Color
FLBuilderCSS::rule( array(
	'selector' 	=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title span.pp-secondary-title",
	'enabled'	=> ( isset( $settings->heading2_color_type ) && 'gradient' == $settings->heading2_color_type ),
	'props' 	=> array(
		'background-image' => FLBuilderColor::gradient( $settings->heading2_gradient_setting ),
		'-webkit-background-clip'	=> 'text',
		'-webkit-text-fill-color'	=> 'rgba(0,0,0,0)',
	),
) );

// Typography
FLBuilderCSS::typography_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'title2_typography',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title span.pp-secondary-title",
) );
?>

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title span.pp-secondary-title {
	<?php if ( $settings->heading2_color ) { ?>
	color: <?php echo pp_get_color_value( $settings->heading2_color ); ?>;
	<?php } ?>
	<?php if ( $settings->heading2_bg_color ) { ?>
	background-color: <?php echo pp_get_color_value( $settings->heading2_bg_color ); ?>;
	<?php } ?>
	<?php if ( 'none' != $settings->heading2_border_style ) { ?>
		border-style: <?php echo $settings->heading2_border_style; ?>;
		border-color: <?php echo pp_get_color_value( $settings->heading2_border_color ); ?>;
	<?php } ?>
	margin-left: <?php echo $settings->heading2_left_margin; ?>px;
	<?php if ( 'block' !== $settings->heading_style ) { ?>
	display: <?php echo ( '' === $settings->heading2_bg_color ) ? 'inline' : 'inline-block'; ?>;
	<?php } ?>
}
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title:hover span.pp-secondary-title {
	<?php if ( isset( $settings->heading2_hover_color ) && ! empty( $settings->heading2_hover_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->heading2_hover_color ); ?>;
		-webkit-text-fill-color: <?php echo pp_get_color_value( $settings->heading2_hover_color ); ?>;
		background: none;
	<?php } ?>
}

<?php
if ( isset( $settings->title_typography ) ) {
	if ( isset( $settings->title_typography['text_transform'] ) ) {
		unset( $settings->title_typography['text_transform'] );
	}
}
// Heading Typography
FLBuilderCSS::typography_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'title_typography',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading .heading-title",
) );
?>

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title {
	margin-top: <?php echo $settings->heading_top_margin; ?>px;
	margin-bottom: <?php echo $settings->heading_bottom_margin; ?>px;
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title.text-inline-block span.pp-primary-title,
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .heading-title.text-inline-block span.pp-secondary-title {
	display: inline-block;
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading .pp-heading-link {
<?php if ( isset( $settings->heading_style ) && 'block' == $settings->heading_style ) { ?>
	display: block;
<?php } ?>
}

/** Separators */
<?php if ( 'inline-prefix' === $settings->heading_separator ) { ?>
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix {
	position: relative;
	<?php if ( 'left' === $settings->heading_alignment ) { ?>
		padding-left: 0 !important;
	<?php } ?>
	<?php if ( 'right' === $settings->heading_alignment ) { ?>
		padding-right: 0 !important;
	<?php } ?>
}
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:before,
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:after {
	content: "";
	display: block;
	position: absolute;
	top: 50%;
	border-width: 0;
	<?php if ( $settings->line_width >= 0 ) { ?>
	width: <?php echo $settings->line_width; ?><?php echo $line_width_unit; ?>;
	<?php } ?>
	<?php if ( $settings->heading_line_style ) { ?>
	border-style: <?php echo $settings->heading_line_style; ?>;
	<?php } ?>
	<?php if ( $settings->line_color ) { ?>
	border-color: <?php echo pp_get_color_value( $settings->line_color ); ?>;
	<?php } ?>
	<?php if ( $settings->line_height >= 0 ) { ?>
	border-bottom-width: <?php echo $settings->line_height; ?>px;
	<?php } ?>
}
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:before {
	right: 100%;
}
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:after {
	left: 100%;
}
<?php
FLBuilderCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'font_title_line_space',
	'selector'     => "div.fl-node-$id .pp-heading-content .pp-heading-prefix",
	'prop'         => 'padding-left',
	'unit'         => 'px'
) );
FLBuilderCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'font_title_line_space',
	'selector'     => "div.fl-node-$id .pp-heading-content .pp-heading-prefix",
	'prop'         => 'padding-right',
	'unit'         => 'px'
) );
?>
<?php } ?>

<?php
if ( 'no' == $settings->dual_heading ) {
	FLBuilderCSS::responsive_rule( array(
		'settings' => $settings,
		'setting_name' => 'font_title_line_space',
		'selector' => "div.fl-node-$id div.pp-heading-content .pp-heading.pp-separator-inline .heading-title span",
		'prop' => 'padding-left',
		'unit' => 'px'
	) );
	FLBuilderCSS::responsive_rule( array(
		'settings' => $settings,
		'setting_name' => 'font_title_line_space',
		'selector' => "div.fl-node-$id div.pp-heading-content .pp-heading.pp-separator-inline .heading-title span",
		'prop' => 'padding-right',
		'unit' => 'px'
	) );
} else {
	FLBuilderCSS::responsive_rule( array(
		'settings' => $settings,
		'setting_name' => 'font_title_line_space',
		'selector' => "div.fl-node-$id div.pp-heading-content .pp-heading.pp-separator-inline .heading-title span.pp-primary-title",
		'prop' => 'padding-left',
		'unit' => 'px'
	) );
	FLBuilderCSS::responsive_rule( array(
		'settings' => $settings,
		'setting_name' => 'font_title_line_space',
		'selector' => "div.fl-node-$id div.pp-heading-content .pp-heading.pp-separator-inline .heading-title span.pp-secondary-title",
		'prop' => 'padding-right',
		'unit' => 'px'
	) );
}
?>

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title <?php if ( 'no' == $settings->dual_heading ) { ?>span<?php } else { ?>span.pp-primary-title<?php } ?>:before {
	<?php if ( $settings->line_width >= 0 ) { ?>
	width: <?php echo $settings->line_width; ?><?php echo $line_width_unit; ?>;
	<?php } ?>
	<?php if ( $settings->heading_line_style ) { ?>
	border-style: <?php echo $settings->heading_line_style; ?>;
	<?php } ?>
	<?php if ( $settings->line_color ) { ?>
	border-color: <?php echo pp_get_color_value( $settings->line_color ); ?>;
	<?php } ?>
	<?php if ( $settings->line_height >= 0 ) { ?>
	border-bottom-width: <?php echo $settings->line_height; ?>px;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title <?php if ( 'no' == $settings->dual_heading ) { ?>span<?php } else { ?>span.pp-secondary-title<?php } ?>:after {
	<?php if ( $settings->line_width >= 0 ) { ?>
	width: <?php echo $settings->line_width; ?><?php echo $line_width_unit; ?>;
	<?php } ?>
	<?php if ( $settings->heading_line_style ) { ?>
	border-style: <?php echo $settings->heading_line_style; ?>;
	<?php } ?>
	<?php if ( $settings->line_color ) { ?>
	border-color: <?php echo pp_get_color_value( $settings->line_color ); ?>;
	<?php } ?>
	<?php if ( $settings->line_height >= 0 ) { ?>
	border-bottom-width: <?php echo $settings->line_height; ?>px;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-sub-heading {
	margin-top: <?php echo $settings->sub_heading_top_margin; ?>px;
	margin-bottom: <?php echo $settings->sub_heading_bottom_margin; ?>px;
}

<?php
// Typography
FLBuilderCSS::typography_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'desc_typography',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-sub-heading, div.fl-node-$id .pp-heading-content .pp-sub-heading p",
) );
?>

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-sub-heading,
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-sub-heading p {
	<?php if ( $settings->sub_heading_color ) { ?>
	color: <?php echo pp_get_color_value( $settings->sub_heading_color ); ?>;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-sub-heading p:last-of-type {
	margin-bottom: 0;
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-heading-separator-align {
	<?php if ( $settings->heading_alignment ) { ?>
	text-align: <?php echo $settings->heading_alignment; ?>;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon {
	<?php if ( in_array( $settings->heading_separator, array( 'icon_only', 'line_with_icon' ) ) && in_array( $settings->heading_separator_postion, array( 'left', 'right' ) ) ) { ?>
		overflow: visible;
	<?php } ?>
}
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon:before {
	<?php if ( $settings->font_icon_line_space >= 0 ) { ?>
	margin-right: <?php echo $settings->font_icon_line_space; ?>px;
	<?php } ?>
	<?php if ( 'line_with_icon' === $settings->heading_separator && 'right' === $settings->heading_separator_postion ) { ?>
		display: none;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon:after {
	<?php if ( $settings->font_icon_line_space >= 0 ) { ?>
	margin-left: <?php echo $settings->font_icon_line_space; ?>px;
	<?php } ?>
	<?php if ( 'line_with_icon' === $settings->heading_separator && 'left' === $settings->heading_separator_postion ) { ?>
		display: none;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon.pp-left:after {
	left: 1%;
}
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon.pp-right:before {
	right: 1%;
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon:before,
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon:after {
	<?php if ( $settings->line_width >= 0 ) { ?>
	width: <?php echo $settings->line_width; ?><?php echo $line_width_unit; ?>;
	<?php } ?>
	<?php if ( $settings->heading_line_style ) { ?>
	border-style: <?php echo $settings->heading_line_style; ?>;
	<?php } ?>
	<?php if ( $settings->line_color ) { ?>
	border-color: <?php echo pp_get_color_value( $settings->line_color ); ?>;
	<?php } ?>
	<?php if ( $settings->line_height >= 0 ) { ?>
	border-bottom-width: <?php echo $settings->line_height; ?>px;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .heading-icon-image {
	<?php if ( 'custom_icon_select' == $settings->heading_icon_select ) { ?>
		<?php if ( $settings->font_icon_font_size >= 0 ) { ?>
		width: <?php echo $settings->font_icon_font_size; ?>px;
		<?php } ?>
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-separator-line {
	<?php if ( $settings->heading_line_style ) { ?>
	border-bottom-style: <?php echo $settings->heading_line_style; ?>;
	<?php } ?>
	<?php if ( $settings->line_height >= 0 ) { ?>
	border-bottom-width: <?php echo $settings->line_height; ?>px;
	<?php } ?>
	<?php if ( $settings->line_color ) { ?>
	border-bottom-color: <?php echo pp_get_color_value( $settings->line_color ); ?>;
	<?php } ?>
	<?php if ( $settings->line_width >= 0 ) { ?>
	width: <?php echo $settings->line_width; ?><?php echo $line_width_unit; ?>;
	<?php } ?>
	<?php if ( 'right' == $settings->heading_alignment ) { ?>
	float: right;
	<?php } elseif ( 'left' == $settings->heading_alignment ) { ?>
	float: left;
	<?php } else { ?>
	margin: 0 auto;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator {
	<?php if ( $settings->font_icon_color ) { ?>
	color: <?php echo pp_get_color_value( $settings->font_icon_color ); ?>;
	<?php } ?>
}

<?php
// Separator margin.
FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'separator_margin',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading-separator",
	'unit'			=> 'px',
	'props'			=> array(
		'margin-top' 	=> 'separator_margin_top',
		'margin-right' 	=> 'separator_margin_right',
		'margin-bottom' => 'separator_margin_bottom',
		'margin-left' 	=> 'separator_margin_left',
	),
) );

// Icon padding.
FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'font_icon_padding',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading-separator .pp-heading-separator-icon",
	'unit'			=> 'px',
	'props'			=> array(
		'padding-top' 		=> 'font_icon_padding_top',
		'padding-right' 	=> 'font_icon_padding_right',
		'padding-bottom' 	=> 'font_icon_padding_bottom',
		'padding-left' 		=> 'font_icon_padding_left',
	),
) );
?>

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-heading-separator-icon {
	display: inline-block;
	text-align: center;
	<?php if ( $settings->font_icon_bg_color ) { ?>
	background: <?php echo pp_get_color_value( $settings->font_icon_bg_color ); ?>;
	<?php } ?>
	<?php if ( $settings->font_icon_border_radius >= 0 ) { ?>
	border-radius: <?php echo $settings->font_icon_border_radius; ?>px;
	<?php } ?>
	<?php if ( $settings->font_icon_border_width >= 0 ) { ?>
	border-width: <?php echo $settings->font_icon_border_width; ?>px;
	<?php } ?>
	<?php if ( $settings->font_icon_border_style ) { ?>
	border-style: <?php echo $settings->font_icon_border_style; ?>;
	<?php } ?>
	<?php if ( $settings->font_icon_border_color ) { ?>
	border-color: <?php echo pp_get_color_value( $settings->font_icon_border_color ); ?>;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-heading-separator-icon i,
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-heading-separator-icon i:before {
	<?php if ( 'font_icon_select' == $settings->heading_icon_select ) { ?>
		<?php if ( $settings->font_icon_font_size >= 0 ) { ?>
		font-size: <?php echo $settings->font_icon_font_size; ?>px;
		<?php } ?>
	<?php } ?>
}

<?php
// Icon span padding.
FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'font_icon_padding',
	'selector' 		=> "div.fl-node-$id .pp-heading-content .pp-heading-separator.icon_only span",
	'unit'			=> 'px',
	'props'			=> array(
		'padding-top' 		=> 'font_icon_padding_top',
		'padding-right' 	=> 'font_icon_padding_right',
		'padding-bottom' 	=> 'font_icon_padding_bottom',
		'padding-left' 		=> 'font_icon_padding_left',
	),
) );
?>
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.icon_only span {
	<?php if ( $settings->font_icon_bg_color ) { ?>
	background: <?php echo pp_get_color_value( $settings->font_icon_bg_color ); ?>;
	<?php } ?>
	<?php if ( $settings->font_icon_border_radius >= 0 ) { ?>
	border-radius: <?php echo $settings->font_icon_border_radius; ?>px;
	<?php } ?>
	<?php if ( $settings->font_icon_border_width >= 0 ) { ?>
	border-width: <?php echo $settings->font_icon_border_width; ?>px;
	<?php } ?>
	<?php if ( $settings->font_icon_border_style ) { ?>
	border-style: <?php echo $settings->font_icon_border_style; ?>;
	<?php } ?>
	<?php if ( $settings->font_icon_border_color ) { ?>
	border-color: <?php echo pp_get_color_value( $settings->font_icon_border_color ); ?>;
	<?php } ?>
}

div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.icon_only img,
div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator.line_with_icon img {
	<?php if ( $settings->font_icon_border_radius >= 0 ) { ?>
	border-radius: <?php echo $settings->font_icon_border_radius; ?>px;
	<?php } ?>
}

<?php if ( isset( $settings->hide_separator ) && 'medium' === $settings->hide_separator ) { ?>
@media (min-width: <?php echo $global_settings->responsive_breakpoint; ?>px) and (max-width: <?php echo $global_settings->medium_breakpoint; ?>px) {
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span:before,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span:after,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:before,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:after {
		content: none !important;
		display: none !important;
	}
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix {
		padding: 0 !important;
	}
}
<?php } ?>

@media only screen and (max-width: <?php echo $global_settings->medium_breakpoint; ?>px) {
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-heading-separator-align,
	div.fl-node-<?php echo $id; ?> .pp-heading-content {
		<?php if ( isset( $settings->heading_alignment_medium ) ) { ?>
		text-align: <?php echo $settings->heading_alignment_medium; ?>;
		<?php } ?>
	}
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-separator-line {
		<?php if ( isset( $settings->heading_alignment_medium ) ) { ?>
			<?php if ( 'right' == $settings->heading_alignment_medium ) { ?>
				float: right;
			<?php } ?>
			<?php if ( 'left' == $settings->heading_alignment_medium ) { ?>
				float: left;
			<?php } ?>
			<?php if ( 'center' == $settings->heading_alignment_medium ) { ?>
				margin: 0 auto;
				float: none;
			<?php } ?>
		<?php } ?>
	}
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix {
		<?php if ( isset( $settings->heading_alignment_medium ) ) { ?>
		<?php if ( 'left' === $settings->heading_alignment_medium ) { ?>
			padding-left: 0 !important;
		<?php } ?>
		<?php if ( 'right' === $settings->heading_alignment_medium ) { ?>
			padding-right: 0 !important;
		<?php } ?>
		<?php } ?>
	}
	<?php if ( isset( $settings->hide_separator ) && 'medium-responsive' === $settings->hide_separator ) { ?>
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span:before,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span:after,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:before,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:after {
		content: none !important;
		display: none !important;
	}
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix {
		padding: 0 !important;
	}
	<?php } ?>
}

@media only screen and (max-width: <?php echo $global_settings->responsive_breakpoint; ?>px) {
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-heading-separator-align,
	div.fl-node-<?php echo $id; ?> .pp-heading-content {
		<?php if ( isset( $settings->heading_alignment_responsive ) ) { ?>
		text-align: <?php echo $settings->heading_alignment_responsive; ?>;
		<?php } ?>
	}
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator .pp-separator-line {
		<?php if ( isset( $settings->heading_alignment_responsive ) ) { ?>
			<?php if ( 'right' == $settings->heading_alignment_responsive ) { ?>
				float: right;
			<?php } ?>
			<?php if ( 'left' == $settings->heading_alignment_responsive ) { ?>
				float: left;
			<?php } ?>
			<?php if ( 'center' == $settings->heading_alignment_responsive ) { ?>
				margin: 0 auto;
				float: none;
			<?php } ?>
		<?php } ?>
	}
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix {
		<?php if ( isset( $settings->heading_alignment_responsive ) ) { ?>
		<?php if ( 'left' === $settings->heading_alignment_responsive ) { ?>
			padding-left: 0 !important;
		<?php } ?>
		<?php if ( 'right' === $settings->heading_alignment_responsive ) { ?>
			padding-right: 0 !important;
		<?php } ?>
		<?php } ?>
	}
	<?php if ( isset( $settings->hide_separator ) && 'responsive' === $settings->hide_separator ) { ?>
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-separator,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span:before,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span:after,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:before,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix:after {
		content: none !important;
		display: none !important;
	}
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading.pp-separator-inline .heading-title span,
	div.fl-node-<?php echo $id; ?> .pp-heading-content .pp-heading-prefix {
		padding: 0 !important;
	}
	<?php } ?>
}
