/* 
Description: Generic Admin Javascript
 
Copyright 2022 Malcolm Shergold

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


function stageshowlib_OnSettingsLoad()
{
	/* Get Disabled GatewaysList */
	
	var selectedTabId = jQuery("#lastTabId").val();
	if (selectedTabId == '')
	{
		selectedTabId = stageshowlib_GetURLParam('tab');
		if (selectedTabId != '')
		{		
			selectedTabId = selectedTabId.replace(/_/g,'-');
			selectedTabId = selectedTabId.toLowerCase()
			selectedTabId = selectedTabId + '-tab';
		}
	}
	
	if (selectedTabId == '')
	{
		selectedTabId = tabIdsList[defaultTabIndex];
	}
	
	stageshowlib_SelectTab(selectedTabId);
	
	var selectedItemId = stageshowlib_GetURLParam('focus');
	if (selectedItemId != '')
	{		
		var focusElem;
		
		// Get the header 'Tab' Element					
		focusElem = document.getElementById(selectedItemId);
		focusElem.focus();
	}
	
	stageshowlib_headerModesInitialise();
}

function stageshowlib_ClickGateway(obj)
{
	stageshowlib_SelectTab('gateway-settings-tab');
}

function stageshowlib_ClickHeader(obj)
{
	stageshowlib_SelectTab(obj.id);
}

function stageshowlib_GetURLParam(paramID)
{
	var rtnVal = '';
	
	var Url = location.href;
	Url.match(/\?(.+)$/);
 	var Params = RegExp.$1;
 	
	Variables = Params.split ('&');
	for (i = 0; i < Variables.length; i++) 
	{
		Separ = Variables[i].split('=');
		if (Separ[0] == paramID)
		{
			rtnVal = Separ[1];
			break;
		}
	}
	
	return rtnVal;
}

function stageshowlib_SelectTab(selectedTabID)
{
	for (index = 0; index < tabIdsList.length-1; index++)
	{
		tabId = tabIdsList[index];
		stageshowlib_ShowOrHideTab(tabId, selectedTabID);
	}
	
	lastTabElem = document.getElementById('lastTabId');
	if (lastTabElem)
	{
		lastTabElem.value = selectedTabID;
	}
	
}

function stageshowlib_HideElement(elemID)
{
	thisElem = document.getElementById(elemID);
	thisElem.style.display = 'none';	
}

function stageshowlib_ShowOrHideTab(tabID, selectedTabID)
{
	var headerElem, tabElem, pageElem, tabWidth, rowstyle;
	
	selectedGatewayTag = '';
	if (tabID == selectedTabID)
	{
		// Show the matching settings rows
		rowstyle = '';
		
		gatewayElem = document.getElementById('GatewaySelected');
		if (gatewayElem)
		{
			var gatewayId = gatewayElem.value;
			gatewayParts = gatewayId.split('_');
			gatewayBase = gatewayParts[0];
			selectedGatewayTag = '-tab-'+gatewayBase+'-row';
		}
	}
	else
	{
		// Hide the matching settings rows
		rowstyle = 'none';
	}
	
	
	// Get the header 'Tab' Element					
	tabElem = document.getElementById(tabID);
	
	// Get the Body Element					
	pageElem = document.getElementById('recordoptions');

	// Get all <tr> entries for this TabID and hide/show them as required
	var tabElements = pageElem.getElementsByTagName("tr");
	for(var i = 0; i < tabElements.length; i++) 
	{
		rowElem = tabElements[i];
		id = rowElem.id;
		
   		if (id.indexOf('-settings-tab') > 0) 
    	{
		    if (id.indexOf(tabID) == 0) 
		    {
		    	if ( (id.indexOf('-tab-') > 0) && (id.indexOf('-tab-row') < 0) )
		    	{
		    		if (selectedGatewayTag != '')
			    	{
			    		/* Must be a Gateway specific entry */
				    	if (id.indexOf(selectedGatewayTag) < 0)
				    	{
							rowElem.style.display = 'none';		
							continue;		
						}		
					}			
				}
				
				// Show or Hide the settings row
				rowElem.style.display = rowstyle;				
			}
	    }
    }

	if (tabID == selectedTabID)
	{
		// Make the font weight normal and background Grey
		tabElem.style.fontWeight = 'bold';	
		tabElem.style.borderBottom = '0px red solid';
		//tabElem.style.backgroundColor = '#F9F9F9';
	}
	else
	{
		// Make the font weight normal and background Grey
		tabElem.style.fontWeight = 'normal';	
		tabElem.style.borderBottom = '1px black solid';		
		//tabElem.style.backgroundColor = '#F1F1F1';
	}	
}

