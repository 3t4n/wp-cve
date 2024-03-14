(function ($) {
	let modalOpen = false;
	let iniFetch = true;
	let loading = false;
	let page = 1;

	wp.customize.bind('ready', function () {
		unsplash();
	});

	const unsplash = function () {
		const unsplashModalOpenButton = document.getElementById('clp-customizer-unsplash');
		const unsplashModalclose = document.querySelector('.clp-unsplash-modal-close');
		const unsplashModal = document.getElementById('clp-unsplash-modal');
		const unsplashSelectButton = document.getElementById('clp-unsplash-select-button');
		const unsplashRemoveButton = document.getElementById('clp-remove-unsplash-photo');
		const unsplashSearchInput = document.getElementById('unsplash-search-input');
		const nonce = unsplashModalOpenButton.dataset.nonce;
		let typingTimeout = null;
		let oldSearchValue = '';

		// Modal Open and load initial data
		unsplashModalOpenButton.addEventListener('click', function (e) {
			e.preventDefault();

			unsplashModal.style.display = 'block';

			if (!modalOpen && iniFetch) {
				var params = {
					page: 1,
					per_page: 20,
					order_by: 'popular',
					type: 'all',
				};

				fetchUnsplash('clp_get_unsplash', params, nonce);

				// attach scroll listener for load more
				$('#clp-unsplash-modal .media-frame .attachments-browser').on('scroll', loadMore);
				$(window).on('resize', loadMore);
			}

			modalOpen = true;
		});

		// Listen for keystroke events
		unsplashSearchInput.addEventListener('keyup', function (e) {
			var params = {
				page: 1,
				per_page: 20,
				type: 'search',
			};

			clearTimeout(typingTimeout);

			// Make a new timeout set to go off in 1000ms (1 second)
			typingTimeout = setTimeout(function () {
				if (unsplashSearchInput.value && oldSearchValue !== unsplashSearchInput.value) {
					params.query = unsplashSearchInput.value;
					page = 1;

					$('#clp-unsplash-modal .grid-item').remove();
					$('#clp-unsplash-images').css('height', 'auto');
					oldSearchValue = unsplashSearchInput.value;
					fetchUnsplash('clp_get_unsplash', params, nonce);
				}
			}, 1000);
		});

		// Select Image
		unsplashModal.addEventListener('click', selectImage);

		function selectImage(event) {
			var element = event.target;

			if (element.tagName == 'IMG' && element.parentNode.classList.contains('grid-item')) {
				$('.grid-item').removeClass('selected');
				element.parentNode.classList.add('selected');
				$('#clp-unsplash-select-button').attr('disabled', false);
				unsplashSelectButton.dataset.image = element.parentNode.dataset.url;
			}
		}

		// Select Button
		unsplashSelectButton.addEventListener('click', function (e) {
			e.preventDefault();
			var newImg = JSON.parse(unsplashSelectButton.dataset.image);

			$('#clp-background-unsplash-url').val(JSON.stringify(newImg)).trigger('change');
			$('.clp-unsplash-thumbnail').attr('src', newImg.urls.small);
			$('.clp-unsplash-link-html').attr('href', newImg.link);
			$('.clp-unsplash-link-portfolio_url').attr('href', newImg.userlink);
			$('.clp-unsplash-link-portfolio_url').html(newImg.username);
			$('.unsplash-info').css('display', 'block');
			unsplashModal.style.display = 'none';
			modalOpen = false;

			// trigger unsplash download
			var downloadUrl = newImg.download + '?client_id=vKi1UhM3J-Oetvi-mBmp3Spp0_YVxCmfANyTHKJmrKA';

			fetch(downloadUrl, {
				method: 'GET',
			}).then((res) => {
				return res.json();
			});
		});

		// Modal Close
		unsplashModalclose.addEventListener('click', function (e) {
			e.preventDefault();
			unsplashModal.style.display = 'none';
			modalOpen = false;
		});

		// Remove Photo
		unsplashRemoveButton.addEventListener('click', function (e) {
			e.preventDefault();
			$('#clp-background-unsplash-url').val('').trigger('change');
			$('.unsplash-info').css('display', 'none');
		});

		function loadMore() {
			var loadMore = $('#load-more')[0];
			var rect = loadMore.getBoundingClientRect();
			var offset =
				rect.top < (window.innerHeight || document.body.clientHeight) &&
				rect.left < (window.innerWidth || document.body.clientWidth);
			var inputQuery = $('#unsplash-search-input').val();
			var type = inputQuery ? 'search' : 'all';

			if (offset && !loading) {
				page++;
				var params = {
					page: page,
					per_page: 20,
					order_by: 'popular',
					type: type,
					query: inputQuery,
				};

				console.log(params);

				fetchUnsplash('clp_get_unsplash', params, nonce);
			}
		}
	};

	// fetch data from Unsplash API
	const fetchUnsplash = function (action, params, nonce) {
		var data = {
			action: action,
			_wpnonce: nonce,
			params: params,
		};

		loading = true;

		jQuery.post(ajaxurl, data, function (response) {
			var result = JSON.parse(response);

			if (result.photos && result.photos.length > 0) {
				appendImages(result);
			} else if (result.photos && result.photos.length === 0) {
				$('.no-media ').css('display', 'block');
				$('.no-media h2').html('No more images found.');
				return false;
			} else {
				$('.no-media ').css('display', 'block');
				$('.no-media h2').html('Error while loading images from Unsplash, please try again later');
				return false;
			}
		});
	};

	// Append Images to Modal
	const appendImages = function (images) {
		const container = document.getElementById('clp-unsplash-images');
		var $grid = $('#clp-unsplash-images');

		if (!iniFetch && !$('.grid-item').length) {
			$grid.masonry('destroy');
		}

		if (iniFetch || !$('.grid-item').length) {
			$grid.masonry({
				itemSelector: '.grid-item',
				gutter: 20,
				percentPosition: true,
				transitionDuration: 0,
			});

			iniFetch = false;
		}

		images.photos.forEach(function (image) {
			const imageWrapper = document.createElement('div');
			imageWrapper.classList.add('grid-item');

			var newImg = {
				urls: {
					original: image.urls.raw,
					small: image.urls.small,
				},
				link: image.links.html,
				username: image.user.name,
				userlink: image.user.links.html,
				download: image.links.download_location,
			};
			imageWrapper.setAttribute('data-url', JSON.stringify(newImg));
			const unsplashImage = new Image();
			unsplashImage.src = newImg.urls.small;
			imageWrapper.appendChild(unsplashImage);
			container.appendChild(imageWrapper);
			$grid.masonry('addItems', imageWrapper);
		});

		$grid.imagesLoaded().progress(function (instance, image) {
			image.img.parentNode.classList.add('loaded');
			$grid.masonry('layout');
		});

		$grid.imagesLoaded().done(function () {
			loading = false;
		});
	};
})(jQuery);
