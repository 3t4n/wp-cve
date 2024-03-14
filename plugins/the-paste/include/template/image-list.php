<?php

if ( ! defined('ABSPATH') ) {
	die();
}

?>
<script type="text/html" id="tmpl-the-paste-image-list">
	<div class="media-frame-title">
		<h1><?php esc_html_e('Upload Pasted Images','the-paste'); ?></h1>
	</div>
	<div class="content"></div>
	<div class="media-frame-toolbar">
		<button type="button" class="button-primary button-hero">
			<span aria-hidden="true" class="dashicons dashicons-yes"></span>
			<?php esc_html_e( 'Upload', 'the-paste' ); ?>
		</button>
	</div>
</script>
<script type="text/html" id="tmpl-the-paste-image-list-item">
	<canvas></canvas>
	<div class="the-paste-toolbar">
		<label class="the-paste-filename">
			<?php esc_html_e( 'Filename', 'the-paste' );  ?>
			<input type="text" name="the-paste-filename" value="<?php esc_attr_e( 'Pasted', 'the-paste' ); ?>" placeholder="" />
		</label>
		<div class="the-paste-format">
			<label data-format="image/webp">
				<input type="radio" name="the-paste-format" value="image/webp">
				<?php _e( 'WebP', 'the-paste' ); ?>
			</label>
			<label data-format="image/png">
				<input type="radio" name="the-paste-format" value="image/png">
				<?php _e( 'PNG', 'the-paste' ); ?>
			</label>
			<label data-format="image/jpeg">
				<input type="radio" name="the-paste-format" value="image/jpeg">
				<?php _e( 'jpeg', 'the-paste' ); ?>
			</label>
			<label data-format="image/svg+xml">
				<input type="radio" name="the-paste-format" value="image/svg+xml">
				<?php _e( 'SVG', 'the-paste' ); ?>
			</label>
		</div>
		<label class="the-paste-quality">
			<span><?php esc_html_e( 'Image Quality', 'the-paste' ); ?></span>
			<input type="range" min="0" max="100" value="{{{ thepaste.options.jpeg_quality }}}" oninput="this.nextElementSibling.value = this.value" />
			<input type="number" min="0" max="100" name="the-paste-quality" value="{{{ thepaste.options.jpeg_quality }}}"  oninput="this.previousElementSibling.value = this.value">
		</label>
		<button type="button" name="discard" class="button-link-delete button">
			<span aria-hidden="true" class="dashicons dashicons-trash"></span>
			<?php esc_html_e('Discard','the-paste') ?>
		</button>
	</div>
</script>
