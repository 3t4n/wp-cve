<?php //echo '<pre>'; print_r( $value ); echo '</pre>'; die; ?>
<div class="areoi-variable-row <?php echo esc_attr( $is_variable ? 'areoi-is-variable' : '' ) ?>">
	<div class="areoi-field areoi-field-visual">
		<div class="areoi-upl-container <?php echo esc_attr( $value ? 'with-image' : '' ) ?>">
			<a href="#" class="areoi-upl areoi-image-opaque">
				<img src="<?php echo esc_url( $value ) ?>" style="width: 100%; height: auto;" />
				<span class="button">Upload image</span>
			</a>
			<a href="#" class="areoi-rmv">Remove image</a>
		</div><!-- .areoi-upl-container -->
		<div></div>
	</div>

	<div class="areoi-field areoi-field-variable">
		<?php include( AREOI__PLUGIN_DIR . 'views/inputs/text.php' ); ?>
		<div></div>
		<button type="button" class="areoi-toggle-field">Use media uploader</button>
	</div>
</div>