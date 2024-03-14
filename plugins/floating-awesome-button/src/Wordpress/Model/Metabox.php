<?php

namespace Fab\Wordpress\Model;

!defined( 'WPINC ' ) or die;

/**
 * Abstract class for wordpress model
 *
 * @package    Fab
 * @subpackage Fab\Includes\Wordpress
 */

abstract class Metabox {

    /**
     * sanitize parameters
     * @return  void
     */
    abstract function sanitize();

    /**
     * save metabox data
     * @return  void
     */
    abstract function save();

}