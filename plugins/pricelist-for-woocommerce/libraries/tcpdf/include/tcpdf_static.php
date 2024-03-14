<?php
//============================================================+
// File name   : tcpdf_static.php
// Version     : 1.1.4
// Begin       : 2002-08-03
// Last Update : 2019-11-01
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2002-2015 Nicola Asuni - Tecnick.com LTD
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
//   Static methods used by the TCPDF class.
//
//============================================================+

/**
 * @file
 * This is a PHP class that contains static methods for the TCPDF class.<br>
 * @package com.tecnick.tcpdf
 * @author Nicola Asuni
 * @version 1.1.2
 */

/**
 * @class TCPDF_STATIC
 * Static methods used by the TCPDF class.
 * @package com.tecnick.tcpdf
 * @brief PHP class for generating PDF documents without requiring external extensions.
 * @version 1.1.1
 * @author Nicola Asuni - info@tecnick.com
 */
class TCPDF_STATIC {
	private static $tcpdf_version = '6.3.5';
	public static $alias_tot_pages = '{:ptp:}';
	public static $alias_num_page = '{:pnp:}';
	public static $alias_group_tot_pages = '{:ptg:}';
	public static $alias_group_num_page = '{:png:}';
	public static $alias_right_shift = '{rsc:';
	public static $enc_padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
	public static $byterange_string = '/ByteRange[0 ********** ********** **********]';
	public static $pageboxes = array('MediaBox', 'CropBox', 'BleedBox', 'TrimBox', 'ArtBox');
	public static function getTCPDFVersion() {
		return self::$tcpdf_version;
	}
	public static function getTCPDFProducer() {
		return "\x54\x43\x50\x44\x46\x20".self::getTCPDFVersion()."\x20\x28\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x74\x63\x70\x64\x66\x2e\x6f\x72\x67\x29";
	}
	public static function set_mqr($mqr) {
		if (!defined('PHP_VERSION_ID')) {
			$version = PHP_VERSION;
			define('PHP_VERSION_ID', (($version[0] * 10000) + ($version[2] * 100) + $version[4]));
		}
		if (PHP_VERSION_ID < 50300) {
			@set_magic_quotes_runtime($mqr);
		}
	}
	public static function get_mqr() {
		if (!defined('PHP_VERSION_ID')) {
			$version = PHP_VERSION;
			define('PHP_VERSION_ID', (($version[0] * 10000) + ($version[2] * 100) + $version[4]));
		}
		if (PHP_VERSION_ID < 50300) {
			return @get_magic_quotes_runtime();
		}
		return 0;
	}
	public static function isValidURL($url) {
		$headers = @get_headers($url);
    	return (strpos($headers[0], '200') !== false);
	}
	public static function removeSHY($txt='', $unicode=true) {
		$txt = preg_replace('/([\\xc2]{1}[\\xad]{1})/', '', $txt);
		if (!$unicode) {
			$txt = preg_replace('/([\\xad]{1})/', '', $txt);
		}
		return $txt;
	}
	public static function getBorderMode($brd, $position='start', $opencell=true) {
		if ((!$opencell) OR empty($brd)) {
			return $brd;
		}
		if ($brd == 1) {
			$brd = 'LTRB';
		}
		if (is_string($brd)) {
			$slen = strlen($brd);
			$newbrd = array();
			for ($i = 0; $i < $slen; ++$i) {
				$newbrd[$brd[$i]] = array('cap' => 'square', 'join' => 'miter');
			}
			$brd = $newbrd;
		}
		foreach ($brd as $border => $style) {
			switch ($position) {
				case 'start': {
					if (strpos($border, 'B') !== false) {
						$newkey = str_replace('B', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
					}
					break;
				}
				case 'middle': {
					if (strpos($border, 'B') !== false) {
						$newkey = str_replace('B', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
						$border = $newkey;
					}
					if (strpos($border, 'T') !== false) {
						$newkey = str_replace('T', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
					}
					break;
				}
				case 'end': {
					if (strpos($border, 'T') !== false) {
						$newkey = str_replace('T', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
					}
					break;
				}
			}
		}
		return $brd;
	}
	public static function empty_string($str) {
		return (is_null($str) OR (is_string($str) AND (strlen($str) == 0)));
	}
	public static function getObjFilename($type='tmp', $file_id='') {
		return tempnam(K_PATH_CACHE, '__tcpdf_'.$file_id.'_'.$type.'_'.md5(TCPDF_STATIC::getRandomSeed()).'_');
	}
	public static function _escape($s) {
		return strtr($s, array(')' => '\\)', '(' => '\\(', '\\' => '\\\\', chr(13) => '\r'));
	}
	public static function _escapeXML($str) {
		$replaceTable = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
		$str = strtr($str, $replaceTable);
		return $str;
	}
	public static function objclone($object) {
		if (($object instanceof Imagick) AND (version_compare(phpversion('imagick'), '3.0.1') !== 1)) {
			return @$object->clone();
		}
		return @clone($object);
	}
	public static function sendOutputData($data, $length) {
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) OR empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			header('Content-Length: '.$length);
		}
		echo $data;
	}
	public static function replacePageNumAliases($page, $replace, $diff=0) {
		foreach ($replace as $rep) {
			foreach ($rep[3] as $a) {
				if (strpos($page, $a) !== false) {
					$page = str_replace($a, $rep[0], $page);
					$diff += ($rep[2] - $rep[1]);
				}
			}
		}
		return array($page, $diff);
	}
	public static function getTimestamp($date) {
		if (($date[0] == 'D') AND ($date[1] == ':')) {
			$date = substr($date, 2);
		}
		return strtotime($date);
	}
	public static function getFormattedDate($time) {
		return substr_replace(date('YmdHisO', intval($time)), '\'', (0 - 2), 0).'\'';
	}
	public static function getRandomSeed($seed='') {
		$rnd = uniqid(rand().microtime(true), true);
		if (function_exists('posix_getpid')) {
			$rnd .= posix_getpid();
		}
		if (function_exists('openssl_random_pseudo_bytes') AND (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
			$rnd .= openssl_random_pseudo_bytes(512);
		} else {
			for ($i = 0; $i < 23; ++$i) {
				$rnd .= uniqid('', true);
			}
		}
		return $rnd.$seed.__FILE__.serialize($_SERVER).microtime(true);
	}
	public static function _md5_16($str) {
		return pack('H*', md5($str));
	}
	public static function _AES($key, $text) {
		$padding = 16 - (strlen($text) % 16);
		$text .= str_repeat(chr($padding), $padding);
		if (extension_loaded('openssl')) {
			$iv = openssl_random_pseudo_bytes (openssl_cipher_iv_length('aes-256-cbc'));
			$text = openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
			return $iv.substr($text, 0, -16);
		}
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
		$text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_CBC, $iv);
		$text = $iv.$text;
		return $text;
	}
	public static function _AESnopad($key, $text) {
		if (extension_loaded('openssl')) {
			$iv = str_repeat("\x00", openssl_cipher_iv_length('aes-256-cbc'));
			$text = openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
			return substr($text, 0, -16);
		}
		$iv = str_repeat("\x00", mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
		$text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_CBC, $iv);
		return $text;
	}
	public static function _RC4($key, $text, &$last_enc_key, &$last_enc_key_c) {
		if (function_exists('mcrypt_encrypt') AND ($out = @mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_STREAM, ''))) {
			return $out;
		}
		if ($last_enc_key != $key) {
			$k = str_repeat($key, ((256 / strlen($key)) + 1));
			$rc4 = range(0, 255);
			$j = 0;
			for ($i = 0; $i < 256; ++$i) {
				$t = $rc4[$i];
				$j = ($j + $t + ord($k[$i])) % 256;
				$rc4[$i] = $rc4[$j];
				$rc4[$j] = $t;
			}
			$last_enc_key = $key;
			$last_enc_key_c = $rc4;
		} else {
			$rc4 = $last_enc_key_c;
		}
		$len = strlen($text);
		$a = 0;
		$b = 0;
		$out = '';
		for ($i = 0; $i < $len; ++$i) {
			$a = ($a + 1) % 256;
			$t = $rc4[$a];
			$b = ($b + $t) % 256;
			$rc4[$a] = $rc4[$b];
			$rc4[$b] = $t;
			$k = $rc4[($rc4[$a] + $rc4[$b]) % 256];
			$out .= chr(ord($text[$i]) ^ $k);
		}
		return $out;
	}
	public static function getUserPermissionCode($permissions, $mode=0) {
		$options = array(
			'owner' => 2,
			'print' => 4,
			'modify' => 8,
			'copy' => 16,
			'annot-forms' => 32,
			'fill-forms' => 256,
			'extract' => 512,
			'assemble' => 1024,
			'print-high' => 2048
			);
		$protection = 2147422012;
		foreach ($permissions as $permission) {
			if (isset($options[$permission])) {
				if (($mode > 0) OR ($options[$permission] <= 32)) {
					if ($options[$permission] == 2) {
						$protection += $options[$permission];
					} else {
						$protection -= $options[$permission];
					}
				}
			}
		}
		return $protection;
	}
	public static function convertHexStringToString($bs) {
		$string = '';
		$bslength = strlen($bs);
		if (($bslength % 2) != 0) {
			$bs .= '0';
			++$bslength;
		}
		for ($i = 0; $i < $bslength; $i += 2) {
			$string .= chr(hexdec($bs[$i].$bs[($i + 1)]));
		}
		return $string;
	}
	public static function convertStringToHexString($s) {
		$bs = '';
		$chars = preg_split('//', $s, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($chars as $c) {
			$bs .= sprintf('%02s', dechex(ord($c)));
		}
		return $bs;
	}
	public static function getEncPermissionsString($protection) {
		$binprot = sprintf('%032b', $protection);
		$str = chr(bindec(substr($binprot, 24, 8)));
		$str .= chr(bindec(substr($binprot, 16, 8)));
		$str .= chr(bindec(substr($binprot, 8, 8)));
		$str .= chr(bindec(substr($binprot, 0, 8)));
		return $str;
	}
	public static function encodeNameObject($name) {
		$escname = '';
		$length = strlen($name);
		for ($i = 0; $i < $length; ++$i) {
			$chr = $name[$i];
			if (preg_match('/[0-9a-zA-Z#_=-]/', $chr) == 1) {
				$escname .= $chr;
			} else {
				$escname .= sprintf('#%02X', ord($chr));
			}
		}
		return $escname;
	}
	public static function getAnnotOptFromJSProp($prop, &$spot_colors, $rtl=false) {
		if (isset($prop['aopt']) AND is_array($prop['aopt'])) {
			return $prop['aopt'];
		}
		$opt = array();
		if (isset($prop['alignment'])) {
			switch ($prop['alignment']) {
				case 'left': {
					$opt['q'] = 0;
					break;
				}
				case 'center': {
					$opt['q'] = 1;
					break;
				}
				case 'right': {
					$opt['q'] = 2;
					break;
				}
				default: {
					$opt['q'] = ($rtl)?2:0;
					break;
				}
			}
		}
		if (isset($prop['lineWidth'])) {
			$linewidth = intval($prop['lineWidth']);
		} else {
			$linewidth = 1;
		}
		if (isset($prop['borderStyle'])) {
			switch ($prop['borderStyle']) {
				case 'border.d':
				case 'dashed': {
					$opt['border'] = array(0, 0, $linewidth, array(3, 2));
					$opt['bs'] = array('w'=>$linewidth, 's'=>'D', 'd'=>array(3, 2));
					break;
				}
				case 'border.b':
				case 'beveled': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'B');
					break;
				}
				case 'border.i':
				case 'inset': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'I');
					break;
				}
				case 'border.u':
				case 'underline': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'U');
					break;
				}
				case 'border.s':
				case 'solid': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'S');
					break;
				}
				default: {
					break;
				}
			}
		}
		if (isset($prop['border']) AND is_array($prop['border'])) {
			$opt['border'] = $prop['border'];
		}
		if (!isset($opt['mk'])) {
			$opt['mk'] = array();
		}
		if (!isset($opt['mk']['if'])) {
			$opt['mk']['if'] = array();
		}
		$opt['mk']['if']['a'] = array(0.5, 0.5);
		if (isset($prop['buttonAlignX'])) {
			$opt['mk']['if']['a'][0] = $prop['buttonAlignX'];
		}
		if (isset($prop['buttonAlignY'])) {
			$opt['mk']['if']['a'][1] = $prop['buttonAlignY'];
		}
		if (isset($prop['buttonFitBounds']) AND ($prop['buttonFitBounds'] == 'true')) {
			$opt['mk']['if']['fb'] = true;
		}
		if (isset($prop['buttonScaleHow'])) {
			switch ($prop['buttonScaleHow']) {
				case 'scaleHow.proportional': {
					$opt['mk']['if']['s'] = 'P';
					break;
				}
				case 'scaleHow.anamorphic': {
					$opt['mk']['if']['s'] = 'A';
					break;
				}
			}
		}
		if (isset($prop['buttonScaleWhen'])) {
			switch ($prop['buttonScaleWhen']) {
				case 'scaleWhen.always': {
					$opt['mk']['if']['sw'] = 'A';
					break;
				}
				case 'scaleWhen.never': {
					$opt['mk']['if']['sw'] = 'N';
					break;
				}
				case 'scaleWhen.tooBig': {
					$opt['mk']['if']['sw'] = 'B';
					break;
				}
				case 'scaleWhen.tooSmall': {
					$opt['mk']['if']['sw'] = 'S';
					break;
				}
			}
		}
		if (isset($prop['buttonPosition'])) {
			switch ($prop['buttonPosition']) {
				case 0:
				case 'position.textOnly': {
					$opt['mk']['tp'] = 0;
					break;
				}
				case 1:
				case 'position.iconOnly': {
					$opt['mk']['tp'] = 1;
					break;
				}
				case 2:
				case 'position.iconTextV': {
					$opt['mk']['tp'] = 2;
					break;
				}
				case 3:
				case 'position.textIconV': {
					$opt['mk']['tp'] = 3;
					break;
				}
				case 4:
				case 'position.iconTextH': {
					$opt['mk']['tp'] = 4;
					break;
				}
				case 5:
				case 'position.textIconH': {
					$opt['mk']['tp'] = 5;
					break;
				}
				case 6:
				case 'position.overlay': {
					$opt['mk']['tp'] = 6;
					break;
				}
			}
		}
		if (isset($prop['fillColor'])) {
			if (is_array($prop['fillColor'])) {
				$opt['mk']['bg'] = $prop['fillColor'];
			} else {
				$opt['mk']['bg'] = TCPDF_COLORS::convertHTMLColorToDec($prop['fillColor'], $spot_colors);
			}
		}
		if (isset($prop['strokeColor'])) {
			if (is_array($prop['strokeColor'])) {
				$opt['mk']['bc'] = $prop['strokeColor'];
			} else {
				$opt['mk']['bc'] = TCPDF_COLORS::convertHTMLColorToDec($prop['strokeColor'], $spot_colors);
			}
		}
		if (isset($prop['rotation'])) {
			$opt['mk']['r'] = $prop['rotation'];
		}
		if (isset($prop['charLimit'])) {
			$opt['maxlen'] = intval($prop['charLimit']);
		}
		if (!isset($ff)) {
			$ff = 0;
		}
		if (isset($prop['readonly']) AND ($prop['readonly'] == 'true')) {
			$ff += 1 << 0;
		}
		if (isset($prop['required']) AND ($prop['required'] == 'true')) {
			$ff += 1 << 1;
		}
		if (isset($prop['multiline']) AND ($prop['multiline'] == 'true')) {
			$ff += 1 << 12;
		}
		if (isset($prop['password']) AND ($prop['password'] == 'true')) {
			$ff += 1 << 13;
		}
		if (isset($prop['NoToggleToOff']) AND ($prop['NoToggleToOff'] == 'true')) {
			$ff += 1 << 14;
		}
		if (isset($prop['Radio']) AND ($prop['Radio'] == 'true')) {
			$ff += 1 << 15;
		}
		if (isset($prop['Pushbutton']) AND ($prop['Pushbutton'] == 'true')) {
			$ff += 1 << 16;
		}
		if (isset($prop['Combo']) AND ($prop['Combo'] == 'true')) {
			$ff += 1 << 17;
		}
		if (isset($prop['editable']) AND ($prop['editable'] == 'true')) {
			$ff += 1 << 18;
		}
		if (isset($prop['Sort']) AND ($prop['Sort'] == 'true')) {
			$ff += 1 << 19;
		}
		if (isset($prop['fileSelect']) AND ($prop['fileSelect'] == 'true')) {
			$ff += 1 << 20;
		}
		if (isset($prop['multipleSelection']) AND ($prop['multipleSelection'] == 'true')) {
			$ff += 1 << 21;
		}
		if (isset($prop['doNotSpellCheck']) AND ($prop['doNotSpellCheck'] == 'true')) {
			$ff += 1 << 22;
		}
		if (isset($prop['doNotScroll']) AND ($prop['doNotScroll'] == 'true')) {
			$ff += 1 << 23;
		}
		if (isset($prop['comb']) AND ($prop['comb'] == 'true')) {
			$ff += 1 << 24;
		}
		if (isset($prop['radiosInUnison']) AND ($prop['radiosInUnison'] == 'true')) {
			$ff += 1 << 25;
		}
		if (isset($prop['richText']) AND ($prop['richText'] == 'true')) {
			$ff += 1 << 25;
		}
		if (isset($prop['commitOnSelChange']) AND ($prop['commitOnSelChange'] == 'true')) {
			$ff += 1 << 26;
		}
		$opt['ff'] = $ff;
		if (isset($prop['defaultValue'])) {
			$opt['dv'] = $prop['defaultValue'];
		}
		$f = 4;
		if (isset($prop['readonly']) AND ($prop['readonly'] == 'true')) {
			$f += 1 << 6;
		}
		if (isset($prop['display'])) {
			if ($prop['display'] == 'display.visible') {
			} elseif ($prop['display'] == 'display.hidden') {
				$f += 1 << 1;
			} elseif ($prop['display'] == 'display.noPrint') {
				$f -= 1 << 2;
			} elseif ($prop['display'] == 'display.noView') {
				$f += 1 << 5;
			}
		}
		$opt['f'] = $f;
		if (isset($prop['currentValueIndices']) AND is_array($prop['currentValueIndices'])) {
			$opt['i'] = $prop['currentValueIndices'];
		}
		if (isset($prop['value'])) {
			if (is_array($prop['value'])) {
				$opt['opt'] = array();
				foreach ($prop['value'] AS $key => $optval) {
					if (isset($prop['exportValues'][$key])) {
						$opt['opt'][$key] = array($prop['exportValues'][$key], $prop['value'][$key]);
					} else {
						$opt['opt'][$key] = $prop['value'][$key];
					}
				}
			} else {
				$opt['v'] = $prop['value'];
			}
		}
		if (isset($prop['richValue'])) {
			$opt['rv'] = $prop['richValue'];
		}
		if (isset($prop['submitName'])) {
			$opt['tm'] = $prop['submitName'];
		}
		if (isset($prop['name'])) {
			$opt['t'] = $prop['name'];
		}
		if (isset($prop['userName'])) {
			$opt['tu'] = $prop['userName'];
		}
		if (isset($prop['highlight'])) {
			switch ($prop['highlight']) {
				case 'none':
				case 'highlight.n': {
					$opt['h'] = 'N';
					break;
				}
				case 'invert':
				case 'highlight.i': {
					$opt['h'] = 'i';
					break;
				}
				case 'push':
				case 'highlight.p': {
					$opt['h'] = 'P';
					break;
				}
				case 'outline':
				case 'highlight.o': {
					$opt['h'] = 'O';
					break;
				}
			}
		}
		return $opt;
	}
	public static function formatPageNumber($num) {
		return number_format((float)$num, 0, '', '.');
	}
	public static function formatTOCPageNumber($num) {
		return number_format((float)$num, 0, '', '.');
	}
	public static function extractCSSproperties($cssdata) {
		if (empty($cssdata)) {
			return array();
		}
		$cssdata = preg_replace('/\/\*[^\*]*\*\//', '', $cssdata);
		$cssdata = preg_replace('/[\s]+/', ' ', $cssdata);
		$cssdata = preg_replace('/[\s]*([;:\{\}]{1})[\s]*/', '\\1', $cssdata);
		$cssdata = preg_replace('/([^\}\{]+)\{\}/', '', $cssdata);
		$cssdata = preg_replace('/@media[\s]+([^\{]*)\{/i', '@media \\1§', $cssdata);
		$cssdata = preg_replace('/\}\}/si', '}§', $cssdata);
		$cssdata = trim($cssdata);
		$cssblocks = array();
		$matches = array();
		if (preg_match_all('/@media[\s]+([^\§]*)§([^§]*)§/i', $cssdata, $matches) > 0) {
			foreach ($matches[1] as $key => $type) {
				$cssblocks[$type] = $matches[2][$key];
			}
			$cssdata = preg_replace('/@media[\s]+([^\§]*)§([^§]*)§/i', '', $cssdata);
		}
		if (isset($cssblocks['all']) AND !empty($cssblocks['all'])) {
			$cssdata .= $cssblocks['all'];
		}
		if (isset($cssblocks['print']) AND !empty($cssblocks['print'])) {
			$cssdata .= $cssblocks['print'];
		}
		$cssblocks = array();
		$matches = array();
		if (substr($cssdata, -1) == '}') {
			$cssdata = substr($cssdata, 0, -1);
		}
		$matches = explode('}', $cssdata);
		foreach ($matches as $key => $block) {
			$cssblocks[$key] = explode('{', $block);
			if (!isset($cssblocks[$key][1])) {
				unset($cssblocks[$key]);
			}
		}
		foreach ($cssblocks as $key => $block) {
			if (strpos($block[0], ',') > 0) {
				$selectors = explode(',', $block[0]);
				foreach ($selectors as $sel) {
					$cssblocks[] = array(0 => trim($sel), 1 => $block[1]);
				}
				unset($cssblocks[$key]);
			}
		}
		$cssdata = array();
		foreach ($cssblocks as $block) {
			$selector = $block[0];
			$matches = array();
			$a = 0;
			$b = intval(preg_match_all('/[\#]/', $selector, $matches));
			$c = intval(preg_match_all('/[\[\.]/', $selector, $matches));
			$c += intval(preg_match_all('/[\:]link|visited|hover|active|focus|target|lang|enabled|disabled|checked|indeterminate|root|nth|first|last|only|empty|contains|not/i', $selector, $matches));
			$d = intval(preg_match_all('/[\>\+\~\s]{1}[a-zA-Z0-9]+/', ' '.$selector, $matches));
			$d += intval(preg_match_all('/[\:][\:]/', $selector, $matches));
			$specificity = $a.$b.$c.$d;
			$cssdata[$specificity.' '.$selector] = $block[1];
		}
		ksort($cssdata, SORT_STRING);
		return $cssdata;
	}
	public static function fixHTMLCode($html, $default_css, $tagvs, $tidy_options, &$tagvspaces) {
		if ($tidy_options === '') {
			$tidy_options = array (
				'clean' => 1,
				'drop-empty-paras' => 0,
				'drop-proprietary-attributes' => 1,
				'fix-backslash' => 1,
				'hide-comments' => 1,
				'join-styles' => 1,
				'lower-literals' => 1,
				'merge-divs' => 1,
				'merge-spans' => 1,
				'output-xhtml' => 1,
				'word-2000' => 1,
				'wrap' => 0,
				'output-bom' => 0,
			);
		}
		$tidy = tidy_parse_string($html, $tidy_options);
		$tidy->cleanRepair();
		$tidy_head = tidy_get_head($tidy);
		$css = $tidy_head->value;
		$css = preg_replace('/<style([^>]+)>/ims', '<style>', $css);
		$css = preg_replace('/<\/style>(.*)<style>/ims', "\n", $css);
		$css = str_replace('/*<![CDATA[*/', '', $css);
		$css = str_replace('/*]]>*/', '', $css);
		preg_match('/<style>(.*)<\/style>/ims', $css, $matches);
		if (isset($matches[1])) {
			$css = strtolower($matches[1]);
		} else {
			$css = '';
		}
		$css = '<style>'.$default_css.$css.'</style>';
		$tidy_body = tidy_get_body($tidy);
		$html = $tidy_body->value;
		$html = str_replace('<br>', '<br />', $html);
		$html = preg_replace('/<div([^\>]*)><\/div>/', '', $html);
		$html = preg_replace('/<p([^\>]*)><\/p>/', '', $html);
		if ($tagvs !== '') {
			$tagvspaces = $tagvs;
		}
		return $css.$html;
	}
	public static function isValidCSSSelectorForTag($dom, $key, $selector) {
		$valid = false;
		$tag = $dom[$key]['value'];
		$class = array();
		if (isset($dom[$key]['attribute']['class']) AND !empty($dom[$key]['attribute']['class'])) {
			$class = explode(' ', strtolower($dom[$key]['attribute']['class']));
		}
		$id = '';
		if (isset($dom[$key]['attribute']['id']) AND !empty($dom[$key]['attribute']['id'])) {
			$id = strtolower($dom[$key]['attribute']['id']);
		}
		$selector = preg_replace('/([\>\+\~\s]{1})([\.]{1})([^\>\+\~\s]*)/si', '\\1*.\\3', $selector);
		$matches = array();
		if (preg_match_all('/([\>\+\~\s]{1})([a-zA-Z0-9\*]+)([^\>\+\~\s]*)/si', $selector, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE) > 0) {
			$parentop = array_pop($matches[1]);
			$operator = $parentop[0];
			$offset = $parentop[1];
			$lasttag = array_pop($matches[2]);
			$lasttag = strtolower(trim($lasttag[0]));
			if (($lasttag == '*') OR ($lasttag == $tag)) {
				$attrib = array_pop($matches[3]);
				$attrib = strtolower(trim($attrib[0]));
				if (!empty($attrib)) {
					switch ($attrib[0]) {
						case '.': {
							if (in_array(substr($attrib, 1), $class)) {
								$valid = true;
							}
							break;
						}
						case '#': {
							if (substr($attrib, 1) == $id) {
								$valid = true;
							}
							break;
						}
						case '[': {
							$attrmatch = array();
							if (preg_match('/\[([a-zA-Z0-9]*)[\s]*([\~\^\$\*\|\=]*)[\s]*["]?([^"\]]*)["]?\]/i', $attrib, $attrmatch) > 0) {
								$att = strtolower($attrmatch[1]);
								$val = $attrmatch[3];
								if (isset($dom[$key]['attribute'][$att])) {
									switch ($attrmatch[2]) {
										case '=': {
											if ($dom[$key]['attribute'][$att] == $val) {
												$valid = true;
											}
											break;
										}
										case '~=': {
											if (in_array($val, explode(' ', $dom[$key]['attribute'][$att]))) {
												$valid = true;
											}
											break;
										}
										case '^=': {
											if ($val == substr($dom[$key]['attribute'][$att], 0, strlen($val))) {
												$valid = true;
											}
											break;
										}
										case '$=': {
											if ($val == substr($dom[$key]['attribute'][$att], -strlen($val))) {
												$valid = true;
											}
											break;
										}
										case '*=': {
											if (strpos($dom[$key]['attribute'][$att], $val) !== false) {
												$valid = true;
											}
											break;
										}
										case '|=': {
											if ($dom[$key]['attribute'][$att] == $val) {
												$valid = true;
											} elseif (preg_match('/'.$val.'[\-]{1}/i', $dom[$key]['attribute'][$att]) > 0) {
												$valid = true;
											}
											break;
										}
										default: {
											$valid = true;
										}
									}
								}
							}
							break;
						}
						case ':': {
							if ($attrib[1] == ':') {
							} else {
							}
							break;
						}
					}
				} else {
					$valid = true;
				}
				if ($valid AND ($offset > 0)) {
					$valid = false;
					$selector = substr($selector, 0, $offset);
					switch ($operator) {
						case ' ': {
							while ($dom[$key]['parent'] > 0) {
								if (self::isValidCSSSelectorForTag($dom, $dom[$key]['parent'], $selector)) {
									$valid = true;
									break;
								} else {
									$key = $dom[$key]['parent'];
								}
							}
							break;
						}
						case '>': {
							$valid = self::isValidCSSSelectorForTag($dom, $dom[$key]['parent'], $selector);
							break;
						}
						case '+': {
							for ($i = ($key - 1); $i > $dom[$key]['parent']; --$i) {
								if ($dom[$i]['tag'] AND $dom[$i]['opening']) {
									$valid = self::isValidCSSSelectorForTag($dom, $i, $selector);
									break;
								}
							}
							break;
						}
						case '~': {
							for ($i = ($key - 1); $i > $dom[$key]['parent']; --$i) {
								if ($dom[$i]['tag'] AND $dom[$i]['opening']) {
									if (self::isValidCSSSelectorForTag($dom, $i, $selector)) {
										break;
									}
								}
							}
							break;
						}
					}
				}
			}
		}
		return $valid;
	}
	public static function getCSSdataArray($dom, $key, $css) {
		$cssarray = array();
		$selectors = array();
		if (isset($dom[($dom[$key]['parent'])]['csssel'])) {
			$selectors = $dom[($dom[$key]['parent'])]['csssel'];
		}
		foreach($css as $selector => $style) {
			$pos = strpos($selector, ' ');
			$specificity = substr($selector, 0, $pos);
			$selector = substr($selector, $pos);
			if (self::isValidCSSSelectorForTag($dom, $key, $selector)) {
				if (!in_array($selector, $selectors)) {
					$cssarray[] = array('k' => $selector, 's' => $specificity, 'c' => $style);
					$selectors[] = $selector;
				}
			}
		}
		if (isset($dom[$key]['attribute']['style'])) {
			$cssarray[] = array('k' => '', 's' => '1000', 'c' => $dom[$key]['attribute']['style']);
		}
		$cssordered = array();
		foreach ($cssarray as $key => $val) {
			$skey = sprintf('%04d', $key);
			$cssordered[$val['s'].'_'.$skey] = $val;
		}
		ksort($cssordered, SORT_STRING);
		return array($selectors, $cssordered);
	}
	public static function getTagStyleFromCSSarray($css) {
		$tagstyle = '';
		foreach ($css as $style) {
			$csscmds = explode(';', $style['c']);
			foreach ($csscmds as $cmd) {
				if (!empty($cmd)) {
					$pos = strpos($cmd, ':');
					if ($pos !== false) {
						$cmd = substr($cmd, 0, ($pos + 1));
						if (strpos($tagstyle, $cmd) !== false) {
							$tagstyle = preg_replace('/'.$cmd.'[^;]+/i', '', $tagstyle);
						}
					}
				}
			}
			$tagstyle .= ';'.$style['c'];
		}
		$tagstyle = preg_replace('/[;]+/', ';', $tagstyle);
		return $tagstyle;
	}
	public static function intToRoman($number) {
		$roman = '';
		if ($number >= 4000) {
			return strval($number);
		}
		while ($number >= 1000) {
			$roman .= 'M';
			$number -= 1000;
		}
		while ($number >= 900) {
			$roman .= 'CM';
			$number -= 900;
		}
		while ($number >= 500) {
			$roman .= 'D';
			$number -= 500;
		}
		while ($number >= 400) {
			$roman .= 'CD';
			$number -= 400;
		}
		while ($number >= 100) {
			$roman .= 'C';
			$number -= 100;
		}
		while ($number >= 90) {
			$roman .= 'XC';
			$number -= 90;
		}
		while ($number >= 50) {
			$roman .= 'L';
			$number -= 50;
		}
		while ($number >= 40) {
			$roman .= 'XL';
			$number -= 40;
		}
		while ($number >= 10) {
			$roman .= 'X';
			$number -= 10;
		}
		while ($number >= 9) {
			$roman .= 'IX';
			$number -= 9;
		}
		while ($number >= 5) {
			$roman .= 'V';
			$number -= 5;
		}
		while ($number >= 4) {
			$roman .= 'IV';
			$number -= 4;
		}
		while ($number >= 1) {
			$roman .= 'I';
			--$number;
		}
		return $roman;
	}
	public static function revstrpos($haystack, $needle, $offset = 0) {
		$length = strlen($haystack);
		$offset = ($offset > 0)?($length - $offset):abs($offset);
		$pos = strpos(strrev($haystack), strrev($needle), $offset);
		return ($pos === false)?false:($length - $pos - strlen($needle));
	}
	public static function getHyphenPatternsFromTEX($file) {
		$data = file_get_contents($file);
		$patterns = array();
		$data = preg_replace('/\%[^\n]*/', '', $data);
		preg_match('/\\\\patterns\{([^\}]*)\}/i', $data, $matches);
		$data = trim(substr($matches[0], 10, -1));
		$patterns_array = preg_split('/[\s]+/', $data);
		$patterns = array();
		foreach($patterns_array as $val) {
			if (!TCPDF_STATIC::empty_string($val)) {
				$val = trim($val);
				$val = str_replace('\'', '\\\'', $val);
				$key = preg_replace('/[0-9]+/', '', $val);
				$patterns[$key] = $val;
			}
		}
		return $patterns;
	}
	public static function getPathPaintOperator($style, $default='S') {
		$op = '';
		switch($style) {
			case 'S':
			case 'D': {
				$op = 'S';
				break;
			}
			case 's':
			case 'd': {
				$op = 's';
				break;
			}
			case 'f':
			case 'F': {
				$op = 'f';
				break;
			}
			case 'f*':
			case 'F*': {
				$op = 'f*';
				break;
			}
			case 'B':
			case 'FD':
			case 'DF': {
				$op = 'B';
				break;
			}
			case 'B*':
			case 'F*D':
			case 'DF*': {
				$op = 'B*';
				break;
			}
			case 'b':
			case 'fd':
			case 'df': {
				$op = 'b';
				break;
			}
			case 'b*':
			case 'f*d':
			case 'df*': {
				$op = 'b*';
				break;
			}
			case 'CNZ': {
				$op = 'W n';
				break;
			}
			case 'CEO': {
				$op = 'W* n';
				break;
			}
			case 'n': {
				$op = 'n';
				break;
			}
			default: {
				if (!empty($default)) {
					$op = self::getPathPaintOperator($default, '');
				} else {
					$op = '';
				}
			}
		}
		return $op;
	}
	public static function getTransformationMatrixProduct($ta, $tb) {
		$tm = array();
		$tm[0] = ($ta[0] * $tb[0]) + ($ta[2] * $tb[1]);
		$tm[1] = ($ta[1] * $tb[0]) + ($ta[3] * $tb[1]);
		$tm[2] = ($ta[0] * $tb[2]) + ($ta[2] * $tb[3]);
		$tm[3] = ($ta[1] * $tb[2]) + ($ta[3] * $tb[3]);
		$tm[4] = ($ta[0] * $tb[4]) + ($ta[2] * $tb[5]) + $ta[4];
		$tm[5] = ($ta[1] * $tb[4]) + ($ta[3] * $tb[5]) + $ta[5];
		return $tm;
	}
	public static function getSVGTransformMatrix($attribute) {
		$tm = array(1, 0, 0, 1, 0, 0);
		$transform = array();
		if (preg_match_all('/(matrix|translate|scale|rotate|skewX|skewY)[\s]*\(([^\)]+)\)/si', $attribute, $transform, PREG_SET_ORDER) > 0) {
			foreach ($transform as $key => $data) {
				if (!empty($data[2])) {
					$a = 1;
					$b = 0;
					$c = 0;
					$d = 1;
					$e = 0;
					$f = 0;
					$regs = array();
					switch ($data[1]) {
						case 'matrix': {
							if (preg_match('/([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$a = $regs[1];
								$b = $regs[2];
								$c = $regs[3];
								$d = $regs[4];
								$e = $regs[5];
								$f = $regs[6];
							}
							break;
						}
						case 'translate': {
							if (preg_match('/([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$e = $regs[1];
								$f = $regs[2];
							} elseif (preg_match('/([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$e = $regs[1];
							}
							break;
						}
						case 'scale': {
							if (preg_match('/([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$a = $regs[1];
								$d = $regs[2];
							} elseif (preg_match('/([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$a = $regs[1];
								$d = $a;
							}
							break;
						}
						case 'rotate': {
							if (preg_match('/([0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$ang = deg2rad($regs[1]);
								$x = $regs[2];
								$y = $regs[3];
								$a = cos($ang);
								$b = sin($ang);
								$c = -$b;
								$d = $a;
								$e = ($x * (1 - $a)) - ($y * $c);
								$f = ($y * (1 - $d)) - ($x * $b);
							} elseif (preg_match('/([0-9\-\.]+)/si', $data[2], $regs)) {
								$ang = deg2rad($regs[1]);
								$a = cos($ang);
								$b = sin($ang);
								$c = -$b;
								$d = $a;
								$e = 0;
								$f = 0;
							}
							break;
						}
						case 'skewX': {
							if (preg_match('/([0-9\-\.]+)/si', $data[2], $regs)) {
								$c = tan(deg2rad($regs[1]));
							}
							break;
						}
						case 'skewY': {
							if (preg_match('/([0-9\-\.]+)/si', $data[2], $regs)) {
								$b = tan(deg2rad($regs[1]));
							}
							break;
						}
					}
					$tm = self::getTransformationMatrixProduct($tm, array($a, $b, $c, $d, $e, $f));
				}
			}
		}
		return $tm;
	}
	public static function getVectorsAngle($x1, $y1, $x2, $y2) {
		$dprod = ($x1 * $x2) + ($y1 * $y2);
		$dist1 = sqrt(($x1 * $x1) + ($y1 * $y1));
		$dist2 = sqrt(($x2 * $x2) + ($y2 * $y2));
		$angle = acos($dprod / ($dist1 * $dist2));
		if (is_nan($angle)) {
			$angle = M_PI;
		}
		if ((($x1 * $y2) - ($x2 * $y1)) < 0) {
			$angle *= -1;
		}
		return $angle;
	}
	public static function pregSplit($pattern, $modifiers, $subject, $limit=NULL, $flags=NULL) {
		if ((strpos($modifiers, 'u') === FALSE) OR (count(preg_split('//u', "\n\t", -1, PREG_SPLIT_NO_EMPTY)) == 2)) {
			return preg_split($pattern.$modifiers, $subject, $limit, $flags);
		}
		$ret = array();
		while (($nl = strpos($subject, "\n")) !== FALSE) {
			$ret = array_merge($ret, preg_split($pattern.$modifiers, substr($subject, 0, $nl), $limit, $flags));
			$ret[] = "\n";
			$subject = substr($subject, ($nl + 1));
		}
		if (strlen($subject) > 0) {
			$ret = array_merge($ret, preg_split($pattern.$modifiers, $subject, $limit, $flags));
		}
		return $ret;
	}
	public static function fopenLocal($filename, $mode) {
		if (strpos($filename, '://') === false) {
			$filename = 'file://'.$filename;
		} elseif (stream_is_local($filename) !== true) {
			return false;
		}
		return fopen($filename, $mode);
	}
	public static function url_exists($url) {
		$crs = curl_init();
		$url = self::encodeUrlQuery($url);
		curl_setopt($crs, CURLOPT_URL, $url);
		curl_setopt($crs, CURLOPT_NOBODY, true);
		curl_setopt($crs, CURLOPT_FAILONERROR, true);
		if ((ini_get('open_basedir') == '') && (!ini_get('safe_mode'))) {
			curl_setopt($crs, CURLOPT_FOLLOWLOCATION, true);
		}
		curl_setopt($crs, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($crs, CURLOPT_TIMEOUT, 30);
		curl_setopt($crs, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($crs, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($crs, CURLOPT_USERAGENT, 'tc-lib-file');
		curl_setopt($crs, CURLOPT_MAXREDIRS, 5);
		if (defined('CURLOPT_PROTOCOLS')) {
		    curl_setopt($crs, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP |  CURLPROTO_FTP | CURLPROTO_FTPS);
		}
		curl_exec($crs);
		$code = curl_getinfo($crs, CURLINFO_HTTP_CODE);
		curl_close($crs);
		return ($code == 200);
	}
	public static function encodeUrlQuery($url) {
		$urlData = parse_url($url);
		if (isset($urlData['query']) && $urlData['query']) {
			$urlQueryData = array();
			parse_str(urldecode($urlData['query']), $urlQueryData);
			$updatedUrl = $urlData['scheme'] . '://' . $urlData['host'] . $urlData['path'] . '?' . http_build_query($urlQueryData);
		} else {
			$updatedUrl = $url;
		}
		return $updatedUrl;
	}
	public static function file_exists($filename) {
		if (preg_match('|^https?://|', $filename) == 1) {
			$result = self::url_exists($filename);
		} elseif (strpos($filename, '://')) {
			$result =  false;
		} else {
			$result = @file_exists($filename);
		}
		return $result;
	}
	public static function fileGetContents($file) {
		$alt = array($file);
		if ((strlen($file) > 1)
		    && ($file[0] === '/')
		    && ($file[1] !== '/')
		    && !empty($_SERVER['DOCUMENT_ROOT'])
		    && ($_SERVER['DOCUMENT_ROOT'] !== '/')
		) {
		    $findroot = strpos($file, $_SERVER['DOCUMENT_ROOT']);
		    if (($findroot === false) || ($findroot > 1)) {
			$alt[] = htmlspecialchars_decode(urldecode($_SERVER['DOCUMENT_ROOT'].$file));
		    }
		}
		$protocol = 'http';
		if (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
		    $protocol .= 's';
		}
		$url = $file;
		if (preg_match('%^//%', $url) && !empty($_SERVER['HTTP_HOST'])) {
			$url = $protocol.':'.str_replace(' ', '%20', $url);
		}
		$url = htmlspecialchars_decode($url);
		$alt[] = $url;
		if (preg_match('%^(https?)://%', $url)
		    && empty($_SERVER['HTTP_HOST'])
		    && empty($_SERVER['DOCUMENT_ROOT'])
		) {
			$urldata = parse_url($url);
			if (empty($urldata['query'])) {
				$host = $protocol.'://'.$_SERVER['HTTP_HOST'];
				if (strpos($url, $host) === 0) {
				    $tmp = str_replace($host, $_SERVER['DOCUMENT_ROOT'], $url);
				    $alt[] = htmlspecialchars_decode(urldecode($tmp));
				}
			}
		}
		if (isset($_SERVER['SCRIPT_URI'])
		    && !preg_match('%^(https?|ftp)://%', $file)
		    && !preg_match('%^//%', $file)
		) {
		    $urldata = @parse_url($_SERVER['SCRIPT_URI']);
		    $alt[] = $urldata['scheme'].'://'.$urldata['host'].(($file[0] == '/') ? '' : '/').$file;
		}
		$alt = array_unique($alt);
		foreach ($alt as $path) {
			if (!self::file_exists($path)) {
				continue;
			}
			$ret = @file_get_contents($path);
			if ( $ret != false ) {
			    return $ret;
			}
			if (!ini_get('allow_url_fopen')
				&& function_exists('curl_init')
				&& preg_match('%^(https?|ftp)://%', $path)
			) {
				$crs = curl_init();
				curl_setopt($crs, CURLOPT_URL, $path);
				curl_setopt($crs, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($crs, CURLOPT_FAILONERROR, true);
				curl_setopt($crs, CURLOPT_RETURNTRANSFER, true);
				if ((ini_get('open_basedir') == '') && (!ini_get('safe_mode'))) {
				    curl_setopt($crs, CURLOPT_FOLLOWLOCATION, true);
				}
				curl_setopt($crs, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($crs, CURLOPT_TIMEOUT, 30);
				curl_setopt($crs, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($crs, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($crs, CURLOPT_USERAGENT, 'tc-lib-file');
				curl_setopt($crs, CURLOPT_MAXREDIRS, 5);
				if (defined('CURLOPT_PROTOCOLS')) {
				    curl_setopt($crs, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP |  CURLPROTO_FTP | CURLPROTO_FTPS);
				}
				$ret = curl_exec($crs);
				curl_close($crs);
				if ($ret !== false) {
					return $ret;
				}
			}
		}
		return false;
	}
	public static function _getULONG($str, $offset) {
		$v = unpack('Ni', substr($str, $offset, 4));
		return $v['i'];
	}
	public static function _getUSHORT($str, $offset) {
		$v = unpack('ni', substr($str, $offset, 2));
		return $v['i'];
	}
	public static function _getSHORT($str, $offset) {
		$v = unpack('si', substr($str, $offset, 2));
		return $v['i'];
	}
	public static function _getFWORD($str, $offset) {
		$v = self::_getUSHORT($str, $offset);
		if ($v > 0x7fff) {
			$v -= 0x10000;
		}
		return $v;
	}
	public static function _getUFWORD($str, $offset) {
		$v = self::_getUSHORT($str, $offset);
		return $v;
	}
	public static function _getFIXED($str, $offset) {
		$m = self::_getFWORD($str, $offset);
		$f = self::_getUSHORT($str, ($offset + 2));
		$v = floatval(''.$m.'.'.$f.'');
		return $v;
	}
	public static function _getBYTE($str, $offset) {
		$v = unpack('Ci', substr($str, $offset, 1));
		return $v['i'];
	}
	public static function rfread($handle, $length) {
		$data = fread($handle, $length);
		if ($data === false) {
			return false;
		}
		$rest = ($length - strlen($data));
		if (($rest > 0) && !feof($handle)) {
			$data .= self::rfread($handle, $rest);
		}
		return $data;
	}
	public static function _freadint($f) {
		$a = unpack('Ni', fread($f, 4));
		return $a['i'];
	}
	public static $page_formats = array(
		'A0'                     => array( 2383.937,  3370.394),
		'A1'                     => array( 1683.780,  2383.937),
		'A2'                     => array( 1190.551,  1683.780),
		'A3'                     => array(  841.890,  1190.551),
		'A4'                     => array(  595.276,   841.890),
		'A5'                     => array(  419.528,   595.276),
		'A6'                     => array(  297.638,   419.528),
		'A7'                     => array(  209.764,   297.638),
		'A8'                     => array(  147.402,   209.764),
		'A9'                     => array(  104.882,   147.402),
		'A10'                    => array(   73.701,   104.882),
		'A11'                    => array(   51.024,    73.701),
		'A12'                    => array(   36.850,    51.024),
		'B0'                     => array( 2834.646,  4008.189),
		'B1'                     => array( 2004.094,  2834.646),
		'B2'                     => array( 1417.323,  2004.094),
		'B3'                     => array( 1000.630,  1417.323),
		'B4'                     => array(  708.661,  1000.630),
		'B5'                     => array(  498.898,   708.661),
		'B6'                     => array(  354.331,   498.898),
		'B7'                     => array(  249.449,   354.331),
		'B8'                     => array(  175.748,   249.449),
		'B9'                     => array(  124.724,   175.748),
		'B10'                    => array(   87.874,   124.724),
		'B11'                    => array(   62.362,    87.874),
		'B12'                    => array(   42.520,    62.362),
		'C0'                     => array( 2599.370,  3676.535),
		'C1'                     => array( 1836.850,  2599.370),
		'C2'                     => array( 1298.268,  1836.850),
		'C3'                     => array(  918.425,  1298.268),
		'C4'                     => array(  649.134,   918.425),
		'C5'                     => array(  459.213,   649.134),
		'C6'                     => array(  323.150,   459.213),
		'C7'                     => array(  229.606,   323.150),
		'C8'                     => array(  161.575,   229.606),
		'C9'                     => array(  113.386,   161.575),
		'C10'                    => array(   79.370,   113.386),
		'C11'                    => array(   56.693,    79.370),
		'C12'                    => array(   39.685,    56.693),
		'C76'                    => array(  229.606,   459.213),
		'DL'                     => array(  311.811,   623.622),
		'DLE'                    => array(  323.150,   637.795),
		'DLX'                    => array(  340.158,   666.142),
		'DLP'                    => array(  280.630,   595.276),
		'E0'                     => array( 2491.654,  3517.795),
		'E1'                     => array( 1757.480,  2491.654),
		'E2'                     => array( 1247.244,  1757.480),
		'E3'                     => array(  878.740,  1247.244),
		'E4'                     => array(  623.622,   878.740),
		'E5'                     => array(  439.370,   623.622),
		'E6'                     => array(  311.811,   439.370),
		'E7'                     => array(  221.102,   311.811),
		'E8'                     => array(  155.906,   221.102),
		'E9'                     => array(  110.551,   155.906),
		'E10'                    => array(   76.535,   110.551),
		'E11'                    => array(   53.858,    76.535),
		'E12'                    => array(   36.850,    53.858),
		'G0'                     => array( 2715.591,  3838.110),
		'G1'                     => array( 1919.055,  2715.591),
		'G2'                     => array( 1357.795,  1919.055),
		'G3'                     => array(  958.110,  1357.795),
		'G4'                     => array(  677.480,   958.110),
		'G5'                     => array(  479.055,   677.480),
		'G6'                     => array(  337.323,   479.055),
		'G7'                     => array(  238.110,   337.323),
		'G8'                     => array(  167.244,   238.110),
		'G9'                     => array(  119.055,   167.244),
		'G10'                    => array(   82.205,   119.055),
		'G11'                    => array(   59.528,    82.205),
		'G12'                    => array(   39.685,    59.528),
		'RA0'                    => array( 2437.795,  3458.268),
		'RA1'                    => array( 1729.134,  2437.795),
		'RA2'                    => array( 1218.898,  1729.134),
		'RA3'                    => array(  864.567,  1218.898),
		'RA4'                    => array(  609.449,   864.567),
		'SRA0'                   => array( 2551.181,  3628.346),
		'SRA1'                   => array( 1814.173,  2551.181),
		'SRA2'                   => array( 1275.591,  1814.173),
		'SRA3'                   => array(  907.087,  1275.591),
		'SRA4'                   => array(  637.795,   907.087),
		'4A0'                    => array( 4767.874,  6740.787),
		'2A0'                    => array( 3370.394,  4767.874),
		'A2_EXTRA'               => array( 1261.417,  1754.646),
		'A3+'                    => array(  932.598,  1369.134),
		'A3_EXTRA'               => array(  912.756,  1261.417),
		'A3_SUPER'               => array(  864.567,  1440.000),
		'SUPER_A3'               => array(  864.567,  1380.472),
		'A4_EXTRA'               => array(  666.142,   912.756),
		'A4_SUPER'               => array(  649.134,   912.756),
		'SUPER_A4'               => array(  643.465,  1009.134),
		'A4_LONG'                => array(  595.276,   986.457),
		'F4'                     => array(  595.276,   935.433),
		'SO_B5_EXTRA'            => array(  572.598,   782.362),
		'A5_EXTRA'               => array(  490.394,   666.142),
		'ANSI_E'                 => array( 2448.000,  3168.000),
		'ANSI_D'                 => array( 1584.000,  2448.000),
		'ANSI_C'                 => array( 1224.000,  1584.000),
		'ANSI_B'                 => array(  792.000,  1224.000),
		'ANSI_A'                 => array(  612.000,   792.000),
		'USLEDGER'               => array( 1224.000,   792.000),
		'LEDGER'                 => array( 1224.000,   792.000),
		'ORGANIZERK'             => array(  792.000,  1224.000),
		'BIBLE'                  => array(  792.000,  1224.000),
		'USTABLOID'              => array(  792.000,  1224.000),
		'TABLOID'                => array(  792.000,  1224.000),
		'ORGANIZERM'             => array(  612.000,   792.000),
		'USLETTER'               => array(  612.000,   792.000),
		'LETTER'                 => array(  612.000,   792.000),
		'USLEGAL'                => array(  612.000,  1008.000),
		'LEGAL'                  => array(  612.000,  1008.000),
		'GOVERNMENTLETTER'       => array(  576.000,   756.000),
		'GLETTER'                => array(  576.000,   756.000),
		'JUNIORLEGAL'            => array(  576.000,   360.000),
		'JLEGAL'                 => array(  576.000,   360.000),
		'QUADDEMY'               => array( 2520.000,  3240.000),
		'SUPER_B'                => array(  936.000,  1368.000),
		'QUARTO'                 => array(  648.000,   792.000),
		'GOVERNMENTLEGAL'        => array(  612.000,   936.000),
		'FOLIO'                  => array(  612.000,   936.000),
		'MONARCH'                => array(  522.000,   756.000),
		'EXECUTIVE'              => array(  522.000,   756.000),
		'ORGANIZERL'             => array(  396.000,   612.000),
		'STATEMENT'              => array(  396.000,   612.000),
		'MEMO'                   => array(  396.000,   612.000),
		'FOOLSCAP'               => array(  595.440,   936.000),
		'COMPACT'                => array(  306.000,   486.000),
		'ORGANIZERJ'             => array(  198.000,   360.000),
		'P1'                     => array( 1587.402,  2437.795),
		'P2'                     => array( 1218.898,  1587.402),
		'P3'                     => array(  793.701,  1218.898),
		'P4'                     => array(  609.449,   793.701),
		'P5'                     => array(  396.850,   609.449),
		'P6'                     => array(  303.307,   396.850),
		'ARCH_E'                 => array( 2592.000,  3456.000),
		'ARCH_E1'                => array( 2160.000,  3024.000),
		'ARCH_D'                 => array( 1728.000,  2592.000),
		'BROADSHEET'             => array( 1296.000,  1728.000),
		'ARCH_C'                 => array( 1296.000,  1728.000),
		'ARCH_B'                 => array(  864.000,  1296.000),
		'ARCH_A'                 => array(  648.000,   864.000),
		'ANNENV_A2'              => array(  314.640,   414.000),
		'ANNENV_A6'              => array(  342.000,   468.000),
		'ANNENV_A7'              => array(  378.000,   522.000),
		'ANNENV_A8'              => array(  396.000,   584.640),
		'ANNENV_A10'             => array(  450.000,   692.640),
		'ANNENV_SLIM'            => array(  278.640,   638.640),
		'COMMENV_N6_1/4'         => array(  252.000,   432.000),
		'COMMENV_N6_3/4'         => array(  260.640,   468.000),
		'COMMENV_N8'             => array(  278.640,   540.000),
		'COMMENV_N9'             => array(  278.640,   638.640),
		'COMMENV_N10'            => array(  296.640,   684.000),
		'COMMENV_N11'            => array(  324.000,   746.640),
		'COMMENV_N12'            => array(  342.000,   792.000),
		'COMMENV_N14'            => array(  360.000,   828.000),
		'CATENV_N1'              => array(  432.000,   648.000),
		'CATENV_N1_3/4'          => array(  468.000,   684.000),
		'CATENV_N2'              => array(  468.000,   720.000),
		'CATENV_N3'              => array(  504.000,   720.000),
		'CATENV_N6'              => array(  540.000,   756.000),
		'CATENV_N7'              => array(  576.000,   792.000),
		'CATENV_N8'              => array(  594.000,   810.000),
		'CATENV_N9_1/2'          => array(  612.000,   756.000),
		'CATENV_N9_3/4'          => array(  630.000,   810.000),
		'CATENV_N10_1/2'         => array(  648.000,   864.000),
		'CATENV_N12_1/2'         => array(  684.000,   900.000),
		'CATENV_N13_1/2'         => array(  720.000,   936.000),
		'CATENV_N14_1/4'         => array(  810.000,   882.000),
		'CATENV_N14_1/2'         => array(  828.000,  1044.000),
		'JIS_B0'                 => array( 2919.685,  4127.244),
		'JIS_B1'                 => array( 2063.622,  2919.685),
		'JIS_B2'                 => array( 1459.843,  2063.622),
		'JIS_B3'                 => array( 1031.811,  1459.843),
		'JIS_B4'                 => array(  728.504,  1031.811),
		'JIS_B5'                 => array(  515.906,   728.504),
		'JIS_B6'                 => array(  362.835,   515.906),
		'JIS_B7'                 => array(  257.953,   362.835),
		'JIS_B8'                 => array(  181.417,   257.953),
		'JIS_B9'                 => array(  127.559,   181.417),
		'JIS_B10'                => array(   90.709,   127.559),
		'JIS_B11'                => array(   62.362,    90.709),
		'JIS_B12'                => array(   45.354,    62.362),
		'PA0'                    => array( 2381.102,  3174.803),
		'PA1'                    => array( 1587.402,  2381.102),
		'PA2'                    => array( 1190.551,  1587.402),
		'PA3'                    => array(  793.701,  1190.551),
		'PA4'                    => array(  595.276,   793.701),
		'PA5'                    => array(  396.850,   595.276),
		'PA6'                    => array(  297.638,   396.850),
		'PA7'                    => array(  198.425,   297.638),
		'PA8'                    => array(  147.402,   198.425),
		'PA9'                    => array(   99.213,   147.402),
		'PA10'                   => array(   73.701,    99.213),
		'PASSPORT_PHOTO'         => array(   99.213,   127.559),
		'E'                      => array(  233.858,   340.157),
		'L'                      => array(  252.283,   360.000),
		'3R'                     => array(  252.283,   360.000),
		'KG'                     => array(  289.134,   430.866),
		'4R'                     => array(  289.134,   430.866),
		'4D'                     => array(  340.157,   430.866),
		'2L'                     => array(  360.000,   504.567),
		'5R'                     => array(  360.000,   504.567),
		'8P'                     => array(  430.866,   575.433),
		'6R'                     => array(  430.866,   575.433),
		'6P'                     => array(  575.433,   720.000),
		'8R'                     => array(  575.433,   720.000),
		'6PW'                    => array(  575.433,   864.567),
		'S8R'                    => array(  575.433,   864.567),
		'4P'                     => array(  720.000,   864.567),
		'10R'                    => array(  720.000,   864.567),
		'4PW'                    => array(  720.000,  1080.000),
		'S10R'                   => array(  720.000,  1080.000),
		'11R'                    => array(  790.866,  1009.134),
		'S11R'                   => array(  790.866,  1224.567),
		'12R'                    => array(  864.567,  1080.000),
		'S12R'                   => array(  864.567,  1292.598),
		'NEWSPAPER_BROADSHEET'   => array( 2125.984,  1700.787),
		'NEWSPAPER_BERLINER'     => array( 1332.283,   892.913),
		'NEWSPAPER_TABLOID'      => array( 1218.898,   793.701),
		'NEWSPAPER_COMPACT'      => array( 1218.898,   793.701),
		'CREDIT_CARD'            => array(  153.014,   242.646),
		'BUSINESS_CARD'          => array(  153.014,   242.646),
		'BUSINESS_CARD_ISO7810'  => array(  153.014,   242.646),
		'BUSINESS_CARD_ISO216'   => array(  147.402,   209.764),
		'BUSINESS_CARD_IT'       => array(  155.906,   240.945),
		'BUSINESS_CARD_UK'       => array(  155.906,   240.945),
		'BUSINESS_CARD_FR'       => array(  155.906,   240.945),
		'BUSINESS_CARD_DE'       => array(  155.906,   240.945),
		'BUSINESS_CARD_ES'       => array(  155.906,   240.945),
		'BUSINESS_CARD_CA'       => array(  144.567,   252.283),
		'BUSINESS_CARD_US'       => array(  144.567,   252.283),
		'BUSINESS_CARD_JP'       => array(  155.906,   257.953),
		'BUSINESS_CARD_HK'       => array(  153.071,   255.118),
		'BUSINESS_CARD_AU'       => array(  155.906,   255.118),
		'BUSINESS_CARD_DK'       => array(  155.906,   255.118),
		'BUSINESS_CARD_SE'       => array(  155.906,   255.118),
		'BUSINESS_CARD_RU'       => array(  141.732,   255.118),
		'BUSINESS_CARD_CZ'       => array(  141.732,   255.118),
		'BUSINESS_CARD_FI'       => array(  141.732,   255.118),
		'BUSINESS_CARD_HU'       => array(  141.732,   255.118),
		'BUSINESS_CARD_IL'       => array(  141.732,   255.118),
		'4SHEET'                 => array( 2880.000,  4320.000),
		'6SHEET'                 => array( 3401.575,  5102.362),
		'12SHEET'                => array( 8640.000,  4320.000),
		'16SHEET'                => array( 5760.000,  8640.000),
		'32SHEET'                => array(11520.000,  8640.000),
		'48SHEET'                => array(17280.000,  8640.000),
		'64SHEET'                => array(23040.000,  8640.000),
		'96SHEET'                => array(34560.000,  8640.000),
		'EN_EMPEROR'             => array( 3456.000,  5184.000),
		'EN_ANTIQUARIAN'         => array( 2232.000,  3816.000),
		'EN_GRAND_EAGLE'         => array( 2070.000,  3024.000),
		'EN_DOUBLE_ELEPHANT'     => array( 1926.000,  2880.000),
		'EN_ATLAS'               => array( 1872.000,  2448.000),
		'EN_COLOMBIER'           => array( 1692.000,  2484.000),
		'EN_ELEPHANT'            => array( 1656.000,  2016.000),
		'EN_DOUBLE_DEMY'         => array( 1620.000,  2556.000),
		'EN_IMPERIAL'            => array( 1584.000,  2160.000),
		'EN_PRINCESS'            => array( 1548.000,  2016.000),
		'EN_CARTRIDGE'           => array( 1512.000,  1872.000),
		'EN_DOUBLE_LARGE_POST'   => array( 1512.000,  2376.000),
		'EN_ROYAL'               => array( 1440.000,  1800.000),
		'EN_SHEET'               => array( 1404.000,  1692.000),
		'EN_HALF_POST'           => array( 1404.000,  1692.000),
		'EN_SUPER_ROYAL'         => array( 1368.000,  1944.000),
		'EN_DOUBLE_POST'         => array( 1368.000,  2196.000),
		'EN_MEDIUM'              => array( 1260.000,  1656.000),
		'EN_DEMY'                => array( 1260.000,  1620.000),
		'EN_LARGE_POST'          => array( 1188.000,  1512.000),
		'EN_COPY_DRAUGHT'        => array( 1152.000,  1440.000),
		'EN_POST'                => array( 1116.000,  1386.000),
		'EN_CROWN'               => array( 1080.000,  1440.000),
		'EN_PINCHED_POST'        => array( 1062.000,  1332.000),
		'EN_BRIEF'               => array(  972.000,  1152.000),
		'EN_FOOLSCAP'            => array(  972.000,  1224.000),
		'EN_SMALL_FOOLSCAP'      => array(  954.000,  1188.000),
		'EN_POTT'                => array(  900.000,  1080.000),
		'BE_GRAND_AIGLE'         => array( 1984.252,  2948.031),
		'BE_COLOMBIER'           => array( 1757.480,  2409.449),
		'BE_DOUBLE_CARRE'        => array( 1757.480,  2607.874),
		'BE_ELEPHANT'            => array( 1746.142,  2182.677),
		'BE_PETIT_AIGLE'         => array( 1700.787,  2381.102),
		'BE_GRAND_JESUS'         => array( 1559.055,  2069.291),
		'BE_JESUS'               => array( 1530.709,  2069.291),
		'BE_RAISIN'              => array( 1417.323,  1842.520),
		'BE_GRAND_MEDIAN'        => array( 1303.937,  1714.961),
		'BE_DOUBLE_POSTE'        => array( 1233.071,  1601.575),
		'BE_COQUILLE'            => array( 1218.898,  1587.402),
		'BE_PETIT_MEDIAN'        => array( 1176.378,  1502.362),
		'BE_RUCHE'               => array( 1020.472,  1303.937),
		'BE_PROPATRIA'           => array(  977.953,  1218.898),
		'BE_LYS'                 => array(  898.583,  1125.354),
		'BE_POT'                 => array(  870.236,  1088.504),
		'BE_ROSETTE'             => array(  765.354,   983.622),
		'FR_UNIVERS'             => array( 2834.646,  3685.039),
		'FR_DOUBLE_COLOMBIER'    => array( 2551.181,  3571.654),
		'FR_GRANDE_MONDE'        => array( 2551.181,  3571.654),
		'FR_DOUBLE_SOLEIL'       => array( 2267.717,  3401.575),
		'FR_DOUBLE_JESUS'        => array( 2154.331,  3174.803),
		'FR_GRAND_AIGLE'         => array( 2125.984,  3004.724),
		'FR_PETIT_AIGLE'         => array( 1984.252,  2664.567),
		'FR_DOUBLE_RAISIN'       => array( 1842.520,  2834.646),
		'FR_JOURNAL'             => array( 1842.520,  2664.567),
		'FR_COLOMBIER_AFFICHE'   => array( 1785.827,  2551.181),
		'FR_DOUBLE_CAVALIER'     => array( 1757.480,  2607.874),
		'FR_CLOCHE'              => array( 1700.787,  2267.717),
		'FR_SOLEIL'              => array( 1700.787,  2267.717),
		'FR_DOUBLE_CARRE'        => array( 1587.402,  2551.181),
		'FR_DOUBLE_COQUILLE'     => array( 1587.402,  2494.488),
		'FR_JESUS'               => array( 1587.402,  2154.331),
		'FR_RAISIN'              => array( 1417.323,  1842.520),
		'FR_CAVALIER'            => array( 1303.937,  1757.480),
		'FR_DOUBLE_COURONNE'     => array( 1303.937,  2040.945),
		'FR_CARRE'               => array( 1275.591,  1587.402),
		'FR_COQUILLE'            => array( 1247.244,  1587.402),
		'FR_DOUBLE_TELLIERE'     => array( 1247.244,  1927.559),
		'FR_DOUBLE_CLOCHE'       => array( 1133.858,  1700.787),
		'FR_DOUBLE_POT'          => array( 1133.858,  1757.480),
		'FR_ECU'                 => array( 1133.858,  1474.016),
		'FR_COURONNE'            => array( 1020.472,  1303.937),
		'FR_TELLIERE'            => array(  963.780,  1247.244),
		'FR_POT'                 => array(  878.740,  1133.858),
	);
	public static function getPageSizeFromFormat($format) {
		if (isset(self::$page_formats[$format])) {
			return self::$page_formats[$format];
		}
		return self::$page_formats['A4'];
	}
	public static function setPageBoxes($page, $type, $llx, $lly, $urx, $ury, $points, $k, $pagedim=array()) {
		if (!isset($pagedim[$page])) {
			$pagedim[$page] = array();
		}
		if (!in_array($type, self::$pageboxes)) {
			return;
		}
		if ($points) {
			$k = 1;
		}
		$pagedim[$page][$type]['llx'] = ($llx * $k);
		$pagedim[$page][$type]['lly'] = ($lly * $k);
		$pagedim[$page][$type]['urx'] = ($urx * $k);
		$pagedim[$page][$type]['ury'] = ($ury * $k);
		return $pagedim;
	}
	public static function swapPageBoxCoordinates($page, $pagedim) {
		foreach (self::$pageboxes as $type) {
			if (isset($pagedim[$page][$type])) {
				$tmp = $pagedim[$page][$type]['llx'];
				$pagedim[$page][$type]['llx'] = $pagedim[$page][$type]['lly'];
				$pagedim[$page][$type]['lly'] = $tmp;
				$tmp = $pagedim[$page][$type]['urx'];
				$pagedim[$page][$type]['urx'] = $pagedim[$page][$type]['ury'];
				$pagedim[$page][$type]['ury'] = $tmp;
			}
		}
		return $pagedim;
	}
	public static function getPageLayoutMode($layout='SinglePage') {
		switch ($layout) {
			case 'default':
			case 'single':
			case 'SinglePage': {
				$layout_mode = 'SinglePage';
				break;
			}
			case 'continuous':
			case 'OneColumn': {
				$layout_mode = 'OneColumn';
				break;
			}
			case 'two':
			case 'TwoColumnLeft': {
				$layout_mode = 'TwoColumnLeft';
				break;
			}
			case 'TwoColumnRight': {
				$layout_mode = 'TwoColumnRight';
				break;
			}
			case 'TwoPageLeft': {
				$layout_mode = 'TwoPageLeft';
				break;
			}
			case 'TwoPageRight': {
				$layout_mode = 'TwoPageRight';
				break;
			}
			default: {
				$layout_mode = 'SinglePage';
			}
		}
		return $layout_mode;
	}
	public static function getPageMode($mode='UseNone') {
		switch ($mode) {
			case 'UseNone': {
				$page_mode = 'UseNone';
				break;
			}
			case 'UseOutlines': {
				$page_mode = 'UseOutlines';
				break;
			}
			case 'UseThumbs': {
				$page_mode = 'UseThumbs';
				break;
			}
			case 'FullScreen': {
				$page_mode = 'FullScreen';
				break;
			}
			case 'UseOC': {
				$page_mode = 'UseOC';
				break;
			}
			case '': {
				$page_mode = 'UseAttachments';
				break;
			}
			default: {
				$page_mode = 'UseNone';
			}
		}
		return $page_mode;
	}


}