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

namespace FPFramework\Base\Factory;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FPFramework\Libs\Registry;

class Config
{
    private $data = [];

    public function __construct()
    {
        $this->setData();
    }

    private function setData()
    {
        $this->data = $this->getData();
    }

    private function getData()
    {
        $data = [
            'offset' => self::getTimezone()
        ];

        $data = new Registry($data);
        return $data;
    }

    /**
     * Get the timezone
     * 
     * @return  string
     */
    private function getTimezone() {
        $tzstring = $this->getTimezoneString();
        $offset   = $this->getGMTOffset();

        // empty timezone, check with offset
        if(empty($tzstring) && 0 != $offset && floor($offset) == $offset)
        {
            $offset_st = $offset > 0 ? "+$offset" : '-' . absint($offset);
            $tzstring  = 'Etc/GMT' . $offset_st;
        }
    
        // Issue with the timezone selected, set to 'UTC'
        if(empty($tzstring))
        {
            $tzstring = 'UTC';
        }

        return $tzstring; 
    }

    /**
     * Retrieves the timezone as string
     * 
     * @return  string
     */
    protected function getTimezoneString()
    {
        return get_option('timezone_string');
    }

    /**
     * Retrieves the GMT offset
     * 
     * @return  string
     */
    protected function getGMTOffset()
    {
        return get_option('gmt_offset');
    }

    public function get($key)
    {
        if (!$this->data->get($key))
        {
            return '';
        }

        return $this->data->get($key);
    }
}