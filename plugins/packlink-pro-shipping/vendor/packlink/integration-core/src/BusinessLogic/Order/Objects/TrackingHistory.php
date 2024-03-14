<?php

namespace Packlink\BusinessLogic\Order\Objects;

/**
 * Class TrackingHistory.
 *
 * @package Packlink\BusinessLogic\Order\Objects
 */
class TrackingHistory
{
    /**
     * Timestamp of tracking entry.
     *
     * @var int
     */
    private $timestamp;
    /**
     * Tracking description.
     *
     * @var string
     */
    private $description;
    /**
     * Tracking city.
     *
     * @var string
     */
    private $city;

    /**
     * Creates a new instance from the raw array of data.
     *
     * @param array $raw
     *
     * @return TrackingHistory
     */
    public static function fromArray(array $raw)
    {
        $result = new static();
        $result->setCity($raw['city']);
        $result->setDescription($raw['description']);
        $result->setTimestamp($raw['timestamp']);

        return $result;
    }

    /**
     * Returns timestamp.
     *
     * @return int Timestamp.
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Sets timestamp.
     *
     * @param int $timestamp Timestamp of entry.
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Returns description.
     *
     * @return string Description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets description.
     *
     * @param string $description Description.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns city.
     *
     * @return string City.
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets city.
     *
     * @param string $city City.
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
}
