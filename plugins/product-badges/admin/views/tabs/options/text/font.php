<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'font_family' ); ?>"><?php _e( 'Font family', 'lionplugins' ); ?></label>

	<select id="" name="<?php $this->input_name( 'text', 'font_family' ); ?>" id="<?php $this->input_id( 'text', 'font_family' ); ?>" class="js-text-font-family">
		<?php 
		foreach( lion_badges_text_font_family_options() as $value => $text ) :
			$selected = ( $value == $this->get_input_value( 'text', 'font_family' ) ) ? 'selected' : '';
		?>
			<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
		<?php endforeach; ?>
	</select>
</p>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'font_size' ); ?>"><?php _e( 'Font size', 'lionplugins' ); ?></label>
	<input type="range" id="<?php $this->input_id( 'text', 'font_size' ); ?>" class="js-text-font-size slider range" min="1" max="100" value="<?php $this->input_value( 'text', 'font_size' ); ?>" />
	<input type="text" name="<?php $this->input_name( 'text', 'font_size' ); ?>" class="js-text-font-size range-val" value="<?php $this->input_value( 'text', 'font_size' ); ?>" /> px
</p>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'color' ); ?>"><?php _e( 'Font color', 'lionplugins' ); ?></label>
	<input type="text" name="<?php $this->input_name( 'text', 'color' ); ?>" value="<?php $this->input_value( 'text', 'color' ); ?>" id="<?php $this->input_id( 'text', 'color' ); ?>" class="js-text-color lion-badges-color-picker" />
</p>

<p class="field">
	<label for="<?php $this->input_id( 'text', 'align' ); ?>"><?php _e( 'Text align', 'lionplugins' ); ?></label>

	<select id="" name="<?php $this->input_name( 'text', 'align' ); ?>" id="<?php $this->input_id( 'text', 'align' ); ?>" class="js-text-align">
		<?php 
		foreach( lion_badges_text_align_options() as $value => $text ) :
				$selected = ( $value == $this->get_input_value( 'text', 'align' ) ) ? 'selected' : '';
		?>
		<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
		<?php endforeach; ?>	
	</select>
</p>