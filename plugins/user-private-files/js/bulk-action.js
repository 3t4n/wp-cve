jQuery(document).ready(function($) {
	
	/* Bulk Select files */
	$('.upfp_folder_tool').on('click', '#upfp_bulk_slct_fldr_btn', function(){
		var curr_fldr_id = $('.upfp_content').attr('data-current-folder');
		var curr_inside = $('.upfp_content').attr('data-inside');
		if(is_bulk){
			is_bulk = '';
			
			$(this).css('box-shadow', '');
			$(this).css('background', '');
			
			$('.upfp_content').removeClass('is_bulk');
			$('.upfp_content .selected').removeClass('selected');
			
			if( (curr_inside != 'trash' && curr_fldr_id != 'all-files' && curr_fldr_id != 'all-shared-files' && curr_fldr_id != 'filter-shared') || fldr_full_acs ){
				$('#upfp_share_fldr_btn, #upfp_rnm_fldr_btn, #upfp_dlt_fldr_btn').show();
			}
			
			if(curr_inside == 'trash'){
				if(curr_fldr_id != 'trash-files'){
					$('.trash_folder_btns').show();
				}
			}
			
			$('.bulk_act_btns, .bulk_act_trash_btns').css('opacity', '0.5').hide();
			$('#upfp_bulk_slct_all').css('opacity', 1);
		} else{
			is_bulk = 'Yes';
			
			$(this).css('box-shadow', '0 0 0px 2px #0071a1');
			$(this).css('background', '#fff');
			
			$('.upfp_content').addClass('is_bulk');
			$('#upfp_share_fldr_btn, #upfp_rnm_fldr_btn, #upfp_dlt_fldr_btn').hide();
			
			if( curr_inside != 'trash' ){
				if(curr_inside == 'shared'){
					if(fldr_full_acs){
						$('#upfp_bulk_dlt_btn, #upfp_bulk_slct_all').show();
					}
				} else{
					$('.bulk_act_btns').show();
				}
			} else{
				$('.bulk_act_trash_btns, #upfp_bulk_slct_all').show();
				$('.trash_folder_btns').hide();
			}
			
		}
		
	});
		// selecting each item
	$('.upfp_content').on('click', '.edit-doc, .sub-folder-action', function(){
		if(is_bulk){
			var under_img = $(this).find('img')[0];
			if($(under_img).hasClass("selected")) {
				$(under_img).removeClass("selected");
			} else{
				$(under_img).addClass("selected");
			}
			var all_selected = $('.upfp_content.is_bulk').find('.selected');
			if(all_selected.length > 0){
				$('.bulk_act_btns, .bulk_act_trash_btns').css('opacity', '1');
			} else{
				$('.bulk_act_btns, .bulk_act_trash_btns').css('opacity', '0.5');
				$('#upfp_bulk_slct_all').css('opacity', 1);
			}
		}
	});
		// select all
	$('.upfp_folder_tool').on('click', '#upfp_bulk_slct_all', function(){
		if(is_bulk){
			if(is_bulk_all){
				$('.upfp_content').find('.edit-doc img, .sub-folder-action img').removeClass("selected");
				$('.bulk_act_btns, .bulk_act_trash_btns').css('opacity', '0.5');
				$(this).css('opacity', 1);
				is_bulk_all = '';
			} else{
				$('.upfp_content').find('.edit-doc img, .sub-folder-action img').addClass("selected");
				$('.bulk_act_btns, .bulk_act_trash_btns').css('opacity', '1');
				is_bulk_all = 'Yes';
			}
		}
	});
	
	/************** Actions for bulk selected files & folders *************/
	// Bulk Delete - both Trash & Permanent
	$('#upfp_bulk_dlt_btn, #upfp_bulk_dlt_permnt_btn').on('click', function(){
		var all_selected = $('.upfp_content.is_bulk').find('.selected');
		if(is_bulk && all_selected.length > 0){
			if( $(this).attr('id') == 'upfp_bulk_dlt_permnt_btn' ){
				var dlt_type = 'permanent';
			} else{
				var dlt_type = 'trash';
			}
			$.each(all_selected, function(i){
				let this_doc_id = $(all_selected[i]).closest('.doc-item').attr('id');
				
				// for selected files
				if(this_doc_id){
					this_doc_id = this_doc_id.replace("doc_", "");
					var fd = new FormData();
					fd.append( 'doc_id', this_doc_id );
					fd.append( 'dlt_type', dlt_type );
					fd.append( 'upf_nonce', ajax_upvf_bulk_obj.nonce );
					fd.append( 'action', 'upvf_pro_delete_file' );
					$.ajax ({
						url: ajax_upvf_bulk_obj.ajaxurl,
						type: 'POST',
						data: fd,
						contentType: false,
						processData: false,
						dataType:"JSON",
						beforeSend: function() {
							// $('.edit_doc_upf_popup').css('opacity', '0.5');
						}
					})
					.done( function(results) {
						if(results.error){
							console.log(results.error);
						} else{
							if(results.rmvd_file){
								// Remove file from content
								$('.upfp_content').find('#doc_' + this_doc_id).remove();
							}
						}
					})
					.fail ( function(data) {
						console.log( data.responseText );
						console.log( 'Request Failed. Status - ' + data.statusText );
					});
				} 
				// for selected folders
				else{
					let this_fldr_id = $(all_selected[i]).closest('.folder-item').attr('id');
					this_fldr_id = this_fldr_id.replace("sub_folder_", "");
					var fd = new FormData();
					fd.append( 'folder_id', this_fldr_id );
					fd.append( 'dlt_type', dlt_type );
					fd.append( 'upf_nonce', ajax_upvf_bulk_obj.nonce );
					fd.append( 'action', 'upvf_pro_delete_folder' );
					$.ajax ({
						url: ajax_upvf_bulk_obj.ajaxurl,
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
							if(results.deleted){
								// Remove folder from content
								$('.upfp_content').find('#sub_folder_' + this_fldr_id).remove();
								if(dlt_type == 'trash'){
									//remove from left nav
									$('.upfp_nav_list.my_folders').find('li[data-folder-id="' + this_fldr_id + '"]').remove();
								}
							}
						}
					})
					.fail ( function(data) {
						console.log( data.responseText );
						console.log( 'Request Failed. Status - ' + data.statusText );
					});
				}
			});
		}
	});
	
	// Bulk Move
	$('body').on('click', '#upfp_bulk_move_btn', function(){
		var all_selected = $('.upfp_content.is_bulk').find('.selected');
		if(is_bulk && all_selected.length > 0){
			
			let selected_fldrs = [];
			$.each(all_selected, function(i){
				let this_fldr_id = $(all_selected[i]).closest('.folder-item').attr('id');
				if(this_fldr_id){
					selected_fldrs[i] = this_fldr_id.replace("sub_folder_", "");
				}
			});
			
			var fd = new FormData();
			fd.append( 'upf_nonce', ajax_upvf_bulk_obj.nonce );
			fd.append( 'selected_fldrs', selected_fldrs );
			fd.append( 'action', 'upvf_pro_get_folders' );
			$.ajax ({
				url: ajax_upvf_bulk_obj.ajaxurl,
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
					var folder_select = '<select id="upvf-bulk-moveto-fldr">';
					folder_select += results.options;
					folder_select += '</select>';
					$('.upfp-move-bulk-files-pp #upvf-move-bulk-files').prepend(folder_select);
					$('.upfp-move-bulk-files-pp').show();
				}
			})
			.fail ( function(data) {
				console.log( data.responseText );
				console.log( 'Request Failed. Status - ' + data.statusText );
			});
		}
	});
	$('body').on('click', '.upvf-move-bulk-files-cls', function(){
		$('body').find('.upfp-move-bulk-files-pp').hide();
		$('.upfp-move-bulk-files-pp select').remove();
		$('.upfp-move-bulk-files-pp').find("form")[0].reset();
	});
	// Move to folder - submit
	$('body').on('submit', '#upvf-move-bulk-files', function(e){
		e.preventDefault();
		var fldr_id = $(this).find('#upvf-bulk-moveto-fldr').val();
		var all_selected = $('.upfp_content.is_bulk').find('.selected');
		if(is_bulk && all_selected.length > 0){
			$.each(all_selected, function(i){
				let this_doc_id = $(all_selected[i]).closest('.doc-item').attr('id');
				
				// for selected files
				if(this_doc_id){
					this_doc_id = this_doc_id.replace("doc_", "");
					var fd = new FormData();
					fd.append( 'doc_id', this_doc_id );
					fd.append( 'fldr_id', fldr_id );
					fd.append( 'upf_nonce', ajax_upvf_bulk_obj.nonce );
					fd.append( 'action', 'upvf_pro_move_file' );
					$.ajax ({
						url: ajax_upvf_bulk_obj.ajaxurl,
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
							if(results.new_fldr_id){
								// hide move file popup
								$('body').find('.upfp-move-bulk-files-pp').hide();
								$('.upfp-move-bulk-files-pp select').remove();
								$('.upfp-move-bulk-files-pp').find("form")[0].reset();
								
								// Remove file from content
								if( results.new_fldr_id != $('.upfp_content').attr('data-current-folder') ){
									$('.upfp_content').find('#doc_' + this_doc_id).remove();
								}
								
							}
						}
					})
					.fail ( function(data) {
						console.log( data.responseText );
						console.log( 'Request Failed. Status - ' + data.statusText );
					});
				}
				// for selected folders
				else{
					let this_fldr_id = $(all_selected[i]).closest('.folder-item').attr('id');
					this_fldr_id = this_fldr_id.replace("sub_folder_", "");
					var fd = new FormData();
					fd.append( 'folder_id', this_fldr_id );
					fd.append( 'target_fldr_id', fldr_id );
					fd.append( 'upf_nonce', ajax_upvf_bulk_obj.nonce );
					fd.append( 'action', 'upvf_pro_move_folder' );
					$.ajax ({
						url: ajax_upvf_bulk_obj.ajaxurl,
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
							if(results.new_fldr_id){
								// hide move file popup
								$('body').find('.upfp-move-bulk-files-pp').hide();
								$('.upfp-move-bulk-files-pp select').remove();
								$('.upfp-move-bulk-files-pp').find("form")[0].reset();
								
								// Remove file from content
								if( results.new_fldr_id != $('.upfp_content').attr('data-current-folder') ){
									$('.upfp_content').find('#sub_folder_' + this_fldr_id).remove();
								}
								
								// update navigation
								if(fldr_id == 'all-files' && results.li_html){
									$('.upfp_nav_list.my_folders').prepend(results.li_html);
								} else if(!results.dont_rmv){
									// remove (if found) from navigation if moved to a sub-folder
									$('.upfp_nav_list.my_folders').find('li#upfp_nav_fldr_' + this_fldr_id).remove();
								}
								
							}
						}
					})
					.fail ( function(data) {
						console.log( data.responseText );
						console.log( 'Request Failed. Status - ' + data.statusText );
					});
				}
			});
		}
	});
	
	// Bulk Restore
	$('#upfp_bulk_rstr_btn').on('click', function(){
		var all_selected = $('.upfp_content.is_bulk').find('.selected');
		if(is_bulk && all_selected.length > 0){
			$.each(all_selected, function(i){
				let this_doc_id = $(all_selected[i]).closest('.doc-item').attr('id');
				// for selected files
				if(this_doc_id){
					this_doc_id = this_doc_id.replace("doc_", "");
					var fd = new FormData();
					fd.append( 'doc_id', this_doc_id );
					fd.append( 'upf_nonce', ajax_upvf_bulk_obj.nonce );
					fd.append( 'action', 'upvf_pro_restore_file' );
					$.ajax ({
						url: ajax_upvf_bulk_obj.ajaxurl,
						type: 'POST',
						data: fd,
						contentType: false,
						processData: false,
						dataType:"JSON",
						beforeSend: function() {
							// $('.edit_doc_upf_popup').css('opacity', '0.5');
						}
					})
					.done( function(results) {
						if(results.error){
							console.log(results.error);
						} else{
							if(results.restored_file){
								// Remove file from content
								$('.upfp_content').find('#doc_' + this_doc_id).remove();
							}
						}
					})
					.fail ( function(data) {
						console.log( data.responseText );
						console.log( 'Request Failed. Status - ' + data.statusText );
					});
				} 
				// for selected folders
				else{
					let this_fldr_id = $(all_selected[i]).closest('.folder-item').attr('id');
					this_fldr_id = this_fldr_id.replace("sub_folder_", "");
					var fd = new FormData();
					fd.append( 'folder_id', this_fldr_id );
					fd.append( 'upf_nonce', ajax_upvf_bulk_obj.nonce );
					fd.append( 'action', 'upvf_pro_restore_folder' );
					$.ajax ({
						url: ajax_upvf_bulk_obj.ajaxurl,
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
							if(results.restored_folder){
								$('#upfp_trash_link').click();
								if(results.li_html){
									$('.upfp_nav_list.my_folders').prepend(results.li_html);
								}
							}
						}
					})
					.fail ( function(data) {
						console.log( data.responseText );
						console.log( 'Request Failed. Status - ' + data.statusText );
					});
				}
			});
		}
	});
	
	
});