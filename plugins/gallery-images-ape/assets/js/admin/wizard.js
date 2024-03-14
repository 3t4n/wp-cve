/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

/* 
 First Run Wizard Gallery Ape 
*/

jQuery(function(){
	var apeGalleryWizardDialog = jQuery("#wpape_showWizard");
	
	var bodyClass = apeGalleryWizardDialog.data("body");

	if(bodyClass) jQuery("body").addClass(bodyClass);

	apeGalleryWizardDialog.dialog({
		'dialogClass' : 'wp-dialog',
		'title': 		apeGalleryWizardDialog.data('title'),
		'modal': 		true,
		'autoOpen': 	apeGalleryWizardDialog.data('open'),
		'width': 		'600', 
	    'maxWidth': 	600,
	    'height': 		'auto',
	    'fluid': 		true, 
	    'resizable': 	false,
		'responsive': 	true,
		'draggable': 	false,
		'closeOnEscape': true,
		'buttons': [
/*			{
				'text' : 	apeGalleryWizardDialog.data('info'),
				'class' : 	'button button-primary',
				'click' : 	function(){
					jQuery(this).dialog('close'); 
					jQuery.post(ajaxurl, { 'action': 'ape_gallery_save_hide_wizard' }, function(response) {
						location.href = "edit.php?post_type=wpape_gallery_type&page=wpape-gallery-settings&tab=source_options";
					});
				}
			},*/
			{
				'text': 	apeGalleryWizardDialog.data('close'),
				'class': 	'button button-default wpape_wizard_close_button',
				'click': 	function() {
					jQuery(this).dialog('close'); 
					//jQuery.post(ajaxurl, { 'action': 'ape_gallery_save_hide_wizard' }, function(response) {
						//alert('Got this from the server: ' + response);
					//});
				}
			}
		],
		open: function( event, ui ){

		},
		close: function( event, ui ){
			jQuery.post(ajaxurl, { 'action': 'ape_gallery_save_hide_wizard' }, function(response) {
				//alert('Got this from the server: ' + response);
			});
		}
	});
	window['apeGalleryWizardDialog'] = apeGalleryWizardDialog;
	jQuery(".ui-dialog-titlebar-close").addClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close");
	
});