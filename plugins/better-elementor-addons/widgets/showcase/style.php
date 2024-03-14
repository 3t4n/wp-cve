<div class="better-showcase style-0">
    <div class="swiper-container parallax-slider">
        <div class="swiper-wrapper">
            <?php foreach ($settings['showcase_list'] as $item) : ?>
                <div class="swiper-slide">
                    <div class="better-bg-img better-valign" data-background="<?php echo esc_url($item['image']['url']); ?>" data-overlay-dark="4">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="caption">
                                        <h1>
                                            <a href="<?php echo esc_url($item['link']['url']); ?>">
                                                <div class="stroke" data-swiper-parallax="-2000"><?php echo esc_html($item['title']); ?></div>
                                                <span data-swiper-parallax="-5000"><?php echo esc_html($item['subtitle']); ?></span>
                                            </a>
                                            <div class="bord"></div>
                                        </h1>
                                        <div class="discover">
                                            <a href="<?php echo esc_url($item['link']['url']); ?>"><span>Explore <br> More</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($settings['show_nav_btn'] === 'yes') : ?>
        <!-- Slider Navigation Buttons -->
        <div class="txt-botm">
            <div class="swiper-button-next swiper-nav-ctrl next-ctrl cursor-pointer">
                <div><span><?php echo esc_html($settings['nav_next']); ?></span></div>
                <div><i class="fas fa-chevron-right"></i></div>
            </div>
            <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl cursor-pointer">
                <div><i class="fas fa-chevron-left"></i></div>
                <div><span><?php echo esc_html($settings['nav_prev']); ?></span></div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($settings['show_dots'] === 'yes') : ?>
        <!-- Slider Dots -->
        <div class="swiper-pagination dots"></div>
        <?php endif; ?>
    </div>
</div>
