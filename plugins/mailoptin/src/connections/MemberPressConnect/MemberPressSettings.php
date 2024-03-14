<?php

namespace MailOptin\MemberPressConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;

class MemberPressSettings
{
    public function __construct()
    {
        add_action('wp_ajax_mo_memberpress_settings_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_memberpress_settings_fetch_custom_fields', [$this, 'fetch_custom_fields']);
        add_action('mepr_display_autoresponders', [$this, 'display_option_fields']);
        add_action('mepr-process-options', [$this, 'store_option_fields']);
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-memberpress', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        ob_start();

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = get_option('mailoptinMemberPressDoubleOptin');
            $double_optin_settings = MemberPressInit::get_instance()->memberpress_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = get_option('mailoptinMemberPressSelectList');
        }

        if (empty($lists)) wp_send_json_error([]);

        MemberPressInit::mp_mailoptin_select_field(
            [
                'id'          => 'mailoptinMemberPressSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list, audience or segment to add members to.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            MemberPressInit::mp_mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-memberpress', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ob_start();

        ?>
        <div id="mailoptin-memberpress-other-settings">
            <h2 class="mo-line-header"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        </div>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinMemberPressMappedFields-' . $key);
            $saved_mapped_field = $this->get_options($mapped_key);

            MemberPressInit::mp_mailoptin_select_field(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => MemberPressInit::get_instance()->memberpress_fields(),
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = 'mailoptinMemberPressTextTags';
                $saved_tags = get_option($tags_key);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = 'mailoptinMemberPressSelectTags';
                $saved_tags = json_decode(get_option($tags_key), true);
            }
            MemberPressInit::get_instance()->memberpress_lead_tag_settings($saved_tags, $connection);
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function display_option_fields()
    {
        $integrations           = MemberPressInit::get_instance()->memberpress_select_integration_options();
        $saved_integration      = $this->get_options('mailoptinMemberPressSelectIntegration', '');
        $subscribe_members      = $this->get_options('mailoptinMemberPressSubscribeMembers', 'no');
        $optin_field_label      = $this->get_options('mailoptinMemberPressOptinFieldLabel', __('Subscribe to our newsletters', 'mailoptin'));
        $optin_checkbox_default = $this->get_options('mailoptinMemberPressOptinCheckboxDefault', 'checked');
        $upsell_url             = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=memberpress_connection';
        $doc_url                = 'https://mailoptin.io/article/memberpress-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=memberpress_connection';
        $content                = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add members that signs up for a membership plan to a specific email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );
        ?>
        <div id="mo-memberpress" class="mo-memberpress-autoresponder-config">
            <input type="checkbox" name="mailoptinMemberPressEnabled" id="mailoptinMemberPressEnabled" <?php checked($this->is_optin_enabled()); ?> />
            <label for="mailoptinMemberPressEnabled"><?php _e('Enable MailOptin', 'mailoptin'); ?></label>
        </div>
        <div id="mo_memberpress_hidden_area" class="mepr-options-sub-pane mo-memberpress-options-sub-pane postbox">
            <div class="postbox-header">
                <h3><span><?= __('MemberPress Integration Settings', 'mailoptin') ?></span></h3>
            </div>
            <div class="inside">
                <div class="mo-memberpress-description">
                    <p><?=
                        sprintf(
                            esc_html__('The MemberPress integration subscribes members to your email marketing software and CRM upon signing up to a membership plan. %sLearn more%s', 'mailoptin'),
                            '<a href="https://mailoptin.io/article/memberpress-mailchimp-aweber-more/" target="_blank">', '</a>'
                        )
                        ?>
                    </p>
                </div>
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
                if (defined('MAILOPTIN_DETACH_LIBSODIUM')) :
                    ?>
                    <div id="mailoptin-memberpress-other-settings">
                        <h2 class="mo-line-header"><span><?= __('Other Settings', 'mailoptin') ?></span></h2>
                    </div>
                    <div id="mailoptin-memberpress-other-settings-fields" class="panel memberpress_options_panel">
                        <div class="options_group" id="mailoptin-memberpress-subscribe-members">
                            <?php
                            MemberPressInit::mp_mailoptin_select_field(
                                [
                                    'id'          => 'mailoptinMemberPressSubscribeMembers',
                                    'label'       => esc_html__('Subscribe Members', 'mailoptin'),
                                    'value'       => $subscribe_members,
                                    'options'     => [
                                        'no'  => __('Automatically', 'mailoptin'),
                                        'yes' => __('Ask for permission', 'mailoptin'),
                                    ],
                                    'description' => __('Choose "Ask for permission" to show an opt-in checkbox during membership signup. Members will only be subscribed to the email marketing list above if they check the checkbox. Choose Automatically to subscribe members silently upon membership plan signup. Caution, this is without the customer\'s consent.', 'mailoptin'),
                                ]
                            );

                            ?>
                        </div>
                        <div class="options_group" id="mailoptin-memberpress-optin-field-label">
                            <?php
                            MemberPressInit::mp_mailoptin_textarea(
                                [
                                    'id'          => 'mailoptinMemberPressOptinFieldLabel',
                                    'label'       => esc_html__('Opt-in Field Label', 'mailoptin'),
                                    'value'       => $optin_field_label,
                                    'description' => __('Customize the label displayed next to the opt-in checkbox.', 'mailoptin'),
                                ]
                            );
                            ?>
                        </div>
                        <div class="options_group" id="mailoptin-memberpress-optin-checkbox-default">
                            <?php

                            MemberPressInit::mp_mailoptin_select_field(
                                [
                                    'id'          => 'mailoptinMemberPressOptinCheckboxDefault',
                                    'label'       => esc_html__('Opt-in Checkbox Default', 'mailoptin'),
                                    'value'       => $optin_checkbox_default,
                                    'options'     => [
                                        'unchecked' => __('Unchecked', 'mailoptin'),
                                        'checked'   => __('Checked', 'mailoptin'),
                                    ],
                                    'description' => __('The default state of the opt-in checkbox.', 'mailoptin'),
                                ]
                            );
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <style type="text/css">
            #mo_memberpress_hidden_area {
                display: none;
            }

            #mo_memberpress_hidden_area .mo-memberpress-form-field {
                display: grid;
                grid-template-columns: 250px 1fr;
                grid-row-gap: 10px;
                margin-bottom: 20px;
            }

