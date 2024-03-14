<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="tab_options_group">
	<p class="field">
		<label for="<?php $this->input_id( 'position', 'top' ); ?>"><?php _e( 'Top', 'lionplugins' ); ?></label>
		<input type="range" id="<?php $this->input_id( 'position', 'top' ); ?>" class="js-position-top slider range" min="-50" max="150" value="<?php $this->input_value( 'position', 'top' ); ?>" />
		<input type="text" name="<?php $this->input_name( 'position', 'top' ); ?>" class="js-position-top range-val" value="<?php $this->input_value( 'position', 'top' ); ?>" /> px
	</p>

	<p class="field">
		<label for="<?php $this->input_id( 'position', 'right' ); ?>"><?php _e( 'Right', 'lionplugins' ); ?></label>
		<input type="range" id="<?php $this->input_id( 'position', 'right' ); ?>" class="js-position-right slider range" min="-50" max="150" value="<?php $this->input_value( 'position', 'right' ); ?>" />
		<input type="text" name="<?php $this->input_name( 'position', 'right' ); ?>" class="js-position-right range-val" value="<?php $this->input_value( 'position', 'right' ); ?>" /> px
	</p>

	<p class="field">
		<label for="<?php $this->input_id( 'position', 'left' ); ?>"><?php _e( 'Left', 'lionplugins' ); ?></label>
		<input type="range" id="<?php $this->input_id( 'position', 'left' ); ?>" class="js-position-left slider range" min="-50" max="150" value="<?php $this->input_value( 'position', 'left' ); ?>" />
		<input type="text" name="<?php $this->input_name( 'position', 'left' ); ?>" class="js-position-left range-val" value="<?php $this->input_value( 'position', 'left' ); ?>" /> px
	</p>
</div>
