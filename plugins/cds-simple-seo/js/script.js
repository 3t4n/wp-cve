(function($) {
	"use strict";

	updatePreview();
	
	if ($("#sseoDefaultMetaTitle").length) {
		$("#sseo_title_count").html('<strong>' + $("#sseoDefaultMetaTitle").val().length + '</strong>');
	}
	
	if ($("#sseoDefaultMetaDescription").length) {
		$("#sseo_desc_count").html('<strong>' + $("#sseoDefaultMetaDescription").val().length + '</strong>');
	}
	
	if ($("#sseoMetaTitle").length) {
		$("#sseo_title_count").html('<strong>' + $("#sseoMetaTitle").val().length + '</strong>');
	}
	
	if ($("#sseoMetaDescription").length) {
		$("#sseo_desc_count").html('<strong>' + $("#sseoMetaDescription").val().length + '</strong>');
	}
	
	$('#sseoMetaTitle').change(function() {
		var count = $(this).val().length;
		$("#sseo_title_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	$('#sseoMetaTitle').keypress(function() {
		var count = $(this).val().length;
		$("#sseo_title_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	$('#sseoMetaTitle').bind('paste', function() {
		var count = $(this).val().length;
		$("#sseo_title_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	
	$('#sseoDefaultMetaTitle').change(function() {
		var count = $(this).val().length;
		$("#sseo_title_count").html('<strong>' + count + '</strong>');
	});
	$('#sseoDefaultMetaTitle').keypress(function() {
		var count = $(this).val().length;
		$("#sseo_title_count").html('<strong>' + count + '</strong>');
	});
	$('#sseoDefaultMetaTitle').bind('paste', function() {
		var count = $(this).val().length;
		$("#sseo_title_count").html('<strong>' + count + '</strong>');
	});

	$('#sseoMetaDescription').change(function() {
		var count = $(this).val().length;
		$("#sseo_desc_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	$('#sseoMetaDescription').keypress(function() {
		var count = $(this).val().length;
		$("#sseo_desc_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	$('#sseoMetaDescription').bind('paste', function() {
		var count = $(this).val().length;
		$("#sseo_desc_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});

	$('#sseoMetaDescription').change(function() {
		var count = $(this).val().length;
		$("#sseo_desc_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	$('#sseoMetaDescription').keypress(function() {
		var count = $(this).val().length;
		$("#sseo_desc_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	$('#sseoMetaDescription').bind('paste', function() {
		var count = $(this).val().length;
		$("#sseo_desc_count").html('<strong>' + count + '</strong>');
		updatePreview();
	});
	
	$("#title").change(function() {
		updatePreview();
	});
	$("#title").keypress(function() {
		updatePreview();
	});
	$("#title").bind(function() {
		updatePreview();
	});
	
	function updatePreview() {
		$("#sseo_snippet_title").html('');

		var sseoMetaDescription = $.trim($("#sseoMetaDescription").val());
		if (sseoMetaDescription.length > 0) {
			$("#sseo_snippet_description").html(sseoMetaDescription);
		}

		var sseoMetaTitle = $.trim($("#sseoMetaTitle").val());
		if (sseoMetaTitle.length > 0) {
			$("#sseo_snippet_title").html(sseoMetaTitle);
		}
		
		if (sseoMetaTitle.length <= 0) {
			var title = $.trim($("#title").val());
			if (title.length > 0) {
				$("#sseo_snippet_title").html(title);
			}
		}
	}

	if ($("#sseo-fb-image").val()) {
		$("#sseo-fb-media-remove").show();
		$("#sseo_fb_media_manager").hide();
	} else {
		$("#sseo-fb-media-remove").hide();
		$("#sseo_fb_media_manager").show();
	}

	if ($("#sseo-tw-image").val()) {
		$("#sseo-tw-media-remove").show();
		$("#sseo_tw_media_manager").hide();
	} else {
		$("#sseo-tw-media-remove").hide();
		$("#sseo_tw_media_manager").show();
	}

	$("#sseo_fb_media_manager").click(function() {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		wp.media.editor.send.attachment = function(props, attachment) {
			$(".fb-img-container").append('<img id="sseo-fb-preview-image" src="'+attachment.url+'" class="media-input" />');
			$("#sseo-fb-image").val(attachment.id);
			$("#sseo-fb-media-remove").show();
			$("#sseo_fb_media_manager").hide();
			wp.media.editor.send.attachment = send_attachment_bkp;
		}
		wp.media.editor.open(button);
		return false;
	});

	$("#sseo_tw_media_manager").click(function() {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		wp.media.editor.send.attachment = function(props, attachment) {
			$(".tw-img-container").append('<img id="sseo-tw-preview-image" src="'+attachment.url+'" class="media-input" />');
			$("#sseo-tw-image").val(attachment.id);
			$("#sseo-tw-media-remove").show();
			$("#sseo_tw_media_manager").hide();
			wp.media.editor.send.attachment = send_attachment_bkp;
		}
		wp.media.editor.open(button);
		return false;
	});
	
	$("#sseo-fb-media-remove").click(function() {
		var answer = confirm('Are you sure?');
		if (answer == true) {
			$(".fb-img-container").empty();
			$("#sseo-fb-image").val(' ');
			$("#sseo-fb-media-remove").hide();
			$("#sseo_fb_media_manager").show();
		}
		return false;
	});
	
	$("#sseo-tw-media-remove").click(function() {
		var answer = confirm('Are you sure?');
		if (answer == true) {
			$(".tw-img-container").empty();
			$("#sseo-tw-image").val(' ');
			$("#sseo-tw-media-remove").hide();
			$("#sseo_tw_media_manager").show();
		}
		return false;
	});
	
})(jQuery);