<?php

declare (strict_types=1);
namespace Prokerala\Api\Astrology\Western\Service\PlanetPositions;

use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Transformer;
use Prokerala\Api\Astrology\Western\Result\PlanetPositions\NatalChart as NatalChartResult;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Traits\ClientAwareTrait;
final class NatalChart
{
    use ClientAwareTrait;
    /**
     * @var string
     */
    protected $slug = '/astrology/natal-planet-position';
    /** @var Transformer<NatalChartResult> */
    private $transformer;
    /**
     * @param Client $client Api client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
        $this->transformer = new Transformer(NatalChartResult::class);
    }
    /**
     * Fetch result from API.
     *
     * @throws RateLimitExceededException
     * @throws QuotaExceededException
     */
    public function process(Location $location, \DateTimeImmutable $datetime, string $houseSystem, string $orb, bool $birthTimeUnknown, string $rectificationChart) : NatalChartResult
    {
        $parameters = ['profile[datetime]' => $datetime->format('c'), 'profile[coordinates]' => $location->getCoordinates(), 'profile[birth_time_unknown]' => $birthTimeUnknown, 'house_system' => $houseSystem, 'orb' => $orb, 'birth_time_rectification' => $rectificationChart];
        $apiResponse = $this->apiClient->process($this->slug, $parameters);
        \assert($apiResponse instanceof \stdClass);
        return $this->transformer->transform($apiResponse->data);
    }
}
