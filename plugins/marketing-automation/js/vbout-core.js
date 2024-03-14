var post_images = [];
var current_image_index = 0;

//	FACEBOOK SHARE LINK
//	*******************
var hasShare = false;
var syncWait = false;

jQuery(document).on('click', '.syncUsersManualy', function(e) { 
	e.preventDefault();
	
	if (syncWait)
		return;
	
	var syncButton = jQuery(this);
	
	jQuery.ajax({
		url: vbAjaxObj.ajax_url,
		type: "POST",
		data: "action=synchronize_wp_users",
		beforeSend: function() {
			//	INVOKE THE OVERLAY
			syncWait = true;
			
			jQuery(syncButton).data('label', jQuery(syncButton).val());
			jQuery(syncButton).val('please wait...');
			
			jQuery(syncButton).attr('disabled', true);
		},
		success: function(data) {
			if (data == 'done') {
				var newMessage = '<span style="color: green;">&nbsp;&nbsp;Synch completed successfully.</span>';
				jQuery(syncButton).after(newMessage);
				jQuery(newMessage).show().delay(3000).fadeOut().remove();
			} else {
				var newMessage = '<span style="color: red;">Synchronization error.</span>';
				jQuery(syncButton).after(newMessage);
				jQuery(newMessage).show().delay(3000).fadeOut().remove();
			}
			
			syncWait = false;
			
			jQuery(syncButton).val(jQuery(syncButton).data('label'));
			jQuery(syncButton).attr('disabled', false);
		},
		error: function(ex) {
			//alert("An error occured: " + ex.status + " " + ex.statusText);
		}
	});
});

jQuery(document).on('click', '.livePreviewCanvas a.prev-page', function(e) { 
	e.preventDefault();
	
	current_image_index--;
	
	if (current_image_index <= 0) {
		current_image_index = 0;
	}
	
	jQuery(jQuery(this).attr('data-channel')+'_photo_url').val(post_images[current_image_index]);
	
	triggerImagePreview(post_images[current_image_index], jQuery(this).attr('data-channel'));
});

jQuery(document).on('click', '.livePreviewCanvas a.next-page', function(e) { 
	e.preventDefault();
	
	current_image_index++;
	
	if (current_image_index >= (post_images.length-1)) {
		current_image_index = post_images.length-1;
	}
	
	jQuery(jQuery(this).attr('data-channel')+'_photo_url').val(post_images[current_image_index]);
	
	triggerImagePreview(post_images[current_image_index], jQuery(this).attr('data-channel'));
});

