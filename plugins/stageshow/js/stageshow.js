/* 
Description: StageShowGold Javascript Code
 
Copyright 2016 Malcolm Shergold

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

function StageShowLib_GetClassID()
{
	return 'stageshow';
}

function  stageshow_OnClickClosewindow(obj, inst)
{
	window.close();
}

function stageshow_OnClickAdd(obj, inst)
{
	if (typeof stageshowCustom_OnClickAdd == 'function') 
	{ 
  		return stageshowCustom_OnClickAdd(obj, inst); 
	}	

	rtnVal = StageShowLib_JQuery_OnClickTrolleyButton(obj, inst, "stageshowlib_jquery_callback"); 
	
	return rtnVal;
}
/*
function stageshow_DiscountChange(obj, inst)
{
	discountInst = inst;
	setTimeout("discountChangeTimeout", 1000);
	rtnVal = StageShowLib_JQuery_OnClickTrolleyButton(obj, inst, "stageshowlib_jquery_callback");
	return rtnVal;
}
*/	
function stageshow_OnClickUpdateprices(obj, inst)
{
	rtnVal = StageShowLib_JQuery_OnClickTrolleyButton(obj, inst, "stageshowlib_jquery_callback");
	return rtnVal;
}
								
function stageshowlib_jquery_callback(data, inst, buttonId, qty)
{
	StageShowLib_JQuery_Callback(data, inst, buttonId, qty);
	
	stageshow_OnClickSelectshow(lastSelectShowObj);
	stageshow_OnClickSelectperf(lastSelectPerfObj);
}

var stageshow_scrollPosn;

function stageshow_OnClickReserve(obj)
{
	if (typeof stageshowCustom_OnClickReserve == 'function') 
	{ 
  		return stageshowCustom_OnClickReserve(obj); 
	}	
	StageShowLib_BeforeSubmit(obj, stageshowlib_cssDomain);
	return true;
}

function stageshow_OnClickCheckout(obj)
{
	if (typeof stageshowCustom_OnClickCheckout == 'function') 
	{ 
  		return stageshowCustom_OnClickCheckout(obj); 
	}
	StageShowLib_BeforeSubmit(obj, stageshowlib_cssDomain);
	
	var uiElemSpec = "#saleDiscountCode";
	var uiElem = jQuery(uiElemSpec);			
	uiElem.prop("disabled", false);	
			
	return true;
}

function stageshow_OnClickCheckoutdetails(obj)
{
	if (typeof stageshowCustom_OnClickCheckoutdetails == 'function') 
	{ 
  		return stageshowCustom_OnClickCheckoutdetails(obj); 
	}
	return true;
}

function stageshow_OnLoadSubmitDetailsForm(obj)
{
	if (typeof stageshowCustom_OnLoadSubmitDetailsForm == 'function') 
	{ 
  		return stageshowCustom_OnLoadSubmitDetailsForm(obj); 
	}
}

function stageshow_OnClickReserveform(obj)
{
	if (typeof stageshowCustom_OnClickReserveform == 'function') 
	{ 
  		return stageshowCustom_OnClickReserveform(obj); 
	}

	if (!stageshow_ValidateCheckoutForm(obj))
	{
		return false;
	}

	StageShowLib_BeforeSubmit(obj, stageshowlib_cssDomain);

	return true;
}

function stageshow_OnClickSubmitDetails(obj)
{
	if (typeof stageshowCustom_OnClickSubmitDetails == 'function') 
	{ 
  		return stageshowCustom_OnClickSubmitDetails(obj); 
	}
	
	/* check elements with stageshow_customco class */
	return stageshow_ValidateCheckoutForm(obj);
}

function stageshow_OnClickRemove(obj, inst)
{
	if (typeof stageshowCustom_OnClickRemove == 'function') 
	{ 
  		return stageshowCustom_OnClickRemove(obj, inst); 
	}
	
	return StageShowLib_JQuery_OnClickTrolleyButton(obj, inst, "stageshowlib_jquery_callback"); 
}

function stageshow_OnClickValidate()
{
	if (typeof StageShowLib_JQuery_Blocked != 'undefined') 
	{ 
  		return true; 
	}	

	if (typeof stageshowCustom_OnClickValidate == 'function') 
	{ 
  		return stageshowCustom_OnClickValidate(); 
	}
	
	return stageshow_JQuery_OnClickValidate(); 
}

function stageshow_PurgeDrilldownAtts(newAtts)
{
	for (var index=0; index<stageshowlib_attStrings.length; index++) 
	{
		var origAtts = stageshowlib_attStrings[index];
		origAtts = origAtts.split(",");
		
		for (var attId=0; attId<origAtts.length; attId++) 
		{
			var thisAtt = origAtts[attId].split("=");
			var key = thisAtt[0];
			
			if (key == "scatt_dd_id") continue;
			if (key == "scatt_dd_perf") continue;

			var attval = thisAtt[1];
			newAtts = newAtts + ',' + key + '=' + attval;
		}
		stageshowlib_attStrings[index] = newAtts;
	}	
}

function stageshow_DrilldownLoaded()
{
	if (typeof stageshowCustom_DrilldownLoaded == 'function') 
	{ 
  		return stageshowCustom_DrilldownLoaded(); 
	}
	
	var showButtonObjList = jQuery("[id^='stageshow-selbutton-show-']");
	var showButtonCount = showButtonObjList.length + 0;
	if (showButtonCount == 1)
	{
		stageshow_OnClickSelectshow(showButtonObjList[0],0);
	}
}

var	lastSelectShowObj = null;

function stageshow_OnClickSelectshow(obj, inst)
{
	if (obj == null) return;
	
	lastSelectShowObj = obj;
	
	jQuery(".stageshow-selector-showbutton").show();
	jQuery(".stageshow-selector-perfrow").hide();
	
	var ourName = obj.id;
	var perfRowClass = ourName.replace("stageshow-selbutton-show-", "stageshow-selector-perfrow-");
	jQuery("."+perfRowClass).show();
	jQuery("#"+obj.id).hide();
}

