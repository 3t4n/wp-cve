<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Traits\Result;

/**
 * @internal
 */
trait RawResponseTrait
{
    /**
     * @var \stdClass|null
     */
    private $apiResponse;
    public function setRawResponse(\stdClass $data) : void
    {
        $this->apiResponse = $data;
    }
    public function getRawResponse() : ?\stdClass
    {
        return $this->apiResponse;
    }
}
