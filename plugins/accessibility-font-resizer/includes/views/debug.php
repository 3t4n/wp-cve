<label for="afr_debug">
	<input <?php echo (get_option( 'afr_debug' ) == '1' ? 'checked' : '' ) ?> id="afr_debug" name="afr_debug" type="checkbox" value="1"><br />
	<?php _e( 'Check if you want to activate debugging for developers', 'swp-font-resizer' ); ?>
</label>