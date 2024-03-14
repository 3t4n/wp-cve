<?php
/* 
Description: General Utilities Code
 
Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if (!class_exists('StageShowLibPermsClass')) 
{
	if (!defined('STAGESHOWLIB_PHPFOLDER_PERMS'))
		define('STAGESHOWLIB_PHPFOLDER_PERMS', 0755);
		
	if (!defined('STAGESHOWLIB_PHPFILE_PERMS'))
		define('STAGESHOWLIB_PHPFILE_PERMS', 0644);
		
	if (!defined('STAGESHOWLIB_LOGFOLDER_PERMS'))
		define('STAGESHOWLIB_LOGFOLDER_PERMS', 0700);
		
	define('STAGESHOWLIB_PHPFOLDER_PERMS_OCTAL', substr('000'.sprintf("%o",STAGESHOWLIB_PHPFOLDER_PERMS),-4));
	define('STAGESHOWLIB_PHPFILE_PERMS_OCTAL', substr('000'.sprintf("%o",STAGESHOWLIB_PHPFILE_PERMS),-4));
			
	class StageShowLibPermsClass // Define class
	{
		static function check_tree($contentsPath, $tree, $dir)
		{
			$src = $contentsPath.$tree.'/'.$dir;
			$setPerms = isset($_GET['set']);
			self::check_allperms($src, $setPerms);
		}
		
		static function check_allperms($src, $setPerms = false)
		{
			if (!file_exists($src)) return;
			
			$file = basename($src);
			$perms = substr(sprintf("%o",fileperms($src)),-4);
			echo "Source permissions = $perms for $file <br>\n";

			self::recurse_perms($src, $setPerms);
		}
		
		static function check_perm($src, $setPerms = false)
		{
			$showErrs = isset($_GET['errs']);
	
			$perms = substr(sprintf("%o",fileperms($src)),-4);
			if ( is_dir($src) )
			{
				$folder = $src;
				
				if (!$setPerms)
				{
					if ((!$showErrs) || ($perms !== STAGESHOWLIB_PHPFOLDER_PERMS_OCTAL))
					{
						echo "Folder permissions = $perms for $folder <br>\n";
					}
				}
				elseif ($perms === STAGESHOWLIB_PHPFOLDER_PERMS_OCTAL)
				{
					echo "Folder permissions for $folder is CORRECT <br>\n";
				}
				else
				{
					// Make sure that destination is not ReadOnly
					if (chmod($folder, STAGESHOWLIB_PHPFOLDER_PERMS))
						echo "Set folder permissions for $folder to ".STAGESHOWLIB_PHPFOLDER_PERMS_OCTAL."<br>\n";
					else
						echo "ERROR Setting folder permissions for $folder <br>\n";
				}

			}
			else 
			{
				$srcFile = $src;

				$file = basename($src);
				if (!$setPerms)
				{
					if ((!$showErrs) || ($perms !== STAGESHOWLIB_PHPFILE_PERMS_OCTAL))
					{
						echo "File permissions = $perms for $file <br>\n";
					}
				}
				elseif ($perms === STAGESHOWLIB_PHPFILE_PERMS_OCTAL)
				{
					echo "File permissions for $file is CORRECT <br>\n";
				}
				else
				{
					// Make sure that destination is not ReadOnly
					// Make sure that destination is not ReadOnly
					if (chmod($srcFile, STAGESHOWLIB_PHPFILE_PERMS))
						echo "Set file permissions for $file to ".STAGESHOWLIB_PHPFILE_PERMS_OCTAL." <br>\n";
					else
						echo "ERROR file folder permissions for $file <br>\n";
				}

			}
		}
		
		static function recurse_perms($src, $setPerms = false)
		{			
			$rtnStatus = true;
			if (!file_exists($src)) return;
			
			self::check_perm($src, $setPerms);			
			$dir = opendir($src);

			while(false !== ( $file = readdir($dir)) )
			{
				if ( $file == '.' ) continue;
				if ( $file == '..' ) continue;
				if ( $file == '.svn' ) continue;
				if ( $file == 'Thumbs.db' ) continue;
		
				$nextPath = $src . '/' . $file;
				self::check_perm($nextPath, $setPerms);
				
				if ( is_dir($nextPath) )
				{
					if (!self::recurse_perms($nextPath, $setPerms))
					{
						$rtnStatus = false;
						break;
					}
				}
			}
			closedir($dir);
			
			return $rtnStatus;
		}
		
	}
}

$contentsPathLength = strpos(__FILE__, 'plugins');
if ($contentsPathLength == false) die;
$contentsPath = substr(__FILE__, 0, $contentsPathLength);
echo "Contents Path: $contentsPath <br>\n";

StageShowLibPermsClass::check_tree($contentsPath, 'plugins', 'stageshow');
StageShowLibPermsClass::check_tree($contentsPath, 'plugins', 'stageshowgold');

StageShowLibPermsClass::check_tree($contentsPath, 'uploads', 'stageshow');
StageShowLibPermsClass::check_tree($contentsPath, 'uploads', 'stageshowgold');

?>