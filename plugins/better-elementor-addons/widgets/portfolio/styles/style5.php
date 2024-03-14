<div class="better-portfolio style-5">        
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($settings['portfolio_items'] as $index => $item) : ?>
                <div class="swiper-slide">
                    <div class="item">
                        <a href="<?php echo esc_url($item['item_image']['url']); ?>" class="popimg">
                            <div class="img">
                                <img src="<?php echo esc_url($item['item_image']['url']); ?>" alt="<?php echo esc_attr($item['item_title']); ?>">
                            </div>
                        </a>
                        <div class="cont">
                            <h6><?php echo esc_html($item['item_title']); ?></h6>
                            <span>
                                <a href="<?php echo esc_url($item['item_cat_link']['url']); ?>"><?php echo esc_html($item['item_cat']); ?></a>, 
                                <a href="<?php echo esc_url($item['item_cat_link_2']['url']); ?>"><?php echo esc_html($item['item_cat_2']); ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
