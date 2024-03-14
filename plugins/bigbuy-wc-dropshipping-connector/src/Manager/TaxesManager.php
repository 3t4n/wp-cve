<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class TaxesManager
{
    /**
     * @var WoocommerceApiAdapterService
     */
    private $woocommerceApiAdapterService;

    public function __construct()
    {
        $this->woocommerceApiAdapterService = new WoocommerceApiAdapterService();
    }

    /**
     * @return array
     */
    public function getTaxes(): array
    {
        $filters = ['orderby' => 'id', 'page' => 1, 'per_page' => 100];

        try {
            return $this->woocommerceApiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_TAXES, $filters);
        } catch (WooCommerceApiExceptionInterface $e) {
            return [];
        }
    }
}