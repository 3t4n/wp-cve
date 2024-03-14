<?php
/* 
Description: StageShow OFX Export functions
 
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

include STAGESHOW_INCLUDE_PATH.'stageshowlib_sales_ofx.php';      

if (!class_exists('StageShowOFXExportAdminClass')) 
{
	class StageShowOFXExportAdminClass extends StageShowLibOFXExportAdminClass // Define class
	{
		
		function __construct($myDBaseObj) //constructor	
		{
			parent::__construct($myDBaseObj);
		}

		function ofx_statement_start_ts()
		{			
			$noOfSales = count($this->sales);
			if ($noOfSales <= 0)
			{
				return '';
			}
			$firstSale = $this->sales[$noOfSales-1];
			$firstsaleTimestamp = StageShowLibMigratePHPClass::Safe_strtotime($firstSale->saleDateTime);
			
			return $firstsaleTimestamp;
		}
		
		function ofx_statement_end_ts()
		{			
			return current_time('timestamp');
		}
		
		function ofx_TxnId($saleTxnId, $trntype)
		{	
			switch ($trntype)
			{
				case TRNTYPE_FEE:
					$saleTxnId .= '-'.TRNTYPE_FEE;
					break;
					
				default:
					break;
			}
			return $saleTxnId;
		}
		
		function ofx_statement_transactions()
		{			
			parent::ofx_statement_transactions();
			
			$memo = 'Downloaded from StageShow';
			
			foreach($this->sales as $sale)
			{
				$index = 0;
				$this->ofx_transaction($sale, TRNTYPE_FEE, $sale->saleExtraDiscount, 'DISCOUNT '.$memo, $index++);
				$this->ofx_transaction($sale, TRNTYPE_FEE, $sale->saleTransactionFee, 'BOOKING FEE '.$memo, $index++);
				$this->ofx_transaction($sale, TRNTYPE_FEE, $sale->saleFee * (-1), 'FEE '.$memo, $index++);
				$this->ofx_transaction($sale, TRNTYPE_SALE, $sale->salePaid, 'SALE '.$memo, $index++);
				$this->ofx_transaction($sale, TRNTYPE_SALE, $sale->saleDonation, 'DONATION '.$memo, $index++);
				$this->ofx_transaction($sale, TRNTYPE_SALE, $sale->salePostage, 'POSTAGE '.$memo, $index++);
			}
			
		}
		
		function ofx_statement()
		{			
			$this->sales = $this->myDBaseObj->GetAllSalesList();
			
			parent::ofx_statement();
		}
	}
}

