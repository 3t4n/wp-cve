<?php 
if ( ! function_exists('ipcmp')):
/**
 * Acts like strcmp, but for strings which represent ip addresses
 * @param string|int $aip
 * @param string|int $bip
 * @return int 
 */
function ipcmp($aip, $bip) {
	is_string($aip) and $aip = ip2long($aip);
	is_string($bip) and $bip = ip2long($bip);
	return strcmp(vsprintf('%u', $aip), vsprintf('%u', $bip));
}
endif;