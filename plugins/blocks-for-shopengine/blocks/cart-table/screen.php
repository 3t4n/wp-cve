<?php
defined('ABSPATH') || exit;

if($block->is_editor) {

	wc()->frontend_includes();

	\Wpmet\Gutenova\Helper::add_product_in_cart_if_no_cart_found();
}

?>

<div class="shopengine shopengine-widget">
	<?php
	$file = empty(WC()->cart->cart_contents) ? '/empty.php' : '/cart.php';
	wc_get_template($file, ['settings' => $settings], __DIR__, __DIR__);
	?>
</div>
