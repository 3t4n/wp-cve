<div class="better-price style-3">
    <div class="item <?php echo esc_attr($settings['better_popular_plan'] === 'yes' ? 'active' : ''); ?>">
        <div class="title">
            <h5><?php echo esc_html($settings['better_price_box_title']); ?></h5>
        </div>
        <div class="amount">
            <h2><span <?php echo esc_attr($settings['better_price_mode_style'] === '2' ? 'class="float"' : ''); ?>>$</span> <?php echo esc_html($settings['better_price_box_amount']); ?></h2>
            <h6><?php echo esc_html($settings['better_price_box_plan']); ?></h6>
        </div>
        <div class="cont <?php echo esc_attr($settings['better_price_mode_style'] === '2' ? 'pricing-mode-2' : ''); ?>">
            <?php echo wp_kses_post($settings['better_price2_box_features']); ?>
        </div>
        <div class="order">
            <?php if ($settings['better_price_mode_style'] === '1') : ?>
                <a href="<?php echo esc_url($settings['better_price_box_button_link']['url']); ?>" class="better-btn-pricing"><?php echo esc_html($settings['better_price_box_button_text']); ?></a>
            <?php endif; ?>
            <?php if ($settings['better_price_mode_style'] === '2') : ?>
                <a href="<?php echo esc_url($settings['better_price_box_button_link']['url']); ?>" class="better-btn-architec"><?php echo esc_html($settings['better_price_box_button_text']); ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>
