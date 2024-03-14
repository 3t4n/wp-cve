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

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class Filter
{
    /**
     * Filter Instance
     * 
     * @var  Filter
     */
    public static $instance;
    
    /**
     * The default filter
     * 
     * @var  string
     */
    const defaultFilter = 'sanitize_text_field';
    
	public static function getInstance()
	{
		if (!is_object(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
    }
    
    /**
     * Filters the value based on given filter
     * 
     * @return  string
     */
    public function clean($value, $filter = self::defaultFilter)
    {
        switch (strtolower($filter)) {
            case 'textarea':
                $filteredValue = $this->filterTextarea($value);
                break;
            case 'php':
                $filteredValue = $this->filterPHP($value);
                break;
            case 'raw':
                $filteredValue = $this->filterRaw($value);
                break;
            case 'css':
                $filteredValue = $this->filterCSS($value);
                break;
            case 'html':
                $filteredValue = $this->filterHTML($value);
                break;
            default:
                if (function_exists($filter))
                {
                    $filteredValue = $filter($value);
                }
                break;
        }

       return $filteredValue;
    }

    /**
     * Filters textarea field
     * 
     * @param   string  $fieldValue
     * 
     * @return  string
     */
    private function filterTextarea($fieldValue)
    {
        if (!is_string($fieldValue))
        {
            return;
        }
        
        $fieldValue = stripslashes($fieldValue);

        $fieldValue = wp_check_invalid_utf8($fieldValue, true);
        $fieldValue = htmlentities($fieldValue);

        $fieldValue = addslashes($fieldValue);

        return $fieldValue;
    }

    /**
     * Filters PHP Code
     * 
     * @param   string  $fieldValue
     * 
     * @return  string
     */
    private function filterPHP($fieldValue)
    {
        if (!is_string($fieldValue))
        {
            return;
        }

		// Remove Zero Width spaces / (non-)joiners
		$fieldValue = str_replace(
			[
				"\xE2\x80\x8B",
				"\xE2\x80\x8C",
				"\xE2\x80\x8D",
			],
			'',
			$fieldValue
        );
        
        return $fieldValue;
    }

    /**
     * Filters Raw
     * 
     * @param   string  $fieldValue
     * 
     * @return  string
     */
    private function filterRaw($fieldValue)
    {
        return $fieldValue;
    }

    /**
     * Filters CSS Code
     * 
     * @param   string  $fieldValue
     * 
     * @return  string
     */
    private function filterCSS($fieldValue)
    {
        if (!is_string($fieldValue))
        {
            return;
        }
        
        return wp_strip_all_tags($fieldValue);
    }

    /**
     * Strips all HTML tags
     * 
     * @param   string  $fieldValue
     * 
     * @return  string
     */
    private function filterHTML($fieldValue)
    {
        if (is_array($fieldValue))
        {
            $fieldValue = implode(',', $fieldValue);
        }
        
        return wp_strip_all_tags($fieldValue);
    }
}