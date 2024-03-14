<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * VikAppointments image resizer helper class.
 *
 * @since 1.6
 */
abstract class VAPImageResizer
{
	/**
	 * Resizes an image proportionally.
	 *
	 * @param 	string 	 $fileimg 	The image source path.
	 * @param 	string 	 $dest 		The destination path of the resized image.
	 * @param 	integer  $towidth 	The final width.
	 * @param 	integer  $toheight 	The final height.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public static function proportionalImage($fileimg, $dest, $towidth, $toheight)
	{
		if (!file_exists($fileimg))
		{
			return false;
		}

		if (empty($towidth) && empty($toheight))
		{
			copy($fileimg, $dest);
			return true;
		}
		
		list($owid, $ohei, $type) = getimagesize($fileimg);

		if ($owid > $towidth || $ohei > $toheight)
		{
			$xscale = $owid / $towidth;
			$yscale = $ohei / $toheight;

			if ($yscale > $xscale)
			{
				$new_width  = round($owid * (1 / $yscale));
				$new_height = round($ohei * (1 / $yscale));
			}
			else
			{
				$new_width  = round($owid * (1 / $xscale));
				$new_height = round($ohei * (1 / $xscale));
			}

			switch ($type)
			{
				case '1' :
					$imagetmp = imagecreatefromgif($fileimg);
					break;

				case '2' :
					$imagetmp = imagecreatefromjpeg($fileimg);
					break;

				default :
					$imagetmp = imagecreatefrompng($fileimg);
			}

			$imageresized = imagecreatetruecolor($new_width, $new_height);
			
			if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG)
			{
				imagealphablending($imageresized, false);
				imagesavealpha($imageresized ,true);
				$transparent = imagecolorallocatealpha($imageresized, 255, 255, 255, 127);
				imagefilledrectangle($imageresized, 0, 0, $new_width, $new_height, $transparent);
			}
			
			imagecopyresampled($imageresized, $imagetmp, 0, 0, 0, 0, $new_width, $new_height, $owid, $ohei);
		
			switch ($type)
			{
				case '1' :
					imagegif($imageresized, $dest);
					break;

				case '2' :
					imagejpeg($imageresized, $dest);
					break;

				default :
					imagealphablending($imageresized, false);
					imagesavealpha($imageresized, true);
					imagepng($imageresized, $dest);
			}
		
			imagedestroy($imageresized);

			return true;
		}
		else
		{
			copy($fileimg, $dest);
		}

		return true;
	}
	
	/**
	 * Resizes an image with banded effect (1 rect on top and 1 rect on bottom).
	 *
	 * @param 	string 	 $fileimg 	The image source path.
	 * @param 	string 	 $dest 		The destination path of the resized image.
	 * @param 	integer  $towidth 	The final width.
	 * @param 	integer  $toheight 	The final height.
	 * @param 	string 	 $rgb 		The RGB values (separated by a comma) of the bands.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public static function bandedImage($fileimg, $dest, $towidth, $toheight, $rgb = '')
	{
		if (!file_exists($fileimg))
		{
			return false;
		}

		if (empty($towidth) && empty($toheight))
		{
			copy($fileimg, $dest);
			return true;
		}
		
		$exp = explode(",", $rgb);

		if (count($exp) == 3)
		{
			$r = trim($exp[0]);
			$g = trim($exp[1]);
			$b = trim($exp[2]);
		}
		else
		{
			$r = 0;
			$g = 0;
			$b = 0;
		}
		
		
		list($owid, $ohei, $type) = getimagesize($fileimg);
		
		if ($owid > $towidth || $ohei > $toheight)
		{
			$xscale = $owid / $towidth;
			$yscale = $ohei / $toheight;

			if ($yscale > $xscale)
			{
				$new_width  = round($owid * (1 / $yscale));
				$new_height = round($ohei * (1 / $yscale));

				$ydest = 0;
				$diff  = $towidth - $new_width;
				$xdest = ($diff > 0 ? round($diff / 2) : 0);
			}
			else
			{
				$new_width  = round($owid * (1 / $xscale));
				$new_height = round($ohei * (1 / $xscale));

				$xdest = 0;
				$diff  = $toheight - $new_height;
				$ydest = ($diff > 0 ? round($diff / 2) : 0);
			}
	
			$imageresized = imagecreatetruecolor($towidth, $toheight);

			$bgColor = imagecolorallocate($imageresized, (int)$r, (int)$g, (int)$b);
			imagefill($imageresized, 0, 0, $bgColor);

			switch ($type)
			{
				case '1' :
					$imagetmp = imagecreatefromgif($fileimg);
					break;

				case '2' :
					$imagetmp = imagecreatefromjpeg($fileimg);
					break;

				default :
					$imagetmp = imagecreatefrompng($fileimg);
			}

			imagecopyresampled($imageresized, $imagetmp, $xdest, $ydest, 0, 0, $new_width, $new_height, $owid, $ohei);

			switch ($type)
			{
				case '1' :
					imagegif($imageresized, $dest);
					break;

				case '2' :
					imagejpeg($imageresized, $dest);
					break;

				default :
					imagepng($imageresized, $dest);
			}

			imagedestroy($imageresized);
			
			return true;
		}
		else
		{
			copy($fileimg, $dest);
		}

		return true;
	}
	
	/**
	 * Crops an image.
	 *
	 * @param 	string 	 $fileimg 	The image source path.
	 * @param 	string 	 $dest 		The destination path of the resized image.
	 * @param 	integer  $towidth 	The final width.
	 * @param 	integer  $toheight 	The final height.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public static function croppedImage($fileimg, $dest, $towidth, $toheight)
	{
		if (!file_exists($fileimg))
		{
			return false;
		}

		if (empty($towidth) && empty($toheight))
		{
			copy($fileimg, $dest);
			return true;
		}
		
		list($owid, $ohei, $type) = getimagesize($fileimg);
		
		if ($owid <= $ohei)
		{
			$new_width  = $towidth;
			$new_height = ($towidth / $owid) * $ohei;
		}
		else
		{
			$new_height = $toheight;
			$new_width  = ($new_height / $ohei) * $owid;   
		}
		
		switch ($type)
		{
			case '1':
				$img_src  = imagecreatefromgif($fileimg);
				$img_dest = imagecreate($new_width, $new_height);
				break;

			case '2':
				$img_src  = imagecreatefromjpeg($fileimg);
				$img_dest = imagecreatetruecolor($new_width, $new_height);
				break;

			default:
				$img_src  = imagecreatefrompng($fileimg);
				$img_dest = imagecreatetruecolor($new_width, $new_height);
		}
		
		imagecopyresampled($img_dest, $img_src, 0, 0, 0, 0, $new_width, $new_height, $owid, $ohei);
		
		switch ($type)
		{
			case '1':
				$cropped = imagecreate($towidth, $toheight);
				break;

			case '2':
				$cropped = imagecreatetruecolor($towidth, $toheight);
				break;

			default:
				$cropped = imagecreatetruecolor($towidth, $toheight);
		}
		
		imagecopy($cropped, $img_dest, 0, 0, 0, 0, $owid, $ohei);
		
		switch ($type)
		{
			case '1' :
				imagegif($cropped, $dest);
				break;

			case '2' :
				imagejpeg($cropped, $dest);
				break;

			default :
				imagepng($cropped, $dest);
		}
		
		imagedestroy($img_dest);
		imagedestroy($cropped);
		
		return true;
	}
}
