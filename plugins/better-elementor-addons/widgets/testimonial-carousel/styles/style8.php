<div class="better-testimonial style-8">
    <div class="row">
        <div class="col-lg-12">
            <div class="testim">
                <?php foreach ( $settings['testi_list'] as $index => $item ) : ?>
                    <div class="item wow fadeInUp" data-wow-delay=".3s">
                        <span class="quote-icon"><img src="<?php echo esc_url( plugins_url('/assets/img/quote.svg', dirname(__FILE__,3)) ); ?>" alt=""></span>
                        <div class="cont">
                            <p><?php echo esc_html($item['text']); ?></p>
                        </div>
                        <div class="info">
                            <div class="author">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="">
                            </div>
                            <h6><?php echo esc_html($item['title']); ?> <span><?php echo esc_html($item['position']); ?></span> </h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
