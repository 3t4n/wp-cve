<?php 
defined( 'ABSPATH' ) || exit;

global $product;
global $ewd_uwcf_controller;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?>
<tr <?php wc_product_class( '', $product ); ?>>
	<?php $ewd_uwcf_controller->wc_table->print_product_content(); ?>
</tr>
