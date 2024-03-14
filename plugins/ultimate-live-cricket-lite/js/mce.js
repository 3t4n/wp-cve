(function() {
	tinymce.PluginManager.add('twd_mce_button', function( editor, url ) {
	   	// function getValues() {
	    // 	return editor.settings.cptPostsList;
	   	// }
		editor.addButton('twd_mce_button', {
			text: 'Add Series Matches',
			icon: false,
			tooltip: 'Series Matches',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Series',
					width: 400,
					height: 100,
					body: [
						{
							type: 'listbox',
							name: 'listboxName',
							label: 'Series Matches',
							'values': tinyMCE.DOM.cptPostsList
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[series-matches series_id="' + e.data.listboxName + '"]');
					}
				});
			}
		});
	});
})();
