<?php

declare(strict_types=1);

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Repository\SupplierRepository;

class SupplierManager
{
    /** @var SupplierRepository $repository */
    protected $repository;
    /** @var AttributeGroupManager */
    protected $attributeGroupManager;

    public function __construct()
    {
        $this->repository = new SupplierRepository();
        $this->attributeGroupManager = new AttributeGroupManager();
    }

    /**
     * @param string $supplierName
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function create(string $supplierName): array
    {
        return $this->attributeGroupManager->create($supplierName, SupplierRepository::ATTRIBUTE_NAME);
    }

    /**
     * @param int $supplierId
     * @param string $attributeLabel
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function update(int $supplierId, string $attributeLabel): array
    {
        return $this->attributeGroupManager->update($supplierId, $attributeLabel, SupplierRepository::ATTRIBUTE_NAME);
    }

    public function findIdByLabel(string $attributeLabel): ?int
    {
        return $this->repository->findIdByLabel($attributeLabel);
    }

    public function findIdByName(): ?int
    {
        return $this->repository->findIdByName();
    }
}