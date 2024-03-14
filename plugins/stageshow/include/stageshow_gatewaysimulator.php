<?php

include 'stageshowlib_gatewaysimulator.php';

if (!class_exists('StageShowGatewaySimulator')) 
{
	class StageShowGatewaySimulator extends GatewaySimulator
	{
		function __construct($notifyDBaseClass, $saleId = 0) 
		{
			parent::__construct($notifyDBaseClass, $saleId);
	    }

		function OutputHeader() 
		{
	    }

		function OutputFooter() 
		{
	    }

		function OutputItemsTableHeader($result) 
		{
			$html  = '';
			$html .= '
			<div>
			<table  class="stageshow-simulator-detailstable">
				<tr class="stageshow-simulator-detailsrow stageshow-simulator-details-header">
					<td class="stageshow-simulator-datetime">Date & Time</td>
					<td class="stageshow-simulator-type">Ticket Type</td>';
			if (isset($result->ticketSeat))
			{
				$html .= '
					<td class="stageshow-simulator-seat">Seat</td>';				
			}
			$html .= '
					<td class="stageshow-simulator-price">Price</td>
					<td class="stageshow-simulator-qty">Qty</td>
				</tr>
			';
			
			return $html;    
	    }
		
		function OutputItemsTableRow($indexNo, $result) 
		{
			$html = '<tr class="stageshow-simulator-detailsrow stageshow-simulator-details-items">';
			
			$description = $result->ticketName; // $result->showName.' - '.$this->myDBaseObj->FormatDateForDisplay($result->perfDateTime);
			$reference = $result->showID.'-'.$result->perfID;
				
			$html .= '<td class="stageshow-simulator-datetime" >'.$this->myDBaseObj->FormatDateForDisplay($result->perfDateTime).'</td>';
			$html .= '<td class="stageshow-simulator-type" >'.$result->ticketType.'</td>';
			
			if (isset($result->ticketSeat))
			{
				if ($result->ticketSeat != '')
				{
					$seat = StageShowZonesDBaseClass::DecodeSeatsList($this->myDBaseObj,  $result->ticketSeat, $result->perfSeatingID);
					$description .= '-'.$seat;
				}
				else
					$seat = '&nbsp;';
				$html .= '<td class="stageshow-simulator-seat" >'.$seat.'</td>';
			}
			$html .= '<td class="stageshow-simulator-price" >'.($result->ticketPaid/$result->ticketQty).'</td>';
			$html .= '<td class="stageshow-simulator-qty" >';
			
			$html .= '
				<input type="hidden" name="quantity'.$indexNo.'" value="'.$result->ticketQty.'"/>
			';
					
			$this->totalSale += $result->ticketPaid;
			$html .= $result->ticketQty;
			$customVal = $result->saleID;
				
			switch ($this->gatewayType)
			{
				case 'paypal':
					$html .= '
						<input type="hidden" name="item_name'.$indexNo.'" value="'.$description.'"/>
						<input type="hidden" name="item_number'.$indexNo.'" value="'.$reference.'"/>
						<input type="hidden" name="option_name1_'.$indexNo.'" value="Ticket Type"/>
						<input type="hidden" name="option_selection1_'.$indexNo.'" value="'.$result->ticketType.'"/>
						<input type="hidden" name="mc_gross_'.$indexNo.'" value="'.$result->ticketPaid.'"/>
						';
					break;
			}

			$html .= '
				</td>
			</tr>';
				
			return $html;    
	    }
		
		function OutputItemsTable($results) 
		{
			if (count($results) == 0) return '';
			
			$html  = "<h2>".$results[0]->showName."</h2>\n";
			$html .= parent::OutputItemsTable($results);
			return $html;
	    }
		
		function OutputCallbackParams($saleId) 
		{
			return parent::OutputCallbackParams($saleId);
		}		


	}
}

?>