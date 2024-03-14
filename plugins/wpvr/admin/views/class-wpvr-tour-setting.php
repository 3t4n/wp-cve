<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Abstract class for WPVR Setting 
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */


abstract class WPVR_Tour_setting {

    /**
     * @param mixed $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    abstract public function render_setting($postdata);

}