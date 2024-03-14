/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

(function($) {

	var wpape_init = 1;
/* 
 Show\Hide block in gallery settings 
*/
	jQuery(document).on('change', '.wpape_action_element',  function(){
		var el = jQuery(this);
		var depends = el.data('depends');
		if(depends){
			if(el.is(':checked')){
				jQuery(depends).show(wpape_init?0:'fast');
			} else {
				jQuery(depends).hide(wpape_init?0:'fast');	
			} 
		}
	});

/* 
 Show\Hide block in gallery settings 
*/

	jQuery(document).on('change', '.wpape_action_element_select',  function(){
		var el = jQuery(this);
		if(el.data('depends')){
			var param 	= window[el.attr('id')+"_depends"];
			if(param!=false){
				jQuery.each(param, function(index, valItem) {
					jQuery(valItem).hide(wpape_init?0:'fast');
				});
				jQuery(param[el.val()]).show(wpape_init?0:'fast');
			}
		}
	});

/* 
 Enabled\Disabled block in gallery settings 
*/

	jQuery('.wpape_colums_auto').on('change', function(){
		var el = jQuery(this);
		if(el.is(':checked')){
			jQuery('#'+el.data('width-id')).attr('disabled', 'disabled');
			jQuery('#'+el.data('colums-id')).removeAttr('disabled');
		} else {
			jQuery('#'+el.data('colums-id')).attr('disabled', 'disabled');
			jQuery('#'+el.data('width-id')).removeAttr('disabled');
		}
	}).change();

	jQuery('.wpape_action_element').change();
	jQuery('.wpape_action_element_select').change();


	if(!WPAPE_GALLERY_PREMIUM){
		jQuery("#wpape_hover").change( function () {
			var el = jQuery(this);
			if(el.val()==2){
				window['apeGalleryDialog'].dialog("open");
				el.selectpicker('val', 1);
			} 
		});
	}
	
	wpape_init= 0;

/* 
 Save accordion stage in gallery settings 
*/

	jQuery('#accordion .panel').on('show.bs.collapse', function (e) {
		jQuery('#wpape_saveAccord').val( jQuery(e.target).attr('id') );
	});

	jQuery('#wpape_hide_overview').click(function(event) {
		event.preventDefault();
		jQuery('#wpape_overview_metabox-hide').click();
	});
	

})(jQuery);