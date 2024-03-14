jQuery(document).ready(function($) {

	// Ajax Search Variations
	$('.search-vars').on('click', function(event) {
		event.preventDefault();

		var $resultContainer = $('.search-result');
		var loaderImg = $(this).next('.loader-img');

		$.ajax({
			type: "POST",
			data: {
				action: 'cleaning_old_vars',
			},
			url: sku_error_fixer_ajaxUrl.url,
			beforeSend: function() {
				loaderImg.fadeIn();
			},
			success: function(data) {
				loaderImg.fadeOut();
				$resultContainer.text('');
				if (data.length > 1) {
					$resultContainer.html(data);
					$resultContainer.slideDown();
				}
			}
		});
	});

	$(document).on('click', '.show-results', function(event) {
		event.preventDefault();
		var maxW = $('.search-td').width();
		if ($(this).next('.needless-child-list').size() > 0 ) {
			$(this).next('.needless-child-list').css('max-width', maxW).slideToggle();
			$(this).toggleClass('open');
			if ($(this).hasClass('open')) {
				$(this).html('Hide list<i></i>');
			} else {
				$(this).html('Show list<i></i>');
			}
		}
	});


	// Ajax Clean SKU Variations
	$('.clean-sku').on('click', function(event) {
		event.preventDefault();

		var $resultContainer = $('.clean-result');
		var loaderImg = $(this).next('.loader-img');
		var key = 'clean';

		$.ajax({
			type: "POST",
			data: {
				action: 'cleaning_old_vars',
				key: key,
			},
			url: sku_error_fixer_ajaxUrl.url,
			beforeSend: function() {
				loaderImg.fadeIn();
			},
			success: function(data) {
				loaderImg.fadeOut();
				$resultContainer.text('');
				if (data.length > 1) {
					$resultContainer.html(data);
					$resultContainer.slideDown();
				}
			}
		});
	});

	// Ajax Removal Variations
	$('.removal-vars').on('click', function(event) {
		event.preventDefault();
		var $resultContainer = $('.removal-result');
		var loaderImg = $(this).next('.loader-img');
		var key = 'removal';

		$.ajax({
			type: "POST",
			data: {
				action: 'cleaning_old_vars',
				key: key,
			},
			url: sku_error_fixer_ajaxUrl.url,
			beforeSend: function() {
				loaderImg.fadeIn();
			},
			success: function(data) {
				loaderImg.fadeOut();
				$resultContainer.text('');
				if (data.length > 1) {
					$resultContainer.html(data);
					$resultContainer.slideDown();
				}
			}
		});
	});

	function autoCleaner(node) {
		if ($('.auto-clean-result').size() > 0) {
			$('.auto-clean-result').remove();
		}
		node.prev('label').append('<span class="auto-clean-result"></span>');
		$resultContainer = $('.auto-clean-result');
		var changing_input = node;
		var sku = node.val();
		var postID = $('input#post_ID').val();

		$.ajax({
			type: "POST",
			data: {
				action: 'auto_change_cleaning',
				sku: sku,
				postID: postID,
			},
			url: sku_error_fixer_ajaxUrl.url,
			beforeSend: function() {
				$resultContainer.html('<i>loading...</i>');
			},
			success: function(data) {
				$resultContainer.text('');
				if (data.length > 1) {
					$resultContainer.html(data);
				}
			}
		});
	}

	// Ajax on change SKU
	$(document.body).on('change', 'input[name^="variable_sku"]', function(e) {
		var target = $(e.target);
		autoCleaner(target);
	});
	$(document.body).on('change', 'input[name="_sku"]', function(e) {
		var target = $(e.target);
		autoCleaner(target);
	});

}); // jQuery on ready end