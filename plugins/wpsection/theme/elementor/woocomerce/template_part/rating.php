
<?php if (isset($settings['show_product_x_rating']) && $settings['show_product_x_rating']) { ?>
    <?php if (!get_post_meta(get_the_id(), 'meta_show_rating', true)) : ?>
        <div class="wps_order order-<?php echo $settings['position_order_two']; ?>">
            <div class="mr_rating">
       


<?php if (isset($settings['show_rating'], $settings['product_avarage_rating_location']) && $settings['show_rating']) { ?>
    <div class="mr_rating_number <?php echo esc_attr($settings['product_avarage_rating_location']); ?>">
        <?php echo mr_product_rating(); ?>
    </div>
<?php } ?>


<?php if (isset($settings['show_avarage_rating'], $settings['product_avarage_rating_location']) && $settings['show_avarage_rating']) { ?>
    <?php if ($product->get_average_rating()) { ?>
        <span class="mr_review_number <?php echo esc_attr($settings['product_avarage_rating_location']); ?>"><?php echo esc_html($review_count_var); ?></span>
    <?php } ?>
<?php } ?>

            </div>
        </div>
    <?php endif; ?>
<?php } ?>

