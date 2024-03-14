function mt_submit()
{
	if(document.form_mt.mt_text.value=="")
	{
		alert(mt_adminscripts.mt_text);
		document.form_mt.mt_text.focus();
		return false;
	}
	else if(document.form_mt.mt_status.value=="")
	{
		alert(mt_adminscripts.mt_status);
		document.form_mt.mt_status.focus();
		return false;
	}
	else if(document.form_mt.mt_order.value=="")
	{
		alert(mt_adminscripts.mt_order);
		document.form_mt.mt_order.focus();
		return false;
	}
	else if(isNaN(document.form_mt.mt_order.value))
	{
		alert(mt_adminscripts.mt_order);
		document.form_mt.mt_order.focus();
		return false;
	}
	else if(document.form_mt.mt_group.value == "" || document.form_mt.mt_group.value == "Select")
	{
		alert(mt_adminscripts.mt_group);
		document.form_mt.mt_group.focus();
		return false;
	}
	else if(document.form_mt.mt_date.value == "")
	{
		alert(mt_adminscripts.mt_date);
		document.form_mt.mt_date.focus();
		return false;
	}
	_mt_escapeVal(document.form_mt.mt_text,'<br>');
}

function mt_delete(id)
{
	if(confirm(mt_adminscripts.mt_delete))
	{
		document.frm_mt_display.action="options-general.php?page=message-ticker&ac=del&did="+id;
		document.frm_mt_display.submit();
	}
}	

function mt_redirect()
{
	window.location = "options-general.php?page=message-ticker";
}

function mt_help()
{
	window.open("http://www.gopiplus.com/work/2010/07/18/message-ticker/");
}

function _mt_escapeVal(textarea,replaceWith)
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