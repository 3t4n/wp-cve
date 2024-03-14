<?php
/* 
Description: Code for TBD
 
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

if (!defined('StageShowLibAdminClass'))
{
	class StageShowLibAdminClass
	{
		function __construct($env, $inForm = false) //constructor	
		{
			$this->env = $env;
			$this->myDBaseObj = $env['DBaseObj'];
			$this->ValidateSaleForm();
		}
		
		function WPNonceField($referer = '', $name = '_wpnonce', $echo = true)
		{
			//$this->myDBaseObj->WPNonceField($referer, $name, $echo);
		}

	}
}

include STAGESHOW_INCLUDE_PATH.'stageshow_salevalidate.php'; 
include STAGESHOW_INCLUDE_PATH.'stageshow_validate_api.php';      
include STAGESHOW_INCLUDE_PATH.'stageshowlib_nonce.php';      
include STAGESHOW_INCLUDE_PATH.'stageshowlib_dbase_base.php';

$DBClass = 'StageShowValidateDBaseClass';
$myDBaseObj = new $DBClass();

$env = array();
$env['Caller'] = __FILE__;
$env['PluginObj'] = null;
$env['DBaseObj'] = $myDBaseObj;

$callerNOnce = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'nonce');
$ourNOnce = StageShowLibNonce::GetStageShowLibNonce(STAGESHOW_SALEVALIDATE_TARGET);
if ($callerNOnce != $ourNOnce)
{
	StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>jQuery Call Error: Authorisation Failed</p></div>');
	exit;
}
	
$SaleClass = 'StageShowSaleValidateClass';
new $SaleClass($env);



