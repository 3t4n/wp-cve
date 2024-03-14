<?php

namespace MailOptin\Core\Connections;

use MailOptin\Core\Admin\Customizer\OptinForm\AbstractCustomizer;
use MailOptin\Core\Core;
use MailOptin\Core\EmailCampaigns\TemplateTrait;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;
use MailOptin\Core\Repositories\EmailCampaignRepository;
use MailOptin\Core\Repositories\OptinCampaignsRepository;
use function MailOptin\Core\is_valid_data;
use function MailOptin\Core\moVar;

abstract class AbstractConnect
{
    use TemplateTrait;

    const EMAIL_MARKETING_TYPE = 'emailmarketing';
    const SOCIAL_TYPE = 'social';
    const CRM_TYPE = 'crm';
    const OTHER_TYPE = 'other';
    const ANALYTICS_TYPE = 'analytics';

    const OPTIN_CAMPAIGN_SUPPORT = 'optin_campaign';
    const OPTIN_CUSTOM_FIELD_SUPPORT = 'optin_custom_field';
    const EMAIL_CAMPAIGN_SUPPORT = 'email_campaign';

    public $extras = [];

    public $name;

    public function __construct()
    {
    }

    public function get_oauth_url($slug)
    {
        return add_query_arg([
            'redirect_url'    => MAILOPTIN_CONNECTIONS_SETTINGS_PAGE,
            'moconnect_nonce' => wp_create_nonce('mo_save_oauth_credentials')
        ],
            MAILOPTIN_OAUTH_URL . "/$slug/"
        );
    }

    public function get_integration_data($data_key, $integration_data = [], $default = '')
    {
        $optin_campaign_id = isset($this->extras['optin_campaign_id']) ? absint($this->extras['optin_campaign_id']) : '';
        $defaults          = (new AbstractCustomizer($optin_campaign_id))->customizer_defaults['integrations'];

        $data   = is_valid_data($default) ? $default : @$defaults[$data_key];
        $bucket = is_array($integration_data) && ! empty($integration_data) ? $integration_data : @$this->extras['integration_data'];

        if (isset($bucket[$data_key]) && is_valid_data($bucket[$data_key])) {
            $data = $bucket[$data_key];
        }

        return $data;
    }

    public function get_integration_tags($data_key)
    {
        $lead_tags = $this->get_integration_data($data_key);

        if ( ! empty($this->extras['form_tags'])) {
            $lead_tags = $this->extras['form_tags'];
        }

        return $lead_tags;
    }

    public function form_custom_fields()
    {
        return OptinCampaignsRepository::form_custom_fields($this->extras['optin_campaign_id']);
    }

    public function form_custom_field_mappings()
    {
        // used by our elementor and others for field mapping
        if (isset($this->extras['form_custom_field_mappings'])) {
            return $this->extras['form_custom_field_mappings'];
        }

        $custom_field_mappings = OptinCampaignsRepository::get_merged_customizer_value($this->extras['optin_campaign_id'], 'custom_field_mappings');

        if ( ! empty($custom_field_mappings)) {
            $custom_field_mappings = json_decode($custom_field_mappings, true);
        }

        return \MailOptin\Core\array_flatten($custom_field_mappings);
    }

    public function data_filter($value)
    {
        return \MailOptin\Core\is_boolean($value) || is_int($value) || ! empty($value);
    }

    /**
     * Helper to check if ajax response is successful.
     *
     * @param $response
     *
     * @return bool
     */
    public static function is_ajax_success($response)
    {
        return isset($response['success']) && $response['success'] === true;
    }

    /**
     * Helper to return success error.
     *
     * @return array
     */
    public static function ajax_success()
    {
        return ['success' => true];
    }

    /**
     * Check if HTTP status code is not successful.
     *
     * @param int $code
     *
     * @return bool
     */
    public static function is_http_code_not_success($code)
    {
        $code = absint($code);

        return $code < 200 || $code > 299;
    }

    /**
     * Check if HTTP status code is successful.
     *
     * @param int $code
     *
     * @return bool
     */
    public static function is_http_code_success($code)
    {
        $code = absint($code);

        return $code >= 200 && $code <= 299;
    }

    /**
     * Helper to return failed error.
     *
     * @param string $error
     *
     * @return array
     */
    public static function ajax_failure($error = '')
    {
        return ['success' => false, 'message' => apply_filters('mo_optin_ajax_error_message', $error)];
    }

