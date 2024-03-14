<?php
$image_proportion = isset( $image_proportion ) ? $image_proportion : 'full';

if ( ! empty( $image ) ) {
	?>
	<div class="qodef-m-media-image">
		<?php echo qi_addons_for_elementor_get_attachment_image( $image, $image_proportion ); ?>
	</div>
<?php } ?>
