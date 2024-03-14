<?php

namespace MailOptin\LifterLMSConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\Connections\ConnectionFactory;

class CourseSidebarSettings
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post'], 10, 3);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'mo_llms_sidebar_metabox',
            __('MailOptin Integration', 'mailoptin'),
            [$this, 'metabox_content'],
            ['course', 'llms_membership'],
            'side'
        );
    }

    public function metabox_content()
    {
        global $post;
        $post_id         = $post->ID;
        $post_type       = $post->post_type;
        $post_type_title = $post_type == 'llms_membership' ? esc_html__('Membership', 'mailoptin') : esc_html__('Course', 'mailoptin');

        $integrations      = Connect::select_integration_options();
        $saved_integration = get_post_meta($post_id, 'mailoptinLLMSSelectIntegration', true);

        $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=lifterlms_connection';
        $doc_url    = 'https://mailoptin.io/article/lifterlms-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=lifterlms_connection';
        $content    = sprintf(
            __('Upgrade to %sMailOptin Premium%s to add students that purchase or enroll to this %s to your email list, assign tags and custom field data to them.', 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>',
            strtolower($post_type_title)
        );

        ?>
        <div id="mailoptin_email_integration" class="panel llms_options_panel">
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

                        self::mailoptin_select_field(
                            [
                                'id'          => 'mailoptinLLMSSelectIntegration',
                                'label'       => esc_html__('Select Integration', 'mailoptin'),
                                'value'       => $saved_integration,
                                'options'     => $integrations,
                                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                            ]
                        );

                        ?>
                        <div class="mailoptin_llms_email_list"></div>
                        <div class="mailoptin_llms_custom_fields_tags"></div>
                        <?php
                        wp_nonce_field('mo_llms_save_subscription_form_setting', 'mo_llms_save_subscription_form_setting_nonce');
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function save_post($post_id, $post, $update)
    {
        if ( ! isset($_POST['post_type']) || ! in_array($_POST['post_type'], ['course', 'llms_membership'])) return;

        $mailoptin_is_connected = isset($_POST['mo_llms_save_subscription_form_setting_nonce']) && wp_verify_nonce($_POST['mo_llms_save_subscription_form_setting_nonce'], 'mo_llms_save_subscription_form_setting');

        if ( ! $mailoptin_is_connected || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
             (defined('DOING_AJAX') && DOING_AJAX) ||
             (false !== wp_is_post_revision($post_id)) ||
             ! current_user_can('edit_posts')) return;

        // Run for the downloads
        $integration = sanitize_text_field($_POST['mailoptinLLMSSelectIntegration']);

        if (isset($integration)) {
            update_post_meta($post_id, 'mailoptinLLMSSelectIntegration', $integration);

            if ( ! empty($integration)) {
                if (isset($_POST['mailoptinLLMSSelectList']) && isset($_POST['mailoptinLLMSSelectIntegration'])) {
                    $connection            = sanitize_text_field($_POST['mailoptinLLMSSelectIntegration']);
                    $connection_email_list = sanitize_text_field($_POST['mailoptinLLMSSelectList']);

                    update_post_meta($post_id, $connection . '[mailoptinLLMSSelectList]', $connection_email_list);

                    foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptinLLMSMappedFields-' . $key);
                        update_post_meta($post_id, $connection . '[' . $mapped_key . ']', sanitize_text_field($_POST[$mapped_key]));
                    }

                    if ( ! empty($_POST['mailoptinLLMSDoubleOptin'])) {
                        update_post_meta($post_id, $connection . '[mailoptinLLMSDoubleOptin]', true);
                    } else {
                        update_post_meta($post_id, $connection . '[mailoptinLLMSDoubleOptin]', false);
                    }

                    if (isset($_POST['mailoptinLLMSTextTags'])) {
                        $text_tags = sanitize_text_field($_POST['mailoptinLLMSTextTags']);
                        update_post_meta($post_id, $connection . '[mailoptinLLMSTextTags]', $text_tags);
                    }

                    if (isset($_POST['mailoptinLLMSSelectTags'])) {
                        $select_tags = json_encode(array_map('sanitize_text_field', $_POST['mailoptinLLMSSelectTags']));
                        update_post_meta($post_id, $connection . '[mailoptinLLMSSelectTags]', $select_tags);
                    }
                }
            }
        }
    }

    /**
     * @param $field
     */
    public static function mailoptin_select_field($field)
    {
        $field = wp_parse_args(
            $field, array(
                'class'    => 'select short',
                'value'    => ! empty($field['value']) ? $field['value'] : [],
                'name'     => $field['id'],
                'multiple' => '',
            )
        );

        $field_attributes['id']    = $field['id'];
        $field_attributes['name']  = $field['name'];
        $field_attributes['class'] = $field['class'];

        ?>
        <div class="mo-llms-form-field">
            <label for="<?= $field['id'] ?>"><?php echo wp_kses_post($field['label']); ?></label>
            <select class="<?= $field['class'] ?>" name="<?= $field['name'] ?>" id="<?= $field['id'] ?>" <?= ! empty($field['multiple']) ? 'multiple="multiple"' : ''; ?>>
                <?php
                if ( ! empty($field['multiple'])) {
                    foreach ($field['options'] as $key => $value) {
                        $selected = isset($field['value']) && is_array($field['value']) && in_array($key, $field['value']) ? 'selected' : '';
                        echo '<option value="' . esc_attr($key) . '"' . $selected . '>' . esc_html($value) . '</option>';
                    }
                } else {
                    foreach ($field['options'] as $key => $value) {
                        echo '<option value="' . esc_attr($key) . '"' . selected($key, $field['value']) . '>' . esc_html($value) . '</option>';
                    }
                }
                ?>
            </select>
            <?php if ( ! empty($field['description'])) : ?>
                <p class="description"><?php echo wp_kses_post($field['description']); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Output a checkbox input box.
     *
     * @param array $field
     */
    public static function mailoptin_checkbox($field)
    {
        $field['value']   = isset($field['value']) ? $field['value'] : '';
        $field['cbvalue'] = isset($field['cbvalue']) ? $field['cbvalue'] : 'yes';
        $field['name']    = isset($field['name']) ? $field['name'] : $field['id'];

        echo '<div class="mo-llms-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="checkbox" class="mo-llms-checkbox" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . '/> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }

    /**
     * @return false|void
     */
    public static function lead_tag_settings($saved_tags, $saved_integration)
    {
        if (empty($saved_integration)) return false;

        if (in_array($saved_integration, Init::select2_tag_connections())) {

            $tags     = [];
            $instance = ConnectionFactory::make($saved_integration);
            if (is_object($instance) && method_exists($instance, 'get_tags')) {
                $tags = $instance->get_tags();
            }

            $options = [];

            foreach ($tags as $value => $label) {
                if (empty($value)) continue;

                $options[$value] = $label;
            }

            self::mailoptin_select_field(
                [
                    'id'          => 'mailoptinLLMSSelectTags',
                    'name'        => 'mailoptinLLMSSelectTags[]',
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'value'       => $saved_tags,
                    'options'     => $options,
                    'class'       => 'moLLMS_select2',
                    'description' => esc_html__('Select tags to assign to users and students enrolled to this course/membership.', 'mailoptin'),
                    'multiple'    => 'multiple'
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.moLLMS_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            self::mailoptin_text_input(
                [
                    'id'          => 'mailoptinLLMSTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to students enrolled to this course/membership.', 'mailoptin'),
                ]
            );
        }
    }

    /**
     * Output a text input box.
     *
     * @param array $field
     */
    public static function mailoptin_text_input($field)
    {
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['name']  = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']  = isset($field['type']) ? $field['type'] : 'text';

        echo '<div class="mo-llms-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '" /> ';

        if ( ! empty($field['description'])) {
            echo '<p class="description">' . wp_kses_post($field['description']) . '</p>';
        }

        echo '</div>';
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