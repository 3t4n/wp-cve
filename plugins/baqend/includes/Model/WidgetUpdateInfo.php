<?php

namespace Baqend\WordPress\Model;

/**
 * Class WidgetUpdateInfo created on 2020-03-27.
 *
 * @author Kevin Twesten
 * @package Baqend\WordPress\Model
 */
class WidgetUpdateInfo {

    /**
     * @var string
     */
    private $widget_id_base;

    /**
     * @var bool
     */
    private $waiting;

    /**
     * @var int
     */
    private $last_revalidation_timestamp;

    /**
     * WidgetUpdateInfo constructor.
     */
    public function __construct() {
        $this->waiting = false;
    }

    /**
     * @return string
     */
    public function getWidgetIdBase() {
        return $this->widget_id_base;
    }

    /**
     * @param string $widget_id_base
     */
    public function setWidgetIdBase( $widget_id_base ) {
        $this->widget_id_base = $widget_id_base;
    }

    /**
     * @return bool
     */
    public function isWaiting() {
        return $this->waiting;
    }

    /**
     * @param bool $waiting
     */
    public function setWaiting( $waiting ) {
        $this->waiting = $waiting;
    }

    /**
     * @return int
     */
    public function getLastRevalidationTimestamp() {
        return $this->last_revalidation_timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setLastRevalidationTimestamp( $timestamp ) {
        $this->last_revalidation_timestamp = $timestamp;
    }

    /**
     * @return bool
     */
    public function revalidation_allowed() {
        if ( is_null( $this->last_revalidation_timestamp ) || $this->last_revalidation_timestamp === 'null' ) {
            return true;
        }

        // Check if the last revalidation is older than 10 minutes (600 seconds).
        $now = current_time( 'timestamp' );
        return $now - $this->last_revalidation_timestamp >= 600;
    }

    public function mark_as_revalidated() {
        $now = current_time( 'timestamp' );
        $this->setLastRevalidationTimestamp( $now );
        $this->setWaiting( false );
    }
}
