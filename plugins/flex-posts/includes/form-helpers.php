<?php
/**
 * Form helper functions
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Input element
 *
 * @param array $args Array of arguments to control the input element.
 */
function flex_posts_input( $args = array() ) {
	$defaults = array(
		'type'  => 'text',
		'name'  => '',
		'id'    => '',
		'value' => '',
		'class' => '',
	);

	$r = wp_parse_args( $args, $defaults );
	?>
	<input type="<?php echo esc_attr( $r['type'] ); ?>"
		name="<?php echo esc_attr( $r['name'] ); ?>"
		<?php if ( ! empty( $r['id'] ) ) : ?>
			id="<?php echo esc_attr( $r['id'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['style'] ) ) : ?>
			style="<?php echo esc_attr( $r['style'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['class'] ) ) : ?>
			class="<?php echo esc_attr( $r['class'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['size'] ) ) : ?>
			size="<?php echo esc_attr( $r['size'] ); ?>"
		<?php endif; ?>
		<?php if ( isset( $r['min'] ) ) : ?>
			min="<?php echo esc_attr( $r['min'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['max'] ) ) : ?>
			max="<?php echo esc_attr( $r['max'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['placeholder'] ) ) : ?>
			placeholder="<?php echo esc_attr( $r['placeholder'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['checked'] ) ) : ?>
			checked="checked"
		<?php endif; ?>
		value="<?php echo esc_attr( $r['value'] ); ?>">
	<?php
}

/**
 * Textarea element
 *
 * @param array $args Array of arguments to control the textarea element.
 */
function flex_posts_textarea( $args = array() ) {
	$defaults = array(
		'name'  => '',
		'id'    => '',
		'value' => '',
		'class' => '',
	);

	$r = wp_parse_args( $args, $defaults );
	?>
	<textarea name="<?php echo esc_attr( $r['name'] ); ?>"
		<?php if ( ! empty( $r['id'] ) ) : ?>
			id="<?php echo esc_attr( $r['id'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['style'] ) ) : ?>
			style="<?php echo esc_attr( $r['style'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['class'] ) ) : ?>
			class="<?php echo esc_attr( $r['class'] ); ?>"
		<?php endif; ?>><?php echo esc_textarea( $r['value'] ); ?></textarea>
	<?php
}

/**
 * Select element
 *
 * @param array $args Array of arguments to control the select element.
 */
function flex_posts_select( $args = array() ) {
	$defaults = array(
		'name'        => '',
		'selected'    => '',
		'id'          => '',
		'class'       => '',
		'options'     => array(),
		'first'       => '',
		'first_value' => '',
		'multiple'    => false,
		'array_name'  => false,
	);

	$r = wp_parse_args( $args, $defaults );

	$r['selected'] = (array) $r['selected'];
	if ( $r['array_name'] ) {
		$r['name'] .= '[]';
	}
	?>
	<select name="<?php echo esc_attr( $r['name'] ); ?>"
		<?php if ( ! empty( $r['id'] ) ) : ?>
			id="<?php echo esc_attr( $r['id'] ); ?>"
		<?php endif; ?>
		<?php if ( ! empty( $r['multiple'] ) ) : ?>
			multiple="multiple"
		<?php endif; ?>
		<?php if ( ! empty( $r['size'] ) ) : ?>
			size="<?php echo esc_attr( $r['size'] ); ?>"
		<?php endif; ?>
		class="<?php echo esc_attr( $r['class'] ); ?>">
		<?php if ( ! empty( $r['first'] ) ) : ?>
			<option value="<?php echo esc_attr( $r['first_value'] ); ?>"><?php echo esc_html( $r['first'] ); ?></option>
		<?php endif; ?>
		<?php foreach ( $r['options'] as $key => $value ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>"<?php echo in_array( (string) $key, $r['selected'], true ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( $value ); ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}
