<?php declare(strict_types=1);
/**
 * Get from old code.
 */

namespace Holded\Woocommerce\Views;

use Holded\SDK\Holded as HoldedSDK;
use Holded\Woocommerce\Loggers\WoocommerceLogger;
use Holded\Woocommerce\Services\OrderService;
use Holded\Woocommerce\Services\ProductService;
use Holded\Woocommerce\Services\Settings;
use Holded\Woocommerce\Services\ShopService;

class ConfigPanel extends \WC_Integration
{
    /** @var string */
    private $holded_api_key;

    /** @var string */
    public $settingsURI;

    /** @var ProductService */
    private $productService;

    /** @var OrderService */
    private $orderService;

    /** @var ShopService */
    private $shopService;

    public function __construct()
    {
        global $woocommerce;

        $this->id = 'holdedwc-configpanel';
        $this->method_title = __('Holded', HOLDED_I10N_DOMAIN);
        $this->method_description = __('Holded invoicing integration with WooCommerce. If you do not have a Holded account try it <a href="https://app.holded.com/signup" target="_blank">here</a>.', HOLDED_I10N_DOMAIN);

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables.
        $this->holded_api_key = (Settings::getInstance())->getApiKey();
        $this->settingsURI = 'admin.php?page=wc-settings&tab=integration&section='.$this->id;

        // Actions.
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_notices', [$this, 'configurationNotices']);
        add_action('woocommerce_update_options_integration_'.$this->id, [$this, 'process_admin_options']);

        add_action('wp_ajax_holdedwc_sync_orders', [$this, 'sync_orders_callback']);
        add_action('wp_ajax_holdedwc_syncButtonProduct', [$this, 'syncButtonProduct_callback']);

        $this->buildHoldedServices($this->holded_api_key);
    }

    private function buildHoldedServices(string $apiKey): void
    {
        $holdedSDK = new HoldedSDK($apiKey, new WoocommerceLogger(), Settings::getInstance()->getApiUrl());

        $this->productService = new ProductService($holdedSDK);
        $this->orderService = new OrderService($holdedSDK);
        $this->shopService = new ShopService($holdedSDK);
    }

    public function enqueueScripts(string $hook): void
    {
        if ($hook != 'woocommerce_page_wc-settings') {
            // Only applies to WC Settings panel
            return;
        }

        wp_register_script('holdedWC-WCConfigPanel-ajaxsync', HOLDED_PLUGIN_DIR.'public/js/holdedWC-WCConfigPanel-ajaxsync.js', ['jquery'], HOLDED_VERSION);
        $params = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('holdedWC-ajax-nonce'),
            'synctxt'  => __('Synchronizing', HOLDED_I10N_DOMAIN),
        ];
        wp_localize_script('holdedWC-WCConfigPanel-ajaxsync', 'holdedWC_ajax_object', $params);
        wp_enqueue_script('holdedWC-WCConfigPanel-ajaxsync');

