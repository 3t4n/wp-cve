
// init namespace
if ( typeof WPLA != 'object') {
    var WPLA = {};
}


// revealing module pattern
WPLA.ProfileSelector = function () {
    // this will be a private property
    let self = {}, item_ids = [], select_mode = '';
    // this will be a public method
    const init = function () {
        self = this; // assign reference to current object to "self"
    
        // jobs window "close" button
        jQuery('#wpla_profile_selector_window .btn_close').click( function(event) {
            tb_remove();                    
        }).hide();

    };

    const select = function ( obj, profile_id ) {
        // console.log( 'selecting #'+profile_id+' to ASIN '+asin );
        // console.log( obj );

        // load task list
        const params = {
            action: 'wpla_select_profile',
            profile_id: profile_id,
            product_ids: item_ids,
            select_mode: window.wpla_select_mode,
            _wpnonce: wpla_ProfileSelector_i18n.wpla_ajax_nonce
        };
        // var jqxhr = jQuery.getJSON( ajaxurl, params ) // GET doesn't work when preparing hundreds of products
        jQuery.post( ajaxurl, params, null, 'json' )
            .done( function( response ) {

                if ( response.success ) {

                    // request was successful
                    tb_remove();

                    window.alert(response.msg);
                    window.location.reload();

                } else {
                    const logMsg = '<div id="message" class="updated" style="display:block !important;"><p>' +
                    'I could not find any items. Sorry.' +
                    '</p></div>';
                    jQuery('#ajax-response').append( logMsg );

                    window.alert( 'There was a problem preparing these products. The server responded:\n\n' + response.msg );
                    console.log( 'response', response );

                }


            })
            .fail( function(e,xhr,error) {
                alert( "There was a problem preparing this product. The server responded:\n\n" + e.responseText );
                console.log( "error", xhr, error );
                console.log( e.responseText );
                console.log( "ajaxurl", ajaxurl );
                console.log( "params", params );
            }
        );

    }

    // show jobs window
    const showWindow = function ( title ) {

        // show jobs window
        // var tbHeight = tb_getPageSize()[1] - 160;
        // var tbURL = "#TB_inline?height="+tbHeight+"&width=500&modal=true&inlineId=wpla_profile_selector_window_container"; 
        const sep   = ajaxurl.indexOf('?') > 0 ? '&' : '?'; // fix for ajaxurl altered by WPML: /wp-admin/admin-ajax.php?lang=en
        const tbURL = ajaxurl + sep + "action=wpla_show_profile_selection&width=640&height=420";

        // jQuery('#wpla_jobs_log').html('').css('height', tbHeight - 130 );
        // jQuery('#wpla_jobs_title').html( title );
        // jQuery('#wpla_jobs_message').html('fetching list of tasks...');
        // jQuery('#wpla_jobs_message').html( wpla_ProfileSelector_i18n.msg_loading_tasks );
        // jQuery('#wpla_jobs_footer_msg').html( "Please don't close this window until all tasks are completed." );
        // jQuery('#wpla_jobs_footer_msg').html( wpla_ProfileSelector_i18n.footer_dont_close );

        // hide close button
        // jQuery('#wpla_profile_selector_window .btn_close').hide();

        // show window
        tb_show("Select profile for "+item_ids.length+" "+window.wpla_select_mode, tbURL);             

    }

    // get selected products
    const getSelectedProducts = function ( select_mode ) {
        item_ids = [];

        // create array of selected product IDs
        let checked_items = jQuery(".check-column input:checked[name='post[]']");

        // create array of selected listing IDs
        if ( 'listings' == select_mode ) {
            checked_items = jQuery(".check-column input:checked[name='listing[]']");
        }

        checked_items.each( function(index, checkbox) {
             item_ids.push( checkbox.value );
             console.log( 'checked listing ID', checkbox.value );
        });
        // console.log( item_ids );

        return item_ids;
    }

    return {
        // declare which properties and methods are supposed to be public
        init: init,
        select: select,
        getSelectedProducts: getSelectedProducts,
        showWindow: showWindow
    }
}();





// init 
jQuery( document ).ready( function () {

    // handle bulk actions click
    jQuery(".tablenav .actions input[type='submit'].action").on('click', function(event) {
        // console.log(event);
        let selected_action;
        if ( 'doaction'  == this.id ) {
            selected_action = jQuery("select[name='action']").first().val();
        }
        if ( 'doaction2' == this.id ) {
            selected_action = jQuery("select[name='action2']").first().val();
        }

        // console.log( 'selected_action', selected_action );
        let select_mode = false;

        if ( 'list_on_amazon' == selected_action ) {
            select_mode = 'products';
        }
        if ( 'wpla_change_profile' == selected_action ) {
            select_mode = 'listings';
        }
        if ( ! select_mode ) return true;
        // console.log( 'select_mode', select_mode );

        // check if any items were selected
        const item_ids = WPLA.ProfileSelector.getSelectedProducts( select_mode );
        if ( item_ids.length > 0 ) {
            event.preventDefault();
            window.wpla_select_mode = select_mode;
            WPLA.ProfileSelector.showWindow();
        }

        return false;
    });

}); 
