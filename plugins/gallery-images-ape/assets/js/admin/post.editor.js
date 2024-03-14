/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

jQuery(function(){
	var apeGalleryDialog = jQuery("#wpape-gallery").appendTo("body");
	apeGalleryDialog.dialog({
		'dialogClass' : 'wp-dialog',
		'title': wpape_gallery_trans.apeGalleryTitle,
		'modal' : true,
		'autoOpen' : false,
		'width': 'auto',
	    'maxWidth': 700,
	    'height': 'auto',
	    'fluid': true, 
	    'resizable': false,
		'responsive': true,
		'draggable': false,
		'closeOnEscape' : true,
		'buttons' : [{
				'text' : wpape_gallery_trans.closeButton,
				'class' : 'button button-default',
				'click' : function() { jQuery(this).dialog('close'); }
		},{
				'text' : wpape_gallery_trans.insertButton,
				'class' : 'button button-primary',
				'click' : function() { 
					var galleryId = jQuery('#page_id', apeGalleryDialog).val();
					window.parent.send_to_editor('[ape-gallery '+galleryId+']');
        			window.parent.tb_remove();
					jQuery(this).dialog('close'); 
				}
		}],
		open: function( event, ui ) {}
	});
	jQuery(document).on( 'click', '#insert-wpape-gallery', function(event) { 
		apeGalleryDialog.dialog('open'); 
		return false; 
	});
});