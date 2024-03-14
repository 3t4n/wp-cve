<?php


class WpSaioAjax
{
    private static $_instance = null;

    public function __construct()
    {
        add_action('wp_ajax_wpsaio_choose_apps_settings', array($this, 'set_choose_apps_settings'));
        add_action('wp_ajax_wpsaio_design_settings', array($this, 'set_design_settings'));
        add_action('wp_ajax_wpsaio_display_settings', array($this, 'set_display_settings'));
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function set_choose_apps_settings()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'wpsaio_nonce')) {
            die('Permission Denied.');
        }

        $form_data = isset($_POST['data']['formDataArray']) ? WpSaioHelper::sanitize_array($_POST['data']['formDataArray']) : [];
        $data = [];

        foreach ($form_data as $app) {
            $data[$app['name']]['params'] = [
                $app['key'] => $app['value'],
                'state' => $app['state'],
                'custom-app-title' => $app['customAppTitle'],
                'url-icon' => $app['urlIcon'],
                'color-icon' => $app['colorIcon']
            ];
        }
        $default_apps = WpSaio::getMessagingApps();
        $custom_apps = [];
        foreach ($data as $key => $value) {
            if ( !in_array($key, array_keys($default_apps))) {
                $replace_key = str_replace('-', '_', $key);
                $custom_app = [];
                $custom_app[$key]['icon'] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M344.476 105.328L1.004 448.799 64.205 512l343.472-343.471-63.201-63.201zm-53.882 96.464l53.882-53.882 20.619 20.619-53.882 53.882-20.619-20.619zM410.885 78.818l37.657-37.656 21.29 21.29-37.656 37.657zM405.99 274.144l21.29-21.29 38.367 38.366-21.29 21.29zM198.501 66.642l21.29-21.29 38.13 38.127-21.292 21.291zM510.735 163.868h-54.289v30.111H510.996v-30.111zM317.017.018v54.289h30.111V0z"/></svg>';
                $custom_app[$key]['title'] =  isset($data[$key]['params']['custom-app-title']) ? $data[$key]['params']['custom-app-title'] : $key;
                $custom_app[$key]['shortcode'] =  "wpsaio_$replace_key";
                $custom_app[$key]['params']['url'] =  $default_apps['custom-app']['params']['url'];
                $custom_apps[$key] = $custom_app[$key];
            }
        }

        //add check here remove those elements in $default_apps which do not exist in $custom_apps
        foreach (array_keys($default_apps) as $default_app_key) {
            if (str_contains($default_app_key, 'custom-app-') && !array_key_exists($default_app_key, $data)) {
                unset($default_apps[$default_app_key]);
            }
        }

        update_option('njt_wp_saio_default_apps', array_merge($default_apps, $custom_apps));

        update_option('njt_wp_saio', $data);
        return true;
    }

    public function set_design_settings()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'wpsaio_nonce')) {
            die('Permission Denied.');
        }

        $enable_plugin = sanitize_text_field($_POST['data']['enablePlugin']);
        $style = sanitize_text_field($_POST['data']['style']);
        $tooltip = sanitize_text_field($_POST['data']['toolTip']);
        $widget_position = sanitize_text_field($_POST['data']['widgetPosition']);
        $padding_from_bottom = sanitize_text_field($_POST['data']['paddingFromBottom']);
        $button_icon = sanitize_text_field($_POST['data']['buttonIcon']);
        $button_image = sanitize_text_field($_POST['data']['buttonImage']);
        $button_color = sanitize_text_field($_POST['data']['buttonColor']);

        update_option('wpsaio_enable_plugin', $enable_plugin);
        update_option('wpsaio_style', $style);
        update_option('wpsaio_tooltip', $tooltip);
        update_option('wpsaio_widget_position', $widget_position);
        update_option('wpsaio_bottom_distance', $padding_from_bottom);
        update_option('wpsaio_button_icon', $button_icon);
        update_option('wpsaio_button_image', $button_image);
        update_option('wpsaio_button_color', $button_color);

        return true;
    }

    public function set_display_settings()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'wpsaio_nonce')) {
            die('Permission Denied.');
        }

        $show_on_desktop = sanitize_text_field($_POST['data']['showOnDesktop']);
        $show_on_mobile = sanitize_text_field($_POST['data']['showOnMobile']);
        $display_condition = sanitize_text_field($_POST['data']['displayCondition']);
        $includes_pages = isset($_POST['data']['includesPagesArray']) ? WpSaioHelper::sanitize_array($_POST['data']['includesPagesArray']) : [];
        $excludes_pages = isset($_POST['data']['excludesPagesArray']) ? WpSaioHelper::sanitize_array($_POST['data']['excludesPagesArray']) : [];

        update_option('wpsaio_show_on_desktop', $show_on_desktop);
        update_option('wpsaio_show_on_mobile', $show_on_mobile);
        update_option('wpsaio_display_condition', $display_condition);
        update_option('wpsaio_includes_pages', $includes_pages);
        update_option('wpsaio_excludes_pages', $excludes_pages);

        return true;
    }
}
