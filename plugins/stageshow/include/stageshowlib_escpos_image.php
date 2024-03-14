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

include STAGESHOWLIB_INCLUDE_PATH.'/stageshowlib_escpos.php';

if (!class_exists('StageShowLibEscPosImageClass')) 
{
	class StageShowLibEscPosImageClass extends StageShowLibEscPosClass // Define class
	{				
		function AddImage($im, $xposn, $yposn, $rot, $useGD = false)
		{
			$imWidth = imagesx($im);
			$imHeight = imagesy($im);
			
			$this->paleteColours = array();
					
if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("Adding image with AddImage(GD) - XPosn:$xposn YPosn:$yposn Width:$imWidth Height:$imHeight Rotation:$rot <br>\n");

			$escPosDataLen = StageShowLibMigratePHPClass::Safe_strlen($this->escPosData);
			$newBitmap = ($this->dataOfst == $escPosDataLen);
			
			switch ($rot)
			{
				case 0: 					
					$xdraw = $xposn;
					$ydraw = $yposn;
					
					$startCol = $xdraw;
					$endCol	= $xdraw + $imWidth - 1;	
					$startRow = $ydraw;
					$endRow = $ydraw + $imHeight - 1;		
					break;
					
				case 180:
					$xdraw = $this->imgWidth - $imWidth - $xposn;
					$ydraw = $this->imgHeight - $imHeight - $yposn;
					
					$startCol = $xdraw;
					$endCol	= $xdraw + $imWidth - 1;	
					$startRow = $ydraw;
					$endRow = $ydraw + $imHeight - 1;		
					break;
					
				case 90: 
					$xdraw = $yposn;
					$ydraw = $this->imgWidth - $imWidth - $xposn;
					
					$startCol = $xdraw;
					$endCol	= $xdraw + $imHeight - 1;	
					$startRow = $ydraw;
					$endRow = $ydraw + $imWidth - 1;		
					break;

				case 270: 
					$xdraw = $this->imgHeight - $imHeight - $yposn;;
					$ydraw = $xposn;
					
					$startCol = $xdraw;
					$endCol	= $xdraw + $imHeight - 1;	
					$startRow = $ydraw;
					$endRow = $ydraw + $imWidth - 1;		
					break;

				default: 
if ($this->DebugLevel >= 1) StageShowLibEscapingClass::Safe_EchoHTML("<br><strong>Rotation not supported ($rot) </strong> @ Line:".__LINE__."<br><br>\n");
					return false;
			}

			$firstCol = ($startCol >> 3)<<3;
			
			$paleteColours = array();
			$paleteCounts = array();
			$lastPixIndex = -1;
			
			$rowDataOfst  = (int)((($startRow * $this->maxCol) + $firstCol) >> 3);
			$rowDataOfst += $this->dataOfst;
			
			$rowDataInc = $this->maxCol >> 3;

			for ($ofstRow=$startRow; $ofstRow <= $endRow; $ofstRow++, $rowDataOfst += $rowDataInc)
			{
				$dataOfst  = $rowDataOfst;
				for ($ofstCol = $firstCol; $ofstCol <= $endCol;)
				{
					$nextByte = 0;
					$unchangedMask = 0;
					
					switch ($rot)
					{
						case 0:
							$yPix = $ofstRow-$startRow;
							break;
							
						case 90:
							$xPix = $endRow-$ofstRow;
							break;
							
						case 180:
							$yPix = $endRow-$ofstRow;
							break;
							
						case 270:
							$xPix = $ofstRow-$startRow;
							break;								
					}
					
//StageShowLibEscapingClass::Safe_EchoHTML("dataOfst is of type ".gettype($dataOfst)." Value: $dataOfst <br>\n");
					for ($bitNo = 7, $mask = 0x80; $bitNo >= 0; $bitNo--, $mask >>= 1)
					{
						if (($ofstCol < $startCol) || ($ofstCol > $endCol))
						{
							$unchangedMask |= $mask;
						}
						else
						{
							switch ($rot)
							{
								case 0:
									$xPix = $ofstCol-$startCol;
									break;
									
								case 90:
									$yPix = $ofstCol-$startCol;
									break;
									
								case 180:
									$xPix = $endCol-$ofstCol;
									break;
									
								case 270:
									$yPix = $endCol-$ofstCol;
									break;								
							}
/*
if ( ($xPix >= $imWidth) 
  || ($yPix >= $imHeight) 
  || ($xPix < 0) 
  || ($yPix < 0) )
{
	StageShowLibEscapingClass::Safe_EchoHTML("<br><strong>imagecolorat Error - X:$xPix Y:$yPix </strong><br><br>\n");
	return false;
}
*/
							$pixIndex = imagecolorat($im, $xPix, $yPix);

							if ($lastPixIndex != $pixIndex)
							{
								$pixColourArray = $this->GetPaletteEntry($im, $pixIndex);								
								$pixelIsBlack = $pixColourArray['black'];
								$lastPixIndex = $pixIndex;
							}
	
							if ($pixelIsBlack)
							{
								$nextByte += $mask;
							}
														
						}
					
						$ofstCol++;
					}
					
					if ($dataOfst >= $escPosDataLen)
					{
						StageShowLibEscapingClass::Safe_EchoHTML("<br><strong>Error: Overrun in AddImage - </strong>Ptr:$dataOfst Len:$escPosDataLen <br><br>\n");
						return false;
					}
			
					if ($newBitmap)
					{
						$this->escPosData .= chr($nextByte);
					}
					else
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
		
//StageShowLibEscapingClass::Safe_EchoHTML(count($paleteColours)." Colours in AddImage Palete <br>\n");
			if (isset($myDBaseObj->timeLogObj)) $myDBaseObj->timeLogObj->LogTimestamp('AddImage');
			return true;
		}
		
		function GetPaletteEntry($im, $pixIndex)
		{
			if (!isset($this->paleteColours[$pixIndex]))
			{
				$pixColourArray = imagecolorsforindex($im, $pixIndex);
				$paletteEntry = new stdClass();
				$pixColour  = ($pixColourArray['red'] << 16);
				$pixColour += ($pixColourArray['green'] << 8);
				$pixColour +=  $pixColourArray['blue'];
				$pixColourArray['rgb'] = $pixColour;
				
				$greyVal = $this->GreyVal($pixColourArray['red'], $pixColourArray['green'], $pixColourArray['blue']);
				$pixColourArray['black'] = ($greyVal <= 127);
				
				$this->paleteColours[$pixIndex] = $pixColourArray;
			}
			else
			{
				$pixColourArray = $this->paleteColours[$pixIndex];
			}
			
			return $pixColourArray;
		}
		
		function GreyVal($red, $green, $blue)
		{
			$grey = ($red * 0.299) + ($green * 0.587) + ($blue * 0.114);
			return $grey;
		}
		
	}
}


