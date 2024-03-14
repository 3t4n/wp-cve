<?php
namespace Outfunnel\Forms;

use DateTime;

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

class ContactForm7 implements Form {
    /**
	 * @var String
	 */
    private const CONTACT_FORM_7_PLUGIN_NAME = 'contact-form-7';

    /**
	 * @var String
	 */
    private const CONTACT_FORM_7_SUBMIT_EVENT = 'contactform7/forms/new_record';


    /**
     * @var ContactForm7
     */
    private static $instance;

    /**
     * @return ContactForm7
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
        return is_plugin_active( self::CONTACT_FORM_7_PLUGIN_NAME . '/wp-contact-form-7.php' )
        && class_exists(\WPCF7_ContactForm::class);
    }

    /**
     * @return String|null
     */
    public function get_plugin_version() {
        if(!$this->is_form_plugin_active()) {
            return null;
        }
        $path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::CONTACT_FORM_7_PLUGIN_NAME . DIRECTORY_SEPARATOR . 'wp-contact-form-7.php';
        return get_plugin_data($path)["Version"];
    }

    /**
     * @return String
     */
    public function get_plugin_name() {
        return self::CONTACT_FORM_7_PLUGIN_NAME;
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

        $contactForms = \WPCF7_ContactForm::find();

        return [
            'data' => array_map(function($contactForm) use ($fields) {
                return [
                    'label' => $contactForm->title,
                    'id' => $contactForm->id,
                    'fields' =>  $contactForm->prop('form')
                ];
            }, $contactForms),
            'formPluginVersion' => $this->get_plugin_version(),
            'formPluginName' => $this->get_plugin_name(),
            'wpVersion' => WORDPRESS_VERSION,
            'ofPluginVersion' => OUTFUNNEL_VERSION
        ];
    }

    /**
     * @return void
     */
    public function run_webhook($contact_form, $abort, $submission) {
        Logger::info("Starting to process CF7 webhook");

        $outfunnel_settings = get_option('outfunnel_settings');

        if (!$outfunnel_settings) {
            return;
        }

        $of_api_key = $outfunnel_settings['of_api_key'];
        $of_account_email = $outfunnel_settings['of_account_email'];
        $of_user_id = $outfunnel_settings['of_id'];

        if (!$of_api_key || !$of_account_email) {
            Logger::warning("Failed to load Outfunnel settings");

            return false;
        }

        $site_url = get_site_url();
        $datetime = new DateTime();
        $submission_time = $datetime->format('c');


        $form_id = $contact_form->id();
        $form_name = $contact_form->title();
        $posted_data = $submission->get_posted_data();
        $form_url = get_permalink($submission->get_meta("container_post_id"));
        if (!$form_url) {
            $form_url = $submission->get_meta('url');
        }

        Logger::info(
            "Starting to send a request",
            $of_user_id,
            [
                'form_name' => $form_name,
                'form_id' => $form_id,
                'form_url' => $form_url
            ]
        );

        $submission_data = array_map(function($formTag) use ($posted_data) {
            $value = $posted_data[$formTag->name];
            return [
                $formTag->name => [
                'fieldType' => $formTag->basetype,
                'fieldName' => $formTag->name,
                'fieldOptions' => $formTag->options,
                'fieldValue' => $value ?? ''
                ]
            ];
        }, $contact_form->scan_form_tags());

        $webhook_data = [
            'data' => [
                'formId' => $form_id,
                'formName' => $form_name,
                'formUrl' => $form_url,
                'submissionTime' => $submission_time,
                'submissionData' => $submission_data
            ],
            'siteUrl' => $site_url,
            'eventType' => self::CONTACT_FORM_7_SUBMIT_EVENT,
            'formPluginVersion' => $this->get_plugin_version(),
            'formPluginName' => $this->get_plugin_name(),
            'wpVersion' => WORDPRESS_VERSION,
            'ofPluginVersion' => OUTFUNNEL_VERSION
        ];

        $response = wp_remote_post(OF_WEBHOOK_URL . '/webhooks/contact-form-7', [
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
        add_action('wpcf7_before_send_mail', [$this, 'run_webhook'], 10, 3);
    }

    private function __construct() {
        if ($this->is_form_plugin_active()) {
            $this->setup_hooks();
        }
    }
}
