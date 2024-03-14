<div class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?>-filters-wrap ewd-uwcf-style-dropdown' <?php echo ( $this->filtering_args['type'] == 'attribute' ? 'data-attribute_name=' . esc_attr( $this->filtering_args['attribute_name'] ) : '' ); ?>>

	<select class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?>-dropdown' >
		<option value='-1'><?php _e( 'All', 'color-filters' ); ?></option>
	
		<?php foreach ( $this->filtering_args['terms'] as $term ) { ?>
		
			<option value='<?php echo esc_attr( $term->slug ); ?>' <?php echo ( in_array( $term->slug, $this->filtering_args['selected_values'] ) ? 'selected' : '' ); ?> <?php echo ( $this->filtering_args['type'] == 'color' ? $this->get_color_style( $term ) : '' ); ?> >
				<?php echo esc_html( $term->name ); ?> <?php echo ( $this->filtering_args['show_product_count'] ? '(' . intval( $term->count ) . ')' : '' ) ?>
			</option>
		<?php } ?>
	</select>

	<?php foreach ( $this->filtering_args['terms'] as $term ) { ?>
		
		<input type='checkbox' class='ewd-uwcf-<?php echo esc_attr( $this->filtering_args['type'] ); ?> ewd-uwcf-filtering-checkbox' value='<?php echo esc_attr( $term->slug ); ?>' <?php echo ( in_array( $term->slug, $this->filtering_args['selected_values'] ) ? 'checked' : '' ); ?> />
	<?php } ?>
</div>