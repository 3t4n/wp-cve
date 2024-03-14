<?php

namespace Baqend\SDK\Model\Config;

/**
 * Class Revision created on 24.01.2018.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model\Config
 */
class Revision extends Subconfig
{

    /**
     * @return string
     */
    public function getVersion() {
        return $this->get('[version]');
    }

    /**
     * @param string $version
     */
    public function setVersion($version) {
        $this->set('[version]', $version);
    }
}
