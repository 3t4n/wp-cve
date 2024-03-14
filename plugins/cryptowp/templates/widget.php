<?php echo $args['before_widget']; ?>

<div class="cryptowp-widget<?php echo esc_attr( $classes ); ?>">

	<?php if ( ! empty( $title ) ) : ?>
		<?php echo $args['before_title']; ?><?php echo $title; ?><?php echo $args['after_title']; ?>
	<?php endif; ?>

	<?php if ( ! empty( $text ) ) : ?>
		<?php echo wpautop( $text ); ?>
	<?php endif; ?>

	<?php if ( ! empty( $coins ) ) {
		$columns_style = ! empty( $columns ) && $columns >= 2 ? ' style="width: ' . ( 100 / intval( $columns ) ) . '%"' : '';
		$columns_classes = ! empty( $columns ) && $columns >= 2 ? ' cryptowp-columns' : '';
		$layout_classes = 'cryptowp-' . ( ! empty( $layout ) ? $layout : 'grid' );
		$coins_classes = $layout_classes . $columns_classes;
		$c = 1;
		include( cryptowp_template( 'cryptowp' ) );
	} ?>

</div>

<?php echo $args['after_widget']; ?>