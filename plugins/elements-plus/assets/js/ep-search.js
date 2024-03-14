(function($) {
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ep-search.default', function($scope){

			var $body = $('body');

			/* -----------------------------------------
			Ajax Search
			----------------------------------------- */
			var $elementSearchForm = $($scope).find('.element-search-form');
			var $categoriesSelect = $elementSearchForm.find('.element-search-category-select');
			var $searchInput = $elementSearchForm.find('.element-search-input');
			var $searchResults = $elementSearchForm.find('.element-search-results');
			var $searchResultsTemplate = $elementSearchForm.find('.element-search-results-item');
			var $spinner = $elementSearchForm.find('.element-search-spinner');

			var postType = $elementSearchForm.find('input[name="post_type"]').val();
			var taxonomy = $elementSearchForm.find('input[name="taxonomy"]').val();
			var postNo = $elementSearchForm.find('input[name="num_posts"]').val();

			var searchNoItemsText = $elementSearchForm.data('no-results');
			var searchUrl = $elementSearchForm.attr('action');
			var allResultsText = $elementSearchForm.data('all-results');
			var numWords = $elementSearchForm.find('input[name="num_words"]').val();

			function dismissSearchResults() {
				$searchResults.hide();
			}

			function queryItems(category, string) {
				return $.ajax({
					url: ep_search_vars.ajaxurl,
					method: 'get',
					data: {
						action: 'elements_plus_search',
						term: category,
						s: string,
						post_type: postType,
						post_taxonomy: taxonomy,
						num_posts: postNo,
						num_words: numWords,
					},
				});
			}

			function queryItemsAndPopulateResults(category, string) {
				if (string.trim().length < 3) {
					dismissSearchResults();
					return;
				}

				$spinner.addClass('visible');

				return queryItems(category, string)
					.done(function (response) {
						$spinner.removeClass('visible');

						if (response.error) {
							var $errorMessage = $searchResultsTemplate.clone();
							var errorString = response.errors.join(', ');

							$errorMessage
								.addClass('error')
								.find('.element-search-results-item-title')
								.text('Error: ' + errorString);
							$searchResults.html($errorMessage).show();

							return;
						}

						var items = response.data;

						if (items.length === 0) {
							var $notFoundMessage = $searchResultsTemplate.clone();
							$notFoundMessage.find('.element-search-results-item-thumb').remove();
							$notFoundMessage
								.find('.element-search-results-item-title')
								.text(searchNoItemsText);
							$searchResults.html($notFoundMessage).show();

							return;
						}

						var $items = items.map(function (item) {
							var $template = $searchResultsTemplate.clone();
							$template.find('a').attr('href', item.url);
							if ( ! item.image ) {
								$template.find('.element-search-results-item-thumb').remove();
							} else {
								$template.find('.element-search-results-item-thumb img').attr('src', item.image);
							}
							$template.find('.element-search-results-item-title')
								.text(item.title);
							$template.find('.element-search-results-item-excerpt')
								.text(item.excerpt);

							return $template;
						});

						$searchResults.html($items);

						if ( parseInt(postNo, 10) < parseInt(items[0].found, 10) ) {
							$searchResults.append( '<li class="element-search-results-item"><a href="' + searchUrl +'?term=' + category + '&s=' + string + '&post_type=' + postType + '&taxonomy=' + taxonomy + '&num_posts=' + postNo + '" class="element-search-all-results">' + allResultsText + '</a></li>' );
						}

						$searchResults.show();
					});
			}

			var throttledQuery = throttle(queryItemsAndPopulateResults, 500);

			if ($elementSearchForm.hasClass('form-ajax-enabled')) {
				$searchInput.on('input', function (event) {
					// Do nothing on arrow up / down as we're using them for navigation
					if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
						return;
					}

					var $this = $(this);
					var string = $this.val();

					if (string.trim().length < 3) {
						dismissSearchResults();
						return;
					}

					throttledQuery($categoriesSelect.val(), $this.val());
				});

				// Bind up / down arrow navigation on search results
				$searchInput.on('keydown', function (event) {
					if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') {
						return;
					}

					var $items = $searchResults.children();
					var $highlighted = $searchResults.find('.highlighted');
					var currentIndex = $highlighted.index();

					if ($items.length === 0 || !$items) {
						return;
					}

					if (event.key === 'ArrowDown') {
						var $next = $items.eq(currentIndex + 1);

						if ($next.length) {
							$items.removeClass('highlighted');
							$next.addClass('highlighted');
						}
					}

					if (event.key === 'ArrowUp') {
						var $prev = $items.eq(currentIndex - 1);

						if ($prev.length) {
							$items.removeClass('highlighted');
							$prev.addClass('highlighted');
						}
					}
				});

				// Bind form submit to go the highlighted item on submit
				// instead of normal search
				$elementSearchForm.on('submit', function (event) {
					var $highlighted = $searchResults.find('.highlighted');

					if ($highlighted.length > 0) {
						event.preventDefault();
						window.location = $highlighted.find('a').attr('href');
					}
				});
			}

			/**
			 * Returns a function, that, when invoked, will only be triggered at most once
			 * during a given window of time. Normally, the throttled function will run
			 * as much as it can, without ever going more than once per `wait` duration;
			 * but if you'd like to disable the execution on the leading edge, pass
			 * `{leading: false}`. To disable execution on the trailing edge, ditto.
			 *
			 * @param {Function} func - The function to be throttled
			 * @param {Number} wait - Wait time in millis
			 * @param {Object} [options]
			 * @returns {function(): *} - The throttled function
			 */
			function throttle( func, wait, options ) {
				var context, args, result;
				var timeout  = null;
				var previous = 0;

				if ( ! options ) {
					options = {};
				}

				var later = function () {
					previous = options.leading === false ? 0 : Date.now();
					timeout  = null;
					result   = func.apply( context, args );
					if ( ! timeout ) {
						context = args = null;
					}
				};

				return function () {
					var now = Date.now();

					if ( ! previous && options.leading === false ) {
						previous = now;
					}

					var remaining = wait - (now - previous);
					context       = this;
					args          = arguments;

					if ( remaining <= 0 || remaining > wait ) {
						if ( timeout ) {
							clearTimeout( timeout );
							timeout = null;
						}
						previous = now;
						result   = func.apply( context, args );
						if ( ! timeout ) {
							context = args = null;
						}
					} else if ( ! timeout && options.trailing !== false ) {
						timeout = setTimeout( later, remaining );
					}
					return result;
				};
			}

			$body
				.on('click', function (event) {
					dismissSearchResults();
				})
				.find('.element-search-input ',
					'.element-search-select')
				.on('click', function (event) {
					event.stopPropagation();
				});
			});

	});
})( jQuery );
