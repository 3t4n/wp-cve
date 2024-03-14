(function() {

    var GIFMaster_New = {
        init : function(editor, url) {
            editor.addButton('gifmaster_new', {
                title: 'GIF Master',
				image: url + '/../gif-icon.png',
                onclick : function(ev) {
					var modalw = 500;
					var modalh = 560;

					editor.windowManager.open({
						title : "GIF Master - Add New GIF",
						file: url + '/../popup/fetch.html?tenor='+gifm_tinymce_obj["tenor_key"]+'&giphy='+gifm_tinymce_obj["giphy_key"],
						width : modalw,
						height : modalh,
						inline : true,
						resizable: true,
						scrollbars: true
					});
				}
            });
        },
    }

    tinymce.create('tinymce.plugins.gifmaster_new', GIFMaster_New);
    tinymce.PluginManager.add('gifmaster_new', tinymce.plugins.gifmaster_new);
})();
