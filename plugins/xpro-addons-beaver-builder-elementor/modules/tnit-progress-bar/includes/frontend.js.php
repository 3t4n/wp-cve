(function($) {

	<?php
	$progressbar_items_count = count( $settings->progressbar_items );
	for ( $i = 0; $i < $progressbar_items_count; $i++ ) {
		$progressbar_item = $settings->progressbar_items[ $i ];
		?>
	if ($('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-progress_v<?php echo esc_attr( $i + 1 ); ?>').length) {
	   
		$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-progress_v<?php echo esc_attr( $i + 1 ); ?>').rProgressbar({
			percentage: <?php echo esc_attr( ( '' !== $progressbar_item->progress_number ) ? $progressbar_item->progress_number : '80' ); ?>
		});
	}
	<?php } ?>
})(jQuery);
