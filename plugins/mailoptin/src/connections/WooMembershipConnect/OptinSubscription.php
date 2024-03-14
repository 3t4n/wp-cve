<?php

namespace MailOptin\WooMembershipConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\WooCommerceConnect\WooInit;
use SkyVerge\WooCommerce\Memberships\Profile_Fields;
use function MailOptin\Core\moVarPOST;

class OptinSubscription
{
    public function __construct()
    {
        add_action('save_post_wc_membership_plan', [$this, 'save_mailoptin_integration']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_filter('mo_mailoptin_js_globals', [$this, 'set_global_variables'], 10, 1);

        add_filter('wc_membership_plan_data_tabs', [$this, 'memberships_tab']);
        add_action('wc_membership_plan_data_panels', [$this, 'memberships_tab_content']);

        add_action('wp_ajax_mo_woomem_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_woomem_fetch_custom_fields', [$this, 'fetch_custom_fields']);

        add_action('wc_memberships_user_membership_status_changed', function ($user_membership, $old_status, $new_status) {

            /** @var \WC_Memberships_User_Membership $user_membership */

            if ( ! is_a($user_membership, '\WC_Memberships_User_Membership')) return;

            if ($new_status != 'active') return;

            $this->subscribe_member(
                $user_membership->get_user_id(),
                $user_membership->get_plan_id(),
                $new_status
            );

        }, 10, 3);

        add_action('wc_memberships_grant_membership_access_from_purchase', function ($membership_plan, $args) {

            /** @var \WC_Memberships_Membership_Plan $membership_plan */

            if ( ! is_a($membership_plan, '\WC_Memberships_Membership_Plan')) return;

            $this->subscribe_member(
                $args['user_id'],
                $membership_plan->get_id(),
                wc_memberships_get_user_membership($args['user_membership_id'])->get_status()
            );

        }, 10, 2);
    }

    public function enqueue_scripts()
    {
        global $post;

        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

        if (isset($post->post_type) && $post->post_type == 'wc_membership_plan') {

            wp_enqueue_script('mailoptin-woomem', MAILOPTIN_WOOCOMMERCE_MEMBERSHIPS_CONNECT_ASSETS_URL . 'woomem.js', ['jquery'], MAILOPTIN_VERSION_NUMBER, true);

            wp_localize_script('mailoptin-woomem', 'moWooMem', [
                'fields'                  => [],
                'ajax_url'                => admin_url('admin-ajax.php'),
                'nonce'                   => wp_create_nonce('mailoptin-woomem'),
                'select2_tag_connections' => Init::select2_tag_connections(),
                'text_tag_connections'    => Init::text_tag_connections()
            ]);
        }
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['woo_mem_plan_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function memberships_tab($tabs)
    {
        $tabs['mailoptin'] = [
            'label'  => 'MailOptin',
            'target' => 'mailoptin_wrap' // ID of the HTML tab body element
        ];

        return $tabs;
    }

    /**
     * @return mixed
     */
    public static function email_service_providers()
    {
        $connections = ConnectionsRepository::get_connections();

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $connections['leadbank'] = __('MailOptin Leads', 'mailoptin');
        }

        //escape webhook connection
        unset($connections['WebHookConnect']);
        unset($connections['WordPressUserRegistrationConnect']);

        return $connections;
    }

    /**
     * @return array
     */
    public function woo_select_integration_options()
    {
        $integrations = self::email_service_providers();

        if ( ! empty($integrations)) {

            $options[''] = esc_html__('Select...', 'mailoptin');

            foreach ($integrations as $value => $label) {

                if (empty($value)) continue;

                // Add list to select options.
                $options[$value] = $label;
            }

            return $options;
        }

        return [];
    }

    /**
     * @return array|false
     */
    public function woo_double_optin_settings($saved_double_optin, $saved_integration)
    {
        if (empty($saved_integration)) return false;

        $is_double_optin          = false;
        $double_optin_connections = Init::double_optin_support_connections();
        foreach ($double_optin_connections as $key => $value) {
            if ($saved_integration === $key) {
                $is_double_optin = $value;
            }
        }

        if (in_array($saved_integration, Init::double_optin_support_connections(true))) {
            return [
                'id'          => 'mailoptinWooMemDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => wc_bool_to_string($saved_double_optin),
            ];
        }

        return [];
    }

    /**
     * @return array
     */
    public function woo_checkout_fields()
    {
        $fields = [];

        $wcm_fields = Profile_Fields::get_profile_field_definitions();

        if (is_array($wcm_fields) && ! empty($wcm_fields)) {

            $fields[''] = '&mdash;&mdash;&mdash;';

            foreach ($wcm_fields as $wcm_field) {
                $fields[$wcm_field->get_slug()] = $wcm_field->get_name();
            }
        }

        return $fields;
    }

    /**
     * @return false|void
     */
    public function woo_lead_tag_settings($saved_tags, $saved_integration)
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

            woocommerce_wp_select(
                [
                    'id'                => 'mailoptinWooMemSelectTags',
                    'name'              => 'mailoptinWooMemSelectTags[]',
                    'label'             => esc_html__('Tags', 'mailoptin'),
                    'value'             => $saved_tags,
                    'options'           => $options,
                    'class'             => 'mowoo_select2',
                    'description'       => esc_html__('Select tags to assign to buyers or customers.', 'mailoptin'),
                    'custom_attributes' => [
                        'multiple' => 'multiple'
                    ]
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.mowoo_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            woocommerce_wp_text_input(
                [
                    'id'          => 'mailoptinWooMemTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to buyers or customers.', 'mailoptin'),
                ]
            );
        }
    }

    public function memberships_tab_content()
    {
        $post_id = get_the_ID();

        $integrations      = $this->woo_select_integration_options();
        $saved_integration = get_post_meta($post_id, 'mailoptinWooMemSelectIntegration', true);

        $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_memberships_connection';
        $doc_url    = 'https://mailoptin.io/article/woocommerce-memberships-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_memberships_connection';

        $content = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add customers that subscribe to this plan to a specific email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );
        ?>
        <style>
            .wc-memberships#wc-memberships-membership-plan-data ul.wc-tabs li.mailoptin_options a:before {
                content: url("<?php echo MAILOPTIN_WOOCOMMERCE_CONNECT_ASSETS_URL.'mailoptin-icon.svg' ?>");
            }

