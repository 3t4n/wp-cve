<?php
    $page = sanitize_text_field($_GET['page']);
    if ($is_paymattic_user) {
        return;
    }
?>
<div class="wppayform_main_nav">
    <span class="wpf_plugin-name">
        <img style="max-width:126px;"
            src="<?php echo esc_url($brand_logo);?>"
        >
    </span>
    <a href="<?php echo admin_url('admin.php?page=wppayform.php#/'); ?>" class="ninja-tab wpf-route-forms">
        <?php _e('All Forms', 'wp-payment-form'); ?>
    </a>
    <a href="<?php echo admin_url('admin.php?page=wppayform.php#/entries');?>" class="ninja-tab wpf-route-entries">
        <?php _e('Entries', 'wp-payment-form'); ?>
    </a>
    <a href="<?php echo admin_url('admin.php?page=wppayform.php#/integrations'); ?>" class="ninja-tab wpf-route-integrations">
        <?php _e('Integrations', 'wp-payment-form'); ?>
    </a>

    <a href="<?php echo admin_url('admin.php?page=wppayform.php#/reports'); ?>" class="ninja-tab wpf-route-reports">
        <?php _e('Reports', 'wp-payment-form'); ?>
    </a>

    <a href="<?php echo admin_url('admin.php?page=wppayform.php#/gateways/stripe'); ?>" class="ninja-tab wpf-route-gateways">
        <?php _e('Payment Gateway', 'wp-payment-form'); ?>
    </a>

    <a href="<?php echo admin_url('admin.php?page=wppayform_settings'); ?>" class="ninja-tab <?php echo ($page == 'wppayform_settings') ? 'ninja-tab-active' : '' ?>">
        <?php _e('Settings', 'wp-payment-form'); ?>
    </a>

    <div class="wppayform-fullscreen-main">
        <span id="wpf-contract-btn"
            class="wpf-contract-btn dashicons dashicons-editor-contract" style="font-size: 24px; padding-left: 4px">
        </span>
        <span id="wpf-expand-btn"
            class="wpf-expand-btn el-icon-full-screen" style="font-size: 20px; font-weight: 500; padding-left: 4px">
        </span>
    </div>

    <?php do_action('wppayform_after_global_menu'); ?>
    <?php if (!defined('WPPAYFORMHASPRO')) : ?>
        <a target="_blank" rel="noopener" href="<?php echo wppayformUpgradeUrl(); ?>" class="ninja-tab buy_pro_tab">
            <?php _e('Upgrade to Pro', 'wp-payment-form'); ?>
        </a>
    <?php endif; ?>
</div>
