<?php

namespace MailOptin\PmProConnect;

use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\ConnectionsRepository;
use function MailOptin\Core\moVarGET;
use function MailOptin\Core\moVarPOST;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_PMPRO_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/PmProConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_PMPRO_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class PMPROInit
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_action('pmpro_checkout_before_submit_button', [$this, 'display_signup_field']);

        add_action('pmpro_checkout_before_change_membership_level', [$this, 'save_optin_checkbox_state'], 10, 2);
        add_action('pmpro_order_status_success', [$this, 'process_signup']);
    }

    public function enqueue_scripts()
    {
        global $post;

        if (moVarGET('page') == 'pmpro-membershiplevels' && defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', ['jquery'], false, true);
            wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
        }

        $screen = get_current_screen();

        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-pmpro-settings', MAILOPTIN_PMPRO_CONNECT_ASSETS_URL . 'settings.js', ['jquery'], MAILOPTIN_VERSION_NUMBER, true);
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', ['jquery'], false, true);
            wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
        }
    }

    protected function is_auto_subscribe_enabled()
    {
        return Settings::instance()->mailoptin_pmpro_subscribe_method() != 'yes';
    }

    public function display_signup_field()
    {
        $saved_connections = Settings::instance()->mailoptin_pmpro_integration_connections();

        if ( ! empty($saved_connections) && ! $this->is_auto_subscribe_enabled()) {

            $optin_label = Settings::instance()->mailoptin_pmpro_optin_checkbox_label();

            if ( empty($optin_label)) $optin_label = __('Subscribe to our newsletters', 'mailoptin');

            ?>
            <div class="pmpro_checkout-field  pmpro_checkout-field-checkbox">
                <input name="mopmpro_opt_in" type="checkbox" value="1" id="mopmpro_opt_in" class="input ">
                <label class="pmprorh_checkbox_label" for="mopmpro_opt_in"><?= $optin_label; ?></label>
            </div>
            <?php
        }
    }

    public function save_optin_checkbox_state($user_id, $morder)
    {
        if ( ! $this->is_auto_subscribe_enabled() && moVarPOST('mopmpro_opt_in') == '1') {
            update_option(sprintf('mo_pmpro_subscribed_checked_%s', $morder->code), 'yes');
        }
    }


    public function process_signup($morder)
    {
        if ($this->is_auto_subscribe_enabled() || get_option(sprintf('mo_pmpro_subscribed_checked_%s', $morder->code)) == 'yes') {

            delete_option(sprintf('mo_pmpro_subscribed_checked_%s', $morder->code));

            Membership::get_instance()->process_submission($morder);

            PMPROSettings::get_instance()->process_submission($morder);
        }
    }

    public function pmpro_fields()
    {
        $user_fields = [
            ''              => '&mdash;&mdash;&mdash;',
            'ID'            => __('User ID', 'mailoptin'),
            'user_login'    => __('Username', 'mailoptin'),
            'user_nicename' => __('User Nicename', 'mailoptin'),
            'user_url'      => __('Website URL', 'mailoptin'),
            'user_email'    => __('Email address', 'mailoptin'),
            'display_name'  => __('Display Name', 'mailoptin'),
            'nickname'      => __('Nickname', 'mailoptin'),
            'first_name'    => __('First Name', 'mailoptin'),
            'last_name'     => __('Last Name', 'mailoptin'),
            'description'   => __('Biographical Info ', 'mailoptin'),

            'pmpro_bname'    => __('Billing Name', 'mailoptin'),
            'pmpro_bstreet'  => __('Billing Street', 'mailoptin'),
            'pmpro_bcity'    => __('Billing City', 'mailoptin'),
            'pmpro_bstate'   => __('Billing State/Province', 'mailoptin'),
            'pmpro_bzipcode' => __('Billing Postal Code', 'mailoptin'),
            'pmpro_bphone'   => __('Billing Phone Number', 'mailoptin'),
            'pmpro_bcountry' => __('Billing Country', 'mailoptin')
        ];

        $pmpro_custom_fields = pmpro_get_user_fields();

        if ( ! empty($pmpro_custom_fields)) {
            $user_fields = array_merge($user_fields, $this->return_pmpro_fields($pmpro_custom_fields));
        }

        return apply_filters('mo_pmpro_custom_users_mapped_fields', array_unique($user_fields));
    }

    public function return_pmpro_fields($fields)
    {
        $pmpro_fields = [];

        if ( ! empty($fields)) {

            foreach ($fields as $field) {

                if (is_array($field)) {

                    foreach ($field as $item) {

                        if (isset($item->meta_key)) {
                            $pmpro_fields[$item->meta_key] = $item->label;
                        }
                    }

                } elseif (isset($field->meta_key)) {
                    $pmpro_fields[$field->meta_key] = $field->label;
                }
            }
        }

        return $pmpro_fields;
    }

    /**
     * @param $value
     * @param $user_id
     *
     * @return string
     */
    public function get_field_value($value, $user_id, \MemberOrder $morder)
    {
        switch ($value) {
            case 'pmpro_bname':
                return $morder->billing->name;
            case 'pmpro_bstreet':
                return $morder->billing->street;
            case 'pmpro_bcity':
                return $morder->billing->city;
            case 'pmpro_bstate':
                return $morder->billing->state;
            case 'pmpro_bzipcode':
                return $morder->billing->zip;
            case 'pmpro_bphone':
                return $morder->billing->phone;
            case 'pmpro_bcountry':
                return $morder->billing->country;
            default:
                $user = get_userdata($user_id);
                if ($user && $user->exists() && isset($user->$value)) {
                    return $user->$value;
                }
        }

        return '';
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

        unset($connections['WebHookConnect']);
        unset($connections['WordPressUserRegistrationConnect']);

        return $connections;
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