<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$templates = (array) $this->data->get('templates', []);
if (!$templates)
{
    return;
}
$allowed_html_tags = \FPFramework\Helpers\WPHelper::getAllowedHTMLTags();
$favorites = (array) $this->data->get('favorites', []);
$main_category_label = $this->data->get('main_category_label', fpframework()->_('FPF_CATEGORY'));
$license_key = $this->data->get('license_key', false);
$license_key_status = $this->data->get('license_key_status', 'invalid');
$plugin_license_settings_url = $this->data->get('plugin_license_settings_url', '');
$plugin = $this->data->get('plugin', '');
$plugin_name = $this->data->get('plugin_name', '');
$plugin_license_type = $this->data->get('plugin_license_type', '');
$plugin_version = $this->data->get('plugin_version', '');
$wp_version = get_bloginfo('version');
$parsed_wp_version = strstr($wp_version, '-', true) ? strstr($wp_version, '-', true) : $wp_version;

$capabilities_event_label = fpframework()->_('FPF_EVENT');
$capabilities_solution_label = fpframework()->_('FPF_SOLUTION');
$capabilities_wordpress_label = fpframework()->_('FPF_UPDATE_WORDPRESS');
$capabilities_wordpress_url = admin_url('update-core.php');
$capabilities_plugin_label = sprintf(fpframework()->_('FPF_UPDATE_PLUGIN_X'), $plugin_name);
$capabilities_plugin_url = admin_url('plugins.php');
$install_plugin_url = admin_url('plugin-install.php');

