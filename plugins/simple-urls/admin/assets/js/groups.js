jQuery(document).ready(function() {
	jQuery('#modal-save-animation h3').text("Updating Group");
	jQuery(document)
		.on('click', '#lasso-lite-group-save', group_save)
		.on('click', '#group_delete_pop', open_delete_group_popup)
		.on('click', '#group_delete_btn', delete_group);

	jQuery("#links-filter").submit(function (e) {
		e.preventDefault();
		let keyword = jQuery("#link-search-input").val().trim();
		lasso_lite_helper.update_url_parameter('link-search-input', keyword);
		load_groups(null, keyword );
	});

	init_page();

	function init_page() {
		let subpage = lasso_lite_helper.get_url_parameter('subpage');
		let page = lasso_lite_helper.get_url_parameter('page');
		if ( page === 'surl-groups' ) {
			load_groups();
		} else if ( page === 'surl-group-detail' && subpage === 'urls' ) {
			load_links();
		}
	}

	function group_save( event ) {
		event.preventDefault();

		let btn_save = jQuery('#group_save');
		let grp_name = jQuery('#grp_name').val().trim();
		let cat_desc = jQuery('#grp_desc').val().trim();
		let modal_save_animation = jQuery('#modal-save-animation');
		let notification_template = 'default-template-notification-group';

		if ( grp_name !== '' ) {
			jQuery("#url-save").find("h3").text("Updating Group");
			lasso_lite_helper.add_loading_button( btn_save );
			modal_save_animation.modal('show');

			jQuery.ajax({
				url: lassoLiteOptionsData.ajax_url,
				type: 'post',
				data: {
					action: 'lasso_lite_store_category',
					nonce: lassoLiteOptionsData.optionsNonce,
					cat_id: jQuery('#grp_id').val(),
					cat_name: grp_name,
					cat_desc: cat_desc,
				},
				beforeSend: function (xhr) {
					lasso_lite_helper.set_progress_bar(98, 20, modal_save_animation);

					if (jQuery('#grp_name').val() == '') {
						xhr.lasso_error = 'Name is required.';
						return false;
					}
				}
			})
			.done(function (res) {
				res = res.data;
				let noti_type = res.cat_id > 0 ? 'green' : 'red';
				lasso_lite_helper.do_notification(res.msg, noti_type, notification_template);
			})
			.fail(function (xhr, status, error) {
				let error_msg = lasso_lite_helper.get_msg_ajax_error( xhr );
				lasso_lite_helper.do_notification(error_msg, 'red', notification_template);
			})
			.always(function() {
				lasso_lite_helper.set_progress_bar_complete();
				setTimeout(function() {
					// Hide update popup by setTimeout to make sure this run after lasso_update_popup.modal('show')
					modal_save_animation.modal('hide');
				}, 1000);
			});
		}
	}

	function load_groups( page_number = null, keyword = null) {
		let container = jQuery('#report-content');
		if ( page_number === null ) {
			page_number = lasso_lite_helper.get_page_from_current_url();
		}

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action: 'lasso_lite_group_get_list',
				nonce: lassoLiteOptionsData.optionsNonce,
				page: page_number,
				keyword: keyword
			},
			beforeSend: function () {
				container.html(lasso_lite_helper.get_loading_image());
			}
		})
		.done(function (res) {
			if ( res.success === true ) {
				let data = res.data;
				let json_data = data.output;

				// empty data
				if (json_data.length == 0) {
					container.html(lasso_lite_helper.empty_html());
					return;
				}

				lasso_lite_helper.inject_to_template(jQuery("#report-content"), 'group-list', json_data);
				lasso_lite_helper.generate_paging( jQuery('.dashboard-pagination'), data.page, data.total, function (page_number) {
					load_groups(page_number);
				}, data.limit_on_page);
			} else {
				container.html(lasso_lite_helper.empty_html());
			}
		})
		.fail(function (xhr, status, error) {
			container.html(lasso_lite_helper.empty_html());
		});
	}
	
	function load_links() {
		let container = jQuery('#report-content');
		let group_id = jQuery('#post_id').val();
		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action: 'lasso_lite_group_get_links',
				nonce: lassoLiteOptionsData.optionsNonce,
				group_id: group_id
			},
			beforeSend: function () {
				container.html(lasso_lite_helper.get_loading_image());
			}
		})
		.done(function (res) {
			if ( res.success === true ) {
				let data = res.data;
				let json_data = data.output;
				if ( json_data.length > 0 ) {
					lasso_lite_helper.inject_to_template(jQuery("#report-content"), 'group-urls', json_data);
				} else {
					container.html(lasso_lite_helper.empty_html());
				}
			} else {
				container.html(lasso_lite_helper.empty_html());
			}
		})
		.fail(function (xhr, status, error) {
			container.html(lasso_lite_helper.empty_html());
		});
	}

	function open_delete_group_popup() {
		let url_count = jQuery("#url_count").val();
		if(url_count > 0){
			jQuery("#group_not_delete").modal("show");
		} else {
			jQuery("#group-delete").modal("show");
		}
	}

	function delete_group() {
		let delete_btn = jQuery(this);
		let cat_id = jQuery("#post_id").val();
		let modal_save_animation = jQuery('#modal-save-animation');

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action: 'lasso_lite_delete_category',
				nonce: lassoLiteOptionsData.optionsNonce,
				cat_id: cat_id,
			},
			beforeSend: function () {
				modal_save_animation.modal('show');
				lasso_lite_helper.add_loading_button( delete_btn );
				jQuery("#group-delete").modal("hide");
				jQuery("#modal-save-animation").find("h3").text("Deleting Group");
				lasso_lite_helper.set_progress_bar(100, 50, modal_save_animation);
			}
		})
		.done(function (res) {
			res = res.data;
			let redirect_url = res.redirect_link;
			window.location.replace(redirect_url);
		})
		.always(function(){
			modal_save_animation.modal('hide');
		});
	}
});