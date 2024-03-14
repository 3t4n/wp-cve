/*************************** Popup title shorting ****************************************/

// var current_menu_item_count = 'none';

/**mm**/
function amm_meni_item_filter(search_filter_obj, page_no,chnage_page){

	var filter_text = search_filter_obj.find('.amm_header_main .menu_item_search_wrapper .menu_item_search').val();
	var author_id = search_filter_obj.find('.amm_menu_item_main_content_wrapper .menu_item_filter_header .author').attr('amm-filter-author');
	var template_name = search_filter_obj.find('.amm_menu_item_main_content_wrapper .menu_item_filter_header .template-list').attr('amm-filter-template-list');
	var category_name = search_filter_obj.find('.amm_menu_item_main_content_wrapper .menu_item_filter_header .category-list').attr('amm-filter-category-list');
	var current_menu_item = search_filter_obj.find('.allready-menu-item .curent-menu_item').val();
	filter_text = jQuery.trim(filter_text);

	if(typeof author_id === 'undefined'){author_id = 'all';}
	if(typeof template_name === 'undefined'){template_name = 'all';}
	if(typeof category_name === 'undefined'){category_name = 'all';}
	if(typeof current_menu_item === 'undefined' || !current_menu_item){	current_menu_item = 'off';}
	if(typeof page_no === 'undefined' || !page_no){	page_no = 1;}

	var menu_item_selector = search_filter_obj.find('.add-menu-item-pagelinks').attr('amm-pagination');
	var post_type_val = search_filter_obj.find('.md_popup_main_wrapper ul.amm_popup_header').attr('amm_post_type');
	var amm_menu_query = search_filter_obj.find('.md_popup_main_wrapper ul.amm_popup_header').attr('amm_menu_query');
	var	action_data;
	if('taxonomy' === amm_menu_query){
		action_data = {
			'action': 'my_action_for_add_new_menu_item_html_filter',
			'page_no' : page_no,
			'post_type' : post_type_val,
			'amm_menu_query' : amm_menu_query,
			'security' : ajax_object.ajax_nonce,
		};
	}else{
		action_data = {
			'action': 'my_action_for_add_new_menu_item_html_filter',
			'page_no' : page_no,
			'post_type' : post_type_val,
			'filter_author':author_id,
			'filter_template':template_name,
			'filter_textbox':filter_text,
			'filter_menu_item':current_menu_item,
			'filter_category':category_name,
			'security' : ajax_object.ajax_nonce,
		};
	}
	jQuery('#menu_manager_popup #menu_manager_popup_container ul.amm_popup_header li').css({'opacity': '0.3'});
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		data: action_data,
		success:function( response ) {
			var finaloutput = jQuery.parseJSON(response);
			if( finaloutput.sucess ){
				//alert_md(finaloutput.sucess);
				jQuery('#'+menu_item_selector).html(finaloutput.sucess);
				jQuery('#'+menu_item_selector).attr('amm_page_count',finaloutput.total_page);
				if('change_page' !== chnage_page) {
					amm_pagination_html(menu_item_selector,finaloutput.total_page);
				}
			}else if(finaloutput.error){
				alert_md(finaloutput.error);
			}
			jQuery('#menu_manager_popup #menu_manager_popup_container ul.amm_popup_header li').removeAttr('style');
		}
	});
}

// function md_menu_item_filter(filter_val, $filter_menu_item, filter_selector, flag_of_filter, author_selected_data, cate_tamplate_selected_data){
// 	var author_val;
// 	if('page' === flag_of_filter){
// 		author_val = author_selected_data;
// 		var template_val = cate_tamplate_selected_data;
// 		//page filter
// 		if(typeof template_val === 'undefined'){ template_val = 'all'; }
// 		if(typeof author_val === 'undefined'){ author_val = 'all'; }

// 		$filter_menu_item.find('li').each(function(){

