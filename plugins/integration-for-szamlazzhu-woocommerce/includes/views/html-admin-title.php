<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

</table>
<div class="wc-szamlazz-settings-section-title">
	<h3 class="wc-settings-sub-title <?php echo esc_attr( $data['class'] ); ?>" id="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></h3>
	<?php if ( ! empty( $data['description'] ) ) : ?>
		<p><?php echo wp_kses_post( $data['description'] ); ?></p>
	<?php endif; ?>
</div>
<table class="form-table">