        wp_enqueue_style('holdedWC-WCConfigPanel-css', HOLDED_PLUGIN_DIR.'public/css/holdedWC-WCConfigPanel.css', '', HOLDED_VERSION);
    }

    /**
     * Check if the user has enabled the plugin functionality, but hasn't provided an api key.
     **/
    public function configurationNotices(): void
    {
        if (empty($this->holded_api_key) && empty($_POST[$this->plugin_id.$this->id.'_holded_api_key'])) {
            $this->showNotice(sprintf(__('WooCommerce Holded: Plugin is enabled but no api key or secret provided. Please enter your api key and secret <a href="%s">here</a>.', HOLDED_I10N_DOMAIN), $this->settingsURI));
        }
    }

    public function init_form_fields(): void
    {
        $this->form_fields = [];

        if (getenv('HOLDED_DEBUG') == 1) {
            $apiUrlSection = [
                'holded_api_url' => [
                    'title'       => __('API Url', HOLDED_I10N_DOMAIN),
                    'type'        => 'text',
                    'description' => __('Only for development environment', HOLDED_I10N_DOMAIN),
                    'desc_tip'    => true,
                    'default'     => (Settings::getInstance())->getApiUrl(),
                ],
            ];
            $this->form_fields = array_merge($this->form_fields, $apiUrlSection);
        }

        $apiKeySection = [
            'holded_api_key' => [
                'title'       => __('API Key', HOLDED_I10N_DOMAIN),
                'type'        => 'text',
                'description' => __('Enter your Holded API Key. You can find this in your Holded account in Settings -> API', HOLDED_I10N_DOMAIN),
                'desc_tip'    => true,
                'default'     => (Settings::getInstance())->getApiKey(),
            ],
        ];
        $this->form_fields = array_merge($this->form_fields, $apiKeySection);

        $logs = get_transient('holded_log') ?: [];
        $logsSection = [
            'holded_logs' => [
                'title'       => __('Logs', HOLDED_I10N_DOMAIN),
                'type'        => 'title',
                'description' => '<div>
                                    <div class="holded-logs-description">'.__('Check error logs', HOLDED_I10N_DOMAIN).'</div>
                                    <button type="button" id="see-holded-logs">'.__('See logs', HOLDED_I10N_DOMAIN).'</button>
                                    <div id="holded-logs" class="holded-logs"><textarea>'.implode("\n", $logs).'</textarea></div>
                                </div>',
            ],
        ];
        $this->form_fields = array_merge($this->form_fields, $logsSection);

        $wc_holded_sync = get_option('holdedwc_sync');

        if ($wc_holded_sync == 1) {
            $syncOrdersSection = [
                'sync_title' => [
                    'title'       => __('Synchronize Orders', HOLDED_I10N_DOMAIN),
                    'type'        => 'title',
                    'description' => __('Synchronize previous orders in "Completed" status with your Holded account.', HOLDED_I10N_DOMAIN),
                ],
                'customize_button_2' => [
                    'title'             => __('Sync - '.date('Y', time()), HOLDED_I10N_DOMAIN),
                    'type'              => 'syncbutton',
                    'custom_attributes' => [
                        'data-period' => '0',
                        'data-action' => 'sync_orders',
                    ],
                    'description' => __('Sync orders', HOLDED_I10N_DOMAIN),
                    'desc_tip'    => true,
                ],
                'customize_button_3' => [
                    'title'             => __('Sync 1T - '.date('Y', time()), HOLDED_I10N_DOMAIN),
                    'type'              => 'syncbutton',
                    'custom_attributes' => [
                        'data-period' => '1',
                        'data-action' => 'sync_orders',
                    ],
                    'description' => __('Sync orders', HOLDED_I10N_DOMAIN),
                    'desc_tip'    => true,
                ],
                'customize_button_4' => [
                    'title'             => __('Sync 2T - '.date('Y', time()), HOLDED_I10N_DOMAIN),
                    'type'              => 'syncbutton',
                    'custom_attributes' => [
                        'data-period' => '2',
                        'data-action' => 'sync_orders',
                    ],
                    'description' => __('Sync orders', HOLDED_I10N_DOMAIN),
                    'desc_tip'    => true,
                ],
                'customize_button_5' => [
                    'title'             => __('Sync 3T - '.date('Y', time()), HOLDED_I10N_DOMAIN),
                    'type'              => 'syncbutton',
                    'custom_attributes' => [
                        'data-period' => '3',
                        'data-action' => 'sync_orders',
                    ],
                    'description' => __('Sync orders', HOLDED_I10N_DOMAIN),
                    'desc_tip'    => true,
                ],
                'customize_button_6' => [
                    'title'             => __('Sync 4T - '.date('Y', time()), HOLDED_I10N_DOMAIN),
                    'type'              => 'syncbutton',
                    'custom_attributes' => [
                        'data-period' => '4',
                        'data-action' => 'sync_orders',
                    ],
                    'description' => __('Sync orders', HOLDED_I10N_DOMAIN),
                    'desc_tip'    => true,
                ],
                'customize_button_7' => [
                    'title'             => __('Sync - '.(date('Y', time()) - 1), HOLDED_I10N_DOMAIN),
                    'type'              => 'syncbutton',
                    'custom_attributes' => [
                        'data-period' => '5',
                        'data-action' => 'sync_orders',
                    ],
                    'description' => __('Sync orders', HOLDED_I10N_DOMAIN),
                    'desc_tip'    => true,
                ],
            ];
            $this->form_fields = array_merge($this->form_fields, $syncOrdersSection);

            $syncBackSection = [
                'syncBack_title' => [
                    'title'       => __('Synchronize Stock', HOLDED_I10N_DOMAIN),
                    'type'        => 'title',
                    'description' => '<p>'.__('Synchronize Stock Levels. When you sell an item on Holded, stock levels in WooCommerce are automatically updated preventing stockouts.', HOLDED_I10N_DOMAIN).'</p><p>'.__('Use the following URL to update Stock levels coming from Holded.', HOLDED_I10N_DOMAIN).'</p>',
                ],
                'syncBack_link' => [
                    'title'             => __('Webhook URL', HOLDED_I10N_DOMAIN),
                    'type'              => 'localLink',
                    'custom_attributes' => [
                        'readonly' => 'readonly',
                    ],
                ],
            ];
            $this->form_fields = array_merge($this->form_fields, $syncBackSection);
        }
    }

    /**
     * @param string  $key
     * @param mixed[] $data
     *
     * @return false|string
     */
    public function generate_syncbutton_html($key, $data)
    {
        $field = $this->plugin_id.$this->id.'_'.$key;
        $defaults = [
            'class'             => 'button-secondary holded-sync-button',
            'css'               => '',
            'custom_attributes' => [
                'data-action' => 'sync_orders',
            ],
            'desc_tip'    => false,
            'description' => '',
            'title'       => '',
        ];

        $data = wp_parse_args($data, $defaults);

        ob_start(); ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr($field); ?>"><?php echo wp_kses_post($data['title']); ?></label>
                <?php echo $this->get_tooltip_html($data); ?>
            </th>
            <td class="forminp">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span>
                    </legend>
                    <button class="<?php echo esc_attr($data['class']); ?>" type="button"
                            name="<?php echo esc_attr($field); ?>" id="<?php echo esc_attr($field); ?>"
                            style="<?php echo esc_attr($data['css']); ?>" <?php echo $this->get_custom_attribute_html($data); ?>><?php echo wp_kses_post($data['title']); ?></button>
                    <?php echo $this->get_description_html($data); ?>
                    <div class="holdedwc-ajaxreply hidden"></div>
                </fieldset>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    /**
     * @param string  $key
     * @param mixed[] $data
     *
     * @return false|string
     */
    public function generate_syncButtonProduct_html($key, $data)
    {
        $field = $this->plugin_id.$this->id.'_'.$key;
        $defaults = [
            'class'             => 'button-secondary holded-sync-button',
            'css'               => '',
            'custom_attributes' => [
                'data-action' => 'syncButtonProduct',
            ],
            'desc_tip'    => false,
            'description' => '',
            'title'       => '',
        ];

        $data = wp_parse_args($data, $defaults);

        ob_start(); ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr($field); ?>"><?php echo wp_kses_post($data['title']); ?></label>
                <?php echo $this->get_tooltip_html($data); ?>
            </th>
            <td class="forminp">
                <fieldset>
                    <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span>
                    </legend>
                    <button class="<?php echo esc_attr($data['class']); ?>" type="button"
                            name="<?php echo esc_attr($field); ?>" id="<?php echo esc_attr($field); ?>"
                            style="<?php echo esc_attr($data['css']); ?>" <?php echo $this->get_custom_attribute_html($data); ?>><?php echo wp_kses_post($data['title']); ?></button>
                    <?php echo $this->get_description_html($data); ?>
                    <div class="holdedwc-ajaxreply hidden"></div>
                </fieldset>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    /**
     * @param string  $key
     * @param mixed[] $data
     *
     * @return false|string
     */
    public function generate_localLink_html($key, $data)
    {
        $field = $this->plugin_id.$this->id.'_'.$key;
        $defaults = [
            'class'             => 'holded-localLink-text',
            'css'               => '',
            'custom_attributes' => [],
            'desc_tip'          => false,
            'description'       => '',
            'title'             => '',
        ];

        $data = wp_parse_args($data, $defaults);

        ob_start(); ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr($field); ?>"><?php echo wp_kses_post($data['title']); ?></label>
                <?php echo $this->get_tooltip_html($data); ?>
            </th>
            <td class="forminp">
                <fieldset>
                    <legend class="screen-reader-text">
                        <span><?php echo wp_kses_post($data['title']); ?></span>
                    </legend>
                    <input class="<?php echo esc_attr($data['class']); ?>" type="text"
                           name="<?php echo esc_attr($field); ?>"
                           id="<?php echo esc_attr($field); ?>"
                           style="<?php echo esc_attr($data['css']); ?>" <?php echo $this->get_custom_attribute_html($data); ?>
                           value="<?php esc_html_e(get_home_url()); ?>"/>
                    <?php echo $this->get_description_html($data); ?>
                </fieldset>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    public function validate_holded_api_url_field(string $url): void
    {
        $value = sanitize_text_field($_POST[$this->plugin_id.$this->id.'_'.$url]);
        Settings::getInstance()->setApiUrl($value);
    }

    /**
     * Validate the API key.
     *
     * @see validate_settings_fields()
     *
     * @param string $key
     *
     * @return void
     */
    public function validate_holded_api_key_field($key)
    {
        $value = sanitize_text_field($_POST[$this->plugin_id.$this->id.'_'.$key]);
        if (empty($value)) {
            update_option('holdedwc_sync', 0);
            \WC_Admin_Settings::add_error(__('The API key or secret are empty. Enter API key or secret to use WooCommerce Holded plugin.', HOLDED_I10N_DOMAIN));

            return;
        }

        if (!$this->holded_api_key) {
            $this->buildHoldedServices($value);
        }

        $result = $this->shopService->checkShop();

        if ($result) {
            update_option('holdedwc_sync', 1);
            $this->setLongTermApiKey($value);
        } else {
            update_option('holdedwc_sync', 0);
            $this->setLongTermApiKey('');
            \WC_Admin_Settings::add_error(__('The API key or secret is not recognised by Holded.', HOLDED_I10N_DOMAIN));
        }
    }

    /**
     * Validate the API secret.
     *
     * @see validate_settings_fields()
     *
     * @param string $key
     *
     * @return string
     */
    public function validate_holded_api_secret_field($key)
    {
        $value = sanitize_text_field($_POST[$this->plugin_id.$this->id.'_'.$key]);
        if (empty($value)) {
            \WC_Admin_Settings::add_error(__('The API key or secret are empty. Enter API key or secret to use WooCommerce Holded plugin.', HOLDED_I10N_DOMAIN));
        }

        return $value;
    }

    /**
     * Ajax callback that syncs products.
     *
     * @return void
     */
    public function syncButtonProduct_callback()
    {
        $response = [
            'error'   => 0,
            'message' => '',
        ];

        $args = [
            'posts_per_page' => -1,
            'limit'          => -1,
            'orderby'        => 'name',
        ];
        $products = wc_get_products($args);

        if (!empty($products)) {
            foreach ($products as $product) {
                $result = $this->productService->updateHoldedProduct($product->get_id());
                $translationMessage = ($result) ? 'Succesfully synced product' : 'Unsuccesfully synced product';
                $response['message'] .= __($translationMessage, HOLDED_I10N_DOMAIN).' #'.$product->get_id().' <br/>';
            }

            $response['message'] .= __('All previous products have been synced with your Holded account.', HOLDED_I10N_DOMAIN);
        } else {
            $response['message'] .= __('No products were found.', HOLDED_I10N_DOMAIN);
        }

        wp_send_json($response);
    }

    /**
     * Ajax callback that syncs order within a period.
     *
     * @return void
     */
    public function sync_orders_callback()
    {
        $response = [
            'error'   => 0,
            'message' => '',
        ];

        $nonce = sanitize_key($_POST['nonce']);
        if (!wp_verify_nonce($nonce, 'holdedWC-ajax-nonce')) {
            $response['error'] = 1;
            $response['message'] = __('There was an error processing this request.', HOLDED_I10N_DOMAIN);
            wp_send_json($response);
        }

        if (empty($this->holded_api_key)) {
            $response['error'] = 1;
            $response['message'] = __('Please save your API key before syncing previous orders.', HOLDED_I10N_DOMAIN);
            wp_send_json($response);
        }

        //Get period
        $period = null;
        if (isset($_POST['period'])) {
            $period_post = (int) sanitize_key($_POST['period']);
            if ($period_post) {
                $period = $period_post;
            }
        }

        $year = date('Y', time());
        switch ($period) {
            case 1:
                $perioddatetmpstart = strtotime('01-01-'.$year);
                $perioddatetmpend = strtotime('31-03-'.$year);
                break;
            case 2:
                $perioddatetmpstart = strtotime('01-04-'.$year);
                $perioddatetmpend = strtotime('30-06-'.$year);
                break;
            case 3:
                $perioddatetmpstart = strtotime('01-07-'.$year);
                $perioddatetmpend = strtotime('30-09-'.$year);
                break;
            case 4:
                $perioddatetmpstart = strtotime('01-10-'.$year);
                $perioddatetmpend = strtotime('31-12-'.$year);
                break;
            case 5:
                //Previous Year
                $perioddatetmpstart = strtotime('01-01-'.($year - 1));
                $perioddatetmpend = strtotime('31-12-'.($year - 1));
                break;
            default:
                $perioddatetmpstart = strtotime('01-01-'.($year));
                $perioddatetmpend = strtotime('31-12-'.($year));
                break;
        }

        //Get completed orders in this period of time.
        $args = [
            'status'         => 'completed',
            'type'           => 'shop_order',
            'limit'          => -1,
            'date_completed' => $perioddatetmpstart.'...'.$perioddatetmpend,
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ];

        $orders = wc_get_orders($args);

        if (!empty($orders)) {
            foreach ($orders as $order) {
                $response['message'] .= $this->orderService->updateHoldedInvoice($order->get_id())
                    ? __('Succesfully synced order', HOLDED_I10N_DOMAIN).' #'.$order->get_id().' '.$order->get_formatted_billing_full_name().', '.date_i18n(get_option('date_format'), $order->get_date_completed()).'<br/>'
                    : __('Unsuccesfully synced order', HOLDED_I10N_DOMAIN).' #'.$order->get_id().' '.$order->get_formatted_billing_full_name().', '.date_i18n(get_option('date_format'), $order->get_date_completed()).'<br/>';
            }
            // $response['message'] .= __('All previous orders have been synced with your Holded account.', HOLDED_I10N_DOMAIN);
        } else {
            $response['message'] .= __('No orders were found.', HOLDED_I10N_DOMAIN);
        }
        wp_send_json($response);
    }

    /**
     * Notice that WooCommerce is required.
     *
     * @param string $message
     *
     * @return void
     */
    public function showNotice($message)
    {
        $class = 'notice notice-error';
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    private function setLongTermApiKey($key)
    {
        $legacySettings = Settings::getInstance();
        $legacySettings->setApiKey($key);
    }
}
