<div class="better-price style-2">
    <div class="item text-center wow fadeInUp md-mb50" data-wow-delay=".3s">
        <div class="type">
            <h5 class="custom-font"><?php echo esc_html($settings['better_price_box_title']); ?></h5>
        </div>
        <div class="amount">
            <h2><span>$</span> <?php echo esc_html($settings['better_price_box_amount']); ?> <i>/ <?php echo esc_html($settings['better_price_box_plan']); ?></i></h2>
        </div>
        <div>
            <p><?php echo wp_kses_post($settings['better_price2_box_features']); ?></p>
        </div>
        <div class="order">
            <a href="<?php echo esc_url($settings['better_price_box_button_link']['url']); ?>" class="better-btn-curve btn-lit <?php echo 'yes' === $settings['better_popular_plan'] ? 'btn-wit' : ''; ?>"><span><?php echo esc_html($settings['better_price_box_button_text']); ?></span></a>
        </div>
    </div>
</div>
