<?php
use WP_Reactions\Lite\Helper;

global $wpra_lite;
?>
<div class="wpreactions primary-color-blue wpra-support-page">
    <!-- top bar -->
    <?php Helper::getTemplate(
        'view/admin/components/top-bar',
        [ "logo" => Helper::getAsset('images/wpj_logo.png') ]
    ); ?>
    <div class="wpra-option-heading d-flex align-items-center justify-content-between heading-left">
        <div>
            <h4><span><?php _e('Product Support', 'wpreactions-lite'); ?></span></h4>
            <p><?php _e('Our support team is ready to assist you.', 'wpreactions-lite'); ?></p>
        </div>
        <a href="https://wpreactions.com/pricing" target="_blank" class="btn btn-blue"><i class="qa qa-star"></i><?php _e('Go Pro', 'wpreactions-lite'); ?></a>
    </div>
    <div class="row">
        <div class="col-md-6 pr-2">
            <div class="wpra-white-box p-3 text-center mb-3">
                <span class="dashicons dashicons-wordpress-alt" style="width: auto;height: auto;font-size: 60px;"></span>
                <h3 class="font-weight-bold"><?php _e('WordPress Forum Support', 'wpreactions-lite'); ?></h3>
                <p class="text-muted mb-0">
                    <a href="https://wordpress.org/support/plugin/wp-reactions-lite/" target="_blank"><?php _e('Find us in the directory', 'wpreactions-lite'); ?></a>
                </p>
            </div>
            <div class="wpra-white-box p-3">
                <h5 class="fw-700 mb-3"><span class="dashicons dashicons-format-aside"></span><?php _e('Visit our Docs', 'wpreactions-lite'); ?>
                </h5>
                <p><?php _e('Please visit our documentation to learn more about WP Reactions Lite. All features and options are covered in detail for your convenience. Here are some popular topics:', 'wpreactions-lite'); ?></p>
                <div id="doc-links" class="pro-features-list">
                    <?php $wpra_lite->make_doc_links(); ?>
                </div>
                <div class="mt-4">
                    <a class="btn btn-blue fw-500 w-100" href="https://wpreactions.com/documentation" target="_blank">
                        <?php _e('Visit Plugin Documentation', 'wpreactions-lite'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 pl-2">
            <div class="wpra-banner-big-left wpra-white-box p-3 text-center">
                <img src="<?php echo Helper::getAsset('images/logo-icon-only.svg'); ?>" alt="" style="width: 80px;">
                <h3 class="fw-600 mt-2" style="font-size: 22px;"><?php _e('Premium Support', 'wpreactions-lite'); ?></h3>
                <p class="pl-5 pr-5"><?php _e('Take emoji user engagement to a whole new level with JoyPixels 3.5, more layouts, more social, analytics, and more!', 'wpreactions-lite') ?></p>
                <div class="mt-3 mb-4">
                    <img src="<?php echo Helper::getAsset('images/banners/support-upgrade-pro.png'); ?>" class="img-fluid" style="width: 260px;" alt="banner support">
                </div>
                <h3 class="fw-700 mb-4"><?php _e('Upgrade to Pro', 'wpreactions-lite'); ?></h3>
                <a href="https://wpreactions.com/pricing" target="_blank" class="btn btn-purple w-100"><?php _e('Get Premium Support', 'wpreactions-lite'); ?></a>
            </div>
        </div>
    </div>
</div>
