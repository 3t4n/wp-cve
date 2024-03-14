jQuery( function( $ ) {

    var table_selector   = 'table.wp-list-table',
        item_selector    = 'tbody tr:not(.inline-edit-row)',
        column_handle    = '<td class="column-handle"></td>',
        selector_row     = '';

    if (wugrat_admin_group_attributes_ordering_params.screen_id === 'edit-wugrat_group') {
        selector_row = '.column-handle input[name="group_term_id"]';
    } else if (wugrat_admin_group_attributes_ordering_params.screen_id === 'product_page_wugrat_attributes_in_group') {
        selector_row = '.column-handle input[name="attribute_name"]';
    }

    if ( 0 === $( table_selector ).find( '.column-handle' ).length ) {
        $( table_selector ).find( 'tr:not(.inline-edit-row)' ).append( column_handle );

        selector_row = '.check-column input';
    }

    $( table_selector ).find( '.column-handle' ).show();

    $.wc_add_missing_sort_handles = function() {
        var all_table_rows = $( table_selector ).find('tbody > tr');
        var rows_with_handle = $( table_selector ).find('tbody > tr > td.column-handle').parent();
        if ( all_table_rows.length !== rows_with_handle.length ) {
            all_table_rows.each(function(index, elem){
                if ( ! rows_with_handle.is( elem ) ) {
                    $( elem ).append( column_handle );
                }
            });
        }
        $( table_selector ).find( '.column-handle' ).show();
    };

    $( document ).ajaxComplete( function( event, request, options ) {
        if ( request && 4 === request.readyState && 200 === request.status && options.data && ( 0 <= options.data.indexOf( '_inline_edit' ) || 0 <= options.data.indexOf( 'add-tag' ) ) ) {
            $.wc_add_missing_sort_handles();
            $( document.body ).trigger( 'init_tooltips' );
        }
    } );

    $( table_selector ).sortable({
        items: item_selector,
        cursor: 'move',
        handle: '.column-handle',
        axis: 'y',
        forcePlaceholderSize: true,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'product-cat-placeholder',
        scrollSensitivity: 40,
        start: function( event, ui ) {
            if ( ! ui.item.hasClass( 'alternate' ) ) {
                ui.item.css( 'background-color', '#ffffff' );
            }
            ui.item.children( 'td, th' ).css( 'border-bottom-width', '0' );
            ui.item.css( 'outline', '1px solid #aaa' );
        },
        stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
            ui.item.children( 'td, th' ).css( 'border-bottom-width', '1px' );
        },
        update: function( event, ui ) {
            var attribute_name = ui.item.find( selector_row ).val();
            var prev_attribute_name = ui.item.prev().find( selector_row ).val();
            var next_attribute_name = ui.item.next().find( selector_row ).val();
            var data       = {
                action: 			 'wugrat_group_attributes_ordering',
                screen_id:			 wugrat_admin_group_attributes_ordering_params.screen_id,
                attribute_name: 	 attribute_name,
                prev_attribute_name: prev_attribute_name,
                next_attribute_name: next_attribute_name,
                group_term_id: 		 wugrat_admin_group_attributes_ordering_params.group_term_id
            };

            // Show Spinner
            ui.item.find( '.check-column input' ).hide();
            ui.item.find( '.check-column' ).append( '<img alt="processing" src="images/wpspin_light.gif" class="waiting" style="margin-left: 6px;" />' );

            // Go do the sorting stuff via ajax
            $.post( ajaxurl, data, function(response){
                if ( response === 'children' ) {
                    window.location.reload();
                } else {
                    ui.item.find( '.check-column input' ).show();
                    ui.item.find( '.check-column' ).find( 'img' ).remove();
                }
            });

            // Fix cell colors
            $( 'table.widefat tbody tr' ).each( function() {
                var i = jQuery( 'table.widefat tbody tr' ).index( this );
                if ( i%2 === 0 ) {
                    jQuery( this ).addClass( 'alternate' );
                } else {
                    jQuery( this ).removeClass( 'alternate' );
                }
            });
        }
    });

});
