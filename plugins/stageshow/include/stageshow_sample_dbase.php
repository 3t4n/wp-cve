<?php
/* 
Description: StageShow Plugin Sample Database functions
 
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

include 'stageshowlib_sample_dbase.php';

if (!class_exists('StageShowSampleDBaseClass')) 
{
	define('STAGESHOW_PRICE_S1_P1_ALL', '12.50');
	define('STAGESHOW_PRICE_S1_P2_ADULT', '5.50');
	define('STAGESHOW_PRICE_S1_P3_ADULT', '4.00');
	define('STAGESHOW_PRICE_S1_P4_ALL', '6.00');
	define('STAGESHOW_PRICE_S1_P2_CHILD', '3.00');
	define('STAGESHOW_PRICE_S1_P3_CHILD', '2.00');
	
	define('STAGESHOW_PRICE_S2_P1_ADULT',  '5.50');
	define('STAGESHOW_PRICE_S2_P1_CHILD',  '3.50');
	define('STAGESHOW_PRICE_S2_P1_FAMILY', '15.00');
	define('STAGESHOW_PRICE_S2_P1_STAFF',  '0.00');
	
	define('STAGESHOW_PRICE_S1_P4_ZA_GENERAL','8.00');
	define('STAGESHOW_PRICE_S1_P4_ZB_ADULT',  '6.00');
	define('STAGESHOW_PRICE_S1_P4_ZB_CHILD',  '3.25');
	define('STAGESHOW_PRICE_S1_P4_ZB_FAMILY', '15.40');
	define('STAGESHOW_PRICE_S1_P4_ZC_ALL',    '6.80');
	
	define('STAGESHOW_PRICE_S1_P5_Z1_ADULT',  '8.10');
	define('STAGESHOW_PRICE_S1_P5_Z1_CHILD',  '5.75');
	define('STAGESHOW_PRICE_S1_P5_Z2_ALL',    '4.25');
	
	define('STAGESHOW_PRICE_S1_P1_ALL_DIS1A', '11.50');
	
	class StageShowSampleDBaseClass extends StageShowLibSampleDBaseClass // Define class
  	{
		function CreateSample($sampleDepth = 0)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$myDBaseObj->GetBoxOfficeClasses();
			
			$dis1 = $myDBaseObj->AddDiscount('Magazine');
			$myDBaseObj->UpdateDiscountCodes($dis1, ",DIS1a,DIS1b,");
			
			$dis2 = $myDBaseObj->AddDiscount('Mailshot');
			$myDBaseObj->UpdateDiscountCodes($dis2, ",DIS2a,DIS2b,");
			
			$myDBaseObj->SavePresetDiscount($dis1, 1, 5.00);
			$myDBaseObj->SavePresetDiscount($dis2, 1, 4.80);
						
			$myDBaseObj->SavePresetDiscount($dis1, 2, 2.50);
			$myDBaseObj->SavePresetDiscount($dis2, 2, 2.45);
							
			$myDBaseObj->SaveDiscountPrice($dis1, 1,  11.50);
			$myDBaseObj->SaveDiscountPrice($dis2, 1,  11.75);
			$myDBaseObj->SaveDiscountPrice($dis2, 2,  4.00);
			$myDBaseObj->SaveDiscountPrice($dis1, 6,  5.00);
			$myDBaseObj->SaveDiscountPrice($dis2, 6,  5.00);
			$myDBaseObj->SaveDiscountPrice($dis1, 7,  3.00);
			$myDBaseObj->SaveDiscountPrice($dis2, 7,  3.00);
			$myDBaseObj->SaveDiscountPrice($dis1, 8,  6.00);
			$myDBaseObj->SaveDiscountPrice($dis2, 9,  2.50);
			$myDBaseObj->SaveDiscountPrice($dis2, 10, 0.00);
			
			$seatingSettings = new stdClass();
		
			// Add Allocated Seating Samples				
			$seatingSettings->seatingRef = 'Main Theatre';
			$seatingSettings->seatingVenue = 'Stageshow Theatre';
			$seatingSettings->zoneRefs = array(
				'Stalls A',
				'Stalls B',
				'Sides',
				'Circle',
				'Images',
				'Legend',
				);
			$seatingSettings->zoneSpecs = array(	
				'[11.5]1.4-1.29,[12.4]2.3-2.30,[13.3]3.2-3.31,[14.2]4.1-6.32',
				'[17.2]7.1-8.3,[17.7]7.6-8.27,[17.31]7.30-8.32,[19.2]9.1-14.32', 
				'[dl2.4]15.1-17.7,[dr2.31]18.1-20.7',
				'[27.6]21.5-25.28',
				'[i1]1.9-9.26,[i2]17.5-18.6,[i2]17.29-18.30',
				'[10.2]N1-32,[11.1]R1-14,[11.34]R1-14,[dl1.4]R15-17,[dl2.1]N1-7,[dr1.31]R18-20,[dr2.34]N1-7,[27.5]R21-25,[27.30]R21-25',

				);
			$seatingSettings->seatingDecodeTable = 'stageshowgold-decodeseats-maintheatre.tab';
			$seatingSettings->zoneAllocSeats = array(
				'1', 
				'1', 
				'1', 
				'1',

				'1', 
				'1', 
				);
			$this->DeleteExistingSeatingLayout($seatingSettings->seatingRef);
			$this->myDBaseObj->CreateLayoutTemplate($seatingSettings);
				
			$seatingSettings->seatingRef = 'Studio';
			$seatingSettings->seatingVenue = 'Stageshow Theatre';
			$seatingSettings->zoneRefs = array(
				'Floor');
			$seatingSettings->zoneSpecs = array(
				'1.1-8.8aa,1.9-8.16');
			$seatingSettings->seatingDecodeTable = '';
			$seatingSettings->zoneAllocSeats = array(
				'1');
			$this->DeleteExistingSeatingLayout($seatingSettings->seatingRef);
			$this->myDBaseObj->CreateLayoutTemplate($seatingSettings);
			
			$seatingSettings->seatingRef = 'In The Round';
			$seatingSettings->seatingVenue = 'The Rondo';
			$seatingSettings->zoneRefs = array(
				'Front Row',
				'Budget Rows');
			$seatingSettings->zoneSpecs = array(
				'1.1-1.36', 
				'2.1-4.36');
			$seatingSettings->seatingDecodeTable = '';
			$seatingSettings->zoneAllocSeats = array(
				'1', 
				'0');
			$this->DeleteExistingSeatingLayout($seatingSettings->seatingRef);
			$this->myDBaseObj->CreateLayoutTemplate($seatingSettings);
			
			$seatingList = $this->myDBaseObj->GetSeatingList();
			foreach ($seatingList as $seating)
			{
				$this->seatingIDs[$seating->seatingRef] = $seating->seatingID;
			}	
			
			$seatingID = $this->seatingIDs['In The Round'];
			$htmlTemplateName = 'stageshowgold-template-InTheRound.html';
			$htmlTemplatePath = STAGESHOWLIB_DEFAULT_TEMPLATES_PATH.'html/'.$htmlTemplateName;
			$statusMsg = '';
			$this->myDBaseObj->ImportSeatingTemplateFromFile($htmlTemplatePath, $htmlTemplateName, $seatingID, $statusMsg);
				
			// Call CreateSample() in base class
			// FUNCTIONALITY: DBase - StageShow - Implement "Create Sample"
			$showName1 = "The Wordpress Show";

			// Sample dates to reflect current date/time
			$showTime1 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("+28 days"))." 20:00";
			$showTime2 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("+29 days"))." 20:00";
			$showTime3 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("+30 days"))." 14:30";
			$showTime4 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("+30 days"))." 20:00";
			// Populate table
			$this->sample_showID1 = $this->AddSampleShow($showName1);
			$statusMsg = '';
			// Populate performances table	  
			$perfCount = 4;
			if (defined('STAGESHOW_SAMPLE_PERFORMANCES_COUNT'))
				$perfCount = STAGESHOW_SAMPLE_PERFORMANCES_COUNT;
			$perfID1 = $perfCount >= 1 ? $this->AddSamplePerformance($statusMsg, $this->sample_showID1, $showTime1, "Day1Eve", 80) : -1;
			$perfID2 = $perfCount >= 2 ? $this->AddSamplePerformance($statusMsg, $this->sample_showID1, $showTime2, "Day2Eve", 60) : -1;
			$perfID3 = $perfCount >= 3 ? $this->AddSamplePerformance($statusMsg, $this->sample_showID1, $showTime3, "Day3Mat", 80) : -1;
			$perfID4 = $perfCount >= 4 ? $this->AddSamplePerformance($statusMsg, $this->sample_showID1, $showTime4, "Day3Eve", 60) : -1;
			if (($perfID1 == 0) ||($perfID2 == 0) || ($perfID3 == 0) || ($perfID4 == 0))
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Cannot Add Performances', 'stageshow').' - '.$statusMsg.'</p></div>');
				return;
			}
			
			if ($sampleDepth < 2)
			{
				// Populate prices table
				$this->priceID_S1_P1_ALL   = $this->AddSamplePrice('Day1Eve', 'All',   STAGESHOW_PRICE_S1_P1_ALL);
				$this->priceID_S1_P2_ADULT = $this->AddSamplePrice('Day2Eve', 'Adult', STAGESHOW_PRICE_S1_P2_ADULT);
				$this->priceID_S1_P3_ADULT = $this->AddSamplePrice('Day3Mat', 'Adult', STAGESHOW_PRICE_S1_P3_ADULT);
				$this->priceID_S1_P4_ALL   = $this->AddSamplePrice('Day3Eve', 'All',   STAGESHOW_PRICE_S1_P4_ALL);
				$this->priceID_S1_P2_CHILD = $this->AddSamplePrice('Day2Eve', 'Child', STAGESHOW_PRICE_S1_P2_CHILD);
				$this->priceID_S1_P3_CHILD = $this->AddSamplePrice('Day3Mat', 'Child', STAGESHOW_PRICE_S1_P3_CHILD);
			}
			
			if (!$this->isDbgOptionSet('Dev_NoSampleSales') && ($sampleDepth < 1))
			{
				// Add some ticket sales
				$saleTime1 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("-4 days"))." 17:32:47";
				$saleTime2 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("-3 days"))." 10:14:51";
				$saleEMail = 'other@someemail.co.zz';
				if (defined('STAGESHOW_SAMPLE_EMAIL'))
					$saleEMail = STAGESHOW_SAMPLE_EMAIL;
				$saleID = $this->AddSampleSale($saleTime1, 'A.N.', 'Other', $saleEMail, 12.00, 'SQP4KMTNIEXGS5ZBU', PAYMENT_API_SALESTATUS_COMPLETED,
					'1 The Street', 'Somewhere', 'Bigshire', 'BG1 5AT', 'UK', '01234 567890', true, 1.25);
				$this->AddSampleSaleItem($saleID, $this->priceID_S1_P3_CHILD, 4, STAGESHOW_PRICE_S1_P3_CHILD);
				$this->AddSampleSaleItem($saleID, $this->priceID_S1_P3_ADULT, 1, STAGESHOW_PRICE_S1_P3_ADULT);
				
				$saleEMail = 'mybrother@someemail.co.zz';
				if (defined('STAGESHOW_SAMPLE_EMAIL'))
					$saleEMail = STAGESHOW_SAMPLE_EMAIL;
				$total2 = (4 * STAGESHOW_PRICE_S1_P1_ALL);
				$saleID = $this->AddSampleSale($saleTime2, 'M.Y.', 'Brother', $saleEMail, $total2, '1S34QJHTK9AAQGGVG', PAYMENT_API_SALESTATUS_COMPLETED,
					'The Bungalow', 'Otherplace', 'Littleshire', 'LI1 9ZZ', 'UK');
				$this->AddSampleSaleItem($saleID, $this->priceID_S1_P1_ALL, 4, STAGESHOW_PRICE_S1_P1_ALL);
				
				$timeStamp = $this->Sample_strtotime("-4 hour");
				if (defined('STAGESHOW_EXTRA_SAMPLE_SALES'))
				{
					// Add a lot of ticket sales
					for ($sampleSaleNo = 1; $sampleSaleNo<=STAGESHOW_EXTRA_SAMPLE_SALES; $sampleSaleNo++)
					{
						$saleDate = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, $timeStamp);
						$saleFirstName = 'Sample'.$sampleSaleNo;
						$saleLastName = 'Buyer'.$sampleSaleNo;
						$saleEMail = 'extrasale'.$sampleSaleNo.'@sample.org.uk';
						$saleID = $this->AddSampleSale($saleDate, $saleFirstName, $saleLastName, $saleEMail, 12.50, 'TXNID_'.$sampleSaleNo, PAYMENT_API_SALESTATUS_COMPLETED,
						'Almost', 'Anywhere', 'Very Rural', 'Tinyshire', 'TN55 8XX', 'UK');
						$this->AddSampleSaleItem($saleID, $this->priceID_S1_P3_ADULT, 3, STAGESHOW_PRICE_S1_P3_ADULT);
						$timeStamp = $this->Sample_strtotime("-1 hour +7 seconds", $timeStamp);
					}
				}
			}

			$showName2 = "StageShow ROCKS!";
			
			// Sample dates to reflect current date/time
			$showTime2 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("-10 days"))." 20:00";
			
			// Populate table
			$showID2 = $this->AddSampleShow($showName2);
			
			// Populate performances table	    
			$perfID1 = $this->AddSamplePerformance($statusMsg, $showID2, $showTime2, "EveningPerf", 80);
			if ($perfID1 == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Cannot Add Performances', 'stageshow').' - '.$statusMsg.'</p></div>');
				return;
			}
			
			if ($sampleDepth < 2)
			{
				// Populate prices table
				$this->priceID_S2_P1_ADULT  = $this->AddSamplePrice('EveningPerf', 'Adult',  STAGESHOW_PRICE_S2_P1_ADULT);
				$this->priceID_S2_P1_CHILD  = $this->AddSamplePrice('EveningPerf', 'Child',  STAGESHOW_PRICE_S2_P1_CHILD);
				$this->priceID_S2_P1_FAMILY = $this->AddSamplePrice('EveningPerf', 'Family', STAGESHOW_PRICE_S2_P1_FAMILY, STAGESHOW_VISIBILITY_PUBLIC, 4);
				$this->priceID_S2_P1_STAFF  = $this->AddSamplePrice('EveningPerf', 'Staff',  STAGESHOW_PRICE_S2_P1_STAFF,  STAGESHOW_VISIBILITY_ADMIN, 1);
			}
			
			if (!$this->isDbgOptionSet('Dev_NoSampleSales') && ($sampleDepth < 1))
			{
				// Add some ticket sales
				$saleTime1 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, StageShowLibMigratePHPClass::Safe_strtotime("-2 days", $this->Sample_strtotime($showTime2)))." 11:09:22";
				$saleTime2 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, StageShowLibMigratePHPClass::Safe_strtotime("-1 days", $this->Sample_strtotime($showTime2)))." 14:27:09";
				
				$saleEMail = 'sample@extns.co.zz';
				if (defined('STAGESHOW_SAMPLE_EMAIL'))
					$saleEMail = STAGESHOW_SAMPLE_EMAIL;
				$total1 = (1 * STAGESHOW_PRICE_S2_P1_FAMILY)
				        + (1 * STAGESHOW_PRICE_S2_P1_CHILD);
				$saleID = $this->AddSampleSale($saleTime1, 'Another', 'Bloke', $saleEMail, $total1, 'GKTICJ4P9N9HB1KA2', PAYMENT_API_SALESTATUS_COMPLETED,
					'Castle Grand', 'Bigcity', 'Dyfbluedd', 'DY1 7ZZ', 'UK');
				$this->AddSampleSaleItem($saleID, $this->priceID_S2_P1_FAMILY, 1, STAGESHOW_PRICE_S2_P1_FAMILY);
				$this->AddSampleSaleItem($saleID, $this->priceID_S2_P1_CHILD, 1, STAGESHOW_PRICE_S2_P1_CHILD);
				
				// Add some ticket sales
				$saleEMail = 'me@selse.org.uk';
				if (defined('STAGESHOW_SAMPLE_EMAIL'))
					$saleEMail = STAGESHOW_SAMPLE_EMAIL;
				$total2 = (3 * STAGESHOW_PRICE_S1_P1_ALL)
				        + (4 * STAGESHOW_PRICE_S1_P2_ADULT)
				        + (2 * STAGESHOW_PRICE_S2_P1_ADULT)
				        + (1 * STAGESHOW_PRICE_S2_P1_CHILD);
				$saleID = $this->AddSampleSale($saleTime2, 'Somebody', 'Else', $saleEMail, $total2, 'V8RZIEQX1GLAG6OLZ', PAYMENT_API_SALESTATUS_COMPLETED,
					'Down and Out', 'Very Rural', 'Tinyshire', 'TN55 8XX', 'UK', '07879 12345678', true, 10.68);
				$this->AddSampleSaleItem($saleID, $this->priceID_S1_P1_ALL, 3, STAGESHOW_PRICE_S1_P1_ALL);
				$this->AddSampleSaleItem($saleID, $this->priceID_S1_P2_ADULT, 4, STAGESHOW_PRICE_S1_P2_ADULT);	
				$this->AddSampleSaleItem($saleID, $this->priceID_S2_P1_ADULT, 2, STAGESHOW_PRICE_S2_P1_ADULT);
				$this->AddSampleSaleItem($saleID, $this->priceID_S2_P1_CHILD, 1, STAGESHOW_PRICE_S2_P1_CHILD);
			}
			
			// Create a couple of price plans
			$this->planID1 = $this->AddSamplePlan('Matinee');
			if ($this->planID1 > 0)
			{
				$this->AddSamplePreset($this->planID1, 'Adult', 5.25);
				$this->AddSamplePreset($this->planID1, 'Child', 2.75);
			}
			
			$this->planID2 = $this->AddSamplePlan('Evening');
			if ($this->planID2 > 0)
			{
				$this->AddSamplePreset($this->planID2, 'Adult', 8.00);	
				$this->AddSamplePreset($this->planID2, 'Child', 5.50);	
				$this->AddSamplePreset($this->planID2, 'Staff', 3.50, STAGESHOW_VISIBILITY_ADMIN);	
			}

			$showTime5 = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("+31 days"))." 20:00";
			$perfID5 = $this->AddSamplePerformance($statusMsg, $this->sample_showID1, $showTime5, "Day4Eve", 60);
			if ($perfID5 == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Cannot Add Performances', 'stageshow').' - '.$statusMsg.'</p></div>');
				return;
			}
							
			if (!$this->isDbgOptionSet('Dev_NoSampleSales') && ($sampleDepth < 1))
			{
				// Add some ticket sales
				$saleTime = date(StageShowDBaseClass::STAGESHOW_DATE_FORMAT, $this->Sample_strtotime("-2 days"))." 23:07:19";
				$saleEMail = 'layout@nothinguseful.org.xx';
				if (defined('STAGESHOW_SAMPLE_EMAIL'))
					$saleEMail = STAGESHOW_SAMPLE_EMAIL;
				$total5 = (3 * STAGESHOW_PRICE_S1_P4_ALL) + STAGESHOW_PRICE_S1_P4_ZB_FAMILY;
				$saleID = $this->AddSampleSale($saleTime, 'Seating', 'Layout', $saleEMail, $total5, 'HET683HA6DNG89J61', PAYMENT_API_SALESTATUS_COMPLETED,
					'14 High Street', 'Downton', 'Devon', 'FX12 7YW', 'UK');
				for ($seatNo = 17, $loop = 1; $loop<=3; $seatNo++, $loop++)
				{
					$saleExtras['ticketSeat'] = '8_'.$seatNo;
					$this->AddSampleSaleItem($saleID, $this->priceID_S1_P4_ZB_ADULT, 1, STAGESHOW_PRICE_S1_P4_ZB_ADULT, $saleExtras);
				}				
				$saleExtras['ticketSeat'] = '8_'.$seatNo++.',8_'.$seatNo++.',8_'.$seatNo++.',8_'.$seatNo++;
				$this->AddSampleSaleItem($saleID, $this->priceID_S1_P4_ZB_FAMILY, 1, STAGESHOW_PRICE_S1_P4_ZB_FAMILY, $saleExtras);
			}
	   	}
		
		function isDbgOptionSet($optionID)
		{
			return $this->myDBaseObj->isDbgOptionSet($optionID);
		}
       
		function saveOptions()
		{
			return $this->myDBaseObj->saveOptions();
		}
		
		function Sample_strtotime($time, $now = '')
		{ 
			if ($now == '')
			{
				if (defined('STAGESHOW_SAMPLE_BASETIME'))
				{
					$now = StageShowLibMigratePHPClass::Safe_strtotime(STAGESHOW_SAMPLE_BASETIME);				
				}
				else
				{
					$now = time();				
				}				
			}
			
			return StageShowLibMigratePHPClass::Safe_strtotime($time, $now);
		}
		
		function AddSampleShow($showName, $showState = STAGESHOW_STATE_ACTIVE)
		{
			return $this->myDBaseObj->AddShow($showName, $showState);
		}
		
		function AddSamplePerformance(&$rtnMsg, $showID, $perfDateTime, $perfRef = '', $perfSeats = -1)
		{
			$perfID = $this->myDBaseObj->CreateNewPerformance($rtnMsg, $showID, $perfDateTime, $perfRef, $perfSeats);
			
			$this->perfIDs[$perfRef] = $perfID;
			
			if ($perfRef == 'Day3Eve')
			{
				// Set 'Day1Eve' Performance to "Main Theatre" seating
				$this->myDBaseObj->UpdateSeatingLayout($perfID, $this->seatingIDs['Main Theatre']);
			}
			
			if ($perfRef == 'Day4Eve')			
			{
				// Set 'EveningPerf' Performance to "In The Round" seating
				$this->myDBaseObj->UpdateSeatingLayout($perfID, $this->seatingIDs['In The Round']);
			}
						
			$this->samplePerfIDs[$perfRef] = $perfID;
				
			return $perfID;
		}
			
		function AddSamplePrice($perfRef, $priceType, $priceValue = STAGESHOW_PRICE_UNKNOWN, $visibility = STAGESHOW_VISIBILITY_PUBLIC, $seats = 1)
		{
			$priceID = 0;
			if ($perfRef == 'Day3Eve')
			{
				$zones = $this->myDBaseObj->GetSeatingLayoutByName('Main Theatre');
				$priceVal = 8.00;
				foreach($zones as $zone)
				{
					if ($zone->zoneRef == 'Circle')
					{
						$this->priceID_S1_P4_ZC_ALL = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'All', STAGESHOW_PRICE_S1_P4_ZC_ALL, STAGESHOW_VISIBILITY_PUBLIC);
					}
					
					if ($zone->zoneRef == 'Stalls A')
					{
						$this->priceID_S1_P4_ZA_GENERAL = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'General', STAGESHOW_PRICE_S1_P4_ZA_GENERAL, STAGESHOW_VISIBILITY_PUBLIC);
					}
					
					if ($zone->zoneRef == 'Stalls B')
					{
						$this->priceID_S1_P4_ZB_ADULT = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'Adult', STAGESHOW_PRICE_S1_P4_ZB_ADULT, STAGESHOW_VISIBILITY_PUBLIC);
						$priceID = $this->priceID_S1_P4_ZB_ADULT;

						$this->priceID_S1_P4_ZB_CHILD = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'Child', STAGESHOW_PRICE_S1_P4_ZB_CHILD, STAGESHOW_VISIBILITY_PUBLIC);

						$this->priceID_S1_P4_ZB_FAMILY = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'Family', STAGESHOW_PRICE_S1_P4_ZB_FAMILY, STAGESHOW_VISIBILITY_PUBLIC);
						$this->UpdatePriceNoOfSeats($this->priceID_S1_P4_ZB_FAMILY, 4);			
					}
					
					$priceVal -= 1.2;
				}
			}
			elseif ($perfRef == 'Day4Eve')
			{
				$zones = $this->myDBaseObj->GetSeatingLayoutByName('In The Round');
				$priceVal = 8.20;
				
				$zone = $zones[1];
				$this->priceID_S1_P5_ZA_ADULT = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'All', STAGESHOW_PRICE_S1_P5_Z2_ALL, STAGESHOW_VISIBILITY_PUBLIC);
				$this->myDBaseObj->SetZoneID($this->priceID_S1_P5_Z2_ADULT, $zone->zoneID);
				//$this->UpdatePriceType($this->priceID_S1_P5_ZA_ADULT, 'All');

				$zone = $zones[0];
				$this->priceID_S1_P5_ZA_CHILD = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'Child', STAGESHOW_PRICE_S1_P5_Z1_CHILD, STAGESHOW_VISIBILITY_PUBLIC);
				$this->myDBaseObj->SetZoneID($this->priceID_S1_P5_Z1_CHILD, $zone->zoneID);

				$this->priceID_S1_P5_ZA_ADULT = $this->AddSamplePriceAndZone($perfRef, $zone->zoneID, 'Adult', STAGESHOW_PRICE_S1_P5_Z1_ADULT, STAGESHOW_VISIBILITY_PUBLIC);
				$this->myDBaseObj->SetZoneID($this->priceID_S1_P5_Z1_ADULT, $zone->zoneID);
			}
			else
			{
				$priceID = $this->AddSampleBasePrice($perfRef, $priceType, $priceValue, $visibility, $seats);
				if ($seats != 1)
				{
					$this->UpdatePriceNoOfSeats($priceID, $seats);
				}
			}
			
			return $priceID;
		}
		
		function AddSampleBasePrice($perfRef, $priceType, $priceValue = STAGESHOW_PRICE_UNKNOWN, $visibility = STAGESHOW_VISIBILITY_PUBLIC)
		{
			$priceParams = new stdClass;
			$priceParams->priceType = $priceType;
			$priceParams->priceValue = $priceValue;
			if ($visibility != STAGESHOW_VISIBILITY_PUBLIC) 
			{
				$priceParams->priceVisibility = $visibility;
			}
			
			$perfID = $this->perfIDs[$perfRef];
			$priceID = $this->myDBaseObj->AddPrice($perfID, $priceParams);
			
			return $priceID;
		}
		
		function AddSampleSaleItem($saleID, $stockID, $qty, $paid, $saleExtras = array())
		{
			if (isset($this->discountedPrice) && isset($this->discountedPrice[$saleID]))
			{
				$paid = $this->discountedPrice[$saleID];
			}
			
			$orderID = $this->myDBaseObj->AddSaleItem($saleID, $stockID, $qty, $paid, $saleExtras);
						
			return $orderID;
		}
		
		function AddSamplePlan($planRef)
		{
			$planID = $this->myDBaseObj->AddPlan($planRef, '', -1);
			
			if (($planRef == 'Evening') && ($planID > 0))
			{
				// Set "Evening" price plan to "Main Theatre" seating
				$seatingID = $this->seatingIDs['Main Theatre'];
				$this->myDBaseObj->UpdatePlanSeating($planID, $seatingID);
			}
			
			return $planID;
		}
			
		function UpdatePriceNoOfSeats($priceID, $seats = 1)
		{
			$this->myDBaseObj->UpdatePrice($priceID, 'priceNoOfSeats', $seats);
		}
		
		function AddSamplePreset($planId, $priceType, $priceValue, $priceVisibility = STAGESHOW_VISIBILITY_PUBLIC)
		{
			$presetId = $this->myDBaseObj->AddPreset($planId, $priceType, $priceValue, $priceVisibility);

			if (isset($this->planID2) && ($planId == $this->planID2)) switch ($priceType)
			{
				case 'Adult':
					$this->myDBaseObj->UpdatePreset($presetId, 'priceZoneID', 1);
					break;
				case 'Child':
					$this->myDBaseObj->UpdatePreset($presetId, 'priceZoneID', 1);
					break;
				case 'Staff':
					$this->myDBaseObj->UpdatePreset($presetId, 'priceZoneID', 3);
					break;		
			}
			return $presetId;
		}
		
		function AddSamplePriceAndZone($perfRef, $zoneID, $priceType, $priceValue, $visibility, $seats = 1)
		{
			$samplePriceID = $this->AddSampleBasePrice($perfRef, $priceType, $priceValue, $visibility, $seats);
			$this->myDBaseObj->SetZoneID($samplePriceID, $zoneID);
			return $samplePriceID;
		}
		
		function DeleteExistingSeatingLayout($seatingRef)
		{
			$results = $this->myDBaseObj->GetSeatingLayoutByName($seatingRef);
			if (count($results) > 0)
			{
				$this->myDBaseObj->DeleteSeatingLayout($results[0]->seatingID);				
			}
		}								
				
		function AddSampleSale($saleDateTime, $saleFirstName, $saleLastName, $saleEMail, $salePaid, $saleTxnId, $saleStatus, $salePPStreet, $salePPCity, $salePPState, $salePPZip, $salePPCountry, $salePPPhone = '', $saleAddPostage = false, $saleDonation = 0)
		{			
			$discountCode = '';
			if (($saleFirstName == 'M.Y.') && ($saleLastName == 'Brother'))
			{
				$discountCode = 'DIS1a';
				$salePaid = $salePaid * STAGESHOW_PRICE_S1_P1_ALL_DIS1A / STAGESHOW_PRICE_S1_P1_ALL;
			}
						
			$saleID = parent::AddSampleSale($saleDateTime, $saleFirstName, $saleLastName, $saleEMail, $salePaid, $saleTxnId, $saleStatus, $salePPStreet, $salePPCity, $salePPState, $salePPZip, $salePPCountry, $salePPPhone, $saleAddPostage, $saleDonation);

			if ($discountCode != '')
			{
				$this->discountedPrice[$saleID] = STAGESHOW_PRICE_S1_P1_ALL_DIS1A;
				
				$sql  = 'UPDATE '.STAGESHOW_SALES_TABLE;
				$sql .= ' SET saleDiscountCode="'.esc_sql($discountCode).'"';
				$sql .= ' WHERE '.STAGESHOW_SALES_TABLE.'.saleID="'.$saleID.'"';
				$this->myDBaseObj->query($sql);	
			}

			return $saleID;
		}
		
		
	}
	
}









