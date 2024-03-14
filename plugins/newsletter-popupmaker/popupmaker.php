<?php

/*
  Plugin Name: Newsletter PopupMaker
  Plugin URI: https://wordpress.org/plugin/newsletter-popupmaker/
  Description: Integrates Popup Maker with Newsletter subscription forms
  Version: 1.0.3
  Requires PHP: 5.6
  Requires at least: 4.6
  Author: Stefano Lissa
  Author URI: https://www.satollo.net/
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
  Text Domain: newsletter-popupmaker
  License: GPLv2 or later
 */

// This loads the admin side after Newsletter has been loaded since it depends on Newsletter presence
add_action('newsletter_loaded', function ($version) {
    if ($version < '7.2.0') {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>Newsletter plugin upgrade required for Popup Maker Addon.</p></div>';
        });
    } else {
        include_once __DIR__ . '/plugin.php';
        new NewsletterPopupMaker('1.0.3');
    }
});

// This code must be here due to the WP action sequence and priorities to hook in the exact moment
// required by Popup Maker
add_action('plugins_loaded', function () {

    // Nothing to do if Popup Maker is not available
    if (class_exists('PUM_Abstract_Integration_Form')) {

        add_filter('pum_integrations', function ($integrations = []) {
            return array_merge($integrations, [
        'newsletterplugin' => new PUM_Integration_Form_NewsletterPlugin(),
            ]);
        });

        class PUM_Integration_Form_NewsletterPlugin extends PUM_Abstract_Integration_Form {

            public $key = 'newsletterplugin';

            public function __construct() {
                add_action('newsletter_user_post_subscribe', array($this, 'on_success'), 10, 1);
            }

            public function label() {
                return 'Newsletter Plugin';
            }

            /**
             * @return bool
             */
            public function enabled() {
                // This plugin loads only when Newsletter is active
                return true;
                //return class_exists( 'RGForms' );
            }

            /**
             * @return array
             */
            public function get_forms() {
                //return GFAPI::get_forms();
            }

            /**
             * @param string $id
             *
             * @return mixed
             */
            public function get_form($id) {
                //return GFAPI::get_forms( $id );
            }

            /**
             * @return array
             */
            public function get_form_selectlist() {
                return [];
                $form_selectlist = ['0' => 'Newsletter'];

                return $form_selectlist;
            }

            /**
             * @param $entry
             * @param $form
             */
            public function on_success($user) {
                pum_integrated_form_submission([
                    'popup_id' => isset($_REQUEST['pum_form_popup_id']) && absint($_REQUEST['pum_form_popup_id']) > 0 ? absint($_REQUEST['pum_form_popup_id']) : false,
                    'form_provider' => $this->key,
                    'form_id' => 0,
                ]);
                return $user;
            }

        }

    } else {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>Popup Maker seems not installed or not active. The integration with Newsletter could not be loaded.</p></div>';
        });
    }
}, 8);
