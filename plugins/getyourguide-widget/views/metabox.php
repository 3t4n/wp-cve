<div>
	<p><strong><?php _e( 'Query', 'getyourguide-widget' ); ?></strong></p>
	<input name="<?php echo $query_field_id; ?>" value="<?php echo $query; ?>">
	<p class="howto">
		<?php _e( 'Overrides the default widget query for this post.', 'getyourguide-widget' ); ?>
	</p>
</div>
<div>
	<p><strong><?php _e( 'Affected Widget Areas', 'getyourguide-widget' ); ?></strong></p>
	<?php foreach ( $sidebars as $sidebar_id => $sidebar_name ): ?>
		<label for="<?php echo $sidebar_id ?>">
			<input name="<?php echo $sidebar_field_id ?>[]" type="checkbox" value="<?php echo $sidebar_id; ?>" <?php checked( in_array( $sidebar_id, $selected_sidebars ) ); ?>>
			<?php echo esc_html( $sidebar_name ); ?>
		</label><br/>
	<?php endforeach; ?>
	<p class="howto">
		<?php _e( 'Select the areas that should use the above specified query.', 'getyourguide-widget' ); ?>
	</p>
</div>