/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

jQuery(function(){
	var apeGalleryTypeDialog = jQuery("#ape-gallery-type-select");
	apeGalleryTypeDialog.dialog({
		'dialogClass' : 'wp-dialog',
		'title': ape_gallery_type_js_text.title,
		'modal' : true,
		'autoOpen' : false,
		'width': '630', 
	    'maxWidth': 630,
	    'height': 'auto',
	    'fluid': true, 
	    'resizable': false,
		'responsive': true,
		'draggable': false,
		'closeOnEscape' : true,
		'buttons' : [{
				'text' : 	ape_gallery_type_js_text.create,
				'class' : 	'button  button-primary button-large',
				'click' : 	wpApeGalleryTypeDialogOpenItem
		}],
	});
	window['apeGalleryTypeDialog'] = apeGalleryTypeDialog;
	jQuery(".ui-dialog-titlebar-close").addClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close");
});