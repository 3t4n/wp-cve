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

namespace FireBox\Core\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class BoxHelper
{
	/**
	 * Get box meta
	 * 
	 * @param   int  $id
	 * 
	 * @return  array
	 */
	public static function getMeta($id)
	{
		return get_post_meta($id, 'fpframework_meta_settings', true);
	}

	/**
	 * Gets all Boxes.
	 * 
	 * @param   array  $status
	 * @param   int    $limit
	 * 
	 * @return  array
	 */
	public static function getAllBoxes($status = ['publish'], $limit = -1)
	{
		// cache key
		$hash = md5('firebox_getAllBoxes_' . implode(',', $status));

		// check cache
		if ($data = wp_cache_get($hash))
		{
			return $data;
        }
		
		$args = [
			'post_status' => $status,
			'post_type' => 'firebox',
			'posts_per_page' => $limit
		];
		
		// Get the query.
		$query = new \WP_Query($args);

		wp_reset_postdata();

		// set cache
		wp_cache_set($hash, $query);

		return $query;
	}

	/**
	 * Retrieves all boxes in a key => value array of ID => title
	 * 
	 * @return  array
	 */
	public static function getAllBoxesParsedByKeyValue()
	{
		if (!$boxes = self::getAllBoxes())
		{
			return [];
		}

		return self::produceKeyValueBoxes($boxes->posts);
	}

	/**
	 * Produce a key,value pair of boxes containg their ID,title
	 * 
	 * @return  array
	 */
	public static function produceKeyValueBoxes($boxes)
	{
		if (!$boxes)
		{
			return [];
		}
		
		$data = [];

		foreach ($boxes as $key => $box)
		{
			$data[$box->ID] = $box->post_title;
		}
		
		return $data;
	}

	/**
	 * Gets all published Boxes except the given id.
	 * The array structure is [ID, title] to properly appear in a Dropdown field.
	 * 
	 * @param   integer  $id
	 * 
	 * @return  array
	 */
	public static function getAllMirrorBoxesExceptID($id)
	{
		if (!$id)
		{
			return [];
		}

		$boxes = firebox()->tables->box->getResults([
			'where' => [
				'ID' => ' NOT IN (' . esc_sql($id) . ')',
				'post_status' => " = 'publish'",
				'post_type' => " = 'firebox'"
			]
		]);

		$boxes_parsed = [];

		foreach ($boxes as $key => $p)
		{
			$boxes_parsed[$p->ID] = $p->post_title . ' (' . $p->ID . ')';
		}

		return $boxes_parsed;
	}

	/**
	 * Get box data
	 * 
	 * @param   int    $box
	 * 
	 * @return  array
	 */
	public static function getBoxData($box)
	{
		if (!$box)
		{
			return false;
		}

		$box = (int) $box;

		$box = firebox()->tables->box->getResults([
			'where' => [
				'ID' => " = '" . esc_sql($box) . "'"
			]
		]);

		return isset($box[0]) ? $box[0] : [];
	}

	/**
	 * Checks whether the box exist
	 * 
	 * @param   int      $box
	 * 
	 * @return  boolean
	 */
	public static function boxExist($box)
	{
		if (!$box)
		{
			return false;
		}
		
		$box = (int) $box;

		$box = firebox()->tables->box->getResults([
			'where' => [
				'ID' => " = '" . esc_sql($box) . "'"
			]
		]);

		if (!$box)
		{
			return false;
		}

		return true;
	}

	/**
	 * Gets boxes in a [id, title] pair from a list of Box IDs
	 * 
	 * @param   array  $items
	 * 
	 * @return  array
	 */
	public static function getSelectedSearchItems($items)
	{
		$boxes = firebox()->tables->box->getResults([
			'where' => [
				'ID' => ' IN(' . implode(',', array_map('intval', $items)) . ')',
				'post_status' => " = 'publish'",
				'post_type' => " = 'firebox'"
			]
		]);

		$boxes_parsed = [];

		foreach ($boxes as $key => $p)
		{
			$boxes_parsed[] = [
				'id' => $p->ID,
				'title' => $p->post_title
			];
		}

		return $boxes_parsed;
	}

	/**
	 * Gets Settings Data
	 * 
	 * @return  array
	 */
	public static function getParams()
	{
		// cache key
		$cache_key = md5('fboxSettings');

		// check cache
		if ($params = wp_cache_get($cache_key))
		{
			return $params;
		}

		// get params
		$params = get_option('firebox_settings');

		// set cache
		wp_cache_set($cache_key, $params);

		return $params;
	}

	/**
	 * Returns the box template from the URL using the "template" query string.
	 * 
	 * @return  array
	 */
	public static function getBoxFromTemplateURL()
	{
		if (!isset($_GET['fpf_use_template'])) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		{
			return;
		}
		
		// $template must be an integer
		$template = isset($_GET['template']) ? intval($_GET['template']) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (empty($template) || $template === 'blank')
		{
			return;
		}
		
		$local_template_path = \FPFramework\Helpers\WPHelper::getPluginUploadsDirectory('firebox', 'templates') . '/template.json';
		if (!file_exists($local_template_path))
		{
			return;
		}
		
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$local_template = file_get_contents($local_template_path);
		if (!$local_template = json_decode($local_template, true))
		{
			return;
		}

		if (!isset($local_template['id']))
		{
			return;
		}

		if (!isset($local_template['template']))
		{
			return;
		}

		if ((int) $local_template['id'] !== (int) $template)
		{
			return;
		}

		// Find images and save them locally.
		$local_template['template']['post_content'] = \FPFramework\Helpers\WPHelper::downloadAndReplaceImages($local_template['template']['post_content']);
		
		return $local_template;
	}
	
