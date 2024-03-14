<?php

/**
 * User: Damian Zamojski (br33f)
 * Date: 22.06.2021
 * Time: 13:42
 */
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Exception\HydrationException;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Psr\Http\Message\ResponseInterface;
interface HydratableInterface
{
    /**
     * Method hydrates DTO with data from blueprint
     * @param ResponseInterface|array $blueprint
     * @throws HydrationException
     */
    public function hydrate($blueprint);
}
