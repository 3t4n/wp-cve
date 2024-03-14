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

class Directory
{
	/**
	 * Copies files from source to directory
	 * 
	 * @param   string  $src
	 * @param   string  $dest
	 * 
	 * @return  void
	 */
	public static function copy($src, $dest)
	{
		$dir = opendir($src);

		mkdir($dest);

		while(false !== ($file = readdir($dir)))
		{
			if (in_array($file, ['.', '..']))
			{
				continue;
			}
		
			if (is_dir($src . DIRECTORY_SEPARATOR . $file))
			{
				self::copy($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
			}
			else
			{
				copy($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
			}
        }

		closedir($dir);
	}

	/**
	 * Creates a directory
	 * 
	 * @param   string  $dir
	 * 
	 * @return  mixed
	 */
	public static function create($dir)
	{
		if (!$dir)
		{
			return;
		}
		
		if (file_exists($dir))
		{
			return;
		}
		
		mkdir($dir);
	}

	/**
	 * Empties a directory
	 * 
	 * @param   string  $dir
	 * 
	 * @return  mixed
	 */
	public static function empty($dir)
	{
		if (!file_exists($dir))
		{
			return;
		}

		$it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new \RecursiveIteratorIterator($it,
					\RecursiveIteratorIterator::CHILD_FIRST);
		
		foreach($files as $file)
		{
			if ($file->isDir())
			{
				rmdir($file->getRealPath());
			}
			else
			{
				unlink($file->getRealPath());
			}
		}
	}

	/**
	 * Deletes a directory
	 * 
	 * @param   string  $dir
	 * 
	 * @return  mixed
	 */
	public static function delete($dir)
	{
		if (!$dir)
		{
			return;
		}
		
		if (!file_exists($dir))
		{
			return;
		}

		self::empty($dir);

		rmdir($dir);
	}

	/**
	 * Checks if the path exists. If not creates the folders as well as subfolders.
	 * 
	 * @param   string  $path	 The folder path
	 * @param   string  $protect If set to true, each folder will be protected by disabling PHP engine and preventing folder browsing
	 * 
	 * @return  bool
	 */
	public static function createDirs($path, $protect = true)
	{
		if (!is_dir($path))
		{
			mkdir($path, 0755, true);

			// New folder created. Let's protect it.
			if ($protect)
			{
				self::writeHtaccessFile($path);
				self::writeIndexHtmlFile($path);
			}
		}

		// Make sure the folder is writable
		return @is_writable($path);
	}

	/**
	 * Add an .htaccess file to the folder in order to disable PHP engine entirely 
	 *
	 * @param  string $path	The path where to write the file
	 *
	 * @return void
	 */
	public static function writeHtaccessFile($path)
	{
		$content = '
			# Block direct PHP access
			<Files *.php>
				deny from all
			</Files>
		';

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		file_put_contents($path . '/.htaccess', $content);
	}

	/**
	 * Creates an empty index.html file to prevent directory listing 
	 *
	 * @param  string $path	The path where to write the file
	 *
	 * @return void
	 */
	public static function writeIndexHtmlFile($path)
	{
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		file_put_contents($path . '/index.html', '<!DOCTYPE html><title></title>');	
	}
}