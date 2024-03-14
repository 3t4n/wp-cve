<?php
defined('ABSPATH') || exit;
use ShopEngine\Utils\Helper; 
?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-product-description">
		<?php
		// Show only in the Editor and Preview Mode.

		if($block->is_editor):

			$product = ShopEngine\Widgets\Products::instance()->get_a_simple_product();

			echo wp_kses(\ShopEngine\Utils\Helper::render($product->get_description()), Helper::get_kses_array());
		else:
			global $post;
			$content = $post->post_content;
			//$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			echo wp_kses($content, Helper::get_kses_array());
		endif;
		?>
    </div>
</div>