function triggerLivePreview(content)
{
	// IF HAS IMAGE DON'T SHOW THE SHARE BOX
	//get the first image
	var content = jQuery('<div>'+ content + '</div>');
	//var imageUrl = jQuery('img:first', content).attr('src');
	//var imageAlt = jQuery('img:first', content).attr('alt');
	var imageUrl = jQuery('a#set-post-thumbnail img:first').attr('src');
	var imageAlt = jQuery('a#set-post-thumbnail img:first').attr('alt');
	
	extract_images = jQuery('img', content);
	post_images = [];
	
	jQuery(extract_images).each(function() { 
		post_images.push(jQuery(this).attr('src'));
	});
	
	triggerImagePreview(imageUrl);
	/*
	if (jQuery('[name=vb_post_title]').val() == '') {
		jQuery(".facebook_livepreview_box .userContent").html('Please insert a text to share...');
		jQuery(".twitter_livepreview_box .ProfileTweet-text").html('Please insert a text to share...');
		jQuery(".linkedin_livepreview_box .share-body").html('Please insert a text to share...');
	} else {
		jQuery(".facebook_livepreview_box .userContent").html(jQuery('[name=vb_post_title]').val());
		jQuery(".twitter_livepreview_box .ProfileTweet-text").html(jQuery('[name=vb_post_title]').val()+' ('+jQuery('[name=vb_post_url]').val()+')');
		jQuery(".linkedin_livepreview_box .share-body").html(jQuery('[name=vb_post_title]').val());
	}
	*/
	
	if (imageUrl == undefined) {
		jQuery('[name=vb_photo_url]').val('');
		//jQuery('[name=photo_alt]').val('');
		
		jQuery('.facebook_livepreview_box .shareHeaderTitle').hide();
		jQuery('.linkedin_livepreview_box .share-title').hide();
		
		jQuery('.facebook_livepreview_box .shareHeaderLink').hide();
		jQuery('.linkedin_livepreview_box .share-link').hide();
				
		jQuery('.facebook_livepreview_box .shareHeaderContent').hide();
		jQuery('.linkedin_livepreview_box .description').hide();
	
		jQuery('.facebook_livepreview_box .shareLink img').hide();
		jQuery('.linkedin_livepreview_box .share-object .image').hide();
	} else {
		jQuery('[name=vb_photo_url]').val(imageUrl);
				
		if (jQuery('[name=vb_post_title]').val() != '') {
			hasShare = true;
			
			//jQuery('[name=photo_alt]').val(imageAlt);

			jQuery('.facebook_livepreview_box .shareHeaderTitle').html(jQuery('[name=vb_post_title]').val());
			jQuery('.facebook_livepreview_box .shareHeaderTitle').show();
			
			jQuery('.linkedin_livepreview_box .share-title .title').html(jQuery('[name=vb_post_title]').val());
			jQuery('.linkedin_livepreview_box .share-title').show();
			
			console.log('hasTitle');
		} else {
			//jQuery('[name=photo_alt]').val('');
			
			jQuery('.facebook_livepreview_box .shareHeaderTitle').hide();
			jQuery('.linkedin_livepreview_box .share-title').hide();
		}
		
		if (jQuery('[name=vb_post_url]').val() != '') {
			hasShare = true; 
			
			jQuery('.facebook_livepreview_box .shareHeaderLink').html(jQuery('[name=vb_post_url]').val());
			jQuery('.facebook_livepreview_box .shareHeaderLink').show();
			
			jQuery('.linkedin_livepreview_box .share-link').html(jQuery('[name=vb_post_url]').val());
			jQuery('.linkedin_livepreview_box .share-link').show();
			
			console.log('hasURL');
		} else {
			jQuery('.facebook_livepreview_box .shareHeaderLink').hide();
			jQuery('.linkedin_livepreview_box .share-link').hide();
		}
		if (jQuery('[name=vb_post_description]').val() != '') {
			hasShare = true; 
			
			jQuery('.facebook_livepreview_box .shareHeaderContent').html(jQuery('[name=vb_post_description]').val());
			jQuery('.facebook_livepreview_box .shareHeaderContent').show();
			
			jQuery('.linkedin_livepreview_box .description').html(jQuery('[name=vb_post_description]').val());
			jQuery('.linkedin_livepreview_box .description').show();
			
			//console.log('hasURL');
		} else {
			jQuery('.facebook_livepreview_box .shareHeaderContent').hide();
			//jQuery('.linkedin_livepreview_box .share-link').hide();
		}
		
		if (imageUrl != undefined) {
			jQuery('.facebook_livepreview_box .shareLink img').attr('src', imageUrl);
			jQuery('.facebook_livepreview_box .shareLink img').show();
			
			jQuery('.linkedin_livepreview_box .share-object .image img').attr('src', imageUrl);
			jQuery('.linkedin_livepreview_box .share-object .image').show();
			
			console.log('hasPreview');
		} else {
			jQuery('.facebook_livepreview_box .shareLink img').hide();
			jQuery('.linkedin_livepreview_box .share-object .image').hide();
		}
	}

	if (hasShare) {
		jQuery('.facebook_livepreview_box .photo').hide();
		
		jQuery('.facebook_livepreview_box .share').show();
		jQuery('.linkedin_livepreview_box .share-object').show();
	} else {
		if (imageUrl != undefined) {
			jQuery('.facebook_livepreview_box .photo').show();
		}
		
		jQuery('.facebook_livepreview_box .share').hide();
		jQuery('.linkedin_livepreview_box .share-object').hide();
	}
}
 
