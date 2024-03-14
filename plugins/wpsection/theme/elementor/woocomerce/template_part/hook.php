    <?php
    /**
    * Hook: woocommerce_before_shop_loop_item.
    */
    do_action('woocommerce_before_shop_loop_item');
    $get_price = $product->get_price();
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $price_html = $product->get_price_html();
    $review_count = $product->get_review_count();


$review_count_var = '';

if (isset($settings['review_text'])) {
    $review_count_var = is_numeric($review_count) ? "($review_count) " . $settings['review_text'] : $review_count . $settings['review_text'];
} else {
    $review_count_var = is_numeric($review_count) ? "($review_count)" : $review_count;
}




    $newness_days = 30; // Number of days the badge is shown
    $created = strtotime($product->get_date_created());
    $stock_quantity = $product->get_stock_quantity();
    $sale_stock_quantity = get_post_meta($product->get_id(), 'total_sales', true);
    $thumbnail_id = get_post_thumbnail_id();
    $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'woocommerce_thumbnail');
    $post_thumbnail_id = get_post_thumbnail_id($post->ID);
    $post_thumbnail_url = wp_get_attachment_url($post_thumbnail_id);
    //Code for Catagories
    $product_categories = get_the_terms(get_the_ID(), 'product_cat');
    // Check if product has categories
    if ($product_categories && !is_wp_error($product_categories)) {
    $thumbnail_id = get_term_meta($product_categories[0]->term_id, 'thumbnail_id', true);
    $thumbnail_url = wp_get_attachment_url($thumbnail_id);
    $category_name = $product_categories[0]->name;
    $category_link = get_term_link($product_categories[0]);
    $category_count = $product_categories[0]->count;
    }
    $thumbnail_style = get_post_meta(get_the_ID(), 'thumbnail_style', true);  
    $repeater_images = get_post_meta(get_the_ID(), 'repeater_images', true);
    $repet_image_title = get_post_meta(get_the_ID(), 'repet_image_title', true);                                
    $meta_image = get_post_meta(get_the_ID(), 'meta_image', true);
    $meta_image_two = get_post_meta(get_the_ID(), 'meta_image_two', true);

  ?>
