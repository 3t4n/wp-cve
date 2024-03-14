<div class="better-portfolio style-3 slider-scroll">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($settings['portfolio_items'] as $index => $item): ?>
                <div class="swiper-slide">
                    <div class="better-bg-img better-valign" data-background="<?php echo esc_url($item['item_image']['url']); ?>" data-overlay-dark="5">
                        <div class="caption">
                            <a href="<?php echo esc_url($item['item_cat_link']['url']); ?>"><span class="tag"><?php echo esc_html($item['item_cat']); ?></span></a>
                            <h1 data-splitting><a href="<?php echo esc_url($item['item_link']['url']); ?>"><?php echo esc_html($item['item_title']); ?></a></h1>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- slider setting -->
        <div class="swiper-button-next swiper-nav-ctrl next-ctrl">
            <i class="fas fa-caret-right"></i>
        </div>
        <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl">
            <i class="fas fa-caret-left"></i>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
