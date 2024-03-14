<div class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?>-filters-wrap ewd-uwcf-style-checklist' <?php echo ( $this->filtering_args['type'] == 'attribute' ? 'data-attribute_name=' . esc_attr( $this->filtering_args['attribute_name'] ) : '' ); ?>>
	
	<?php foreach ( $this->filtering_args['terms'] as $term ) { ?>
		
		<div class='ewd-uwcf-color-item text-<?php echo esc_attr( $this->filtering_args['disable_text'] ); ?>'>
			<input type='checkbox' class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?> ewd-uwcf-filtering-checkbox ewd-uwcf-checklist' value='<?php echo esc_attr( $term->slug ); ?>' <?php echo ( in_array( $term->slug, $this->filtering_args['selected_values'] ) ? 'checked' : '' ); ?> />

			<?php if ( $this->filtering_args['type'] == 'color' and ! $this->filtering_args['disable_color'] ) { ?>
				
				<div class='ewd-uwcf-color-wrap'>
					<div class='ewd-uwcf-color-preview <?php echo ( in_array( $term->slug, $this->filtering_args['selected_values'] ) ? 'ewd-uwcf-selected' : '' ); ?> <?php echo ( $this->filtering_args['color_shape'] == 'circle' ? 'ewd-uwcf-rcorners' : '' ); ?>' <?php echo $this->get_color_style( $term ); ?> ></div>
				</div>
			<?php } ?>

			<?php if ( ! $this->filtering_args['disable_text'] ) { ?>

				<div class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?>-link <?php echo ( in_array( $term->slug, $this->filtering_args['selected_values'] ) ? 'ewd-uwcf-selected' : '' ); ?>'>
					<span class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?>-name'><?php echo esc_html( $term->name ); ?></span> 
				 	<?php if ( $this->filtering_args['show_product_count'] ) { ?> <span class='ewd-uwcf-product-count'><?php echo '(' . esc_html( $term->count ) . ')'; ?></span><?php } ?>
				</div>
			<?php } ?>

		</div>
	<?php } ?>

	<div class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?>-item ewd-uwcf-all-<?php echo esc_attr( $this->filtering_args['type'] ); ?>'>
		<?php echo esc_html( $this->get_label( 'label-show-all-' . ( strpos( $this->filtering_args['type'], 'attribute' ) === false ? esc_html( $this->filtering_args['type'] ) : 'attribute' ) ) ); ?>
	</div>
	
</div>