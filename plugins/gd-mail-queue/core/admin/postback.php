<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_admin_postback {
    public function __construct() {
        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gd-mail-queue-settings') {
            $this->settings();
        }

        if (isset($_POST['option_page']) && $_POST['option_page'] == 'gd-mail-queue-tools') {
            $this->tools();
        }

        do_action('gdmaq_admin_postback_handler');
    }

    private function save_settings($panel) {
        d4p_includes(array(
            array('name' => 'settings', 'directory' => 'admin'),
            array('name' => 'walkers', 'directory' => 'admin'),
            array('name' => 'functions', 'directory' => 'admin')
        ), GDMAQ_D4PLIB);

        include(GDMAQ_PATH.'core/admin/options.php');

        $scope = $_REQUEST['gdmaq_scope'];

        $options = new gdmaq_admin_settings();
        $settings = $options->settings($panel);

        $processor = new d4pSettingsProcess($settings);
        $processor->base = 'gdmaqvalue';

        $data = $processor->process();

        if ($scope == 'blog') {
            foreach ($data as $group => $values) {
                if (!empty($group)) {
                    foreach ($values as $name => $value) {
                        $value = apply_filters('gdmaq_save_settings_value', $value, $name, $group);

                        gdmaq_settings()->set($name, $value, $group);
                    }

                    gdmaq_settings()->save($group);
                }
            }
        }

        do_action('gdmaq_save_settings_'.$panel);
        do_action('gdmaq_saved_the_settings');
    }

    private function settings() {
        check_admin_referer('gd-mail-queue-settings-options');

        $this->save_settings(gdmaq_admin()->panel);

        wp_redirect(gdmaq_admin()->current_url().'&message=saved');
        exit;
    }

    private function tools() {
        check_admin_referer('gd-mail-queue-tools-options');

        $data = $_POST['gdmaqtools'];
        $panel = $data['panel'];
        $message = '';

        if ($panel == 'import') {
            if (is_uploaded_file($_FILES['import_file']['tmp_name'])) {
                $raw = file_get_contents($_FILES['import_file']['tmp_name']);
                $data = json_decode($raw, true);

                if (is_array($data)) {
                    gdmaq_settings()->import_from_object($data);

                    $message = '&message=imported';
                }
            }
        } else if ($panel == 'remove') {
            $remove = isset($data['remove']) ? (array)$data['remove'] : array();

            if (empty($remove)) {
                $message = '&message=nothing-removed';
            } else {
                if (isset($remove['settings']) && $remove['settings'] == 'on') {
                    gdmaq_settings()->remove_plugin_settings_by_group('info');
                    gdmaq_settings()->remove_plugin_settings_by_group('settings');
                    gdmaq_settings()->remove_plugin_settings_by_group('cleanup');
                    gdmaq_settings()->remove_plugin_settings_by_group('queue');
                    gdmaq_settings()->remove_plugin_settings_by_group('htmlfy');
                }

                if (isset($remove['statistics']) && $remove['statistics'] == 'on') {
                    gdmaq_settings()->remove_plugin_settings_by_group('statistics');
                }

                if (isset($remove['cron']) && $remove['cron'] == 'on') {
                    d4p_remove_cron('gdmaq_queue');
                    d4p_remove_cron('gdmaq_maintenance');
                }

                if (isset($remove['drop']) && $remove['drop'] == 'on') {
                    require_once(GDMAQ_PATH.'core/admin/install.php');

                    gdmaq_drop_database_tables();

                    if (!isset($remove['disable'])) {
                        gdmaq_settings()->mark_for_update();
                    }
                } else if (isset($remove['truncate']) && $remove['truncate'] == 'on') {
                    require_once(GDMAQ_PATH.'core/admin/install.php');

                    gdmaq_truncate_database_tables();
                }

                if (isset($remove['disable']) && $remove['disable'] == 'on') {
                    deactivate_plugins('gd-mail-queue/gd-mail-queue.php', false, false);

                    wp_redirect(admin_url('plugins.php'));
                    exit;
                }

                $message = '&message=removed';
            }
        }

        wp_redirect(gdmaq_admin()->current_url().$message);
        exit;
    }
}
