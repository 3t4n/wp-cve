<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Endpoints;

use Holded\Woocommerce\Adapters\ProductAdapter;

class ExportProductsEndpoint extends AbstractEndpoint
{
    private const LIMIT = 10;

    public function init(): void
    {
        add_action('rest_api_init', [$this, 'registerEndpoint']);
    }

    public function registerEndpoint(): void
    {
        register_rest_route($this->apiNamespace, '/exportProducts', [
            'methods'  => \WP_REST_Server::EDITABLE,
            'callback' => [$this, 'processExportProducts'],
            'args'     => [
                'page' => [
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
                'limit' => [
                    'required'          => false,
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
    public function processExportProducts(\WP_REST_Request $request)
    {
        $authentication = $this->authentication($request);
        if (is_wp_error($authentication) && is_object($authentication)) {
            return $authentication;
        }

        $requestData = $request->get_body_params();
        $page = $requestData['page'];
        $limit = $requestData['limit'];

        $args = [
            'page'    => $page,
            'limit'   => $limit ?? self::LIMIT,
            'orderby' => 'name',
        ];

        $response = [
            'products' => [],
        ];

        $products = wc_get_products($args);
        foreach ($products as $product) {
            $dtoProduct = ProductAdapter::fromWoocommerceToDTO($product);

            if ($dtoProduct->kind === 'simple' && empty($dtoProduct->sku)) {
                continue;
            }

            if ($dtoProduct->kind === 'variants') {
                $dtoProduct->removeVariantsWithoutSku();

                if (!$dtoProduct->hasVariants()) {
                    continue;
                }
            }

            $response['products'][] = $dtoProduct;
        }

        $response['isLastPage'] = count($products) < $limit;

        return new \WP_REST_Response($response, 200);
    }
}
