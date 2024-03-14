<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\NoResultException;
use WcMipConnector\Exception\WooCommerceApiAdapterException;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Service\LoggerService;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class ZoneManager
{
    private const ALL_WORLD_ZONE = 'All World';
    private const LOCAL_PICKUP = 'local_pickup';

    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var TaxesManager */
    protected $taxesManager;
    /** @var LoggerService */
    protected $logger;

    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->taxesManager = new TaxesManager();
        $loggerService = new LoggerService();
        $this->logger = $loggerService->getInstance();
    }

    /**
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function findAllZones(): array
    {
        return $this->apiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_SHIPPING_ZONES);
    }

    /**
     * @param int $zoneId
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function findShippingMethodByZoneId(int $zoneId): array
    {
        $filters = ['zone_id' => $zoneId];

        return $this->apiAdapterService->getItems(WooCommerceApiMethodTypes::TYPE_SHIPPING_ZONES_METHOD, $filters);
    }

    /**
     * @throws WooCommerceApiAdapterException
     */
    public function disableShippingZoneMethod(int $zoneId, string $instanceId): void
    {
        $shippingZone = [
            'zone_id' => $zoneId,
            'instance_id' => $instanceId,
            'method_id' => self::LOCAL_PICKUP,
            'enabled' => false,
        ];

        try {
            $this->apiAdapterService->updateItem(WooCommerceApiMethodTypes::TYPE_SHIPPING_ZONES_METHOD, $shippingZone);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @return int
     * @throws NoResultException
     */
    private function getAllWorldZoneId(): int
    {
        $zonesIndexedById = null;

        try {
            $findAllZones = $this->findAllZones();
            $zonesIndexedById = \array_column($findAllZones, 'id', 'name');
        } catch (WooCommerceApiExceptionInterface $exception) {
            $this->logger->error('Exception error int getAllWorldZoneId: '. $exception->getMessage());
        }

        if (!array_key_exists(self::ALL_WORLD_ZONE, $zonesIndexedById)) {
            throw new NoResultException('No ID found.');
        }

        return $zonesIndexedById[self::ALL_WORLD_ZONE];
    }

    /**
     * @param int $zoneId
     * @return int
     * @throws WooCommerceApiExceptionInterface
     * @throws NoResultException
     */
    private function getShippingMethodInstanceId(int $zoneId): int
    {
        $zoneShippingMethods = $this->findShippingMethodByZoneId($zoneId);

        foreach ($zoneShippingMethods as $zoneShippingMethod) {
            if ($zoneShippingMethod['enabled'] && $zoneShippingMethod['method_id'] === self::LOCAL_PICKUP) {
                return $zoneShippingMethod['instance_id'];
            }
        }

        throw new NoResultException('No ID found');
    }

    public function disableLocalPickup(): void
    {
        try {
            $zoneId = $this->getAllWorldZoneId();
            $instanceId = $this->getShippingMethodInstanceId($zoneId);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            return;
        }

        try {
            $this->disableShippingZoneMethod($zoneId, $instanceId);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}