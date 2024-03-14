jQuery(document).ready(function ($) {

	var search_result_content = '<div class="results-new-design"><div class="results-content"></div></div>';

	$(".elementor-button").click(function () {
		setTimeout(function () {
			var $popupBox = $('.dialog-message');

			if ($popupBox.length > 0) {
				var $searchInput = $popupBox.find('[role="search"]');

				if ($searchInput.length > 0) {
					$searchInput.append(search_result_content);
				}
			}
		}, 1000);
	});

	// $('body').append(search_result_content);
	$('[role="search"]').append(search_result_content);

	$(document).on('keyup', 'input[name="s"]', function (e) {
		e.preventDefault();
		e.stopPropagation();

		let search_value = $(this).val();

		if (search_value.length >= 3) {

			if ($(window).width() > 1024) {
				let element = $(this);
				let position = $(element).offset();
				var input_width = element.width();
				console.log('position-top' + position.top);
				console.log('position-left' + position.left);
				console.log('input_width' + input_width);

				if (position.top > 40 && position.left < 800) {
					$('.results-new-design').removeClass("top");
					$('.results-new-design').removeClass("middel");
					$('.results-new-design').addClass("bottom");
					console.log("bottom2");
				}
				if (position.top > 40 && position.left > 500) {
					$('.results-new-design').removeClass("bottom");
					$('.results-new-design').removeClass("middel");
					$('.results-new-design').addClass("top");
					console.log("top");
				}
				if (position.top > 1100 && position.left < 500) {
					$('.results-new-design').removeClass("top");
					$('.results-new-design').removeClass("middel");
					$('.results-new-design').addClass("bottom");
					console.log("bottom");
				}
				if (input_width > 900) {
					$('.results-new-design').removeClass("top");
					$('.results-new-design').removeClass("bottom");
					$('.results-new-design').addClass("middel");
					console.log("middel");
				}
			}

			// Tab position
			if ($(window).width() < 1024) {
				let element = $(this);
				let position = $(element).offset();

				if (position.top > 60 && position.left > 400) {
					$('.results-new-design').removeClass("bottom");
					$('.results-new-design').removeClass("middel");
					$('.results-new-design').addClass("top");
				}
				else if (position.top > 1600 && position.left > 25) {
					$('.results-new-design').removeClass("top");
					$('.results-new-design').removeClass("middel");
					$('.results-new-design').addClass("bottom");
				}
				else if (position.top > 200 && position.left > 5) {
					$('.results-new-design').removeClass("top");
					$('.results-new-design').removeClass("bottom");
					$('.results-new-design').addClass("middel");
				}
				else if (position.top > 60 && position.left > 100) {
					$('.results-new-design').removeClass("top");
					$('.results-new-design').removeClass("middel");
					$('.results-new-design').addClass("bottom");
				}
			}

			let element = $('.dialog-message');
			if (element.length > 0) {
				// let position = element.offset();
				let width = element.outerWidth();
				let newWidth = width - 35;
				console.log('width' + width);
				console.log('newWidth' + newWidth);
				if (newWidth <= 500) {
					setTimeout(function () {
						$('.rtl .search-suggestion-box').css('display', 'unset');
						$('.search-suggestion-box').css('display', 'unset');
					}, 2000);
				}

				$('.results-new-design').css('width', newWidth + 'px');
			}

			var this_s = this;

			let $resultsContent = $(this_s).closest('[role="search"]').children('.results-new-design').children('.results-content');
			// let $resultsContent = $('.results-new-design').children('.results-content');

			$resultsContent.html('<div class="loader">Loading....</div>');
			var $loader = $('.loader');
			$loader.show();

			let form_data = new FormData();
			form_data.append('action', 'save_user_search');
			form_data.append('s', search_value);

			$.ajax({
				type: 'post',
				url: webapp.ajax_url,
				data: form_data,
				processData: false,
				contentType: false,
				dataType: 'json',
				beforeSend: function () {
					$(this_s).closest('[role="search"]').children('.results-new-design').show();
					// $('.results-new-design').show();
				},
				success: function (res) {
					$loader.hide();

					// hide and show result content
					// $('.results-new-design').show();
					// $('.results-new-design').children('.results-content').show();
					$(this_s).closest('[role="search"]').children('.results-new-design').show();
					$(this_s).closest('[role="search"]').children('.results-new-design').children('.results-content').show();

					let search_result_html = res.result_html;
					// $('.results-new-design').children('.results-content').html(search_result_html);
					$(this_s).closest('[role="search"]').find('.results-new-design .results-content').html(search_result_html);

				}
			});
		}
		return false;
	});

	$('body').click(function () {
		$('.results-new-design').hide();
	});

});