<?php
defined( 'ABSPATH' ) || exit();

$core        = $args['core'];
$plugin_info = $args['plugin_info'];
?>
<div class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-metabox' ); ?>">

	<!-- First Frame Image -->
	<div class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-first-frame-img ' . $plugin_info['classes_prefix'] . '-metabox-section' ); ?>">
		<h6><?php esc_html_e( 'First Frame Image', 'wp-gif-editor' ); ?></h6>
		<small class="text-muted bg-light"><?php esc_html_e( 'This image will be used to avoid slow page load due to GIF big size, The GIF will be loaded after the page finish loading.', 'wp-gif-editor' ); ?></small>

		<div class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-first-frame-img-wrapper' ); ?>">
		</div>
		<button disabled class="button button-primary disabled"><?php esc_html_e( 'Create first frame image', 'wp-gif-editor' ); ?></button>
		<?php $core->pro_btn(); ?>
	</div>

	<hr/>

	<!-- Regenerate Subsizes -->
	<div class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-regenerate-subsizes ' . $plugin_info['classes_prefix'] . '-metabox-section' ); ?>">
		<h6><?php esc_html_e( 'Regenerate Subsizes', 'wp-gif-editor' ); ?></h6>
		<button disabled class="button button-primary disabled"><?php esc_html_e( 'Regenerate Subsizes', 'wp-gif-editor' ); ?></button>
		<?php $core->pro_btn(); ?>
		<span style="display:block;margin-top:10px;"><?php esc_html_e( 'Regenerate animted sub-sizes from the gif main image.', 'wp-gif-editor' ); ?></span>

	</div>
</div>
<?php
