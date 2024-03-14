<div class="better-slider style-11">
    <div class="swiper-container parallax-slider">
        <div class="swiper-wrapper">
            <?php foreach ($settings['slider_list'] as $index => $item): ?>
                <div class="swiper-slide">
                    <div class="better-bg-img better-valign" data-background="<?php echo esc_url($item['image']['url']); ?>" data-overlay-dark="<?php echo esc_attr($settings['slider_mask']); ?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="caption hmone">
                                        <<?php echo tag_escape($item['title_html_tag']); ?> class="title" data-splitting>
                                            <a href="<?php echo esc_url($item['title_link']['url']); ?>">
                                                <?php echo wp_kses_post($item['title']); ?>
                                            </a>
                                        </<?php echo tag_escape($item['title_html_tag']); ?>>
                                        <p class="mt-10"><?php echo wp_kses_post($item['text']); ?></p>
                                        <?php if (!empty($item['btn_link']['url'])): ?>
                                            <a href="<?php echo esc_url($item['btn_link']['url']); ?>" class="better-btn-architec btn-bord btn-lit mt-30">
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
        <?php if ($settings['show_arrows'] == 'visible'): ?>
            <div class="setone top">
                <div class="swiper-button-next swiper-nav-ctrl next-ctrl">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl">
                    <i class="fas fa-chevron-left"></i>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
