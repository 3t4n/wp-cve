<?php
/* 
Description: StageShow Plugin Top Level Code
 
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

if (!defined('STAGESHOWLIB_DATABASE_FULL')) define('STAGESHOWLIB_DATABASE_FULL', true);

if (!class_exists('StageShowCartPluginClass')) 
	include 'stageshow_trolley.php';
	
if (!class_exists('StageShowPluginClass')) 
{
	define('STAGESHOW_MENUPAGE_SEATING', STAGESHOW_CODE_PREFIX.'_seating');
	define('STAGESHOW_MENUPAGE_DISCOUNTS', STAGESHOW_CODE_PREFIX.'_discounts');
	
	class StageShowPluginClass extends StageShowCartPluginClass // Define class 
	{
		const PAGEMODE_SELECTSEATS = 'selectseats';
		
		function __construct($caller) 
		{
			parent::__construct($caller);	
			
			//Actions
			register_activation_hook( $caller, array(&$this, 'activate') );
			register_deactivation_hook( $caller, array(&$this, 'deactivate') );
				
			add_action('wp_print_styles', array(&$this, 'load_user_styles') );
			add_action('wp_print_scripts', array(&$this, 'load_user_scripts') );
			
			//add_action('wp_enqueue_scripts', array(&$this, 'load_user_scripts') );
			add_action('admin_enqueue_scripts', array(&$this, 'load_admin_scripts') );
			
			// Add a reference to the header
			add_action('wp_head', array(&$this, 'OutputMetaTag'));
			
			// Add action to process callback
			add_action('wp_loaded', array(&$this, 'OnWPLoaded'));
/*			
			// Function to add notification to admin page
			add_action( 'admin_notices', array(&$this, 'AdminUpgradeNotice'));
*/
			
			$myDBaseObj = $this->myDBaseObj;
			
			//Actions
			add_action('admin_menu', array(&$this, 'GenerateMenus'));
		  
			add_action('init', array(&$this, 'OnWPInit'));	  
						
			add_action("wp_ajax_stageshowlib_ajax_request" , array(&$this, 'stageshow_ajax_call'));
			add_action("wp_ajax_nopriv_stageshowlib_ajax_request" , array(&$this, 'stageshow_ajax_call'));
			
			$versionCheckRslt = $myDBaseObj->compareVersion();
			if ($versionCheckRslt != StageShowLibDBaseClass::VERSION_UNCHANGED)
			{
				// FUNCTIONALITY: Main - Call "Activate" on plugin update
				// Versions are different ... call activate() to do any updates
				$newInstall = $versionCheckRslt == StageShowLibDBaseClass::VERSION_NEWINSTALL;
				$this->activate($newInstall);
			}			
			
		}

		static function CreateDBClass($caller)
		{			
			if (!class_exists('StageShowDBaseClass')) 
				include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_api.php';
				
			return new StageShowDBaseClass($caller);		
		}
		
		function load_user_styles() 
		{
			//Add Style Sheet
			$this->myDBaseObj->enqueue_style(STAGESHOW_CODE_PREFIX, STAGESHOW_STYLESHEET_URL); // StageShow core style

			$this->load_customCSS();
			
			$this->load_seatingCSS();					
		}
		
		function load_customCSS() 
		{
			$myDBaseObj = $this->myDBaseObj;
					
			$cssURL = $myDBaseObj->getOption('CustomStylesheetPath');
 			if ($cssURL != '')
			{
				$cssURL = STAGESHOW_UPLOADS_URL . 'css/'.$cssURL;
				$myDBaseObj->enqueue_style(STAGESHOW_CODE_PREFIX.'-customcss', $cssURL);
			}
		}
		
		function load_seatingCSS() 
		{
			$myDBaseObj = $this->myDBaseObj;
			
			if ($myDBaseObj->isOptionSet('Custom_BOClass'))	
				$myDBaseObj->enqueue_style('stageshow-seats', STAGESHOW_URL.'css/stageshow-seats-custom.css');
			else
				$myDBaseObj->enqueue_style('stageshow-seats', STAGESHOW_URL.'css/stageshow-seats.css');
		}
				
		//Returns an array of admin options
		// Saves the admin options to the options data table
		
		// ----------------------------------------------------------------------
		// Activation / Deactivation Functions
		// ----------------------------------------------------------------------
		
		function activate($newInstall)
		{
			include STAGESHOW_INCLUDE_PATH.'stageshow_barcode.php';

			$myDBaseObj = $this->myDBaseObj;
				    
			$myDBaseObj->GetBoxOfficeClasses();
			
			if (version_compare(PHP_VERSION, '5.3.0') < 0) 
			{
			    die('Cannot Activate - StageShow requires PHP version 5.3 or later - PHP '.PHP_VERSION.' Installed');
			}
			
	  		// FUNCTIONALITY: Activate - Add defaults to options that are not set
			$defaultOptions = $myDBaseObj->GetDefaultOptions();
			foreach ($defaultOptions as $optionKey => $optionValue)
			{
				// Add default values to settings that are not already set
				if (!isset($myDBaseObj->adminOptions[$optionKey]) || ($myDBaseObj->adminOptions[$optionKey] == ''))
					$myDBaseObj->adminOptions[$optionKey] = $optionValue;
			}

			// Bump the activation counter
			$myDBaseObj->adminOptions['ActivationCount']++;
			
 			if ($myDBaseObj->adminOptions['ActivationCount'] == 1)
			{
				// First time activation ....
				$myDBaseObj->adminOptions['QtySelectMode'] = STAGESHOWLIB_QTYSELECT_TEXT.STAGESHOWLIB_QTYSELECT_SINGLE;
			}
			
			$myDBaseObj->adminOptions['TestModeEnabled'] = file_exists(STAGESHOW_TEST_PATH.'stageshow_testsettings.php');
			
			$LogsFolder = ABSPATH . '/' . $myDBaseObj->adminOptions['LogsFolderPath'];
			if (!is_dir($LogsFolder))
				mkdir($LogsFolder, STAGESHOWLIB_LOGFOLDER_PERMS, true);

			if (defined('STAGESHOWLIB_LOG_HTTPIO'))
			{
				// Initialise from a define constant so we can log first activation
				$myDBaseObj->dbgOptions['Dev_LogHTTP'] = true;
			}

			if ( !isset($myDBaseObj->adminOptions['GetPurchaserAddress'])
			  && defined('STAGESHOWLIB_SHIPPING_REQUIRED') )
			{
				$myDBaseObj->adminOptions['GetPurchaserAddress'] = true;
			}
			
	  		// FUNCTIONALITY: Activate - Set EMail template to file name ONLY
			// EMail Template defaults to templates folder - remove folders from path
			$myDBaseObj->CheckEmailTemplatePath('EMailTemplatePath', STAGESHOW_ACTIVATE_EMAIL_TEMPLATE_PATH, STAGESHOW_DEFAULT_EMAIL_TEMPLATE_PATH);

      		parent::activate($newInstall);
      
			$setupUserRole = $myDBaseObj->adminOptions['SetupUserRole'];

	  		// FUNCTIONALITY: Activate - Add Capabilities
			// Add capability to submit events to all default users
			$adminRole = get_role($setupUserRole);
			if ( !empty($adminRole) ) 
			{
				// Adding Manage StageShow Capabilities to Administrator					
				if (!$adminRole->has_cap(STAGESHOWLIB_CAPABILITY_RESERVEUSER))
					$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_RESERVEUSER);
				if (!$adminRole->has_cap(STAGESHOWLIB_CAPABILITY_VALIDATEUSER))
					$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_VALIDATEUSER);
				if (!$adminRole->has_cap(STAGESHOWLIB_CAPABILITY_SALESUSER))
					$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_SALESUSER);
				if (!$adminRole->has_cap(STAGESHOWLIB_CAPABILITY_VIEWSALESUSER))
					$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_VIEWSALESUSER);
				if (!$adminRole->has_cap(STAGESHOWLIB_CAPABILITY_ADMINUSER))
					$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_ADMINUSER);
				if (!$adminRole->has_cap(STAGESHOWLIB_CAPABILITY_SETUPUSER))
					$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_SETUPUSER);
				if (!$adminRole->has_cap(STAGESHOWLIB_CAPABILITY_VIEWSETTINGS))
					$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_VIEWSETTINGS);
			}				
			
			// Add copies of PayPal IPN notification code for historical configurations
			// Note: MixedCase copy of stageshow_ipn_callback.php does nothing on Windows Server
			copy(STAGESHOW_FILE_PATH.'stageshow_ipn_callback.php', STAGESHOW_FILE_PATH.'stageshow_NotifyURL.php');
			copy(STAGESHOW_FILE_PATH.'stageshow_ipn_callback.php', STAGESHOW_FILE_PATH.'StageShow_ipn_callback.php');
			
			StageShowLibUtilsClass::DeleteFile(STAGESHOW_UPLOADS_PATH.'/emails/stageshow_SaleTimeoutEMail.php');
			
      		$myDBaseObj->upgradeDB();
      		
      		if (file_exists(STAGESHOW_UPLOADS_PATH.'/wp-config-db.php'))
				StageShowLibUtilsClass::DeleteFile(STAGESHOW_UPLOADS_PATH.'/wp-config-db.php');
      			
      		if (STAGESHOW_FOLDER == 'stageshow')
      		{
      			$PluginRoot = WP_CONTENT_DIR . '/plugins/';
				if (file_exists($PluginRoot.'stageshowgold'))
				{
					StageShowLibUtilsClass::deleteDir($PluginRoot.'stageshowgold');		
				}
				if (file_exists($PluginRoot.'stageshowplus'))
				{
					StageShowLibUtilsClass::deleteDir($PluginRoot.'stageshowplus');
				}
			}
				
 			if ($myDBaseObj->adminOptions['ActivationCount'] == 1)
			{
				// New installs default to Code-128 Barcodes
				$myDBaseObj->adminOptions['BarcodeType'] = DSTBarcode::BARCODE_TYPE_CODE128;
			}
			
			if (!isset($myDBaseObj->adminOptions['BarcodeType']))
			{
				// Existing sites keep Code-39 Barcodes
				$myDBaseObj->adminOptions['BarcodeType'] = DSTBarcode::BARCODE_TYPE_CODE39;
			}				
			
			$myDBaseObj->CheckEmailTemplatePath('ReserveEMailTemplatePath', STAGESHOW_ACTIVATE_RESERVEEMAIL_TEMPLATE_PATH);
			$myDBaseObj->CheckEmailTemplatePath('EMailSummaryTemplatePath', STAGESHOW_ACTIVATE_EMAILSUMMARY_TEMPLATE_PATH);
			
 			if (isset($myDBaseObj->adminOptions['GatewaySelected'])
 			  && ($myDBaseObj->adminOptions['GatewaySelected'] == 'paypal') )
 			{
 				// Upgrade to enable PayPal Express
				$myDBaseObj->adminOptions['GatewaySelected'] = 'paypal_exp';
			}
      		
			if (isset($myDBaseObj->adminOptions['EnableReservations']))
			{
				// EnableReservations changed to ReservationsMode
				$myDBaseObj->adminOptions['ReservationsMode'] = STAGESHOW_RESERVATIONSMODE_LOGIN;
			}				
			
			if (isset($myDBaseObj->dbgOptions['Dev_EnablePrinting']))
			{
				// EnableReservations changed to ReservationsMode
				$myDBaseObj->adminOptions['EnablePrinting'] = $myDBaseObj->dbgOptions['Dev_EnablePrinting'];
				unset($myDBaseObj->dbgOptions['Dev_EnablePrinting']);
			}				
			
      		$this->myDBaseObj->saveOptions();    
      		  
      		do_action('StageShowAction_activate', $this);
		}

	    function deactivate()
	    {
	    }

		function stageshow_ajax_call()
		{
			$request = StageShowLibUtilsClass::GetHTTPAlphaNumericElem('post', 'request'); 
			
			switch ($request)
			{
				case 'stageshowlib_ajax_request':
					ob_start();
					// Handle AJAX Call ...
					$response = ob_get_contents();
					ob_end_clean();
					break;
				
				case 'stageshow_sample':
					include STAGESHOW_INCLUDE_PATH.'stageshow_show_sample.php';
					die;
				
				case 'stageshowlib_jquery_callback':
					$targetFile = StageShowLibUtilsClass::GetHTTPAlphaNumericElem('post', 'target'); 
					switch ($targetFile)
					{
						case STAGESHOWLIB_UPDATETROLLEY_TARGET:
						case STAGESHOW_SALEVALIDATE_TARGET:
							include STAGESHOW_INCLUDE_PATH.$targetFile;
							die;
					}
					// Fall into next case ...
					
				default:
					$response = 'Unrecognised AJAX Call';
					break;	
			}
		    
		    echo json_encode($response);
		    wp_die();
		}
		
		function OnWPLoaded()
		{
			$incValid = array(STAGESHOW_TICKETPRINT_TARGET, STAGESHOW_SAMPLES_TARGET, STAGESHOWLIB_SENDEMAIL_TARGET);
			$adminValid = array(STAGESHOW_EXPORT_TARGET, STAGESHOW_DBEXPORT_TARGET, STAGESHOWLIB_VIEWEMAIL_TARGET);
			
			self::HandleCallbacks($incValid, $adminValid);
		}

 		function OutputMetaTag()
		{
			$myDBaseObj = $this->myDBaseObj;
			
	  		// FUNCTIONALITY: Runtime - Output StageShow Meta Tag
			// Get Version Number
			$pluginID = $myDBaseObj->get_pluginName();
			$pluginVer = $myDBaseObj->get_version();
			$boxofficeURL = $myDBaseObj->getOption('boxofficeURL');
			
			StageShowLibEscapingClass::Safe_EchoHTML("\n<meta name='$pluginID' content='$pluginID for WordPress by Malcolm Shergold - Ver:$pluginVer - BoxOfficeURL:$boxofficeURL' />\n");						
		}
		
		function CreateSample($sampleDepth = 0)
		{
			include STAGESHOW_INCLUDE_PATH.'stageshow_sample_dbase.php'; 
				
			$myDBaseObj = $this->myDBaseObj;
			$myDBaseObj->setOption('CustomStylesheetPath', 'stageshow-samples.css');
			$this->myDBaseObj->saveOptions();
			
			$sampleClassId = 'StageShowSampleDBaseClass';
			$sampleClassObj = new $sampleClassId($myDBaseObj);
			$sampleClassObj->CreateSample($sampleDepth);
		}
		
		function printAdminPage() 
		{
			$this->adminPageActive = true;
		
			$id = StageShowLibUtilsClass::GetHTTPTextElem('get', 'id'); 
			$this->SetTrolleyID($id);

			$this->outputAdminPage();
		}
		
		function outputAdminPage() 
		{
			//Outputs an admin page
      			
			$myDBaseObj = $this->myDBaseObj;					
			
			$pageSubTitle = StageShowLibUtilsClass::GetHTTPTextElem('get', 'page');
      		switch ($pageSubTitle)
      		{
				case STAGESHOW_MENUPAGE_ADMINMENU:
				case STAGESHOW_MENUPAGE_OVERVIEW:
				default :
					include 'admin/stageshow_manage_overview.php';
					$classId = 'StageShowOverviewAdminClass';
					new $classId($this->env);
					break;
					
				case STAGESHOW_MENUPAGE_SEATING :
					include 'admin/stageshow_manage_seating.php';      
					$classId = 'StageShowSeatingAdminClass';
					new $classId($this->env);
					break;
										
        		case STAGESHOW_MENUPAGE_DISCOUNTS:
					include 'admin/stageshow_manage_discounts.php';     
					$classId = 'StageShowDiscountsAdminClass';
					new $classId($this->env);
          			break;
          
        		case STAGESHOW_MENUPAGE_SHOWS:
					include 'admin/stageshow_manage_shows.php';     
					$classId = 'StageShowShowsAdminClass';
					new $classId($this->env);
          			break;
          
				case STAGESHOW_MENUPAGE_PRICEPLANS :
					include 'admin/stageshow_manage_priceplans.php';      
					$classId = 'StageShowPricePlansAdminClass';
					new $classId($this->env);
					break;
										
        		case STAGESHOW_MENUPAGE_PERFORMANCES :
					include 'admin/stageshow_manage_performances.php';
					$classId = 'StageShowPerformancesAdminClass';
					new $classId($this->env);
					break;
					
				case STAGESHOW_MENUPAGE_PRICES :
					include 'admin/stageshow_manage_prices.php';      
					$classId = 'StageShowPricesAdminClass';
					new $classId($this->env);
					break;
					
				case STAGESHOW_MENUPAGE_SALES :
					include 'admin/stageshow_manage_sales.php';
					$classId = 'StageShowSalesAdminClass';
					new $classId($this->env);
					break;
					
				case STAGESHOW_MENUPAGE_SETTINGS :
					include 'admin/stageshow_manage_settings.php';
					$classId = 'StageShowSettingsAdminClass';
					new $classId($this->env);
					break;
          
				case STAGESHOW_MENUPAGE_TOOLS:
					include 'admin/stageshow_manage_tools.php';
					$classId = 'StageShowToolsAdminClass';
					new $classId($this->env);							 
					break;
							
				case STAGESHOW_MENUPAGE_TESTSETTINGS:
					include STAGESHOW_TEST_PATH.'stageshow_testsettings.php';
					$classId = 'StageShowTestSettingsAdminClass';
					new $classId($this->env);							 
					break;	
					
				case STAGESHOW_MENUPAGE_DEVTEST:
					include STAGESHOW_TEST_PATH.'stageshowlib_devtestcaller.php';   
					new StageShowLibDevCallerClass($this->env);
					break;
			}
		}//End function printAdminPage()	
		
		function load_user_scripts()
		{
			$myDBaseObj = $this->myDBaseObj;			

			parent::load_user_scripts();

			// Add our own Javascript
			$myDBaseObj->enqueue_script( 'stageshow-js', plugins_url( 'js/stageshow.js', __FILE__ ));

			$jsURL = $myDBaseObj->getOption('CustomJavascriptPath');
 			if ($jsURL != '')
			{
				$jsURL = STAGESHOW_UPLOADS_URL . 'js/'.$jsURL;
				$myDBaseObj->enqueue_script( 'stageshow-customjs', $jsURL);
			}
		}	
		
		function load_admin_scripts($page)
		{
			$myDBaseObj = $this->myDBaseObj;			

			parent::load_admin_scripts($page);

			// Add our own style sheet
			$myDBaseObj->enqueue_style( 'stageshow', plugins_url( 'admin/css/stageshow-admin.css', __FILE__ ));
			
			// Add our own Javascript
			$myDBaseObj->enqueue_script( 'stageshow-admin', plugins_url( 'admin/js/stageshow-admin.js', __FILE__ ));
			$myDBaseObj->enqueue_script( 'stageshow-dtpicker', plugins_url( 'admin/js/datetimepicker_css.js', __FILE__ ));
					
			$cssURL = $myDBaseObj->getOption('CustomAdminStylesheetPath');
 			if ($cssURL != '')
			{
				$cssURL = STAGESHOW_UPLOADS_URL . 'css/'.$cssURL;
				$myDBaseObj->enqueue_style(STAGESHOW_CODE_PREFIX.'-customcss', $cssURL);
			}
			
			$this->load_customCSS();
			$this->load_seatingCSS();		
		}

		function AddToMenusList(&$menusList, $name, $cap, $id, $after='') 
		{
			if ($after=='')
			{
				$menusList[] = array('name'=>$name, 'cap'=> $cap, 'id'=>$id);
				return $menusList;
			}
			
			$newMenuList = array();
			foreach ($menusList as $menuItem)
			{
				$newMenuList[] = $menuItem;
				if ($menuItem['id'] == $after)
				{
					$newMenuList[] = array('name'=>$name, 'cap'=> $cap, 'id'=>$id);
				}
			}

			$menusList = $newMenuList;
		}

		function GetMenusList($adminCap) 
		{
			$menusList = array();
			
			$this->AddToMenusList($menusList, __('Overview', 'stageshow'),      $adminCap,                   STAGESHOW_MENUPAGE_ADMINMENU);			
			$this->AddToMenusList($menusList, __('Shows', 'stageshow'),         STAGESHOWLIB_CAPABILITY_ADMINUSER, STAGESHOW_MENUPAGE_SHOWS);   			
			$this->AddToMenusList($menusList, __('Performances', 'stageshow'),  STAGESHOWLIB_CAPABILITY_ADMINUSER, STAGESHOW_MENUPAGE_PERFORMANCES);
			$this->AddToMenusList($menusList, __('Prices', 'stageshow'),        STAGESHOWLIB_CAPABILITY_ADMINUSER, STAGESHOW_MENUPAGE_PRICES);
			
			if ( current_user_can(STAGESHOWLIB_CAPABILITY_VALIDATEUSER)
			  || current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER)
			  || current_user_can(STAGESHOWLIB_CAPABILITY_VIEWSALESUSER) )
			{
				$this->AddToMenusList($menusList, __('Sales', 'stageshow'),     $adminCap,                   STAGESHOW_MENUPAGE_SALES);
				$this->AddToMenusList($menusList, __('Tools', 'stageshow'),     $adminCap,                   STAGESHOW_MENUPAGE_TOOLS);				
			}
			$this->AddToMenusList($menusList, __('Price Plans', 'stageshow'),   STAGESHOWLIB_CAPABILITY_ADMINUSER, STAGESHOW_MENUPAGE_PRICEPLANS, STAGESHOW_MENUPAGE_SHOWS); 
			$this->AddToMenusList($menusList, __('Seating Plans', 'stageshow'), STAGESHOWLIB_CAPABILITY_ADMINUSER, STAGESHOW_MENUPAGE_SEATING, STAGESHOW_MENUPAGE_ADMINMENU);     					
			$this->AddToMenusList($menusList, __('Discount Codes', 'stageshow'), STAGESHOWLIB_CAPABILITY_ADMINUSER, STAGESHOW_MENUPAGE_DISCOUNTS, STAGESHOW_MENUPAGE_SEATING);     					
			return $menusList;
		}
		
		function GenerateMenus() 
		{
			$myDBaseObj = $this->myDBaseObj;		
			
			if (!isset($this)) {
				return;
			}

			// Array of Admin capabilities in decreasing order of functionality
			$stageShow_caps = array(
				STAGESHOWLIB_CAPABILITY_DEVUSER,
				STAGESHOWLIB_CAPABILITY_SETUPUSER,
				STAGESHOWLIB_CAPABILITY_ADMINUSER,
				STAGESHOWLIB_CAPABILITY_SALESUSER,
				STAGESHOWLIB_CAPABILITY_VALIDATEUSER,
				STAGESHOWLIB_CAPABILITY_VIEWSETTINGS,
				STAGESHOWLIB_CAPABILITY_VIEWSALESUSER,
			);
			
			foreach ($stageShow_caps as $stageShow_cap)
			{
				if (current_user_can($stageShow_cap))
				{
					$adminCap = $stageShow_cap;
					break;
				}
			}
			
			if (current_user_can(STAGESHOWLIB_CAPABILITY_SETUPUSER))
			{
				$viewSettingsCap = STAGESHOWLIB_CAPABILITY_SETUPUSER;
			}
			else
			{
				$viewSettingsCap = STAGESHOWLIB_CAPABILITY_VIEWSETTINGS;
			}
			
			if (isset($adminCap) && function_exists('add_menu_page')) 
			{
				$ourPluginName = $myDBaseObj->get_pluginName();
				
				$icon_url = STAGESHOW_ADMIN_IMAGES_URL.'stageshow16grey.png';
				add_menu_page($ourPluginName, $ourPluginName, $adminCap, STAGESHOW_MENUPAGE_ADMINMENU, array(&$this, 'printAdminPage'), $icon_url);
				
				$menusList = $this->GetMenusList($adminCap);
				foreach ($menusList as $menuItem)
				{
					add_submenu_page( STAGESHOW_MENUPAGE_ADMINMENU, $menuItem['name'], $menuItem['name'], $menuItem['cap'], $menuItem['id'], array(&$this, 'printAdminPage'));
				}

				add_submenu_page( STAGESHOW_MENUPAGE_ADMINMENU, __('Settings', 'stageshow'), __('Settings', 'stageshow'),    $viewSettingsCap,                   STAGESHOW_MENUPAGE_SETTINGS,     array(&$this, 'printAdminPage'));

			}	
			
		}

		function OutputContent_OnlineStoreFooter()
		{
			if ($this->myDBaseObj->getOption('SkipDrivenLink'))
			{
				return;
			}
			
			return parent::OutputContent_OnlineStoreFooter();
		}
		
		function OutputContent_TrolleyJQueryPostvars()
		{
			$jqCode = parent::OutputContent_TrolleyJQueryPostvars();
			
			if ($this->myDBaseObj->isOptionSet('AllowDonation'))
			{
				$jqCode .= '
				var saleDonationElem = document.getElementById("saleDonation");
				if (saleDonationElem)
				{
					postvars.saleDonation = saleDonationElem.value;
				}';
			}
				
			if ($this->myDBaseObj->isOptionSet('PostTicketsEnabled'))
			{
				$jqCode .= '
				var salePostTicketsElem = document.getElementById("salePostTickets");
				if (salePostTicketsElem)
				{
					if (salePostTicketsElem.type == "checkbox")
					{
						if (salePostTicketsElem.checked)
						{
							postvars.salePostTickets = true;
						}
					}
					else
					{
						if (salePostTicketsElem.value != "")
						{
							postvars.salePostTickets = true;
						}						
					}
				}';
			}		
			
			if (StageShowLibUtilsClass::IsElementSet('request', 'caldate'))
			{
				$caldate = StageShowLibUtilsClass::GetHTTPDateTime($_REQUEST, 'caldate'); 
				$jqCode .= '
				postvars.caldate = "'.$caldate.'";';
			}				
			
			$jqCode .= '
			var saleDiscountCodeElem = document.getElementById("saleDiscountCode");
			if (saleDiscountCodeElem)
			{
				postvars.saleDiscountCode = saleDiscountCodeElem.value;
			}';
			
			return $jqCode;
		}

		function OutputContent_OnlinePurchaserDetails($cartContents, $extraHTML = '')
		{
			$formHTML = $extraHTML;
			
			if ($this->myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED) 
			{
				// Output Select Status Drop-down Dialogue
				$saleStatus = isset($cartContents->saleStatus) ? $cartContents->saleStatus : '';
				$selectCompleted = ($saleStatus == PAYMENT_API_SALESTATUS_COMPLETED) ? 'selected=true ' : '';
				$selectReserved  = ($saleStatus == PAYMENT_API_SALESTATUS_RESERVED) ? 'selected=true ' : '';
				
				$formHTML .=  '
				<tr class="stageshow-boxoffice-formRow">
					<td class="stageshow-boxoffice-formFieldID">'.__('Status', 'stageshow').':&nbsp;</td>
					<td class="stageshow-boxoffice-formFieldValue" colspan="2">
				<select id="saleStatus" name="saleStatus">
					<option value="'.PAYMENT_API_SALESTATUS_COMPLETED.'" '.$selectCompleted.'>'.__('Completed', 'stageshow').'&nbsp;</option>
					<option value="'.PAYMENT_API_SALESTATUS_RESERVED.'" '.$selectReserved.'>'.__('Reserved', 'stageshow').'&nbsp;</option>
				</select>
					</td>
				</tr>
				';
			}
			else
			{
				$formHTML .= '
				<input type="hidden" id="saleStatus" name="saleStatus" value="'.PAYMENT_API_SALESTATUS_COMPLETED.'"/>
				';
			}

			$formHTML = parent::OutputContent_OnlinePurchaserDetails($cartContents, $formHTML);
			
			return $formHTML;
		}
		
		function OnlineStore_ScanCheckoutSales()
		{
			$discountCode = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleDiscountCode'); 
			$disDetails = $this->myDBaseObj->GetDiscountCode($discountCode);
						
			$rslt = parent::OnlineStore_ScanCheckoutSales();			
			
			// saleCustomValues includes user input for Custom values defined by the site designer
			// and the value of the "Note To Seller" if it is enabled
			if (StageShowLibUtilsClass::IsElementSet('post', 'saleCustomValues'))
			{
				$saleCustomValues = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleCustomValues'); 
				$rslt->saleDetails['saleNoteToSeller'] .= StageShowLibDBaseClass::_real_escape($saleCustomValues);
			}
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'salePostTickets'))
			{
				$salePostage = $this->myDBaseObj->getOption('PostageFee');
				$this->OnlineStore_AddExtraPayment($rslt, $salePostage, 'salePostage');				
			}	
			
			// Add extra payment (set if stageshow_filter_discount defined)
			$cartContents = $this->GetTrolleyContents();
			$this->OnlineStore_AddExtraPayment($rslt, -1*$cartContents->saleExtraDiscount, 'saleExtraDiscount');
			
			if (isset($rslt->checkoutMsg) && ($rslt->checkoutMsg != '')) 
			{
				return $rslt;
			}
			
			$rslt->saleDetails['saleDiscountCode'] = $disDetails;

			$cartContents = $this->GetTrolleyContents();
			if (isset($cartContents->rows))
			{
				$paramCount = 0;
				foreach ($cartContents->rows as $cartIndex => $cartEntry)
				{				
					$paramCount++;
					if (isset($cartEntry->customFields))
					{
						$rslt->customFields[$paramCount] = $cartEntry->customFields;
					}

					if (!$this->AreSeatsDefined($cartEntry))
					{
						$rslt->checkoutMsg = __('One or more seats is not selected', 'stageshow');
						return $rslt;
					}
					
					$rslt->saleDetails['saleGatewayIndex'] = $cartEntry->showGatewayIndex;
				}
				
			}
			
			return $rslt;
		}
					
		function OnlineStore_ProcessCheckout()
		{
			$myDBaseObj = $this->myDBaseObj;
				
			if ($myDBaseObj->IsButtonClicked('reserve'))	// checkout without online payment
			{
				$mode = $this->myDBaseObj->getOption('ReservationsMode');
				switch ($mode)
				{
					case STAGESHOW_RESERVATIONSMODE_LOGIN:
					case STAGESHOW_RESERVATIONSMODE_LOGINFORM:
						if (!current_user_can(STAGESHOWLIB_CAPABILITY_RESERVEUSER))
							return;
						// Get User details from User DB
						$loggedInUser = wp_get_current_user();										
						$user_metaInfo = get_user_meta($loggedInUser->ID);
						break;
										
					case STAGESHOW_RESERVATIONSMODE_FORM:
						break;
					
					case STAGESHOW_RESERVATIONSMODE_DISABLED:
					default:
						return;
				}
				
				unset($_POST['salePostTickets']);	// Reservations are never posted
				
				$checkoutRslt = $this->OnlineStore_ScanCheckoutSales();
				if (isset($checkoutRslt->checkoutMsg)) 
				{
					$this->checkoutMsg = $checkoutRslt->checkoutMsg;
					return;
				}

				// Lock tables so we can commit the pending sale
				$this->myDBaseObj->LockSalesTable();

				// Check quantities before we commit 
				$ParamsOK = $this->IsOnlineStoreItemAvailable($checkoutRslt);					
					
				if ($ParamsOK)
	  			{
					$saleDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
						
					// TODO - Make sure that TxnID is unique
					$saleTxnId = 'RES-'.time();	
					
					switch ($mode)
					{
						case STAGESHOW_RESERVATIONSMODE_LOGIN:
							$checkoutRslt->saleDetails['saleFirstName'] = $this->GetUserInfo($user_metaInfo, 'first_name');
							$checkoutRslt->saleDetails['saleLastName'] = $this->GetUserInfo($user_metaInfo, 'last_name');	
							$checkoutRslt->saleDetails['saleEMail'] = $loggedInUser->data->user_email;
							
							$checkoutRslt->saleDetails['salePPStreet']  = $this->GetUserInfo($user_metaInfo, 'UserAddress1');
							$checkoutRslt->saleDetails['salePPStreet'] .= $this->GetUserInfo($user_metaInfo, 'UserAddress2', "\n");
							$checkoutRslt->saleDetails['salePPStreet'] .= $this->GetUserInfo($user_metaInfo, 'UserAddress3', "\n");
								
							$checkoutRslt->saleDetails['salePPCity'] = $this->GetUserInfo($user_metaInfo, 'UserCity');
							$checkoutRslt->saleDetails['salePPState'] = $this->GetUserInfo($user_metaInfo, 'UserCounty');
							$checkoutRslt->saleDetails['salePPZip'] = $this->GetUserInfo($user_metaInfo, 'UserPostcode');
							$checkoutRslt->saleDetails['salePPCountry'] = $this->GetUserInfo($user_metaInfo, 'UserCountry');
							$checkoutRslt->saleDetails['salePPPhone'] = $this->GetUserInfo($user_metaInfo, 'UserPhone');			
							break;
							
						case STAGESHOW_RESERVATIONSMODE_FORM:
						case STAGESHOW_RESERVATIONSMODE_LOGINFORM:
							$checkoutRslt->saleDetails['saleFirstName'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-saleFirstName');
							$checkoutRslt->saleDetails['saleLastName'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-saleLastName');	
							$checkoutRslt->saleDetails['saleEMail'] = StageShowLibUtilsClass::GetHTTPEMail('post', 'checkoutdetails-saleEMail');
							
							$checkoutRslt->saleDetails['salePPStreet']  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-salePPStreet');
							$checkoutRslt->saleDetails['salePPCity'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-salePPCity');
							$checkoutRslt->saleDetails['salePPState'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-salePPState');
							$checkoutRslt->saleDetails['salePPZip'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-salePPZip');
							$checkoutRslt->saleDetails['salePPCountry'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-salePPCountry');
							$checkoutRslt->saleDetails['salePPPhone'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'checkoutdetails-salePPPhone');			
							break;
					}
					$checkoutRslt->saleDetails['saleTxnId'] = $saleTxnId;

					$checkoutRslt->saleDetails['salePaid'] = $checkoutRslt->totalDue;
					$checkoutRslt->saleDetails['saleFee'] = '0.0';
								
					$checkoutRslt->saleDetails['saleDateTime'] = $saleDateTime;
					$checkoutRslt->saleDetails['saleStatus'] = PAYMENT_API_SALESTATUS_RESERVED;

					$checkoutRslt->saleDetails['saleNoteToSeller'] = $checkoutRslt->saleDetails['saleNoteToSeller'];
									
					$checkoutRslt->saleDetails['salePPName'] = $myDBaseObj->GetSaleName($checkoutRslt->saleDetails);
					
					// No transaction fee with reservations ... remove it so it defaults to zero
					unset($checkoutRslt->saleDetails['saleTransactionFee']);
					
					// Log sale to DB
					$saleId = $this->myDBaseObj->LogSale($checkoutRslt, StageShowLibSalesDBaseClass::STAGESHOWLIB_LOGSALEMODE_RESERVE);
						
					$this->ClearTrolleyContents();	// Clear the shopping cart
					
					$this->checkoutMsg = __('Tickets reserved', 'stageshow').' - '.__('Confirmation EMail sent to', 'stageshow').' '.$checkoutRslt->saleDetails['saleEMail'];
					$this->checkoutMsgClass = 'stageshow'.'-ok';
				}
				else
				{
					$this->checkoutMsg = __('Cannot Checkout', 'stageshow').' - '.$this->checkoutMsg;
				}	
					
				// Release Tables
				$this->myDBaseObj->UnLockTables();	
				
				// Only send email after tables are unlocked
				if (isset($saleId))		
					$emailStatus = $this->myDBaseObj->EMailSale($saleId);
			}
					
			parent::OnlineStore_ProcessCheckout();
		}

		function OnlineStore_EMailSaleButton($saleDetails)
		{
			if (!current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER)
			  && !current_user_can(STAGESHOWLIB_CAPABILITY_ADMINUSER) )
			{
				return '';
			}
							
			return parent::OnlineStore_EMailSaleButton($saleDetails);
		}
		
		function OutputContent_GetAtts($atts)
		{
			$urlParamsAllowed = 0;
			$urlParamsPassed = 0;
			
			$atts_id = isset($atts['id']) ? $atts['id'] : '';
			$atts_perf = isset($atts['perf']) ? $atts['perf'] : '';
			
			if (StageShowLibMigratePHPClass::Safe_substr($atts_id, 0, 4) == 'url-')
			{
				$urlParamsAllowed++;
				$urlParamId = StageShowLibMigratePHPClass::Safe_substr($atts_id, 4);
				if (StageShowLibUtilsClass::IsElementSet('get', $urlParamId)) 
				{
					$urlParamsPassed++;
					$clientAtt = StageShowLibUtilsClass::GetHTTPTextElem('get', $urlParamId);
					if ($clientAtt[0] == '"')
					{
						$clientAtt = StageShowLibMigratePHPClass::Safe_substr($clientAtt, 1, StageShowLibMigratePHPClass::Safe_strlen($clientAtt)-2);
					}
					$atts['id'] = $clientAtt;
					$atts['perf'] = '';
				}
				else
				{
					$atts['id'] = '';
				}
			}

			if (StageShowLibMigratePHPClass::Safe_substr($atts_perf, 0, 4) == 'url-')
			{
				$urlParamsAllowed++;
				$urlParamId = StageShowLibMigratePHPClass::Safe_substr($atts_perf, 4);
				if (StageShowLibUtilsClass::IsElementSet('get', $urlParamId)) 
				{
					$urlParamsPassed++;
					$clientAtt = StageShowLibUtilsClass::GetHTTPTextElem('get', $urlParamId);
					if ($clientAtt[0] == '"')
					{
						$clientAtt = StageShowLibMigratePHPClass::Safe_substr($clientAtt, 1, StageShowLibMigratePHPClass::Safe_strlen($clientAtt)-2);
					}
					$atts['perf'] = $clientAtt;
				}
				else
				{
					$atts['perf'] = '';
				}
			}
		
			if (($urlParamsAllowed > 0) && ($urlParamsPassed == 0))
			{
			}

			return parent::OutputContent_GetAtts($atts);
		}
		
		function GetOnlineStoreMaxSales($result)
		{
			if ($result->seatingID > 0)
			{
				// Return number of seats in zone
				return $result->zoneSeats;
			}
	
			return parent::GetOnlineStoreMaxSales($result);
		}
			
		function GetOnlineStoreTrolleyDetails($cartIndex, $cartEntry)
		{
			$saleDetails = parent::GetOnlineStoreTrolleyDetails($cartIndex, $cartEntry);
			if (isset($cartEntry->seatLocns))
			{
				$saleDetails['itemDetail' . $cartIndex] = $cartEntry->seatLocns;				
				$saleDetails['perfID' . $cartIndex] = $cartEntry->perfID;				
			}
			
			return $saleDetails;
		}

		function IsOnlineStoreItemAvailable($saleItems)
		{
			$myDBaseObj = $this->myDBaseObj;

			$ParamsOK = true;
			
			$unzonedSaleItems = new stdClass();
			$unzonedSaleItems->totalSales = array();
			$unzonedSaleItems->maxSales = array();
			
			// If using Allocated Seating ... check that seats are still available
			$itemNo = 0;
			while (true)
			{
				$itemNo++;
				if (!isset($saleItems->saleDetails['itemID'.$itemNo]))
					break;
					
				$perfID = $saleItems->saleDetails['perfID'.$itemNo];
				$priceID = $saleItems->saleDetails['itemID'.$itemNo];
				
				$perfAndZoneID = isset($saleItems->invIDs[$itemNo]) ? $saleItems->invIDs[$itemNo] : $perfID ;
				$idParts = explode('-', $perfAndZoneID);
				
				if ((count($idParts) != 2) || !isset($saleItems->saleDetails['itemDetail'.$itemNo]))
				{
					// Performance does not have seating plan
					$unzonedSaleItems->totalSales[$perfID] = $saleItems->totalSales[$perfID];
					$unzonedSaleItems->maxSales[$perfID] = $saleItems->maxSales[$perfID];
					continue;
				}
					
				$seatID = $saleItems->saleDetails['itemDetail'.$itemNo];
				if ($seatID == '')
				{	
					$perfID = $idParts[0];
					$zoneID = $idParts[1];
					// Get seatingID, zoneID and zoneAllocSeats from PriceID
					
					$qty = $saleItems->saleDetails['qty'.$itemNo];

					$zoneSaleQty  = $this->myDBaseObj->GetSalesQtyByPerfAndZone($perfID, $zoneID);
					$zoneSaleQty += $qty;
					$seatsAvailable = $saleItems->maxSales[$perfAndZoneID];
					
					if ( ($seatsAvailable > 0) && ($seatsAvailable < $zoneSaleQty) ) 
					{
						$this->checkoutMsg = __('Cannot Checkout', 'stageshow').' - ';
						$this->checkoutMsg .= __('Sold out for one or more items', 'stageshow');
						$ParamsOK = false;
					}
				}
				else
				{
					if (!$myDBaseObj->IsSeatAvailable($perfID, $seatID))
					{
						$this->checkoutMsg = __('One or more seats selected is no longer available', 'stageshow');
						$ParamsOK = false;
						break;										
					}					
				}				
			}
			
			if ($ParamsOK)
			{
				$ParamsOK = parent::IsOnlineStoreItemAvailable($unzonedSaleItems);
			}

			return $ParamsOK;
		}
			
		function OnlineStore_AddCustomFieldValues($cartContents)
		{
			if (!isset($cartContents->extraFields)) return;
			
			if (!isset($cartContents->rows)) return;
			
			$firstKey = $cartContents->extraFields[0];
			$keyPrefixLen = StageShowLibMigratePHPClass::Safe_strpos($firstKey, "_customcoItem_");
			$keyPrefixLen += StageShowLibMigratePHPClass::Safe_strlen("_customcoItem_");
			$keyPrefix = StageShowLibMigratePHPClass::Safe_substr($firstKey, 0, $keyPrefixLen);
			foreach ($cartContents->rows as $cartIndex => $cartEntry)
			{
				$saleExtras = array();
				foreach ($cartContents->extraFields as $fieldID)
				{
					$postID = $fieldID.$cartIndex;
					if (!StageShowLibUtilsClass::IsElementSet('request', $postID))
					{
						// Could be a "QuickSale" - Uses different indexes for custom fields
						$postID = $fieldID.$cartEntry->seatLocns;
					}
					$postVal = StageShowLibDBaseClass::GetSafeString($postID);
					
					$dbfieldID = StageShowLibMigratePHPClass::Safe_substr($fieldID, $keyPrefixLen);
					$saleExtras[$dbfieldID] = $postVal;
				}
				
				$cartContents->rows[$cartIndex]->customFields = $saleExtras;
			}

			$this->SaveTrolleyContents($cartContents, __LINE__);
		}
		
	}
}





