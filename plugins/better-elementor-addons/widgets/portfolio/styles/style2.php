<div class="better-portfolio style-2 metro position-re light">
    <div class="row">
        <div class="col-lg-12 no-padding">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php $count = 0;
                    foreach ($settings['portfolio_items'] as $index => $item) : 
                        $count++; ?>
                        <div class="swiper-slide">
                            <div class="content wow fadeInUp" data-wow-delay=".3s">
                                <div class="item-img better-bg-img wow imago"
                                     data-background="<?php echo esc_url($item['item_image']['url']); ?>">
                                </div>
                                <div class="cont">
                                    <h6><a href="<?php echo esc_url($item['item_cat_link']['url']); ?>"><?php echo esc_html($item['item_cat']); ?></a></h6>
                                    <h4><a href="<?php echo esc_url($item['item_link']['url']); ?>"><?php echo esc_html($item['item_title']); ?></a></h4> 
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl cursor-pointer">
                    <span class="arrow prv">Prev</span>
                </div>
                <div class="swiper-button-next swiper-nav-ctrl next-ctrl cursor-pointer">
                    <span class="arrow nxt">Next</span>
                </div>

                <div class="set-control">
                    <div class="swiper-pagination"></div>
                    <div class="activeslide custom-font">1</div>
                    <div class="totalslide custom-font"><?php echo esc_html($count); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
