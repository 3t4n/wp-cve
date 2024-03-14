<?php
declare(strict_types=1);

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiAdapterException;
use WcMipConnector\Model\WCBatchRequest;

class EmailConfiguratorService
{
    /** @var WoocommerceApiAdapterService  */
    private $woocommerceApiAdapterService;
    /** @var LoggerService  */
    private $logger;

    public function __construct()
    {
        $this->woocommerceApiAdapterService = new WoocommerceApiAdapterService();
        $this->logger = new LoggerService();
    }

    public function updateWooCommerceEmailSettings(bool $isEnabled): bool
    {
        $enabledValue = $isEnabled ? 'yes' : 'no';
        $request = [
            [
                'group_id' => 'email_customer_completed_order',
                'id' => 'enabled'
            ],
            [
                'group_id' => 'email_customer_on_hold_order',
                'id' => 'enabled'
            ],
            [
                'group_id' => 'email_customer_processing_order',
                'id' => 'enabled'
            ],

        ];
        $queryParams = ['value' => $enabledValue];

        $wcBatchRequest = new WCBatchRequest();
        $wcBatchRequest->update = $request;

        try {
            $this->woocommerceApiAdapterService->batchItems(
                WooCommerceApiMethodTypes::TYPE_SETTINGS_OPTIONS,
                $wcBatchRequest,
                $queryParams
            );
        } catch (WooCommerceApiAdapterException $exception) {
            $this->logger->error('Could not update email options: '.$exception->getMessage());

            return false;
        }

        return true;
    }

}