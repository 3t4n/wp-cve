(function($) {
    //on dom load
    $(function() {
        
        var wait = function() {
            $('body').css('cursor', 'wait');
        };
        var nowait = function() {
            $('body').css('cursor', 'inherit');       
        };
        
        var checkSave = function(e, cb) {
            e.preventDefault();
            
            //make sure we have an active menu
            if ($('.nav-tab-active').text().trim() == "+") {
                alert('Invalid menu selection. Please select an existing menu.');
                return false;                
            }
            
            //make sure the menu is populated with items 
            if ($('.menu li').length == 0) {
                alert("This menu is empty.  Please select a menu with one or more menu items");
                return false;
            }
            
            //check for unsaved changes first because we will try to reload the page on success
            if (wpNavMenu.menusChanged && !confirm( navMenuL10n.saveAlert) ) {
                return false;
            }
            else {
                return cb();
            }
        };
        
        //Add buttons to publish-actions container
        
        //Feature to copy an existing menu to a new one
        $('.publishing-action').append('<input id="copy_menu_header" title="Duplicate this menu to a newly created menu" class="button-primary menu-save" name="copy_menu" value="Copy Menu..." type="submit" style="margin-left: 5px;" />');
        
        $('#copy_menu_header').click(function(e) {
            return checkSave(e, function() {
                var cur_menu_name = $('.nav-tab-active').text().trim();
                var name = prompt('Enter a new menu name to copy this menu ("' + cur_menu_name + '") to', 'Copy of ' + cur_menu_name);
                
                if (name) {
                    wait();
                    //request to copy current menu to new menu name
                    $.get(
                        ajaxurl,
                        {
                            nonce : emc2eme.new_menu_nonce,
                            action : 'emc2eme_copy_menu',
                            menu_name : name,
                            menu : $('#menu').val()
                        },
                        function(r) {
                            if (isNaN(r)) {
                                alert(r);
                            }
                            else {
                                //prevent dialog box
                                window.onbeforeunload = null;
                                wpNavMenu.menusChanged = false;
                                
                                alert('Menu copied successfully');
                                //a numeric response means success, reload the current page to show new menu
                                location.replace(location);
                            }
                        }
                    );
                }
                
                return false;
            });
        });
        
        //Feature to enforce menu strucutre on the actual page hierarchy
        $('.publishing-action').append('<input id="sync_pages_header" title="Enforces this menu\'s hierarchy on the page-level hierarchy" class="button-primary menu-save" name="sync_pages" value="Sync Page Structure..." type="submit" style="margin-left: 5px;" />');
        $('#sync_pages_header').click(function(e) {
            return checkSave(e, function() {
                
                if (confirm('Are you sure? \n\nThis will irreversibly change the parent page relationships of all pages in the system to match the menu hierarchy of all page menu items.  \n\nContinue?')) {
                    wait();
                    $.get(
                        ajaxurl,
                        {
                            nonce : emc2eme.sync_menu_nonce,
                            action : 'emc2eme_sync_pages',
                            menu : $('#menu').val()
                        },
                        function(r) {
                            nowait();
                            alert(r);
                        }
                    );                
                }
                
                return false;  
            });
        });
        
        
    });
})(jQuery);