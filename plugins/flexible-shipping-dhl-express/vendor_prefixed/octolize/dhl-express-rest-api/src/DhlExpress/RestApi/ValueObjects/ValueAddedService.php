<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

class ValueAddedService
{
    private string $serviceCode;
    private ?\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\DangerousGood $dangerousGood = null;
    private float $value = 0;
    private string $currency = '';
    private string $method = '';
    public function __construct(string $serviceCode, ?\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\DangerousGood $dangerousGood = null, float $value = 0, string $currency = '', string $method = '')
    {
        $this->method = $method;
        $this->currency = $currency;
        $this->value = $value;
        $this->dangerousGood = $dangerousGood;
        $this->serviceCode = $serviceCode;
    }
    public function getAsArray() : array
    {
        $result = [];
        $result['serviceCode'] = $this->serviceCode;
        if ($this->dangerousGood) {
            $result['dangerousGoods'][] = $this->dangerousGood->getAsArray();
        }
        if ($this->value) {
            $result['value'] = $this->value;
        }
        if ($this->currency) {
            $result['currency'] = $this->currency;
        }
        if ($this->method) {
            $result['method'] = $this->method;
        }
        return $result;
    }
}