    /**
     * Save error log.
     *
     * @param string $message
     * @param int $campaign_log_id
     * @param int $email_campaign_id
     */
    public static function save_campaign_error_log($message, $campaign_log_id, $email_campaign_id)
    {
        if ( ! isset($message) || ! isset($campaign_log_id) || ! isset($email_campaign_id)) {
            return;
        }

        $email_campaign_name = EmailCampaignRepository::get_email_campaign_name($email_campaign_id);
        $filename            = md5($email_campaign_name . $campaign_log_id);

        $error_log_folder = MAILOPTIN_CAMPAIGN_ERROR_LOG;

        // does bugs folder exist? if NO, create it.
        if ( ! file_exists($error_log_folder)) {
            mkdir($error_log_folder, 0755);
        }

        error_log(current_time('mysql') . ': ' . $message . "\r\n\r\n", 3, "{$error_log_folder}{$filename}.log");

        if ( ! apply_filters('mailoptin_disable_sending_email_campaign_error', false, $email_campaign_id)) {

            $email_campaign_name = EmailCampaignRepository::get_email_campaign_name($email_campaign_id);

            $main_message = apply_filters(
                'mo_email_campaign_error_email_message',
                sprintf(
                    __('The email campaign "%s" had the following error "%s".', 'mailoptin'),
                    $email_campaign_name,
                    $message
                )
            );

            $email = apply_filters('mo_email_campaign_error_email_address', get_option('admin_email'));

            $subject = apply_filters('mo_email_campaign_error_email_subject', sprintf(__('Warning! "%s" Email Campaign Is Not Working', 'mailoptin'), $email_campaign_name), $email_campaign_id);

            @wp_mail($email, $subject, $main_message);
        }
    }

    /**
     * get email service/connect specific optin error log.
     *
     * @param string $filename log file name.
     *
     * @return string
     */
    public static function get_optin_error_log($filename = 'error')
    {
        $error_log_file = MAILOPTIN_OPTIN_ERROR_LOG . $filename . '.log';

        // Return an empty string if the file does not exist
        if ( ! file_exists($error_log_file)) {
            return '';
        }

        return file_get_contents($error_log_file);
    }

    /**
     * return link to email service/connect specific optin error log.
     *
     * @param string $filename log file name.
     * @param bool $raw_url
     *
     * @return string
     */
    public static function get_optin_error_log_link($filename = 'error', $raw_url = false)
    {
        if ( ! self::has_optin_error_log($filename)) {
            return '';
        }


        $url = esc_url(
            add_query_arg(
                '_wpnonce',
                wp_create_nonce('mailoptin-log'),
                admin_url('admin-ajax.php?action=mailoptin_view_error_log&file=' . $filename)
            )
        );

        if ($raw_url) return $url;

        return sprintf(
            __('%sView Error Log%s', 'mailoptin'),
            "<a href='$url' style='color: #cc0000; font-weight: 400' target='_blank'>",
            '</a>'
        );
    }

    /**
     * check if there is an email service/connect specific optin error log.
     *
     * @param string $filename log file name.
     *
     * @return bool
     */
    public static function has_optin_error_log($filename = 'error')
    {
        $error_log_file = MAILOPTIN_OPTIN_ERROR_LOG . $filename . '.log';

        if (file_exists($error_log_file)) {
            return true;
        }

        return false;
    }

    /**
     * Save email service/connect specific optin errors.
     *
     * @param string $message error message
     * @param string $filename log file name.
     * @param int|null $optin_campaign_id
     *
     * @return bool
     */
    public static function save_optin_error_log($message, $filename = 'error', $optin_campaign_id = null, $optin_campaign_type = null)
    {
        $error_log_folder = MAILOPTIN_OPTIN_ERROR_LOG;

        // does bugs folder exist? if NO, create it.
        if ( ! file_exists($error_log_folder)) {
            mkdir($error_log_folder, 0755);
        }

        $response = error_log(current_time('mysql') . ': ' . $message . "\r\n", 3, "{$error_log_folder}{$filename}.log");

        self::send_optin_error_email($optin_campaign_id, $message, $optin_campaign_type);

        return $response;
    }

    public static function error_message_html_template($main_content, $footer_content, $url)
    {
        ob_start();
        require dirname(__FILE__) . '/error-html-tmpl.php';

        return ob_get_clean();
    }

    public static function error_message_plain_text($main_content, $footer_content, $url)
    {
        return "$main_content

" . sprintf(esc_html__("Use the coupon %s on checkout to save 20%% off MailOptin premium (%s)", 'mailoptin'), 'MOSAVE20', $url) . "

" . sprintf(esc_html__('Get 20%% Discount Now (%s)', 'mailoptin'), $url) . "

$footer_content";
    }

