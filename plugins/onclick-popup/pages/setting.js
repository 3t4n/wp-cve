function onclickpopup_submit()
{
	if(document.onclickpopup_form.onclickpopup_group.value=="")
	{
		alert(onclickpopup_adminscripts.onclickpopup_group);
		document.onclickpopup_form.onclickpopup_group.focus();
		return false;
	}
	else if(document.onclickpopup_form.onclickpopup_title.value=="")
	{
		alert(onclickpopup_adminscripts.onclickpopup_title);
		document.onclickpopup_form.onclickpopup_title.focus();
		document.onclickpopup_form.onclickpopup_title.select();
		return false;
	}
	else if(document.onclickpopup_form.onclickpopup_content.value=="")
	{
		alert(onclickpopup_adminscripts.onclickpopup_content);
		document.onclickpopup_form.onclickpopup_content.focus();
		document.onclickpopup_form.onclickpopup_content.select();
		return false;
	}
}

function onclickpopup_delete(id)
{
	if(confirm(onclickpopup_adminscripts.onclickpopup_delete))
	{
		document.frm_onclickpopup_display.action="admin.php?page=onclick-popup-content&ac=del&did="+id;
		document.frm_onclickpopup_display.submit();
	}
}	

function onclickpopup_redirect()
{
	window.location = "admin.php?page=onclick-popup-content";
}

function onclickpopup_help()
{
	window.open("http://www.gopiplus.com/work/2011/11/13/wordpress-plugin-onclick-popup/");
}