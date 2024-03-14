/*
amm_plugin Name: Advance Menu Manager
Plugin URI : wwww.multidots.com
Author : Multidots Solutions Pvt. Ltd.
*/

;(function($) {
        
	$.amm_menu_manager = function(el) {
		var amm_plugin = this;

		var amm_event = function() {

			amm_plugin.el = el;

			amm_plugin.$menu = $(el);
			amm_plugin.$menuItems = amm_plugin.$menu.find('li.menu-item');

			amm_plugin.$menuItems.each(function(){
				amm_menu_item(jQuery(this));
			});

			//Drag
			amm_plugin.$menu.on('mousedown', 'li.menu-item', function(){

			});

			//Drop
			amm_plugin.$menu.on( 'mouseup', 'li.menu-item', function(){

				var $dropped_item = jQuery( this );
				var $startParents = amm_parents_item( $dropped_item );
				setTimeout( function(){
					amm_fresh_item( 'parents' , $dropped_item , $startParents );
				}, 400);

				//menu object change status
				menu_object_change_status = 'yes';
			});

			amm_plugin.itemcount = 0;

			//ADM menu jQuery event's

			jQuery('.submit-add-to-menu').click(function(){
				//admin click the 'Add Item' button
				
				amm_plugin.itemcount = amm_plugin.$menuItems.size();
				checking_for_new_menu_item();

			});
			jQuery(document).on('click', '.item-controls .delete_node', function() {
				var del_item_obj = jQuery(this).closest('li');
				//var del_result = confirm("You are sure to permanently delete this menu item. \n 'Cancel' to stop 'OK' to delete");
				confirm_md('Are You sure you want to permanently delete this menu item? \n\'Cancel\' to stop \'OK\' to delete','Delete Confirmation', function(del_result_con){
					if (del_result_con) {
						//change menu depth
						var del_item_depth = amm_menu_item_depth(del_item_obj);
						var del_item_prent_id = del_item_obj.find('.menu-item-data-parent-id').val();
						var del_item_id = del_item_obj.find('.menu-item-data-db-id').val();

						var loop_flag = 1; //looping only chile item
						//menu-item-depth
						del_item_obj.nextUntil(function(){
							if( !jQuery(this).hasClass('menu-item-depth-0') && loop_flag === 1 && !jQuery(this).hasClass('menu-item-depth-'+del_item_depth)){
								var item_get_depth = amm_menu_item_depth(jQuery(this));
								var item_parent_id = jQuery(this).find('.menu-item-settings .menu-item-data-parent-id').val();
								//change menu depth related to deleted parent menu`
								jQuery(this).removeClass('menu-item-depth-'+item_get_depth);
								jQuery(this).addClass('menu-item-depth-'+(item_get_depth - 1));

								if( del_item_id === item_parent_id ){
									//check delete menu id to chile menu parent id if it's same than replace with delete menu parent id
									jQuery(this).find('.menu-item-settings .menu-item-data-parent-id').val(del_item_prent_id);
								}
								//if parent menu item delete then all chiled menu desplayd
								jQuery(this).removeClass('item-hide');
							}else{
								loop_flag = 2;
							}
						});
						//end change menu depth logic
						var old_del_nodes = '';
						var del_node_add = del_item_id;
						old_del_nodes = jQuery( '#delete_menu_items' ).val();
						//del_node_add = del_item_obj.attr( 'data-attr-menu-item' );
						jQuery( '#delete_menu_items' ).val( del_node_add + ',' + old_del_nodes );
						//jQuery(this).closest('li').slideUp(500);
						del_item_obj.closest('li').remove();

						var $deleted_item = del_item_obj.parents( 'li.menu-item' );
						var $startParents = amm_parents_item( $deleted_item );
						amm_fresh_item();
						setTimeout(function(){ amm_fresh_item( 'delete' , $deleted_item , $startParents ); }, 500);
					}
				});
			});
			jQuery(document).on('click', '.item-controls span.menu_sub_details', function() {
				jQuery(this).toggleClass('active');
				var li_item = jQuery(this).closest( 'li' ).find('.menu-item-settings');
				//li_item.slideToggle('show');
				li_item.toggleClass('item-show');
				$item = jQuery(this).parents('li.menu-item');

			});
			
			/********* Bulk-delete *********/
			var bulk_del_array = [];
			jQuery('#bulk-delete-wrapper .bulk-delete-btn .bulk-del-checkbox').change(function() {

				if(jQuery(this).prop('checked') === false) {
					jQuery('div#bulk-delete-wrapper span.bulk-btn').removeClass('active');
					jQuery('ul#menu-to-edit li .delete_node').show('x');
					jQuery('ul#menu-to-edit li .bulk-delx-items_wrap').remove();
					jQuery('#amm-bulk-delete-atension').removeClass('active');
					bulk_del_array = [];
				} else {
					jQuery('div#bulk-delete-wrapper span.bulk-btn').addClass('active');
					jQuery('ul#menu-to-edit li .delete_node').after('<span class="bulk-delx-items_wrap"><input type="checkbox" name="bulk-delx-items[]" class="bulk-del-x"></span>');
					jQuery('ul#menu-to-edit li .delete_node').hide();
					jQuery('#amm-bulk-delete-atension').addClass('active');
				}
				jQuery('div#amm_back_to_content span.back-to-menu').trigger( 'click' );
			});

			jQuery(document).on('change','ul#menu-to-edit li .bulk-del-x',function(){

				var del_item_obj_val = jQuery(this).closest('li').attr('ID');
				if ($.inArray(del_item_obj_val, bulk_del_array) !== -1)	{
					bulk_del_array.splice( $.inArray(del_item_obj_val, bulk_del_array), 1 );
				}else{
					bulk_del_array.push(del_item_obj_val);
				}
			});

			jQuery('#bulk-delete-wrapper span.bulk-btn #selected_bulk_menuitems_delete').click(function(){
				if(jQuery.isEmptyObject(bulk_del_array)){
					alert_md('Please select at least one menu items');
				}else{
					confirm_md('Are You sure you want to permanently delete Selected menu item? \n\n\'Cancel\' to stop \'OK\' to delete','Delete Confirmation', function(selected_del_items){
						if(selected_del_items){
							jQuery.each( bulk_del_array, function( key, value ) {
								Delete_all_bulk_menu(value);
							});
							//Save menu changes
							jQuery('#menu_container .submit-btn_save_menu #amm_save_menu_header').trigger( 'click' );
						}
					});
				}
			});

			jQuery('#bulk-delete-wrapper span.bulk-btn #amm_all_bulk_menuitems').click(function(){
				confirm_md('Are You sure you want to delete all menu item? \n\n \'Cancel\' to stop \'OK\' to delete','Delete Confirmation', function(selected_del_items){
					if(selected_del_items){
						jQuery( '#menu-to-edit li' ).each(function() {
							Delete_all_bulk_menu(jQuery(this).attr('ID'));
						});
						//Save menu changes
						jQuery('#menu_container .submit-btn_save_menu #amm_save_menu_header').trigger( 'click' );
					}
				});
			});
			/********* Bulk-delete *********/
		};

		var amm_menu_item = function($item){
			//Check for amm_menu_item_dom_obj
			var amm_menu_item_dom_obj = $item.find('.child_items');
			if(amm_menu_item_dom_obj.size() === 0){
				// if admin will added new menu item then we will create your menu item
				amm_menu_item_dom_obj = made_amm_menu_item_dom_obj($item);
			}

			var $kids = amm_children_item($item);
			var numkids = $kids.filter('.menu-item-depth-'+(amm_menu_item_depth($item)+1)).size();
			var amm_kids_num = $kids.size();

			// No change on chiled hierarchy
			if(amm_menu_item_dom_obj.hasClass('child_items-counter_'+numkids) && amm_menu_item_dom_obj.hasClass('amm_all_deep_chlid_count_'+amm_kids_num)){
				return;
			}

			amm_menu_item_dom_obj.removeClass();
			amm_menu_item_dom_obj.addClass('child_items');
			amm_menu_item_dom_obj.addClass('child_items-counter_'+numkids);
			amm_menu_item_dom_obj.addClass('amm_all_deep_chlid_count_'+amm_kids_num);
			amm_menu_item_dom_obj.text(numkids).attr('title', amm_kids_num);

			amm_draw_highlights($item, $kids);

			if(0 >= numkids ){
				amm_menu_item_dom_obj.closest( 'span.my-menu-controls-groups').find('.amm_highlighter').hide();
				amm_menu_item_dom_obj.closest( 'span.my-menu-controls-groups').find('.block_hide_show').hide();
				amm_menu_item_dom_obj.hide();
			}else{
				if(!$item.find('.my-menu-controls-groups .block_hide_show').hasClass('chiled-hide')){
					$item.find('.my-menu-controls-groups .block_hide_show').addClass('chiled-hide');
				}
				amm_menu_item_dom_obj.closest( 'span.my-menu-controls-groups').find('.block_hide_show').show();
				amm_menu_item_dom_obj.show();
			}
			amm_menu_item_dom_obj.hide();
		};

		var made_amm_menu_item_dom_obj = function($item){

			var custom_html_added ='<span class="my-menu-controls">'+
			'<span class="my-menu-controls-groups">'+
			'<span class="click block_hide_show chiled-hide" style="display: none;"></span>'+
			'<span class="child_items child_items-counter_0 amm_all_deep_chlid_count_0" title="0" style="display: none;">0</span>'+
			'<span class="amm_highlighter" style="display: none;"> </span>'+
			'</span>'+
			'<span class="add_menu_item_in_nav_menu" title="Add new menu item below this menu item">&nbsp;</span>'+
			'</span>';
			var menu_item_html = $item;

			var item_parent_id = 0;
			var item_parent_depth = 0;
			var menu_id_show_class = '';

			//data-depth of menu
			if('none' !== curent_menu_add_obj ){
				$item.remove();
				// check admin can added menu item after chield menu item
				if(append_menu_item_same_level){
					append_menu_item_same_level.after(menu_item_html);
				}else{
					curent_menu_add_obj.after(menu_item_html);
				}

				item_parent_id = curent_menu_add_obj.find('.menu-item-data-parent-id').val();
				item_parent_depth = amm_menu_item_depth(curent_menu_add_obj);

				if(curent_menu_add_obj.find('.item-controls .view_menu_id').hasClass('item-show')) {
					menu_id_show_class = 'item-show';
				}
			}
			var $amm_menu_item_added;
			if($item.parent().hasClass( 'menu-manager-plus-menu-wrapper')){

				$item.find('.item-controls a').remove();
				$item.find('.item-controls .item-order').remove();
				$item.find('.item-controls .item-type').addClass('menu_item_type');
				var item_type_for_edit = $item.find('.item-controls .item-type').text();
				item_type_for_edit = item_type_for_edit.toLowerCase();
				$item.find('.item-controls .item-type').removeClass('item-type');
				if('post' === item_type_for_edit || 'page' === item_type_for_edit ){
					$item.find('.menu-item-handle .item-title .menu-item-title').append('<span class="amm_main_menu_item_edit" title="Edit this item">&nbsp;</span>');
				}

				$item.find('.item-controls').prepend('<span class="view_menu_id '+menu_id_show_class+'" >#'+$item.attr('id')+'</span>');

				$item.find('.item-controls').append('<span class="menu_sub_details " title="View Attributes">&nbsp;</span>');
				$item.find('.item-controls').append('<span data-attr-menu-item='+$item.attr('id')+' class="delete_node" title="Delete this item">X</span>');

				//menu item Seeting html
				$item.find('.menu-item-settings').addClass('menu-manager-plus-setting');

				$item.find('.menu-item-settings .menu-item-actions a.item-delete.submitdelete').addClass('menu_manager-plus-setting-delete');
				$item.find('.menu-item-settings .menu-item-actions a.item-cancel.submitcancel').addClass('menu_manager-plus-setting-cancel');
				$item.find('.menu-item-settings .field-move').remove();

				$item.attr('data-depth', item_parent_depth);
				$item.removeClass('menu-item-depth-0');
				$item.addClass('menu-item-depth-'+item_parent_depth);
				$item.find('.menu-item-settings .menu-item-data-parent-id').val(item_parent_id);

				$amm_menu_item_added = $(custom_html_added);

				$item.find('.menu-item-bar').append('<span class="my-menu-controls">'+
				'<span class="my-menu-controls-groups">'+
				'<span class="click block_hide_show chiled-hide" style="display: none;"></span>'+
				'<span class="child_items child_items-counter_0 amm_all_deep_chlid_count_0" title="0" style="display: none;">0</span>'+
				'<span class="amm_highlighter" style="display: none;"> </span>'+
				'</span>'+
				'<span class="add_menu_item_in_nav_menu" title="Add new menu item below this menu item">&nbsp;</span>'+
				'</span>');
				jQuery('#menu_manager_popup').fadeOut();
			}

			// menu item added on checkbox selection order
			curent_menu_add_obj = $(menu_item_html);

			return $amm_menu_item_added;
		};

		var amm_children_item = function($item){
			var depth = amm_menu_item_depth($item);
			var selector = '';

			while(depth >= 0){
				selector +=  '.menu-item-depth-'+depth;
				if(depth > 0) {
					selector+= ', ';
				}
				depth--;
			}
			return $item.nextUntil(selector);

		};

		var amm_parents_item = function( $item ){

			var depth = amm_menu_item_depth( $item );

			if( depth === 0 ) {
				return $item;
			}

			var selector = '';

			depth = depth-1;
			while(depth >= 0){
				selector +=  '.menu-item-depth-'+depth;
				if(depth > 0) {
					selector+= ', ';
				}
				depth--;
			}
			var $parents = $item;
			var $prev = $item.prev( '.menu-item' );
			while( $prev.size() > 0 ){
				if( $prev.is( selector ) ){
					$parents = $parents.add( $prev );
					if( $prev.hasClass( 'menu-item-depth-0' ) ){
						break;
					}
				}
				$prev = $prev.prev( '.menu-item' );
			}
			return $parents;
		};

		var amm_menu_item_depth = function($item){

			var depth = 0;
			if($item.hasClass('menu-item-depth-0')) { depth = 0; }
			else if($item.hasClass('menu-item-depth-1')) { depth = 1; }
			else if($item.hasClass('menu-item-depth-2')) { depth = 2; }
			else if($item.hasClass('menu-item-depth-3')) { depth = 3; }
			else if($item.hasClass('menu-item-depth-4')) { depth = 4; }
			else if($item.hasClass('menu-item-depth-5')) { depth = 5; }
			else if($item.hasClass('menu-item-depth-6')) { depth = 6; }
			else if($item.hasClass('menu-item-depth-7')) { depth = 7; }
			else if($item.hasClass('menu-item-depth-8')) { depth = 8; }

			return depth;
		};

		var amm_draw_highlights = function($item, $kids){
			var $amm_parent_highlighter = $item.find('.amm_block_highlight');

			var top = $item.position().top;
			var height = $item.outerHeight() + 9;

			var $last = $kids.last();
			if($last.size() > 0){
				var bottom = $last.position().top + $last.outerHeight();
				height = bottom - top + 7;
			}

			if($amm_parent_highlighter.size() > 0){
				$amm_parent_highlighter.css({
				'height': height+'px'
				});
			}
			else{
				$amm_parent_highlighter = $('<div class="amm_block_highlight">');

				$amm_parent_highlighter.css({
				'top'	: '-7px',
				'height': height+'px'
				});
				$amm_parent_highlighter.hide();
				$item.append($amm_parent_highlighter);
			}
		};

		var amm_fresh_item = function( set , $item , $items ){

			switch( set ){
				case 'parents':
				$items = $items.add( amm_parents_item( $item ) );
				break;
				case 'delete':
				break;
				default:
				$items = amm_plugin.$menuItems;
			}

			$items.each( function(){
				amm_menu_item( jQuery(this) );
			});
		};

		function checking_for_new_menu_item(){

			var $itemGroup = $('#menu-to-edit li.menu-item');

			$itemGroup.each(function(){
				amm_menu_item(jQuery(this));
			});

			setTimeout(function(){
				checking_for_new_menu_item();
			}, 500);
		}
		
		function Delete_all_bulk_menu(objects){
			var del_item_obj = jQuery('#'+objects);
			//change menu depth
			var del_item_depth = amm_menu_item_depth(del_item_obj);
			var del_item_prent_id = del_item_obj.find('.menu-item-data-parent-id').val();
			var del_item_id = del_item_obj.find('.menu-item-data-db-id').val();

			var loop_flag = 1; //looping only chile item
			//menu-item-depth
			del_item_obj.nextUntil(function(){
				if( !jQuery(this).hasClass('menu-item-depth-0') && loop_flag === 1 && !jQuery(this).hasClass('menu-item-depth-'+del_item_depth)){
					var item_get_depth = amm_menu_item_depth(jQuery(this));
					var item_parent_id = jQuery(this).find('.menu-item-settings .menu-item-data-parent-id').val();
					//change menu depth related to deleted parent menu`
					jQuery(this).removeClass('menu-item-depth-'+item_get_depth);
					jQuery(this).addClass('menu-item-depth-'+(item_get_depth - 1));

					if(del_item_id === item_parent_id){
						//check delete menu id to chile menu parent id if it's same than replace with delete menu parent id
						jQuery(this).find('.menu-item-settings .menu-item-data-parent-id').val(del_item_prent_id);
					}
					//if parent menu item delete then all chiled menu desplayd
					jQuery(this).removeClass('item-hide');
				}else{
					loop_flag =2;
				}
			});
			//end change menu depth logic
			var old_del_nodes = '';
			var del_node_add = del_item_id;
			old_del_nodes = jQuery( '#delete_menu_items' ).val();
			//del_node_add = del_item_obj.attr( 'data-attr-menu-item' );
			jQuery( '#delete_menu_items' ).val( del_node_add + ',' + old_del_nodes );
			//jQuery(this).closest('li').slideUp(500);
			del_item_obj.closest('li').remove();

			var $deleted_item = del_item_obj.parents( 'li.menu-item' );
			var $startParents = amm_parents_item( $deleted_item );
			amm_fresh_item();
			setTimeout(function(){ amm_fresh_item( 'delete' , $deleted_item , $startParents ); }, 500);
		}
		amm_event();
	};

	$.fn.amm_menu_manager = function(options) {

		return this.each(function() {
			if (undefined === jQuery(this).data('amm_menu_manager')) {
				var amm_menu_manager = new $.amm_menu_manager(this, options);
				jQuery(this).data('amm_menu_manager', amm_menu_manager);
			}
		});
	};
})(jQuery);

