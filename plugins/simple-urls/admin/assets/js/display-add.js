jQuery(document).ready(function () {
	jQuery(function() {
		let limit       = 5;
		let currentPage = 1;
		let tab         = 'single';

		jQuery(document)
			.on('click', '.lasso-display-type', add_display)
			.on('click', '.lasso-display-add-btn.add-btn', add_short_code_single_main)
			.on('click', '.btn-create-link', show_create_link_modal)
			.on('hidden.bs.modal', '#lasso-display-add', reset_pop_up_display_modal);

		/**
		 * Show modal Choose a Display Type
		 */
		function add_display() {
			tab = jQuery(this).data('tab');
			show_tab( jQuery(this).data('tab-container') );
		}

		/**
		 * Show selected tab.
		 *
		 * @param tab_container
		 */
		function show_tab(tab_container) {
			let tab_container_el = jQuery('#' + tab_container);
			jQuery('#lasso-display-type').addClass('d-none');
			tab_container_el.removeClass('d-none');
			tab_container_el.find('.search-keys input').addClass('d-none');
			tab_container_el.find('.search-keys input#search-key-' + tab).removeClass('d-none');

			if(tab === 'single') {
				single_list();
			}
		}

		function single_list(entering_search = false) {
			let keyword      = jQuery('.search-keys input#search-key-' + tab).val();
			let current_page = get_current_page(entering_search);

			jQuery.ajax({
				url  : lassoLiteOptionsData.ajax_url,
				type : 'post',
				data : {
					action  : 'lasso_lite_get_single',
					nonce   : lassoLiteOptionsData.optionsNonce,
					keyword : keyword,
					limit   : limit,
					page    : current_page,
				},
				beforeSend: function() {
					show_loading();
				}
			})
				.done(function(res) {
					if (typeof res.data != 'undefined') {
						let data            = res.data;
						let json_data       = data.output;
						let single_total    = parseInt(data.total);
						let page            = data.page;
						let html_pagination = '<div id="pagination-container" class="pagination"></div>';
						let el_all_link     = jQuery("#all_links");
						currentPage         = page;

						lasso_lite_helper.inject_to_template(el_all_link, 'single-list', json_data);
						jQuery(el_all_link).append(html_pagination);
						paginator(single_total);
					}
				});
		}

		// Get tab's current page
		function get_current_page(entering_search = false) {
			if ( entering_search ) {
				currentPage = 1;
			}

			return currentPage;
		}

		function show_loading() {
			let html = '<div class="py-5"><div class="loader"></div></div>';
			if(tab === 'single') {
				jQuery("#all_links").html(html);
			}
		}

		function paginator(count) {
			let paginator = jQuery('#pagination-container').pagination({
				items: count,
				itemsOnPage: limit,
				currentPage: currentPage,
				cssStyle: 'light-theme',
				onPageClick: function(pageNumber, event) {
					if(tab === 'single') {
						currentPage = pageNumber;
						lasso_lite_helper.remove_page_number_out_of_url();
						single_list();
					}
				}
			});
		}

		function add_short_code_single_main() {
			try { add_short_code_single_block(this); } catch (error) {}
			try { add_short_code_single(this); } catch (error) {}
		}

		function show_create_link_modal(){
			jQuery("#lasso-display-add").modal("hide");
			jQuery("#url-add").modal("show");
		}

		jQuery(".search-keys input").off('keyup').on('keyup',function( e ) {
			// WHEN ENTER IS PRESSED, SEARCH
			if(e.which === 13) {
				single_list(true);
			}
		});

		/**
		 * Reset pop-up on close
		 */
		function reset_pop_up_display_modal() {
			jQuery("#lasso-display-type").removeClass("d-none");
			jQuery("#lasso-display-add .tab-container").addClass("d-none");
			jQuery("#lasso-display-add .tab-container .lasso-items").html('');
			single_list();
		}
	});
});
