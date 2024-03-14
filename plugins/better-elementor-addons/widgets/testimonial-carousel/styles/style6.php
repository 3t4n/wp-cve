<section class="better-testimonial style-6">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="quote-icon">
                    <img src="<?php echo esc_url( plugins_url('/assets/img/quote.svg', dirname(__FILE__, 3)) ); ?>" alt="">
                </div>
            </div>
            <div class="col-lg-7 better-valign">
                <div class="tistem better-full-width">
                    <?php foreach ( $settings['testi_list'] as $index => $item ) : ?>
                        <div class="item">
                            <div class="text-bg"><?php echo esc_html($item['number']); ?></div>
                            <p><?php echo esc_html($item['text']); ?></p>
                            <h6 class="gr-text"><?php echo esc_html($item['title']); ?></h6>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-2 better-valign">
                <div class="controls better-full-width">
                    <div class="float-right">
                        <span class="pe-7s-angle-left prev cursor-pointer"></span>
                        <span class="pe-7s-angle-right next cursor-pointer"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
