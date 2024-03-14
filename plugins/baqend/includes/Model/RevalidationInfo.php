<?php

namespace Baqend\WordPress\Model;

use Baqend\SDK\Model\AssetFilter;

/**
 * Class RevalidationInfo created on 2019-10-22.
 *
 * @author Kevin Twesten
 * @package Baqend\WordPress\Model
 */
class RevalidationInfo {

    /**
     * @var array
     */
    private $filters;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @return array
     */
    public function getFilters() {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters( array $filters ) {
        $this->filters = $filters;
    }

    /**
     * @return int
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp( $timestamp ) {
        $this->timestamp = $timestamp;
    }

    /**
     * @param AssetFilter $filter
     * @param int $timestamp
     * @return bool
     */
    public function outdated( $filter, $timestamp ) {
        if ( !isset( $this->filters ) || !isset( $this->timestamp ) ) {
            return true;
        }

        $matching_filters = array_filter( $this->filters, function( $saved_filter ) use ( $filter ) {
            // Use of '==' is intended to compare both objects
            return $filter == $saved_filter;
        });

        // The filter was not part of the last revalidation request.
        if ( sizeof( $matching_filters ) < 1 ) {
            return true;
        }

        // Ensure that the filter was not executed within the last 5 seconds.
        return ( $timestamp - $this->timestamp ) > 5;
    }
}
