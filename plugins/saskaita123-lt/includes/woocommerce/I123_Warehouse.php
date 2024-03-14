<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Woocommerce;

use S123\Includes\Requests\S123_ApiRequest;

if (!defined('ABSPATH')) exit;

class I123_Warehouse
{
    /**
     * API request object
     *
     */
    private $apiRequest;

    public function __construct(S123_ApiRequest $api = null)
    {
        $this->apiRequest = $api ?: new S123_ApiRequest();
    }

    public function s123_register()
    {
        add_filter('cron_schedules', array($this, 'i123_custom_cron_schedule'));
        $this->i123_warehouse_cron_job();
        add_action('i123_sync_warehouse_cron_hook', array($this, 'i123_sync_warehouse_products'));
    }

    public function i123_sync_warehouse_products()
    {
        $response = $this->apiRequest->s123_makeGetRequest($this->apiRequest->getApiUrl('warehouse_sync'));

        if ($response['code'] !== 200) {
            return null;
        }

        if (empty($response['body']['data'])) {
            return null;
        }

        foreach ($response['body']['data'] as $product) {
            $productId = wc_get_product_id_by_sku($product['sku']);

            if (empty($productId)) {
                continue;
            }

            update_post_meta($productId, '_stock', $product['quantity']);

            if ($product['quantity'] > 0) {
                update_post_meta($productId, '_stock_status', 'instock');
            } else {
                update_post_meta($productId, '_stock_status', 'outofstock');
            }
        }

    }

    public function i123_custom_cron_schedule($schedules)
    {
        $schedules['every_six_hours'] = array(
            'interval' => 21600,
            'display' => __('Every 6 hours'),
        );

        return $schedules;
    }

    public static function i123_warehouse_cron_job()
    {
        if (!wp_next_scheduled('i123_sync_warehouse_cron_hook')) {
            wp_schedule_event(time(), 'every_six_hours', 'i123_sync_warehouse_cron_hook');
        }
    }
}