<div class="better-about style-1 better-section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="main-tit">
                    <h2 class="custom-font wow" data-splitting><?php echo wp_kses_post($settings['section_title']); ?></h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content">
                    <p class="wow txt" data-splitting><?php echo wp_kses_post($settings['section_subtitle']); ?></p>
                    <div class="exp">
                        <h3 class="better-bg-img better-valign custom-font" data-background="<?php echo esc_url($settings['number_image']['url']); ?>"><?php echo wp_kses_post($settings['section_number']); ?></h3>
                        <h5 class="custom-font better-valign">
                            <span class="wow" data-splitting><?php echo wp_kses_post($settings['section_text']); ?></span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid office">
        <div class="row d-flex justify-content-center">
            <?php foreach ($settings['images_list'] as $index => $item): ?>
                <div class="col-lg-<?php echo esc_attr($item['column']) ?> better-lg-padding">
                    <div class="item better-bg-img wow imago" data-background="<?php echo esc_url($item['item_image']['url']); ?>">
                        <div class="num">
                            <?php echo wp_kses_post($item['number']); ?>
                        </div>
                        <span class="tit custom-font"><?php echo wp_kses_post($item['title']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
