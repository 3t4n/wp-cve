<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Hooks extends Feature {

    /**
     * Feature construect
     *
     * @return void
     * @var    object   $plugin     Feature configuration
     * @pattern prototype
     */
    public function __construct( $plugin ) {
        $this->WP          = $plugin->getWP();
        $this->key         = 'core_hooks';
        $this->name        = 'Hooks';
        $this->description = 'Handles plugin hooks management';
    }

    /**
     * Sanitize input
     */
    public function sanitize() {
        /** Grab Data */
        $this->params = $_POST;
        $this->params = $this->params['fab_hooks'];

        /** Sanitize Text Field */
        $this->params = (object) $this->WP->sanitizeTextField( $this->params );
    }

    /**
     * Transform data before save
     */
    public function transform() {
        /** Validate active/inactive asset */
        $plugin   = \Fab\Plugin::getInstance();
        $this->params = (object) $plugin->getHelper()->transformBooleanValue( (array) $this->params );

        return $this->params;
    }

}