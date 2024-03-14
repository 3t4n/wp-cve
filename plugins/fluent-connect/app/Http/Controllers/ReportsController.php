<?php

namespace FluentConnect\App\Http\Controllers;

use FluentConnect\App\Models\ActionLog;
use FluentConnect\App\Models\FeedRunner;
use FluentConnect\App\Models\Integration;
use FluentConnect\Framework\Request\Request;
use FluentConnect\Framework\Support\Arr;

class ReportsController extends Controller
{
    public function dashboardStat(Request $request)
    {
        // Check if ThriveCart is configured or not
        $hasThriveCart = !!Integration::where('provider', 'thrivecart')->first();
        $requireSetup = !$hasThriveCart || !defined('FLUENTCRM_PLUGIN_VERSION');

        $returnData = [
            'has_thrivecart' => $hasThriveCart,
            'has_fluentcrm'  => defined('FLUENTCRM_PLUGIN_VERSION'),
            'require_setup'  => $requireSetup,
            'stats' => false
        ];

        if(!$requireSetup) {
            $returnData['stats'] = [
                'runners_count' => FeedRunner::count(),
                'actions_count' => ActionLog::count()
            ];
        }

        return $returnData;
    }

    public function installFluentCRM(Request $request)
    {
        if (defined('FLUENTCRM_PLUGIN_VERSION')) {
            return [
                'message' => 'FluentCRM already exist. No need to install again'
            ];
        }

        $plugin_id = 'fluent-crm';
        $plugin = [
            'name'      => 'FluentCRM',
            'repo-slug' => 'fluent-crm',
            'file'      => 'fluent-crm.php',
        ];
        try {
            $this->backgroundInstaller($plugin, $plugin_id);
        } catch (\Exception $exception) {
            return $this->sendError([
                'message' => $exception->getMessage()
            ]);
        }

        return [
            'message' => 'FluentCRM has been successfully installed and activated'
        ];

    }


    private function backgroundInstaller($plugin_to_install, $plugin_id)
    {
        if (!empty($plugin_to_install['repo-slug'])) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            require_once ABSPATH . 'wp-admin/includes/plugin.php';

            WP_Filesystem();

            $skin = new \Automatic_Upgrader_Skin();
            $upgrader = new \WP_Upgrader($skin);
            $installed_plugins = array_reduce(array_keys(\get_plugins()), array($this, 'associate_plugin_file'), array());
            $plugin_slug = $plugin_to_install['repo-slug'];
            $plugin_file = isset($plugin_to_install['file']) ? $plugin_to_install['file'] : $plugin_slug . '.php';
            $installed = false;
            $activate = false;

            // See if the plugin is installed already.
            if (isset($installed_plugins[$plugin_file])) {
                $installed = true;
                $activate = !is_plugin_active($installed_plugins[$plugin_file]);
            }

            // Install this thing!
            if (!$installed) {
                // Suppress feedback.
                ob_start();

                try {
                    $plugin_information = plugins_api(
                        'plugin_information',
                        array(
                            'slug'   => $plugin_slug,
                            'fields' => array(
                                'short_description' => false,
                                'sections'          => false,
                                'requires'          => false,
                                'rating'            => false,
                                'ratings'           => false,
                                'downloaded'        => false,
                                'last_updated'      => false,
                                'added'             => false,
                                'tags'              => false,
                                'homepage'          => false,
                                'donate_link'       => false,
                                'author_profile'    => false,
                                'author'            => false,
                            ),
                        )
                    );

                    if (is_wp_error($plugin_information)) {
                        throw new \Exception($plugin_information->get_error_message());
                    }

                    $package = $plugin_information->download_link;
                    $download = $upgrader->download_package($package);

                    if (is_wp_error($download)) {
                        throw new \Exception($download->get_error_message());
                    }

                    $working_dir = $upgrader->unpack_package($download, true);

                    if (is_wp_error($working_dir)) {
                        throw new \Exception($working_dir->get_error_message());
                    }

                    $result = $upgrader->install_package(
                        array(
                            'source'                      => $working_dir,
                            'destination'                 => WP_PLUGIN_DIR,
                            'clear_destination'           => false,
                            'abort_if_destination_exists' => false,
                            'clear_working'               => true,
                            'hook_extra'                  => array(
                                'type'   => 'plugin',
                                'action' => 'install',
                            ),
                        )
                    );

                    if (is_wp_error($result)) {
                        throw new \Exception($result->get_error_message());
                    }

                    $activate = true;

                } catch (\Exception $e) {
                }

                // Discard feedback.
                ob_end_clean();
            }

            wp_clean_plugins_cache();

            // Activate this thing.
            if ($activate) {
                try {
                    $result = activate_plugin($installed ? $installed_plugins[$plugin_file] : $plugin_slug . '/' . $plugin_file);
                    if (is_wp_error($result)) {
                        throw new \Exception($result->get_error_message());
                    }
                } catch (\Exception $e) {
                }
            }
        }
    }
}


