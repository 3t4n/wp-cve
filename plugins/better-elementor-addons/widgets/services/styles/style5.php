<div class="better-services style-5">
    <div class="item <?php if ( ! empty( $settings['image']['url'] ) ) { echo 'better-bg-img';} ?> wow fadeInUp" data-wow-delay=".3s" data-background="<?php echo esc_url( $settings['image']['url'] ); ?>">
        <span class="icon <?php echo esc_attr( $settings['style4_item_icon'] ); ?>"></span>
        <h6 class="mb-20"><?php echo esc_html( $settings['title'] ); ?></h6>
        <p><?php echo esc_html( $settings['text'] ); ?></p>
        <?php if ( ! empty( $settings['link']['url'] ) ) : ?>
            <a href="<?php echo esc_url( $settings['link']['url'] ); ?>" class="more mt-30"><?php echo esc_html( $settings['btn_text'] ); ?></a>
        <?php endif; ?>
    </div>
</div>
