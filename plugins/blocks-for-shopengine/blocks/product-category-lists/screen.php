<?php

defined('ABSPATH') || exit;

$shopengine_product_cat_lists_cats           = $settings["shopengine_category_list"]["desktop"];
$image_url                                   = '';
$category_ids                                = (isset($shopengine_product_cat_lists_cats) && !empty($shopengine_product_cat_lists_cats)) ? $shopengine_product_cat_lists_cats : [];
$shopengine_product_cat_lists_show_cat_image = $settings["shopengine_show_category_image"]["desktop"] == 1 ? 'yes' : 'no';
$shopengine_product_cat_lists_show_count     = $settings["shopengine_show_product_count"]["desktop"] == 1 ? 'yes' : 'no';
$shopengine_product_cat_lists_show_icon      = $settings["shopengine_show_icon"]["desktop"] == 1 ? 'yes' : 'no';
$shopengine_product_cat_lists_icon           = ["value"   => explode(" ", $settings["shopengine_choose_icon"]["desktop"])[1], "library" => explode(" ", $settings["shopengine_choose_icon"]["desktop"])[0]];
$category_lists = $settings['category_lists'];
require_once(__DIR__ . '/helper.php');
?>
<div class="shopengine shopengine-widget">

    <div class="shopengine-product-category-lists">

        <?php if ($category_ids || !empty($category_lists)) : ?>

            <div class="shopengine-category-lists-grid">

                <?php if ($settings['shopengine_product_cat_list_styles']['desktop'] == 'normal' && !empty($category_ids)) : ?>
                    <?php foreach ($category_ids as $key => $category_id) :
                        $term = get_term($category_id["id"], 'product_cat');
                        $thumbnail_id = get_term_meta($category_id["id"], 'thumbnail_id', true);
                        if ($thumbnail_id && isset($shopengine_product_cat_lists_show_cat_image) && $shopengine_product_cat_lists_show_cat_image === 'yes') {
                            $image_url = wp_get_attachment_url($thumbnail_id);
                        }

                        if (!empty($term)) : ?>

                            <div class="single-cat-list-item" style="background-image: url(' <?php echo esc_attr(esc_url($image_url)) ?> ')">
                                <div class="product-category-wrap">
                                    <div class="single-product-category">
                                        <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                            <h3 class="product-category-title">
                                                <?php echo esc_html($term->name); ?>
                                            </h3>
                                            <?php if (isset($shopengine_product_cat_lists_show_count) && $shopengine_product_cat_lists_show_count == 'yes') : ?>
                                                <p class="cat-count">
                                                    <?php 
                                                    // Translators: %s represents the number of products in the category.
                                                    echo esc_html(sprintf(_n('%s product', '%s products', $term->count, 'shopengine-gutenberg-addon'), $term->count)); ?>
                                                </p>
                                            <?php endif;

                                            if (isset($shopengine_product_cat_lists_show_icon) && $shopengine_product_cat_lists_show_icon == 'yes') : ?>
                                                <span class="cat-icon">
                                                    <span class="<?php echo esc_attr($shopengine_product_cat_lists_icon["library"] . " " . esc_attr($shopengine_product_cat_lists_icon["value"])); ?>"></span>
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                        <!-- dot shap -->
                                    </div>
                                </div>
                            </div>

                    <?php endif;

                    endforeach; ?>
                <?php elseif ($settings['shopengine_product_cat_list_styles']['desktop'] == 'style_2') : ?>
                    <?php
                    if (!empty($category_lists)) : foreach ($category_lists as $key => $content) :
                            $terms = isset($content) ? $content['shopengine_product_cat_list']['desktop'] : [];
                            $term_id = !empty($terms) ? $terms['id'] : 1;
                            $cat_name = !empty($terms) ? $terms['text'] : '';
                            $cat_link = get_term_link($term_id);
                            if ($cat_name) : ?>
                                <a href="<?php echo esc_url($cat_link); ?>" class="shopengine-category-items">
                                    <div class="shopengine-category-icon">
                                        <?php render_icon($content['shopengine_product_cat_lists_icons']['desktop'], ['aria-hidden' => 'true']); ?>
                                    </div>
                                    <h4 class="product-category-list-title"><?php echo esc_html($cat_name); ?></h4>
                                </a>
                    <?php endif; endforeach; endif; ?>
                <?php endif; ?>

            </div>

        <?php else :

            esc_html_e('Add some category', 'shopengine-gutenberg-addon');

        endif; ?>

    </div>

</div>