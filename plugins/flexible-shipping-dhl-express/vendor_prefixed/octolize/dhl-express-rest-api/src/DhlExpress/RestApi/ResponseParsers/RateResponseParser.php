<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ResponseParsers;

use DateTimeImmutable;
use Exception;
use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\TotalPriceNotFoundException;
use DhlVendor\Octolize\DhlExpress\RestApi\Traits\GetRawResponse;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Rate;
class RateResponseParser
{
    use GetRawResponse;
    public const DEFAULT_CURRENCY = 'EUR';
    /**
     * @var array
     */
    private $response;
    public function __construct(array $response)
    {
        $this->response = $response;
    }
    /**
     * Parse the API rates response and return array of available rates
     *
     * @param array $response
     *
     * @return Rate[]
     * @throws TotalPriceNotFoundException
     */
    public function parse() : array
    {
        $rates = [];
        if (!isset($this->response['products']) || !\is_iterable($this->response['products'])) {
            return $rates;
        }
        foreach ($this->response['products'] as $p) {
            $rates = \array_merge($rates, $this->parseProductRates($p));
        }
        return $rates;
    }
    /**
     * @param array $rate
     *
     * @return Rate[]
     * @throws TotalPriceNotFoundException|Exception
     */
    protected function parseProductRates(array $rate) : array
    {
        $rates = [];
        $prices = $this->parsePrice($rate);
        $estimatedDeliveryDateAndTime = new \DateTimeImmutable($rate['deliveryCapabilities']['estimatedDeliveryDateAndTime']);
        $totalTransitDays = $rate['deliveryCapabilities']['totalTransitDays'];
        $pricingDate = new \DateTimeImmutable($rate['pricingDate']);
        foreach ($prices as $currency => $price) {
            $rates[] = new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Rate((string) $rate['productName'], (string) $rate['productCode'], (string) $rate['localProductCode'], (string) $rate['localProductCountryCode'], (bool) $rate['isCustomerAgreement'], (float) $rate['weight']['volumetric'], (float) $rate['weight']['provided'], (float) $price['total_price'], (float) $price['total_tax'], (string) $currency, $estimatedDeliveryDateAndTime, $totalTransitDays, $pricingDate);
        }
        return $rates;
    }
    /**
     * @param array $rate
     *
     * @return array
     * @throws TotalPriceNotFoundException
     */
    protected function parsePrice(array $rate) : array
    {
        $prices = $rate['totalPrice'];
        $total_price = [];
        foreach ($prices as $p) {
            if (isset($p['priceCurrency'])) {
                $price = (float) $p['price'];
                $total_price[(string) $p['priceCurrency']] = ['total_price' => $price, 'total_tax' => $this->parseTax($rate, $p['priceCurrency'], $p['currencyType'])];
            }
        }
        return $total_price;
    }
    protected function parseTax(array $rate, string $currency, string $currencyType) : float
    {
        $tax = 0;
        if (!isset($rate['totalPriceBreakdown']) || !\is_iterable($rate['totalPriceBreakdown'])) {
            return $tax;
        }
        foreach ($rate['totalPriceBreakdown'] as $p) {
            if ($p['currencyType'] === $currencyType && $p['priceCurrency'] === $currency) {
                foreach ($p['priceBreakdown'] as $b) {
                    if ($b['typeCode'] === 'STTXA') {
                        $tax += (float) $b['price'];
                    }
                }
            }
        }
        return $tax;
    }
}
