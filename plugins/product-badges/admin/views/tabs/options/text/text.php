<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'text' ); ?>"><?php _e( 'Text', 'lionplugins' ); ?></label>
	<input type="text" name="<?php $this->input_name( 'text', 'text' ); ?>" id="<?php $this->input_id( 'text', 'text' ); ?>" class="js-text-text" value="<?php $this->input_value( 'text', 'text' ); ?>" />
</p>