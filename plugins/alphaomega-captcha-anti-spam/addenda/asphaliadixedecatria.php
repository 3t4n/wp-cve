<?php

	include("decatriaaokolonalexi.php");
	$pisoicona = getcwd() . '/lexitikokabe.jpg';
	$edoicona = imagecreatefromjpeg($pisoicona);
	$megalitero = 255;
	$iconaipsos = imagesy($edoicona);
	$iconafarthos = imagesx($edoicona);
        $kokino = mt_rand(67,$megalitero);
	$prasino = mt_rand(67,$megalitero);
	$pmle = mt_rand(67,$megalitero);
 	$lexihroma = imagecolorallocate($edoicona,$megalitero-$kokino,$megalitero-$prasino,$megalitero-$pmle);
	$klidi = '';
	$akrivosdixelexi = '';
	shuffle($aokolonalexi);
	$aodialexelexi = explode('＃＃＃＃', trim(base64_decode(convert_uudecode(urldecode(stripslashes(trim($aokolonalexi[mt_rand(0, count($aokolonalexi) - 1)])))))));
	for ( $steuclidewy = 0; $steuclidewy < strlen($aodialexelexi[0]); $steuclidewy++ ) {
		$fatrand = mt_rand(0, 1);
		if ( $fatrand == 0 ) {
			$akrivosdixelexi .= strtolower($aodialexelexi[0][$steuclidewy]);
		} else {
			$akrivosdixelexi .= strtoupper($aodialexelexi[0][$steuclidewy]);
		}
	}
	$iconafrasi = $akrivosdixelexi;
	$ergalio = getcwd() . '/grafistilo.ttf';
	$ergaliomegathos = 12;
	$ola = imagettfbbox($ergaliomegathos, 0, $ergalio, $iconafrasi);
	$fardoslexi = $ola[4] - $ola[6];
	$enarxix = mt_rand(5,$iconafarthos/2);
	if ($enarxix > ($iconafarthos-$fardoslexi-5))
	{
	$enarxix = 3;
	}
	session_start();
	$_SESSION['aoakrivosidiolexi'] = '0';
	$_SESSION['aokrifoklidislexi'] = trim($aodialexelexi[1]);	
	$leximegathos = 12;
	$lexiarhaio = getcwd() . '/grafistilo.ttf';
	$apotelisma = imagettftext($edoicona, $leximegathos, mt_rand(-4,4), $enarxix, mt_rand($leximegathos,$iconaipsos-5), $lexihroma, $lexiarhaio, $iconafrasi);
	ob_clean();
	header("Content-Type:image/png");
	header("Content-Disposition:inline ; filename=daimage.png");
	imagepng($edoicona);
	imagedestroy($edoicona);
	ob_end_flush();

?>
