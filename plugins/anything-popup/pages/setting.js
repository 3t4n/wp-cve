function _pop_submit()
{
	if((document.pop_form.pop_width.value=="") || isNaN(document.pop_form.pop_width.value))
	{
		alert(pop_adminscripts.pop_width)
		document.pop_form.pop_width.focus();
		return false;
	}
	else if((document.pop_form.pop_height.value=="") || isNaN(document.pop_form.pop_height.value))
	{
		alert(pop_adminscripts.pop_height)
		document.pop_form.pop_height.focus();
		return false;
	}
	else if((document.pop_form.pop_headercolor.value==""))
	{
		alert(pop_adminscripts.pop_headercolor)
		document.pop_form.pop_headercolor.focus();
		return false;
	}
	else if((document.pop_form.pop_bordercolor.value==""))
	{
		alert(pop_adminscripts.pop_bordercolor)
		document.pop_form.pop_bordercolor.focus();
		return false;
	}
	else if((document.pop_form.pop_header_fontcolor.value==""))
	{
		alert(pop_adminscripts.pop_header_fontcolor)
		document.pop_form.pop_header_fontcolor.focus();
		return false;
	}
	else if((document.pop_form.pop_title.value==""))
	{
		alert(pop_adminscripts.pop_title)
		document.pop_form.pop_title.focus();
		return false;
	}
	else if((document.pop_form.pop_caption.value==""))
	{
		alert(pop_adminscripts.pop_caption)
		document.pop_form.pop_caption.focus();
		return false;
	}
	else if(document.pop_form.pop_content.value=="")
	{
		alert(pop_adminscripts.pop_content)
		document.pop_form.pop_content.focus();
		return false;
	}
}

function _pop_delete(id)
{
	if(confirm(pop_adminscripts.pop_delete))
	{
		document.frm_pop_display.action="options-general.php?page=anything-popup&ac=del&did="+id;
		document.frm_pop_display.submit();
	}
}	

function _pop_redirect()
{
	window.location = "options-general.php?page=anything-popup";
}

function _pop_help()
{
	window.open("http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/");
}