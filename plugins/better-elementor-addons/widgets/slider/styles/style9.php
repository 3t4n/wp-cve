<div class="better-slider style-9">
    <div class="cta__slider-wrapper nofull swiper-container">
        <div class="swiper-wrapper cta__slider">
            <?php foreach ($settings['slider_list'] as $index => $item): ?>
                <div class="cta__slider-item swiper-slide">
                    <div class="media-wrapper slide-inner better-valign">
                        <div class="better-bg-img" data-background="<?php echo esc_url($item['image']['url']); ?>" data-overlay-dark="<?php echo esc_attr($settings['slider_mask']);?>"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="caption">
                                        <div class="custom">
                                            <h5 class="thin subtitle"><?php echo wp_kses_post($item['subtitle']); ?></h5>
                                            <<?php echo tag_escape($item['title_html_tag']); ?> class="title" data-splitting><?php echo wp_kses_post($item['title']); ?></<?php echo tag_escape($item['title_html_tag']); ?>>
                                        </div>
                                        <p class="text"><?php echo wp_kses_post($item['text']); ?></p>
                                        <?php if (!empty($item['btn_link']['url'])): ?>
                                        <a href="<?php echo esc_url($item['btn_link']['url']); ?>" class="better-btn-architec btn-color mt-30 button">
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
            <div class="cta__slider-arrows">
                <i id="better-slider9-next" class="cta__slider-arrow cta__slider-arrow--next">
                    <i class="fas fa-chevron-up"></i>
                </i>
                <i id="better-slider9-previous" class="cta__slider-arrow cta__slider-arrow--previous">
                    <i class="fas fa-chevron-down"></i>
                </i>
            </div>
        <?php endif; ?>
    </div>
</div>