var	lastSelectPerfObj = null;

function stageshow_OnClickSelectperf(obj, inst)
{
	if (obj == null) return;
	
	lastSelectPerfObj = obj;
	
	var ourName = obj.id;
	var show_perf_parts = ourName.replace("stageshow-selbutton-perf-", "").split("-");;
	var showID = show_perf_parts[0];
	var perfID = show_perf_parts[1];
	
	var newAtts = "scatt_dd_id="+showID+",scatt_dd_perf="+perfID+",";
	stageshow_PurgeDrilldownAtts(newAtts);
	
	jQuery("#stageshow-selector-table").hide();

	jQuery(".stageshow-boxoffice-row").hide();	
	jQuery(".stageshow-boxoffice-perfnote").hide();	
	
	var rowsClassId = ".stageshow-boxoffice-row-perf" + perfID;	
	jQuery(rowsClassId).show();
	
	var showDivId = "#stageshow-boxoffice-body-" + showID;
	jQuery(showDivId).show();
	
	jQuery("#stageshow-selbutton-back-div").show();
}

function stageshow_OnClickSelectorback()
{
	lastSelectPerfObj = null;

	stageshow_PurgeDrilldownAtts('');
	
	/* Hide the button */
	jQuery("#stageshow-selbutton-back-div").hide();

	/* Hide all box-office ticket entries */
	jQuery(".stageshow-boxoffice-body").hide();

	/* Show the selector */
	jQuery("#stageshow-selector-table").show();
}

function stageshow_OnLoadPurchaserDetailsForm()
{
	/* Get list of elements with stageshow_customco class */
	var classSpec = ".stageshow_customco";
	stageshow_OnLoadPrecheckoutForm(classSpec, null);
}

function stageshow_OnLoadPrecheckoutForm(classSpec, initValuesArray)
{
	var buttonElemsList = jQuery(classSpec);
	var initValues = (initValuesArray != null);
	
	/* Add event handlers */
	jQuery.each(buttonElemsList,
		function(i, listObj) 
		{
			/* Get the input format of the control */
			var ctrldata = stageshow_GetDetailsCtrlDefs(listObj);
			if (ctrldata.type !== '')
			{
				var uiElemName = listObj.name;
				var uiElemSpec = "#" + uiElemName;
				jQuery(uiElemSpec).keypress(ctrldata, stageshow_OnKeypressCheckoutDetails);
				jQuery(uiElemSpec).on('paste', ctrldata, stageshow_OnPasteCheckoutDetails);
				
				if (initValues && (typeof initValuesArray[uiElemName] != 'undefined'))
				{
					listObj.value = initValuesArray[uiElemName];
				}
			}
		}
	);
}

function stageshow_ValidateCheckoutForm(obj)
{
	/* Get list of elements with stageshow_customco class */
	var classSpec = ".stageshow_customco";
	var buttonElemsList = jQuery(classSpec);
	var status = true;
	
	/* Add event handlers */
	for (var i=0; i<buttonElemsList.length; i++)
	{
		var listObj = buttonElemsList[i];
		
		/* Get the <tr> element that this element is inside */
		var trElem = listObj.closest('tr');
		var trVisible = jQuery(trElem).css('visibility');
		var trDisplay = jQuery(trElem).css('display');
		
		/* Get the input format of the control */
		var ctrldata = stageshow_GetDetailsCtrlDefs(listObj);
		if ((ctrldata.type !== '') && (trDisplay != 'none'))
		{
			var ctrlText = '';
			var ctrlLen = 0;
			
			switch (ctrldata.type)
			{
				case 'select':
				case 'select-one':
					ctrlText = listObj.selectedIndex;
					ctrlLen = 1;
					if (ctrldata.minval == 'undef') ctrldata.minval = 0;
					break;
					
				case 'text':
					ctrlText = listObj.value;
					ctrlLen = ctrlText.length;
					if (ctrldata.minval == 'undef') ctrldata.minval = 1;
					break;
				
				default:					
					ctrlText = listObj.value;
					ctrlLen = ctrlText.length;
					break;
			}
			
			if ( (ctrldata.minchars != 'undef') && (ctrlLen < ctrldata.minchars) )
			{
				if (ctrlLen === 0)
					stageshow_ShowError(listObj, 'Entry is required');
				else
					stageshow_ShowError(listObj, 'Entry is too short');
				status = false;
				return false;
			}					
			
			if ( (ctrldata.maxchars != 'undef') && (ctrlLen > ctrldata.maxchars) )
			{
				stageshow_ShowError(listObj, 'Entry is too long');
				status = false;
				return false;
			}					
			
			if ( (ctrldata.minval != 'undef') && (ctrlText < ctrldata.minval) )
			{
				stageshow_ShowError(listObj, 'Entry is required');
				status = false;
				return false;
			}					
			
			if (!StageShowLib_ElemValueCheck(ctrldata.type, ctrlText))
			{
				stageshow_ShowError(listObj, 'Entry has unexpected/missing character(s)');
				status = false;
				return false;
			}					
		
		}
		
	}
	
	stageshow_ShowError(null, '');
	return true;
}

