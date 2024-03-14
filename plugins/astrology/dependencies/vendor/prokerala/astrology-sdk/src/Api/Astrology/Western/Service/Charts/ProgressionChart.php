<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Western\Service\Charts;

use Prokerala\Api\Astrology\Location;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Traits\ClientAwareTrait;
final class ProgressionChart
{
    use ClientAwareTrait;
    /**
     * @var string
     */
    protected $slug = '/astrology/progression-chart';
    /**
     * @param Client $client Api client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
    }
    /**
     * Fetch result from API.
     *
     * @throws RateLimitExceededException
     * @throws QuotaExceededException
     */
    public function process(Location $location, \DateTimeImmutable $datetime, Location $transitLocation, int $progressionYear, string $houseSystem, string $orb, bool $birthTimeUnknown, string $rectificationChart, string $aspectFilter) : string
    {
        $parameters = ['profile[datetime]' => $datetime->format('c'), 'profile[coordinates]' => $location->getCoordinates(), 'profile[birth_time_unknown]' => $birthTimeUnknown, 'progression_year' => $progressionYear, 'current_coordinates' => $transitLocation->getCoordinates(), 'house_system' => $houseSystem, 'orb' => $orb, 'aspect_filter' => $aspectFilter, 'birth_time_rectification' => $rectificationChart];
        return $this->apiClient->process($this->slug, $parameters);
    }
}
