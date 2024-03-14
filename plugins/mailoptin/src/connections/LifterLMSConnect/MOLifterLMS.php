<?php

namespace MailOptin\LifterLMSConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\Repositories\ConnectionsRepository;
use function MailOptin\Core\moVar;

defined('ABSPATH') || exit;


class MOLifterLMS extends \LLMS_Abstract_Integration
{

    public $id = 'mailoptin';
    public $title = '';


    protected function configure()
    {
        $this->title       = __('MailOptin Integration', 'mailoptin');
        $this->description = __('Automatically add your LifterLMS students and members to your email marketing lists during enrollment and registration.', 'mailoptin');

        if ($this->is_enabled()) {

            if ($this->is_consent_enabled()) {
                add_action('llms_registration_privacy', [$this, 'consent_form_field'], 15);
            }

            // user registration.
            add_action('lifterlms_user_registered', [$this, 'save_user_info'], 20, 3);

            add_action('llms_user_enrolled_in_course', [$this, 'user_enrolled'], 10, 2);
            add_action('llms_user_added_to_membership_level', [$this, 'user_enrolled'], 10, 2);
        }
    }

    public function consent_form_field()
    {
        if ($this->get_user_consent_status()) return;

        llms_form_field(array(
            'columns'     => 12,
            'description' => '',
            'checked'     => $this->get_option('mo_llms_subscribe_checked_default', 'no') == 'yes',
            'id'          => 'llms_mailoptin_consent',
            'label'       => $this->get_optin_checkbox_label(),
            'last_column' => true,
            'type'        => 'checkbox',
            'value'       => 'yes',
        ));
    }

    /**
     * Called during user registration from open registration or checkout forms.
     *
     * @param int $user_id WP User ID.
     * @param array $post_data form $_POST data.
     * @param string $screen update type.
     *
     * @return void
     */
    public function save_user_info($user_id, $post_data, $screen)
    {
        if ($this->save_consent($user_id, $post_data)) {
            $this->global_subscription($user_id);
        }
    }

    /**
     * Handle user enrollment in a course or a membership
     *
     * @param int $user_id WP User ID.
     * @param int $post_id WP Post ID.
     *
     * @return void
     */
    public function user_enrolled($user_id, $post_id)
    {
        if ( ! $this->get_user_consent_status($user_id)) {
            return;
        }

        $this->per_course_subscription($user_id, $post_id);
    }

    /**
     * @param int $user_id WP User ID.
     * @param int $post_id WP Post ID.
     *
     * @return void
     */
    private function per_course_subscription($user_id, $post_id)
    {
        $connection = get_post_meta($post_id, 'mailoptinLLMSSelectIntegration', true);

        if (empty($connection)) return;

        $student = llms_get_student($user_id);

        $connection_email_list = get_post_meta($post_id, $connection . '[mailoptinLLMSSelectList]', true);

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
            $mapped_key       = rawurlencode('mailoptinLLMSMappedFields-' . $key);
            $saved_mapped_key = get_post_meta($post_id, $connection . '[' . $mapped_key . ']', true);
            if ( ! empty($saved_mapped_key)) {
                $field_map[$key] = $saved_mapped_key;
            }
        }

