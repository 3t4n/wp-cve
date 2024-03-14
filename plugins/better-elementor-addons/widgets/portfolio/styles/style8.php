<div class="better-portfolio style-8">
    <div class="section-head">
        <h3><?php echo esc_html($settings['port_title']); ?></h3>
    </div>
    <div class="swiper-container work-curs">
        <div class="swiper-wrapper">
            <?php foreach ($settings['portfolio_items'] as $index => $item) : ?>
                <div class="swiper-slide">
                    <div class="item">
                        <div class="img">
                            <img src="<?php echo esc_url($item['item_image']['url']); ?>" alt="<?php echo esc_attr($item['item_title']); ?>">
                        </div>
                        <div class="cont">
                            <h5><a href="<?php echo esc_url($item['item_link']['url']); ?>"><?php echo esc_html($item['item_title']); ?></a></h5>
                            <span><a href="<?php echo esc_url($item['item_cat_link']['url']); ?>"><?php echo esc_html($item['item_cat']); ?></a></span><span><a href="<?php echo esc_url($item['item_cat_link_2']['url']); ?>"><?php echo esc_html($item['item_cat_2']); ?></a></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="controls">
        <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="swiper-button-next swiper-nav-ctrl next-ctrl">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</div>
