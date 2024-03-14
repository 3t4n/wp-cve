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

class FileHelper
{
	/**
	 * Reads and parses all files and returns only PHP files with path and their name only
	 * 
	 * @param   String  $folder
	 * 
	 * @return  array
	 */
	public static function getFileNamesFromFolder($folder)
	{
		if (!is_string($folder) || empty($folder))
		{
			return [];
		}
		
		$files = \scandir($folder);

		$parsedFiles = [];
		
		foreach ($files as $file)
		{
			if (!(strpos($file, '.php') !== false))
			{
				continue;
			}

			if (in_array($file, ['index.php', '.', '..']))
			{
				continue;
			}

			// remove .php from file name
			$file = explode('.php', $file);
			
			$parsedFiles[] = $file[0];
		}

		return $parsedFiles;
	}
}