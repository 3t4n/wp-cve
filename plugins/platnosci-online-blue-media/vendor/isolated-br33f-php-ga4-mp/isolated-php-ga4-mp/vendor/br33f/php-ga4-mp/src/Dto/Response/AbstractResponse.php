<?php

/**
 * User: Damian Zamojski (br33f)
 * Date: 22.06.2021
 * Time: 11:10
 */
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Response;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\HydratableInterface;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Exception\HydrationException;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Psr\Http\Message\ResponseInterface;
abstract class AbstractResponse implements HydratableInterface
{
    /**
     * AbstractResponse constructor.
     * @param ResponseInterface|null $blueprint
     * @throws HydrationException
     */
    public function __construct(ResponseInterface $blueprint = null)
    {
        if ($blueprint !== null) {
            $this->hydrate($blueprint);
        }
    }
}
