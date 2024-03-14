/**
 *     content text slider on post
 *     Copyright (C) 2011 - 2023 www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *     http://www.gopiplus.com/work/2012/01/02/content-text-slider-on-post-wordpress-plugin/
 */

function ctsop_submit()
{
	if(document.ctsop_form.ctsop_text.value=="")
	{
		alert(ctsop_adminscripts.ctsop_text)
		document.ctsop_form.ctsop_text.focus();
		return false;
	}
	else if(document.ctsop_form.ctsop_status.value=="")
	{
		alert(ctsop_adminscripts.ctsop_status)
		document.ctsop_form.ctsop_status.focus();
		return false;
	}
	else if(document.ctsop_form.ctsop_group.value=="")
	{
		alert(ctsop_adminscripts.ctsop_group)
		document.ctsop_form.ctsop_group.focus();
		return false;
	}
	else if(document.ctsop_form.ctsop_order.value=="")
	{
		alert(ctsop_adminscripts.ctsop_order1)
		document.ctsop_form.ctsop_order.focus();
		return false;
	}
	else if(isNaN(document.ctsop_form.ctsop_order.value))
	{
		alert(ctsop_adminscripts.ctsop_order2)
		document.ctsop_form.ctsop_order.focus();
		document.ctsop_form.ctsop_order.select();
		return false;
	}
	_ctsop_escapeVal(document.ctsop_form.ctsop_text,'<br>');
}

function ctsop_delete(id)
{
	if(confirm(ctsop_adminscripts.ctsop_delete))
	{
		document.frm_ctsop_display.action="options-general.php?page=content-text-slider-on-post&ac=del&did="+id;
		document.frm_ctsop_display.submit();
	}
}	

function ctsop_redirect()
{
	window.location = "options-general.php?page=content-text-slider-on-post";
}

function _ctsop_help()
{
	window.open("http://www.gopiplus.com/work/2012/01/02/content-text-slider-on-post-wordpress-plugin/");
}

function _ctsop_escapeVal(textarea,replaceWith)
{
textarea.value = escape(textarea.value) //encode textarea strings carriage returns
for(i=0; i<textarea.value.length; i++)
{
	//loop through string, replacing carriage return encoding with HTML break tag
	if(textarea.value.indexOf("%0D%0A") > -1)
	{
		//Windows encodes returns as \r\n hex
		textarea.value=textarea.value.replace("%0D%0A",replaceWith)
	}
	else if(textarea.value.indexOf("%0A") > -1)
	{
		//Unix encodes returns as \n hex
		textarea.value=textarea.value.replace("%0A",replaceWith)
	}
	else if(textarea.value.indexOf("%0D") > -1)
	{
		//Macintosh encodes returns as \r hex
		textarea.value=textarea.value.replace("%0D",replaceWith)
	}
}
textarea.value=unescape(textarea.value) //unescape all other encoded characters
}