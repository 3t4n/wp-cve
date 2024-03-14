<?php
defined('ABSPATH') || exit;

$post_type = get_post_type();
$product   = \ShopEngine\Widgets\Products::instance()->get_product($post_type);
?>

<div class="shopengine-product-meta">
	<?php woocommerce_template_single_meta(); ?>
</div>
