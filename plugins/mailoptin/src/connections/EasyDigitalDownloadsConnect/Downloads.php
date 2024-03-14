<?php

namespace MailOptin\EasyDigitalDownloadsConnect;

use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Connections\Init;

class Downloads
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post'], 10, 3);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'mo_edd_download_metabox',
            __('MailOptin Integration', 'mailoptin'),
            [$this, 'download_metabox_content'],
            'download',
            'side'
        );
    }

    public function download_metabox_content()
    {
        global $post;
        $download_id       = $post->ID;
        $integrations      = EDDInit::get_instance()->edd_select_integration_options();
        $saved_integration = get_post_meta($download_id, 'mailoptinEDDSelectIntegration', true);
        $upsell_url        = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=edd_connection';
        $doc_url           = 'https://mailoptin.io/article/edd-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=edd_connection';
        $content           = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add customers that purchase this product to your email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );

        ?>
        <div id="mailoptin_email_integration" class="panel edd_options_panel">
            <?php if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) : ?>
                <div class="mo-external-upsell-block">
                    <p><?= $content ?></p>
                    <p>
                        <a href="<?= $upsell_url ?>" style="margin-right: 10px;" class="button-primary" target="_blank">
                            <?php esc_html_e('Upgrade to MailOptin Premium', 'mailoptin'); ?>
                        </a>
                        <a href="<?= $doc_url ?>" target="_blank">
                            <?php esc_html_e('Learn more', 'mailoptin'); ?>
                        </a>
                    </p>
                </div>
            <?php else : ?>
                <div class="options_group">
                    <?php if ( ! empty($integrations)) {
                        EDDInit::edd_mailoptin_select_field(
                            [
                                'id'          => 'mailoptinEDDSelectIntegration',
                                'label'       => esc_html__('Select Integration', 'mailoptin'),
                                'value'       => $saved_integration,
                                'options'     => $integrations,
                                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                            ]
                        );

                        ?>
                        <div class="mailoptin_edd_email_list"></div>
                        <div class="mailoptin_edd_custom_fields_tags"></div>
                        <?php
                        wp_nonce_field('mo_edd_save_subscription_form_setting', 'mo_edd_save_subscription_form_setting_nonce');
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function save_post($post_id, $post, $update)
    {
        if ( ! isset($_POST['post_type']) || $_POST['post_type'] != 'download') return;

        $mailoptin_is_connected = isset($_POST['mo_edd_save_subscription_form_setting_nonce']) && wp_verify_nonce($_POST['mo_edd_save_subscription_form_setting_nonce'], 'mo_edd_save_subscription_form_setting');

        if ( ! $mailoptin_is_connected || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
             (defined('DOING_AJAX') && DOING_AJAX) ||
             (false !== wp_is_post_revision($post_id)) ||
             ! current_user_can('edit_posts')) return;

        // Run for the downloads
        $integration = sanitize_text_field($_POST['mailoptinEDDSelectIntegration']);

        if (isset($integration)) {
            update_post_meta($post_id, 'mailoptinEDDSelectIntegration', $integration);

            if ( ! empty($integration)) {
                if (isset($_POST['mailoptinEDDSelectList']) && isset($_POST['mailoptinEDDSelectIntegration'])) {
                    $connection            = sanitize_text_field($_POST['mailoptinEDDSelectIntegration']);
                    $connection_email_list = sanitize_text_field($_POST['mailoptinEDDSelectList']);

                    update_post_meta($post_id, $connection . '[mailoptinEDDSelectList]', $connection_email_list);

                    foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptinEDDMappedFields-' . $key);
                        update_post_meta($post_id, $connection . '[' . $mapped_key . ']', sanitize_text_field($_POST[$mapped_key]));
                    }

                    if ( ! empty($_POST['mailoptinEDDDoubleOptin'])) {
                        update_post_meta($post_id, $connection . '[mailoptinEDDDoubleOptin]', true);
                    } else {
                        update_post_meta($post_id, $connection . '[mailoptinEDDDoubleOptin]', false);
                    }

                    if (isset($_POST['mailoptinEDDTextTags'])) {
                        $text_tags = sanitize_text_field($_POST['mailoptinEDDTextTags']);
                        update_post_meta($post_id, $connection . '[mailoptinEDDTextTags]', $text_tags);
                    }

                    if (isset($_POST['mailoptinEDDSelectTags'])) {
                        $select_tags = json_encode(array_map('sanitize_text_field', $_POST['mailoptinEDDSelectTags']));
                        update_post_meta($post_id, $connection . '[mailoptinEDDSelectTags]', $select_tags);
                    }
                }
            }
        }
    }

    public function process_submission($download_id, $payment_meta)
    {
        $connection = get_post_meta($download_id, 'mailoptinEDDSelectIntegration', true);

        if (empty($connection)) return;

        $connection_email_list = get_post_meta($download_id, $connection . '[mailoptinEDDSelectList]', true);

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
            $mapped_key       = rawurlencode('mailoptinEDDMappedFields-' . $key);
            $saved_mapped_key = get_post_meta($download_id, $connection . '[' . $mapped_key . ']', true);
            if ( ! empty($saved_mapped_key)) {
                $field_map[$key] = $saved_mapped_key;
            }
        }

        $user_info = $payment_meta['user_info'];

        $email = $payment_meta['email'];

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            if ($value) {
                $payload[$key] = EDDInit::get_instance()->get_field_value($value, $email);
            }
        }

        $double_optin = false;
        if (in_array($connection, Init::double_optin_support_connections(true))) {
            $double_optin = get_post_meta($download_id, $connection . '[mailoptinEDDDoubleOptin]', true) == '1';
        }

        $form_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {

            $tags_key  = $connection . '[mailoptinEDDTextTags]';
            $form_tags = get_post_meta($download_id, $tags_key, true);

            if ( ! empty($form_tags)) {
                $exploded_form_tags = explode(',', $form_tags);
                array_push($exploded_form_tags, get_the_title($download_id));

                $form_tags = implode(',', array_filter($exploded_form_tags));
            }

        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $tags_key  = $connection . '[mailoptinEDDSelectTags]';
            $form_tags = json_decode(get_post_meta($download_id, $tags_key, true), true);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user_info['first_name'];
        $last_name  = $user_info['last_name'];

        $name = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'Easy Digital Downloads';

        $optin_data->connection_service    = $connection;
        $optin_data->connection_email_list = $connection_email_list;

        $optin_data->user_agent                = esc_html($_SERVER['HTTP_USER_AGENT']);
        $optin_data->is_timestamp_check_active = false;
        $optin_data->is_double_optin           = $double_optin;

        if ( ! empty($form_tags)) {
            $optin_data->form_tags = $form_tags;
        }

        // Loop through field map.
        foreach ($field_map as $name => $value) {

            // If no field is mapped, skip it.
            if (empty($value)) {
                continue;
            }

            $field_value = EDDInit::get_instance()->get_field_value($value, $payment_meta['email']);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    /**
     * @return self
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}