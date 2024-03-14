<?php
/**
 * Content for dashboard section in admin area.
 *
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
?>
<div id="cvmm-dashboard">
    <h2 class="cvmm-admin-title"><?php esc_html_e( 'Get Started with WP Magazine Modules Lite', 'wp-magazine-modules-lite' ); ?></h2>
    <div class="cvmm-admin-desc">
        <?php
            esc_html_e( 'Thank you so much for installing the WP Magazine Modules Lite Plugin. We have designed and developed the most impressive post-layout designs for Gutenberg and Elementor ! If you have any confusions, please check out our documentation on below link:', 'wp-magazine-modules-lite' );
        ?>
    </div>
    <div class="cvmm-admin-img">
        <img src="<?php echo esc_url( plugins_url( 'includes/assets/images/dashboard-img.jpg', dirname(__DIR__) ) ); ?>">
    </div>
    <div class="cvmm-main-btn-wrap">
        <?php if ( current_user_can( 'edit_posts' ) ) { ?>
                <div class="cvmm-main-btn">
                    <a class="button-primary" href="<?php echo esc_url( admin_url().'post-new.php?post_type=page' ); ?>" target="_blank">
                        <?php esc_html_e( 'Create first template', 'wp-magazine-modules-lite' ); ?>
                    </a>
                </div><!-- .cvmm-main-btn -->
        <?php } ?>
        <div class="cvmm-main-btn">
            <a class="button-primary" href="<?php echo esc_url( 'https://demo.codevibrant.com/plugins/wp-magazine-modules/' ); ?>" target="_blank">
                <?php esc_html_e( 'View Demos', 'wp-magazine-modules-lite' ); ?>
            </a>
        </div><!-- .cvmm-main-btn -->
        <div class="cvmm-main-btn">
            <a class="button-primary" href="<?php echo esc_url( 'https://docs.codevibrant.com/plugins/wp-magazine-modules' ); ?>" target="_blank">
                <?php esc_html_e( 'Documentation', 'wp-magazine-modules-lite' ); ?>
            </a>
        </div><!-- .cvmm-main-btn -->
    </div>
</div><!-- .cvmm-dashboard -->