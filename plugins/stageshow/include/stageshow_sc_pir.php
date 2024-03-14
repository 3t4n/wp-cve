<?php

if (!class_exists('StageShowPIRShortcodeClass')) 
{
	include STAGESHOW_INCLUDE_PATH.'stageshowlib_sc_pir.php';
	include STAGESHOW_INCLUDE_PATH.'stageshow_pir_export.php';
	
	class StageShowPIRShortcodeClass extends StageShowLibPIRShortcodeClass // Define class
	{	
		function __construct($pluginObj)
		{	
			parent::__construct($pluginObj);
		}
		
		function GetTSVFileName($tsvData)
		{
			$saleName = $tsvData->saleFirstName."-".$tsvData->saleLastName;
			$currTime = date('Ymd-His');
			
			$saleName = preg_replace('/([\.])/', '', $saleName);
			$fileName = "$saleName-$currTime.tsv";
			
			return $fileName;
		}
		
		function CreateExportObj()
		{
			return new StageShowPIRExportAdminClass($this->myDBaseObj);
		}
		
	}
}
		
?>