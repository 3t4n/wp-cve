<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'padding_top' ); ?>"><?php _e( 'Padding top', 'lionplugins' ); ?></label>
	<input type="range" id="<?php $this->input_id( 'text', 'padding_top' ); ?>" class="js-text-padding-top slider range" min="1" max="100" value="<?php $this->input_value( 'text', 'padding_top' ); ?>" />
	<input type="text" name="<?php $this->input_name( 'text', 'padding_top' ); ?>" class="js-text-padding-top range-val" value="<?php $this->input_value( 'text', 'padding_top' ); ?>" /> px
</p>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'padding_right' ); ?>"><?php _e( 'Padding right', 'lionplugins' ); ?></label>
	<input type="range" id="<?php $this->input_id( 'text', 'padding_right' ); ?>" class="js-text-padding-right slider range" min="1" max="100" value="<?php $this->input_value( 'text', 'padding_right' ); ?>" />
	<input type="text" name="<?php $this->input_name( 'text', 'padding_right' ); ?>" class="js-text-padding-right range-val" value="<?php $this->input_value( 'text', 'padding_right' ); ?>" /> px
</p>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'padding_bottom' ); ?>"><?php _e( 'Padding bottom', 'lionplugins' ); ?></label>
	<input type="range" id="<?php $this->input_id( 'text', 'padding_bottom' ); ?>" class="js-text-padding-bottom slider range" min="1" max="100" value="<?php $this->input_value( 'text', 'padding_bottom' ); ?>" />
	<input type="text" name="<?php $this->input_name( 'text', 'padding_bottom' ); ?>" class="js-text-padding-bottom range-val" value="<?php $this->input_value( 'text', 'padding_bottom' ); ?>" /> px
</p>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'padding_left' ); ?>"><?php _e( 'Padding left', 'lionplugins' ); ?></label>
	<input type="range" id="<?php $this->input_id( 'text', 'padding_left' ); ?>" class="js-text-padding-left slider range" min="1" max="100" value="<?php $this->input_value( 'text', 'padding_left' ); ?>" />
	<input type="text" name="<?php $this->input_name( 'text', 'padding_left' ); ?>" class="js-text-padding-left range-val" value="<?php $this->input_value( 'text', 'padding_left' ); ?>" /> px
</p>