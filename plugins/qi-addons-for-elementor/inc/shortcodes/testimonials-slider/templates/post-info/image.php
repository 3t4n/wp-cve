<?php
$size = isset( $size ) ? $size : 'thumbnail';

if ( ! empty( $item_author_image ) ) { ?>
	<div class="qodef-e-media-image">
		<?php echo qi_addons_for_elementor_get_attachment_image( $item_author_image, $size ); ?>
	</div>
<?php } ?>
