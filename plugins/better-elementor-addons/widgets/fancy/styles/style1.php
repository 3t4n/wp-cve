<?php
/**
 * Fancy style-1
 *
 * @since 1.0.8
 *
 */
?>
<div class="better-fancy style-1">
    <div class="ab-exp">
        <div class="row">
            <div class="col-md-4 btr-mb-20">
                <div class="pattern better-bg-img better-bg-repeat" data-background="<?php echo esc_url( $settings['bg_image']['url'] ); ?>">
                </div>
            </div>
            <div class="col-md-8 wow fadeInUp" data-wow-delay=".3s">
                <div class="img btr-mb-20 wow imago">
                    <img src="<?php echo esc_url( $settings['t_image']['url'] ); ?>" alt="">
                </div>
            </div>
            <div class="col-md-7 wow fadeInUp" data-wow-delay=".3s">
                <div class="img wow imago">
                    <img src="<?php echo esc_url( $settings['b_image']['url'] ); ?>" alt="">
                </div>
            </div>
            <div class="col-md-5">
                <div class="years-exp">
                    <div class="exp-text">
                        <h2 class="custom-font"><?php echo esc_html( $settings['title'] ); ?></h2>
                        <h6><?php echo esc_html( $settings['subtitle'] ); ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
