<?php
/**
 * @var $args
 */

use \GRIM_CEW\Settings;

?>
<div class="settings-row">
	<div class="settings-label">
		<label for="<?php echo esc_attr( $args['name'] ); ?>"><?php echo wp_kses( $args['label'], array( 'strong' => array() ) ); ?></label>
		<?php if ( ! empty( $args['description'] ) ) { ?>
			<small><?php echo esc_html( $args['description'] ); ?></small>
		<?php } ?>
	</div>
	<div class="settings-input">
		<input type="checkbox" value="1" id="<?php echo esc_attr( $args['name'] ); ?>"
				name="<?php echo isset( $args['sub_name'] ) ? esc_attr( "{$args['name']}[{$args['sub_name']}]" ) : esc_attr( $args['name'] ); ?>"
				<?php checked( isset( $args['sub_name'] ) ? Settings::get_option( $args['name'], $args['sub_name'] ) : Settings::get_option( $args['name'] ) ); ?>>
	</div>
</div>
