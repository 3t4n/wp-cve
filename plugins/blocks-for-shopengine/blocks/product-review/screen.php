<?php
defined('ABSPATH') || exit;

$post_type = get_post_type();

$product = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());

$in_editor_mode = $block->is_editor;

?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-product-review">
		<?php

		$open = comments_open();

		if($in_editor_mode) {

			$open = true;

			global $post;

			$main_post = clone $post;

			$post = get_post($product->get_id());

			include __DIR__ . '/dummy-review.php';
		}

		if($open && !$in_editor_mode) {
			comments_template();
		}

		if($in_editor_mode) {

			global $post;

			$post = $main_post;
		}

		?>
    </div>
</div>
