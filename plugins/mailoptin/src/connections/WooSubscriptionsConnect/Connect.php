<?php

namespace MailOptin\WooSubscriptionsConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var MailBGProcess
     */
    public $wc_sub_bg_process_instance;
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'WooSubscriptionsConnect';

    public function __construct()
    {
        add_action('plugins_loaded', function () {

            if (class_exists('\WC_Subscriptions')) {

                add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
                add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
                add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);
                add_filter('mo_page_targeting_search_response', [$this, 'select2_search'], 10, 3);

                $this->wc_sub_bg_process_instance = new MailBGProcess();

                add_action('init', [$this, 'unsubscribe_handler']);
                add_action('init', [$this, 'view_online_version']);
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
        $connections[self::$connectionName] = __('WooCommerce Subscriptions', 'mailoptin');

        return $connections;
    }

    public function select2_search($response, $search_type, $q)
    {
        if ($search_type == 'WooSubscriptionsConnect_subscribers') {
            $subscribers = $this->get_members(500, '', '', $q);

            if (is_array($subscribers) && ! empty($subscribers)) {
                $response = [];
                foreach ($subscribers as $subscriber) {
                    $response[$subscriber->ID] = $subscriber->display_name . " ($subscriber->user_email)";
                }
            }
        }

        return $response;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings_bucket = [
            'WooSubscriptionsConnect_products',
            'WooSubscriptionsConnect_cancelled_products',
            'WooSubscriptionsConnect_expired_products',
            'WooSubscriptionsConnect_subscribers',
            'WooSubscriptionsConnect_notice'
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

    public function get_subscription_products()
    {
        $cache = false;

        if ($cache === false) {

            $products = wc_get_products([
                'type'   => array('subscription', 'subscription_variation', 'variable-subscription'),
                'limit'  => -1,
                'status' => 'publish'
            ]);

            $options = [];

            /** @var \WC_Product $product */
            foreach ($products as $product) {
                $options[$product->get_id()] = $product->get_name();
            }

            $cache = $options;
        }

        return $cache;
    }

    function get_members($limit = 0, $product_id = '', $status = '', $search = '', $page = 0)
    {
        global $wpdb;

        $replacements = [1];

        $sql = "SELECT DISTINCT u.*";
        if ( ! empty($product_id)) {
            $sql = "SELECT u.*, wcoim.meta_value as product_id";
        }

        $sql .= " FROM {$wpdb->prefix}posts as p
        JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id
        JOIN {$wpdb->prefix}users as u ON pm.meta_value = u.ID
        JOIN {$wpdb->prefix}woocommerce_order_items as wcoi ON wcoi.order_id = p.ID
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta as wcoim ON wcoim.order_item_id = wcoi.order_item_id";

        $sql .= " WHERE 1 = %d AND p.post_type = 'shop_subscription' AND pm.meta_key = '_customer_user'";

        if ( ! empty($status)) {
            $replacements[] = $status;
            $sql            .= " AND p.post_status = %s";
        }

        if ( ! empty($product_id)) {
            $replacements[] = $product_id;
            $sql            .= " AND wcoim.meta_key = '_product_id' AND wcoim.meta_value = %d";
        }

        if ( ! empty($search)) {
            $search         = '%' . $wpdb->esc_like(sanitize_text_field($search)) . '%';
            $replacements[] = $search;
            $replacements[] = $search;
            $replacements[] = $search;
            $sql            .= " AND (u.user_login LIKE %s OR u.user_email LIKE %s OR u.display_name LIKE %s)";
        }

        if ($limit > 0) {
            $replacements[] = $limit;
            $sql            .= " LIMIT %d";
        }

        if ($limit > 0 && $page > 0) {
            $replacements[] = ($page - 1) * $limit;
            $sql            .= " OFFSET %d";
        }

        return $wpdb->get_results($wpdb->prepare($sql, $replacements));
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
        $controls['WooSubscriptionsConnect_products'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooSubscriptionsConnect_products]',
            [
                'label'       => __('Restrict to Product Active Subscription', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooSubscriptionsConnect_products]',
                'description' => __('Select the subscription products whose active subscribers will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_subscription_products(),
                'priority'    => 62
            ]
        );

        $controls['WooSubscriptionsConnect_cancelled_products'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooSubscriptionsConnect_cancelled_products]',
            [
                'label'       => __('Restrict to Product Cancelled Subscription', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooSubscriptionsConnect_cancelled_products]',
                'description' => __('Select the subscription products whose cancelled subscribers will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_subscription_products(),
                'priority'    => 63
            ]
        );

        $controls['WooSubscriptionsConnect_expired_products'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooSubscriptionsConnect_expired_products]',
            [
                'label'       => __('Restrict to Product Expired Subscription', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooSubscriptionsConnect_expired_products]',
                'description' => __('Select the subscription products whose expired subscribers will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_subscription_products(),
                'priority'    => 64
            ]
        );

        $controls['WooSubscriptionsConnect_subscribers'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooSubscriptionsConnect_subscribers]',
            [
                'label'       => __('Restrict to Selected Subscribers', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooSubscriptionsConnect_subscribers]',
                'description' => __('Select the subscribers that emails will only be delivered to.', 'mailoptin'),
                'search_type' => 'WooSubscriptionsConnect_subscribers',
                'choices'     => (function () {
                    $subscribers = $this->get_members(500);
                    $bucket      = [];
                    foreach ($subscribers as $subscriber) {
                        $bucket[$subscriber->ID] = $subscriber->display_name . " ($subscriber->user_email)";
                    }

                    return $bucket;
                })(),
                'priority'    => 65
            ]
        );

        $controls['WooSubscriptionsConnect_notice'] = new WP_Customize_Custom_Content(
            $wp_customize,
            $option_prefix . '[WooSubscriptionsConnect_notice]',
            array(
                'content'  => esc_html__('Leave all "Restrict to ..." settings empty to send to all subscribers.', 'mailoptin'),
                'section'  => $customizerClassInstance->campaign_settings_section_id,
                'settings' => $option_prefix . '[WooSubscriptionsConnect_notice]',
                'priority' => 66,
            )
        );

        return $controls;
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
        $active_subscription_products    = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooSubscriptionsConnect_products', []);
        $cancelled_subscription_products = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooSubscriptionsConnect_cancelled_products', []);
        $expired_subscription_products   = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooSubscriptionsConnect_expired_products', []);

        $subscribers = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooSubscriptionsConnect_subscribers', []);

        $bucket = [];

        if (empty($active_subscription_products) && empty($cancelled_subscription_products) && empty($expired_subscription_products) && empty($subscribers)) {

            $page  = 1;
            $loop  = true;
            $limit = 2000;

            while ($loop === true) {

                $users = $this->get_members($limit, '', '', '', $page);

                if ( ! empty($users)) {

                    foreach ($users as $user) {

                        if (in_array($user->user_email, $bucket)) continue;

                        $item             = new \stdClass();
                        $item->user_email = $user->user_email;
                        $bucket[]         = $user->user_email;

                        $item->email_campaign_id = $email_campaign_id;
                        $item->campaign_log_id   = $campaign_log_id;

                        $this->wc_sub_bg_process_instance->push_to_queue($item);
                    }
                }

                if (count($users) < $limit) {
                    $loop = false;
                }

                $page++;
            }

        } else {

            if (is_array($active_subscription_products) && ! empty($active_subscription_products)) {

                foreach ($active_subscription_products as $product_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $_users = $this->get_members($_limit, $product_id, 'wc-active', '', $_page);

                        if ( ! empty($_users)) {

                            foreach ($_users as $_user) {

                                if (in_array($_user->user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $_user->user_email;
                                $bucket[]         = $_user->user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->wc_sub_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($_users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if (is_array($cancelled_subscription_products) && ! empty($cancelled_subscription_products)) {

                foreach ($cancelled_subscription_products as $product_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $_users = $this->get_members($_limit, $product_id, 'wc-cancelled', '', $_page);

                        if ( ! empty($_users)) {

                            foreach ($_users as $_user) {

                                if (in_array($_user->user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $_user->user_email;
                                $bucket[]         = $_user->user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->wc_sub_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($_users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if (is_array($expired_subscription_products) && ! empty($expired_subscription_products)) {

                foreach ($expired_subscription_products as $product_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $_users = $this->get_members($_limit, $product_id, 'wc-expired', '', $_page);

                        if ( ! empty($_users)) {

                            foreach ($_users as $_user) {

                                if (in_array($_user->user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $_user->user_email;
                                $bucket[]         = $_user->user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->wc_sub_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($_users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if ( ! empty($subscribers)) {

                foreach ($subscribers as $subscriber) {

                    $user = get_userdata(absint($subscriber));

                    if (in_array($user->user_email, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = $user->user_email;
                    $bucket[]         = $user->user_email;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->wc_sub_bg_process_instance->push_to_queue($item);
                }
            }
        }

        $this->wc_sub_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                         ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_wcsubscription_unsubscribe']) || empty($_GET['mo_wcsubscription_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_wcsubscription_unsubscribe']);

        $contacts   = get_option('mo_wcsubscription_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_wcsubscription_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_wcsubscription_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_wcsubscription_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

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