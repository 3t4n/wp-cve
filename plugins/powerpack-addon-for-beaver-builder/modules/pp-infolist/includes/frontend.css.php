<?php
$layout = $settings->layouts;

FLBuilderCSS::dimension_field_rule( array(
	'settings'		=> $settings,
	'setting_name'	=> 'item_padding',
	'selector'		=> ".fl-node-$id .pp-infolist-wrap .pp-list-item-content",
	'props'			=> array(
		'padding-top' 		=> 'item_padding_top',
		'padding-right' 	=> 'item_padding_right',
		'padding-bottom' 	=> 'item_padding_bottom',
		'padding-left' 		=> 'item_padding_left',
	),
	'unit'			=> 'px',
) );

FLBuilderCSS::border_field_rule( array(
	'settings'		=> $settings,
	'setting_name'	=> 'item_border',
	'selector'		=> ".fl-node-$id .pp-infolist-wrap .pp-list-item-content",
) );
?>

.fl-node-<?php echo $id; ?> .pp-infolist-wrap .pp-list-item-content {
	<?php if ( isset( $settings->item_bg ) && ! empty( $settings->item_bg ) ) { ?>
		background-color: <?php echo pp_get_color_value( $settings->item_bg ); ?>;
	<?php } ?>
	transition: all 0.3s ease-in-out;
}
.fl-node-<?php echo $id; ?> .pp-infolist-wrap .pp-list-item-content:hover {
	<?php if ( isset( $settings->item_bg_hover ) && ! empty( $settings->item_bg_hover ) ) { ?>
		background-color: <?php echo pp_get_color_value( $settings->item_bg_hover ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->item_border_hover ) && ! empty( $settings->item_border_hover ) ) { ?>
		border-color: <?php echo pp_get_color_value( $settings->item_border_hover ); ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-infolist-wrap .pp-list-item {
	padding-bottom: 0;
}

<?php if ( isset( $settings->icon_position ) && 'with_heading' === $settings->icon_position ) { ?>
	.fl-node-<?php echo $id; ?> .pp-infolist-wrap .layout-1 .pp-list-item .pp-list-item-content,
	.fl-node-<?php echo $id; ?> .pp-infolist-wrap .layout-2 .pp-list-item .pp-list-item-content {
		align-items: flex-start;
	}
<?php } ?>

<?php
	// List - Spacing
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'list_spacing',
		'selector'		=> ".fl-node-$id .pp-infolist ul",
		'prop'			=> 'gap',
		'unit'			=> 'px',
		'enabled'		=> ( isset( $settings->list_spacing ) && $settings->list_spacing >= 0 )
	) );
?>

.fl-node-<?php echo $id; ?> .pp-infolist-title .pp-infolist-title-text {
	<?php if ( $settings->title_color ) { ?>
		color: <?php echo pp_get_color_value( $settings->title_color ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->title_margin['top'] ) && $settings->title_margin['top'] >= 0 ) { ?>
	margin-top: <?php echo $settings->title_margin['top']; ?>px;
	<?php } ?>
	<?php if ( isset( $settings->title_margin['bottom'] ) && $settings->title_margin['bottom'] >= 0 ) { ?>
	margin-bottom: <?php echo $settings->title_margin['bottom']; ?>px;
	<?php } ?>
	transition: color 0.2s ease-in-out;
}
.fl-node-<?php echo $id; ?> .pp-infolist-title .pp-infolist-title-text:hover {
	<?php if ( isset( $settings->title_hover_color ) && ! empty( $settings->title_hover_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->title_hover_color ); ?>;
	<?php } ?>
}

<?php
	// Title Typography
	FLBuilderCSS::typography_field_rule( array(
		'settings'		=> $settings,
		'setting_name' 	=> 'title_typography',
		'selector' 		=> ".fl-node-$id .pp-infolist-title .pp-infolist-title-text",
	) );
?>

.fl-node-<?php echo $id; ?> .pp-infolist-description {
	<?php if( $settings->text_color ) { ?>color: <?php echo pp_get_color_value( $settings->text_color ); ?>;<?php } ?>
}

<?php
	// Text Typography
	FLBuilderCSS::typography_field_rule( array(
		'settings'		=> $settings,
		'setting_name' 	=> 'text_typography',
		'selector' 		=> ".fl-node-$id .pp-infolist-description",
	) );
?>

