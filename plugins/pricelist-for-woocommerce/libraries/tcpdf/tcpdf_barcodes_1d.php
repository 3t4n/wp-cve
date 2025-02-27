<?php
//============================================================+
// File name   : tcpdf_barcodes_1d.php
// Version     : 1.0.027
// Begin       : 2008-06-09
// Last Update : 2014-10-20
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2008-2014 Nicola Asuni - Tecnick.com LTD
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
//               common 1D barcodes to be used with TCPDF.
//
//============================================================+

/**
 * @file
 * PHP class to creates array representations for common 1D barcodes to be used with TCPDF.
 * @package com.tecnick.tcpdf
 * @author Nicola Asuni
 * @version 1.0.027
 */

/**
 * @class TCPDFBarcode
 * PHP class to creates array representations for common 1D barcodes to be used with TCPDF (http://www.tcpdf.org).<br>
 * @package com.tecnick.tcpdf
 * @version 1.0.027
 * @author Nicola Asuni
 */
class TCPDFBarcode {
	protected $barcode_array;
	public function __construct($code, $type) {
		$this->setBarcode($code, $type);
	}
	public function getBarcodeArray() {
		return $this->barcode_array;
	}
	public function getBarcodeSVG($w=2, $h=30, $color='black') {
		$code = $this->getBarcodeSVGcode($w, $h, $color);
		header('Content-Type: application/svg+xml');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Disposition: inline; filename="'.md5($code).'.svg";');
		echo $code;
	}
	public function getBarcodeSVGcode($w=2, $h=30, $color='black') {
		$repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
		$svg = '<'.'?'.'xml version="1.0" standalone="no"'.'?'.'>'."\n";
		$svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";
		$svg .= '<svg width="'.round(($this->barcode_array['maxw'] * $w), 3).'" height="'.$h.'" version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n";
		$svg .= "\t".'<desc>'.strtr($this->barcode_array['code'], $repstr).'</desc>'."\n";
		$svg .= "\t".'<g id="bars" fill="'.$color.'" stroke="none">'."\n";
		$x = 0;
		foreach ($this->barcode_array['bcode'] as $k => $v) {
			$bw = round(($v['w'] * $w), 3);
			$bh = round(($v['h'] * $h / $this->barcode_array['maxh']), 3);
			if ($v['t']) {
				$y = round(($v['p'] * $h / $this->barcode_array['maxh']), 3);
				$svg .= "\t\t".'<rect x="'.$x.'" y="'.$y.'" width="'.$bw.'" height="'.$bh.'" />'."\n";
			}
			$x += $bw;
		}
		$svg .= "\t".'</g>'."\n";
		$svg .= '</svg>'."\n";
		return $svg;
	}
	public function getBarcodeHTML($w=2, $h=30, $color='black') {
		$html = '<div style="font-size:0;position:relative;width:'.($this->barcode_array['maxw'] * $w).'px;height:'.($h).'px;">'."\n";
		$x = 0;
		foreach ($this->barcode_array['bcode'] as $k => $v) {
			$bw = round(($v['w'] * $w), 3);
			$bh = round(($v['h'] * $h / $this->barcode_array['maxh']), 3);
			if ($v['t']) {
				$y = round(($v['p'] * $h / $this->barcode_array['maxh']), 3);
				$html .= '<div style="background-color:'.$color.';width:'.$bw.'px;height:'.$bh.'px;position:absolute;left:'.$x.'px;top:'.$y.'px;">&nbsp;</div>'."\n";
			}
			$x += $bw;
		}
		$html .= '</div>'."\n";
		return $html;
	}
	public function getBarcodePNG($w=2, $h=30, $color=array(0,0,0)) {
		$data = $this->getBarcodePngData($w, $h, $color);
		header('Content-Type: image/png');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		echo $data;
	}
	public function getBarcodePngData($w=2, $h=30, $color=array(0,0,0)) {
		$width = ($this->barcode_array['maxw'] * $w);
		$height = $h;
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
		$x = 0;
		foreach ($this->barcode_array['bcode'] as $k => $v) {
			$bw = round(($v['w'] * $w), 3);
			$bh = round(($v['h'] * $h / $this->barcode_array['maxh']), 3);
			if ($v['t']) {
				$y = round(($v['p'] * $h / $this->barcode_array['maxh']), 3);
				if ($imagick) {
					$bar->rectangle($x, $y, ($x + $bw - 1), ($y + $bh - 1));
				} else {
					imagefilledrectangle($png, $x, $y, ($x + $bw - 1), ($y + $bh - 1), $fgcol);
				}
			}
			$x += $bw;
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
		switch (strtoupper($type)) {
			case 'C39': {
				$arrcode = $this->barcode_code39($code, false, false);
				break;
			}
			case 'C39+': {
				$arrcode = $this->barcode_code39($code, false, true);
				break;
			}
			case 'C39E': {
				$arrcode = $this->barcode_code39($code, true, false);
				break;
			}
			case 'C39E+': {
				$arrcode = $this->barcode_code39($code, true, true);
				break;
			}
			case 'C93': {
				$arrcode = $this->barcode_code93($code);
				break;
			}
			case 'S25': {
				$arrcode = $this->barcode_s25($code, false);
				break;
			}
			case 'S25+': {
				$arrcode = $this->barcode_s25($code, true);
				break;
			}
			case 'I25': {
				$arrcode = $this->barcode_i25($code, false);
				break;
			}
			case 'I25+': {
				$arrcode = $this->barcode_i25($code, true);
				break;
			}
			case 'C128': {
				$arrcode = $this->barcode_c128($code, '');
				break;
			}
			case 'C128A': {
				$arrcode = $this->barcode_c128($code, 'A');
				break;
			}
			case 'C128B': {
				$arrcode = $this->barcode_c128($code, 'B');
				break;
			}
			case 'C128C': {
				$arrcode = $this->barcode_c128($code, 'C');
				break;
			}
			case 'EAN2': {
				$arrcode = $this->barcode_eanext($code, 2);
				break;
			}
			case 'EAN5': {
				$arrcode = $this->barcode_eanext($code, 5);
				break;
			}
			case 'EAN8': {
				$arrcode = $this->barcode_eanupc($code, 8);
				break;
			}
			case 'EAN13': {
				$arrcode = $this->barcode_eanupc($code, 13);
				break;
			}
			case 'UPCA': {
				$arrcode = $this->barcode_eanupc($code, 12);
				break;
			}
			case 'UPCE': {
				$arrcode = $this->barcode_eanupc($code, 6);
				break;
			}
			case 'MSI': {
				$arrcode = $this->barcode_msi($code, false);
				break;
			}
			case 'MSI+': {
				$arrcode = $this->barcode_msi($code, true);
				break;
			}
			case 'POSTNET': {
				$arrcode = $this->barcode_postnet($code, false);
				break;
			}
			case 'PLANET': {
				$arrcode = $this->barcode_postnet($code, true);
				break;
			}
			case 'RMS4CC': {
				$arrcode = $this->barcode_rms4cc($code, false);
				break;
			}
			case 'KIX': {
				$arrcode = $this->barcode_rms4cc($code, true);
				break;
			}
			case 'IMB': {
				$arrcode = $this->barcode_imb($code);
				break;
			}
			case 'IMBPRE': {
				$arrcode = $this->barcode_imb_pre($code);
				break;
			}
			case 'CODABAR': {
				$arrcode = $this->barcode_codabar($code);
				break;
			}
			case 'CODE11': {
				$arrcode = $this->barcode_code11($code);
				break;
			}
			case 'PHARMA': {
				$arrcode = $this->barcode_pharmacode($code);
				break;
			}
			case 'PHARMA2T': {
				$arrcode = $this->barcode_pharmacode2t($code);
				break;
			}
			default: {
				$this->barcode_array = false;
				$arrcode = false;
				break;
			}
		}
		$this->barcode_array = $arrcode;
	}
	protected function barcode_code39($code, $extended=false, $checksum=false) {
		$chr['0'] = '111331311';
		$chr['1'] = '311311113';
		$chr['2'] = '113311113';
		$chr['3'] = '313311111';
		$chr['4'] = '111331113';
		$chr['5'] = '311331111';
		$chr['6'] = '113331111';
		$chr['7'] = '111311313';
		$chr['8'] = '311311311';
		$chr['9'] = '113311311';
		$chr['A'] = '311113113';
		$chr['B'] = '113113113';
		$chr['C'] = '313113111';
		$chr['D'] = '111133113';
		$chr['E'] = '311133111';
		$chr['F'] = '113133111';
		$chr['G'] = '111113313';
		$chr['H'] = '311113311';
		$chr['I'] = '113113311';
		$chr['J'] = '111133311';
		$chr['K'] = '311111133';
		$chr['L'] = '113111133';
		$chr['M'] = '313111131';
		$chr['N'] = '111131133';
		$chr['O'] = '311131131';
		$chr['P'] = '113131131';
		$chr['Q'] = '111111333';
		$chr['R'] = '311111331';
		$chr['S'] = '113111331';
		$chr['T'] = '111131331';
		$chr['U'] = '331111113';
		$chr['V'] = '133111113';
		$chr['W'] = '333111111';
		$chr['X'] = '131131113';
		$chr['Y'] = '331131111';
		$chr['Z'] = '133131111';
		$chr['-'] = '131111313';
		$chr['.'] = '331111311';
		$chr[' '] = '133111311';
		$chr['$'] = '131313111';
		$chr['/'] = '131311131';
		$chr['+'] = '131113131';
		$chr['%'] = '111313131';
		$chr['*'] = '131131311';
		$code = strtoupper($code);
		if ($extended) {
			$code = $this->encode_code39_ext($code);
		}
		if ($code === false) {
			return false;
		}
		if ($checksum) {
			$code .= $this->checksum_code39($code);
		}
		$code = '*'.$code.'*';
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$clen = strlen($code);
		for ($i = 0; $i < $clen; ++$i) {
			$char = $code[$i];
			if(!isset($chr[$char])) {
				return false;
			}
			for ($j = 0; $j < 9; ++$j) {
				if (($j % 2) == 0) {
					$t = true;
				} else {
					$t = false;
				}
				$w = $chr[$char][$j];
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				++$k;
			}
			$bararray['bcode'][$k] = array('t' => false, 'w' => 1, 'h' => 1, 'p' => 0);
			$bararray['maxw'] += 1;
			++$k;
		}
		return $bararray;
	}
	protected function encode_code39_ext($code) {
		$encode = array(
			chr(0) => '%U', chr(1) => '$A', chr(2) => '$B', chr(3) => '$C',
			chr(4) => '$D', chr(5) => '$E', chr(6) => '$F', chr(7) => '$G',
			chr(8) => '$H', chr(9) => '$I', chr(10) => '$J', chr(11) => '£K',
			chr(12) => '$L', chr(13) => '$M', chr(14) => '$N', chr(15) => '$O',
			chr(16) => '$P', chr(17) => '$Q', chr(18) => '$R', chr(19) => '$S',
			chr(20) => '$T', chr(21) => '$U', chr(22) => '$V', chr(23) => '$W',
			chr(24) => '$X', chr(25) => '$Y', chr(26) => '$Z', chr(27) => '%A',
			chr(28) => '%B', chr(29) => '%C', chr(30) => '%D', chr(31) => '%E',
			chr(32) => ' ', chr(33) => '/A', chr(34) => '/B', chr(35) => '/C',
			chr(36) => '/D', chr(37) => '/E', chr(38) => '/F', chr(39) => '/G',
			chr(40) => '/H', chr(41) => '/I', chr(42) => '/J', chr(43) => '/K',
			chr(44) => '/L', chr(45) => '-', chr(46) => '.', chr(47) => '/O',
			chr(48) => '0', chr(49) => '1', chr(50) => '2', chr(51) => '3',
			chr(52) => '4', chr(53) => '5', chr(54) => '6', chr(55) => '7',
			chr(56) => '8', chr(57) => '9', chr(58) => '/Z', chr(59) => '%F',
			chr(60) => '%G', chr(61) => '%H', chr(62) => '%I', chr(63) => '%J',
			chr(64) => '%V', chr(65) => 'A', chr(66) => 'B', chr(67) => 'C',
			chr(68) => 'D', chr(69) => 'E', chr(70) => 'F', chr(71) => 'G',
			chr(72) => 'H', chr(73) => 'I', chr(74) => 'J', chr(75) => 'K',
			chr(76) => 'L', chr(77) => 'M', chr(78) => 'N', chr(79) => 'O',
			chr(80) => 'P', chr(81) => 'Q', chr(82) => 'R', chr(83) => 'S',
			chr(84) => 'T', chr(85) => 'U', chr(86) => 'V', chr(87) => 'W',
			chr(88) => 'X', chr(89) => 'Y', chr(90) => 'Z', chr(91) => '%K',
			chr(92) => '%L', chr(93) => '%M', chr(94) => '%N', chr(95) => '%O',
			chr(96) => '%W', chr(97) => '+A', chr(98) => '+B', chr(99) => '+C',
			chr(100) => '+D', chr(101) => '+E', chr(102) => '+F', chr(103) => '+G',
			chr(104) => '+H', chr(105) => '+I', chr(106) => '+J', chr(107) => '+K',
			chr(108) => '+L', chr(109) => '+M', chr(110) => '+N', chr(111) => '+O',
			chr(112) => '+P', chr(113) => '+Q', chr(114) => '+R', chr(115) => '+S',
			chr(116) => '+T', chr(117) => '+U', chr(118) => '+V', chr(119) => '+W',
			chr(120) => '+X', chr(121) => '+Y', chr(122) => '+Z', chr(123) => '%P',
			chr(124) => '%Q', chr(125) => '%R', chr(126) => '%S', chr(127) => '%T');
		$code_ext = '';
		$clen = strlen($code);
		for ($i = 0 ; $i < $clen; ++$i) {
			if (ord($code[$i]) > 127) {
				return false;
			}
			$code_ext .= $encode[$code[$i]];
		}
		return $code_ext;
	}
	protected function checksum_code39($code) {
		$chars = array(
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
			'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
			'W', 'X', 'Y', 'Z', '-', '.', ' ', '$', '/', '+', '%');
		$sum = 0;
		$clen = strlen($code);
		for ($i = 0 ; $i < $clen; ++$i) {
			$k = array_keys($chars, $code[$i]);
			$sum += $k[0];
		}
		$j = ($sum % 43);
		return $chars[$j];
	}
	protected function barcode_code93($code) {
		$chr[48] = '131112';
		$chr[49] = '111213';
		$chr[50] = '111312';
		$chr[51] = '111411';
		$chr[52] = '121113';
		$chr[53] = '121212';
		$chr[54] = '121311';
		$chr[55] = '111114';
		$chr[56] = '131211';
		$chr[57] = '141111';
		$chr[65] = '211113';
		$chr[66] = '211212';
		$chr[67] = '211311';
		$chr[68] = '221112';
		$chr[69] = '221211';
		$chr[70] = '231111';
		$chr[71] = '112113';
		$chr[72] = '112212';
		$chr[73] = '112311';
		$chr[74] = '122112';
		$chr[75] = '132111';
		$chr[76] = '111123';
		$chr[77] = '111222';
		$chr[78] = '111321';
		$chr[79] = '121122';
		$chr[80] = '131121';
		$chr[81] = '212112';
		$chr[82] = '212211';
		$chr[83] = '211122';
		$chr[84] = '211221';
		$chr[85] = '221121';
		$chr[86] = '222111';
		$chr[87] = '112122';
		$chr[88] = '112221';
		$chr[89] = '122121';
		$chr[90] = '123111';
		$chr[45] = '121131';
		$chr[46] = '311112';
		$chr[32] = '311211';
		$chr[36] = '321111';
		$chr[47] = '112131';
		$chr[43] = '113121';
		$chr[37] = '211131';
		$chr[128] = '121221';
		$chr[129] = '311121';
		$chr[130] = '122211';
		$chr[131] = '312111';
		$chr[42] = '111141';
		$code = strtoupper($code);
		$encode = array(
			chr(0) => chr(131).'U', chr(1) => chr(128).'A', chr(2) => chr(128).'B', chr(3) => chr(128).'C',
			chr(4) => chr(128).'D', chr(5) => chr(128).'E', chr(6) => chr(128).'F', chr(7) => chr(128).'G',
			chr(8) => chr(128).'H', chr(9) => chr(128).'I', chr(10) => chr(128).'J', chr(11) => '£K',
			chr(12) => chr(128).'L', chr(13) => chr(128).'M', chr(14) => chr(128).'N', chr(15) => chr(128).'O',
			chr(16) => chr(128).'P', chr(17) => chr(128).'Q', chr(18) => chr(128).'R', chr(19) => chr(128).'S',
			chr(20) => chr(128).'T', chr(21) => chr(128).'U', chr(22) => chr(128).'V', chr(23) => chr(128).'W',
			chr(24) => chr(128).'X', chr(25) => chr(128).'Y', chr(26) => chr(128).'Z', chr(27) => chr(131).'A',
			chr(28) => chr(131).'B', chr(29) => chr(131).'C', chr(30) => chr(131).'D', chr(31) => chr(131).'E',
			chr(32) => ' ', chr(33) => chr(129).'A', chr(34) => chr(129).'B', chr(35) => chr(129).'C',
			chr(36) => chr(129).'D', chr(37) => chr(129).'E', chr(38) => chr(129).'F', chr(39) => chr(129).'G',
			chr(40) => chr(129).'H', chr(41) => chr(129).'I', chr(42) => chr(129).'J', chr(43) => chr(129).'K',
			chr(44) => chr(129).'L', chr(45) => '-', chr(46) => '.', chr(47) => chr(129).'O',
			chr(48) => '0', chr(49) => '1', chr(50) => '2', chr(51) => '3',
			chr(52) => '4', chr(53) => '5', chr(54) => '6', chr(55) => '7',
			chr(56) => '8', chr(57) => '9', chr(58) => chr(129).'Z', chr(59) => chr(131).'F',
			chr(60) => chr(131).'G', chr(61) => chr(131).'H', chr(62) => chr(131).'I', chr(63) => chr(131).'J',
			chr(64) => chr(131).'V', chr(65) => 'A', chr(66) => 'B', chr(67) => 'C',
			chr(68) => 'D', chr(69) => 'E', chr(70) => 'F', chr(71) => 'G',
			chr(72) => 'H', chr(73) => 'I', chr(74) => 'J', chr(75) => 'K',
			chr(76) => 'L', chr(77) => 'M', chr(78) => 'N', chr(79) => 'O',
			chr(80) => 'P', chr(81) => 'Q', chr(82) => 'R', chr(83) => 'S',
			chr(84) => 'T', chr(85) => 'U', chr(86) => 'V', chr(87) => 'W',
			chr(88) => 'X', chr(89) => 'Y', chr(90) => 'Z', chr(91) => chr(131).'K',
			chr(92) => chr(131).'L', chr(93) => chr(131).'M', chr(94) => chr(131).'N', chr(95) => chr(131).'O',
			chr(96) => chr(131).'W', chr(97) => chr(130).'A', chr(98) => chr(130).'B', chr(99) => chr(130).'C',
			chr(100) => chr(130).'D', chr(101) => chr(130).'E', chr(102) => chr(130).'F', chr(103) => chr(130).'G',
			chr(104) => chr(130).'H', chr(105) => chr(130).'I', chr(106) => chr(130).'J', chr(107) => chr(130).'K',
			chr(108) => chr(130).'L', chr(109) => chr(130).'M', chr(110) => chr(130).'N', chr(111) => chr(130).'O',
			chr(112) => chr(130).'P', chr(113) => chr(130).'Q', chr(114) => chr(130).'R', chr(115) => chr(130).'S',
			chr(116) => chr(130).'T', chr(117) => chr(130).'U', chr(118) => chr(130).'V', chr(119) => chr(130).'W',
			chr(120) => chr(130).'X', chr(121) => chr(130).'Y', chr(122) => chr(130).'Z', chr(123) => chr(131).'P',
			chr(124) => chr(131).'Q', chr(125) => chr(131).'R', chr(126) => chr(131).'S', chr(127) => chr(131).'T');
		$code_ext = '';
		$clen = strlen($code);
		for ($i = 0 ; $i < $clen; ++$i) {
			if (ord($code[$i]) > 127) {
				return false;
			}
			$code_ext .= $encode[$code[$i]];
		}
		$code_ext .= $this->checksum_code93($code_ext);
		$code = '*'.$code_ext.'*';
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$clen = strlen($code);
		for ($i = 0; $i < $clen; ++$i) {
			$char = ord($code[$i]);
			if(!isset($chr[$char])) {
				return false;
			}
			for ($j = 0; $j < 6; ++$j) {
				if (($j % 2) == 0) {
					$t = true;
				} else {
					$t = false;
				}
				$w = $chr[$char][$j];
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				++$k;
			}
		}
		$bararray['bcode'][$k] = array('t' => true, 'w' => 1, 'h' => 1, 'p' => 0);
		$bararray['maxw'] += 1;
		++$k;
		return $bararray;
	}
	protected function checksum_code93($code) {
		$chars = array(
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
			'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
			'W', 'X', 'Y', 'Z', '-', '.', ' ', '$', '/', '+', '%',
			'<', '=', '>', '?');
		$code = strtr($code, chr(128).chr(131).chr(129).chr(130), '<=>?');
		$len = strlen($code);
		$p = 1;
		$check = 0;
		for ($i = ($len - 1); $i >= 0; --$i) {
			$k = array_keys($chars, $code[$i]);
			$check += ($k[0] * $p);
			++$p;
			if ($p > 20) {
				$p = 1;
			}
		}
		$check %= 47;
		$c = $chars[$check];
		$code .= $c;
		$p = 1;
		$check = 0;
		for ($i = $len; $i >= 0; --$i) {
			$k = array_keys($chars, $code[$i]);
			$check += ($k[0] * $p);
			++$p;
			if ($p > 15) {
				$p = 1;
			}
		}
		$check %= 47;
		$k = $chars[$check];
		$checksum = $c.$k;
		$checksum = strtr($checksum, '<=>?', chr(128).chr(131).chr(129).chr(130));
		return $checksum;
	}
	protected function checksum_s25($code) {
		$len = strlen($code);
		$sum = 0;
		for ($i = 0; $i < $len; $i+=2) {
			$sum += $code[$i];
		}
		$sum *= 3;
		for ($i = 1; $i < $len; $i+=2) {
			$sum += ($code[$i]);
		}
		$r = $sum % 10;
		if($r > 0) {
			$r = (10 - $r);
		}
		return $r;
	}
	protected function barcode_msi($code, $checksum=false) {
		$chr['0'] = '100100100100';
		$chr['1'] = '100100100110';
		$chr['2'] = '100100110100';
		$chr['3'] = '100100110110';
		$chr['4'] = '100110100100';
		$chr['5'] = '100110100110';
		$chr['6'] = '100110110100';
		$chr['7'] = '100110110110';
		$chr['8'] = '110100100100';
		$chr['9'] = '110100100110';
		$chr['A'] = '110100110100';
		$chr['B'] = '110100110110';
		$chr['C'] = '110110100100';
		$chr['D'] = '110110100110';
		$chr['E'] = '110110110100';
		$chr['F'] = '110110110110';
		if ($checksum) {
			$clen = strlen($code);
			$p = 2;
			$check = 0;
			for ($i = ($clen - 1); $i >= 0; --$i) {
				$check += (hexdec($code[$i]) * $p);
				++$p;
				if ($p > 7) {
					$p = 2;
				}
			}
			$check %= 11;
			if ($check > 0) {
				$check = 11 - $check;
			}
			$code .= $check;
		}
		$seq = '110';
		$clen = strlen($code);
		for ($i = 0; $i < $clen; ++$i) {
			$digit = $code[$i];
			if (!isset($chr[$digit])) {
				return false;
			}
			$seq .= $chr[$digit];
		}
		$seq .= '1001';
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		return $this->binseq_to_array($seq, $bararray);
	}
	protected function barcode_s25($code, $checksum=false) {
		$chr['0'] = '10101110111010';
		$chr['1'] = '11101010101110';
		$chr['2'] = '10111010101110';
		$chr['3'] = '11101110101010';
		$chr['4'] = '10101110101110';
		$chr['5'] = '11101011101010';
		$chr['6'] = '10111011101010';
		$chr['7'] = '10101011101110';
		$chr['8'] = '10101110111010';
		$chr['9'] = '10111010111010';
		if ($checksum) {
			$code .= $this->checksum_s25($code);
		}
		if((strlen($code) % 2) != 0) {
			$code = '0'.$code;
		}
		$seq = '11011010';
		$clen = strlen($code);
		for ($i = 0; $i < $clen; ++$i) {
			$digit = $code[$i];
			if (!isset($chr[$digit])) {
				return false;
			}
			$seq .= $chr[$digit];
		}
		$seq .= '1101011';
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		return $this->binseq_to_array($seq, $bararray);
	}
	protected function binseq_to_array($seq, $bararray) {
		$len = strlen($seq);
		$w = 0;
		$k = 0;
		for ($i = 0; $i < $len; ++$i) {
			$w += 1;
			if (($i == ($len - 1)) OR (($i < ($len - 1)) AND ($seq[$i] != $seq[($i+1)]))) {
				if ($seq[$i] == '1') {
					$t = true;
				} else {
					$t = false;
				}
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				++$k;
				$w = 0;
			}
		}
		return $bararray;
	}
	protected function barcode_i25($code, $checksum=false) {
		$chr['0'] = '11221';
		$chr['1'] = '21112';
		$chr['2'] = '12112';
		$chr['3'] = '22111';
		$chr['4'] = '11212';
		$chr['5'] = '21211';
		$chr['6'] = '12211';
		$chr['7'] = '11122';
		$chr['8'] = '21121';
		$chr['9'] = '12121';
		$chr['A'] = '11';
		$chr['Z'] = '21';
		if ($checksum) {
			$code .= $this->checksum_s25($code);
		}
		if((strlen($code) % 2) != 0) {
			$code = '0'.$code;
		}
		$code = 'AA'.strtolower($code).'ZA';

		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$clen = strlen($code);
		for ($i = 0; $i < $clen; $i = ($i + 2)) {
			$char_bar = $code[$i];
			$char_space = $code[$i+1];
			if((!isset($chr[$char_bar])) OR (!isset($chr[$char_space]))) {
				return false;
			}
			$seq = '';
			$chrlen = strlen($chr[$char_bar]);
			for ($s = 0; $s < $chrlen; $s++){
				$seq .= $chr[$char_bar][$s] . $chr[$char_space][$s];
			}
			$seqlen = strlen($seq);
			for ($j = 0; $j < $seqlen; ++$j) {
				if (($j % 2) == 0) {
					$t = true;
				} else {
					$t = false;
				}
				$w = (float)$seq[$j];
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				++$k;
			}
		}
		return $bararray;
	}
	protected function barcode_c128($code, $type='') {
		$chr = array(
			'212222',
			'222122',
			'222221',
			'121223',
			'121322',
			'131222',
			'122213',
			'122312',
			'132212',
			'221213',
			'221312',
			'231212',
			'112232',
			'122132',
			'122231',
			'113222',
			'123122',
			'123221',
			'223211',
			'221132',
			'221231',
			'213212',
			'223112',
			'312131',
			'311222',
			'321122',
			'321221',
			'312212',
			'322112',
			'322211',
			'212123',
			'212321',
			'232121',
			'111323',
			'131123',
			'131321',
			'112313',
			'132113',
			'132311',
			'211313',
			'231113',
			'231311',
			'112133',
			'112331',
			'132131',
			'113123',
			'113321',
			'133121',
			'313121',
			'211331',
			'231131',
			'213113',
			'213311',
			'213131',
			'311123',
			'311321',
			'331121',
			'312113',
			'312311',
			'332111',
			'314111',
			'221411',
			'431111',
			'111224',
			'111422',
			'121124',
			'121421',
			'141122',
			'141221',
			'112214',
			'112412',
			'122114',
			'122411',
			'142112',
			'142211',
			'241211',
			'221114',
			'413111',
			'241112',
			'134111',
			'111242',
			'121142',
			'121241',
			'114212',
			'124112',
			'124211',
			'411212',
			'421112',
			'421211',
			'212141',
			'214121',
			'412121',
			'111143',
			'111341',
			'131141',
			'114113',
			'114311',
			'411113',
			'411311',
			'113141',
			'114131',
			'311141',
			'411131',
			'211412',
			'211214',
			'211232',
			'233111',
			'200000'
		);
		$keys_a = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_';
		$keys_a .= chr(0).chr(1).chr(2).chr(3).chr(4).chr(5).chr(6).chr(7).chr(8).chr(9);
		$keys_a .= chr(10).chr(11).chr(12).chr(13).chr(14).chr(15).chr(16).chr(17).chr(18).chr(19);
		$keys_a .= chr(20).chr(21).chr(22).chr(23).chr(24).chr(25).chr(26).chr(27).chr(28).chr(29);
		$keys_a .= chr(30).chr(31);
		$keys_b = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~'.chr(127);
		$fnc_a = array(241 => 102, 242 => 97, 243 => 96, 244 => 101);
		$fnc_b = array(241 => 102, 242 => 97, 243 => 96, 244 => 100);
		$code_data = array();
		$len = strlen($code);
		switch(strtoupper($type)) {
			case 'A': {
				$startid = 103;
				for ($i = 0; $i < $len; ++$i) {
					$char = $code[$i];
					$char_id = ord($char);
					if (($char_id >= 241) AND ($char_id <= 244)) {
						$code_data[] = $fnc_a[$char_id];
					} elseif (($char_id >= 0) AND ($char_id <= 95)) {
						$code_data[] = strpos($keys_a, $char);
					} else {
						return false;
					}
				}
				break;
			}
			case 'B': {
				$startid = 104;
				for ($i = 0; $i < $len; ++$i) {
					$char = $code[$i];
					$char_id = ord($char);
					if (($char_id >= 241) AND ($char_id <= 244)) {
						$code_data[] = $fnc_b[$char_id];
					} elseif (($char_id >= 32) AND ($char_id <= 127)) {
						$code_data[] = strpos($keys_b, $char);
					} else {
						return false;
					}
				}
				break;
			}
			case 'C': {
				$startid = 105;
				if (ord($code[0]) == 241) {
					$code_data[] = 102;
					$code = substr($code, 1);
					--$len;
				}
				if (($len % 2) != 0) {
					return false;
				}
				for ($i = 0; $i < $len; $i+=2) {
					$chrnum = $code[$i].$code[$i+1];
					if (preg_match('/([0-9]{2})/', $chrnum) > 0) {
						$code_data[] = intval($chrnum);
					} else {
						return false;
					}
				}
				break;
			}
			default: {
				$sequence = array();
				$numseq = array();
				preg_match_all('/([0-9]{4,})/', $code, $numseq, PREG_OFFSET_CAPTURE);
				if (isset($numseq[1]) AND !empty($numseq[1])) {
					$end_offset = 0;
					foreach ($numseq[1] as $val) {
						$offset = $val[1];
						if ($offset > $end_offset) {
							$sequence = array_merge($sequence, $this->get128ABsequence(substr($code, $end_offset, ($offset - $end_offset))));
						}
						$slen = strlen($val[0]);
						if (($slen % 2) != 0) {
							--$slen;
						}
						$sequence[] = array('C', substr($code, $offset, $slen), $slen);
						$end_offset = $offset + $slen;
					}
					if ($end_offset < $len) {
						$sequence = array_merge($sequence, $this->get128ABsequence(substr($code, $end_offset)));
					}
				} else {
					$sequence = array_merge($sequence, $this->get128ABsequence($code));
				}
				foreach ($sequence as $key => $seq) {
					switch($seq[0]) {
						case 'A': {
							if ($key == 0) {
								$startid = 103;
							} elseif ($sequence[($key - 1)][0] != 'A') {
								if (($seq[2] == 1) AND ($key > 0) AND ($sequence[($key - 1)][0] == 'B') AND (!isset($sequence[($key - 1)][3]))) {
									$code_data[] = 98;
									$sequence[$key][3] = true;
								} elseif (!isset($sequence[($key - 1)][3])) {
									$code_data[] = 101;
								}
							}
							for ($i = 0; $i < $seq[2]; ++$i) {
								$char = $seq[1][$i];
								$char_id = ord($char);
								if (($char_id >= 241) AND ($char_id <= 244)) {
									$code_data[] = $fnc_a[$char_id];
								} else {
									$code_data[] = strpos($keys_a, $char);
								}
							}
							break;
						}
						case 'B': {
							if ($key == 0) {
								$tmpchr = ord($seq[1][0]);
								if (($seq[2] == 1) AND ($tmpchr >= 241) AND ($tmpchr <= 244) AND isset($sequence[($key + 1)]) AND ($sequence[($key + 1)][0] != 'B')) {
									switch ($sequence[($key + 1)][0]) {
										case 'A': {
											$startid = 103;
											$sequence[$key][0] = 'A';
											$code_data[] = $fnc_a[$tmpchr];
											break;
										}
										case 'C': {
											$startid = 105;
											$sequence[$key][0] = 'C';
											$code_data[] = $fnc_a[$tmpchr];
											break;
										}
									}
									break;
								} else {
									$startid = 104;
								}
							} elseif ($sequence[($key - 1)][0] != 'B') {
								if (($seq[2] == 1) AND ($key > 0) AND ($sequence[($key - 1)][0] == 'A') AND (!isset($sequence[($key - 1)][3]))) {
									$code_data[] = 98;
									$sequence[$key][3] = true;
								} elseif (!isset($sequence[($key - 1)][3])) {
									$code_data[] = 100;
								}
							}
							for ($i = 0; $i < $seq[2]; ++$i) {
								$char = $seq[1][$i];
								$char_id = ord($char);
								if (($char_id >= 241) AND ($char_id <= 244)) {
									$code_data[] = $fnc_b[$char_id];
								} else {
									$code_data[] = strpos($keys_b, $char);
								}
							}
							break;
						}
						case 'C': {
							if ($key == 0) {
								$startid = 105;
							} elseif ($sequence[($key - 1)][0] != 'C') {
								$code_data[] = 99;
							}
							for ($i = 0; $i < $seq[2]; $i+=2) {
								$chrnum = $seq[1][$i].$seq[1][$i+1];
								$code_data[] = intval($chrnum);
							}
							break;
						}
					}
				}
			}
		}
		$sum = $startid;
		foreach ($code_data as $key => $val) {
			$sum += ($val * ($key + 1));
		}
		$code_data[] = ($sum % 103);
		$code_data[] = 106;
		$code_data[] = 107;
		array_unshift($code_data, $startid);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		foreach ($code_data as $val) {
			$seq = $chr[$val];
			for ($j = 0; $j < 6; ++$j) {
				if (($j % 2) == 0) {
					$t = true;
				} else {
					$t = false;
				}
				$w = (float)$seq[$j];
				$bararray['bcode'][] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
			}
		}
		return $bararray;
	}
	protected function get128ABsequence($code) {
		$len = strlen($code);
		$sequence = array();
		$numseq = array();
		preg_match_all('/([\0-\31])/', $code, $numseq, PREG_OFFSET_CAPTURE);
		if (isset($numseq[1]) AND !empty($numseq[1])) {
			$end_offset = 0;
			foreach ($numseq[1] as $val) {
				$offset = $val[1];
				if ($offset > $end_offset) {
					$sequence[] = array('B', substr($code, $end_offset, ($offset - $end_offset)), ($offset - $end_offset));
				}
				$slen = strlen($val[0]);
				$sequence[] = array('A', substr($code, $offset, $slen), $slen);
				$end_offset = $offset + $slen;
			}
			if ($end_offset < $len) {
				$sequence[] = array('B', substr($code, $end_offset), ($len - $end_offset));
			}
		} else {
			$sequence[] = array('B', $code, $len);
		}
		return $sequence;
	}
	protected function barcode_eanupc($code, $len=13) {
		$upce = false;
		if ($len == 6) {
			$len = 12;
			$upce = true;
		}
		$data_len = $len - 1;
		$code = str_pad($code, $data_len, '0', STR_PAD_LEFT);
		$code_len = strlen($code);
		$sum_a = 0;
		for ($i = 1; $i < $data_len; $i+=2) {
			$sum_a += $code[$i];
		}
		if ($len > 12) {
			$sum_a *= 3;
		}
		$sum_b = 0;
		for ($i = 0; $i < $data_len; $i+=2) {
			$sum_b += ($code[$i]);
		}
		if ($len < 13) {
			$sum_b *= 3;
		}
		$r = ($sum_a + $sum_b) % 10;
		if($r > 0) {
			$r = (10 - $r);
		}
		if ($code_len == $data_len) {
			$code .= $r;
		} elseif ($r !== intval($code[$data_len])) {
			return false;
		}
		if ($len == 12) {
			$code = '0'.$code;
			++$len;
		}
		if ($upce) {
			$tmp = substr($code, 4, 3);
			if (($tmp == '000') OR ($tmp == '100') OR ($tmp == '200')) {
				$upce_code = substr($code, 2, 2).substr($code, 9, 3).substr($code, 4, 1);
			} else {
				$tmp = substr($code, 5, 2);
				if ($tmp == '00') {
					$upce_code = substr($code, 2, 3).substr($code, 10, 2).'3';
				} else {
					$tmp = substr($code, 6, 1);
					if ($tmp == '0') {
						$upce_code = substr($code, 2, 4).substr($code, 11, 1).'4';
					} else {
						$upce_code = substr($code, 2, 5).substr($code, 11, 1);
					}
				}
			}
		}
		$codes = array(
			'A'=>array(
				'0'=>'0001101',
				'1'=>'0011001',
				'2'=>'0010011',
				'3'=>'0111101',
				'4'=>'0100011',
				'5'=>'0110001',
				'6'=>'0101111',
				'7'=>'0111011',
				'8'=>'0110111',
				'9'=>'0001011'),
			'B'=>array(
				'0'=>'0100111',
				'1'=>'0110011',
				'2'=>'0011011',
				'3'=>'0100001',
				'4'=>'0011101',
				'5'=>'0111001',
				'6'=>'0000101',
				'7'=>'0010001',
				'8'=>'0001001',
				'9'=>'0010111'),
			'C'=>array(
				'0'=>'1110010',
				'1'=>'1100110',
				'2'=>'1101100',
				'3'=>'1000010',
				'4'=>'1011100',
				'5'=>'1001110',
				'6'=>'1010000',
				'7'=>'1000100',
				'8'=>'1001000',
				'9'=>'1110100')
		);
		$parities = array(
			'0'=>array('A','A','A','A','A','A'),
			'1'=>array('A','A','B','A','B','B'),
			'2'=>array('A','A','B','B','A','B'),
			'3'=>array('A','A','B','B','B','A'),
			'4'=>array('A','B','A','A','B','B'),
			'5'=>array('A','B','B','A','A','B'),
			'6'=>array('A','B','B','B','A','A'),
			'7'=>array('A','B','A','B','A','B'),
			'8'=>array('A','B','A','B','B','A'),
			'9'=>array('A','B','B','A','B','A')
		);
		$upce_parities = array();
		$upce_parities[0] = array(
			'0'=>array('B','B','B','A','A','A'),
			'1'=>array('B','B','A','B','A','A'),
			'2'=>array('B','B','A','A','B','A'),
			'3'=>array('B','B','A','A','A','B'),
			'4'=>array('B','A','B','B','A','A'),
			'5'=>array('B','A','A','B','B','A'),
			'6'=>array('B','A','A','A','B','B'),
			'7'=>array('B','A','B','A','B','A'),
			'8'=>array('B','A','B','A','A','B'),
			'9'=>array('B','A','A','B','A','B')
		);
		$upce_parities[1] = array(
			'0'=>array('A','A','A','B','B','B'),
			'1'=>array('A','A','B','A','B','B'),
			'2'=>array('A','A','B','B','A','B'),
			'3'=>array('A','A','B','B','B','A'),
			'4'=>array('A','B','A','A','B','B'),
			'5'=>array('A','B','B','A','A','B'),
			'6'=>array('A','B','B','B','A','A'),
			'7'=>array('A','B','A','B','A','B'),
			'8'=>array('A','B','A','B','B','A'),
			'9'=>array('A','B','B','A','B','A')
		);
		$k = 0;
		$seq = '101';
		if ($upce) {
			$bararray = array('code' => $upce_code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
			$p = $upce_parities[$code[1]][$r];
			for ($i = 0; $i < 6; ++$i) {
				$seq .= $codes[$p[$i]][$upce_code[$i]];
			}
			$seq .= '010101';
		} else {
			$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
			$half_len = intval(ceil($len / 2));
			if ($len == 8) {
				for ($i = 0; $i < $half_len; ++$i) {
					$seq .= $codes['A'][$code[$i]];
				}
			} else {
				$p = $parities[$code[0]];
				for ($i = 1; $i < $half_len; ++$i) {
					$seq .= $codes[$p[$i-1]][$code[$i]];
				}
			}
			$seq .= '01010';
			for ($i = $half_len; $i < $len; ++$i) {
				$seq .= $codes['C'][$code[$i]];
			}
			$seq .= '101';
		}
		$clen = strlen($seq);
		$w = 0;
		for ($i = 0; $i < $clen; ++$i) {
			$w += 1;
			if (($i == ($clen - 1)) OR (($i < ($clen - 1)) AND ($seq[$i] != $seq[$i+1]))) {
				if ($seq[$i] == '1') {
					$t = true;
				} else {
					$t = false;
				}
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				++$k;
				$w = 0;
			}
		}
		return $bararray;
	}
	protected function barcode_eanext($code, $len=5) {
		$code = str_pad($code, $len, '0', STR_PAD_LEFT);
		if ($len == 2) {
			$r = $code % 4;
		} elseif ($len == 5) {
			$r = (3 * ($code[0] + $code[2] + $code[4])) + (9 * ($code[1] + $code[3]));
			$r %= 10;
		} else {
			return false;
		}
		$codes = array(
			'A'=>array(
				'0'=>'0001101',
				'1'=>'0011001',
				'2'=>'0010011',
				'3'=>'0111101',
				'4'=>'0100011',
				'5'=>'0110001',
				'6'=>'0101111',
				'7'=>'0111011',
				'8'=>'0110111',
				'9'=>'0001011'),
			'B'=>array(
				'0'=>'0100111',
				'1'=>'0110011',
				'2'=>'0011011',
				'3'=>'0100001',
				'4'=>'0011101',
				'5'=>'0111001',
				'6'=>'0000101',
				'7'=>'0010001',
				'8'=>'0001001',
				'9'=>'0010111')
		);
		$parities = array();
		$parities[2] = array(
			'0'=>array('A','A'),
			'1'=>array('A','B'),
			'2'=>array('B','A'),
			'3'=>array('B','B')
		);
		$parities[5] = array(
			'0'=>array('B','B','A','A','A'),
			'1'=>array('B','A','B','A','A'),
			'2'=>array('B','A','A','B','A'),
			'3'=>array('B','A','A','A','B'),
			'4'=>array('A','B','B','A','A'),
			'5'=>array('A','A','B','B','A'),
			'6'=>array('A','A','A','B','B'),
			'7'=>array('A','B','A','B','A'),
			'8'=>array('A','B','A','A','B'),
			'9'=>array('A','A','B','A','B')
		);
		$p = $parities[$len][$r];
		$seq = '1011';
		$seq .= $codes[$p[0]][$code[0]];
		for ($i = 1; $i < $len; ++$i) {
			$seq .= '01';
			$seq .= $codes[$p[$i]][$code[$i]];
		}
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		return $this->binseq_to_array($seq, $bararray);
	}
	protected function barcode_postnet($code, $planet=false) {
		if ($planet) {
			$barlen = Array(
				0 => Array(1,1,2,2,2),
				1 => Array(2,2,2,1,1),
				2 => Array(2,2,1,2,1),
				3 => Array(2,2,1,1,2),
				4 => Array(2,1,2,2,1),
				5 => Array(2,1,2,1,2),
				6 => Array(2,1,1,2,2),
				7 => Array(1,2,2,2,1),
				8 => Array(1,2,2,1,2),
				9 => Array(1,2,1,2,2)
			);
		} else {
			$barlen = Array(
				0 => Array(2,2,1,1,1),
				1 => Array(1,1,1,2,2),
				2 => Array(1,1,2,1,2),
				3 => Array(1,1,2,2,1),
				4 => Array(1,2,1,1,2),
				5 => Array(1,2,1,2,1),
				6 => Array(1,2,2,1,1),
				7 => Array(2,1,1,1,2),
				8 => Array(2,1,1,2,1),
				9 => Array(2,1,2,1,1)
			);
		}
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
		$k = 0;
		$code = str_replace('-', '', $code);
		$code = str_replace(' ', '', $code);
		$len = strlen($code);
		$sum = 0;
		for ($i = 0; $i < $len; ++$i) {
			$sum += intval($code[$i]);
		}
		$chkd = ($sum % 10);
		if($chkd > 0) {
			$chkd = (10 - $chkd);
		}
		$code .= $chkd;
		$len = strlen($code);
		$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
		$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
		$bararray['maxw'] += 2;
		for ($i = 0; $i < $len; ++$i) {
			for ($j = 0; $j < 5; ++$j) {
				$h = $barlen[$code[$i]][$j];
				$p = floor(1 / $h);
				$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
				$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
				$bararray['maxw'] += 2;
			}
		}
		$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
		$bararray['maxw'] += 1;
		return $bararray;
	}
	protected function barcode_rms4cc($code, $kix=false) {
		$notkix = !$kix;
		$barmode = array(
			'0' => array(3,3,2,2),
			'1' => array(3,4,1,2),
			'2' => array(3,4,2,1),
			'3' => array(4,3,1,2),
			'4' => array(4,3,2,1),
			'5' => array(4,4,1,1),
			'6' => array(3,1,4,2),
			'7' => array(3,2,3,2),
			'8' => array(3,2,4,1),
			'9' => array(4,1,3,2),
			'A' => array(4,1,4,1),
			'B' => array(4,2,3,1),
			'C' => array(3,1,2,4),
			'D' => array(3,2,1,4),
			'E' => array(3,2,2,3),
			'F' => array(4,1,1,4),
			'G' => array(4,1,2,3),
			'H' => array(4,2,1,3),
			'I' => array(1,3,4,2),
			'J' => array(1,4,3,2),
			'K' => array(1,4,4,1),
			'L' => array(2,3,3,2),
			'M' => array(2,3,4,1),
			'N' => array(2,4,3,1),
			'O' => array(1,3,2,4),
			'P' => array(1,4,1,4),
			'Q' => array(1,4,2,3),
			'R' => array(2,3,1,4),
			'S' => array(2,3,2,3),
			'T' => array(2,4,1,3),
			'U' => array(1,1,4,4),
			'V' => array(1,2,3,4),
			'W' => array(1,2,4,3),
			'X' => array(2,1,3,4),
			'Y' => array(2,1,4,3),
			'Z' => array(2,2,3,3)
		);
		$code = strtoupper($code);
		$len = strlen($code);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 3, 'bcode' => array());
		if ($notkix) {
			$checktable = array(
				'0' => array(1,1),
				'1' => array(1,2),
				'2' => array(1,3),
				'3' => array(1,4),
				'4' => array(1,5),
				'5' => array(1,0),
				'6' => array(2,1),
				'7' => array(2,2),
				'8' => array(2,3),
				'9' => array(2,4),
				'A' => array(2,5),
				'B' => array(2,0),
				'C' => array(3,1),
				'D' => array(3,2),
				'E' => array(3,3),
				'F' => array(3,4),
				'G' => array(3,5),
				'H' => array(3,0),
				'I' => array(4,1),
				'J' => array(4,2),
				'K' => array(4,3),
				'L' => array(4,4),
				'M' => array(4,5),
				'N' => array(4,0),
				'O' => array(5,1),
				'P' => array(5,2),
				'Q' => array(5,3),
				'R' => array(5,4),
				'S' => array(5,5),
				'T' => array(5,0),
				'U' => array(0,1),
				'V' => array(0,2),
				'W' => array(0,3),
				'X' => array(0,4),
				'Y' => array(0,5),
				'Z' => array(0,0)
			);
			$row = 0;
			$col = 0;
			for ($i = 0; $i < $len; ++$i) {
				$row += $checktable[$code[$i]][0];
				$col += $checktable[$code[$i]][1];
			}
			$row %= 6;
			$col %= 6;
			$chk = array_keys($checktable, array($row,$col));
			$code .= $chk[0];
			++$len;
		}
		$k = 0;
		if ($notkix) {
			$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
			$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
			$bararray['maxw'] += 2;
		}
		for ($i = 0; $i < $len; ++$i) {
			for ($j = 0; $j < 4; ++$j) {
				switch ($barmode[$code[$i]][$j]) {
					case 1: {
						$p = 0;
						$h = 2;
						break;
					}
					case 2: {
						$p = 0;
						$h = 3;
						break;
					}
					case 3: {
						$p = 1;
						$h = 1;
						break;
					}
					case 4: {
						$p = 1;
						$h = 2;
						break;
					}
				}
				$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
				$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
				$bararray['maxw'] += 2;
			}
		}
		if ($notkix) {
			$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 3, 'p' => 0);
			$bararray['maxw'] += 1;
		}
		return $bararray;
	}
	protected function barcode_codabar($code) {
		$chr = array(
			'0' => '11111221',
			'1' => '11112211',
			'2' => '11121121',
			'3' => '22111111',
			'4' => '11211211',
			'5' => '21111211',
			'6' => '12111121',
			'7' => '12112111',
			'8' => '12211111',
			'9' => '21121111',
			'-' => '11122111',
			'$' => '11221111',
			':' => '21112121',
			'/' => '21211121',
			'.' => '21212111',
			'+' => '11222221',
			'A' => '11221211',
			'B' => '12121121',
			'C' => '11121221',
			'D' => '11122211'
		);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$w = 0;
		$seq = '';
		$code = 'A'.strtoupper($code).'A';
		$len = strlen($code);
		for ($i = 0; $i < $len; ++$i) {
			if (!isset($chr[$code[$i]])) {
				return false;
			}
			$seq = $chr[$code[$i]];
			for ($j = 0; $j < 8; ++$j) {
				if (($j % 2) == 0) {
					$t = true;
				} else {
					$t = false;
				}
				$w = (float)$seq[$j];
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				++$k;
			}
		}
		return $bararray;
	}
	protected function barcode_code11($code) {
		$chr = array(
			'0' => '111121',
			'1' => '211121',
			'2' => '121121',
			'3' => '221111',
			'4' => '112121',
			'5' => '212111',
			'6' => '122111',
			'7' => '111221',
			'8' => '211211',
			'9' => '211111',
			'-' => '112111',
			'S' => '112211'
		);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$w = 0;
		$seq = '';
		$len = strlen($code);
		$p = 1;
		$check = 0;
		for ($i = ($len - 1); $i >= 0; --$i) {
			$digit = $code[$i];
			if ($digit == '-') {
				$dval = 10;
			} else {
				$dval = intval($digit);
			}
			$check += ($dval * $p);
			++$p;
			if ($p > 10) {
				$p = 1;
			}
		}
		$check %= 11;
		if ($check == 10) {
			$check = '-';
		}
		$code .= $check;
		if ($len > 10) {
			$p = 1;
			$check = 0;
			for ($i = $len; $i >= 0; --$i) {
				$digit = $code[$i];
				if ($digit == '-') {
					$dval = 10;
				} else {
					$dval = intval($digit);
				}
				$check += ($dval * $p);
				++$p;
				if ($p > 9) {
					$p = 1;
				}
			}
			$check %= 11;
			$code .= $check;
			++$len;
		}
		$code = 'S'.$code.'S';
		$len += 3;
		for ($i = 0; $i < $len; ++$i) {
			if (!isset($chr[$code[$i]])) {
				return false;
			}
			$seq = $chr[$code[$i]];
			for ($j = 0; $j < 6; ++$j) {
				if (($j % 2) == 0) {
					$t = true;
				} else {
					$t = false;
				}
				$w = (float)$seq[$j];
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				++$k;
			}
		}
		return $bararray;
	}
	protected function barcode_pharmacode($code) {
		$seq = '';
		$code = intval($code);
		while ($code > 0) {
			if (($code % 2) == 0) {
				$seq .= '11100';
				$code -= 2;
			} else {
				$seq .= '100';
				$code -= 1;
			}
			$code /= 2;
		}
		$seq = substr($seq, 0, -2);
		$seq = strrev($seq);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		return $this->binseq_to_array($seq, $bararray);
	}
	protected function barcode_pharmacode2t($code) {
		$seq = '';
		$code = intval($code);
		do {
			switch ($code % 3) {
				case 0: {
					$seq .= '3';
					$code = ($code - 3) / 3;
					break;
				}
				case 1: {
					$seq .= '1';
					$code = ($code - 1) / 3;
					break;
				}
				case 2: {
					$seq .= '2';
					$code = ($code - 2) / 3;
					break;
				}
			}
		} while($code != 0);
		$seq = strrev($seq);
		$k = 0;
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
		$len = strlen($seq);
		for ($i = 0; $i < $len; ++$i) {
			switch ($seq[$i]) {
				case '1': {
					$p = 1;
					$h = 1;
					break;
				}
				case '2': {
					$p = 0;
					$h = 1;
					break;
				}
				case '3': {
					$p = 0;
					$h = 2;
					break;
				}
			}
			$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
			$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
			$bararray['maxw'] += 2;
		}
		unset($bararray['bcode'][($k - 1)]);
		--$bararray['maxw'];
		return $bararray;
	}
	protected function barcode_imb($code) {
		$asc_chr = array(4,0,2,6,3,5,1,9,8,7,1,2,0,6,4,8,2,9,5,3,0,1,3,7,4,6,8,9,2,0,5,1,9,4,3,8,6,7,1,2,4,3,9,5,7,8,3,0,2,1,4,0,9,1,7,0,2,4,6,3,7,1,9,5,8);
		$dsc_chr = array(7,1,9,5,8,0,2,4,6,3,5,8,9,7,3,0,6,1,7,4,6,8,9,2,5,1,7,5,4,3,8,7,6,0,2,5,4,9,3,0,1,6,8,2,0,4,5,9,6,7,5,2,6,3,8,5,1,9,8,7,4,0,2,6,3);
		$asc_pos = array(3,0,8,11,1,12,8,11,10,6,4,12,2,7,9,6,7,9,2,8,4,0,12,7,10,9,0,7,10,5,7,9,6,8,2,12,1,4,2,0,1,5,4,6,12,1,0,9,4,7,5,10,2,6,9,11,2,12,6,7,5,11,0,3,2);
		$dsc_pos = array(2,10,12,5,9,1,5,4,3,9,11,5,10,1,6,3,4,1,10,0,2,11,8,6,1,12,3,8,6,4,4,11,0,6,1,9,11,5,3,7,3,10,7,11,8,2,10,3,5,8,0,3,12,11,8,4,5,1,3,0,7,12,9,8,10);
		$code_arr = explode('-', $code);
		$tracking_number = $code_arr[0];
		if (isset($code_arr[1])) {
			$routing_code = $code_arr[1];
		} else {
			$routing_code = '';
		}
		switch (strlen($routing_code)) {
			case 0: {
				$binary_code = 0;
				break;
			}
			case 5: {
				$binary_code = bcadd($routing_code, '1');
				break;
			}
			case 9: {
				$binary_code = bcadd($routing_code, '100001');
				break;
			}
			case 11: {
				$binary_code = bcadd($routing_code, '1000100001');
				break;
			}
			default: {
				return false;
				break;
			}
		}
		$binary_code = bcmul($binary_code, 10);
		$binary_code = bcadd($binary_code, $tracking_number[0]);
		$binary_code = bcmul($binary_code, 5);
		$binary_code = bcadd($binary_code, $tracking_number[1]);
		$binary_code .= substr($tracking_number, 2, 18);
		$binary_code = $this->dec_to_hex($binary_code);
		$binary_code = str_pad($binary_code, 26, '0', STR_PAD_LEFT);
		$binary_code_arr = chunk_split($binary_code, 2, "\r");
		$binary_code_arr = substr($binary_code_arr, 0, -1);
		$binary_code_arr = explode("\r", $binary_code_arr);
		$fcs = $this->imb_crc11fcs($binary_code_arr);
		$first_byte = sprintf('%2s', dechex((hexdec($binary_code_arr[0]) << 2) >> 2));
		$binary_code_102bit = $first_byte.substr($binary_code, 2);
		$codewords = array();
		$data = $this->hex_to_dec($binary_code_102bit);
		$codewords[0] = bcmod($data, 636) * 2;
		$data = bcdiv($data, 636);
		for ($i = 1; $i < 9; ++$i) {
			$codewords[$i] = bcmod($data, 1365);
			$data = bcdiv($data, 1365);
		}
		$codewords[9] = $data;
		if (($fcs >> 10) == 1) {
			$codewords[9] += 659;
		}
		$table2of13 = $this->imb_tables(2, 78);
		$table5of13 = $this->imb_tables(5, 1287);
		$characters = array();
		$bitmask = 512;
		foreach($codewords as $k => $val) {
			if ($val <= 1286) {
				$chrcode = $table5of13[$val];
			} else {
				$chrcode = $table2of13[($val - 1287)];
			}
			if (($fcs & $bitmask) > 0) {
				$chrcode = ((~$chrcode) & 8191);
			}
			$characters[] = $chrcode;
			$bitmask /= 2;
		}
		$characters = array_reverse($characters);
		$k = 0;
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 3, 'bcode' => array());
		for ($i = 0; $i < 65; ++$i) {
			$asc = (($characters[$asc_chr[$i]] & pow(2, $asc_pos[$i])) > 0);
			$dsc = (($characters[$dsc_chr[$i]] & pow(2, $dsc_pos[$i])) > 0);
			if ($asc AND $dsc) {
				$p = 0;
				$h = 3;
			} elseif ($asc) {
				$p = 0;
				$h = 2;
			} elseif ($dsc) {
				$p = 1;
				$h = 2;
			} else {
				$p = 1;
				$h = 1;
			}
			$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
			$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
			$bararray['maxw'] += 2;
		}
		unset($bararray['bcode'][($k - 1)]);
		--$bararray['maxw'];
		return $bararray;
	}
	protected function barcode_imb_pre($code) {
		if (!preg_match('/^[fadtFADT]{65}$/', $code) == 1) {
			return false;
		}
		$characters = str_split(strtolower($code), 1);
		$k = 0;
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 3, 'bcode' => array());
		for ($i = 0; $i < 65; ++$i) {
			switch($characters[$i]) {
				case 'f': {
					$p = 0;
					$h = 3;
					break;
				}
				case 'a': {
					$p = 0;
					$h = 2;
					break;
				}
				case 'd': {
					$p = 1;
					$h = 2;
					break;
				}
				case 't': {
					$p = 1;
					$h = 1;
					break;
				}
			}
			$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
			$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
			$bararray['maxw'] += 2;
		}
		unset($bararray['bcode'][($k - 1)]);
		--$bararray['maxw'];
		return $bararray;
	}
	public function dec_to_hex($number) {
		$i = 0;
		$hex = array();
		if($number == 0) {
			return '00';
		}
		while($number > 0) {
			if($number == 0) {
				array_push($hex, '0');
			} else {
				array_push($hex, strtoupper(dechex(bcmod($number, '16'))));
				$number = bcdiv($number, '16', 0);
			}
		}
		$hex = array_reverse($hex);
		return implode($hex);
	}
	public function hex_to_dec($hex) {
		$dec = 0;
		$bitval = 1;
		$len = strlen($hex);
		for($pos = ($len - 1); $pos >= 0; --$pos) {
			$dec = bcadd($dec, bcmul(hexdec($hex[$pos]), $bitval));
			$bitval = bcmul($bitval, 16);
		}
		return $dec;
	}
	protected function imb_crc11fcs($code_arr) {
		$genpoly = 0x0F35;
		$fcs = 0x07FF;
		$data = hexdec($code_arr[0]) << 5;
		for ($bit = 2; $bit < 8; ++$bit) {
			if (($fcs ^ $data) & 0x400) {
				$fcs = ($fcs << 1) ^ $genpoly;
			} else {
				$fcs = ($fcs << 1);
			}
			$fcs &= 0x7FF;
			$data <<= 1;
		}
		for ($byte = 1; $byte < 13; ++$byte) {
			$data = hexdec($code_arr[$byte]) << 3;
			for ($bit = 0; $bit < 8; ++$bit) {
				if (($fcs ^ $data) & 0x400) {
					$fcs = ($fcs << 1) ^ $genpoly;
				} else {
					$fcs = ($fcs << 1);
				}
				$fcs &= 0x7FF;
				$data <<= 1;
			}
		}
		return $fcs;
	}
	protected function imb_reverse_us($num) {
		$rev = 0;
		for ($i = 0; $i < 16; ++$i) {
			$rev <<= 1;
			$rev |= ($num & 1);
			$num >>= 1;
		}
		return $rev;
	}
	protected function imb_tables($n, $size) {
		$table = array();
		$lli = 0;
		$lui = $size - 1;
		for ($count = 0; $count < 8192; ++$count) {
			$bit_count = 0;
			for ($bit_index = 0; $bit_index < 13; ++$bit_index) {
				$bit_count += intval(($count & (1 << $bit_index)) != 0);
			}
			if ($bit_count == $n) {
				$reverse = ($this->imb_reverse_us($count) >> 3);
				if ($reverse >= $count) {
					if ($reverse == $count) {
						$table[$lui] = $count;
						--$lui;
					} else {
						$table[$lli] = $count;
						++$lli;
						$table[$lli] = $reverse;
						++$lli;
					}
				}
			}
		}
		return $table;
	}

}