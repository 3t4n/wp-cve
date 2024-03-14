jQuery(document).ready(function($){
	
	$('.ewic_select').bind('change', function() {
		
		if ($(this).find('option:selected').filter(":contains('PRO')").length > 0) {
			
				$("#myModalupgrade").modal({
					keyboard: false,
					backdrop: 'static'
					});
			
				}
	
		});	
	
		jQuery('.eno_options').slideUp();	
		
		//jQuery(".chosen-select").chosen({disable_search_threshold: 10});	
		
		
		jQuery('.eno_section h3').click(function(){		
			if(jQuery(this).parent().next('.eno_options').css('display')=='none')
				{	jQuery(this).removeClass('inactive');
					jQuery(this).addClass('active');
					jQuery(this).children('img').removeClass('inactive');
					jQuery(this).children('img').addClass('active');
					
				}
			else
				{	jQuery(this).removeClass('active');
					jQuery(this).addClass('inactive');		
					jQuery(this).children('img').removeClass('active');			
					jQuery(this).children('img').addClass('inactive');
				}
				
			jQuery(this).parent().next('.eno_options').slideToggle('slow');	
		});	
	

//  INSERT IMAGE
    jQuery(".addimage a").bind("click", function(event) {
	
		var msgclka = jQuery(this).attr( 'rel' );
		var msgclk = msgclka.split("-");

		if ( msgclk[1] == 'l35' ) {
			window.send_to_editor = function(html) {
				if (msgclk[0] == 'image'){
					imgurl = jQuery('img',html).attr('src');
					jQuery('#notify_image').val(imgurl);
					jQuery('#imgthumbnailprv').attr("src", imgurl);
					if (imgurl.length > 0 ) {
						tb_remove();
						return false;
						};
					}
				}
			}
			else if ( msgclk[1] == 'g35' ) {
				var emgUploadFrame=false;
				event.preventDefault();
				if (emgUploadFrame) {
					emgUploadFrame.open();
					return;
					}
					emgUploadFrame = wp.media.frames.my_upload_frame = wp.media({
						frame: "select",
						title: "Insert Image",
						library: {
							type: msgclk[0]
							},
						button: {
							text: "Insert into Notify",
							},
						multiple: false
						});
						
					emgUploadFrame.on("select", function () {
						var selection = emgUploadFrame.state().get("selection");
						selection.map(function (attachment) {
							attachment = attachment.toJSON();
							if (attachment.id) {
								var newLogoID = attachment.id;
								
								if ( msgclk[0] == 'image' ) {
									var FinalMediaURL = attachment.sizes.full.url;
									jQuery("#notify_image").val(FinalMediaURL);
									jQuery('#imgthumbnailprv').attr("src", FinalMediaURL);
									
									if (jQuery("#notify_image").val().length > 0 ) {
										jQuery(".deleteimage").show("slow");
										} else {
											jQuery(".deleteimage").hide("fast");
											}
									}			
								}
							});
						});
						emgUploadFrame.open();
					}
			});	
			
// DELETE IMAGE			
	jQuery('.delimage a').click(function() {
		var answer = confirm('Are you sure you want to delete this image?');
			if (answer){
				jQuery("#notify_image").val('');
				jQuery('#imgthumbnailprv').attr("src", jQuery(this).attr( 'data-img' ));
				
					if (jQuery("#notify_image").val().length > 0 ) {
						jQuery(".deleteimage").show("slow");
						} else {
							jQuery(".deleteimage").hide("fast");
							}			

		}
			else {}
	});
	
// Shortcode hover		
	
jQuery.fn.enotyselectText = function(){
    var doc = document
        , element = this[0]
        , range, selection
    ;
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};


    jQuery(".enoty-sc-metabox").mouseover(function(){
        jQuery(this).animate({opacity: 0.25}, function(){
            jQuery(this).animate({opacity: 1});
			jQuery(this).enotyselectText();
        });
    });
	
	
jQuery('#enoty_cp_ribbon_container').on('change', function () {
	
	var sosbutoncon = jQuery('.enoty_cp_share_fb, .enoty_cp_share_twtr, .enoty_cp_share_gplus, .enoty_cp_share_pin, .enoty_cp_share_email, .enoty_cp_share_pos');
	var custombutton = jQuery('.enoty_cp_ribbon_text_fcol, .enoty_cp_ribbon_text, .enoty_cp_ribbon_button_text, .enoty_cp_button_text_fcol, .enoty_cp_ribbon_button_gradient_color, .enoty_cp_ribbon_button_link, .enoty_cp_ribbon_button_link_target');
	var optionbtn = jQuery('.enoty_cp_optin_privacy_note, .enoty_cp_optin_phldr_name, .enoty_cp_option_name_opt, .enoty_cp_optin_phldr_email, .enoty_cp_optin_submit_text, .enoty_cp_optin_text_fcol, .enoty_cp_option_submit_gradient_color');

		jQuery(optionbtn).css("background-color", "");
		jQuery(sosbutoncon).css("background-color", "");
		jQuery(custombutton).css("background-color", "");
			
	switch (this.value) {

		case 'optin':
			jQuery(".enoty_cp_ribbon").fadeIn(500);
			jQuery(sosbutoncon).hide('slow');
			jQuery(custombutton).hide('slow');
			jQuery(optionbtn).fadeIn(500);
			jQuery(optionbtn).effect("highlight", {}, 1000);
			jQuery(optionbtn).css("background-color", "#F3FCF3");
		
		break;
		
		case 'socialbutton':
			jQuery(".enoty_cp_ribbon").fadeIn(500);
			jQuery(optionbtn).hide('slow');
			jQuery(custombutton).hide('slow');
			jQuery(sosbutoncon).fadeIn(500);
			jQuery(sosbutoncon).effect("highlight", {}, 1000);
			jQuery(sosbutoncon).css("background-color", "#EAF4FF");
			
		break;
		
		case 'button':
			jQuery(".enoty_cp_ribbon").fadeIn(500);
			jQuery(optionbtn).hide('slow');
			jQuery(sosbutoncon).hide('slow');
			jQuery(custombutton).fadeIn(500);
			jQuery(custombutton).effect("highlight", {}, 1000);
			jQuery(custombutton).css("background-color", "#FFF8F0");
		
		break;
		
		case 'none':
			jQuery(".enoty_cp_ribbon").hide("slow");
			jQuery(optionbtn).hide('slow');
			jQuery(sosbutoncon).hide('slow');
			jQuery(custombutton).hide('slow');
		
		break;
		
		}			
	});	
	
// MailingList Manager	
jQuery('#easynotify_mailman').on('change', function () {
		    //jQuery('.mailmanager-fields').find("div").not(".mailmanager-selector").fadeOut("slow");
			jQuery(".mailmanager-selector").siblings().fadeOut("slow");
			jQuery('.'+jQuery(this).val()+'-field').fadeIn(500);
	});	
	
	jQuery('#easynotify_mailman').trigger("change");	
	
		
}); // END DOC READY

