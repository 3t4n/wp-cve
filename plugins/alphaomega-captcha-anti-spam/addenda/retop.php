<?php

if ( get_option( 'aostichia' ) !== FALSE ) {

	$aostichiaoa = get_option( 'aostichia' );

	if ( !array_key_exists('posakakominimaechoume', $aostichiaoa) || !array_key_exists('posakakominimaechoumeskini', $aostichiaoa) ) {
		/* na min alaxis auto edo - na anixis kenurio ean topos yiati edo echoume to sinolo tous kakes minimata */
		$aostichiaoa['posakakominimaechoume']  = '0' ;
		$aostichiaoa['posakakominimaechoumeskini']  = '0' ;
		$aostichiaoa['stepposakakominimadeftero'] = 'MESSAGES';
		$aostichiaoa['dikomasstragisekolona'] = 'lose weight＃＃＃＃buy this now＃＃＃＃x-ray glasses＃＃＃＃work from home＃＃＃＃sex＃＃＃＃viagra＃＃＃＃greed is good＃＃＃＃hypnotize your boss';
		
	}

	if ( strpos($aostichiaoa['dikomasstragisekolona'],"＃") === FALSE ) {
	
	$aostichiaoa['dikomasstragisekolona'] = 'lose weight＃＃＃＃buy this now＃＃＃＃x-ray glasses＃＃＃＃work from home＃＃＃＃sex＃＃＃＃viagra＃＃＃＃greed is good＃＃＃＃hypnotize your boss';

	}

	if ( !array_key_exists('stepstragisekolonatoraiine', $aostichiaoa) ) {

		$aostichiaoa['stepstragisekolonatoraiine']  = 'Default Spam Filter List' ;
		
	}

	if ( strpos($aostichiaoa['tichoaristera'],"Please Enter Security Code") !== FALSE ) {
	
	$aostichiaoa['tichoaristera'] = '<a href="https://wordpress.org/extend/plugins/alphaomega-captcha-anti-spam/" title=" Checkout the AlphaOmega Captcha Plugin at WordPress.org " style="text-decoration:none;" target="_blank"><div style="padding-top:25px;padding-bottom:2px;font-size:1.0em;font-weight:normal;color:#0073aa;display:block;">AlphaOmega Captcha Classica &nbsp;&ndash;&nbsp; Enter Security Code</div></a>';

	}

	$aostichiaoa['aostichiaarithmo']  = '3.3';
	$aostichiaoa['michanionoma'] = ' AlphaOmega Captcha ';
	$aostichiaoa['zimari'] = 'addenda/updatelogs/3.0/2.8/2.5/2.0/1.7/loukumi.png';
	$aostichiaoa['stragiseklistopre'] = 'Inactivate Spam Filter';
	$aostichiaoa['stepstragiseklisestragismasvisekoubi'] = 'disabled';
	update_option( 'aostichia', $aostichiaoa );

} 

?>