            #poststuff h2.mo-woocommerce-map-field-title {
                padding: 0 20px 0 0 !important;
                margin-left: 10px !important;
                margin-right: 162px !important;
            }

            h2.mo-woocommerce-map-field-title {
                width: 100%;
                text-align: left;
                border-bottom: 1px solid #c3c4c7;
                line-height: 0.1em !important;
                margin: 10px 0 20px !important;
                font-weight: bold !important;
            }

            h2.mo-woocommerce-map-field-title span {
                background: #fff;
                padding-right: 10px;
            }
        </style>
        <div id="mailoptin_wrap" class="panel woocommerce_options_panel">
            <?php if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) : ?>
                <div class="mo-woo-upsell-block">
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
            <?php else : wp_nonce_field('mailoptin_woomem', 'mailoptin_woomem_nonce'); ?>
                <div class="options_group">
                    <?php if ( ! empty($integrations)) {
                        woocommerce_wp_select(
                            [
                                'id'          => 'mailoptinWooMemSelectIntegration',
                                'label'       => esc_html__('Select Integration', 'mailoptin'),
                                'value'       => $saved_integration,
                                'options'     => $integrations,
                                'desc_tip'    => true,
                                'description' => __('Select your email marketing software or CRM.', 'mailoptin')
                            ]
                        );

                        ?>
                        <div class="mailoptin_woomem_email_list"></div>
                        <div class="mailoptin_woomem_custom_fields_tags"></div>
                        <?php
                    }
                    ?>
                </div>

            <?php endif; ?>
        </div>
        <?php
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-woomem', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        $plan_id = (int)moVarPOST('plan_id');

        ob_start();

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = get_post_meta($plan_id, $connection . '[mailoptinWooMemDoubleOptin]', true);
            $double_optin_settings = $this->woo_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = get_post_meta($plan_id, $connection . '[mailoptinWooMemSelectList]', true);
        }

        if (empty($lists)) wp_send_json_error([]);

        woocommerce_wp_select([
            'id'          => 'mailoptinWooMemSelectList',
            'label'       => esc_html__('Select List', 'mailoptin'),
            'value'       => $saved_lists,
            'options'     => $lists,
            'desc_tip'    => true,
            'description' => __('Select the email list, audience or segment to add customers to.', 'mailoptin'),
        ]);

        if ( ! empty($double_optin_settings)) {
            woocommerce_wp_checkbox($double_optin_settings);
        }

        wp_send_json_success([
            'lists' => ob_get_clean()
        ]);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-woomem', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $plan_id = (int)moVarPOST('plan_id');

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ob_start();

        ?>
        <h2 class="mo-woocommerce-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinWooMemMappedFields-' . $key);
            $saved_mapped_field = get_post_meta($plan_id, $connection . '[' . $mapped_key . ']', true);

            woocommerce_wp_select(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => $this->woo_checkout_fields(),
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = $connection . '[mailoptinWooMemTextTags]';
                $saved_tags = get_post_meta($plan_id, $tags_key, true);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = $connection . '[mailoptinWooMemSelectTags]';
                $saved_tags = json_decode(get_post_meta($plan_id, $tags_key, true), true);
            }
            $this->woo_lead_tag_settings($saved_tags, $connection);
        }

        wp_send_json_success([
            'fields' => ob_get_clean()
        ]);
    }


    public function save_mailoptin_integration($post_id)
    {
        if ( ! isset($_POST['mailoptin_woomem_nonce']) || ! wp_verify_nonce($_POST['mailoptin_woomem_nonce'], 'mailoptin_woomem')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['mailoptinWooMemSelectIntegration'])) {
            update_post_meta($post_id, 'mailoptinWooMemSelectIntegration', sanitize_text_field($_POST['mailoptinWooMemSelectIntegration']));
        }

        if ( ! empty($_POST['mailoptinWooMemSelectList']) && ! empty($_POST['mailoptinWooMemSelectIntegration'])) {

            $connection            = sanitize_text_field($_POST['mailoptinWooMemSelectIntegration']);
            $connection_email_list = sanitize_text_field($_POST['mailoptinWooMemSelectList']);

            update_post_meta($post_id, $connection . '[mailoptinWooMemSelectList]', $connection_email_list);

            foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                $mapped_key = rawurlencode('mailoptinWooMemMappedFields-' . $key);
                if (isset($_POST[$mapped_key])) {
                    $insert_mapped_key = $connection . '[' . $mapped_key . ']';
                    update_post_meta($post_id, $insert_mapped_key, sanitize_text_field($_POST[$mapped_key]));
                }
            }

            if ( ! empty($_POST['mailoptinWooMemDoubleOptin'])) {
                update_post_meta($post_id, $connection . '[mailoptinWooMemDoubleOptin]', true);
            } else {
                update_post_meta($post_id, $connection . '[mailoptinWooMemDoubleOptin]', false);
            }

            if ( ! empty($_POST['mailoptinWooMemTextTags'])) {
                $text_tags = sanitize_text_field($_POST['mailoptinWooMemTextTags']);
                update_post_meta($post_id, $connection . '[mailoptinWooMemTextTags]', $text_tags);
            }

            if ( ! empty($_POST['mailoptinWooMemSelectTags'])) {
                $select_tags = json_encode($_POST['mailoptinWooMemSelectTags']);
                update_post_meta($post_id, $connection . '[mailoptinWooMemSelectTags]', $select_tags);
            }
        }
    }

    /**
     * @param $user_id
     * @param $field_id
     *
     * @return mixed
     */
    public function get_field_value($user_id, $field_id)
    {
        $value = '';

        $value_obj = Profile_Fields::get_profile_field($user_id, $field_id);

        if (is_a($value_obj, Profile_Fields\Profile_Field::class)) {
            $value = $value_obj->get_value();
        }

        return $value;
    }

    public function subscribe_member($user_id, $plan_id, $new_status)
    {
        static $cache_bucket = [];

        $cache_key = implode(':', func_get_args());

        if (isset($cache_bucket[$cache_key])) return;

        $field_map = [];

        $connection_service = get_post_meta($plan_id, 'mailoptinWooMemSelectIntegration', true);

        if (empty($connection_service)) return;

        $connection_email_list = get_post_meta($plan_id, $connection_service . '[mailoptinWooMemSelectList]', true);

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key           = rawurlencode('mailoptinWooMemMappedFields-' . $key);
            $field_map_item_value = get_post_meta($plan_id, $connection_service . '[' . $mapped_key . ']', true);

            if ( ! empty($field_map_item_value)) {
                $field_map[$key] = $field_map_item_value;
            }
        }

        $user = get_userdata($user_id);

        if (empty($user->user_email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = $this->get_field_value($user_id, $value);
        }

        $double_optin = false;
        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = get_post_meta($plan_id, $connection_service . '[mailoptinWooMemDoubleOptin]', true) === "1";
        }

        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {
            $form_tags = get_post_meta($plan_id, $connection_service . '[mailoptinWooMemTextTags]', true);
        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $form_tags = json_decode(get_post_meta($plan_id, $connection_service . '[mailoptinWooMemSelectTags]', true), true);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user->first_name;
        $last_name  = $user->last_name;
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $user->user_email;
        $optin_data->optin_campaign_type = 'WooCommerce Memberships';

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

            $field_value = $this->get_field_value($user_id, $value);

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