        $email = $student->get('user_email');

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            if ($value) {
                $payload[$key] = $student->get($value);
            }
        }

        $double_optin = false;
        if (in_array($connection, Init::double_optin_support_connections(true))) {
            $double_optin = get_post_meta($post_id, $connection . '[mailoptinLLMSDoubleOptin]', true) == '1';
        }

        $form_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {

            $tags_key  = $connection . '[mailoptinLLMSTextTags]';
            $form_tags = get_post_meta($post_id, $tags_key, true);

            if ( ! empty($form_tags)) {
                $exploded_form_tags = explode(',', $form_tags);
                array_push($exploded_form_tags, get_the_title($post_id));

                $form_tags = implode(',', array_filter($exploded_form_tags));
            }

        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $tags_key  = $connection . '[mailoptinLLMSSelectTags]';
            $form_tags = json_decode(get_post_meta($post_id, $tags_key, true), true);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $student->get('first_name');
        $last_name  = $student->get('last_name');

        $name = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'LifterLMS';

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

            $field_value = $student->get($value);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        AjaxHandler::do_optin_conversion($optin_data);
    }

    /**
     * @param $user_id
     *
     * @return array|void
     */
    private function global_subscription($user_id)
    {
        $field_map = [];

        $connection_service = $this->get_option('mo_llms_integration');

        if (empty($connection_service)) return;

        $student = llms_get_student($user_id);

        $connection_email_list = $this->get_option('mo_llms_list');

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key      = rawurlencode('mo_llms_list_mapped_fields_' . $key);
            $field_map[$key] = $this->get_option($mapped_key);
        }

        $email = $student->get('user_email');

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            if ($value) {
                $payload[$key] = $student->get($value);
            }
        }

        $double_optin = false;
        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = $this->get_option('mo_llms_doi') == 'yes';
        }

        $form_tags = '';

        if (in_array($connection_service, Init::text_tag_connections())) {

            $form_tags = $this->get_option('mo_llms_text_tags', '');

            if ( ! empty($form_tags)) {

                $exploded_form_tags = explode(',', $form_tags);

                $form_tags = implode(',', array_filter($exploded_form_tags));
            }

        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $tags = $this->get_option('mo_llms_select_tags');

            if ( ! empty($tags)) {
                $form_tags = $tags;
            }
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $student->get('first_name');
        $last_name  = $student->get('last_name');

        $name = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'LifterLMS';

        $optin_data->connection_service    = $connection_service;
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

            $field_value = $student->get($value);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        return AjaxHandler::do_optin_conversion($optin_data);
    }

    private function save_consent($person_id, $post_data = array())
    {
        $consent = true;

        if ($this->is_enabled()) {

            // Try go get data directly from the $_POST request.
            $consent = llms_filter_input(INPUT_POST, 'llms_mailoptin_consent', FILTER_SANITIZE_STRING);

            // fall back to the $post_data passed from the LLMS action if it wasn't in the $_POST data.
            if (is_null($consent) && isset($post_data['llms_mailoptin_consent'])) {
                $consent = $post_data['llms_mailoptin_consent'];
            }

            // No consent data was posted, don't save anything and return false.
            if (is_null($consent)) return false;

            // Ensure we only save a yes/no value in the usermeta.
            $consent = in_array($consent, ['yes', 'no'], true) ? $consent : 'no';
            update_user_meta($person_id, 'llms_mailoptin_consent', $consent);
        }

        return llms_parse_bool($consent);
    }

    private function is_consent_enabled()
    {
        return $this->get_option('mo_llms_subscribe_method', false) == 'yes';
    }

    private function get_optin_checkbox_label()
    {
        return $this->get_option('mo_llms_subscribe_consent_message', __('Subscribe to our newsletter', 'mailoptin'));
    }

    private function get_user_consent_status($user_id = null)
    {
        $status = false;

        if ($this->is_consent_enabled()) {

            $user_id = $user_id ? $user_id : get_current_user_id();

            if ($user_id) {
                $status = llms_parse_bool(get_user_meta($user_id, 'llms_mailoptin_consent', true));
            }

        } else {
            $status = true;
        }

        return $status;
    }

    /**
     * @return   array
     */
    protected function get_integration_settings()
    {
        $content = [];

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {

            $connections = Connect::email_service_providers();

            $saved_connection = $this->get_option('mo_llms_integration');

            $content[] = array(
                'title'   => __('Select Integration', 'mailoptin'),
                'desc'    => '<br>' . __('Select your email marketing software or CRM', 'mailoptin'),
                'id'      => $this->get_option_name('mo_llms_integration'),
                'type'    => 'select',
                'options' => $connections,
            );

            if ( ! empty($saved_connection)) {

                $lists = Connect::email_list_options($saved_connection);

                $content[] = [
                    'title'   => __('Select List', 'mailoptin'),
                    'desc'    => __('Select the email list, audience or contact list to add customers', 'mailoptin'),
                    'id'      => $this->get_option_name('mo_llms_list'),
                    'type'    => 'select',
                    'class'   => 'llms-select2',
                    'options' => $lists,
                ];

                $content[] = Connect::double_optin_settings($saved_connection, $this->get_option_name('mo_llms_doi'));

                $saved_lists = $this->get_option('mo_llms_list');

                if ( ! empty($saved_lists)) {

                    $integration_name = moVar(ConnectionsRepository::get_connections(), $saved_connection);

                    $field_map = Init::merge_vars_field_map($saved_connection, $saved_lists);

                    if ( ! empty($field_map)) {

                        $content[] = [
                            'title' => __('Map Fields', 'mailoptin'),
                            'type'  => 'subtitle',
                        ];

                        $content[] = array(
                            'type'  => 'custom-html',
                            'id'    => 'llms-mo-custom-fields-heading',
                            'value' => '<span style="display:inline-block;width:225px;font-weight:600;">' . sprintf(__('%s Field', 'mailoptin'), $integration_name) . '</span><span style="font-weight:600;">' . __('LifterLMS Field', 'mailoptin') . '</span>',
                        );

                        foreach (Init::merge_vars_field_map($saved_connection, $saved_lists) as $key => $value) {
                            $mapped_key = rawurlencode('mo_llms_list_mapped_fields_' . $key);

                            $content[] = [
                                'id'      => $this->get_option_name($mapped_key),
                                'title'   => $value,
                                'options' => Connect::get_core_fields(),
                                'type'    => 'select',
                            ];
                        }
                    }
                }

                if (in_array($saved_connection, Init::select2_tag_connections())) {

                    $tags     = [];
                    $instance = ConnectionFactory::make($saved_connection);
                    if (is_object($instance) && method_exists($instance, 'get_tags')) {
                        $tags = $instance->get_tags();
                    }

                    $options = [];

                    foreach ($tags as $value => $label) {
                        if (empty($value)) continue;

                        $options[$value] = $label;
                    }

                    $content[] = [
                        'id'       => $this->get_option_name('mo_llms_select_tags'),
                        'title'    => esc_html__('Tags', 'mailoptin'),
                        'desc'     => esc_html__('Select tags to assign to customers and users.', 'mailoptin'),
                        'options'  => $options,
                        'class'    => 'llms-select2',
                        'type'     => 'multiselect',
                        'multiple' => 'multiple'
                    ];

                } elseif (in_array($saved_connection, Init::text_tag_connections())) {

                    $content[] = [
                        'id'    => $this->get_option_name('mo_llms_text_tags'),
                        'title' => esc_html__('Tags', 'mailoptin'),
                        'desc'  => '<br>' . esc_html__('Enter a comma-separated list of tags to assign to customers.', 'mailoptin'),
                        'type'  => 'text',
                    ];
                }

                $content[] = [
                    'title' => __('Other Settings', 'mailoptin'),
                    'type'  => 'subtitle',
                ];

                $content[] = [
                    'id'      => $this->get_option_name('mo_llms_subscribe_method'),
                    'name'    => __('Subscription Method', 'mailoptin'),
                    'desc'    => __('Choose "Ask for permission" to show an opt-in checkbox during enrollment and registration. Students will only be subscribed if they check the checkbox. Choose Automatically to subscribe silently after registration and enrollment.', 'mailoptin'),
                    'options' => [
                        'no'  => __('Automatically', 'mailoptin'),
                        'yes' => __('Ask for permission', 'mailoptin')
                    ],
                    'type'    => 'select',
                    'class'   => 'llms-select2'
                ];

                $content[] = [
                    'id'   => $this->get_option_name('mo_llms_subscribe_checked_default'),
                    'name' => __('Optin Checkbox Default', 'mailoptin'),
                    'desc' => __('Decide whether the newsletter signup checkbox displayed in checkout page should be checked by default or not? Only used if Subscription Method is set to "Ask for permission"', 'mailoptin'),
                    'type' => 'checkbox'
                ];

                $content[] = [
                    'id'      => $this->get_option_name('mo_llms_subscribe_consent_message'),
                    'name'    => __('Optin Checkbox Label', 'mailoptin'),
                    'desc'    => '<br>' . __('This is only used if Subscription Method is set to "Ask for permission" and it is the text that will display beside the optin checkbox.', 'mailoptin'),
                    'default' => __('Subscribe to our newsletter', 'mailoptin'),
                    'type'    => 'textarea'
                ];
            }
        } else {

            $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=lifterlms_connection_settings';
            $doc_url    = 'https://mailoptin.io/article/lifterlms-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=lifterlms_connection_settings';

            $body = sprintf(
                __("Upgrade to %sMailOptin Premium%s to add all WooCommerce customers and customers that purchase products belonging to a specific category or tag to your email marketing list.", 'mailoptin'),
                '<a target="_blank" href="' . $upsell_url . '">',
                '</a>'
            );

            $html = '<div class="mo-lifterlms-upsell-block">';
            $html .= sprintf('<p>%s</p>', $body);
            $html .= sprintf('<p><a href="%s" style="margin-right: 10px;" class="button-primary" target="_blank">', $upsell_url);
            $html .= esc_html__('Upgrade to MailOptin Premium', 'mailoptin');
            $html .= '</a>';
            $html .= sprintf('<a href="%s" target="_blank">', $doc_url);
            $html .= esc_html__('Learn more', 'mailoptin');
            $html .= '</a>';
            $html .= '</p>';
            $html .= '</div>';

            $content[] = [
                'value' => $html,
                'type'  => 'custom-html-no-wrap'
            ];

        }

        return $content;

    }

}
