<?php

namespace MailOptin\WooCommerceConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\ControlsHelpers;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var WoocommerceMailBGProcess
     */
    public $woo_bg_process_instance;

    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'WooCommerceConnect';

    public function __construct()
    {
        // Early action for WooCommerce compatibility.
        add_action('before_woocommerce_init', [$this, 'declare_hpos_compat']);

        add_action('woocommerce_init', function () {
            WooInit::get_instance();

            add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
            add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
            add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

            $this->woo_bg_process_instance = new WoocommerceMailBGProcess();

            add_action('init', [$this, 'unsubscribe_handler']);
            add_action('init', [$this, 'view_online_version']);
        });
    }

    public function declare_hpos_compat()
    {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', MAILOPTIN_SYSTEM_FILE_PATH, true);
        }
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('WooCommerce', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['WooCommerceConnect_products'] = [
            'default'   => '',
            'type'      => 'option',
            'transport' => 'postMessage',
        ];

        $settings['WooCommerceConnect_customers'] = [
            'default'   => '',
            'type'      => 'option',
            'transport' => 'postMessage',
        ];

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
        $controls['WooCommerceConnect_products'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooCommerceConnect_products]',
            array(
                'label'       => __('Restrict to Products', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooCommerceConnect_products]',
                'description' => __('Select the products whose customers will receive emails from this campaign. Leave this and "Restrict to Selected Customers" empty to send to all customers.', 'mailoptin'),
                'search_type' => 'woocommerce_products',
                'choices'     => ControlsHelpers::get_post_type_posts('product'),
                'priority'    => 62
            )
        );

        $controls['WooCommerceConnect_customers'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooCommerceConnect_customers]',
            array(
                'label'       => __('Restrict to Selected Customers', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooCommerceConnect_customers]',
                'description' => __('Select the customers that emails will only be delivered to. Leave this and "Restrict to Products" empty to send to all customers.', 'mailoptin'),
                'search_type' => 'woocommerce_customers',
                'choices'     => $this->get_customers(),
                'priority'    => 63
            )
        );

        return $controls;
    }

    protected function get_customers()
    {
        static $cache = null;

        if (is_null($cache)) {

            $all_users = get_users([
                'number' => 200,
                'role'   => 'customer',
                'fields' => ['ID', 'user_email', 'display_name']
            ]);

            $result = [];

            foreach ($all_users as $user) {
                $result[$user->ID] = sprintf('%s (%s)', $user->display_name, $user->user_email);
            }

            $cache = $result;
        }

        return $cache;
    }

    protected function get_product_customers($product_id, $limit = 0, $page = 0)
    {
        global $wpdb;

        $statuses = array_map('esc_sql', wc_get_is_paid_statuses());

        $replacements = [$product_id];

        $sql = "SELECT DISTINCT pm.meta_value FROM {$wpdb->posts} AS p
              INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
              INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
              INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
              WHERE p.post_status IN ( 'wc-" . implode("','wc-", $statuses) . "' )
              AND pm.meta_key IN ( '_billing_email' )
              AND im.meta_key IN ( '_product_id', '_variation_id' )
              AND im.meta_value = %d
        ";

        if ($limit > 0) {
            $replacements[] = $limit;
            $sql            .= " LIMIT %d";
        }

        if ($limit > 0 && $page > 0) {
            $replacements[] = ($page - 1) * $limit;
            $sql            .= " OFFSET %d";
        }

        return $wpdb->get_col($wpdb->prepare($sql, $replacements));
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
        $products  = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooCommerceConnect_products', []);
        $customers = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooCommerceConnect_customers', []);

        $bucket = [];

        if (empty($products) && empty($customers)) {

            $page = 1;
            $loop = true;

            while ($loop === true) {

                $users = get_users([
                    'number' => 2000,
                    'paged'  => $page,
                    'role'   => 'customer',
                    'fields' => ['user_email']
                ]);

                foreach ($users as $user) {

                    if (in_array($user->user_email, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = $user->user_email;
                    $bucket[]         = $user->user_email;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->woo_bg_process_instance->push_to_queue($item);
                }

                if (count($users) < 2000) {
                    $loop = false;
                }

                $page++;
            }

        } else {

            if (is_array($products) && ! empty($products)) {

                foreach ($products as $product) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $_users = $this->get_product_customers($product, $_limit, $_page);

                        if ( ! empty($_users)) {

                            foreach ($_users as $_user_email) {

                                if (in_array($_user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $_user_email;
                                $bucket[]         = $_user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->woo_bg_process_instance->push_to_queue($item);
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

                foreach ($customers as $user_id) {

                    $user = get_userdata(absint($user_id));

                    if (in_array($user->user_email, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = $user->user_email;
                    $bucket[]         = $user->user_email;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->woo_bg_process_instance->push_to_queue($item);
                }
            }
        }

        $this->woo_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                      ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_woo_unsubscribe']) || empty($_GET['mo_woo_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_woo_unsubscribe']);

        $contacts   = get_option('mo_woo_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_woo_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_woo_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_woo_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

        wp_die($success_message, $success_message, ['response' => 200]);
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