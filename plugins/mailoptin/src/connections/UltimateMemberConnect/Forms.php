<?php

namespace MailOptin\UltimateMemberConnect;

use MailOptin\Core\AjaxHandler;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Connections\Init;

class Forms
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post']);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'mo_um_form_metabox',
            __('MailOptin Integration', 'mailoptin'),
            [$this, 'form_metabox_content'],
            'um_form',
            'side'
        );
    }

    public function form_metabox_content()
    {
        global $post;
        $form_id           = $post->ID;
        $integrations      = UMInit::get_instance()->um_select_integration_options();
        $saved_integration = get_post_meta($form_id, 'mailoptinUMSelectIntegration', true);
        $upsell_url        = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=ultimatemember_connection';
        $doc_url           = 'https://mailoptin.io/article/ultimate-member-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=ultimatemember_connection';
        $content           = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add users that register via this registration form to your email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );
        ?>
        <div id="mailoptin_email_integration" class="panel um_options_panel">
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
                        UMInit::um_mailoptin_select_field(
                            [
                                'id'          => 'mailoptinUMSelectIntegration',
                                'label'       => esc_html__('Select Integration', 'mailoptin'),
                                'value'       => $saved_integration,
                                'options'     => $integrations,
                                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                            ]
                        );

                        ?>
                        <div class="mailoptin_um_email_list"></div>
                        <div class="mailoptin_um_custom_fields_tags"></div>
                        <?php
                        wp_nonce_field('mo_um_save_subscription_form_setting', 'mo_um_save_subscription_form_setting_nonce');
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function save_post($post_id)
    {
        if ( ! isset($_POST['post_type']) || $_POST['post_type'] != 'um_form') return;

        $mailoptin_is_connected = isset($_POST['mo_um_save_subscription_form_setting_nonce']) && wp_verify_nonce($_POST['mo_um_save_subscription_form_setting_nonce'], 'mo_um_save_subscription_form_setting');

        if ( ! $mailoptin_is_connected || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
             (defined('DOING_AJAX') && DOING_AJAX) ||
             (false !== wp_is_post_revision($post_id)) ||
             ! current_user_can('edit_posts')) return;

        $integration = sanitize_text_field($_POST['mailoptinUMSelectIntegration']);

        if (isset($integration)) {
            update_post_meta($post_id, 'mailoptinUMSelectIntegration', $integration);

            if ( ! empty($integration)) {
                if (isset($_POST['mailoptinUMSelectList']) && isset($_POST['mailoptinUMSelectIntegration'])) {
                    $connection            = sanitize_text_field($_POST['mailoptinUMSelectIntegration']);
                    $connection_email_list = sanitize_text_field($_POST['mailoptinUMSelectList']);

                    update_post_meta($post_id, $connection . '[mailoptinUMSelectList]', $connection_email_list);

                    foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptinUMMappedFields-' . $key);
                        update_post_meta($post_id, $connection . '[' . $mapped_key . ']', sanitize_text_field($_POST[$mapped_key]));
                    }

                    if ( ! empty($_POST['mailoptinUMDoubleOptin'])) {
                        update_post_meta($post_id, $connection . '[mailoptinUMDoubleOptin]', true);
                    } else {
                        update_post_meta($post_id, $connection . '[mailoptinUMDoubleOptin]', false);
                    }

                    if (isset($_POST['mailoptinUMTextTags'])) {
                        $text_tags = sanitize_text_field($_POST['mailoptinUMTextTags']);
                        update_post_meta($post_id, $connection . '[mailoptinUMTextTags]', $text_tags);
                    }

                    if (isset($_POST['mailoptinUMSelectTags'])) {
                        $select_tags = json_encode(array_map('sanitize_text_field', $_POST['mailoptinUMSelectTags']));
                        update_post_meta($post_id, $connection . '[mailoptinUMSelectTags]', $select_tags);
                    }
                }
            }
        }
    }

    public function process_submission($form_id, $user_id)
    {
        $connection = get_post_meta($form_id, 'mailoptinUMSelectIntegration', true);

        if (empty($connection)) return;

        $connection_email_list = get_post_meta($form_id, $connection . '[mailoptinUMSelectList]', true);

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
            $mapped_key       = rawurlencode('mailoptinUMMappedFields-' . $key);
            $saved_mapped_key = get_post_meta($form_id, $connection . '[' . $mapped_key . ']', true);
            if ( ! empty($saved_mapped_key)) {
                $field_map[$key] = $saved_mapped_key;
            }
        }

        $user = get_userdata($user_id);

        $email = $user->user_email;

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            if ($value) {
                $payload[$key] = isset($user->$value) ? $user->$value : '';
            }
        }

        $double_optin = false;
        if (in_array($connection, Init::double_optin_support_connections(true))) {
            $double_optin = get_post_meta($form_id, $connection . '[mailoptinUMDoubleOptin]', true) == '1';
        }

        $form_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {

            $tags_key  = $connection . '[mailoptinUMTextTags]';
            $form_tags = get_post_meta($form_id, $tags_key, true);

            if ( ! empty($form_tags)) {
                $exploded_form_tags = explode(',', $form_tags);
                array_push($exploded_form_tags, get_the_title($form_id));

                $form_tags = implode(',', array_filter($exploded_form_tags));
            }

        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $tags_key  = $connection . '[mailoptinUMSelectTags]';
            $form_tags = json_decode(get_post_meta($form_id, $tags_key, true), true);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user->first_name ?? '';
        $last_name  = $user->last_name ?? '';

        $name = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'Ultimate Member';

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
            if (empty($value)) continue;

            $field_value = isset($user->$value) ? $user->$value : '';

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        AjaxHandler::do_optin_conversion($optin_data);
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