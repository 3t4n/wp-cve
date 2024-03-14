<?php
/**
 * Content for help section in admin area.
 *
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
?>
<div id="cvmm-help" style="display:none">
    <h2 class="cvmm-admin-title">
        <?php esc_html_e( 'Do you need any help related to our plugin ?', 'wp-magazine-modules-lite' ); ?>
    </h2>
    <div class="cvmm-admin-img">
        <img src="<?php echo esc_url( plugins_url( 'includes/assets/images/support-img.jpg', dirname(__DIR__) ) ); ?>">
    </div>
    <div class="cvmm-admin-box-wrapper">
        <div class="cvmm-admin-fields">
            <?php esc_html_e( 'Our documentation gives all the necessary detailed information to get you started. It provides an elaborated overview on plugin features, how to use those features and how to troubleshoot errors.', 'wp-magazine-modules-lite' ); ?>
            <div class="cvmm-main-btn">
                <a class="button-primary" href="https://docs.codevibrant.com/plugins/wp-magazine-modules/" target="_blank"> <?php esc_html_e( 'Documentation', 'wp-magazine-modules-lite' ); ?> </a>
            </div><!-- .cvmm-main-btn -->
        </div><!-- .cvmm-admin-fields -->

        <div class="cvmm-admin-fields">
            <?php esc_html_e( 'Our TeamSupport specialists are standing by to better understand your customer support needs and solve your problem for you. We aim to provide professional technical support  24/7 to satisfy your need and wish. We also offer support via email and social media.', 'wp-magazine-modules-lite' ); ?>
            <div class="cvmm-main-btn">
                <a class="button-primary" href="https://codevibrant.com/contact/" target="_blank"> <?php esc_html_e( 'Support', 'wp-magazine-modules-lite' ); ?> </a>
            </div><!-- .cvmm-main-btn -->
        </div><!-- .cvmm-admin-fields -->

        <div class="cvmm-admin-fields">
            <?php esc_html_e( 'Here are our some plugin related latest blogs.', 'wp-magazine-modules-lite' ); ?>
            <div class="cvmm-main-btn">
                <a class="button-primary" href="https://wpallresources.com" target="_blank"> <?php esc_html_e( 'Wpallresources', 'wp-magazine-modules-lite' ); ?> </a>
            </div><!-- .cvmm-main-btn -->
        </div><!-- .cvmm-admin-fields -->

    </div><!-- .cvmm-admin-box-wrapper -->
</div><!-- .cvmm-help -->