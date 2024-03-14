<?php

$glossaenabubi = ' ';
$glossadiobubi = ' ';
$glossatriabubi = ' ';
$glossateserabubi = ' ';
$glossapentebubi = ' ';
$aostichiaoa['glossa'] = '1';
/*
if ( $aostichiaoa['glossa'] === '1' ) {
	$glossaenabubi = ' checked="checked" ';
} elseif ( $aostichiaoa['glossa'] === '2' ) {
	$glossadiobubi = ' checked="checked" ';
} elseif ( $aostichiaoa['glossa'] === '3' ) {
	$glossatriabubi = ' checked="checked" ';
} elseif ( $aostichiaoa['glossa'] === '4' ) {
	$glossateserabubi = ' checked="checked" ';
} elseif ( $aostichiaoa['glossa'] === '5' ) {
	$glossapentebubi = ' checked="checked" ';
}
*/
$tichoenakubi = ' ';
$tichodiokubi = ' ';
$tichotriakubi = ' ';
$tichoteserakubi = ' ';
$tichopentekubi = ' ';
$tichoexikubi = ' ';
$tichoeftakubi = ' ';
$tichoochtokubi = ' ';
$tichoeneakubi = ' ';
$tichodecakubi = ' ';
$tichoendecakubi = ' ';
$tichododecakubi = ' ';
$tichodecatriakubi = ' ';
$tichodecateserakubi = ' ';
$tichodecapentekubi = ' ';
$tichodecaexikubi = ' ';
$tichodecaeftakubi = ' ';
$tichodecaochtokubi = ' ';
$tichodecaeneakubi = ' ';
if ( $aostichiaoa['titichonadixis'] === '1' ) {
	$tichoenakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '2' ) {
	$tichodiokubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '3' ) {
	$tichotriakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '4' ) {
	$tichoteserakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '5' ) {
	$tichopentekubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '6' ) {
	$tichoexikubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '7' ) {
	$tichoeftakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '8' ) {
	$tichoochtokubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '9' ) {
	$tichoeneakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '10' ) {
	$tichodecakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '11' ) {
	$tichoendecakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '12' ) {
	$tichododecakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '13' ) {
	$tichodecatriakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '14' ) {
	$tichodecateserakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '15' ) {
	$tichodecapentekubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '16' ) {
	$tichodecaexikubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '17' ) {
	$tichodecaeftakubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '18' ) {
	$tichodecaochtokubi = ' checked="checked" ';
} elseif ( $aostichiaoa['titichonadixis'] === '19' ) {
	$tichodecaeneakubi = ' checked="checked" ';
}
$tominimatichotopos = ' ';
$tostichiotichotopos = ' ';
if ( $aostichiaoa['dixeminimaticho'] === '1' ) {
	$tominimatichotopos = ' checked="checked" ';
}
if ( $aostichiaoa['dixestichioticho'] === '1' ) {
	$tostichiotichotopos = ' checked="checked" ';
}
$tichotoposepano = ' ';
$tichotoposmesi = ' ';
$tichotoposkato = ' ';
$stichiotoposepano = ' ';
$stichiotoposkato = ' ';
if ( $aostichiaoa['minimatichotopos'] === '1' ) {
	$tichotoposepano = ' checked="checked" ';
} elseif ( $aostichiaoa['minimatichotopos'] === '2' ) {
	$tichotoposmesi = ' checked="checked" ';
} elseif ( $aostichiaoa['minimatichotopos'] === '3' ) {
	$tichotoposkato = ' checked="checked" ';
}
if ( $aostichiaoa['stichiotichotopos'] === '2' ) {
	$stichiotoposkato = ' checked="checked" ';
}
$stragiseonomakubi = ' ';
$stragisediefthinsikubi = ' ';
$stragisehitaftafpikubi = ' ';
$stragiseminimakubi = ' ';
if ( $aostichiaoa['stragiseonoma'] === '1' ) {
	$stragiseonomakubi = ' checked="checked" ';
}
if ( $aostichiaoa['stragisediefthinsi'] === '1' ) {
	$stragisediefthinsikubi = ' checked="checked" ';
}
if ( $aostichiaoa['stragisehataftafpi'] === '1' ) {
	$stragisehitaftafpikubi = ' checked="checked" ';
}
if ( $aostichiaoa['stragiseminima'] === '1' ) {
	$stragiseminimakubi = ' checked="checked" ';
}
if ( $aostichiaoa['stragisekabosaminima'] === '0' ) {
	$tutostragismaparakalo = $aostichiaoa['stragiseklisto'];
} else {
	if ( $aostichiaoa['stragisemedikomas'] === '1' ) {
		$tutostragismaparakalo = str_replace('＃＃＃＃', PHP_EOL, $aostichiaoa['dikomasstragisekolona']);
	} else {
		$tutostragismaparakalo = str_replace("\\", "", str_replace('＃＃＃＃', PHP_EOL, $aostichiaoa['dikosustragisekolona']));
	}
}
$dikomasustragismaparakalokubipatisma = str_replace("＃＃＃＃", "\\n", $aostichiaoa['dikomasstragisekolona']);
if ( $aostichiaoa['dikosustragisekolona'] === '' ) {
	$dixedikosustragismakolonakubipatisma =  $aostichiaoa['dikosustragisekolonadenechitipotaminima'];				
} else {
	$dixedikosustragismakolonakubipatisma =  str_replace("\"", "&quot;", str_replace('＃＃＃＃', "\\n", $aostichiaoa['dikosustragisekolona']));
}
if ( $aostichiaoa['nadixisdikomaskakofaniminimaapantisi'] === '1' ) {
	$kakofaniminimaapantasititlo = $aostichiaoa['kakofaniminimaapantasititlo'];
	$kakofaniminimaapantasiminima = $aostichiaoa['kakofaniminimaapantasiminima'];
} else {
	$kakofaniminimaapantasititlo = stripslashes($aostichiaoa['dikosukakofaniminimaapantasititlo']);
	$kakofaniminimaapantasiminima = stripslashes($aostichiaoa['dikosukakofaniminimaapantasiminima']);
}
if ( $aostichiaoa['stepstragisekolonatoraiine'] !== 'None' ) { 

	$stepstragiseklisestragismasvisekoubi = '';
	$stepstragiseklisestragisma = $aostichiaoa['stragiseklistopre'];

} else {

	$stepstragiseklisestragismasvisekoubi = $aostichiaoa['stepstragiseklisestragismasvisekoubi'];

}

$sideroonomaklisto = explode(' ', trim($aostichiaoa['michanionoma']));
$sideroonoma = $sideroonomaklisto[0];

?>
