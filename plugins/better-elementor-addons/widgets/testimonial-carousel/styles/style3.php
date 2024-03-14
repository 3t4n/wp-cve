<div class="better-testimonial style-3 <?php echo esc_attr($settings['dark_mode'] == 'yes' ? 'better-bg-blc' : ''); ?>">
    <div class="container-fluid no-padding">
        <div class="row">
            <div class="col-lg-8 no-padding order2">
                <div class="box">
                    <div class="slic-item wow fadeInUp" data-wow-delay=".3S">
                        <?php foreach ($settings['testi_list'] as $index => $item): ?>
                            <div class="item <?php echo esc_attr($settings['dark_mode'] == 'yes' ? 'dark' : ''); ?>">
                                <div class="info">
                                    <div class="author">
                                        <div class="img-author">
                                            <div class="img">
                                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="">
                                            </div>
                                        </div>
                                        <div class="cont">
                                            <h6 class="author-name custom-font"><?php echo esc_html($item['title']); ?></h6>
                                            <span class="author-details"><?php echo esc_html($item['position']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <p><?php echo esc_html($item['text']); ?></p>
                                <div class="qoute-img">
                                    <img src="<?php echo esc_url(plugins_url('/assets/img/quote-light.svg', dirname(__FILE__, 3))); ?>" alt="">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="qoute-icon">
                        <img src="<?php echo esc_url(plugins_url('/assets/img/left-quote.svg', dirname(__FILE__, 3))); ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 offset-lg-1 order1">
                <div class="better-heading style-2 custom-font mt-80 mb-0">
                    <h6 class="wow fadeIn" data-wow-delay=".5s"><?php echo wp_kses_post($settings['section_subtitle']); ?></h6>
                    <h3 class="wow" data-splitting><?php echo wp_kses_post($settings['section_title']); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>
