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
		<input type="checkbox"
			id="<?php echo esc_attr( $field->get_name() ); ?>"
			name="<?php echo esc_attr( $field->get_name() ); ?>"
			value="1"
			<?php echo ( $settings_values[ $field->get_name() ] ) ? 'checked' : ''; ?>
			class="fwSettings__fieldToggle">
		<label for="<?php echo esc_attr( $field->get_name() ); ?>"
			class="fwSettings__fieldToggleLabel"></label>
		<?php if ( $field->get_description() !== null ) : ?>
			<div class="fwSettings__fieldDesc"><?php echo wp_kses_post( $field->get_description() ); ?></div>
		<?php endif; ?>
	</div>
</div>
