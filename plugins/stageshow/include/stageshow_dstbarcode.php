<?php
/* 
Description: 
 
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

include 'stageshowlib_barcodebase.php';
	
if (!class_exists('DSTBarcode'))
{
	// Code39 Barcode defines
	// Note: The width ratio between BARCODE_THINWIDTH and BARCODE_THICKWIDTH can be chosen between 1:2 and 1:3.
	if (!defined('BARCODE_THINWIDTH'))
		define ('BARCODE_THINWIDTH',1);
		
	if (!defined('BARCODE_THICKWIDTH'))
		define ('BARCODE_THICKWIDTH',3);
		
	if (!defined('BARCODE_HEIGHT'))
		define ('BARCODE_HEIGHT',45);
		
	if (!defined('BARCODE_FONTSIZE'))
		define ('BARCODE_FONTSIZE',5);	

	if (!defined('BARCODE_STARTSTOPCHAR'))
		define ('BARCODE_STARTSTOPCHAR','*');

	class DSTBarcode // Define class
	{
		// Define Class variables (MJS)
		private $code_type, $isHorizontal, $size, $code_keys, $lastError;
		public $fontSize, $code39_thinwidth, $code39_widewidth, $padding;
		
		// Define Barcode Type Enumerations (MJS)
		const BARCODE_TYPE_CODE128 = 'code128';
		const BARCODE_TYPE_CODE39 = 'code39';
		const BARCODE_TYPE_CODE25 = 'code25';
		const BARCODE_TYPE_CODABAR = 'codabar';
		
		const BARCODE_HORIZONTAL = 'horizontal';
		const BARCODE_VERTICAL = 'vertical';
		
		function __construct($code_type = self::BARCODE_TYPE_CODE39, $orientation = self::BARCODE_HORIZONTAL, $size = 20, $mode = 'png')
		{
			// Initialise default values for Object (MJS))
			$this->fontSize = 12;
			$this->code39_thinwidth = '1';
			$this->code39_widewidth = '2';

			$this->padding = 10;	// Padding - Number of widths of thin stripes either side
			
			$this->mode = $mode;
			$this->code_type = StageShowLibMigratePHPClass::Safe_strtolower($code_type);
			$this->lastError = "";
			
			$this->size = $size;	
			
			$orientation = StageShowLibMigratePHPClass::Safe_strtolower($orientation);
			switch ($orientation)
			{
				case self::BARCODE_HORIZONTAL:
					$this->isHorizontal = true;
					break;
					
				case self::BARCODE_VERTICAL:
					$this->isHorizontal = false;
					break;
					
				default:
					$this->lastError = "Invalid Orientation";
					$this->code_type = '';
					return;
			}
			
			// Code Table Initialisation from original code moved to constructor (MJS))
			switch ($this->code_type)
			{			
				// Translate the $text into barcode the correct $this->code_type
				case self::BARCODE_TYPE_CODE128:
				{
					// Must not change order of array elements as the checksum depends on the array's key to validate final code
					$this->code_array  = array(
						" " => "212222",
						"!" => "222122",
						'"' => "222221",
						"#" => "121223",
						"$" => "121322",
						"%" => "131222",
						"&" => "122213",
						"'" => "122312",
						"(" => "132212",
						")" => "221213",
						"*" => "221312",
						"+" => "231212",
						"," => "112232",
						"-" => "122132",
						"." => "122231",
						"/" => "113222",
						"0" => "123122",
						"1" => "123221",
						"2" => "223211",
						"3" => "221132",
						"4" => "221231",
						"5" => "213212",
						"6" => "223112",
						"7" => "312131",
						"8" => "311222",
						"9" => "321122",
						":" => "321221",
						";" => "312212",
						"<" => "322112",
						"=" => "322211",
						">" => "212123",
						"?" => "212321",
						"@" => "232121",
						"A" => "111323",
						"B" => "131123",
						"C" => "131321",
						"D" => "112313",
						"E" => "132113",
						"F" => "132311",
						"G" => "211313",
						"H" => "231113",
						"I" => "231311",
						"J" => "112133",
						"K" => "112331",
						"L" => "132131",
						"M" => "113123",
						"N" => "113321",
						"O" => "133121",
						"P" => "313121",
						"Q" => "211331",
						"R" => "231131",
						"S" => "213113",
						"T" => "213311",
						"U" => "213131",
						"V" => "311123",
						"W" => "311321",
						"X" => "331121",
						"Y" => "312113",
						"Z" => "312311",
						"[" => "332111",
						"\\" => "314111",
						"]" => "221411",
						"^" => "431111",
						"_" => "111224",
						"\`" => "111422",
						"a" => "121124",
						"b" => "121421",
						"c" => "141122",
						"d" => "141221",
						"e" => "112214",
						"f" => "112412",
						"g" => "122114",
						"h" => "122411",
						"i" => "142112",
						"j" => "142211",
						"k" => "241211",
						"l" => "221114",
						"m" => "413111",
						"n" => "241112",
						"o" => "134111",
						"p" => "111242",
						"q" => "121142",
						"r" => "121241",
						"s" => "114212",
						"t" => "124112",
						"u" => "124211",
						"v" => "411212",
						"w" => "421112",
						"x" => "421211",
						"y" => "212141",
						"z" => "214121",
						"{" => "412121",
						"|" => "111143",
						"}" => "111341",
						"~" => "131141",
						"DEL" => "114113",
						"FNC 3" => "114311",
						"FNC 2" => "411113",
						"SHIFT" => "411311",
						"CODE C" => "113141",
						"FNC 4" => "114131",
						"CODE A" => "311141",
						"FNC 1" => "411131",
						"Start A" => "211412",
						"Start B" => "211214",
						"Start C" => "211232",
						"Stop" => "2331112"
					);
					$this->code_keys   = array_keys($this->code_array);
					$this->code_values = array_flip($this->code_keys);
					break;
				}
				case self::BARCODE_TYPE_CODE39:
				{
					$this->code_array = array(
						"0" => "111221211",
						"1" => "211211112",
						"2" => "112211112",
						"3" => "212211111",
						"4" => "111221112",
						"5" => "211221111",
						"6" => "112221111",
						"7" => "111211212",
						"8" => "211211211",
						"9" => "112211211",
						"A" => "211112112",
						"B" => "112112112",
						"C" => "212112111",
						"D" => "111122112",
						"E" => "211122111",
						"F" => "112122111",
						"G" => "111112212",
						"H" => "211112211",
						"I" => "112112211",
						"J" => "111122211",
						"K" => "211111122",
						"L" => "112111122",
						"M" => "212111121",
						"N" => "111121122",
						"O" => "211121121",
						"P" => "112121121",
						"Q" => "111111222",
						"R" => "211111221",
						"S" => "112111221",
						"T" => "111121221",
						"U" => "221111112",
						"V" => "122111112",
						"W" => "222111111",
						"X" => "121121112",
						"Y" => "221121111",
						"Z" => "122121111",
						"-" => "121111212",
						"." => "221111211",
						" " => "122111211",
						"$" => "121212111",
						"/" => "121211121",
						"+" => "121112121",
						"%" => "111212121",
						"*" => "121121211"
					);
					break;
				}
				case self::BARCODE_TYPE_CODE25:
				{
					$this->code_array1 = array(
						"1",
						"2",
						"3",
						"4",
						"5",
						"6",
						"7",
						"8",
						"9",
						"0"
					);
					$this->code_array2 = array(
						"3-1-1-1-3",
						"1-3-1-1-3",
						"3-3-1-1-1",
						"1-1-3-1-3",
						"3-1-3-1-1",
						"1-3-3-1-1",
						"1-1-1-3-3",
						"3-1-1-3-1",
						"1-3-1-3-1",
						"1-1-3-3-1"
					);
					break;
				}
				case self::BARCODE_TYPE_CODABAR:
				{
					$this->code_array1 = array(
						"1",
						"2",
						"3",
						"4",
						"5",
						"6",
						"7",
						"8",
						"9",
						"0",
						"-",
						"$",
						":",
						"/",
						".",
						"+",
						"A",
						"B",
						"C",
						"D"
					);
					$this->code_array2 = array(
						"1111221",
						"1112112",
						"2211111",
						"1121121",
						"2111121",
						"1211112",
						"1211211",
						"1221111",
						"2112111",
						"1111122",
						"1112211",
						"1122111",
						"2111212",
						"2121112",
						"2121211",
						"1121212",
						"1122121",
						"1212112",
						"1112122",
						"1112221"
					);
					break;
				}
				default:
				{
					$this->lastError = "Invalid Barcode Type ($code_type)";
					$this->code_type = '';
					break;
				}
			} //$this->code_type
		}
		
		public function GetLastError()
		{
			return $this->lastError;
		}
		
		protected $code_array;
		
		public function GetPermittedChars()
		{
			$testText = '';
			foreach ($this->code_array as $nextChar => $bitmap)
			{
				if ($nextChar === BARCODE_STARTSTOPCHAR)
					continue;
				$testText .= $nextChar;
			}
			
			return $testText;
		}
		
		public function createImage($text='', $showText=true)
		{
			if (!$this->isHorizontal) $showText = false;
			
			if ($showText)
				$pxHt = imagefontheight($this->fontSize) + 2;
			else
				$pxHt = 0;
			
			$code_string = "";
			
			// Translate the $text into barcode: Barcode type is in $this->code_type
			switch ($this->code_type)
			{				
				case self::BARCODE_TYPE_CODE128:
				{
					$chksum      = 104;
					for ($X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($text); $X++)
					{
						$activeKey = StageShowLibMigratePHPClass::Safe_substr($text, ($X - 1), 1);
						$code_string .= $this->code_array[$activeKey];
						$chksum = ($chksum + ($this->code_values[$activeKey] * $X));
					} //$X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($text); $X++
					$code_string .= $this->code_array[$this->code_keys[($chksum - (intval($chksum / 103) * 103))]];
					$code_string = "211214" . $code_string . "2331112";
					break;
				}
				case self::BARCODE_TYPE_CODE39:
				{
					// Convert to uppercase
					$upper_text = StageShowLibMigratePHPClass::Safe_strtoupper($text);
					for ($X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($upper_text); $X++)
					{
						$code_string .= $this->code_array[StageShowLibMigratePHPClass::Safe_substr($upper_text, ($X - 1), 1)] . "1";
					}
					$code_string = "1211212111" . $code_string . "121121211";

					$code_string = StageShowLibMigratePHPClass::Safe_str_replace('1', 'T', $code_string);
					$code_string = StageShowLibMigratePHPClass::Safe_str_replace('2', $this->code39_widewidth, $code_string);
					$code_string = StageShowLibMigratePHPClass::Safe_str_replace('T', $this->code39_thinwidth, $code_string);
					break;
				}
				case self::BARCODE_TYPE_CODE25:
				{
					
					for ($X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($text); $X++)
					{
						for ($Y = 0; $Y < count($this->code_array1); $Y++)
						{
							if (StageShowLibMigratePHPClass::Safe_substr($text, ($X - 1), 1) == $this->code_array1[$Y])
								$temp[$X] = $this->code_array2[$Y];
						} //$Y = 0; $Y < count($this->code_array1); $Y++
					} //$X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($text); $X++
					for ($X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($text); $X += 2)
					{
						if (isset($temp[$X]) && isset($temp[($X + 1)]))
						{
							$temp1 = explode("-", $temp[$X]);
							$temp2 = explode("-", $temp[($X + 1)]);
							for ($Y = 0; $Y < count($temp1); $Y++)
								$code_string .= $temp1[$Y] . $temp2[$Y];
						} //isset($temp[$X]) && isset($temp[($X + 1)])
					} //$X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($text); $X += 2
					$code_string = "1111" . $code_string . "311";
					break;
				}
				case self::BARCODE_TYPE_CODABAR:
				{
					// Convert to uppercase
					$upper_text  = StageShowLibMigratePHPClass::Safe_strtoupper($text);
					for ($X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($upper_text); $X++)
					{
						for ($Y = 0; $Y < count($this->code_array1); $Y++)
						{
							if (StageShowLibMigratePHPClass::Safe_substr($upper_text, ($X - 1), 1) == $this->code_array1[$Y])
								$code_string .= $this->code_array2[$Y] . "1";
						} //$Y = 0; $Y < count($this->code_array1); $Y++
					} //$X = 1; $X <= StageShowLibMigratePHPClass::Safe_strlen($upper_text); $X++
					$code_string = "11221211" . $code_string . "1122121";
					break;
				}
				default:
				{
					return '';			
				}
			}
			
			// Pad the edges of the barcode
			$code_length = $this->padding * 2;
			for ($i = 1; $i <= StageShowLibMigratePHPClass::Safe_strlen($code_string); $i++)
				$code_length = $code_length + (integer) (StageShowLibMigratePHPClass::Safe_substr($code_string, ($i - 1), 1));
			if ($this->isHorizontal)
			{
				$img_width  = $code_length;
				$img_height = $this->size;
			}
			else
			{
				$img_width  = $this->size;
				$img_height = $code_length;
			}
			$image = imagecreate($img_width, $img_height + $pxHt);
			$black = imagecolorallocate($image, 0, 0, 0);
			$white = imagecolorallocate($image, 255, 255, 255);
			imagefill($image, 0, 0, $white);
			
			$location = $this->padding;
			for ($position = 1; $position <= StageShowLibMigratePHPClass::Safe_strlen($code_string); $position++)
			{
				$cur_size = $location + (StageShowLibMigratePHPClass::Safe_substr($code_string, ($position - 1), 1));
				if ($this->isHorizontal)
					imagefilledrectangle($image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black));
				else
					imagefilledrectangle($image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black));
				$location = $cur_size;
			}

			if ($showText)
			{
				$pxWid = imagefontwidth($this->fontSize) * StageShowLibMigratePHPClass::Safe_strlen($text) + 10;
				$bigCenter = $img_width / 2;
				$textCenter = $pxWid / 2;
				imagestring($image, $this->fontSize, ($bigCenter - $textCenter) + 5, $img_height + 1, $text, $black);
			}
			
			if ($this->mode == 'img') return $image;
/*	
			// Draw barcode to the screen
			header('Content-type: image/png');
*/
			
			// Image origianally output to screen is now captured and returned (MJS)
			ob_start();		
			switch($this->mode)
			{
				case 'gif': $image_value = imagegif($image);
				case 'png': $image_value = imagepng($image);
				case 'jpeg': $image_value = imagejpeg($image);
				case 'wbmp': $image_value = imagewbmp($image);
			}
			$bin = ob_get_contents();
			ob_end_clean();
			
			imagedestroy($image);
			
			return $bin;
		}
	}
} //!class_exists('DSTBarcode')

?>