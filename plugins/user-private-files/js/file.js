jQuery(document).ready(function ($) {

	var doc_type = is_bulk = is_bulk_all = fldr_full_acs = file_full_acs = '';

	// Reset tool buttons
	function main_reset_tool_btns(shown_class = '', shown_class_1 = '') {
		$('.upfp_folder_banner .upfp_folder_tool a, .upfp_folder_banner .upfp_folder_tool form').hide();
		$('.upfp_folder_banner .upfp_folder_tool ' + shown_class).show();
		$('.upfp_folder_banner .upfp_folder_tool ' + shown_class_1).show();
	}

	// Upload files btn
	$('#upfp_upload_btn').on('click', function () {
		$('.add-doc-pp').show();
		closeFilePreview();
	});

	// Reset add-file popup fields
	function reset_upload_fields() {
		$('#upf_upload_sec').find(".uploader input[type='file']").val('');
		$('.add-doc-pp').hide();
		$('.upfp_upload_error').html('').hide();
		$('.upfp_uploaded-sec').html('');
	}

	// close popup
	$('body').on('click', '.closePopup', function () {
		reset_upload_fields();
	});

	// Manual select
	$('#upf_upload_sec .uploader button').on('click', function () {
		$('#upf_upload_sec #upload_doc').click();
	});
	$('#upload_doc').on('change', function (e) {
		// Submitting the upload-file form
		var files_list = $('#upload_doc').prop('files');
		var i = 0;
		var j = 1;
		$(files_list).each(function () {
			var file = $(this)[0];
			doc_type = file.type;

			var max_size = ajax_upf_obj.max_upload_size;
			if (file.size > max_size) {
				alert(ajax_upf_obj.max_err);
				return false;
			}
			var trimmed_file_name = jQuery.trim(file.name);

			var spnr_svg = '<svg viewBox="0 0 100 100">' +
				'<path d="M 50,50 m 0,-47 a 47,47 0 1 1 0,94 a 47,47 0 1 1 0,-94" stroke="#000" stroke-width="1" fill-opacity="0"></path>' +
				'<path class="upfp_spnr" d="M 50,50 m 0,-47 a 47,47 0 1 1 0,94 a 47,47 0 1 1 0,-94" stroke="green" stroke-width="6" fill-opacity="0" style="stroke-dasharray: 296, 296; stroke-dashoffset: 296;"></path>' +
				'</svg>';
			var file_html = '<div class="upfp_single-file"><span>' + file.name + '</span><span class="upfp_spnr_circle" id="upfp_sf_spnr_' + i + '">' + spnr_svg + '</span></div>';

			$('.upfp_uploaded-sec').append(file_html);

			if (file && (trimmed_file_name.length > 0)) {
				var fd = new FormData();
				fd.append('doc_ttl', file.name);
				fd.append('docfile', file);
				fd.append('upf_nonce', ajax_upf_obj.nonce);

				var target_folder_id = $('.upfp_content').attr('data-current-folder');
				fd.append('fldr_id', target_folder_id);

				fd.append('action', 'upvf_pro_upload_doc_callback');
				var bar = $('#upfp_sf_spnr_' + i + ' .upfp_spnr');
				jQuery.ajax({
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						xhr.upload.addEventListener("progress", function (evt) {
							if (evt.lengthComputable) {
								var percentComplete = evt.loaded / evt.total;
								percentComplete = parseInt(percentComplete * 100);
								var decrease_val = parseInt(296 / percentComplete);
								bar.css('stroke-dashoffset', decrease_val);
							}
						}, false);
						return xhr;
					},
					url: ajax_upf_obj.ajaxurl,
					type: 'POST',
					data: fd,
					contentType: false,
					processData: false,
					dataType: "JSON"
				})
					.done(function (results) {
						if (results.error) {
							$('.upfp_upload_error').html(results.error).show();
						} else {
							if (results.new_doc_id) {
								var new_doc_id = results.new_doc_id;
								var new_doc_thumb = results.thumb_url;
								var new_doc_src = results.doc_src;
								var doc_ttl = results.doc_ttl;

								$doc_pht_html = '<div id="doc_' + new_doc_id + '" class="doc-item" data-alwd-usrs>';
								if (doc_type.indexOf("image") != -1) {
									$doc_pht_html += '<a class="upfp_single_file edit-doc" href="javascript:void(0);"><img data-type="img" data-src="' + new_doc_src + '" src="' + new_doc_thumb + '"></a>';
								} else {
									var prvw_image = 'File_thumbnail.png';
									
									if (doc_type.indexOf("pdf") != -1) {
										prvw_image = 'PDF_thumbnail.png';
									} 
									else if (doc_type.indexOf("video") != -1) {
										prvw_image = 'Video_thumbnail.png';
									} 
									else if (doc_type.indexOf("document") != -1) {
										prvw_image = 'Doc_thumbnail.png';
									} 
									else if (doc_type.indexOf("zip") != -1) {
										prvw_image = 'Zip_thumbnail.png';
									}
									
									let doc_prvw_img = ajax_upf_obj.upvf_plugin_url + 'images/' + prvw_image;
									$doc_pht_html += '<a class="upfp_single_file edit-doc" href="javascript:void(0);"><img data-src="' + new_doc_src + '" src="' + doc_prvw_img + '"></a>';
								}
								$doc_pht_html += '<p class="doc_ttl">' + doc_ttl + '</p></div>';

								if ($('.upfp_content .doc-item:first').length) {
									$($doc_pht_html).insertBefore(".upfp_content .doc-item:first");
								} 
								else if ($('.upfp_content .folder-item:last').length) {
									$($doc_pht_html).insertAfter(".upfp_content .folder-item:last");
								} 
								else {
									$('.upfp_content').prepend($doc_pht_html);
								}
								
								$('.no-files-err').html('');

								if (j == files_list.length) {
									reset_upload_fields();
								}
								j++;

							}
						}

					})
					.fail(function (data) {
						console.log('Request Failed. Status - ' + data.statusText);
						bar.css('stroke-dashoffset', '296');
					})
			} else {
				$('.upfp_upload_error').html('Unable to upload file. Please check all fields').show();
			}

			i++;

		});

	});


	// close file-preview popup function
	function closeFilePreview() {

		$('.edit_doc_upf_popup .doc_view').html('');
		$('.edit_doc_upf_popup, .upfp_file_info').hide();

		// Update breadcrumb
		$('.upfp_folder_banner .upfp_parmalink').find('.bc_file_name').remove();

		if ($('.upfp_content').attr('data-current-folder') == 'all-files') { // for home
			main_reset_tool_btns('#upfp_bulk_slct_fldr_btn');
		}
		else if ($('.upfp_content').attr('data-current-folder') == 'all-shared-files') { // for main shared
			main_reset_tool_btns('.swm_fltr_frm');
		}
		else if ($('.upfp_content').attr('data-inside') == 'trash') {

			if ($('.upfp_content').attr('data-current-folder') == 'trash-files') { // main trash

				main_reset_tool_btns('.trash_action_btns', '#upfp_bulk_slct_fldr_btn');

			} else { // a trash folder

				main_reset_tool_btns('.trash_folder_btns', '#upfp_bulk_slct_fldr_btn');
				$('.upfp_folder_info').show();
			}

		} else { // inside a folder

			if ($('.upfp_file_info').attr('data-full-acs') == 'false') {
				main_reset_tool_btns('');
			} else {
				main_reset_tool_btns('.folder_btns');
			}
			$('.upfp_folder_info').show();
		}

		// display folder info if inside a share folder
		if ($('.upfp_content').attr('data-inside') == 'shared' && $('.upfp_content').attr('data-current-folder') != 'all-shared-files') {
			$('.upfp_folder_info').show();
		}

	}

	// Edit a doc
	$('body').on('click', '.close_edit_popup', function () {
		closeFilePreview();
	});

	$('.upfp_content').on('click', '.edit-doc', function () {

		if (is_bulk) {
			return;
		}

		let doc_id = $(this).closest('.doc-item').attr('id');

		var fd = new FormData();
		fd.append('doc_id', doc_id);
		fd.append('upf_nonce', ajax_upf_obj.nonce);

		var is_shared = $(this).closest('.doc-item').attr('data-share');

		if ($('.upfp_content').attr('data-inside') == 'shared' || is_shared) {
			fd.append('inside_shared', '1');
		}

		fd.append('action', 'upvf_pro_preview_file');
		jQuery.ajax({
			url: ajax_upf_obj.ajaxurl,
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

					$('.upfp_file_info #edit_doc_ttl').html(results.doc_ttl);
					$('.upfp_file_info #edit_doc_desc').html(results.doc_desc);

					let doc_src = results.doc_src;
					if (results.file_type == 'img') {

						$('.edit_doc_upf_popup .doc_view').html('<img src="' + doc_src + '">');

					} else if (results.file_type == 'vdo') {

						$('.edit_doc_upf_popup .doc_view').html('<video controls autoplay src="' + doc_src + '"></video>');

					} else { // for displaying documents

						var ext = doc_src.substr((doc_src.lastIndexOf('.') + 1));
						if (ext == 'pdf') {
							let object = "<object data='" + doc_src + "' type=\"application/pdf\" width=\"100%\" height=\"100%\">";
							object += "If you are unable to view this file, you can download it from <a href='" + doc_src + "'>here</a>";
							object += " or download <a target = \"_blank\" href = \"http://get.adobe.com/reader/\">Adobe PDF Reader</a> to view the file.";
							object += "</object>";
							$('.edit_doc_upf_popup .doc_view').html(object);
						} else {
							$('.edit_doc_upf_popup .doc_view').html("Unable to load the file preview. You can download it from <a href='" + doc_src + "' download>here</a>");
						}

					}

					$('.upfp_file_info #file-access-hdng, .upfp_file_info .upfp_file_access_list').show();
					// $('.upfp_file_info #file-shared-by').hide();
					$('.upfp_file_info .upfp_file_shared_by').html(results.author);

					$('.upfp_file_info #pencil_doc_name, .upfp_file_info #pencil_doc_desc, .upfp_file_info #add_doc_user').show();

					// If inside shared folders & no full access
					if (($('.upfp_content').attr('data-inside') == 'shared' || is_shared) && !results.full_access) {
						$('.upfp_file_info').attr('data-full-acs', 'false');
						$('.upfp_file_info #file-access-hdng, .upfp_file_info .upfp_file_access_list').hide();
						$('.upfp_file_info #file-shared-by').show();
						$('.upfp_file_info .upfp_file_shared_by').html(results.author);

						$('.upfp_file_info #pencil_doc_name, .upfp_file_info #pencil_doc_desc, .upfp_file_info #add_doc_user').hide();
					}
					else {

						$('.upfp_file_info').attr('data-full-acs', 'true');

						// Allowed Users
						let alwd_usrs = results.alwd_emails;
						if (alwd_usrs) {
							let alwd_usr_html = '';

							var rmv_usr_btn = '<span class="rmv-file-acs"></span>';
							if ($('.upfp_content').attr('data-inside') == 'trash') {
								rmv_usr_btn = '';
								$('.upfp_file_info #pencil_doc_name, .upfp_file_info #pencil_doc_desc, .upfp_file_info #add_doc_user').hide();
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
							$('.upfp_file_access_list').html(alwd_usr_html);
						} else {
							$('.upfp_file_access_list').html('');
						}

					}

					$('.edit_doc_upf_popup').attr('data-file', doc_id);

					// Display Preview
					$('.edit_doc_upf_popup, .upfp_file_info').show();

					// load comments
					$('.upfp_file_comments').html(results.cmnts_html);

					// Hide folder info
					$('.upfp_folder_info').hide();

					// Update breadcrumb
					if (results.doc_ttl != '') {
						$('.upfp_folder_banner .upfp_parmalink').append('<small class="bc_file_name"> / ' + results.doc_ttl + '</small>');
					}

					// Reset tools
					if ($('.upfp_content').attr('data-inside') == 'trash') {
						main_reset_tool_btns('.trash_file_btns');
					} else if (($('.upfp_content').attr('data-inside') == 'shared' || is_shared) && !results.full_access) {
						main_reset_tool_btns('');
						$('.upfp_folder_tool #upfp_download_btn').show();
					} else {
						main_reset_tool_btns('.file_btns');
						if (results.full_access) {
							$('.upfp_folder_tool #upfp_move_btn').hide();
						}
					}

					// Update download link
					$('.upfp_folder_tool #upfp_download_btn').attr('href', doc_src).attr('download', results.doc_ttl);

				}

			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})

	});

	// for admins - allow bulk users
	$('input[name="share_type"]').on('change', function () {
		let share_type = $(this).attr('id');

		$('.share_file_popup form').hide();

		if (share_type == "singleShare") {
			$('.share_file_popup #upf_allow_access_frm').show();
		} 
		else if (share_type == "roleShare") {
			$('.share_file_popup #upf_allow_access_frm_to_role').show();
		} 
		else if (share_type == "allShare") {
			$('.share_file_popup #upf_allow_access_frm_all').show();
		}

	});

	// Add user to files
	$('body').on('click', '#upfp_share_btn, #add_doc_user', function () {
		$('.share_file_popup').fadeIn();
	});

	$('body').on('click', '.close_share_popup', function () {
		$('.share_file_popup .upfp-error').html('').hide();
		$('.share_file_popup').hide();
		$('.share_file_popup').find("form")[0].reset();
	});

	$("#upf_allow_access_frm").on("submit", function (e) {
		e.preventDefault();

		var access_lvl = $(this).find('select.upfp_share_acs_lvl').val();

		var fd = new FormData();
		fd.append('usr_email', $('#allowed_usr_mail').val());
		fd.append('docid', $('.edit_doc_upf_popup').attr('data-file'));
		fd.append('access_lvl', access_lvl);
		fd.append('upf_nonce', ajax_upf_obj.nonce);
		fd.append('action', 'upvf_pro_update_doc');
		jQuery.ajax({
			url: ajax_upf_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					$('.share_file_popup .upfp-error').html(results.error).show();
					console.log(results.error);
				} else {
					if (results.alwd_emails) {

						var alwd_usrs = results.alwd_emails;
						if (alwd_usrs) {
							var alwd_usr_html = '';

							var rmv_usr_btn = '<span class="rmv-file-acs"></span>';
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
							$('.upfp_file_access_list').html(alwd_usr_html);
						}

						$('.share_file_popup .upfp-error').html('').hide();
						$('.share_file_popup').hide();
						$('.share_file_popup').find("form#upf_allow_access_frm")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});
	});

	// add role
	$("#upf_allow_access_frm_to_role").on("submit", function (e) {
		e.preventDefault();

		var access_lvl = $(this).find('select.upfp_share_acs_lvl').val();

		var fd = new FormData();
		fd.append('usr_type', 'role');
		fd.append('usr_role', $('#allowed_role').val());
		fd.append('docid', $('.edit_doc_upf_popup').attr('data-file'));
		fd.append('access_lvl', access_lvl);
		fd.append('upf_nonce', ajax_upf_obj.nonce);
		fd.append('action', 'upvf_pro_add_bulk');
		jQuery.ajax({
			url: ajax_upf_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					$('.share_file_popup .upfp-error').html(results.error).show();
					console.log(results.error);
				} else {
					if (results.alwd_emails) {

						var alwd_usrs = results.alwd_emails;
						if (alwd_usrs) {
							var alwd_usr_html = '';

							var rmv_usr_btn = '<span class="rmv-file-acs"></span>';
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
							$('.upfp_file_access_list').html(alwd_usr_html);
						}

						$('.share_file_popup .upfp-error').html('').hide();
						$('.share_file_popup').hide();
						$('.share_file_popup').find("form#upf_allow_access_frm")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});
	});

	// add all users
	$("#upf_allow_access_frm_all").on("submit", function (e) {
		e.preventDefault();

		var access_lvl = $(this).find('select.upfp_share_acs_lvl').val();

		var fd = new FormData();
		fd.append('usr_type', 'all');
		fd.append('docid', $('.edit_doc_upf_popup').attr('data-file'));
		fd.append('access_lvl', access_lvl);
		fd.append('upf_nonce', ajax_upf_obj.nonce);
		fd.append('action', 'upvf_pro_add_bulk');
		jQuery.ajax({
			url: ajax_upf_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: "JSON"
		})
			.done(function (results) {
				if (results.error) {
					$('.share_file_popup .upfp-error').html(results.error).show();
					console.log(results.error);
				} else {
					if (results.alwd_emails) {

						var alwd_usrs = results.alwd_emails;
						if (alwd_usrs) {
							var alwd_usr_html = '';

							var rmv_usr_btn = '<span class="rmv-file-acs"></span>';
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
							$('.upfp_file_access_list').html(alwd_usr_html);
						}

						$('.share_file_popup .upfp-error').html('').hide();
						$('.share_file_popup').hide();
						$('.share_file_popup').find("form#upf_allow_access_frm")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});
	});

	// Remove user from a file
	$('.upfp_file_access_list').on('click', '.rmv-file-acs', function () {
		var data = {
			action: 'upvf_pro_rmv_access',
			upf_nonce: ajax_upf_obj.nonce,
			doc_id: $('.edit_doc_upf_popup').attr('data-file'),
			user: $(this).closest('.alwd-usr-li').attr('data-usr-id')
		};
		$.post(ajax_upf_obj.ajaxurl, data, function (response) {
			if (response.length > 0) {
				var res = $.parseJSON(response);
				if (res.rmvd_usr) {
					$('.alwd-usr-li[data-usr-id="' + res.rmvd_usr + '"]').remove();
				} else {
					console.log(response);
				}
			} else {
				console.log('no response');
			}
		});
	});

	// Rename file
	$('body').on('click', '#upfp_rename_btn, #pencil_doc_name', function () {
		var target_file = $('.edit_doc_upf_popup').attr('data-file');
		target_file = target_file.replace("doc_", "");
		var current_file_name = $('.upfp_file_info').find('#edit_doc_ttl').html();
		if (target_file.length > 0) {

			$('.upfp-rnm-file-pp #rnm-file-name').val(current_file_name);
			$('.upfp-rnm-file-pp #upvf-rnm-file-id').val(target_file);
			$('.upfp-rnm-file-pp').show();

		}
	});
	$('body').on('click', '.upvf-rnm-file-cls', function () {
		$('body').find('.upfp-rnm-file-pp').hide();
		$('.upfp-rnm-file-pp').find("form")[0].reset();
	});
	$('body').on('submit', '#upvf-rename-file', function (e) {
		e.preventDefault();
		var fd = new FormData();
		var file_id = $(this).find('#upvf-rnm-file-id').val();
		var file_name = $(this).find('#rnm-file-name').val();
		fd.append('file_id', file_id);
		fd.append('file_new_name', file_name);
		fd.append('upf_nonce', ajax_upf_obj.nonce);
		fd.append('action', 'upvf_pro_rename_file');
		jQuery.ajax({
			url: ajax_upf_obj.ajaxurl,
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
					if (results.file_id) {

						// Update in breadcrumbs
						$('.upfp_parmalink').find('.bc_file_name').text(' / ' + file_name);

						// Update in sidebar
						$('.upfp_file_info #edit_doc_ttl').html(file_name);

						// content
						$('body').find('#doc_' + file_id + ' .doc_ttl').html(file_name);

						// close popup
						$('body').find('.upfp-rnm-file-pp').hide();
						$('.upfp-rnm-file-pp').find("form")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})

	});

	// Edit file description
	$('body').on('click', '#pencil_doc_desc', function () {
		var target_file = $('.edit_doc_upf_popup').attr('data-file');
		target_file = target_file.replace("doc_", "");
		var current_file_dsc = $('.upfp_file_info').find('#edit_doc_desc').html();
		if (target_file.length > 0) {

			$('.upfp-file-dsc-pp #update-file-dsc').val(current_file_dsc);
			$('.upfp-file-dsc-pp #upvf-file-dsc-id').val(target_file);
			$('.upfp-file-dsc-pp').show();

		}
	});
	$('body').on('click', '.upvf-file-dsc-cls', function () {
		$('body').find('.upfp-file-dsc-pp').hide();
		$('.upfp-file-dsc-pp').find("form")[0].reset();
	});
	$('body').on('submit', '#upvf-file-dsc', function (e) {
		e.preventDefault();
		var fd = new FormData();
		var file_id = $(this).find('#upvf-file-dsc-id').val();
		var file_dsc = $(this).find('#update-file-dsc').val();
		fd.append('file_id', file_id);
		fd.append('file_new_dsc', file_dsc);
		fd.append('upf_nonce', ajax_upf_obj.nonce);
		fd.append('action', 'upvf_pro_update_file_dsc');
		jQuery.ajax({
			url: ajax_upf_obj.ajaxurl,
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
					if (results.file_id) {

						// Update in sidebar
						$('.upfp_file_info #edit_doc_desc').html(file_dsc);

						// close popup
						$('body').find('.upfp-file-dsc-pp').hide();
						$('.upfp-file-dsc-pp').find("form")[0].reset();

					}
				}
			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			})

	});


	// Delete File popup
	$('#upfp_delete_btn, #upfp_trash_dlt_file_btn').on('click', function () {
		var target_file = $('.edit_doc_upf_popup').attr('data-file');
		target_file = target_file.replace("doc_", "");

		if ($(this).attr('id') == 'upfp_trash_dlt_file_btn') {
			var dlt_type = 'permanent';
			$('.upfp-dlt-file-pp #upfp_dlt_msg_permanent').show();
			$('.upfp-dlt-file-pp #upfp_dlt_msg_trash').hide();
		} else {
			var dlt_type = 'trash';
			$('.upfp-dlt-file-pp #upfp_dlt_msg_trash').show();
			$('.upfp-dlt-file-pp #upfp_dlt_msg_permanent').hide();
		}
		if (target_file.length > 0) {

			$('.upfp-dlt-file-pp').attr('data-dlt-type', dlt_type);
			$('.upfp-dlt-file-pp #upvf-dlt-file-id').val(target_file);
			$('.upfp-dlt-file-pp').show();

		}
	});

	// Delete file
	$('body').on('click', '.upvf-dlt-file-cls', function () {
		$('body').find('.upfp-dlt-file-pp').hide();
		$('.upfp-dlt-file-pp').find("form")[0].reset();
	});

	$('body').on('submit', '#upvf-delete-file', function (e) {
		e.preventDefault();

		var file_id = $(this).find('#upvf-dlt-file-id').val();
		var dlt_type = $(this).closest('.upfp-dlt-file-pp').attr('data-dlt-type');
		var fd = new FormData();
		fd.append('doc_id', file_id);
		fd.append('dlt_type', dlt_type);
		fd.append('upf_nonce', ajax_upf_obj.nonce);
		fd.append('action', 'upvf_pro_delete_file');
		$.ajax({
			url: ajax_upf_obj.ajaxurl,
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
					if (results.rmvd_file) {

						// remove delete file popup
						$('body').find('.upfp-dlt-file-pp').hide();
						$('.upfp-dlt-file-pp').find("form")[0].reset();

						closeFilePreview();

						// Remove file from content
						$('.upfp_content').find('#doc_' + file_id).remove();

					}
				}

			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});

	});

	// Trash - Restore File
	$('body').on('click', '#upfp_restore_file_btn', function () {
		var target_file = $('.edit_doc_upf_popup').attr('data-file');
		target_file = target_file.replace("doc_", "");
		if (target_file.length > 0) {

			var fd = new FormData();
			fd.append('doc_id', target_file);
			fd.append('upf_nonce', ajax_upf_obj.nonce);
			fd.append('action', 'upvf_pro_restore_file');
			$.ajax({
				url: ajax_upf_obj.ajaxurl,
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
						if (results.restored_file) {

							closeFilePreview();

							// Remove file from content
							$('.upfp_content').find('#doc_' + target_file).remove();

						}
					}
				})
				.fail(function (data) {
					console.log(data.responseText);
					console.log('Request Failed. Status - ' + data.statusText);
				});

		}
	});

	// Move file to a folder popup - get all folders
	$('body').on('click', '#upfp_move_btn', function () {
		var target_file = $('.edit_doc_upf_popup').attr('data-file');
		target_file = target_file.replace("doc_", "");
		if (target_file.length > 0) {

			var folder_select = 'No Folder found';
			var fd = new FormData();
			fd.append('upf_nonce', ajax_upf_obj.nonce);
			fd.append('action', 'upvf_pro_get_folders');
			$.ajax({
				url: ajax_upf_obj.ajaxurl,
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
						folder_select = '<select id="upvf-moveto-fldr">';
						folder_select += results.options;
						folder_select += '</select>';

						$('.upfp-move-file-pp #upvf-move-file').prepend(folder_select);
						$('.upfp-move-file-pp #upvf-move-file-id').val(target_file);
						$('.upfp-move-file-pp').show();

					}
				})
				.fail(function (data) {
					console.log(data.responseText);
					console.log('Request Failed. Status - ' + data.statusText);
				});

		}
	});
	$('body').on('click', '.upvf-move-file-cls', function () {
		$('body').find('.upfp-move-file-pp').hide();
		$('.upfp-move-file-pp select').remove();
		$('.upfp-move-file-pp').find("form")[0].reset();
	});
	// Move file to folder - submit
	$('body').on('submit', '#upvf-move-file', function (e) {
		e.preventDefault();

		var file_id = $(this).find('#upvf-move-file-id').val();
		var fldr_id = $(this).find('#upvf-moveto-fldr').val();
		var fd = new FormData();
		fd.append('doc_id', file_id);
		fd.append('fldr_id', fldr_id);
		fd.append('upf_nonce', ajax_upf_obj.nonce);
		fd.append('action', 'upvf_pro_move_file');
		$.ajax({
			url: ajax_upf_obj.ajaxurl,
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
					if (results.new_fldr_id) {

						// hide move file popup
						$('body').find('.upfp-move-file-pp').hide();
						$('.upfp-move-file-pp select').remove();
						$('.upfp-move-file-pp').find("form")[0].reset();

						closeFilePreview();

						// Remove file from content
						if (results.new_fldr_id != $('.upfp_content').attr('data-current-folder')) {
							$('.upfp_content').find('#doc_' + file_id).remove();
						}

					}
				}

			})
			.fail(function (data) {
				console.log(data.responseText);
				console.log('Request Failed. Status - ' + data.statusText);
			});

	});

	// Add new comment to a file
	$("#upfp_file_cmnt_frm").on("submit", function (e) {
		e.preventDefault();

		var comment = $(this).find('#upfp_file_new_cmnt').val();

		var trimmed_comment = jQuery.trim(comment);
		if (trimmed_comment.length > 0) {

			var fd = new FormData();
			fd.append('docid', $('.edit_doc_upf_popup').attr('data-file'));
			fd.append('cmnt', comment);
			fd.append('upf_nonce', ajax_upf_obj.nonce);
			fd.append('action', 'upvf_pro_file_add_cmnt');
			jQuery.ajax({
				url: ajax_upf_obj.ajaxurl,
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
						if (results.added) {

							var cmnt_html = '<p>';
							cmnt_html += '<span class="cmnt_usr_avatar">' + results.user_avatar + '</span>';
							cmnt_html += '<span class="cmnt_usr_email">' + results.user_email + '</span>';
							cmnt_html += '<span class="cmnt_usr_cmnt">' + comment + '</span>';
							cmnt_html += '</p>';

							// prepend comment to the sidebar cmnt list
							$('.upfp_file_comments').prepend(cmnt_html);

							// reset form
							$('.upfp_file_info').find("form#upfp_file_cmnt_frm")[0].reset();

						}
					}
				})
				.fail(function (data) {
					console.log(data.responseText);
					console.log('Request Failed. Status - ' + data.statusText);
				});

		} else {
			console.log('Empty comment');
		}
	});

});