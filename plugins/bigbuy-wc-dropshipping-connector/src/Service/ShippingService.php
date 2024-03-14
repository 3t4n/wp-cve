<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\Base\Exception\MultiShippingCartException;
use WcMipConnector\Client\BigBuy\Model\ShippingOption;
use WcMipConnector\Client\BigBuy\Model\ShippingRequest;
use WcMipConnector\Client\BigBuy\Shipping\Service\CarrierService;
use WcMipConnector\Client\BigBuy\Shipping\Service\ShippingService as BigBuyShippingService;
use WcMipConnector\Client\BigBuy\Factory\ShippingFactory as BigBuyShippingFactory;
use WcMipConnector\Enum\StatusTypes;
use WcMipConnector\Factory\BigBuy\ShippingFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\ShippingServiceManager;
use WcMipConnector\Model\ShippingServiceDelay;

class ShippingService
{
    private const TAX_DIVISION = 100.0;
    private const ROUND_PRECISION = 2;
    private const MIN_DATE_TO_UPDATE_CARRIERS = 7;
    private const DEFAULT_WAREHOUSE_ID = 1;
    public const NAME_FREE_SHIPPING = 'Free shipping';

    /** @var TaxesService */
    protected $taxesService;
    /** @var BigBuyShippingService */
    private $shippingServiceBigBuy;
    /** @var PublicationOptionsService */
    private $publicationOptionsService;
    /** @var LoggerService  */
    private $loggerService;
    /** @var CarrierService */
    private $carrierService;
    /** @var ShippingServiceManager */
    private $shippingServiceManager;
    /** @var ShippingServiceDelayTranslationService */
    protected $shippingServiceDelayTranslationService;
    /** @var ShippingFactory */
    private $shippingFactory;
    /** @var CacheService */
    private $cacheService;

    /**
     * ShippingService constructor.
     * @throws \Exception
     */
    public function __construct(
        ?BigBuyShippingService $shippingServiceBigBuy = null,
        ?PublicationOptionsService $publicationOptionsService = null,
        ?CacheService $cacheService = null,
        ?ShippingServiceManager $shippingServiceManager = null
    ) {
        $this->taxesService = new TaxesService();
        $apiKey = ConfigurationOptionManager::getApiKey();
        $this->shippingServiceBigBuy = $shippingServiceBigBuy !== null ? $shippingServiceBigBuy : BigBuyShippingService::getInstance($apiKey);
        $this->carrierService = CarrierService::getInstance($apiKey);
        $this->publicationOptionsService = $publicationOptionsService !== null ? $publicationOptionsService : new PublicationOptionsService();
        $this->loggerService = new LoggerService();
        $this->shippingServiceDelayTranslationService = new ShippingServiceDelayTranslationService();
        $this->shippingFactory = new ShippingFactory();
        $this->cacheService = $cacheService !== null ? $cacheService : CacheService::getInstance();
        $this->shippingServiceManager = $shippingServiceManager !== null ? $shippingServiceManager : new ShippingServiceManager();
    }

    /**
     * @param string $countryIsoCode
     * @param string $postCode
     * @param array $products
     * @return array
     * @throws ClientErrorException
     * @throws \Throwable
     */
    public function getPackageShippingCost(string $countryIsoCode, string $postCode, array $products): array
    {
        $cheapestCarrier = [];
        $publicationOptions = $this->publicationOptionsService->getPublicationOptions();

        if (
            \array_key_exists('shippingRateIncludedCountryIsoCode', $publicationOptions)
            && $publicationOptions['shippingRateIncludedCountryIsoCode'] === \strtolower($countryIsoCode)
        ) {
            $cheapestCarrier['carrier_name'] = self::NAME_FREE_SHIPPING;
            $cheapestCarrier['carrier_delay'] = '';
            $cheapestCarrier['carrier_cost'] = 0.0;
            $cheapestCarrier['taxes'] = 0.0;
            $cheapestCarrier['id'] = 0;

            return $cheapestCarrier;
        }

        $requestParams = $this->shippingFactory->create($countryIsoCode, $postCode, $products);
        $carriers = $this->getShippingCosts($requestParams);

        if (empty($carriers)) {
            return [];
        }

        $shippingServices = $this->shippingServiceManager->getDisabledNamesIndexedByName();

        $conversionFactor = $publicationOptions['conversionFactor'];
        $taxRate = 0.0;
        $taxId = 0;

        try {
            $tax = $this->taxesService->getTaxWithMaxRate($countryIsoCode);
            $taxRate = $tax['Rate'] / self::TAX_DIVISION;
            $taxId = $tax['Id'];
        } catch (\Exception $exception) {
            $this->loggerService->info('Empty taxes for country with iso_code '.$countryIsoCode);
        }

        foreach ($carriers as $shippingCost) {
            if (\array_key_exists($shippingCost->shippingService->name, $shippingServices)) {
                continue;
            }

            $shippingCostWithConversionRate = $shippingCost->cost * $conversionFactor;
            $shippingTax = $shippingCostWithConversionRate * $taxRate;
            $shippingWithTaxesAndConversionRate = \round($shippingCostWithConversionRate + $shippingTax, self::ROUND_PRECISION);

            if (
                empty($cheapestCarrier)
                || !\array_key_exists('carrier_cost', $cheapestCarrier)
                || $shippingWithTaxesAndConversionRate < $cheapestCarrier['carrier_cost']
            ) {
                $shippingServiceDelay = $this->shippingServiceDelayTranslationService->getDelayTranslationFromIsoCode(
                    $shippingCost->shippingService->delay
                );

                $cheapestCarrier['carrier_name'] = $shippingCost->shippingService->serviceName;
                $cheapestCarrier['carrier_delay'] = $shippingServiceDelay;
                $cheapestCarrier['carrier_cost'] = $shippingWithTaxesAndConversionRate;
                $cheapestCarrier['taxes'] = \round($shippingTax, self::ROUND_PRECISION);
                $cheapestCarrier['id'] = $taxId;
            }
        }

        return $cheapestCarrier;
    }

