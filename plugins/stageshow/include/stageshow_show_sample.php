<?php
/* 
Description: StageShow Plugin Top Level Code
 
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

	$fileNamesList = StageShowLibUtilsClass::GetHTTPAlphaNumericElem($_REQUEST, 'file');
	$fileNames = explode(',', $fileNamesList);
	foreach ($fileNames as $fileName)
	{
		if (!preg_match('/^[A-Za-z0-9\-\_]+\.php$/', $fileName))
		{
			continue;		
		}
		
		$filePath = STAGESHOW_FILE_PATH.'samples/'.$fileName;
		if (!file_exists($filePath))
		{
			continue;		
		}
		
		$text = file_get_contents($filePath);
		StageShowLibEscapingClass::Safe_EchoHTML(StageShowLibMigratePHPClass::Safe_str_replace("\n", "<br>\n", StageShowLibMigratePHPClass::Safe_htmlspecialchars($text)));		
	}


