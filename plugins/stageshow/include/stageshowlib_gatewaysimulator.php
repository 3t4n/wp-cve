<?php

if (!class_exists('GatewaySimulator')) 
{
	class GatewaySimulator
	{
		function __construct($notifyDBaseClass, $saleId = 0) 
		{
	  		$this->transactionID = current_time('timestamp');		
			$this->myDBaseObj = new $notifyDBaseClass(__FILE__);
			$this->gatewayType = $this->myDBaseObj->gatewayObj->GetType();
						
			$this->totalSale = 0.00;

			$formHTML = '';
			
			$devMode = true;
			if (StageShowLibUtilsClass::IsElementSet('get', 'id'))
			{
				$saleId = StageShowLibUtilsClass::GetHTTPInteger('get', 'id', -1);
				$devMode = false;
			}
			elseif (StageShowLibUtilsClass::IsElementSet('post', 'id'))
			{
				$saleId = StageShowLibUtilsClass::GetHTTPInteger('post', 'id', -1);
			}
			
			if ($saleId == 0)
			{
				$saleList = $this->GetPendingSales();	
				
				if (count($saleList) == 0)
				{
					$siteURL = get_option('siteurl');
					$selectHTML  = '<p>No pending sales - Checkout a sale to add one<p>'."\n";
					$selectHTML .= 'Go to <a href="'.$siteURL.'">site</a>'."\n";
					StageShowLibEscapingClass::Safe_EchoHTML($selectHTML);
					return;
				}
				
				if (count($saleList) == 1)
				{
					$saleId = $saleList[0]->saleID;
				}				
			}
			
			if ($saleId == 0)
			{
				$formHTML .= $this->OutputHeader();
				$formHTML .=  '<form name="gateway_sim" method="post">';			
				$formHTML .=  $this->OutputSaleSelect($saleList); 
			}
			if ($saleId > 0)
			{
				if (!is_numeric($saleId)) 
					die("Program Terminated at Line ".__LINE__." in file ".__FILE__);
				
				$actionHTML = '';

				$formHTML .= $this->OutputHeader();
				$formHTML .=  '<form name="gateway_sim" '.$actionHTML.' method="post">';			
				$formHTML .=  $this->OutputSaleForm($saleId);
			}
			$formHTML .=  '</form>';			
			
			StageShowLibEscapingClass::Safe_EchoHTML($formHTML);
	   	}

		function GetGatewayNotifyURL()
		{
			return $this->myDBaseObj->gatewayObj->GatewayNotifyURL;
	   	}

		function OutputSaleForm($saleId)
		{
			$this->paramIDs = '';
			
			$formHTML  = ''; 
			$formHTML .= '<br>'; 
			$formHTML .= '<h2>Sale Details:</h2><div class="gatewaysim_saledetails">'; 
			$formHTML .= $this->OutputSaleDetails($saleId); 
			$formHTML .= '</div>'; 
			
			$formHTML .= '<div class="gatewaysim_purchaserdetails">'."\n";			
			$formHTML .= "<h2>Purchaser Details:</h2>\n"; 
			$formHTML .= "<table>\n";			

			// Output all Gateway callback values as edit boxes
			$formHTML .= $this->OutputInputFields();
			$formHTML .= $this->OutputActionsTable();	
			$formHTML .= "</table>\n";			
			$formHTML .= "<div>\n";			
			
			// Now Output all other Gateway tags as hidden fields
			$formHTML .= $this->OutputCallbackParams($saleId); 
			
			return $formHTML;
		}
			
		function OutputHeader() 
		{
			$header = '
				<!-- OutputHeader function not defined! -->
			';

			return $header;
	    }
		
		function GetPendingSales() 
		{
			$myDBaseObj = $this->myDBaseObj;
			$sqlFilters = array();
			
			$sql  = 'SELECT saleID, saleDateTime, saleTxnId, salestatus FROM '.$myDBaseObj->DBTables->Sales;
			$sql .= ' WHERE salestatus = "'.PAYMENT_API_SALESTATUS_CHECKOUT.'" ';
			$sql .= ' OR salestatus = "'.PAYMENT_API_SALESTATUS_TIMEOUT.'" ';
			
			$sqlFilters['noLoginID'] = true;
			$saleList = $myDBaseObj->get_results($sql, true, $sqlFilters);
			
			return $saleList;
	    }
		
		function OutputSaleSelect($saleList) 
		{
			$selectHTML = '
			<p>
			<table width="100%" border="0">
			<tr>
				<td class="gatewaysim_formFieldID">Select Sale:&nbsp; </td>
				<td>&nbsp;</td>
				<td class="gatewaysim_formFieldValue" colspan="2">
					<div align="left">
						<select name="id">
			';
			
			foreach ($saleList as $sale)
			{
				$selectHTML .= '<option value="'.$sale->saleID.'" selected="">'.$sale->saleDateTime.'</option>'."\n";
			}
			
			$selectHTML .= '
						</select>
					</div>
				</td>
				</tr>
				<tr><td colspan="4">&nbsp;</td></tr>
				<tr>
	                <td colspan="4">
						<div align="center">
							<input class="button-primary" type="submit" name="selectSale" value="SELECT"/>
						</div>
					</td>
				</tr>
	            </table>
			';
			
			return $selectHTML;
	    }
		
		function OutputSaleDetails($saleId) 
		{
			$sqlFilters['noLoginID'] = true;
			$this->saleList = $this->myDBaseObj->GetSale($saleId, $sqlFilters);
			return  $this->OutputItemsTable($this->saleList); 
	    }
		
		function OutputItemsTableHeader($result) 
		{
			StageShowLibUtilsClass::UndefinedFuncCallError($this, 'OutputItemsTableHeader');
	    }
		
		function OutputItemsTableRow($indexNo, $result) 
		{
			StageShowLibUtilsClass::UndefinedFuncCallError($this, 'OutputItemsTableRow');
	    }
		
		function OutputSupplementaryRow($title, $value) 
		{
			$this->totalSale += $value;
			$rowClass = 'gatewaysim-'.StageShowLibMigratePHPClass::Safe_strtolower($title).'row';
			$html = "<tr class=$rowClass><td>$title</td><td>$value</td></tr>\n";
			return $html;
	    }
		
		function OutputItemsTable($results) 
		{
			$html = '';
			
			if (count($results) == 0) return '';
				
			$html .= $this->AddJavascript(count($results));
			
			$PayPalLogoImageFile = '';
			$PayPalHeaderImageFile = '';

			$hiddenTags  = "\n";
			switch ($this->gatewayType)
			{
				case 'paypal':
					$hiddenTags .= $this->AddHiddenTag('cmd', '_s-xclick');
					if (StageShowLibMigratePHPClass::Safe_strlen($PayPalLogoImageFile) > 0) 
					{
			        	$hiddenTags .= '<input type="hidden" name="image_url" value="'.$this->myDBaseObj->getImagesURL().$PayPalLogoImageFile.'"/>'."\n";
					}
					if (StageShowLibMigratePHPClass::Safe_strlen($PayPalHeaderImageFile) > 0) 
					{
						$hiddenTags .= '<input type="hidden" name="cpp_logo_image" value="'.$this->myDBaseObj->getImagesURL().$PayPalHeaderImageFile.'"/>'."\n";
					}

					$hiddenTags .= $this->AddHiddenTag('on0', 'TicketType');
					$hiddenTags .= $this->AddHiddenTag('SiteURL', get_site_url());
					break;
			}
	             
			$indexNo = 0;
			foreach($results as $result) 
			{
				if ($indexNo == 0) $html .= $this->OutputItemsTableHeader($result);
				$indexNo++;
				$html .= $this->OutputItemsTableRow($indexNo, $result);
			}

			$html .= "<tr class=gatewaysim-extrarows><td>&nbsp;</td></tr>\n";			
			$html .= $this->OutputSupplementaryRow('Postage', $results[0]->salePostage);
			$html .= $this->OutputSupplementaryRow('Donation', $results[0]->saleDonation);
			if (isset($results[0]->saleExtraDiscount))
			{
				$html .= $this->OutputSupplementaryRow('Discount', $results[0]->saleExtraDiscount);
			}
			$html .= $this->OutputSupplementaryRow('Transaction Fee', $results[0]->saleTransactionFee);

			$html .= '			
		  		</table>
				</div>
					'; 					

			$gross = $this->totalSale;
			$fee = round(0.20 + ($gross * 0.034), 2);
			$net = $gross - $fee;
			
			$customVal = $results[0]->saleID;
			switch ($this->gatewayType)
			{
				case 'paypal':
					$html .= $this->AddHiddenTag('num_cart_items', $indexNo);
					$html .= $this->AddHiddenTag('custom', $customVal);
					$html .= $this->AddHiddenTag('mc_fee', $fee);
					break;
			}
			
			return $html;
	    }
		
		function OutputActionsTable() 
		{
			switch ($this->gatewayType)
			{
				case 'paypal':
				default:
					$payment_status_OK = 'Completed';
					break;
			}
			
			$readOnly = isset($this->CanEditTotal) ? '' : ' readonly="readonly" ';
			$testEmail = '';
			$actionsHTML = '';
			$actionsHTML .= '
				<tr class="gatewaysim_formRow">
					<td class="gatewaysim_formFieldID">EMail:&nbsp;</td>
					<td class="gatewaysim_formFieldValue" colspan="2">
						<input name="payer_email" id="payer_email" type="text" maxlength="30" size="31" value="'.$testEmail.'"  />
					</td>
				</tr>
				<tr class="gatewaysim_formRow">
					<td class="gatewaysim_formFieldID">Total:&nbsp;</td>
					<td class="gatewaysim_formFieldValue" colspan="2">
						<input name="mc_gross" id="mc_gross" type="text" maxlength="6" size="6" value="'.$this->totalSale.'" '.$readOnly.'/>
					</td>
				</tr>
			';
			$actionsHTML .= $this->AddHiddenTag('txn_id', $this->transactionID);
			$actionsHTML .= $this->AddHiddenTag('payment_status', 'Completed');

			$actionsHTML .= '
				<tr class="gatewaysim_formRow"><td>&nbsp;</td></tr>
				<tr class="gatewaysim_formRow">
					<td class="ButtonCol" colspan="3">
						<input class="button-primary" type="submit" name="SUBMIT_simulateGateway" value="SUBMIT"/>
					</td>
				</tr>
			';
			
			return $actionsHTML;
	    }
		
		function OutputCallbackParams($saleId) 
		{
			$html = '';
			$optionsID = $this->myDBaseObj->opts['CfgOptionsID'];
			$currOptions = $this->myDBaseObj->ReadSettings($optionsID);
			
			switch ($this->gatewayType)
			{
				case 'paypal':
					$receiverEMail = $currOptions['PayPalAPIEMail'];									
					if (isset($this->saleList) && isset($this->saleList[0]->saleGatewayIndex))
					{
						$saleGatewayIndex = $this->saleList[0]->saleGatewayIndex;
						if ($saleGatewayIndex > 1)
						{
							$receiverEMail = $currOptions['PayPalAPIEMail_'.$saleGatewayIndex];									
						}
					}
					
			        $html .= $this->AddHiddenTag('charset', 'windows-1252');		
					
			        $html .= $this->AddHiddenTag('address_name', '(Unused)');
					
			        $html .= $this->AddHiddenTag('address_status', 'unconfirmed');
			        $html .= $this->AddHiddenTag('business', 'wibble@wobble.org.uk');

					// TODO - Test with Zollstockgürtel
			        $html .= $this->AddHiddenTag('contact_phone', '01234 567890');
					
			        $html .= $this->AddHiddenTag('mc_currency', 'GBP');

			        // $html .= $this->AddHiddenTag('payer_email', 'buyer%40punter.com');
			        $html .= $this->AddHiddenTag('payer_status', 'unverified');
			        $html .= $this->AddHiddenTag('payment_date', '23%3A03%3A08+Sep+26%2C+2010+PDT');
			        $html .= $this->AddHiddenTag('receiver_email', $receiverEMail);				
			        $html .= $this->AddHiddenTag('verify_sign', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123.abcdefghijklmnopqrstuvwxy');						
			        
			        $html .= $this->AddHiddenTag('paramIDs', $this->paramIDs);
					break;
			}
					
			return $html;
		}		

	    function OutputInputFields() 
		{			
			$html = '';
	
			switch ($this->gatewayType)
			{
				case 'paypal':
			        $html .= $this->AddHiddenTag('first_name', 'My', true);
			        $html .= $this->AddHiddenTag('last_name', 'Tester', true);
					
			        $html .= $this->AddHiddenTag('address_street', 'Highgrove House', true);
			        $html .= $this->AddHiddenTag('address_city', 'Sometown', true);
			        $html .= $this->AddHiddenTag('address_state', 'Gloucestershire', true);
			        $html .= $this->AddHiddenTag('address_zip', 'GL8 8TN', true);
			        $html .= $this->AddHiddenTag('address_country', 'United Kingdom', true);
			        $html .= $this->AddHiddenTag('address_country_code', 'GB', false);
			        break;
			        
			}
			
			return $html;
	    }
		
	    function AddHiddenTag($tagName, $tagValue, $editable = false) 
		{
			if (!$editable)
	        	return "<input type=\"hidden\" name=\"$tagName\" value=\"$tagValue\">\n";
			
			$tagTitle = ucwords(StageShowLibMigratePHPClass::Safe_str_replace("_", " ", $tagName));
			$paramID = 'PayPalVal_'.$tagName;
			
			$sessionVar = 'StageShowLib_Sim_'.$tagName;
			$tagValue = $this->myDBaseObj->IsSessionElemSet($sessionVar) ? $this->myDBaseObj->GetSessiomElem($sessionVar) : '';
			if ($tagValue != '')
			{
				$tagValue = $tagValue.'';
			}
			if ($this->paramIDs != '') $this->paramIDs .= ',';
			$this->paramIDs .= $tagName;
			
			return '
				<tr class="gatewaysim_formRow">
					<td class="gatewaysim_formFieldID">'.$tagTitle.':&nbsp;</td>
					<td class="gatewaysim_formFieldValue" colspan="2">
						<input name="'.$tagName.'" id="'.$tagName.'" type="text" maxlength="50" size="50" value="'.$tagValue.'" />
					</td>
				</tr>
			';
			
	    }

		function AddJavascript($itemsCount) 
		{
			$code = '
	<script language="JavaScript">
	<!--
			function StageShowLib_onclickqty() {
			var total = 0.0;
	';
			for ($indexNo = 1; $indexNo <= $itemsCount; $indexNo++)
				$code .= 'total += (parseFloat(document.gateway_sim.mc_gross_'.$indexNo.'.value) * document.gateway_sim.quantity'.$indexNo.".selectedIndex);\n";
			$code .= '
			document.gateway_sim.mc_gross.value = total;
			document.gateway_sim.mc_fee.value = (Math.floor((total*3.4) + 20))/100;
			}

			function StageShowLib_onclickrefresh() {
			var now = new Date();
			var transactionID;

			transactionID = Math.floor(now.getTime()/1000);
			document.gateway_sim.txn_id.value = transactionID;
			}

	//-->
	</script>

			';
			
			return $code;
		}
			

	}
}



