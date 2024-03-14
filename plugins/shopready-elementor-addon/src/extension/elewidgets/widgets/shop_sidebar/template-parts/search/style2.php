<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Product Search
 * Category Search
 * Layout Two
 * @since 1.0
 */

if ($settings['redirect_to_shop'] == 'yes') {
    $shop_page_url = get_permalink(wc_get_page_id('shop'));
} else {
    $shop_page_url = $obj->get_current_page_url();
}

$category_list = $settings['category_list'];
$result_list = get_terms(
    array(
        'taxonomy' => 'product_cat',
        'include' => $category_list,
        'hide_empty' => false,
        'orderby' => 'include',
    )
);

?>

<div class="wooready_search_layout_2">
    <form role="search" method="get" action="<?php echo esc_url($shop_page_url); ?>">
        <div class="wooready_input_wrapper ">
            <div class="wooready_input_box flex-basis:80 position:relative">
                <input type="text" autocomplete="off"
                    placeholder="<?php echo esc_html($settings['search_palceholder']); ?>"
                    value="<?php echo esc_attr(get_search_query()); ?>" name="s"
                    title="<?php echo esc_attr_x('Search for:', 'label', 'shopready-elementor-addon'); ?>" />
                <input type="hidden" name="post_type" value="product" />
                <div class="wooready_nice_select display:flex">
                    <?php if ($settings['category'] == 'yes') { ?>
                        <select name="wr-category">
                            <option data-display="<?php echo wp_kses_post($settings['all_cats']); ?>">
                                <?php echo wp_kses_post($settings['all_cats']) ?>
                            </option>
                            <?php foreach ($result_list as $item): ?>
                                <option <?php selected($selected, $item->slug) ?> value="<?php echo esc_attr($item->slug); ?>">
                                    <?php echo esc_html($item->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php } ?>
                    <button>
                        <?php echo wp_kses_post($search_icon ? $search_icon : ''); ?>
                        <?php echo esc_html($settings['search_button_label'] ? $settings['search_button_label'] : ''); ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <?php if ($settings['auto_complate'] == 'yes'): ?>
        <?php do_action('shop_ready_search_widget_auto_complete'); ?>
        <div class="shopready_search_auto_complate_box">
        </div>
    <?php endif; ?>
</div>