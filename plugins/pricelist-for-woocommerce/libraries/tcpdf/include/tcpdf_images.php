<?php
//============================================================+
// File name   : tcpdf_images.php
// Version     : 1.0.005
// Begin       : 2002-08-03
// Last Update : 2014-11-15
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2002-2014 Nicola Asuni - Tecnick.com LTD
//
// This file is part of TCPDF software library.
//
// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the License
// along with TCPDF. If not, see
// <http://www.tecnick.com/pagefiles/tcpdf/LICENSE.TXT>.
//
// See LICENSE.TXT file for more information.
// -------------------------------------------------------------------
//
// Description :
//   Static image methods used by the TCPDF class.
//
//============================================================+

/**
 * @file
 * This is a PHP class that contains static image methods for the TCPDF class.<br>
 * @package com.tecnick.tcpdf
 * @author Nicola Asuni
 * @version 1.0.005
 */

/**
 * @class TCPDF_IMAGES
 * Static image methods used by the TCPDF class.
 * @package com.tecnick.tcpdf
 * @brief PHP class for generating PDF documents without requiring external extensions.
 * @version 1.0.005
 * @author Nicola Asuni - info@tecnick.com
 */
class TCPDF_IMAGES {
	public static $svginheritprop = array('clip-rule', 'color', 'color-interpolation', 'color-interpolation-filters', 'color-profile', 'color-rendering', 'cursor', 'direction', 'display', 'fill', 'fill-opacity', 'fill-rule', 'font', 'font-family', 'font-size', 'font-size-adjust', 'font-stretch', 'font-style', 'font-variant', 'font-weight', 'glyph-orientation-horizontal', 'glyph-orientation-vertical', 'image-rendering', 'kerning', 'letter-spacing', 'marker', 'marker-end', 'marker-mid', 'marker-start', 'pointer-events', 'shape-rendering', 'stroke', 'stroke-dasharray', 'stroke-dashoffset', 'stroke-linecap', 'stroke-linejoin', 'stroke-miterlimit', 'stroke-opacity', 'stroke-width', 'text-anchor', 'text-rendering', 'visibility', 'word-spacing', 'writing-mode');
	public static function getImageFileType($imgfile, $iminfo=array()) {
		$type = '';
		if (isset($iminfo['mime']) AND !empty($iminfo['mime'])) {
			$mime = explode('/', $iminfo['mime']);
			if ((count($mime) > 1) AND ($mime[0] == 'image') AND (!empty($mime[1]))) {
				$type = strtolower(trim($mime[1]));
			}
		}
		if (empty($type)) {
            $type = strtolower(trim(pathinfo(parse_url($imgfile, PHP_URL_PATH), PATHINFO_EXTENSION)));
		}
		if ($type == 'jpg') {
			$type = 'jpeg';
		}
		return $type;
	}
	public static function setGDImageTransparency($new_image, $image) {
		$tcol = array('red' => 255, 'green' => 255, 'blue' => 255);
		$tid = imagecolortransparent($image);
		$palletsize = imagecolorstotal($image);
		if (($tid >= 0) AND ($tid < $palletsize)) {
			$tcol = imagecolorsforindex($image, $tid);
		}
		$tid = imagecolorallocate($new_image, $tcol['red'], $tcol['green'], $tcol['blue']);
		imagefill($new_image, 0, 0, $tid);
		imagecolortransparent($new_image, $tid);
		return $new_image;
	}
	public static function _toPNG($image, $tempfile) {
		imageinterlace($image, 0);
		imagepng($image, $tempfile);
		imagedestroy($image);
		$retvars = self::_parsepng($tempfile);
		unlink($tempfile);
		return $retvars;
	}
	public static function _toJPEG($image, $quality, $tempfile) {
		imagejpeg($image, $tempfile, $quality);
		imagedestroy($image);
		$retvars = self::_parsejpeg($tempfile);
		unlink($tempfile);
		return $retvars;
	}
	public static function _parsejpeg($file) {
		if (!@TCPDF_STATIC::file_exists($file)) {
			return false;
		}
		$a = getimagesize($file);
		if (empty($a)) {
			return false;
		}
		if ($a[2] != 2) {
			return false;
		}
		$bpc = isset($a['bits']) ? intval($a['bits']) : 8;
		if (!isset($a['channels'])) {
			$channels = 3;
		} else {
			$channels = intval($a['channels']);
		}
		switch ($channels) {
			case 1: {
				$colspace = 'DeviceGray';
				break;
			}
			case 3: {
				$colspace = 'DeviceRGB';
				break;
			}
			case 4: {
				$colspace = 'DeviceCMYK';
				break;
			}
			default: {
				$channels = 3;
				$colspace = 'DeviceRGB';
				break;
			}
		}
		$data = file_get_contents($file);
		$icc = array();
		$offset = 0;
		while (($pos = strpos($data, "ICC_PROFILE\0", $offset)) !== false) {
			$length = (TCPDF_STATIC::_getUSHORT($data, ($pos - 2)) - 16);
			$msn = max(1, ord($data[($pos + 12)]));
			$nom = max(1, ord($data[($pos + 13)]));
			$icc[($msn - 1)] = substr($data, ($pos + 14), $length);
			$offset = ($pos + 14 + $length);
		}
		if (count($icc) > 0) {
			ksort($icc);
			$icc = implode('', $icc);
			if ((ord($icc[36]) != 0x61) OR (ord($icc[37]) != 0x63) OR (ord($icc[38]) != 0x73) OR (ord($icc[39]) != 0x70)) {
				$icc = false;
			}
		} else {
			$icc = false;
		}
		return array('w' => $a[0], 'h' => $a[1], 'ch' => $channels, 'icc' => $icc, 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'DCTDecode', 'data' => $data);
	}
	public static function _parsepng($file) {
		$f = @fopen($file, 'rb');
		if ($f === false) {
			return false;
		}
		if (fread($f, 8) != chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10)) {
			return false;
		}
		fread($f, 4);
		if (fread($f, 4) != 'IHDR') {
			return false;
		}
		$w = TCPDF_STATIC::_freadint($f);
		$h = TCPDF_STATIC::_freadint($f);
		$bpc = ord(fread($f, 1));
		$ct = ord(fread($f, 1));
		if ($ct == 0) {
			$colspace = 'DeviceGray';
		} elseif ($ct == 2) {
			$colspace = 'DeviceRGB';
		} elseif ($ct == 3) {
			$colspace = 'Indexed';
		} else {
			fclose($f);
			return 'pngalpha';
		}
		if (ord(fread($f, 1)) != 0) {
			fclose($f);
			return false;
		}
		if (ord(fread($f, 1)) != 0) {
			fclose($f);
			return false;
		}
		if (ord(fread($f, 1)) != 0) {
			fclose($f);
			return false;
		}
		fread($f, 4);
		$channels = ($ct == 2 ? 3 : 1);
		$parms = '/DecodeParms << /Predictor 15 /Colors '.$channels.' /BitsPerComponent '.$bpc.' /Columns '.$w.' >>';
		$pal = '';
		$trns = '';
		$data = '';
		$icc = false;
		$n = TCPDF_STATIC::_freadint($f);
		do {
			$type = fread($f, 4);
			if ($type == 'PLTE') {
				$pal = TCPDF_STATIC::rfread($f, $n);
				fread($f, 4);
			} elseif ($type == 'tRNS') {
				$t = TCPDF_STATIC::rfread($f, $n);
				if ($ct == 0) {
					$trns = array(ord($t[1]));
				} elseif ($ct == 2) {
					$trns = array(ord($t[1]), ord($t[3]), ord($t[5]));
				} else {
					if ($n > 0) {
						$trns = array();
						for ($i = 0; $i < $n; ++ $i) {
							$trns[] = ord($t[$i]);
						}
					}
				}
				fread($f, 4);
			} elseif ($type == 'IDAT') {
				$data .= TCPDF_STATIC::rfread($f, $n);
				fread($f, 4);
			} elseif ($type == 'iCCP') {
				$len = 0;
				while ((ord(fread($f, 1)) != 0) AND ($len < 80)) {
					++$len;
				}
				if (ord(fread($f, 1)) != 0) {
					fclose($f);
					return false;
				}
				$icc = TCPDF_STATIC::rfread($f, ($n - $len - 2));
				$icc = gzuncompress($icc);
				fread($f, 4);
			} elseif ($type == 'IEND') {
				break;
			} else {
				TCPDF_STATIC::rfread($f, $n + 4);
			}
			$n = TCPDF_STATIC::_freadint($f);
		} while ($n);
		if (($colspace == 'Indexed') AND (empty($pal))) {
			fclose($f);
			return false;
		}
		fclose($f);
		return array('w' => $w, 'h' => $h, 'ch' => $channels, 'icc' => $icc, 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'FlateDecode', 'parms' => $parms, 'pal' => $pal, 'trns' => $trns, 'data' => $data);
	}

}