<?php

namespace WPPayForm\App\Http\Controllers;

use WPPayForm\App\Services\BackgroundInstaller;

class AddonsController extends Controller
{
    public function installAndActivate()
    {
        $addonSlug = $this->request->get("slug") ? $this->request->get("slug") : '';
        $name = $this->request->get("name") ? $this->request->get("name") : '';
        $title = $this->request->get("title") ? $this->request->get("title") : '';
        $source = $this->request->get("source") ? $this->request->get("source") : '';
        $url = $this->request->get("url") ? $this->request->get("url") : '';

        if ('wordpress' === $source) {
            $this->installFromWordpress($name, $addonSlug, $title);
        } else {
            $this->installFromOutside($name, $addonSlug, $title, $source, $url);
        }
    }

    public function updateFromGithub()
    {
        $addonSlug = $this->request->get("slug") ? $this->request->get("slug") : '';
        $name = $this->request->get("name") ? $this->request->get("name") : '';
        $url = $this->request->get("url") ? $this->request->get("url") : '';
        if(!$addonSlug || !$url) {
            wp_send_json_error([
                'message' => __('Invalid request. Please try again', 'wp-payment-form-pro'),
            ],423);
        }

        $this->updateLatestVersion($addonSlug, $url, $name);
        
    }

    public function updateLatestVersion($slug, $url, $name) {

        // Deleting process start
        // Check if the plugin is active
        $plugin = $slug . '/' . $slug . '.php';
        // First deactivate the plugin
        if (!function_exists('deactivate_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
            deactivate_plugins($plugin);
        } else {
            deactivate_plugins($plugin);
        }
       

        // Delete the plugin
        if (!function_exists('delete_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
            $deleted = delete_plugins([$plugin]);
        } else {
            $deleted = delete_plugins([$plugin]);
        }
    
        // Remove the plugin from the plugins list
        if ($deleted) {
            $plugins = get_option('active_plugins');
            $key = array_search($plugin, $plugins);
            if (false !== $key) {
                unset($plugins[$key]);
                update_option('active_plugins', $plugins);
            }
        }
        // Deleting end

        // Clean up any cached plugin data
        wp_cache_flush();

        // Download the plugin ZIP file
        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            // Handle the error
            wp_send_json_error(
                [
                    'message' => 'Error downloading plugin: ' . $response->get_error_message()
                ],
                423
            );
        }

        // Get the plugin contents from the response
        $plugin_contents = wp_remote_retrieve_body($response);

        // Save the plugin ZIP file to a temporary location
        $temp_file = tempnam(sys_get_temp_dir(),  'plugin');

        if (!$temp_file) {
            // Handle the error
            wp_send_json_error(
                [
                    'message' => 'Error creating temporary.'
                ],
                423
            );
        }

        file_put_contents($temp_file, $plugin_contents);

        // now extarct, rename and activate plugin
        static::renameAndActivatePlugin($slug, $temp_file, $name);
    }

    public function installFromWordpress($name, $addonSlug, $title)
    {
        if (!$addonSlug || !$name || !$title) {
            wp_send_json_error('Wrong addon provided', 423);
        }

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugin_slug = $addonSlug;

        if (!is_plugin_active($plugin_slug . '/' . $plugin_slug . '.php')) {
            $plugins = get_plugins();
            $plugin_file = $plugin_slug . '/' . $plugin_slug . '.php';

            $plugin = [
                'name'      => $title,
                'repo-slug' => $plugin_slug,
                'file'      => $plugin_slug . '.php',
                'redirect_url'  => self_admin_url('admin.php?page=wppayform.php#/gateways/' . $name)
            ];

            if (!isset($plugins[$plugin_file])) {

                (new BackgroundInstaller())->install($plugin);

                wp_send_json_success(
                    [
                        'message'  => 'Successfully enabled ' . $title,
                        'redirect_url' => $plugin['redirect_url']
                    ],
                    200
                );
            } else {
                if (!function_exists('activate_plugin')) {
                    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
                }

                $plugin_activation = activate_plugin($plugin_file);

                if (is_wp_error($plugin_activation)) {
                    // Handle the error
                    $error_message = $plugin_activation->get_error_message();
                    wp_send_json_error($error_message, 423);
                    // ...
                } else {
                    wp_send_json_success([
                        'message'  => 'Successfully enabled ' . $title,
                        'redirect_url' => $plugin['redirect_url']
                    ], 200);
                }
            }
        }

        die();
    }

    public function installFromOutside($name, $addonSlug, $title, $source, $url)
    {

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();
        $plugin_slug = $addonSlug;
        // Check if the plugin is present
        foreach ($plugins as $plugin_file => $plugin_data) {
            // Check if the plugin slug or name matches
            if ($plugin_slug === $plugin_data['TextDomain'] || $plugin_slug === $plugin_data['Name']) {
                if (!function_exists('activate_plugin')) {
                    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
                }
                // Activate the plugin
                $plugin_activation = activate_plugin($plugin_file);
                if (is_wp_error($plugin_activation)) {
                    // Handle the error
                    $error_message = $plugin_activation->get_error_message();
                    wp_send_json_error($error_message, 423);
                    // ...
                }
                wp_send_json_success(
                    [
                        'message'  => 'Successfully enabled ' . $title,
                        'redirect_url' => self_admin_url('admin.php?page=wppayform.php#/gateways/' . $name)
                    ],
                    200
                );
            }
        }

        // If the loop completes without finding the plugin, it is not present

        $this->proccedToInstall($name, $addonSlug, $title, $source, $url);
    }

    public function proccedToInstall($name, $addonSlug, $title, $source, $url)
    {
        $plugin_url = $url;

        if ('' == $plugin_url) {
            wp_send_json_error(['message' => 'No valid url provided to install!'], 423);
        }

        // Download the plugin ZIP file
        $response = wp_remote_get($plugin_url);
        if (is_wp_error($response)) {
            // Handle the error
            wp_send_json_error(
                [
                    'message' => 'Error downloading plugin: ' . $response->get_error_message()
                ],
                423
            );
        }

        // Get the plugin contents from the response
        $plugin_contents = wp_remote_retrieve_body($response);

        // Save the plugin ZIP file to a temporary location
        $temp_file = tempnam(sys_get_temp_dir(),  'plugin');

        if (!$temp_file) {
            // Handle the error
            wp_send_json_error(
                [
                    'message' => 'Error creating temporary.'
                ],
                423
            );
        }

        file_put_contents($temp_file, $plugin_contents);
        // now extarct, rename and activate plugin
        static::renameAndActivatePlugin($addonSlug, $temp_file, $name);
    }

    public static function renameAndActivatePlugin($slug, $tempFile, $name)
    {
        // Extract the plugin ZIP file
        $zip = new \ZipArchive();
        $extracted_path = WP_CONTENT_DIR . '/plugins/';

        if ($zip->open($tempFile) === true) {
            $zip->extractTo($extracted_path);
            // get folder name
            $first_index = 0; // Assuming the first index contains the folder
            $extracted_file_name = $zip->getNameIndex($first_index);
            $extracted_folder_name = basename($extracted_file_name);
            $zip->close();
            // rename to actuall addonSlug
            $new_folder_path = $extracted_path . $slug;

            rename($extracted_path . $extracted_folder_name, $new_folder_path);
            // flushing the wp_cache to recognize the newly added plugin
            wp_cache_flush();
        } else {
            // Handle the error
            echo 'Error extracting plugin ZIP file';
            return;
        }

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // safe activation
        $plugins = get_plugins();
        $plugin_slug = $slug;
        // Check if the plugin is present
        foreach ($plugins as $plugin_file => $plugin_data) {
            // Check if the plugin slug or name matches
            if ($plugin_slug === $plugin_data['TextDomain'] || $plugin_slug === $plugin_data['Name']) {
                if (!function_exists('activate_plugin')) {
                    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
                }
                // Activate the plugin
                $plugin_activation = activate_plugin($plugin_file);
                if (is_wp_error($plugin_activation)) {
                    // Handle the error
                    $error_message = $plugin_activation->get_error_message();
                    wp_send_json_error($error_message, 423);
                    // ...
                }
                wp_send_json_success(
                    [
                        'message'  => 'Successfully installed ' . $name,
                        'redirect_url' => self_admin_url('admin.php?page=wppayform.php#/gateways/' . $name)
                    ],
                    200
                );
            }
        }

        // Plugin activation failed
        wp_send_json_error(
            [
                'message' => 'Error activating plugin: Plugin not found.'
            ],
            423
        );
    }

}
