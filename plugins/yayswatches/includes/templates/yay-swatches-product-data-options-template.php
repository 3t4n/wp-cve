<?php defined( 'ABSPATH' ) || exit; ?>
<div class="yay-swatches-templates-container">
<div class="yay-swatches-product-options-content yay-swatches-metaboxes wc-metaboxes">
	<?php require_once 'yay-swatches-product-attribute-template.php'; ?>
	<div class="toolbar">
		<button type="button" class="button button-primary yay-swatches-btn-save-product-attributes"><?php esc_html_e( 'Save swatches settings', 'yay-swatches' ); ?></button>
		<button type="button" class="button yay-swatches-btn-reset-product-attributes"><?php esc_html_e( 'Reset to default', 'yay-swatches' ); ?></button>
	</div>
</div>
</div>