// 			var c_obj_val_temp = jQuery(this).find('.template-list').text();
// 			var c_obj_val_auth = jQuery(this).find('.author').text();

// 			if('all' !== author_val && 'all' !== template_val){
// 				//both author and template
// 				if( author_val === c_obj_val_auth && template_val === c_obj_val_temp ){
// 					jQuery(this).removeClass('menu_filter_hide');
// 				}else{
// 					jQuery(this).addClass('menu_filter_hide');
// 				}
// 			}else if('all' !== author_val && 'all' === template_val){

// 				if( author_val === c_obj_val_auth ){
// 					jQuery(this).removeClass('menu_filter_hide');
// 				}else{
// 					jQuery(this).addClass('menu_filter_hide');
// 				}
// 			}else if('all' === author_val && 'all' !== template_val){
// 				if( template_val === c_obj_val_temp ){
// 					jQuery(this).removeClass('menu_filter_hide');
// 				}else{
// 					jQuery(this).addClass('menu_filter_hide');
// 				}
// 			}else{
// 				jQuery(this).removeClass('menu_filter_hide');
// 			}
// 		});

// 	}else{
// 		//post filter
// 		author_val = author_selected_data;
// 		var category_val = cate_tamplate_selected_data;
// 		if(typeof category_val === 'undefined' ){ category_val = 'all'; }
// 		if(typeof author_val === 'undefined' ){ author_val = 'all'; }

// 		$filter_menu_item.find('li').each(function(){

// 			var c_obj_val_auth = jQuery(this).find('.author').text();
// 			var c_cate_all = jQuery(this).find('.category-list').text();
// 			var cate_array = c_cate_all.split(',');

// 			if('all' !== author_val && 'all' !== category_val ){
// 				//both author and category
// 				if( author_val === c_obj_val_auth && jQuery.inArray(category_val, cate_array) !== -1 ){
// 					jQuery(this).removeClass('menu_filter_hide');
// 				}else{
// 					jQuery(this).addClass('menu_filter_hide');
// 				}

// 			}else if('all' !== author_val && 'all' === category_val ){
// 				if( author_val === c_obj_val_auth ){
// 					jQuery(this).removeClass('menu_filter_hide');
// 				}else{
// 					jQuery(this).addClass('menu_filter_hide');
// 				}
// 			}else if('all' === author_val && 'all' !== category_val ){
// 				if( jQuery.inArray(category_val, cate_array) !== -1 ){
// 					jQuery(this).removeClass('menu_filter_hide');
// 				}else{
// 					jQuery(this).addClass('menu_filter_hide');
// 				}
// 			}else{
// 				jQuery(this).removeClass('menu_filter_hide');
// 			}
// 		});
// 	}
// }

