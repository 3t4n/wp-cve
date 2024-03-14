<?php

namespace MailOptin\TutorLMSConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;

class Course
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post'], 11, 3);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'mo_tutorlms_course_metabox',
            __('MailOptin Integration', 'mailoptin'),
            [$this, 'course_metabox_content'],
            'courses',
            'side'
        );
    }

    public function course_metabox_content()
    {
        global $post;
        $course_id         = $post->ID;
        $integrations      = TutorInit::get_instance()->tutorlms_select_integration_options();
        $saved_integration = TutorInit::get_instance()->tutorlms_get_field('mailoptinTutorLMSSelectIntegration', $course_id);
        $upsell_url        = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=tutorlms_connection';
        $doc_url           = 'https://mailoptin.io/article/tutorlms-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=tutorlms_connection';
        $content           = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add students that enrols this course to a specific email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );
        ?>
        <div id="mailoptin_email_integration" class="panel tutorlms_options_panel">
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
                        TutorInit::tl_mailoptin_select_field(
                            [
                                'id'          => 'mailoptinTutorLMSSelectIntegration',
                                'label'       => esc_html__('Select Integration', 'mailoptin'),
                                'value'       => $saved_integration,
                                'options'     => $integrations,
                                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                            ]
                        );

                        ?>
                        <div class="mailoptin_tutorlms_email_list"></div>
                        <div class="mailoptin_tutorlms_custom_fields_tags"></div>
                        <?php
                        wp_nonce_field('mo_tutorlms_save_subscription_form_setting', 'mo_tutorlms_save_subscription_form_setting_nonce');
                    }
                    ?>
                </div>

            <?php endif; ?>
        </div>
        <?php
    }

    public function save_post($post_id, $post, $update)
    {
        if ( ! isset($_POST['post_type']) || $_POST['post_type'] != 'courses') return;

        $mailoptin_is_connected = isset($_POST['mo_tutorlms_save_subscription_form_setting_nonce']) && wp_verify_nonce($_POST['mo_tutorlms_save_subscription_form_setting_nonce'], 'mo_tutorlms_save_subscription_form_setting');

        if ( ! $mailoptin_is_connected || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
             (defined('DOING_AJAX') && DOING_AJAX) ||
             (false !== wp_is_post_revision($post_id)) ||
             ! current_user_can('edit_posts')) return;

        // Run for the courses
        $integration = sanitize_text_field($_POST['mailoptinTutorLMSSelectIntegration']);

        if (isset($integration)) {
            update_post_meta($post_id, 'mailoptinTutorLMSSelectIntegration', $integration);

            if ( ! empty($integration)) {
                if (isset($_POST['mailoptinTutorLMSSelectList']) && isset($_POST['mailoptinTutorLMSSelectIntegration'])) {
                    $connection            = sanitize_text_field($_POST['mailoptinTutorLMSSelectIntegration']);
                    $connection_email_list = sanitize_text_field($_POST['mailoptinTutorLMSSelectList']);

                    update_post_meta($post_id, $connection . '[mailoptinTutorLMSSelectList]', $connection_email_list);

                    foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptinTutorLMSMappedFields-' . $key);
                        update_post_meta($post_id, $connection . '[' . $mapped_key . ']', sanitize_text_field($_POST[$mapped_key]));
                    }

                    if ( ! empty($_POST['mailoptinTutorLMSDoubleOptin'])) {
                        update_post_meta($post_id, $connection . '[mailoptinTutorLMSDoubleOptin]', true);
                    } else {
                        update_post_meta($post_id, $connection . '[mailoptinTutorLMSDoubleOptin]', false);
                    }

                    if (isset($_POST['mailoptinTutorLMSTextTags'])) {
                        $text_tags = sanitize_text_field($_POST['mailoptinTutorLMSTextTags']);
                        update_post_meta($post_id, $connection . '[mailoptinTutorLMSTextTags]', $text_tags);
                    }

                    if (isset($_POST['mailoptinTutorLMSSelectTags'])) {
                        $select_tags = json_encode(array_map('sanitize_text_field', $_POST['mailoptinTutorLMSSelectTags']));
                        update_post_meta($post_id, $connection . '[mailoptinTutorLMSSelectTags]', $select_tags);
                    }
                }
            }
        }
    }

    /**
     * @param $connection
     * @param $course_id
     * @param $user_id
     *
     * @return bool|void
     */
    public function process_submission($connection, $course_id, $user_id)
    {
        if (empty($connection)) return;

        $connection_email_list = TutorInit::get_instance()->tutorlms_get_field($connection . '[mailoptinTutorLMSSelectList]', $course_id);

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
            $mapped_key       = rawurlencode('mailoptinTutorLMSMappedFields-' . $key);
            $saved_mapped_key = TutorInit::get_instance()->tutorlms_get_field($connection . '[' . $mapped_key . ']', $course_id);
            if ( ! empty($saved_mapped_key)) {
                $field_map[$key] = $saved_mapped_key;
            }
        }

        $user_data = get_userdata($user_id);

        //get the email
        $email = $user_data->user_email;

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = TutorInit::get_instance()->get_field_value($value, $user_id);
        }

        $double_optin = false;
        if (in_array($connection, Init::double_optin_support_connections(true))) {
            $double_optin = TutorInit::get_instance()->tutorlms_get_field($connection . '[mailoptinTutorLMSDoubleOptin]', $course_id) == '1';
        }

        $form_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {
            $course             = get_post($course_id);
            $tags_key           = $connection . '[mailoptinTutorLMSTextTags]';
            $form_tags          = TutorInit::get_instance()->tutorlms_get_field($tags_key, $course_id);
            $exploded_form_tags = explode(',', $form_tags);
            array_push($exploded_form_tags, $course->post_title);

            $form_tags = implode(',', array_filter($exploded_form_tags));
        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $tags_key  = $connection . '[mailoptinTutorLMSSelectTags]';
            $form_tags = json_decode(TutorInit::get_instance()->tutorlms_get_field($tags_key, $course_id), true);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user_data->first_name;
        $last_name  = $user_data->last_name;
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'Tutor LMS';

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

            if (in_array($name, ['moEmail', 'moName', 'moFirstName', 'moLastName'])) continue;

            $field_value = TutorInit::get_instance()->get_field_value($value, $user_id);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    /**
     * @return Course
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