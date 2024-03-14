jQuery(function($){
   
    // Are we replacing the menu
    if( po_object.original_menu ){
        
        // ----- replace the menu
        
        $('#adminmenu').html( po_object.original_menu );
        
        
        // ----- find the current menu items
        
        let current_url             = window.location.href;
        let $current_menu_item      = false;
        let $current_menu_sub_item  = false;
        
        // find the current menu sub_item
        $('#adminmenu > li > ul.wp-submenu > li > a').each(function(){
            
            let link_url = $(this).attr("href");
            
            if( current_url.endsWith( link_url ) ){
                
                $current_menu_sub_item = $(this).parent();
                
                return;
            }
            
        });
        
        // find the current menu item
        $('#adminmenu > li > a').each(function(){
            
            let link_url = $(this).attr("href");
            
            if( current_url.endsWith( link_url ) ){
                
                $current_menu_item = $(this).parent();
                
                return;
            }
            
        });
        
        // if current menu item not found, use the parent of the sub menu item
        if( ! $current_menu_item && $current_menu_sub_item ){
            
            $current_menu_item = $current_menu_sub_item.parents('li.menu-top');
        }
        
        // ----- remove the incorrect menu classes
        
        $('#adminmenu > li > ul.wp-submenu > li').removeClass("current");
        $('#adminmenu > li').removeClass("wp-has-current-submenu wp-menu-open").addClass("wp-not-current-submenu");
        $('#adminmenu > li > a').removeClass("wp-has-current-submenu wp-menu-open").addClass("wp-not-current-submenu");
        
        // ----- add correct menu classes
        
        if( $current_menu_item ){
            
            $current_menu_item.addClass("wp-has-current-submenu wp-menu-open").removeClass("wp-not-current-submenu");
            $current_menu_item.children('a').addClass("wp-has-current-submenu wp-menu-open").removeClass("wp-not-current-submenu");
            
            if( $current_menu_sub_item ){
                
                $current_menu_sub_item.addClass("current");
            }
        }
        
        // ----- add correct circled counters
        
        if( po_object.dashboard_updates ){
            
            // TODO add the element if it needed but doesn't exist
            
            $('#adminmenu > #menu-dashboard .update-plugins').attr("class", "update-plugins count-" + po_object.dashboard_updates );
            $('#adminmenu > #menu-dashboard .update-plugins .update-count').html( po_object.dashboard_updates );
        } else {
            $('#adminmenu > #menu-dashboard .update-plugins').remove();
        }
        
        if( po_object.plugin_updates ){
            
            // TODO add the element if it needed but doesn't exist
            
            $('#adminmenu > #menu-plugins .update-plugins').attr("class", "update-plugins count-" + po_object.plugin_updates );
            $('#adminmenu > #menu-plugins .update-plugins .update-count').html( po_object.plugin_updates );
        } else {
            $('#adminmenu > #menu-plugins .update-plugins').remove();
        }
        
    }
    
    // show the menu
    $('#adminmenu').show();
    
    
    // Should we fix the topbar site-name menu
    if( po_object.topbar_menu ){
        
        $('#wp-admin-bar-site-name-default').append( po_object.topbar_menu );
        
    }
    
    
    // Should we use the original list of +New
    if( po_object.new_posts ){
        
        $('#wp-admin-bar-new-content-default').html( po_object.new_posts );
        
    }
    
});