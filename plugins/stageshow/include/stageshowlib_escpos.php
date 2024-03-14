<?php
/* 
Description: Code for Ticket Printing with Esc/Pos Commands
 
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

/*
Referencies:

http://www.libpng.org/pub/png/spec/1.2/PNG-DataRep.html#DR.Image-layout
https://en.wikipedia.org/wiki/Portable_Network_Graphics#cite_note-10
*/

if (!class_exists('StageShowLibEscPosClass')) 
{
	if (!defined('TICKETPRINT_TEMPLATESFOLDER'))
		define('TICKETPRINT_TEMPLATESFOLDER', STAGESHOWLIB_UPLOADS_PATH.'/tickets');
	
	if (!defined('TICKETPRINT_TICKETBORDER'))
		define('TICKETPRINT_TICKETBORDER', 2);
	
	define('ESCPOS_LOGTIME', true);
	
	class StageShowLibEscPosClass // extends TBD // Define class
	{
		const LF = 0x0a;
		const CR = 0x0d;
		const ESC = 0x1b;
		const GS = 0x1d;

		const RASTERMODE_NORMAL = 0;
		const RASTERMODE_DBLWIDTH = 1;
		const RASTERMODE_DBLHEIGHT = 2;
		const RASTERMODE_QUAD = 3;
		
		var $barCodeType;	
		var $barCodeClass;
		
		var $imgCount = 0;
			
		function __construct($myDBaseObj) //constructor	StageShowLibEscPosClass
		{
			$this->myDBaseObj = $myDBaseObj;
			
			$this->barCodeType = $myDBaseObj->adminOptions['BarcodeType'];	
			$this->barCodeClass = $myDBaseObj->GetBarcodeClass();
			
			$this->DebugLevel = StageShowLibUtilsClass::IsElementSet('post', 'DebugLevel') ? (int)$_POST['DebugLevel'] : 0;
			//parent::__construct($env);
			
		}
		
		static function imageCreateFromAny($filepath) 
		{
		    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
		    $allowedTypes = array(
		        1,  // [] gif
		        2,  // [] jpg
		        3,  // [] png
		        6   // [] bmp
		    );
		    
		    if (!in_array($type, $allowedTypes)) 
		    {
		        return false;
		    }
		    
		    switch ($type) 
		    {
		        case 1 :
		            $im = imageCreateFromGif($filepath);
		        	break;
		        	
		        case 2 :
		            $im = imageCreateFromJpeg($filepath);
		        	break;
		        	
		        case 3 :
		            $im = imageCreateFromPng($filepath);
		        	break;
		        	
		        case 6 :
		            $im = imageCreateFromBmp($filepath);
		        	break;
		    } 
		      
		    return $im; 
		} 
		
		static function ImageToHTML($im, $alt='', $format='png')
		{
			if ($im == null) return '';
			
			ob_start();	
			switch ($format)
			{
				case 'png': imagepng($im, NULL, 0, PNG_NO_FILTER); break;
				default: break;
			}
			$bin = ob_get_contents();
			ob_end_clean();
			return self::PNGDataToHTML($bin);
		}
		
		static function PNGDataToHTML($bin, $alt='', $format='png')
		{
			$imageBase64 = chunk_split(base64_encode($bin));
			
			$imageSrc  = '<img alt="'.$alt.'" src="data:image/'.$format.';base64,';
			$imageSrc .= $imageBase64;
			$imageSrc .= '">';
			
			return $imageSrc;
		}
		
		function GetEscPosTemplate($path, $ticketRot)
		{
			$this->escPosData = file_get_contents($path);
			
			$index = 0;
			for ($index = 0; $index<10; $index++)
			{
				if ($this->escPosData[$index] == chr(self::GS))
				{
					$index += 4;
					$this->maxCol = ord($this->escPosData[$index]) + (ord($this->escPosData[$index+1]) << 8);
					$this->maxCol *= 8;
					$index += 2;
					$this->maxRow = ord($this->escPosData[$index]) + (ord($this->escPosData[$index+1]) << 8);
					$index += 2;
					$this->dataOfst = $index;			
if ($this->DebugLevel >= 1)
{
	StageShowLibEscapingClass::Safe_EchoHTML("escPosData Loaded: <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("filepath=$path <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("maxCol=".$this->maxCol." <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("maxRow=".$this->maxRow." <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("dataOfst=".$this->dataOfst." <br>\n");
}
				}
			}
			$this->dataRot = $ticketRot;
			
			return true;
		}
		
		function SaveEscPosTemplate($path)
		{
			file_put_contents($path, $this->escPosData);
		}
		
		static function EscPosToImage($escPosData)
		{
			$DebugLevel = StageShowLibUtilsClass::IsElementSet('post', 'DebugLevel') ? (int)$_POST['DebugLevel'] : 0;
			
			$escPosDatalen = StageShowLibMigratePHPClass::Safe_strlen($escPosData);
			if ($escPosDatalen < 10)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error: Cannot convert escPosData to Image - Header Missing <br>\n");
				return null;
			}
			if ($DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML(("escPosDatalen: $escPosDatalen <br>\n"));
			
			$startIndex = -1;
			for ($index = 0; $index<10; $index++)
			{
				if ($escPosData[$index] == chr(self::GS))
				{
					$startIndex = $index;
					break;
				}
			}
			
			if ($startIndex < 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error: Cannot convert escPosData to Image - Could not parse data <br>\n");
				return null;
			}
			
			$index = $startIndex + 4;
			$maxCol = ord($escPosData[$index]) + (ord($escPosData[$index+1]) << 8);
			$maxCol *= 8;
			$index += 2;
			$maxRow = ord($escPosData[$index]) + (ord($escPosData[$index+1]) << 8);
			$index += 2;
			$dataOfst = $index;			
//StageShowLibEscapingClass::Safe_EchoHTML("escPosData Parsed: <br>\n");
if ($DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Width=".$maxCol." pixels<br>\n");
if ($DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Height=".$maxRow." pixels<br>\n");
//StageShowLibEscapingClass::Safe_EchoHTML("Data Length=".$escPosDatalen." <br>\n");
//StageShowLibEscapingClass::Safe_EchoHTML("dataOfst=".$dataOfst." <br>\n");

			$escImg = imagecreatetruecolor($maxCol, $maxRow);
			imagetruecolortopalette($escImg, false, 2);
			$white = imagecolorallocate($escImg, 255, 255, 255);
			$black = imagecolorallocate($escImg, 0, 0, 0);
			imagefill($escImg, 0, 0, $white);

			// Loop around rows ...
			for ($ofstRow=0; $ofstRow < $maxRow; $ofstRow++)
			{
				// Loop around columns ...
				for ($ofstCol = 0; $ofstCol < $maxCol;)
				{
if ($index >= $escPosDatalen)
{
	StageShowLibEscapingClass::Safe_EchoHTML(("<br><strong>Index is out of Range </strong><br>\n"));
	StageShowLibEscapingClass::Safe_EchoHTML(("ofstRow: $ofstRow <br>\n"));
	StageShowLibEscapingClass::Safe_EchoHTML(("ofstCol: $ofstCol <br>\n"));
	return null;
}
					
					$nextByte = ord($escPosData[$index]);
					$index++;
					if ($nextByte == 0)
					{
						$ofstCol += 8;
						continue;
					}
					
					for ($mask = 0x80, $bitNo = 7; $bitNo >= 0; $bitNo--, $ofstCol++)
					{
						$mask = 1 << $bitNo;
						if (($nextByte & $mask) != 0)
						{
							// Set pixel
							imagesetpixel($escImg, $ofstCol, $ofstRow, $black);
						}
					}
				}				
			}
			
			return $escImg;
		}
		
		static function ShowEscPosImage($escPosData)
		{
			StageShowLibEscapingClass::Safe_EchoHTML("Decoded escPos Image: <br>\n");
			$escImg = StageShowLibEscPosClass::EscPosToImage($escPosData);
			StageShowLibEscapingClass::Safe_EchoHTML("<br>\n");
			StageShowLibEscapingClass::Safe_EchoHTML(StageShowLibEscPosClass::ImageToHTML($escImg));
			StageShowLibEscapingClass::Safe_EchoHTML("<br><br>\n");
		}
				
		function GetImageLimits($im, $rot)
		{
			$this->imgWidth = imagesx($im);
			$this->imgHeight = imagesy($im);
								
			switch (intval($rot))
			{
				case 0: 					
				case 180: 
					$this->maxCol = (int)(8*ceil(imagesx($im)/8));
					$this->maxRow = imagesy($im);
					break;
					
				case 90: 
				case 270: 
					$this->maxCol = (int)(8*ceil(imagesy($im)/8));
					$this->maxRow = imagesx($im);
					break;

				default: 
StageShowLibEscapingClass::Safe_EchoHTML("<br><strong>Rotation not supported ($rot) </strong> @ Line:".__LINE__."<br><br>\n");
					return false;
			}

			return true;
		}
		
		function ParseXML($saleRecord, $template)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$perfDetail = $myDBaseObj->GetPerformanceDetails($saleRecord->perfID);
			
			$xmlPath = TICKETPRINT_TEMPLATESFOLDER.'/'.$template;
			if (($template == '') || !file_exists($xmlPath))
			{
				return null;
			}

			$xml = simplexml_load_file($xmlPath);
			$xmlObj = new stdClass();
			$xmlObj->XmlFile = $template;
			$xmlObj->XmlPath = $xmlPath;
			$xmlObj->XmlTS = filemtime($xmlPath);
			$xmlObj->LoadTS = time();
			
			$xmlObj->TicketPath = TICKETPRINT_TEMPLATESFOLDER.'/ticket-'.$saleRecord->perfID.'.escpos';
			
			if (isset($xml->Image))
			{
				// Load the image
				$backgroundPath = TICKETPRINT_TEMPLATESFOLDER.'/'.$xml->Image->File;
				if (!file_exists($backgroundPath))
				{
					return null;
				}
				$xmlObj->BkgrndType = 'Image';
				$xmlObj->BkgrndPath = $backgroundPath;
				$xmlObj->BkgrndTS = filemtime($backgroundPath);
			}
			else if (isset($xml->Width) && isset($xml->Height))
			{
				$xmlObj->BkgrndType = 'Blank';
				$xmlObj->BkgrndWidth = $xml->Width;
				$xmlObj->BkgrndHeight = $xml->Height;
				$width = (int)$xml->Width;					
				$height = (int)$xml->Height;
			}			
			else
			{
				return null;
			}
			
			$xmlObj->Rotation = isset($xml->Rotation) ? (int)$xml->Rotation : 0;
			
			$xmlObj->Items = array();
			if (isset($xml->Text))
			{
				foreach ($xml->Text as $xmlText)
				{
					$element = new stdClass();
					$element->Type = 'Text';
					
					$element->Spec = (string)$xmlText->Content;
					$element->Content = $myDBaseObj->AddEventToTemplate($element->Spec, $perfDetail);
					$element->HasDBContent = (StageShowLibMigratePHPClass::Safe_strpos($element->Content, '[')===false) ? false : true;
					
					$element->X = (int)$xmlText->Posn['X'];					
					$element->Y = (int)$xmlText->Posn['Y'];	
					
					$element->Rotation = isset($xmlText->Rotation) ? (int)$xmlText->Rotation : 0;							
					$element->FontSize = isset($xmlText->Font['Size']) ? (int)$xmlText->Font['Size'] : 10;	
					$element->FontFile = isset($xmlText->Font['File']) ? (string)$xmlText->Font['File'] : 'arial.ttf';	
					$element->Align    = isset($xmlText->Posn['Align']) ? StageShowLibMigratePHPClass::Safe_strtolower($xmlText->Posn['Align']) : '';
					
					$element->BorderUp    = isset($xmlText->Border['Up'])    ? (int)$xmlText->Border['Up'] : TICKETPRINT_TICKETBORDER;
					$element->BorderDown  = isset($xmlText->Border['Down'])  ? (int)$xmlText->Border['Down'] : TICKETPRINT_TICKETBORDER;
					$element->BorderLeft  = isset($xmlText->Border['Left'])  ? (int)$xmlText->Border['Left'] : TICKETPRINT_TICKETBORDER;
					$element->BorderRight = isset($xmlText->Border['Right']) ? (int)$xmlText->Border['Right'] : TICKETPRINT_TICKETBORDER;
					
					$xmlObj->Items[] = $element;
				}
			}
			
			if (isset($xml->Barcode))
			{
				foreach ($xml->Barcode as $xmlBarcode)
				{
					$element = new stdClass();
					$element->Type = 'Barcode';
					$element->HasDBContent = true;
					
					$element->X = (int)$xmlBarcode->Posn['X'];					
					$element->Y = (int)$xmlBarcode->Posn['Y'];	
					
					$element->Rotation = isset($xmlBarcode->Rotation) ? (int)$xmlBarcode->Rotation : 0;							
					//$element->FontSize = isset($xmlBarcode->Font['Size']) ? (int)$xmlBarcode->Font['Size'] : 10;	
					//$element->FontFile = isset($xmlBarcode->Font['File']) ? (string)$xmlBarcode->Font['File'] : 'arial.ttf';	
					
					$xmlObj->Items[] = $element;
				}
			}
			
			return $xmlObj;
		}
		
		function GetTicketTemplate($saleRecord)
		{
			if ($saleRecord->perfTicketTemplate != '')
			{
				$template = $saleRecord->perfTicketTemplate;
			}
			else if ($saleRecord->showTicketTemplate != '')
			{
				$template = $saleRecord->showTicketTemplate;
			}
			else
			{
				$template = $this->myDBaseObj->getOption('TicketTemplatePath');
			}
			
			return $template;
		}
		
		function GetTicketImage($saleRecord, $template, $toEscPos=true)
		{
			if ($template == '')
			{
				$template = $this->GetTicketTemplate($saleRecord);
			}

			$myDBaseObj = $this->myDBaseObj;
			
			if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('GetTicketImage-Entry');

			// Set the enviroment variable for GD
			putenv('GDFONTPATH=' . STAGESHOWLIB_UPLOADS_PATH.'/fonts');

			$xmlObj = null;
			if (!StageShowLibUtilsClass::IsElementSet('post', 'flushCache') && $toEscPos)
				$xmlObj = $myDBaseObj->GetTicketSpec($saleRecord->perfID);
				
			if ($xmlObj != null)
			{
				if ($xmlObj->XmlFile != $template)
				{
					// XML File has changed
					$xmlObj = null;
				}
				else if ($xmlObj->XmlTS != filemtime($xmlObj->XmlPath))
				{
					// XML File has been modified
					$xmlObj = null;
				}
				else if (($xmlObj->BkgrndType == 'Image') && ($xmlObj->BkgrndTS != filemtime($xmlObj->BkgrndPath)))
				{
					// Background image has been modified
if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Background image File has been modified <br>\n");					
					$xmlObj = null;
				}
			}
			
			if (($xmlObj == null) || !$toEscPos || !file_exists($xmlObj->TicketPath))
			{
				$xmlObj = $this->ParseXML($saleRecord, $template);
				if ($xmlObj == null) return false;
			
				if ($xmlObj->BkgrndType == 'Image')
				{
					// Create image fron background image
					$this->ticketImg = self::imageCreateFromAny($xmlObj->BkgrndPath);
					imagetruecolortopalette($this->ticketImg, false, 2);
					$xmlObj->BkgrndWidth = imagesx($this->ticketImg);
					$xmlObj->BkgrndHeight = imagesy($this->ticketImg);
					if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('imageCreateFromAny');
				}
				else if ($xmlObj->BkgrndType == 'Blank')
				{
					$width = $xmlObj->BkgrndWidth;					
					$height = $xmlObj->BkgrndHeight;

					// Create image (fixed size)
					$this->ticketImg = imagecreatetruecolor($width, $height);
					imagetruecolortopalette($this->ticketImg, false, 2);
					$white = imagecolorallocate($this->ticketImg, 255, 255, 255);
					imagefill($this->ticketImg, 0, 0, $white);
					if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('empty image');
				}			
				else
				{
					return false;
				}

				if ($toEscPos)
				{
					// Save the spec to the database
					$myDBaseObj->SetTicketSpec($saleRecord->perfID, $xmlObj);
				
					if (!isset($this->maxRow))
					{
						$this->GetImageLimits($this->ticketImg, $xmlObj->Rotation);				
					}
					
					$this->EncodeBitmap($this->ticketImg, $xmlObj->Rotation);
					
					$this->AddItemsToTicket($xmlObj, $saleRecord, $toEscPos, false);
					
					$this->SaveEscPosTemplate($xmlObj->TicketPath);

					if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('SaveEscPosTemplate');					
				}
				else
				{
					$this->AddItemsToTicket($xmlObj, $saleRecord, $toEscPos, false);					
				}
			}
			else
			{
				// Load saved template
				$labelPath = $xmlObj->BkgrndPath;
				if (!$this->GetEscPosTemplate($xmlObj->TicketPath, $xmlObj->Rotation))
				{
					return false;
				}
				
				$this->imgWidth = $xmlObj->BkgrndWidth;
				$this->imgHeight = $xmlObj->BkgrndHeight;
			}

			$this->AddItemsToTicket($xmlObj, $saleRecord, $toEscPos, true);

			if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('Add Barcode');
			
			if (!$toEscPos) return $this->ticketImg;
		}
			
		function AddItemsToTicket($xmlObj, $saleRecord, $toEscPos=true, $dbItems=true)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			// Add database fields to the image ... if specified
			foreach ($xmlObj->Items as $xmlItem)
			{
				if ($xmlItem->HasDBContent != $dbItems) 
				{
					continue;
				}
				
				$x = $xmlItem->X;				
				$y = $xmlItem->Y;
			
				if ($xmlItem->Type == 'Text')
				{
					$contentField = $xmlItem->Content;
				
					$fontSize = $xmlItem->FontSize;
					$fontFile = $xmlItem->FontFile;
			
					$contentField = $this->myDBaseObj->AddEventToTemplate($contentField, $saleRecord);

					// Get the bounding box with zero rotation
					$bbox = imagettfbbox($fontSize, 0, $fontFile, $contentField);
					$xMin = $bbox[6];
					$yMin = $bbox[7];
					$xMax = $bbox[2];
					$yMax = $bbox[3];
					
					// Convert the results to overall text size
					$textWidth = abs($xMax - $xMin);
					$textHeight = abs($yMax - $yMin);

					switch ($xmlItem->Rotation)
					{
						case 0:
							$imgWidth = $textWidth;
							$imgHeight = $textHeight;
							
							$xOfst = $textWidth;
							$yOfst = 0;
														
							$imgX = $x + $xMin;
							$imgY = $y + $yMin;
							
							$textX = 0-$xMin;
							$textY = 0-$yMin;
							break;
							
						case 90:
							$imgWidth = $textHeight;
							$imgHeight = $textWidth;
							
							$xOfst = 0;
							$yOfst = $textWidth;
							
							$imgX = $x + $yMin;
							$imgY = $y - $xMin - $textWidth;
							
							$textX = $textHeight-$yMax;
							$textY = $textWidth-$xMin;
							break;
							
						case 180:
							$imgWidth = $textWidth;
							$imgHeight = $textHeight;
							break;
							
						case 270:
							$imgWidth = $textHeight;
							$imgHeight = $textWidth;
							
							$xOfst = 0;
							$yOfst = 0-$textWidth;
							
							$imgX = $x - $yMax;
							$imgY = $y - $xMin;
							
							$textX = $yMax;
							$textY = $xMin;
							break;
							
						default:
							// TODO - Deal with other rotations
							continue 2;							
							break;
					}
			
					$align = $xmlItem->Align;
					switch ($align)
					{
						case 'center':
						case 'centre':
							$imgX -= (int)($xOfst/2);
							$imgY += (int)($yOfst/2);
							break;
							
						case 'right':
							$imgX -= $xOfst;
							$imgY += $yOfst;
							break;
							
						default:
							break;
					}

					$imgWidth += ($xmlItem->BorderLeft + $xmlItem->BorderRight);
					$imgHeight += ($xmlItem->BorderUp + $xmlItem->BorderDown);
					$textX += $xmlItem->BorderLeft;
					$textY += $xmlItem->BorderUp;
					$imgX -= $xmlItem->BorderLeft;
					$imgY -= $xmlItem->BorderUp;						

					$textImg = imagecreatetruecolor($imgWidth, $imgHeight);
					imagetruecolortopalette($textImg, false, 2);
					$black = imagecolorallocate($textImg, 0, 0, 0);
					$white = imagecolorallocate($textImg, 255, 255, 255);
					imagefill($textImg, 0, 0, $white);
					imagettftext($textImg, $fontSize, $xmlItem->Rotation, $textX, $textY, $black, $fontFile, $contentField);
					if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('imagettftext-'.$xmlItem->Content);
					if ($toEscPos) 
					{
						if ($this->AddImage($textImg, $imgX, $imgY, $xmlObj->Rotation))
						{
if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Image ($contentField): <br>".StageShowLibEscPosClass::ImageToHTML($textImg)."<br>\n");
						}
					}
					else
						imagecopy($this->ticketImg, $textImg, $imgX, $imgY, 0, 0, $imgWidth, $imgHeight);
				}


				if ($xmlItem->Type == 'Barcode')
				{
					// Add a Barcode to the image
					$barcodeRot = $xmlItem->Rotation;
				
					$barcodeObj = new $this->barCodeClass($this->barCodeType, $barcodeRot, 20, 'img');
					$bin = $barcodeObj->CreateBarcodeImage($saleRecord->saleTxnId, true);
				
					if ($toEscPos) 
						$this->AddImage($bin, $x, $y, $barcodeRot+$xmlObj->Rotation);
					else
						imagecopy($this->ticketImg, $bin, $x, $y, 0, 0, imagesx($bin), imagesy($bin));
				}

			}
			if (!$toEscPos) return $this->ticketImg;
		}
		
		function EncodeBitmap($im, $rot)
		{
if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Encoding Image - Width:".imagesx($im)." Height:".imagesy($im)." <br>\n");
			if (!$this->GetImageLimits($im, $rot)) return null;
			
if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Number of rows: $this->maxRow<br>\n");

			$this->escPosData = '';
			
			// ESCP_Initialise
			$this->escPosData .= chr(self::ESC);
			$this->escPosData .= '@';
			
			if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('EncodeBitmap-Data Start');

			// ESCP_Image(Bitmap ourImage)
			$this->escPosData .= chr(self::GS);
			$this->escPosData .= 'v';
			$this->escPosData .= '0';
			
			// Raster Graphics Mode
			$this->escPosData .= chr(self::RASTERMODE_NORMAL);
			
			// The width in bytes ... 
			$byteWidth = intval(ceil($this->maxCol / 8));
			$this->escPosData .= chr(($byteWidth & 0xFF));
			$this->escPosData .= chr(($byteWidth >> 8));

			// The width in bits
			$bitsHeight = $this->maxRow;
			$this->escPosData .= chr(($bitsHeight & 0xFF));
			$this->escPosData .= chr(($bitsHeight >> 8));

			$this->dataOfst = StageShowLibMigratePHPClass::Safe_strlen($this->escPosData);
			$this->dataRot = $rot;

			// Pad out data with null entries
			$totalLength = $this->dataOfst + ($byteWidth*$bitsHeight);
			$this->escPosData = StageShowLibMigratePHPClass::Safe_str_pad($this->escPosData, $totalLength, chr(0));

			$this->AddImage($im, 0, 0, $rot, true);

			if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('EncodeBitmap-Data Start');
		}
		
	}
}


