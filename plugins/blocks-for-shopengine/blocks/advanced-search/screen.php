<?php

defined('ABSPATH') || exit;

require_once('helper.php');
$post_type = get_post_type();
$random_id = uniqid();
if (!empty($settings['shopengine_custom_ordering_list']['desktop'])) {
    generate_order_item_css($settings['shopengine_custom_ordering_list']['desktop'], $random_id);
}
$cats = ShopEngine\Utils\Helper::category_list_by_taxonomy('product_cat');

$category_id = '';

if(is_archive() && !is_shop() && !is_product_tag()) {
	$category    = get_queried_object();
	$category_id = isset($category->term_id) ? $category->term_id : '';
}
?>
<div class="gutenova-element-<?php echo esc_attr($random_id); ?>">
    <div class="shopengine shopengine-widget">
        <div class="shopengine-advanced-search">
            <form method="GET" style="position:relative;"
                action="<?php echo esc_url(get_rest_url(null, 'shopengine/v1/advanced-search')); ?>/"
                class="shopengine-search-form">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>"/>
                <input type="hidden" name="post_type" value="product"/>

                <div class="search-input-group">

                    <!-- search button -->
                    <button type="submit" class="search-btn">
                        <i class="<?php echo esc_attr($settings["shopengine_search_icon"]["desktop"]); ?>"></i>
                        <?php if(!empty($settings['shopengine_search_text'])) : ?>
                            <span class="shopengine-search-text"><?php echo esc_html($settings['shopengine_search_text']['desktop']); ?></span>
                        <?php endif; ?>
                    </button>
                    <!-- search input -->
                    <input type="search" name="s" class="shopengine-advanced-search-input"
                        placeholder="<?php esc_attr_e('Search for Products...', 'shopengine-gutenberg-addon'); ?>">

                    <!-- search category -->
                    <div class="shopengine-category-select-wraper">
                        <select class="shopengine-ele-nav-search-select" name="product_cat">
                            <option value=""><?php echo !empty($settings['shopengine_all_cat_input']) ? esc_html($settings['shopengine_all_cat_input']['desktop']) : ''; ?></option>
                            <?php if(is_array($cats) && !empty($cats)): ?>
                                <?php foreach($cats as $cat) { ?>
                                    <option
                                        <?php selected($category_id, $cat->term_id); ?>
                                            class="<?php echo esc_attr($cat->category_parent !== 0 ? 'child-category' : '') ?>"
                                            value="<?php echo esc_attr($cat->term_id); ?>">
                                        <?php echo esc_html($cat->name); ?>
                                    </option>
                                <?php } ?>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>

                <div class="shopengine-search-result-container">
                    <div class="shopengine-search-result">

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>