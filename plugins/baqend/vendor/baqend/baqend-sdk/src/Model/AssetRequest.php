<?php

namespace Baqend\SDK\Model;

/**
 * Class AssetRequest created on 31.05.21.
 *
 * @author  Kevin Twesten
 * @package Baqend\SDK\Model
 */
class AssetRequest implements \JsonSerializable
{

    /**
     * @var AssetFilter|null
     */
    private $filter;

    /**
     * @var string
     */
    private $triggeredBy;

    /**
     * Model constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        $this->setFilter(isset($data['filter']) ? $data['filter'] : null);
        $this->setTriggeredBy(isset($data['triggeredBy']) ? $data['triggeredBy'] : 'unknown');
    }

    /**
     * @return AssetFilter
     */
    public function getFilter() {
        return $this->filter;
    }

    /**
     * @param AssetFilter|null $filter
     */
    public function setFilter($filter) {
        $this->filter = $filter;
    }

    /**
     * @return string
     */
    public function getTriggeredBy() {
        return $this->triggeredBy;
    }

    /**
     * @param string $triggeredBy
     */
    public function setTriggeredBy($triggeredBy) {
        $this->triggeredBy = $triggeredBy;
    }

    public function jsonSerialize() {
        $response = [];
        if ($this->filter) {
            $response['filter'] = $this->filter;
        }
        if ($this->triggeredBy) {
            $response['triggeredBy'] = $this->triggeredBy;
        }

        return $response;
    }
}
