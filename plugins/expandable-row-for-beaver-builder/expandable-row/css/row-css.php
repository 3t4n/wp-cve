<?php if ( $row->settings->is_enable == 'yes' ): ?>

	/* Icon Padding */
	.fl-node-<?php echo $row->node; ?> .bber-icon {
		color: <?php echo ($row->settings->er_bc_icon_color != '' )? '#'.$row->settings->er_bc_icon_color : 'inherit' ?>;
		font-size: <?php echo ($row->settings->er_icon_size != '' )? $row->settings->er_icon_size.'px' : 'inherit' ?>;
		vertical-align: middle;
		padding: 0 10px;
		-webkit-transition: all 0.3s ease-out;
		-moz-transition: all 0.3s ease-out;
		-ms-transition: all 0.3s ease-out;
		-o-transition: all 0.3s ease-out;
		transition: all 0.3s ease-out;
	}
	.fl-node-<?php echo $row->node; ?> .bber-expanded .bber-icon {
		color: <?php echo ($row->settings->er_ac_icon_color != '' )? '#'.$row->settings->er_ac_icon_color : 'inherit' ?>;
	}

	/* Image Padding */
	.fl-node-<?php echo $row->node; ?> .bber-image {
		padding: 0 10px;
	}
	.fl-node-<?php echo $row->node; ?> .bb-er-row:hover .bber-icon {
		color: <?php echo ($row->settings->er_bc_icon_hcolor != '' )? '#'.$row->settings->er_bc_icon_hcolor : 'inherit' ?>;
	}
	.fl-node-<?php echo $row->node; ?> .bber-expanded:hover .bber-icon {
		color: <?php echo ($row->settings->er_ac_icon_hcolor != '' )? '#'.$row->settings->er_ac_icon_hcolor : 'inherit' ?>;
	}

	<?php if ( ! FLBuilderModel::is_builder_active() ): ?>
		.fl-node-<?php echo $row->node; ?> .fl-row-content-wrap {
			display: none;
		}
	<?php endif ?>

	/* Expandable Row */
	.fl-node-<?php echo $row->node; ?> .bb-er-row {
		width:100%;
		cursor: pointer;
		color: #<?php echo ($row->settings->er_bc_title_color != '') ? $row->settings->er_bc_title_color : '000' ; ?>;
		<?php if( $row->settings->er_bg_type == 'color'): ?>
			background-color:#<?php echo ($row->settings->er_bc_bg_color != '') ? $row->settings->er_bc_bg_color : 'c7c7c7' ; ?>;
		<?php elseif ($row->settings->er_bg_type == 'image' ): ?>
			background-image: url(<?php echo isset($row->settings->er_bc_bg_image_src)? $row->settings->er_bc_bg_image_src : null ; ?>);
		<?php endif ?>
		<?php if($row->settings->er_title_typography['family'] != 'Default'):?>
		font-family: <?php echo ($row->settings->er_title_typography['family'] != 'Default')? $row->settings->er_title_typography['family'] : 'inherit' ?>;
		<?php if ( isset( $row->settings->er_title_typography['weight'] ) ) { ?>
		font-weight: <?php echo ($row->settings->er_title_typography['weight'] != 'default' || $row->settings->er_title_typography['weight'] != 'regular' ) ? $row->settings->er_title_typography['weight'] : '500' ; ?>;
		<?php }
		endif ?>
		font-size: <?php echo ($row->settings->er_font_size != '' ) ? $row->settings->er_font_size : '28' ; ?>px;
		line-height: <?php echo ($row->settings->er_line_height != '' ) ? $row->settings->er_line_height : '32' ; ?>px;
		text-align: <?php echo $row->settings->er_title_align; ?>;
		padding-top: <?php echo ($row->settings->er_padding_top != '' ) ? $row->settings->er_padding_top : '20' ; ?>px;
		padding-bottom: <?php echo ($row->settings->er_padding_bottom != '' ) ? $row->settings->er_padding_bottom : '20' ; ?>px;
		padding-left: <?php echo ($row->settings->er_padding_left != '' ) ? $row->settings->er_padding_left : '20' ; ?>px;
		padding-right: <?php echo ($row->settings->er_padding_right != '' ) ? $row->settings->er_padding_right : '20' ; ?>px;
		-webkit-transition: all 0.3s ease-out;
		-moz-transition: all 0.3s ease-out;
		-ms-transition: all 0.3s ease-out;
		-o-transition: all 0.3s ease-out;
		transition: all 0.3s ease-out;
	}

	/* After click expand */
	.fl-node-<?php echo $row->node; ?> .bber-expanded {
		color: <?php echo ($row->settings->er_ac_title_color != '') ? '#'.$row->settings->er_ac_title_color : 'inherit' ; ?>;
		<?php if ($row->settings->er_bg_type == 'color' ): ?>
			background-color: <?php echo ($row->settings->er_ac_bg_color != '') ? '#'.$row->settings->er_ac_bg_color : '#c7c7c7' ; ?>;
		<?php elseif ($row->settings->er_bg_type == 'image' ): ?>
			background-image: url(<?php echo isset($row->settings->er_ac_bg_image_src)? $row->settings->er_ac_bg_image_src : null ; ?>);
		<?php endif ?>

	}

	/* Expandable row hover */
	.fl-node-<?php echo $row->node; ?> .bb-er-row:hover {
		color: <?php echo ($row->settings->er_bc_title_hcolor != '') ? '#'.$row->settings->er_bc_title_hcolor : 'inherit' ; ?>;
		<?php if ($row->settings->er_bg_type == 'color' ): ?>
			background-color: <?php echo ($row->settings->er_bc_bg_hcolor != '') ? '#'.$row->settings->er_bc_bg_hcolor : 'inherit' ; ?>;
		<?php endif ?>
	}

	/* After click hover */
	.fl-node-<?php echo $row->node; ?> .bber-expanded:hover {
		color: <?php echo ($row->settings->er_ac_title_hcolor != '') ? '#'.$row->settings->er_ac_title_hcolor : 'inherit' ; ?>;
		<?php if ($row->settings->er_bg_type == 'color' ): ?>
			background-color: <?php echo ($row->settings->er_ac_bg_hcolor != '') ? '#'.$row->settings->er_ac_bg_hcolor : '#c7c7c7' ; ?>;
		<?php endif ?>	
	}

	/* Image icon size */
	.fl-node-<?php echo $row->node; ?> .bber-image img {
		vertical-align: middle;
		width: <?php echo ( $row->settings->er_image_size != '' ) ? $row->settings->er_image_size : 'auto' ?>px;
	}

	/* Dashicons alignment */
	.fl-node-<?php echo $row->node; ?> .dashicons,
	.fl-node-<?php echo $row->node; ?> .dashicons-before:before {
		width: auto;
    	height: auto;
    	font-size: <?php echo ($row->settings->er_icon_size != '' )? $row->settings->er_icon_size.'px' : 'inherit' ?>;
	}

<?php endif ?>