function triggerImagePreview(ImageId, channel)
{
	if(typeof channel === 'undefined')
		channel = 'all';
	
	if (ImageId == undefined) {
		// NO IMAGE
		if (channel == 'facebook' || channel == 'all') {
			jQuery('.facebook_livepreview_box .shareLink .img').attr('src', '');
			jQuery('.facebook_livepreview_box .scaledImage .img').attr('src', ImageId);
			
			if (hasShare) {
				jQuery('.facebook_livepreview_box .photo').hide();
			} else {
				jQuery('.facebook_livepreview_box .photo').show();
			}
			
			jQuery('[name=facebook_photo_url]').val(ImageId);
		}
		
		if (channel == 'twitter' || channel == 'all') {
			jQuery('.twitter_livepreview_box .TwitterPhoto-mediaSource').attr('src', '');
			jQuery('.twitter_livepreview_box .TwitterPhoto-media').hide();
			
			jQuery('[name=twitter_photo_url]').val(ImageId);
		}
		
		if (channel == 'linkedin' || channel == 'all') {
			jQuery('.linkedin_livepreview_box .share-object .image img').attr('src', '');
			jQuery('.linkedin_livepreview_box .share-object .image').hide();
			jQuery('.linkedin_livepreview_box .share-object').hide();
			
			jQuery('[name=linkedin_photo_url]').val(ImageId);
		}
	} else {
		// AN IMAGE
		if (channel == 'facebook' || channel == 'all') {
			jQuery('.facebook_livepreview_box .shareLink .img').attr('src', ImageId);
			jQuery('.facebook_livepreview_box .scaledImage .img').attr('src', ImageId);
			
			if (hasShare) {
				jQuery('.facebook_livepreview_box .photo').hide();
			} else {
				jQuery('.facebook_livepreview_box .photo').show();
			}
						
			jQuery('[name=facebook_photo_url]').val(ImageId);
		}
		
		if (channel == 'twitter' || channel == 'all') {
			jQuery('.twitter_livepreview_box .TwitterPhoto-mediaSource').attr('src', ImageId);
			jQuery('.twitter_livepreview_box .TwitterPhoto-media').show();
			
			jQuery('[name=twitter_photo_url]').val(ImageId);
		}
		
		if (channel == 'linkedin' || channel == 'all') {
			jQuery('.linkedin_livepreview_box .share-object .image img').attr('src', ImageId);
			jQuery('.linkedin_livepreview_box .share-object .image').show();
			jQuery('.linkedin_livepreview_box .share-object').show();
			
			jQuery('[name=linkedin_photo_url]').val(ImageId);
		}

		// if (channel == 'instagram' || channel == 'all') {
		// 	jQuery('.instagram_livepreview_box .share-object .image img').attr('src', ImageId);
		// 	jQuery('.instagram_livepreview_box .share-object .image').show();
		// 	jQuery('.instagram_livepreview_box .share-object').show();
		//
		// 	jQuery('[name=instagram_photo_url]').val(ImageId);
		// }
		//
		// if (channel == 'pinterest' || channel == 'all') {
		// 	jQuery('.pinterest_livepreview_box .share-object .image img').attr('src', ImageId);
		// 	jQuery('.pinterest_livepreview_box .share-object .image').show();
		// 	jQuery('.pinterest_livepreview_box .share-object').show();
		//
		// 	jQuery('[name=pinterest_photo_url]').val(ImageId);
		// }
	}
}


function triggerLiveBeforePublish(social) {


	if (social == 'facebook') {
		if (jQuery('#da-ex-buttons-checkbox input:checked').length) {
			jQuery('#FacebookLivePreview').show();
		} else {
			jQuery('#FacebookLivePreview').hide();
		}
	} else if (social == 'twitter') {
		if (jQuery('#da-ex-buttons-checkbox1 input:checked').length) {
			jQuery('#TwitterLivePreview').show();
		} else {
			jQuery('#TwitterLivePreview').hide();
		}
	} else if (social == 'linkedin') {
		if (jQuery('#da-ex-buttons-checkbox2 input:checked').length) {
			jQuery('#LinkedinLivePreview').show();
		} else {
			jQuery('#LinkedinLivePreview').hide();
		}
	} else if (social == 'instagram') {
		if (jQuery('#da-ex-buttons-checkbox3 input:checked').length) {
			jQuery('#InstagramLivePreview').show();
		} else {
			jQuery('#InstagramLivePreview').hide();
		}
	} else if (social == 'pinterest') {
		if (jQuery('#da-ex-buttons-checkbox4 input:checked').length) {
			jQuery('#PinterestLivePreview').show();
		} else {
			jQuery('#PinterestLivePreview').hide();
		}
	}
}