    /**
     * @param ShippingRequest $requestParams
     * @return array
     * @throws ClientErrorException
     * @throws \Throwable
     */
    public function getShippingCosts(ShippingRequest $requestParams): array
    {
        try {
            $shippingOptionsResponse = $this->getShippingCost($requestParams);
        } catch (MultiShippingCartException $multiShippingCartException) {
            $shippingOptionsResponse = $this->handleMultiShipping($requestParams, \json_decode($multiShippingCartException->getMessage(), true));
        } catch (\Throwable $exception) {
            $this->loggerService->critical(__METHOD__.' Error: '.$exception->getMessage());

            return [];
        }

        return $shippingOptionsResponse;
    }

    /**
     * @throws ClientErrorException|MultiShippingCartException|\Throwable
     */
    private function getShippingCost(ShippingRequest $requestParams): array
    {
        $itemId = \md5(\json_encode($requestParams));
        $shippingOptionsResponseContents = $this->cacheService->findOneById($itemId);

        if (!empty($shippingOptionsResponseContents)) {
            $shippingOptions = [];
            $shippingOptionsResponse = \json_decode($shippingOptionsResponseContents, true);
            $shippingOptionsData = $shippingOptionsResponse['shippingOptions'] ?? [];

            foreach ($shippingOptionsData as $shippingOptionData) {
                $shippingOptions[] = BigBuyShippingFactory::getInstance()->create($shippingOptionData);
            }

            return $shippingOptions;
        }

        $shippingOptionsResponse = [];
        $iteration = 1;
        $exponent = 2;
        $maxRetries = 10;
        $initialWait = \random_int(200, 500);

        do {
            $errorMessage = '';

            try {
                $shippingOptionsResponse = $this->shippingServiceBigBuy->getShippingCost($requestParams);
            } catch (\Throwable $exception) {
                if ($exception instanceof MultiShippingCartException) {
                    throw $exception;
                }

                if ($exception->getCode() !== StatusTypes::HTTP_TOO_MANY_REQUESTS) {
                    $this->loggerService->critical(__METHOD__.' Error: '.$exception->getMessage());

                    throw $exception;
                }

                $errorMessage = $exception->getMessage();
            }

            if (empty($errorMessage)) {
                $request = \md5(\json_encode($requestParams));
                $responseContents =\json_encode($shippingOptionsResponse);

                $this->cacheService->save($request, $responseContents);
                $shippingOptions = [];
                $shippingOptionsData = $shippingOptionsResponse['shippingOptions'] ?? [];

                foreach ($shippingOptionsData as $shippingOptionData) {
                    $shippingOptions[] = BigBuyShippingFactory::getInstance()->create($shippingOptionData);
                }

                return $shippingOptions;
            }

            $iteration++;
            \usleep($initialWait);
            $initialWait *= $exponent;
        } while ($iteration < $maxRetries);

        throw new ClientErrorException(StatusTypes::HTTP_TOO_MANY_REQUESTS, $errorMessage);
    }

