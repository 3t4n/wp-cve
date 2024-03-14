<?php

function StageShowLib_ITNTestLogging($filename)
{
	$IPNRxdMsg  = 'ITNReq at ' . date(DATE_RFC822) . "\n";
	$IPNRxdMsg .= 'Params: ' . print_r($_REQUEST, true) . "\n";

	$IPNRxdMsg  = StageShowLibMigratePHPClass::Safe_str_replace("\n", "<br>\n", $IPNRxdMsg);

	StageShowLib_ITNTestLogToFile($filename, $IPNRxdMsg);
}

function StageShowLib_ITNTestLogToFile($filename, $IPNRxdMsg)
{
	$logFilePath = WP_CONTENT_DIR.'/uploads/logs';

	$perms = fileperms($logFilePath);
	//StageShowLibEscapingClass::Safe_EchoHTML("perms: ".sprintf('0%o', $perms)." <br>\n");
	if (($perms & 0077) != 0)	
	{
		$reqPerms = 0700;
		$rtnVal = chmod($logFilePath, $reqPerms);
	//	StageShowLibEscapingClass::Safe_EchoHTML("perms Updated To: ".sprintf('0%o', $reqPerms)." <br>\n");
	//	StageShowLibEscapingClass::Safe_EchoHTML("rtnVal: $rtnVal <br>\n");
	}

	$logFilePath .= '/'.$filename.'_'.date('Ymd').'.log';
	
	$IPNRxdMsg .= 'logFilePath: ' . $logFilePath . "<br>\n";
	$IPNRxdMsg .= "\n";

	$logFile = fopen($logFilePath, 'ab');
	
	fwrite($logFile, $IPNRxdMsg, StageShowLibMigratePHPClass::Safe_strlen($IPNRxdMsg));
	fclose($logFile);
}

StageShowLib_ITNTestLogging('ITNCalls');

