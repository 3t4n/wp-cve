<div id="services" class="better-services style-1 better-bg-repeat better-bg-img <?php echo esc_attr($settings['show_readmore_btn'] !== 'yes' ? 'serv-pg' : ''); ?>">
    <div class="item wow fadeInUp md-mb50" data-wow-delay=".3s">
        <h6 class="custom-font">
            <span class="letr better-bg-img custom-font" data-background="<?php echo esc_url($settings['first_letter_image']['url']); ?>">
                <?php echo wp_kses_post($settings['title_first_letter']); ?>
            </span>
            <?php echo wp_kses_post($settings['title']); ?>
        </h6>
        <p><?php echo wp_kses_post($settings['text']); ?></p>
        <span class="icon <?php echo esc_attr($settings['icon']['value']); ?>"></span>
        <?php if ($settings['show_readmore_btn'] === 'yes') : ?> 
            <a href="<?php echo esc_url($settings['link']['url']); ?>" class="more custom-font">Read More <i class="pe-7s-angle-right"></i></a> 
        <?php endif; ?>
    </div>
</div>