// LAYOUT CONTROL
function notyLayoutctrl (ctrl) {		
			switch (ctrl) {
				
				case 'head_txt.png':
					jQuery('.enoty_cp_maincontent').show('slow');
					jQuery('.enoty_cp_main_text_size_col').show('slow');
					jQuery('.enoty_cp_img').hide('slow');
					jQuery('.enoty_cp_bullet').hide('slow');
					jQuery('.enoty_cp_bullet_list_text').hide('slow');
					jQuery('.enoty_cp_bullet_style_color').hide('slow');
					jQuery('.enoty_cp_video').hide('slow');
					jQuery('.enoty_cp_video_autoplay').hide('slow');					
					
				break;
				
				case 'head_img_txt_list.png':
					jQuery('.enoty_cp_maincontent').show('slow');
					jQuery('.enoty_cp_main_text_size_col').show('slow');
					jQuery('.enoty_cp_img').show('slow');
					jQuery('.enoty_cp_bullet').show('slow');
					jQuery('.enoty_cp_bullet_list_text').show('slow');
					jQuery('.enoty_cp_bullet_style_color').show('slow');
					jQuery('.enoty_cp_video').hide('slow');
					jQuery('.enoty_cp_video_autoplay').hide('slow');
				
				break;
				
				case 'head_txt_img.png':
					jQuery('.enoty_cp_maincontent').show('slow');
					jQuery('.enoty_cp_main_text_size_col').show('slow');
					jQuery('.enoty_cp_img').show('slow');
					jQuery('.enoty_cp_bullet').hide('slow');
					jQuery('.enoty_cp_bullet_list_text').hide('slow');
					jQuery('.enoty_cp_bullet_style_color').hide('slow');
					jQuery('.enoty_cp_video').hide('slow');
					jQuery('.enoty_cp_video_autoplay').hide('slow');
					
				break;	
				
				case 'head_txt_list.png':
					jQuery('.enoty_cp_maincontent').show('slow');
					jQuery('.enoty_cp_main_text_size_col').show('slow');
					jQuery('.enoty_cp_img').hide('slow');
					jQuery('.enoty_cp_bullet').show('slow');
					jQuery('.enoty_cp_bullet_list_text').show('slow');
					jQuery('.enoty_cp_bullet_style_color').show('slow');
					jQuery('.enoty_cp_video').hide('slow');
					jQuery('.enoty_cp_video_autoplay').hide('slow');
				
				break;	
				
				case 'head_txt_list_img.png':
					jQuery('.enoty_cp_maincontent').hide('slow');
					jQuery('.enoty_cp_main_text_size_col').hide('slow');
					jQuery('.enoty_cp_img').show('slow');
					jQuery('.enoty_cp_bullet').show('slow');
					jQuery('.enoty_cp_bullet_list_text').show('slow');
					jQuery('.enoty_cp_bullet_style_color').show('slow');
					jQuery('.enoty_cp_video').hide('slow');
					jQuery('.enoty_cp_video_autoplay').hide('slow');

				break;	
				
				case 'head_video_txt_list.png':
					jQuery('.enoty_cp_video').show('slow');
					jQuery('.enoty_cp_maincontent').show('slow');
					jQuery('.enoty_cp_main_text_size_col').show('slow');
					jQuery('.enoty_cp_img').hide('slow');
					jQuery('.enoty_cp_bullet').show('slow');
					jQuery('.enoty_cp_bullet_list_text').show('slow');
					jQuery('.enoty_cp_bullet_style_color').show('slow');
					jQuery('.enoty_cp_video_autoplay').show('slow');

				break;	

			}

}	

