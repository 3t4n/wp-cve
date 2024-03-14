/* 
Description: Stateshow Admin Javascript
 
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


var PerfIDList = new Array();

function stageshow_OnClickSeatingID(obj)
{
	var selectId = obj.id;
	var selectedIndex = obj.selectedIndex;
	if (typeof(selectedIndex) == 'undefined')
	{
		selectedIndex = obj.value;
	}
	var elemId = selectId.replace('perfSeatingID', '');
	var showMaxSeats = (selectedIndex == 0);
	var seatsObjId = 'perfSeats' + elemId;
	var seatsObj = document.getElementById(seatsObjId);
	if (showMaxSeats)
	{
		seatsObj.style.display = '';
	}
	else
	{
		seatsObj.style.display = 'none';
	}
}

function stageshow_TestClickSeatEx(event, obj)
{
	obj.event = event;
	stageshow_TestClickSeat(obj);
}

function stageshow_TestClickSeat(obj)
{
	var seatId, hiddenSeatsElem, hiddenZonesElem;
	
	seatIdParts = obj.id.split("-");
	seatId = seatIdParts[seatIdParts.length-1];
		
	seatsElem = document.getElementById("stageshow-boxoffice-layout-seatdef");

	var className = obj.className;
	var classPosn = className.search(SeatRequestedClassText);
	
	/* Remove existing class specifier */
	className  = className.replace(SeatAvailableClassText, ' ');
	className  = className.replace(SeatRequestedClassText, ' ');
	className  = className.replace(SeatUnknownClassText, ' ');
	className  = className.replace('  ', ' ');
	
	seatName = 'Row ' + seatId.replace('_', ' Seat ');
	seatName = obj.title;
	
	if (classPosn < 0)
	{
		className = SeatRequestedClassText + ' ' + className;
		seatsElem.innerHTML = seatName + ' Changed to Booked';		
	}
	else
	{
		className = SeatAvailableClassText + ' ' + className;
		seatsElem.innerHTML = seatName + ' Changed to Available';		
	}
	obj.className = className;
}

function stageshow_DisableLinkButton(buttonId)
{
	buttonObj = document.getElementById(buttonId);
	buttonObj.removeAttribute('href');
}

function stageshow_OnChangeZoneRef(obj)
{
	return stageshow_OnChangeZoneEntry(obj, "zoneRef");
}

function stageshow_OnChangeZoneSpec(obj)
{
	return stageshow_OnChangeZoneEntry(obj, "zoneSpec");
}

function stageshow_OnChangeZoneAlloc(obj)
{
	return stageshow_OnChangeZoneEntry(obj, "zoneAllocSeats");
}

function stageshow_OnChangeZoneDecode(obj)
{
	return stageshow_OnChangeZoneEntry(obj, "seatingDecodeTable");
}

function stageshow_OnChangeZoneEntry(obj, elemRootId)
{
	var changedElemName = obj.name;
	var changedElemValue;
	if (obj.type == 'checkbox')
	{
		if (obj.checked) changedElemValue = 1;
		else changedElemValue = 0;
	}
	else
	{
		changedElemValue = encodeURIComponent(obj.value);
	}
	
	/* Extract SeatingID and Zone ID from name */
	var ids = changedElemName.replace(elemRootId, "");
	var idParts = ids.split("_");
	var seatingId = idParts[0];
	
	/* Get View Template Link Button Object */
	var buttonId = "stageshow-viewtemplate-" + seatingId;
	var buttonObj = document.getElementById(buttonId);
		
	/* Get Link URL from object */
	var buttonHref = buttonObj.getAttribute('href')

	/* Remove this Zone Spec from URL */
	var HrefUrlAndParams = buttonHref.split("?");
	var buttonHref = HrefUrlAndParams[0];
	var params = HrefUrlAndParams[1].split("&");
	var sep = "?";
	
	/* Add new value of Zone Spec to URL */
	for (var index=0; index<params.length; index++) 
	{
		var i = params[index].indexOf('=');
		var paramId = params[index].slice(0, i);
		var paramValue = params[index].slice(i + 1);

		if (paramId == changedElemName)
		{
			paramValue = changedElemValue;
		}
		
		if (paramId != "")
		{
			buttonHref += sep + paramId + "=" + paramValue;
			sep = "&";
		}
	}
	
	/* Update button URL */
	buttonObj.setAttribute('href', buttonHref);
	
	return true;
}