$install_svg_icon = '<svg class="icon" width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 10L2 13L14 13L14 10" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 1C8.5 0.723858 8.27614 0.5 8 0.5C7.72386 0.5 7.5 0.723858 7.5 1L8.5 1ZM7.64645 10.3536C7.84171 10.5488 8.15829 10.5488 8.35355 10.3536L11.5355 7.17157C11.7308 6.97631 11.7308 6.65973 11.5355 6.46447C11.3403 6.2692 11.0237 6.2692 10.8284 6.46447L8 9.29289L5.17157 6.46447C4.97631 6.2692 4.65973 6.2692 4.46447 6.46447C4.2692 6.65973 4.2692 6.97631 4.46447 7.17157L7.64645 10.3536ZM7.5 1L7.5 10L8.5 10L8.5 1L7.5 1Z" fill="currentColor"/></svg>';
$activate_svg_icon = '<svg class="icon" width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 10L2 13L14 13L14 10" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 1C8.5 0.723858 8.27614 0.5 8 0.5C7.72386 0.5 7.5 0.723858 7.5 1L8.5 1ZM7.64645 10.3536C7.84171 10.5488 8.15829 10.5488 8.35355 10.3536L11.5355 7.17157C11.7308 6.97631 11.7308 6.65973 11.5355 6.46447C11.3403 6.2692 11.0237 6.2692 10.8284 6.46447L8 9.29289L5.17157 6.46447C4.97631 6.2692 4.65973 6.2692 4.46447 6.46447C4.2692 6.65973 4.2692 6.97631 4.46447 7.17157L7.64645 10.3536ZM7.5 1L7.5 10L8.5 10L8.5 1L7.5 1Z" fill="currentColor"/></svg>';
$update_svg_icon = '<svg class="icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.50006 4.6001L7.70013 2.80017L9.50006 1.00024" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.60022 2.80029C11.5824 2.80029 14 5.21786 14 8.20008C14 9.79931 13.3048 11.2362 12.2001 12.2249" stroke="currentColor" stroke-linecap="round"/><path d="M6.5 11.7993L8.29993 13.5992L6.5 15.3992" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.39979 13.5989C4.41757 13.5989 2 11.1814 2 8.19915C2 6.59991 2.69522 5.16305 3.79993 4.17432" stroke="currentColor" stroke-linecap="round"/></svg>';
$pro_svg_icon = '<svg class="icon" width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 10C7.5 10.2761 7.72386 10.5 8 10.5C8.27614 10.5 8.5 10.2761 8.5 10L7.5 10ZM8.35355 3.64645C8.15829 3.45118 7.84171 3.45118 7.64645 3.64645L4.46447 6.82843C4.2692 7.02369 4.2692 7.34027 4.46447 7.53553C4.65973 7.7308 4.97631 7.7308 5.17157 7.53553L8 4.70711L10.8284 7.53553C11.0237 7.7308 11.3403 7.7308 11.5355 7.53553C11.7308 7.34027 11.7308 7.02369 11.5355 6.82843L8.35355 3.64645ZM8.5 10L8.5 4L7.5 4L7.5 10L8.5 10Z" fill="currentColor"/><path d="M14 7C14 10.3137 11.3137 13 8 13C4.68629 13 2 10.3137 2 7C2 3.68629 4.68629 1 8 1C11.3137 1 14 3.68629 14 7Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$key_svg_icon = '<svg class="icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="4.81803" cy="11.3135" r="3" transform="rotate(-45 4.81803 11.3135)" stroke="currentColor"/><line x1="6.93933" y1="9.19231" x2="13.3033" y2="2.82835" stroke="currentColor" stroke-linecap="round"/><path d="M12.5962 4.24219L14.0104 5.6564" stroke="currentColor" stroke-linecap="round"/><path d="M10.4749 6.36377L11.182 7.07088" stroke="currentColor" stroke-linecap="round"/></svg>';
$insert_svg_icon = '<svg class="icon" width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 4L3 1L14 1L14 13L3 13L3 10" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 6.5C1.72386 6.5 1.5 6.72386 1.5 7C1.5 7.27614 1.72386 7.5 2 7.5V6.5ZM11.3536 7.35355C11.5488 7.15829 11.5488 6.84171 11.3536 6.64645L8.17157 3.46447C7.97631 3.2692 7.65973 3.2692 7.46447 3.46447C7.2692 3.65973 7.2692 3.97631 7.46447 4.17157L10.2929 7L7.46447 9.82843C7.2692 10.0237 7.2692 10.3403 7.46447 10.5355C7.65973 10.7308 7.97631 10.7308 8.17157 10.5355L11.3536 7.35355ZM2 7.5L11 7.5V6.5L2 6.5V7.5Z" fill="currentColor"/></svg>';
$error_svg_icon = '<svg class="icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.3351 6.19586L14.8335 6.1558C14.8198 5.98487 14.7194 5.8329 14.5676 5.75318C14.4157 5.67346 14.2336 5.67711 14.0851 5.76285L14.3351 6.19586ZM9.26069 9.12581L9.54456 8.7142C9.38565 8.60461 9.17786 8.59628 9.01069 8.6928L9.26069 9.12581ZM7.8715 6.71947L8.1215 7.15248C8.28866 7.05597 8.38534 6.87187 8.3699 6.67947L7.8715 6.71947ZM12.9456 3.78971L13.1956 4.22272C13.3441 4.13697 13.4383 3.98108 13.4452 3.80971C13.4521 3.63834 13.3706 3.47541 13.2294 3.37807L12.9456 3.78971ZM10.8097 5.02286L10.5597 4.58984C10.415 4.67343 10.3215 4.82385 10.3108 4.99068L10.8097 5.02286ZM10.7023 6.68909L10.2033 6.65691C10.1903 6.85835 10.2997 7.04783 10.4807 7.1373L10.7023 6.68909ZM12.199 7.42915L11.9774 7.87735C12.1273 7.95145 12.3042 7.94575 12.449 7.86216L12.199 7.42915ZM12.9741 9.69824C14.2675 8.9515 14.9456 7.54965 14.8335 6.1558L13.8367 6.23593C13.919 7.25948 13.4207 8.2857 12.4741 8.83221L12.9741 9.69824ZM8.97683 9.53742C10.1279 10.3313 11.6808 10.4449 12.9741 9.69824L12.4741 8.83221C11.5276 9.37869 10.3898 9.29714 9.54456 8.7142L8.97683 9.53742ZM4.02698 12.7248L9.51069 9.55882L9.01069 8.6928L3.52698 11.8588L4.02698 12.7248ZM1.44618 12.0333C1.96789 12.937 3.12335 13.2466 4.02698 12.7248L3.52698 11.8588C3.10164 12.1044 2.55777 11.9587 2.3122 11.5333L1.44618 12.0333ZM2.1377 9.45253C1.23407 9.97424 0.924469 11.1297 1.44618 12.0333L2.3122 11.5333C2.06664 11.108 2.21237 10.5641 2.6377 10.3186L2.1377 9.45253ZM7.6215 6.28646L2.1377 9.45253L2.6377 10.3186L8.1215 7.15248L7.6215 6.28646ZM9.23251 3.21753C7.93928 3.96418 7.26126 5.36578 7.3731 6.75946L8.3699 6.67947C8.28777 5.65605 8.78604 4.63 9.73251 4.08356L9.23251 3.21753ZM13.2294 3.37807C12.0784 2.58449 10.5257 2.47093 9.23251 3.21753L9.73251 4.08356C10.6789 3.53714 11.8166 3.61861 12.6618 4.20136L13.2294 3.37807ZM11.0597 5.45587L13.1956 4.22272L12.6956 3.3567L10.5597 4.58984L11.0597 5.45587ZM11.2012 6.72127L11.3087 5.05504L10.3108 4.99068L10.2033 6.65691L11.2012 6.72127ZM12.4206 6.98094L10.9239 6.24089L10.4807 7.1373L11.9774 7.87735L12.4206 6.98094ZM14.0851 5.76285L11.949 6.99614L12.449 7.86216L14.5851 6.62888L14.0851 5.76285Z" fill="currentColor"/></svg>';
$loading_svg_icon = '<svg class="icon loading" width="16" height="16" xmlns="http://www.w3.org/2000/svg" style="shape-rendering: auto;" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><circle cx="50" cy="50" fill="none" stroke="currentColor" stroke-width="10" r="40" stroke-dasharray="160.22122533307947 55.40707511102649"><animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform></circle></svg>';

