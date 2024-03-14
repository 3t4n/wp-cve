jQuery(document).ready(function($){
	"use strict";
	$('select').on('change', function() {
		var id = $(this).attr('id')+"_preview";
		var img_url = $('option:selected', this).data('img');
		$("#"+id+' .preview_image').attr("src",img_url);
	});

	$('body').on('click', '.blogsqode-upload', function(e) {
		
		e.preventDefault();

		var activeFileUploadContext = $(this).parent();

		var customFileFrame = wp.media.frames.customHeader = wp.media({
			title: $(this).data('choose'),
			button: { text: $(this).data('update') }
		});

		customFileFrame.on('select', function() {
			var attachment = customFileFrame.state().get("selection").first();
			$('input', activeFileUploadContext).val(attachment.attributes.url).trigger('change');
			$('.blogsqode-upload', activeFileUploadContext).hide();
			$('.blogsqode-upload-remove', activeFileUploadContext).show();
		});

		customFileFrame.open();
	});

	$('body').on('click', '.blogsqode-upload-remove', function(e) {

		e.preventDefault();

		var activeFileUploadContext = $(this).parent();
		$('input', activeFileUploadContext).val('');
		$(this).prev().fadeIn('slow');
		$(this).fadeOut('slow');
	});

	$(window).on('load', function(){
		jQuery('.blogsqode-settings-content .notice').remove();
	});
});