(function($){
	var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+Base64._keyStr.charAt(s)+Base64._keyStr.charAt(o)+Base64._keyStr.charAt(u)+Base64._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=Base64._keyStr.indexOf(e.charAt(f++));o=Base64._keyStr.indexOf(e.charAt(f++));u=Base64._keyStr.indexOf(e.charAt(f++));a=Base64._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
	
	$(document).ready(function() { 
		$('.chosen-select').chosen({'width':'47%'});
	
		$('.livePreviewCanvas > a').click(function() {
			if (!$(this).attr('data-triggered')) {
				if( $('#content').is(':visible') ) {
					var content = $('#content').val();
				} else {


					var editor = tinyMCE.get("content");
						if(isset(editor.getContent()))
					var content = editor.getContent();
				}
			
				triggerLivePreview(content);
				$('.livePreviewCanvas > a').attr('data-triggered', true)
			}
			
			if ($(this).next().is(':hidden'))
				$(this).next().slideDown();
			else
				$(this).next().slideUp();
		});
		
		$('.vb_tooltip').qtip({
			content: {
				attr: 'alt'
			},
			position: {
				my: 'bottom left',
				at: 'top center'
			},
			style   : {
				tip: {
					corner: true
				},
				classes : 'qtip-bootstrap'
			},
			show    : {
				when: {
					event: 'mouseover'
				}
			},
			hide    : {
				fixed: true,
				when : {
					event: 'mouseout'
				}
			}

		});
		
		$('#Vbout-settings-tabs a').click(function(e) { 
			e.preventDefault();
			
			$('#Vbout-settings-tabs a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			
			
			$('.tabs-panel').removeClass('tabs-panel-active').addClass('tabs-panel-inactive');
			$('#'+$(this).attr('data-tab')).removeClass('tabs-panel-inactive').addClass('tabs-panel-active');
			
			var tab = $(this).attr('data-tab');
			$('#vb-current-navtab').val(tab);
		});
		
		$('.sliderButton').click(function() { 
			$('#vbout-connect').find('[type=submit]').show();
			
			if (!$('#'+$(this).attr('data-slider')).is(':visible')) {
				$('.vbout_slider_box').slideUp();
				
				$('#'+$(this).attr('data-slider')).slideToggle();
				
				$('[name=vbout_method]').val($(this).attr('data-method'));
			}
		});
		
		if ($('#vbout-connect').length) {
			$('#vbout-connect').find('[type=submit]').hide();
			
			$('#vbout-connect').submit(function() { 
				var returnVar = true;
				
				$('#vbout-connect').find('.error_placeholder').remove();
				
				if ($('#UserKeySlider').is(':visible') && $('#vbout_userkey').val() == '') {
					$('#vbout_userkey').after('<p style="color: red;" class="error_placeholder">This field is required.</p>');

					returnVar = false;
				} else if ($('#AppKeySlider').is(':visible') && ($('#vbout_appkey').val() == '' || $('#vbout_clientsecret').val() == '' || $('#vbout_authtoken').val() == '')) {
					$('#AppKeySlider').find('input[type=text]').each(function() { 
						if ($(this).val() == '')
							$(this).after('<p style="color: red;" class="error_placeholder">This field is required.</p>');
					});
					
					returnVar = false;
				} else if (!$('#UserKeySlider').is(':visible') && !$('#AppKeySlider').is(':visible')) {
					alert('Please choose a method to connect to Vbout.');
					returnVar = false;
				}
				
				return returnVar;
			});
			
			if ($('#message').length) {
				$('[data-method='+$('[name=vbout_method]').val()+']').trigger('click');
			}
		}
		
		if ($('#vbout_tracking_domain').length) {
			$('#vbout_tracking_domain').change(function() { 
				$('#vbout_tracking_code').val(Base64.decode($('#trackingcode-'+$(this).val()).html()).replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&').replace(/&quot;/g,'"'));
			});
		}
		
		if ($('[name=vb_post_title]').length) {
			$('[name=vb_post_title]').change(function() { 
				$('#vb_post_schedule_emailsubject').val($(this).val());
			});
		}
		
		
	});
})(jQuery)
