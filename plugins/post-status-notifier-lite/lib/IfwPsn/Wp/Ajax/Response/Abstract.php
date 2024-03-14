<?php
/**
 * ifeelweb.de Wordpress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Abstract.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 */
abstract class IfwPsn_Wp_Ajax_Response_Abstract
{
    /**
     * Output response header
     */
    abstract public function header();

    /**
     * Outputs the response data
     */
    abstract public function output();
}