.fl-node-<?php echo $id; ?> .pp-infolist-icon {
	<?php if( $settings->icon_border_radius ) { ?>border-radius: <?php echo $settings->icon_border_radius; ?>px;<?php } ?>
	<?php if( $settings->show_border == 'yes' ) { ?>
		<?php if( $settings->icon_border_color ) { ?>border-color: <?php echo pp_get_color_value( $settings->icon_border_color ); ?>;<?php } ?>
		<?php if( $settings->icon_border_style ) { ?>border-style: <?php echo $settings->icon_border_style; ?>;<?php } ?>
		<?php if( $settings->icon_border_width ) { ?>border-width: <?php echo $settings->icon_border_width; ?>px;<?php } ?>
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-infolist-icon-inner img {
	<?php if( $settings->icon_border_radius ) { ?>border-radius: <?php echo $settings->icon_border_radius; ?>px;<?php } ?>
}

<?php
	// Icon - Inside Spacing
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_box_size',
		'selector'		=> ".fl-node-$id .pp-infolist-icon",
		'prop'			=> 'padding',
		'unit'			=> 'px',
	) );

	// Icon - Size
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_font_size',
		'selector'		=> ".fl-node-$id .pp-infolist-icon-inner img",
		'prop'			=> 'width',
		'unit'			=> 'px',
	) );

	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_font_size',
		'selector'		=> ".fl-node-$id .pp-infolist-icon-inner img",
		'prop'			=> 'height',
		'unit'			=> 'px',
	) );

	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_font_size',
		'selector'		=> ".fl-node-$id .pp-infolist-icon-inner span.pp-icon, .fl-node-$id .pp-infolist-icon-inner span.pp-icon:before",
		'prop'			=> 'font-size',
		'unit'			=> 'px',
	) );

	// Icon - Box Size
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_box_width',
		'selector'		=> ".fl-node-$id .pp-infolist-icon-inner",
		'prop'			=> 'width',
		'unit'			=> 'px',
	) );

	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_box_width',
		'selector'		=> ".fl-node-$id .pp-infolist-icon-inner",
		'prop'			=> 'height',
		'unit'			=> 'px',
	) );
?>

.fl-node-<?php echo $id; ?> .pp-infolist-icon:hover {
	<?php if( $settings->show_border == 'yes' ) { ?>
		<?php if( $settings->icon_border_color_hover ) { ?>border-color: <?php echo pp_get_color_value( $settings->icon_border_color_hover ); ?>;<?php } ?>
	<?php } ?>
}

<?php
	// Icons - Gap
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_gap',
		'selector'		=> ".fl-node-$id .pp-infolist-wrap .layout-1 .pp-icon-wrapper",
		'prop'			=> 'margin-right',
		'unit'			=> 'px',
		'enabled'		=> ( $settings->icon_gap >= 0 )
	) );

	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_gap',
		'selector'		=> ".fl-node-$id .pp-infolist-wrap .layout-2 .pp-icon-wrapper",
		'prop'			=> 'margin-left',
		'unit'			=> 'px',
		'enabled'		=> ( $settings->icon_gap >= 0 )
	) );

	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'icon_gap',
		'selector'		=> ".fl-node-$id .pp-infolist-wrap .layout-3 .pp-icon-wrapper",
		'prop'			=> 'margin-bottom',
		'unit'			=> 'px',
		'enabled'		=> ( $settings->icon_gap >= 0 )
	) );
?>

