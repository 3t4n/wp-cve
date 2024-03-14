<?php

use WP_Reactions\Lite\Helper;

?>
<div class="wpra-dashboard wpreactions">
    <!-- top bar -->
	<?php
	Helper::getTemplate(
		'view/admin/components/top-bar',
		[
			"section_title" => "DASHBOARD",
			"logo"          => Helper::getAsset( 'images/wpj_logo.png' ),
			"screen"        => "dashboard",
		]
	);
	?>
    <div class="wpra-folder">
        <div class="wpra-folder-item wpra-white-box">
            <a class="wpra-folder-title" href="<?php echo Helper::getAdminPage( 'global' ); ?>">
                <span class="wpra-icon wpra-icon-globe"></span>
                <h3><?php _e( 'Global Activation', 'wpreactions' ); ?></h3>
            </a>
            <div class="wpra-folder-desc">
				<?php _e( 'Deploy your emojis on pages and posts with one-click-activation. Customize your reactions on your blog posts, pages and all post types.', 'wpreactions' ); ?>
            </div>
        </div>
        <div class="wpra-folder-item wpra-white-box">
            <span class="wpra-folder-item-badge">PRO</span>
            <a class="wpra-folder-title" href="#">
                <span class="wpra-icon wpra-icon-sh"></span>
                <h3><?php _e( 'Shortcode Generator', 'wpreactions' ); ?></h3>
            </a>
            <div class="wpra-folder-desc">
				<?php _e( 'Make shortcode and paste your reactions under images, videos and anywhere you want to engage users and get a reaction.', 'wpreactions' ); ?>
            </div>
        </div>
        <div class="wpra-folder-item wpra-white-box">
            <span class="wpra-folder-item-badge">PRO</span>
            <a class="wpra-folder-title" href="#">
                <span class="wpra-icon wpra-icon-woo"></span>
                <h3><?php _e( 'Woo Reactions', 'wpreactions' ); ?></h3>
            </a>
            <div class="wpra-folder-desc">
				<?php _e( 'Manage the locations for Woo Reactions and place your emoji reactions on product pages so your customers can react to your products.', 'wpreactions' ); ?>
            </div>
        </div>
        <div class="wpra-folder-item wpra-white-box">
            <a class="wpra-folder-title">
                <span class="wpra-folder-item-badge">PRO</span>
                <span class="wpra-icon wpra-icon-chart"></span>
                <h3><?php _e( 'Analytics', 'wpreactions' ); ?></h3>
            </a>
            <div class="wpra-folder-desc">
				<?php _e( 'Monitor how your users are reacting to your content. Collect emotional data and social shares to better understand your users.', 'wpreactions' ); ?>
            </div>
        </div>
        <div class="wpra-folder-item wpra-white-box">
            <span class="wpra-folder-item-badge">PRO</span>
            <a class="wpra-folder-title" href="#">
                <span class="wpra-icon wpra-icon-cog"></span>
                <h3><?php _e( 'Settings', 'wpreactions' ); ?></h3>
            </a>
            <div class="wpra-folder-desc">
				<?php _e( 'Manage post types that are included in your theme and choose what pages you would like your emojis to show.', 'wpreactions' ); ?>
            </div>
        </div>
        <div class="wpra-folder-item wpra-white-box">
            <span class="wpra-folder-item-badge">COMING</span>
            <a class="wpra-folder-title" href="#">
                <span class="wpra-icon wpra-icon-smile"></span>
                <h3><?php _e( 'Add-on Pack', 'wpreactions' ); ?></h3>
            </a>
            <div class="wpra-folder-desc">
			    <?php _e( 'Add more emojis coming soon. Our design team is working on new emoji reactions for you to choose from.', 'wpreactions' ); ?>
            </div>
        </div>
    </div>
    <div class="dashboard-banners">
        <div class="wpra-banner wpra-white-box wpra-js-link" data-href="https://wpreactions.com/become-an-affiliate/">
            <div class="wpra-banner-img">
                <img src="<?php echo Helper::getAsset('images/banners/affiliates.png'); ?>" alt="affiliates banner">
            </div>
            <div class="wpra-banner-content">
                <h3><?php _e('Affiliates', 'wpreactions-lite'); ?></h3>
                <p><?php _e('WP Reactions Pro is growing fast and we want to take you along for the ride. Earn 30% with us on each sale with our affiliate dashboard.', 'wpreactions-lite'); ?></p>
                <a href="https://wpreactions.com/become-an-affiliate/" class="btn btn-purple" target="_blank"><?php _e('Learn more', 'wpreactions-lite'); ?></a>
            </div>
        </div>
        <div class="wpra-banner wpra-white-box">
            <div class="wpra-banner-img">
                <img src="<?php echo Helper::getAsset('images/banners/go-pro-dashboard.png'); ?>" alt="affiliates banner">
            </div>
            <div class="wpra-banner-content">
                <h3><?php _e('Go Pro', 'wpreactions-lite'); ?></h3>
                <p><?php _e('Upgrade to our PRO version to take things to the next level in animated emoji user engagement.', 'wpreactions-lite'); ?></p>
                <a href="https://wpreactions.com/pricing/" class="btn btn-purple" target="_blank"><?php _e('Upgrade to Pro', 'wpreactions-lite'); ?></a>
            </div>
        </div>
    </div>
</div>
