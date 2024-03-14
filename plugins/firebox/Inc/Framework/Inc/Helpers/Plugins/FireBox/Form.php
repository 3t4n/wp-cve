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

namespace FPFramework\Helpers\Plugins\FireBox;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Form
{
	/**
	 * Returns all FireBox forms in key,value pairs.
	 * 
	 * @param   int		$offset
	 * @param   int		$limit
	 * @param   array	$exclude_campaign_ids
	 * @param   string  $search_form_name
	 * 
	 * @return  array
	 */
	public static function getForms($offset = 0, $limit = 20, $exclude_campaign_ids = [], $search_form_name = '')
	{
		if (!function_exists('firebox'))
		{
			return [];
		}
		
		$where = [
			'post_type' => " = 'firebox'",
			'post_status' => " IN ('publish', 'draft')"
		];

		$payload = [
			'where' => $where
		];

		if (!$popups = firebox()->tables->box->getResults($payload))
		{
			return [];
		}

		$forms = [];

		// Find forms
		foreach ($popups as $index => $post)
		{
			$id = $post->ID;
			
			if ($exclude_campaign_ids && in_array($id, $exclude_campaign_ids))
			{
				continue;
			}
			
			if (!has_block('firebox/form', $post))
			{
				continue;
			}

			if (!$campaign_forms = self::getCampaignForms($id, $search_form_name))
			{
				continue;
			}

			$forms = array_merge($forms, $campaign_forms);
		}

		return array_slice($forms, $offset, $limit);
	}

	/**
	 * Returns all FireBox forms in key,value pairs given their IDs.
	 * 
	 * @param   array	$ids
	 * 
	 * @return  array
	 */
	public static function getFormsByIDs($ids = [])
	{
		if (!function_exists('firebox'))
		{
			return [];
		}
		
		$where = [
			'post_type' => " = 'firebox'",
			'post_status' => " IN ('publish', 'draft')"
		];

		$payload = [
			'where' => $where
		];

		if (!$popups = firebox()->tables->box->getResults($payload))
		{
			return [];
		}

		$forms = [];

		// Find forms
		foreach ($popups as $index => $post)
		{
			$id = $post->ID;
			
			if (!has_block('firebox/form', $post))
			{
				continue;
			}

			if (!$campaign_forms = self::getCampaignForms($id))
			{
				continue;
			}

			$forms = array_merge($forms, $campaign_forms);
		}

		$foundForms = [];

		foreach ($forms as $key => $value)
		{
			if (!in_array($key, $ids))
			{
				continue;
			}
			
			$foundForms[$key] = $value;
		}

		return $foundForms;
	}

	public static function getCampaignForms($id = null, $search_form_name = '')
	{
		if (!$id)
		{
			return [];
		}

		if (!$blocks = parse_blocks(get_the_content(null, false, $id)))
		{
			return [];
		}

		$forms = [];

		foreach ($blocks as $key => $block)
		{
			if (isset($block['innerBlocks']))
			{
				foreach ($block['innerBlocks'] as $innerBlock)
				{
					// Find form block
					if (!$form_block = self::findRecursiveForm($innerBlock))
					{
						continue;
					}

					$atts = isset($form_block['attrs']) ? $form_block['attrs'] : false;
					if (!$atts)
					{
						continue;
					}
	
					$block_unique_id = isset($atts['uniqueId']) ? $atts['uniqueId'] : false;
					if (!$block_unique_id)
					{
						continue;
					}

					$form_name = isset($form_block['attrs']['formName']) ? $form_block['attrs']['formName'] : firebox()->_('FB_UNTITLED_FORM');;

					if ($search_form_name !== '' && stripos($form_name, trim($search_form_name)) === false)
					{
						continue;
					}

					$forms[$block_unique_id] = $form_name;
				}
			}

			if ($block['blockName'] !== 'firebox/form')
			{
				continue;
			}

			$atts = isset($block['attrs']) ? $block['attrs'] : false;
			if (!$atts)
			{
				continue;
			}

			$block_unique_id = isset($atts['uniqueId']) ? $atts['uniqueId'] : false;
			if (!$block_unique_id)
			{
				continue;
			}

			$form_name = isset($block['attrs']['formName']) ? $block['attrs']['formName'] : firebox()->_('FB_UNTITLED_FORM');

			if ($search_form_name !== '' && stripos($form_name, trim($search_form_name)) === false)
			{
				continue;
			}

			$forms[$block_unique_id] = $form_name;
		}

		return $forms;
	}

	/**
	 * Finds all supported blocks recursively.
	 * 
	 * @param   array  $block
	 * 
	 * @return  array
	 */
	public static function findRecursiveForm($block)
	{
		$supported_blocks = ['firebox/form'];
		
		if (in_array($block['blockName'], $supported_blocks))
		{
			return $block;
		}

		if (empty($block['innerBlocks']))
		{
			return;
		}

		foreach ($block['innerBlocks'] as $innerBlockItem)
		{
			$innerBlock = self::findRecursiveForm($innerBlockItem, $supported_blocks);

			if (!$innerBlock)
			{
				continue;
			}
			
			return $innerBlock;
		}

		return;
	}
}