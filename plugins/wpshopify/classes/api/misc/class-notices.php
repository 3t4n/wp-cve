<?php

namespace ShopWP\API\Misc;

use ShopWP\Options;
use ShopWP\Messages;
use ShopWP\Transients;
use ShopWP\Utils\Data as Data;

if (!defined('ABSPATH')) {
    exit();
}

class Notices extends \ShopWP\API
{
    public $plugin_settings;
    public $DB_Settings_General;
    public $Backend;
    public $DB_Settings_Syncing;

    public function __construct(
        $plugin_settings,
        $DB_Settings_General,
        $Backend,
        $DB_Settings_Syncing
    ) {
        $this->plugin_settings = $plugin_settings;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->Backend = $Backend;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function delete_notices($request)
    {
        return $this->handle_response([
            'response' => $this->DB_Settings_General->set_app_uninstalled(0),
        ]);
    }

    public function error(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('error', $message, $dismiss_name, $cache_type);
    }

    public function warning_notice(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('warning', $message, $dismiss_name, $cache_type);
    }

    public function success_notice(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('success', $message, $dismiss_name, $cache_type);
    }

    public function info_notice(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('info', $message, $dismiss_name, $cache_type);
    }

    public function show_notice_markup(
        $type,
        $dismiss_name,
        $message,
        $cache_type
    ) {
        ?>
      <div class="notice wps-notice notice-<?php
      echo sanitize_html_class($type);

      if ($dismiss_name) {
          echo ' is-dismissible" data-dismiss-name="' . sanitize_html_class($dismiss_name);
      }
      ?>" data-dismiss-type="<?= sanitize_html_class($cache_type); ?>">

         <p><?= sanitize_text_field($message); ?></p>

      </div>
      <?php
    }

    private function notice(
        $type,
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        if ($cache_type === 'transient') {
            $already_dismissed = Transients::get(
                "wps_admin_dismissed_{$dismiss_name}"
            );
        } else {
            $already_dismissed = Options::get(
                "wps_admin_dismissed_{$dismiss_name}"
            );
        }

        if ($already_dismissed) {
            return;
        }

        $this->show_notice_markup($type, $dismiss_name, $message, $cache_type);
    }

    public function disable_post_edit_title()
    {
      if ($this->Backend->is_admin_posts_page($this->Backend->get_screen_id())) {
         echo '<script>jQuery(document).ready(function ($) {$("#title").attr("disabled","disabled");});</script>';
      }
    }

    public function show_tracking_notice()
    {
        if ($this->Backend->is_plugin_specific_pages()) {
            $this->warning_notice(
                Messages::get('notice_allow_tracking'),
                'notice_allow_tracking',
                'option'
            );
        }
    }

    public function dismiss_notice($request)
    {
        $dismiss_name = sanitize_text_field(
            $request->get_param('dismiss_name')
        );
        $dismiss_type = sanitize_text_field(
            $request->get_param('dismiss_type')
        );
        $dismiss_value = sanitize_text_field(
            $request->get_param('dismiss_value')
        );

        if (!$dismiss_name) {
            return $this->handle_response([
                'response' => false,
            ]);
        }

        if (!$dismiss_type) {
            $dismiss_type = 'transient';
        }

        if ($dismiss_type === 'transient') {
            $notice_dismissed = Transients::set(
                "wps_admin_dismissed_{$dismiss_name}",
                true,
                0
            );
        } elseif ($dismiss_type === 'option') {
            $notice_dismissed = Options::update(
                "wps_admin_dismissed_{$dismiss_name}",
                true
            );
        }

        if ($dismiss_value) {
            if ($dismiss_name === 'notice_allow_tracking') {
                $this->DB_Settings_General->update_col('allow_tracking', Data::to_bool_int($dismiss_value));
            }
        }

        return $this->handle_response([
            'response' => $notice_dismissed,
        ]);
    }

    public function register_route_notices()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/notices',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_notices'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_notices_dismiss()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/notices/dismiss',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'dismiss_notice'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_notices']);
        add_action('rest_api_init', [$this, 'register_route_notices_dismiss']);
        add_action('admin_notices', [$this, 'disable_post_edit_title']);
    }
}
