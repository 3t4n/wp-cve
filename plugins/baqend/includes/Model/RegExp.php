<?php

namespace Baqend\WordPress\Model;

/**
 * Class RegExp created on 2018-11-28.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Model
 */
class RegExp {

    /** @var string */
    private $source;

    /** @var string */
    private $flags;

    /**
     * RegExp constructor.
     *
     * @param string $source
     * @param string $flags
     */
    public function __construct( $source, $flags = '' ) {
        $this->source = $source;
        $this->flags  = $flags;
    }

    /**
     * Escapes all regular expressions.
     *
     * @param string $subject
     * @return string
     */
    public static function escape( $subject ) {
        return preg_replace( '/[[\]{}()*+?.,\\^$|#\s\/]/', '\\\\$0', $subject );
    }

    /**
     * @return string
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getFlags() {
        return $this->flags;
    }

    /**
     * @return string
     */
    public function __toString() {
        return '/' . $this->source . '/' . $this->flags;
    }
}
