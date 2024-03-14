<?php
//============================================================+
// File name   : tcpdf_filters.php
// Version     : 1.0.001
// Begin       : 2011-05-23
// Last Update : 2014-04-25
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2011-2013 Nicola Asuni - Tecnick.com LTD
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
// Description : This is a PHP class for decoding common PDF filters (PDF 32000-2008 - 7.4 Filters).
//
//============================================================+

/**
 * @file
 * This is a PHP class for decoding common PDF filters (PDF 32000-2008 - 7.4 Filters).<br>
 * @package com.tecnick.tcpdf
 * @author Nicola Asuni
 * @version 1.0.001
 */

/**
 * @class TCPDF_FILTERS
 * This is a PHP class for decoding common PDF filters (PDF 32000-2008 - 7.4 Filters).<br>
 * @package com.tecnick.tcpdf
 * @brief This is a PHP class for decoding common PDF filters.
 * @version 1.0.001
 * @author Nicola Asuni - info@tecnick.com
 */
class TCPDF_FILTERS {
	private static $available_filters = array('ASCIIHexDecode', 'ASCII85Decode', 'LZWDecode', 'FlateDecode', 'RunLengthDecode');
	public static function getAvailableFilters() {
		return self::$available_filters;
	}
	public static function decodeFilter($filter, $data) {
		switch ($filter) {
			case 'ASCIIHexDecode': {
				return self::decodeFilterASCIIHexDecode($data);
				break;
			}
			case 'ASCII85Decode': {
				return self::decodeFilterASCII85Decode($data);
				break;
			}
			case 'LZWDecode': {
				return self::decodeFilterLZWDecode($data);
				break;
			}
			case 'FlateDecode': {
				return self::decodeFilterFlateDecode($data);
				break;
			}
			case 'RunLengthDecode': {
				return self::decodeFilterRunLengthDecode($data);
				break;
			}
			case 'CCITTFaxDecode': {
				return self::decodeFilterCCITTFaxDecode($data);
				break;
			}
			case 'JBIG2Decode': {
				return self::decodeFilterJBIG2Decode($data);
				break;
			}
			case 'DCTDecode': {
				return self::decodeFilterDCTDecode($data);
				break;
			}
			case 'JPXDecode': {
				return self::decodeFilterJPXDecode($data);
				break;
			}
			case 'Crypt': {
				return self::decodeFilterCrypt($data);
				break;
			}
			default: {
				return self::decodeFilterStandard($data);
				break;
			}
		}
	}
	public static function decodeFilterStandard($data) {
		return $data;
	}
	public static function decodeFilterASCIIHexDecode($data) {
		$decoded = '';
		$data = preg_replace('/[\s]/', '', $data);
		$eod = strpos($data, '>');
		if ($eod !== false) {
			$data = substr($data, 0, $eod);
			$eod = true;
		}
		$data_length = strlen($data);
		if (($data_length % 2) != 0) {
			if ($eod) {
				$data = substr($data, 0, -1).'0'.substr($data, -1);
			} else {
				self::Error('decodeFilterASCIIHexDecode: invalid code');
			}
		}
		if (preg_match('/[^a-fA-F\d]/', $data) > 0) {
			self::Error('decodeFilterASCIIHexDecode: invalid code');
		}
		$decoded = pack('H*', $data);
		return $decoded;
	}
	public static function decodeFilterASCII85Decode($data) {
		$decoded = '';
		$data = preg_replace('/[\s]/', '', $data);
		if (strpos($data, '<~') !== false) {
			$data = substr($data, 2);
		}
		$eod = strpos($data, '~>');
		if ($eod !== false) {
			$data = substr($data, 0, $eod);
		}
		$data_length = strlen($data);
		if (preg_match('/[^\x21-\x75,\x74]/', $data) > 0) {
			self::Error('decodeFilterASCII85Decode: invalid code');
		}
		$zseq = chr(0).chr(0).chr(0).chr(0);
		$group_pos = 0;
		$tuple = 0;
		$pow85 = array((85*85*85*85), (85*85*85), (85*85), 85, 1);
		$last_pos = ($data_length - 1);
		for ($i = 0; $i < $data_length; ++$i) {
			$char = ord($data[$i]);
			if ($char == 122) {
				if ($group_pos == 0) {
					$decoded .= $zseq;
				} else {
					self::Error('decodeFilterASCII85Decode: invalid code');
				}
			} else {
				$tuple += (($char - 33) * $pow85[$group_pos]);
				if ($group_pos == 4) {
					$decoded .= chr($tuple >> 24).chr($tuple >> 16).chr($tuple >> 8).chr($tuple);
					$tuple = 0;
					$group_pos = 0;
				} else {
					++$group_pos;
				}
			}
		}
		if ($group_pos > 1) {
			$tuple += $pow85[($group_pos - 1)];
		}
		switch ($group_pos) {
			case 4: {
				$decoded .= chr($tuple >> 24).chr($tuple >> 16).chr($tuple >> 8);
				break;
			}
			case 3: {
				$decoded .= chr($tuple >> 24).chr($tuple >> 16);
				break;
			}
			case 2: {
				$decoded .= chr($tuple >> 24);
				break;
			}
			case 1: {
				self::Error('decodeFilterASCII85Decode: invalid code');
				break;
			}
		}
		return $decoded;
	}
	public static function decodeFilterLZWDecode($data) {
		$decoded = '';
		$data_length = strlen($data);
		$bitstring = '';
		for ($i = 0; $i < $data_length; ++$i) {
			$bitstring .= sprintf('%08b', ord($data[$i]));
		}
		$data_length = strlen($bitstring);
		$bitlen = 9;
		$dix = 258;
		$dictionary = array();
		for ($i = 0; $i < 256; ++$i) {
			$dictionary[$i] = chr($i);
		}
		$prev_index = 0;
		while (($data_length > 0) AND (($index = bindec(substr($bitstring, 0, $bitlen))) != 257)) {
			$bitstring = substr($bitstring, $bitlen);
			$data_length -= $bitlen;
			if ($index == 256) {
				$bitlen = 9;
				$dix = 258;
				$prev_index = 256;
				$dictionary = array();
				for ($i = 0; $i < 256; ++$i) {
					$dictionary[$i] = chr($i);
				}
			} elseif ($prev_index == 256) {
				$decoded .= $dictionary[$index];
				$prev_index = $index;
			} else {
				if ($index < $dix) {
					$decoded .= $dictionary[$index];
					$dic_val = $dictionary[$prev_index].$dictionary[$index][0];
					$prev_index = $index;
				} else {
					$dic_val = $dictionary[$prev_index].$dictionary[$prev_index][0];
					$decoded .= $dic_val;
				}
				$dictionary[$dix] = $dic_val;
				++$dix;
				if ($dix == 2047) {
					$bitlen = 12;
				} elseif ($dix == 1023) {
					$bitlen = 11;
				} elseif ($dix == 511) {
					$bitlen = 10;
				}
			}
		}
		return $decoded;
	}
	public static function decodeFilterFlateDecode($data) {
		$decoded = @gzuncompress($data);
		if ($decoded === false) {
			self::Error('decodeFilterFlateDecode: invalid code');
		}
		return $decoded;
	}
	public static function decodeFilterRunLengthDecode($data) {
		$decoded = '';
		$data_length = strlen($data);
		$i = 0;
		while($i < $data_length) {
			$byte = ord($data[$i]);
			if ($byte == 128) {
				break;
			} elseif ($byte < 128) {
				$decoded .= substr($data, ($i + 1), ($byte + 1));
				$i += ($byte + 2);
			} else {
				$decoded .= str_repeat($data[($i + 1)], (257 - $byte));
				$i += 2;
			}
		}
		return $decoded;
	}
	public static function decodeFilterCCITTFaxDecode($data) {
		self::Error('~decodeFilterCCITTFaxDecode: this method has not been yet implemented');
	}
	public static function decodeFilterJBIG2Decode($data) {
		self::Error('~decodeFilterJBIG2Decode: this method has not been yet implemented');
	}
	public static function decodeFilterDCTDecode($data) {
		self::Error('~decodeFilterDCTDecode: this method has not been yet implemented');
	}
	public static function decodeFilterJPXDecode($data) {
		self::Error('~decodeFilterJPXDecode: this method has not been yet implemented');
	}
	public static function decodeFilterCrypt($data) {
		self::Error('~decodeFilterCrypt: this method has not been yet implemented');
	}
	public static function Error($msg) {
		throw new Exception('TCPDF_PARSER ERROR: '.$msg);
	}

}