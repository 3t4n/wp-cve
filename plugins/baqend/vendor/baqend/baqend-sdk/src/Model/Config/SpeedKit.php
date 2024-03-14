<?php

namespace Baqend\SDK\Model\Config;

/**
 * Class SpeedKit created on 20.01.2018.
 *
 * @author  Florian Bücklers
 * @package Baqend\SDK\Model\Config
 */
class SpeedKit extends Subconfig
{

    /**
     * Gets the HTTP auth user for the configured url, this allows to use
     * Speed Kit on staging environments which are protected by HTTP auth.
     *
     * @return string
     */
    public function getHttpAuthUser() {
        return $this->get('[httpAuthUser]');
    }

    /**
     * Sets the HTTP auth user for the configured url, this allows to use Speed Kit on staging environments which are
     * protected by HTTP auth.
     *
     * @param string $httpAuthUser
     */
    public function setHttpAuthUser($httpAuthUser) {
        $this->set('[httpAuthUser]', $httpAuthUser);
    }

    /**
     * Gets the HTTP auth password for the configured url, this allows to use Speed Kit on
     * staging environments which are protected by HTTP auth.
     *
     * @return string
     */
    public function getHttpAuthPass() {
        return $this->get('[httpAuthPass]');
    }

    /**
     * Sets the HTTP auth password for the configured url, this allows to use Speed Kit on staging environments
     * which are protected by HTTP auth.
     *
     * @param string $httpAuthPass
     */
    public function setHttpAuthPass($httpAuthPass) {
        $this->set('[httpAuthPass]', $httpAuthPass);
    }

    /**
     * Gets the HTTP auth url, this allows to use Speed Kit on staging environments which are
     * protected by HTTP auth.
     *
     * @return string
     */
    public function getHttpAuthURI() {
        return $this->get('[httpAuthURI]');
    }

    /**
     * Sets the http auth url, this allows to use Speed Kit on staging environments which are
     * protected by http auth
     * @param string $httpAuthURI
     */
    public function setHttpAuthURI($httpAuthURI) {
        $this->set('[httpAuthURI]', $httpAuthURI);
    }

    /**
     * Gets the http auth type for the configured url, this allows to use Speed Kit on staging environments which are
     * protected by http auth. Can be one of "basic", "digest".
     *
     * @return string
     */
    public function getHttpAuthType() {
        return $this->get('[httpAuthType]');
    }

    /**
     * Sets the http auth type for the configured url, this allows to use Speed Kit on staging environments which are
     * protected by http auth. Can be one of "basic", "digest".
     *
     * @param string $httpAuthType
     */
    public function setHttpAuthType($httpAuthType) {
        $this->set('[httpAuthType]', $httpAuthType);
    }

    /**
     * Returns the current activation status of Speed Kit.
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->get('[enabled]');
    }

    /**
     * Change the current activation status of Speed Kit.
     *
     * @param bool $enabled true to enable Speed Kit / false to disable Speed Kit immediately.
     */
    public function setEnabled($enabled) {
        $this->set('[enabled]', (bool) $enabled);
    }
}
