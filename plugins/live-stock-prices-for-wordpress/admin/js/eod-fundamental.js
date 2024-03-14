// Use jQuery UI (sortable) for right list of selected data
function eod_fd_make_list_sortable() {
    let $list = jQuery(".selected_list");
    if( $list.length === 0 ) return;

    $list.sortable({
        revert: false,
        revertDuration: 0,
        cursor: "grabbing",
        stop: function (e, ul){
            eod_prepare_fd_in_input();
        },
        over: function () {
            outside_drop = false;
        },
        out: function () {
            outside_drop = true;
        },
        beforeStop: function (event, ui) {
            if (outside_drop === true) ui.item.remove();
        }
    });
}

// Use jQuery UI (draggable) for left source data list
function eod_fd_make_list_draggable($list){
    if(!$list || $list.length === 0) return;
    $list.draggable({
        //connectToSortable: ".selected_list, .has_child > ul, .selected_list ul",
        connectToSortable: ".selected_list, .selected_list ul",
        helper: "clone",
        revert: "invalid",
        cursor: "grabbing",
        stop: function (e, ul){
            let $li = ul.helper.eq(0);
            $li.attr('style', '');

            // Change add button to remove
            $li.find('button').attr('title', 'remove item').text('-');

            // Dehierarchize
            eod_fd_dehierarchize_list();

            // Prepare data
            eod_prepare_fd_in_input();
        }
    });

}

// Transform hierarchical list to flat
function eod_fd_dehierarchize_list(){
    let $selected_list = jQuery('.fd_list.selected_list');
    $selected_list.find('li').each(function(){
        if(jQuery(this).find('> ul').length) {
            jQuery(this).after( jQuery(this).find('li') );
            jQuery(this).remove();
        }
        jQuery(this).find('> span').attr('style', '');
    });
}

// Interpret right list of selected data and write to input
function eod_prepare_fd_in_input(){
    let selected_list = [];
    jQuery('.selected_list li > span').each(function(){
        selected_list.push(jQuery(this).attr('data-slug'));
    });
    jQuery('#fd_list').val( JSON.stringify(selected_list) );
}

// Select financials group
jQuery(document).on('change', 'select[name=financial_group]', function(){
    jQuery('.fd_list.selected_list').html('');

    // Searching selected group
    let selected_group = jQuery(this).val().split('->').join('_');
    jQuery('.fd_list.source_list').each(function() {
        jQuery(this).toggleClass( 'active', jQuery(this).hasClass( selected_group ) )
    });
});

// Select fundamental data type
jQuery(document).on('change', '#fd_type', function(e){
    jQuery('.fd_list.source_list').removeClass('active');
    jQuery('.fd_list.source_list.'+jQuery(this).val()).addClass('active');
    jQuery('.fd_list.selected_list').html('');
    eod_prepare_fd_in_input();
});

// Clicking on the remove or add button
jQuery(document).on('click', '.selected_list button, .source_list li > span', function(e){
    e.preventDefault();
    let $source_item = jQuery(this).is('button') ? jQuery(this).parent() : jQuery(this);
    if($source_item.find('> button').text() === '+') {
        let $item = jQuery( $source_item.parent().clone().attr('class', '') );

        // Remove all hide elements
        $item.find('.hide').remove();

        // Change add button to remove
        $item.find('button').attr('title', 'remove item').text('-');
        $item.find('span').attr('title','');

        jQuery('.fd_list.selected_list').append( $item );

        // Dehierarchize
        eod_fd_dehierarchize_list();

    }else{
        $source_item.parent().remove();
    }
    eod_prepare_fd_in_input();
});

jQuery( function() {
    eod_fd_make_list_draggable(jQuery( ".source_list .draggable" ));
    eod_fd_make_list_sortable();
    // jQuery( ".fd_array_grid ul, .fd_array_grid li" ).disableSelection();

    // Search Fundamental Data item is source list
    let $search_input = jQuery('.search_fd_variable');
    if($search_input.length)
        $search_input.keyup( jQuery.debounce(300, function(e){
            let text = jQuery(this).val().toLowerCase();
            if(text === ''){
                jQuery('.fd_list.source_list li').show();
            } else {
                jQuery('.fd_list.source_list .has_child > span').each(function () {
                    let group_name = jQuery(this).text().toLowerCase();

                    // Finding by group. Show entire group when matched.
                    if( group_name.indexOf( text ) > -1 ){
                        jQuery(this).parent().find('li').show();

                    // Else compare each child element.
                    }else{
                        jQuery(this).parent().find('li:not(".has_child") > span').each(function() {
                            let name = jQuery(this).text().toLowerCase();
                            jQuery(this).parent().toggle( name.indexOf(text) > -1 );
                        });
                    }
                });
            }
        }));
} );