/**
 *     Popup with fancybox
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
 */

function _Popupwfb_submit()
{
	if(document.Popupwfb_form.Popupwfb_width.value=="")
	{
		alert(Popupwfb_adminscripts.Popupwfb_width)
		document.Popupwfb_form.Popupwfb_width.focus();
		return false;
	}
	else if(document.Popupwfb_form.Popupwfb_timeout.value=="")
	{
		alert(Popupwfb_adminscripts.Popupwfb_timeout)
		document.Popupwfb_form.Popupwfb_timeout.focus();
		return false;
	}
	else if(document.Popupwfb_form.Popupwfb_title.value=="")
	{
		alert(Popupwfb_adminscripts.Popupwfb_title)
		document.Popupwfb_form.Popupwfb_title.focus();
		return false;
	}
	else if(document.Popupwfb_form.Popupwfb_content.value=="")
	{
		alert(Popupwfb_adminscripts.Popupwfb_content)
		document.Popupwfb_form.Popupwfb_content.focus();
		return false;
	}
	else if(document.Popupwfb_form.Popupwfb_group.value=="")
	{
		alert(Popupwfb_adminscripts.Popupwfb_group)
		document.Popupwfb_form.Popupwfb_group.focus();
		return false;
	}
	else if(document.Popupwfb_form.Popupwfb_status.value=="")
	{
		alert(Popupwfb_adminscripts.Popupwfb_status)
		document.Popupwfb_form.Popupwfb_status.focus();
		return false;
	}
}

function _Popupwfb_submit_setting()
{
	if(document.Popupwfb_form_setting.Popupwfb_session.value == "")
	{
		alert(Popupwfb_adminscripts.Popupwfb_session)
		document.Popupwfb_form_setting.Popupwfb_session.focus();
		return false;
	}
}

function _Popupwfb_delete(id)
{
	if(confirm(Popupwfb_adminscripts.Popupwfb_delete))
	{
		document.frm_Popupwfb_display.action="options-general.php?page=popup-with-fancybox&ac=del&did="+id;
		document.frm_Popupwfb_display.submit();
	}
}	

function _Popupwfb_redirect()
{
	window.location = "options-general.php?page=popup-with-fancybox";
}

function _Popupwfb_help()
{
	window.open("http://www.gopiplus.com/work/2013/08/08/popup-with-fancybox-wordpress-plugin/");
}