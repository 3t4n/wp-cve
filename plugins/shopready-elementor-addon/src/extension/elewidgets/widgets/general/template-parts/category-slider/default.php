<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Category Slider Layout One
 * @see https://docs.woocommerce.com/document/category_slider/
 * @author quomodosoft.com
 */


$wc_options = get_option('woocommerce_permalinks');


?>


<?php foreach ($settings['category_slider_items'] as $item):

    $category = get_term_by('slug', $item['product_category'], 'product_cat');
    $category_link = get_term_link($category);
    $category_name = $category->name;
    $category_icon = $item['category_slider_icon'] ? shop_ready_render_icons($item['category_slider_icon'], 'wready-icons') : null;
    $background_class = ($item['category_slider_img_or_icon_or_bg'] == 'background_image') ? esc_html__('woo-ready-category-slider-bg-image', 'shopready-elementor-addon') : null;
    $background_image_url = ($item['category_slider_bg_image'] != '') ? $item['category_slider_bg_image']['url'] : null;
    $background_image = ($item['category_slider_img_or_icon_or_bg'] == 'background_image') && ($item['category_slider_bg_image'] != '') ? 'style="background-image: url(' . $background_image_url . ')";' : null;

    ?>

    <div class="woo-ready-category-slider-item <?php echo esc_attr($background_class); ?>">
        <a href="<?php echo esc_url($category_link); ?>" <?php echo wp_kses_post($background_image); ?>>
            <?php if ($item['category_slider_img_or_icon_or_bg'] != 'background_image'): ?>
                <div class="category-slider-thumb">
                    <?php if ($item['category_slider_img_or_icon_or_bg'] == 'icon'):
                        echo wp_kses_post($category_icon);
                    elseif ($item['category_slider_img_or_icon_or_bg'] == 'img'): ?>
                        <img src="<?php echo esc_url($item['category_slider_image']['url']); ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="category-slider-content">
                <span>
                    <?php echo wp_kses_post($category_name); ?>
                </span>
            </div>
        </a>
    </div>

<?php endforeach; ?>