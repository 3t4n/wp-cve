<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>

<div class="tab_options_group">
	<p class="field">
		<label for="<?php $this->input_id( 'shape_style', 'background' ); ?>"><?php _e( 'Background color', 'lionplugins' ); ?></label>
		<input type="text" name="<?php $this->input_name( 'shape_style', 'background' ); ?>" value="<?php $this->input_value( 'shape_style', 'background' ); ?>" id="<?php $this->input_id( 'shape_style', 'background' ); ?>" class="js-shape_style-background lion-badges-color-picker" />
	</p>

	<p class="field">
		<label for="<?php $this->input_id( 'shape_style', 'size' ); ?>"><?php _e( 'Size', 'lionplugins' ); ?></label>
		<input type="range" class="js-shape-style-size slider range" min="1" max="100" value="<?php $this->input_value( 'shape_style', 'size' ); ?>" />
		<input type="text" name="<?php $this->input_name( 'shape_style', 'size' ); ?>" id="<?php $this->input_id( 'shape_style', 'size' ); ?>" class="js-shape-style-size range-val" value="<?php $this->input_value( 'shape_style', 'size' ); ?>" /> px
	</p>
</div>
