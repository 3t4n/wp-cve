<?php
/**
 * Created by PhpStorm.
 * User: thanhlam
 * Date: 15/01/2021
 * Time: 20:47
 */

namespace NineKolor\TelegramWC\Classes;
use NineKolor\TelegramWC\Classes\WooCommerce as Woo;
class Core
{
    public $telegram;
    protected static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __construct() {
        $this->setTelegram();
        $this->hooks();
        $this->language();
    }
    public function language() {
        load_plugin_textdomain('nktgnfw', false, trailingslashit( dirname( plugin_basename( __DIR__ ) ) ) . 'languages' );
    }
    public function hooks(){
        add_filter( 'woocommerce_get_settings_pages', array($this, 'addWooSettingSection') );
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_script'));

        $order_status_changed_enabled = get_option('nktgnfw_send_after_order_status_changed',false);
        if($order_status_changed_enabled == 'yes'){
            add_action('woocommerce_order_status_changed', array($this, 'woocommerce_order_status_changed'), 20,4);
        }
        else{
            add_action('woocommerce_checkout_order_processed', array($this, 'woocommerce_new_order'));
        }

    }
    public function setTelegram(){
        $this->telegram = new Sender();
        $this->telegram->chatID = get_option('nktgnfw_setting_chatid');
        $this->telegram->token = get_option('nktgnfw_setting_token');
    }
    public function sendNewOrderToTelegram($orderID){
        $wc = new Woo($orderID);
        $template = get_option('nktgnfw_setting_template');
        $message = $wc->getBillingDetails($template);
        $this->telegram->sendMessage($message);
    }
    public function addWooSettingSection($settings){
        $settings[] = new SettingPage();
        return $settings;
    }
    public function woocommerce_new_order($order_id)
    {
        $wasSent = get_post_meta($order_id, 'telegramWasSent', true);
        if (!$wasSent) {
            update_post_meta($order_id, 'telegramWasSent', 1);
            $this->sendNewOrderToTelegram($order_id);
        }

    }
    public function woocommerce_order_status_changed($order_id, $status_transition_from, $status_transition_to, $that)
    {
        $order = wc_get_order($order_id);
        $statuses = get_option('nktgnfw_order_statuses');
        if (in_array('wc-' . $order->get_status(), $statuses)) {
            $this->sendNewOrderToTelegram($order->data['id']);
        }
    }
    public function admin_enqueue_script(){
        wp_enqueue_style('nktgnfw', plugin_dir_url(__FILE__) . '../assets/css/admin.css', '', false, 'all');
        wp_enqueue_script('nktgnfw', plugin_dir_url(__FILE__) . '../assets/js/admin.js', array('jquery'), false, true);
    }
}