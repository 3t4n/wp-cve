<?php
/** 
 * @package   	VikRentItems - Libraries
 * @subpackage 	update
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Class used to handle the software changelog.
 *
 * @since 1.0
 */
class VikRentItemsChangelog
{
	/**
	 * Builds the changelog previously stored.
	 *
	 * @return 	string 	the parsed HTML code of the changelog
	 *
	 * @uses 	getTree()
	 */
	public static function build()
	{
		$tree = self::getTree();

		if (empty($tree))
		{
			return '';
		}
		
		$changelog = '';
		
		foreach ($tree as $versionlog)
		{
			// open version
			$file = JHtml::fetch('layoutfile', 'html.changelog.version.open');
			$changelog .= $file->render(array(
				'title' 	=> $versionlog->title,
				'version' 	=> $versionlog->version
			));

			// parse the sections of this version changelog
			foreach ($versionlog->sections as $section)
			{
				// open section
				$file = JHtml::fetch('layoutfile', 'html.changelog.section.open');
				$changelog .= $file->render(array(
					'title' => $section->title
				));

				// parse the children of this section
				foreach ($section->children as $feature)
				{
					// feature item
					$file = JHtml::fetch('layoutfile', 'html.changelog.feature.item');
					$changelog .= $file->render(array(
						'title' 	=> $feature->title,
						'descr' 	=> $feature->description
					));
				}

				// close section
				$file = JHtml::fetch('layoutfile', 'html.changelog.section.close');
				$changelog .= $file->render();
			}

			// close version
			$file = JHtml::fetch('layoutfile', 'html.changelog.version.close');
			$changelog .= $file->render();
		}

		return $changelog;
	}

	/**
	 * Returns the current changelog tree object
	 *
	 * @return 	mixed 	string if empty, object otherwise
	 */
	public static function getTree()
	{
		$changelog = get_option('vikrentitems_changelog', '');

		return !empty($changelog) ? json_decode($changelog) : '';
	}

	/**
	 * Stores the returned changelog for the current version in JSON.
	 * If no change-log for the current version, the old value is unset.
	 *
	 * @param 	object 	$tree 	the changelog tree object
	 *
	 * @return 	void
	 */
	public static function store($tree)
	{
		$changelog = '';
		
		if (!empty($tree))
		{
			$changelog = json_encode($tree);
		}
		
		update_option('vikrentitems_changelog', $changelog);
	}

	/**
	 * Registers options upon installation of the plugin.
	 *
	 * @return 	void
	 */
	public static function install()
	{
		update_option('vikrentitems_changelog', '');
	}

	/**
	 * Deletes options upon uninstallation of the plugin.
	 *
	 * @return 	void
	 */
	public static function uninstall()
	{
		delete_option('vikrentitems_changelog');
	}
}
