<?php

namespace WcMipConnector\Controller;

defined('ABSPATH') || exit;

require __DIR__.'/../../vendor/autoload.php';

use WcMipConnector\Manager\OrderLogManager;
use WcMipConnector\Enum\WooCommerceOrderStatus;
use WcMipConnector\Manager\SystemManager;
use WcMipConnector\Service\LoggerService;

class OrderController
{
    public const ORDER_ROUTE = 'order';
    public const ORDER_CONTROLLER_URL = '/'.ApiController::API_ENDPOINT.'/'.self::ORDER_ROUTE;

    /** @var OrderLogManager  */
    private $orderLogManager;
    /** @var SystemManager  */
    private $systemManager;
    /** @var LoggerService  */
    private $logger;

    public function __construct()
    {
        $this->orderLogManager = new OrderLogManager();
        $this->systemManager = new SystemManager();
        $loggerService = new LoggerService();
        $this->logger = $loggerService->getInstance();
    }

    /**
     * @param array $queryVars
     * @return bool
     */
    public static function canHandleRequest(array $queryVars): bool
    {
        return \array_key_exists(ApiController::MIP_ROUTE, $queryVars) && ($queryVars[ApiController::MIP_ROUTE] === self::ORDER_ROUTE);
    }

    public function processAction(): void
    {
        $this->systemManager->resetFailureCount();
        $webhookResponse = \json_decode(@file_get_contents('php://input'), true);
        
        if (!$webhookResponse) {
            $this->logger->error('Could not decode WebHook response');
            
            return;
        }

        if (!\array_key_exists('id', $webhookResponse) || !\array_key_exists('status', $webhookResponse)) {
            $this->logger->error('Could not log order from WebHook action. Webhook response: '.\json_encode($webhookResponse));

            return;
        }

        $orderLog = $this->orderLogManager->getByOrderId((int) $webhookResponse['id']);

        if (!$orderLog && $webhookResponse['status'] === WooCommerceOrderStatus::PROCESSING) {
            $this->orderLogManager->save($webhookResponse);
            $this->logger->info('The order '.$webhookResponse['id'].' has been mapped.');
        }
    }
}