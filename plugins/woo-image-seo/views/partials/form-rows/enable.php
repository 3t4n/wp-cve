<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// provided when rendering this partial
if ( empty( $type ) ) {
    return;
}

$name = $type . '[enable]';

?>

<div class="form__row">
	<input
		type="checkbox"
		class="hidden"
		name="<?php echo $name ?>"
		value="0"
		checked
	>

	<label
		class="label--checkbox"
		for="<?php echo $name ?>"
	>
		<input
			type="checkbox"
			name="<?php echo $name ?>"
			id="<?php echo $name ?>"
			value="1"
			<?php checked( $settings[ $type ]['enable'] ) ?>
		><?php
			/* translators: %s is one of the following: "alt" or "title" */
			printf( __( 'Automatic %s attributes', 'woo-image-seo' ), $type )
		?>

		<span class="checkmark"></span>
	</label>
</div><!-- /.form__row -->