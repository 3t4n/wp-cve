<?php
//============================================================+
// File name   : tcpdf_barcodes_2d.php
// Version     : 1.0.015
// Begin       : 2009-04-07
// Last Update : 2014-05-20
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2009-2014 Nicola Asuni - Tecnick.com LTD
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
// You should have received a copy of the GNU Lesser General Public License
// along with TCPDF.  If not, see <http://www.gnu.org/licenses/>.
//
// See LICENSE.TXT file for more information.
// -------------------------------------------------------------------
//
// Description : PHP class to creates array representations for
//               2D barcodes to be used with TCPDF.
//
//============================================================+

/**
 * @file
 * PHP class to creates array representations for 2D barcodes to be used with TCPDF.
 * @package com.tecnick.tcpdf
 * @author Nicola Asuni
 * @version 1.0.015
 */

/**
 * @class TCPDF2DBarcode
 * PHP class to creates array representations for 2D barcodes to be used with TCPDF (http://www.tcpdf.org).
 * @package com.tecnick.tcpdf
 * @version 1.0.015
 * @author Nicola Asuni
 */
class TCPDF2DBarcode {
	protected $barcode_array = false;
	public function __construct($code, $type) {
		$this->setBarcode($code, $type);
	}
	public function getBarcodeArray() {
		return $this->barcode_array;
	}
	public function getBarcodeSVG($w=3, $h=3, $color='black') {
		$code = $this->getBarcodeSVGcode($w, $h, $color);
		header('Content-Type: application/svg+xml');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Disposition: inline; filename="'.md5($code).'.svg";');
		echo $code;
	}
	public function getBarcodeSVGcode($w=3, $h=3, $color='black') {
		$repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
		$svg = '<'.'?'.'xml version="1.0" standalone="no"'.'?'.'>'."\n";
		$svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";
		$svg .= '<svg width="'.round(($this->barcode_array['num_cols'] * $w), 3).'" height="'.round(($this->barcode_array['num_rows'] * $h), 3).'" version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n";
		$svg .= "\t".'<desc>'.strtr($this->barcode_array['code'], $repstr).'</desc>'."\n";
		$svg .= "\t".'<g id="elements" fill="'.$color.'" stroke="none">'."\n";
		$y = 0;
		for ($r = 0; $r < $this->barcode_array['num_rows']; ++$r) {
			$x = 0;
			for ($c = 0; $c < $this->barcode_array['num_cols']; ++$c) {
				if ($this->barcode_array['bcode'][$r][$c] == 1) {
					$svg .= "\t\t".'<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" />'."\n";
				}
				$x += $w;
			}
			$y += $h;
		}
		$svg .= "\t".'</g>'."\n";
		$svg .= '</svg>'."\n";
		return $svg;
	}
	public function getBarcodeHTML($w=10, $h=10, $color='black') {
		$html = '<div style="font-size:0;position:relative;width:'.($w * $this->barcode_array['num_cols']).'px;height:'.($h * $this->barcode_array['num_rows']).'px;">'."\n";
		$y = 0;
		for ($r = 0; $r < $this->barcode_array['num_rows']; ++$r) {
			$x = 0;
			for ($c = 0; $c < $this->barcode_array['num_cols']; ++$c) {
				if ($this->barcode_array['bcode'][$r][$c] == 1) {
					$html .= '<div style="background-color:'.$color.';width:'.$w.'px;height:'.$h.'px;position:absolute;left:'.$x.'px;top:'.$y.'px;">&nbsp;</div>'."\n";
				}
				$x += $w;
			}
			$y += $h;
		}
		$html .= '</div>'."\n";
		return $html;
	}
	public function getBarcodePNG($w=3, $h=3, $color=array(0,0,0)) {
		$data = $this->getBarcodePngData($w, $h, $color);
		header('Content-Type: image/png');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		echo $data;

	}
	public function getBarcodePngData($w=3, $h=3, $color=array(0,0,0)) {
		$width = ($this->barcode_array['num_cols'] * $w);
		$height = ($this->barcode_array['num_rows'] * $h);
		if (function_exists('imagecreate')) {
			$imagick = false;
			$png = imagecreate($width, $height);
			$bgcol = imagecolorallocate($png, 255, 255, 255);
			imagecolortransparent($png, $bgcol);
			$fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
		} elseif (extension_loaded('imagick')) {
			$imagick = true;
			$bgcol = new imagickpixel('rgb(255,255,255');
			$fgcol = new imagickpixel('rgb('.$color[0].','.$color[1].','.$color[2].')');
			$png = new Imagick();
			$png->newImage($width, $height, 'none', 'png');
			$bar = new imagickdraw();
			$bar->setfillcolor($fgcol);
		} else {
			return false;
		}
		$y = 0;
		for ($r = 0; $r < $this->barcode_array['num_rows']; ++$r) {
			$x = 0;
			for ($c = 0; $c < $this->barcode_array['num_cols']; ++$c) {
				if ($this->barcode_array['bcode'][$r][$c] == 1) {
					if ($imagick) {
						$bar->rectangle($x, $y, ($x + $w - 1), ($y + $h - 1));
					} else {
						imagefilledrectangle($png, $x, $y, ($x + $w - 1), ($y + $h - 1), $fgcol);
					}
				}
				$x += $w;
			}
			$y += $h;
		}
		if ($imagick) {
			$png->drawimage($bar);
			return $png;
		} else {
			ob_start();
			imagepng($png);
			$imagedata = ob_get_clean();
			imagedestroy($png);
			return $imagedata;
		}
	}
	public function setBarcode($code, $type) {
		$mode = explode(',', $type);
		$qrtype = strtoupper($mode[0]);
		switch ($qrtype) {
			case 'DATAMATRIX': {
				require_once(dirname(__FILE__).'/include/barcodes/datamatrix.php');
				$qrcode = new Datamatrix($code);
				$this->barcode_array = $qrcode->getBarcodeArray();
				$this->barcode_array['code'] = $code;
				break;
			}
			case 'PDF417': {
				require_once(dirname(__FILE__).'/include/barcodes/pdf417.php');
				if (!isset($mode[1]) OR ($mode[1] === '')) {
					$aspectratio = 2;
				} else {
					$aspectratio = floatval($mode[1]);
				}
				if (!isset($mode[2]) OR ($mode[2] === '')) {
					$ecl = -1;
				} else {
					$ecl = intval($mode[2]);
				}
				$macro = array();
				if (isset($mode[3]) AND ($mode[3] !== '') AND isset($mode[4]) AND ($mode[4] !== '') AND isset($mode[5]) AND ($mode[5] !== '')) {
					$macro['segment_total'] = intval($mode[3]);
					$macro['segment_index'] = intval($mode[4]);
					$macro['file_id'] = strtr($mode[5], "\xff", ',');
					for ($i = 0; $i < 7; ++$i) {
						$o = $i + 6;
						if (isset($mode[$o]) AND ($mode[$o] !== '')) {
							$macro['option_'.$i] = strtr($mode[$o], "\xff", ',');
						}
					}
				}
				$qrcode = new PDF417($code, $ecl, $aspectratio, $macro);
				$this->barcode_array = $qrcode->getBarcodeArray();
				$this->barcode_array['code'] = $code;
				break;
			}
			case 'QRCODE': {
				require_once(dirname(__FILE__).'/include/barcodes/qrcode.php');
				if (!isset($mode[1]) OR (!in_array($mode[1],array('L','M','Q','H')))) {
					$mode[1] = 'L';
				}
				$qrcode = new QRcode($code, strtoupper($mode[1]));
				$this->barcode_array = $qrcode->getBarcodeArray();
				$this->barcode_array['code'] = $code;
				break;
			}
			case 'RAW':
			case 'RAW2': {
				$code = preg_replace('/[\s]*/si', '', $code);
				if (strlen($code) < 3) {
					break;
				}
				if ($qrtype == 'RAW') {
					$rows = explode(',', $code);
				} else {
					$code = substr($code, 1, -1);
					$rows = explode('][', $code);
				}
				$this->barcode_array['num_rows'] = count($rows);
				$this->barcode_array['num_cols'] = strlen($rows[0]);
				$this->barcode_array['bcode'] = array();
				foreach ($rows as $r) {
					$this->barcode_array['bcode'][] = str_split($r, 1);
				}
				$this->barcode_array['code'] = $code;
				break;
			}
			case 'TEST': {
				$this->barcode_array['num_rows'] = 5;
				$this->barcode_array['num_cols'] = 15;
				$this->barcode_array['bcode'] = array(
					array(1,1,1,0,1,1,1,0,1,1,1,0,1,1,1),
					array(0,1,0,0,1,0,0,0,1,0,0,0,0,1,0),
					array(0,1,0,0,1,1,0,0,1,1,1,0,0,1,0),
					array(0,1,0,0,1,0,0,0,0,0,1,0,0,1,0),
					array(0,1,0,0,1,1,1,0,1,1,1,0,0,1,0));
				$this->barcode_array['code'] = $code;
				break;
			}
			default: {
				$this->barcode_array = false;
			}
		}
	}
}