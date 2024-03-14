<?php

namespace MailOptin\EasyDigitalDownloadsConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\ControlsHelpers;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var MailBGProcess
     */
    public $edd_bg_process_instance;

    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'EasyDigitalDownloadsConnect';

    public function __construct()
    {
        add_action('plugins_loaded', function () {
            if (class_exists('\Easy_Digital_Downloads')) {
                EDDInit::get_instance();

                add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
                add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
                add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

                $this->edd_bg_process_instance = new MailBGProcess();

                add_action('init', [$this, 'unsubscribe_handler']);
                add_action('init', [$this, 'view_online_version']);

                add_filter('mo_page_targeting_search_response', [$this, 'select2_search'], 10, 3);
            }
        });
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Easy Digital Downloads', 'mailoptin');

        return $connections;
    }


    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings_bucket = [
            'EasyDigitalDownloadsConnect_products',
            'EasyDigitalDownloadsConnect_customers',
            'EasyDigitalDownloadsConnect_active_sub',
            'EasyDigitalDownloadsConnect_expired_sub',
            'EasyDigitalDownloadsConnect_cancelled_sub',
            'EasyDigitalDownloadsConnect_completed_sub',
            'EasyDigitalDownloadsConnect_notice'
        ];

        foreach ($settings_bucket as $item) {

            $settings[$item] = [
                'default'   => '',
                'type'      => 'option',
                'transport' => 'postMessage',
            ];
        }

        return $settings;
    }

    /**
     * @param array $controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param Customizer $customizerClassInstance
     *
     * @return mixed
     */
    public function integration_customizer_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        // always prefix with the name of the connect/connection service.
        $controls['EasyDigitalDownloadsConnect_customers'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[EasyDigitalDownloadsConnect_customers]',
            array(
                'label'       => __('Restrict to Selected Customers', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[EasyDigitalDownloadsConnect_customers]',
                'description' => __('Select the customers that emails will only be delivered to.', 'mailoptin'),
                'search_type' => 'edd_customers',
                'choices'     => $this->get_customers(),
                'priority'    => 62
            )
        );

        $controls['EasyDigitalDownloadsConnect_products'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[EasyDigitalDownloadsConnect_products]',
            array(
                'label'       => __('Restrict to Products', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[EasyDigitalDownloadsConnect_products]',
                'description' => __('Select the products or downloads whose customers will receive emails from this campaign.', 'mailoptin'),
                'search_type' => 'edd_products',
                'choices'     => ControlsHelpers::get_post_type_posts('download'),
                'priority'    => 63
            )
        );

        if (class_exists('\EDD_Recurring')) {

            $controls['EasyDigitalDownloadsConnect_active_sub'] = new WP_Customize_Chosen_Select_Control(
                $wp_customize,
                $option_prefix . '[EasyDigitalDownloadsConnect_active_sub]',
                array(
                    'label'       => __('Restrict to Product Active Subscription', 'mailoptin'),
                    'section'     => $customizerClassInstance->campaign_settings_section_id,
                    'settings'    => $option_prefix . '[EasyDigitalDownloadsConnect_active_sub]',
                    'description' => __('Select the subscription products whose active subscribers will receive emails from this campaign.', 'mailoptin'),
                    'search_type' => 'edd_products',
                    'choices'     => ControlsHelpers::get_post_type_posts('download'),
                    'priority'    => 64
                )
            );

            $controls['EasyDigitalDownloadsConnect_cancelled_sub'] = new WP_Customize_Chosen_Select_Control(
                $wp_customize,
                $option_prefix . '[EasyDigitalDownloadsConnect_cancelled_sub]',
                array(
                    'label'       => __('Restrict to Product Cancelled Subscription', 'mailoptin'),
                    'section'     => $customizerClassInstance->campaign_settings_section_id,
                    'settings'    => $option_prefix . '[EasyDigitalDownloadsConnect_cancelled_sub]',
                    'description' => __('Select the subscription products whose cancelled subscribers will receive emails from this campaign.', 'mailoptin'),
                    'search_type' => 'edd_products',
                    'choices'     => ControlsHelpers::get_post_type_posts('download'),
                    'priority'    => 65
                )
            );

            $controls['EasyDigitalDownloadsConnect_expired_sub'] = new WP_Customize_Chosen_Select_Control(
                $wp_customize,
                $option_prefix . '[EasyDigitalDownloadsConnect_expired_sub]',
                array(
                    'label'       => __('Restrict to Product Expired Subscription', 'mailoptin'),
                    'section'     => $customizerClassInstance->campaign_settings_section_id,
                    'settings'    => $option_prefix . '[EasyDigitalDownloadsConnect_expired_sub]',
                    'description' => __('Select the subscription products whose expired subscribers will receive emails from this campaign.', 'mailoptin'),
                    'search_type' => 'edd_products',
                    'choices'     => ControlsHelpers::get_post_type_posts('download'),
                    'priority'    => 66
                )
            );

            $controls['EasyDigitalDownloadsConnect_completed_sub'] = new WP_Customize_Chosen_Select_Control(
                $wp_customize,
                $option_prefix . '[EasyDigitalDownloadsConnect_completed_sub]',
                array(
                    'label'       => __('Restrict to Product Completed Subscription', 'mailoptin'),
                    'section'     => $customizerClassInstance->campaign_settings_section_id,
                    'settings'    => $option_prefix . '[EasyDigitalDownloadsConnect_completed_sub]',
                    'description' => __('Select the subscription products whose completed subscribers will receive emails from this campaign.', 'mailoptin'),
                    'search_type' => 'edd_products',
                    'choices'     => ControlsHelpers::get_post_type_posts('download'),
                    'priority'    => 67
                )
            );
        }

        $controls['EasyDigitalDownloadsConnect_notice'] = new WP_Customize_Custom_Content(
            $wp_customize,
            $option_prefix . '[EasyDigitalDownloadsConnect_notice]',
            array(
                'content'  => esc_html__('Leave all "Restrict to ..." settings empty to send to all customers.', 'mailoptin'),
                'section'  => $customizerClassInstance->campaign_settings_section_id,
                'settings' => $option_prefix . '[EasyDigitalDownloadsConnect_notice]',
                'priority' => 68
            )
        );

        return $controls;
    }

    public function select2_search($response, $search_type, $q)
    {
        if ($search_type == 'edd_products') {
            $response = ControlsHelpers::get_post_type_posts('download', 500, 'publish', $q);
        }

        if ($search_type == 'edd_customers') {
            $response = $this->get_customers(500, $q);
        }

        return $response;
    }

    protected function get_customers($number = 200, $search = '', $page = 1)
    {
        $page = $page < 1 ? 1 : $page;

        $cache_key = sprintf('edd_customer_%s_%s_%s', $number, $search, $page);

        static $cache = [];

        if ( ! isset($cache[$cache_key])) {

            $offset = ($page - 1) * $number;

            $all_users = edd_get_customers([
                'number' => intval($number),
                'fields' => ['email', 'name'],
                'search' => $search,
                'offset' => $offset
            ]);

            $result = [];

            foreach ($all_users as $user) {
                $result[$user->email] = sprintf('%s (%s)', $user->name, $user->email);
            }

            $cache[$cache_key] = $result;
        }

        return $cache[$cache_key];
    }

    public function edd_product_customers($product_id, $limit = 0, $page = 1)
    {
        $page = $page < 1 ? 1 : $page;

        $offset = ($page - 1) * $limit;

        global $wpdb;

        $replacements = [$product_id];

        $statuses  = "'" . implode("', '", $wpdb->_escape(edd_get_deliverable_order_item_statuses())) . "'";
        $status_id = "oi.status IN({$statuses})";

        $sql = "SELECT DISTINCT o.email AS email
		FROM {$wpdb->edd_orders} o
		INNER JOIN {$wpdb->edd_order_items} oi ON o.id = oi.order_id
		WHERE {$status_id}
		AND oi.product_id = %d
		AND o.type = 'sale'";

        if ($limit > 0) {
            $sql            .= " LIMIT %d";
            $replacements[] = $limit;
        }

        if ($offset > 0) {
            $sql            .= "  OFFSET %d";
            $replacements[] = $offset;
        }

        return $wpdb->get_col(
            $wpdb->prepare(
                $sql,
                $replacements
            )
        );
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_edd_unsubscribe']) || empty($_GET['mo_edd_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_edd_unsubscribe']);

        $contacts   = get_option('mo_edd_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_edd_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_edd_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_edd_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

        wp_die($success_message, $success_message, ['response' => 200]);
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
        $products  = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'EasyDigitalDownloadsConnect_products', []);
        $customers = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'EasyDigitalDownloadsConnect_customers', []);

        $active_sub_products    = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'EasyDigitalDownloadsConnect_active_sub', []);
        $expired_sub_products   = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'EasyDigitalDownloadsConnect_expired_sub', []);
        $cancelled_sub_products = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'EasyDigitalDownloadsConnect_cancelled_sub', []);
        $completed_sub_products = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'EasyDigitalDownloadsConnect_completed_sub', []);

        $bucket = [];

        if (empty($products) && empty($customers) && empty($active_sub_products) && empty($expired_sub_products) && empty($cancelled_sub_products) && empty($completed_sub_products)) {

            $page  = 1;
            $loop  = true;
            $limit = 2000;

            while ($loop === true) {

                $users = $this->get_customers($limit, '', $page);

                if ( ! empty($users)) {

                    foreach ($users as $user_email => $user_name) {

                        if (in_array($user_email, $bucket)) continue;

                        $item             = new \stdClass();
                        $item->user_email = $user_email;
                        $bucket[]         = $user_email;

                        $item->email_campaign_id = $email_campaign_id;
                        $item->campaign_log_id   = $campaign_log_id;

                        $this->edd_bg_process_instance->push_to_queue($item);
                    }
                }

                if (count($users) < $limit) {
                    $loop = false;
                }

                $page++;
            }

        } else {

            if (is_array($products) && ! empty($products)) {

                foreach ($products as $download_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $_users = $this->edd_product_customers($download_id, $_limit, $_page);

                        if ( ! empty($_users)) {

                            foreach ($_users as $_user_email) {

                                if (in_array($_user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $_user_email;
                                $bucket[]         = $_user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->edd_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($_users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if ( ! empty($customers)) {

                foreach ($customers as $customer) {

                    if (in_array($customer, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = $customer;
                    $bucket[]         = $customer;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->edd_bg_process_instance->push_to_queue($item);
                }
            }


            if (class_exists('\EDD_Subscriptions_DB')) {

                if (is_array($active_sub_products) && ! empty($active_sub_products)) {

                    foreach ($active_sub_products as $download_id) {

                        $subs = (new \EDD_Subscriptions_DB())->get_subscriptions(['product_id' => $download_id, 'status' => 'active', 'number' => 0]);

                        if ( ! empty($subs)) {

                            foreach ($subs as $sub) {

                                if (in_array($sub->customer->email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $sub->customer->email;
                                $bucket[]         = $sub->customer->email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->edd_bg_process_instance->push_to_queue($item);
                            }
                        }
                    }
                }

                if (is_array($expired_sub_products) && ! empty($expired_sub_products)) {

                    foreach ($expired_sub_products as $download_id) {

                        $subs = (new \EDD_Subscriptions_DB())->get_subscriptions(['product_id' => $download_id, 'status' => 'expired', 'number' => 0]);

                        if ( ! empty($subs)) {

                            foreach ($subs as $sub) {

                                if (in_array($sub->customer->email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $sub->customer->email;
                                $bucket[]         = $sub->customer->email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->edd_bg_process_instance->push_to_queue($item);
                            }
                        }
                    }
                }

                if (is_array($cancelled_sub_products) && ! empty($cancelled_sub_products)) {

                    foreach ($cancelled_sub_products as $download_id) {

                        $subs = (new \EDD_Subscriptions_DB())->get_subscriptions(['product_id' => $download_id, 'status' => 'cancelled', 'number' => 0]);

                        if ( ! empty($subs)) {

                            foreach ($subs as $sub) {

                                if (in_array($sub->customer->email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $sub->customer->email;
                                $bucket[]         = $sub->customer->email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->edd_bg_process_instance->push_to_queue($item);
                            }
                        }
                    }
                }

                if (is_array($completed_sub_products) && ! empty($completed_sub_products)) {

                    foreach ($completed_sub_products as $download_id) {

                        $subs = (new \EDD_Subscriptions_DB())->get_subscriptions(['product_id' => $download_id, 'status' => 'completed', 'number' => 0]);

                        if ( ! empty($subs)) {

                            foreach ($subs as $sub) {

                                if (in_array($sub->customer->email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $sub->customer->email;
                                $bucket[]         = $sub->customer->email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->edd_bg_process_instance->push_to_queue($item);
                            }
                        }
                    }
                }
            }
        }

        $this->edd_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                      ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
    }

    /**
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