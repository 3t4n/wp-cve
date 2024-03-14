<?php

namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Ups;

use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CollectionPoints\CheckoutAddress;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CollectionPoints\CollectionPointFormatter;
class AjaxCollectionPoints implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const AJAX_ACTION = 'ups-collection-points';
    private \UpsFreeVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider $collection_points_provider;
    private bool $limit_to_single_point;
    public function __construct(\UpsFreeVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider $collection_points_provider, bool $limit_to_single_point = \false)
    {
        $this->collection_points_provider = $collection_points_provider;
        $this->limit_to_single_point = $limit_to_single_point;
    }
    public function hooks() : void
    {
        \add_action('wp_ajax_' . self::AJAX_ACTION, [$this, 'get_collection_points']);
        \add_action('wp_ajax_nopriv_' . self::AJAX_ACTION, [$this, 'get_collection_points']);
    }
    public function get_collection_points() : void
    {
        \check_ajax_referer(self::AJAX_ACTION, 'security');
        $points = [];
        $address = new \UpsFreeVendor\WPDesk\WooCommerceShipping\CollectionPoints\CheckoutAddress($_REQUEST);
        $collection_point_formatter = new \UpsFreeVendor\WPDesk\WooCommerceShipping\CollectionPoints\CollectionPointFormatter();
        foreach ($this->collection_points_provider->get_nearest_collection_points($address->prepare_address()) as $collection_point) {
            $points[] = ['id' => $collection_point->collection_point_id, 'text' => $collection_point_formatter->get_collection_point_as_label($collection_point)];
            if ($this->limit_to_single_point) {
                break;
            }
        }
        \wp_send_json(['items' => $points]);
    }
}
