/*
*	jQuery Scripts for WP Custom Login Page Logo Plugin
*/

jQuery(document).ready(function($){
	
	
	var uploadedImageWidth = 0;
	var uploadedImageHeight = 0;

	// open thickbox w/ media upload
	$('.wpclpl-logo-upload-btn').click(function() {
		tb_show('Select an image file for custom admin logo. (Click "Insert into Post" to select it.)', 'media-upload.php?referer=wpclpl-settings&type=image&TB_iframe=true&post_id=0', false);
		return false;
	});
		
	
	// send data to editor...
	window.send_to_editor = function(html) {
		
		var findUploadedLogoSrcRegex = /<img[^>]+src="(http:\/\/[^">]+)"/g;
		var uploadedLogoSrc = findUploadedLogoSrcRegex.exec(html)[1];
		var uploadedImageWidth = $(html).find('img').width();
		var uploadedImageHeight = $(html).find('img').height();
		
		$('.wpclpl-logo-url').val(uploadedLogoSrc);
		tb_remove();
		$('.wpclpl-currentlogo, .wpclpl-default-logo').fadeOut(300);		
		$('<img class="wpclpl-logo-preview" style="display:none; "src="'+uploadedLogoSrc+'" />')
			.insertAfter('.wpclpl-currentlogo')
			.delay(500)
			.fadeIn(300);
			
		$('#wpclpl-logo-preview').attr('src', $('.wpclpl-logo-url').val());
		$('#wpclpl-logo-preview-wrap a.thickbox').attr('href', $('.wpclpl-logo-url').val());
		
		$('#wpclpl-logo-width').attr('data-size-width',uploadedImageWidth).text(uploadedImageWidth);
		$('#wpclpl-logo-height').attr('data-size-height',uploadedImageHeight).text(uploadedImageHeight);
		$('.wpclpl-logo-dimensions-wrap').fadeIn(300);
		
	}
	
	
	// modal window
	function wpclplShowModal(ID){
		
		$('<div class="wpclpl-modal-box-wrap"></div>').insertBefore('#wpwrap');

		$('.'+ID).fadeIn(300,function(){
		
			// yes, reset...
			$('.wpclpl-reset-confirmed').click(function(){
				$('.wpclpl-logo-url, .wpclpl-custom-css, .wpclpl-additional-text').val('');
				$('.wpclpl-logo-preview').attr('src', '').css();
				$('#wpclpl-preview-css').html('');
				$('.wpclpl-modal-box-wrap').fadeOut(300);
			});
			
			// no, cancel
			$('.wpclpl-reset-cancel').click(function(){	
				$('.wpclpl-modal-box-wrap').fadeOut(300);
			});		
			
		});
		
	}
	
	// click on reset buttons: reset image / reset all settings
	$('.wpclpl-reset-btn').click(function(e){
		e.preventDefault();
		wpclplShowModal( $(this).attr('id') );
		
	});
	
	$('.wpclpl-logo-remove-img-btn').click(function(){
		
		$('.wpclpl-logo-url').val('');
		$('.wpclpl-logo-size').text('');
		$('.wpclpl-logo-preview-wrap').html('<div class="wpclpl-currentlogo" style="background-image: url(\'/wp-admin/images/wordpress-logo.svg?ver=20131107\')"></div>');
				
	});
	
	function showImageDimensions(){
		
		var imgElem = ($('#wpclpl-logo-preview').length > 0) ? '.wpclpl-logo-preview' : '.wpclpl-currentlogo';	
		var imgWidth = $(imgElem).width();		
		var imgHeight = $(imgElem).height();
		
		$('#wpclpl-logo-width').attr('data-size-width',imgWidth).text(imgWidth);
		$('#wpclpl-logo-height').attr('data-size-height',imgHeight).text(imgHeight);
		$('.wpclpl-logo-dimensions-wrap').fadeIn(300);
	}
	
	showImageDimensions();
	
});