function stageshow_updateExportOptions(obj)
{
	exportFormatElem = document.getElementById("export_format");
	exportTypeElem = document.getElementById("export_type");

	hideShowRow = (exportFormatElem.value == "ofx");
	if (!hideShowRow)
	{
		hideShowRow = (exportTypeElem.value == "settings");
	}
	
	selectShowRow = document.getElementById("stageshow-export_show-row");
	selectFilterRow = document.getElementById("stageshow-export_filter-row");
	if (hideShowRow)
	{
		selectShowRow.style.display = 'none';
		selectPerfRow.style.display = 'none';
		if (selectFilterRow) selectFilterRow.style.display = 'none';
	}
	else
	{
		selectShowRow.style.display = '';
		stageshow_onSelectShow(obj);
		if (selectFilterRow) 
		{
			selectFilterRow.style.display = '';
			for (filterIndex = 1; filterIndex<100; filterIndex++)
			{
				selectFilterElem = document.getElementById("filterSelect"+filterIndex);
				if (!selectFilterElem) break;
				selectFilterFile = selectFilterElem.value;
				if (selectFilterFile.indexOf("_"+exportTypeElem.value+"_") !== -1)
				{
					selectFilterElem.style.display = '';
				}
				else
				{
					selectFilterElem.style.display = 'none';
				}
				selectFilterElem = selectFilterElem;
			}
		}
	}		
}

function stageshow_onSelectShow(obj)
{
	SelectControl = document.getElementById("export_showid");
	showID = SelectControl.value;
	hidePerfSelect = (showID == 0);
	
	selectPerfRow = document.getElementById("stageshow-export_performance-row");
	selectFilterRow = document.getElementById("stageshow-export_filter-row");
	if (hidePerfSelect)
	{
		selectPerfRow.style.display = 'none';
	}
	else
	{
		selectPerfRow.style.display = '';
		SelectControl = document.getElementById("export_perfid");

		/* Remove the current options */
		while (SelectControl.length > 0)
		{
			SelectControl.remove(0);
		}
		
		/* Reload the new options */
		for (i=0; i<perfselect_id.length; i++)
		{
			OptionIDs = perfselect_id[i].split ('.');
			if ((i==0) || (OptionIDs[0] == showID))
			{
				var option = document.createElement("option");
				option.value = perfselect_id[i];
				option.text = perfselect_text[i];
				SelectControl.add(option);					
			}
		}

	}		
}

function stageshow_OnLoadPerformances()
{
	for (var index=0; index<PerfIDList.length; index++)
	{
		var seatingSetObjId = 'perfSeatingID' + PerfIDList[index];
		var seatingSetObj = document.getElementById(seatingSetObjId);
		stageshow_OnClickSeatingID(seatingSetObj);	
	}
}

function stageshow_onSelectExportType(obj)
{
	SelectControl = document.getElementById("export_format");
	newExportFormat = SelectControl.value;
	isOFXDownload = newExportFormat == "ofx";
	
	exportTypeRow = document.getElementById("stageshow-export_type-row");
	if (isOFXDownload)
	{
		exportTypeRow.style.display = 'none';
		downloadButton = document.getElementById("downloadvalidator");
		downloadButton.style.visibility = 'hidden';
	}
	else
	{
		exportTypeRow.style.display = '';
		stageshow_SetDownloadButtonStyle(obj);
	}
	stageshow_updateExportOptions();		
}

function stageshow_onSelectDownload(obj)
{
	stageshow_SetDownloadButtonStyle();
	stageshow_updateExportOptions();		
}

function stageshow_SetDownloadButtonStyle(obj)
{
	SelectControl = document.getElementById("export_type");
	newDownloadType = SelectControl.value;
	downloadValidatorEnabled = newDownloadType == "summary";

	downloadButton = document.getElementById("downloadvalidator");
	if (downloadValidatorEnabled)
		downloadButton.style.visibility = 'visible';
	else
		downloadButton.style.visibility = 'hidden';
}
