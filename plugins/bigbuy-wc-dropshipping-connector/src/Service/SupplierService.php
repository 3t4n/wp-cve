<?php

declare(strict_types=1);

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\SupplierManager;

class SupplierService
{
    private const PRODUCT_SUPPLIER_BIGBUY = 'BB';
    private const PRODUCT_SUPPLIER_LABEL = 'Supplier';

    /** @var SupplierManager */
    protected $supplierManager;
    /** @var LoggerService */
    protected $logger;
    /** @var array */
    private $supplierData = [];

    public function __construct()
    {
        $this->logger = new LoggerService;
        $this->supplierManager = new SupplierManager();
    }

    public function getAttribute(): array
    {
        if (!empty($this->supplierData)) {
            return $this->supplierData;
        }

        try {
            $supplierId = $this->createSupplierGroupIfNotExists();
        } catch (WooCommerceApiExceptionInterface $exception) {
            $this->logger->getInstance()->error('Create Supplier Group - Exception message: '.$exception->getMessage());
            return [];
        }

        return [
            'id' => $supplierId,
            'visible' => false,
            'options' => [self::PRODUCT_SUPPLIER_BIGBUY]
        ];
    }

    private function createSupplierGroupIfNotExists(): ?int
    {
        $supplierId = ConfigurationOptionManager::getSupplierId();
        $supplierIdByName = $this->supplierManager->findIdByName();
        $supplierIdByLabel = $this->supplierManager->findIdByLabel(self::PRODUCT_SUPPLIER_LABEL);

        if ($supplierId && $supplierId === $supplierIdByName && $supplierId === $supplierIdByLabel) {
            return $supplierId;
        }

        if ($supplierIdByName !== null) {
            ConfigurationOptionManager::setSupplierId($supplierIdByName);
            $this->supplierManager->update($supplierIdByName, self::PRODUCT_SUPPLIER_LABEL);

            return $supplierIdByName;
        }

        if ($supplierIdByLabel !== null) {
            ConfigurationOptionManager::setSupplierId($supplierIdByLabel);
            $this->supplierManager->update($supplierIdByLabel, self::PRODUCT_SUPPLIER_LABEL);

            return $supplierIdByLabel;
        }

        $supplier = $this->supplierManager->create(self::PRODUCT_SUPPLIER_LABEL);
        $supplierId = $supplier['id'];
        ConfigurationOptionManager::setSupplierId($supplierId);

        return $supplierId;
    }
}