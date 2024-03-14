
jQuery(document).ready(function($) {
	
	function display_suggestions(keyword) {
		keyword_selected = keyword;
		$('#wpks-suggestions').html('<div id="ajax-loader"></div>');
		$.post(ajaxurl, {'action': 'wpks_keyword_suggestions', 'wpks_keyword': keyword}, function(response) {
			$('#wpks-suggestions').html(response);
		});
	}

	$('#wpks-suggest').live('click', function(event) {
		if ($('#wpks-keyword').val() != '') {
			display_suggestions($('#wpks-keyword').val());
		}
		else {
			$('#wpks-suggestions').html(objectL10n.please_enter_keyword);
		}
		event.preventDefault();
	});
	
	$('.wpks-set-title').live('click', function(event) {
		$('#post #titlediv #title').attr('value', $(this).attr('keyword'));
		$('#post #titlediv #title').blur();
		event.preventDefault();
	});

	$('.wpks-set-tag').live('click', function(event) {
		$('#new-tag-post_tag').attr('value', $('#new-tag-post_tag').attr('value') + $(this).attr('keyword') + ', ');
		$('#post #titlediv #title').blur();
		event.preventDefault();
	});

	jQuery("#wpks-keyword").gcomplete({
		style: "default",
		effect: false,
		pan: '#wpks-keyword'
	});

});