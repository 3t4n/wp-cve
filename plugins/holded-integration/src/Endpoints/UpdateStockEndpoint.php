<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Endpoints;

use Holded\SDK\Loggers\ProductLogger;

class UpdateStockEndpoint extends AbstractEndpoint
{
    public function init(): void
    {
        add_action('rest_api_init', [$this, 'registerEndpoint']);
    }

    public function registerEndpoint(): void
    {
        register_rest_route($this->apiNamespace, '/stockUpdate', [
            'methods'  => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'processStockUpdate'],
            'args'     => [
                'sku' => [
                    'required'          => true,
                    'validate_callback' => function ($param, $request, $key) {
                        return !empty($param);
                    },
                    'sanitize_callback' => function ($param, $request, $key) {
                        return sanitize_text_field($param);
                    },
                ],
                'stock' => [
                    'required'          => true,
                    'validate_callback' => function ($param, $request, $key) {
                        if (is_numeric($param)) {
                            if ($param >= 0) {
                                return true;
                            }
                        }

                        return false;
                    },
                ],
            ],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * @return \WP_Error|\WP_REST_Response
     */
    public function processStockUpdate(\WP_REST_Request $request)
    {
        $authentication = $this->authentication($request);
        if (is_wp_error($authentication) && is_object($authentication)) {
            return $authentication;
        }

        $requestData = $request->get_body_params();
        $sku = $requestData['sku'];
        $stock = $requestData['stock'];

        $product_id = wc_get_product_id_by_sku($sku);
        if (!empty($product_id)) {
            $product = wc_get_product($product_id);
            if ($product->managing_stock()) {
                if ($product->get_stock_quantity() != $stock) {
                    $productToLog = ($product->get_type() === 'variation')
                        ? wc_get_product($product->get_parent_id())
                        : $product;
                    (ProductLogger::getLogger())->setLastUpdatedProductSku($productToLog->get_sku());

                    $product->set_stock_quantity($stock);
                    if ($stock > 0) {
                        $product->set_stock_status();
                    } else {
                        $product->set_stock_status('outofstock');
                    }
                    $product->save();
                }

                $response = [
                    'action'      => 'updated',
                    'description' => 'The product "'.esc_html($product->get_title()).'" with ID '.esc_attr($product_id).' and SKU '.esc_html($sku).' updated its stock to '.esc_attr($stock).'.',
                ];
            } else {
                $response = [
                    'action'      => 'none',
                    'description' => 'This product is not managing stock.',
                ];
            }
        } else {
            $response = [
                'action'      => 'none',
                'description' => 'There is no product with that SKU.',
            ];
        }

        return new \WP_REST_Response($response, 200);
    }
}
