<?php
/* 
Description: Code for Data Export functionality
 
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

include STAGESHOW_INCLUDE_PATH.'stageshowlib_export.php';
//include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_api.php';

if (!class_exists('StageShowDBExportAdminClass')) 
{
	if (!defined('STAGESHOWLIB_MIMETYPE_SQL'))
		define('STAGESHOWLIB_MIMETYPE_SQL', 'application/x-sql');
		
	class StageShowDBExportAdminClass extends StageShowLibExportAdminClass  // Define class
	{
		function __construct($myDBaseObj) //constructor	
		{
			parent::__construct($myDBaseObj);
			
			$mimeType = STAGESHOWLIB_MIMETYPE_SQL;

			$this->fileName = 'stageshow_db.sql';
			$this->output_downloadHeader($mimeType);
			$this->export_stageshow_db();
		}

		function export_stageshow_db()
		{			
			$sqlExport = $this->myDBaseObj->GetDatabaseSQL(STAGESHOW_TABLE_PREFIX);
			StageShowLibEscapingClass::Safe_EchoHTML($sqlExport);
		}
	}
}

if ( StageShowLibUtilsClass::IsElementSet('post', 'download' ) )
{
	$dbaseClass = STAGESHOWLIB_DBASE_CLASS;
	new StageShowDBExportAdminClass(new $dbaseClass(__FILE__));
} 
