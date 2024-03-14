<?php

namespace Baqend\WordPress\Model;

/**
 * Class LatestComparison created on 2018-07-02.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Model
 */
class LatestComparison {

    /**
     * @var string
     */
    private $id;

    /**
     * @var boolean
     */
    private $speedKit;

    /**
     * @var array
     */
    private $fields;

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isSpeedKit() {
        return $this->speedKit;
    }

    /**
     * @param bool $speedKit
     */
    public function setSpeedKit( $speedKit ) {
        $this->speedKit = $speedKit;
    }

    /**
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields( array $fields ) {
        $this->fields = $fields;
    }
}
