<?php

namespace PlatiOnlinePO6\Inc\Admin;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use PlatiOnlinePO6\Inc\Core\WC_Plationline;
use PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Additional;
use PlatiOnlinePO6\Inc\Libraries\PO5;
use WC_Order;
use WP_Post;

/**
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 */
class Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * The text domain of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_text_domain The text domain of this plugin.
     */
    private $plugin_text_domain;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param string $plugin_text_domain The text domain of this plugin.
     *
     * @since       1.0.0
     *
     */
    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/plationline-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-jquery-confirm', plugin_dir_url(__FILE__) . 'css/jquery-confirm.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script($this->plugin_name . '-jquery-confirm', plugin_dir_url(__FILE__) . 'js/jquery-confirm.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-jquery-confirm');

        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/plationline-admin.js', array('jquery', 'wp-i18n'), $this->version, true);
        wp_set_script_translations($this->plugin_name, 'plationline');
        wp_localize_script($this->plugin_name, 'po6', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_enqueue_script($this->plugin_name);
    }

    public function add_meta_box_po()
    {
        global $theorder;
        global $post;

        $order = ($post instanceof WP_Post) ? wc_get_order($post->ID) : null;

        if ($order instanceof WC_Order === false) {
            $order = $theorder;
        }

        if ($order instanceof WC_Order) {
            if (stripos($order->get_payment_method(), 'plationline') !== false && !empty($order->get_transaction_id())) {
                if (class_exists(\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class)) {
                    $screen = wc_get_container()->get(\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled()
                        ? wc_get_page_screen_id('shop-order')
                        : 'shop_order';
                } else {
                    $screen = 'shop_order';
                }

                add_meta_box(
                    'form_remote_actions_po',
                    \sprintf(__('PlatiOnline Remote Actions - Transaction ID #%s', 'plationline'), $order->get_transaction_id()),
                    array($this, 'form_remote_actions_po'),
                    $screen
                );
            }
        }
    }

    public function form_remote_actions_po()
    {
        load_template(plugin_dir_path(__FILE__) . 'views/html-plationline-admin-display.php');
    }

    public function query()
    {
        if (empty($_POST['order'])) {
            wp_die('No post sent');
        }
        $order_id = (int)$_POST['order'];
        $order = new \WC_Order($order_id);
        $transid = $order->get_transaction_id();

        if (!empty($transid)) {
            if ($order->get_payment_method() === 'plationline_additional') {
                $po5 = new WC_PlatiOnline_Additional;
            } else {
                $po5 = new WC_Plationline;
            }
            $po5_settings = $po5->settings;
            $po = new PO5();

            $po->f_login = $po5_settings['merchant_id_' . \strtolower($order->get_currency())];
            if (empty($po->f_login)) {
                $this->response('error', 'F_LOGIN is empty for currency: ' . $order->get_currency());
                wp_die();
            }
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);

            $urlparts = \parse_url(\home_url());
            $domain = \preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $f_request['f_order_number'] = $order_id;
            $f_request['x_trans_id'] = $transid; // transaction ID

            $response_query = $po->query($f_request, 0);

            if ($po->get_xml_tag_content($response_query, 'PO_ERROR_CODE') == 1) {
                $this->response('error', $po->get_xml_tag_content($response_query, 'PO_ERROR_REASON'));
            } else {
                $o = $po->get_xml_tag($response_query, 'ORDER');
                $tranzaction = $po->get_xml_tag($o, 'TRANZACTION');
                $F_ORDER_NUMBER = $po->get_xml_tag_content($o, 'F_ORDER_NUMBER');
                $starefin1 = $po->get_xml_tag_content($po->get_xml_tag($tranzaction, 'STATUS_FIN1'), 'CODE');
                $starefin2 = $po->get_xml_tag_content($po->get_xml_tag($tranzaction, 'STATUS_FIN2'), 'CODE');
                $status = (new WC_Plationline)->get_woo_status_by_po_status($starefin1, $starefin2) ?: (new WC_Plationline)->get_woo_status_by_po_status($starefin1);
                $this->response('success', 'Current transaction status is: <b>' . WC_Plationline::$po_order_statuses[$status] . '</b>');
            }
        } else {
            $this->response('error', 'No transaction ID is defined on this order, cannot query');
        }
        wp_die();
    }

    public function void()
    {
        if (empty($_POST['order'])) {
            wp_die('No post sent');
        }
        $order_id = (int)$_POST['order'];
        $order = new \WC_Order($order_id);
        $transid = $order->get_transaction_id();

        if (!empty($transid)) {
            if ($order->get_payment_method() === 'plationline_additional') {
                $po5 = new WC_PlatiOnline_Additional;
            } else {
                $po5 = new WC_Plationline;
            }
            $po5_settings = $po5->settings;
            $po = new PO5();
            $po->f_login = $po5_settings['merchant_id_' . \strtolower($order->get_currency())];
            if (empty($po->f_login)) {
                $this->response('error', 'F_LOGIN is empty for currency: ' . $order->get_currency());
                wp_die();
            }
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);

            $urlparts = \parse_url(\home_url());
            $domain = \preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $f_request['f_order_number'] = $order_id;
            $f_request['x_trans_id'] = $transid; // transaction ID

            $response_void = $po->void($f_request, 7);

            if ($po->get_xml_tag_content($response_void, 'PO_ERROR_CODE') == 1) {
                $order->add_order_note('Requested <b>VOID</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>' . $po->get_xml_tag_content($response_void, 'PO_ERROR_REASON') . '</b>');
                $this->response('error', $po->get_xml_tag_content($response_void, 'PO_ERROR_REASON'));
            } else {
                switch ($po->get_xml_tag_content($response_void, 'X_RESPONSE_CODE')) {
                    case '7':
                        $order->add_order_note('Successfully requested <b>VOID</b> for Plationline Transaction ID: <b>' . $transid . '</b>');
                        $this->response('success', 'Transaction <b>' . $transid . '</b> successfully voided');
                        break;
                    case '10':
                        $order->add_order_note('Requested <b>VOID</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>10</b>');
                        $this->response('error', 'Errors occured, transaction NOT VOIDED');
                        break;
                }
            }
        } else {
            $this->response('error', 'No transaction ID is defined on this order, cannot void');
        }
        wp_die();
    }

    public function cancel_recurrence()
    {
        if (empty($_POST['order'])) {
            wp_die('No post sent');
        }
        $order_id = (int)$_POST['order'];
        $order = new \WC_Order($order_id);
        $transid = $order->get_transaction_id();

        if (!empty($transid)) {
            $po5 = new WC_Plationline;
            $po5_settings = $po5->settings;
            $po = new PO5();
            $po->f_login = $po5_settings['merchant_id_' . \strtolower($order->get_currency())];
            if (empty($po->f_login)) {
                $this->response('error', 'F_LOGIN is empty for currency: ' . $order->get_currency());
                wp_die();
            }
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);

            $urlparts = \parse_url(\home_url());
            $domain = \preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $f_request['f_order_number'] = $order_id;
            $f_request['x_trans_id'] = $transid; // transaction ID

            $response_cancel_recurrence = $po->cancel_recurrence($f_request, 26);

            if ($po->get_xml_tag_content($response_cancel_recurrence, 'PO_ERROR_CODE') == 1) {
                $order->add_order_note('Requested <b>CANCEL RECURRENCE</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>' . $po->get_xml_tag_content($response_cancel_recurrence, 'PO_ERROR_REASON') . '</b>');
                $this->response('error', $po->get_xml_tag_content($response_cancel_recurrence, 'PO_ERROR_REASON'));
            } else {
                switch ($po->get_xml_tag_content($response_cancel_recurrence, 'X_RESPONSE_CODE')) {
                    case '26':
                        $order->add_order_note('Successfully requested <b>CANCEL RECURRENCE</b> for Plationline Transaction ID: <b>' . $transid . '</b>');
                        $this->response('success', 'Transaction <b>' . $transid . '</b> successfully cancelled recurrence');
                        break;
                    case '10':
                        $order->add_order_note('Requested <b>CANCEL RECURRENCE</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>10</b>');
                        $this->response('error', 'Errors occured, recurrence NOT CANCELLED');
                        break;
                }
            }
        } else {
            $this->response('error', 'No transaction ID is defined on this order, cannot cancel recurrence');
        }
        wp_die();
    }

    public function settle()
    {
        if (empty($_POST['order'])) {
            wp_die('No post sent');
        }
        $order_id = (int)$_POST['order'];
        $order = new \WC_Order($order_id);
        $transid = $order->get_transaction_id();

        if (!empty($transid)) {
            if ($order->get_payment_method() === 'plationline_additional') {
                $po5 = new WC_PlatiOnline_Additional;
            } else {
                $po5 = new WC_Plationline;
            }
            $po5_settings = $po5->settings;
            $po = new PO5();
            $po->f_login = $po5_settings['merchant_id_' . \strtolower($order->get_currency())];
            if (empty($po->f_login)) {
                $this->response('error', 'F_LOGIN is empty for currency: ' . $order->get_currency());
                wp_die();
            }
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);

            $urlparts = \parse_url(\home_url());
            $domain = \preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $f_request['f_order_number'] = $order_id;
            $f_request['x_trans_id'] = $transid; // transaction ID
            $f_request['f_shipping_company'] = \substr($order->get_shipping_method(), 0, 250); // shipping company
            $f_request['f_awb'] = ''; // awb

            $response_settle = $po->settle($f_request, 3);

            if ($po->get_xml_tag_content($response_settle, 'PO_ERROR_CODE') == 1) {
                $order->add_order_note('Requested <b>SETTLE</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>' . $po->get_xml_tag_content($response_settle, 'PO_ERROR_REASON') . '</b>');
                $this->response('error', $po->get_xml_tag_content($response_settle, 'PO_ERROR_REASON'));
            } else {
                switch ($po->get_xml_tag_content($response_settle, 'X_RESPONSE_CODE')) {
                    case '3':
                        $order->add_order_note('Successfully requested <b>SETTLE</b> for Plationline Transaction ID: <b>' . $transid . '</b>');
                        $this->response('success', 'Transaction <b>' . $transid . '</b> successfully settled');
                        break;
                    case '10':
                        $order->add_order_note('Requested <b>SETTLE</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>10</b>');
                        $this->response('error', 'Errors occured, transaction NOT VOIDED');
                        break;
                }
            }
        } else {
            $this->response('error', 'No transaction ID is defined on this order, cannot settle');
        }
        wp_die();
    }

    public function refund()
    {
        if (empty($_POST['order'])) {
            wp_die('No post sent');
        }
        $order_id = (int)$_POST['order'];
        $order = new \WC_Order($order_id);
        $transid = $order->get_transaction_id();

        if (!empty($transid)) {
            if ($order->get_payment_method() === 'plationline_additional') {
                $po5 = new WC_PlatiOnline_Additional;
            } else {
                $po5 = new WC_Plationline;
            }
            $po5_settings = $po5->settings;
            $po = new PO5();
            $po->f_login = $po5_settings['merchant_id_' . \strtolower($order->get_currency())];
            if (empty($po->f_login)) {
                $this->response('error', 'F_LOGIN is empty for currency: ' . $order->get_currency());
                wp_die();
            }
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);

            $urlparts = \parse_url(\home_url());
            $domain = \preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $f_request['f_order_number'] = $order_id;
            $f_request['x_trans_id'] = $transid; // transaction ID
            $f_request['f_amount'] = (float)$_POST['amount']; // needed amount

            $response_refund = $po->refund($f_request, 1);
            if ($po->get_xml_tag_content($response_refund, 'PO_ERROR_CODE') == 1) {
                $order->add_order_note('Requested <b>REFUND</b> for <b>' . $f_request['f_amount'] . ' ' . $order->get_currency() . '</b>, Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>' . $po->get_xml_tag_content($response_refund, 'PO_ERROR_REASON') . '</b>');
                $this->response('error', $po->get_xml_tag_content($response_refund, 'PO_ERROR_REASON'));
            } else {
                switch ($po->get_xml_tag_content($response_refund, 'X_RESPONSE_CODE')) {
                    case '1':
                        $order->add_order_note('Successfully requested <b>REFUND</b> for <b>' . $po->get_xml_tag_content($response_refund, 'F_AMOUNT') . ' ' . $order->get_currency() . '</b>, Plationline Transaction ID: <b>' . $transid . '</b>');
                        $this->response('success', 'The amount of <b>' . $po->get_xml_tag_content($response_refund, 'F_AMOUNT') . ' ' . $order->get_currency() . '</b> successfully refunded');
                        break;
                    case '10':
                        $order->add_order_note('Requested <b>REFUND</b> for <b>' . $f_request['f_amount'] . ' ' . $order->get_currency() . '</b>, Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>10</b>');
                        $this->response('error', 'Errors occured, transaction NOT REFUNDED');
                        break;
                }
            }
        } else {
            $this->response('error', 'No transaction ID is defined on this order, cannot refund');
        }
        wp_die();
    }

    public function settle_amount()
    {
        if (empty($_POST['order'])) {
            wp_die('No post sent');
        }
        $order_id = (int)$_POST['order'];
        $order = new \WC_Order($order_id);
        $transid = $order->get_transaction_id();

        if (!empty($transid)) {
            if ($order->get_payment_method() === 'plationline_additional') {
                $po5 = new WC_PlatiOnline_Additional;
            } else {
                $po5 = new WC_Plationline;
            }
            $po5_settings = $po5->settings;
            $po = new PO5();
            $po->f_login = $po5_settings['merchant_id_' . \strtolower($order->get_currency())];
            if (empty($po->f_login)) {
                $this->response('error', 'F_LOGIN is empty for currency: ' . $order->get_currency());
                wp_die();
            }
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);

            $urlparts = \parse_url(\home_url());
            $domain = \preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $f_request['f_order_number'] = $order_id;
            $f_request['x_trans_id'] = $transid; // transaction ID
            $f_request['f_shipping_company'] = \substr($order->get_shipping_method(), 0, 250); // shipping company
            $f_request['f_awb'] = ''; // awb
            $f_request['f_amount'] = (float)$_POST['amount']; // needed amount

            $response_settle = $po->settle_amount($f_request, 31);

            if ($po->get_xml_tag_content($response_settle, 'PO_ERROR_CODE') == 1) {
                $order->add_order_note('Requested <b>Settle</b> for <b>' . $f_request['f_amount'] . ' ' . $order->get_currency() . '</b>, Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>' . $po->get_xml_tag_content($response_settle, 'PO_ERROR_REASON') . '</b>');
                $this->response('error', $po->get_xml_tag_content($response_settle, 'PO_ERROR_REASON'));
            } else {
                switch ($po->get_xml_tag_content($response_settle, 'X_RESPONSE_CODE')) {
                    case '3':
                        $order->add_order_note('Successfully requested <b>Settle</b> for <b>' . $po->get_xml_tag_content($response_settle, 'F_AMOUNT') . ' ' . $order->get_currency() . '</b>, Plationline Transaction ID: <b>' . $transid . '</b>');
                        $this->response('success', 'The amount of <b>' . $po->get_xml_tag_content($response_settle, 'F_AMOUNT') . ' ' . $order->get_currency() . '</b> successfully settled');
                        break;
                    case '10':
                        $order->add_order_note('Requested <b>SETTLE</b> for <b>' . $f_request['f_amount'] . ' ' . $order->get_currency() . '</b>, Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>10</b>');
                        $this->response('error', 'Errors occured, transaction NOT SETTLED');
                        break;
                }
            }
        } else {
            $this->response('error', 'No transaction ID is defined on this order, cannot settle selected amount');
        }
        wp_die();
    }

    public function plationline_auth_to_processing($order_id, $from, $to)
    {
        global $woocommerce;
        $order = new \WC_Order($order_id);
        if ($order->get_payment_method() === 'plationline_additional') {
            $po5 = new WC_PlatiOnline_Additional;
        } else {
            $po5 = new WC_Plationline;
        }
        $po5_settings = $po5->settings;
        $payment_method = $order->get_payment_method();

        if (stripos($payment_method, 'plationline') !== false && $from == $po5_settings['authorized_order_status'] && $to == 'processing') {
            $mailer = $woocommerce->mailer();
            $email = $mailer->emails['WC_Email_Customer_Processing_Order'];
            $email->trigger($order_id);
        }
    }

    private function response($status = 'error', $message = '')
    {
        echo json_encode(array('status' => $status, 'message' => __($message, 'plationline')));
    }
}
