<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// provided when rendering this partial
if ( empty( $type ) ) {
    return;
}

?>
<?php for ( $i = 1; $i < 4; $i++ ) : $name = $type .'[custom][' . $i . ']'; ?>
	<div class="form__row">
		<label class="text-select" for="<?php echo $name ?>">
			<span><?php _e( 'Custom Text', 'woo-image-seo' ) ?> <?php echo $i ?></span>
		</label>

		<input
			type="text"
			data-custom-text="1"
			name="<?php echo $name ?>"
			id="<?php echo $name ?>"
			value="<?php echo ! isset( $settings[ $type ]['custom'][ $i ] ) ? '' : $settings[ $type ]['custom'][ $i ] ?>"
		>
	</div><!-- /.form__row -->
<?php endfor; ?>