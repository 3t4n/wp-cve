jQuery(function($){
    
    $('body').append('<div id="po_please_wait"><div id="po_please_wait_message"><b>Plugin Optimizer</b><span>Please wait...</span></div></div>');
    
    
    // clean the menu we're fetching
    if( po_object.alphabetize_menu ){
        
        // remove separators
        $('#adminmenu > li.wp-menu-separator:not(:eq(0))').remove();
        
        // define items we don't want to sort
        let $items_to_exclude = $('#adminmenu > li#menu-dashboard');
        $items_to_exclude.add( $('#adminmenu > li#collapse-menu') );
        
        // define items to sort
        let $items_to_sort = $('#adminmenu > li').not( $items_to_exclude )
        
        // sort the items
        let $sorted_items = $items_to_sort.sort(function( a, b ){
            
            let title_a = $( a ).children('a').children('.wp-menu-name').text().toUpperCase();
            let title_b = $( b ).children('a').children('.wp-menu-name').text().toUpperCase();
            
            return title_a.localeCompare( title_b );
            
        });
        
        // move the sorted items
        $sorted_items.insertBefore('#adminmenu li#collapse-menu');
        
    }
    
    // clean the topbar site-name submenu
    $('#wp-admin-bar-view-site').remove();
    
    
    // grab the menu html
    let menu_html = $('#adminmenu').html();
    
    // grab the topbar site-name submenu additional items
    let topbar_menu_html = $('#wp-admin-bar-site-name-default').html();
    
    // grab the topbar site-name submenu additional items
    let new_html = $('#wp-admin-bar-new-content-default').html();
    
    // form the data object for sending
    let data = {
        action                   : 'po_save_original_menu',
        menu_html                : menu_html,
        topbar_menu_html         : topbar_menu_html,
        new_html                 : new_html
    };
    
    // save the menu in wp_options
    $.post( po_object.ajax_url, data, function( response ) {
        
        $('#po_please_wait_message').html('<b>Plugin Optimizer</b><span>Refreshing...</span>');
        
        window.location.href = po_object.redirect_to ? po_object.redirect_to : window.location.href;
        
    }, "json");
    
    
});