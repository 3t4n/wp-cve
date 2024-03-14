<?php
/* 
Description: 
 
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

if (!class_exists('StageShowZonesDBaseClass')) 
{
	class StageShowZonesDBaseClass
	{
		// TODO - All Seating Zone Function below are also defined in stageshow_dbase_api.php ....
		static function SeatsToHTML($value)
		{
			return StageShowLibMigratePHPClass::Safe_str_replace(',', '<br>', $value);
		}
		
		static function DecodeSeatsList($myDBaseObj, $value, $seatingID)
		{
			if (($seatingID != 0) && ($value != ''))
			{
				$decode = self::GetSeatingDecodeDef($myDBaseObj, $seatingID);
				$value = self::DecodeSeatsFromDefs($myDBaseObj, $value, $decode);
			}
			
			return $value;
		}
		
		static function DecodeSeatsFromDefs($myDBaseObj, $value, $decode)
		{
			$seatsList = explode(',', $value);
			$value = '';
			$delim = '';
			foreach ($seatsList as $seatDef)
			{
				$seatCoords = explode('_', $seatDef);
				$rowNo = $seatCoords[0];
				$seatNo = $seatCoords[1];
				if (isset($decode->rows[$rowNo]))
				{
					$rowNo = $decode->rows[$rowNo];
				}
				else
				{
					$rowNo .= '_';
				}
				if (isset($decode->seats[$seatNo]))
				{
					$seatNo = $decode->seats[$seatNo];
				}
				
				$value .= $delim.$rowNo.$seatNo;		
				$delim = ',';			
			}			
			
			return $value;
		}
		
		static function GetSeatingDecodeDef($myDBaseObj, $seatingID)
		{
			static $lastSeatingID = 0;
			static $decode = null;
			
			if ($seatingID == $lastSeatingID)
			{
				return $decode;
			}
			
			$lastSeatingID = $seatingID;
			$decode = null;
			if ($seatingID > 0)
			{
				$results = self::GetSeatingTemplate($myDBaseObj, $seatingID, 'seatDecodesObj');	
				if ((count($results) > 0) && (StageShowLibMigratePHPClass::Safe_strlen($results[0]->seatDecodesObj) > 0))
				{
					$decode = unserialize($results[0]->seatDecodesObj);
				}
			}
			
			return $decode;
		}

		static function GetSeatingTemplate($myDBaseObj, $seatingID, $fieldsList = '*')
		{
			$sql  = "SELECT $fieldsList FROM ".STAGESHOW_SEATING_TABLE;			
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID="'.$seatingID.'"';						
			
			$results = $myDBaseObj->get_results($sql);
			
			return $results;
		}

		static function GetSeatingZone($myDBaseObj, $zoneID)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_ZONES_TABLE;			
			$sql .= ' WHERE '.STAGESHOW_ZONES_TABLE.'.zoneID="'.$zoneID.'"';					
			
			$zonesArray = $myDBaseObj->get_results($sql);
			
			return count($zonesArray) > 0 ? $zonesArray[0] : null;
		}
				
	}
}

?>