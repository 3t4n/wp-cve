<?php 
	global $c4d_plugin_manager;
	$removeIcon = isset($c4d_plugin_manager['c4d-woo-wishlist-remove-icon']) ? $c4d_plugin_manager['c4d-woo-wishlist-remove-icon'] : 'fa fa-trash-o';
?>
<div class="c4d-woo-wishlist-cart__list_header">
	<h3 class="title">
		<?php esc_html_e('Your Wishlist', 'c4d-woo-wishlist'); ?>
		<span class="count"> (<span class="number"><?php echo count(array_filter($current, 'strlen')); ?></span>) <?php esc_html_e('items', 'c4d-woo-wishlist'); ?></span>
	</h3>
</div>
<?php if (count($current) < 1) return; ?>
<ul class="c4d-woo-wishlist-cart__list_items">
<?php 
$args = array(
    'post_type' 		=> 'product',
    'orderby'   		=> 'date',
	'order'     		=> 'desc',
    'post_status'       => 'publish',
    'post__in' 			=> $current

);
$q = new WP_Query( $args );
if ($q->have_posts()) {
	while($q->have_posts()){
		$q->the_post();
		global $product;
		
			echo '<li class="item">';
			echo '<div class="image">';
			echo '<a href="'.esc_attr($product->get_permalink()).'">';
			echo woocommerce_get_product_thumbnail('thumbnail');
			echo '</a>';
			echo '</div>';

			echo '<div class="middle">';
			echo '<a href="'.esc_attr($product->get_permalink()).'"><h3 class="title">'.$product->get_title().'</h3></a>';
			echo '<div class="price">'. $product->get_price_html() . '</div>';
			woocommerce_template_loop_add_to_cart();
			echo '</div>';

			echo '<div class="right">';
			
			echo '<a data-id="'.esc_attr($product->get_id()).'" class="c4d-woo-wishlist-remove-item" href="#"><i class="'.esc_attr($removeIcon).'"></i></a>';
			echo '</div>';

			echo '</li>';
		
	}
}
woocommerce_reset_loop();
wp_reset_postdata();
?>
</ul>