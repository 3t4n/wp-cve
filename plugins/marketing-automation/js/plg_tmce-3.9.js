(function() {
"use strict";   
	
	var rs_val = [];
	
	for(var i in vbout_forms_shortcodes){
		rs_val[i] = {text: vbout_forms_shortcodes[i][0], value: vbout_forms_shortcodes[i][1], onclick : function() {
			//tinymce.execCommand('mceInsertContent', false, vbout_forms_shortcodes[i]);
		}};
	}
	
	tinymce.PluginManager.add( 'vbtFormsShortCodes', function( editor, url ) {

		editor.addButton( 'vbtFormsShortCodes', {
			type: 'listbox',
			title: 'Vbout Forms Shortcodes',			
			text: 'Vbout Forms',
			icon: false,
			onselect: function(e) {
				if ( typeof e.control['_value'] !== 'undefined' ) {
					tinymce.execCommand('mceInsertContent', false, e.control['_value']);
				} else {
					tinymce.execCommand('mceInsertContent', false, e.control.value());
				}
			}, 
			values: rs_val
 		});
	});
	
	setTimeout(function() {
		jQuery('.mce-widget.mce-btn').each(function() {
			var btn = jQuery(this);
			if (btn.attr('aria-label')=="Vbout Forms Shortcodes")
				btn.find('span').css({padding:"10px 20px 10px 10px"});
		});
	},1000);
 
})();