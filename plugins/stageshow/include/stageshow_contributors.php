<?php

if (!class_exists('StageShowContributorsClass')) 
{
	class StageShowContributorsClass
	{
		static function GetContributors()
		{
			// Array of StageShow Contributors
			// The array that follows is an array of comma separated entries
			// Format: [Name],[Contribution],[URL]
			$conDefs = array(
				'TengYong Ng,			Date & Time Picker,		http://www.rainforestnet.com/datetimepicker/datetimepicker.htm',
				'David Tufts,			Barcode Generator,   	http://davidscotttufts.com/2009/03/31/how-to-create-barcodes-in-php/',
				'Deltalab,				QR Code Generator,   	http://phpqrcode.sourceforge.net/',
				'Nicholas Collinson,	French Translation, 	http://collinson.fr/web',
				'Ogi Djuraskovic,		Serbian Translation,	http://firstsiteguide.com',
				'Andrew Kurtis,			Spanish Translation, 	',
			);
			
			$contributorsList = array();
			foreach ($conDefs as $conDef)
			{
				$conEntries = explode(',', $conDef);
				
				$url = StageShowLibMigratePHPClass::Safe_trim($conEntries[2]);
				if ($url == '')
				{
					$url = 'n/a';
				}
				else
				{
					$url = "<a href=\"$url\" target=\"_blank\">$url</a>";
				}
				
				$ackEntry = new stdClass();
				$ackEntry->name = StageShowLibMigratePHPClass::Safe_trim($conEntries[0]);
				$ackEntry->contribution = StageShowLibMigratePHPClass::Safe_trim($conEntries[1]);
				$ackEntry->url = $url;
				
				$contributorsList[] = $ackEntry;
			}
			
			return $contributorsList;
		}
	}
}

?>