var post_per_page = 10;
function amm_pagination_html($curent_ul_obj, total_post_no, new_pagination_limit){

	$curent_ul_obj = '#'+$curent_ul_obj+'.amm_popup_header';
	if(typeof new_pagination_limit !== 'undefined'){
		post_per_page = new_pagination_limit;
	}else{
		var amm_post_per_page = jQuery($curent_ul_obj).attr('amm_post_per_page');
		post_per_page = parseInt(amm_post_per_page);
	}

	var total_post;

	total_post = typeof total_post_no !== 'undefined' ? total_post_no : jQuery($curent_ul_obj).attr('amm_page_count');

	if('total_page' === total_post_no ) {
		total_post = jQuery($curent_ul_obj).attr('amm_page_count');
	}

	if( parseInt(total_post) > parseInt(post_per_page) ){
		jQuery($curent_ul_obj).closest('.amm_item_main_wrapper').find('.add-menu-item-pagelinks').show();

		var total_noof_page = total_post/post_per_page;
		if(total_noof_page % 1 !== 0 ){ total_noof_page = total_noof_page+1; }
		total_noof_page = Math.floor(total_noof_page);
		var hide_page_link = '';
		if(total_noof_page <= pagination_link_gap ){
			hide_page_link = 'amm_pagination_link_hide';
		}

		var frist = '<li class="amm_page_first amm_pagination_link_hide '+hide_page_link+'"><a class="goto_previous" title="1">first</a></li>';
		var last = '<li class="amm_page_last '+hide_page_link+' "><a class="goto_next" title="'+total_noof_page+'">last</a></li>';

		var start, items = '', end, nav = '';
		start = '<ul class="amm_pagination">';
		end = '</ul>';
		items += '<li class="amm_page_link"><a class="active" title="1">1</a></li>';
		for (i=2;i<=total_noof_page;i++){
			items += '<li class="amm_page_link"><a class="" title="'+i+'">'+i+'</a></li>';
		}
		nav = start + frist + items + last+ end;
		jQuery($curent_ul_obj).closest('.amm_item_main_wrapper').find('.add-menu-item-pagelinks').html('');
		jQuery($curent_ul_obj).closest('.amm_item_main_wrapper').find('.add-menu-item-pagelinks').append(nav);
		var li_count = 0;
		jQuery($curent_ul_obj+' li').each(function(){
			//menu_filter_hide menu_exists_hide search_filter_hide
			if(!jQuery(this).hasClass('menu_filter_hide') &&  !jQuery(this).hasClass('menu_exists_hide') && !jQuery(this).hasClass('search_filter_hide')) {
				if( li_count >= post_per_page){
					jQuery(this).addClass('amm_pagination_hide');
				}
				li_count++;
			}
		});
	}else{
		jQuery($curent_ul_obj).closest('.amm_item_main_wrapper').find('.add-menu-item-pagelinks').hide();
	}
	pagination_page_option($curent_ul_obj);
}

// function pagination_page_item(page_item_array, total_menu_item, page_no){
// 	var li_count = 1, item_start, end_start;

// 	if( page_no > 1){
// 		item_start = ((page_no - 1 ) * post_per_page) + 1;
// 		end_start = ( page_no * post_per_page);
// 	}else{
// 		item_start = 1;
// 		end_start = post_per_page;
// 	}

// 	jQuery('#'+page_item_array+' li').each(function(){
// 		if(!jQuery(this).hasClass('menu_filter_hide') &&  !jQuery(this).hasClass('menu_exists_hide') && !jQuery(this).hasClass('search_filter_hide')) {
// 			if( (li_count <= end_start ) && (li_count >= item_start)){
// 				jQuery(this).removeClass('amm_pagination_hide');
// 			}else{
// 				jQuery(this).addClass('amm_pagination_hide');
// 			}
// 			li_count++;
// 		}
// 	});
// }

var pagination_link_gap = 3;

function pagination_page_option(pagination_link_obj){
	var count_link = jQuery(pagination_link_obj).closest('.amm_item_main_wrapper').find('.add-menu-item-pagelinks ul.amm_pagination li').length;
	if(count_link > (parseInt(pagination_link_gap)+2)){
		var load_prev ='<li class="load_pre amm_pagination_link_hide" data-page-no = "0" ><a class="" title="-">...</a></li>';
		var load_next = '<li class="load_next"><a class="" title="-" data-page-no = "'+pagination_link_gap+'" >...</a></li>';
		var link_obj = jQuery(pagination_link_obj).closest('.amm_item_main_wrapper').find('.add-menu-item-pagelinks ul.amm_pagination');

		link_obj.find('li:first').after(load_prev);
		link_obj.find('li:gt('+(pagination_link_gap+1)+')').addClass('amm_pagination_link_hide');
		link_obj.find('li:last').removeClass('amm_pagination_link_hide');
		//link_obj.find('li:nth-child('+(pagination_link_gap+3)+')').before(load_next);
		link_obj.find('li:last').before(load_next);
	}
}

