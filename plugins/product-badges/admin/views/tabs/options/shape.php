<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>

<div id="badge-design">
	<div class="badge-row">
		<input type="radio" name="<?php $this->input_name( 'shape', 'badge' ); ?>" id="<?php $this->input_id( 'shape', 'badge' ); ?>" <?php checked( $this->get_input_value( 'shape', 'badge' ), 'square' ); ?> value="square" />
		<img src="<?php echo LION_BADGES_URL . '/admin/assets/images/square.png'; ?>" />
	</div>
	
	<div class="badge-row">
		<input type="radio" name="<?php $this->input_name( 'shape', 'badge' ); ?>" id="<?php $this->input_id( 'shape', 'badge' ); ?>" <?php checked( $this->get_input_value( 'shape', 'badge' ), 'circle' ); ?> value="circle" />
		<img src="<?php echo LION_BADGES_URL . '/admin/assets/images/circle.png'; ?>" />
	</div>
</div>
