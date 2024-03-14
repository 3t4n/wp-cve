<?php

namespace Fab\Wordpress\Customizer;

!defined( 'WPINC ' ) or die;

/**
 * Register all actions
 *
 * @package    Fab
 * @subpackage Fab\Includes\Wordpress
 */

class Setting extends Customizer {

    /**
     * Build
     * @return  void
     */
    public function build($wp_customize){
        $wp_customize->add_setting($this->ID, $this->args);
    }

}