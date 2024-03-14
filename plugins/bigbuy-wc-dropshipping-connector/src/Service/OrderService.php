<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceOrderStatus;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\OrderFactory;
use WcMipConnector\Manager\OrderLogManager;
use WcMipConnector\Manager\OrderManager;

class OrderService
{
    private const ORDER_STATUS_IN_PROCESS = 'ORDER_IN_PROCESS';
    private const ORDER_STATUS_SENT = 'ORDER_SENT';
    private const ORDER_STATUS_PENDING_REVIEW = 'ORDER_PENDING_REVIEW';
    private const ORDER_STATUS_DELIVERED = 'ORDER_DELIVERED';
    private const ORDER_STATUS_CANCELLED = 'ORDER_CANCELLED';

    /** @var OrderManager  */
    private $orderManager;
    /** @var OrderLogManager  */
    private $orderLogManager;
    /** @var LoggerService  */
    private $logger;

    public function __construct()
    {
        $this->orderManager = new OrderManager();
        $this->orderLogManager = new OrderLogManager();
        $loggerService = new LoggerService();
        $this->logger = $loggerService->getInstance();
    }

    /**
     * @param int $orderId
     * @return array|null
     */
    public function findOrderByOrderId(int $orderId): ?array
    {
        try {
            $order = $this->orderManager->getById($orderId);
        } catch (\Exception $e) {
            $this->logger->error('Getting Order Id: '.$orderId .' - Error message: '. $e->getMessage());
            return null;
        }
        return $order;
    }

    /**
     * @param string $stateOrder
     * @return string|null
     */
    public function stateOrderMap(string $stateOrder): ?string
    {
        switch ($stateOrder) {
            case self::ORDER_STATUS_CANCELLED:
                return WooCommerceOrderStatus::CANCELLED;
            case self::ORDER_STATUS_DELIVERED:
                return WooCommerceOrderStatus::COMPLETED;
            case self::ORDER_STATUS_SENT:
            case self::ORDER_STATUS_IN_PROCESS:
                return WooCommerceOrderStatus::PROCESSING;
            case self::ORDER_STATUS_PENDING_REVIEW:
                return WooCommerceOrderStatus::ON_HOLD;
            default:
                $this->logger->error('Order Status not valid: ' . $stateOrder);
                return null;
        }
    }

    /**
     * @param array $orderData
     * @param string $orderNewStatus
     * @return array
     */
    public function changeStateOrder(array $orderData, string $orderNewStatus): array
    {
        $response = [
            'OrderID' => $orderData['OrderID'],
            'State' => false,
        ];

        $order = $this->findOrderByOrderId((int)$orderData['OrderID']);

        if (!$order) {
            $this->logger->warning('Order with ID: '.$orderData['OrderID'].' has not found');

            return $response;
        }

        if ($orderData['TrackingNumber']) {
            try {
                $this->addOrderNoteIfNotExists($order['id'], $orderData['ShippingService'], $orderData['TrackingNumber']);
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error('Adding tracking in order id: '.$order['id'] .' - Error message: '. $exception->getMessage(), [$orderData, $order]);
            }
        }

        if (((string)$order['status'] === $orderNewStatus)
            || !$this->isValidNewOrderStatusId((string)$order['status'], $orderNewStatus)) {
            $this->logger->error('Order status not updated in order id: ' . $orderData['OrderID'] . ' - Current status: ' . $order['status'] . ' - New status: ' . $orderNewStatus . ' - Order data request: ' . \json_encode($orderData));

            return $response;
        }

        try {
            $this->orderManager->updateStatus($order['id'], $orderNewStatus);
            $response['State'] = true;
        } catch (\Exception $exception) {
            $this->logger->error('Changing order status in Order id: '.$order['id'] .' - New status: '.$orderNewStatus.' - Error message: '. $exception->getMessage(), [$orderData, $order]);
            $response['State'] = false;
        }
        return $response;
    }

    /**
     * @param int $orderId
     * @param string $shippingService
     * @param string $trackingNumber
     * @throws WooCommerceApiExceptionInterface
     */
    private function addOrderNoteIfNotExists(int $orderId, string $shippingService, string $trackingNumber): void
    {
        $orderNotes = $this->orderManager->findOrderNotesById($orderId);

        $orderFactory = new OrderFactory();
        $orderNoteData = $orderFactory->createTrackingNote($shippingService, $trackingNumber);

        foreach ($orderNotes as $orderNote) {
            if ($orderNote['note'] === $orderNoteData['note']) {
                return;
            }
        }

        $this->orderManager->addOrderNote($orderId, $orderNoteData);
    }

    /**
     * @param string|null $currentOrderStatusId
     * @param string $orderStatusId
     * @return bool
     */
    public function isValidNewOrderStatusId(?string $currentOrderStatusId, string $orderStatusId): bool
    {
        $orderStatusInvalidTransitionsIndexedByOrderStatusId = [
            WooCommerceOrderStatus::PROCESSING => [
                WooCommerceOrderStatus::PENDING,
                WooCommerceOrderStatus::PROCESSING
            ],
            WooCommerceOrderStatus::COMPLETED => [
                WooCommerceOrderStatus::PROCESSING,
                WooCommerceOrderStatus::PENDING
            ],
            WooCommerceOrderStatus::CANCELLED => [
                WooCommerceOrderStatus::PENDING,
                WooCommerceOrderStatus::PROCESSING,
                WooCommerceOrderStatus::COMPLETED,
            ],
        ];

        if (!\array_key_exists($currentOrderStatusId, $orderStatusInvalidTransitionsIndexedByOrderStatusId)) {
            return true;
        }

        if (\in_array($orderStatusId, $orderStatusInvalidTransitionsIndexedByOrderStatusId[$currentOrderStatusId], true)) {
            return false;
        }

        return true;
    }

    public function handleUnmappedOrders(): void
    {
        try {
            $ordersIdsNotMapped = $this->orderLogManager->getOrderIdsNotMappedFilteredByDay();
        } catch (\Exception $e) {
            $this->logger->error('Exception in getOrderIdsNotMappedFilteredByDay: '. $e->getMessage());

            return;
        }

        if (empty($ordersIdsNotMapped)) {
            return;
        }

        foreach ($ordersIdsNotMapped as $orderId) {
            $order = $this->findOrderByOrderId($orderId);

            if (
                empty($order)
                || !\array_key_exists('status', $order)
                || $order['status'] !== WooCommerceOrderStatus::PROCESSING
            ) {
                continue;
            }

            $this->orderLogManager->save($order);
            $this->logger->info('The order '.$order['id'].' has been mapped.');
        }
    }
}