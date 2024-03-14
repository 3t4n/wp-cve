<div class="better-services style-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="img">
                    <img class="thumparallax-down" src="<?php echo esc_url($settings['better_services2_image']['url']); ?>" alt="">
                </div>
            </div>
            <div class="col-lg-6 better-valign">
                <div class="content">
                    <h4 class="wow" data-splitting><?php echo wp_kses_post($settings['title']); ?></h4>
                    <p class="wow txt" data-splitting><?php echo wp_kses_post($settings['subtitle']); ?></p>
                    <ul class="feat v">
                        <?php foreach ($settings['services_list'] as $index => $item) : ?>
                            <li class="wow fadeInUp" data-wow-delay=".2s">
                                <h6><span><?php echo esc_html($index + 1); ?></span> <?php echo wp_kses_post($item['item_title']); ?></h6>
                                <p><?php echo wp_kses_post($item['item_text']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
