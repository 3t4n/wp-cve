/*!
 * intelliwidget.js - Javascript for the Admin.
 *
 * @package IntelliWidget
 * @subpackage js
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 *
 */
(function($){
    "use strict";      

    /**
     * BEGIN FUNCTIONS
     */
     
    // store panel open state so it can persist across ajax refreshes
    function updateOpenPanels( container ) {
        container.find( '.inside' ).each( function() {
            var inside = $( this ).prop( 'id' );
            //console.log( 'update panels: ' + inside );
            openPanels[ inside ] = $( this ).parent( '.iw-collapsible' ).hasClass( 'closed' ) ? 0 : 1;
        } );
    }
    
    function refreshOpenPanels( e, widget ) { // a, b ) {

        for ( var key in openPanels ) {
            if ( openPanels.hasOwnProperty( key ) && 1 === openPanels[ key ] ) {
                //console.log( 'refreshOpenPanels: ' + key );
                $( '#' + key ).parent( '.iw-collapsible' ).removeClass( 'closed' );
                if ( widget ) {
                    $( '#' + key ).find( '.intelliwidget-multiselect' ).multiSelect();
                }
            }
        }
    }
        
    function initTabs() {
        $( '.iw-tabbed-sections' ).each( function() {
            var container = $( this );
            container.data( 'viewWidth', 0 );
            container.data( 'visWidth',  0 );
            container.data( 'leftTabs',  [] ); 
            container.data( 'rightTabs', [] );
            container.data( 'visTabs',   [] );
            container.find( '.iw-tab' ).each( function() {
                container.data( 'visTabs' ).push( $( this ).prop( 'id' ) );
                container.data( 'visWidth', container.data( 'visWidth' ) + $( this ).outerWidth() );
                $( this ).show();
            } );
        } );
        reflowTabs();
    }
    
    function reflowTabs() {
        $( '.iw-tabbed-sections' ).each( function() {
            var container = $( this ),
                count = 0;
            container.data( 'viewWidth', container.find( '.iw-tabs' ).width() - 24 ); // minus space for arrows
            if ( container.data( 'viewWidth' ) > 0 ) {
                while ( container.data( 'visTabs' ).length && container.data( 'visWidth' ) > container.data( 'viewWidth' ) ) {
                    var leftMost = container.data( 'visTabs' ).shift(),
                        tabWidth = $( '#' + leftMost ).outerWidth();
                    container.data( 'visWidth', container.data( 'visWidth' ) - tabWidth );
                    $( '#' + leftMost ).hide();
                    container.data( 'leftTabs' ).push( leftMost );
                    if ( ++count > 50 ) { break; } // infinite loop safety check
                }
            }
        } );
        setArrows();
    }
    
    function rightShiftTabs( el ) {
        // left arrow clicked, shift all tabs to the right
        var container = el.parent( '.iw-tabbed-sections' ),
            rightMost;
        if ( ( rightMost = container.data( 'visTabs' ).pop() ) ) {
            container.data( 'visWidth', container.data( 'visWidth' ) - $( '#' + rightMost ).outerWidth() );
            $( '#' + rightMost ).hide();
            container.data( 'rightTabs' ).unshift( rightMost );
        }
        if ( ( rightMost = container.data( 'leftTabs' ).pop() ) ) {
            container.data( 'visWidth', container.data( 'visWidth' ) + $( '#' + rightMost ).outerWidth() );
            $( '#' + rightMost ).show();
            container.data( 'visTabs' ).unshift( rightMost );
        }
        setArrows();
    }
    
    function leftShiftTabs( el ) {
        // right arrow clicked, shift all tabs to the left
        var container = el.parent( '.iw-tabbed-sections' ),
            leftMost;
        if ( ( leftMost = container.data( 'visTabs' ).shift() ) ) {
            container.data( 'visWidth', container.data( 'visWidth' ) - $( '#' + leftMost ).outerWidth() );
            $( '#' + leftMost ).hide();
            container.data( 'leftTabs' ).push( leftMost );
        }
        if ( ( leftMost = container.data( 'rightTabs' ).shift() ) ) {
            container.data( 'visWidth', container.data( 'visWidth' ) + $( '#' + leftMost ).outerWidth() );
            $( '#' + leftMost ).show();
            container.data( 'visTabs' ).push( leftMost );
        }
        setArrows();
    }
    
    function setArrows() {
        $( '.iw-larr, .iw-rarr' ).css( 'visibility', 'hidden' );
        $( '.iw-tabbed-sections' ).each( function() {
            var container = $( this );
            // if rightTabs, show >>
            if ( container.data( 'rightTabs' ).length ) { container.find( '.iw-rarr' ).css( 'visibility', 'visible' ); }
            // if leftTabs, show <<
            if ( container.data( 'leftTabs' ).length ) { container.find( '.iw-larr' ).css( 'visibility', 'visible' ); }
        } );
    }
    
    function parse_ids( id ) {
            // parse id to get section number
        var idparts         = id.split( '_' ),
            boxid           = idparts.pop(),
            objid           = idparts.pop();
        return objid + '_' + boxid;
    }
    
    /**
     * ajax_post
     * Common function for all ajax calls
     */
    function ajax_post( element, postData, callback ) {
        // if this is not widget page get post id
        start_ajax( element );
        // handle nonce value
        if ( is_widget_admin ) {
            // customizer
            if ( 'undefined' !== typeof window.wp.customize && 'undefined' !== typeof window.wp.customize.Widgets ) {
                //console.log( 'customer widget nonce: ' + wp.customize.Widgets.data.nonce );
                postData.nonce = window.wp.customize.Widgets.data.nonce;
                postData.wp_customize = 'on';
            } else {
                // widget admin
                postData._wpnonce_widgets = $( '#_wpnonce_widgets' ).val();
            }
        } else {
            // post/term/options admin
            postData.iwpage = $( '#iwpage' ).val();
            postData[ window.IWAjax.idfield ] = $( '#' + window.IWAjax.idfield ).val();
        }
        //console.log( postData );
        //console.log( IWAjax );
        $.post(  
            // get ajax url from localized object
            window.IWAjax.ajaxurl,  
            //Data  
            postData,
            //on success function  
            function( response ) {
                //console.log( response );
                if ( 'fail' === response ) {
                    //console.log( postData.action + ' failed' );
                    end_ajax( element, 'failure' );
                } else {
                    //console.log( element );
                    if ( callback ) {
                        callback( element, response );
                    }
                    end_ajax( element, 'success' );
                    // widgets page now triggers widget-updated event, so only trigger for child profiles
                    if ( !is_widget_admin ){
                        $( document ).trigger( 'widget-updated' );
                    }
                }
            }, //( postData.action.indexOf( 'select_menu' ) > 0 ) || 
            ( postData.action.indexOf( '_delete' ) > 0 ) ? 'text' : 'json' // backward compatibility for IW Pro
        ).fail( function(){ // xhr, res, err ) {
            //console.log( 'fail before post' );
            //console.log( err.message );
            end_ajax( element, 'failure' );
        } );  
        return false;  
    }
    function set_spinner( element, status ) {
        var $spinner;
        if ( $( element ).hasClass( 'iw-save' ) ){
            $spinner = $( element ).parent( '.iw-save-container' ).siblings( '.spinner' ).first();
        } else if ( $( element ).hasClass( 'iw-delete' ) ) {
            $spinner = $( element ).parent( '.submitbox' ).siblings( '.spinner' ).first();
        } else if ( $( element ).hasClass( 'iw-toggle iw-collapsible' ) ) {
            $spinner = $( element ).parent( '.' ).siblings( '.spinner' ).first();
        } else {
            $spinner = $( element ).parents( '.inside' ).first().find( '.spinner' ).first();
        }
        $spinner.css( { 'visibility': status } ).show();
        if ( 'hidden' === status ) { $spinner.hide(); }
    }
        
    function start_ajax( element ) {
        /* show/hide spinner */
        $( containerSel ).removeClass( 'success failure' );
        set_spinner( element, 'visible' );
        // disable the button until ajax returns
        $( element ).prop( 'disabled', true );    
    }
    
    function end_ajax( element, status ) {
        // reset status. use selector because element has been replaced
        var sel = '#' + $( element ).attr( 'id' );
        $( sel ).prop( 'disabled', false );
        set_spinner( sel, 'hidden' );
        $( sel ).parents( '.inside,.iw-tabbed-section' ).first().find( containerSel ).first().addClass( status );
    }
    /**
     * Ajax Save Custom Post Type Data
     */
    function save_cdfdata( e ) {
        var postData = {};
        // find inputs for this section
        $( '.intelliwidget-input' ).each( function() {
            postData[ $( this ).attr( 'id' ) ] = ( 'checkbox' === $( this ).attr( 'type' ) ? 
                ( $( this ).is(':checked') ? 1 : 0 ) : $( this ).val() );
        } );
        // add wp ajax action to array
        postData.action = 'iw_' + window.IWAjax.objtype + '_cdfsave';
        //console.log( postData );
        // send to wp
        ajax_post( e.target, postData, null );
    }
    
    /**
     * Ajax Save IntelliWidget Meta Box Data
     */
    function save_postdata ( e ) { 
        var $sectionform    = $( e.target ).parents( '.iw-tabbed-section' ).first(), // get section selector
            $savebutton     = $sectionform.find( '.iw-save' ), // get button selector
            thisID          = $sectionform.prop( 'id' ),
            pre             = parse_ids( thisID ),
            // build post data array
            postData        = {};
        //console.log( 'thisID: ' + thisID + ' pre: ' + pre );
        updateOpenPanels( $sectionform );
        // special handling for post types ( array of checkboxes )
        postData[ 'intelliwidget_' + pre + '_post_types' ] = [];
        // find inputs for this section
        $sectionform.find( 'select,textarea,input[type=text],input[type=checkbox]:checked,input[type=hidden]' ).each( function() {
            // get field id
            var $el     = $( this ),
                field   = $el.prop( 'id' ),
                val     = $el.val();
            //console.log( 'fieldID: ' + fieldID );
            if ( field.indexOf( '_post_types' ) > 0 ) {
                // special handling for post types
                postData[ 'intelliwidget_' + pre + '_post_types' ].push( val );
            } else {
                // otherwise add to post data
                postData[ field ] = val;
            }
            if ( field.indexOf( '_menu_location' ) > 0 ) {
                // special case for menu_location
                if ( '' !== val ) { postData[ 'intelliwidget_' + pre + '_replace_widget' ] = 'nav_menu_location-' + val; }
            }
        } );
        // add wp ajax action to array
        postData.action = 'iw_' + window.IWAjax.objtype + '_save';
        //console.log( postData );
        // send to wp
        ajax_post( $savebutton, postData, callback_save );
        return false;  
    }
    
    /**
     * Ajax Save Copy Page Input
     */
    function copy_profile ( e ) { 
        // build post data array
        var postData = {};
        // find inputs for this section
        postData.intelliwidget_widget_page_id = $( '#intelliwidget_widget_page_id' ).val();
        // add wp ajax action to array
        postData.action = 'iw_' + window.IWAjax.objtype + '_copy';
        //console.log( postData );
        // send to wp
        ajax_post( e.target, postData, null );
        return false;  
    }
    
    /**
     * Ajax Add new IntelliWidget Tab Section
     */
    function add_tabbed_section ( e ) { 
        // don't act like a link
        e.preventDefault();
        e.stopPropagation();
        var href        = $( e.target ).attr( 'href' ),
            postData    = url_to_array( href ); // build post data array from query string
        // add wp ajax action to array
        postData.action = 'iw_' + window.IWAjax.objtype + '_add';
        // send to wp
        ajax_post( e.target, postData, callback_add );
        return false;  
    }
    
    /**
     * Ajax Delete IntelliWidget Tab Section
     */
    function delete_tabbed_section ( e ) { 
        // don't act like a link
        e.preventDefault();
        e.stopPropagation();
        var href        = $( e.target ).attr( 'href' ), // get href from link
            postData    = url_to_array( href );     // build post data array from query string
        // add wp ajax action to array
        postData.action = 'iw_' + window.IWAjax.objtype + '_delete';
        // send to wp
        ajax_post( e.target, postData, callback_delete );
        return false;  
    }
    
    /**
     * Ajax Fetch multiselect menus
     */
    function get_menus( e) { 
        var parentSel       = is_widget_admin ?  '.widget' : '.iw-tabbed-section',
            $sectionform    = $( e.target ).parents( parentSel ).first(),
            // parse id to get section number
            thisID          = is_widget_admin ? $sectionform.find( '.widget-id' ).val() : parse_ids( $sectionform.prop( 'id' ) ),
            // get section selector
            menuSel         = is_widget_admin ? '#widget-' + thisID + '-menus' : '#intelliwidget_' + thisID + '_menus',
            // build post data array
            postData        = {};
        // only load once
        if ( $( menuSel ).has( 'select' ).length ) {
            //console.log( 'refreshing multiselects...' );
            $( menuSel ).find( '.intelliwidget-multiselect' ).multiSelect( 'refresh' );
            return false; 
        }
        //console.log( 'loading menus...' );
        if ( is_widget_admin ) {
            postData[ 'widget-id' ] = $sectionform.find( '.widget-id' ).val();
            // add wp ajax action to array
            postData.action = 'iw_widget_menus';
        } else {
            // find inputs for this section
            $sectionform.find( 'input[type="hidden"]' ).each( function() {
                // add to post data
                postData[ $( this ).attr( 'id' ) ] = $( this ).val();
            } );
            postData.action = 'iw_' + window.IWAjax.objtype + '_menus';
        }
        // add wp ajax action to array
        //console.log( postData );
        // send to wp
        ajax_post( menuSel, postData, callback_menus );
    }

    function menu_search( element ) {
        var searchVal   = $( element ).val(),
            searchID    = $( element ).attr( 'id' ),
            menuSel     = searchID.substring( 0, searchID.indexOf( 'search' ) ),
            parts       = menuSel.split( /[\-_]/ ),
            type        = parts.pop(),
            box_id      = parts.pop(),
            action      = 'iw_' + ( is_widget_admin ? 'widget' : 'post' ) + '_select_menu', 
            // FIXME: should be generated from IWAjax.objtype but cant for backward compatibility
            postData = {};
        //console.log( 'menu_search: ' + searchVal + ' has return: ' + searchVal.match( /[\n\r]/ ) );
        postData[ searchID ]    = searchVal.replace( /[\n\r]/g, '' ); // strip newline/return
        postData[ menuSel ]     = $( '#' + menuSel ).val();
        postData.menutype       = type;
        postData.action         = action;
        if ( is_widget_admin ) {
            postData[ 'widget-id' ] = 'intelliwidget-' + box_id;
        } else {
            postData.intelliwidget_box_id = box_id;
        }
        ajax_post( '#' + menuSel, postData, callback_menu );
    }

    /**
     * Ajax Callback functions
     * Executed when XHR returns
     */
    function callback_save( element, response ) {
        //console.log( 'callback_save' );
        // refresh section form
        var $tab            = $( response.tab ),
            $curtab         = $( '.iw-tabs' ).find( '#' + $tab.prop( 'id' ) ),
            $sectionform    = $( element ).parents( '.iw-tabbed-section' ).first(),
            $container      = $sectionform.parent( '.iw-tabbed-sections' );
        $curtab.html( $tab.html() );
        $sectionform.html( response.form ).find( '.intelliwidget-multiselect' ).multiSelect();
        //if ( 'post' === IWAjax.objtype ) { bind_events( $sectionform ); }
        $container.tabs( 'refresh' ).tabs( { active: $curtab.index() } );
    }
    
    function callback_add( element, response ) {
        //console.log( 'callback_add' );
        var $container  = $( element ).parent( '.inside' ).find( '.iw-tabbed-sections' ),
            $form       = $( response.form ).hide(),
            $tab        = $( response.tab ).hide();
        $container.append( $form );
        //if ( 'post' === IWAjax.objtype ) { bind_events( $form ); }
        $container.find( '.iw-tabs' ).append( $tab );
        $tab.show();
        $container.tabs( 'refresh' ).tabs( { active: $tab.index() } );
        initTabs();
    }
    
    function callback_delete( element ) {
        //console.log( 'callback_delete' );
        var $sectionform    = $( element ).parents( '.iw-tabbed-section' ).first(),
            $container      = $sectionform.parent( '.iw-tabbed-sections' ),
            thisID          = $sectionform.prop( 'id' ),
            // get box id 
            pre             = parse_ids( thisID ),
            survivor = $sectionform.index();
        $sectionform.remove();
        //console.log( 'pre: ' + pre );
        $( '#iw_tab_' + pre ).remove();
        $container.tabs( 'refresh' );
        initTabs();
        $container.tabs( { active: survivor } );
    }
    
    function callback_menus( element, response ) {
        //console.log( 'callback_menus' );
        //console.log( element );
        $( element ).html( response ).find( '.intelliwidget-multiselect' ).multiSelect();
    }
    
    function callback_menu( element, response ) {
        //console.log( 'callback_menus' );
        //console.log( element );
        $( element ).html( response ).prop( 'disabled', false ).multiSelect( 'refresh' );
    }
    
    /**
     * nice little url -> name:value pairs codec
     */
    function url_to_array( url ) {
        var pair, i, request = {},
            pairs = url.substring( url.indexOf( '?' ) + 1 ).split( '&' );
        for ( i = 0; i < pairs.length; i++ ) {
            pair = pairs[ i ].split( '=' );
            request[ decodeURIComponent( pair[ 0 ] ) ] = decodeURIComponent( pair[ 1 ] );
        }
        return request;
    }
    
    /** 
     * set visible timestamp and timestamp hidden inputs to form inputs 
     * only validates form if validate param is true
     * this allows values to be reset/cleared
     */
    function iwUpdateTimestampText( field, validate ) {
        // retrieve values from form
        var attemptedDate, 
            div         = '#' + field + '_div', 
            clearForm   = ( !validate && !$( '#' + field ).val() ),  
            aa          = $( '#' + field + '_aa' ).val(),
            mm          = ( '00' + $( '#' + field + '_mm' ).val() ).slice( -2 ), 
            jj          = ( '00' + $( '#' + field + '_jj' ).val() ).slice( -2 ), 
            hh          = ( '00' + $( '#' + field + '_hh' ).val() ).slice( -2 ), 
            mn          = ( '00' + $( '#' + field + '_mn' ).val() ).slice( -2 ); //,
            //og          = $( '#' + field + '_og' ).val();
        //console.log( ' field: ' + div + ' aa: ' + aa + ' mm: ' + mm + ' jj: ' + jj + ' hh: ' + hh + ' mn: ' + mn );
        if ( ! $( div ).length ) { return true; }
        // construct date object
        attemptedDate = new Date( aa, mm - 1, jj, hh, mn );
        //console.log( 'date: ' + attemptedDate );
        // validate inputs by comparing to date object
        if ( attemptedDate.getFullYear() != aa || 
            ( 1 + attemptedDate.getMonth() ) != mm || 
            attemptedDate.getDate() != jj ||
            attemptedDate.getMinutes() != mn ) {
            // date object returned invalid
            // if validating, display error and return invalid
                if ( true === validate ) { //&& !og ) {
                    $( div ).addClass( 'form-invalid' );
                    $( '.iw-cdfsave' ).prop( 'disabled', true );
                    return false;
                }
                // otherwise clear form ( value is/was null )  
                clearForm = true;
        }
        // date validated or ignored, reset invalid class
        $( div ).removeClass( 'form-invalid' );
        
        $( '.iw-cdfsave' ).prop( 'disabled', false );
        if ( clearForm ) {
            // replace date fields with empty string
            //if ( ! og ) { $( '#' + field + '_timestamp' ).html( '' ); }
            $( '#' + field ).val( '' );
        } else {
            // format displayed date string from form values
            //if ( 'intelliwidget_expire_date' === field ) {
                //$( '#intelliwidget_ongoing' ).val( $( '#' + field + '_og' ).is( ':checked' ) ? 1 : 0 );
                //if ( $( '#' + field + '_og' ).is( ':checked' ) ) {
                //    $( '#' + field + '_timestamp' ).html( $( '#intelliwidget_ongoing_label' ).text() );
                //    $( '#' + field ).val( '' );
                //    return true;
                //}
            //}
            $( '#' + field + '_timestamp' ).html( 
                '<b>' +
                $( 'option[value="' + $( '#' + field + '_mm' ).val() + '"]', '#' + field + '_mm' ).text() + ' ' +
                jj + ', ' +
                aa + ' @ ' +
                hh + ':' +
                mn + '</b> '
            );
            // format date field from form values
            $( '#' + field ).val( 
                aa + '-' +
                $( '#' + field + '_mm' ).val() + '-' +
                jj + ' ' +
                hh + ':' +
                mn                    
            );
        }
        return true;
    }
    
    function check_key( e ) {
        //console.log( 'check_key' );
        //console.log( e );
        if ( $( e.target ).hasClass( 'iw-menusearch' ) ) {
            // wait for typing to pause before submitting
            clearTimeout( $( e.target ).data( 'timer' ) );
            $( e.target ).data( 'timer', setTimeout( menu_search, 400, e.target ) );
            //console.log( 'check key: ' + e.which );
            if ( 13 === e.which ) { 
                //console.log( 'search return char detected!' );
                e.stopPropagation();
                e.preventDefault();
                return false;
            }
        } else if ( 13 === e.which ) { 
            e.stopPropagation();
            e.preventDefault();
            //console.log( 'widget return char detected!' );
            save_postdata.call( e );
            return false;
        }
    }

    function init(){
        /**
         * START EVENT BINDINGS ( delegate where posible )
         */

        // Add handler to check if panels were open before ajax save and reopen them
        $( document ).on( 'widget-updated', refreshOpenPanels );
        $( '.iw-tabbed-sections' ).tabs( { active: ( $( 'iw-tab' ).length - 1 ) } );
        $( 'body' ).on( 'click', '.iw-collapsible > .iw-toggle, .iw-collapsible > h4, .iw-collapsible > h3', function( e ) {
            //console.log( 'panel toggle');
            e.stopPropagation();
            var $p = $( this ).parent( '.iw-collapsible' ), 
                $sectionform = $( this ).parents( 'div.widget, div.iw-tabbed-section' ).first();
            if ( $p.hasClass( 'closed' ) ){
                //console.log( 'opening panel' );
                $p.removeClass( 'closed' );
                // get menus if this is post selection panel
                if ( $p.hasClass( 'panel-selection' ) ) {
                    //console.log( 'fetching selection panel' );
                    //$( '.panel-selection' ).not( $p ).each( function(){ $( this ).addClass( 'closed' ); } );
                    get_menus( e );
                }
            } else {
                //console.log( 'closing selection panel' );
                $p.addClass( 'closed' );
            }
            updateOpenPanels( $sectionform );
        } );
        // bind click events to edit page meta box buttons
        $( '#intelliwidget_main_meta_box,.main-meta-box' ).on( 'click', '.iw-save', save_postdata );    
        $( '#intelliwidget_post_meta_box' ).on( 'click', '.iw-cdfsave', save_cdfdata );    
        $( '#intelliwidget_main_meta_box,.main-meta-box' ).on( 'click', '.iw-copy', copy_profile );    
        $( '#intelliwidget_main_meta_box,.main-meta-box' ).on( 'click', '.iw-add', add_tabbed_section );    
        $( '#intelliwidget_main_meta_box,.main-meta-box' ).on( 'click', '.iw-delete', delete_tabbed_section );
        // update visibility of form inputs
        $( '#intelliwidget_main_meta_box,.main-meta-box' ).on( 'change', '.iw-control', save_postdata );    
        $( 'body' ).on( 'change', '.intelliwidget-form-container .iw-widget-control', function() {
            var $sectionform = $( this ).parents( 'div.widget' ).first(),
                widgetid = $sectionform.find( '.widget-id' ).val();
            //console.log( 'widget id: ' + widgetid );
            if ( 'undefined' !== typeof window.wp.customize && 'undefined' !== typeof window.wp.customize.Widgets ) {
                // customizer submits on change
                //return;
                var $control = window.wp.customize.Widgets.getWidgetFormControlForWidget( widgetid );
                $control.liveUpdateMode = false;
                $control.updateWidget();
                //console.log( $control );
            } else {
                updateOpenPanels( $sectionform );
                window.wpWidgets.save( $sectionform, 0, 0, 0 );
            }
        } );

        // bind keydown events
        $( '#intelliwidget_main_meta_box,.main-meta-box' ).on( 'keydown', 'input,select,textarea', check_key );
        $( 'body' ).on( 'keydown', '.intelliwidget-form-container .iw-menusearch', function( e ) {
            //console.log( 'body delegated' );
            //console.log( e );
            if ( 13 === e.which ){
                //console.log( 'return key pressed' );
                e.stopPropagation();
                e.preventDefault();
                menu_search( e.target );
                return false;
            }
            // wait for typing to pause before submitting
            clearTimeout( $( e.target ).data( 'timer' ) );
            $( e.target ).data( 'timer', setTimeout( menu_search, 400, e.target ) );
            //return false;
        } );
        /**
         * manipulate IntelliWidget timestamp inputs
         * Adapted from wp-admin/js/post.js in Wordpress Core
         */
        if ( 'post' === window.IWAjax.objtype ) {
            // format visible timestamp values
            iwUpdateTimestampText( 'intelliwidget_event_date', false );
            iwUpdateTimestampText( 'intelliwidget_expire_date', false );
        }
        // bind edit links to reveal timestamp input form
        $( '#intelliwidget_post_meta_box' ).on( 'click', 'a.intelliwidget-edit-timestamp', function() {
            var field = $( this ).attr( 'id' ).split( '-', 1 );
            if ( $( '#' + field + '_div' ).is( ":hidden" ) ) {
                $( '#' + field + '_div' ).slideDown( 'fast' );
                $( '#' + field + '_mm' ).focus();
                $( this ).hide();
            }
            return false;
        } );
        // bind click to clear timestamp ( resets form to current date/time and clears date fields )
        $( '#intelliwidget_post_meta_box' ).on( 'click', '.intelliwidget-clear-timestamp', function() {
            var field = $( this ).attr( 'id' ).split( '-', 1 );
            $( '#' + field + '_div' ).slideUp( 'fast' );
            $( '#' + field + '_mm' ).val( $( '#' + field + '_cur_mm' ).val() );
            $( '#' + field + '_jj' ).val( $( '#' + field + '_cur_jj' ).val() );
            $( '#' + field + '_aa' ).val( $( '#' + field + '_cur_aa' ).val() );
            $( '#' + field + '_hh' ).val( $( '#' + field + '_cur_hh' ).val() );
            $( '#' + field + '_mn' ).val( $( '#' + field + '_cur_mn' ).val() );
            //$( '#' + field + '_og' ).prop( 'checked', false );
            $( '#' + field + '_timestamp' ).html( '' );
            $( '#' + field ).val( '' );
            $( 'a#' + field + '-edit' ).show();
            iwUpdateTimestampText( field, false );
            return false;
        } );
        // bind cancel button to reset values ( or empty string if orig field is empty ) 
        $( '#intelliwidget_post_meta_box' ).on( 'click', '.intelliwidget-cancel-timestamp', function() {
            var field = $( this ).attr( 'id' ).split( '-', 1 );
            $( '#' + field + '_div' ).slideUp( 'fast' );
            $( '#' + field + '_mm' ).val( $( '#' + field + '_hidden_mm' ).val() );
            $( '#' + field + '_jj' ).val( $( '#' + field + '_hidden_jj' ).val() );
            $( '#' + field + '_aa' ).val( $( '#' + field + '_hidden_aa' ).val() );
            $( '#' + field + '_hh' ).val( $( '#' + field + '_hidden_hh' ).val() );
            $( '#' + field + '_mn' ).val( $( '#' + field + '_hidden_mn' ).val() );
            //$( '#' + field + '_og' ).prop( 'checked', $( '#' + field + '_hidden_og' ).val() ? true : false );
            $( 'a#' + field + '-edit' ).show();
            iwUpdateTimestampText( field, false );
            return false;
        } );
        // bind 'Ok' button to update timestamp to inputs
        $( '#intelliwidget_post_meta_box' ).on( 'click', '.intelliwidget-save-timestamp', function () { 
            var field = $( this ).attr( 'id' ).split( '-', 1 );
            if ( iwUpdateTimestampText( field, true ) ) {
                $( '#' + field + '_div' ).slideUp( 'fast' );
                $( 'a#' + field + '-edit' ).show();
            }
            return false;
        } );
        // bind right and left scroll arrows
        $( '.iw-tabbed-sections' ).on( 'click', '.iw-larr, .iw-rarr', function( e ) {
            e.preventDefault();
            e.stopPropagation();
            if ( $( this ).is( ':visible' ) ) {
                if ( $( this ).hasClass( 'iw-larr' ) ) { rightShiftTabs( $( this ) ); }
                else { leftShiftTabs( $( this ) ); }
            }
        } );
        // reflow tabs on resize
        $( window ).resize( reflowTabs );
        // END EVENT BINDINGS
        // reveal intelliwidget sections
        $( '.iw-tabbed-sections' ).slideDown();
        // set up tabs
        initTabs();
    }
    /* END OF FUNCTIONS */

    var openPanels      = {},
        containerSel    = '.iw-copy-container,.iw-save-container,.iw-cdf-container',
        is_widget_admin = ( '' === window.IWAjax.objtype );
    $( document ).ready( function() {
        init();
    });
} )(jQuery);

