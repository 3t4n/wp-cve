<?php
/**
 * Declare view-js.php file
 *
 * @package view-js
 */

?>
<script>
	let interval_timeout_init = 10;
	let init_handle = setInterval(function () {
		// Check lasso_helper load already.
		if ( typeof lasso_lite_helper != "undefined") {
			// Clearing the interval.
			clearInterval(init_handle);
			// Call the initializing function init_lasso_view
			init_lasso_view();
		}
	}, interval_timeout_init);

	/**
	 * Init init_lasso_view
	 */
	function init_lasso_view () {
		var monetize_id;
		jQuery(document).ready(function() {
			// At the begin, if search parameter available, set this value to search input
			let search_parameter = lasso_lite_helper.get_url_parameter( 'search' );

			if ( search_parameter ) {
				jQuery('#search-links input').val( search_parameter );
			}

			// Form submit disabled
			jQuery('form').submit(function() {
				return false;
			});

			// *************************
			//  Pagination
			// *************************
			var container = jQuery('#report-content');
			var pagination = jQuery('.pagination');
			var search_term = "<?php echo esc_js($_GET['search'] ?? ''); // phpcs:ignore ?>";
			var tab_filter = "<?php echo esc_js( $_GET['filter'] ?? '' ) ; // phpcs:ignore ?>";
			var url_page_number = lasso_lite_helper.get_page_from_current_url();
			var limit = 10;

			function full_list_paginate(set_page) {
				lasso_lite_helper.set_pagination_cache(lasso_lite_helper.get_page_name(), set_page);

				var data = {
					items: jQuery('#total-posts').val(),
					displayedPages: 3,
					itemsOnPage: limit,
					cssStyle: 'light-theme',
					prevText: '<i class="far fa-angle-double-left"></i> Previous',
					nextText: 'Next <i class="far fa-angle-double-right"></i>',
					onPageClick: function(pageNumber, event) {
						var sortable = jQuery('.sortable-col.active');
						var orderBy = '';
						var orderType = '';
						if(sortable) {
							orderBy = sortable.attr('data-order-by');
							orderType = sortable.attr('data-order-type');
						}

						lasso_lite_helper.set_pagination_cache(lasso_lite_helper.get_page_name(), pageNumber);
						lasso_lite_helper.remove_page_number_out_of_url();

						if(['asc', 'desc'].includes(orderType) && orderBy !== '') {
							get_data_via_ajax(pageNumber, limit, orderBy, orderType);
						} else {
							get_data_via_ajax(pageNumber, limit);
						}
					}
				};

				if(set_page > 0) {
					data.currentPage = set_page;
				}
				pagination.pagination(data);

				return pagination;
			}
			full_list_paginate();

			// *************************
			//  Search
			// *************************
			// Action when click search button
			jQuery('#search-icon').unbind().click(function(){
				get_data_via_ajax(1, limit);
			});

			// TYPE TO ADD TAGS TO SEARCH BAR
			jQuery('#search-links input').off('focusout');
			jQuery('#search-links input')
				.on('focusout', function() { 
					if(this) {
						var txt = this.value.replace(/[^a-zA-Z0-9\+\-\.\#]/g,' ');
						search_term = txt.trim();
					}   
				})
				.on('keyup', function( e ) {
					// WHEN ENTER IS PRESSED, SEARCH
					if(e.which == 13) {
						jQuery(this).focusout();
						lasso_lite_helper.clear_notifications();
						lasso_lite_helper.remove_page_number_out_of_url();
						get_data_via_ajax(1, limit);
					}
				});

			// *************************
			//  Main Report Generation 
			// *************************

			// Get full url data via ajax
			function get_data_via_ajax(page, limit, order_by = undefined, order_type = undefined, container_name = '') {
				var link_type = '<?php echo $page; // phpcs:ignore ?>';
				var t0 = performance.now();
				var no_field_ids = [];

				// Push search to url parameter
				lasso_lite_helper.update_url_parameter('search', search_term);

				// Apply filter import plugin
				tab_filter = lasso_lite_helper.get_url_parameter( 'filter' );
				container = jQuery('#report-content');

				jQuery.ajax({
					url: lassoLiteOptionsData.ajax_url,
					type: 'post',
					data: {
						action: 'lasso_lite_import',
						nonce: lassoLiteOptionsData.optionsNonce,
						post_id: '<?php echo esc_js( $_GET['post_id'] ?? '' ); // phpcs:ignore ?>',
						link_type: link_type,
						pageNumber: page,
						pageSize: limit,
						order_by: order_by,
						order_type: order_type,
						search: search_term,
						keyword: '<?php echo esc_js( $_GET['keyword'] ?? '' ); // phpcs:ignore ?>',
						filter: tab_filter,
						no_field_ids: no_field_ids
					},
					beforeSend: function() {
						if(tab_filter != 'url-details') {
							// Loading image
							container.html(lasso_lite_helper.get_loading_image());
						}

						if( -1 === jQuery.inArray(tab_filter, [ "opportunities", "out-of-stock", "broken-links" ] ) ) {
							tab_filter = '';
						}
						pagination.pagination('disable');
					}
				})
				.done(function(response) {
					var t1 = performance.now();
					// console.log("Query response took " + (t1 - t0) + " milliseconds.");
					if(response.success) {
						var post = response.data.post;
						var responseData = response.data;
						var order_icon = (post.order_type == 'asc') ? ' <i class="fas fa-caret-up green"></i> ': ' <i class="fas fa-caret-down green"></i> ';
						jQuery('table > thead').find('i').remove();
						jQuery('table > thead').find('th[order-by="' + post.order_by + '"]')
							.attr('order-type', post.order_type).append(order_icon);

						var html = get_html(responseData.data, post);
						container.html(html);

						jQuery('.subsubsub').find('li.active').find('span').text(responseData.total.total);
						jQuery('#total-posts').val(responseData.total.total);
						if(jQuery("#js-report-result").length != 0) {
							jQuery('#js-report-result').html(responseData.total.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " Found");
						}

						// import view popup
						init_import_events();
						render_filter_plugin_select(responseData.total.plugins);
					} else {
						container.html('Failed to load data.');
					}
				})
				.fail(function(xhr) {
					container.html('Failed to load data.');
				})
				.always(function(res) {
					var page = res && 'object' === typeof res && 'data' in res ? res.data.page : 1;

					// Don't change pagination if get custom fields of Lasso url
					if(container_name != 'custom-fields') {
						full_list_paginate(page);
					}

					pagination.pagination('enable');
				});
			}

			// Initial get data
			get_data_via_ajax(url_page_number, limit, undefined, undefined, '');

			// Get html for full url data
			function get_html(data, post) {
				var html = '';
				var default_image = '';
				if(data.length > 0) {
					for (let index = 0; index < data.length; index++) {
						const element = data[index];
						var collapse_id = index;
						var icon = (element.count > 0) ? '<i class="fas fa-caret-down green"></i>' : '';
						var image_url = (!element.thumbnail) ? default_image : element.thumbnail;
						var image = `<img alt="${ element.post_title }" src="${ image_url }" loading="lazy" class="rounded border" width="50" height="50" />`;
						var display_type = 'text-link';
						var type = `<a class="lasso-list-btn">${ element.type }</a>`;
						var status = `<a class="lasso-list-btn">${ element.status }</a>`;
						var create_link = `<a href="${ element.link_slug }" target="_blank" class="pl-2"><i class="far fa-external-link-alt green"></i></a><a class="trash-lasso-modal pl-2" href="#"><i class="far fa-trash-alt red"></i></a>`;
						var link_count = parseInt(element.count);
						var toggle_checked = element.link_report_color == 'green' ? 'checked' : '';

						html += `<?php include SIMPLE_URLS_DIR . '/admin/views/rows/import-url.php'; ?>`;
					}
				} else {
					jQuery('#js-report-result').html("0 Found");

					html = lasso_lite_helper.empty_html();
				}

				return html;
			}

			// *************************
			//  Import Page
			// *************************
			jQuery(document)
				.on('change', '#filter-plugin', filter_import_plugin_change);

			function filter_import_plugin_change() {
				var selected_value = jQuery(this).val();

				lasso_lite_helper.update_url_parameter('filter', selected_value); // Push filter to url parameter
				lasso_lite_helper.clear_notifications();
				lasso_lite_helper.remove_page_number_out_of_url();
				get_data_via_ajax(1, limit);
			}

			function render_filter_plugin_select(plugins) {
				var filter_plugin_select = jQuery('#filter-plugin');
				var options              = '<option value="">All Plugins</option>';

				plugins.forEach(function(plugin_source) {
					var selected = plugin_source == lasso_lite_helper.get_url_parameter( 'filter' ) ? 'selected' : '';
					options += '<option '+ selected +' value="' + plugin_source + '">' + plugin_source + '</option>';
				});

				filter_plugin_select.html(options);
			}
		});
	}
</script>
