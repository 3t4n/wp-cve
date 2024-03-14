(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */	
	
	$(function() {
		/*Plugin License Check*/
		jQuery("#myworks_wc_xero_sync_check_license").submit(function(e){
            e.preventDefault();
			var mw_wc_xero_license = jQuery('#mw_wc_xero_license').val();
			mw_wc_xero_license = jQuery.trim(mw_wc_xero_license);
			if(mw_wc_xero_license==''){
				alert('Please enter license key');
				return false;
			}
            var data = {
                "action": "myworks_wc_xero_sync_check_license"
            };
            data = jQuery(this).serialize() + "&" + jQuery.param(data);
			jQuery('#mwqs_license_chk_loader').css('visibility','visible');
            jQuery.ajax({
               type: "POST",
               url: ajaxurl,
               data: data,
               cache:false,
               datatype: "json",
               success: function(data){
				   jQuery('#mwqs_license_chk_loader').css('visibility','hidden');
                   alert(data);
				   if(data=='License Activated'){
					   location.reload();
				   }                   
               },
			   error: function(data) {
					jQuery('#mwqs_license_chk_loader').css('visibility','hidden');
				    alert('Error');
			   }
             });
			 
        });
		
		
	})
	
})( jQuery );

function mw_xero_sync_check_all(checkbox,start_with){
	var is_checked = checkbox.checked;
	jQuery('input:checkbox').each(function(){		
		if(typeof(jQuery(this).attr('id'))!=='undefined' && jQuery(this).attr('id').match("^"+start_with) && !jQuery(this).is(':disabled')){			
			if(is_checked){
				jQuery(this).prop('checked', true);
			}else{
				jQuery(this).prop('checked', false);
			}
		}		
	});
}

var mwXsPopUpWin_obj=0;
function popUpWindow(URLStr,popUpWin, left, top, width, height){
 //Fixed Width Height
 width = 750;
 height = 480;
 
 left = (screen.width/2)-(width/2);
 top = (screen.height/2)-(height/2);
	
  if(mwXsPopUpWin_obj){
    //if(!mwXsPopUpWin_obj.closed) mwXsPopUpWin_obj.close();    
    if(mwXsPopUpWin_obj.name==popUpWin){
	 alert('Sync status window already opened');
     return false;
     }
  }
 mwXsPopUpWin_obj = open(URLStr, popUpWin, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
 return mwXsPopUpWin_obj;
}

function mw_xero_sync_string_is_empty(val){
	return (val === undefined || val == null || val.length <= 0) ? true : false;
}