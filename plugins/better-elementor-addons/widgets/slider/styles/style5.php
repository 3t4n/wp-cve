<div class="better-slider style-5 ctrl-one">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($settings['slider_list'] as $index => $item): ?> 
                <div class="swiper-slide">
                    <div class="item-img better-bg-img better-valign" data-background="<?php echo esc_url($item['image']['url']); ?>" data-overlay-dark="8">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-9 col-md-11">
                                    <div class="caption text-center bold">
                                        <h4 class="custom-font"><?php echo wp_kses_post($item['subtitle']); ?></h4>
                                        <<?php echo tag_escape($item['title_html_tag']); ?> class="title"><?php echo wp_kses_post($item['title']); ?></<?php echo tag_escape($item['title_html_tag']); ?>>
                                        <h6><?php echo wp_kses_post($item['text']); ?></h6>
                                        <?php if (!empty($item['btn_link']['url'])): ?>
                                        <a href="<?php echo esc_url($item['btn_link']['url']); ?>" class="better-btn-skew btn-bord mt-30">
                                            <span><?php echo esc_html($item['btn_text']); ?></span><i></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bord"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($settings['show_arrows'] == 'visible'): ?>
            <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl cursor-pointer">
                <span class="arrow prv pe-7s-angle-left"></span>
            </div>
            <div class="swiper-button-next swiper-nav-ctrl next-ctrl cursor-pointer">
                <span class="arrow nxt pe-7s-angle-right"></span>
            </div>
        <?php endif; ?>

        <?php if ($settings['show_dots'] == 'visible'): ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
</div>