.fl-node-<?php echo $id; ?> .pp-infolist-wrap .layout-1 .pp-list-connector {
	<?php if( $settings->connector_color ) { ?>border-left-color: <?php echo pp_get_color_value( $settings->connector_color ); ?>;<?php } ?>
	<?php if( $settings->connector_type ) { ?>border-left-style: <?php echo $settings->connector_type; ?>;<?php } ?>
	<?php if( $settings->connector_width ) { ?>border-left-width: <?php echo $settings->connector_width; ?>px;<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-infolist-wrap .layout-2 .pp-list-connector {
	<?php if( $settings->connector_color ) { ?>border-right-color: <?php echo pp_get_color_value( $settings->connector_color ); ?>;<?php } ?>
	<?php if( $settings->connector_type ) { ?>border-right-style: <?php echo $settings->connector_type; ?>;<?php } ?>
	<?php if( $settings->connector_width ) { ?>border-right-width: <?php echo $settings->connector_width; ?>px;<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-infolist-wrap .layout-3 .pp-list-connector {
	<?php if( $settings->connector_color ) { ?>border-top-color: <?php echo pp_get_color_value( $settings->connector_color ); ?>;<?php } ?>
	<?php if( $settings->connector_type ) { ?>border-top-style: <?php echo $settings->connector_type; ?>;<?php } ?>
	<?php if( $settings->connector_width ) { ?>border-top-width: <?php echo $settings->connector_width; ?>px;<?php } ?>
}

/* Icon common styles */
.fl-node-<?php echo $id; ?> .pp-list-item .pp-infolist-icon .pp-icon {
	<?php if ( isset( $settings->icon_background ) && ! empty( $settings->icon_background ) ) { ?>
	background-color: <?php echo pp_get_color_value( $settings->icon_background ); ?>;
	<?php } ?>
	<?php if ( $settings->icon_border_radius ) { ?>
	border-radius: <?php echo $settings->icon_border_radius; ?>px;
	<?php } ?>
	<?php if ( $settings->icon_color ) { ?>
	color: <?php echo pp_get_color_value( $settings->icon_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-list-item .pp-infolist-icon:hover .pp-icon {
	<?php if ( isset( $settings->icon_background_hover ) && ! empty( $settings->icon_background_hover ) ) { ?>
	background-color: <?php echo pp_get_color_value( $settings->icon_background_hover ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->icon_color_hover ) && ! empty( $settings->icon_color_hover ) ) { ?>
	color: <?php echo pp_get_color_value( $settings->icon_color_hover ); ?>;
	<?php } ?>
}

<?php
// List items loop.
$number_items = count( $settings->list_items );
for ( $i=0; $i < $number_items; $i++ ) :
	$item = $settings->list_items[ $i ]; ?>

	.fl-node-<?php echo $id; ?> .pp-list-item-<?php echo $i; ?> .pp-infolist-icon .pp-icon {
		<?php if ( isset( $item->icon_background ) && ! empty( $item->icon_background ) ) { ?>
		background-color: <?php echo pp_get_color_value( $item->icon_background ); ?>;
		<?php } ?>
		<?php if( $item->icon_color ) { ?>color: <?php echo pp_get_color_value( $item->icon_color ); ?>;<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-list-item-<?php echo $i; ?> .pp-infolist-icon:hover .pp-icon {
		<?php if ( isset( $item->icon_background_hover ) && ! empty( $item->icon_background_hover ) ) { ?>
		background-color: <?php echo pp_get_color_value( $item->icon_background_hover ); ?>;
		<?php } ?>
		<?php if ( isset( $item->icon_color_hover ) && ! empty( $item->icon_color_hover ) ) { ?>
		color: <?php echo pp_get_color_value( $item->icon_color_hover ); ?>;
		<?php } ?>
	}

	<?php if( $item->link_type == 'read_more' ) { ?>
		.fl-node-<?php echo $id; ?> .pp-list-item-<?php echo $i; ?> .pp-more-link {
			<?php if( $item->read_more_color ) { ?>color: <?php echo pp_get_color_value( $item->read_more_color ); ?>;<?php } ?>
		}
		.fl-node-<?php echo $id; ?> .pp-list-item-<?php echo $i; ?> .pp-more-link:hover {
			<?php if( $item->read_more_color_hover ) { ?>color: <?php echo pp_get_color_value( $item->read_more_color_hover ); ?>;<?php } ?>
		}
	<?php } ?>

	.fl-node-<?php echo $id; ?> .pp-list-item-<?php echo $i; ?> .animated {
		<?php if( $item->animation_duration ) { ?>-webkit-animation-duration: <?php echo $item->animation_duration; ?>ms;<?php } ?>
		<?php if( $item->animation_duration ) { ?>-moz-animation-duration: <?php echo $item->animation_duration; ?>ms;<?php } ?>
		<?php if( $item->animation_duration ) { ?>-o-animation-duration: <?php echo $item->animation_duration; ?>ms;<?php } ?>
		<?php if( $item->animation_duration ) { ?>-ms-animation-duration: <?php echo $item->animation_duration; ?>ms;<?php } ?>
		<?php if( $item->animation_duration ) { ?>animation-duration: <?php echo $item->animation_duration; ?>ms;<?php } ?>
	}
<?php endfor; ?>

.fl-node-<?php echo $id; ?> .pp-infolist-wrap .layout-3 .pp-list-item {
	width: <?php echo 100 / $number_items; ?>%;
}


@media only screen and (max-width: 768px) {
	.fl-node-<?php echo $id; ?> .pp-infolist-wrap .layout-3 .pp-list-item {
		width: 100%;
		float: none;
	}
}
