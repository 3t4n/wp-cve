<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Numerology\Service\Chaldean;

use Prokerala\Api\Astrology\Transformer;
use Prokerala\Api\Numerology\Result\Chaldean\LifePath;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Traits\ClientAwareTrait;
final class LifePathNumber
{
    use ClientAwareTrait;
    /**
     * @var string
     */
    protected $slug = '/numerology/chaldean/life-path-number';
    /** @var Transformer<LifePath> */
    private $transformer;
    /**
     * @param Client $client Api client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
        $this->transformer = new Transformer(LifePath::class);
    }
    /**
     * Fetch result from API.
     *
     *@throws RateLimitExceededException
     **
     * @throws QuotaExceededException
     */
    public function process(\DateTimeImmutable $datetime) : LifePath
    {
        $parameters = ['datetime' => $datetime->format('c')];
        $apiResponse = $this->apiClient->process($this->slug, $parameters);
        \assert($apiResponse instanceof \stdClass);
        return $this->transformer->transform($apiResponse->data);
    }
}
