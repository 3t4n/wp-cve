<?php

namespace MailOptin\LeadBankConnect;

use MailOptin\Core\Connections\ConnectionInterface;
use MailOptin\Core\Repositories\OptinConversionsRepository as ConversionsRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect implements ConnectionInterface
{
    /**
     * @var WP_Leadbank_Mail_BG_Process
     */
    public $lb_process_instance;

    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'LeadBankConnect';

    public function __construct()
    {
        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_action('plugins_loaded', array($this, 'init'));
        add_action('init', [$this, 'unsubscribe_handler']);
        add_action('init', [$this, 'view_online_version']);
    }

    public function init()
    {
        $this->lb_process_instance = new WP_Leadbank_Mail_BG_Process();
    }

    public static function features_support()
    {
        return [self::EMAIL_CAMPAIGN_SUPPORT];
    }

    /**
     * Register Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('MailOptin Leads', 'mailoptin');

        return $connections;
    }

    /**
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
        $lead_bank = ConversionsRepository::get_conversions();

        // campaign log and email campaign IDs to each $users_data
        $users_data = [];

        foreach ($lead_bank as $item) {

            $email_address = $item['email'];

            $user_data['email_address']     = $email_address;
            $user_data['email_campaign_id'] = $email_campaign_id;
            $user_data['campaign_log_id']   = $campaign_log_id;
            // using email address as array key to prevent sending multiple email to one email
            // because leadbank can have one email address subscriber eg say subscribes via popup and sidebar.
            $users_data[$email_address] = $user_data;
        }

        foreach ($users_data as $user_data) {
            $this->lb_process_instance->push_to_queue($user_data);
        }

        $this->lb_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                  ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_leadbank_unsubscribe']) || empty($_GET['mo_leadbank_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_leadbank_unsubscribe']);

        $contacts   = get_option('mo_leadbank_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_leadbank_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_leadbank_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_leadbank_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

        wp_die($success_message, $success_message, ['response' => 200]);
    }

    /**
     * @return Connect
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