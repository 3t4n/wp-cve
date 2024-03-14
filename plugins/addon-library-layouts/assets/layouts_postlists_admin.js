
function LayoutsPostsListPageAdmin(){
	
	var t = this;
	
	
	/**
	 * add import button
	 */
	function addImportButton(){
		var objWrap = jQuery('.wrap h1');
		var html = "<a id='uc_button_import_layout' class='page-title-action uc-button-import' href='javascript:void(0)'>Import Layout</a>";
		objWrap.append(html);
		
	}
	
	
	/**
	 * init the page
	 */
	this.init = function(){
		
		addImportButton();
		
		var objListAdmin = new UniteCreatorAdmin_LayoutsList();
		objListAdmin.initImportLayoutDialog();
		
		//init layout exporter
		jQuery(".uc_button_export").click(objListAdmin.onExportClick);
		
	}
	
	
}

