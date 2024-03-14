<?php

namespace Baqend\SDK\Model;

/**
 * Class Device created on 21.12.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class Device extends Entity
{

    /**
     * @var string
     */
    private $deviceOs;

    /**
     * Device constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->setDeviceOs('');
    }

    /**
     * Returns the device's operating system.
     *
     * @return string
     */
    final public function getDeviceOs() {
        return $this->deviceOs;
    }

    /**
     * Sets the device's operating system.
     *
     * @param string $deviceOs
     * @return static
     */
    final public function setDeviceOs($deviceOs) {
        $this->deviceOs = (string) $deviceOs;
        return $this;
    }
}
