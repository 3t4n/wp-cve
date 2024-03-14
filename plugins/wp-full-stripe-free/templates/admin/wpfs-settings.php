<?php
/** @var $data */
$accountId = isset($_GET['accountId']) ? sanitize_text_field($_GET['accountId']) : null;
$mode = isset($_GET['mode']) ? sanitize_text_field($_GET['mode']) : null;
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings">
        <?php include('partials/wpfs-header.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <?php
        $settingsItems = array();

        $testStatus = $this->getTestAccountStatus();

        $liveStatus = $this->getLiveAccountStatus();

        $disabledLive = $liveStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_COMPLETE || $liveStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_ENABLED ? false : true;
        $disabledTest = $testStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_COMPLETE || $testStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_ENABLED ? false : true;
        $disabled = $disabledLive && $disabledTest ? true : false;
        $isNewFlow = false;
        if ($this->options->get(MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM) == true || $this->options->get(MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM) == true) {
            $isNewFlow = true;
        }
        $disabled = $disabled && $isNewFlow;

        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-stripe',
            'url' => $this->getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_SETTINGS_STRIPE),
            'title' => __('Stripe account', 'wp-full-stripe-admin'),
            'description' => __('Configure your Stripe API keys, and set up webhooks', 'wp-full-stripe-admin'),
            'disabled' => false
        ));
        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-form',
            'url' => $this->getAdminUrlBySlugAndParams(MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS, [MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS]),
            'title' => __('Forms', 'wp-full-stripe-admin'),
            'description' => __('Set global settings & styles for your payment forms', 'wp-full-stripe-admin'),
            'disabled' => $disabled
        ));
        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-email',
            'url' => $this->getAdminUrlBySlugAndParams(MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS, [MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS]),
            'title' => __('Email notifications', 'wp-full-stripe-admin'),
            'description' => __('Customize and align your e-mails to your brand', 'wp-full-stripe-admin'),
            'disabled' => $disabled
        ));
        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-security',
            'url' => $this->getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_SETTINGS_SECURITY),
            'title' => __('Security', 'wp-full-stripe-admin'),
            'description' => __('Keep your payment forms secure', 'wp-full-stripe-admin'),
            'disabled' => $disabled
        ));
        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-customer-portal',
            'url' => $this->getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_SETTINGS_CUSTOMER_PORTAL),
            'title' => __('Customer portal', 'wp-full-stripe-admin'),
            'description' => __('Configure how your customers can manage their cards, subscriptions, and invoices', 'wp-full-stripe-admin'),
            'disabled' => $disabled
        ));
        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-wp',
            'url' => $this->getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_SETTINGS_WORDPRESS_DASHBOARD),
            'title' => __('WordPress dashboard', 'wp-full-stripe-admin'),
            'description' => __('Set your currency format preferences', 'wp-full-stripe-admin'),
            'disabled' => $disabled
        ));
        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-add-ons',
            'url' => $this->getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_SETTINGS_ADDONS),
            'title' => __('Add-ons', 'wp-full-stripe-admin'),
            'description' => __('Manage your activated add-ons', 'wp-full-stripe-admin'),
            'disabled' => $disabled
        ));
        array_push($settingsItems, array(
            'cssClasses' => 'wpfs-illu-logs',
            'url' => $this->getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_SETTINGS_LOGS),
            'title' => __('Error logging', 'wp-full-stripe-admin'),
            'description' => __('Help the developers debug plugin issues', 'wp-full-stripe-admin'),
            'disabled' => false
        ));
        ?>

        <?php if ($disabled): ?>
            <div class="wpfs-announcement">
                <?php esc_html_e('Settings are disabled until you have connected your Stripe account.', 'wp-full-stripe-admin'); ?><br />
                Follow <a
                    href="https://support.paymentsplugin.com/article/31-step-by-step-guide-to-setup-stripe-on-fullpay-v7">our
                    guide</a> for step-by-step instructions.
            </div>
        <?php endif; ?>
        <div class="wpfs-list wpfs-list--hub">
            <?php foreach ($settingsItems as $item) { ?>
                <?php if ($item['disabled']) { ?>
                    <div class="wpfs-list__item" style="opacity: 0.5; cursor: not-allowed;">
                        <div class="<?php echo $item['cssClasses']; ?> wpfs-list__icon"></div>
                        <div class="wpfs-list__text">
                            <div class="wpfs-list__title">
                                <?php echo $item['title']; ?>
                            </div>
                            <div class="wpfs-list__desc">
                                <?php echo $item['description']; ?>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <a class="wpfs-list__item" href="<?php echo $item['url']; ?>">
                        <div class="<?php echo $item['cssClasses']; ?> wpfs-list__icon"></div>
                        <div class="wpfs-list__text">
                            <div class="wpfs-list__title">
                                <?php echo $item['title']; ?>
                            </div>
                            <div class="wpfs-list__desc">
                                <?php echo $item['description']; ?>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>

        <?php include('partials/wpfs-settings-test-data.php'); ?>
    </div>
    <script type="text/javascript">
        // Define a global JavaScript variable for the accountId
        var accountIdFromPHP = <?php echo json_encode($accountId); ?>;
        var accountModeFromPHP = <?php echo json_encode($mode); ?>;
    </script>
</div>