(function() {
    tinymce.create('tinymce.plugins.CHR.Utube', {
        init : function(ed, url) {
            ed.addButton('showUtube', {
                title : 'YouTube Simple Gallery',
                image : url + '/icon-youtube.png',
                onclick : function() {
                    tb_show("Insert YouTube Simple Gallery", url+"/../tinymce/chrUtube-tinymce-page.php?a=a&width=670&height=600");
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "YouTube Simple Gallery",
                author : 'CHR Designer',
                authorurl : 'http://www.chrdesigner.com/',
                infourl : 'www.chrdesigner.com/demo/plugins/youtube-simple-gallery',
                version : "2.2.0"
            };
        }
    });
    tinymce.PluginManager.add('chrUtube', tinymce.plugins.CHR.Utube);
})();