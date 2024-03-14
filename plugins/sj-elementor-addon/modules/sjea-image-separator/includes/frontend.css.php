.elementor-element .sjea-image-separator-<?php echo $node_id; ?> {
	<?php if ( $settings['position'] == 'top' ) { ?>
		<?php if ( $settings['align'] == 'center' ) { ?>
			-webkit-transform: translate(-50%, -<?php echo $settings['gutter']; ?>%);
		        -ms-transform: translate(-50%, -<?php echo $settings['gutter']; ?>%);
		            transform: translate(-50%, -<?php echo $settings['gutter']; ?>%);
		<?php } else { ?>
			-webkit-transform: translate(0, -<?php echo $settings['gutter']; ?>%);
		        -ms-transform: translate(0, -<?php echo $settings['gutter']; ?>%);
		            transform: translate(0, -<?php echo $settings['gutter']; ?>%);
		<?php } ?>
	<?php }else{ ?>
		<?php if ( $settings['align'] == 'center' ) { ?>
			-webkit-transform: translate(-50%, <?php echo $settings['gutter']; ?>%);
		        -ms-transform: translate(-50%, <?php echo $settings['gutter']; ?>%);
		            transform: translate(-50%, <?php echo $settings['gutter']; ?>%);
		<?php } else { ?>
			-webkit-transform: translate(0, <?php echo $settings['gutter']; ?>%);
		        -ms-transform: translate(0, <?php echo $settings['gutter']; ?>%);
		            transform: translate(0, <?php echo $settings['gutter']; ?>%);
		<?php } ?>
	<?php } ?>
}