foreach ($templates as $template_key => $template)
{
    $is_favorite = array_key_exists($template->id, $favorites);
    $image = isset($template->image) ? $template->image : '';

    $external_plugins = $template->fields->external_plugins;

    $item_class = isset($template->css_class) ? trim($template->css_class) : '';

    $valid_wp_version = $valid_item_version = $valid_third_party_plugin_version = false;

    $required_wp_version = isset($template->fields->minimum_wp_version) && !empty($template->fields->minimum_wp_version) ? $template->fields->minimum_wp_version : '';
    $required_item_version = isset($template->fields->minimum_plugin_version) && !empty($template->fields->minimum_plugin_version) ? $template->fields->minimum_plugin_version : '';
    $parsed_required_item_version = strstr($required_item_version, '-', true) ? strstr($required_item_version, '-', true) : $required_item_version;

    $errors = [];

    $capabilities = [
        'pro' => [
            'requirement' => $template->is_pro ? 'pro' : 'lite',
            'detected' => $plugin_license_type === 'lite' ? 'lite' : 'pro'
        ],
        'category' => [
            'value' => $template->category,
            'label' => $main_category_label
        ],
        'event' => [
            'value' => isset($template->filters->event) ? html_entity_decode($template->filters->event) : '',
            'label' => $capabilities_event_label
        ],
        'solution' => [
            'value' => $template->filters->solution,
            'label' => $capabilities_solution_label
        ],
        'wordpress' => [
            'value' => $required_wp_version,
            'label' => $capabilities_wordpress_label,
            'icon' => '',
            'url' => $capabilities_wordpress_url,
            'detected' => $wp_version
        ],
        'plugin' => [
            'value' => $required_item_version,
            'label' => $capabilities_plugin_label,
            'icon' => '',
            'url' => $capabilities_plugin_url,
            'detected' => $plugin_version
        ],
        'third_party_dependencies' => [
            'value' => $external_plugins,
            'errors' => []
        ],
        'license_error' => [
            'value' => ''
        ]
    ];

    /**
     * A template may not be available for the following reasons:
     * - User has an older version than the one specified in the template
     * - Plugin version may be outdated
     * - 3rd-party plugins are required and missing (not installed and/or not activated)
     */

    // WP Version Check
    if ($required_wp_version)
    {
        $valid_wp_version = !empty(trim($required_wp_version)) ? version_compare($parsed_wp_version, $required_wp_version, '>=') : false;
        if (!$valid_wp_version)
        {
            $capabilities['wordpress']['icon'] = 'update';
            $errors['wordpress'] = $capabilities['wordpress'];
            $errors['wordpress']['full_label'] = fpframework()->_('FPF_UPDATE_WORDPRESS_TO_INSERT_TEMPLATE');
        }
    }

    // Item Version Check
    if ($required_item_version)
    {
        $valid_item_version = !empty(trim($required_item_version)) ? version_compare($plugin_version, $parsed_required_item_version, '>=') : false;
        if (!$valid_item_version)
        {
            $capabilities['plugin']['icon'] = 'update';
            $errors['plugin'] = $capabilities['plugin'];
            $errors['plugin']['full_label'] = sprintf(fpframework()->_('FPF_UPDATE_PLUGIN_X_TO_INSERT_TEMPLATE'), $plugin_name);
        }
    }

    // 3rd party plugin Check
    if (is_array($external_plugins) && count($external_plugins))
    {
        foreach ($external_plugins as $key => $external_plugin)
        {
            if (!$external_plugin->slug || !$external_plugin->name || !$external_plugin->version)
            {
                continue;
            }

            $valid_third_party_plugin_version = false;

            $external_plugin_requirement = $external_plugin->version;

            $url = $capabilities_plugin_url;

            $multiplePlugins = [
                $external_plugin->slug
            ];

            if (strpos($external_plugin->slug, "##") !== false)
            {
                $multiplePlugins = explode('##', $external_plugin->slug);
            }

            foreach ($multiplePlugins as $thirdPartyPluginSlug)
            {
                $icon = $label = $full_label = $detected = 'none';
    
                // 3rd-party plugin is not installed
                if (!\FPFramework\Helpers\WPHelper::isPluginInstalled($thirdPartyPluginSlug))
                {
                    $icon = 'install';
                    $label = sprintf(fpframework()->_('FPF_INSTALL_PLUGIN_X'), $external_plugin->name);
                    $full_label = sprintf(fpframework()->_('FPF_INSTALL_PLUGIN_X_TO_INSERT_TEMPLATE'), $external_plugin->name);
                    $url = $install_plugin_url . '?s=' . $external_plugin->name;
                }
                // 3rd-party plugin is installed but not active
                else if (!\is_plugin_active($thirdPartyPluginSlug))
                {
                    $icon = 'activate';
                    $label = sprintf(fpframework()->_('FPF_ACTIVATE_PLUGIN_X'), $external_plugin->name);
                    $full_label = sprintf(fpframework()->_('FPF_ACTIVATE_PLUGIN_X_TO_INSERT_TEMPLATE'), $external_plugin->name);
                }
                // 3rd-party plugin is installed, active but we need to check whether its version is valid
                else
                {
                    $third_party_plugin_installed_version = \FPFramework\Helpers\WPHelper::getThirdPartyPluginVersion($thirdPartyPluginSlug);
                    $parsed_required_third_party_plugin_version = strstr($external_plugin_requirement, '-', true) ? strstr($external_plugin_requirement, '-', true) : $external_plugin_requirement;
                    $valid_third_party_plugin_version = !empty(trim($parsed_required_third_party_plugin_version)) ? version_compare($third_party_plugin_installed_version, $parsed_required_third_party_plugin_version, '>=') : false;
                    
                    $icon = 'update';
                    $label = sprintf(fpframework()->_('FPF_UPDATE_PLUGIN_X'), $external_plugin->name);
                    $full_label = sprintf(fpframework()->_('FPF_UPDATE_PLUGIN_X_TO_INSERT_TEMPLATE'), $external_plugin->name);
                    $detected = $third_party_plugin_installed_version;
                }
    
                // Set third party item information
                $capabilities['third_party_dependencies']['value'][$key]->icon = $icon;
                $capabilities['third_party_dependencies']['value'][$key]->label = $label;
                $capabilities['third_party_dependencies']['value'][$key]->full_label = $full_label;
                $capabilities['third_party_dependencies']['value'][$key]->detected = $detected;
                $capabilities['third_party_dependencies']['value'][$key]->url = $url;
                $capabilities['third_party_dependencies']['value'][$key]->valid = $valid_third_party_plugin_version;
                $capabilities['third_party_dependencies']['value'][$key]->version = $external_plugin_requirement;
            }

            if (!$valid_third_party_plugin_version)
            {
                // Add the external plugins we are having issues with
                $capabilities['third_party_dependencies']['errors'][] = $external_plugin->name;
                
                // Set error index used to retrieve this error message action
                $capabilities['third_party_dependencies']['error_index'] = $key;

                // Add the error
                $errors['third_party_dependencies_' . $key] = $capabilities['third_party_dependencies'];
            }
        }
    }

    /**
     * Check other cases where a template may not be available:
     * - Is Pro but we have the Free version
     * - Is Pro and template is Pro
     *   - We have not entered a license key OR We have entered a license key but it's not valid
     */

    // Is Pro but we have the Free version
    if ($plugin_license_type === 'lite' && $template->is_pro)
    {
        $errors = [
            'pro' => [
                'icon' => 'pro',
                'class' => 'fpf-modal-opener red',
                'data_attributes' => 'data-fpf-modal-item="' . esc_attr($template->title) . '" data-fpf-modal="#fpfUpgradeToPro" data-fpf-plugin="' . esc_attr($plugin_name) . '"',
                'label' => fpframework()->_('FPF_UPGRADE_TO_UC_PRO'),
                'full_label' => fpframework()->_('FPF_UPGRADE_TO_PRO_TO_UNLOCK_TEMPLATE')
            ]
        ] + $errors;
    }
    // Is Pro and template is Pro
    else if ($plugin_license_type === 'pro' && $template->is_pro && (empty($license_key) || $license_key_status !== 'valid'))
    {
        // We have not entered a license key
        if (empty($license_key))
        {
            $errors['license'] = [
                'icon' => 'key',
                'url' => $plugin_license_settings_url,
                'label' => fpframework()->_('FPF_SET_LICENSE_KEY'),
                'full_label' => fpframework()->_('FPF_NO_LICENSE_KEY_DETECTED'),
            ];
            $capabilities['license_error']['value'] = 'missing';
        }
        // We have entered a license key but it's invalid/expired
        else if ($license_key_status !== 'valid')
        {
            $errors['license'] = [
                'icon' => 'key',
                'url' => $plugin_license_settings_url,
                'label' => fpframework()->_('FPF_INVALID_EXPIRED_LICENSE_KEY'),
                'full_label' => fpframework()->_('FPF_INVALID_LICENSE_KEY_ENTERED')
            ];
            $capabilities['license_error']['value'] = 'expired';
        }
    }

    if ($errors)
    {
        $item_class .= ' has-errors';

        // If its a PRO template and we are not a PRO user, add a "is-pro" CSS class
        if ($template->is_pro && $plugin_license_type === 'lite')
        {
            $item_class .= ' is-pro';
        }
    }
    ?>
    <div
        class="fpf-library-item <?php esc_attr_e($item_class); ?>"
        data-id="<?php esc_attr_e($template->id); ?>"
        data-note="<?php esc_attr_e($template->fields->note); ?>"

        <?php
        foreach ($template->sort as $sort_key => $sort_value)
        {
            ?>data-sort-<?php esc_html_e($sort_key); ?>="<?php esc_attr_e($sort_value); ?>"<?php
        }
        ?>

        data-filter-category="<?php esc_attr_e($template->category); ?>"
        <?php foreach ($template->filters as $filter_key => $filter_value): ?>
            data-filter-<?php esc_attr_e($filter_key); ?>="<?php esc_attr_e($filter_value); ?>"
        <?php endforeach; ?>

        <?php if ($plugin_license_type === 'lite'): ?>
        data-filter-compatibility="<?php echo $template->is_pro ? 'Pro' : 'Free'; ?>"
        <?php endif; ?>

        data-title="<?php esc_attr_e($template->title); ?>"
        data-capabilities="<?php echo htmlspecialchars(wp_json_encode($capabilities), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="fpf-library-item-wrap">
            <div class="fpf-template-item-message fpf-alert callout is-hidden">
                <span class="fpf-template-item-message-text text"></span>
                <button class="fpf-library-messages-hide-btn close-button" aria-label="Dismiss alert" type="button" data-close>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="fpf-library-item-image-wrapper">
                <div class="fpf-library-item-image-inner">
                    <img loading="lazy" src="<?php echo esc_url($image); ?>" alt="<?php esc_attr_e($template->title); ?>" />

                    <div class="fpf-library-item-hover">
                        <a href="#templates-library-previewer" class="fpf-button outline fpf-library-preview-item fpf-modal-opener" data-fpf-modal-prevent="false" data-fpf-modal="#fpf-library-preview-popup" title="<?php esc_attr_e(fpframework()->_('FPF_PREVIEW_TEMPLATE')); ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11.5" cy="11.5" r="6" stroke="currentColor"/>
                                <line x1="15.7071" y1="16" x2="19" y2="19.2929" stroke="currentColor" stroke-linecap="round"/>
                                <line x1="11.5" y1="9" x2="11.5" y2="14" stroke="currentColor"/>
                                <line x1="14" y1="11.5" x2="9" y2="11.5" stroke="currentColor"/>
                            </svg>
                            <span><?php esc_html_e(fpframework()->_('FPF_PREVIEW')); ?></span>
                        </a>
                        <?php if (isset($errors['wordpress']) || isset($errors['plugin']) || isset($errors['third_party_dependencies_0'])): ?>
                        <div class="dependencies-wrapper">
                            <div class="title"><?php esc_html_e(fpframework()->_('FPF_REQUIREMENTS')); ?></div>
                            <div class="dependencies">
                                <?php
                                if (array_key_exists('wordpress', $errors))
                                {
                                    ?><span class="fpf-modal-opener error" data-fpf-modal="#fpf-library-item-info-popup"><?php esc_html_e(fpframework()->_('FPF_WP') . ' ' . ($required_wp_version ? $required_wp_version : $wp_version)); ?></span><?php
                                }
                                if (array_key_exists('plugin', $errors))
                                {
                                    ?><span class="fpf-modal-opener error" data-fpf-modal="#fpf-library-item-info-popup"><?php esc_html_e($plugin_name . ' ' . ($required_item_version ? $required_item_version : $plugin_version)); ?></span><?php
                                }
                                if (is_array($external_plugins) && count($external_plugins))
                                {
                                    foreach ($external_plugins as $external_plugin_key => $external_plugin)
                                    {
                                        if (!array_key_exists('third_party_dependencies_' . $external_plugin_key, $errors) || !array_key_exists('errors', $errors['third_party_dependencies_' . $external_plugin_key]) || !in_array($external_plugin->name, $errors['third_party_dependencies_' . $external_plugin_key]['errors']))
                                        {
                                            continue;
                                        }
                                        
                                        ?><span class="fpf-modal-opener error" data-fpf-modal="#fpf-library-item-info-popup"><?php esc_html_e($external_plugin->name . ' ' . $external_plugin->version); ?></span><?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                if ($template->is_pro && $plugin_license_type === 'lite')
                {
                    ?>
                    <span class="ribbon"><?php esc_html_e(fpframework()->_('FPF_PRO')); ?></span>
                    <?php
                }
                ?>
            </div>
            <div class="fpf-library-item-bottom">
                <div class="template-label"><?php esc_html_e($template->title); ?></div>
                <div class="fpf-library-item-bottom-buttons">
                    <a href="#" data-template-id="<?php esc_attr_e($template->id); ?>" class="info fpf-modal-opener fpf-library-template-item-info" title="<?php esc_attr_e(fpframework()->_('FPF_TEMPLATE_INFORMATION')); ?>" data-fpf-modal="#fpf-library-item-info-popup">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="8" cy="8" r="7" stroke="currentColor"/>
                            <rect x="7" y="7" width="2" height="5" fill="currentColor"/>
                            <rect x="7" y="4" width="2" height="2" fill="currentColor"/>
                        </svg>
                    </a>
                    <a href="#" class="fpf-library-favorite-icon fpf-library-favorite-item<?php echo $is_favorite ? ' active' : ''; ?>" title="<?php esc_attr_e(fpframework()->_('FPF_LIBRARY_SAVE_TEMPLATE_FAVORITES')); ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.902 6.62124C14.3943 9.04222 11.0187 11.1197 7.99845 14C4.97819 11.1197 1.60265 9.04223 1.09492 6.62125C0.231957 2.50649 5.47086 -0.0322558 7.99845 4.12617C10.7204 -0.0322523 15.7649 2.50648 14.902 6.62124Z" stroke="currentColor" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <div class="fpf-library-item-actions">
                    <?php
                    // We have errors with this template
                    if (!empty($errors))
                    {
                        // Multiple errors, but do not show this message when we still need to Upgrade to Pro.
                        if (count($errors) > 1 && !isset($errors['pro']))
                        {
                            ?>
                            <a href="#" class="fpf-modal-opener" data-fpf-modal="#fpf-library-item-info-popup" data-template-id="<?php esc_attr_e($template->id); ?>">
                                <?php
                                echo wp_kses($error_svg_icon, $allowed_html_tags);
                                esc_html_e(fpframework()->_('FPF_MULTIPLE_ISSUES_DETECTED'));
                                ?>
                            </a>
                            <?php
                        }
                        // One error
                        else
                        {
                            $error_keys = array_keys($errors);
                            $error_values = array_values($errors);
                            $error_items = [$error_values[0]];

                            foreach ($error_items as $error_item)
                            {
                                // 3rd-party dependency has an array of plugins
                                if (isset($error_item['value']) && is_array($error_item['value']) && isset($error_item['error_index']))
                                {
                                    $error_item = (array) $error_item['value'][$error_item['error_index']];
                                }
                                
                                $class = isset($error_item['class']) ? $error_item['class'] : '';
                                $data_atts = isset($error_item['data_attributes']) ? ' ' . $error_item['data_attributes'] : '';
                                $url = isset($error_item['url']) ? $error_item['url'] : '#';
                                ?>
                                <a href="<?php echo esc_url($url); ?>" class="<?php esc_attr_e($class); ?>" target="_blank"<?php echo wp_kses($data_atts, $allowed_html_tags); ?>>
                                    <?php echo wp_kses(${$error_item['icon'] . '_svg_icon'}, $allowed_html_tags); ?>
                                    <?php if (isset($error_item['full_label'])): ?>
                                        <span class="full-label"><?php esc_html_e($error_item['full_label']); ?></span>
                                    <?php endif; ?>
                                    <span class="label"><?php esc_html_e($error_item['label']); ?></span>
                                </a>
                                <?php
                            }
                        }
                    }
                    // No errors, we can use the template
                    else
                    {
                        ?>
                        <a href="#" class="fpf-library-item-insert-btn" data-template-id="<?php esc_attr_e($template->id); ?>">
                            <?php
                            echo wp_kses($insert_svg_icon, $allowed_html_tags);
                            echo wp_kses($loading_svg_icon, $allowed_html_tags);
                            ?>
                            <span class="full-label"><?php esc_html_e(fpframework()->_('FPF_INSERT_TEMPLATE')); ?></span>
                            <span class="label"><?php esc_html_e(fpframework()->_('FPF_INSERT')); ?></span>
                        </a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="info-popup-actions">
                <?php
                // Show errors
                if ($errors)
                {
                    foreach ($errors as $error_key => $error)
                    {
                        // 3rd-party dependency has an array of plugins
                        if (isset($error['value']) && is_array($error['value']) && isset($error['error_index']))
                        {
                            $error = (array) $error['value'][$error['error_index']];
                        }
                        
                        $url = isset($error['url']) ? $error['url'] : '#';
                        $class = isset($error['class']) ? $error['class'] : '';
                        $data_atts = isset($error['data_attributes']) ? ' ' . $error['data_attributes'] : '';
                        if ($error['icon'] !== 'pro')
                        {
                            $class .= ' orange';
                        }

                        // Add error key (which capability this error corresponds to, i.e. wordpress, pro, etc...)
                        $class .= ' ' . $error_key;
                        ?>
                        <a href="<?php echo esc_url($url); ?>" target="_blank" class="fpf-button outline <?php esc_attr_e($class); ?>"<?php echo wp_kses($data_atts, $allowed_html_tags); ?>>
                            <?php echo wp_kses(${$error['icon'] . '_svg_icon'}, $allowed_html_tags); ?>
                            <span class="label"><?php esc_html_e($error['label']); ?></span>
                        </a>
                        <?php
                    }
                }
                // Show insert button
                else
                {
                    ?>
                    <a href="#" class="fpf-button outline blue fpf-library-item-insert-btn" data-template-id="<?php esc_attr_e($template->id); ?>">
                        <?php
                        echo wp_kses($insert_svg_icon, $allowed_html_tags);
                        echo wp_kses($loading_svg_icon, $allowed_html_tags);
                        esc_html_e(fpframework()->_('FPF_INSERT_TEMPLATE_NOW'));
                        ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}