(function() {
	tinymce.create('tinymce.plugins.vbtFormsShortCodes', {
 
		init : function(ed, url) {
		},
		createControl : function(n, cm) {
 
            if(n=='vbtFormsShortCodes'){
                var mlb = cm.createListBox('vbtFormsShortCodes', {
                     title: 'Vbout Forms',
                     onselect : function(v) {
						 console.log(v);
                     	if(tinyMCE.activeEditor.selection.getContent() == ''){
                            tinyMCE.activeEditor.selection.setContent( v )
                        }
                     }
                });
 
                for(var i in vbout_forms_shortcodes) {
					console.log('mlb.add('+vbout_forms_shortcodes[i][0]+','+vbout_forms_shortcodes[i][1]+');');
                	mlb.add(vbout_forms_shortcodes[i][0],vbout_forms_shortcodes[i][1]);
				}
				
                return mlb;
            }
            return null;
        } 
	});
	tinymce.PluginManager.add('vbtFormsShortCodes', tinymce.plugins.vbtFormsShortCodes);
	
	setTimeout(function() {
		jQuery('.mce-widget.mce-btn').each(function() {
			var btn = jQuery(this);
			if (btn.attr('aria-label')=="Vbout Forms")
				btn.find('span').css({padding:"10px 20px 10px 10px"});
		});
	},1000);
	
})();