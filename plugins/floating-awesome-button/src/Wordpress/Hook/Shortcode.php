<?php

namespace Fab\Wordpress\Hook;

!defined( 'WPINC ' ) or die;

/**
 * Abstract class for hook
 *
 * @package    Fab
 * @subpackage Fab\Includes\Wordpress
 */

class Shortcode extends Hook {

    /**
     * Run hook
     * @return  void
     */
    public function run(){
        add_shortcode(
            $this->hook,
            array( $this->component, $this->callback )
        );
    }

}