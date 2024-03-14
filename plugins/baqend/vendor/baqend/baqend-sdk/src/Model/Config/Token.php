<?php

namespace Baqend\SDK\Model\Config;

/**
 * Class Token created on 24.01.2018.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model\Config
 */
class Token extends Subconfig
{

    /**
     * @return int
     */
    public function getLongLifetime() {
        return $this->get('[longLifetime]');
    }

    /**
     * @param int $longLifetime
     */
    public function setLongLifetime($longLifetime) {
        $this->set('[longLifetime]', $longLifetime);
    }

    /**
     * @return int
     */
    public function getShortLifetime() {
        return $this->get('[shortLifetime]');
    }

    /**
     * @param int $shortLifetime
     */
    public function setShortLifetime($shortLifetime) {
        $this->set('[shortLifetime]', $shortLifetime);
    }

    /**
     * @return int
     */
    public function getAdminLongLifetime() {
        return $this->get('[adminLongLifetime]');
    }

    /**
     * @param int $adminLongLifetime
     */
    public function setAdminLongLifetime($adminLongLifetime) {
        $this->set('[adminLongLifetime]', $adminLongLifetime);
    }

    /**
     * @return int
     */
    public function getVerificationLifetime() {
        return $this->get('[verificationLifetime]');
    }

    /**
     * @param int $verificationLifetime
     */
    public function setVerificationLifetime($verificationLifetime) {
        $this->set('[verificationLifetime]', $verificationLifetime);
    }

    /**
     * @return int
     */
    public function getResourceLifetime() {
        return $this->get('[resourceLifetime]');
    }

    /**
     * @param int $resourceLifetime
     */
    public function setResourceLifetime($resourceLifetime) {
        $this->set('[resourceLifetime]', $resourceLifetime);
    }
}
