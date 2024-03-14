function pelm_setCookie(c_name, value, exdays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}

function pelm_getCookie(c_name) {
	var i, x, y, ARRcookies = document.cookie.split(";");
	for (i = 0; i < ARRcookies.length; i++) {
		x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
		y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
		x = x.replace(/^\s+|\s+$/g, "");
		if (x == c_name) {
			return unescape(y);
		}
	}
}

jQuery(document).ready(function(){
	jQuery('.save-state').each(function(i){
	    var val =  pelm_getCookie( 'pelm_' + jQuery(this).attr('id') );
		if(val)
			jQuery(this).val( val );
	});
});

function pelm_do_load(withImportSettingsSave,attach_properties){
    pending_load++;
	try{
		if(pending_load < 6){
			var n = 0;
			for(var key in tasks){
				if(tasks.hasOwnProperty(key))
					n++;
			}
				
			if(n > 0) {
			  setTimeout(function(){
				pelm_do_load();
			  },2000);
			  return;
			}
		}
	}catch(ex){
		pending_load = 0;
	}

    var POST_DATA = {pelm_nonce_check: jQuery("input[name='pelm_nonce_check']:last").val(), pelm_security: jQuery("input[name='pelm_security']:last").val() };
	
	POST_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
	POST_DATA.sortColumn           = pelm_get_sort_property();
	POST_DATA.limit                = jQuery('#txtlimit').val();
	POST_DATA.page_no              = jQuery('#paging_page').val();
	
	jQuery('.filter_option *[name]').each(function(ind){
		POST_DATA[jQuery(this).attr("name")] = jQuery(this).val();
	});
	
	if(typeof attributes !== 'undefined'){
		for(var index in attributes){
			if(attributes.hasOwnProperty(index)){
				POST_DATA["pattribute_" + attributes[index].id] = jQuery('.filter_option *[name="pattribute_' + attributes[index].id + '"]').val();
			}
		}
	}
	
	if(typeof custom_fields !== 'undefined'){
		for(var index in custom_fields){
			if(custom_fields.hasOwnProperty(index)){
				if(custom_fields[index].type == "term"){
					POST_DATA[custom_fields[index].name] = jQuery('.filter_option *[name="' + custom_fields[index].name + '"]').val();
				}
			}
		}
	}
	
	if(attach_properties){
		for(var aprop in attach_properties){
			if(attach_properties.hasOwnProperty(aprop)){
				POST_DATA[aprop] = attach_properties[aprop];
			}
		}
	}

	jQuery('#operationFRM').empty();
	jQuery('#operationFRM').append(jQuery("<input type='hidden' name='elpm_shop_com' value='wooc' />"));
	
	for(var key in POST_DATA){
		if(POST_DATA.hasOwnProperty(key)){
			if(POST_DATA[key])
				jQuery('#operationFRM').append("<INPUT type='hidden' name='" + key + "' value='" + POST_DATA[key] + "' />");
		}
	}
	
	if(withImportSettingsSave){
	  var settings = {};
	  jQuery('#settings-panel INPUT[name],#settings-panel TEXTAREA[name],#settings-panel SELECT[name]').each(function(i){
		if(jQuery(this).attr('type') == "checkbox")
			settings[jQuery(this).attr('name')] = jQuery(this)[0].checked ? 1 : 0;
		else
			settings[jQuery(this).attr('name')] = jQuery(this).val() instanceof Array ? jQuery(this).val().join(",") : jQuery(this).val(); 
	  });
	  settings.save_import_settings = 1;
	  
	  for(var key in settings){
		  if(settings.hasOwnProperty(key)){
			  var inp = jQuery("<INPUT type='hidden' />");
			  inp.attr("name", key);
			  inp.attr("value",settings[key]);
			  jQuery('#operationFRM').append(inp);
		  }
	  }
	}
	
    jQuery('#operationFRM').submit();
}

window.doLoad = pelm_do_load;

function pelm_store_state(){
	if(!window.pelm_localStorage_clear_flag){
		var manualColumnWidths = [];
		for(var i = 0; i < DG.countCols(); i++){
			var w = DG.getColWidth(i);
			manualColumnWidths.push(w == 80 ? null : w );
		}
		DG.runHooks('persistentStateSave', 'manualColumnWidths', manualColumnWidths);
	}
	
	jQuery('.save-state').each(function(i){
		pelm_setCookie('pelm_' + jQuery(this).attr('id'), jQuery(this).val(), 30);  
	});
}

function pelm_set_variation_product_thumbnail(id,url){
  try{
     
	
	var qs = url.split('?')[1].split('&');
    for(var i = 0; i < qs.length; i++)
		if(qs[i].indexOf('attachment_id') >= 0)
			id = qs[i].split("=")[1];
	
 	var url = jQuery('#TB_iframeContent').contents().find('INPUT[name="attachments[' + id + '][url]"').val();
	if(!url){
		url = jQuery('INPUT[name="attachments[' + id + '][url]"').val();
	}
	
	if(!url){
	    var thmb = jQuery('#TB_iframeContent').contents().find('#media-item-' + id + ' IMG.thumbnail');
		
		if(!thmb[0])
			thmb = jQuery('#media-item-' + id + ' IMG.thumbnail');
		
		if(thmb[0])
			url	= thmb.attr('src');
	}
	
	if(url){
		url = url.split('uploads/')[1];
		url = url.replace('-150x150','');
		
		if(window.customImageEditorSave)
			window.customImageEditorSave(id,url);
		else if(window.parent.customImageEditorSave)
            window.parent.customImageEditorSave(id,url);		
	}
  }catch(e){}
};

function WPSetThumbnailID(id){
  try{
	var url = jQuery('#TB_iframeContent').contents().find('INPUT[name="attachments[' + id + '][url]"').val();
	if(!url){
		url = jQuery('INPUT[name="attachments[' + id + '][url]"').val();
	}
	
	if(!url){
	    var thmb = jQuery('#TB_iframeContent').contents().find('#media-item-' + id + ' IMG.thumbnail');
		
		if(!thmb[0])
			thmb = jQuery('#media-item-' + id + ' IMG.thumbnail');
		
		if(thmb[0])
			url	= thmb.attr('src');
	}
	
	if(url){
		url = url.split('uploads/')[1];
		url = url.replace('-150x150','');
		
		if(window.customImageEditorSave)
			window.customImageEditorSave(id,url);
		else if(window.parent.customImageEditorSave)
            window.parent.customImageEditorSave(id,url);		
	}
  }catch(e){}
} 

function WPSetThumbnailHTML(id){
 //
}



