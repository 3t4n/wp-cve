<?php
/**
 * Displays table style template.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

?>
<div class="styleWrapper">
	<label for="<?php echo esc_attr( $args['key'] ); ?>" class="<?php echo $args['style']['isChecked'] ? 'active' : null; ?>">
		<div class="imgWrapper">
			<img src="<?php echo esc_url( $args['style']['imgUrl'] ); ?>" alt="<?php echo esc_html( $args['key'] ); ?>">
		</div>
		<input type="radio" name="<?php esc_attr( $args['style']['inputName'] ); ?>"
			value="<?php echo esc_attr( $args['key'] ); ?><?php echo ( isset( $args['isPro'] ) && $args['isPro'] ) || ( isset( $args['isUpcoming'] ) && $args['isUpcoming'] ) ? ' disabled' : ''; ?>"
			id="<?php echo esc_attr( $args['key'] ); ?>"
			class="<?php echo ( isset( $args['isPro'] ) && $args['isPro'] ) || ( isset( $args['isUpcoming'] ) && $args['isUpcoming'] ) ? 'pro_feature_input' : ''; ?>">
	</label>
</div>
