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
	
include 'stageshow_dstbarcode.php';

include 'stageshow_qrbarcode.php';

if (!class_exists('StageShowBarcodeClass'))
{
	class StageShowBarcodeClass extends BarcodeBase // Define class
	{
		function __construct($code_type = DSTBarcode::BARCODE_TYPE_CODE39, $orientation = DSTBarcode::BARCODE_HORIZONTAL, $size = 20, $mode = 'png')
		{
			$this->code_type = $code_type;
			$this->orientation = $orientation;
			$this->size = $size;
			$this->mode = $mode;			
		}
		
		function createImage($text='', $showText=true)
		{
			$code_type = $this->code_type;
			$orientation = $this->orientation;
			$size = $this->size;
			$mode = $this->mode;
			
			switch ($code_type)
			{
				case StageShowLibQRCodeClass::BARCODE_TYPE_QRCODE:
					$text .= " ";
					$barcodeObj = new StageShowLibQRCodeClass($code_type, $orientation, $size, $mode);
					return $barcodeObj->CreateBarcodeImage($text);
					break;

				case DSTBarcode::BARCODE_TYPE_CODE128:
				case DSTBarcode::BARCODE_TYPE_CODE39:
					$size = BARCODE_HEIGHT;
					
					$barcodeObj = new DSTBarcode($code_type, $orientation, $size, $mode);
					
					$barcodeObj->code39_thinwidth = ''.BARCODE_THINWIDTH;
					$barcodeObj->code39_widewidth = ''.BARCODE_THICKWIDTH;

					$barcodeObj->padding = 0;

					$barcodeObj->fontSize = BARCODE_FONTSIZE;
					return $barcodeObj->createImage($text, $showText);
					break;
			}
			
			return null;
		}
		
		static function GetBarcodeTypesArray()
		{
			return array(
				DSTBarcode::BARCODE_TYPE_CODE128 => 'Code 128',
				DSTBarcode::BARCODE_TYPE_CODE39  => 'Code 39',
/*				
				DSTBarcode::BARCODE_TYPE_CODE25  => 'Code 25',
				DSTBarcode::BARCODE_TYPE_CODABAR => 'Codabar',
*/
				StageShowLibQRCodeClass::BARCODE_TYPE_QRCODE => 'QR Code',
			);
		}
		
		// Commented out Class Def (StageShowBarcodeClass)
		
	}
}

