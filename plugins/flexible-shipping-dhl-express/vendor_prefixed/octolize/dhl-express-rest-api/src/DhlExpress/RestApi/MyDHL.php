<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi;

use DhlVendor\Octolize\DhlExpress\RestApi\Services\RateService;
use DhlVendor\Octolize\DhlExpress\RestApi\Services\ShipmentService;
class MyDHL
{
    protected \DhlVendor\Octolize\DhlExpress\RestApi\Client $client;
    public function __construct(string $username, string $password, bool $testMode = \false)
    {
        $this->client = new \DhlVendor\Octolize\DhlExpress\RestApi\Client($username, $password, $testMode);
    }
    public function enableMockServer() : void
    {
        $this->client->enableMockServer();
    }
    public function getRateService() : \DhlVendor\Octolize\DhlExpress\RestApi\Services\RateService
    {
        return new \DhlVendor\Octolize\DhlExpress\RestApi\Services\RateService($this->client);
    }
    public function getShipmentService() : \DhlVendor\Octolize\DhlExpress\RestApi\Services\ShipmentService
    {
        return new \DhlVendor\Octolize\DhlExpress\RestApi\Services\ShipmentService($this->client);
    }
    public function getClient() : \DhlVendor\Octolize\DhlExpress\RestApi\Client
    {
        return $this->client;
    }
}
