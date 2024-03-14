/* 
Description: StageShow Export Javascript
 
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

function onPageLoad(obj) 
{
	// Set initial focus to TxnId edit box
	var ourTxnIdObj = document.getElementById("TxnId");
	ourTxnIdObj.focus();
}
	
function onKeyPress(obj) 
{
	if (obj.keyCode == 13)
	{
		VerifyTxnId();
	}
}

function onclickverify(obj) 
{
	VerifyTxnId();
}
	
function LogVerified(index) 
{
	var VerifiesList = "";
	
	try
	{
		var timeNow = new Date();		
		if (typeof verifysList[index] === "undefined")	
		{
			verifysList[index] = "";
		}
			
		VerifiesList = verifysList[index];
		verifysList[index] += timeNow.toLocaleString() + "<br> ";	
	}
	catch (err)
	{
	}
	
	return VerifiesList;
		
}
	
function AddHTMLElem(elemType, elemClass)
{
	var elem = "<" + elemType + " class=" + elemClass + ">";
	return elem;
}
	
function AddTableData(elemType, elemClass, elemVal)
{
	var elem = AddHTMLElem(elemType, elemClass);
	var elem = elem + elemVal + "</" + elemType + ">";
	return elem;
}

