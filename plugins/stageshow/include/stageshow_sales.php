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

if (!class_exists('StageShowSalesCartPluginClass')) 
	include STAGESHOW_INCLUDE_PATH.'stageshow_trolley_sales.php';
	
include 'stageshowlib_salesplugin.php';
	
if (!class_exists('StageShowSalesPluginClass')) 
{
	class StageShowSalesPluginClass extends StageShowSalesCartPluginClass // Define class
	{
		function __construct()
		{
			$this->cssBaseID = "stageshow-boxoffice";
			$this->stockAnchor = "boxoffice";
		
			if (defined('STAGESHOW_SHORTCODE'))
			{
				$this->shortcode = STAGESHOW_SHORTCODE;
				$this->dbshortcode = $this->shortcode.'-db';
			}
			else
			{
				$this->shortcode = STAGESHOW_SHORTCODE_PREFIX."-boxoffice";
				$this->dbshortcode = STAGESHOW_SHORTCODE_PREFIX.'-db';
			}
			
	  		// FUNCTIONALITY: Runtime - Load StageShow custom language file
			load_plugin_textdomain('stageshow', false, STAGESHOW_LANG_RELPATH);
			
			parent::__construct();
		}
	
		function OutputContent_GetAtts( $atts )
		{
			$atts = shortcode_atts(array(
				'id'    => '',
				'perf'  => '',
				'count' => '',
				'months' => '',
				'cols' => '',
				'separate' => '',
				'anchor' => '',
				'style' => 'normal' 
			), $atts );
        
			if (($atts['id'] != '') && !is_numeric($atts['id']))
			{
				$showID = $this->myDBaseObj->GetShowID(StageShowLibMigratePHPClass::Safe_htmlspecialchars_decode($atts['id']));
				if ($showID > 0) 
					$atts['id'] = $showID;
				else
				{
					if (current_user_can(STAGESHOWLIB_CAPABILITY_ADMINUSER))
					{
						$scShowErrorMsg = __("Shortcode Specifies Non-existant Show", 'stageshow')." (".$atts['id'].")";
						StageShowLibEscapingClass::Safe_EchoHTML("<div id=NonExistantShowError class=stageshow-error>$scShowErrorMsg</div>\n");											
					}
					$atts['id'] = '';
				}
			}
			
        	return $atts;
		}
		
		function OutputContent_TrolleyJQueryPostvars()
		{
			return parent::OutputContent_TrolleyJQueryPostvars();			
		}
	
		function OutputContent_DoShortcode($atts, $isAdminPage=false)
		{
			return parent::OutputContent_DoShortcode($atts);
		}

		function OutputContent_OnlineStoreFooter()
		{
			if ($this->adminPageActive)
				return;
				
			$url = $this->myDBaseObj ->get_pluginURI();
			$name = $this->myDBaseObj ->get_pluginName();
			$weblink = __('Driven by').' <a target="_blank" href="'.$url.'">'.$name.'</a>';
			
			return '<div class="stageshow-boxoffice-weblink">'.$weblink.'</div>'."\n";
		}
		
		function GetOnlineStoreMaxSales($result)
		{
			return $result->perfSeats;
		}
			
		function IsOnlineStoreItemAvailable($saleItems)
		{
			$ParamsOK = true;
			$this->checkoutMsg = '';
			
			// Check quantities before we commit 
			foreach ($saleItems->totalSales as $perfID => $qty)
			{						
				$perfSaleQty  = $this->myDBaseObj->GetSalesQtyByPerfID($perfID);
				$perfSaleQty += $qty;
				$seatsAvailable = $saleItems->maxSales[$perfID];
				if ( ($seatsAvailable > 0) && ($seatsAvailable < $perfSaleQty) ) 
				{
					$this->checkoutMsg = __('Sold out for one or more performances', 'stageshow');
					$ParamsOK = false;
					break;
				}
			}
			
			return $ParamsOK;
		}
		
		function GetUserInfo($user_metaInfo, $fieldId, $fieldSep = '')
		{
			if (isset($this->myDBaseObj->adminOptions[$fieldId]))
			{
				$metaField = $this->myDBaseObj->adminOptions[$fieldId];
			}
			else
			{
				$metaField = $fieldId;
			}
			
			if ($metaField == '')
				return '';
				
			if (!isset($user_metaInfo[$metaField][0]))
				return $fieldSep == '' ? __('Unknown', 'stageshow') : '';
			
			$userInfoVal = 	$user_metaInfo[$metaField][0];
			return $fieldSep.$userInfoVal;
		}
		
		function OnlineStore_AddExtraPayment(&$rslt, $amount, $detailID)
		{
			if (($rslt->totalDue > 0) && ($amount != 0))
			{
				$rslt->totalDue += $amount;
				$rslt->saleDetails[$detailID] = $amount;			
			}
			else
			{
				$rslt->saleDetails[$detailID] = 0;				
			}	
		}
		
		function OutputContent_PIRShortcode($atts)
		{
			include STAGESHOW_INCLUDE_PATH.'stageshow_sc_pir.php';
			
			$pirObj = new StageShowPIRShortcodeClass($this);
			$outputContent = $pirObj->Output($atts);
			
			return $outputContent;
		}
		
	}
}