    public static function send_optin_error_email($optin_campaign_id, $error_message, $optin_campaign_type = '')
    {
        if (apply_filters('mailoptin_disable_send_optin_error_email', false, $optin_campaign_id)) {
            return;
        }

        if ( ! isset($optin_campaign_id, $error_message)) return;

        $email = apply_filters('mo_optin_campaign_error_email_address', get_option('admin_email'));

        $optin_campaign_name = OptinCampaignsRepository::get_optin_campaign_name($optin_campaign_id);

        if (intval($optin_campaign_id) === 0) {
            $optin_campaign_name = $optin_campaign_type;
        }

        $subject = apply_filters(
            'mo_optin_form_email_error_email_subject',
            sprintf(__('Warning! "%s" Optin Campaign Is Not Working', 'mailoptin'), $optin_campaign_name),
            $optin_campaign_id,
            $error_message
        );

        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) {

            $main_message = apply_filters(
                'mo_optin_form_email_error_email_message',
                sprintf(
                    __('The optin campaign "%s" is failing to convert leads due to the following error "%s".', 'mailoptin'),
                    $optin_campaign_name,
                    $error_message
                )
            );

            $footer_message = sprintf(
                __('This e-mail was sent by %s plugin on %s (%s)', 'mailoptin'),
                'MailOptin',
                \MailOptin\Core\site_title(),
                site_url()
            );

            $url = 'https://bit.ly/2KyV3Ng';

            $plain_text_message = self::error_message_plain_text($main_message, $footer_message, $url);
            $html_message       = self::error_message_html_template($main_message, $footer_message, $url);

            add_action('phpmailer_init', function ($phpmailer) use ($plain_text_message) {
                $phpmailer->AltBody = $plain_text_message;
            });

            $response = wp_mail($email, $subject, $html_message, ['Content-Type' => 'text/html']);

            if ( ! $response) @wp_mail($email, $subject, $plain_text_message);

        } else {

            $message = apply_filters(
                'mo_optin_form_email_error_email_message',
                sprintf(
                    __('The optin campaign "%s" is failing to convert leads due to the following error "%s". %6$s -- %6$sThis e-mail was sent by %s plugin on %s (%s)', 'mailoptin'),
                    $optin_campaign_name,
                    $error_message,
                    'MailOptin',
                    \MailOptin\Core\site_title(),
                    site_url(),
                    "\r\n\n"
                )
            );

            @wp_mail($email, $subject, $message);
        }
    }

    /**
     * Split full name into first and last names.
     *
     * @param string $name
     *
     * @return array
     */
    public static function get_first_last_names($name)
    {
        $data = [];

        $names = explode(' ', $name);

        if (isset($names[0])) {
            $data[] = trim($names[0]);
            unset($names[0]);
        }

        $data[] = implode(' ', $names);

        return $data;
    }

    public function get_first_name()
    {
        return moVar(self::get_first_last_names($this->name), 0, '');
    }

    public function get_last_name()
    {
        return moVar(self::get_first_last_names($this->name), 1, '');
    }

    /**
     * Get selected list ID of an email campaign.
     *
     * @param int $email_campaign_id
     *
     * @return int|string
     */
    public function get_email_campaign_list_id($email_campaign_id)
    {
        return EmailCampaignRepository::get_customizer_value($email_campaign_id, 'connection_email_list');
    }

    /**
     * Get campaign title of an email campaign.
     *
     * @param int $email_campaign_id
     *
     * @return string
     */
    public function get_email_campaign_campaign_title($email_campaign_id)
    {
        return EmailCampaignRepository::get_email_campaign_name($email_campaign_id);
    }

    /**
     * Convert campaign log ID to UUID.
     *
     * @param int $id
     * @param string $meta_key
     *
     * @return string
     */
    public function campaignlog_id_to_uuid($id, $meta_key)
    {
        $uuid = wp_generate_password(12, false);

        AbstractCampaignLogMeta::add_campaignlog_meta($id, $meta_key, $uuid);

        return $uuid;
    }

    /**
     * Convert UUID back to campaign log ID.
     *
     * @param string $uuid
     * @param string $meta_key
     *
     * @return null|string
     */
    public function uuid_to_campaignlog_id($uuid, $meta_key)
    {
        global $wpdb;
        $table = $wpdb->prefix . Core::campaign_log_meta_table_name;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT campaign_log_id from $table WHERE meta_key = '%s' AND meta_value = %s",
                $meta_key,
                $uuid
            )
        );
    }

    /**
     * Created this method because a need arose where we needed to call statically a method to refresh token
     *
     * @param $integration
     * @param $refresh_token
     * @param array $args
     *
     * @return mixed
     * @throws \Exception
     */
    public static function static_oauth_token_refresh($integration, $refresh_token, $args = [])
    {
        $url = sprintf(MAILOPTIN_OAUTH_URL . '/%s/?refresh_token=%s', $integration, $refresh_token);

        if ( ! empty($args)) {
            $url = add_query_arg($args, $url);
        }

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $response_body = wp_remote_retrieve_body($response);

        $result = \json_decode($response_body, true);

        if ( ! isset($result['success']) || $result['success'] !== true) {
            throw new \Exception('Error failed to refresh ' . $response_body);
        }

        return $result;
    }

    /**
     * @param $integration
     * @param $refresh_token
     * @param array $args
     *
     * @return mixed
     * @throws \Exception
     */
    public function oauth_token_refresh($integration, $refresh_token, $args = [])
    {
        return self::static_oauth_token_refresh($integration, $refresh_token, $args);
    }

    /**
     * Subtracting 5 minute to account for any lag and to ensure token is refreshed before it expires.
     *
     * @param $expires_at
     *
     * @return float|int
     */
    public function oauth_expires_at_transform($expires_at)
    {
        return absint($expires_at) - (5 * MINUTE_IN_SECONDS);
    }
}