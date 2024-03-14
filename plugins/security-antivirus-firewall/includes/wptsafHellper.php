<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

/* need  for support php 5.2 */
/*if (!function_exists('get_called_class')) {
	function get_called_class(){
	    $arr = array(); 
	    $arrTraces = debug_backtrace();
	    foreach ($arrTraces as $arrTrace){
	       if(!array_key_exists("class", $arrTrace)) continue;
	       if(count($arr)==0) $arr[] = $arrTrace['class'];
	       else if(get_parent_class($arrTrace['class'])==end($arr)) $arr[] = $arrTrace['class'];
	    }
	    return end($arr);
	}
}*/

class wpToolsSAFHelperClass{

	static function getTypeLabel($type){
		$returnValue = '';
		switch ($type) {
			case 'info':
					$returnValue = 'Notification';
				break;
			case 'danger':
					$returnValue = 'Error'; 
				break;
			
			default:
				$returnValue = $type;
				break;
		}
		return $returnValue;
	}


	static function getKeyFile(){
		$proPath 	= '';
		$key_dir  	= 'wptoolskeysaf';
		$key_file 	= 'wptoolskeysaf.php';
		$proPath = WPTSAF_DIR.$key_file;
		if( file_exists($proPath) ) return $proPath;
		for($i=-1;$i<6;$i++){ 
			$proPath = WP_PLUGIN_DIR.'/'.$key_dir.($i!=-1?'-'.$i:'').'/'.$key_file;
			if ( file_exists($proPath) ) return $proPath;
		}
		for($i=0;$i<6;$i++){ 
			$proPath = WPTSAF_DIR.'/'.$key_dir.$i.'/'.$key_file;
			if ( file_exists($proPath) ) return $proPath;
		}
		return false;
	}

	static function getIpInfo( $ipAdress, $needReturn = 0 ){
		$html = '';
		
		if( $ipAdress && $ipAdress!='127.0.0.1' ){ 
			$html .= '<a href="http://wptools.co/whois.html?ip='.$ipAdress.'" class="wpsaf_iplink" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> '.$ipAdress.'</a>';	
		} else {
			$html .= $ipAdress;
		}

		if($needReturn) return $html;
			else echo $html;
	}

	static function getCheckIcon( $isEnabled ){
		if($isEnabled){ 
			echo '<i class="fa fa-check-circle fa-lg text-success"></i>';
		} else {
			echo '<i class="fa  fa-times-circle fa-lg text-danger"></i>';
		}
		echo '&nbsp;';
	}

	static function getAccordion( $tabs = array() ){
		if( !isset($tabs) || !is_array($tabs) || !count($tabs) ) return '';
		$id = mt_rand();
		$html = '
		<div class="panel-group" id="wsaf-tabs-root-'.$id.'" role="tablist" aria-multiselectable="true">';
		$tabCounter = 0;
		foreach ($tabs as $value) {
			if(!isset($value) || !is_array($value) || count($value)!=2 ) next();
			$tabId = 'wsaf-tabs-root-'.$id.'-tab-'.$tabCounter;
			$tabHedaerId = $tabId.'-header';
			$html .= '
			<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="'.$tabHedaerId.'">
			    	<h4 class="panel-title">
			    		<a role="button" data-toggle="collapse" data-parent="#wsaf-tabs-root-'.$id.'" href="#'.$tabId.'" aria-expanded="true" aria-controls="'.$tabId.'">
			          		'.$value['title'].'
			        	</a>
			      	</h4>
			    </div>
			    <div id="'.$tabId.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="'.$tabHedaerId.'">
			      <div class="panel-body">'.$value['text'].'</div>
			    </div>
			</div>';  // '.(!$tabCounter?'in':'').'
			++$tabCounter;
		}
		$html .= '
		</div>';
		return $html;
	}
}