var glgloader;

jQuery(document).ready(function($) {
	
	var ytIsLoaded = false, freeLoaded = false, preLoaded = false;
	// Activate checkbox
	$(".ios-checkbox").iosCheckbox();
	
	// Animate Settings Page on Load
	jQuery(".panelloader").removeClass("ploader").hide();
	jQuery("#page-settings").fadeIn(1000);
	
	// Apply WP ColorPicker
	$('#glg_gallery_overlay_color').wpColorPicker();
		
	// Submit handler
	$(".glg_form_submit").bind("click",function (e) {
		
		e.preventDefault();
		
		$('.glg_save_status').hide();
		
		var formData = $('#'+$(this).data('formname')).serializeArray();
		
		glg_form_save( formData, $(this).data('formname'), $(this).data('nonce'), $(this) );
		
		});
		
		
	// SAVE DATA AJAX	
	function glg_form_save( fdata, fid, nnc, smbt ) {
		
		jQuery('.set_'+fid).css('color', '#148919');
		jQuery('.set_'+fid).hide();
	
		window.clearTimeout(glgloader);
	
		jQuery(smbt).attr('disabled','disabled');
		jQuery('#loader_'+fid).addClass('button_loading');
	
		loading(fid);
	
		dat = {};
		dat['action'] = 'glg_ajax_save_settings';
		dat['fieldsdata'] = fdata;
		dat['security'] = nnc;
	
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: dat,
		
			success: function( response ) {
			
				switch ( response.ok ) { 
			
    				case true: 
				
					glg_cleanup(smbt, fid);

        			break;

				default:
			
				alert('Failed! Please refresh the page and try again.');
				glg_cleanup(smbt, fid);

				}
			
			// end success-		
			}
			
		// end ajax
		});

	
	}
	
	// Restore element to default
	function glg_cleanup( smbt, fid ) {
	
		$('#overlay').remove();
		jQuery(smbt).removeAttr('disabled');
		jQuery('#loader_'+fid).removeClass('button_loading');
		jQuery('.set_'+fid).fadeIn(500);
	
		glgloader = window.setTimeout(function() {
		
			jQuery('.set_'+fid).fadeOut();
	
		}, 4000);
	};


	// Create animate on Save
	function loading(fid) {
        // add the overlay with loading image to the page
        var over = '<div id="overlay">' +
            '<img id="loading">' +
            '</div>';
        $(over).appendTo('#'+fid);
    };
	
	
	// AJAX Get Free Plugins
	$('body').on('click', '.glg_ajax_caller', function(event){
		
		glg_ajax_caller_func($(this));

		event.preventDefault();
		
	});
		
	$('body').on('click', '#glg-docs', function(event){
		
		if (!ytIsLoaded) {
			$(".glg-iframe").attr('src', function(index, attr){ 
			  return attr;
			});
		}
		
		ytIsLoaded = true;
		event.preventDefault();
		
	});
		
		
	// RETRIEVE FREE PLUGINS LIST
	function glg_ajax_caller_func( elmnt ) {
		
		if (elmnt.data('act') == 'glg_free_plugins_page' && freeLoaded) return false;
		if (elmnt.data('act') == 'glg_pro_plugins_page' && preLoaded) return false;
		
		$(".glgloader").removeClass("glgloaderclass");	
		$('#'+elmnt.data('act')).empty().hide();
		$(".glgloader").fadeIn(300).addClass("glgloaderclass");
	
		dat = {};
		dat['action'] = elmnt.data('act');
		dat['security'] = elmnt.data('nonce');
	
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data: dat,
		
			success: function( response ) {
				
				
				if ( response ) {
					
					$(".glgloader").removeClass("glgloaderclass").hide();
					$('#'+elmnt.data('act')).html(response).fadeIn(1000);
					if (elmnt.data('act') == 'glg_free_plugins_page') freeLoaded = true;
					if (elmnt.data('act') == 'glg_pro_plugins_page') preLoaded = true;

				} else {
					
					alert('Failed! Please refresh the page and try again.');
					
					}

			}
			
		// end ajax
		});
	
	}
	// Option tabs
    $( "#option-tree-settings-api" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#option-tree-settings-api li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );

});

