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

namespace FireBox\Core\Admin\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Metaboxes
{
	/**
	 * The path where plugin metaboxes can be found
	 * 
	 * @var  string
	 */
	private $path = '\FireBox\Core\Admin\Includes\Metaboxes\\';

	public function __construct()
	{
		// get all plugin metaboxes
		add_filter('fpframework/metaboxes_filter', [$this, 'filterPluginMetaboxes']);
	}
	
	/**
	 * Adds plugin metaboxes to framework list of metaboxes
	 * 
	 * @param   array   $metaboxes
	 * 
	 * @return  array
	 */
	public function filterPluginMetaboxes($metaboxes)
	{
		$metaboxes[] = $this->getMetaboxes();
		return $metaboxes;
	}

	/**
	 * Returns all plugin metaboxes
	 * 
	 * @return  array
	 */
	private function getMetaboxes()
	{
		return [
			'path' => $this->path,
			'names' => \FPFramework\Helpers\FileHelper::getFileNamesFromFolder(FBOX_PLUGIN_DIR . 'Inc/Core/Admin/Includes/Metaboxes/')
		];
	}
}