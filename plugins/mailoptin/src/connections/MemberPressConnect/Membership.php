<?php

namespace MailOptin\MemberPressConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;

class Membership
{
    public $post_type = 'memberpressproduct';

    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post'], 11, 3);
    }

    public function add_meta_boxes()
    {
        if (MemberPressSettings::get_instance()->is_optin_enabled()) {
            add_meta_box(
                'mo_memberpress_metabox',
                __('MailOptin Integration', 'mailoptin'),
                [$this, 'membership_metabox_content'],
                $this->post_type,
                'side'
            );
        }
    }

    public function membership_metabox_content()
    {
        global $post;
        $course_id         = $post->ID;
        $integrations      = MemberPressInit::get_instance()->memberpress_select_integration_options();
        $saved_integration = get_post_meta($course_id, 'mailoptinMemberPressSelectIntegration', true);
        $upsell_url        = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=memberpress_connection';
        $doc_url           = 'https://mailoptin.io/article/memberpress-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=memberpress_connection';
        $content           = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add members that subscribes to this membership to a specific email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );
        ?>
        <div id="mailoptin_email_integration" class="panel memberpress_options_panel">
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
                        MemberPressInit::mp_mailoptin_select_field(
                            [
                                'id'          => 'mailoptinMemberPressSelectIntegration',
                                'label'       => esc_html__('Select Integration', 'mailoptin'),
                                'value'       => $saved_integration,
                                'options'     => $integrations,
                                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                            ]
                        );

                        ?>
                        <div class="mailoptin_memberpress_email_list"></div>
                        <div class="mailoptin_memberpress_custom_fields_tags"></div>
                        <?php
                        wp_nonce_field('mo_memberpress_save_subscription_form_setting', 'mo_memberpress_save_subscription_form_setting_nonce');
                    }
                    ?>
                </div>

            <?php endif; ?>
        </div>
        <?php
    }

    public function save_post($post_id, $post, $update)
    {
        if ( ! isset($_POST['post_type']) || $_POST['post_type'] != $this->post_type) return;

        $mailoptin_is_connected = isset($_POST['mo_memberpress_save_subscription_form_setting_nonce']) && wp_verify_nonce($_POST['mo_memberpress_save_subscription_form_setting_nonce'], 'mo_memberpress_save_subscription_form_setting');

        if ( ! $mailoptin_is_connected || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
             (defined('DOING_AJAX') && DOING_AJAX) ||
             (false !== wp_is_post_revision($post_id)) ||
             ! current_user_can('edit_posts')) return;

        // Run for the courses
        $integration = sanitize_text_field($_POST['mailoptinMemberPressSelectIntegration']);


        if (isset($integration)) {
            update_post_meta($post_id, 'mailoptinMemberPressSelectIntegration', $integration);

            if ( ! empty($integration)) {
                if (isset($_POST['mailoptinMemberPressSelectList']) && isset($_POST['mailoptinMemberPressSelectIntegration'])) {
                    $connection            = sanitize_text_field($_POST['mailoptinMemberPressSelectIntegration']);
                    $connection_email_list = sanitize_text_field($_POST['mailoptinMemberPressSelectList']);

                    update_post_meta($post_id, $connection . '[mailoptinMemberPressSelectList]', $connection_email_list);

                    foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptinMemberPressMappedFields-' . $key);
                        update_post_meta($post_id, $connection . '[' . $mapped_key . ']', sanitize_text_field($_POST[$mapped_key]));
                    }

                    if ( ! empty($_POST['mailoptinMemberPressDoubleOptin'])) {
                        update_post_meta($post_id, $connection . '[mailoptinMemberPressDoubleOptin]', '1');
                    } else {
                        update_post_meta($post_id, $connection . '[mailoptinMemberPressDoubleOptin]', '0');
                    }

                    if (isset($_POST['mailoptinMemberPressTextTags'])) {
                        $text_tags = sanitize_text_field($_POST['mailoptinMemberPressTextTags']);
                        update_post_meta($post_id, $connection . '[mailoptinMemberPressTextTags]', $text_tags);
                    }

                    if (isset($_POST['mailoptinMemberPressSelectTags'])) {
                        $select_tags = json_encode(array_map('sanitize_text_field', $_POST['mailoptinMemberPressSelectTags']));
                        update_post_meta($post_id, $connection . '[mailoptinMemberPressSelectTags]', $select_tags);
                    }
                }
            }
        }
    }

    public function process_submission($connection, $membership_product_id, $user_data)
    {
        if (empty($connection)) return;

        $connection_email_list = get_post_meta($membership_product_id, $connection . '[mailoptinMemberPressSelectList]', true);

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
            $mapped_key       = rawurlencode('mailoptinMemberPressMappedFields-' . $key);
            $saved_mapped_key = get_post_meta($membership_product_id, $connection . '[' . $mapped_key . ']', true);
            if ( ! empty($saved_mapped_key)) {
                $field_map[$key] = $saved_mapped_key;
            }
        }

        //get the email
        $email = $user_data->user_email;

        if (empty($email)) return;

        $user_id = $user_data->ID;

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = MemberPressInit::get_instance()->get_field_value($value, $user_id);
        }

        $double_optin = false;
        if (in_array($connection, Init::double_optin_support_connections(true))) {
            $double_optin = get_post_meta($membership_product_id, $connection . '[mailoptinMemberPressDoubleOptin]', true) == '1';
        }

        $form_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {
            $memberpress_product = get_post($membership_product_id);
            $tags_key            = $connection . '[mailoptinMemberPressTextTags]';
            $form_tags           = get_post_meta($membership_product_id, $tags_key, true);
            $exploded_form_tags  = explode(',', $form_tags);
            array_push($exploded_form_tags, $memberpress_product->post_title);

            $form_tags = implode(',', array_filter($exploded_form_tags));
        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $tags_key  = $connection . '[mailoptinMemberPressSelectTags]';
            $form_tags = json_decode(get_post_meta($membership_product_id, $tags_key, true), true);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user_data->first_name;
        $last_name  = $user_data->last_name;
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'MemberPress';

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

            if (in_array($name, ['moEmail', 'moName', 'moFirstName', 'moLastName'])) continue;

            $field_value = MemberPressInit::get_instance()->get_field_value($value, $user_id);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    /**
     * @return Membership
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