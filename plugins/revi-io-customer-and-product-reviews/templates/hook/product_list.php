<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!empty($product_info) && $product_info->num_ratings > 0 && !empty($product_info->avg_rating)) : ?>

    <?php
    $stars = '';

    for ($i = 1; $i <= 5; $i++) {
        switch ($product_info->avg_rating) {
            case ($product_info->avg_rating >= $i):
                $stars .= '<span class="ministar ministar_active5"></span>';
                break;
            case ($product_info->avg_rating >= (($i - 1) + 0.75)):
                $stars .= '<span class="ministar ministar_active4"></span>';
                break;
            case ($product_info->avg_rating >= (($i - 1) + 0.5)):
                $stars .= '<span class="ministar ministar_active3"></span>';
                break;
            case ($product_info->avg_rating >= (($i - 1) + 0.25)):
                $stars .= '<span class="ministar ministar_active2"></span>';
                break;
            case ($product_info->avg_rating < $i):
                $stars .= '<span class="ministar ministar_active1"></span>';
                break;
            default:
                $stars .= '<span class="ministar ministar_active1"></span>';
                break;
        }
    }
    ?>

    <div class="list_revi_stars_container">
        <?= $stars ?>
        <?php if (!empty($REVI_DISPLAY_PRODUCT_LIST_TEXT)) : ?>
            <small class="revi_num_reviews"><?= $product_info->num_ratings ?> <?= ($product_info->num_ratings > 1) ? esc_html_e('reviews', 'revi-io-customer-and-product-reviews') : esc_html_e('review', 'revi-io-customer-and-product-reviews') ?></small>
        <?php endif; ?>
    </div>

<?php elseif (!empty($REVI_DISPLAY_PRODUCT_LIST_EMPTY)) : ?>

    <div class="list_revi_stars_container">
        <span class="ministar ministar_active1"></span>
        <span class="ministar ministar_active1"></span>
        <span class="ministar ministar_active1"></span>
        <span class="ministar ministar_active1"></span>
        <span class="ministar ministar_active1"></span>
        <?php if (!empty($REVI_DISPLAY_PRODUCT_LIST_TEXT)) : ?>
            <small class="revi_num_reviews"> 0 <?= esc_html_e('reviews', 'revi-io-customer-and-product-reviews') ?></small>
        <?php endif; ?>
    </div>


<?php endif; ?>