<?php

namespace Fab\Feature;

!defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Backend extends Feature {

    /**
     * Feature construect
     * @return void
     * @var    object   $plugin     Feature configuration
     * @pattern prototype
     */
    public function __construct($plugin){
        $this->key = 'core_backend';
        $this->name = 'Backend';
        $this->description = 'Handles plugin backend core function';
    }

}