    /**
     * @param ShippingRequest $shippingRequest
     * @param array $response
     * @return array
     * @throws ClientErrorException
     * @throws MultiShippingCartException
     * @throws \Throwable
     */
    private function handleMultiShipping(ShippingRequest $shippingRequest, array $response): array
    {
        if (!\array_key_exists('error_detail', $response)) {
            return [];
        }

        $warehouses = $response['error_detail']['warehouses'];
        $shippingOptionsIndexedByWarehouseId = [];

        foreach ($warehouses as $warehouse) {
            $references = $warehouse['references'];
            $requestParams = $this->shippingFactory->createMultiShipping($shippingRequest, $references);
            $shippingOptionsResponse = $this->getShippingCost($requestParams);
            $shippingOptionsIndexedByWarehouseId[$warehouse['id']] = $shippingOptionsResponse;
        }

        $shippingOptionsDefaultWarehouseId = \array_key_exists(
            self::DEFAULT_WAREHOUSE_ID,
            $shippingOptionsIndexedByWarehouseId
        )
            ? self::DEFAULT_WAREHOUSE_ID
            : \array_key_first($shippingOptionsIndexedByWarehouseId)
        ;

        $multiShippingOptions = [];
        $additionalShippingServices = [];

        foreach ($shippingOptionsIndexedByWarehouseId as $warehouseId => &$shippingOptions) {
            if (empty($shippingOptions)) {
                continue;
            }

            \usort($shippingOptions, static function (ShippingOption $a, ShippingOption $b) {
                $costA = $a->cost;
                $costB = $b->cost;

                if ($costA === $costB) {
                    return 0;
                }

                return ($costA > $costB) ? +1 : -1;
            });

            if ($warehouseId === $shippingOptionsDefaultWarehouseId) {
                continue;
            }

            /** @var ShippingOption $cheapestShippingOption */
            $cheapestShippingOption = \current($shippingOptions);

            foreach ($shippingOptionsIndexedByWarehouseId[$shippingOptionsDefaultWarehouseId] as $shippingOption) {
                $shippingOption->cost = $shippingOption->cost + $cheapestShippingOption->cost;

                if (!\array_key_exists($cheapestShippingOption->shippingService->serviceName, $additionalShippingServices)) {
                    $additionalShippingServices[$cheapestShippingOption->shippingService->serviceName] = clone $cheapestShippingOption;
                }

                $shippingServiceDelay = ShippingServiceDelay::fromString($shippingOption->shippingService->delay);
                $additionalShippingServiceDelay = ShippingServiceDelay::fromString($additionalShippingServices[$cheapestShippingOption->shippingService->serviceName]->shippingService->delay);
                $daysMin = \min(
                    $shippingServiceDelay->getShippingDaysMin(),
                    $additionalShippingServiceDelay->getShippingDaysMin()
                );
                $daysMax = \max(
                    $shippingServiceDelay->getShippingDaysMax(),
                    $additionalShippingServiceDelay->getShippingDaysMax()
                );
                $shippingServiceDelay = ShippingServiceDelay::fromMinAndMAx($daysMin, $daysMax);
                $shippingOption->shippingService->delay = $shippingServiceDelay->getDelay().' days';

                $multiShippingOptions[] = $shippingOption;
            }
        }

        return $multiShippingOptions;
    }

    /**
     * @return array
     */
    public function getShippingServices(): array
    {
        $cacheId = CacheService::generateCacheKey(__METHOD__);
        if (CacheService::getInstance()->has($cacheId)) {
            return CacheService::getInstance()->get($cacheId);
        }

        $carriers = $this->carrierService->getCarriers() ?: [];

        CacheService::getInstance()->set($cacheId, $carriers, 10);

        return $carriers;
    }

    public function updateShippingServices(): void
    {
        $shippingServices = $this->getShippingServices();

        if (!$shippingServices || !$this->shouldUpdateCarriers()) {
            return;
        }

        $shippingServicesIndexById = $this->shippingServiceManager->getAllIndexedById();

        foreach ($shippingServices as $shippingService) {
            if (\array_key_exists($shippingService['id'], $shippingServicesIndexById)) {
                $this->shippingServiceManager->update($shippingService);

                continue;
            }

            $this->shippingServiceManager->insert($shippingService);
        }
    }

    private function shouldUpdateCarriers(): bool
    {
        $lastApiCarrierUpdateDate = ConfigurationOptionManager::getLastCarrierUpdate();

        $currentDateTime = new \DateTime('now');
        $currentTime = $currentDateTime->format('Y-m-d H:i:s');

        if (!$lastApiCarrierUpdateDate) {
            ConfigurationOptionManager::setLastCarrierUpdate($currentTime);

            return true;
        }

        $lastApiCarrierUpdateDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $lastApiCarrierUpdateDate);
        $intervalFromLastUpdate = $currentDateTime->diff($lastApiCarrierUpdateDateTime);

        if ($intervalFromLastUpdate->days < self::MIN_DATE_TO_UPDATE_CARRIERS) {
            return false;
        }

        ConfigurationOptionManager::setLastCarrierUpdate($currentTime);

        return true;
    }

    public function getShippingMethods($methods): array
    {
        if (ConfigurationOptionManager::getCarrierOption() === true) {
            return $methods;
        }

        $methods[ShippingMethodService::SHIPPING_METHOD_ID] = new ShippingMethodService();

        return $methods;
    }
}
