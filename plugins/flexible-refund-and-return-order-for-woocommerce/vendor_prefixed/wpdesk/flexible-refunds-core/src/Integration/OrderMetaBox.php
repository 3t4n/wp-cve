<?php

namespace FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Integration;

use FRFreeVendor\WPDesk\Persistence\PersistentContainer;
use FRFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FRFreeVendor\WPDesk\View\Renderer\Renderer;
use WC_Order;
use WP_Post;
class OrderMetaBox implements \FRFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var PersistentContainer
     */
    private $settings;
    public function __construct(\FRFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \FRFreeVendor\WPDesk\Persistence\PersistentContainer $settings)
    {
        $this->renderer = $renderer;
        $this->settings = $settings;
    }
    public function hooks()
    {
        \add_action('add_meta_boxes', [$this, 'add_meta_boxes'], 11, 2);
    }
    public function add_meta_boxes($post_type, $post)
    {
        $post_id = \method_exists($post, 'get_id') ? $post->get_id() : $post->ID;
        if ($post_id) {
            $order = \wc_get_order($post_id);
            if ($order instanceof \WC_Order) {
                $meta = $order->get_meta('fr_refund_request_data');
                if (!empty($meta)) {
                    \add_meta_box('shop_order_fr_meta_box', \__('Refund Request', 'flexible-refund-and-return-order-for-woocommerce'), [$this, 'fr_meta_box_content'], $this->get_meta_box_screens(), 'normal', 'high', ['order' => $order]);
                }
            }
        }
    }
    private function get_meta_box_screens() : array
    {
        $screens = ['shop_subscription', 'woocommerce_page_wc-orders'];
        try {
            $hpos_controller = \wc_get_container()->get(\FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Integration\CustomOrdersTableController::class);
            if ($hpos_controller->custom_orders_table_usage_is_enabled()) {
                return \array_merge([\wc_get_page_screen_id('shop-order')], $screens);
            } else {
                return \array_merge(['shop_order'], $screens);
            }
        } catch (\Exception $e) {
            return \array_merge(['shop_order'], $screens);
        }
    }
    /**
     * @param Order $post
     * @param array   $data
     *
     * @return void
     */
    public function fr_meta_box_content($post_or_order_object, array $data)
    {
        $order = $data['args']['order'];
        $this->renderer->output_render('order/refund-meta-box', ['order' => $order, 'settings' => $this->settings]);
    }
}
