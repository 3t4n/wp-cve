<?php
//============================================================+
// File name   : datamatrix.php
// Version     : 1.0.008
// Begin       : 2010-06-07
// Last Update : 2014-05-06
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2010-2014  Nicola Asuni - Tecnick.com LTD
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
// DESCRIPTION :
//
// Class to create DataMatrix ECC 200 barcode arrays for TCPDF class.
// DataMatrix (ISO/IEC 16022:2006) is a 2-dimensional bar code.
//============================================================+

/**
* @file
* Class to create DataMatrix ECC 200 barcode arrays for TCPDF class.
* DataMatrix (ISO/IEC 16022:2006) is a 2-dimensional bar code.
*
* @package com.tecnick.tcpdf
* @author Nicola Asuni
* @version 1.0.008
*/

// custom definitions
if (!defined('DATAMATRIXDEFS')) {
	define('DATAMATRIXDEFS', true);

}
define('ENC_ASCII', 0);
define('ENC_C40', 1);
define('ENC_TXT', 2);
define('ENC_X12', 3);
define('ENC_EDF', 4);
define('ENC_BASE256', 5);
define('ENC_ASCII_EXT', 6);
define('ENC_ASCII_NUM', 7);
class Datamatrix {
	protected $barcode_array = array();
	protected $last_enc = ENC_ASCII;
	protected $symbattr = array(
		array(0x00a,0x00a,0x008,0x008,0x00a,0x00a,0x008,0x008,0x001,0x001,0x001,0x003,0x005,0x001,0x003,0x005),
		array(0x00c,0x00c,0x00a,0x00a,0x00c,0x00c,0x00a,0x00a,0x001,0x001,0x001,0x005,0x007,0x001,0x005,0x007),
		array(0x00e,0x00e,0x00c,0x00c,0x00e,0x00e,0x00c,0x00c,0x001,0x001,0x001,0x008,0x00a,0x001,0x008,0x00a),
		array(0x010,0x010,0x00e,0x00e,0x010,0x010,0x00e,0x00e,0x001,0x001,0x001,0x00c,0x00c,0x001,0x00c,0x00c),
		array(0x012,0x012,0x010,0x010,0x012,0x012,0x010,0x010,0x001,0x001,0x001,0x012,0x00e,0x001,0x012,0x00e),
		array(0x014,0x014,0x012,0x012,0x014,0x014,0x012,0x012,0x001,0x001,0x001,0x016,0x012,0x001,0x016,0x012),
		array(0x016,0x016,0x014,0x014,0x016,0x016,0x014,0x014,0x001,0x001,0x001,0x01e,0x014,0x001,0x01e,0x014),
		array(0x018,0x018,0x016,0x016,0x018,0x018,0x016,0x016,0x001,0x001,0x001,0x024,0x018,0x001,0x024,0x018),
		array(0x01a,0x01a,0x018,0x018,0x01a,0x01a,0x018,0x018,0x001,0x001,0x001,0x02c,0x01c,0x001,0x02c,0x01c),
		array(0x020,0x020,0x01c,0x01c,0x010,0x010,0x00e,0x00e,0x002,0x002,0x004,0x03e,0x024,0x001,0x03e,0x024),
		array(0x024,0x024,0x020,0x020,0x012,0x012,0x010,0x010,0x002,0x002,0x004,0x056,0x02a,0x001,0x056,0x02a),
		array(0x028,0x028,0x024,0x024,0x014,0x014,0x012,0x012,0x002,0x002,0x004,0x072,0x030,0x001,0x072,0x030),
		array(0x02c,0x02c,0x028,0x028,0x016,0x016,0x014,0x014,0x002,0x002,0x004,0x090,0x038,0x001,0x090,0x038),
		array(0x030,0x030,0x02c,0x02c,0x018,0x018,0x016,0x016,0x002,0x002,0x004,0x0ae,0x044,0x001,0x0ae,0x044),
		array(0x034,0x034,0x030,0x030,0x01a,0x01a,0x018,0x018,0x002,0x002,0x004,0x0cc,0x054,0x002,0x066,0x02a),
		array(0x040,0x040,0x038,0x038,0x010,0x010,0x00e,0x00e,0x004,0x004,0x010,0x118,0x070,0x002,0x08c,0x038),
		array(0x048,0x048,0x040,0x040,0x012,0x012,0x010,0x010,0x004,0x004,0x010,0x170,0x090,0x004,0x05c,0x024),
		array(0x050,0x050,0x048,0x048,0x014,0x014,0x012,0x012,0x004,0x004,0x010,0x1c8,0x0c0,0x004,0x072,0x030),
		array(0x058,0x058,0x050,0x050,0x016,0x016,0x014,0x014,0x004,0x004,0x010,0x240,0x0e0,0x004,0x090,0x038),
		array(0x060,0x060,0x058,0x058,0x018,0x018,0x016,0x016,0x004,0x004,0x010,0x2b8,0x110,0x004,0x0ae,0x044),
		array(0x068,0x068,0x060,0x060,0x01a,0x01a,0x018,0x018,0x004,0x004,0x010,0x330,0x150,0x006,0x088,0x038),
		array(0x078,0x078,0x06c,0x06c,0x014,0x014,0x012,0x012,0x006,0x006,0x024,0x41a,0x198,0x006,0x0af,0x044),
		array(0x084,0x084,0x078,0x078,0x016,0x016,0x014,0x014,0x006,0x006,0x024,0x518,0x1f0,0x008,0x0a3,0x03e),
		array(0x090,0x090,0x084,0x084,0x018,0x018,0x016,0x016,0x006,0x006,0x024,0x616,0x26c,0x00a,0x09c,0x03e),
		array(0x008,0x012,0x006,0x010,0x008,0x012,0x006,0x010,0x001,0x001,0x001,0x005,0x007,0x001,0x005,0x007),
		array(0x008,0x020,0x006,0x01c,0x008,0x010,0x006,0x00e,0x001,0x002,0x002,0x00a,0x00b,0x001,0x00a,0x00b),
		array(0x00c,0x01a,0x00a,0x018,0x00c,0x01a,0x00a,0x018,0x001,0x001,0x001,0x010,0x00e,0x001,0x010,0x00e),
		array(0x00c,0x024,0x00a,0x020,0x00c,0x012,0x00a,0x010,0x001,0x002,0x002,0x00c,0x012,0x001,0x00c,0x012),
		array(0x010,0x024,0x00e,0x020,0x010,0x012,0x00e,0x010,0x001,0x002,0x002,0x020,0x018,0x001,0x020,0x018),
		array(0x010,0x030,0x00e,0x02c,0x010,0x018,0x00e,0x016,0x001,0x002,0x002,0x031,0x01c,0x001,0x031,0x01c)
	);
	protected $chset_id = array(ENC_C40 => 'C40', ENC_TXT => 'TXT', ENC_X12 =>'X12');
	protected $chset = array(
		'C40' => array(
			'S1'=>0x00,'S2'=>0x01,'S3'=>0x02,0x20=>0x03,0x30=>0x04,0x31=>0x05,0x32=>0x06,0x33=>0x07,0x34=>0x08,0x35=>0x09,
			0x36=>0x0a,0x37=>0x0b,0x38=>0x0c,0x39=>0x0d,0x41=>0x0e,0x42=>0x0f,0x43=>0x10,0x44=>0x11,0x45=>0x12,0x46=>0x13,
			0x47=>0x14,0x48=>0x15,0x49=>0x16,0x4a=>0x17,0x4b=>0x18,0x4c=>0x19,0x4d=>0x1a,0x4e=>0x1b,0x4f=>0x1c,0x50=>0x1d,
			0x51=>0x1e,0x52=>0x1f,0x53=>0x20,0x54=>0x21,0x55=>0x22,0x56=>0x23,0x57=>0x24,0x58=>0x25,0x59=>0x26,0x5a=>0x27),
		'TXT' => array(
			'S1'=>0x00,'S2'=>0x01,'S3'=>0x02,0x20=>0x03,0x30=>0x04,0x31=>0x05,0x32=>0x06,0x33=>0x07,0x34=>0x08,0x35=>0x09,
			0x36=>0x0a,0x37=>0x0b,0x38=>0x0c,0x39=>0x0d,0x61=>0x0e,0x62=>0x0f,0x63=>0x10,0x64=>0x11,0x65=>0x12,0x66=>0x13,
			0x67=>0x14,0x68=>0x15,0x69=>0x16,0x6a=>0x17,0x6b=>0x18,0x6c=>0x19,0x6d=>0x1a,0x6e=>0x1b,0x6f=>0x1c,0x70=>0x1d,
			0x71=>0x1e,0x72=>0x1f,0x73=>0x20,0x74=>0x21,0x75=>0x22,0x76=>0x23,0x77=>0x24,0x78=>0x25,0x79=>0x26,0x7a=>0x27),
		'SH1' => array(
			0x00=>0x00,0x01=>0x01,0x02=>0x02,0x03=>0x03,0x04=>0x04,0x05=>0x05,0x06=>0x06,0x07=>0x07,0x08=>0x08,0x09=>0x09,
			0x0a=>0x0a,0x0b=>0x0b,0x0c=>0x0c,0x0d=>0x0d,0x0e=>0x0e,0x0f=>0x0f,0x10=>0x10,0x11=>0x11,0x12=>0x12,0x13=>0x13,
			0x14=>0x14,0x15=>0x15,0x16=>0x16,0x17=>0x17,0x18=>0x18,0x19=>0x19,0x1a=>0x1a,0x1b=>0x1b,0x1c=>0x1c,0x1d=>0x1d,
			0x1e=>0x1e,0x1f=>0x1f),
		'SH2' => array(
			0x21=>0x00,0x22=>0x01,0x23=>0x02,0x24=>0x03,0x25=>0x04,0x26=>0x05,0x27=>0x06,0x28=>0x07,0x29=>0x08,0x2a=>0x09,
			0x2b=>0x0a,0x2c=>0x0b,0x2d=>0x0c,0x2e=>0x0d,0x2f=>0x0e,0x3a=>0x0f,0x3b=>0x10,0x3c=>0x11,0x3d=>0x12,0x3e=>0x13,
			0x3f=>0x14,0x40=>0x15,0x5b=>0x16,0x5c=>0x17,0x5d=>0x18,0x5e=>0x19,0x5f=>0x1a,'F1'=>0x1b,'US'=>0x1e),
		'S3C' => array(
			0x60=>0x00,0x61=>0x01,0x62=>0x02,0x63=>0x03,0x64=>0x04,0x65=>0x05,0x66=>0x06,0x67=>0x07,0x68=>0x08,0x69=>0x09,
			0x6a=>0x0a,0x6b=>0x0b,0x6c=>0x0c,0x6d=>0x0d,0x6e=>0x0e,0x6f=>0x0f,0x70=>0x10,0x71=>0x11,0x72=>0x12,0x73=>0x13,
			0x74=>0x14,0x75=>0x15,0x76=>0x16,0x77=>0x17,0x78=>0x18,0x79=>0x19,0x7a=>0x1a,0x7b=>0x1b,0x7c=>0x1c,0x7d=>0x1d,
			0x7e=>0x1e,0x7f=>0x1f),
		'S3T' => array(
			0x60=>0x00,0x41=>0x01,0x42=>0x02,0x43=>0x03,0x44=>0x04,0x45=>0x05,0x46=>0x06,0x47=>0x07,0x48=>0x08,0x49=>0x09,
			0x4a=>0x0a,0x4b=>0x0b,0x4c=>0x0c,0x4d=>0x0d,0x4e=>0x0e,0x4f=>0x0f,0x50=>0x10,0x51=>0x11,0x52=>0x12,0x53=>0x13,
			0x54=>0x14,0x55=>0x15,0x56=>0x16,0x57=>0x17,0x58=>0x18,0x59=>0x19,0x5a=>0x1a,0x7b=>0x1b,0x7c=>0x1c,0x7d=>0x1d,
			0x7e=>0x1e,0x7f=>0x1f),
		'X12' => array(
			0x0d=>0x00,0x2a=>0x01,0x3e=>0x02,0x20=>0x03,0x30=>0x04,0x31=>0x05,0x32=>0x06,0x33=>0x07,0x34=>0x08,0x35=>0x09,
			0x36=>0x0a,0x37=>0x0b,0x38=>0x0c,0x39=>0x0d,0x41=>0x0e,0x42=>0x0f,0x43=>0x10,0x44=>0x11,0x45=>0x12,0x46=>0x13,
			0x47=>0x14,0x48=>0x15,0x49=>0x16,0x4a=>0x17,0x4b=>0x18,0x4c=>0x19,0x4d=>0x1a,0x4e=>0x1b,0x4f=>0x1c,0x50=>0x1d,
			0x51=>0x1e,0x52=>0x1f,0x53=>0x20,0x54=>0x21,0x55=>0x22,0x56=>0x23,0x57=>0x24,0x58=>0x25,0x59=>0x26,0x5a=>0x27)
		);
	public function __construct($code) {
		$barcode_array = array();
		if ((is_null($code)) OR ($code == '\0') OR ($code == '')) {
			return false;
		}
		$cw = $this->getHighLevelEncoding($code);
		$nd = count($cw);
		if ($nd > 1558) {
			return false;
		}
		foreach ($this->symbattr as $params) {
			if ($params[11] >= $nd) {
				break;
			}
		}
		if ($params[11] < $nd) {
			return false;
		} elseif ($params[11] > $nd) {
			if ((($params[11] - $nd) > 1) AND ($cw[($nd - 1)] != 254)) {
				if ($this->last_enc == ENC_EDF) {
					$cw[] = 124;
					++$nd;
				} elseif (($this->last_enc != ENC_ASCII) AND ($this->last_enc != ENC_BASE256)) {
					$cw[] = 254;
					++$nd;
				}
			}
			if ($params[11] > $nd) {
				$cw[] = 129;
				++$nd;
				for ($i = $nd; $i < $params[11]; ++$i) {
					$cw[] = $this->get253StateCodeword(129, $i);
				}
			}
		}
		$cw = $this->getErrorCorrection($cw, $params[13], $params[14], $params[15]);
		$grid = array_fill(0, ($params[2] * $params[3]), 0);
		$places = $this->getPlacementMap($params[2], $params[3]);
		$grid = array();
		$i = 0;
		$rdri = ($params[4] - 1);
		$rdci = ($params[5] - 1);
		for ($vr = 0; $vr < $params[9]; ++$vr) {
			for ($r = 0; $r < $params[4]; ++$r) {
				$row = (($vr * $params[4]) + $r);
				for ($hr = 0; $hr < $params[8]; ++$hr) {
					for ($c = 0; $c < $params[5]; ++$c) {
						$col = (($hr * $params[5]) + $c);
						if ($r == 0) {
							if ($c % 2) {
								$grid[$row][$col] = 0;
							} else {
								$grid[$row][$col] = 1;
							}
						} elseif ($r == $rdri) {
							$grid[$row][$col] = 1;
						} elseif ($c == 0) {
							$grid[$row][$col] = 1;
						} elseif ($c == $rdci) {
							if ($r % 2) {
								$grid[$row][$col] = 1;
							} else {
								$grid[$row][$col] = 0;
							}
						} else {
							if ($places[$i] < 2) {
								$grid[$row][$col] = $places[$i];
							} else {
								$cw_id = (floor($places[$i] / 10) - 1);
								$cw_bit = pow(2, (8 - ($places[$i] % 10)));
								$grid[$row][$col] = (($cw[$cw_id] & $cw_bit) == 0) ? 0 : 1;
							}
							++$i;
						}
					}
				}
			}
		}
		$this->barcode_array['num_rows'] = $params[0];
		$this->barcode_array['num_cols'] = $params[1];
		$this->barcode_array['bcode'] = $grid;
	}
	public function getBarcodeArray() {
		return $this->barcode_array;
	}
	protected function getGFProduct($a, $b, $log, $alog, $gf) {
		if (($a == 0) OR ($b == 0)) {
			return 0;
		}
		return ($alog[($log[$a] + $log[$b]) % ($gf - 1)]);
	}
	protected function getErrorCorrection($wd, $nb, $nd, $nc, $gf=256, $pp=301) {
		$log[0] = 0;
		$alog[0] = 1;
		for ($i = 1; $i < $gf; ++$i) {
			$alog[$i] = ($alog[($i - 1)] * 2);
			if ($alog[$i] >= $gf) {
				$alog[$i] ^= $pp;
			}
			$log[$alog[$i]] = $i;
		}
		ksort($log);
		$c = array_fill(0, ($nc + 1), 0);
		$c[0] = 1;
		for ($i = 1; $i <= $nc; ++$i) {
			$c[$i] = $c[($i-1)];
			for ($j = ($i - 1); $j >= 1; --$j) {
				$c[$j] = $c[($j - 1)] ^ $this->getGFProduct($c[$j], $alog[$i], $log, $alog, $gf);
			}
			$c[0] = $this->getGFProduct($c[0], $alog[$i], $log, $alog, $gf);
		}
		ksort($c);
		$num_wd = ($nb * $nd);
		$num_we = ($nb * $nc);
		for ($b = 0; $b < $nb; ++$b) {
			$block = array();
			for ($n = $b; $n < $num_wd; $n += $nb) {
				$block[] = $wd[$n];
			}
			$we = array_fill(0, ($nc + 1), 0);
			for ($i = 0; $i < $nd; ++$i) {
				$k = ($we[0] ^ $block[$i]);
				for ($j = 0; $j < $nc; ++$j) {
					$we[$j] = ($we[($j + 1)] ^ $this->getGFProduct($k, $c[($nc - $j - 1)], $log, $alog, $gf));
				}
			}
			$j = 0;
			for ($i = $b; $i < $num_we; $i += $nb) {
				$wd[($num_wd + $i)] = $we[$j];
				++$j;
			}
		}
		ksort($wd);
		return $wd;
	}
	protected function get253StateCodeword($cwpad, $cwpos) {
		$pad = ($cwpad + (((149 * $cwpos) % 253) + 1));
		if ($pad > 254) {
			$pad -= 254;
		}
		return $pad;
	}
	protected function get255StateCodeword($cwpad, $cwpos) {
		$pad = ($cwpad + (((149 * $cwpos) % 255) + 1));
		if ($pad > 255) {
			$pad -= 256;
		}
		return $pad;
	}
	protected function isCharMode($chr, $mode) {
		$status = false;
		switch ($mode) {
			case ENC_ASCII: {
				$status = (($chr >= 0) AND ($chr <= 127));
				break;
			}
			case ENC_C40: {
				$status = (($chr == 32) OR (($chr >= 48) AND ($chr <= 57)) OR (($chr >= 65) AND ($chr <= 90)));
				break;
			}
			case ENC_TXT: {
				$status = (($chr == 32) OR (($chr >= 48) AND ($chr <= 57)) OR (($chr >= 97) AND ($chr <= 122)));
				break;
			}
			case ENC_X12: {
				$status = (($chr == 13) OR ($chr == 42) OR ($chr == 62));
				break;
			}
			case ENC_EDF: {
				$status = (($chr >= 32) AND ($chr <= 94));
				break;
			}
			case ENC_BASE256: {
				$status = (($chr == 232) OR ($chr == 233) OR ($chr == 234) OR ($chr == 241));
				break;
			}
			case ENC_ASCII_EXT: {
				$status = (($chr >= 128) AND ($chr <= 255));
				break;
			}
			case ENC_ASCII_NUM: {
				$status = (($chr >= 48) AND ($chr <= 57));
				break;
			}
		}
		return $status;
	}
	protected function lookAheadTest($data, $pos, $mode) {
		$data_length = strlen($data);
		if ($pos >= $data_length) {
			return $mode;
		}
		$charscount = 0;
		if ($mode == ENC_ASCII) {
			$numch = array(0, 1, 1, 1, 1, 1.25);
		} else {
			$numch = array(1, 2, 2, 2, 2, 2.25);
			$numch[$mode] = 0;
		}
		while (true) {
			if (($pos + $charscount) == $data_length) {
				if ($numch[ENC_ASCII] <= ceil(min($numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_X12], $numch[ENC_EDF], $numch[ENC_BASE256]))) {
					return ENC_ASCII;
				}
				if ($numch[ENC_BASE256] < ceil(min($numch[ENC_ASCII], $numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_X12], $numch[ENC_EDF]))) {
					return ENC_BASE256;
				}
				if ($numch[ENC_EDF] < ceil(min($numch[ENC_ASCII], $numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_X12], $numch[ENC_BASE256]))) {
					return ENC_EDF;
				}
				if ($numch[ENC_TXT] < ceil(min($numch[ENC_ASCII], $numch[ENC_C40], $numch[ENC_X12], $numch[ENC_EDF], $numch[ENC_BASE256]))) {
					return ENC_TXT;
				}
				if ($numch[ENC_X12] < ceil(min($numch[ENC_ASCII], $numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_EDF], $numch[ENC_BASE256]))) {
					return ENC_X12;
				}
				return ENC_C40;
			}
			$chr = ord($data[$pos + $charscount]);
			$charscount++;
			if ($this->isCharMode($chr, ENC_ASCII_NUM)) {
				$numch[ENC_ASCII] += (1 / 2);
			} elseif ($this->isCharMode($chr, ENC_ASCII_EXT)) {
				$numch[ENC_ASCII] = ceil($numch[ENC_ASCII]);
				$numch[ENC_ASCII] += 2;
			} else {
				$numch[ENC_ASCII] = ceil($numch[ENC_ASCII]);
				$numch[ENC_ASCII] += 1;
			}
			if ($this->isCharMode($chr, ENC_C40)) {
				$numch[ENC_C40] += (2 / 3);
			} elseif ($this->isCharMode($chr, ENC_ASCII_EXT)) {
				$numch[ENC_C40] += (8 / 3);
			} else {
				$numch[ENC_C40] += (4 / 3);
			}
			if ($this->isCharMode($chr, ENC_TXT)) {
				$numch[ENC_TXT] += (2 / 3);
			} elseif ($this->isCharMode($chr, ENC_ASCII_EXT)) {
				$numch[ENC_TXT] += (8 / 3);
			} else {
				$numch[ENC_TXT] += (4 / 3);
			}
			if ($this->isCharMode($chr, ENC_X12) OR $this->isCharMode($chr, ENC_C40)) {
				$numch[ENC_X12] += (2 / 3);
			} elseif ($this->isCharMode($chr, ENC_ASCII_EXT)) {
				$numch[ENC_X12] += (13 / 3);
			} else {
				$numch[ENC_X12] += (10 / 3);
			}
			if ($this->isCharMode($chr, ENC_EDF)) {
				$numch[ENC_EDF] += (3 / 4);
			} elseif ($this->isCharMode($chr, ENC_ASCII_EXT)) {
				$numch[ENC_EDF] += (17 / 4);
			} else {
				$numch[ENC_EDF] += (13 / 4);
			}
			if ($this->isCharMode($chr, ENC_BASE256)) {
				$numch[ENC_BASE256] += 4;
			} else {
				$numch[ENC_BASE256] += 1;
			}
			if ($charscount >= 4) {
				if (($numch[ENC_ASCII] + 1) <= min($numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_X12], $numch[ENC_EDF], $numch[ENC_BASE256])) {
					return ENC_ASCII;
				}
				if ((($numch[ENC_BASE256] + 1) <= $numch[ENC_ASCII])
					OR (($numch[ENC_BASE256] + 1) < min($numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_X12], $numch[ENC_EDF]))) {
					return ENC_BASE256;
				}
				if (($numch[ENC_EDF] + 1) < min($numch[ENC_ASCII], $numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_X12], $numch[ENC_BASE256])) {
					return ENC_EDF;
				}
				if (($numch[ENC_TXT] + 1) < min($numch[ENC_ASCII], $numch[ENC_C40], $numch[ENC_X12], $numch[ENC_EDF], $numch[ENC_BASE256])) {
					return ENC_TXT;
				}
				if (($numch[ENC_X12] + 1) < min($numch[ENC_ASCII], $numch[ENC_C40], $numch[ENC_TXT], $numch[ENC_EDF], $numch[ENC_BASE256])) {
					return ENC_X12;
				}
				if (($numch[ENC_C40] + 1) < min($numch[ENC_ASCII], $numch[ENC_TXT], $numch[ENC_EDF], $numch[ENC_BASE256])) {
					if ($numch[ENC_C40] < $numch[ENC_X12]) {
						return ENC_C40;
					}
					if ($numch[ENC_C40] == $numch[ENC_X12]) {
						$k = ($pos + $charscount + 1);
						while ($k < $data_length) {
							$tmpchr = ord($data[$k]);
							if ($this->isCharMode($tmpchr, ENC_X12)) {
								return ENC_X12;
							} elseif (!($this->isCharMode($tmpchr, ENC_X12) OR $this->isCharMode($tmpchr, ENC_C40))) {
								break;
							}
							++$k;
						}
						return ENC_C40;
					}
				}
			}
		}
	}
	protected function getSwitchEncodingCodeword($mode) {
		switch ($mode) {
			case ENC_ASCII: {
				$cw = 254;
				if ($this->last_enc == ENC_EDF) {
					$cw = 124;
				}
				break;
			}
			case ENC_C40: {
				$cw = 230;
				break;
			}
			case ENC_TXT: {
				$cw = 239;
				break;
			}
			case ENC_X12: {
				$cw = 238;
				break;
			}
			case ENC_EDF: {
				$cw = 240;
				break;
			}
			case ENC_BASE256: {
				$cw = 231;
				break;
			}
		}
		return $cw;
	}
	protected function getMaxDataCodewords($numcw) {
		foreach ($this->symbattr as $key => $matrix) {
			if ($matrix[11] >= $numcw) {
				return $matrix[11];
			}
		}
		return 0;
	}
	protected function getHighLevelEncoding($data) {
		$enc = ENC_ASCII;
		$pos = 0;
		$cw = array();
		$cw_num = 0;
		$data_length = strlen($data);
		while ($pos < $data_length) {
			$this->last_enc = $enc;
			switch ($enc) {
				case ENC_ASCII: {
					if (($data_length > 1) AND ($pos < ($data_length - 1)) AND ($this->isCharMode(ord($data[$pos]), ENC_ASCII_NUM) AND $this->isCharMode(ord($data[$pos + 1]), ENC_ASCII_NUM))) {
						$cw[] = (intval(substr($data, $pos, 2)) + 130);
						++$cw_num;
						$pos += 2;
					} else {
						$newenc = $this->lookAheadTest($data, $pos, $enc);
						if ($newenc != $enc) {
							$enc = $newenc;
							$cw[] = $this->getSwitchEncodingCodeword($enc);
							++$cw_num;
						} else {
							$chr = ord($data[$pos]);
							++$pos;
							if ($this->isCharMode($chr, ENC_ASCII_EXT)) {
								$cw[] = 235;
								$cw[] = ($chr - 127);
								$cw_num += 2;
							} else {
								$cw[] = ($chr + 1);
								++$cw_num;
							}
						}
					}
					break;
				}
				case ENC_C40 :
				case ENC_TXT :
				case ENC_X12 : {
					$temp_cw = array();
					$p = 0;
					$epos = $pos;
					$set_id = $this->chset_id[$enc];
					$charset = $this->chset[$set_id];
					do {
						$chr = ord($data[$epos]);
						++$epos;
						if ($chr & 0x80) {
							if ($enc == ENC_X12) {
								return false;
							}
							$chr = ($chr & 0x7f);
							$temp_cw[] = 1;
							$temp_cw[] = 30;
							$p += 2;
						}
						if (isset($charset[$chr])) {
							$temp_cw[] = $charset[$chr];
							++$p;
						} else {
							if (isset($this->chset['SH1'][$chr])) {
								$temp_cw[] = 0;
								$shiftset = $this->chset['SH1'];
							} elseif (isset($chr, $this->chset['SH2'][$chr])) {
								$temp_cw[] = 1;
								$shiftset = $this->chset['SH2'];
							} elseif (($enc == ENC_C40) AND isset($this->chset['S3C'][$chr])) {
								$temp_cw[] = 2;
								$shiftset = $this->chset['S3C'];
							} elseif (($enc == ENC_TXT) AND isset($this->chset['S3T'][$chr])) {
								$temp_cw[] = 2;
								$shiftset = $this->chset['S3T'];
							} else {
								return false;
							}
							$temp_cw[] = $shiftset[$chr];
							$p += 2;
						}
						if ($p >= 3) {
							$c1 = array_shift($temp_cw);
							$c2 = array_shift($temp_cw);
							$c3 = array_shift($temp_cw);
							$p -= 3;
							$tmp = ((1600 * $c1) + (40 * $c2) + $c3 + 1);
							$cw[] = ($tmp >> 8);
							$cw[] = ($tmp % 256);
							$cw_num += 2;
							$pos = $epos;
							$newenc = $this->lookAheadTest($data, $pos, $enc);
							if ($newenc != $enc) {
								$enc = $newenc;
								if ($enc != ENC_ASCII) {
									$cw[] = $this->getSwitchEncodingCodeword(ENC_ASCII);
									++$cw_num;
								}
								$cw[] = $this->getSwitchEncodingCodeword($enc);
								++$cw_num;
								$pos -= $p;
								$p = 0;
								break;
							}
						}
					} while (($p > 0) AND ($epos < $data_length));
					if ($p > 0) {
						$cwr = ($this->getMaxDataCodewords($cw_num) - $cw_num);
						if (($cwr == 1) AND ($p == 1)) {
							$c1 = array_shift($temp_cw);
							--$p;
							$cw[] = ($chr + 1);
							++$cw_num;
							$pos = $epos;
							$enc = ENC_ASCII;
							$this->last_enc = $enc;
						} elseif (($cwr == 2) AND ($p == 1)) {
							$c1 = array_shift($temp_cw);
							--$p;
							$cw[] = 254;
							$cw[] = ($chr + 1);
							$cw_num += 2;
							$pos = $epos;
							$enc = ENC_ASCII;
							$this->last_enc = $enc;
						} elseif (($cwr == 2) AND ($p == 2)) {
							$c1 = array_shift($temp_cw);
							$c2 = array_shift($temp_cw);
							$p -= 2;
							$tmp = ((1600 * $c1) + (40 * $c2) + 1);
							$cw[] = ($tmp >> 8);
							$cw[] = ($tmp % 256);
							$cw_num += 2;
							$pos = $epos;
							$enc = ENC_ASCII;
							$this->last_enc = $enc;
						} else {
							if ($enc != ENC_ASCII) {
								$enc = ENC_ASCII;
								$this->last_enc = $enc;
								$cw[] = $this->getSwitchEncodingCodeword($enc);
								++$cw_num;
								$pos = ($epos - $p);
							}
						}
					}
					break;
				}
				case ENC_EDF: {
					$temp_cw = array();
					$epos = $pos;
					$field_length = 0;
					$newenc = $enc;
					do {
						$chr = ord($data[$epos]);
						if ($this->isCharMode($chr, ENC_EDF)) {
							++$epos;
							$temp_cw[] = $chr;
							++$field_length;
						}
						if (($field_length == 4) OR ($epos == $data_length) OR !$this->isCharMode($chr, ENC_EDF)) {
							if (($epos == $data_length) AND ($field_length < 3)) {
								$enc = ENC_ASCII;
								$cw[] = $this->getSwitchEncodingCodeword($enc);
								++$cw_num;
								break;
							}
							if ($field_length < 4) {
								$temp_cw[] = 0x1f;
								++$field_length;
								for ($i = $field_length; $i < 4; ++$i) {
									$temp_cw[] = 0;
								}
								$enc = ENC_ASCII;
								$this->last_enc = $enc;
							}
							$tcw = (($temp_cw[0] & 0x3F) << 2) + (($temp_cw[1] & 0x30) >> 4);
							if ($tcw > 0) {
								$cw[] = $tcw;
								$cw_num++;
							}
							$tcw= (($temp_cw[1] & 0x0F) << 4) + (($temp_cw[2] & 0x3C) >> 2);
							if ($tcw > 0) {
								$cw[] = $tcw;
								$cw_num++;
							}
							$tcw = (($temp_cw[2] & 0x03) << 6) + ($temp_cw[3] & 0x3F);
							if ($tcw > 0) {
								$cw[] = $tcw;
								$cw_num++;
							}
							$temp_cw = array();
							$pos = $epos;
							$field_length = 0;
							if ($enc == ENC_ASCII) {
								break;
							}
						}
					} while ($epos < $data_length);
					break;
				}
				case ENC_BASE256: {
					$temp_cw = array();
					$field_length = 0;
					while (($pos < $data_length) AND ($field_length <= 1555)) {
						$newenc = $this->lookAheadTest($data, $pos, $enc);
						if ($newenc != $enc) {
							$enc = $newenc;
							break;
						} else {
							$chr = ord($data[$pos]);
							++$pos;
							$temp_cw[] = $chr;
							++$field_length;
						}
					}
					if ($field_length <= 249) {
						$cw[] = $this->get255StateCodeword($field_length, ($cw_num + 1));
						++$cw_num;
					} else {
						$cw[] = $this->get255StateCodeword((floor($field_length / 250) + 249), ($cw_num + 1));
						$cw[] = $this->get255StateCodeword(($field_length % 250), ($cw_num + 2));
						$cw_num += 2;
					}
					if (!empty($temp_cw)) {
						foreach ($temp_cw as $p => $cht) {
							$cw[] = $this->get255StateCodeword($cht, ($cw_num + $p + 1));
						}
					}
					break;
				}
			}
		}
		return $cw;
	}
	protected function placeModule($marr, $nrow, $ncol, $row, $col, $chr, $bit) {
		if ($row < 0) {
			$row += $nrow;
			$col += (4 - (($nrow + 4) % 8));
		}
		if ($col < 0) {
			$col += $ncol;
			$row += (4 - (($ncol + 4) % 8));
		}
		$marr[(($row * $ncol) + $col)] = ((10 * $chr) + $bit);
		return $marr;
	}
	protected function placeUtah($marr, $nrow, $ncol, $row, $col, $chr) {
		$marr = $this->placeModule($marr, $nrow, $ncol, $row-2, $col-2, $chr, 1);
		$marr = $this->placeModule($marr, $nrow, $ncol, $row-2, $col-1, $chr, 2);
		$marr = $this->placeModule($marr, $nrow, $ncol, $row-1, $col-2, $chr, 3);
		$marr = $this->placeModule($marr, $nrow, $ncol, $row-1, $col-1, $chr, 4);
		$marr = $this->placeModule($marr, $nrow, $ncol, $row-1, $col,   $chr, 5);
		$marr = $this->placeModule($marr, $nrow, $ncol, $row,   $col-2, $chr, 6);
		$marr = $this->placeModule($marr, $nrow, $ncol, $row,   $col-1, $chr, 7);
		$marr = $this->placeModule($marr, $nrow, $ncol, $row,   $col,   $chr, 8);
		return $marr;
	}
	protected function placeCornerA($marr, $nrow, $ncol, $chr) {
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-1, 0,       $chr, 1);
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-1, 1,       $chr, 2);
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-1, 2,       $chr, 3);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-2, $chr, 4);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-1, $chr, 5);
		$marr = $this->placeModule($marr, $nrow, $ncol, 1,       $ncol-1, $chr, 6);
		$marr = $this->placeModule($marr, $nrow, $ncol, 2,       $ncol-1, $chr, 7);
		$marr = $this->placeModule($marr, $nrow, $ncol, 3,       $ncol-1, $chr, 8);
		return $marr;
	}
	protected function placeCornerB($marr, $nrow, $ncol, $chr) {
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-3, 0,       $chr, 1);
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-2, 0,       $chr, 2);
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-1, 0,       $chr, 3);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-4, $chr, 4);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-3, $chr, 5);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-2, $chr, 6);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-1, $chr, 7);
		$marr = $this->placeModule($marr, $nrow, $ncol, 1,       $ncol-1, $chr, 8);
		return $marr;
	}
	protected function placeCornerC($marr, $nrow, $ncol, $chr) {
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-3, 0,       $chr, 1);
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-2, 0,       $chr, 2);
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-1, 0,       $chr, 3);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-2, $chr, 4);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-1, $chr, 5);
		$marr = $this->placeModule($marr, $nrow, $ncol, 1,       $ncol-1, $chr, 6);
		$marr = $this->placeModule($marr, $nrow, $ncol, 2,       $ncol-1, $chr, 7);
		$marr = $this->placeModule($marr, $nrow, $ncol, 3,       $ncol-1, $chr, 8);
		return $marr;
	}
	protected function placeCornerD($marr, $nrow, $ncol, $chr) {
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-1, 0,       $chr, 1);
		$marr = $this->placeModule($marr, $nrow, $ncol, $nrow-1, $ncol-1, $chr, 2);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-3, $chr, 3);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-2, $chr, 4);
		$marr = $this->placeModule($marr, $nrow, $ncol, 0,       $ncol-1, $chr, 5);
		$marr = $this->placeModule($marr, $nrow, $ncol, 1,       $ncol-3, $chr, 6);
		$marr = $this->placeModule($marr, $nrow, $ncol, 1,       $ncol-2, $chr, 7);
		$marr = $this->placeModule($marr, $nrow, $ncol, 1,       $ncol-1, $chr, 8);
		return $marr;
	}
	protected function getPlacementMap($nrow, $ncol) {
		$marr = array_fill(0, ($nrow * $ncol), 0);
		$chr = 1;
		$row = 4;
		$col = 0;
		do {
			if (($row == $nrow) AND ($col == 0)) {
				$marr = $this->placeCornerA($marr, $nrow, $ncol, $chr);
				++$chr;
			}
			if (($row == ($nrow - 2)) AND ($col == 0) AND ($ncol % 4)) {
				$marr = $this->placeCornerB($marr, $nrow, $ncol, $chr);
				++$chr;
			}
			if (($row == ($nrow - 2)) AND ($col == 0) AND (($ncol % 8) == 4)) {
				$marr = $this->placeCornerC($marr, $nrow, $ncol, $chr);
				++$chr;
			}
			if (($row == ($nrow + 4)) AND ($col == 2) AND (!($ncol % 8))) {
				$marr = $this->placeCornerD($marr, $nrow, $ncol, $chr);
				++$chr;
			}
			do {
				if (($row < $nrow) AND ($col >= 0) AND (!$marr[(($row * $ncol) + $col)])) {
					$marr = $this->placeUtah($marr, $nrow, $ncol, $row, $col, $chr);
					++$chr;
				}
				$row -= 2;
				$col += 2;
			} while (($row >= 0) AND ($col < $ncol));
			++$row;
			$col += 3;
			do {
				if (($row >= 0) AND ($col < $ncol) AND (!$marr[(($row * $ncol) + $col)])) {
					$marr = $this->placeUtah($marr, $nrow, $ncol, $row, $col, $chr);
					++$chr;
				}
				$row += 2;
				$col -= 2;
			} while (($row < $nrow) AND ($col >= 0));
			$row += 3;
			++$col;
		} while (($row < $nrow) OR ($col < $ncol));
		if (!$marr[(($nrow * $ncol) - 1)]) {
			$marr[(($nrow * $ncol) - 1)] = 1;
			$marr[(($nrow * $ncol) - $ncol - 2)] = 1;
		}
		return $marr;
	}

}