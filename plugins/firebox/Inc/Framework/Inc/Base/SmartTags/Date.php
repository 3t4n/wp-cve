<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\SmartTags\SmartTag;

class Date extends SmartTag
{
    /**
     * Constructor
     *
     * @param object    $factory    The framework factory object
     * @param array     $options    Assignment configuration options
     */
    public function __construct($factory = null, $options = null)
    {
        parent::__construct($factory, $options);

		$this->tz = wp_timezone();
        $this->date = $this->factory->getDate()->setTimezone($this->tz);
    }

    /**
     * Returns the current date with timezone applied
     * 
     * @return  string
     */
    public function getDate()
    {
        $format = $this->parsedOptions->get('format', 'Y-m-d H:i:s');

        return $this->date->format($format);
    }
}