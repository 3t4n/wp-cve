<?php
$rows_object = $nodes['rows'];
foreach ( $nodes['rows'] as $row_object ) {
	$id  = $row_object->node;
	$row = $row_object->settings;
	if ( $global_settings->responsive_enabled ) { // Responsive Sizes
		if ( $row->separator_shape_height_medium != '' ) { ?>
            @media(max-width: <?php echo $global_settings->medium_breakpoint; ?>px) {
			<?php if ( $row->separator_shape_height_medium != '' && $row->separator_shape == 'round_split' ) { ?>
                .fl-node-<?php echo $id; ?> .njba-top-row-separator.njba-round-split:before,
                .fl-node-<?php echo $id; ?> .njba-top-row-separator.njba-round-split:after {
                height: <?php echo $row->separator_shape_height_medium; ?>px;
                }
			<?php } else { ?>
                .fl-node-<?php echo $id; ?> .njba-top-row-separator svg {
                height: <?php echo $row->separator_shape_height_medium; ?>px;
                }
			<?php } ?>
            }
		<?php }
		if ( $row->separator_shape_height_small != '' ) { ?>
            @media(max-width: <?php echo $global_settings->responsive_breakpoint; ?>px) {
			<?php if ( $row->separator_shape_height_small != '' ) { ?>
				<?php if ( $row->separator_shape == 'round_split' ) { ?>
                    .fl-node-<?php echo $id; ?> .njba-top-row-separator.njba-round-split:before,
                    .fl-node-<?php echo $id; ?> .njba-top-row-separator.njba-round-split:after {
                    height: <?php echo $row->separator_shape_height_small; ?>px;
                    }
				<?php } else { ?>
                    .fl-node-<?php echo $id; ?> .njba-top-row-separator svg {
                    height: <?php echo $row->separator_shape_height_small; ?>px;
                    }
				<?php } ?>
			<?php } ?>
            }
		<?php }
	}
}
?>
