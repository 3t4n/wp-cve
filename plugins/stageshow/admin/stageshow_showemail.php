<?php

include STAGESHOW_INCLUDE_PATH.'stageshowlib_showemail.php';	

if (!class_exists('StageShowDisplayEMailClass')) 
{
	class StageShowDisplayEMailClass extends StageShowLibDisplayEMailClass // Define class
	{
		function __construct($DBaseClass, $callerPath)
		{			
			parent::__construct($DBaseClass, $callerPath);
			$this->ProcessRemoteCall($_REQUEST);
		}		
		
		function GetReqSale($request)
		{			
			$saleID = StageShowLibUtilsClass::GetHTTPTextElem('request', 'id');	
			if (!is_numeric($saleID)) return null;						
			return $this->myDBaseObj->GetSaleDetails($saleID);
		}		
		
		function GetEMailAddress($salesList)
		{			
			if (count($salesList) < 1) 
				return '';
				
			return $salesList[0]->saleEMail;
		}		

	}
}
		
new StageShowDisplayEMailClass(STAGESHOWLIB_DBASE_CLASS, 'stageshow_showemail.php');	


?>