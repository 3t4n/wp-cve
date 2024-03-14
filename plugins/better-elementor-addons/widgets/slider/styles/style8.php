<div class="better-slider style-8" <?php echo 'data-slider-settings=\'{"speed":' . esc_attr($speed) . '}\'>'; ?>>
    <div class="swiper-container parallax-slider">
        <div class="swiper-wrapper">

            <?php foreach ($settings['slider_list'] as $index => $item): ?>
                <div class="swiper-slide">
                    <div class="better-bg-img better-valign" data-background="<?php echo esc_url($item['image']['url']); ?>" data-overlay-dark="<?php echo esc_attr($settings['slider_mask']);?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="caption hmone">
                                        <<?php echo tag_escape($item['title_html_tag']); ?> class="title" data-splitting>
                                            <a href="<?php echo esc_url($item['btn_link']['url']); ?>"><?php echo wp_kses_post($item['title']); ?></a>
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

        <!-- slider setting -->
        <?php if ($settings['show_arrows'] == 'visible'): ?>
            <div class="setone">
                <div class="swiper-button-next swiper-nav-ctrl next-ctrl">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl">
                    <i class="fas fa-chevron-left"></i>
                </div>
            </div>
        <?php endif; ?>

        <div class="side">
            <?php if (!empty($settings['logo_image']['url'])): ?>
                <div class="logo-icon">
                    <img src="<?php echo esc_url($settings['logo_image']['url']); ?>" alt="logo">
                </div>
            <?php endif; ?>

            <?php if ($settings['show_paging'] == 'yes'): ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>

            <div class="social">
                <?php foreach ($settings['style8_social_links_list'] as $index => $item): ?>
                    <a href="<?php echo esc_url($item['style8_social_btn_link']['url']); ?>"><i class="<?php echo esc_attr($item['style8_social_btn_icon']['value']); ?>"></i></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
