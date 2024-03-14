<?php

namespace MailOptin\WordPressUserRegistrationConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'WordPressUserRegistrationConnect';

    public function __construct()
    {
        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_action('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'));

        parent::__construct();
    }

    public static function features_support()
    {
        return [
            self::OPTIN_CAMPAIGN_SUPPORT,
            self::OPTIN_CUSTOM_FIELD_SUPPORT
        ];
    }

    /**
     * Register Constant Contact Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('WordPress User Registration', 'mailoptin');

        return $connections;
    }

    public function integration_customizer_settings($defaults)
    {
        $defaults['connection_email_list'] = 'subscriber';

        $defaults['WordPressUserRegistrationConnect_autologin'] = apply_filters('mailoptin_customizer_optin_campaign_WordPressUserRegistrationConnect_autologin', false);

        return $defaults;
    }

    /**
     * @param $controls
     *
     * @return array
     */
    public function integration_customizer_controls($controls)
    {

        if (defined('MAILOPTIN_DETACH_LIBSODIUM') === true) {
            $controls[] = [
                'field'       => 'toggle',
                'name'        => 'WordPressUserRegistrationConnect_autologin',
                'label'       => __('Enable Auto Login', 'mailoptin'),
                'description' => __("Automatically log users in to your site after a successful registration.", 'mailoptin')
            ];
        } else {

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to automatically log in users after registration.", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=wpuserregistration_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            $controls[] = [
                'name'    => 'WordPressUserRegistrationConnect_upgrade_notice',
                'field'   => 'custom_content',
                'content' => $content
            ];
        }

        return $controls;
    }

    /**
     * Fulfill interface contract.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        return $this->replace_footer_placeholder_tags($content);
    }

    /**
     * {@inherit_doc}
     *
     * Return array of email list
     *
     * @return mixed
     */
    public function get_email_list()
    {
        $wp_roles = wp_roles()->roles;
        $wp_roles = array_reduce(array_keys($wp_roles), function ($carry, $item) use ($wp_roles) {

            if ('administrator' != $item) {
                $carry[$item] = $wp_roles[$item]['name'];
            }

            return $carry;
        });

        return $wp_roles;
    }

    /**
     * {@inherit_doc}
     *
     * Return array of email list
     *
     * @param string $list_id
     *
     * @return mixed
     */
    public function get_optin_fields($list_id = '')
    {
        return [
            'user_login'   => __('Username', 'mailoptin'),
            'user_pass'    => __('Password', 'mailoptin'),
            'user_url'     => __('Website', 'mailoptin'),
            'display_name' => __('Display Name', 'mailoptin'),
            'nickname'     => __('Nickname', 'mailoptin'),
            'description'  => __('Biographical Description', 'mailoptin')
        ];
    }

    /**
     *
     * {@inheritdoc}
     *
     * @param int $email_campaign_id
     * @param int $campaign_log_id
     * @param string $subject
     * @param string $content_html
     * @param string $content_text
     *
     * @return array
     * @throws \Exception
     *
     */
    public function send_newsletter($email_campaign_id, $campaign_log_id, $subject, $content_html, $content_text)
    {
        return [];
    }

    /**
     * @param string $name
     * @param string $email
     * @param $role
     * @param mixed|null $extras
     *
     * @return mixed
     */
    public function subscribe($name, $email, $role, $extras = null)
    {
        return (new Subscription($name, $email, $role, $extras))->subscribe();
    }

    /**
     * Singleton poop.
     *
     * @return Connect|null
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