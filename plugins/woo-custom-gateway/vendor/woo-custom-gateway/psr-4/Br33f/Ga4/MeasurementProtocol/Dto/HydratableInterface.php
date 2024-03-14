<?php
/**
 * User: Damian Zamojski (br33f)
 * Date: 22.06.2021
 * Time: 13:42
 */

namespace RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto;

use RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Exception\HydrationException;
use RichardMuvirimi\WooCustomGateway\Vendor\Psr\Http\Message\ResponseInterface;

interface HydratableInterface
{
    /**
     * Method hydrates DTO with data from blueprint
     * @param ResponseInterface|array $blueprint
     * @throws HydrationException
     */
    public function hydrate($blueprint);
}