<div class="better-clients style-2">
    <div class="brands">
        <div class="item wow fadeIn" data-wow-delay=".8s">
            <div class="img">
                <img class="img1" src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="">
                <a href="<?php echo esc_url( $settings['link']['url'] ); ?>" <?php if ( $settings['link']['is_external'] ) { echo 'target="_blank"'; } ?> class="link" data-splitting><?php echo esc_html( wp_kses_post( $settings['text'] ) ); ?></a>
            </div>
        </div>
    </div>
</div>
