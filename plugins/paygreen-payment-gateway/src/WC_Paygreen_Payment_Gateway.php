<?php

namespace Paygreen\Module;

use Paygreen\Module\Exception\WC_Paygreen_Payment_Exception;
use Paygreen\Module\Exception\WC_Paygreen_Payment_Forbidden_Access_Exception;
use Paygreen\Module\Exception\WC_Paygreen_Payment_Listener_Exception;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Listener_Helper;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Payment_Order_Helper;
use Paygreen\Sdk\Payment\V3\Environment;
use WC_Order;
use WC_Payment_Gateway;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *  WC_Paygreen_Payment_Gateway class.
 */
class WC_Paygreen_Payment_Gateway extends WC_Payment_Gateway
{
    const ID = 'paygreen_payment';

    /** @var string */
    protected $environment;
    /** @var string */
    protected $shop_id;
    /** @var string */
    protected $public_key;
    /** @var string */
    protected $secret_key;
    /** @var string */
    protected $sub_shop_id;

    public function __construct()
    {
        $this->id = self::ID;
        $this->icon = ''; // apply_filters( 'woocommerce_gateway_icon', plugins_url('assets/img/logo-bank_card.svg', WC_PAYGREEN_PAYMENT_MAIN_FILE));
        $this->has_fields = true;
        $this->method_title = 'PayGreen';
        $this->method_description = __('PayGreen is a 100% French online payment solution that allows you to accept and manage payments on your e-commerce in a simple and efficient way. We are the first payment platform focused on sustainable development.', 'paygreen-payment-gateway');
        // gateways can support products, subscriptions, refunds, saved payment methods,
        $this->supports = ['products'];
        // We need to set up screen_button (screen_button is used to handle which admin section to display)
        $this->form_fields = [
            'screen_button' => [
                'id'    => 'menu',
                'type'  => 'screen_button',
            ],
        ];

        $this->init_settings();
        $this->init_form_fields();

        $this->title = $this->get_option('title', __('Pay with PayGreen', 'paygreen-payment-gateway'));
        $this->description = $this->get_option('description');
        $this->environment = $this->get_option('environment');
        $this->shop_id = $this->get_option('shop_id');
        $this->public_key = $this->get_option('public_key');
        $this->secret_key = $this->get_option('secret_key');
        $this->sub_shop_id = $this->get_option('sub_shop_id');

        // Allow to not display the description if it's empty on older versions of woocommerce
        if (empty($this->get_option('description'))) {
            $this->has_fields = false;
        }

        // Hook used to saved settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        // Hook used to display errors which is in the parent class.
        add_action('admin_notices', [$this, 'display_errors' ], 9999);
        // Hook used to modify gateway title on order details page
        add_filter('woocommerce_gateway_title', [$this, 'modify_gateway_method_title'], 10, 2);
        // Hook used to modify payment method title on invoice
        add_action('woocommerce_checkout_create_order', [$this, 'modify_order_before_save'], 10, 2);
    }

    public function modify_gateway_method_title($title, $id)
    {
        return is_admin() && $id === $this->id ? $this->method_title : $title;
    }

    public function modify_order_before_save($order, $data)
    {
        if (isset($data['payment_method']) && $data['payment_method'] === $this->id) {
            $order->set_payment_method_title($this->method_title);
        }
    }

    public function generate_screen_button_html()
    {
        ?>
            <div>
                <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=checkout&section=paygreen_payment&screen=settings'); ?>" class="button"><?php echo __('Settings', 'paygreen-payment-gateway'); ?></a>
                <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=checkout&section=paygreen_payment&screen=eligible_categories'); ?>" class="button"><?php echo __('Eligible categories', 'paygreen-payment-gateway'); ?></a>
            </div>
        <?php
    }

    /**
     * @return void
     */
    public function init_form_fields() {
        // If logged we need to determine which screen of fields to display
        if (isset($_GET['screen']) && $_GET['screen'] === 'eligible_categories') {
            $this->init_eligible_categories_fields();
        } else {
            $this->init_settings_fields();
        }
    }