if(typeof menus === 'undefined'){
	var menus = jQuery('#menu-to-edit');
}

var curent_menu_add_obj = 'none';
var append_menu_item_same_level = '';
//** Get current chiled/depth

//tinymce objects none
var tinymce_obj = '';
var tinymce_obj_click_count = 0;

function get_depth_of_menu_item ( $menu_item ) {
	var depth_menu_item = 0;
	if($menu_item.hasClass('menu-item-depth-0')) { depth_menu_item = 0; }
	else if($menu_item.hasClass('menu-item-depth-1')) { depth_menu_item = 1; }
	else if($menu_item.hasClass('menu-item-depth-2')) { depth_menu_item = 2; }
	else if($menu_item.hasClass('menu-item-depth-3')) { depth_menu_item = 3; }
	else if($menu_item.hasClass('menu-item-depth-4')) { depth_menu_item = 4; }
	else if($menu_item.hasClass('menu-item-depth-5')) { depth_menu_item = 5; }
	else if($menu_item.hasClass('menu-item-depth-6')) { depth_menu_item = 6; }
	else if($menu_item.hasClass('menu-item-depth-7')) { depth_menu_item = 7; }
	else if($menu_item.hasClass('menu-item-depth-8')) { depth_menu_item = 8; }

	return depth_menu_item;
}

