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
use Prokerala\Api\Astrology\Western\Result\PlanetPositions\CompositeChart as CompositeChartResult;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Traits\ClientAwareTrait;
final class CompositeChart
{
    use ClientAwareTrait;
    /**
     * @var string
     */
    protected $slug = '/astrology/composite-planet-aspect';
    /** @var Transformer<CompositeChartResult> */
    private $transformer;
    /**
     * @param Client $client Api client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
        $this->transformer = new Transformer(CompositeChartResult::class);
    }
    /**
     * Fetch result from API.
     *
     * @throws RateLimitExceededException
     * @throws QuotaExceededException
     */
    public function process(Location $primaryBirthLocation, \DateTimeImmutable $primaryBirthTime, Location $secondaryBirthLocation, \DateTimeImmutable $secondaryBirthTime, Location $currentLocation, \DateTimeImmutable $transitDateTime, string $houseSystem, string $orb, bool $primaryBirthTimeUnknown, bool $secondaryBirthTimeUnknown, string $rectificationChart) : CompositeChartResult
    {
        $parameters = ['primary_profile[datetime]' => $primaryBirthTime->format('c'), 'primary_profile[coordinates]' => $primaryBirthLocation->getCoordinates(), 'primary_profile[birth_time_unknown]' => $primaryBirthTimeUnknown, 'secondary_profile[datetime]' => $secondaryBirthTime->format('c'), 'secondary_profile[coordinates]' => $secondaryBirthLocation->getCoordinates(), 'secondary_profile[birth_time_unknown]' => $secondaryBirthTimeUnknown, 'transit_datetime' => $transitDateTime->format('c'), 'current_coordinates' => $currentLocation->getCoordinates(), 'house_system' => $houseSystem, 'orb' => $orb, 'birth_time_rectification' => $rectificationChart];
        $apiResponse = $this->apiClient->process($this->slug, $parameters);
        \assert($apiResponse instanceof \stdClass);
        return $this->transformer->transform($apiResponse->data);
    }
}
