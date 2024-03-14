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

include 'stageshowlib_export.php';

if (!class_exists('StageShowPIRExportAdminClass')) 
{
	class StageShowPIRExportAdminClass extends StageShowLibExportAdminClass  // Define class
	{
		function __construct($myDBaseObj) //constructor	
		{
			parent::__construct($myDBaseObj);			
			
			$this->fieldNames = $this->GetFields();
		}

		function GetFields()
		{			
			$fieldNames = array(
				'saleEMail'             => __('EMail', 'stageshow'),
				'saleFirstName'         => __('First Name', 'stageshow'),
				'saleLastName'          => __('Last Name', 'stageshow'),

				'salePPStreet'          => __('Street', 'stageshow'),
				'salePPCity'            => __('City', 'stageshow'),
				'salePPState'           => __('County', 'stageshow'),
				'salePPZip'             => __('Postcode', 'stageshow'),
				'salePPCountry'         => __('Country', 'stageshow'),
				'salePPPhone'           => __('Phone', 'stageshow'),
				
				'saleDateTime'          => __('Sale Date & eTime', 'stageshow'),
				'salePaidDateTime'      => __('Paid Date & Time', 'stageshow'),
				
				'salePaid'              => __('Payment Amount', 'stageshow'),
				'salePostage'           => __('Postage', 'stageshow'),
				'saleDonation'          => __('Donation', 'stageshow'),
				'saleExtraDiscount'     => __('ExtraDiscount', 'stageshow'),
				'saleTransactionFee'    => __('TransactionFee', 'stageshow'),
				'saleFee'               => __('Fee', 'stageshow'),
				'saleMethod'            => __('Payment Method', 'stageshow'),
				
				'seatingVenue'          => __('Venue', 'stageshow'),
				'showName'              => __('Show Name', 'stageshow'),
				'perfDateTime'          => __('Performance Date & Time', 'stageshow'),
				'zoneRef'               => __('Zone', 'stageshow'),

				'ticketQty'             => __('Ticket Qty', 'stageshow'),
				'ticketPaid'            => __('Ticket Paid', 'stageshow'),
				'ticketSeat'            => __('Ticket Seat', 'stageshow'),
				
				'priceType'             => __('Price Type', 'stageshow'),
				'priceValue'            => __('Price Value', 'stageshow'),
				'priceNoOfSeats'        => __('Price No Of Seats', 'stageshow'),				
				);
				
			return $fieldNames;
		}

		function GetPIRExportFile($pirRecords)
		{			
			return $this->GetExport($pirRecords);
		}
	}
}

