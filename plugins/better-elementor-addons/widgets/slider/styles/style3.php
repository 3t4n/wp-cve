<section class="better-slider style-3 better-bg-img better-valign" data-background="<?php echo esc_url($settings['bg_image']['url']); ?>" data-overlay-dark="<?php echo esc_attr($settings['slider_mask']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-9">
                <div class="cont">
                    <h6><?php echo esc_html($settings['better_slider4_title']); ?></h6>
                    <h2><?php echo esc_html($settings['better_slider4_subtitle']); ?></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($settings['better_slider4_list'] as $index => $item): ?>
                <div class="col-lg-3">
                    <div class="item mt-30">
                        <h6><?php echo esc_html($item['better_slider4_list_title']); ?></h6>
                        <p><?php echo esc_html($item['better_slider4_list_content']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
