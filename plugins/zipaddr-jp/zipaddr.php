<?php
if( !defined('ABSPATH') ) exit;
function zipaddr_jp_change($output, $opt=""){
	$flds= zipaddrjp_fld(); foreach($flds as $i => $key){$keys=zipaddr_SYS.$key; $$keys="";}
	$param= unserialize( get_option(zipaddr_DEFINE) ); // get定義情報
	foreach((array)$param as $key => $data){
		$da= ($data=="&nbsp;") ?  $data : htmlspecialchars($data,ENT_QUOTES,'UTF-8');
		$$key= $da;
	}
	$sysid= array();
	$sysid[1]= "ContactForm7,contactform7";
	$sysid[2]= "MWWPForm,    mwwpform";
	$sysid[3]= "TrustForm,   trustform";
	$sysid[4]= "NinjaForms,  ninjaforms";
	$sysid[5]= "WP-Members,  wpmembers";
	$sysid[6]= "WPForms,     wpforms";
	$sysid[7]= "VisualFB,    visualformb";
	$sysid[8]= "WooCommerce, woocommerce";
	$sysid[9]= "Welcart2,    welcart2";
	$sysid[10]="MailformPro, mailformpro";
	$sysid[11]="SnowMonkeyForm,snowmonkeyform";
	$sysid[12]="TieredWorks, tieredworks";
	$sysid[13]="Forminator,  forminator";
	$sysid[14]="booking-package,bookingpackage";
	$sysid[99]="###other###, tricks";
//
	$contf7= strpos($output, 'wpcf7-form');       //Contact Form 7
	$mwform= strpos($output, 'mw_wp_form_');      //MW WP Form
	$trustf= strpos($output, '#trust-form');      //Trust Form
	$ninjaf= strpos($output, 'ninja-forms-');     //Ninja Forms
	$wpmem=  strpos($output, 'id="wpmem_reg"');   //WP-Members
	$wpfms=  strpos($output, 'id="wpforms-form-');//WPForms
	$visufb= strpos($output, 'vfb-form-');        //Visual Forms Builder
	$woocmb= strpos($output, 'woocommerce-billing'); //Woo Commerce
	$woocms= strpos($output, 'woocommerce-shipping');//Woo Commerce
	$welcart=strpos($output, '[zipcode]');        //Welcart
	$mailpro=strpos($output, 'id="mailformpro"'); //Mailform Pro
	$mailfor=strpos($output, "mfpc('mailform'");  //Mailform
	$snowmon=strpos($output, 'snow-monkey-form'); //SnowMonkeyForm
	$tieredw=strpos($output, 'SF-contact');       //TieredWorks
	$formina=strpos($output, 'forminator-label'); //Forminator
	$bookpak=strpos($output, 'id="booking-package"'); //booking-package
	$yubin=  strpos($output, '郵便番号');
//フォームの自動判定
	$sid= "";
		 if( $contf7 !==false ) $sid= 1;
	else if( $mwform !==false ) $sid= 2;
	else if( $trustf !==false ) $sid= 3;
	else if( $ninjaf !==false ){$sid= 4; $sys_dyna="1";}
	else if( $wpmem  !==false ){$sid= 5; $sys_dyna="1";}
	else if( $wpfms  !==false ) $sid= 6;
	else if( $visufb !==false ) $sid= 7;
	else if( $woocmb !==false || $woocms!==false ) $sid= 8;
	else if( $welcart!==false ) $sid= 9;
	else if( $mailpro!==false || $mailfor!==false) $sid= 10;
	else if( $snowmon!==false ) $sid= 11;
	else if( $tieredw!==false ) $sid= 12;
	else if( $formina!==false ) $sid= 13;
	else if( $bookpak!==false ){$sid= 14; $sys_dyna="1";}
	else if( empty($sys_syid))  $sid= 99;
//
	if( $sid < 99 ){;}                            //自動判定
	else
	if( strpos($output,'zip')!==false || strpos($output,'postc')!==false || $sys_drct!="" ){;} //kword
	else
	if( ($wpfms!==false || $visufb!==false) && $yubin!==false ){;}
	else  return $output;
	$apid= "";
	if( !empty($sid) ) {list($sys_syid,$apid)=explode(",", $sysid[$sid]); $apid=trim($apid);}

	$jsfile= '<script type="text/javascript" charset="UTF-8"';
	$http="http"; $lcpath="";                     // http,  // local_path
if(isset($_SERVER['HTTPS'])) {$http=(empty($_SERVER['HTTPS'])||$_SERVER['HTTPS']=='off')? 'http':'https';}
	$pth= isset($_SERVER['SERVER_NAME']) ?  $http.'://'.$_SERVER['SERVER_NAME'] : ""; // host用
	if( empty($sys_site) ) $sys_site= "4";        // パラメータの初期変換
	if( empty($sys_keta) ) $sys_keta= "7";
//	if( $sys_site=="1"  || $opt!="" ) $sys_site= "4"; // welcartはzipaddrx.js
	if( $sys_keta < 5 ||  7 < $sys_keta ) $sys_keta= "7";
	if( $sys_pfon < 9 || 25 < $sys_pfon ) $sys_pfon= "";
	if( $sys_sfon < 9 || 25 < $sys_sfon ) $sys_sfon= "";
	if( $sys_site == "2" || $sys_site == "3" ){
		$lcpath= $pth.'/css/zipaddr.css';
		$wk= @file_get_contents($lcpath);
		$wk2= strpos($wk,"autozip");
		if( empty($wk) || $wk2===false ) $lcpath= zipaddr_git.'zipaddr.css'; // 定義がなければ補う
	 }                                   // 変換の判定開始
                                         $uls= zipaddr_COM.'js/zipaddr7.js';
 	 if( $sys_site=="2"||$sys_site=="3") $uls= zipaddr_git.   'zipaddr3.js';
//se if( $sys_site == "3" )              $uls= zipaddr_git.   'zipaddr30.js';
else if( $sys_site == "4" )              $uls= zipaddr_git.   'zipaddrx.js';
else if( $sys_site == "5" )              $uls= zipaddr_git.   'zipaddra.js';
//	$pre=($sys_site=="4") ?  "D." : "ZP.";        // prefix
	$pre= "ZP.";                                  // prefix
//モジュール・ファイル生成
	$js = $jsfile.' src="'.$uls.'?v='.zipaddr_VERS.'"></script>';
//オプション・パラメータ生成
	$js.= $jsfile.">function zipaddr_ownb(){" .$pre."dli='".$sys_deli."';";
//	$js.= empty($ninjaf) ? $pre."wp='1';" : $pre."wp='2';";
	$js.= $pre."wp='1';";
	$js.= $pre."uver='".get_bloginfo('version')."';";
//	$js.= $pre."min=".$sys_keta.";"  .$pre."uver='".get_bloginfo('version')."';";
//	if( $opt != "" )    $js.= $pre."welcart='1';";
	if( $sys_tate!="" ) $js.= $pre."top=".    $sys_tate. ";";
	if( $sys_yoko!="" ) $js.= $pre."left=".   $sys_yoko. ";";
	if( $sys_pfon!="" ) $js.= $pre."pfon=".   $sys_pfon. ";";
	if( $sys_sfon!="" ) $js.= $pre."sfon=".   $sys_sfon. ";";
	if( $sys_focs!="" ) $js.= $pre."focus='". $sys_focs."';";
	if( $sys_syid!="" ) $js.= $pre."sysid='". $sys_syid."';";
	if( $sys_plce!="" ) $js.= $pre."holder='".$sys_plce."';";
	if( $sys_dyna!="" ) $js.= $pre."dyna='".  $sys_dyna."';";
	if( $sys_site=="3") $js.= $pre."min='7';";
	if( defined('zipaddr_IDENT') && zipaddr_IDENT == "3" ) $js.= $pre."usces='1';";
	$js.= '}</script>';
//pmファイル生成
	if( !empty($apid) ){
		$js.= $jsfile.' src="'.zipaddr_git.$apid.'.js"></script>';
	}
//stylesheetファイル生成
	if( $sys_site=="2" || $sys_site=="3" ) $js.= '<link rel="stylesheet" href="'.$lcpath.'" />'; // style
//オウンコード設定パラメータ生成
//	if( $sys_parm != "" ){
		$skip1= "skip=1";
		$syskip= strpos($sys_parm, $skip1);
		if( $syskip === false && $sys_gide == "1" ){
			if( !empty($sys_parm) ) $sys_parm.= "|";
			$sys_parm.= $skip1;
		}
		$sys_parm= str_replace("|", ",", $sys_parm);
		if( !empty($sys_parm) ) $js.= '<input type="hidden" name="zipaddr_param" id="zipaddr_param" value="'.$sys_parm.'">';
//	}
	if( !empty($opt) || $ninjaf!==false || $visufb!==false ) {$ans= $output.$js;} //Ninja Forms、Visual Forms Builder
	else
	if( !empty($sys_drct) ){                      // 無条件挿入
		$ans= $output;
		$urlh= isset($_SERVER['REQUEST_URI']) ?  $_SERVER['REQUEST_URI'] : "";
		$wk= explode(";", $sys_drct);
		foreach($wk as $ka => $da){
			if( strpos($urlh,$da)!==false ) {$ans=$output.$js; break;}
		}
	}
	else
		$ans= str_ireplace("<form", $js."<form", $output);
	return $ans;
}
function zipaddr_jp_usces($formtag,$type,$data) {return zipaddr_jp_change($formtag,"1");}
function zipaddr_jp_welcart($script) {return $script;
	$keywd1="if(delivery_days[selected]";
$addon="
if(typeof Zip.welorder==='function'){
	var wk1= $('#delivery_country').val();
	var wk2= $('#delivery_pref').val();
	if( wk1!='' && wk2!='' ) {delivery_country=wk1; delivery_pref=wk2;}
}
";
	$wk0= strpos($script,$keywd1);
	if( $wk0!==false ) {$script= str_replace($keywd1, $addon.$keywd1, $script);}
	return $script;
}
?>
