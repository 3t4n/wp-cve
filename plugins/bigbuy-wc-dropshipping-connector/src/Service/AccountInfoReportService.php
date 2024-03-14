<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\AccountInfoReportFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Model\WoocommerceReportModel;
use WcMipConnector\Client\BigBuy\Shipping\Service\CarrierService;

class AccountInfoReportService
{
    /** @var WoocommerceApiAdapterService  */
    protected $woocommerceApiAdapterService;
    /** @var SystemService */
    private $systemService;

    public function __construct()
    {
        $this->woocommerceApiAdapterService = new WoocommerceApiAdapterService();
        $this->systemService = new SystemService();
    }

    /**
     * @return array|null
     * @throws WooCommerceApiExceptionInterface
     */
    public function get(): ?array
    {
        $accountInfoReport = $this->woocommerceApiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_SYSTEM_STATUS);
        $apiKeyEnabled = $this->systemService->isValidApiKey(ConfigurationOptionManager::getApiKey());

        $accountInfoReportModel = AccountInfoReportFactory::create($accountInfoReport, $apiKeyEnabled);

        return json_decode(json_encode($accountInfoReportModel), true);
    }

    /**
     * @return WoocommerceReportModel|null
     */
    public function getWoocommerceReport(): ?WoocommerceReportModel
    {
        try {
            $accountInfoReport = $this->woocommerceApiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_SYSTEM_STATUS);
        } catch (WooCommerceApiExceptionInterface $e) {
            return null;
        }

        return AccountInfoReportFactory::createWoocommerceReport($accountInfoReport);
    }
}