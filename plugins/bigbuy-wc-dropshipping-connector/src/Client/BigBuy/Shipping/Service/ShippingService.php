<?php

namespace WcMipConnector\Client\BigBuy\Shipping\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\Base\Exception\MultiShippingCartException;
use WcMipConnector\Client\BigBuy\Base\Service\AbstractService;
use WcMipConnector\Client\BigBuy\Model\ShippingOption;
use WcMipConnector\Client\BigBuy\Model\ShippingRequest;
use WcMipConnector\Enum\StatusTypes;

class ShippingService extends AbstractService
{
    const URL_SHIPPING_COST = '/rest/shipping/orders';
    const CART_PRODUCTS_FROM_DIFFERENT_WAREHOUSE = 'This cart contains products from different warehouses';

    /** @var ShippingService */
    public static $instance;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        parent::__construct($apiKey);
    }

    /**
     * @param string $apiKey
     * @return ShippingService
     */
    public static function getInstance($apiKey)
    {
        if (!self::$instance) {
            self::$instance = new self($apiKey);
        }

        return self::$instance;
    }

    /**
     * @param ShippingRequest $shippingRequest
     * @return ShippingOption[]
     * @throws MultiShippingCartException
     * @throws ClientErrorException
     */
    public function getShippingCost(ShippingRequest $shippingRequest): array
    {
        try {
            $shippingOptionsResponse = $this->post(self::URL_SHIPPING_COST, \json_decode(\json_encode($shippingRequest), true));
        } catch (\Exception $exception) {
            if (
                $exception->getCode() === StatusTypes::HTTP_CONFLICT
                && strpos($exception->getMessage(), self::CART_PRODUCTS_FROM_DIFFERENT_WAREHOUSE) !== false
            ) {
                throw new MultiShippingCartException($exception->getMessage(), $exception->getCode());
            }

            if ($exception instanceof ClientErrorException && $exception->getCode() === StatusTypes::HTTP_TOO_MANY_REQUESTS) {
                throw new ClientErrorException($exception->getCode(), $exception->getMessage());
            }

            return [];
        }

        if (
            empty($shippingOptionsResponse)
            || \array_key_exists('error', $shippingOptionsResponse)
            || (
                \array_key_exists('code', $shippingOptionsResponse)
                && $shippingOptionsResponse['code'] !== 200
            )
        ) {
            return [];
        }

        if (!\is_array($shippingOptionsResponse)) {
            return [];
        }

        return $shippingOptionsResponse;
    }
}
