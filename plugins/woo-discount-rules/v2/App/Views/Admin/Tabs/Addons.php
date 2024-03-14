<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<style>
    .awdr-addons {
        clear: both;
        padding-top: 10px;
    }
    .awdr-addon {
        background: #fff;
        border: 1px solid #ccc;
        float: left;
        padding: 14px;
        position: relative;
        margin: 0 15px 16px 0;
        width: 320px;
        min-height: 320px;
        opacity: .9;
        transition: all .2s ease-in-out;
        cursor: default;
    }
    .awdr-addon:hover {
        border: 1px solid #bbb;
        opacity: 1;
        transform: scale(1.05);
        z-index: 5;
    }
    .awdr-addon h3 {
        margin-top: 2px;
    }
    .awdr-addons .addon-badge {
        position: absolute;
        color: white;
        padding: 2px 6px;
        font-weight: bold;
        text-transform: uppercase;
        top: 14px;
        right: 14px;
    }
    .awdr-addons .addon-image {
        display: block;
        height: 160px;
    }
    .awdr-addons .addon-image img {
        height: 100%;
        width: 100%;
    }
    .awdr-addons .addon-info {
        margin-bottom: 32px;
    }
    .awdr-addons .addon-actions {
        position: absolute;
        bottom: 14px;
        width: calc(100% - 28px);
    }
</style>
<br>
<div id="wpbody-content" class="awdr-container">
    <?php if ($addon_activated !== '' || $addon_deactivated !== '') {
        $status = $addon_activated || $addon_deactivated ? 'success' : 'error';
        if ($addon_activated !== '') {
            $message = $addon_activated
                ? __('Addon activated successfully.', 'woo-discount-rules')
                : __('Addon activate failed.', 'woo-discount-rules');
        }
        if ($addon_deactivated !== '') {
            $message = $addon_deactivated
                ? __('Addon deactivated successfully.', 'woo-discount-rules')
                : __('Addon deactivate failed.', 'woo-discount-rules');
        }
        if (!empty($status) && !empty($message)) { ?>
            <div class="notice notice-<?php echo esc_attr($status); ?>">
                <p><?php echo esc_html($message); ?></p>
            </div>
            <div class="clear"></div>
        <?php } ?>
    <?php } ?>

    <?php if (empty($active_addons) && empty($available_addons)) { ?>
        <div class="notice notice-error">
            <p><?php _e("Unable to load addons! Try again later.", 'woo-discount-rules'); ?></p>
        </div>
        <div class="clear"></div>
    <?php } ?>

    <h2><?php _e("Active Add-Ons", 'woo-discount-rules'); ?></h2>
    <div class="awdr-addons" style="padding: 10px;">
        <?php if (!empty($active_addons)): ?>
            <?php foreach ($active_addons as $slug => $addon) { ?>
                <div class="awdr-addon">
                    <h3 class="addon-header"><?php echo esc_html($addon['name']); ?></h3>
                    <?php if (!empty($addon['is_pro'])): ?>

                    <?php endif; ?>
                    <a class="addon-image" <?php if (!empty($addon['product_url'])) echo 'href="' . esc_url($addon['product_url']) .'"'; ?>>
                        <img src="<?php echo esc_url($addon['icon_url']); ?>" alt="<?php echo esc_attr($addon['name']); ?>"/>
                    </a>
                    <div class="addon-info">
                        <p><?php echo esc_html($addon['description']); ?></p>
                        <?php if (!empty($addon['message'])): ?>
                            <p style="color: #e39434;"><?php echo wp_kses($addon['message'], array('br' => array())); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="addon-actions">
                        <?php if (!empty($addon['page_url'])): ?>
                            <a href="<?php echo esc_url($addon['page_url']); ?>" title="<?php echo esc_attr($addon['name']); ?>" class="button-primary">
                                <?php _e("Open", 'woo-discount-rules'); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($addon['settings_url'])): ?>
                            <a href="<?php echo esc_url($addon['settings_url']); ?>" class="button-secondary">
                                <?php _e("Settings", 'woo-discount-rules'); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($addon['is_installed'])): ?>
                            <a style="float: right;" href="<?php echo esc_url(add_query_arg(['deactivate_addon' => $slug, 'nonce' => wp_create_nonce('awdr_addon_deactivate')])); ?>" class="button-secondary">
                                <?php _e("Deactivate", 'woo-discount-rules'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>
        <?php else: ?>
            <p class="no-addons"><?php _e("No Active Add-Ons", 'woo-discount-rules'); ?></p>
        <?php endif; ?>
    </div>
    <div class="clear"></div>

    <h2><?php _e("Available Add-Ons", 'woo-discount-rules'); ?></h2>
    <div class="awdr-addons" style="padding: 10px;">
        <?php if (!empty($available_addons)): ?>
            <?php foreach ($available_addons as $slug => $addon) { ?>
                <div class="awdr-addon">
                    <h3 class="addon-header"><?php echo esc_html($addon['name']); ?></h3>
                    <div class="addon-badge" style="background: <?php echo !empty($addon['is_pro']) ? '#257AF0' : '#349832'; ?>">
                        <?php if (!empty($addon['is_pro'])) {
                            if (isset($addon['price']) && !empty($addon['price'])) {
                                echo esc_html($addon['price']);
                            } else {
                                _e("Paid", 'woo-discount-rules');
                            }
                        } else {
                            _e("Requires PRO", 'woo-discount-rules');
                        } ?>
                    </div>
                    <a class="addon-image" <?php if (!empty($addon['product_url'])) echo 'href="' . esc_url($addon['product_url']) .'"'; ?>>
                        <img src="<?php echo esc_url($addon['icon_url']); ?>" alt="<?php echo esc_attr($addon['name']); ?>"/>
                    </a>
                    <div class="addon-info">
                        <p><?php echo esc_html($addon['description']); ?></p>
                        <?php if (!empty($addon['message'])): ?>
                            <p style="color: #e39434;"><?php echo wp_kses($addon['message'], array('br' => array())); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="addon-actions">
                        <?php if (!empty($addon['download_url']) && empty($addon['is_installed'])): ?>
                            <a href="<?php echo esc_url($addon['download_url']); ?>" title="<?php echo esc_attr($addon['name']); ?>" class="button-primary">
                                <?php _e("Download", 'woo-discount-rules'); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($addon['product_url'])): ?>
                            <a href="<?php echo esc_url($addon['product_url']); ?>" target="_blank" class="button-secondary">
                                <?php if (empty($addon['download_url']) && empty($addon['is_installed'])) {
                                    _e("Get this addon", 'woo-discount-rules');
                                } else {
                                    _e("More info", 'woo-discount-rules');
                                } ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($addon['is_installed'])): ?>
                            <a href="<?php echo esc_url(add_query_arg(['activate_addon' => $slug, 'nonce' => wp_create_nonce('awdr_addon_activate')])); ?>"
                               class="button-primary"  style="float: right; <?php if (empty($addon['is_activatable'])) echo 'pointer-events: none;'; ?>"
                               <?php if (empty($addon['is_activatable'])) echo 'disabled'; ?>>
                                <?php _e("Activate", 'woo-discount-rules'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>
        <?php else: ?>
            <p class="no-addons"><?php _e("No available addons", 'woo-discount-rules'); ?></p>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
</div>
