<?php
/*
Plugin Name: Outfunnel: Web Visitor Tracking & CRM Integration
Version: 2.9.3
Author: Outfunnel
Author URI: https://outfunnel.com/
Description: Easily sync leads from various Wordpress forms to Pipedrive, Copper, HubSpot and other CRMs. Includes web visitor tracking.
Text Domain: outfunnel
Domain Path: /languages
 */

namespace Outfunnel;

use Outfunnel\Forms\ContactForm7;
use Outfunnel\Forms\Elementor;
use Outfunnel\Forms\GravityPlugin;
use Outfunnel\Forms\Form;
use Outfunnel\Forms\Logger;

include 'autoloader.php';

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('OUTFUNNEL')) {

    class OUTFUNNEL {

        public $plugin_version = '2.9.3';

        /**
         * @var Form
         */
        private $elementor;

        /**
         * @var Form
         */
        private $contact_form_7;

        /**
         * @var Form
         */
        private $gravity;

        public function __construct() {
            Autoloader::init();

            if (!defined('OUTFUNNEL_VERSION')) {
                define('OUTFUNNEL_VERSION', $this->plugin_version);
            }

            if (!defined('WORDPRESS_VERSION')) {
                define('WORDPRESS_VERSION', $this->get_current_wp_version());
            }

            if (!defined('OF_APP_URL')) {
                define('OF_APP_URL', 'https://app.outfunnel.com');
            }

            if (!defined('OF_API_URL')) {
                define('OF_API_URL', 'https://api.outfunnel.com');
            }

            if (!defined('OF_CDN_URL')) {
                define('OF_CDN_URL', 'https://cdn.outfunnel.com');
            }

            if (!defined('OF_WEBHOOK_URL')) {
                define('OF_WEBHOOK_URL', 'https://push.outfunnel.com');
            }

            if (!defined('OF_TEXT_DOMAIN')) {
                define('OF_TEXT_DOMAIN', 'outfunnel');
            }

            if (!defined('OF_SUPPORTED_FORM_SOURCES')) {
                define('OF_SUPPORTED_FORM_SOURCES', ['elementor', 'contact-form-7', 'gravity']);
            }

            $this->plugin_includes();
        }


        /**
         * @return String|null
         */
        private function get_current_wp_version() {
            return get_bloginfo( 'version' );
        }

        private function get_logging_data($of_account_email, $of_api_key) {
            $response = wp_remote_get(OF_API_URL . '/v1/wordpress/config', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-account-email' => $of_account_email,
                    'x-api-key' => $of_api_key
                ],
            ]);

            if (!in_array($response['response']['code'], [200, 201], true)) {
                $type = 'error';
                $message = __('Something went wrong', OF_TEXT_DOMAIN);
                $body = json_decode($response['body']);

                if (count($body->errors)) {
                    $message = $body->errors[0]->message;
                }

                add_settings_error(
                    'outfunnel_settings',
                    esc_attr('settings_updated'),
                    $message,
                    $type
                );

                return null;
            }

            $response_payload = json_decode($response['body'], true)['data'];

            return [
                'logging_url' => $response_payload['loggingUrl'],
                'logging_api_key' => $response_payload['apiKey'],
                'expires_at' => $response_payload['expiresAt']
            ];
        }

        public function plugin_includes() {
            if (is_admin()) {
                add_filter('plugin_action_links', [$this, 'plugin_action_links'], 10, 2);
            }
            add_action('rest_api_init', [$this, 'rest_api_init']);
            add_action('plugins_loaded', [$this, 'plugins_loaded_handler']);
            add_action('admin_init', [$this, 'settings_api_init']);
            add_action('admin_menu', [$this, 'add_options_menu']);
            add_action('wp_head', [$this, 'add_tracking_code']);

            add_action('activated_plugin', [$this, 'activation_redirect']);
        }

        public function activation_redirect($plugin) {
            // $plugin should be "outfunnel/outfunnel.php"
            if ($plugin == plugin_basename(__FILE__)) {
                exit(wp_redirect(admin_url('options-general.php?page=outfunnel-settings')));
            }
        }

        public function plugins_loaded_handler() {
            load_plugin_textdomain('outfunnel', false, dirname(plugin_basename(__FILE__)) . '/languages/');

            $this->elementor = Elementor::instance();
            $this->contact_form_7 = ContactForm7::instance();
            $this->gravity = GravityPlugin::instance();
        }

        public function plugin_url() {
            if ($this->plugin_url) {
                return $this->plugin_url;
            }

            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }

        public function plugin_action_links($links, $file) {
            if ($file == plugin_basename(dirname(__FILE__) . '/outfunnel.php')) {
                $links[] = '<a href="options-general.php?page=outfunnel-settings">' . __('Settings', 'outfunnel') . '</a>';
            }

            return $links;
        }

        private function get_active_form_sources() {
            return array_filter(OF_SUPPORTED_FORM_SOURCES, function($source) {
                return $this->get_form_source($source) && $this->get_form_source($source)->is_form_plugin_active();
            });
        }

        private function get_form_source($form_source) {
            $source = str_replace('-', '_', $form_source);

            if ($this->$source) {
                return $this->$source;
            }

            return null;
        }

        public function get_all_forms($request) {
            $form_source = $this->get_form_source($request['form_source']);

            if ($form_source === null) {
                return [
                    'errors' => [[
                        error => 'required_form_plugin_not_active',
                        message => 'Required form plugin not active',
                        context => 'form_source',
                    ]],
                ];
            }

            return $form_source->get_all_forms();
        }

        public function rest_api_init() {
            register_rest_route(
                'outfunnel/v2',
                '/form-sources/(?P<form_source>[a-zA-Z0-9_-]+)/forms',
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'get_all_forms'],
                    'permission_callback' => [$this, 'check_outfunnel_api_key'],
                    'args' => [
                        'form_source' => [
                            'required' => true,
                            'validate_callback' => function($param, $request, $key) {
                                return in_array($param, OF_SUPPORTED_FORM_SOURCES);
                            },
                        ]
                    ]
                ]
            );
        }

        public function add_options_menu() {
            if (is_admin()) {
                add_options_page(__('Outfunnel', 'outfunnel'), __('Outfunnel', 'outfunnel'), 'manage_options', 'outfunnel-settings', [$this, 'options_page']);
            }
        }

        public function settings_api_init() {
            register_setting('outfunnelpage', 'outfunnel_settings', [
                'sanitize_callback' => [$this, 'outfunnel_settings_save_callback']
            ]);

            add_settings_section(
                'outfunnel_tracking_section',
                __('Web tracking configuration', 'outfunnel'),
                [$this, 'outfunnel_tracking_settings_section_callback'],
                'outfunnelpage'
            );

            add_settings_field(
                'of_id',
                __('Tracking ID', 'outfunnel'),
                [$this, 'of_id_field'],
                'outfunnelpage',
                'outfunnel_tracking_section'
            );

            add_settings_section(
                'outfunnel_forms_section',
                __('Web forms integration', 'outfunnel'),
                [$this, 'outfunnel_forms_settings_section_callback'],
                'outfunnelpage'
            );

            add_settings_field(
                'of_account_email',
                __('Account email', 'outfunnel'),
                [$this, 'of_account_email_field'],
                'outfunnelpage',
                'outfunnel_forms_section'
            );

            add_settings_field(
                'of_api_key',
                __('API key', 'outfunnel'),
                [$this, 'of_api_key_field'],
                'outfunnelpage',
                'outfunnel_forms_section'
            );

            add_settings_section(
                'outfunnel_log_section',
                __('Logging', 'outfunnel'),
                [$this, 'outfunnel_log_settings_section_callback'],
                'outfunnelpage'
            );

            add_settings_field(
                'of_enable_logging',
                'Enable Logging',
                [$this, 'of_enable_logging'],
                'outfunnelpage',
                'outfunnel_log_section'
            );

        }

        public function options_page() {
            ?>
            <div class="wrap">
            <h2>Outfunnel Web Tracking - v<?=OUTFUNNEL_VERSION?></h2>

            <form action='options.php' method='post'>
            <?php
                settings_fields('outfunnelpage');
                do_settings_sections('outfunnelpage');
                submit_button();
            ?>
            </form>
            </div>
            <?php
        }

        public function outfunnel_settings_save_callback($data) {
            if (empty($data['of_api_key'])) {
                return $data;
            }

            $form_sources = $this->get_active_form_sources();

            if (!count($form_sources)) {
                $faq_url = 'https://support.outfunnel.com/en/articles/5234038-wordpress-forms-crm-integration';
                add_settings_error(
                    'outfunnel_settings',
                    esc_attr('settings_updated'),
                    sprintf(
                        wp_kses(
                            __('Did not find any active form plugins. Please install and activate a <a target="_blank" href="%s">supported form plugin</a>', OF_TEXT_DOMAIN),
                            ['a' => ['href' => [], 'target' => []]]
                        ), esc_url($faq_url)
                    ),
                    'error'
                );

                return $data;
            }

            $site_url = get_site_url();

            foreach($form_sources as $source) {
                $response = wp_remote_post(OF_API_URL . '/v1/integrations', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'x-account-email' => $data['of_account_email'],
                        'x-api-key' => $data['of_api_key']
                    ],
                    'body' => wp_json_encode([
                        'integration' => $source,
                        'siteUrl' => $site_url,
                        'formPluginVersion' => $this->get_form_source($source)->get_plugin_version(),
                        'formPluginName' => $this->get_form_source($source)->get_plugin_name(),
                        'wpVersion' => WORDPRESS_VERSION,
                        'ofPluginVersion' => OUTFUNNEL_VERSION
                    ]),
                ]);

                if (is_wp_error($response)) {
                    $type = 'error';
                    $message = $response->get_error_message();

                    add_settings_error(
                        'outfunnel_settings',
                        esc_attr('settings_updated'),
                        $message,
                        $type
                    );

                    return $data;
                }

                if (!in_array($response['response']['code'], [200, 201], true)) {
                    $type = 'error';
                    $message = __('Something went wrong', OF_TEXT_DOMAIN);
                    $body = json_decode($response['body']);

                    if (count($body->errors)) {
                        $message = $body->errors[0]->message;
                    }

                    add_settings_error(
                        'outfunnel_settings',
                        esc_attr('settings_updated'),
                        $message,
                        $type
                    );

                    return $data;
                }
            }

            if (!empty($data['of_enable_logging'])) {
                $logging_data = $this::get_logging_data($data['of_account_email'], $data['of_api_key']);

                $data['logging_url'] = $logging_data['logging_url'];
                $data['logging_api_key'] = $logging_data['logging_api_key'];
                $data['logging_api_key_expiration'] = $logging_data['expires_at'];
            }

            return $data;
        }

        public function outfunnel_tracking_settings_section_callback() {
            $config = get_option('outfunnel_settings');
            $of_id = $config['of_id'];
            $url = OF_APP_URL . '/web-tracking';
            echo sprintf(wp_kses(__('Outfunnel\'s <a target="_blank" href="%s">Web tracking</a> shows you which leads are visiting your website, where they come from, and which pages they\'ve been on. Add the tracking ID of your Outfunnel account to enable.', 'outfunnel'), ['a' => ['href' => [], 'target' => []]]), esc_url($url));
        }

        public function of_id_field() {
            $outfunnel_settings = get_option('outfunnel_settings');
            $of_id = $outfunnel_settings['of_id'];
            ?>
            <input type='text' name='outfunnel_settings[of_id]' value='<?=esc_attr($of_id);?>' placeholder='ie. 5b5c331c27d95c39e42a09d3' class="regular-text code">
            <p class="description"><?=__('Enter your Outfunnel Tracking ID for this website', OF_TEXT_DOMAIN);?></p>
            <?php
        }

        public function outfunnel_forms_settings_section_callback() {
            $config = get_option('outfunnel_settings');
            $of_id = $config['of_id'];
            $url = OF_APP_URL . '/connections';
            echo sprintf(wp_kses(__('Outfunnel\'s <a target="_blank" href="%s">App connector</a> records Wordpress form submissions in your CRM. Add the account email and API key of your Outfunnel account to enable.', OF_TEXT_DOMAIN), ['a' => ['href' => [], 'target' => []]]), esc_url($url));
        }

        public function outfunnel_log_settings_section_callback() {
            echo wp_kses('Enable sending logs to Outfunnel', []);
        }

        public function of_account_email_field() {
            $outfunnel_settings = get_option('outfunnel_settings');
            $of_account_email = $outfunnel_settings['of_account_email'];
            ?>
            <input type='text' name='outfunnel_settings[of_account_email]' value='<?=esc_attr($of_account_email);?>' placeholder='ie. example@example.com' class="regular-text code">
            <p class="description"><?=__('Enter your Outfunnel account email', OF_TEXT_DOMAIN);?></p>
            <?php
        }

        public function of_enable_logging() {
            $outfunnel_settings = get_option('outfunnel_settings');
            $of_enable_logging = isset($outfunnel_settings['of_enable_logging']) && $outfunnel_settings['of_enable_logging'] === "on";

            echo '<input type="checkbox" name="outfunnel_settings[of_enable_logging]"' . checked( 1, $of_enable_logging, false ) . '/>';
        }

        public function of_api_key_field() {
            $outfunnel_settings = get_option('outfunnel_settings');
            $of_api_key = $outfunnel_settings['of_api_key'];
            ?>
            <input type='text' name='outfunnel_settings[of_api_key]' value='<?=esc_attr($of_api_key);?>' placeholder='ie. 5c419ca4a2f010de1762a8a44f3012ee8b829084553b1be83eaa94d498e7f07d' class="regular-text code">
            <p class="description"><?=__('Enter your Outfunnel API key', OF_TEXT_DOMAIN);?></p>
            <?php
        }

        public function check_outfunnel_api_key($request) {
            $outfunnel_settings = get_option('outfunnel_settings');

            if (!$outfunnel_settings) {
                return false;
            }

            $api_key = $request->get_header('x-api-key');
            $account_email = $request->get_header('x-account-email');

            if (!$api_key || !$account_email) {
                return false;
            }

            $of_api_key = $outfunnel_settings['of_api_key'];
            $of_account_email = $outfunnel_settings['of_account_email'];

            if (!$of_api_key || !$of_account_email) {
                return false;
            }

            $is_request_authorized = $api_key == $of_api_key
                && $of_account_email == $account_email;

            return $is_request_authorized;
        }

        public function add_tracking_code() {

            $outfunnel_settings = get_option('outfunnel_settings');
            $of_id = $outfunnel_settings['of_id'];

            if (!empty($of_id)) {
                ?>

<!-- Generated with Outfunnel Web Tracking plugin v<?=$this->plugin_version?> -->
<script>
window.OFID = "<?=htmlspecialchars($of_id)?>";
window.OF_WP_VERSION = "<?=$this->plugin_version?>";
(function(){
var script = document.createElement('script');
var url = '<?=esc_attr(OF_CDN_URL)?>/c.js?v='+ new Date().toISOString().substring(0,10);
script.setAttribute('src', url);
document.getElementsByTagName('head')[0].appendChild(script);
})();
</script>
<!-- / Outfunnel Web Tracking plugin -->

<?php

                // empty line after <?php needed for indentation
            }
        }
    }

    $GLOBALS['outfunnel'] = new OUTFUNNEL();
}
