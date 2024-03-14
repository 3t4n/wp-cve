<div class="wrap wpsf-wrap">
    <div class="wpsf-header wpsf-clearfix">
        <h1 class="wpsf-floatLeft">
            <img src="<?php echo WPSF_URL . 'images/logo.png' ?>" class="wpsf-plugin-logo" />
            <span class="wpsf-sub-header"><?php esc_html_e('About', 'wp-subscription-forms'); ?></span>
        </h1>
        <div class="wpsf-add-wrap">
            <a href="<?php echo admin_url('admin.php?page=add-subscription-form'); ?>"><input type="button" class="wpsf-button-primary" value="<?php esc_html_e('Add New Form', 'wp-subscription-forms'); ?>"></a>
        </div>
    </div>

    <div class="wpsf-form-wrap wpsf-form-add-block wpsf-left-wrap wpsf-clearfix">

        <div class="wpsf-content-block">
            <h2><?php esc_html_e('About Plugin', 'wp-subscription-forms'); ?></h2>
            <p><?php esc_html_e("WP Subscription Forms is a free subscription form builder plugin which is very easy to use and provides you the very intuitive interface to create and integrate subscriptions forms in your site in no time. ", 'wp-subscription-forms'); ?></p>
            <p><?php esc_html_e("You can create unlimited subscription forms, choose a stunning layout from our 10 beautifully pre designed templates, display subscription forms in popup form, collect subscribers in backend and export them to CSV file and what not. With this plugin you are just few seconds away from collecting the subscribers from your WordPress site because creating and integrating a subscription form in any site is that easy with our plugin.", 'wp-subscription-forms'); ?></p>
        </div>

        <div class="wpsf-content-block">
            <h2><?php esc_html_e('Features', 'wp-subscription-forms'); ?></h2>
            <ul class="wpsf-bullets">
                <li><?php esc_html_e('Unlimited Subscription Forms', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('10 Pre Designed Subscription Form Templates', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('Popup Subscription Forms', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('Enable disable each form components', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('Ajax Form Submission', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('Subscribers CSV Export', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('Backend Form Preview', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('RTL Compatible', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('Translation Ready', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('Mobile Friendly', 'wp-subscription-forms'); ?></li>
                <li><?php esc_html_e('All browsers Compatible', 'wp-subscription-forms'); ?></li>

            </ul>
        </div>

        <div class="wpsf-content-block">
            <h2><?php esc_html_e('About WP Shuffle', 'wp-subscription-forms'); ?></h2>
            <p><?php esc_html_e('We are a bunch of WordPress Enthusiasts with an aim to develop the WordPress Themes and Plugins that adds a value to any WordPress site. Our mission is to create a WordPress product that is easy to use, highly customizable and offers innovative features that are useful for every another WordPress site.', 'wp-subscription-forms'); ?></p>
            <p><?php esc_html_e('If talking about support, we value our customers more that we value our products so our qualified support team is always there to provide any assistance with our products.', 'wp-subscription-forms'); ?></p>
        </div>

        <div class="wpsf-content-block">
            <h2><?php esc_html_e('Our Themes', 'wp-subscription-forms'); ?></h2>
            <a href="https://wpshuffle.com/wordpress-themes">https://wpshuffle.com/wordpress-themes/</a>
        </div>

        <div class="wpsf-content-block">
            <h2><?php esc_html_e('Our Plugins', 'wp-subscription-forms'); ?></h2>
            <a href="https://wpshuffle.com/wordpress-plugins">https://wpshuffle.com/wordpress-plugins/</a>
        </div>

    </div>

    <?php include(WPSF_PATH . 'inc/views/backend/upgrade-to-pro.php'); ?>

</div>
