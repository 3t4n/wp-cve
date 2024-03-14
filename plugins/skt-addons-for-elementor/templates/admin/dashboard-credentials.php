<?php

/**
 * Dashboard credentials tab template
 */

defined('ABSPATH') || die();

$credential_list = self::get_credentials();
$credential_data = skt_addons_elementor_get_credentials();
$has_pro = skt_addons_elementor_has_pro();

?>
<div class="skt-dashboard-panel">
    <div class="skt-dashboard-panel__header">
        <div class="skt-dashboard-panel__header-content">
            <p class="f16"><?php printf(esc_html__('You can enter credentials from here. %sAfter changing any input make sure to click the Save Changes button.%s', 'skt-addons-elementor'), '<strong>', '</strong>'); ?></p>
        </div>
    </div>

    <div class="skt-dashboard-credentials">
        <?php
        foreach ($credential_list as $cred_key => $cred_data) :
            $title = isset($cred_data['title']) ? $cred_data['title'] : '';
            $help = isset($cred_data['help']) ? $cred_data['help'] : '';
            $icon = isset($cred_data['icon']) ? $cred_data['icon'] : '';
            $is_pro = isset($cred_data['is_pro']) && $cred_data['is_pro'] ? true : false;
            $is_placeholder = $is_pro && !skt_addons_elementor_has_pro();
            $class_attr = 'skt-dashboard-credentials__item';

            $fields = isset($cred_data['fiels']) ? $cred_data['fiels'] : '';

            if ($is_pro) {
                $class_attr .= ' item--is-pro';
            }

            $checked = '';

            // if ( ! in_array( $cred_key, $inactive_features ) ) {
            //     $checked = 'checked="checked"';
            // }

            if ($is_placeholder) {
                $class_attr .= ' item--is-placeholder';
                $checked = 'disabled="disabled"';
            }
        ?>
            <div class="<?php echo esc_attr($class_attr); ?>">
                <div class="skt-dashboard-credentials__item-title-wrap">
                    <?php if ($is_pro) : ?>
                        <span class="skt-dashboard-credentials__item-badge"><?php esc_html_e('Pro', 'skt-addons-elementor'); ?></span>
                    <?php endif; ?>
                    <span class="skt-dashboard-credentials__item-icon"><i class="<?php echo esc_attr($icon); ?>"></i></span>
                    <h3 class="skt-dashboard-credentials__item-title">
                        <label for="skt-widget-<?php echo esc_attr($cred_key); ?>" <?php echo esc_attr($is_placeholder) ? 'data-tooltip="Get pro"' : ''; ?>>
                            <?php echo esc_html($title); ?>
                        </label>
                    </h3>
                </div>
                <div class="skt-dashboard-credentials__item-input-wrap">
                    <?php foreach ($fields as $key => $value) : ?>
                        <div class="skt-dashboard-credentials__item-input">
                            <label for="skt-widget-<?php echo esc_attr($cred_key . '-' . $value['name']); ?>">
                                <?php echo esc_html($value['label']); ?>
                                <?php if (!empty($value['help'])) : ?>
                                    <a href="<?php echo esc_url($value['help']['link']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($value['help']['instruction']); ?></a>
                                <?php endif; ?>
                            </label>
                            <?php if ($value['type'] == 'textarea') : ?>
                                <textarea id="skt-widget-<?php echo esc_attr($cred_key); ?>" <?php echo esc_attr($checked); ?> class="skt-credential" name="credentials[<?php echo esc_attr($cred_key); ?>][<?php echo esc_attr($value['name']); ?>]" cols="30" rows="10"><?php echo esc_attr(isset($credential_data[$cred_key][$value['name']]) ? $credential_data[$cred_key][$value['name']] : ''); ?></textarea>
                            <?php else : ?>
                                <input id="skt-widget-<?php echo esc_attr($cred_key . '-' . $value['name']); ?>" <?php echo esc_attr($checked); ?> type="<?php echo esc_attr($value['type']); ?>" class="skt-credential" name="credentials[<?php echo esc_attr($cred_key); ?>][<?php echo esc_attr($value['name']); ?>]" value="<?php echo esc_attr(isset($credential_data[$cred_key][$value['name']]) ? $credential_data[$cred_key][$value['name']] : ''); ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
    <div class="skt-dashboard-panel__footer">
        <button disabled class="skt-dashboard-btn skt-dashboard-btn--save" type="submit"><?php esc_html_e('Save Settings', 'skt-addons-elementor'); ?></button>
    </div>
</div>