function StageShowLib_KeyCheck(crtlType, key)
{
	var rtnVal = true;
	var regex = '';
	
	switch (crtlType)
	{
		case 'alpha':
			regex = /^[a-zA-Z\-\.\s\']*$/;
			break;
			
		case 'decimal':
			regex = /^[0-9]*$/;
			break;
			
		case 'email':
			regex = /^[a-zA-Z0-9_\-\.@]+$/;
			break;
			
		case 'numeric':
			regex = /^[0-9\.]+$/;
			break;
			
		default:
			return true;
	}
	
	if (regex != '')
	{
		var posn = key.search(regex);
		rtnVal = (posn >= 0);
	}

	if (rtnVal)	
		StageShowLib_EnableControls(null, '');
	
	return rtnVal;
}

function StageShowLib_ElemValueCheck(crtlType, value)
{
	var rtnVal = true;
	var regex = '';
	
	switch (crtlType)
	{
		case 'alpha':
			regex = /^[a-zA-Z\-\.\s\']*$/;
			break;
			
		case 'decimal':
			regex = /^[0-9]*$/;
			break;
			
		case 'email':
			regex = /^[a-zA-Z0-9_\-\.]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9\-]+$/;
			break;
			
		case 'numeric':
			regex = /^[0-9\.]+$/;
			break;
			
		default:
			return true;
	}
	
	if (regex != '')
	{
		var posn = value.search(regex);
		rtnVal = (posn >= 0);
	}

	return rtnVal;
}

function stageshow_OnKeypressCheckoutDetails(event)
{
	if (event.altKey || event.ctrlKey)
	{
		return true;
	}
	
	switch (event.keyCode)
	{
		case 13:
			event.preventDefault();
			return false;
			
		case 8:
		case 46:
			return true;
	}
	
	stageshow_ShowError(null, '');
	
	var crtlType = event.data.type;
	return StageShowLib_KeyCheck(crtlType, event.key);
}

function stageshow_OnPasteCheckoutDetails(event)
{
	stageshow_ShowError(null, '');
	
	return false;
}

function stageshow_ShowError(obj, msg)
{
	var errorElems = jQuery('.stageshow-trolley-ui-error');
	errorElems.removeClass('stageshow-trolley-ui-error');
	
	var statusElem = jQuery('#CheckoutFormStatus');	
	if ((msg === '') || (obj == null))
	{
		statusElem[0].innerText = '';
		statusElem.hide();
		return;		
	}
	
	var errorField;
	if (("alt" in obj) && (obj.alt != ''))
	{
		errorField = obj.alt;
	}
	else
	{
		errorField = obj.name.replace('stageshow_customcoItem_', '');
		errorField = errorField.replace('_', ' ');
	}
	var errmsg = ' Error: ' + errorField + ' - ' + msg;

	statusElem[0].innerText = errmsg;
	statusElem.show();
	
	jQuery('#'+obj.id).addClass("stageshow-trolley-ui-error");		
}

function stageshow_capitalize_Words(str)
{
	return StageShowLib_capitalize_Words(str);
}

function stageshow_getAllUrlParams(url) 
{
	return StageShowLib_getAllUrlParams(url);
}

/* Seat Selector class definitions - Redefined if STAGESHOW_CLASS_BOXOFFICE_***** values are defined */
var SeatUnknownClassText = 'stageshow-boxoffice-seat-unknown';
var SeatAvailableClassText = 'stageshow-boxoffice-seat-available';
var SeatRequestedClassText = 'stageshow-boxoffice-seat-requested';
var SeatReservedClassText = 'stageshow-boxoffice-seat-reserved';	// Used for Both Booked & Reserved Seats
var SeatAllocatedClassText = 'stageshow-boxoffice-seat-allocated';
var SeatBookedClassText = 'stageshow-boxoffice-seat-booked';
var SeatLockedClassText = 'stageshow-boxoffice-seat-locked';
var SeatDisabledClassText = 'stageshow-boxoffice-seat-disabled';

var SeatLayoutClassText = 'stageshow-boxoffice-layout-seat-';

/* Seat Selector id definitions - Never Redefined */
var SeatCountBlockIdRoot = "stageshow-boxoffice-zoneSeatsBlock";
var SeatLayoutBlockId = "#stageshow-boxoffice-seats";
var SeatsLoadingBlockId = "#stageshow-boxoffice-loading";

var SeatStateInvalid = -1;
var SeatStateAvailable = 0;
var SeatStateRequested = 1;
var SeatStateReserved = 2;
var SeatStateAllocated = 3;
var SeatStateBooked = 4;
var SeatStateDisabled = 5;

var SeatLeftEndClass = 'stageshow-boxoffice-leftend';
var SeatRightEndClass = 'stageshow-boxoffice-rightend';

var hasEndLimitTags;

var zonesReq = new Array();

var limitSelectedCount = true;

var lastSeatId = '';

var errorObj = null;

function stageshow_OnSeatsLoad()
{
	/* Check if Block End Markers are defined */
	var elemsList = jQuery("."+SeatLeftEndClass);
	hasEndLimitTags = (elemsList.length > 0);
	
	stageshow_InitialiseSeats();	
		
	jQuery(SeatsLoadingBlockId).hide();
	jQuery(SeatLayoutBlockId).show();
	jQuery('#trolley').css("visibility", "visible"); 
	jQuery('#stageshow-trolley-trolley-std').show();

	if (typeof stageshowCustom_OnSeatsLoad == 'function')
	{ 
  		stageshowCustom_OnSeatsLoad(); 
	}	
	
}

function stageshow_InitialiseSeats()
{
	/* pageMode is not defined if AJAX call generates fatal error */
	if (typeof pageMode != 'undefined')
	{
		switch (pageMode)
		{
			case 'lockoutseats':
			case 'quicksale':
				limitSelectedCount = false;
				break;
				
			default:
				limitSelectedCount = true;
				break;
		}
	}
	
	/* Clear hidden pass back values - Required if page is refreshed */
	var seatsSelectedElem;
	seatsSelectedElem = document.getElementById('stageshow-seatselected-seats');
	if (!seatsSelectedElem) return;
	var zonesSelectedElem;
	zonesSelectedElem = document.getElementById('stageshow-seatselected-zones');
	if (!zonesSelectedElem) return;
	
	seatsSelectedElem.value = '';
	zonesSelectedElem.value = '';
	
	seatsRequestedCount = 0;
	for (var zoneID in zones)
	{
		zonesReq[zoneID] = zones[zoneID];
		seatsRequestedCount += zones[zoneID];
	}

	/* Note: Uses maxRows and maxCols which must be defined in template */
	var row, col;
	for (row=1; row<=maxRows; row++)
	{
		for (col=1; col<=maxCols; col++)
		{
			var seatId = row + '_' + col;
			var seatFullId = "#" + SeatLayoutClassText + seatId;
			var seatObj = document.getElementById(SeatLayoutClassText + seatId);
			
			if (seatObj != null)
			{
				var className  = seatObj.className.replace(SeatUnknownClassText, '');
				var zoneID = stageshow_IsZoneValid(seatObj);
				
				if (zoneID > 0)
				{					
					switch (stageshow_SeatAvailability(seatId))
					{
						case '': 
							seatObj.alt = seatObj.title + " " + SeatAltAvailable;
							seatObj.className = SeatAvailableClassText + ' ' + className;
							break;
							
						case 'selected': 
							seatObj.alt = seatObj.title + " " + SeatAltRequested;
							seatObj.className = SeatAvailableClassText + ' ' + className;
							stageshow_ToggleSeat(seatObj, false);
							break;
							
						case 'allocated': 
							seatObj.alt = seatObj.title + " " + SeatAltAllocated;
							seatObj.className = SeatAllocatedClassText + ' ' + className;
							break;
							
						case 'booked': 
							seatObj.alt = seatObj.title + " " + SeatAltBooked;
							seatObj.className = SeatBookedClassText + ' ' + className;
							break;
							
						case 'locked': 
							seatObj.alt = seatObj.title + " " + SeatAltLocked;
							seatObj.className = SeatLockedClassText + ' ' + className;
							break;
							
						default: 
							seatObj.alt = seatObj.title + " " + SeatAltReserved;
							seatObj.className = SeatReservedClassText + ' ' + className;
							break;							
					}

					var meta = stageshow_MetaFromObj(seatObj);
					if (meta != '') 
					{
						seatObj.title = meta;
						seatObj.alt = meta;
					}
				}
				else
				{
					/* Zone not found for seat */
					seatObj.alt = seatObj.title + " " + SeatAltDisabled;
					seatObj.className = SeatDisabledClassText + ' ' + className;					
				}
			}
			else
			{
				/* Could not load seat object */
				missingSeatId = seatId;
			}
		}
	}
	
	for (var zoneID in zones) 
	{
		stageshow_UpdateZonesCount(zoneID, zonesReq[zoneID], zones[zoneID]);		
	}
	
	if ((seatsRequestedCount == 0) || !limitSelectedCount)
	{
		jQuery('#' + SeatCountBlockIdRoot).hide();
	}		
}

function stageshow_OnClickSeatsSelectorButton(obj)
{
	if (typeof StageShowLib_JQuery_Blocked != 'undefined') 
	{ 
  		return true; 
	}	

	var postvars = {
		jquery: "true"
	};
	
	switch(obj.id)
	{
		case "selectseats":		
			stageshow_scrollPosn = jQuery(window).scrollTop();
			jQuery('#trolley').css("visibility", "hidden"); 
			jQuery('#stageshow-trolley-trolley-std').hide();
			jQuery(SeatsLoadingBlockId).css("padding-top", "");
			break;
			
		case "seatsselected":
			postvars["PerfId"] = jQuery("#PerfId").val();
			postvars["stageshow-seatselected-seats"] = jQuery("#stageshow-seatselected-seats").val();
			postvars["stageshow-seatselected-zones"] = jQuery("#stageshow-seatselected-zones").val();		

			var seatSelectorHeight = jQuery('#stageshow-trolley-trolley-std').outerHeight();
			var loadingHeight = jQuery(SeatsLoadingBlockId).outerHeight();
			var padding = seatSelectorHeight - loadingHeight;
			
			jQuery(SeatsLoadingBlockId).css("padding-top", padding + "px");
			loadingHeight = jQuery(SeatsLoadingBlockId).outerHeight();

			jQuery('#stageshow-trolley-trolley-std').hide();
			break;
			
		default:
			break;
	}
	
	jQuery(SeatsLoadingBlockId).show();
			
	return StageShowLib_JQuery_ActionTrolleyButton(obj, 1, postvars, "stageshow_SeatsSelectorCallback");
}

function stageshow_SeatsSelectorCallback(data, inst, buttonId, qty)
{
	/* Call the standard callabck function */
	StageShowLib_JQuery_Callback(data, inst, buttonId, qty);
	
	switch(buttonId)
	{
		case "selectseats":
			stageshow_OnSeatsLoad();
			break;	
			
		case "seatsselected":
			stageshow_OnClickSelectshow(lastSelectShowObj);
			stageshow_OnClickSelectperf(lastSelectPerfObj);
		
			jQuery(SeatsLoadingBlockId).hide();
			jQuery(SeatLayoutBlockId).show();
			jQuery('#trolley').css("visibility", "visible"); 
			jQuery('#stageshow-trolley-trolley-std').show();
			
			/* Scroll back to the position before seats selection */
			jQuery(window).scrollTop(stageshow_scrollPosn);
			break;	
			
		default:
			break;	
	}
	
}

function stageshow_OnClickSelectseats(obj)
{
	if (typeof stageshowCustom_OnClickSelectseats == 'function') 
	{ 
  		return stageshowCustom_OnClickSelectseats(obj); 
	}	

	rtnVal = stageshow_OnClickSeatsSelectorButton(obj); 		
	return rtnVal;
}

function stageshow_OnClickSeatsselected(obj)
{
	if (typeof stageshowCustom_OnClickSeatsselected == 'function') 
	{ 
  		return stageshowCustom_OnClickSeatsselected(obj); 
	}	
	
	rtnVal = stageshow_OnClickSeatsSelectorButton(obj); 		
	return rtnVal;
}

function stageshow_SetSeatClass(rowNo, colNo, newClass)
{
	fullId = "#" + SeatLayoutClassText + rowNo + '_' + colNo;
	seatJObj = jQuery(fullId);
	seatJObj.removeClass(SeatUnknownClassText);
	seatJObj.addClass(newClass);
}

function stageshow_SeatAvailability(seatId)
{
	var allocatedIndex = jQuery.inArray(seatId, allocatedSeats);
	if (allocatedIndex >= 0) 
	{
		return 'allocated';
	}
	
	var bookedIndex = jQuery.inArray(seatId, bookedSeats);
	if (bookedIndex >= 0) 
	{
		return 'booked';
	}
	
	var lockedIndex = jQuery.inArray(seatId, lockedSeats);
	if (lockedIndex >= 0) 
	{
		return 'locked';
	}
	
	var reservedIndex = jQuery.inArray(seatId, reservedSeats);
	if (reservedIndex >= 0) 
	{
		return 'reserved';
	}
	
	var selectedIndex = jQuery.inArray(seatId, selectedSeats);
	if (selectedIndex >= 0) 
	{
		return 'selected';
	}
	
	return '';
}

function stageshow_GetZoneNo(obj)
{
	var className = obj.className;
	var posn = className.indexOf("stageshow-boxoffice-zone");
	className = className.slice(posn+24);
	var zoneTemp = className.split(" ");
	var zoneNo = zoneTemp[0];
	return zoneNo;
}

function stageshow_IsZoneValid(obj)
{
	var chkZoneID = stageshow_GetZoneNo(obj);
	if ((chkZoneID > 0) && (zones[chkZoneID] >= 0))
	{
		return parseInt(chkZoneID);		
	}
		
	return 0;
}

function stageshow_InitSeatFromTrolley(obj)
{
	stageshow_ClickSeat(obj);
}

function stageshow_IsSeatState(obj, srchState)
{
	thisSeatClass = obj.className;
	return (thisSeatClass.indexOf(srchState) > -1);
}

function stageshow_GetSeatState(obj)
{
	thisSeatClass = obj.className;
	if (thisSeatClass.indexOf(SeatRequestedClassText) > -1)
	{
		return SeatStateRequested;
	}
	
	if (thisSeatClass.indexOf(SeatAllocatedClassText) > -1)
	{
		return SeatStateAllocated;
	}
	
	if (thisSeatClass.indexOf(SeatReservedClassText) > -1)
	{
		return SeatStateReserved;
	}
	
	if (thisSeatClass.indexOf(SeatBookedClassText) > -1)
	{
		return SeatStateBooked;
	}
	
	if (thisSeatClass.indexOf(SeatDisabledClassText) > -1)
	{
		return SeatStateDisabled;
	}
	
	return SeatStateAvailable;
}

function stageshow_CheckClickSeat(obj)
{
	if (!hasEndLimitTags)
		return true;
	
	if (typeof stageshowCustom_CheckClickSeat == 'function') 
	{ 
  		return stageshowCustom_CheckClickSeat(obj); 
	}
	
	if (minSeatSpace <= 0)
		return true;
		
	seatPosnParts = obj.id.split("_");
	clickedColNo = parseInt(seatPosnParts[1]);
	
	var seatsStates = [];
	var limits = [];
	
	/* Get the new state of the seat just clicked */
	seatState = stageshow_GetSeatState(obj);
	switch (seatState)
	{
		case SeatStateAvailable:
			seatState = SeatStateRequested;
			break;	
			
		case SeatStateRequested:
			seatState = SeatStateAvailable;
			break;	
			
		default:
			return false;
	}
	seatsStates[clickedColNo] = seatState;
	
	/* 
		Scan this row both ways - Stop at one of the following conditions:
		When the seat is at the end of the row
		When the seat is next to an aisle
		When the next seat is a Reserved Seat
	*/
	availSeatsCount = 0;	
	for (loopCount=0; loopCount<=1; loopCount++)
	{
		seatNo = clickedColNo;
		if (loopCount == 0) 
		{
			scanOffset = -1;
			scanEnd = SeatLeftEndClass;
		}
		else 
		{
			scanOffset = 1;
			scanEnd = SeatRightEndClass;
		}
		
		limits[loopCount] = seatNo;
		if (stageshow_IsSeatState(obj, scanEnd))
		{
			continue;
		}
		
		for ( ; (seatNo > 0) && (seatNo <= maxCols); )
		{
			seatNo += scanOffset;
			seatObjId = seatPosnParts[0] + '_' + seatNo;
			nextSeatObj = document.getElementById(seatObjId);
			seatState = stageshow_GetSeatState(nextSeatObj);
						
			if (seatState >= SeatStateReserved)
			{
				/* Stop scanning without updating seatsStates */
				break;
			}
			
			seatsStates[seatNo] = seatState;
			limits[loopCount] = seatNo;		
			if (stageshow_IsSeatState(nextSeatObj, scanEnd))
			{
				/* Update seatsStates - Then Stop scanning */
				break;
			}			
		}
	}
	
	/* Add a right hand terminator for the scan */
	seatNo = limits[1] + 1;
	seatsStates[seatNo] = SeatStateInvalid;
	limits[1] = seatNo;
	
	/*  Scan Seats Block for an available blocks smaller than the limit */
	lastSeatState = -1;	
	availableBlocksCount=0;
	requestedBlocksCount=0;
	conseqAvailableSeats = 0;
	smallGapsCount = 0;
	for (seatNo=limits[0]; seatNo<=limits[1]; seatNo++)
	{
		seatState = seatsStates[seatNo];
		if (seatState == SeatStateAvailable)
		{
			if (lastSeatState != seatState) availableBlocksCount++;
			conseqAvailableSeats++;	
		}
		else
		{			
			if (lastSeatState != seatState)
			{
				if (seatState == SeatStateRequested)
				{
					requestedBlocksCount++;
				}
				if ((conseqAvailableSeats > 0) && (conseqAvailableSeats < minSeatSpace))
				{
					smallGapsCount++;
				}
			}
			conseqAvailableSeats = 0;
		}
		lastSeatState = seatState;
	}
	
	if ((requestedBlocksCount > 1) || (availableBlocksCount > 1))
	{
		if (smallGapsCount > 0)
		{
			return false;
		}
	}
	
	return true;
}

function stageshow_UpdateZonesCount(zoneID, zoneCountRequested, zoneCountCurrent)
{
	if (!limitSelectedCount) return;
	
	var blockElem = document.getElementById(SeatCountBlockIdRoot);
	if (blockElem == null)
		return;
		
	var zoneElem = document.getElementById(SeatCountBlockIdRoot+zoneID);
	if (zoneElem == null)
		return;
		
	requestedElem = document.getElementById(SeatCountBlockIdRoot+"-requested"+zoneID);
	requestedElem.innerHTML = zoneCountRequested;
	
	zoneCountSelected = zoneCountRequested - zoneCountCurrent;
	selectedElem = document.getElementById(SeatCountBlockIdRoot+"-selected"+zoneID);
	selectedElem.innerHTML = zoneCountSelected;
	
	zoneElem.style.display = '';
	blockElem.style.display = '';
}

function stageshow_GetSeatIndex(obj)
{
	if (typeof seatInfoSeatIDs == 'undefined') 
	{
		return -1;
	}
	
	seatIdParts = obj.id.split("-");
	seatId = seatIdParts[seatIdParts.length-1];
	
	var index = jQuery.inArray(seatId, seatInfoSeatIDs);
	return index;
}

function stageshow_GetSaleID(index)
{
	if (index >= 0) 
	{
		return seatInfoSaleIDs[index];
	}
	
	return 0;
}

function stageshow_GetSeatMeta(index)
{
	if (index < 0) return'';
	
	var metaMsg = '';
	var meta = seatInfoTicketMeta[index];
	var metaParts = meta.split("|");
	for (i = 0; i < metaParts.length-1; i++) 
	{
		metaMsg += metaParts[i]+"\n";
	}
	
	return metaMsg;
}

function stageshow_MetaFromObj(obj)
{
	var seatIndex = stageshow_GetSeatIndex(obj);
	if (seatIndex < 0) return '';
	
	var msg = stageshow_GetSeatMeta(seatIndex);
	return msg;
}

function stageshow_OpenSale(obj)
{
		var seatmsg = stageshow_MetaFromObj(obj);
		if (seatmsg == '') return;
/*		
jQuery("#mydialog").dialog({
  buttons: {
    'Confirm': function() {
       //do something
       jQuery(this).dialog('close');
    },
    'Cancel': function() {
       jQuery(this).dialog('close');
    }
  }
});
*/
		alert(seatmsg);
}

function stageshow_ClickSeatEx(event, obj)
{
	var origSeatState = stageshow_GetSeatState(obj);	/* Get state of seat */
	
	obj.event = event;
	rtnval = stageshow_ClickSeat(obj);
	
	var formTemplateElem = jQuery('#stageshow_quicksale_formtemplate');
	if (formTemplateElem.length > 0)
	{
		var seatElem = jQuery('#'+obj.id);
		var seatRef = stageshow_GetSeatId(obj);
		var seatTitle = seatElem[0].title;
		var newElemId = 'stageshow_quicksale_form_' + seatRef;
		
		var newSeatState = stageshow_GetSeatState(obj);	/* Get state of seat */
		if ((newSeatState == SeatStateRequested) && (newSeatState != origSeatState))
		{
			/* Get the <tr> element that this element is in. Then get count of seats */
			var trElem = seatElem.closest('tr');
			var tdElems = trElem.find('td');
			var cols = tdElems.length;
			
			/* Get Next Element */
			for (k=0; k<=cols; k++)
			{
				var nextElem = trElem.next();
				if (!nextElem) break;
				if (nextElem[0].tagName != 'TR') break;				
				var nextId = nextElem[0].id;
				if (nextId == '') break;
				if (nextId.indexOf('stageshow_quicksale_form_') > -1)
				{
					if (nextId > newElemId) break;
					trElem = nextElem;
				}
			}
			
			/* Add a new row after the seats' row */
			var seatDetails = formTemplateElem[0].innerHTML;
			seatDetails = seatDetails.replace(/\[cartIndex\]/g, seatRef);
			seatDetails = seatDetails.replace('\[seatAlt\]', seatTitle);
			seatDetails = seatDetails.replace(/stageshow_customco_template/g, 'stageshow_customco');			
			var newElem = '<tr id=' + newElemId + ' class=stageshow_quicksale_itemform ><td colspan=' + cols + '>' + seatDetails + '</td></tr>';

			jQuery(newElem).insertAfter(trElem);
			
			/* Get Zone of selected seat */
			var seatZoneNo = stageshow_GetZoneNo(seatElem[0]);
			
			/* Get Zone Name from Zone Id */
			var zoneName = '('+zonenames[seatZoneNo]+')';
			
			/* Remove Options that are not in this Zone */						
			var typeSelectElemID = 'stageshow_quicksaleItem_PriceID'+seatRef;
			var typeSelectElem = jQuery('#'+typeSelectElemID);
			var typeOptionElems = typeSelectElem.find('option');
			for (o=typeOptionElems.length-1; o>=0; o--)
			{				
				var optionText = typeOptionElems[o].text;
				if (optionText.substr(0-zoneName.length) != zoneName)
				{
					typeOptionElems[o].remove();
				}
			}
			
			var pFormObj = jQuery('#stageshow_quicksale_purchaserform')[0];
			var pformHTML = '<tr id=stageshow_quicksale_purchaserform_row><td colspan=' + cols + '>'+pFormObj.outerHTML+'</td></tr>';
			var pFormRowObj = jQuery('#stageshow_quicksale_purchaserform_row')[0];

			if (typeof stageshowCustom_OnSeatsLoadAddQuicksaleForm == 'function')
			{ 
				stageshowCustom_OnSeatsLoadAddQuicksaleForm(seatRef);
			}	

			pFormObj.remove();
			if (pFormRowObj) 
			{
				pFormRowObj.remove();
			}
			
			/* Find all Custom Form Items ... Move Purchaser Details after last one */
			var itemFormElems = jQuery('.stageshow_quicksale_itemform');
			var lastItemIndex = itemFormElems.length-1;

			pformHTML = pformHTML.replace(/customco_templateItem_/g, 'customcoItem_');		
			
			jQuery(pformHTML).insertAfter(itemFormElems[lastItemIndex]);
			jQuery('#stageshow_quicksale_purchaserform').show(); 
		}
		else
		{
			var newElem = jQuery('#' + newElemId);
			newElem.remove();
			
			var checkoutformElems = jQuery('.stageshow-checkoutform');
			
			if (checkoutformElems.length <= 1)
			{
				jQuery('#stageshow_quicksale_purchaserform').hide(); 
			}
		}
	}
	
	return rtnval;
}

function stageshow_ClickSeat(obj)
{
	if (pageMode == "viewsales")
	{
		stageshow_OpenSale(obj);
		return;
	}
	
	if (typeof stageshowCustom_ClickSeat == 'function') 
	{ 
  		return stageshowCustom_ClickSeat(obj); 
	}
	
	if ((typeof obj.event != 'undefined') && (lastSeatId != ''))
	{
		var event = obj.event;
		var isShift = event.shiftKey;
		if (isShift)
		{
			firstSelSeatId = lastSeatId;
			firstSeatLocn = firstSelSeatId.split("_");
			firstRow = parseInt(firstSeatLocn[0]);
			firstSeat = parseInt(firstSeatLocn[1]);
			
			var seatId = stageshow_GetSeatId(obj);			
			lastSeatLocn = seatId.split("_");
			lastRow = parseInt(lastSeatLocn[0]);
			lastSeat = parseInt(lastSeatLocn[1]);
			
			if (firstRow < lastRow) 
			{
				startRow = firstRow;
				endRow = lastRow;
			}	
			else
			{
				startRow = lastRow;
				endRow = firstRow;
			}
			
			if (firstSeat < lastSeat) 
			{
				startSeat = firstSeat;
				endSeat = lastSeat;
			}	
			else
			{
				startSeat = lastSeat;
				endSeat = firstSeat;
			}
						
			for (rowNo=startRow; rowNo<=endRow; rowNo++)
			{
				for (seatNo=startSeat; seatNo<=endSeat; seatNo++)
				{
					var deatSeatId = rowNo+"_"+seatNo;
					if (deatSeatId == firstSelSeatId) continue;
					var seatObjId = obj.id.replace(seatId, deatSeatId);
					var destSeatObj = document.getElementById(seatObjId);
					if (destSeatObj == null) continue;
					stageshow_ToggleSeat(destSeatObj, true);
				}
			}	
			
			return;		
		}
	}
	
	stageshow_ToggleSeat(obj, true);
}

function stageshow_AlertInvalidSeat()
{
	if (typeof stageshowCustom_AlertInvalidSeat == 'function') 
	{ 
  		return stageshowCustom_AlertInvalidSeat(); 
	}
	
	alert(CantReserveSeatMessage);
}

function stageshow_GetSeatId(obj)
{
	seatIdParts = obj.id.split("-");
	seatId = seatIdParts[seatIdParts.length-1];
	return seatId;
}

function stageshow_ToggleSeat(obj, isClick)
{
	var hiddenSeatsElem, hiddenZonesElem;
	
	var seatId = stageshow_GetSeatId(obj);
	
	lastSeatId = '';
	
	zoneID = stageshow_IsZoneValid(obj);
	if (zoneID == 0)
	{
		return;
	}
				
	availClass = SeatAvailableClassText;
	
	seatStatus = stageshow_SeatAvailability(seatId);
	switch (seatStatus)
	{
		case '': 
		case 'selected': 
			break;
			
		case 'locked': 
			if (SeatAllowLocked)
			{
				availClass = SeatLockedClassText;
				break;
			}
			/* Otherwise drop into next case */
 		case 'booked': 
		case 'allocated': 
		case 'reserved': 
		default: 
			return;							
	}

	if (isClick && !stageshow_CheckClickSeat(obj))
	{
		stageshow_AlertInvalidSeat();
		return;
	}

	/* Add a space either side of the name */
	/* This prevents a match with part of any longer Ids */
	seatIdMark = " " + seatId + " ";
	zoneIDMark = " " + zoneID + " ";
	
	var className = obj.className;
	var classPosn = className.search(availClass);
	
	hiddenSeatsElem = document.getElementById("stageshow-seatselected-seats");
	hiddenZonesElem = document.getElementById("stageshow-seatselected-zones");
	
	/* Remove existing class specifier */
	className  = className.replace(availClass + ' ', '');
	className  = className.replace(SeatRequestedClassText + ' ', '');
	
	if (classPosn >= 0)
	{
		if (limitSelectedCount)
		{
			if (zones[zoneID] <= 0) 
				return;
			zones[zoneID] = zones[zoneID] - 1;
		}
			
		className = SeatRequestedClassText + ' ' + className;		
		hiddenSeatsElem.value = hiddenSeatsElem.value + seatIdMark;
		hiddenZonesElem.value = hiddenZonesElem.value + zoneIDMark;
		seatState = SeatAltRequested;
	}
	else
	{
		if (limitSelectedCount)
		{
			zones[zoneID] = zones[zoneID] + 1;
		}
		
		className = availClass + ' ' + className;
		
		seatsList = hiddenSeatsElem.value.replace("  ", " ");
		zonesList = hiddenZonesElem.value.replace("  ", " ");
		
		seatsListArray = seatsList.split(" ");
		zonesListArray = zonesList.split(" ");
		
		seatsList = "";
		zonesList = "";
		for (i = 0; i <= seatsListArray.length-1; i++) 
		{
			if ((seatsListArray[i] == seatId) || (seatsListArray[i] == ""))
			{
				continue;
			}
			seatsList += " "+seatsListArray[i]+" ";
			zonesList += " "+zonesListArray[i]+" ";
		}
	
		hiddenSeatsElem.value = seatsList;
		hiddenZonesElem.value = zonesList;
		seatState = SeatAltAvailable;
	}
	if (isClick)
	{
		stageshow_UpdateZonesCount(zoneID, zonesReq[zoneID], zones[zoneID]);
	}

	lastSeatId = seatId;
	
	obj.className = className;
	obj.alt = obj.title + " " + seatState;
}

function  stageshow_OnClickSeatsavailable(obj, inst)
{
	var buttonIdParts = obj.id.split("_");
	var perfId = parseInt(buttonIdParts[1]);
	
	var url = window.location.pathname;
	if (url.indexOf('?') > -1)
	{
		url = url.replace('?', '?showSeatsPerfID=' + perfId + '&');
	}
	else if (url.indexOf('#') > -1)
	{
		url = url.replace('#', '?showSeatsPerfID=' + perfId + '#');
	}
	else
	{
		url = url + '?showSeatsPerfID=' + perfId;
	}

	window.open(url, '_blank');
	return false;
}

function  stageshow_OnClickPrintTickets(obj, inst)
{
	var ajax_status = true;
	var ajax_data = "";
	
	var noOfSales = tktList_SaleID.length;
	if (noOfSales <= 0) return false;
	
	StageShowLib_SetBusy(true, "stageshowlib-tools-ui", "stageshowlib-tools-ui");

	var statusElem = document.getElementById("stageshowgold-print-status");
	/* statusElem.innerHTML = 'URL:' + printReqURL; */
		
	for (var index=1; index<=noOfSales; index++)
	{
		var saleID = tktList_SaleID[index-1];
		
		jQuery.ajax({
				url: printReqURL,
				type: 'post',
				timeout: 30000,
		        data: 
		        {
					jquery: 'true',
		        	id: saleID,
					action: 'spool',
					nonce: ourNOnce,
					TxnId: txnId
		        },
		        async: false,
		        cache: false,
		        success: function(data) 
		        {
		        	ajax_data = data;
		        	ajax_status = true;
		            return data;
		        },      
		        error: function(jqXHR, textStatus, errorThrown) 
		        {
		        	ajax_data = textStatus;
		        	ajax_status = false;
		            return data;
		        }        
		    });
		    
		if (!ajax_status)
		{
			statusElem.innerHTML = 'Print Request Failed - ' + ajax_data;
			break;					
		}
		
		// Update unprinted sales count
		statusElem.innerHTML = "Print Request OK - " + index + " of " + noOfSales;
	}
	
	if (ajax_status) statusElem.innerHTML = "Printing Complete";
	
	StageShowLib_SetBusy(false, pluginId + "-trolley-ui", pluginId + "-trolley-button");
	
	return true;
}

function stageshow_GetDetailsCtrlDefs(listObj)
{
	var uiElemSpec = "#" + listObj.name;
	var uiElem = jQuery(uiElemSpec);
	
	var ctrldata = {
		name: listObj.name
	};
		
	/* Get the input format of the control */
	var type = uiElem.attr('input-format');			
	if (typeof type == 'undefined')
	{
		type = uiElem.attr('type');			
		if (typeof type == 'undefined')
		{
			type = uiElem[0].type;			
			if (typeof type == 'undefined')
			{
				type = '';			
			}
		}
	}
	
	if (type != '')
	{
		ctrldata.type = type;
		
		ctrldata.minchars = stageshow_TagAttr(uiElem, 'minchars');
		ctrldata.maxchars = stageshow_TagAttr(uiElem, 'maxchars');		
		ctrldata.minval = stageshow_TagAttr(uiElem, 'minval');		
	}
	
	return ctrldata;
}

function stageshow_TagAttr(uiElem, tag)
{
	var attrval = uiElem.attr(tag);
	if (typeof attrval == 'undefined') 
		attrval = 'undef';
		
	return attrval;
}

function stageshow_OnLoadCheckoutDetails()
{
	/* Get list of elements with stageshow_customco class */
	var classSpec = ".stageshow_customco";
	if (typeof stageshow_customco_vals != 'undefined')
	{
		initValuesArray = stageshow_customco_vals;		
	}
	else
	{
		initValuesArray = null;
	}
	
	stageshow_OnLoadPrecheckoutForm(classSpec, initValuesArray);
}

function stageshow_OnClickCheckoutloadform(obj)
{
	if (typeof stageshowCustom_OnClickCheckoutLoadForm == 'function') 
	{ 
  		return stageshowCustom_OnClickCheckoutLoadForm(obj); 
	}
	StageShowLib_BeforeSubmit(obj, stageshowlib_cssDomain);
	return true;
}

function stageshow_OnClickCheckoutForm2(obj)
{
	return stageshow_OnClickCheckoutForm(obj);
}

function stageshow_OnClickCheckoutForm(obj)
{
	if (!stageshow_ValidateCheckoutForm(obj))
	{
		return false;
	}
		
	return stageshow_OnClickCheckout(obj);
}

function stageshow_OnClickShowsaledetails(obj)
{
	var x=1;
	var y=2;
	
	/* Note: Uses maxRows and maxCols which must be defined in template */
	var row, col;
	for (row=1; row<=maxRows; row++)
	{
		for (col=1; col<=maxCols; col++)
		{
			var seatId = row + '_' + col;
			var seatFullId = "#" + SeatLayoutClassText + seatId;
			var seatObj = document.getElementById(SeatLayoutClassText + seatId);
			
			if (seatObj != null)
			{
				var meta = stageshow_MetaFromObj(seatObj);
				meta = meta.replace(/\n/g, "<br>");
				seatObj.innerHTML = meta;
				
				var className = seatObj.className;				
				className = className.replace("stageshow-boxoffice-seat ", "stageshow-boxoffice-seatdetails ");
				seatObj.className = className;
			}
		}
	}
}

