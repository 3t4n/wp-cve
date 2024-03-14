<?php

defined('ABSPATH') || exit;

    define('SELECTOR_PREFIX', '.shopengine-archive-products.shopengine-archive-products--view-list ');
?>
<Style>
    <?php echo esc_attr(SELECTOR_PREFIX);?> .shopengine-archive-mode-grid {
        column-gap: <?php echo esc_attr( $settings['shopengine_image_gap_from_content']['desktop']);?>px;
    }
    <?php echo esc_attr(SELECTOR_PREFIX);?> .shopengine-archive-products__left-image img {
        width: <?php echo esc_attr($settings['shopengine_archive_products_image_width']['desktop']);?>px;
        height: <?php echo esc_attr($settings['shopengine_archive_products_image_height']['desktop']);?>px;
        object-fit: <?php echo esc_attr($settings['shopengine_archive_products_image_fit']['desktop']);?>;
        object-position: <?php echo esc_attr($settings['shopengine_image_view_position']['desktop']);?>;
    }
    <?php echo esc_attr(SELECTOR_PREFIX);?> .product .loop-product--btns .loop-product--btns-inner {
        margin-top: <?php echo esc_attr($settings['shopengine_archive_products_content_gap']['desktop']);?>px;
    }
    <?php echo esc_attr(SELECTOR_PREFIX);?> .product .woocommerce-LoopProduct-link .woocommerce-loop-product__title {
        font-size: <?php echo esc_attr( $settings['shopengine_product_title_font_size']['desktop']);?>px;
        font-weight: <?php echo esc_attr( $settings['shopengine_product_title_font_weight']['desktop']);?>;
        text-transform: <?php echo esc_attr( $settings['shopengine_product_title_text_transform']['desktop']);?>;
        line-height: <?php echo esc_attr( $settings['shopengine_product_title_line_height']['desktop']);?>px;
        letter-spacing: <?php echo esc_attr( $settings['shopengine_product_title_letter_spacing']['desktop']);?>px;
        word-spacing: <?php echo esc_attr( $settings['shopengine_product_title_wordspace']['desktop']);?>px;
        color: <?php echo esc_attr( $settings['shopengine_archive_product_title_color']['desktop']);?>;
    }
</Style>

<div class="shopengine shopengine-widget ">
    <div class="shopengine-archive-view-mode ">
        <div class="shopengine-archive-view-mode-switch-list ">
            <button title="<?php esc_attr_e('Gird View', 'shopengine-gutenberg-addon'); ?>" class="shopengine-archive-view-mode-switch grid-four isactive" data-view="grid">
                <i aria-hidden="true" class="<?php echo esc_attr( $settings["shopengine_archive_view_mode_four_grid_icon"]["desktop"]) ? 
                    esc_attr($settings["shopengine_archive_view_mode_four_grid_icon"]["desktop"]) : "fas fa-th-large"; ?>"
                ></i>
            </button>
            <button title="<?php esc_attr_e('Gird 3 Columns View', 'shopengine-gutenberg-addon'); ?>" class="shopengine-archive-view-mode-switch grid-three" data-view="grid-3">
                <i aria-hidden="true" class="<?php echo esc_attr( $settings["shopengine_archive_view_mode_three_grid_icon"]["desktop"]) ? 
                    esc_attr($settings["shopengine_archive_view_mode_three_grid_icon"]["desktop"]) : "fas fa-th fa-rotate-90"; ?>"
                ></i>
            </button>
            <button title="<?php esc_attr_e('Gird 2 Columns View','shopengine-gutenberg-addon'); ?>" class="shopengine-archive-view-mode-switch grid-two" data-view="grid-2">
                <i aria-hidden="true" class="<?php echo esc_attr( $settings["shopengine_archive_view_mode_two_grid_icon"]["desktop"]) ? 
                    esc_attr($settings["shopengine_archive_view_mode_two_grid_icon"]["desktop"]) : "fas fa-grip-vertical"; ?>"
                ></i>
            </button>
            <button title="<?php esc_attr_e('List View','shopengine-gutenberg-addon'); ?>" class="shopengine-archive-view-mode-switch grid-list" data-view="list">
                <i aria-hidden="true" class="<?php echo esc_attr( $settings["shopengine_archive_view_mode_list_grid_icon"]["desktop"]) ? 
                    esc_attr($settings["shopengine_archive_view_mode_list_grid_icon"]["desktop"]) : "fas fa-th-list"; ?>"
                ></i>
            </button> 
        </div>
    </div>
</div>