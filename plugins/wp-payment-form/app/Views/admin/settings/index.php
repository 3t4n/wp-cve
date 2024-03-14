<?php

use WPPayForm\App\Modules\Builder\Helper;
use WPPayForm\Framework\Support\Arr;

?>
<?php do_action('wppayform_global_menu'); ?>
<?php $assetUrl = WPPAYFORM_URL . 'assets/images/'; ?>

<div class="payform_admin wpf_content_wrapper_parent_v2">
    <div class="payform_section_header">
        <h2 class="payform_gateway_title">
            <?php _e('Paymattic Global Settings', 'wp-payment-form'); ?>
        </h2>
    </div>
    <div class="wpf_content_wrapper_v2 gap-20">
        <?php do_action('wppayform_before_global_settings_wrapper'); ?>
        <div class="wppayform_sidebar_v2 border-radius-8">
            <ul class="wppayform_sidebar_list_v2" style="margin-top: 0px;">
                <li class="<?php echo Helper::getHtmlElementClass('settings', $currentComponent); ?>">
                    <a data-hash="settings" href="<?php echo Helper::makeMenuUrl('wppayform_settings', [
                        'hash' => 'settings'
                    ]); ?>">
                        <?php echo '<img src="' . $assetUrl . 'form/settings.svg" />'; ?>
                        <p>
                            <?php echo __('General Settings') ?>
                        </p>
                    </a>
                </li>
                <?php foreach ($components as $componentName => $component): ?>
                    <li class="<?php echo esc_attr(Helper::getHtmlElementClass($component['hash'], $currentComponent)); ?> wppayform_item_<?php echo esc_attr($componentName); ?>" data-wppayform-settings-list="">
                        <a data-settings_key="<?php echo Arr::get($component, 'settings_key'); ?>"
                            data-component="<?php echo Arr::get($component, 'component', ''); ?>"
                            data-hash="<?php echo Arr::get($component, 'hash', ''); ?>"
                            href="<?php echo esc_url(Helper::makeMenuUrl('wppayform_settings', $component)); ?>">
                            <?php
                            $componentTitle = strtolower($component['title']);
                            $img = Arr::get($component, 'svg', '') ? Arr::get($component, 'svg', '') : '<img class="el-icon-discount" src="' . $assetUrl . 'integrations/' . $componentTitle . '.svg' . '"/>';
                            echo wp_kses_post($img);
                            ?>

                            <p>
                                <?php echo $component['title'] ?>
                            </p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="wppayform_main_content_v2 border-radius-8">
            <?php
            do_action('wppayform_global_settings_component_' . $currentComponent);
            ?>
        </div>
        <?php do_action('wppayform_after_global_settings_wrapper'); ?>
    </div>
</div>

<script>
    var settingsListItems = document.querySelectorAll('[data-wppayform-settings-list]');
    settingsListItems.forEach(function (item) {
        item.addEventListener('click', function () {
            if (typeof jQuery !== 'undefined') {
                jQuery('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });
    });
</script>