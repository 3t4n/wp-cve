<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Repository\OrderLogRepository;

class OrderLogManager
{
    /** @var OrderLogRepository  */
    protected $orderLogRepository;

    public function __construct()
    {
        $this->orderLogRepository = new OrderLogRepository();
    }

    public function getByOrderId(int $orderId)
    {
        return $this->orderLogRepository->getByOrderId($orderId);
    }

    public function save(array $order): ?bool
    {
        $data = [
            'order_id' => $order['id'],
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null,
            'date_process' => null,
        ];

        return $this->orderLogRepository->save($data);
    }

    /**
     * @param string $date
     * @return array|null
     */
    public function getOrdersWithoutShippingByDate(string $date): ?array
    {
        return $this->orderLogRepository->getOrdersWithoutShippingByDate($date);
    }

    /**
     * @param string $date
     * @return bool
     */
    public function checkIfExistsMoreOrdersToSend(string $date): bool
    {
        return $this->orderLogRepository->checkIfExistsMoreOrdersToSend($date);
    }

    public function setOrderAsProcess(int $orderId): bool
    {
        $data = [
            'date_process' => date('Y-m-d H:i:s'),
        ];

        $where = [
            'order_id' => $orderId,
        ];

        return $this->orderLogRepository->update($data, $where);
    }

    public function setOrderAsUpdate(int $orderId): bool
    {
        $data = [
            'date_update' => date('Y-m-d H:i:s'),
        ];

        $where = [
            'order_id' => $orderId,
        ];

        return $this->orderLogRepository->update($data, $where);
    }

    public function getOrderIdsNotMappedFilteredByDay(int $days = 1): array
    {
        return $this->orderLogRepository->getOrderIdsNotMappedFilteredByDay($days);
    }

    /**
     * @return int
     * @throws \Exception thrown when query cannot be executed
     */
    public function countNotMapped(): int
    {
        return $this->orderLogRepository->countNotMapped();
    }

    /**
     * @return int
     * @throws \Exception thrown when query cannot be executed
     */
    public function countMapped(): int
    {
        return $this->orderLogRepository->countMapped();
    }

    /**
     * @param array $order
     * @return bool|null
     */
    public function saveAndSetAsProcessed(array $order): ?bool
    {
        $data = [
            'order_id' => $order['id'],
            'date_add' => $order['date_created'],
            'date_update' => null,
            'date_process' => date('Y-m-d H:i:s'),
        ];

        return $this->orderLogRepository->save($data);
    }
}