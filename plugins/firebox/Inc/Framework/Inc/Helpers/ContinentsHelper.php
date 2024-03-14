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

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class ContinentsHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Continents';

		parent::__construct($provider);
	}

	/**
	 * Parses given data to a key,value array
	 * 
	 * @param   array  $items
	 * 
	 * @return  array
	 */
	public static function parseData($items)
	{
		$items = (array) $items;
		$items = array_filter($items);

		if (empty($items))
		{
			return [];
		}

		$data = [];

		foreach ($items as $key => $value)
		{
			$data[] = [
				'id' => $key,
				'title' => $value
			];
		}
		
		return $data;
	}

    /**
     * Return a continent's code from it's name
     *
     * @param   string  $cont
	 * 
     * @return  mixed
     */
    public static function getCode($cont)
    {
		if (!is_string($cont) || empty(trim($cont)))
		{
			return null;
		}
		
		$cont = \ucwords(strtolower(trim($cont)));
		
        foreach (self::getContinents() as $key => $value)
        {
            if (strpos($value, $cont) !== false)
            {
                return $key;
            }
		}
		
        return null;
    }

	/**
	 * Returns all continents
	 * 
	 * @return  array
	 */
	public static function getContinents()
	{
		return [
			'af' => fpframework()->_('FPF_CONTINENT_AF'),
			'as' => fpframework()->_('FPF_CONTINENT_AS'),
			'eu' => fpframework()->_('FPF_CONTINENT_EU'),
			'na' => fpframework()->_('FPF_CONTINENT_NA'),
			'sa' => fpframework()->_('FPF_CONTINENT_SA'),
			'oc' => fpframework()->_('FPF_CONTINENT_OC'),
			'an' => fpframework()->_('FPF_CONTINENT_AN'),
		];
	}
}