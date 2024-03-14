<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Repository\ShippingServiceRepository;

class ShippingServiceManager
{
    /** @var ShippingServiceRepository */
    private $repository;

    public function __construct()
    {
        $this->repository = new ShippingServiceRepository();
    }

    /**
     * @return array
     */
    public function getAllIndexedById(): array
    {
        return $this->repository->getAllIndexedById();
    }

    /**
     * @param string $order
     * @param string|null $orderBy
     * @return array
     */
    public function findAllAndOrderBy(string $order, ?string $orderBy): array
    {
        return $this->repository->findAllAndOrderBy($order, $orderBy);
    }

    /**
     * @return array
     */
    public function getDisabledNamesIndexedByName(): array
    {
        return $this->repository->getDisabledNamesIndexedByName();
    }

    /**
     * @param array $shippingService
     * @return bool
     */
    public function insert(array $shippingService): bool
    {
        $data = [
            'id' => $shippingService['id'],
            'name' => $shippingService['name'],
            'active' => 1,
        ];

        return $this->repository->insert($data);
    }

    /**
     * @param array $shippingService
     * @return bool
     */
    public function update(array $shippingService): bool
    {
        $data = [
            'name' => $shippingService['name'],
        ];

        $filter = [
            'id' => $shippingService['id'],
        ];

        return $this->repository->update($data, $filter);
    }

    /**
     * @param int $shippingServiceId
     * @return bool
     */
    public function enable(int $shippingServiceId): bool
    {
        $data = [
            'active' => 1,
        ];

        $filter = [
            'id' => $shippingServiceId,
        ];

        return $this->repository->update($data, $filter);
    }

    /**
     * @param int $shippingServiceId
     * @return bool
     */
    public function disable(int $shippingServiceId): bool
    {
        $data = [
            'active' => 0,
        ];

        $filter = [
            'id' => $shippingServiceId,
        ];

        return $this->repository->update($data, $filter);
    }
}