<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Western\Service\PlanetPositions;

use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Transformer;
use Prokerala\Api\Astrology\Western\Result\PlanetPositions\SolarReturnChart as SolarReturnChartResult;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Traits\ClientAwareTrait;
final class SolarReturnChart
{
    use ClientAwareTrait;
    /**
     * @var string
     */
    protected $slug = '/astrology/solar-return-planet-position';
    /** @var Transformer<SolarReturnChartResult> */
    private $transformer;
    /**
     * @param Client $client Api client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
        $this->transformer = new Transformer(SolarReturnChartResult::class);
    }
    /**
     * Fetch result from API.
     *
     * @throws RateLimitExceededException
     * @throws QuotaExceededException
     */
    public function process(Location $location, \DateTimeImmutable $datetime, Location $transitLocation, int $solarYear, string $houseSystem, string $orb, bool $birthTimeUnknown, string $rectificationChart) : SolarReturnChartResult
    {
        $parameters = ['profile[datetime]' => $datetime->format('c'), 'profile[coordinates]' => $location->getCoordinates(), 'profile[birth_time_unknown]' => $birthTimeUnknown, 'solar_return_year' => $solarYear, 'current_coordinates' => $transitLocation->getCoordinates(), 'house_system' => $houseSystem, 'orb' => $orb, 'birth_time_rectification' => $rectificationChart];
        $apiResponse = $this->apiClient->process($this->slug, $parameters);
        \assert($apiResponse instanceof \stdClass);
        return $this->transformer->transform($apiResponse->data);
    }
}
