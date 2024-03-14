<?php
namespace Outfunnel\Forms;

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

class GravityPlugin implements Form {
    /**
	 * @var String
	 */
    private const GRAVITY_FORMS_PLUGIN_NAME = 'gravityforms';

    /**
	 * @var String
	 */
    private const GRAVITY_FORM_SUBMIT_EVENT = 'gform_after_submission';

    /**
     * @var GravityPlugin
     */
    private static $instance;

    /**
     * @return GravityPlugin
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return Boolean
     */
    public function is_form_plugin_active() {
        return is_plugin_active( self::GRAVITY_FORMS_PLUGIN_NAME . '/gravityforms.php' );
    }

    /**
     * @return String|null
     */
    public function get_plugin_version() {
        if(!$this->is_form_plugin_active()) {
            return null;
        }
        $path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::GRAVITY_FORMS_PLUGIN_NAME . DIRECTORY_SEPARATOR . 'gravityforms.php';
        return get_plugin_data($path)["Version"];
    }


    /**
     * @return String
     */
    public function get_plugin_name() {
        return self::GRAVITY_FORMS_PLUGIN_NAME;
    }

    public function get_all_forms() {
        if (!$this->is_form_plugin_active()) {
            return [
                'errors' => [[
                    error => 'required_form_plugin_not_active',
                    message => 'Required form plugin not active',
                    context => 'form_source',
                ]],
            ];
        }

        $forms = \GFAPI::get_forms(true, false);

        return [
            'data' => $forms,
            'formPluginVersion' => $this->get_plugin_version(),
            'formPluginName' => $this->get_plugin_name(),
            'wpVersion' => WORDPRESS_VERSION,
            'ofPluginVersion' => OUTFUNNEL_VERSION
        ];
    }

    /**
     * @return void
     */
    public function run_webhook($entry, $form) {
        Logger::info("Starting to process Gravity webhook");

        $outfunnel_settings = get_option('outfunnel_settings');

        if (!$outfunnel_settings) {
            Logger::warning("Failed to load Outfunnel settings");

            return;
        }

        $of_api_key = $outfunnel_settings['of_api_key'];
        $of_account_email = $outfunnel_settings['of_account_email'];
        $of_user_id = $outfunnel_settings['of_id'];

        if (!$of_api_key || !$of_account_email) {
            Logger::warning("Api key or account email missing", $of_user_id);

            return false;
        }

        $site_url = get_site_url();

        $webhook_data = [
            'data' => [
                'form' => $form,
                'submissionData' => $entry,
            ],
            'siteUrl' => $site_url,
            'eventType' => self::GRAVITY_FORM_SUBMIT_EVENT,
            'formPluginVersion' => $this->get_plugin_version(),
            'formPluginName' => $this->get_plugin_name(),
            'wpVersion' => WORDPRESS_VERSION,
            'ofPluginVersion' => OUTFUNNEL_VERSION
        ];

        $response = wp_remote_post(OF_WEBHOOK_URL . '/webhooks/gravity', [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key' => $of_api_key,
                'x-account-email' => $of_account_email,
            ],
            'body' => wp_json_encode($webhook_data),
        ]);

        Logger::info("Webhook response", $of_user_id, ['response_code' => $response['response']['code']]);
    }

    /**
     * @return void
     */
    private function setup_hooks() {
        add_action(self::GRAVITY_FORM_SUBMIT_EVENT, [$this, 'run_webhook'], 10, 2);
    }

    private function __construct() {
        if ($this->is_form_plugin_active()) {
            $this->setup_hooks();
        }
    }


}
