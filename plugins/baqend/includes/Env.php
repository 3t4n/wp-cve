<?php

namespace Baqend\WordPress;

/**
 * Class Env created on 2018-03-14.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress
 */
class Env {

    /**
     * Returns the current date time formatted.
     *
     * @return string
     */
    public function getFormattedDateTime() {
        return date( 'Y-m-d H:i:s' );
    }

    /**
     * Get the host of the blog's homepage.
     *
     * @return string host (URL minus the protocol)
     */
    public function host() {
        return untrailingslashit( strip_protocol( $this->origin() ) );
    }

    /**
     * Get the scheme of the blog's homepage.
     *
     * @return string
     */
    public function scheme() {
        $pattern = '/:\/\/.*/';

        return preg_replace( $pattern, '', $this->origin() );
    }

    /**
     * @return string
     */
    public function origin() {
        return untrailingslashit( home_url() );
    }
}
