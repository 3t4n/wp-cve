<div class="better-info-box style-3">
    <div class="square-flip">
        <div class='square better-bg-img' data-background="<?php echo esc_url($settings['image']['url']); ?>">
            <div class="square-container d-flex align-items-end">
                <div class="box-title">
                    <h4 class="icon-title"><?php echo esc_html($settings['title']); ?></h4>
                </div>
            </div>
            <div class="flip-overlay"></div>
        </div>
        <div class='square2'>
            <div class="square-container2">
                <p class="icon-text"><?php echo wp_kses_post($settings['text']); ?></p>
            </div>
        </div>
    </div>
</div>
