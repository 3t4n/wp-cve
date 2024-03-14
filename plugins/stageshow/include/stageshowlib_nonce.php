<?php
/* 
Description: Core Library Database Access functions

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

if (!class_exists('StageShowLibNonce'))
{
	if (!defined('MINUTE_IN_SECONDS'))
	{
		define( 'MINUTE_IN_SECONDS', 60 );
		define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
		define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );		
	}
	
	class StageShowLibNonce // Define class
	{
		static function GetStageShowLibNonce($action = -1)
		{
			$stringToHash = '';
			return self::GetStageShowLibNonceEx($action, $stringToHash);
		}
		
		static function GetStageShowLibNonceEx($action, &$stringToHash)
		{
			$uid = StageShowLibMigratePHPClass::Safe_str_replace("\\","", StageShowLibMigratePHPClass::Safe_str_replace("/","",__FILE__));
			$token = ''; // SID;
				
			$nonce_life = DAY_IN_SECONDS;
			$i = ceil(time() / ( $nonce_life / 2 ));

			$stringToHash = $i . '|' . $action . '|' . $uid . '|' . $token;
			$stringToHash = $i . '|' . $action . '|' . $uid . '|' . $token . '|'. NONCE_KEY;

			$localNOnce = md5($stringToHash);
			$localNOnce = StageShowLibMigratePHPClass::Safe_substr($localNOnce, -12, 10);
			
			return $localNOnce;
		}
		
	}
}

?>