<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Service;

use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Result\Panchang\DishaShool as DishaShoolResult;
use Prokerala\Api\Astrology\Traits\Service\AyanamsaAwareTrait;
use Prokerala\Api\Astrology\Traits\Service\TimeZoneAwareTrait;
use Prokerala\Api\Astrology\Transformer;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Traits\ClientAwareTrait;
final class DishaShool
{
    use AyanamsaAwareTrait;
    use ClientAwareTrait;
    /** @use TimeZoneAwareTrait<DishaShoolResult> */
    use TimeZoneAwareTrait;
    /**
     * @var string
     */
    protected $slug = '/astrology/disha-shool';
    /** @var Transformer<DishaShoolResult> */
    private $transformer;
    /**
     * @param Client $client Api client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
        $this->transformer = new Transformer(DishaShoolResult::class);
        $this->addDateTimeTransformer($this->transformer);
    }
    /**
     * Fetch result from API.
     *
     * @param Location           $location Location details
     * @param \DateTimeInterface $datetime Date and time
     *
     * @throws QuotaExceededException
     * @throws RateLimitExceededException
     **
     */
    public function process(Location $location, \DateTimeInterface $datetime, string $la = 'en') : DishaShoolResult
    {
        $parameters = ['datetime' => $datetime->format('c'), 'coordinates' => $location->getCoordinates(), 'ayanamsa' => $this->getAyanamsa(), 'la' => $la];
        $apiResponse = $this->apiClient->process($this->slug, $parameters);
        \assert($apiResponse instanceof \stdClass);
        return $this->transformer->transform($apiResponse->data);
    }
}
