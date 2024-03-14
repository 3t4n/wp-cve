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

include 'stageshowlib_escpos_image.php';

if (!class_exists('StageShowLibEscPosPNGClass')) 
{
	class StageShowLibEscPosPNGClass extends StageShowLibEscPosImageClass // Define class
	{				
		function AddImage($im, $xposn, $yposn, $rot, $useGD = false)
		{
			if (!isset($this->escPosData))
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error: Called AddImage with escPosData not defined <br>\n");
				return false;
			}
			
			if (!$useGD)
			{
				$imWidth = imagesx($im);
				$imHeight = imagesy($im);
				$pixels = $imWidth*$imHeight;
					
				$PNGImageSizeLimit = StageShowLibUtilsClass::GetHTTPInteger('post','PNGImageSizeLimit', 16000);
				$useGD = ($pixels >= $PNGImageSizeLimit);
if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Images Size: $pixels Pixels<br>\n");
			}
			
			if ($useGD)	// Use GD for images greater than 2k .... imagepng gave problems!
				return parent::AddImage($im, $xposn, $yposn, $rot);

if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Adding image with AddImage(PNG) - XPosn:$xposn YPosn:$yposn Width:$imWidth Height:$imHeight Rotation:$rot <br>\n");

			$this->escPosDataLen = StageShowLibMigratePHPClass::Safe_strlen($this->escPosData);
			$this->newBitmap = ($this->dataOfst == $this->escPosDataLen);
			
			// Get the pixels - PNG encoded image with no compression			
			ob_start();
			imagepng($im, NULL, 0, PNG_NO_FILTER);
			$pngLen = ob_get_length();
			$this->png = ob_get_contents();
			ob_end_clean();
			
			if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('imagepng');			
if ($this->DebugLevel >= 3)
{
	StageShowLibEscapingClass::Safe_EchoHTML("<br>pngLen: ".FileUtilsClass::ToHexAndDecimal($pngLen,8)." <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML(StageShowLibEscPosClass::PNGDataToHTML($this->png));
	StageShowLibEscapingClass::Safe_EchoHTML("<br>\n");
}
if ($this->DebugLevel >= 3)
{
	FileUtilsClass::DumpBytes($this->png, $pngLen);		
}
			$pngIndex = 0;
			$this->pngPixelIndex = 0;
			$pngIndex += 8;	// Skip Header
		
			while ($pngIndex < $pngLen)
			{
				$chunkLen  = (ord($this->png[$pngIndex++]) << 24);
				$chunkLen += (ord($this->png[$pngIndex++]) << 16);
				$chunkLen += (ord($this->png[$pngIndex++]) << 8);
				$chunkLen += (ord($this->png[$pngIndex++]));				
								
				$chunkType  = $this->png[$pngIndex++];				
				$chunkType .= $this->png[$pngIndex++];
				$chunkType .= $this->png[$pngIndex++];
				$chunkType .= $this->png[$pngIndex++];
				
if ($this->DebugLevel >= 2) 
{
	StageShowLibEscapingClass::Safe_EchoHTML("Type: $chunkType - ");	
	StageShowLibEscapingClass::Safe_EchoHTML("Length: ".FileUtilsClass::ToHexAndDecimal($chunkLen,8)."<br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("Offset: ".FileUtilsClass::ToHexAndDecimal($pngIndex-8,8)."<br>\n");
}
				switch ($chunkType)
				{
					case 'IHDR':
/*
PNG Chunk Definitions are in the following URL:
http://www.libpng.org/pub/png/spec/1.2/PNG-Chunks.html#C.IHDR

IHDR Image header

The IHDR chunk must appear FIRST. It contains:

Width:              4 bytes
Height:             4 bytes
Bit depth:          1 byte
Color type:         1 byte
Compression method: 1 byte
Filter method:      1 byte
Interlace method:   1 byte

*/					
						$hdrIndex = $pngIndex;
						$imageWidth  = (ord($this->png[$hdrIndex++]) << 24);
						$imageWidth += (ord($this->png[$hdrIndex++]) << 16);
						$imageWidth += (ord($this->png[$hdrIndex++]) << 8);
						$imageWidth += (ord($this->png[$hdrIndex++]));				
				
						$imageHeight  = (ord($this->png[$hdrIndex++]) << 24);
						$imageHeight += (ord($this->png[$hdrIndex++]) << 16);
						$imageHeight += (ord($this->png[$hdrIndex++]) << 8);
						$imageHeight += (ord($this->png[$hdrIndex++]));				
				
						$imageBitDepth = (ord($this->png[$hdrIndex++]));				
						$imageColorType = (ord($this->png[$hdrIndex++]));				
						$imageCompression = (ord($this->png[$hdrIndex++]));	
									
if ($this->DebugLevel >= 2) 
{
	StageShowLibEscapingClass::Safe_EchoHTML("Image Width: ".FileUtilsClass::ToHexAndDecimal($imageWidth,8)." ($imageWidth) <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("Image Height: ".FileUtilsClass::ToHexAndDecimal($imageHeight,8)." ($imageHeight) <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("Bit Depth: $imageBitDepth <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("Color Type: $imageColorType <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("Compression: $imageCompression <br>\n");
}
/*
	Get Run-time "constants"
	pixelsPerByte is the number of bits used by each pixel
	pixelMask is a bit mask to extract Pixel data from a data byte
	xOfstShift is used to convert the x Coordinate to a offset into the data block array
	bitsIndexMask is a bit mask to convert x Coordinate to Pixel Number (within a byte)
*/
			
			switch($imageBitDepth)
			{
				case 1:
					$pixelsPerByte = 8;
					$pixelsShift = 1;
					$this->pixelMask = 0x01;	
					$this->xOfstShift = 3;	
					$this->bitsIndexMask = 0x07;
					break;
					
				case 2:
					$pixelsPerByte = 4;
					$pixelsShift = 2;
					$this->pixelMask = 0x03;	
					$this->xOfstShift = 2;	
					$this->bitsIndexMask = 0x03;	
					break;
					
				case 4:
					$pixelsPerByte = 2;
					$pixelsShift = 4;					
					$this->pixelMask = 0x0f;	
					$this->xOfstShift = 1;	
					$this->bitsIndexMask = 0x01;	
					break;
					
				case 8:
					$pixelsPerByte = 1;
					$pixelsShift = 8;
					$this->pixelMask = 0xff;	
					$this->xOfstShift = 0;	
					$this->bitsIndexMask = 0x00;	
					break;		
			}

			for ($bitsIndex=0; $bitsIndex<8; $bitsIndex++)
			{
				$this->pixelShift[$bitsIndex] = ($pixelsPerByte - $bitsIndex - 1) * $imageBitDepth;	
			}
								
			$this->pixelDataRowLength = intval(ceil($imageWidth / $pixelsPerByte));
			$this->pixelDataRowLength++;	// Filter type code entry (always 0) preceeds the data - Increase length by 1
			
if ($this->DebugLevel >= 2)
{
	StageShowLibEscapingClass::Safe_EchoHTML("pixelsPerByte: $pixelsPerByte <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("pixelMask: ".FileUtilsClass::ToHexAndDecimal($this->pixelMask,2)." <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("pixelDataRowLength: ".FileUtilsClass::ToHexAndDecimal($this->pixelDataRowLength,4)." <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("pngPixelIndex: ".FileUtilsClass::ToHexAndDecimal($this->pngPixelIndex,4)." <br>\n");
}

						break;
						
					case 'PLTE':
						$plteIndex = $pngIndex;
						$this->noOfPaletteEntries = $chunkLen/3;
						for ($plteNo = 0; $plteNo<$this->noOfPaletteEntries; $plteNo++)
						{
							$red = (ord($this->png[$plteIndex++]));				
							$green = (ord($this->png[$plteIndex++]));				
							$blue = (ord($this->png[$plteIndex++]));
							
							// Determine if this palette entry should be shown as black
							$greyVal = $this->GreyVal($red, $green, $blue);
							$this->isBlack[$plteNo] = ($greyVal <= 127);
if ($this->DebugLevel >= 2)
{
	$paleteColour = $this->isBlack[$plteNo] ? 'Black' : 'White';						
	StageShowLibEscapingClass::Safe_EchoHTML("RGB: ($red, $green, $blue) - $paleteColour<br>\n");
}
						}
						break;
						
					case 'IDAT':
						if ($this->pngPixelIndex == 0)
						{
							$this->pngPixelIndex = $pngIndex;
							$this->pngPixelIndex += 8;	// 7 ZLib/DEFLATE specification bytes + filter type code
							$dstOfst = $pngIndex+$chunkLen;
if ($this->DebugLevel >= 2)
{
	StageShowLibEscapingClass::Safe_EchoHTML("pngPixelIndex: ".FileUtilsClass::ToHexAndDecimal($this->pngPixelIndex,4)." <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("dstOfst: ".FileUtilsClass::ToHexAndDecimal($dstOfst,4)." <br>\n");
}
						}
						else
						{
							// Move "up" png data to create contiguous block
							$srcOfst = $pngIndex;
if ($this->DebugLevel >= 2)
{
	StageShowLibEscapingClass::Safe_EchoHTML("srcOfst(start): ".FileUtilsClass::ToHexAndDecimal($srcOfst,4)." <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("dstOfst(start): ".FileUtilsClass::ToHexAndDecimal($dstOfst,4)." <br>\n");
}
							for ($i=0; $i<$chunkLen; $i++)
							{
								$pngData = $this->png[$srcOfst++];
								$this->png[$dstOfst++] = $pngData;
							}
if ($this->DebugLevel >= 2)
{
	StageShowLibEscapingClass::Safe_EchoHTML("dstOfst(end): ".FileUtilsClass::ToHexAndDecimal($dstOfst,4)." <br>\n");
}
						}
						break;
				}
				
				$pngIndex += ($chunkLen + 4);	// Skip data and CRC			
			}

$pngEndChunkData = $dstOfst - 4;	// Last IDAT chunk has DEFLATE CRC at end
if ($this->DebugLevel >= 2) StageShowLibEscapingClass::Safe_EchoHTML("pngEndChunkData: ".FileUtilsClass::ToHexAndDecimal($pngEndChunkData,4)." <br>\n");
for ($filterCodeOfst = $this->pngPixelIndex-1; $filterCodeOfst < $pngEndChunkData; $filterCodeOfst += $this->pixelDataRowLength)
{
	$pngVal = ord($this->png[$filterCodeOfst]);
	if ($pngVal != chr(0))
	{
		StageShowLibEscapingClass::Safe_EchoHTML("<strong>ERROR: Unexpected Scan line filter </strong>- filterCodeOfst: ".FileUtilsClass::ToHexAndDecimal($filterCodeOfst,4)." - data:".FileUtilsClass::ToHexAndDecimal($pngVal,2)." <br>\n");
	}
}

			switch ($rot)
			{
				case 0: 
				case 180: 
					$this->EncodeFromPNG_0($im, $xposn, $yposn, $rot); 
					break;	
									
				case 90: 
				case 270: 
					$this->EncodeFromPNG_90($im, $xposn, $yposn, $rot); 
					break;	
					
				default:
					StageShowLibEscapingClass::Safe_EchoHTML("<br><strong>Rotation not supported ($rot) </strong> @ Line:".__LINE__."<br><br>\n");
					break;				
			}
			
//StageShowLibEscapingClass::Safe_EchoHTML(count($paleteColours)." Colours in AddImage Palete <br>\n");
			if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('AddImage');
			return true;
		}
		
		function EncodeFromPNG_0($im, $xposn, $yposn, $rot)
		{		
			$imWidth = imagesx($im);
			$imHeight = imagesy($im);
				
			if ($rot == 0)
			{
				$xdraw = $xposn;
				$ydraw = $yposn;
				
				$startCol = $xdraw;
				$endCol	= $xdraw + $imWidth - 1;	
				$startRow = $ydraw;
				$endRow = $ydraw + $imHeight - 1;		

				$xPixStart = 0;
				$yPixStart = 0;
				$yPixStep = 1;
				$xPixStep = 1;
			}
			else if ($rot == 180)
			{
				$xdraw = $this->imgWidth - $imWidth - $xposn;
				$ydraw = $this->imgHeight - $imHeight - $yposn;
				
				$startCol = $xdraw;
				$endCol	= $xdraw + $imWidth - 1;	
				$startRow = $ydraw;
				$endRow = $ydraw + $imHeight - 1;		

				$xPixStart = $imWidth-1;
				$yPixStart = $imHeight-1;;
				$yPixStep = -1;
				$xPixStep = -1;
			}
			else
			{
				return false;
			}
							
			$firstCol = ($startCol >> 3)<<3;

if ($this->DebugLevel >= 2)
{
	StageShowLibEscapingClass::Safe_EchoHTML("startCol:$startCol endCol:$endCol <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("firstCol:$firstCol <br>\n");
	StageShowLibEscapingClass::Safe_EchoHTML("startRow:$startRow endRow:$endRow <br>\n");
}
		
			$paleteColours = array();
			
			$rowDataOfst  = (int)((($startRow * $this->maxCol) + $firstCol) >> 3);
			$rowDataOfst += $this->dataOfst;
			
			$rowDataInc = $this->maxCol >> 3;
			

			$yPix = $yPixStart;
			for ($ofstRow=$startRow; $ofstRow <= $endRow; $ofstRow++, $rowDataOfst += $rowDataInc, $yPix+=$yPixStep)
			{				
				$dataOfst  = $rowDataOfst;

				$xPix = $xPixStart;
				
				for ($ofstCol = $firstCol; $ofstCol <= $endCol;)
				{
					$nextByte = 0;
					$unchangedMask = 0;
					
					$rowPixelOfst  = $this->pngPixelIndex + ($yPix * $this->pixelDataRowLength);	

					for ($mask = 0x80; $mask != 0; $mask >>= 1, $xPix += $xPixStep)
					{
						if (($ofstCol < $startCol) || ($ofstCol > $endCol))
						{
							$unchangedMask |= $mask;
							$xPix = $xPixStart - $xPixStep;
							$bitsIndex = 0;
						}
						else
						{
							// Get pixel data
							$bitsIndex  = ($xPix & $this->bitsIndexMask);
							//if ($bitsIndex == 0)
							{
								$xOfst = $xPix >> $this->xOfstShift;
								$pixelOfst  = $rowPixelOfst + $xOfst;	
								
								$pngData = ord($this->png[(int)$pixelOfst]);
							}
							
							$pixelData = $pngData >> $this->pixelShift[$bitsIndex];
							$pixelData &= $this->pixelMask;

							if ($pixelData>=$this->noOfPaletteEntries)
							{
								StageShowLibEscapingClass::Safe_EchoHTML("isBlack[$pixelData] is NOT defined - Exiting <br>\n");
								return;
							}
							$pixelIsBlack = $this->isBlack[$pixelData];
							
							if ($pixelIsBlack)
							{
								$nextByte += $mask;
							}
														
						}
					
						$ofstCol++;
					}
					
					if ($this->newBitmap)
					{
						$this->escPosData .= chr($nextByte);
					}
					else if (($dataOfst >= 0) && ($dataOfst < $this->escPosDataLen))
					{
						if ($unchangedMask == 0)
						{
							$this->escPosData[$dataOfst] = chr($nextByte);
						}
						else
						{
							$escPosVal = $this->escPosData[$dataOfst];
							$escPosVal &= chr($unchangedMask);
							$escPosVal |= chr($nextByte);
							$this->escPosData[$dataOfst] = $escPosVal;						
						}
					}
					
					$dataOfst++;
				}
			}					
		}

		function EncodeFromPNG_90($im, $xposn, $yposn, $rot)
		{		
			$imWidth = imagesx($im);
			$imHeight = imagesy($im);

			if ($rot == 90)
			{
				$xdraw = $yposn;
				$ydraw = $this->imgWidth - $imWidth - $xposn;
				
				$startCol = $xdraw;
				$endCol	= $xdraw + $imHeight - 1;	
				$startRow = $ydraw;
				$endRow = $ydraw + $imWidth - 1;		

				$startingRowOffset = $this->pngPixelIndex;
				
				$xPixStart = $endRow-$startRow;
				$xPixStep = -1;
				$rowPixelOfstStep = $this->pixelDataRowLength;
			}
			else if ($rot == 270)
			{
				$xdraw = $this->imgHeight - $imHeight - $yposn;;
				$ydraw = $xposn;
				
				$startCol = $xdraw;
				$endCol	= $xdraw + $imHeight - 1;	
				$startRow = $ydraw;
				$endRow = $ydraw + $imWidth - 1;		

				$startingRowOffset = $this->pngPixelIndex + (($imHeight-1) * $this->pixelDataRowLength);
				
				$xPixStart = 0;
				$xPixStep = 1;
				$rowPixelOfstStep = 0-$this->pixelDataRowLength;				
			}
			else
			{
				return false;
			}	

			$firstCol = ($startCol >> 3)<<3;

			$paleteColours = array();
			
			$rowDataOfst  = (int)((($startRow * $this->maxCol) + $firstCol) >> 3);
			$rowDataOfst += $this->dataOfst;
			
			$rowDataInc = $this->maxCol >> 3;
			
			$startMask = 0x80;
			$startUnchangedMask = 0;
			for ($ofstCol = $firstCol; $ofstCol < $startCol; $startMask >>= 1, $ofstCol++)
			{
				$startUnchangedMask |= $startMask;
			}
			
			$xPix = $xPixStart;
			for ($ofstRow=$startRow; $ofstRow <= $endRow; $ofstRow++, $rowDataOfst += $rowDataInc, $xPix += $xPixStep)
			{				
				$dataOfst  = $rowDataOfst;

				$rowPixelOfst  = $startingRowOffset;	
				
				$mask = $startMask;
				
				for ($ofstCol = $startCol; $ofstCol <= $endCol;)
				{
					$nextByte = 0;
					$unchangedMask = $startUnchangedMask; // 0;
					
					for (; $mask != 0; $mask >>= 1)
					{
						if (($ofstCol > $endCol))
						{
							$unchangedMask |= $mask;
							$bitsIndex = 0;
						}
						else
						{
							// Get pixel data
							$bitsIndex  = ($xPix & $this->bitsIndexMask);
							$xOfst = $xPix >> $this->xOfstShift;
							$pixelOfst  = $rowPixelOfst + $xOfst;	
								
							$pngData = ord($this->png[(int)$pixelOfst]);
							
							$pixelData = $pngData >> $this->pixelShift[$bitsIndex];
							$pixelData &= $this->pixelMask;

							if ($pixelData >= $this->noOfPaletteEntries)
							{
								StageShowLibEscapingClass::Safe_EchoHTML("isBlack[$pixelData] is NOT defined - Exiting <br>\n");
								return;
							}
							$pixelIsBlack = $this->isBlack[$pixelData];
							
							if ($pixelIsBlack)
							{
								$nextByte += $mask;
							}
														
							$rowPixelOfst += $rowPixelOfstStep;	
						}
					
						$ofstCol++;
					}
					
					$mask = 0x80;
					
					if ($this->newBitmap)
					{
						$this->escPosData .= chr($nextByte);
					}
					else if (($dataOfst >= 0) && ($dataOfst < $this->escPosDataLen))
					{
						if ($unchangedMask == 0)
						{
							$this->escPosData[$dataOfst] = chr($nextByte);
						}
						else
						{
							$escPosVal = $this->escPosData[$dataOfst];
							$escPosVal &= chr($unchangedMask);
							$escPosVal |= chr($nextByte);
							$this->escPosData[$dataOfst] = $escPosVal;						
						}
					}					
					
					$dataOfst++;
				}
			}					
		}

	}
}