	/**
	 * Duplicates a box
	 * 
	 * @param   integer  $box_id
	 * 
	 * @return  bool
	 */
	public static function duplicateBox($box_id)
	{
		// get box
		$box = firebox()->tables->box->getResults([
			'where' => [
				'ID' => " = '" . esc_sql($box_id) . "'",
				'post_status' => " = '" . esc_sql(get_post_status($box_id)) . "'",
				'post_type' => " = 'firebox'"
			],
			'limit' => 1
		]);

		if (empty($box))
		{
			return false;
		}

		// reset box ID and make it a draft
		$box = $box[0];
		$box->ID = '';
		$box->post_title = 'Copy of ' . $box->post_title;
		$box->post_status = 'draft';

		$factory = new \FPFramework\Base\Factory();
			
		$tz = wp_timezone();
		$date_without_tz = $factory->getDate();
		$date_with_tz = $factory->getDate()->setTimezone($tz);

		$box->post_date = $date_with_tz->format('Y-m-d H:i:s');
		$box->post_date_gmt = $date_without_tz->format('Y-m-d H:i:s');

		Form\Form::ensureUniqueFormIDs($box->post_content);

		// get meta options
		$meta = self::getMeta($box_id);

		// insert new box
		$new_box_id = firebox()->tables->box->insert($box);

		// add meta options for new box
		update_post_meta($new_box_id, 'fpframework_meta_settings', wp_slash($meta));

		return true;
	}

	/**
	 * Reset Box Stats
	 * 
	 * @param   array  $box_ids
	 * 
	 * @return  void
	 */
	public static function resetBoxStats($box_ids)
	{
		$logs_table = firebox()->tables->boxlog->getFullTableName();
		$logs_details_table = firebox()->tables->boxlogdetails->getFullTableName();

		// delete box logs details
		firebox()->tables->boxlogdetails->executeRaw("DELETE FROM `$logs_details_table` WHERE log_id IN (SELECT id FROM `$logs_table` WHERE box IN (" . implode(",", $box_ids) . "))");

		// delete box logs
		firebox()->tables->boxlog->deleteRaw('WHERE box IN (' . implode(',', $box_ids) . ')');
	}

	/**
	 * Exports boxes
	 * 
	 * @param   array  $box_ids
	 * 
	 * @return  string
	 */
	public static function exportBoxes($box_ids)
	{
		// get boxes
		$boxes = firebox()->tables->box->getResults([
			'where' => [
				'ID' => ' IN (' . implode(',', esc_sql($box_ids)) . ')',
				'post_type' => " = 'firebox'"
			]
		]);

		$boxes = (array) $boxes;

		if (!count($boxes))
		{
			return;
		}

		$exported = [];
		
		$filename = firebox()->_('FB_PLUGIN_NAME') . ' Items';

		// name for 1 box
		if (count($boxes) == 1)
		{
			$name = mb_strtolower(html_entity_decode($boxes['0']->post_title));
			$name = preg_replace('#[^a-z0-9_-]#', '_', $name);
			$name = trim(preg_replace('#__+#', '_', $name), '_-');

			$filename = firebox()->_('FB_PLUGIN_NAME') .  ' Item (' . $name . ')';
		}

		foreach ($boxes as $box)
		{
			$meta = self::getMeta($box->ID);

			$exported[] = [
				'box' => $box,
				'meta' => $meta
			];
		}

		// SET DOCUMENT HEADER
		if (preg_match('#Opera(/| )([0-9].[0-9]{1,2})#', $_SERVER['HTTP_USER_AGENT']))
		{
			$UserBrowser = "Opera";
		}
		elseif (preg_match('#MSIE ([0-9].[0-9]{1,2})#', $_SERVER['HTTP_USER_AGENT']))
		{
			$UserBrowser = "IE";
		}
		else
		{
			$UserBrowser = '';
		}
		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
		@ob_end_clean();
		ob_start();

		header('Content-Type: ' . $mime_type);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		if ($UserBrowser == 'IE')
		{
			header('Content-Disposition: inline; filename="' . $filename . '.fbox"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}
		else
		{
			header('Content-Disposition: attachment; filename="' . $filename . '.fbox"');
			header('Pragma: no-cache');
		}

		// PRINT STRING
		echo wp_json_encode($exported);
		die();
	}

	/**
	 * Returns the last date viewed of a campaign.
	 * 
	 * @param   int		$id
	 * 
	 * @return  string
	 */
	public static function getCampaignLastDateViewed($id = null)
	{
		if (!$id)
		{
			return;
		}

		$last_date_viewed = firebox()->tables->boxlog->getResults([
			'select' => [
				'date as last_date_viewed'
			],
			'where' => [
				'box' => ' = ' . esc_sql($id),
			],
			'orderby' => ' date desc',
			'limit' => 1
		]);

		return isset($last_date_viewed[0]->last_date_viewed) ? get_date_from_gmt($last_date_viewed[0]->last_date_viewed) : null;
	}
}