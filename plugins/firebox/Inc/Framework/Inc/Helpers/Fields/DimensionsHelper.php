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

namespace FPFramework\Helpers\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FPFramework\Base\Functions;

class DimensionsHelper
{
	/**
	 * Parses the Dimensions field data
	 * 
	 * @param   array     $dimensions	  Data array
	 * @param   string    $prefix 		  The prefix
	 * @param   boolean   $important 	  Whether to add !important
	 * 
	 * @return  string
	 */
	public static function parseDimensionsData($dimensions, $prefix, $important = false)
	{
		if (!is_string($prefix))
		{
			return [];
		}

		$dimensions = (array) $dimensions;

		$unit = isset($dimensions['unit']) ? $dimensions['unit'] : 'px';

		$suffix = $important ? ' !important' : '';
		
		// remove unit and linked keys
		unset($dimensions['unit']);
		unset($dimensions['linked']);

		// Auto unit
		if ($unit === 'auto')
		{
			$data = [];

			foreach ($dimensions as $key => $value)
			{
				$key_suffix = $key !== 'value' ? $key : '';
				$data[$prefix . $key_suffix] = 'auto' . $suffix;
			}

			return $data;
		}

		if (empty($dimensions))
		{
			return [];
		}

		$data = [];

		// Shorthands
		if (in_array($prefix, ['padding', 'margin']))
		{
			// Concat to 1 line, all numbers are equal
			if (count($dimensions) === 4 && Functions::allArrayValuesEqual($dimensions))
			{
				$value = reset($dimensions);
				
				if ($value !== '')
				{
					return [$prefix => intval($value) !== 0 ? $value . $unit . $suffix : 0];
				}
			}

			if (Functions::getTotalNonEmptyArrayValues($dimensions) === 4)
			{
				// Concat to 1 line, Top == Bottom & Left == Right
				if (isset($dimensions['top']) && isset($dimensions['bottom']) && $dimensions['top'] === $dimensions['bottom'] &&
					isset($dimensions['left']) && isset($dimensions['right']) && $dimensions['left'] === $dimensions['right'])
				{
					$dimensionTop = $dimensions['top'] !== '' ? $dimensions['top'] : 0;
					$dimensionLeft = $dimensions['left'] !== '' ? $dimensions['left'] : 0;
					
					return [$prefix => $dimensionTop . $unit . $suffix . ' ' . $dimensionLeft . $unit . $suffix];
				}
				// All values are different
				else
				{
					return [$prefix => implode(' ', array_map(function($value) use ($unit, $suffix) {
						return intval($value) !== 0 ? $value . $unit . $suffix : 0;
					},array_values($dimensions)))];
				}
			}

			// Handle individual cases (specific dimension keys have values, i.e. padding top, margin left, etc...)
			$css = [];

			foreach (['top', 'right', 'bottom', 'left'] as $pos)
			{
				$item_value = isset($dimensions[$pos]) && $dimensions[$pos] !== '' ? $dimensions[$pos] : '';

				if ($item_value === '')
				{
					continue;
				}
				
				$value = $item_value !== '' ? $item_value : '';

				$css[$prefix . '-' . $pos] = $value . (!in_array($value, [0, '0']) ? $unit : '') . $suffix;
			}

			return $css;
		}
		
		foreach ($dimensions as $key => $value)
		{
			if (!is_string($value) && !is_int($value))
			{
				continue;
			}
			
			if (empty($value) && $value != '0')
			{
				continue;
			}

			$data[$prefix] = $value . (!in_array($value, [0, '0']) ? $unit : '') . $suffix;
		}

		return $data;
	}

	/**
	 * Parses the Dimensions field data as Border Radius
	 * 
	 * @param   array     $dimensions	  Data array
	 * @param   boolean   $important 	  Whether to add !important
	 * 
	 * @return  string
	 */
	public static function parseDimensionsBorderRadiusData($dimensions, $important = false)
	{
		if (empty($dimensions))
		{
			return [];
		}
		
		$dimensions = (array) $dimensions;

		$unit = isset($dimensions['unit']) ? $dimensions['unit'] : 'px';

		// remove unit and linked keys
		unset($dimensions['unit']);
		unset($dimensions['linked']);

		$suffix = is_bool($important) && $important ? ' !important' : '';

		// Concat to 1 line, all numbers are equal
		if (count($dimensions) === 4 && Functions::allArrayValuesEqual($dimensions))
		{
            $first_element = reset($dimensions);
			return [
				'border-radius' => intval($first_element) !== 0 ? $first_element . $unit . $suffix : 0
			];
		}

		if (Functions::getTotalNonEmptyArrayValues($dimensions) === 4)
		{
			// Concat to 1 line, Top Left = Bottom Right & Top Right = Bottom Left
			if (isset($dimensions['top_left']) && isset($dimensions['bottom_right']) && $dimensions['top_left'] === $dimensions['bottom_right'] &&
				isset($dimensions['top_right']) && isset($dimensions['bottom_left']) && $dimensions['top_right'] === $dimensions['bottom_left'])
			{
				$top_left_value = $dimensions['top_left'];
				$top_left = intval($top_left_value) !== 0 ? $top_left_value . $unit . $suffix : 0;
				
				$top_right_value = $dimensions['top_right'];
				$top_right = intval($top_right_value) !== 0 ? $top_right_value . $unit . $suffix : 0;

				return ['border-radius' => $top_left . ' ' . $top_right];
			}
			// All values are different
			else
			{
				return ['border-radius' => implode(' ', array_map(function($value) use ($unit, $suffix) {
					return intval($value) !== 0 ? $value . $unit . $suffix : 0;
				}, array_values($dimensions)))];
			}
		}

		// Handle individual cases (specific border radius keys have values, i.e. border top right, border bottom left, etc...)
		$data = [];

		foreach (['top_left', 'top_right', 'bottom_right', 'bottom_left'] as $pos)
		{
			$item_value = isset($dimensions[$pos]) && $dimensions[$pos] !== '' ? $dimensions[$pos] : '';

			if ($item_value === '' || is_array($item_value))
			{
				continue;
			}
			
			$value = $item_value !== '' ? $item_value : '';

			$data['border-' . str_replace('_', '-', $pos) . '-radius'] = $value . (!in_array($value, [0, '0']) ? $unit : '') . $suffix;
		}

		return $data;
	}
}