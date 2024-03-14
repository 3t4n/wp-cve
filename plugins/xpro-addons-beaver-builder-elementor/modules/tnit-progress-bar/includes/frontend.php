<?php

/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 */

if ( 'style-1' === $settings->progressbar_style ) {
	$progressbar_class = 'tnit-progressbar-item tnit-progressBoxvh1 tnit-progressBox tnit-progress-radius';
} elseif ( 'style-2' === $settings->progressbar_style ) {
	$progressbar_outer = ' tnit-progress-style_v2';
	$progressbar_class = 'tnit-progressbar-item tnit-progress-blueColor';
} elseif ( 'style-3' === $settings->progressbar_style ) {
	$progressbar_class = 'tnit-progressbar-item tnit-progressbar-item_v2';
} elseif ( 'style-4' === $settings->progressbar_style ) {
	$progressbar_class = 'tnit-progressbar-item tnit-progressbar-item_v3';
} elseif ( 'style-5' === $settings->progressbar_style ) {
	$progressbar_class = 'tnit-progressbar-item tnit-progressbar-item_v1 tnit-progress-item-flex';
}

?>

<div class="tnit-progressbar-outer">

	<?php
	$progressbar_items_count = count( $settings->progressbar_items );
	for ( $i = 0; $i < $progressbar_items_count; $i++ ) {
		$progressbar_item = $settings->progressbar_items[ $i ];

		?>
		<div class="<?php echo esc_attr( $progressbar_class ); ?> tnit-progressbar-item-<?php echo esc_attr( $i ); ?><?php ( 'gradient' === $settings->progress_color_type ) ? ' tnit-progress_gradient' : ''; ?>">
			<?php if ( 'style-5' === $settings->progressbar_style ) { ?>
				<div class="tnit-flex-column-start">
			<?php } ?>
				<?php if ( 'style-3' !== $settings->progressbar_style ) { ?>
					<h4 class="tnit-progress-title"><?php echo esc_attr( $progressbar_item->progressbar_title ); ?></h4>
				<?php } ?>
			<?php if ( 'style-5' === $settings->progressbar_style ) { ?>
				</div>
			<?php } ?>

			<?php if ( 'style-5' === $settings->progressbar_style ) { ?>
				<div class="tnit-flex-column-end">
			<?php } ?>
			<?php if ( 'style-3' === $settings->progressbar_style ) { ?>
				<div class="tnit-progressbar-wrapper">
					<h4 class="tnit-progress-title"><?php echo esc_attr( $progressbar_item->progressbar_title ); ?></h4>
			<?php } ?>
				<div class="tnit-progress_v<?php echo esc_attr( $i + 1 ); ?>"></div>
			<?php if ( 'style-3' === $settings->progressbar_style ) { ?>
				</div>
			<?php } ?>
			<?php if ( ! empty( $progressbar_item->progressbar_des ) ) { ?>
				<div class="tnit-text">
					<?php echo esc_attr( $progressbar_item->progressbar_des ); ?>
				</div>
			<?php } ?>
			<?php if ( 'style-5' === $settings->progressbar_style ) { ?>
				</div>
			<?php } ?>

		</div><!--Progress Item End-->
	<?php } ?>
</div>
