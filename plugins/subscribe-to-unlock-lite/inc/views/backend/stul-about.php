<div class="wrap stul-wrap">
    <div class="stul-header stul-clearfix">
        <h1 class="stul-floatLeft">
            <img src="<?php echo STUL_URL . 'images/logo.png' ?>" class="stul-plugin-logo" />
            <span class="stul-sub-header"><?php esc_html_e('About', 'subscribe-to-unlock-lite'); ?></span>
        </h1>
        <div class="stul-add-wrap">
            <a href="<?php echo admin_url('admin.php?page=add-subscription-form'); ?>"><input type="button" class="stul-button-white" value="<?php esc_html_e('Add New Form', 'subscribe-to-unlock-lite'); ?>"></a>
        </div>
        <div class="stul-social">
            <a href="https://www.facebook.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-facebook-alt"></i></a>
            <a href="https://twitter.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-twitter"></i></a>
        </div>
    </div>

    <div class="stul-form-wrap stul-form-add-block stul-clearfix">

        <div class="stul-block-wrap">
            <div class="stul-content-block">
                <h2><?php esc_html_e('About Plugin', 'subscribe-to-unlock-lite'); ?></h2>
                <p><?php esc_html_e("As the name explains, this plugin makes it fast and easy to capture subscribers right from your WordPress site by simply locking some specific content of your site until users subscribe to your site. You can choose either link verification or unlock code verification to verify the subscribers.", 'subscribe-to-unlock-lite'); ?></p>
                <p><?php esc_html_e("You can configure form, choose a stunning layout from our 2 beautifully pre designed templates, export subscribers and what not. With this plugin you are just few seconds away from collecting the subscribers from your WordPress site because integrating a subscription form in any site is that easy with our plugin.", 'subscribe-to-unlock-lite'); ?></p>
            </div>
            <div class="stul-content-block">
                <h2><?php esc_html_e('Features', 'subscribe-to-unlock-lite'); ?></h2>
                <ul class="stul-bullets">
                    <li><?php esc_html_e('2 Pre Designed Subscription Form Templates', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Two Locker Modes - Hard and Soft Lock', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Two Email verification methods available - Link or Unlock Code', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Enable disable each form components', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Ajax Form Submission', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Subscribers CSV Export', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Backend Form Preview', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Enable/disable form components', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('All device friendly and browser Compatibility', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Dedicated Support', 'subscribe-to-unlock-lite'); ?></li>
                    <li><?php esc_html_e('Translation Ready', 'subscribe-to-unlock-lite'); ?></li>

                </ul>
            </div>
            <div class="stul-content-block">
                <h2><?php esc_html_e('About WP Shuffle', 'subscribe-to-unlock-lite'); ?></h2>
                <p><?php esc_html_e('We are a bunch of WordPress Enthusiasts with an aim to develop the WordPress Themes and Plugins that adds a value to any WordPress site. Our mission is to create a WordPress product that is easy to use, highly customizable and offers innovative features that are useful for every another WordPress site.', 'subscribe-to-unlock-lite'); ?></p>
                <p><?php esc_html_e('If talking about support, we value our customers more that we value our products so our qualified support team is always there to provide any assistance with our products.', 'subscribe-to-unlock-lite'); ?></p>
            </div>
            <div class="stul-content-block">
                <h2><?php esc_html_e('Our Themes', 'subscribe-to-unlock-lite'); ?></h2>
                <a href="https://wpshuffle.com/wordpress-themes">https://wpshuffle.com/wordpress-themes/</a>
            </div>
            <div class="stul-content-block">
                <h2><?php esc_html_e('Our Plugins', 'subscribe-to-unlock-lite'); ?></h2>
                <a href="https://wpshuffle.com/wordpress-plugins">https://wpshuffle.com/wordpress-plugins/</a>
            </div>
        </div>
        <?php include(STUL_PATH . 'inc/views/backend/upgrade-to-pro-sidebar.php'); ?>

    </div>
</div>
