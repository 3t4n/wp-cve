<?php

if (!class_exists('StageShowLibDisplayEMailClass'))
{
	class StageShowLibDisplayEMailClass // Define class
	{
		var $saleAttr = 'id';
		var $templatePathAttr = 'template';
		var $callerPath;

		function __construct($DBaseClass, $callerPath)
		{
			$this->callerPath = $callerPath;
			$this->myDBaseObj = new $DBaseClass($callerPath);
		}

		function ProcessRemoteCall($request)
		{
			$templatePath = '';
			if (StageShowLibUtilsClass::IsElementSet('request', '_wpnonce'))
			{
				$isSalesUser = current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER);

				// Called from an admin page = Check wpnonce
				$callerFileName = basename($this->callerPath);
				$this->myDBaseObj->CheckAdminReferer($callerFileName);

				// Get sale	and ticket details
				$salesList = $this->GetReqSale($request);

				$path = '/emails/';
				if (StageShowLibUtilsClass::IsElementSet('request', 'path'))
				{
					switch (StageShowLibUtilsClass::GetHTTPTextElem('request', 'path'))
					{
						case 'forms':
							$path = '/forms/';
							break;
					}
				}

				if (StageShowLibUtilsClass::IsElementSet('request', 'template'))
				{
					$templatePath = StageShowLibUtilsClass::GetHTTPFilenameElem($request, 'template');
					if (!StageShowLibMigratePHPClass::Safe_strpos($templatePath, '/'))
					{
						$templatePath = STAGESHOWLIB_UPLOADS_PATH.$path.$request['template'];
					}
				}
			}
			else
			{
				// Called by purchaser - verify EMail address and saleTXnId
				if (StageShowLibUtilsClass::IsElementSet('post', 'template'))
				{
					$tmpltFile = StageShowLibUtilsClass::GetHTTPFilenameElem('post', 'template');
					$templatePath = STAGESHOWLIB_UPLOADS_PATH.'/emails/'.$tmpltFile;
				}

				if (!isset($request['saleEMail']))
					return false;

				if (!isset($request['saleTxnId']))
					return false;

				$saleEMail = StageShowLibHTTPIO::GetRequestedString('saleEMail');
				$saleTxnId = StageShowLibHTTPIO::GetRequestedString('saleTxnId');

				$saleID = $this->myDBaseObj->GetSaleFromTxnId($saleTxnId, $saleEMail);

				if ($saleID == 0)
					return false;

				// Get sale	and ticket details
				$salesList = $this->GetSaleDetails($saleID);
			}

			$emailAll = $this->DisplayEMail($salesList, $templatePath);
			if (!isset($emailAll['email'])) return;

			$html_emailContents = ($emailAll['email']);
			StageShowLibEscapingClass::Safe_EchoHTML($html_emailContents);
		}

		function GetEMailBody($saleID, $templatePath)
		{
			$salesList = $this->GetSaleDetails($saleID);

			// Add variables from form to sale ... so thay can be used in email templates
			foreach (array_keys($_POST) as $postKey)
			{
				if (isset($salesList[0]->$postKey)) continue;

				$salesList[0]->$postKey = StageShowLibUtilsClass::GetHTTPTextElem('post', $postKey);
			}

			$emailAll = $this->DisplayEMail($salesList, $templatePath);
			$emailContent = $emailAll['email'];

			$pregRslt = preg_match('/\<body[\s\S]*?\>([\s\S]*?)\<\/body/', $emailContent, $matches);
			if ($pregRslt == 1)
			{
				$emailAll['body'] =  $matches[1];
			}

			return $emailAll;
		}

		function GetReqSale($request)
		{
			die('function GetReqSale() must be defined in '.get_class($this));
		}

		function GetEMailAddress($salesList)
		{
			die('function GetEMailAddress() must be defined in '.get_class($this));
		}

		function GetSaleDetails($salesList)
		{
			die('function GetSaleDetails() must be defined in '.get_class($this));
		}

		function DisplayEMail($salesList, $templatePath)
		{
			if (count($salesList) < 1)
				return 'salesList Empty';

			if ($templatePath == '')
			{
				$templatePath = $this->myDBaseObj->GetEmailTemplatePath('EMailTemplatePath', $salesList);
			}

			$rtnStatus = $this->myDBaseObj->AddRecordToTemplate($salesList, $templatePath, $EMailSubject, $saleConfirmation);

			if (!StageShowLibMigratePHPClass::Safe_strpos($saleConfirmation, '</html>'))
			{
				$saleConfirmation = StageShowLibMigratePHPClass::Safe_str_replace("\n", "<br>\n", $saleConfirmation);
			}

			$saleEMail = $this->GetEMailAddress($salesList);

			$emailheaders = '';
			if (!StageShowLibUtilsClass::IsElementSet('request', 'noheader'))
			{
				$emailheaders .= 'To: '.StageShowLibMigratePHPClass::Safe_htmlspecialchars($saleEMail)."<br>\n";
				$emailheaders .= 'Subject: '.$EMailSubject."<br>\n";
				$emailheaders .= "<br>\n";

				$findBodyTagRslt = preg_match('/(\<body[\s\S]*?\>)/i', $saleConfirmation, $matches);
				if ($findBodyTagRslt == 1)
				{
					$saleConfirmation = StageShowLibMigratePHPClass::Safe_str_replace($matches[1], $matches[1]."\n".$emailheaders, $saleConfirmation);
				}
				else
				{
					$saleConfirmation = $emailheaders.$saleConfirmation;
				}
			}

			$rtnval['to'] = $saleEMail;
			$rtnval['subject'] = $EMailSubject;
			$rtnval['email'] = $saleConfirmation;

			return $rtnval;
		}
	}
}



