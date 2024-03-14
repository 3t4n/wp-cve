<div class="better-services style-3">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ( $settings['style3_services_list'] as $index => $item ) : ?>
            <div class="swiper-slide">
                <div class="item">
                    <span class="icon gr-text <?php echo esc_attr( $item['style3_item_icon'] ); ?>"></span>
                    <h6><?php echo esc_html( $item['style3_item_title'] ); ?></h6>
                    <p><?php echo esc_html( $item['style3_item_text'] ); ?></p>
                    <a href="<?php echo esc_url( $item['style3_item_link']['url'] ); ?>"><?php esc_html_e( 'Read More', 'text-domain' ); ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
