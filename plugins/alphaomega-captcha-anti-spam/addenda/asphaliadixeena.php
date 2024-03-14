<?php

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
	$numxers = 6;
	$aovitadiolexi = array();
	$aovitadiolexi = array('2', '3', '4', '5', '6', '7', '8', '9', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');
	for ( $i = 0; $i < $numxers; $i++ ) {
		shuffle($aovitadiolexi);
		$klidi .= $aovitadiolexi[mt_rand(0, count($aovitadiolexi) - 1)];	
	}
	$iconafrasi = $klidi;
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
	$_SESSION['aoakrivosidiolexi'] = '1';
	$_SESSION['aokrifoklidislexi'] = $klidi;
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
