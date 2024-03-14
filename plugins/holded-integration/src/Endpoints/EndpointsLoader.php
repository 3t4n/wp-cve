<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Endpoints;

class EndpointsLoader
{
    public function load(): void
    {
        $endpoints = [
            UpdateStockEndpoint::class,
            HealthEndpoint::class,
            ExportProductsEndpoint::class,
            PaymentMethodsEndpoint::class,
            DeactivateEndpoint::class,
        ];

        foreach ($endpoints as $endpoint) {
            (new $endpoint())->init();
        }
    }
}
