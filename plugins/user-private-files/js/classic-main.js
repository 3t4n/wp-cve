jQuery(document).ready(function($) {
	
	let doc_type = '';
	let drop_file = '';
	
	// Common popup functions
	$('.closePopup').on('click', function(){
		$('.edit_doc_upf_popup .doc_view').html('');
		$(this).closest('.upvf-popup').hide();
		$(this).closest('.upvf-popup').find("form")[0].reset();
		$(this).closest('#upf_upload_sec').find(".uploader input[type='file']").val('');
	});
	
	// Drag/Drop style & prevent the page from redirecting
	$("div:not(.upload-area)").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
		$('.upload-area').css("background", '');
    });
    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
		$(this).css("background", 'lightgrey');
    });
	$("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });
	
	// Drop
    $('#upload_doc_file').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
		var file = e.originalEvent.dataTransfer.files;
		var reader = new FileReader();
		reader.onload = function (event) {
			var url_data = event.target.result;
			if(file[0].type.includes('image')){
				$('#add-doc-frm img.doc_prvw_img').attr('src', url_data).show();
				doc_type = 'image';
				$('#add-doc-frm .doc_prvw_txt').text('').hide();
			} else if(file[0].type.includes('audio')){
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('AUDIO').show();
				doc_type = 'audio';
			} else if(file[0].type.includes('video')){
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('VIDEO').show();
				doc_type = 'video';
			} else if(file[0].type.includes('pdf')){
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('PDF').show();
				doc_type = 'doc/pdf';
			} else{
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('DOC').show();
				doc_type = 'doc';
			}
			$('#add-doc-frm .doc_prvw_txt').attr('data-src', url_data);
			var fileName = file[0].name;
			$('#add-doc-frm .uploaded-doc').attr('data-ext', fileName);
		}
		drop_file = file;
		reader.readAsDataURL(file[0]);
		$(this).css("background", '');
		$('.add-doc-pp').removeClass('upvf-hidden').fadeIn();
    });
	// Manual select
	$('#upf_upload_sec .uploader button').on('click', function(){
		$('#upf_upload_sec #upload_doc').click();
	});
	$('#upload_doc').on('change', function(e){
		var reader = new FileReader();
		var fileName = this.files[0].name;
		var fileType = this.files[0].type;
		reader.onload = function (event) {
			var url_data = event.target.result;
			if(fileType.includes('image')){
				$('#add-doc-frm img.doc_prvw_img').attr('src', url_data).show();
				doc_type = 'image';
				$('#add-doc-frm .doc_prvw_txt').text('').hide();
			} else if(fileType.includes('audio')) {
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('AUDIO').show();
				doc_type = 'audio';
			} else if(fileType.includes('video')) {
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('VIDEO').show();
				doc_type = 'video';
			} else if(fileType.includes('pdf')){
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('PDF').show();
				doc_type = 'doc/pdf';
			} else{
				$('#add-doc-frm img.doc_prvw_img').attr('src', ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png').show();
				$('#add-doc-frm .doc_prvw_txt').text('DOC').show();
				doc_type = 'doc';
			}
			$('#add-doc-frm .doc_prvw_txt').attr('data-src', url_data);
			$('#add-doc-frm .uploaded-doc').attr('data-ext', fileName);
		}
		reader.readAsDataURL(this.files[0]);
		$('.add-doc-pp').removeClass('upvf-hidden').fadeIn();
	});
	
	// Submitting the upload-file form
	$( "#add-doc-frm" ).on( "submit", function(e) {
		e.preventDefault();
		if(doc_type){
			var fd = new FormData();
			fd.append( 'doc_ttl', jQuery('#add-doc-frm #doc_ttl').val() );
			fd.append( 'doc_desc', jQuery('#add-doc-frm #doc_desc').val() );
			
			var selected_files;
			//if selected manually
			selected_files = $('#upload_doc').prop('files');

			//drag & drop
			if(selected_files[0] == undefined){
				selected_files = drop_file;
			}

			fd.append( 'docfile', selected_files[0] );
			
			if($('select#fldr_id').length > 0){
				if(jQuery('select#fldr_id').val() > 0){
					fd.append( 'fldr_id', jQuery('select#fldr_id').val() );
				}
			}
			
			fd.append( 'action', 'classic_upload_doc_callback' );
			fd.append( 'upf_nonce', ajax_upf_classic_obj.nonce );
			
			var bar = $('.progress_bar');
			bar.width('5%');
			jQuery.ajax ({
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt) {
					  if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						if(percentComplete >= 5){
							bar.width(percentComplete + '%');
						}
					  }
					}, false);
					return xhr;
				},
				url: ajax_upf_classic_obj.ajaxurl,
				type: 'POST',
				data: fd,
				contentType: false,
				processData: false,
				dataType:"JSON"
			})
			.done( function(results) {
				if(results.error){
					console.log(results.error);
				} else{
					if(results.new_doc_id){
						var new_doc_id = results.new_doc_id;
						var new_doc_thumb = results.thumb_url;
						var new_doc_src = results.doc_src;
						var doc_mime_type = results.doc_type;
						var doc_ttl = jQuery('#add-doc-frm #doc_ttl').val();
						
						$doc_pht_html = '<div id="doc_'+new_doc_id+'" class="doc-item" data-alwd-usrs doc_type="'+doc_mime_type+'">';
						if(doc_type == 'image'){
							$doc_pht_html += '<a class="edit-doc" href="javascript:void(0);"><img data-type="img" data-src="'+new_doc_src+'" src="'+new_doc_thumb+'"></a>';
						} else{
							let doc_prvw_img = ajax_upf_classic_obj.upvf_plugin_url + 'images/document.png';
							$doc_pht_html += '<a class="edit-doc" href="javascript:void(0);"><img data-src="'+new_doc_src+'" src="'+doc_prvw_img+'"></a>';
						}
						$doc_pht_html += '<p class="doc_ttl">'+doc_ttl+'</p></div>';
						
						if($('select#fldr_id').length > 0){
							var selected_fldr_id = $('select#fldr_id').val();
							if(selected_fldr_id > 0){
								$('#fldr-'+selected_fldr_id).find('.all-docs').prepend($doc_pht_html);
							} else{
								$('.all-docs.docs-list').prepend($doc_pht_html);
							}
						} else{
							$('.all-docs.docs-list').prepend($doc_pht_html);
						}
						
						bar.width(0);
						$('.add-doc-pp').hide();
						$('#upf_upload_sec').find("form#add-doc-frm")[0].reset();
						$('#upf_upload_sec').find(".uploader input[type='file']").val('');
						
					}
				}
			})
			.fail ( function(data) {
				console.log( data.responseText );
				console.log( 'Request Failed. Status - ' + data.statusText );
				bar.width(0);
			})
		}
	});
	
	// Edit a doc
	$('.all-docs').on('click', '.edit-doc', function(){
		let doc_id = $(this).closest('.doc-item').attr('id');
		let doc_mime_type = $(this).closest('.doc-item').attr('doc_type');
		let doc_ttl = $(this).closest('.doc-item').find('.doc_ttl').text();
		let doc_src = $(this).find('img').attr('data-src');
		let doc_desc = $(this).closest('.doc-item').find('.doc_desc').text();
		
		$('.edit_doc_upf_popup #edit_doc_ttl').html(doc_ttl);
		$('.edit_doc_upf_popup #edit_doc_desc').html(doc_desc);
		
		if(doc_mime_type.includes('image')) {
			$('.edit_doc_upf_popup .doc_view').html('<img src="'+doc_src+'">');
		} else if(doc_mime_type.includes('video')) {
			$('.edit_doc_upf_popup .doc_view').html('<video style="height:100%;width:100%;" controls autoplay src="' + doc_src + '"></video>');
		} else if(doc_mime_type.includes('audio')) {
			$('.edit_doc_upf_popup .doc_view').html('<audio  style="width:100%;" controls autoplay src="' + doc_src + '"></audio>');
		} else {
			var ext = doc_src.substr( (doc_src.lastIndexOf('.') +1) );
			if(ext == 'pdf'){
				let object = "<object data='"+doc_src+"' type=\"application/pdf\" width=\"100%\" height=\"100%\">";
				object += "If you are unable to view this file, you can download it from <a href='"+doc_src+"'>here</a>";
				object += " or download <a target = \"_blank\" href = \"http://get.adobe.com/reader/\">Adobe PDF Reader</a> to view the file.";
				object += "</object>";
				$('.edit_doc_upf_popup .doc_view').html(object);
			} else{
				$('.edit_doc_upf_popup .doc_view').html("Unable to load the file preview. You can download it from <a href='"+doc_src+"'>here</a>");
			}
			
		}
		
		let alwd_usrs = $(this).closest('.doc-item').attr('data-alwd-usrs');
		if(alwd_usrs){
			let alwd_user_array = alwd_usrs.split(",");
			let alwd_usr_html = '';
			$.each(alwd_user_array,function(i){
				let alwd_usr_data = alwd_user_array[i].split(":");
				alwd_usr_html += '<p class="alwd-usr-p" data-usr-id="'+alwd_usr_data[0]+'">' + alwd_usr_data[1] + ' <span class="rmv-file-acs">Remove</span></p>';
			});
			$('.doc_curr_alwd_users').html('<h5>Allowed Users</h5>' + alwd_usr_html);
		} else{
			$('.doc_curr_alwd_users').html('');
		}
		
		$('.edit_doc_upf_popup').attr('data-file', doc_id);
		$('.edit_doc_upf_popup').removeClass('upvf-hidden').fadeIn();
	});
	
	// add user to files
	$( "#upf_allow_access_frm" ).on( "submit", function(e) {
		e.preventDefault();
		var fd = new FormData();
		fd.append( 'usr_email', $('#allowed_usr_mail').val() );
		fd.append( 'docid', $('.edit_doc_upf_popup').attr('data-file') );
		
		fd.append( 'action', 'dpk_upvf_update_doc' );
		fd.append( 'upf_nonce', ajax_upf_classic_obj.nonce );
		
		var status_msg = $('#status');
		$.ajax ({
			url: ajax_upf_classic_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType:"JSON",
			beforeSend: function() {
				$('.edit_doc_upf_popup').css('opacity', '0.5');
			}
		})
		.done( function(results) {
			if(results.error){
				console.log(results.error);
			} else{
				if(results.added_user_email){
					var usr_html = '<p class="alwd-usr-p" data-usr-id="'+results.added_user_id+'">'+results.added_user_email+' <span class="rmv-file-acs">Remove</span></p>';
					$('.doc_curr_alwd_users').append(usr_html);
					var doc_elem_id = $('.edit_doc_upf_popup').attr('data-file');
					var doc_usrs = $('.all-docs').find('#'+doc_elem_id);
					var new_doc_usr = results.added_user_id + ':' + results.added_user_email;
					if(doc_usrs.attr('data-alwd-usrs').length){
						doc_usrs.attr('data-alwd-usrs', doc_usrs.attr('data-alwd-usrs') + ',' + new_doc_usr);
					} else{
						doc_usrs.attr('data-alwd-usrs', new_doc_usr);
					}
				}
			}
			$('.edit_doc_upf_popup').css('opacity', '');
			$('.edit_doc_upf_popup').find("form#upf_allow_access_frm")[0].reset();
			
		})
		.fail ( function(data) {
			console.log( data.responseText );
			console.log( 'Request Failed. Status - ' + data.statusText );
			$('.edit_doc_upf_popup').css('opacity', '');
		});
	});
	
	// Remove user from a file
	$('.edit_doc_upf_popup').on('click', '.rmv-file-acs', function(){
		var data = {
			action: 'dpk_upvf_rmv_access',
			upf_nonce: ajax_upf_classic_obj.nonce,
			doc_id: $('.edit_doc_upf_popup').attr('data-file'),
			user: $(this).closest('.alwd-usr-p').attr('data-usr-id')
		};
		$.post(ajax_upf_classic_obj.ajaxurl, data, function (response) {
			if (response.length > 0) {
				var res = $.parseJSON(response);
				if(res.rmvd_usr){
					$('.alwd-usr-p[data-usr-id="'+res.rmvd_usr+'"]').remove();
					var doc_elem_id = $('.edit_doc_upf_popup').attr('data-file');
					var doc_usrs = $('#'+doc_elem_id);
					if(doc_usrs.attr('data-alwd-usrs').length){
						var old_doc_usrs = doc_usrs.attr('data-alwd-usrs');
						var new_doc_usrs = old_doc_usrs;
						var target_usr_code = res.rmvd_usr + ':' + res.rmvd_usr_email;
						if(old_doc_usrs.includes(',' + target_usr_code)){
							var new_doc_usrs = old_doc_usrs.replace(',' + target_usr_code, "");
						} else if(old_doc_usrs.includes(target_usr_code + ',')){
							var new_doc_usrs = old_doc_usrs.replace(target_usr_code + ',', "");
						} else if(old_doc_usrs.includes(target_usr_code)){
							var new_doc_usrs = old_doc_usrs.replace(target_usr_code, "");
						}
						doc_usrs.attr('data-alwd-usrs', new_doc_usrs);
					}
					
				} else{
					console.log(response);
				}
			} else{
				console.log('no response');
			}
		});
	});
	
	// Remove a file
	$('.edit_doc_upf_popup').on('click', '#dlt-doc-file', function(){
		var file_id = $('.edit_doc_upf_popup').attr('data-file');
		var fd = new FormData();
		fd.append( 'doc_id', file_id );
		
		fd.append( 'action', 'dpk_upvf_rmv_file' );
		fd.append( 'upf_nonce', ajax_upf_classic_obj.nonce );
		
		$.ajax ({
			url: ajax_upf_classic_obj.ajaxurl,
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType:"JSON",
			beforeSend: function() {
				$('.edit_doc_upf_popup').css('opacity', '0.5');
			}
		})
		.done( function(results) {
			if(results.error){
				console.log(results.error);
			} else{
				if(results.rmvd_file){
					$('.all-docs #' + file_id).remove();
				}
			}
			$('.edit_doc_upf_popup').css('opacity', '').hide();
		})
		.fail ( function(data) {
			console.log( data.responseText );
			console.log( 'Request Failed. Status - ' + data.statusText );
			$('.edit_doc_upf_popup').css('opacity', '').hide();
		});
		
	});
	
	// Filters on files dashboard
	$('#files_filter #grp_by').on('change', function(){
		$('#files_filter').submit();
	});
	

});