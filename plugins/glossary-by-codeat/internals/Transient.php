<?php

/**
 * Plugin name
 *
 * @package   Plugin_name
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Internals;

use  Glossary\Engine ;
/**
 * Transient used by the plugin
 */
class Transient extends Engine\Base
{
    /**
     * Initialize the class.
     *
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        return true;
    }

}