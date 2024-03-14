jQuery(document).ready(function($){
	var tbxhr;
    jQuery('a.change_property_trigger').click(function(){
		jQuery(this).next('div.change_property_wrap').show();
		jQuery(this).hide();
    });
    jQuery('a.cancel_trigger').click(function(){
		jQuery(this).parent('div.change_property_wrap').siblings('a.change_property_trigger').show();
		jQuery(this).parent('div.change_property_wrap').hide();
    });
    	
    jQuery('.change_property_wrap .styler_list').change(function(){
	if( tbxhr != null ) {
		tbxhr.abort();
		tbxhr = null;
	}
	tbxhr = jQuery.ajax({
	    type: "POST"
	    , url: 'tblight.php?controller=tblight&action=changeStatusAjax&ajax=1'
	    , data: 'id='+itemID+'&new_status='+jQuery(this).val()
	    , dataType: 'json'
	    //, async: false
	    , beforeSend: function(){
		jQuery('.change_property_wrap .styler_list').after('<img src="'+filePath+'assets/images/ajax-loader.gif" alt="Loading.." id="ajax_loader" />');
	    }
	    , complete: function(){
	    }
	    , success: function(response){
		jQuery('img#ajax_loader').remove();
		if(response.error==1)
		{
		    alert(response.msg);
		    return false;
		}
		else {
		    window.location.reload();
		}
	    }
	})
    });    			
});