// RIBBON CONTROL
function ribbonrelayout (ctrl) {
	
	var sosbutoncon = jQuery('.enoty_cp_share_fb, .enoty_cp_share_twtr, .enoty_cp_share_gplus, .enoty_cp_share_pin, .enoty_cp_share_email, .enoty_cp_share_pos');
	var custombutton = jQuery('.enoty_cp_ribbon_text_fcol, .enoty_cp_ribbon_text, .enoty_cp_ribbon_button_text, .enoty_cp_button_text_fcol, .enoty_cp_ribbon_button_gradient_color, .enoty_cp_ribbon_button_link, .enoty_cp_ribbon_button_link_target');
	var optionbtn = jQuery('.enoty_cp_optin_privacy_note, .enoty_cp_optin_phldr_name, .enoty_cp_option_name_opt, .enoty_cp_optin_phldr_email, .enoty_cp_optin_submit_text, .enoty_cp_optin_text_fcol, .enoty_cp_option_submit_gradient_color');

		jQuery(optionbtn).css("background-color", "");		
		jQuery(sosbutoncon).css("background-color", "");
		jQuery(custombutton).css("background-color", "");
		jQuery(optionbtn).hide();
		jQuery(sosbutoncon).hide();
		jQuery(custombutton).hide();
		
			
	switch (ctrl) {

		case 'optin':
			jQuery(".enoty_cp_ribbon").fadeIn(500);
			jQuery(sosbutoncon).hide('slow');	
			jQuery(custombutton).hide('slow');
			jQuery(optionbtn).fadeIn(500);
			jQuery(optionbtn).effect("highlight", {}, 1000);
			jQuery(optionbtn).css("background-color", "#F3FCF3");
		
		break;
		
		case 'socialbutton':
			jQuery(".enoty_cp_ribbon").fadeIn(500);
			jQuery(optionbtn).hide('slow');
			jQuery(custombutton).hide('slow');
			jQuery(sosbutoncon).fadeIn(500);
			jQuery(sosbutoncon).effect("highlight", {}, 1000);
			jQuery(sosbutoncon).css("background-color", "#EAF4FF");
			
		break;
		
		case 'button':
			jQuery(".enoty_cp_ribbon").fadeIn(500);
			jQuery(optionbtn).hide('slow');
			jQuery(sosbutoncon).hide('slow');
			jQuery(custombutton).fadeIn(500);
			jQuery(custombutton).effect("highlight", {}, 1000);
			jQuery(custombutton).css("background-color", "#FFF8F0");
					
		break;
		
		case 'none':
			jQuery(".enoty_cp_ribbon").hide("slow");
			jQuery(optionbtn).hide('slow');
			jQuery(sosbutoncon).hide('slow');
			jQuery(custombutton).hide('slow');
		
		break;
		
		}		
	
}

/* Notify Page ScrollToTop */
function scrollToTop() {
    verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = jQuery('body');
    offset = element.offset();
    offsetTop = offset.top;
    jQuery('html, body').animate({scrollTop: offsetTop}, 700, 'linear');
}