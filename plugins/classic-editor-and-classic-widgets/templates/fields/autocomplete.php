<?php
/**
 * @var $args
 */

use \GRIM_CEW\Settings;

?>
<div class="settings-row align-normal">
	<div class="settings-label">
		<label for="<?php echo esc_attr( $args['name'] ); ?>"><?php echo wp_kses( $args['label'], array( 'strong' => array() ) ); ?></label>
		<?php if ( ! empty( $args['description'] ) ) { ?>
			<small><?php echo esc_html( $args['description'] ); ?></small>
		<?php } ?>
	</div>
	<div class="settings-input">
		<input type="text" class="cew-autocomplete"
				data-target="<?php echo esc_attr( $args['name'] ); ?>"
				placeholder="<?php echo esc_attr__( 'Type to Search...', 'classic-editor-and-classic-widgets' ); ?>">
		<input type="hidden" id="<?php echo esc_attr( $args['name'] ); ?>"
				name="<?php echo esc_attr( $args['name'] ); ?>"
				value="<?php echo esc_attr( stripslashes( Settings::get_option( $args['name'] ) ?? '' ) ); ?>">
		<div class="cew-autocomplete-terms"></div>
	</div>
</div>
