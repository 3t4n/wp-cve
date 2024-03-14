jQuery(document).ready(function ($) {

	// loading for first time files load
	$('.upfp_content').waitForImages(function () {
		$('#preloader_sec').hide();
		$(this).show();
	});

	// Reset tool buttons
	function reset_tool_btns(shown_class = '', shown_class_1 = '') {
		$('.upfp_folder_banner .upfp_folder_tool a, .upfp_folder_banner .upfp_folder_tool form').hide();
		$('.upfp_folder_banner .upfp_folder_tool ' + shown_class).show();
		$('.upfp_folder_banner .upfp_folder_tool ' + shown_class_1).show();
	}

	/* Load files from folder */
	function load_folder_items(elem, fltr_email = '') {

		// check if filter by
		if (typeof elem == 'string' && elem == 'filter') {

			var fldr_id = 'filter-shared';
			var fldr_name = 'Filtered - ' + fltr_email;

			var folder_status = "";
			var data_share = "true";

		}
		else {
			// check if clicked from breadcrumbs
			if (typeof elem == 'string') {
				var fldr_id = elem.replace("bc_fldr_id_", "");
				var fldr_name = $('.upfp_parmalink').find('#' + elem).text();
				
				// Check if trash folder clicked from BC
				var folder_status = $('#' + elem).attr('data-status');

				// Check if shared folder clicked from BC
				var data_share = $('#' + elem).attr('data-share');

			} 
			else {
				var fldr_id = $(elem).closest('.upfp_fldr_obj').attr('data-folder-id');
				var fldr_name = $(elem).closest('.upfp_fldr_obj').attr('data-folder-name');

				// Check if trash folder clicked
				var folder_status = $(elem).closest('.upfp_fldr_obj').attr('data-status');

				// check if shared folder clicked
				var data_share = $(elem).closest('.upfp_fldr_obj').attr('data-share');
			}

		}

		var fd = new FormData();
		fd.append('fldr_id', fldr_id);

		if (fltr_email != '') {
			fd.append('fltr_email', fltr_email);
		}

		if (folder_status == 'trash' || fldr_id == 'trash-files') {
			fd.append('fldr_status', 'trash');
		}

		if (data_share == 'true') {
			fd.append('data_share', 'true');
		}

		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);

		fd.append('action', 'upvf_pro_load_flder');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON",
			beforeSend: function () {
				$('#preloader_sec').css('display', 'flex');
				$('.upfp_content').hide();
			}
		})
			.done(function (results) {
				if (results.error) {
                    $('#preloader_sec').hide();
					$('.upfp_content').html(results.error).show();
				} else {

					if (results.html) {

						// Remove bulk selection
						is_bulk = is_bulk_all = '';
						$(this).css('box-shadow', '');
						$(this).css('background', '');
						$('.upfp_content').removeClass('is_bulk');
						$('.upfp_content .doc-item img').removeClass('selected');
						$('.bulk_act_btns, .bulk_act_trash_btns').css('opacity', '0.5').hide();
						$('#upfp_bulk_slct_all').css('opacity', 1);
						if (fldr_id != 'trash-files' && fldr_id != 'all-files' && fldr_id != 'all-shared-files' && fldr_id != 'filter-shared') {
							$('#upfp_share_fldr_btn, #upfp_rnm_fldr_btn, #upfp_dlt_fldr_btn').show();
						}

						// update content div id & folder name
						$('.upfp_content').attr('data-current-folder', fldr_id).attr('data-cf-name', fldr_name);
						if (fldr_id == 'trash-files' || folder_status == 'trash') {
							$('.upfp_content').attr('data-inside', 'trash');
						} else if (data_share == 'true') {
							$('.upfp_content').attr('data-inside', 'shared');
						} else {
							$('.upfp_content').attr('data-inside', '');
						}

						// sidebar
						if (data_share != 'true' || results.full_access) {

							$('.upfp_folder_info #access-hdng, .upfp_folder_info .upfp_folder_access_list').show();

							// display allowed Users in sidebar
							let alwd_usrs = results.alwd_emails;
							if (alwd_usrs) {
								let alwd_usr_html = '';

								var rmv_usr_btn = '<span class="rmv-fldr-acs"></span>';
								if (fldr_id == 'trash-files' || folder_status == 'trash') {
									rmv_usr_btn = '';
								}

								var acs_full = '';
								var rmv_usr_btn_new;
								$.each(alwd_usrs, function (i, val) {

									if (alwd_usrs[i][2] != '') { acs_full = '(' + alwd_usrs[i][2] + ')'; } else { acs_full = ''; }

									rmv_usr_btn_new = rmv_usr_btn;
									if (alwd_usrs[i][3] == 1) {
										rmv_usr_btn_new = '';
									}

									alwd_usr_html += '<li class="alwd-usr-li" data-usr-id="' + alwd_usrs[i][0] + '"><span> ' + alwd_usrs[i][1] + '</span><span> ' + acs_full + '</span>' + rmv_usr_btn_new + '</li>';

								});
								$('.upfp_folder_access_list').html(alwd_usr_html);
							} else {
								$('.upfp_folder_access_list').html('');
							}

							$('.upfp_folder_info #pencil_fldr_name, .upfp_folder_info #add_fldr_user').show();

						} else { // if inside share folders OR limited access
							if (fldr_id != 'all-shared-files') {
								$('.upfp_folder_info #access-hdng, .upfp_folder_info .upfp_folder_access_list').hide();
								$('.upfp_folder_info #pencil_fldr_name, .upfp_folder_info #add_fldr_user').hide();
							}
						}

						// shared/created by
						$('.upfp_folder_info .upfp_folder_shared_by').html(results.author);
						$('.upfp_folder_info #shared-by').show();

						if (folder_status == 'trash' && fldr_id != 'trash-files') { // if inside trash sub-folders
							$('.upfp_folder_info #pencil_fldr_name, .upfp_folder_info #add_fldr_user').hide();
						}

						// update folder name in sidebar
						$('.upfp_folder_info #folder_name').html(results.folder_ttl);

						// display folder info in sidebar
						if (fldr_id == 'trash-files' || fldr_id == 'all-files' || fldr_id == 'all-shared-files' || fldr_id == 'filter-shared') {
							$('.upfp_folder_info').hide();
						} else {
							$('.upfp_folder_info').show();

							// Update name in navigation
							$('#upfp_nav').find('li[data-folder-id="' + fldr_id + '"] .upfp_foldr span').text(' ' + results.folder_ttl);
							$('#upfp_nav').find('li[data-folder-id="' + fldr_id + '"]').attr('data-folder-name', results.folder_ttl);
						}

						if (results.left_panel) {
							$('#upfp_nav.upfp_col').html(results.left_panel);
						}

						// update nav folder class
						$('#upfp_nav li').removeClass('upfp_li_active');
						if (fldr_id == 'all-files') { // for home
							$('#upfp_nav li[data-folder-id="all-files"]').addClass('upfp_li_active');
						}
						else if (fldr_id == 'trash-files' || folder_status == 'trash') {
							$('#upfp_nav li[data-folder-id="trash-files"]').addClass('upfp_li_active');
						}
						else if (fldr_id == 'all-shared-files') {
							$('#upfp_nav li[data-folder-id="all-shared-files"]').addClass('upfp_li_active');
						}
						else {
							$('#upfp_nav').find('li#upfp_nav_fldr_' + fldr_id).addClass('upfp_li_active');
						}

						// Update breadcrumb
						if (fldr_name != '') {
							$('.upfp_folder_banner .upfp_parmalink span').html(' / ');
						} else {
							$('.upfp_folder_banner .upfp_parmalink span').html('');
						}

						// Remove file name if exist
						$('.upfp_folder_banner .upfp_parmalink').find('.bc_file_name').remove();

						if (data_share == 'true') {
							$('.upfp_folder_banner #upfp_bc_folder').html(' <span id="bc_fldr_id_all-shared-files" data-share="true">Shared</span>');
							if (fldr_id != 'all-shared-files') {
								$('.upfp_folder_banner #upfp_bc_folder').append(' / ' + '<small id="bc_fldr_id_' + fldr_id + '">' + results.folder_ttl + '</small>');
							}
						} 
						else {

							if (results.all_parent_folders.length === 0 || folder_status == 'trash') {
								// If inside root folder or trash folders		

								if (folder_status == 'trash' && fldr_id != 'trash-files') {

									$('.upfp_folder_banner #upfp_bc_folder').html('<span id="bc_fldr_id_trash-files">Trash</span> / ');
									$('.upfp_folder_banner #upfp_bc_folder').append('<span id="bc_fldr_id_' + fldr_id + '" data-status="trash">' + results.folder_ttl + '</span>');

								} 
								else {
									$('.upfp_folder_banner #upfp_bc_folder').html('<span id="bc_fldr_id_' + fldr_id + '">' + fldr_name + '</span>');
								}

							} 
							else {
								// Inside a sub folder
								$('.upfp_folder_banner #upfp_bc_folder, .upfp_folder_banner .upfp_parmalink span').html('');

								// Get all parent folders first
								jQuery.each(results.all_parent_folders, function (index, item) {
									$('.upfp_folder_banner #upfp_bc_folder').append(' / ' + '<span id="bc_fldr_id_' + item.id + '">' + item.name + '</span>');
								});

								$('.upfp_folder_banner #upfp_bc_folder').append(' / ' + '<span id="bc_fldr_id_' + fldr_id + '">' + results.folder_ttl + '</span>');

							}
						}

						// Update tool buttons
						reset_tool_btns('');

						if (data_share != 'true' || results.full_access) {

							if (fldr_name != '' && folder_status != 'trash' && fldr_id != 'trash-files') {
								reset_tool_btns('.folder_btns');
							}
							else if (folder_status == 'trash' && fldr_id != 'trash-files') {
								// if inside trash subfolders
								reset_tool_btns('.trash_folder_btns', '#upfp_bulk_slct_fldr_btn');
							}
							else if (fldr_id == 'trash-files') {
								reset_tool_btns('.trash_action_btns', '#upfp_bulk_slct_fldr_btn');
							}
							else if (fldr_name == '') { // for all-files
								reset_tool_btns('#upfp_bulk_slct_fldr_btn');
							}

						} else {
							if (fldr_id == 'all-shared-files' || fldr_id == 'filter-shared') {
								reset_tool_btns('.swm_fltr_frm');
							}
						}

						// reset filter form
						if (fldr_id != 'filter-shared') {
							$('#upfp_swm_fltr_box').val('');
						}

						// pre-loader
						$('.upfp_content').html(results.html).waitForImages(function () {
							$('#preloader_sec').hide();
							$(this).show();
						});

						// Hide upload files/folder btns if inside trash or shared folders
						if (fldr_id == 'trash-files' || folder_status == 'trash' || fldr_id == 'all-shared-files') {
							$('#upfp_upload_btn, #upfp_newfolder_btn').hide();
						} else {
							if (data_share == 'true' && !results.full_access) {
								$('#upfp_upload_btn, #upfp_newfolder_btn').hide();
							} else {
								$('#upfp_upload_btn, #upfp_newfolder_btn').show();
							}
						}

						// if user has full access, set this global js variable for use in bulk-action file
						if (results.full_access) {
							fldr_full_acs = 1;
						} else {
							fldr_full_acs = '';
						}

						// Hide all popups & file info panel
						$('.edit_doc_upf_popup .doc_view').html('');
						$('.upfp-popup, .upfp_file_info').hide();

					}

				}

			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})

	}

	// Load from navigation
	$('#upfp_nav').on('click', '.upfp_foldr', function () {
		load_folder_items($(this));
	});

	// Load from breadcrumbs
	$('#upfp_bc_folder').on('click', 'span', function () {
		load_folder_items($(this).attr('id'));
	});
	// home
	$('.upfp_parmalink').on('click', '#upfp_pl_home', function () {
		$('#upfp_home_link').click();
	});

	// Load files from main content
	$('.upfp_content').on('click', '.sub-folder-action', function () {
		if (is_bulk) {
			return;
		}
		load_folder_items($(this));
	});


	/* New folder */
	$('#upfp_newfolder_btn').on('click', function () {

		$('.edit_doc_upf_popup .close_edit_popup').click();

		let parent_fldr_id = $('.upfp_content').attr('data-current-folder');
		if (parent_fldr_id != 'all-files') {
			$('.upfp-popup.new-fldr #parent_fldr').val(parent_fldr_id);
		} else {
			$('.upfp-popup.new-fldr #parent_fldr').val('0');
		}

		$('.upfp-popup.new-fldr').show();

	});

	/* close new folder popup */
	$('body').on('click', '.new_fldr_closePopup', function () {
		$(this).closest('.upfp-popup.new-fldr').hide();
		$('.upfp-popup.new-fldr').find("form")[0].reset();
	});


	/* New folder submission */
	$("body").on("submit", "#upvf-new-fldr", function (e) {
		e.preventDefault();
		var fd = new FormData();

		fd.append('fldr_ttl', jQuery('body').find('#new-fldr-name').val());

		var parent_folder_val = jQuery('body').find('#upvf-new-fldr').find('#parent_fldr').val();
		if (parent_folder_val > 0) {
			fd.append('parent_fldr', parent_folder_val);
		}

		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_new_flder_callback');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					console.log(results.error);
				} else {
					if (results.html) {

						$('.no-files-err').html('');
						$('.upfp_content').prepend(results.html);

						if (results.folders_li) {
							$('.upfp_nav_list.my_folders').prepend(results.folders_li);
						}

					}
				}

				$('.upfp-popup.new-fldr').hide();
				$('.upfp-popup.new-fldr').find("form")[0].reset();

			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})
	});

	// Rename folder
	$('body').on('click', '#upfp_rnm_fldr_btn, #pencil_fldr_name', function () {
		var target_fldr = $('.upfp_content').attr('data-current-folder');
		var current_fldr_name = $('.upfp_content').attr('data-cf-name');
		if (target_fldr > 0) {

			$('.upfp-rnm-pp #rnm-fldr-name').val(current_fldr_name);
			$('.upfp-rnm-pp #upvf-rnm-fldr-id').val(target_fldr);
			$('.upfp-rnm-pp').show();

		}
	});
	$('body').on('click', '.upvf-rnm-cls', function () {
		$('body').find('.upfp-rnm-pp').hide();
		$('.upfp-rnm-pp').find("form")[0].reset();
	});
	$('body').on('submit', '#upvf-rename-fldr', function (e) {
		e.preventDefault();
		var fd = new FormData();
		var fldr_id = $(this).find('#upvf-rnm-fldr-id').val();
		var fldr_name = $(this).find('#rnm-fldr-name').val();
		fd.append('folder_id', fldr_id);
		fd.append('folder_new_name', fldr_name);
		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_rename_folder');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					console.log(results.error);
				} else {
					if (results.fldr_id) {

						// Update in navigation
						$('#upfp_nav').find('li[data-folder-id="' + fldr_id + '"] .upfp_foldr span').text(' ' + fldr_name);
						$('#upfp_nav').find('li[data-folder-id="' + fldr_id + '"]').attr('data-folder-name', fldr_name);

						// Update in breadcrumbs
						$('.upfp_parmalink').find('#bc_fldr_id_' + fldr_id).text(fldr_name);

						// Update in sidebar
						$('.upfp_folder_info').find('#folder_name').text(fldr_name);

						// Update in content
						$('.upfp_content').attr('data-cf-name', fldr_name);

						// close popup
						$('body').find('.upfp-rnm-pp').hide();
						$('.upfp-rnm-pp').find("form")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})

	});

	// Delete folder	
	$('#upfp_dlt_fldr_btn, #upfp_trash_dlt_fldr_btn').on('click', function () {
		var target_fldr = $('.upfp_content').attr('data-current-folder');

		if ($(this).attr('id') == 'upfp_trash_dlt_fldr_btn') {
			var dlt_type = 'permanent';
			$('.upvf-dlt-pp #dlt_folder_permanent').show();
		} else {
			var dlt_type = 'trash';
			$('.upvf-dlt-pp #dlt_folder_trash').show();
		}
		if (target_fldr > 0) {

			$('.upvf-dlt-pp').attr('data-dlt-type', dlt_type);
			$('.upvf-dlt-pp #upvf-dlt-fldr-id').val(target_fldr);
			$('.upvf-dlt-pp').show();
		}
	});

	$('body').on('click', '.upvf-dlt-cls', function () {
		$('body').find('.upvf-dlt-pp').hide();
		$('.upvf-dlt-pp').find("form")[0].reset();
		// reset delete popup message
		$('.upvf-dlt-pp #dlt_folder_trash').hide();
		$('.upvf-dlt-pp #dlt_folder_permanent').hide();
	});
	$('body').on('submit', '#upvf-delete-fldr', function (e) {
		e.preventDefault();
		var fd = new FormData();
		var fldr_id = $(this).find('#upvf-dlt-fldr-id').val();
		var dlt_type = $(this).closest('.upvf-dlt-pp').attr('data-dlt-type');
		fd.append('folder_id', fldr_id);
		fd.append('dlt_type', dlt_type);
		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_delete_folder');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					console.log(results.error);
				} else {
					if (results.deleted) {

						if (dlt_type == 'trash') {

							if (results.parent_folder) { // go to parent folder
								$('#upfp_bc_folder').find('#bc_fldr_id_' + results.parent_folder).click();
							} else { // go to home
								$('#upfp_home_link').click();
							}
							// remove from left nav
							$('.upfp_nav_list.my_folders').find('li[data-folder-id="' + fldr_id + '"]').remove();

						} else { // go to trash main folder
							$('#upfp_trash_link').click();
						}

						$('body').find('.upvf-dlt-pp').hide();
						$('.upvf-dlt-pp').find("form")[0].reset();
						// reset delete popup message
						$('.upvf-dlt-pp #dlt_folder_trash').hide();
						$('.upvf-dlt-pp #dlt_folder_permanent').hide();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})

	});

	// Trash - Restore Folder
	$('body').on('click', '#upfp_restore_fldr_btn', function () {
		var target_fldr = $('.upfp_content').attr('data-current-folder');
		if (target_fldr.length > 0) {

			var fd = new FormData();
			fd.append('folder_id', target_fldr);
			fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
			fd.append('action', 'upvf_pro_restore_folder');
			$.ajax({
				url: ajax_upvf_frnt_obj.ajaxurl,
				type: 'POST',
				data: fd,
				contentType: false,
				processData: false,
				dataType: "JSON"
			})
				.done(function (results) {
					if (results.error) {
						console.log(results.error);
					} else {

						if (results.restored_folder) {

							$('#upfp_trash_link').click();

							if (results.li_html) {
								$('.upfp_nav_list.my_folders').prepend(results.li_html);
							}
							else if (results.shared_li_html) {
								$('.upfp_nav_list.shared_fldrs').prepend(results.shared_li_html);
							}

						}
					}

				})
				.fail(function (data) {
					console.log(data.responseText);
					console.log('Request Failed. Status - ' + data.statusText);
				});

		}
	});

	// Trash - Empty trash
	$('#upfp_trash_empty_btn').on('click', function () {
		$('.upvf-empty-trash-pp').show();
	});

	$('body').on('click', '.upvf-et-cls', function () {
		$('body').find('.upvf-empty-trash-pp').hide();
	});

	$('body').on('submit', '#upvf-empty-trash', function (e) {
		e.preventDefault();
		var fd = new FormData();
		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_empty_trash');
		$.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					console.log(results.error);
				} else {
					if (results.done) {
						// Refresh Trash page
						$('#upfp_trash_link').click();
						$('body').find('.upvf-empty-trash-pp').hide();
					}
				}

			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});
	});

	// for admins - allow bulk add users
	$('input[name="fldr_share_type"]').on('change', function () {
		let fldr_share_type = $(this).attr('id');

		$('.share_folder_popup form').hide();

		if (fldr_share_type == "fldrSingleShare") {
			$('.share_folder_popup #upf_allow_folder_access_frm').show();
		} 
		else if (fldr_share_type == "fldrRoleShare") {
			$('.share_folder_popup #upf_allow_folder_access_frm_to_role').show();
		} 
		else if (fldr_share_type == "fldrAllShare") {
			$('.share_folder_popup #upf_allow_folder_access_frm_all').show();
		}

	});

	// Add user to folder
	$('body').on('click', '#upfp_share_fldr_btn, #add_fldr_user', function () {
		$('.share_folder_popup').fadeIn();
	});

	$('body').on('click', '.close_share_folder_popup', function () {
		$('.share_folder_popup .upfp-error').html('').hide();
		$('.share_folder_popup').hide();
		$('.share_folder_popup').find("form")[0].reset();
	});

	$("#upf_allow_folder_access_frm").on("submit", function (e) {
		e.preventDefault();

		var access_lvl = $(this).find('select.upfp_share_acs_lvl').val();

		var fd = new FormData();
		fd.append('usr_email', $('#fldr_allowed_usr_mail').val());
		fd.append('fldr_id', $('.upfp_content').attr('data-current-folder'));
		fd.append('access_lvl', access_lvl);
		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_share_folder');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					$('.share_folder_popup .upfp-error').html(results.error).show();
					console.log(results.error);
				} else {
					if (results.alwd_emails) {

						var alwd_usrs = results.alwd_emails;
						if (alwd_usrs) {
							var alwd_usr_html = '';

							var rmv_usr_btn = '<span class="rmv-fldr-acs"></span>';

							var acs_full = '';
							var rmv_usr_btn_new;
							$.each(alwd_usrs, function (i, val) {

								if (alwd_usrs[i][2] != '') { acs_full = '(' + alwd_usrs[i][2] + ')'; } else { acs_full = ''; }

								rmv_usr_btn_new = rmv_usr_btn;
								if (alwd_usrs[i][3] == 1) {
									rmv_usr_btn_new = '';
								}

								alwd_usr_html += '<li class="alwd-usr-li" data-usr-id="' + alwd_usrs[i][0] + '"><span> ' + alwd_usrs[i][1] + '</span><span> ' + acs_full + '</span>' + rmv_usr_btn_new + '</li>';
							});
							$('.upfp_folder_access_list').html(alwd_usr_html);
						}

						$('.share_folder_popup .upfp-error').html('').hide();
						$('.share_folder_popup').hide();
						$('.share_folder_popup').find("form")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});
	});

	// add role
	$("#upf_allow_folder_access_frm_to_role").on("submit", function (e) {
		e.preventDefault();

		var access_lvl = $(this).find('select.upfp_share_acs_lvl').val();

		var fd = new FormData();
		fd.append('usr_type', 'role');
		fd.append('usr_role', $('#fldr_allowed_role').val());
		fd.append('fldr_id', $('.upfp_content').attr('data-current-folder'));
		fd.append('access_lvl', access_lvl);
		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_share_folder_bulk');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					$('.share_folder_popup .upfp-error').html(results.error).show();
					console.log(results.error);
				} else {
					if (results.alwd_emails) {

						var alwd_usrs = results.alwd_emails;
						if (alwd_usrs) {
							var alwd_usr_html = '';
							var rmv_usr_btn = '<span class="rmv-fldr-acs"></span>';

							var acs_full = '';
							var rmv_usr_btn_new;
							$.each(alwd_usrs, function (i, val) {

								if (alwd_usrs[i][2] != '') { acs_full = '(' + alwd_usrs[i][2] + ')'; } else { acs_full = ''; }

								rmv_usr_btn_new = rmv_usr_btn;
								if (alwd_usrs[i][3] == 1) {
									rmv_usr_btn_new = '';
								}

								alwd_usr_html += '<li class="alwd-usr-li" data-usr-id="' + alwd_usrs[i][0] + '"><span> ' + alwd_usrs[i][1] + '</span><span> ' + acs_full + '</span>' + rmv_usr_btn_new + '</li>';
							});

							$('.upfp_folder_access_list').html(alwd_usr_html);
						}

						$('.share_folder_popup .upfp-error').html('').hide();
						$('.share_folder_popup').hide();
						$('.share_folder_popup').find("form")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});
	});

	// add all users
	$("#upf_allow_folder_access_frm_all").on("submit", function (e) {
		e.preventDefault();

		var access_lvl = $(this).find('select.upfp_share_acs_lvl').val();

		var fd = new FormData();
		fd.append('usr_type', 'all');
		fd.append('fldr_id', $('.upfp_content').attr('data-current-folder'));
		fd.append('access_lvl', access_lvl);
		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_share_folder_bulk');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					$('.share_folder_popup .upfp-error').html(results.error).show();
					console.log(results.error);
				} else {
					if (results.alwd_emails) {

						var alwd_usrs = results.alwd_emails;
						if (alwd_usrs) {
							var alwd_usr_html = '';
							var rmv_usr_btn = '<span class="rmv-fldr-acs"></span>';

							var acs_full = '';
							var rmv_usr_btn_new;
							$.each(alwd_usrs, function (i, val) {

								if (alwd_usrs[i][2] != '') { acs_full = '(' + alwd_usrs[i][2] + ')'; } else { acs_full = ''; }

								rmv_usr_btn_new = rmv_usr_btn;
								if (alwd_usrs[i][3] == 1) {
									rmv_usr_btn_new = '';
								}

								alwd_usr_html += '<li class="alwd-usr-li" data-usr-id="' + alwd_usrs[i][0] + '"><span> ' + alwd_usrs[i][1] + '</span><span> ' + acs_full + '</span>' + rmv_usr_btn_new + '</li>';
							});
							$('.upfp_folder_access_list').html(alwd_usr_html);
						}

						$('.share_folder_popup .upfp-error').html('').hide();
						$('.share_folder_popup').hide();
						$('.share_folder_popup').find("form")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});
	});

	// Remove user from a folder
	$('.upfp_folder_access_list').on('click', '.rmv-fldr-acs', function () {
		var data = {
			action: 'upvf_pro_rmv_fldr_access',
			upf_nonce: ajax_upvf_frnt_obj.nonce,
			fldr_id: $('.upfp_content').attr('data-current-folder'),
			user: $(this).closest('.alwd-usr-li').attr('data-usr-id')
		};
		$.post(ajax_upvf_frnt_obj.ajaxurl, data, function (response) {
			if (response.length > 0) {
				var res = $.parseJSON(response);
				if (res.rmvd_usr) {
					$('.alwd-usr-li[data-usr-id="' + res.rmvd_usr + '"]').remove();
				} 
				else {
					console.log(response);
				}
			} 
			else {
				console.log('no response');
			}
		});
	});

	/* Search */
	$("#top_search_frm").on("submit", function (e) {
		e.preventDefault();

		var search_keyword = $('#upfp_search_box').val();

		var fd = new FormData();
		fd.append('search_keyword', search_keyword);
		fd.append('upf_nonce', ajax_upvf_frnt_obj.nonce);
		fd.append('action', 'upvf_pro_search');
		jQuery.ajax({
			url: ajax_upvf_frnt_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					console.log(results.error);
				} else {

					if (results.html) {

						// load results
						$('.upfp_content').html(results.html);

						// update content attrs
						$('.upfp_content').attr('data-current-folder', 'all-files').attr('data-cf-name', '').attr('data-inside', '');

						// update nav folder class
						$('#upfp_nav li').removeClass('upfp_li_active');
						$('#upfp_nav li[data-folder-id="all-files"]').addClass('upfp_li_active');

						// Update breadcrumb
						$('.upfp_folder_banner .upfp_parmalink').find('.bc_file_name').remove();
						$('.upfp_folder_banner .upfp_parmalink span').html(' / ');
						$('.upfp_folder_banner #upfp_bc_folder').html(' <small>Search</small>');

						// Update tool buttons
						reset_tool_btns('');

						// Hide upload files/folder btns
						$('#upfp_upload_btn, #upfp_newfolder_btn').hide();

						// Hide all popups & file info panel
						$('.upfp-popup, .upfp_folder_info, .upfp_file_info').hide();

					}
				}

			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})
	});


	/* Filter shared files */
	$("#swm_fltr_frm").on("submit", function (e) {
		e.preventDefault();
		var fltr_email = $('#upfp_swm_fltr_box').val();
		load_folder_items('filter', fltr_email);
	});

	$('#upfp_reset_fltr').on('click', function () {
		load_folder_items($('.upfp_nav_list li[data-folder-id="all-shared-files"]')); // pass shared all-files nav item
	});

});