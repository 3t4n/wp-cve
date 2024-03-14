/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

/* 
 Setup dialog in gallery settings 
*/

jQuery(function(){
	var apeGalleryDialog = jQuery("#wpape_showInformation");
	apeGalleryDialog.dialog({
		'dialogClass' : 'wp-dialog',
		'title': ape_gallery_js_text.title,
		'modal' : true,
		'autoOpen' : ape_gallery_js_text.open=='1' ? true : false,
		'width': '490', 
	    'maxWidth': 490,
	    'height': 'auto',
	    'fluid': true, 
	    'resizable': false,
		'responsive': true,
		'draggable': false,
		'closeOnEscape' : true,
		'buttons' : [{
				'text'  : 	ape_gallery_js_text.close,
				'class' : 	'button button-link wpape_dialog_close wpape_dialog_continue',
				'click' : 	function() { jQuery(this).dialog('close'); }
		},{
				'text' : 	ape_gallery_js_text.info,
				'class' : 	'button-primary wpape_getproversion_blank wpape_close_dialog',
				'click' : 	function(){}
		}],
	});
	window['apeGalleryDialog'] = apeGalleryDialog;
	jQuery(".ui-dialog-titlebar-close").addClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close");
	
	jQuery('.wpape-block-premium').click( function(event ){
		event.preventDefault();
		apeGalleryDialog.dialog("open");
	});
});