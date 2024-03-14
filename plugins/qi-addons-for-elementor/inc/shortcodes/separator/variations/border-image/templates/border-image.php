<?php
$separator_image = isset( $separator_border_image ) ? 'background-image: url(' . qi_addons_for_elementor_get_attachment_image_url( $separator_border_image, 'full' ) . ')' : '';
?>
<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-line" <?php qi_addons_for_elementor_framework_inline_style( $separator_image ); ?>></div>
</div>
