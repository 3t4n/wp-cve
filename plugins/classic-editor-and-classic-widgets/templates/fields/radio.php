<?php
/**
 * @var $args
 */

use \GRIM_CEW\Settings;

$value = Settings::get_option( $args['name'] ) ?? $args['default'];
?>

<div class="settings-row">
	<div class="settings-label">
		<label><?php echo wp_kses( $args['label'], array( 'strong' => array() ) ); ?></label>
		<?php if ( ! empty( $args['description'] ) ) { ?>
			<small><?php echo esc_html( $args['description'] ); ?></small>
		<?php } ?>
	</div>
	<div class="settings-input">
		<?php
		if ( ! empty( $args['fields'] ) ) {
			foreach ( $args['fields'] as $key => $field ) {
				?>
				<label for="<?php echo esc_attr( $args['name'] . '_' . $key ); ?>">
					<input type="radio"
						id="<?php echo esc_attr( $args['name'] . '_' . $key ); ?>"
						name="<?php echo esc_attr( $args['name'] ); ?>"
						value="<?php echo esc_attr( $field['value'] ); ?>"
						<?php checked( $value, $field['value'] ); ?>>
					<?php echo esc_html( $field['label'] ); ?>
				</label>
				<?php
			}
		}
		?>
	</div>
</div>
