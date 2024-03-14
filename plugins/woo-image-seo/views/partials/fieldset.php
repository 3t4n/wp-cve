<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// provided when rendering this partial
if ( empty( $type ) || empty( $settings ) ) {
    return;
}

?>
<fieldset>
	<legend>
		<?php _e( ucfirst( $type ) . ' attributes' ) ?>
	</legend>

	<?php woo_image_seo_render_form_row( 'enable', $type, $settings ) ?>
	
	<?php woo_image_seo_render_form_row( 'force', $type, $settings ) ?>

	<?php woo_image_seo_render_form_row( 'attribute-builder', $type, $settings ) ?>

	<?php woo_image_seo_render_form_row( 'custom-texts', $type, $settings ) ?>

	<?php woo_image_seo_render_form_row( 'count', $type, $settings ) ?>
</fieldset>