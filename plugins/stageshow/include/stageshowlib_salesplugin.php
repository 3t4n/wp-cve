<?php
/*
Description: Core Library Generic Base Class for Sales Plugins

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

if (!class_exists('StageShowLibSalesCartPluginBaseClass')) 
	include STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_salesplugin_trolley.php';
	
if (!class_exists('StageShowLibSalesPluginBaseClass')) 
{
	include 'stageshowlib_nonce.php';

	if (!defined('STAGESHOWLIB_SCROLLTOANCHOR_OFFSET'))
		define('STAGESHOWLIB_SCROLLTOANCHOR_OFFSET', 0);
		
	if (!defined('STAGESHOWLIB_SCROLLTOANCHOR_DURATION'))
		define('STAGESHOWLIB_SCROLLTOANCHOR_DURATION', 1000);
		
	define('STAGESHOWLIB_CHECKOUTSTATUS_COMPLETE', 'Complete');
	define('STAGESHOWLIB_CHECKOUTSTATUS_CANCELLED', 'Cancelled');
	
	if (!defined('STAGESHOWLIB_DOMAIN'))
		define('STAGESHOWLIB_DOMAIN', 'stageshow');
	class StageShowLibSalesPluginBaseClass extends StageShowLibSalesCartPluginBaseClass // Define class
	{
		var $stockAnchor = "stock";
		var $hasActiveTrolley = false;
		var $secure_logged_in_cookie = false;
		
		function __construct()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			parent::__construct();
							
			// Add an action to check for Payment Gateway redirect
			add_action('wp_loaded', array(&$this, 'OnlineStore_ProcessCheckout'));

			if (isset($myDBaseObj->adminOptions['NeedPluginCookie'])
			  && $myDBaseObj->adminOptions['NeedPluginCookie'])
			{
				add_filter('secure_logged_in_cookie', array(&$this, 'Filter_secure_logged_in_cookie'), 10, 3);
				add_action('set_logged_in_cookie', array(&$this, 'SetAuthCookie'));
				add_action('clear_auth_cookie', array(&$this, 'ClearAuthCookie'));
			}
			
			add_filter( 'the_content', array($this, 'Filter_StageShowLib_TheContent'), 10, 1);	

			// FUNCTIONALITY: Main - Add ShortCode for client "front end"
			add_shortcode($this->shortcode, array(&$this, 'OutputContent_DoShortcode'));
			if (isset($this->dbshortcode))
				add_shortcode($this->dbshortcode, array(&$this, 'OutputContent_PIRShortcode'));
			
			if (defined('STAGESHOWLIB_BLOCK_HTTPS'))
			{
				add_filter('http_api_transports', array($this, 'StageShowLibBlockSSLHttp'), 10, 3);				
			}

		}
	
		function Reset()
		{
			$this->shortcodeCount = 0;
			parent::Reset();
		}
		
		function Filter_StageShowLib_TheContent($content)
		{
			// Page creation is complete - Reset Counters etc. in case it is created again (i.e. with Yoast SEO)
			$this->Reset();
			
			return $content;
		}
		
		function Filter_secure_logged_in_cookie($secure_logged_in_cookie, $user_id = '', $secure = false)
		{
			// Save the value of $secure_logged_in_cookie so we can use it later
			$this->secure_logged_in_cookie = $secure_logged_in_cookie;
			
			return $secure_logged_in_cookie;
		}
		
		function SetAuthCookie($logged_in_cookie, $expire = 0, $expiration = '', $user_id = '', $scheme = '')
		{
			if (!$this->NeedsPluginCookie()) return;
			
			$pluginsCookiePath = StageShowLibMigratePHPClass::Safe_substr(WP_PLUGIN_URL, StageShowLibMigratePHPClass::Safe_strlen(StageShowLibUtilsClass::GetPageHost())).'/';
			setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, $pluginsCookiePath, COOKIE_DOMAIN, $this->secure_logged_in_cookie, true);
		}
	
		function ClearAuthCookie()
		{
			if (!$this->NeedsPluginCookie()) return;
			
			// empty value and expiration one hour before
			$pluginsCookiePath = StageShowLibMigratePHPClass::Safe_substr(WP_PLUGIN_URL, StageShowLibMigratePHPClass::Safe_strlen(StageShowLibUtilsClass::GetPageHost())).'/';
			setcookie(LOGGED_IN_COOKIE, '', time()-10000, $pluginsCookiePath);
		}
		
		function NeedsPluginCookie()
		{
			$cookieURL = StageShowLibUtilsClass::GetPageHost().COOKIEPATH;
			$cookieURLLen = StageShowLibMigratePHPClass::Safe_strlen($cookieURL);

			// Now check if the page URL is in the COOKIEPATH
			if (StageShowLibMigratePHPClass::Safe_substr(StageShowLibUtilsClass::GetPageURL(), 0, $cookieURLLen) != $cookieURL)
				return false;
			
			return true;	
		}
		
		function StageShowLibBlockSSLHttp($transports, $args, $url)
		{
			$argsCount = count($args);
			if (($argsCount == 1) && isset($args['ssl']))
			{
//StageShowLibEscapingClass::Safe_EchoHTML("<br> ***************** HTTP SSL Transport Disabled ***************** <br>\n");
				return array();
			}

			return $transports;
		}

		function GetOurURL()
		{			
			$actionURL = remove_query_arg('_wpnonce');
			$actionURL = remove_query_arg('remove', $actionURL);
			$actionURL = remove_query_arg('editpage', $actionURL);
			
			$actionURL = remove_query_arg('saleCompleteID', $actionURL);
			$actionURL = remove_query_arg('saleCompleteTxn', $actionURL);
			
			// Remove the bookmark (if it exists)
			$actionURLParts = explode('#', $actionURL);
			
			return $actionURLParts[0];
		}		
		
		function activate($newInstall)
		{
			$myDBaseObj = $this->myDBaseObj;
      
			$myDBaseObj->adminOptions['TimeoutEMailTemplatePath'] = STAGESHOWLIB_SALE_TIMEOUT_EMAIL_TEMPLATE_PATH;
			$myDBaseObj->adminOptions['RejectedEMailTemplatePath'] = STAGESHOWLIB_SALE_REJECTED_EMAIL_TEMPLATE_PATH;
			
			// Set the "NeedPluginCookie" flag if the Plugins URL is not in the COOKIEPATH			
			$cookieURL = StageShowLibUtilsClass::GetPageHost().COOKIEPATH;
			$cookieURLLen = StageShowLibMigratePHPClass::Safe_strlen($cookieURL);
			$myDBaseObj->adminOptions['NeedPluginCookie'] = (StageShowLibMigratePHPClass::Safe_substr(WP_PLUGIN_URL, 0, $cookieURLLen) !== $cookieURL);
				
			if (!isset($myDBaseObj->adminOptions['MIMEEncoding']))
			{
				// Keep original MIME encoding code for existing installations
				// Use the revised method for new installs
				if ($newInstall)
					$myDBaseObj->adminOptions['MIMEEncoding'] = STAGESHOWLIB_MIMEENCODING_PHPMAILER;
				else
					$myDBaseObj->adminOptions['MIMEEncoding'] = STAGESHOWLIB_MIMEENCODING_PLUGIN;
			}
      		
      		$myDBaseObj->saveOptions(); 
		}
		
		function load_user_scripts()
		{
			$myDBaseObj = $this->myDBaseObj;			

			// Add our own Javascript
			$myDBaseObj->enqueue_script( 'stageshowlib-lib', plugins_url( 'js/stageshowlib_js.js', dirname(__FILE__)));

			$myDBaseObj->gatewayObj->Gateway_LoadUserScripts();

			wp_enqueue_script('jquery');
		}	
		
		function load_admin_scripts($page)
		{
			$myDBaseObj = $this->myDBaseObj;			

			$myDBaseObj->enqueue_script( 'stageshowlib_admin', plugins_url( 'admin/js/stageshowlib_admin.js', dirname(__FILE__) ));
			
			$myDBaseObj->gatewayObj->Gateway_LoadAdminStyles();
		}
		
		function GetOnlineStoreMaxSales($result)
		{
			return -1;
		}
			
		function IsOnlineStoreItemAvailable($saleItems)
		{
			return true;
		}

		function OutputContent_OnlineStoreFooter()
		{
		}

		function OutputContent_OnlineStoreMessages()
		{
			if (isset($this->checkoutMsg))
			{
				if (!isset($this->checkoutMsgClass))
				{
					$this->checkoutMsgClass = 'stageshow'.'-error error';
				}
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.$this->checkoutMsgClass.'"><p>'.$this->checkoutMsg.'</p></div>');					
			}				
		}
		
		function OutputContent_GetAtts( $atts )
		{
			$atts = shortcode_atts(array(
				'id'    => '',
				'count' => '',
				'anchor' => '',
				'style' => 'normal' 
			), $atts );
        
        	return $atts;
		}
		
		function OutputContent_TrolleyButtonJQuery($atts)
		{
			if (!defined('STAGESHOWLIB_UPDATETROLLEY_TARGET')) return 0;
			
			// Inject JS into output 
			$scriptCode = "\n<script>\n";

			if ($this->shortcodeCount == 1)
			{
				$scriptCode .=  "var stageshowlib_attStrings = [];\n";				
				$scriptCode .=  "var stageshowlib_pageAnchor = [];\n";
				$scriptCode .=  "var stageshowlib_cssDomain = '".'stageshow'."';\n";
				$scriptCode .=  "var stageshowlib_qtyInputsList = [];\n";	
				
				$hasAddButtonPerPrice = !is_numeric(StageShowLibMigratePHPClass::Safe_strpos($this->myDBaseObj->getOption('QtySelectMode'), STAGESHOWLIB_QTYSELECT_SINGLE)) ? 'true' : 'false';
				$scriptCode .=  "var stageshowlib_hasAddButtonPerPrice = $hasAddButtonPerPrice;\n";				
			}

			$comma = '';
			$attString = '';
			foreach ($atts as $attKey => $attVal)
			{
				$attVal = StageShowLibMigratePHPClass::Safe_str_replace("'", "\'", $attVal);
				$attKey = 'scatt_'.$attKey;
				$attString .= $comma.$attKey."=".$attVal;
				$comma = ',';
			}
			$index = $this->shortcodeCount-1;
			$scriptCode .=  "stageshowlib_attStrings[$index] = '".$attString."';\n";

			if ($this->shortcodeCount == 1)
			{
				$jQueryURL = admin_url( 'admin-ajax.php' );
				$scriptCode .=  'var jQueryURL = "'.$jQueryURL.'";'."\n";
				
				$scriptCode .=  '
					function StageShowLib_JQuery_PostVars(postvars)
					{
					    jQueryURL = "'.$jQueryURL.'";
						';
							
				$scriptCode .= $this->OutputContent_TrolleyJQueryPostvars();
				
				// Add the AJAX handler ID						
				$scriptCode .=  '
						postvars.action = "stageshowlib_ajax_request";
				';
						
				$scriptCode .=  '
						postvars.request  = "stageshowlib_jquery_callback";
						postvars.sessionID = "'.$this->myDBaseObj->sessionCookieID.'";
						postvars.target = "'.STAGESHOWLIB_UPDATETROLLEY_TARGET.'";
						
						return postvars;
					}
				';
			}
			
			if ($this->shortcodeCount == 1)
			{
				$scriptCode .= '
					jQuery(document).ready(
						function()
						{
						}
					);
				';
			}
			
			$scriptCode .=  "</script>\n";
			
			return $this->InjectJSCode($scriptCode, false);			
		}
		
		function OutputContent_TrolleyJQueryPostvars()
		{
			$jqCode = '';
			$stringToHash = '';
			$_wpnonce = StageShowLibNonce::GetStageShowLibNonceEx(STAGESHOWLIB_UPDATETROLLEY_TARGET, $stringToHash);
			if ($_wpnonce != '')
			{
				$jqCode .= '
				/* stringToHash: '.$stringToHash.' */
				postvars._wpnonce = "'.$_wpnonce.'";';
			}				
			
			if (StageShowLibUtilsClass::IsElementSet('request', 'action'))
			{
				$reqAction = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'action'); 
				$jqCode .= '
				postvars.action = "'.$reqAction.'";';
			}				
			
			return $jqCode;
		}
				
		function OutputContent_ShortcodeStyle()
		{
			return '';
		}

		function OutputContent_DoShortcode($atts, $isAdminPage=false)
		{
	  		// FUNCTIONALITY: Runtime - Output Shop Front
			$myDBaseObj = $this->myDBaseObj;
			
			$pluginID = $myDBaseObj->get_pluginName();
			$pluginVer = $myDBaseObj->get_version();
			$pluginAuthor = $myDBaseObj->get_author();
			$pluginURI = $myDBaseObj->get_pluginURI();
			
			$this->shortcodeCount++;
			
			if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);	// Disable Caching (WP Super Cache)
			
			// StageShowLib uses inline scripts and styles - wpautop breaks these so disable it
			//remove_filter('the_content', 'wpautop');

			$myDBaseObj->AllUserCapsToServervar();
					
			// Remove any incomplete Checkouts
			$myDBaseObj->PurgePendingSales();
			
			$outputContent  = "\n<!-- \n";
			$outputContent .= "$pluginID Plugin Code - Starts Here\n";
			if (is_array($atts))
			{
				foreach ($atts as $attID => $att)
				{
					$outputContent .= "$attID=$att \n";			
				}
			}			
			$outputContent .= "--> \n";
			if (!$isAdminPage) $outputContent .= $this->OutputContent_ShortcodeStyle();

			$outputContent .= '<form></form>'."\n";		// Insulate from unterminated form tags

			$actionURL = $this->GetOurURL();
			$actionURL = remove_query_arg('ppexp', $actionURL);

			$atts = $this->OutputContent_GetAtts($atts);

			if (defined('STAGESHOWLIB_JQUERY_DISABLE') || $this->myDBaseObj->isDbgOptionSet('Dev_DisableJS')) 
			{
				StageShowLibEscapingClass::Safe_EchoScript("
<script>
var StageShowLib_JQuery_Blocked = 0;\n
</script>");
			}
			else
			{
	 			$outputContent .= $this->OutputContent_TrolleyButtonJQuery($atts);
			}
		      
		    if ($this->myDBaseObj->IsSessionElemSet('gatewaystatus'))
		    {
		    	switch ($this->myDBaseObj->GetSessionElem('gatewaystatus'))
		    	{
					case STAGESHOWLIB_CHECKOUTSTATUS_COMPLETE:
						$this->checkoutMsg = __('Checkout Complete', 'stageshow').'<br>'.__('Please check your EMail for confirmation', 'stageshow');
						$this->checkoutMsgClass = 'stageshow'.'-ok';
						$this->boxofficeOverride = '';
						$this->suppressUI = true;
						break;
						
					case STAGESHOWLIB_CHECKOUTSTATUS_CANCELLED:
						$this->checkoutMsg = __('Checkout Cancelled', 'stageshow');
						$this->checkoutMsgClass = 'stageshow'.'-error error';		
						break;
				}
				
				$this->myDBaseObj->UnsetSessionElem('gatewaystatus');
				$this->myDBaseObj->UnsetSessionElem('gatewaycbid');
				$this->myDBaseObj->UnsetSessionElem('gatewaytxnid');
			}
        	
        	$ourAnchor = $atts['anchor'];
			if ($ourAnchor != '')
			{
				$pageAnchor = self::ANCHOR_PREFIX.$ourAnchor;	// i.e. trolley
				$outputContent .= "<script>\n";
				$outputContent .= "stageshowlib_pageAnchor[".$this->shortcodeCount."] = '$pageAnchor';\n";
				$outputContent .= "anchorOffset = ".STAGESHOWLIB_SCROLLTOANCHOR_OFFSET.";\n";
				$outputContent .= "anchorDuration = ".STAGESHOWLIB_SCROLLTOANCHOR_DURATION.";\n";
				$outputContent .= "</script>\n";
			}
			
			$actionURL .= '#'.self::ANCHOR_PREFIX.'trolley';			
			$outputContent .= '<form id=trolley method="post" action="'.$actionURL.'">'."\n";				
		
			$divId = $this->cssTrolleyBaseID.'-container'.$this->shortcodeCount;			
			$boxoffDiv = "<div id=$divId name=$divId>\n";	
			
			$divId = $this->cssTrolleyBaseID.'-trolley-std';			
			$trolleyDiv = "<div id=$divId name=$divId>\n";	
			$endDiv = '</div>'."\n";	
			
			$outputContent .= $myDBaseObj->GetWPNonceField();
			
			$putProductsAfterTrolley = $myDBaseObj->getOption('ProductsAfterTrolley');
			
			if (isset($this->editpage))
			{
				$outputContent .= '<input type="hidden" name="editpage" value="'.$this->editpage.'"/>'."\n";				

				if ($this->editpage == 'tickets')
				{
					$putProductsAfterTrolley = $myDBaseObj->IsButtonClicked('editbuyer');
				}
								
			}				
			
			ob_start();
			$trolleyContent = $this->Cart_OnlineStore_GetCheckoutDetails();	
			if ($trolleyContent == '')	
			{
				$showBoxOffice = true;
				$this->hasActiveTrolley |= $this->Cart_OnlineStore_HandleTrolley();
			}
			else
			{
				// Just output checkout details dialogue
				$showBoxOffice = false;	
			}
			$trolleyContent = ob_get_contents();
			ob_end_clean();
			if ($showBoxOffice)
			{
				ob_start();
				
				StageShowLibEscapingClass::Safe_EchoHTML($this->Cart_OutputContent_Anchor($this->stockAnchor));
			
				if (!$this->OutputContent_ProcessGatewayCallbacks($atts))
				{
					$this->Cart_OutputContent_OnlineStoreMain($atts);
					$uiOut = $this->OutputContent_OnlineTrolleyUserInterface();
				}
				
				if (isset($this->boxofficeOverride))
				{
					$boxofficeContent = $this->boxofficeOverride;
				}
				else
				{
					$boxofficeContent = ob_get_contents();
				}
				ob_end_clean();				
			}
			else
			{
				$boxofficeContent = '';
			}

			if ($this->hasActiveTrolley) $trolleyContent .= $uiOut;
			else if ($this->storeRows > 0) $boxofficeContent .= $uiOut;
			
			$boxofficeContent = $boxoffDiv.$boxofficeContent.$endDiv;
			$trolleyContent = $trolleyDiv.$trolleyContent.$endDiv;
			
			$this->OutputContent_OnlineStoreMessages();
			
			$trolleyContent = apply_filters('stageshow'.'_filter_trolley', $trolleyContent);
			$boxofficeContent = apply_filters('stageshow'.'_filter_boxoffice', $boxofficeContent);
			
			if ($putProductsAfterTrolley)
			{
				$outputContent .= $trolleyContent.$boxofficeContent;
			}
			else
			{
				$outputContent .= $boxofficeContent.$trolleyContent;
			}
			
			$outputContent .= '</form>'."\n";	
			
			if (!$this->adminPageActive 
			  && $showBoxOffice
			  && defined('STAGESHOWLIB_LOADING_URL') 
			  && (STAGESHOWLIB_LOADING_URL != ''))
			{
				$nameAndClass = STAGESHOWLIB_DOMAIN."-boxoffice-loading";
				$outputContent .= '<div id="'.$nameAndClass.'" class="'.$nameAndClass.'"><img src="'.STAGESHOWLIB_LOADING_URL.'"></div>'."\n";					
			}
			
			$outputContent .= $this->OutputContent_OnlineStoreFooter();
				
			$outputContent .= "\n<!-- $pluginID Plugin Code - Ends Here -->\n";
			
			if (!$this->hasActiveTrolley)
			{
				$boxofficeURL = StageShowLibUtilsClass::GetPageURL();
				if ($myDBaseObj->getOption('boxofficeURL') != $boxofficeURL)
				{
					$myDBaseObj->adminOptions['boxofficeURL'] = $boxofficeURL;
					$myDBaseObj->saveOptions();
				}
			}

			return $outputContent;						
		}
				
		function OutputContent_PIRShortcode($atts)
		{
			include STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_sc_pir.php';
			
			$pirObj = new StageShowLibPIRShortcodeClass($this);
			$outputContent = $pirObj->Output($atts);
			
			return $outputContent;
		}

		static function HandleCallbacks($incValid = array(), $adminValid = array())
		{
			$reqURL = StageShowLibUtilsClass::GetPageURL();
			
			if (StageShowLibMigratePHPClass::Safe_strpos($reqURL, STAGESHOWLIB_CALLBACK_BASENAME) !== false) 
			{
				// Code for callback from payment gateway
				// URL is of the form {siteURL}/stageshow_callback/{gatewayName}
				$reqURLParts = explode('?', $reqURL);
				// Callback Mark must be in URL address (rather than in params)			
				if (StageShowLibMigratePHPClass::Safe_strpos($reqURLParts[0], STAGESHOWLIB_CALLBACK_BASENAME) !== false) 
				{
					$callbackFile = basename($reqURLParts[0]);
					$callbackFile = "/stageshowlib_{$callbackFile}_callback.php";			
					$callbackFilePath = dirname(__FILE__).$callbackFile;
					
					if (!file_exists($callbackFilePath))
						die("Called $reqURL - Callback Does not exist");
					
					include $callbackFilePath;
					die;			
				}
			}

			if (StageShowLibUtilsClass::IsElementSet('request', STAGESHOWLIB_CALLBACK_BASENAME))			  
			{
				$callbackFile = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, STAGESHOWLIB_CALLBACK_BASENAME);
				if (in_array($callbackFile, $incValid))
					$callbackFile = "/include/{$callbackFile}";			
				else if (in_array($callbackFile, $adminValid))
					$callbackFile = "/admin/{$callbackFile}";
				else
					die;
				
				$callbackFilePath = dirname(dirname(__FILE__)).$callbackFile;

				if (!file_exists($callbackFilePath))
					die("Called $reqURL - Callback Does not exist");
				
				include $callbackFilePath;
				die;			
			} 
		}
 		
		function GetOnlineStoreTrolleyDetails($cartIndex, $cartEntry)
		{
			$saleDetails['itemID' . $cartIndex] = $cartEntry->itemID;
			$saleDetails['qty' . $cartIndex] = $cartEntry->qty;;
			
			return $saleDetails;
		}

		function OutputContent_ProcessGatewayCallbacks()
		{
			if ($this->myDBaseObj->gatewayObj->IsCallback($this))
			{
				return true;
			}
				
			return false;
		}

		function OnlineStore_AddCustomFieldValues($cartContents)
		{
			
		}
		
		function OnlineStore_ScanCheckoutSales()
		{
			$myDBaseObj = $this->myDBaseObj;
				
			// Check that request matches contents of cart
			$passedParams = array();	// Dummy array used when checking passed params
			
			$rslt = new stdClass();
			$rslt->saleDetails = array();
			$rslt->paypalParams = array();
			$ParamsOK = true;
			
			$rslt->totalDue = 0;
								
			$cartContents = $this->GetTrolleyContents();
			
			$this->OnlineStore_AddCustomFieldValues($cartContents);
				
			
			$rslt->saleDetails['saleNoteToSeller'] = StageShowLibDBaseClass::GetSafeString('saleNoteToSeller', '');
			
			if (!isset($cartContents->rows))
			{
				$rslt->checkoutMsg  = __('Cannot Checkout', 'stageshow').' - ';
				$rslt->checkoutMsg .= __('Shopping Trolley Empty', 'stageshow');
				return $rslt;
			}
			
			
			// Build request parameters for redirect to Payment Gateway checkout
			$paramCount = 0;
			$rslt->invIDs = array();
			foreach ($cartContents->rows as $cartIndex => $cartEntry)
			{				
				$paramCount++;
				$itemID = $cartEntry->itemID;
				$qty = $cartEntry->qty;
					
				$priceEntries = $this->GetOnlineStoreProductDetails($itemID);

				if (count($priceEntries) == 0)
				{
					$rslt->checkoutMsg  = __('Cannot Checkout', 'stageshow').' - ';
					$rslt->checkoutMsg .= __('One or more items are no longer available', 'stageshow');
					return $rslt;
				}
					
				// Get sales quantities for each item
				$priceEntry = $priceEntries[0];
				$invID = $this->GetOnlineStoreInventoryID($priceEntry);
				$rslt->invIDs[$paramCount] = $invID;
				isset($rslt->totalSales[$invID]) ? $rslt->totalSales[$invID] += $qty : $rslt->totalSales[$invID] = $qty;
						
				// Save the maximum number of sales for this stock item to a class variable
				$rslt->maxSales[$invID] = $this->GetOnlineStoreMaxSales($priceEntry);

				$ParamsOK &= $this->CheckGatewayParam($passedParams, "id" , $cartEntry->itemID, $cartIndex);
				$ParamsOK &= $this->CheckGatewayParam($passedParams, "qty" , $cartEntry->qty, $cartIndex);
				if (!$ParamsOK)
				{
					$rslt->checkoutMsg  = __('Cannot Checkout', 'stageshow').' - ';
					$rslt->checkoutMsg .= __('Shopping Trolley Contents have changed', 'stageshow');
					return $rslt;
				}
						
				$itemPrice = $this->GetOnlineStoreItemPrice($priceEntry);
				
				$rslt->saleDetails = array_merge($rslt->saleDetails, $this->GetOnlineStoreTrolleyDetails($paramCount, $cartEntry));
				$rslt->saleDetails['itemPaid' . $paramCount] = $itemPrice;
				
				$rslt->totalDue += ($itemPrice * $qty);
			}
			
			$this->OnlineStore_AddExtraPayment($rslt, $cartContents->saleTransactionFee, 'saleTransactionFee');

			if (StageShowLibUtilsClass::IsElementSet('post', 'saleDonation'))
			{
				$cartContents->saleDonation = StageShowLibHTTPIO::GetRequestedCurrency('saleDonation', false);
			}	
			
			if ($cartContents->saleDonation > 0)
			{
				$this->OnlineStore_AddExtraPayment($rslt, $cartContents->saleDonation, 'saleDonation');				
			}	
			
			// Shopping Trolley contents have changed if there are "extra" passed parameters 
			$cartIndex++;
			$ParamsOK &= !StageShowLibUtilsClass::IsElementSet('post', 'id'.$cartIndex);
			$ParamsOK &= !StageShowLibUtilsClass::IsElementSet('post', 'qty'.$cartIndex);
			if (!$ParamsOK)
			{
				$rslt->checkoutMsg = __('Cannot Checkout', 'stageshow').' - ';
				$rslt->checkoutMsg .= __('Item(s) removed from Shopping Trolley', 'stageshow');
				return $rslt;
			}
			
			return $rslt;
		}		

		function OnlineStore_AddExtraPayment(&$rslt, $amount, $detailID)
		{
		}
		
		function OnlineStore_ProcessCheckout()
		{
			// Process checkout request for Integrated Trolley
			// This function must be called before any output as it redirects to Payment Gateway if successful
			$myDBaseObj = $this->myDBaseObj;	
			
			$myDBaseObj->GetJSONEncodedPostVars();
									
			$checkout = $myDBaseObj->gatewayObj->IsCheckout($this);
			if ($checkout != '')
			{
				$checkoutRslt = $this->OnlineStore_ScanCheckoutSales();
				if (isset($checkoutRslt->checkoutMsg)) 
				{
					$this->checkoutMsg = $checkoutRslt->checkoutMsg;
					return;
				}
							
				if ($checkoutRslt->totalDue == 0)
				{
					$this->checkoutMsg = __('Cannot Checkout', 'stageshow').' - ';
					$this->checkoutMsg .= __('Total sale is zero', 'stageshow');
					return;
				}
			
				// Process Filter - Allows custom code to change processing
				//apply_filters('stageshowlib_checkout', $this);
								
				// Lock tables so we can commit the pending sale
				$this->myDBaseObj->LockSalesTable();
					
				// Check quantities before we commit 
				$ParamsOK = $this->IsOnlineStoreItemAvailable($checkoutRslt);
					
				if ($ParamsOK)
  				{
					$userFieldsList = $myDBaseObj->gatewayObj->Gateway_ClientFields();
					foreach ($userFieldsList as $userField => $userLabel)
					{
						$elemId = 'checkoutdetails-'.$userField;
						$checkoutRslt->saleDetails[$userField] = StageShowLibUtilsClass::GetHTTPTextElem('post', $elemId);
					}

					$systemFieldsList = $myDBaseObj->gatewayObj->Gateway_SystemFields();				
					foreach ($systemFieldsList as $systemField => $systemValue)
					{
						$checkoutRslt->saleDetails[$systemField] = $systemValue;
					}
					
					$checkoutRslt->saleDetails['saleMethod'] = $myDBaseObj->gatewayObj->GetPaymentMethod();
					
					// Update quantities ...
					$saleId = $this->myDBaseObj->LogSale($checkoutRslt, StageShowLibSalesDBaseClass::STAGESHOWLIB_LOGSALEMODE_CHECKOUT);					
					
					// Release Tables
					$this->myDBaseObj->UnLockTables();
					
					$saleRecord = $this->myDBaseObj->GetPendingSale($saleId);	
					$gatewayURL = $myDBaseObj->gatewayObj->GetGatewayRedirectURL($saleRecord);					
					$gatewayURL = apply_filters('stageshow'.'_filter_GatewayRedirectURL', $gatewayURL);
				}
				else
				{				
					// Release Tables
					$this->myDBaseObj->UnLockTables();
					
					$this->checkoutMsg = __('Cannot Checkout', 'stageshow').' - '.$this->checkoutMsg;
				}	
					
				if ($ParamsOK)
  				{
					$this->ClearTrolleyContents();
				
					if ($this->myDBaseObj->isDbgOptionSet('Dev_IPNLocalServer'))
					{
						$this->checkoutMsg .= __('Using Local IPN Server - Gateway Checkout call skipped', 'stageshow');
						if ($this->myDBaseObj->isDbgOptionSet('Dev_IPNDisplay'))
						{
							$gatewayURLParams = StageShowLibMigratePHPClass::Safe_str_replace('&', '<br>', $gatewayURL);
							$this->checkoutMsg .= "<br>Gateway URL:$gatewayURLParams<br>\n";
						}					
					}
					else 
					{
						
						// RedirectToGateway() does not return if browser is redirected ....
						$this->checkoutMsg .= $myDBaseObj->gatewayObj->RedirectToGateway($gatewayURL);
						$this->checkoutMsgClass = 'stageshow'.'-ok';
						$this->boxofficeOverride = '';
						$this->suppressUI = true;
						//exit;
					}
				}
				else
				{
					$this->checkoutMsg = __('Cannot Checkout', 'stageshow').' - ';
					$this->checkoutMsg .= __('Sold out for one or more items', 'stageshow');
					return;						
				}
				
			}			

			$checkoutComplete = $myDBaseObj->gatewayObj->IsComplete();
			if ($checkoutComplete != null)
			{
				if (isset($checkoutComplete->checkoutMsg))
				{
					$this->checkoutMsg = $checkoutComplete->checkoutMsg;
					$this->checkoutMsgClass = 'stageshow'.$checkoutComplete->checkoutMsgClass;
				}
				else
				{
					$completeMsg = __('Transaction Complete - Ref: ', 'stageshow').$checkoutComplete->saleTxnId;
					$this->checkoutMsg = $completeMsg.$this->checkoutMsg;
					$this->checkoutMsgClass = 'stageshow'.'-ok';
				}
			}
		}
		
		function CheckGatewayParam(&$paramsArray, $paramId, $paramValue, $paramIndex = 0)		
		{
			if ($paramIndex > 0)
				$paramId .= $paramIndex;
					
			$paramsArray[$paramId] = $paramValue;
			if (StageShowLibUtilsClass::IsElementSet('post', $paramId))
			{
				if ($_POST[$paramId] != $paramValue)
				{
					return false;
				}
			}
			else
			{
				return false;
			}
				
			return true;
		}
		
	}
	
}