function stageshowlib_OnTicketButtonClick(showEMailURL)
{
	var saleSelectObj = document.getElementById('TestSaleID');
	saleId = saleSelectObj.value;
	
	stageshowlib_OpenTicketView(saleId, showEMailURL, 'ShowEMailNOnce');
}

function stageshowlib_OpenTicketView(saleId, showEMailURL, wpnonceId = '_wpnonce')
{
	var wpnonceObj = document.getElementById(wpnonceId);
	
	var sepChar = '?';
	if (showEMailURL.indexOf('?') > 0)
		sepChar = '&';
		
	saleParam = 'id=' + saleId;
	wpnonceParam = '_wpnonce=' + wpnonceObj.value;
	url = showEMailURL + sepChar + saleParam + '&' + wpnonceParam;
	
	var templateObj = document.getElementById('emailTemplate');
	if (templateObj)
	{
		var templateFile = templateObj.value;
		url += '&template=' + templateFile;
	}
	
	window.open(url);
}

function stageshowlib_JSONEncodePost(obj, classId)	
{	
	var formElem = obj.form;
	
	var elemsList = jQuery(formElem).find("." + classId);
	var elemLen = elemsList.length + 1;
	
	var postvars = {
		jsonEncoded: "true"
	};
	
	for (i=0; i<elemsList.length; i++)
	{
		var elemObj = elemsList[i];
		var elemId = elemObj.id;
		
		if (elemObj.type != 'checkbox')
		{
			elemVal = elemObj.value;
		}
		else
		{
			elemVal = elemObj.checked;
		}	
		postvars[elemId] = elemVal;
		
		elemObj.removeAttribute("name");
	}
	
	var encodedJSONString = JSON.stringify(postvars);

	var input = jQuery("<input>")
		.attr("type", "hidden")
		.attr("name", "stageshowlib_PostVars").val(encodedJSONString);
               
	jQuery(formElem).append(jQuery(input));	
	
	return true;
}
	
function stageshowlib_addHiddenElem(targetElem, elemName, elemVal)
{
	var formObj = targetElem.form;
	
	jQuery('<input>', 
	{
	    type: 'hidden',
	    id: elemName,
	    name: elemName,
	    value: elemVal
	}
	).appendTo(formObj);
}
	
function stageshowlib_headerModesInitialise()
{
	var HeaderImageModeElem = document.getElementById("PayPalHeaderImageMode");
	if (!HeaderImageModeElem) return;
	
	if (HeaderImageModeElem.addEventListener) 
	{
        HeaderImageModeElem.addEventListener("click", stageshowlib_headerModesVisibility, false);
    } 
    else 
    {
        HeaderImageModeElem.attachEvent("onclick", stageshowlib_headerModesVisibility);
    }  
	
	stageshowlib_headerModesVisibility();
}

function stageshowlib_headerModesVisibility()
{
	HeaderImageModeElem = document.getElementById("PayPalHeaderImageMode");
	HeaderImageFileElem = document.getElementById("PayPalHeaderImageFile");
	HeaderImageURLElem = document.getElementById("PayPalHeaderURL");
	
	switch (HeaderImageModeElem.value)
	{
		default:	
			HeaderImageFileElem.style.display = '';
			HeaderImageURLElem.style.display = 'none';
			break;
			
		case "ImagesURL":		
			HeaderImageFileElem.style.display = 'none';
			HeaderImageURLElem.style.display = '';
			break;
	}
	
}

function stageshowlib_Plugin_UsesAjax()
{
	return (typeof StageShowLib_Ajax_PluginName !== 'undefined');
}

function stageshowlib_Plugin_AjaxCall(data)
{
	if (!stageshowlib_Plugin_UsesAjax()) return;
		
	data.action = StageShowLib_Ajax_PluginName;
	data.security = StageShowLib_Ajax_NOnce;
	data.ajaxpage = StageShowLib_Ajax_PageId;
	
	if (!data.hasOwnProperty("ajaxdebug"))
	{
		var debugElem = document.getElementById(StageShowLib_Ajax_DebugElemId);
		data.debug = ((debugElem) && (debugElem.checked));
	}
	
	stageshowlib_AjaxCall(data);
}
