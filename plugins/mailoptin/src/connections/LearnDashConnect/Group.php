<?php

namespace MailOptin\LearnDashConnect;

use MailOptin\Connections\Init;

class Group
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post']);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'mo_learndash_course_metabox',
            __('MailOptin Integration', 'mailoptin'),
            [$this, 'course_metabox_content'],
            'groups',
            'side'
        );
    }

    public function course_metabox_content()
    {
        global $post;
        $course_id         = $post->ID;
        $integrations      = LearnDashInit::get_instance()->learndash_select_integration_options();
        $saved_integration = LearnDashInit::get_instance()->learndash_get_field('mailoptinLearnDashSelectIntegration', $course_id);
        $upsell_url        = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=learndash_connection';
        $doc_url           = 'https://mailoptin.io/article/learndash-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=learndash_connection';
        $content           = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add students that enrols this course to a specific email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );
        ?>
        <div id="mailoptin_email_integration" class="panel learndash_options_panel">
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
                        LearnDashInit::ld_mailoptin_select_field(
                            [
                                'id'          => 'mailoptinLearnDashSelectIntegration',
                                'label'       => esc_html__('Select Integration', 'mailoptin'),
                                'value'       => $saved_integration,
                                'options'     => $integrations,
                                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                            ]
                        );

                        ?>
                        <div class="mailoptin_learndash_email_list"></div>
                        <div class="mailoptin_learndash_custom_fields_tags"></div>
                        <?php
                        wp_nonce_field('mo_learndash_save_subscription_form_setting', 'mo_learndash_save_subscription_form_setting_nonce');
                    }
                    ?>
                </div>

            <?php endif; ?>
        </div>
        <?php
    }

    public function save_post($post_id)
    {
        if ( ! isset($_POST['post_type']) || $_POST['post_type'] != 'groups') return;

        $mailoptin_is_connected = isset($_POST['mo_learndash_save_subscription_form_setting_nonce']) && wp_verify_nonce($_POST['mo_learndash_save_subscription_form_setting_nonce'], 'mo_learndash_save_subscription_form_setting');

        if ( ! $mailoptin_is_connected || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
             (defined('DOING_AJAX') && DOING_AJAX) ||
             (false !== wp_is_post_revision($post_id)) ||
             ! current_user_can('edit_posts')) return;

        // Run for the group
        $integration = sanitize_text_field($_POST['mailoptinLearnDashSelectIntegration']);

        if (isset($integration)) {

            update_post_meta($post_id, 'mailoptinLearnDashSelectIntegration', $integration);

            if ( ! empty($integration)) {

                if (isset($_POST['mailoptinLearnDashSelectList']) && isset($_POST['mailoptinLearnDashSelectIntegration'])) {

                    $connection            = sanitize_text_field($_POST['mailoptinLearnDashSelectIntegration']);
                    $connection_email_list = sanitize_text_field($_POST['mailoptinLearnDashSelectList']);

                    update_post_meta($post_id, $connection . '[mailoptinLearnDashSelectList]', $connection_email_list);

                    foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptinLearnDashMappedFields-' . $key);
                        update_post_meta($post_id, $connection . '[' . $mapped_key . ']', sanitize_text_field($_POST[$mapped_key]));
                    }

                    if ( ! empty($_POST['mailoptinLearnDashDoubleOptin'])) {
                        update_post_meta($post_id, $connection . '[mailoptinLearnDashDoubleOptin]', true);
                    } else {
                        update_post_meta($post_id, $connection . '[mailoptinLearnDashDoubleOptin]', false);
                    }

                    if (isset($_POST['mailoptinLearnDashTextTags'])) {
                        $text_tags = sanitize_text_field($_POST['mailoptinLearnDashTextTags']);
                        update_post_meta($post_id, $connection . '[mailoptinLearnDashTextTags]', $text_tags);
                    }

                    if (isset($_POST['mailoptinLearnDashSelectTags'])) {
                        $select_tags = json_encode(array_map('sanitize_text_field', $_POST['mailoptinLearnDashSelectTags']));
                        update_post_meta($post_id, $connection . '[mailoptinLearnDashSelectTags]', $select_tags);
                    }
                }
            }
        }
    }

    /**
     * @return Group
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