function pagination_data_load_own(page_obj){

	var page_no = page_obj.attr('title');
	if(page_no !== '-'){
		var menu_item_selector = page_obj.parents('.add-menu-item-pagelinks').attr('amm-pagination');
		/**mm**/
		amm_meni_item_filter(jQuery('#'+menu_item_selector).closest('.amm_item_main_wrapper'),page_no,'change_page');

		page_obj.closest('ul.amm_pagination').find('li a').removeClass('active');
		page_obj.addClass('active');
	}
}
/*************** ready event *******************/
jQuery(document).ready( function() {

	//add menu item pagination
	jQuery('#menu_manager_popup #menu_manager_popup_container ul.amm_popup_header').each(function(){
		amm_pagination_html(jQuery(this).attr('id'));
	});

	/** author filter **/
	jQuery('#menu_manager_popup #menu_manager_popup_container div.menu_item_filter_header.amm_popup_header_wrapper select.filter_data').change(function(){
		var filter_val = jQuery(this).val();
		var filter_obj_selecot = jQuery(this).attr('data-filter');
		//amm_filer_value
		jQuery(this).closest('.menu_item_filter_header.amm_popup_header_wrapper').find('.'+filter_obj_selecot).attr('amm-filter-'+filter_obj_selecot,filter_val);

		/**mm**/
		amm_meni_item_filter(jQuery(this).closest('.amm_item_main_wrapper'));
	});

	jQuery('.menu_item_search_wrapper .menu_item_search').keyup(function(){

		var search_filter_obj = jQuery(this).closest('.amm_item_main_wrapper').find('div.md_popup_main_wrapper ul.amm_popup_header');
		//close edit item
		var search_text = jQuery(this).val();
		search_text = jQuery.trim(search_text);
		search_text = search_text.toLowerCase();
		search_filter_obj.find('li.no_record').remove();

		search_filter_obj.find('li').removeClass('amm_pagination_hide');
		// var pagination_obj = jQuery(this).closest('div.amm_item_main_wrapper').find('.add-menu-item-pagelinks');

		var amm_filter_selector = jQuery(this).closest('div.md_popup_main_wrapper').find('.menu_item_filter_header.amm_popup_header_wrapper');
		amm_filter_selector.find('.title').attr('amm-filter-title',search_text);

		amm_meni_item_filter(jQuery(this).closest('.amm_item_main_wrapper'));
	});

	// Search in texonomys
	jQuery(document).on('keyup', '.control-section.open .amm_taxonomy_search', function() {
		var search_text = jQuery(this).val();
		var texo = jQuery(this).attr('data-taxonomy');
		var action_data = {
			'action': 'my_action_for_amm_taxonomy_search',
			'search_text' : search_text,
			'texo' : texo
		};
		jQuery('#menu_manager_popup #menu_manager_popup_container ul#' + texo + 'checklist li').css({'opacity': '0.3'});
		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: action_data,
			success:function( response ) {
				var finaloutput = jQuery.parseJSON(response);
				if( finaloutput.sucess ){
					jQuery( 'ul#' + texo + 'checklist li').remove();
					jQuery( 'ul#' + texo + 'checklist').html(finaloutput.html);
				}else if(finaloutput.error){
					alert_md(finaloutput.error);
				}else{
					jQuery( 'ul#' + texo + 'checklist li').remove();
					jQuery( 'ul#' + texo + 'checklist').html(finaloutput.html);
				}
				jQuery('#menu_manager_popup #menu_manager_popup_container ul.amm_popup_header li').removeAttr('style');
			}
		});
	});

	//Hide menu item
	jQuery('span.allready-menu-item .curent-menu_item').change(function() {

		var ul_selector = jQuery(this).attr('data-selector');
		jQuery('ul#'+ul_selector+' li.no_record').remove();
		// var pagination_obj = jQuery('ul#'+ul_selector).closest('div.amm_item_main_wrapper').find('.add-menu-item-pagelinks');

		if (this.checked) {
			jQuery(this).val('on');
		}else{
			jQuery(this).val('off');
		}
		amm_meni_item_filter(jQuery(this).closest('.amm_item_main_wrapper'));
	});

	//amm pagination click event
	jQuery(document).on('click', '.amm_item_main_wrapper .add-menu-item-pagelinks .amm_pagination li.amm_page_link a', function(){
		pagination_data_load_own(jQuery(this));
	});
	jQuery(document).on('click', '.amm_item_main_wrapper .add-menu-item-pagelinks .amm_pagination li.amm_page_last a', function(){
		pagination_data_load_own(jQuery(this));
	});
	jQuery(document).on('click', '.amm_item_main_wrapper .add-menu-item-pagelinks .amm_pagination li.amm_page_first a', function(){
		pagination_data_load_own(jQuery(this));
	});

	jQuery(document).on('click', '.amm_item_main_wrapper .add-menu-item-pagelinks .amm_pagination li.load_next a', function(){
		var all_menu_item_link = jQuery(this).parents('.add-menu-item-pagelinks').find('ul.amm_pagination');
		var data_page_no = jQuery(this).attr('data-page-no');
		var count = 0;
		var show_link = parseInt(parseInt(pagination_link_gap) + parseInt(data_page_no));
		jQuery(this).attr('data-page-no',show_link);
		// var next_html = jQuery(this).parents('li.load_next').html();
		all_menu_item_link.find('li.load_pre a').attr('data-page-no',show_link);

		all_menu_item_link.find('li.amm_page_link').each(function(){
			var page_no = jQuery(this).find('a').attr('title');
			if( parseInt(page_no) > parseInt(data_page_no) && parseInt(page_no) <= parseInt(show_link) ){
				jQuery(this).removeClass('amm_pagination_link_hide');
			}else{
				jQuery(this).addClass('amm_pagination_link_hide');
			}
			count++;
		});
		var total_page = all_menu_item_link.find('li.amm_page_link').length;

		if( total_page <= show_link ){
			all_menu_item_link.find('li.amm_page_last').addClass('amm_pagination_link_hide');
			all_menu_item_link.find('li.load_next').addClass('amm_pagination_link_hide');
		}
		all_menu_item_link.find('li.amm_page_first').removeClass('amm_pagination_link_hide');
		all_menu_item_link.find('li.load_pre').removeClass('amm_pagination_link_hide');
	});

	jQuery(document).on('click', '.amm_item_main_wrapper .add-menu-item-pagelinks .amm_pagination li.load_pre a', function(){
		var all_menu_item_link = jQuery(this).parents('.add-menu-item-pagelinks').find('ul.amm_pagination');
		var data_page_no = jQuery(this).attr('data-page-no');
		var count = 0;
		var show_link = parseInt(parseInt(data_page_no) - parseInt(pagination_link_gap));
		jQuery(this).attr('data-page-no',show_link);
		// var next_html = jQuery(this).parents('li.load_pre').html();

		all_menu_item_link.find('li.load_next a').attr('data-page-no',show_link);

		data_page_no = parseInt(parseInt(show_link) - parseInt(pagination_link_gap) ) ;

		all_menu_item_link.find('li.amm_page_link').each(function(){
			var page_no = jQuery(this).find('a').attr('title');
			if( parseInt(page_no) > parseInt(data_page_no) && parseInt(page_no) <= parseInt(show_link) ){
				jQuery(this).removeClass('amm_pagination_link_hide');
			}else{
				jQuery(this).addClass('amm_pagination_link_hide');
			}
			count++;
		});

		// var total_page = all_menu_item_link.find('li.amm_page_link').length;

		if(data_page_no <= 0  ){
			all_menu_item_link.find('li.amm_page_first').addClass('amm_pagination_link_hide');
			all_menu_item_link.find('li.load_pre').addClass('amm_pagination_link_hide');
		}
		all_menu_item_link.find('li.amm_page_last').removeClass('amm_pagination_link_hide');
		all_menu_item_link.find('li.load_next').removeClass('amm_pagination_link_hide');

	});

	/************ edit menu ***************/
	jQuery(document).on('click', 'div.md_popup_main_wrapper ul.amm_popup_header li span i.menu_item_edit', function(){
		jQuery(this).closest('li').addClass('amm_edit_menu_item_open');
		jQuery(this).closest('li.amm_edit_menu_item_open').find('.menu_item_edit_div.amm_hide').fadeIn();
		jQuery(this).parents('li').find('span i.menu_item_edit').hide();
	});

	jQuery(document).on('click', 'div.md_popup_main_wrapper ul.amm_popup_header li div.menu_item_edit_div.amm_hide .submit_edit_post .amm_menu_edit_cancel', function(){
		jQuery(this).closest('li').find('.menu_item_edit_div.amm_hide').fadeOut();
		jQuery(this).parents('li').find('span i.menu_item_edit').removeAttr('style');
		jQuery(this).parents('li').removeClass('amm_edit_menu_item_open');
	});
	/************ new page/post/category added **********/
	jQuery(document).on('click', '#menu_manager_popup #menu_manager_popup_container div.amm_header_main span.page-title-action', function(){

		var obj = jQuery(this).closest('.amm_item_main_wrapper');
		//obj.find('.add_mew_item_wrapper').fadeOut("slow", function() {jQuery(this).removeClass("amm_deactive");});
		obj.find('.md_popup_main_wrapper').fadeOut('fast');
		obj.find('.amm_header_main').fadeOut('fast');
		obj.find('p.button-controls').fadeOut('fast');
		obj.find('p.amm_list_of_page').fadeOut('fast');
		obj.find('.add_mew_item_wrapper').removeClass('amm_deactive');
		var current_editor = obj.attr('amm_editor_selector');
		//for tinymca widget add on new page/post event.
		tinymce.remove(current_editor);
		amm_add_tinymce_widget_add(current_editor);
	});

	// add new item cancel event
	jQuery(document).on('click', '#menu_manager_popup #menu_manager_popup_container .add_item_inner_main .add_item_submit_row_wrapper button.button-secondary.amm_menu_add_cancel', function(){
		var obj = jQuery(this).closest('.amm_item_main_wrapper');
		obj.find('.add_mew_item_wrapper').addClass('amm_deactive');
		obj.find('.md_popup_main_wrapper').fadeIn();
		obj.find('.amm_header_main').fadeIn('slow');
		obj.find('p.button-controls').fadeIn('slow');
		obj.find('p.amm_list_of_page').fadeIn('slow');
		//obj.find('form.add_new_item_form').reset();

		obj.find('.add_item_details_left div.row input[type=text]').val('');
		obj.find('.add_item_details_left div.row input[type=checkbox]').removeAttr('checked');
		obj.find('.add_item_details_left div.row .amm_select').prop('selectedIndex',0);
	});

	// add new item cancel event
	jQuery(document).on('click', '#menu_manager_popup #menu_manager_popup_container .add_item_inner_main .add_item_submit_row_wrapper button.button-primary.amm_submit_post_for_add_item', function(){
		var obj = jQuery(this).closest('.add_item_inner_main');
		var this_obj = jQuery(this);
		var post_title = obj.find('.add_item_details_left div.row .amm_item_title').val();
		post_title = jQuery.trim(post_title);
		obj.find('.add_item_details_left div.row .amm_item_title').val(post_title);

		var post_slug = obj.find('.add_item_details_left div.row .amm_item_slug').val();
		post_slug = jQuery.trim(post_slug);
		obj.find('.add_item_details_left div.row .amm_item_slug').val(post_slug);

		var post_author = obj.find('.add_item_details_left div.row .amm_item_author option:selected').val();
		// var post_author_text = obj.find('.add_item_details_left div.row .amm_item_author option:selected').text();

		var post_content = tinyMCE.activeEditor.getContent();

		if (post_content.length === 0){
			post_content = obj.find('.amm_item_content_wrapper .amm-post-page-content.wp-editor-area').val();
		}

		var post_type = obj.find('.add_item_details_left div.row .add_item_post_type').val();
		var post_category = [];
		obj.find('.add_item_details_left div.row ul.amm_select li input.set_new_category:checked').each( function(){
			post_category.push(jQuery(this).val());
		});

		if( '' === post_title ){
			alert_md('Please enter title'); 
			return false;
		}
		obj.addClass('loading');
		var action_data;
		if(jQuery(this).hasClass('amm_page_edit')){
			//page edit
			var post_temp = obj.find('.add_item_details_left div.row .amm_item_template option:selected').val();
			// var post_temp_text = obj.find('.add_item_details_left div.row .amm_item_template option:selected').text();

			action_data = {
				'action': 'my_action_for_popup_add_new_post',
				'post_title' : post_title,
				'post_slug' : post_slug,
				'post_author' : post_author,
				'post_template' : post_temp,
				'item_post_type' : post_type,
				'item_content':post_content,
				'post_category': post_category,
				'security' : ajax_object.ajax_nonce,
			};

		}else{
			//post edit
			var post_cat = obj.find('.add_item_details_left div.row ul.amm_select .set_new_category:checked').serialize();
			action_data = {
				'action': 'my_action_for_popup_add_new_post',
				'post_title' : post_title,
				'post_slug' : post_slug,
				'post_author' : post_author,
				'post_category' : post_cat,
				'item_post_type' : post_type,
				'item_content':post_content,
				'security' : ajax_object.ajax_nonce,
			};
		}

		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: action_data,
			success:function( response ) {
				var finaloutput = jQuery.parseJSON(response);

				if( finaloutput.sucess ){
					if( finaloutput.post ){
						alert_md('Post'+finaloutput.sucess);
					}else if( finaloutput.page ){
						alert_md('Page'+finaloutput.sucess);
					}
					obj.closest('.amm_item_main_wrapper').find('.md_popup_main_wrapper ul.amm_popup_header').prepend(finaloutput.new_post_html);
					obj.closest('.amm_item_main_wrapper').find('.md_popup_main_wrapper ul.amm_popup_header li.no_record').remove();
					obj.find('.add_item_details_left div.row input[type=text]').val('');
					obj.find('.add_item_details_left div.row input[type=checkbox]').removeAttr('checked');
					obj.find('.add_item_details_left div.row .amm_select').prop('selectedIndex',0);
					obj.find('.amm_item_content_wrapper .amm-post-page-content.wp-editor-area').val('');
					tinyMCE.activeEditor.setContent('');
					this_obj.closest('.add_item_submit_row_wrapper').find('.amm_menu_add_cancel').trigger( 'click' );
					obj.removeClass('loading');
				}else if(finaloutput.error){
					alert_md(finaloutput.error);
					obj.removeClass('loading');
				}
			}
		});
	});

	//popup accordation menu option click
	jQuery(document).on('click', '#menu_manager_popup #menu_manager_popup_container #nav-menu-meta .outer-border li.accordion-section', function(){
		jQuery(this).closest('#side-sortables').find('ul.outer-border li.control-section').removeClass('accordation_css');
		jQuery(this).closest('#side-sortables').find('ul.outer-border li.control-section').removeClass('accordion-section');
		jQuery(this).closest('#side-sortables').find('ul.outer-border li.control-section').addClass('accordion-section');
		jQuery(this).addClass('accordation_css');
		jQuery(this).removeClass('accordion-section');
	});
	//#menu_manager_popup #menu_manager_popup_container #nav-menu-meta .outer-border li.accordion-section
	var fist_li_of_popup_item = jQuery('#menu_manager_popup #menu_manager_popup_container #nav-menu-meta ul.outer-border li.accordion-section').first();
	fist_li_of_popup_item.closest('#side-sortables').find('ul.outer-border li.control-section').removeClass('accordion-section');
	fist_li_of_popup_item.closest('#side-sortables').find('ul.outer-border li.control-section').addClass('accordion-section');
	fist_li_of_popup_item.addClass('accordation_css');
	fist_li_of_popup_item.removeClass('accordion-section');
	fist_li_of_popup_item.find('.accordion-section-content').show();

	function amm_add_tinymce_widget_add(tinymce_selector){
		tinymce.init({
			// selector: tinymce_selector,

			theme:'modern',
			skin:'lightgray',
			language:'en',

			formats:{
				alignleft: [
				{selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'left'}},
				{selector: 'img,table,dl.wp-caption', classes: 'alignleft'}
				],
				aligncenter: [
				{selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'center'}},
				{selector: 'img,table,dl.wp-caption', classes: 'aligncenter'}
				],
				alignright: [
				{selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'right'}},
				{selector: 'img,table,dl.wp-caption', classes: 'alignright'}
				],
				strikethrough: {inline: 'del'}
			},
			relative_urls:false,
			remove_script_host:false,
			convert_urls:false,
			browser_spellcheck:true,
			fix_list_elements:true,
			entities:'38,amp,60,lt,62,gt',
			entity_encoding:'raw',
			keep_styles:false,
			paste_webkit_styles:'font-weight font-style color',
			preview_styles:'font-family font-size font-weight font-style text-decoration text-transform',
			wpeditimage_disable_captions:false,
			wpeditimage_html5_captions:true,
			plugins:'charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview',
			selector:'#' + tinymce_selector,
			resize:'vertical',
			menubar:false,
			wpautop:true,
			indent:false,
			toolbar1:'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,wp_more,spellchecker,dfw,',
			toolbar2:'formatselect,underline,alignjustify,forecolor,removeformat,outdent,indent',
			toolbar3:'',
			toolbar4:'',
			tabfocus_elements:':prev,:next',
		});
		tinymce.execCommand('mceAddEditor', false, tinymce_selector);
		tinymce.settings = tinyMCEPreInit.mceInit.content;
		tinymce.execCommand('mceAddControl', false, tinymce_selector);
	}

	/*********************/

	jQuery('.amm_post_perpage').on('change', function(){

		var page_per_post = jQuery(this).val();
		var amm_option_key = jQuery(this).attr('data_post_per_page');

		var pagination_obj =  jQuery(this).attr('data_pagination');

		jQuery.ajax({
			type : 'post',
			url : ajaxurl,
			data : {
				action: 'my_action_for_add_pagination_limit','page_per_post' :page_per_post,'amm_option_key':amm_option_key,
			},
			success: function(data_val) {
				var finaloutput = jQuery.parseJSON(data_val);

				if(finaloutput.sucess){
					// make new pagination
					amm_pagination_html(pagination_obj,'total_page',page_per_post);
					amm_meni_item_filter(jQuery('#'+pagination_obj).closest('.amm_item_main_wrapper'),1,'change_page');
					jQuery('#'+pagination_obj).closest('ul.amm_pagination').find('li a').removeClass('active');

				}else if(finaloutput.error){
					alert_md('Please Try Again','Fail..');
				}
			}
		});
	});
	/** Select all checkbox JS */
	jQuery(document).on('click', '.list-controls .amm-check-opt', function(e){
		e.preventDefault();
		if( jQuery( this ).hasClass('select-all') ){
			jQuery('.list-controls .amm-check-opt.select-all').hide();
			jQuery(this).parents('li').find('ul.categorychecklist .menu-item-checkbox').each(function(){
				jQuery(this).prop('checked',true);
			});
			jQuery('.list-controls .amm-check-opt.deselect-all').show();
		}else{
			jQuery('.list-controls .amm-check-opt.deselect-all').hide();
			jQuery(this).parents('li').find('ul.categorychecklist .menu-item-checkbox').each(function(){
				jQuery(this).prop('checked',false);
			});
			jQuery('.list-controls .amm-check-opt.select-all').show();
		}
		
	});
});