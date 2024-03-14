<?php

namespace Baqend\SDK\Model\Config;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class Config created on 22.01.2018.
 *
 * @author Florian Bücklers
 * @package Baqend\SDK\Model\Config
 */
class Config
{

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var Revision
     */
    private $revision;

    /**
     * @var Token
     */
    private $token;

    /**
     * @var SpeedKit
     */
    private $speedKit;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Config constructor.
     */
    public function __construct() {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->revision = new Revision($this, '[revision]');
        $this->speedKit = new SpeedKit($this, '[speedKit]');
        $this->token = new Token($this, '[token]');
    }

    /**
     * Checks if an configuration property has an configured value.
     *
     * @param string $property The configuration property name.
     * @return bool If the configuration has a configured value.
     */
    public function has($property) {
        return $this->propertyAccessor->isReadable($this->data, $property)
            && $this->propertyAccessor->getValue($this->data, $property) !== null;
    }

    /**
     * Checks if the configured property value is true.
     *
     * @param string $property The configuration property name.
     * @return bool If the configuration property is strict equals true.
     */
    public function is($property) {
        return $this->get($property) === true;
    }

    /**
     * Gets the configured property value.
     *
     * @param string $property The configuration property name.
     * @param string $default If the configuration does not contains the property this value will be returned instead.
     * @return mixed The configured value.
     */
    public function get($property, $default = null) {
        return $this->has($property) ? $this->propertyAccessor->getValue($this->data, $property) : $default;
    }

    /**
     * Sets the configured property value.
     *
     * @param string $property The configuration property name.
     * @param mixed $value The new configuration property value.
     */
    public function set($property, $value) {
        try {
            $this->propertyAccessor->setValue($this->data, $property, $value);
        } catch (\TypeError $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return Revision
     */
    public function getRevision() {
        return $this->revision;
    }

    /**
     * @return Token
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @return SpeedKit
     */
    public function getSpeedKit() {
        return $this->speedKit;
    }

    /**
     * Returns the currently configured allowed domains.
     *
     * @return string[] A ist of domains which are allowed to use speedkit
     */
    public function getWhitelistedDomains() {
        return $this->get('[whitelistedDomains]', []);
    }

    /**
     * Set the allowed domains which are allowed to include speed kit for this app.
     *
     * @param string[] $whitelistedDomains The allowed list of domains which are allowed to include speedkit.
     * An empty list allows any domain to use speed kit with this app
     */
    public function setWhitelistedDomains(array $whitelistedDomains) {
        $this->set('[whitelistedDomains]', $whitelistedDomains);
    }

    /**
     * @return bool
     */
    public function isAppDeactivated() {
        return $this->is('[appDeactivated]');
    }

    /**
     * @param bool $appDeactivated
     */
    public function setAppDeactivated($appDeactivated) {
        $this->set('[appDeactivated]', $appDeactivated);
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data) {
        $this->data = $data;
    }
}
