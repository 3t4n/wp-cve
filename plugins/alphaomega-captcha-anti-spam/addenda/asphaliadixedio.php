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
	$num1 = mt_rand(1, 100);
	$num2 = mt_rand(1, 100);
	$iconafrasi = $num1 . ' + ' . $num2;
	$ergalio = getcwd() . '/grafistilo.ttf';
	$ergaliomegathos = 12;
	$ola = imagettfbbox($ergaliomegathos, 0, $ergalio, $iconafrasi);
	$fardoslexi = $ola[4] - $ola[6];
	$enarxix = mt_rand(5,$iconafarthos/2);
	if ($enarxix > ($iconafarthos-$fardoslexi-5)) {
	$enarxix = 3;
	}
	session_start();
	$_SESSION['aoakrivosidiolexi'] = '1';
	$_SESSION['aokrifoklidislexi'] = strval($num1 + $num2);
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
