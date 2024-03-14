(function() {
    tinymce.create('tinymce.plugins.ccrtiny', {
        init : function(ed, url) {
            ed.addCommand('shortcodeGenerator', function() {

                tb_show("Add FAQ Shortcode Generator", url + '/shortcodes.php?&width=630&height=350');

                
            });
            //Add button
            ed.addButton('jwscgenerator', {    
                title : 'Add FAQ Shortcode Generator', 
                cmd : 'shortcodeGenerator', 
                image : url + '/shortcode-icon.png' 
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : 'Jewel Theme TinyMCE',
                author : 'JewelTheme',
                authorurl : 'https://www.jeweltheme.com',
                infourl : 'https://www.jeweltheme.com',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
            };
        }
    });
    tinymce.PluginManager.add('jw_buttons', tinymce.plugins.ccrtiny);
})();