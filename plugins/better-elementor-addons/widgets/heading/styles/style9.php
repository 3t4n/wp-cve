<div class="better-heading style-9">
    <h6 class="fw-500 mb-10"><?php echo esc_html($settings['better_heading_title_1']); ?></h6>
    <h3 class="gr-text mb-20"><?php echo esc_html($settings['better_heading_title']); ?></h3>
    <p class="fw-300"><?php echo esc_html($settings['better_heading_des']); ?></p>
    <?php if (!empty($settings['link']['url'])) : ?>
        <a href="<?php echo esc_url($settings['link']['url']); ?>" class="better-btn-curve btn-lit mt-40">
            <span><?php echo esc_html($settings['btn_text']); ?></span>
        </a>
    <?php endif; ?>
</div>
