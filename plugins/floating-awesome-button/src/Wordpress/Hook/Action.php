<?php

namespace Fab\Wordpress\Hook;

!defined( 'WPINC ' ) or die;

/**
 * Register all actions
 *
 * @package    Fab
 * @subpackage Fab\Includes\Wordpress
 */

class Action extends Hook {

    /**
     * Run hook
     * @return  void
     */
    public function run(){
        add_action(
            $this->hook,
            array( $this->component, $this->callback ),
            $this->priority,
            $this->accepted_args
        );
    }

}