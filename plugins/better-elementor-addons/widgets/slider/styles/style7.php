<div class="better-slider style-7" <?php echo 'data-slider-settings=\'{"speed":' . esc_attr($speed) . '}\'>'; ?>>
    <div class="swiper-container parallax-slider">
        <div class="swiper-wrapper">
            <?php foreach ($settings['slider_list'] as $index => $item): ?>
                <div class="swiper-slide">
                    <div class="better-bg-img better-valign" data-background="<?php echo esc_url($item['image']['url']); ?>" data-overlay-dark="<?php echo esc_attr($settings['slider_mask']);?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7 col-md-9">
                                    <div class="caption center">
                                        <<?php echo tag_escape($item['title_html_tag']); ?> class="title" data-splitting><?php echo wp_kses_post($item['title']); ?></<?php echo tag_escape($item['title_html_tag']); ?>>
                                        <p><?php echo wp_kses_post($item['text']); ?></p>
                                        <?php if (!empty($item['btn_link']['url'])): ?>
                                        <a href="<?php echo esc_url($item['btn_link']['url']); ?>" class="better-btn-curve btn-lit mt-30">
                                            <span><?php echo esc_html($item['btn_text']); ?></span>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- slider setting -->
        <div class="setone setwo">
            <div class="swiper-button-next swiper-nav-ctrl next-ctrl cursor-pointer">
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl cursor-pointer">
                <i class="fas fa-chevron-left"></i>
            </div>
        </div>
        <div class="swiper-pagination top botm custom-font"></div>

        <div class="social-icon">
            <?php foreach ($settings['style8_social_links_list'] as $index => $item): ?>
            <a href="<?php echo esc_url($item['style8_social_btn_link']['url']); ?>"><i class="<?php echo esc_attr($item['style8_social_btn_icon']['value']); ?>"></i></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