            #mo_memberpress_hidden_area .mo-memberpress-form-field label {
                font-weight: bold;
            }

            #mo_memberpress_hidden_area .mo-memberpress-form-field .description {
                grid-column: 2;
            }

            #mo_memberpress_hidden_area .mo-memberpress-form-field textarea,
            #mo_memberpress_hidden_area .mo-memberpress-form-field input,
            #mo_memberpress_hidden_area .mo-memberpress-form-field select {
                max-width: 25rem;
            }

            #mo_memberpress_hidden_area #mailoptin-memberpress-other-settings .mo-line-header {
                width: 100%;
                text-align: left;
                border-bottom: 1px solid #c3c4c7;
                line-height: 0.1em;
                margin: 10px 0 20px;
                font-weight: bold;
            }

            #mo_memberpress_hidden_area #mailoptin-memberpress-other-settings .mo-line-header span {
                background: #fff;
                padding-right: 10px;
                font-size: 14px;
            }
        </style>
        <?php
    }

    public function process_submission($connection, $user_data)
    {
        if (empty($connection)) return;

        $connection_email_list = $this->get_options('mailoptinMemberPressSelectList');

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
            $mapped_key       = rawurlencode('mailoptinMemberPressMappedFields-' . $key);
            $saved_mapped_key = $this->get_options($mapped_key);
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
            $double_optin = $this->get_options('mailoptinMemberPressDoubleOptin') == '1';
        }

        $form_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {
            $form_tags          = $this->get_options('mailoptinMemberPressTextTags');
            $exploded_form_tags = explode(',', $form_tags);

            $form_tags = implode(',', array_filter($exploded_form_tags));
        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $form_tags = json_decode($this->get_options('mailoptinMemberPressSelectTags'));
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

    public function store_option_fields()
    {

        $is_memberpress_enabled = '0';
        if (isset($_POST['mailoptinMemberPressEnabled'])) {
            $is_memberpress_enabled = '1';
        }

        update_option('mailoptinMemberPressEnabled', $is_memberpress_enabled);

        if ($is_memberpress_enabled != '1') return;

        update_option('mailoptinMemberPressSubscribeMembers', sanitize_text_field($_POST['mailoptinMemberPressSubscribeMembers']));
        update_option('mailoptinMemberPressOptinFieldLabel', sanitize_text_field($_POST['mailoptinMemberPressOptinFieldLabel']));
        update_option('mailoptinMemberPressOptinCheckboxDefault', sanitize_text_field($_POST['mailoptinMemberPressOptinCheckboxDefault']));

        $integration = sanitize_text_field($_POST['mailoptinMemberPressSelectIntegration']);
        if (isset($integration)) {
            update_option('mailoptinMemberPressSelectIntegration', sanitize_text_field($_POST['mailoptinMemberPressSelectIntegration']));

            if ( ! empty($integration)) {
                if (isset($_POST['mailoptinMemberPressSelectList']) && isset($_POST['mailoptinMemberPressSelectIntegration'])) {
                    $connection            = sanitize_text_field($_POST['mailoptinMemberPressSelectIntegration']);
                    $connection_email_list = sanitize_text_field($_POST['mailoptinMemberPressSelectList']);

                    update_option('mailoptinMemberPressSelectList', sanitize_text_field($_POST['mailoptinMemberPressSelectList']));

                    foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptinMemberPressMappedFields-' . $key);
                        update_option($mapped_key, sanitize_text_field($_POST[$mapped_key]));
                    }

                    if ( ! empty($_POST['mailoptinMemberPressDoubleOptin'])) {
                        update_option('mailoptinMemberPressDoubleOptin', '1');
                    } else {
                        update_option('mailoptinMemberPressDoubleOptin', '0');
                    }

                    if (isset($_POST['mailoptinMemberPressTextTags'])) {
                        $text_tags = sanitize_text_field($_POST['mailoptinMemberPressTextTags']);
                        update_option('mailoptinMemberPressTextTags', $text_tags);
                    }

                    if (isset($_POST['mailoptinMemberPressSelectTags'])) {
                        $select_tags = json_encode(array_map('sanitize_text_field', $_POST['mailoptinMemberPressSelectTags']));
                        update_option('mailoptinMemberPressSelectTags', $select_tags);
                    }

                }
            }
        }
    }

    public function is_optin_enabled()
    {
        //enable by default
        return get_option('mailoptinMemberPressEnabled', '1') == '1';
    }

    public function get_options($option_key, $default_value = false)
    {
        return get_option($option_key, $default_value);
    }

    /**
     * @return MemberPressSettings
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