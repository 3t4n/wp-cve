function clos_ajax_load_changelog( pluginslug, sectionid ) {
	var clos_sack = new sack(clos_ajaxurl);
	clos_sack.execute = 1;
	clos_sack.method = 'POST';
	clos_sack.setVar( "action", "clos_ajax_load_changelog" );
	clos_sack.setVar( "pluginslug", pluginslug );
	clos_sack.setVar( "sectionid", sectionid );
	clos_sack.onError = function() { alert('AJAX error on reading changelog section') };
	clos_sack.runAJAX();
}