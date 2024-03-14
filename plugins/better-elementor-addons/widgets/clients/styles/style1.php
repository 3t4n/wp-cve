<div class="better-clients style-1 better-section-padding <?php echo esc_attr( $settings['dark_style'] == 'yes' ? 'better-bg-blc box' : '' ); ?>">
    <div class="brands-crs">
        <?php foreach ( $settings['better_clients_list'] as $index => $item ) : ?>
            <div class="brands">
                <div class="item" data-wow-delay=".3s">
                    <div class="img">
                        <img src="<?php echo esc_url( $item['client_image']['url'] ); ?>" alt="">
                    </div>
                    <a href="<?php echo esc_url( $item['client_link']['url'] ); ?>" class="link" data-splitting><?php echo esc_html( $item['client_name'] ); ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
