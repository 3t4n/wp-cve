<div class="xpro-logo-grid-wrapper xpro-logo-grid-box xpro-logo-grid-col-tablet<?php echo esc_attr( $settings->grid_columns_medium ); ?> xpro-logo-grid-col-mobile<?php echo esc_attr( $settings->grid_columns_responsive ); ?> xpro-logo-grid-col-<?php echo esc_attr( $settings->grid_columns ); ?> ">
	<?php
	$logo_grid_form_field_count = count( $settings->logo_grid_form_field );
	for ( $i = 0; $i < $logo_grid_form_field_count; $i++ ) :
		$item      = $settings->logo_grid_form_field[ $i ];
		$title_tag = ( $item->general_image_link ) ? 'a' : 'span';
		if ( $item->general_image_link_target ) {
			$title_attr = ' target= ' . $item->general_image_link_target;
		}
		$title_attr .= ( 'yes' === $item->general_image_link_nofollow ) ? ' rel=nofollow' : '';

		$title_attr .= $item->general_image_link ? ' href=' . $item->general_image_link . '' : '';
		?>
		<<?php echo esc_attr( $title_tag ) . esc_attr( $title_attr ); ?> class="xpro-logo-grid-item xpro-logo-grid-link">

		<figure class="xpro-logo-grid-figure">
			<?php $module->render_image( $item ); ?>
		</figure>

		</<?php echo esc_attr( $title_tag ); ?>>

	<?php endfor; ?>
</div>



