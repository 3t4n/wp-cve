<input type="hidden" name="<?php echo esc_attr( $this->checkbox_name ); ?>" value="0" />
<span>
	<input type="checkbox" name="<?php echo esc_attr( $this->checkbox_name ); ?>" value="1" />
	<label for="<?php echo esc_attr( $this->checkbox_name ); ?>">
		<?php echo $label; ?>
	</label>
</span>