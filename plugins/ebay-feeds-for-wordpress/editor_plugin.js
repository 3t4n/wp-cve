function ebffwplugin() {
	var feedurl = prompt("Insert your Feed URL here", "");
	var items = prompt("Items to display", "5");
	inpost = '[ebayfeedsforwordpress feed="' + feedurl + '" items="' + items + '"]';
    return inpost;
}

(function() {

    tinymce.create('tinymce.plugins.ebffwplugin', {

        init : function(ed, url){
            ed.addButton('ebffwplugin', {
                title : 'Insert eBay Feed',
                onclick : function() {
                    ed.execCommand(
                        'mceInsertContent',
                        false,
                        ebffwplugin()
                        );
                },
                image: url + "/ebay.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'Contnet Mage plugin',
                author : 'Grzegorz Winiarski',
                authorurl : 'http://ditio.net',
                infourl : '',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('ebffwplugin', tinymce.plugins.ebffwplugin);
    
})();
