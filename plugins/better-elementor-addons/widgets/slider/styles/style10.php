<div class="better-slider style-10 better-valign better-bg-img" data-background="<?php echo esc_url($settings['bg_image']['url']); ?>" data-overlay-dark="<?php echo esc_attr($settings['slider_mask']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="caption hmone">
                    <h1 data-splitting>
                        <a href="<?php echo esc_url($settings['slider5_btn_link']['url']); ?>">
                            <?php echo wp_kses_post($settings['slider5_title1']); ?>
                        </a>
                        <div class="bord"></div>
                    </h1>
                    <p class="mt-10"><?php echo wp_kses_post($settings['slider5_text']); ?></p>
                    <?php if (!empty($settings['slider5_btn_link']['url'])): ?>
                        <a href="<?php echo esc_url($settings['slider5_btn_link']['url']); ?>" class="better-btn-architec btn-bord btn-lit mt-30">
                            <span><?php echo esc_html($settings['slider5_btn_text']); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