    public function init_settings_fields(){
        $environments = [
            Environment::ENVIRONMENT_SANDBOX => 'Sandbox',
            Environment::ENVIRONMENT_PRODUCTION => 'Production',
        ];

        if ($this->isDevMode()) {
            $environments[Environment::ENVIRONMENT_RECETTE] = 'Recette';
        }

        $this->form_fields = array_merge($this->form_fields, [
            'enabled' => [
                'title'       => __('Enable/Disable', 'paygreen-payment-gateway'),
                'label'       => __('Enable Paygreen Gateway', 'paygreen-payment-gateway'),
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no'
            ],
            'environment' => [
                'title'       => __('Environment', 'paygreen-payment-gateway'),
                'label'       => __('Enable Test Mode', 'paygreen-payment-gateway'),
                'type'        => 'select',
                'description' => __('Place the payment gateway in test mode using test API keys.', 'paygreen-payment-gateway'),
                'default'     => Environment::ENVIRONMENT_SANDBOX,
                'desc_tip'    => true,
                'options' => $environments,
            ],
            'shop_id' => [
                'title'       => __('Shop id', 'paygreen-payment-gateway'),
                'type'        => 'text',
            ],
            'public_key' => [
                'title'       => __('Public Key', 'paygreen-payment-gateway'),
                'type'        => 'text',
            ],
            'secret_key' => [
                'title'       => __('Secret Key', 'paygreen-payment-gateway'),
                'type'        => 'text',
            ],
            'sub_shop_id' => [
                'title'       => __('Sub-Shop identifier', 'paygreen-payment-gateway'),
                'type'        => 'text',
                'description' => __('If this store is a sub-shop and you want to create payments in its name, add its id.', 'paygreen-payment-gateway')
            ],
            'title'       => [
                'title'       => __('Title', 'paygreen-payment-gateway'),
                'type'        => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'paygreen-payment-gateway'),
                'default'     => __('Pay with PayGreen', 'paygreen-payment-gateway'),
                'desc_tip'    => true,
            ],
            'description' => [
                'title'       => __('Payment secured', 'paygreen-payment-gateway'),
                'type'        => 'text',
                'description' => __('You can set a message such as "Payment secured with PayGreen." above your hosted fields.', 'paygreen-payment-gateway'),
                'default'     => '',
                'desc_tip'    => true,
            ],
            'detailed_logs' => [
                'title'       => __('Enable/Disable', 'paygreen-payment-gateway'),
                'label'       => __('Enable detailed logs', 'paygreen-payment-gateway'),
                'description'       => __('Please note that activating detailed logs will significantly increase the weight of logs saved. This option should only be activated for bug resolution.', 'paygreen-payment-gateway'),
                'type'        => 'checkbox',
                'default'     => $this->isDevMode() ? 'yes' : 'no',
            ],
        ]);
    }

    public function init_eligible_categories_fields()
    {
        $categories = $this->get_products_categories();

        $this->form_fields = array_merge($this->form_fields, [
            'title' => [
                'description' => __('PayGreen offers payment by meal ticket or holiday voucher.<br/> On this page you can define which category is compatible with these payment methods.', 'paygreen-payment-gateway'),
                'type' => 'title',
            ],
            'available_for_food_allow_all' => [
                'title'       => __('Make all categories eligible for food payment methods', 'paygreen-payment-gateway'),
                'label'       => __('By checking this box, all product categories will be eligible. Regardless of the categories selected below.', 'paygreen-payment-gateway'),
                'type'        => 'checkbox',
                'default'     => 'no',
            ],
            'available_for_food' => [
                'title' => __('Available for food payment methods', 'paygreen-payment-gateway'),
                'description' => __('Selected categories will be eligible for : Swile, Connecs, Wedoofood, Restoflash.', 'paygreen-payment-gateway'),
                'type' => 'multiselect',
                'options' => $categories,
            ],
            'available_for_travel_allow_all' => [
                'title'       => __('Make all categories eligible for food travel methods', 'paygreen-payment-gateway'),
                'label'       => __('By checking this box, all product categories will be eligible. Regardless of the categories selected below.', 'paygreen-payment-gateway'),
                'type'        => 'checkbox',
                'default'     => 'no',
            ],
            'available_for_travel' => [
                'title' => __('Available for travel payment methods', 'paygreen-payment-gateway'),
                'description' => __('Selected categories will be eligible for : ANCV.', 'paygreen-payment-gateway'),
                'type' => 'multiselect',
                'options' => $categories,
            ],
            'shipping_cost_exclusion' => [
                'title' => __('Exclusion of shipping costs', 'paygreen-payment-gateway'),
                'label' => __('By checking this box, shipping costs will be excluded from the calculation of eligible amounts.', 'paygreen-payment-gateway'),
                'type' => 'checkbox',
                'default' => 'yes',
            ],
        ]);
    }

    /**
     * Processes and saves admin options.
     * If there is an error thrown, will continue to save and validate fields, but will leave the errored field out.
     *
     * @return void
     * @throws WC_Paygreen_Payment_Exception
     */
    public function process_admin_options()
    {
        $post_data = $this->get_post_data();

        if (isset($post_data['woocommerce_paygreen_payment_environment'])
            && $post_data['woocommerce_paygreen_payment_environment'] !== $this->settings['environment']
        ) {
            unset($this->settings['token']);
            unset($this->settings['token_expire_at']);
            update_option('woocommerce_paygreen_payment_settings', $this->settings);
        }

        if (isset($this->settings['token'])
            && !empty($post_data)
            && isset($post_data['woocommerce_paygreen_payment_environment'])
            && isset($post_data['woocommerce_paygreen_payment_shop_id'])
            && isset($post_data['woocommerce_paygreen_payment_public_key'])
            && isset($post_data['woocommerce_paygreen_payment_secret_key'])
            && array(
                $post_data['woocommerce_paygreen_payment_environment'],
                $post_data['woocommerce_paygreen_payment_shop_id'],
                $post_data['woocommerce_paygreen_payment_public_key'],
                $post_data['woocommerce_paygreen_payment_secret_key']
            ) !== array(
                $this->settings['environment'],
                $this->settings['shop_id'],
                $this->settings['public_key'],
                $this->settings['secret_key']
            ))
        {
            $gateways = WC()->payment_gateways->payment_gateways();

            foreach ($gateways as $gateway) {
                if (preg_match('/^paygreen_payment/', $gateway->id) && $gateway->enabled === 'yes') {
                    $gateway->update_option('enabled', 'no');
                }
            }

            $this->add_error(__('The PayGreen payment methods have been deactivated following the modification of your identifiers.', 'paygreen-payment-gateway'));
        }

        parent::process_admin_options();

        if (isset($post_data['woocommerce_paygreen_payment_detailed_logs'])
            && $post_data['woocommerce_paygreen_payment_detailed_logs'] == '1'
            && !$this->isLogDirectoryWritable()
        ) {
            if (defined('WC_LOG_DIR')) {
                $this->add_error(
                    sprintf(
                        /* translators: $1%s path to logs directory */
                        __('The activation of the detailed logs has failed. The log directory %1$s is not writable. To allow logging make %1$s writable.', 'paygreen-payment-gateway'),
                        WC_LOG_DIR
                    )
                );
            } else {
                $this->add_error(__('The activation of the detailed logs has failed. The log directory is not writable. To allow logging make the log directory writable.', 'paygreen-payment-gateway'));
            }

            $this->settings['detailed_logs'] = '0';
            update_option('woocommerce_paygreen_payment_settings', $this->settings);
        }

        try {
            $client = WC_Paygreen_Payment_Api::get_paygreen_client([], true);

            $response = $client->getPublicKey($this->settings['public_key']);

            if ($response->getStatusCode() !== 200) {
                $this->add_error(__('Authentication failed. Please check your public key.', 'paygreen-payment-gateway'));
                return;
            }

            $data = json_decode($response->getBody()->getContents())->data;

            if ($data === null || $data->revoked_at !== null) {
                $this->add_error(__('Authentication failed. Your public key has expired.', 'paygreen-payment-gateway'));
            }

            if (!WC_Paygreen_Payment_Api::has_active_payment_methods()) {
                $this->add_error(__('No activated payment methods were found on your account. Please check your payment methods.', 'paygreen-payment-gateway'));
            }

            WC_Paygreen_Payment_Listener_Helper::register_payment_listener();
        } catch (WC_Paygreen_Payment_Forbidden_Access_Exception $exception) {
            $this->add_error($exception->get_localized_message());
        } catch(WC_Paygreen_Payment_Listener_Exception $exception) {
            $this->add_error($exception->getMessage());
        } catch (\Exception $exception) {
            $this->add_error(__('Authentication failed. Please check your credentials or the selected environment.', 'paygreen-payment-gateway'));
        }
    }

    /**
     * Process the payment
     *
     * @param int $order_id
     * @return array
     * @since 0.0.0
     */
    public function process_payment($order_id) {
        try {
            $wc_order = wc_get_order($order_id);

            // Non-strict condition is intentional because woocommerce can return int/string value
            if ($wc_order->get_total() == 0) {
                return $this->complete_free_order($wc_order);
            }

            $payment_order = WC_Paygreen_Payment_Payment_Order_Helper::create_payment_order($this, $wc_order);
            WC_Paygreen_Payment_Payment_Order_Helper::add_payment_order_to_order($payment_order['payment_order_id'], $wc_order);

            return [
                'result' => 'success',
                'redirect' => $payment_order['hosted_payment_url'],
            ];
        } catch (WC_Paygreen_Payment_Exception $exception) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Gateway::process_payment - Exception - ' . preg_replace("/\n/", '<br>', (string) $exception->getMessage() . '<br>' . $exception->getTraceAsString()));
            wc_add_notice($exception->get_localized_message(), 'error');

            return [
                'result' => 'error',
                'redirect' => '',
            ];
        }
    }

    /**
     * Completes an order without a positive value.
     *
     * @since 0.1.0
     * @param WC_Order $wc_order The order to complete.
     * @return array Redirection data for `process_payment`.
     */
    public function complete_free_order($wc_order)
    {
        // Clean cart.
        WC()->cart->empty_cart();

        $wc_order->payment_complete();

        // Redirect to thank you page
        return [
            'result' => 'success',
            'redirect' => $this->get_return_url($wc_order),
        ];
    }

    public function modify_successful_payment_result($result, $order_id)
    {

    }

    /**
     * @return bool
     */
    public function is_available()
    {
        if (WC_Paygreen_Payment_Api::is_authenticated()
            && isset($this->settings['has_active_payment_methods'])
            && !$this->settings['has_active_payment_methods']
        ) {
            return false;
        }

        return parent::is_available() && strtolower(get_woocommerce_currency()) === 'eur';
    }

    /**
     * Get product categories
     * @return array
     */
    private function get_products_categories()
    {
        $categories = [];

        $taxonomy = 'product_cat';
        $order_by = 'name';
        $show_count = 0; // 1 for yes, 0 for no
        $pad_counts = 0; // 1 for yes, 0 for no
        $hierarchical = 1; // 1 for yes, 0 for no
        $title = '';
        $empty = 0;

        $args = [
            'taxonomy' => $taxonomy,
            'orderby' => $order_by,
            'show_count' => $show_count,
            'pad_counts' => $pad_counts,
            'hierarchical' => $hierarchical,
            'title_li' => $title,
            'hide_empty' => $empty
        ];

        foreach (get_categories($args) as $category) {
            if($category->category_parent === 0) {
                $category_id = $category->term_id;
                $categories[$category_id] = $category->name;
            }
        }

        return $categories;
    }

    /**
     * @return bool
     */
    private function isDevMode()
    {
        return getenv('PAYGREEN_DEBUG') == 1;
    }

    /**
     * @return bool
     */
    private function isLogDirectoryWritable()
    {
        $result = false;

        if (defined('WC_LOG_DIR')) {
            $result = (bool) @fopen( WC_LOG_DIR . 'test-log.log', 'a' );
        }

        return $result;
    }
}