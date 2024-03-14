<?php
namespace Outfunnel\Forms;

use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;
use ElementorPro\Modules\Forms\Submissions\Database\Repositories\Form_Snapshot_Repository;
use DateTime;

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

class Elementor implements Form {
    /**
	 * @var String
	 */
    private const ELEMENTOR_PRO_PLUGIN_NAME = 'elementor-pro';

    /**
	 * @var String
	 */
    private const ELEMENTOR_PRO_FORM_SUBMIT_EVENT = 'elementor_pro/forms/new_record';


    /**
     * @var Elementor
     */
    private static $instance;

    /**
     * @return Elementor
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
        return is_plugin_active( self::ELEMENTOR_PRO_PLUGIN_NAME . '/elementor-pro.php' )
        && class_exists(Form_Snapshot_Repository::class)
        && class_exists(Ajax_Handler::class)
        && class_exists(Form_Record::class);
    }

    /**
     * @return String|null
     */
    public function get_plugin_version() {
        if(!$this->is_form_plugin_active()) {
            return null;
        }
        $path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::ELEMENTOR_PRO_PLUGIN_NAME . DIRECTORY_SEPARATOR . 'elementor-pro.php';
        return get_plugin_data($path)["Version"];
    }

    /**
     * @return String
     */
    public function get_plugin_name() {
        return self::ELEMENTOR_PRO_PLUGIN_NAME;
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

        $forms = Form_Snapshot_Repository::instance()->all();

        return [
            'data' => $forms->map(function ($form) {
                return [
                    'label' => $form->get_label(),
                    'id' => $form->id,
                    'fields' => $form->fields
                ];
            })->values(),
            'formPluginVersion' => $this->get_plugin_version(),
            'formPluginName' => $this->get_plugin_name(),
            'wpVersion' => WORDPRESS_VERSION,
            'ofPluginVersion' => OUTFUNNEL_VERSION
        ];
    }

    /**
     * @param Form_Record
     * @param Ajax_Handler
     * @return void
     */
    public function run_webhook($record, $handler) {
        Logger::info("Starting to process Elementor webhook");

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

        $form_name = $record->get_form_settings('form_name');
        // Currently we do not support custom form ids
        $form_id = $record->get_form_settings('id');

        $raw_fields = $record->get('fields');

        $site_url = get_site_url();

        $datetime = new DateTime();
        $submission_time = $datetime->format('c');
        $form_url = get_permalink(get_the_ID());

        Logger::info(
            "Starting to send a request",
            $of_user_id,
            [
                'form_name' => $form_name,
                'form_id' => $form_id
            ]
        );

        $webhook_data = [
            'data' => [
                'formId' => $form_id,
                'formName' => $form_name,
                'submissionTime' => $submission_time,
                'submissionData' => $raw_fields,
                'formUrl' => $form_url
            ],
            'siteUrl' => $site_url,
            'eventType' => self::ELEMENTOR_PRO_FORM_SUBMIT_EVENT,
            'formPluginVersion' => $this->get_plugin_version(),
            'formPluginName' => $this->get_plugin_name(),
            'wpVersion' => WORDPRESS_VERSION,
            'ofPluginVersion' => OUTFUNNEL_VERSION
        ];

        Logger::info("Sending out webhook", $of_user_id);

        $response = wp_remote_post(OF_WEBHOOK_URL . '/webhooks/elementor', [
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
        add_action(self::ELEMENTOR_PRO_FORM_SUBMIT_EVENT, [$this, 'run_webhook'], 10, 2);
    }

    private function __construct() {
        if ($this->is_form_plugin_active()) {
            $this->setup_hooks();
        }
    }
}
