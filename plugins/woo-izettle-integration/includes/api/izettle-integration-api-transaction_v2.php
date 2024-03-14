<?php
/**
 * IZ_Integration_API_Transaction_V2
 *
 * @class           IZ_Integration_API_Transaction_V2
 * @since           1.0.0
 * @package         WC_iZettle_Integration
 * @category        Class
 * @author          bjorntech
 */

defined('ABSPATH') || exit;

if (!class_exists('IZ_Integration_API_Transaction_V2', false)) {

    class IZ_Integration_API_Transaction_V2 extends IZ_Integration_API_Transaction
    {
        public function start_tracking_inventory($product_uuid)
        {
            if (wc_string_to_bool(get_option('izettle_use_old_inventory_api'))) {
                $this->connect_to_service();
                $response = $this->post(
                    'organizations/' . $this->get_organization_uuid() . '/inventory',
                    array(
                        'productUuid' => $product_uuid,
                    ),
                    true,
                    true,
                    'inventory'
                );
                return $response;
            } else {
                $this->connect_to_service();
                $response = $this->post(
                    'v3/products',
                    array(
                        array(
                            'productUuid' => $product_uuid,
                            'tracking' => 'enable'
                        )
                    ),
                    true,
                    true,
                    'inventory'
                );
                return $response;
            }
        }

        public function custom_low_stock($product_uuid, $variant_uuid, $inventory_uuid ,$low_stock_alert, $low_stock)
        {
            $this->connect_to_service();
            $response = $this->post(
                'v3/custom-low-stock',
                array(
                    array(
                        'productUuid' => $product_uuid,
                        'variantUuid' => $variant_uuid,
                        'lowStockAlert' => $low_stock_alert,
                        'lowStockLevel' => $low_stock,
                        'inventoryUuid' => $inventory_uuid)
                ),
                true,
                true,
                'inventory'
            );
            return $response;
        }
        
        public function get_locations()
        {

            if (wc_string_to_bool(get_option('izettle_use_old_inventory_api'))) {
                $this->connect_to_service();
                $response = $this->get(
                    'organizations/' . $this->get_organization_uuid() . '/locations',
                    true,
                    'inventory'
                );
                return $response;
            } else {
                $this->connect_to_service();
                $response = $this->get(
                    'v3/inventories',
                    true,
                    'inventory'
                );

                $modified_response = array_map(function ($inventory) {
                    $mod_inventory = (object) [
                        'uuid' => $inventory->inventoryUuid,
                        'name' => $inventory->name
                    ];
                    
                    return $mod_inventory;
                }, $response);

                return $modified_response;
            }
        }

        public function set_inventory($change){

            if (wc_string_to_bool(get_option('izettle_use_old_inventory_api'))) {

                $this->connect_to_service();
                $response = $this->put(
                    'organizations/self/inventory',
                    $change,
                    true,
                    true,
                    'inventory'
                );
                return $response;

            } else {

                $this->connect_to_service();

                $new_request_body = [
                    'identifier' => $change['externalUuid'],
                    'movements' => array_map(function ($changes){
                        $new_changes = (object) [
                            'productUuid' => $changes['productUuid'],
                            'variantUuid' => $changes['variantUuid'],
                            'change' => $changes['change'],
                            'from' => $changes['fromLocationUuid'],
                            'to' => $changes['toLocationUuid'],
                        ];

                        return $new_changes;
                    },$change['changes'])
                ];

                $response = $this->post(
                    'v3/movements',
                    $new_request_body,
                    true,
                    true,
                    'inventory'
                );
                return $response;
            }
        }

        public function get_location_content($location, $product_uuid = '')
        {

            if (wc_string_to_bool(get_option('izettle_use_old_inventory_api'))) {

                $this->connect_to_service();
                $response = $this->get(
                    'organizations/' . $this->get_organization_uuid() . '/inventory/locations',
                    true,
                    'inventory',
                    array(
                        'type' => $location,
                    )
                );

                return $response;

            } else {

                $this->connect_to_service();

                $locations = apply_filters('izettle_stock_locations', null);

                $potentially_tracked_products = $this->post(
                    'v3/products/status',
                    array(
                        $product_uuid
                    ),
                    true,
                    true,
                    'inventory'
                );

                $tracked_products = [];

                foreach($potentially_tracked_products as $potentially_tracked_product) {
                    if(rest_sanitize_boolean($potentially_tracked_product->enabled)){
                        array_push($tracked_products, $potentially_tracked_product->productUuid);
                    }
                }

                $response = $this->get(
                    'v3/stock/' . $locations[$location] . '/products/' . $product_uuid,
                    true,
                    'inventory'
                );

                $modified_response = (object) [
                    'locationUuid' => $locations[$location],
                    'trackedProducts' => $tracked_products,
                    'variants' => array_map(function ($variant){
                        $locations = apply_filters('izettle_stock_locations', null);
                        $new_variant = (object) [
                            'locationUuid' => $variant->inventoryUuid,
                            'locationType' => array_search($variant->inventoryUuid, $locations),
                            'productUuid' => $variant->productUuid,
                            'variantUuid' => $variant->variantUuid,
                            'balance' => $variant->balance,
                            'lowStockLevel' => $variant->lowStockLevel,
                            'lowStockAlert' => $variant->lowStockAlert
                        ];

                        return $new_variant;
                    }, $response),
                    'latest' => ((count($response) > 0) ? reset($response)->updated : '')
                ];

                return $modified_response;
                
            }
        }
    }
}
