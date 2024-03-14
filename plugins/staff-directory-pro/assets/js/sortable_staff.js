jQuery(function () {
	
	/* Force the placeholder to be the size of the element it represents,
	 * otherwise the placeholder doesn't seem to get the right height (too short).
	 * See: https://stackoverflow.com/a/6261429
	*/
	var set_row_dimentions =  function(ev, ui) {
		var hidden_cols = jQuery(ui.item).find('> td:hidden, > th:hidden').length;
		jQuery(ui.placeholder).find('> td, > th').slice( (-1 * hidden_cols) ).css('display','none');
		ui.placeholder.height(ui.item.height());
		ui.placeholder.width(ui.item.width());
	};
	
	
	/*
	 * Send an AJAX message to the server to tell it not to show the
	 * review alert box again.
	 */
	var post_new_menu_order = function (new_menu_order) {
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			data: { 
				menu_order: new_menu_order,
				action: "company_directory_save_new_menu_order",
			},
			success: function (data) {
			}
		});
	};

	var update_menu_order_inputs =  function(ev, ui) {
		var the_list = ui.item.parent();
		setTimeout( function () {
			var menu_order_counter = 0;
			if ( typeof(company_directory) != 'undefined' && company_directory.starting_index ) {
				var menu_order_counter = company_directory.starting_index;
			}
			var new_menu_order = {};
			jQuery(the_list).find('tr.type-staff-member').each( function () {
				
				var my_post_id = jQuery(this).find('.menu_order_input').data('post-id');
				new_menu_order[my_post_id] = menu_order_counter;
				
				jQuery(this).find('.menu_order_input')
							.val(menu_order_counter)
							.attr('value', menu_order_counter);
				menu_order_counter++;
			});	
			
			// update menu order on server
			post_new_menu_order(new_menu_order);
			
		}, 10);
	};
	
	var fix_helper_widths = function(e, ui) {
		ui.children().each(function() {
			jQuery(this).width( jQuery(this).width() );
		});
		return ui;
	};
	
	
	var init_sortable_staff =  function() {
		
			jQuery('#the-list').sortable( {
				handle: '.sortable_handle',
				start: set_row_dimentions,
				update: update_menu_order_inputs,
				items: '> tr.type-staff-member',
				forceHelperSize: true,
				placeholder: 'company-directory-sortable-placeholder',
				helper: fix_helper_widths
			} );
		
	}
	
	init_sortable_staff();
	
});