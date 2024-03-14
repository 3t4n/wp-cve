<!-- Title -->

<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><b><?php echo $strings['title']; ?>:</b></label></p>

<p>
	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $val['title'] ); ?>" class="widefat" />
</p>

<!-- Text -->

<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><b><?php echo $strings['description']; ?>:</b></label></p>

<p>
	<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="widefat" rows="3"><?php echo esc_attr( $val['text'] ); ?></textarea>
</p>

<!-- Coins -->

<p><label><b><?php echo $strings['show_coins']; ?>:</b></label></p>

<?php if ( ! empty( $this->option['coins'] ) ) : ?>
	<p>
		<?php foreach ( $this->option['coins'] as $coin => $fields ) :
			$id = esc_attr( $fields['symbol'] );
			$check = ! empty( $val['coins'][$id] ) ? $val['coins'][$id] : '';
		?>

			<span style="display: inline-block; padding: 4px;">
				<input id="<?php echo $this->get_field_id( 'coins' ) . "-{$id}"; ?>" type="checkbox" name="<?php echo $this->get_field_name( 'coins' ) . "[{$id}]"; ?>" value="1" <?php checked( $check ); ?> />

				<label for="<?php echo $this->get_field_id( 'coins' ) . "-{$id}"; ?>"><?php echo esc_html( $fields['name'] ); ?></label>
			</span>

		<?php endforeach; ?>
	</p>
<?php else : ?>
	<?php echo $strings['import_coins_notice']; ?>
<?php endif; ?>

<!-- Layout -->

<p><label for="<?php echo $this->get_field_id( 'layout' ); ?>"><b><?php echo $strings['layout']; ?>:</b></label></p>

<p>
	<select id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>" style="max-width: 100%;">
		<option value="" <?php selected( $val['layout'], 'grid' ); ?>><?php echo $strings['grid_default']; ?></option>
		<option value="list" <?php selected( $val['layout'], 'list' ); ?>><?php echo $strings['list']; ?></option>
	</select>
</p>

<!-- Columns -->

<div id="<?php echo $this->get_field_id( 'columns' ); ?>-wrap" style="display: <?php echo $show_columns; ?>">

	<p><label for="<?php echo $this->get_field_id( 'columns' ); ?>"><b><?php echo $strings['columns']; ?>:</b></label></p>

	<p>
		<input type="number" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>" value="<?php echo esc_attr( $val['columns'] ); ?>" class="widefat" style="width: 20%;" placeholder="1" />
	</p>

</div>

<!-- Display -->

<p><b><?php echo $strings['display']; ?>:</b></p>

<!-- Hide Icon -->

<p>
	<input id="<?php echo $this->get_field_id( 'hide_icon' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'hide_icon' ); ?>" value="1" <?php checked( $val['hide_icon'] ); ?> />

	<label for="<?php echo $this->get_field_id( 'hide_icon' ); ?>"><?php echo $strings['hide_coin_icons']; ?></label>
</p>

<!-- Hide Percent -->

<p>
	<input id="<?php echo $this->get_field_id( 'hide_percent' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'hide_percent' ); ?>" value="1" <?php checked( $val['hide_percent'] ); ?> />

	<label for="<?php echo $this->get_field_id( 'hide_percent' ); ?>"><?php echo $strings['hide_percent_change']; ?></label>
</p>

<!-- Classes -->

<p><label for="<?php echo $this->get_field_id( 'classes' ); ?>"><b><?php echo sprintf( $strings['html_classes'], '<acronym title="HyperText Markup Language">HTML</acronym>' ); ?>:</b></label></p>

<p>
	<input type="text" id="<?php echo $this->get_field_id( 'classes' ); ?>" name="<?php echo $this->get_field_name( 'classes' ); ?>" value="<?php echo esc_attr( $val['classes'] ); ?>" class="widefat" />
</p>

<!-- Script -->

<script>
	( function() {
		document.getElementById( '<?php echo $this->get_field_id( 'layout' ); ?>' ).onchange = function() {
			document.getElementById( '<?php echo $this->get_field_id( 'columns' ); ?>-wrap' ).style.display = this.value == 'list' ? 'none' : 'block';
		}
	})();
</script>