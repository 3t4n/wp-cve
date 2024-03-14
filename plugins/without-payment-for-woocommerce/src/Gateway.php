<?php

declare(strict_types=1);

namespace Coderun\WithoutPaymentWoocommerce;

use Coderun\WithoutPaymentWoocommerce\Exceptions\VariablesException;
use Coderun\WithoutPaymentWoocommerce\Utils\Pages as PagesUtils;
use WC_Logger;
use WC_Payment_Gateway;

use function sprintf;

/**
 * Class Gateway
 */
class Gateway extends WC_Payment_Gateway
{
    public $id = 'coderun_without_payment_woocommerce';
    /**
     * @inheritdoc
     *
     * @var string
     */
    public $method_title = 'Gateway without payment';
    /**
     * @inheritdoc
     *
     * @var string
     */
    public $method_description = 'The gateway is a plug to inform the customer at the payment stage that he will be contacted to confirm the order.';
    /** @var WC_Logger */
    protected WC_Logger $logger;

    /**
     * @param string $pluginUrlDir
     */
    public function __construct(string $pluginUrlDir)
    {
        /** @phpstan-ignore-next-line */
        $this->icon = apply_filters('woocommerce_without_icon', $pluginUrlDir . '/public/without.png');
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();
        $this->title = $this->settings['title'];
        $this->description = $this->settings['description'];
        $this->logger = wc_get_logger();

        add_action(sprintf('woocommerce_receipt_%s', $this->id), [$this, 'receiptPage']);
        add_action(sprintf('woocommerce_update_options_payment_gateways_%s', $this->id), [$this, 'process_admin_options']);
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function admin_options(): void
    {
        ?>
        <h3><?php _e('Gateway without payment', 'coderun-without-payment-woocommerce'); ?></h3>
        <p><?php _e('Setting up the Payment Gateway', 'coderun-without-payment-woocommerce'); ?></p>
        <table class="form-table">
            <?php
            $this->generate_settings_html();
            ?>
        </table>
        
        <?php
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function init_form_fields(): void
    {
        $this->form_fields = [
            'enabled'         => [
                'title'   => __('Turn on/Switch off', 'coderun-without-payment-woocommerce'),
                'type'    => 'checkbox',
                'label'   => __('If the checkbox is set, the gateway is displayed on the order confirmation page', 'coderun-without-payment-woocommerce'),
                'default' => 'no',
            ],
            'title'           => [
                'title'       => __('Title', 'coderun-without-payment-woocommerce'),
                'type'        => 'text',
                'description' => __('This is the name that the user sees during the check.', 'coderun-without-payment-woocommerce'),
                'default'     => __('Without', 'woocommerce'),
            ],
            'without_success' => [
                'title'       => __('Redirection Page', 'coderun-without-payment-woocommerce'),
                'type'        => 'select',
                'options'     => PagesUtils::listOfSitePages('Выберите страницу...'),
                'description' => __('The buyer will get to this page after confirming the order, your page can be designed according to your taste', 'coderun-without-payment-woocommerce'),
            ],
            'without_confirm' => [
                'title'   => __('Disable confirmation', 'coderun-without-payment-woocommerce'),
                'type'    => 'checkbox',
                'label'   => __('Disable the order confirmation page. If the check mark is set - after choosing payment by this method, the buyer will immediately get to the page you selected in the "Redirect Page" option', 'coderun-without-payment-woocommerce'),
                'default' => 'no',
            ],
            'order_status'    => [
                'title'       => __('The status to which the order should go', 'coderun-without-payment-woocommerce'),
                'type'        => 'select',
                'options'     => PagesUtils::listOfAvailableOrderStatuses('Выберите статус...'),
                'description' => __('This status will be assigned to the order upon completion of the buyer\'s actions', 'coderun-without-payment-woocommerce'),
                'default'     => '',
            ],
            'debug'           => [
                'title'   => __('Logging mode', 'coderun-without-payment-woocommerce'),
                'type'    => 'checkbox',
                'label'   => __('Enable logging', 'coderun-without-payment-woocommerce'),
                'default' => 'no',
            ],
            'description'     => [
                'title'       => __('Description', 'coderun-without-payment-woocommerce'),
                'type'        => 'textarea',
                'description' => __('Description of the payment method that the client will see on your website.', 'coderun-without-payment-woocommerce'),
                'default'     => 'Without payment.',
            ],
        ];
    }


    /**
     * Генерация кнопок на странице подтверждения заказа
     *
     * @param int $orderId
     *
     * @return string
     */
    protected function generateForm(int $orderId): string
    {
        $order = wc_get_order($orderId);

        $actionUrl = esc_url($_SERVER['REQUEST_URI']);
        /** @phpstan-ignore-next-line  $url */
        $url = $order->get_cancel_order_url();
        if (!is_string($url)) {
            $url = '';
        }
        return sprintf(
            '<form action="%s" method="POST">
        <input type="submit" class="button alt" name="coderun_without_payment_woocommerce_without_pay" value="%s" />
        <a class="button cancel" href="%s">%s</a>
        </form>',
            $actionUrl,
            __('Confirm', 'woocommerce'),
            $url,
            __('Refuse & Return to Cart', 'woocommerce')
        );
    }

    /**
     * @inheritDoc
     *
     * @param int $orderId
     *
     * @return array<string, string>
     */
    public function process_payment($order_id): array
    {
        $order = wc_get_order($order_id);

        return [
            'result'   => 'success',
            'redirect' => add_query_arg(
                'order',
                $order->get_id(),
                add_query_arg('key', $order->get_order_key(), get_permalink(wc_get_page_id('pay')))
            ),
        ];
    }

    /**
     * Страница подтверждения заказ
     *
     * @param int $orderId
     *
     * @return void
     */
    public function receiptPage(int $orderId): void
    {
        $order = wc_get_order($orderId);
        try {
            if ($this->settings['without_confirm'] == 'yes') {
                WC()->cart->empty_cart();
                $action_adr = get_permalink($this->settings['without_success']);
                $this->updateStatus($order->get_id());
                wp_redirect($action_adr);
            } else {
                if (isset($_POST['coderun_without_payment_woocommerce_without_pay'])) {
                    WC()->cart->empty_cart();
                    $this->updateStatus($orderId);
                    $url = get_permalink($this->settings['without_success']);
                    wp_redirect($url);
                } else {
                    echo '<p>' . __('Thank you for the order, to confirm the order - click on the button below!', 'coderun-without-payment-woocommerce') . '</p>';
                    echo $this->generateForm($order->get_id()); //Кнопки и прочее
                }
            }
        } catch (VariablesException $exception) {
            $this->logger->critical($exception->getMessage(), $exception->getPrevious());
        }
    }

    /**
     * Обновление статуса заказа
     *
     * @param int|null $orderId
     *
     * @return void
     */
    protected function updateStatus(?int $orderId = null): void
    {
        if ($orderId == null && !empty($_GET['order'])) {
            $orderId = intval($_GET['order']);
        }
        if ($orderId == null) {
            throw VariablesException::valueIsNotDefined('$orderId');
        }
        $order = wc_get_order($orderId);
        $orderStatus = 'processing';
        if (!empty($this->get_option('order_status'))) {
            $orderStatus = $this->get_option('order_status');
        }
        $order->update_status($orderStatus);
    }
}