/*
* MultiSelect v0.9.11
* Copyright (c) 2012 Louis Cuny
*
* This program is free software. It comes without any warranty, to
* the extent permitted by applicable law. You can redistribute it
* and/or modify it under the terms of the Do WTF You Want
* To Public License, Version 2, as published by Sam Hocevar. See
* http://sam.zoy.org/wtfpl/COPYING for more details.
*/

(function ($) {

  "use strict";


 /* MULTISELECT CLASS DEFINITION
  * ====================== */

  var MultiSelect = function (element, options) {
    this.options = options;
    this.$element = $(element);
    this.$container = $('<div/>', { 'class': "ms-container" });
    this.$selectableContainer = $('<div/>', { 'class': 'ms-selectable' });
    this.$selectionContainer = $('<div/>', { 'class': 'ms-selection' });
    this.$selectableUl = $('<ul/>', { 'class': "ms-list", 'tabindex' : '-1' });
    this.$selectionUl = $('<ul/>', { 'class': "ms-list", 'tabindex' : '-1' });
    this.scrollTo = 0;
    this.elemsSelector = 'li:visible:not(.ms-optgroup-label,.ms-optgroup-container,.'+options.disabledClass+')';
  };

  MultiSelect.prototype = {
    constructor: MultiSelect,

    init: function(){
      var that = this,
          ms = this.$element;

      if (ms.next('.ms-container').length === 0){
        ms.css({ position: 'absolute', left: '-9999px' });
        ms.attr('id', ms.attr('id') ? ms.attr('id') : Math.ceil(Math.random()*1000)+'multiselect');
        this.$container.attr('id', 'ms-'+ms.attr('id'));
        this.$container.addClass(that.options.cssClass);
        ms.find('option').each(function(){
          that.generateLisFromOption(this);
        });

        this.$selectionUl.find('.ms-optgroup-label').hide();

        if (that.options.selectableHeader){
          that.$selectableContainer.append(that.options.selectableHeader);
        }
        that.$selectableContainer.append(that.$selectableUl);
        if (that.options.selectableFooter){
          that.$selectableContainer.append(that.options.selectableFooter);
        }

        if (that.options.selectionHeader){
          that.$selectionContainer.append(that.options.selectionHeader);
        }
        that.$selectionContainer.append(that.$selectionUl);
        if (that.options.selectionFooter){
          that.$selectionContainer.append(that.options.selectionFooter);
        }

        that.$container.append(that.$selectableContainer);
        that.$container.append(that.$selectionContainer);
        ms.after(that.$container);

        that.activeMouse(that.$selectableUl);
        that.activeKeyboard(that.$selectableUl);

        var action = that.options.dblClick ? 'dblclick' : 'click';

        that.$selectableUl.on(action, '.ms-elem-selectable', function(){
          that.select($(this).data('ms-value'));
        });
        that.$selectionUl.on(action, '.ms-elem-selection', function(){
          that.deselect($(this).data('ms-value'));
        });

        that.activeMouse(that.$selectionUl);
        that.activeKeyboard(that.$selectionUl);

        ms.on('focus', function(){
          that.$selectableUl.focus();
        })
      }

      var selectedValues = ms.find('option:selected').map(function(){ return $(this).val(); }).get();
      that.select(selectedValues, 'init');

      if (typeof that.options.afterInit === 'function') {
        that.options.afterInit.call(this, this.$container);
      }
    },

    'generateLisFromOption' : function(option, index, $container){
      var that = this,
          ms = that.$element,
          attributes = "",
          $option = $(option);

      for (var cpt = 0; cpt < option.attributes.length; cpt++){
        var attr = option.attributes[cpt];

        if(attr.name !== 'value' && attr.name !== 'disabled'){
          attributes += attr.name+'="'+attr.value+'" ';
        }
      }
      var selectableLi = $('<li '+attributes+'><span>'+that.escapeHTML($option.text())+'</span></li>'),
          selectedLi = selectableLi.clone(),
          value = $option.val(),
          elementId = that.sanitize(value);

      selectableLi
        .data('ms-value', value)
        .addClass('ms-elem-selectable')
        .attr('id', elementId+'-selectable');

      selectedLi
        .data('ms-value', value)
        .addClass('ms-elem-selection')
        .attr('id', elementId+'-selection')
        .hide();

      if ($option.prop('disabled') || ms.prop('disabled')){
        selectedLi.addClass(that.options.disabledClass);
        selectableLi.addClass(that.options.disabledClass);
      }

      var $optgroup = $option.parent('optgroup');

      if ($optgroup.length > 0){
        var optgroupLabel = $optgroup.attr('label'),
            optgroupId = that.sanitize(optgroupLabel),
            $selectableOptgroup = that.$selectableUl.find('#optgroup-selectable-'+optgroupId),
            $selectionOptgroup = that.$selectionUl.find('#optgroup-selection-'+optgroupId);

        if ($selectableOptgroup.length === 0){
          var optgroupContainerTpl = '<li class="ms-optgroup-container"></li>',
              optgroupTpl = '<ul class="ms-optgroup"><li class="ms-optgroup-label"><span>'+optgroupLabel+'</span></li></ul>';

          $selectableOptgroup = $(optgroupContainerTpl);
          $selectionOptgroup = $(optgroupContainerTpl);
          $selectableOptgroup.attr('id', 'optgroup-selectable-'+optgroupId);
          $selectionOptgroup.attr('id', 'optgroup-selection-'+optgroupId);
          $selectableOptgroup.append($(optgroupTpl));
          $selectionOptgroup.append($(optgroupTpl));
          if (that.options.selectableOptgroup){
            $selectableOptgroup.find('.ms-optgroup-label').on('click', function(){
              var values = $optgroup.children(':not(:selected, :disabled)').map(function(){ return $(this).val() }).get();
              that.select(values);
            });
            $selectionOptgroup.find('.ms-optgroup-label').on('click', function(){
              var values = $optgroup.children(':selected:not(:disabled)').map(function(){ return $(this).val() }).get();
              that.deselect(values);
            });
          }
          that.$selectableUl.append($selectableOptgroup);
          that.$selectionUl.append($selectionOptgroup);
        }
        index = index == undefined ? $selectableOptgroup.find('ul').children().length : index + 1;
        selectableLi.insertAt(index, $selectableOptgroup.children());
        selectedLi.insertAt(index, $selectionOptgroup.children());
      } else {
        index = index == undefined ? that.$selectableUl.children().length : index;

        selectableLi.insertAt(index, that.$selectableUl);
        selectedLi.insertAt(index, that.$selectionUl);
      }
    },

    'addOption' : function(options){
      var that = this;

      if (options.value !== undefined && options.value !== null){
        options = [options];
      } 
      $.each(options, function(index, option){
        if (option.value !== undefined && option.value !== null &&
            that.$element.find("option[value='"+option.value+"']").length === 0){
          var $option = $('<option value="'+option.value+'">'+option.text+'</option>'),
              index = parseInt((typeof option.index === 'undefined' ? that.$element.children().length : option.index)),
              $container = option.nested == undefined ? that.$element : $("optgroup[label='"+option.nested+"']")

          $option.insertAt(index, $container);
          that.generateLisFromOption($option.get(0), index, option.nested);
        }
      })
    },

    'escapeHTML' : function(text){
      return $("<div>").text(text).html();
    },

    'activeKeyboard' : function($list){
      var that = this;

      $list.on('focus', function(){
        $(this).addClass('ms-focus');
      })
      .on('blur', function(){
        $(this).removeClass('ms-focus');
      })
      .on('keydown', function(e){
        switch (e.which) {
          case 40:
          case 38:
            e.preventDefault();
            e.stopPropagation();
            that.moveHighlight($(this), (e.which === 38) ? -1 : 1);
            return;
          case 37:
          case 39:
            e.preventDefault();
            e.stopPropagation();
            that.switchList($list);
            return;
          case 9:
            if(that.$element.is('[tabindex]')){
              e.preventDefault();
              var tabindex = parseInt(that.$element.attr('tabindex'), 10);
              tabindex = (e.shiftKey) ? tabindex-1 : tabindex+1;
              $('[tabindex="'+(tabindex)+'"]').focus();
              return;
            }else{
              if(e.shiftKey){
                that.$element.trigger('focus');
              }
            }
        }
        if($.inArray(e.which, that.options.keySelect) > -1){
          e.preventDefault();
          e.stopPropagation();
          that.selectHighlighted($list);
          return;
        }
      });
    },

    'moveHighlight': function($list, direction){
      var $elems = $list.find(this.elemsSelector),
          $currElem = $elems.filter('.ms-hover'),
          $nextElem = null,
          elemHeight = $elems.first().outerHeight(),
          containerHeight = $list.height(),
          containerSelector = '#'+this.$container.prop('id');

      $elems.removeClass('ms-hover');
      if (direction === 1){ // DOWN

        $nextElem = $currElem.nextAll(this.elemsSelector).first();
        if ($nextElem.length === 0){
          var $optgroupUl = $currElem.parent();

          if ($optgroupUl.hasClass('ms-optgroup')){
            var $optgroupLi = $optgroupUl.parent(),
                $nextOptgroupLi = $optgroupLi.next(':visible');

            if ($nextOptgroupLi.length > 0){
              $nextElem = $nextOptgroupLi.find(this.elemsSelector).first();
            } else {
              $nextElem = $elems.first();
            }
          } else {
            $nextElem = $elems.first();
          }
        }
      } else if (direction === -1){ // UP

        $nextElem = $currElem.prevAll(this.elemsSelector).first();
        if ($nextElem.length === 0){
          var $optgroupUl = $currElem.parent();

          if ($optgroupUl.hasClass('ms-optgroup')){
            var $optgroupLi = $optgroupUl.parent(),
                $prevOptgroupLi = $optgroupLi.prev(':visible');

            if ($prevOptgroupLi.length > 0){
              $nextElem = $prevOptgroupLi.find(this.elemsSelector).last();
            } else {
              $nextElem = $elems.last();
            }
          } else {
            $nextElem = $elems.last();
          }
        }
      }
      if ($nextElem.length > 0){
        $nextElem.addClass('ms-hover');
        var scrollTo = $list.scrollTop() + $nextElem.position().top - 
                       containerHeight / 2 + elemHeight / 2;

        $list.scrollTop(scrollTo);
      }
    },

    'selectHighlighted' : function($list){
      var $elems = $list.find(this.elemsSelector),
          $highlightedElem = $elems.filter('.ms-hover').first();

      if ($highlightedElem.length > 0){
        if ($list.parent().hasClass('ms-selectable')){
          this.select($highlightedElem.data('ms-value'));
        } else {
          this.deselect($highlightedElem.data('ms-value'));
        }
        $elems.removeClass('ms-hover');
      }
    },

    'switchList' : function($list){
      $list.blur();
      this.$container.find(this.elemsSelector).removeClass('ms-hover');
      if ($list.parent().hasClass('ms-selectable')){
        this.$selectionUl.focus();
      } else {
        this.$selectableUl.focus();
      }
    },

    'activeMouse' : function($list){
      var that = this;

      $('body').on('mouseenter', that.elemsSelector, function(){
        $(this).parents('.ms-container').find(that.elemsSelector).removeClass('ms-hover');
        $(this).addClass('ms-hover');
      });

      $('body').on('mouseleave', that.elemsSelector, function () {
          $(this).parents('.ms-container').find(that.elemsSelector).removeClass('ms-hover');
      });
    },

    'refresh' : function() {
      this.destroy();
      this.$element.multiSelect(this.options);
    },

    'destroy' : function(){
      $("#ms-"+this.$element.attr("id")).remove();
      this.$element.css('position', '').css('left', '');
      this.$element.removeData('multiselect');
    },

    'select' : function(value, method){
      if (typeof value === 'string'){ value = [value]; }

      var that = this,
          ms = this.$element,
          msIds = $.map(value, function(val){ return(that.sanitize(val)); }),
          selectables = this.$selectableUl.find('#' + msIds.join('-selectable, #')+'-selectable').filter(':not(.'+that.options.disabledClass+')'),
          selections = this.$selectionUl.find('#' + msIds.join('-selection, #') + '-selection').filter(':not(.'+that.options.disabledClass+')'),
          options = ms.find('option:not(:disabled)').filter(function(){ return($.inArray(this.value, value) > -1); });

      if (method === 'init'){
        selectables = this.$selectableUl.find('#' + msIds.join('-selectable, #')+'-selectable');
        selections = this.$selectionUl.find('#' + msIds.join('-selection, #') + '-selection');
      }

      if (selectables.length > 0){
        selectables.addClass('ms-selected').hide();
        selections.addClass('ms-selected').show();

        options.prop('selected', true);

        that.$container.find(that.elemsSelector).removeClass('ms-hover');

        var selectableOptgroups = that.$selectableUl.children('.ms-optgroup-container');
        if (selectableOptgroups.length > 0){
          selectableOptgroups.each(function(){
            var selectablesLi = $(this).find('.ms-elem-selectable');
            if (selectablesLi.length === selectablesLi.filter('.ms-selected').length){
              $(this).find('.ms-optgroup-label').hide();
            }
          });

          var selectionOptgroups = that.$selectionUl.children('.ms-optgroup-container');
          selectionOptgroups.each(function(){
            var selectionsLi = $(this).find('.ms-elem-selection');
            if (selectionsLi.filter('.ms-selected').length > 0){
              $(this).find('.ms-optgroup-label').show();
            }
          });
        } else {
          if (that.options.keepOrder && method !== 'init'){
            var selectionLiLast = that.$selectionUl.find('.ms-selected');
            if((selectionLiLast.length > 1) && (selectionLiLast.last().get(0) != selections.get(0))) {
              selections.insertAfter(selectionLiLast.last());
            }
          }
        }
        if (method !== 'init'){
          ms.trigger('change');
          if (typeof that.options.afterSelect === 'function') {
            that.options.afterSelect.call(this, value);
          }
        }
      }
    },

    'deselect' : function(value){
      if (typeof value === 'string'){ value = [value]; }

      var that = this,
          ms = this.$element,
          msIds = $.map(value, function(val){ return(that.sanitize(val)); }),
          selectables = this.$selectableUl.find('#' + msIds.join('-selectable, #')+'-selectable'),
          selections = this.$selectionUl.find('#' + msIds.join('-selection, #')+'-selection').filter('.ms-selected').filter(':not(.'+that.options.disabledClass+')'),
          options = ms.find('option').filter(function(){ return($.inArray(this.value, value) > -1); });

      if (selections.length > 0){
        selectables.removeClass('ms-selected').show();
        selections.removeClass('ms-selected').hide();
        options.prop('selected', false);

        that.$container.find(that.elemsSelector).removeClass('ms-hover');

        var selectableOptgroups = that.$selectableUl.children('.ms-optgroup-container');
        if (selectableOptgroups.length > 0){
          selectableOptgroups.each(function(){
            var selectablesLi = $(this).find('.ms-elem-selectable');
            if (selectablesLi.filter(':not(.ms-selected)').length > 0){
              $(this).find('.ms-optgroup-label').show();
            }
          });

          var selectionOptgroups = that.$selectionUl.children('.ms-optgroup-container');
          selectionOptgroups.each(function(){
            var selectionsLi = $(this).find('.ms-elem-selection');
            if (selectionsLi.filter('.ms-selected').length === 0){
              $(this).find('.ms-optgroup-label').hide();
            }
          });
        }
        ms.trigger('change');
        if (typeof that.options.afterDeselect === 'function') {
          that.options.afterDeselect.call(this, value);
        }
      }
    },

    'select_all' : function(){
      var ms = this.$element,
          values = ms.val();

      ms.find('option:not(":disabled")').prop('selected', true);
      this.$selectableUl.find('.ms-elem-selectable').filter(':not(.'+this.options.disabledClass+')').addClass('ms-selected').hide();
      this.$selectionUl.find('.ms-optgroup-label').show();
      this.$selectableUl.find('.ms-optgroup-label').hide();
      this.$selectionUl.find('.ms-elem-selection').filter(':not(.'+this.options.disabledClass+')').addClass('ms-selected').show();
      this.$selectionUl.focus();
      ms.trigger('change');
      if (typeof this.options.afterSelect === 'function') {
        var selectedValues = $.grep(ms.val(), function(item){
          return $.inArray(item, values) < 0;
        });
        this.options.afterSelect.call(this, selectedValues);
      }
    },

    'deselect_all' : function(){
      var ms = this.$element,
          values = ms.val();

      ms.find('option').prop('selected', false);
      this.$selectableUl.find('.ms-elem-selectable').removeClass('ms-selected').show();
      this.$selectionUl.find('.ms-optgroup-label').hide();
      this.$selectableUl.find('.ms-optgroup-label').show();
      this.$selectionUl.find('.ms-elem-selection').removeClass('ms-selected').hide();
      this.$selectableUl.focus();
      ms.trigger('change');
      if (typeof this.options.afterDeselect === 'function') {
        this.options.afterDeselect.call(this, values);
      }
    },

    sanitize: function(value){
      var hash = 0, i, character;
      if (value.length == 0) {return hash;}
      var ls = 0;
      for (i = 0, ls = value.length; i < ls; i++) {
        character  = value.charCodeAt(i);
        hash  = ((hash<<5)-hash)+character;
        hash |= 0; // Convert to 32bit integer
      }
      return hash;
    }
  };

  /* MULTISELECT PLUGIN DEFINITION
   * ======================= */

  $.fn.multiSelect = function () {
    var option = arguments[0],
        args = arguments;

    return this.each(function () {
      var $this = $(this),
          data = $this.data('multiselect'),
          options = $.extend({}, $.fn.multiSelect.defaults, $this.data(), typeof option === 'object' && option);

      if (!data){ $this.data('multiselect', (data = new MultiSelect(this, options))); }

      if (typeof option === 'string'){
        data[option](args[1]);
      } else {
        data.init();
      }
    });
  };

  $.fn.multiSelect.defaults = {
    keySelect: [32],
    selectableOptgroup: false,
    disabledClass : 'disabled',
    dblClick : false,
    keepOrder: false,
    cssClass: ''
  };

  $.fn.multiSelect.Constructor = MultiSelect;

  $.fn.insertAt = function(index, $parent) {
    return this.each(function() {
      if (index === 0) {
        $parent.prepend(this);
      } else {
        $parent.children().eq(index - 1).after(this);
      }
    });
};

})(jQuery);