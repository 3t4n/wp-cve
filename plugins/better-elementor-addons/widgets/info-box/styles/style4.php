<div class="better-info-box style-4">
    <div class="item">
        <span class="icon better-icon <?php echo esc_attr($settings['info_icon']['value']); ?>"></span>
        <div class="cont">
            <h6 class="icon-title"><?php echo esc_html($settings['title']); ?></h6>
            <p class="icon-text"><?php echo wp_kses_post($settings['text']); ?></p>
        </div>
    </div>
</div>
