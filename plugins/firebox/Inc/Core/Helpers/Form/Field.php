<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Helpers\Form;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Field
{
	/**
	 * Get Field Name given a field block.
	 * 
	 * @param   array   $block
	 * 
	 * @return  string
	 */
	public static function getFieldName($block)
	{
		if (isset($block['attrs']['fieldName']))
		{
			return $block['attrs']['fieldName'];
		}

		return str_replace('firebox/', '', $block['blockName']);
	}
	
	/**
	 * Get the field label.
	 * 
	 * @param   array   $block
	 * 
	 * @return  string
	 */
	public static function getFieldLabel($block)
	{
		$label = isset($block['attrs']['fieldLabel']) ? $block['attrs']['fieldLabel'] : '';

		if (empty($label))
		{
			$type = self::getFieldType($block['blockName']);

			switch ($type)
			{
				case 'text':
					$label = 'Text field';
					break;
				case 'textarea':
					$label = 'Textarea field';
					break;
				case 'email':
					$label = 'Email field';
					break;
				case 'hidden':
					$label = 'Hidden field';
					break;
				case 'dropdown':
					$label = 'Dropdown field';
					break;
			}
		}
		
		return $label;
	}

	/**
	 * Returns the field tpye given a field block name.
	 * 
	 * @param   string  $blockName
	 * 
	 * @return  string
	 */
	public static function getFieldType($blockName)
	{
		return str_replace('firebox/', '', $blockName);
	}

	/**
	 * Returns the field class instance.
	 * 
	 * @param   array   $params
	 * 
	 * @return  /FireBox/Core/Form/Fields/Field
	 */
	public static function getFieldClass($params)
	{
		$type = isset($params['type']) ? $params['type'] : '';
		if (!$type)
		{
			return;
		}
		
		$class = '\FireBox\Core\Form\Fields\Fields\\' . ucfirst($type);
		if (!class_exists($class))
		{
			return;
		}

		return new $class($params);
	}
	
    /**
     * Convert all applicable characters to HTML entities
     *
     * @param  string $input The input string.
     *
     * @return string
     */
    public static function escape($input)
    {
        if (!is_string($input))
        {
            return $input;
        }

        // Convert all HTML tags to HTML entities.
        $input = htmlspecialchars($input, ENT_NOQUOTES, 'UTF-8');

        // We do not need any Smart Tag replacements take place here, so we need to escape curly brackets too.
        $input = str_replace(['{', '}'], ['&#123;', '&#125;'], $input);

        // Respect newline characters, by converting them to <br> tags.
        $input = nl2br($input);

        return $input;
    }
}