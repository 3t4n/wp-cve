(function() {

    function insertLinkPreviewContent(editor, url, index) {
        url = jQuery.trim(url);

        if (url) {
            var loaderId = 'wplinkpreview-loader-' + index;

            jQuery.ajax({
                type: 'GET',
                url: ajaxurl,
                beforeSend: function() {
                    jQuery('#postdivrich').prepend('<p id="' + loaderId + '"><img src="' + siteurl + '/wp-admin/images/loading.gif" /> Loading link preview for: ' + url + ' </p>');
                },
                data: {
                    action: 'fetch_wplinkpreview',
                    url: url
                },
                success: function(html) {
                    editor.insertContent(html);
                },
                complete: function() {
                    jQuery('#' + loaderId).remove();
                }
            });
        }
    }

	tinymce.PluginManager.add( 'wplinkpreview_plugin', function( editor, url ) {
		editor.addButton('wplinkpreview_plugin', {
			title: 'Insert Link Preview',
            icon: 'mce-ico mce-i-preview wplinkpreview',
            onclick: function() {
                editor.windowManager.open({
                    title: 'WP Link Preview',
                    body: [{
                        type: 'textbox',
                        name: 'urls',
                        label: 'URLs (one per line)',
                        autofocus: true,
                        minHeight: 60,
                        minWidth: 250, 
                        multiline: true
                    }],
                    onsubmit: function( e ) {
                        var urls = e.data.urls.split('\n');

                        for (var i = 0; i < urls.length; i++) {
                            insertLinkPreviewContent(editor, urls[i], i);
                        }
                    }
                });
            }
		});
	});
})();