var menu_object_change_status = 'none';
// var bulk_del_array = [];

jQuery(document).ready( function() {

	jQuery('#md_amm_menu_form input[type=text], #md_amm_menu_form ul#menu-to-edit li input[type=checkbox]').change(function(){
		menu_object_change_status = 'yes';
	});

	jQuery('#md_amm_menu_form').submit(function(){
		window.onbeforeunload = null;
		if('yes' === menu_object_change_status){
			jQuery(this).append('<input type="hidden" name="add_to_revision" value="add revision" >');
		}
	});

	var $menu = jQuery('#menu-to-edit');

	if($menu.size() === 0) {
		return;
	}

	$menu.amm_menu_manager();
	// var amm_obj = $menu.data('amm_menu_manager');
	jQuery('.add-new-menu-action_custom').click(function(){
		jQuery('#custom-new-menu-name').val('');
		jQuery('#manage-menus_add_new_menu').slideToggle();
		//event.stopPropagation();
	});
	jQuery(document).on('keypress','#custom-new-menu-name',function(e){
		//jQuery('#custom-new-menu-name').live("keypress", function(e) {
		if (e.keyCode === 13) {
			jQuery('#save_menu_custom').trigger( 'click' );
		}
	});

	jQuery(document).on('click','.amm_ec_item', function(){
		jQuery(this).parent().toggleClass('active');
	});

	jQuery('#save_menu_custom').on('click',function(){
		var new_menu_name = jQuery('#custom-new-menu-name').val();
		if ( new_menu_name === '' ) {
			jQuery('#custom-new-menu-name').css('box-shadow','0px 0px 2px rgba(255, 0, 0, 0.8)');
			jQuery('#custom-new-menu-name').css('border-color','#313335');
		} else {
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.ajax({
				url: ajaxurl,
				type:'POST',
				data: {
				'action': 'my_action_create_menu_ajax',
				'security': ajax_object.ajax_nonce,
				'new_menu_name': new_menu_name
				},
				success:function(response) {
					if( parseInt( response ) === 1 ) {
						window.location.reload();
					}else if( parseInt( response ) === 2 ){
						alert_md('"'+new_menu_name +'"' + ' menu is already registered');
					}
				}
			});
		}
	});

	//duplicate menu ajax call
	jQuery('#amm_duplicate_menu').on('click', function(){
		if (confirm('Are you sure want to duplicate menu?')) {
			var menuid = jQuery(this).attr('data-menu-id');
			var menuname = jQuery(this).attr('data-menu-name');
			jQuery.ajax({
				url: ajaxurl,
				type:'POST',
				data: {
				'action': 'amm_duplicate_menu',
				'security': ajax_object.ajax_nonce,
				'menuid': menuid,
				'menuname':menuname
				},
				success:function(response) {
					if( '0' === response ){
						alert( 'Duplicate menu found.' );
					}else{
						window.location.reload();
					}
				}
			});
		}
	});

	/* =============================================================== */

	jQuery('#page_on_front').on('change', function(){
		var menu_id = jQuery('#page_on_front').val();
		if( 0 === menu_id ) {
			jQuery('#menu_list_id').slideUp().hide();
		}
	});

	jQuery('#amm_menu_delete').on('click', function(){
		var menu_id = jQuery('#page_on_front').val();
		confirm_md('You are about to permanently delete this menu? \n\'Cancel\' to stop \'Ok\' to delete','Delete Confirmation',
		function(result) {
			if (result) {
				jQuery('#munu_manager_plus_full_loading_wrapper').css('height',jQuery( document ).height());
				jQuery('#munu_manager_plus_full_loading_wrapper').fadeIn();
				//Logic to delete the item
				var data = {
					'action': 'my_action_delete_menu',
					'security': ajax_object.ajax_nonce,
					'delete_menu_id': menu_id
				};
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					if(response === '1' ) {
						alert_md(' Menu deleted successfully ');
						window.location.reload();
					}else{
						jQuery('#munu_manager_plus_full_loading_wrapper').fadeOut();
						alert_md('Please try again later');
					}
				});
			}
		});
	});

	/* Count total menu items are present in current menu navigation */
	// var $total_elements = jQuery('#menu-to-edit').find('li.menu-item');
	// var menu_item='';
	// var count=0;

	/**
	* toggle_plus_action method
	* This function is used to hide all child elements of the parent menu items
	* @Version 1.0.0
	* @Author Multiodots
	*/
	jQuery( '#toggle_plus_action' ).click( function() {
		var all_child_elements = jQuery('#menu-to-edit').find('.menu-item:not(.menu-item-depth-0)');
		all_child_elements.removeClass('item-hide');
		jQuery( '#menu-to-edit li' ).each(function() {
			jQuery(this).find('span.click.block_hide_show').addClass('chiled-hide');
			//menu setting section closed
			jQuery(this).find('.menu-item-settings.menu-manager-plus-setting').removeClass('item-show');
			jQuery(this).find('.menu_sub_details').removeClass('active');
		});
		jQuery(this).css('font-weight','bold');
		//jQuery('#toggle_minus_action').css("display", "block")
		jQuery('#toggle_minus_action').css('font-weight', 'normal');
		jQuery(this).hide();
		jQuery('#toggle_minus_action').show();
	});

	/**
	* toggle_minus_action method
	* This function is used to show all child elements of the parent menu items
	* @Version 1.0.0
	* @Author Multiodots
	*/
	jQuery( '#toggle_minus_action' ).click( function() {
		var all_child_elements = jQuery('#menu-to-edit').find('.menu-item:not(.menu-item-depth-0)');
		all_child_elements.addClass('item-hide');
		jQuery('#menu-to-edit li').find('span.click.block_hide_show').removeClass('chiled-hide');
		//jQuery(this).hide();
		//jQuery('#toggle_plus_action').css("display", "block");
		jQuery(this).css('font-weight', 'bold');
		jQuery('#toggle_plus_action').css('font-weight', 'normal');

		jQuery( '#menu-to-edit li' ).each(function() {
			jQuery(this).find('span.click.block_hide_show').removeClass('chiled-hide');
			//menu setting section closed
			jQuery(this).find('.menu-item-settings.menu-manager-plus-setting').removeClass('item-show');
			jQuery(this).find('.menu_sub_details').removeClass('active');
		});
		jQuery(this).hide();
		jQuery('#toggle_plus_action').show();
	});

	/*
	* Add + and - controls to block for expand and collapse the menu items start
	*/

	jQuery(document).on('click', 'span.click.block_hide_show', function() {

		var parent_li_id = jQuery(this).closest('li').attr('id');
		var hide_controlls = '';

		//only same level menu item hided
		var same_level_depth_obj = jQuery(this).closest('li').next();
		same_level_depth_obj = get_depth_of_menu_item(same_level_depth_obj);
		same_level_depth_obj =  'menu-item-depth-'+same_level_depth_obj;

		//var element_depth = parseInt(jQuery('#'+parent_li_id).attr('data-depth'));
		var element_depth = parseInt(get_depth_of_menu_item(jQuery(this).closest('li')));
		while(element_depth >= 0){
			hide_controlls +=  '.menu-item-depth-'+element_depth;
			if(element_depth > 0) {
				hide_controlls += ', ';
			}
			element_depth--;
		}

		$array = jQuery('#'+parent_li_id).nextUntil(hide_controlls);
		jQuery( this ).toggleClass('chiled-hide');

		jQuery.each( $array, function() {
			jQuery(this).find('.menu-item-settings.menu-manager-plus-setting').removeClass('item-show');
		});

		if(jQuery( this ).hasClass('chiled-hide')){

			//same level item will hide
			jQuery.each( $array, function() {
				if(jQuery(this).hasClass(same_level_depth_obj)) {
					jQuery(this).removeClass('item-hide');
					jQuery(this).find('.my-menu-controls-groups span.click.block_hide_show').removeClass('chiled-hide');
				}
			});
		}else{
			jQuery.each( $array, function() {
				jQuery(this).addClass('item-hide');
				jQuery(this).find('.my-menu-controls-groups span.click.block_hide_show').addClass('chiled-hide');
			});
		}
	});

	jQuery(document).on('click', '.add_first_menu_item', function() {
		//open popup
		curent_menu_add_obj = 'none';
		jQuery('#menu_manager_popup').css('height',jQuery( document ).height());
		jQuery('#menu_manager_popup').css('padding-top',jQuery(window).scrollTop() - 90);
		jQuery('#menu_manager_popup').fadeIn();
		menu_object_change_status = 'yes';
		/**amm**/
		jQuery('.nav-menu-meta ul li .menu-item-checkbox').removeAttr('checked');
		jQuery('.nav-menu-meta ul li .button-controls .add-to-menu .spinner').removeAttr('style');
		
		//for tinymca widget cancle all add new page/post event.
		hide_page_post_add_html();

	});

	jQuery(document).on('click', '.add_menu_item_in_nav_menu', function() {
		//open popup
		/**amm**/
		tinymce.remove();
		jQuery('.nav-menu-meta ul li .menu-item-checkbox').removeAttr('checked');
		jQuery('.nav-menu-meta ul li .button-controls .add-to-menu .spinner').removeAttr('style');

		jQuery('#menu_manager_popup').css('height',jQuery( document ).height());
		jQuery('#menu_manager_popup').css('padding-top',jQuery(window).scrollTop() - 300);
		jQuery('#menu_manager_popup').fadeIn();
		jQuery('#menu_manager_popup_main-wrapper').fadeIn();
		jQuery('#menu_manager_popup_container #amm_wp_post_edit.wp-post-edit').remove();

		curent_menu_add_obj = jQuery(this).parents('li.menu-item');

		// if admin can added menu item as same level of menu( added new menu item after chiled menu)
		// first of all clear sub menu obj than check admin added menu as same level with chiled menu ot not
		append_menu_item_same_level ='';
		var loop_flag = 1;
		var c_item_get_depth = get_depth_of_menu_item(curent_menu_add_obj);
		curent_menu_add_obj.nextUntil(function(){
			var currrent_item_depth = get_depth_of_menu_item(jQuery(this));
			if( !jQuery(this).hasClass('menu-item-depth-0') && loop_flag === 1 && !jQuery(this).hasClass('menu-item-depth-'+c_item_get_depth) && currrent_item_depth > c_item_get_depth ){
				append_menu_item_same_level = jQuery(this);
			}else{
				loop_flag = 2;
			}
		});
		//for tinymca widget cancle all add new page/post event.
		hide_page_post_add_html();
	});

	// menu setting inner delete menu item click event

	jQuery(document).on('click', 'li .menu-item-settings a.menu_manager-plus-setting-delete', function() {
		jQuery(this).closest( 'li' ).find('.delete_node').trigger( 'click' );
		return false;
	});

	jQuery(document).on('click', 'li .menu-item-settings a.menu_manager-plus-setting-cancel', function() {
		jQuery(this).closest( 'li' ).find('.item-controls span.menu_sub_details').trigger( 'click' );
		return false;
	});

	// heightlighted feature

	jQuery('ul.menu-manager-plus-menu-wrapper li .menu-item-bar').hover(function(){
		var $c_item = jQuery(this).closest( 'li' );

		if(!$c_item.find('.my-menu-controls-groups span.click.block_hide_show').hasClass('chiled-hide')){
			return false;
		}
		var item_buffer = 9;
		var Item_height = $c_item.outerHeight() + item_buffer;

		var loop_flag = 1;
		var item_get_depth = get_depth_of_menu_item($c_item);
		var counter = 1;

		$c_item.nextUntil(function(){
			var currrent_item_depth = get_depth_of_menu_item(jQuery(this));
			if( !jQuery(this).hasClass('menu-item-depth-0') && loop_flag === 1 && !jQuery(this).hasClass('menu-item-depth-'+item_get_depth) && currrent_item_depth > item_get_depth ){
				if(!jQuery( this ).hasClass('item-hide')){
					counter++;
					Item_height += jQuery( this ).outerHeight();
					Item_height += item_buffer;
				}
			}else{
				loop_flag = 2;
			}
		});
		$c_item.find('.amm_block_highlight').css('height',Item_height);
	}, function(){
		jQuery(this).closest( 'li' ).find('.amm_block_highlight').fadeOut();
	});

	// add new page popup open
	jQuery(document).on('click', '#menu_manager_popup_container div#mm_cancel', function() {
		jQuery('#menu_manager_popup').fadeOut();
	});

	//esc key press then add new page popup will closed
	jQuery(document).keyup(function(e) {
		if (e.keyCode === 27) { jQuery('#menu_manager_popup').fadeOut(); }
	});

	// Menu Id hideshow on click
	jQuery('#amm_menu_item_id').click(function(){
		jQuery(this).toggleClass('active');
		jQuery('.view_menu_id').toggleClass('item-show');
		if ( jQuery(this ).is( '.active' ) ) {				 
		    jQuery(this).text('Hide Menu Id');
			jQuery('.menu-item-handle .item-title').css('margin-right', '18em');			 
		}else{
			jQuery(this).text('Show Menu Id');
			jQuery('.menu-item-handle .item-title').css('margin-right', '13em');
		}
	});

	/*************************** menu revision ****************************************/
	//click on menu revision setting button
	jQuery('#amm_menu_revision #amm_menu_revision_status').change(function() {
		jQuery('div#amm_menu_revision span#amm_revision_loader').fadeIn();
		var revision_current_menu_id = jQuery('#amm_menu_revision #revision_current_menu_id').val();
		var amm_menu_revision_status = 'on';

		if(jQuery('#amm_menu_revision #amm_menu_revision_status').prop('checked') === false){
			//do something
			amm_menu_revision_status ='off';
		}

		jQuery.ajax({url: ajaxurl,type:'POST',data: {
			'action': 'my_action_for_menu_revision_setting',
			'security': ajax_object.ajax_nonce,
			'revision_menu_id':revision_current_menu_id,
			'revision_menu_status':amm_menu_revision_status
		},
		success:function(response) {
			jQuery('div#amm_menu_revision span#amm_revision_loader').fadeOut();
			if('error' === response){
				alert_md('Please try again later.','Menu Revision Settings');
			}else{
				jQuery('#amm_menu_revision .amm_menu_revision_content h3 span').removeAttr('class');
				if(amm_menu_revision_status === 'on'){
					alert_md('The Menu Revision Functionality has been enabled. Your revisions will be stored in the database and you shall be able to restore them.','Menu Revision Settings');
					jQuery('#amm_menu_revision .amm_menu_revision_content h3 span').addClass('Enabled');
					jQuery('#amm_menu_revision .amm_menu_revision_content h3 span').text('Enabled');
					jQuery('div#amm_menu_revision .revision_div_pro:nth-of-type(2)').show();
				}else{
					alert_md('The Menu Revision Functionality has been disabled. You will not be able to restore any menu revisions now.','Menu Revision Settings');
					jQuery('#amm_menu_revision .amm_menu_revision_content h3 span').addClass('Disabled');
					jQuery('#amm_menu_revision .amm_menu_revision_content h3 span').text('Disabled');
					jQuery('div#amm_menu_revision .revision_div_pro:nth-of-type(2)').hide();
				}
				
			}
		}
		});

	}); //end of menu revision click

	// comparision emenu_button click

	jQuery('.list_of_menu_revision li input[type="radio"][name="menu_revision_date"]').change(function() {
		var revision_date = jQuery('.list_of_menu_revision li input[type="radio"][name="menu_revision_date"]:checked').val();
		var revision_current_menu_id = jQuery('#amm_menu_revision #revision_current_menu_id').val();

		jQuery('#menu_revision_main_wrapper').animate({'opacity':'0.5'},500);
		jQuery('.amm_menu_revision_wrapper div#compare_revision').fadeIn();

		//wp_ajax_my_action_for_menu_revision_compare
		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: {
			'action': 'my_action_for_menu_revision_compare',
			'revision_date':revision_date,
			'revision_menu_id' : revision_current_menu_id,
			},
			success:function(response) {
				jQuery('#view_menu_reviion').html(response);
				jQuery('div#amm_menu_revision .view_menu_reviion_wrapper').fadeIn();
				jQuery('#amm_menu_revision div#page_bottom_revision_list').fadeIn();
				jQuery('#menu_revision_main_wrapper').animate({'opacity':'1'},500);
			}
		});
	});

	//menu restore revision click on the current menu
	jQuery('.amm_menu_revision_wrapper #compare_revision .save-menu-revision').click(function(){
		var revision_date = jQuery('.list_of_menu_revision li input[type="radio"][name="menu_revision_date"]:checked').val();
		var revision_date_mesg = jQuery('.list_of_menu_revision li input[type="radio"][name="menu_revision_date"]:checked').attr('title');
		var revision_current_menu_id = jQuery('#amm_menu_revision #revision_current_menu_id').val();

		confirm_md('Are you sure you want to Restore the ' + '<strong>' + revision_date_mesg + '</strong>' + ' menu Revision? ','Revision Restore Confirmation',
		function(resote_menu_item_result) {
			if (resote_menu_item_result) {
				jQuery('#munu_manager_plus_full_loading_wrapper').css('height',jQuery( document ).height());
				jQuery('#munu_manager_plus_full_loading_wrapper').fadeIn();
				jQuery.ajax({
					url: ajaxurl,
					type:'POST',
					data: {
					'action': 'my_action_for_menu_revision_restore',
					'security': ajax_object.ajax_nonce,
					'revision_date':revision_date,
					'revision_menu_id' : revision_current_menu_id,
					},
					success:function(response) {
						jQuery('#md_amm_menu_form').append('<input type="hidden" name="add_to_revision" value="add revision" >');
						jQuery('ul#menu-to-edit').html(response);
						jQuery('#md_amm_menu_form .submit-btn_save_menu .submit-btn_save_menu_span #amm_save_menu_header').trigger( 'click' );
					}
				});
			}
		});
	});

	//flush revision
	jQuery('.amm_menu_revision_wrapper #flush-revision .flush-menu-revision').click(function(){
		var revision_current_menu_id = jQuery('#amm_menu_revision #revision_current_menu_id').val();
		confirm_md('Are you sure you want to delete/flush all your revisions?','Flush Revision Confirmation?',
		function(resote_menu_item_result) {
			if (resote_menu_item_result) {
				jQuery('#munu_manager_plus_full_loading_wrapper').css('height',jQuery( document ).height());
				jQuery('#munu_manager_plus_full_loading_wrapper').fadeIn();
				//wp_ajax_my_action_for_menu_revision_flush
				jQuery.ajax({
					url: ajaxurl,
					type:'POST',
					data: {
					'action': 'my_action_for_menu_revision_flush',
					'security': ajax_object.ajax_nonce,
					'revision_menu_id' : revision_current_menu_id,
					},
					success:function(response) {
						jQuery('#munu_manager_plus_full_loading_wrapper').fadeOut();
						if('error' === response){
							alert_md('Please try again later.','Error');
						}else{
							window.location.reload();
						}
					}
				});
			}
		});
	});

	/*************************** End Menu revision ****************************************/

	/*************************** Back to top event ****************************************/
	
	jQuery('div#amm_back_to_content span.back-to-menu').click(function() { jQuery('html, body').animate({ scrollTop: jQuery('#nav-menus-frame').offset().top }, 1000);});
	jQuery('div#amm_back_to_content span.back-to-menu-revision ').click(function() { jQuery('html, body').animate({ scrollTop: jQuery('#amm_menu_revision').offset().top }, 1000);});
	jQuery('div#amm_back_to_content span.back-to-top').click(function() { jQuery('html, body').animate({ scrollTop: jQuery('#wpbody-content').offset().top }, 1000);});
	jQuery('div#amm_back_to_content span.back-to-locked_menu').click(function() { jQuery('html, body').animate({ scrollTop: jQuery('.lock-main h3.menu_subheadings.amm_ec_item').offset().top - 20 }, 1000);});

	jQuery(window).scroll( function(){
		if( jQuery(window).scrollTop() > 150 ){
			jQuery('div#amm_back_to_content').fadeIn(1000);
		}else{
			jQuery('div#amm_back_to_content').fadeOut(500);
		}
	});
	/********************************* Edit menu item ******************************************/
	jQuery(document).on({
		mouseenter: function () {
			jQuery(this).find('span.amm_main_menu_item_edit').css('display','inline-block');
		},
		mouseleave: function () {
			jQuery(this).find('span.amm_main_menu_item_edit').removeAttr('style');
		}
	},'ul.menu-manager-plus-menu-wrapper li .menu-item-handle .item-title');

	jQuery(document).on('click', 'ul.menu-manager-plus-menu-wrapper li .menu-item-handle .item-title .menu-item-title', function() {
		var post_id = jQuery(this).closest('li.menu-item').find('.menu-item-settings .menu-item-data-object-id').val();
		tinymce_obj_click_count++;	
		if(tinymce_obj_click_count === 1) {
		if(post_id){
			jQuery.ajax({
				url: ajaxurl,
				type:'POST',
				data: {
					'action': 'my_action_for_popup_menu_item_edit_front_end',
					'post_id' : post_id,
					'security' : ajax_object.ajax_nonce,
				},
				success:function( response ) {
					var finaloutput = jQuery.parseJSON(response);
					if( finaloutput.sucess ){
						tinymce.remove();
						tinymce_obj = finaloutput.wp_editor_selector;
						//open popup
						jQuery('#menu_manager_popup').css('height',jQuery( document ).height());
						jQuery('#menu_manager_popup').css('padding-top',jQuery(window).scrollTop() - 90);
						jQuery('#menu_manager_popup').fadeIn();
						jQuery('#menu_manager_popup_main-wrapper').fadeOut();
						jQuery('#menu_manager_popup_container #amm_wp_post_edit.wp-post-edit').remove();

						jQuery('#menu_manager_popup_container').append('<div id="amm_wp_post_edit" class="wp-post-edit">'+finaloutput.post_html+'</div>');

						tinymce.init({
							// selector: finaloutput.wp_editor_selector,
							
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
							selector:'#' + finaloutput.wp_editor_selector,
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
						tinyMCE.execCommand('mceAddEditor', false, finaloutput.wp_editor_selector);

						tinyMCE.settings = tinyMCEPreInit.mceInit.content;

						tinyMCE.execCommand('mceAddControl', false, finaloutput.wp_editor_selector);

					}else if(finaloutput.error){
						alert_md(finaloutput.error);
					}
					tinymce_obj_click_count = 0;
				}
			});
		}
		}else{
			if(tinymce_obj_click_count >= 2 ) {
				tinymce_obj_click_count = 0;
			}
		}
	});
	jQuery(document).on('click','#menu_manager_popup #menu_manager_popup_container #Amp_amp_update_wrapper.amp_update_new_item_wrapper .amp_update_item_inner_main .amp_update_item_submit_row_wrapper .amp_submit_post_for_front_amp_update_item', function(){

		var post_id = jQuery(this).attr('id');
		var obj = jQuery(this).closest('#amm_wp_post_edit.wp-post-edit').find('#Amp_amp_update_wrapper.amp_update_new_item_wrapper');

		var amp_post_title = obj.find('.amp_update_item_inner_main .amp_update_item_details_left div.row .amp_item_title').val();
		amp_post_title = jQuery.trim(amp_post_title);

		var amp_edit_slug = obj.find('.amp_update_item_inner_main .amp_update_item_details_left div.row .amp_item_slug').val();
		amp_edit_slug = jQuery.trim(amp_edit_slug);

		var amp_post_author = obj.find('.amp_update_item_inner_main .amp_update_item_details_left div.row select.amp_item_author').val();

		var post_content;

		post_content = tinyMCE.activeEditor.getContent();
		if (post_content.length === 0){
			post_content = '';
		}

		//validation
		if('' === amp_post_title){ 
			alert_md('Item title can not be blank'); 
			return false; 
		}
		var	action_data;
		if(jQuery(this).hasClass('amp_page_update')){

			var	amp_post_template = obj.find('.amp_update_item_inner_main .amp_update_item_details_left div.row select.amp_item_template').val();
			action_data = {
				'action': 'my_action_for_main_popup_fontend_menu_item_edit_submit',
				'post_author' : amp_post_author,
				'post_template' : amp_post_template,
				'post_slug' : amp_edit_slug,
				'post_title' : amp_post_title,
				'post_id' : post_id,
				'post_content':post_content,
				'security' : ajax_object.ajax_nonce,
			};
		} else {
			var	amp_set_new_category = obj.find('.amp_update_item_inner_main .amp_update_item_details_left div.row ul.amp_select .set_new_category:checked').serialize();
			var	amp_old_category = obj.find('.amp_update_item_inner_main .amp_update_item_details_left div.row .old_category').val();

			action_data = {
				'action': 'my_action_for_main_popup_fontend_menu_item_edit_submit',
				'post_author' : amp_post_author,
				'post_category' : 'edit_category',
				'post_category_old' : amp_old_category,
				'post_category_new' : amp_set_new_category,
				'post_slug' : amp_edit_slug,
				'post_title' : amp_post_title,
				'post_id' : post_id,
				'post_content':post_content,
				'security' : ajax_object.ajax_nonce,
			};
		}
		obj.addClass('loading');

		var this_obj = jQuery(this);

		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: action_data,
			success:function( response ) {
				var finaloutput = jQuery.parseJSON(response);

				if( finaloutput.sucess ){
					alert_md(finaloutput.sucess);
					this_obj.closest('.amp_update_item_submit_row_wrapper').find('.amp_menu_amp_update_cancel').trigger( 'click' );
					location.reload();
				}else if(finaloutput.error){
					alert_md(finaloutput.error);
				}
				obj.removeClass('loading');
			}
		});
	});
	jQuery(document).on('click','#menu_manager_popup #menu_manager_popup_container #Amp_amp_update_wrapper.amp_update_new_item_wrapper .amp_update_item_inner_main .amp_update_item_submit_row_wrapper .amp_menu_amp_update_cancel', function(){
		jQuery('#menu_manager_popup_container #amp_wp_post_edit.wp-post-edit').remove();
		jQuery('#menu_manager_popup').fadeOut();
	});

	/** end ****************************************/

	/** Menu Lock ****************************************/
	jQuery(document).on('click','#amm_menu_locked .amm_menu_locked_submit_wrapper #amm_locked_this_menu', function(){

		var obj = jQuery(this).closest('.amm_menu_locked_description');
		var	locked_user_list = obj.find('.amm_locked_user_checkbox:checked').serialize();
		var locked_menu_id = obj.find('#current_menu_id').val();
		var menu_name =  obj.find('#current_menu_name').val();
		var key_user =  obj.find('#current_user').val();

		var	locked_action_data = {
			'action': 'my_action_for_menu_locked_submit',
			'user_list' : locked_user_list,
			'menu_id' : locked_menu_id,
			'menu_name' : menu_name,
			'current_user' : key_user,
			'security' : ajax_object.ajax_nonce,
		};

		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: locked_action_data,
			success:function( response ) {
				var finaloutput = jQuery.parseJSON(response);
				if( finaloutput.sucess ){
					alert_md(finaloutput.sucess);
				} else if(finaloutput.error){
					alert_md(finaloutput.error);
				}
			}
		});
	});
	
	//menu lock status changes
	jQuery('#amm_menu_locked .enabled_Disabled .amm_menu_locked_status #amm_menu_locked_status').change(function() {
		jQuery('div#amm_menu_locked #menu_locked_loader').fadeIn();
		var current_menu_id = jQuery('#amm_menu_locked .amm_menu_locked_description #current_menu_id').val();
		
		var amm_menu_locked_status = '';

		if(jQuery(this).prop('checked') === false){
			//do something
			amm_menu_locked_status ='Disabled';
		}else{
			amm_menu_locked_status = 'Enabled';
		}

		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: {
			'action': 'my_action_for_menu_locked_status',
			'menu_id':current_menu_id,
			'menu_locked_status':amm_menu_locked_status,
			'security' : ajax_object.ajax_nonce,
			},
			success:function(response) {
				var finaloutput = jQuery.parseJSON(response);
				if( finaloutput.sucess ){
					jQuery('#amm_menu_locked .amm_menu_locked_content h3 span').attr('id',amm_menu_locked_status);					
					jQuery('#amm_menu_locked .amm_menu_locked_content h3 span').text(amm_menu_locked_status);
					
					if(amm_menu_locked_status === 'Disabled'){
						jQuery('div#amm_menu_locked #menu_locked_user_list').hide();
						jQuery('div#amm_menu_locked .amm_locked_user_data_list li input:checkbox').removeAttr('checked');
						alert_md('The Menu Lock Functionality has been disabled. Users having access to your site credentials will have access to the menu.');
						
					}else{
						alert_md('The Menu Lock Functionality has been enabled. Check the box to allow a specific user to have access to the menu');						
						jQuery('div#amm_menu_locked #menu_locked_user_list').show();						
					}
				}else if(finaloutput.error){
					alert_md(finaloutput.error);
				}
				jQuery('div#amm_menu_locked #menu_locked_loader').fadeOut();
			}
		});
	}); //end of menu locked click

	/** end Menu Lock ****************************************/
	
	function hide_page_post_add_html(){
		
		var obj = jQuery('.amm_item_main_wrapper');
		obj.find('.add_mew_item_wrapper').addClass('amm_deactive');
		obj.find('.md_popup_main_wrapper').fadeIn();
		obj.find('.amm_header_main').fadeIn('slow');
		obj.find('p.button-controls').fadeIn('slow');
		obj.find('p.amm_list_of_page').fadeIn('slow');

		obj.find('.add_item_details_left div.row input[type=text]').val('');
		obj.find('.add_item_details_left div.row input[type=checkbox]').removeAttr('checked');
		obj.find('.add_item_details_left div.row .amm_select').prop('selectedIndex',0);
	}
}); //end of document ready