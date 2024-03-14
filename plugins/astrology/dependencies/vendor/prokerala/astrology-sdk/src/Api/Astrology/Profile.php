<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology;

final class Profile
{
    /**
     * @var \Prokerala\Api\Astrology\Location
     */
    private $location;
    /**
     * @var \DateTimeInterface
     */
    private $datetime;
    public function __construct(\Prokerala\Api\Astrology\Location $location, \DateTimeInterface $datetime)
    {
        $this->location = $location;
        $this->datetime = $datetime;
    }
    /**
     * Get birth time.
     */
    public function getDateTime() : \DateTimeInterface
    {
        return $this->datetime;
    }
    /**
     * Get birth location.
     */
    public function getLocation() : \Prokerala\Api\Astrology\Location
    {
        return $this->location;
    }
}
