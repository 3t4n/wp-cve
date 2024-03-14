(function() {

    tinymce.create('tinymce.plugins.aparat_shortcode', {

        init : function(ed, url){
            ed.addButton('aparat_shortcode', {
                title : 'افزودن ویدئوی آپارات',
                onclick : function() {
					var aparat = prompt("شناسه ویدئوی آپارات را درج کنید :\n\nبه‌عنوان مثال شناسه ویدئوی http://www.aparat.com/v/iybdS عبارت است از : iybdS",'');
					if( aparat ){
						//ed.selection.setContent('[aparat id="'+ ed.selection.getContent() +'"]');
						ed.selection.setContent('[aparat id="'+ aparat +'"]');
					}
                },
                image: url + "/aparat.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'Aparat Shrotcut button',
                author : 'mahmood khosravi',
                authorurl : 'https://iraweb.ir/',
                infourl : 'https://wordpress.org/plugins/aparat-responsive/',
                version : "1.2"
            };
        }
    });

    tinymce.PluginManager.add('aparat_shortcode', tinymce.plugins.aparat_shortcode);
    
})();
