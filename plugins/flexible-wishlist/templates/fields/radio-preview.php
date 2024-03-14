<?php
/**
 * @var WPDesk\FlexibleWishlist\Settings\Option\Option $field           .
 * @var mixed[]                                        $settings_values .
 * @package WPDesk\FlexibleWishlist
 */

?>
<div class="fwSettings__field">
	<div class="fwSettings__fieldTitle"><?php echo esc_html( $field->get_label() ); ?></div>
	<div class="fwSettings__fieldWrapper">
		<ul class="fwSettings__fieldValues">
			<?php foreach ( $field->get_options() as $option_key => $option_label ) : ?>
				<li class="fwSettings__fieldValue">
					<input type="radio"
						id="<?php echo esc_attr( $field->get_name() . '_' . $option_key ); ?>"
						name="<?php echo esc_attr( $field->get_name() ); ?>"
						value="<?php echo esc_attr( $option_key ); ?>"
						<?php echo ( (string) $settings_values[ $field->get_name() ] === $option_key ) ? 'checked' : ''; ?>
						<?php echo ( in_array( $option_key, $field->get_disabled_options(), true ) ) ? 'disabled' : ''; ?>
						class="fwSettings__fieldRadio">
					<label for="<?php echo esc_attr( $field->get_name() . '_' . $option_key ); ?>"
						class="fwSettings__fieldRadioPreview">
						<?php echo esc_html( $option_label ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php if ( $field->get_description() !== null ) : ?>
			<div class="fwSettings__fieldDesc"><?php echo wp_kses_post( $field->get_description() ); ?></div>
		<?php endif; ?>
	</div>
</div>
