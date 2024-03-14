/**
 * Created by truongsa on 12/18/16.
 */


(function(deparam){
    if (typeof require === 'function' && typeof exports === 'object' && typeof module === 'object') {
        try {
            var jquery = require('jquery');
        } catch (e) {
        }
        module.exports = deparam(jquery);
    } else if (typeof define === 'function' && define.amd){
        define(['jquery'], function(jquery){
            return deparam(jquery);
        });
    } else {
        var global;
        try {
            global = (false || eval)('this'); // best cross-browser way to determine global for < ES5
        } catch (e) {
            global = window; // fails only if browser (https://developer.mozilla.org/en-US/docs/Web/Security/CSP/CSP_policy_directives)
        }
        global.deparam = deparam(global.jQuery); // assume jQuery is in global namespace
    }
})(function ($) {
    var deparam = function( params, coerce ) {
        var obj = {},
            coerce_types = { 'true': !0, 'false': !1, 'null': null };

        // Iterate over all name=value pairs.
        params.replace(/\+/g, ' ').split('&').forEach(function(v){
            var param = v.split( '=' ),
                key = decodeURIComponent( param[0] ),
                val,
                cur = obj,
                i = 0,

            // If key is more complex than 'foo', like 'a[]' or 'a[b][c]', split it
            // into its component parts.
                keys = key.split( '][' ),
                keys_last = keys.length - 1;

            // If the first keys part contains [ and the last ends with ], then []
            // are correctly balanced.
            if ( /\[/.test( keys[0] ) && /\]$/.test( keys[ keys_last ] ) ) {
                // Remove the trailing ] from the last keys part.
                keys[ keys_last ] = keys[ keys_last ].replace( /\]$/, '' );

                // Split first keys part into two parts on the [ and add them back onto
                // the beginning of the keys array.
                keys = keys.shift().split('[').concat( keys );

                keys_last = keys.length - 1;
            } else {
                // Basic 'foo' style key.
                keys_last = 0;
            }

            // Are we dealing with a name=value pair, or just a name?
            if ( param.length === 2 ) {
                val = decodeURIComponent( param[1] );

                // Coerce values.
                if ( coerce ) {
                    val = val && !isNaN(val) && ((+val + '') === val) ? +val        // number
                        : val === 'undefined'                       ? undefined         // undefined
                        : coerce_types[val] !== undefined           ? coerce_types[val] // true, false, null
                        : val;                                                          // string
                }

                if ( keys_last ) {
                    // Complex key, build deep object structure based on a few rules:
                    // * The 'cur' pointer starts at the object top-level.
                    // * [] = array push (n is set to array length), [n] = array if n is
                    //   numeric, otherwise object.
                    // * If at the last keys part, set the value.
                    // * For each keys part, if the current level is undefined create an
                    //   object or array based on the type of the next keys part.
                    // * Move the 'cur' pointer to the next level.
                    // * Rinse & repeat.
                    for ( ; i <= keys_last; i++ ) {
                        key = keys[i] === '' ? cur.length : keys[i];
                        cur = cur[key] = i < keys_last
                            ? cur[key] || ( keys[i+1] && isNaN( keys[i+1] ) ? {} : [] )
                            : val;
                    }

                } else {
                    // Simple key, even simpler rules, since only scalars and shallow
                    // arrays are allowed.

                    if ( Object.prototype.toString.call( obj[key] ) === '[object Array]' ) {
                        // val is already an array, so push on the next value.
                        obj[key].push( val );

                    } else if ( {}.hasOwnProperty.call(obj, key) ) {
                        // val isn't an array, but since a second value has been specified,
                        // convert val into an array.
                        obj[key] = [ obj[key], val ];

                    } else {
                        // val is a scalar.
                        obj[key] = val;
                    }
                }

            } else if ( key ) {
                // No value was defined, so set something meaningful.
                obj[key] = coerce
                    ? undefined
                    : '';
            }
        });

        return obj;
    };
    if ($) {
        $.prototype.deparam = $.deparam = deparam;
    }
    return deparam;
});

window.megamenu_live_preview = window.megamenu_live_preview || {};
window.megamenu_live_previewing = 0;

( function( api, $ ) {
    "use strict";

    var cookies = {
        set: function ( cookieName, value, exdays ) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = cookieName + "=" + value + ";" + expires + ";path=/";
        },
        get: function( cookieName ){
            var name = cookieName + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

    };

    /**
     * Function that loads the Mustache template
     */
    var repeaterTemplate = _.memoize(function () {
        var compiled,
        /*
         * Underscore's default ERB-style templates are incompatible with PHP
         * when asp_tags is enabled, so WordPress uses Mustache-inspired templating syntax.
         *
         * @see track ticket #22344.
         */
            options = {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                escape: /\{\{([^\}]+?)\}\}(?!\})/g,
                variable: 'data'
            };

        return function (data, tplId ) {
            if ( typeof tplId === "undefined" ) {
                tplId = '#mm-item-settings-tpl';
            }
            compiled = _.template(jQuery( tplId ).html(), null, options);
            return compiled(data);
        };
    });

    var template = repeaterTemplate();


    $(document).ready( function(){

        var $document = $(document);
        var availableWidgets = $( '#widgets-left' );
        var availableMenuItems = $( '#available-menu-items' );
        var $drop = $( '<div id="megamenu-widget-modal-drop"></div>' );
        var $menuDrop = $( '<div id="megamenu-menu-modal-drop"></div>' );
        $( '#customize-controls').append( $drop );
        $( '#customize-controls').append( $menuDrop );
        var colTpl = template({}, '#megamenu-wp-col-tpl');
        var maxCol = 12; // layout 12 columns
        var megaColumns = [ 1, 2, 3, 4 ];

        window.activeMegaEditor = null;
        window.megaMenuItems = [];

        // Add Close button to widgets modal
        availableWidgets.find( '#available-widgets').append( '<a class="megamenu-close-widgets" href="#"><span class="dashicons dashicons-no-alt"></span></a>' );

        var megaPanel = function( menu_id ) {
            var megaEditor = this;
            this.menuId = menu_id;
            this._ready = 'no';
            this.control = api.Menus.getMenuItemControl( this.menuId );
            this.settingValue = {};
            this.wpValues = this.control.setting();

        }; // end function megaPanel



        megaPanel.prototype = $.extend( true, megaPanel.prototype, {

            changeId: function( menu_id ){
                this.megaPanelObj.attr( 'id', 'megamenu-id-' + menu_id );
                this.menuId = menu_id;
                this.control = api.Menus.getMenuItemControl( this.menuId );
                this.settingValue = this.control.setting();
            },

            loadData: function( open ){
                if ( this._ready == 'loading' ) {
                    return ;
                }
                this._ready = 'loading';
                var m = this;
                var id = this.menuId;
                $( '.megamenu-wp-btn[menu-id="'+id+'"]' ).addClass( 'updating-message' );
                this.ajax( {
                    action :'mega_menu_load_item_data',
                    menu_id: this.menuId
                },  function( res ){
                    m._ready = 'ok';
                    if ( res.success ) {
                        m.settingValue = res.data;
                    } else {
                        m.settingValue = {};
                    }
                    $( '.megamenu-wp-btn[menu-id="'+id+'"]' ).removeClass( 'updating-message' );
                    m.init();
                    m.sortableItems();
                    if ( typeof open !== 'undefined' && open ) {
                        m.active();
                    }
                } );
            },

            active: function(){
                if ( this._ready == 'loading' ) {
                    return ;
                }
                if ( this._ready == 'no' ) {
                    this.loadData( true );
                } else if ( this._ready == 'ok' ) {
                    var left = $( '#customize-controls' ).width();
                    $( '.megamenu-wp').removeClass( 'active' );
                    this.megaPanelObj.css( 'left', left ).addClass( 'active' );
                    this.livePreview();
                }
            },

            setPanelHeight: function( height ){
                var wh = $( window).height();
                var headerH = $( '.megamenu-tabs', this.megaPanelObj ).outerHeight();
                if ( height > wh ) {
                    height = wh;
                }
                var h = ( height - headerH ) ;

                $( '.megamenu-contents', this.megaPanelObj).css( { height: h, maxHeight: h, padding: 0 } );
            },

            livePreview: function(){
                try {
                    var iframe = $('#customize-preview iframe').contents();
                    $( '.mega-hover' , iframe ).removeClass( 'mega-hover' );
                    var menuitem = iframe.find( '#menu-item-'+this.menuId );
                    if ( window.megamenu_live_preview[ this.menuId ] ) {
                        menuitem.addClass( 'mega-hover mega-animation' );
                    } else {
                        menuitem.removeClass( 'mega-hover mega-animation' );
                    }
                } catch ( e ){

                }
            },

            hidePreview: function(){
                try {
                    var iframe = $('#customize-preview iframe').contents();
                    $( '.mega-live-view-item' , iframe ).removeClass( 'mega-hover' );
                    var menuitem = iframe.find( '#menu-item-'+this.menuId );
                    menuitem.removeClass( 'mega-hover mega-animation' );
                } catch ( e ){

                }
            },

            uniqueID: function () {
                // Math.random should be unique because of its seeding algorithm.
                // Convert it to base 36 (numbers + letters), and grab the first 9 characters
                // after the decimal.
                return '_' + Math.random().toString(36).substr(2, 9);
            },

            getWidgetTplByIid: function( widget_id ){
                var widget = availableWidgets.find( '[id^="widget-tpl-'+widget_id+'-"]');
                if ( widget.length > 0 ) {
                    return widget.eq( 0).find( '.widget' ).clone();
                }
                return false;
            },

            renderLayout: function( settingValue ){
                var megaEditor = this;
                var hasCol = false;

                var vt = $.type( settingValue );
                if ( vt !== 'object' && vt !== 'array' ) {
                    settingValue = {};
                }
                $.each( settingValue, function( rowIndex, rowData ) {
                    // Loop columns
                    var row = $( '.row', megaEditor.megaPanelObj).eq( rowIndex );
                    var numCol = rowData.length;

                    var label = megamenuSettings.single_col;
                    if ( numCol > 1 ) {
                        label = megamenuSettings.plural_col;
                        label = label.replace( '%s', numCol );
                    }

                    $( '.row-actions .action-label', row).html( label );

                    $.each( rowData, function( colIndex, colData ){
                        var colTpl = template( colData.settings , '#megamenu-wp-col-tpl');

                        var col = $( colTpl );
                        $( '.row-inner', row ).append( col );
                        hasCol = true;
                        $.each( colData.settings, function ( key, value ) {
                            megaEditor.updateColumnSettings( col, key, value );
                        } );

                        col.attr( 'data-col', colData.settings.col );
                        // Loop Items
                        $.each( colData.items, function( itemIndex, item ){
                            if ( item._item_type == 'widget' ) {
                                
                            } else if ( item._item_type == 'menu_item' ) {
                                megaEditor.addMenuItem( item, col );
                            }
                        } );

                    } );
                } );

                return hasCol;
            },

            renderDefaultLayout: function(){
                var numCol = 3;
                var megaEditor = this;
                var label = megamenuSettings.single_col;
                if ( numCol > 1 ) {
                    label = megamenuSettings.plural_col;
                    label = label.replace( '%s', numCol );
                }

                $( '.row .row-actions .action-label', megaEditor.megaPanelObj ).html( label );

                // Add default insert columns
                for ( var i = 0; i < numCol; i++) {
                    $('.megamenu-layout-area .row', megaEditor.megaPanelObj).eq(0).find('.row-inner').append(colTpl);
                }
            },

            updateColumnSettings: function( $col, key, value ) {
                var settings = $col.data( 'item-settings' ) || {};
                if ( ! settings ) {
                    settings = {};
                }
                if ( key ) {
                    settings[ key ] = value;
                    $col.data( 'item-settings', settings );
                }

                return settings;
            },

            updateData: function(){
                var megaEditor = this;
                // Get layout data
                var data = [];
                $( '.row', megaEditor.megaPanelObj ).each( function( rowIndex ){
                    data[ rowIndex ] = [];
                    $( '.col', $( this ) ).each( function( columnIndex ){
                        var colData = megaEditor.updateColumnData( $( this ) );
                        var colSettings = megaEditor.updateColumnSettings( $( this ) );
                        colSettings.col = $( this ).attr( 'data-col' ) || 1;
                        data[ rowIndex ][ columnIndex ] = {
                            settings: colSettings,
                            items: colData
                        };
                    } );
                } );
                // Get menu data
                var settingValue = _.clone( megaEditor.control.setting() );

                // Setup layout data
                settingValue.mega_menu_layout = data;

                // Get settings data
                settingValue.mega_menu_settings = megaEditor.settingForm.serialize();
                settingValue.mega_menu_settings = $.deparam( settingValue.mega_menu_settings );

                // Styling data {
                var style_settings = megaEditor.styleForm.serialize();
                style_settings = $.deparam( style_settings );

                if ( settingValue.mega_menu_settings.enable ) {
                    settingValue.mega_enable = 1;
                } else {
                    settingValue.mega_enable = 0;
                }

                // Get post form
                settingValue.mega_menu_post = megaEditor.postForm.serialize();
                settingValue.mega_menu_post = $.deparam( settingValue.mega_menu_post );
                settingValue.mega_menu_settings.style = style_settings;
                settingValue._t = megaEditor.uniqueID();

                $document.trigger( 'megamenu_data_changed', [ settingValue, megaEditor ] );

                // Let control known the data changed.
                megaEditor.control.setting.set( settingValue );

                return settingValue;
            },

            settingActions: function(){
                var megaEditor = this;
                this.settingForm.submit( function(){
                    return false;
                } );

                if ( this.settingValue.mega_enable != 1 ) {
                    $( '.field:not(.enable-field)', megaEditor.settingForm ).addClass( 'display-none hide' );
                    $( '.megamenu-tabs .setting-tab', megaEditor.megaPanelObj).addClass( 'display-none hide' );
                    $( '.megamenu-content:not(.tab-settings)', megaEditor.settingForm).addClass( 'display-none hide' );
                }
                // When input changes
                megaEditor.megaPanelObj.on('keyup change data_change', 'select, input, textarea', function (e) {
                    // e.preventDefault();
                    var data = megaEditor.updateData();


                    if ( data.mega_menu_settings.enable == 1 || data.mega_menu_settings.enable == '1' ) {
                        megaEditor.control.container.addClass( 'mega-active' );
                        $( '.megamenu-tabs .setting-tab', megaEditor.megaPanelObj).removeClass( 'display-none hide' ).removeAttr( 'style' );
                        $( '.field:not(.enable-field)', megaEditor.settingForm).removeClass( 'display-none hide' ).removeAttr( 'style' );
                        $( '.megamenu-content:not(.tab-settings)', megaEditor.settingForm).removeClass( 'display-none hide' ).removeAttr( 'style' ).removeAttr( 'style' );
                        $( '.megamenu-tabs .setting-tab[data-tab="'+data.mega_menu_settings.menu_type+'"]', megaEditor.megaPanelObj).removeClass( 'display-none hide' ).removeAttr( 'style' );

                    } else {
                        megaEditor.control.container.removeClass( 'mega-active' );
                        $( '.field:not(.enable-field)', megaEditor.settingForm ).addClass( 'display-none hide' );
                        $( '.megamenu-tabs .setting-tab', megaEditor.megaPanelObj).addClass( 'display-none hide' );
                        $( '.megamenu-content:not(.tab-settings)', megaEditor.settingForm).addClass( 'display-none hide' );
                    }


                    if ( data.mega_menu_settings.menu_type == 'layout' ) {
                        $( '.megamenu-tabs li[data-tab="layout"]', megaEditor.megaPanelObj).removeClass( 'display-none hide' );
                        $( '.megamenu-tabs li[data-tab="post"]', megaEditor.megaPanelObj).addClass( 'display-none hide' );
                    } else {
                        $( '.megamenu-tabs li[data-tab="layout"]', megaEditor.megaPanelObj).addClass( 'display-none hide' );
                        $( '.megamenu-tabs li[data-tab="post"]', megaEditor.megaPanelObj).removeClass( 'display-none hide' );
                    }

                });

                // Color Picker
                $( '.color', megaEditor.megaPanelObj ).each( function(){
                    var c = $( this );
                    c.wpColorPicker( {
                        change: function( e, w ){
                            c.val(  w.color.toString() ).trigger( 'data_change' );
                        },
                        clear: function( ){
                            c.val( '' ).trigger( 'data_change' );
                        }
                    } );

                } );

            },

            postsActions: function(){
                var megaEditor = this;
                this.postForm.find( '.list-cate-tpl').removeAttr( 'id' );
                $( '.list-sortable', this.postForm).sortable( {
                    handle: '.handle',
                    update: function(){
                        megaEditor.updateData();
                    }
                } );

                //Conditional to show more link settings

                if ( megaEditor.settingValue.mega_menu_post.show_all_link != '' || megaEditor.settingValue.mega_menu_post !== 'no' ) {
                    $( '.all_link_more_settings', megaEditor.postForm).show();
                }
                $( 'select[name="show_all_link"]', this.postForm ).on( 'change event_change', function(){
                    var v = $( this).val();
                    if ( v != '' && v != 'no' ) {
                        $( '.all_link_more_settings', megaEditor.postForm).show();
                    } else {
                        $( '.all_link_more_settings', megaEditor.postForm).hide();
                    }
                } );

                if ( megaEditor.settingValue.mega_menu_post.tabs_layout !=='no-tabs' ) {
                    $( '.show_all_link', this.postForm).show();
                } else {
                    $( '.show_all_link', this.postForm).hide();
                    $( '.all_link_more_settings', megaEditor.postForm).hide();
                }

                $( 'select[name="tabs_layout"]', this.postForm).on( 'change', function(){
                    var v = $( this).val();
                    if ( v != 'no-tabs' ) {
                        $( '.show_all_link', megaEditor.postForm).show();
                        $( 'select[name="show_all_link"]', megaEditor.postForm ).trigger('event_change');
                    } else {
                        $( '.show_all_link', megaEditor.postForm).hide();
                        $( '.all_link_more_settings', megaEditor.postForm).hide();
                    }

                } );


                var select = $( 'select.list-cate-tpl', this.postForm );

                var selectPostType = this.postForm.find( 'select.post_type' );
                var selectTax = this.postForm.find( 'select.dynamic-tax' );
                var selectTerms = this.postForm.find( 'select.select-terms' );
                var listTerms = $( '.list-sortable', megaEditor.postForm );
                var termsHistory = {};

                // add existing cats
                var cat_type =  $.type( megaEditor.settingValue.mega_menu_post.terms );
                if ( cat_type === 'object' || cat_type === 'array' ) {
                    $.each( megaEditor.settingValue.mega_menu_post.terms, function( i, term ) {

                        var html = '<div class="item"> <input type="hidden" name="terms[]" value="'+term.term_id+'"> <div class="handle">'+term.name+'</div> <a class="remove" href="#"></a></div>';
                        listTerms.append( html );

                    } );

                    termsHistory[ megaEditor.settingValue.mega_menu_post.tax ] = listTerms.html();
                }

                this.postForm.on( 'click', '.repeatable-cat .add-item-cat', function( e ){
                    e.preventDefault();
                    var id = select.val();
                    id = parseInt( id );
                    var numberItem = listTerms.find( '.item').length;
                    var p = select.closest( '.field' );
                    p.find( '.limit_post_type_msg').remove();
                    if ( ! selectPostType.hasClass( 'pro-version' ) && numberItem >= 2 ) {
                        var msg = '<div class="description limit_post_type_msg">'+megamenuSettings.limit_number_msg+'</div>';
                        p.append( msg );
                        $('option:selected', select).removeAttr('selected');
                    } else {
                        var tax = selectTax.val();
                        if ( id > 0 ) {
                            var label = $('option:selected', select).html();
                            $('option:selected', select).removeAttr('selected');
                            var find = '&nbsp;';
                            var re = new RegExp(find, 'g');
                            label = label.replace(re, '');
                            var html = '<div class="item"> <input type="hidden" name="terms[]" value="' + id + '"> <div class="handle">' + label + '</div> <a class="remove" href="#"></a></div>';
                            listTerms.append( html );
                            termsHistory[ tax ] = listTerms.html();
                            megaEditor.updateData();
                        }
                    }
                } );

                this.postForm.on( 'click', '.item .remove', function( e ){
                    e.preventDefault();
                    var menuItem = $( this ).closest( '.item');
                    menuItem.hide();
                    setTimeout( function(){
                        menuItem.remove();
                        megaEditor.updateData();
                    }, 500 );
                } );

                this.postForm.on( 'keyup change', 'input, select:not(.no-change), textarea', function( e ){
                    megaEditor.updateData();
                });

                $( '.field.tax-type, .field.cats', megaEditor.postForm ).hide();

                // When post type change
                selectPostType.on( 'change value_change', function( e ){
                   // e.preventDefault();
                    var v = $( this).val();
                    var options = '';
                    var p = selectPostType.closest( '.field' );
                    p.find( '.limit_post_type_msg').remove();
                    $('.field', megaEditor.postForm).not(p).show();
                    if ( selectPostType.hasClass( 'pro-version' ) ) {
                        if ( megamenuSettings.posts[ v ] ) {
                            $.each( megamenuSettings.posts[ v ].taxs, function( index, tax ) {
                                options += '<option value="'+tax.name+'">'+tax.label+'</option>';
                            } );

                            selectTax.html( '<option value="">----</option>' + options );
                            if ( options != '' ){
                                $( '.field.tax-type, .field.cats', megaEditor.postForm ).show();
                            } else {
                                $( '.field.tax-type, .field.cats', megaEditor.postForm ).hide();
                            }

                        }
                    } else {
                        if ( v == 'post' ) {
                            if ( megamenuSettings.posts[v] ) {
                                $.each(megamenuSettings.posts[v].taxs, function (index, tax) {
                                    options += '<option value="' + tax.name + '">' + tax.label + '</option>';
                                });

                                selectTax.html('<option value="">----</option>' + options);
                                if (options != '') {
                                    $('.field.tax-type, .field.cats', megaEditor.postForm ).show();
                                } else {
                                    $('.field.tax-type, .field.cats', megaEditor.postForm ).hide();
                                }

                            }
                        } else {
                            $('.field.tax-type, .field.cats', megaEditor.postForm ).hide();
                            $('.field', megaEditor.postForm).not(p).hide();
                            var msg = '<div class="description limit_post_type_msg">'+megamenuSettings.limit_post_type_msg+'</div>';
                            p.append( msg );

                        }
                    }

                    listTerms.html('');
                    megaEditor.postForm.find('.field.cats').hide();

                    if ( e.type !== "value_change" ) {
                        megaEditor.updateData();
                    }


                } );

                selectTax.on( 'change value_change', function( e ){
                    var t   = selectPostType.val();
                    var tax = $( this ).val();
                    var label = $( this).find('option:selected').html();
                    if ( tax != '' ) {
                        megaEditor.postForm.find('.cats .label').html(label);

                        listTerms.html('');
                        if (termsHistory[tax]) {
                            listTerms.append(termsHistory[tax]);
                            megaEditor.postForm.find('.field.cats').show();
                        }

                        if (typeof window.megamenuwpCacheTerms === "undefined") {
                            window.megamenuwpCacheTerms = {};
                        }

                        if (window.megamenuwpCacheTerms[tax]) {
                            selectTerms.html(window.megamenuwpCacheTerms[tax]);
                            megaEditor.postForm.find('.field.cats').show();
                        } else {
                            megaEditor.ajax({
                                action: 'mega_menu_load_terms',
                                post_type: t,
                                tax: tax,
                                _nonce: megamenuSettings._nonce
                            }, function (res) {
                                if (res.success) {
                                    var options = '<option value="">----</option>';
                                    $.each(res.data, function (index, tax) {
                                        options += '<option value="' + tax.term_id + '">' + tax.name + '</option>';
                                    });
                                    selectTerms.html(options);
                                    // cache items loaded and not load it again.
                                    window.megamenuwpCacheTerms[tax] = options;
                                    megaEditor.postForm.find('.field.cats').show();
                                }
                            });
                        }
                    } else {
                        megaEditor.postForm.find('.field.cats').hide();
                    }

                    if ( e.type !== "value_change" ) {
                        megaEditor.updateData();
                    }

                } );

                selectPostType.trigger( 'value_change' );
                selectTax.trigger( 'value_change' );
                try {
                    if ( megaEditor.settingValue.mega_menu_post.tax ) {
                        selectTax.find( 'option[value="'+megaEditor.settingValue.mega_menu_post.tax+'"]').attr( 'selected', 'selected' );
                        selectTax.trigger( 'value_change' );
                    }
                } catch ( e ){

                }

            },

            sortableItems: function(){
                var megaEditor = this;
                // Sortable widgets
                $(".megamenu-layout-area .sortable",megaEditor.megaPanelObj ).each( function(){
                    if ( ! $( this).hasClass( 'ui-sortable' ) ) {
                        $( this ).sortable({
                            connectWith: ".megamenu-layout-area .sortable",
                            handle: '.widget-title, .menu-item-handle',
                            change: function( event, ui ){
                                // console.log( 'change' );
                            },
                            update: function( event, ui ){
                                $( '.row', megaEditor.megaPanelObj ).each( function(){
                                    $( '.col', $( this ) ).each( function(){
                                        megaEditor.updateColumnData( $( this ) );
                                    } );
                                } );

                                megaEditor.updateData();
                            }
                        });
                    }

                } );
            },

            ajax: function( data, successCb, errorCb ){
                $.ajax( {
                    url : ajaxurl,
                    data: data,
                    type: "POST",
                    dataType: 'json',
                    success: function( res ){
                        if ( typeof successCb === "function" ) {
                            successCb( res );
                        }
                    },
                    error: function( res ) {
                        if ( typeof errorCb === "function" ) {
                            errorCb( res );
                        }
                    }
                } );
            },

            

            addMenuItem: function ( data, col ) {
                var megaEditor = this;
                var is_update = true, addCol;

                if ( col ) {
                    addCol =  col;
                    is_update = false;
                } else {
                    addCol = window.megaMenuChosingColumn;
                }

                if ( addCol ) {
                    var menu = template( data, '#tmpl-customize-control-nav_menu_item-content' );
                    menu = '<div class="menu-item"><form>' + menu + '</form></div>';
                    menu = $( menu );
                    $('.sortable', addCol ).append( menu );

                    try {
                        $( '.edit-menu-item-attr-title', menu ).val( data.attr_title );
                        $( '.edit-menu-item-title', menu ).val( data.title );
                        $( '.edit-menu-item-classes', menu ).val( data.classes );
                        $( '.edit-menu-item-description', menu ).val(  data.description );
                        $( '.edit-menu-item-url', menu ).val( data.url );
                        $( '.edit-menu-item-xfn', menu ).val( data.xfn );

                        if ( data.target == '_blank') {
                            $( 'input.edit-menu-item-target', menu).attr( 'checked', 'checked' );
                        }
                    } catch ( e ) {

                    }

                    if ( is_update ) {
                        megaEditor.updateColumnData( window.megaMenuChosingColumn );
                        megaEditor.updateData();
                    }
                }
            },

            updateColumnData: function( $col ) {
                var megaEditor = this;
                var data = [];
                $( '.sortable > div', $col ).each( function( index ){
                    var item = $( this );
                    if ( item.hasClass( 'menu-item') ) {
                        data[ index ] = megaEditor.getMenuData( item );
                    } else {
                        data[ index ] = megaEditor.getWidgetData( item );
                    }
                } );

                $col.data( 'item-data', data );
                return data;
            },

            getWidgetData: function( widget ){
                var _data = widget.find('form').serialize();
                _data = $.deparam( _data );
                var data = {
                    _item_type: 'widget',
                    widget_id: _data.id_base,
                    settings: {}
                };
                try {
                    $.each( _data[ 'widget-'+_data.id_base ], function( _id, _widgetSettings ) {
                        data.settings = _widgetSettings;
                    } );
                } catch ( e ) {

                }
                return data;
            },

            getMenuData: function( menu ){

                var data = {};
                var itemId = $( '.menu-item-data-db-id', menu).val() || '';
                //itemId = itemId.split( '-' );
                data._item_type = 'menu_item';

                data.menu_item_id = itemId ;

                // try get menu atts
                var item_data = api.Menus.availableMenuItems.findWhere( { id: data.menu_item_id } );
                if ( item_data ) {
                    data.item_type_label = item_data.attributes.type_label;
                    data.original_title = item_data.attributes.title;
                    data.title = item_data.attributes.title;
                    data.object_id = item_data.attributes.object_id;
                    data.type = item_data.attributes.type;
                    data.object = item_data.attributes.object;
                } else {
                    data.type = 'custom';
                    data.item_type = 'custom';
                    data.object = 'custom';
                    data.item_type_label = api.Menus.data.l10n.custom_label;
                }

                data.status = 'publish';
                data.target = '';
                if ( $( 'input.edit-menu-item-target', menu).is( ':checked' ) ) {
                    data.target = '_blank';
                }

                data.attr_title = $( '.edit-menu-item-attr-title', menu ).val();
                data.title = $( '.edit-menu-item-title', menu).val();
                data.classes = $( '.edit-menu-item-classes', menu ).val();
                data.description = $( '.edit-menu-item-description', menu ).val();
                data.url = $( '.edit-menu-item-url', menu ).val() || '';
                data.xfn = $( '.edit-menu-item-xfn', menu ).val();

                return data;
            },

            setup: function(){
                var megaEditor = this;
                if ( typeof this.settingValue !== 'object' ) {
                    this.settingValue = {};
                }
                this.settingValue= $.extend( {}, {
                    mega_menu_layout: {},
                    mega_menu_settings: {},
                    mega_menu_post: {}
                }, this.settingValue );

                window._megaMenuCache = window._megaMenuCache || {};

                if ( typeof this.settingValue.mega_menu_layout === "undefined" ) {
                    if ( typeof window._megaMenuCache[ menu_id ] !== "undefined" ){
                        $.each( window._megaMenuCache[ menu_id ], function( key, value ) {
                            megaEditor.settingValue[ key ] = value;
                        } );
                    }
                }

                if ( ! typeof this.settingValue.mega_menu_settings.style === "undefined" ) {
                    this.settingValue.mega_menu_settings.style = {};
                }

                // Add mega panel
                this.megaPanelObj = $( template( {
                    menu_id: this.menuId,
                    title:  this.wpValues.title || this.wpValues.original_title,
                    settings: this.settingValue.mega_menu_settings || {},
                    postSettings: this.settingValue.mega_menu_post || {},
                    style: this.settingValue.mega_menu_settings.style || {}
                } ) );
                $('.wp-full-overlay').append( megaEditor.megaPanelObj );

                this.settingForm = $( 'form.mega-settings-form', this.megaPanelObj );
                this.styleForm = $( 'form.mega-style-form', this.megaPanelObj );
                this.postForm = $( 'form.mega-post-form', this.megaPanelObj );

                var dragElement = $( ".megamenu-drag", megaEditor.megaPanelObj );
                dragElement.draggable({
                    axis: "y",
                    containment: "window",
                    iframeFix: true,
                    drag: function( event, ui ) {
                        var wh = $( window).height();
                        var headerH = $( '.megamenu-tabs', megaEditor.megaPanelObj ).outerHeight();
                        var h = wh - ( ui.offset.top + headerH ) ;
                        if ( h <  headerH ) {
                            dragElement.css( top, -1 );
                        } else {
                            $( '.megamenu-contents', megaEditor.megaPanelObj).css( { height: h, maxHeight: h, padding: 0 } );
                        }

                    }
                });

                // Live Preview
                $( '.panel-live-view', megaEditor.megaPanelObj).on( 'click', function( e ){
                    e.preventDefault();
                    if ( ! window.megamenu_live_preview[ megaEditor.menuId ] ) {
                        window.megamenu_live_preview[ megaEditor.menuId ] = true;
                        window.megamenu_live_previewing = 'menu-item-'+megaEditor.menuId;
                        $( this ).addClass( 'viewing' );
                    } else {
                        window.megamenu_live_preview[ megaEditor.menuId ] = false;
                        $( this ).removeClass( 'viewing' );
                    }
                    megaEditor.livePreview();
                } );

                // Add/Change number of columns
                megaEditor.megaPanelObj.on('click', '.row .col-change', function (e) {
                    e.preventDefault();
                    var act = $( this).attr( 'data-act' ) || 'increment';
                    var row = $( this).closest( '.row' );
                    var n = $( '.col', row ).length;
                    var nextCols = n ;
                    var index = $.inArray( n, megaColumns );

                    if ( index > -1 ) {
                        // Increment
                        if ( act == 'increment' ){
                            if ( index < megaColumns.length - 1 ) {
                                nextCols = megaColumns[ index + 1 ];
                            } else {
                                nextCols = megaColumns[ megaColumns.length - 1 ];
                            }
                        } else {
                            if ( index > -1 ) {
                                nextCols = megaColumns[ index - 1 ];
                            } else {
                                nextCols = megaColumns[ 0 ];
                            }
                        }

                    }

                    if ( ! nextCols ) {
                        nextCols =  1;
                    }

                    var label = megamenuSettings.single_col;
                    if ( nextCols > 1 ) {
                        label = megamenuSettings.plural_col;
                        label = label.replace( '%s', nextCols );
                    }
                    $( '.row-actions .action-label', row).html( label );
                    var i ;
                    if ( nextCols !=  n ) {
                        var _n = Math.round( maxCol / nextCols );
                        var diff = Math.abs( nextCols - n );
                        if ( diff > 0 ) {
                            if ( nextCols > n) {
                                for ( i = 0; i < diff; i++) {
                                    $( '.row-inner', row ).append(colTpl);
                                }
                                megaEditor.sortableItems();
                            } else {
                                // Need to move item inside remove column to other column that not remove.
                                var columnNotRemove = $('.col', row).eq( n - diff - 1 );
                                var removeCol;
                                for ( i = 0; i < diff; i++) {
                                    n = $('.col', row ).length;
                                    removeCol = $('.col', row).eq(n - 1);
                                    $( '.sortable', columnNotRemove).append( $( '.sortable > div', removeCol) );
                                    removeCol.remove();
                                }
                            }
                        }

                        $( '.col', row ).attr( 'data-col', _n );
                    }

                    megaEditor.updateData();

                });

                // When column heading changed
                // column-heading
                megaEditor.megaPanelObj.on('change keyup', '.col .column-heading', function (e) {
                    var col = $( this ).closest( '.col' );
                    var h = $( this).val();
                    megaEditor.updateColumnSettings( col, 'heading', h );
                    megaEditor.updateData();
                });


                // Resize column
                megaEditor.megaPanelObj.on('click', '.row .col .col-resize', function (e) {
                    e.preventDefault();
                    var row = $( this ).closest( '.row' );
                    var col = $( this ).closest( '.col' );
                    var totalCol = $( '.col', row).length;
                    var colIndex = col.index();
                    var numCol = col.attr( 'data-col' ) || 12;
                    numCol = parseInt( numCol );
                    var action = $( this).attr( 'data-act' ) || '';

                    var nextDataCol, preDataCol;

                    var prevCol, nexCol;
                    // If Has left column
                    if ( colIndex > 0 ) {
                        prevCol = $( '.col', row).eq( colIndex - 1 );
                    }

                    // If has right column
                    if ( colIndex <= totalCol - 2 ) {
                        nexCol = $( '.col', row ).eq( colIndex + 1 );
                    }

                    if ( action == 'left' ) {
                        if ( prevCol ) {
                            preDataCol = prevCol.attr( 'data-col' ) || 1;
                            preDataCol = parseInt( preDataCol );
                            if ( preDataCol > 1 ) {
                                prevCol.attr( 'data-col', preDataCol - 1 );
                                megaEditor.updateColumnSettings( prevCol, 'col', preDataCol - 1 );

                                // Make current col bigger
                                col.attr( 'data-col', numCol + 1 );
                                megaEditor.updateColumnSettings( col, 'col', numCol + 1 );
                            }

                        } else {
                            if ( numCol > 1 ) {
                                col.attr( 'data-col', numCol - 1 );
                                megaEditor.updateColumnSettings( col, 'col', numCol - 1 );
                                if ( nexCol ) {
                                    nextDataCol = nexCol.attr( 'data-col' ) || 1;
                                    nextDataCol = parseInt( nextDataCol );
                                    nexCol.attr( 'data-col', nextDataCol + 1 );
                                    megaEditor.updateColumnSettings( nexCol, 'col', nextDataCol + 1 );
                                }
                            }

                        }

                    } else { // right
                        if ( ! nexCol && ! prevCol ) {
                            if ( numCol < maxCol  ) {
                                col.attr('data-col', numCol + 1 );
                                megaEditor.updateColumnSettings( col, 'col', numCol + 1 );
                            }
                        } else {
                            if (nexCol) {
                                nextDataCol = nexCol.attr('data-col') || 1;
                                nextDataCol = parseInt(nextDataCol);
                                if (nextDataCol < maxCol && nextDataCol > 1) {
                                    nexCol.attr('data-col', nextDataCol - 1);
                                    megaEditor.updateColumnSettings( nexCol, 'col', nextDataCol - 1 );
                                    // Make current col bigger
                                    col.attr('data-col', numCol + 1);
                                    megaEditor.updateColumnSettings( col, 'col', numCol + 1 );
                                }
                            } else {
                                if (numCol > 1) {
                                    col.attr('data-col', numCol - 1);
                                    megaEditor.updateColumnSettings( col, 'col', numCol - 1 );
                                    if (prevCol) {
                                        preDataCol = prevCol.attr('data-col') || 1;
                                        preDataCol = parseInt(preDataCol);
                                        prevCol.attr('data-col', preDataCol + 1);
                                        megaEditor.updateColumnSettings( prevCol, 'col', preDataCol + 1 );
                                    }
                                }
                            }
                        }
                    }

                    megaEditor.updateData();

                });

                // Tabs action
                megaEditor.megaPanelObj.on('click', '.megamenu-tabs li:not(.no-action)', function (e) {
                    e.preventDefault();
                    var li = $(this);
                    megaEditor.megaPanelObj.find('.megamenu-tabs li').removeClass('active');
                    li.addClass('active');
                    var c = li.attr('data-tab') || '';
                    if (c) {
                        $('.megamenu-contents .megamenu-content', megaEditor.megaPanelObj ).removeClass('active');
                        $('.megamenu-contents .megamenu-content.tab-' + c, megaEditor.megaPanelObj ).addClass('active');
                    }
                });


                // Toggle Widget settings
                megaEditor.megaPanelObj.on('click', '.col .widget .widget-action', function (e) {
                    e.preventDefault();
                    var widget = $(this).closest('.widget');
                    widget.toggleClass('open');
                    if (widget.hasClass('open')) {
                        $('.widget-inside', widget).slideDown(200);
                    } else {
                        $('.widget-inside', widget).slideUp(200);
                    }

                });

                // Open modal Add widget to selected column
                megaEditor.megaPanelObj.on('click', '.mega-col-widgets .add-item', function (e) {
                    e.preventDefault();
                    var widgets = $(this).closest('.mega-col-widgets');
                    if ( typeof megamenuSettings.limit_widget_msg !== "undefined" ) {
                        if ( $( '.limit_widget_msg', widgets).length <= 0 ) {
                            widgets.append( '<div class="limit_widget_msg description">'+ megamenuSettings.limit_widget_msg + '</div>');
                        }
                    } else {

                        

                    }
                });


                // Open modal Add menu items to selected column
                megaEditor.megaPanelObj.on('click', '.mega-col-widgets .add-menu', function (e) {
                    e.preventDefault();
                    var widgets = $(this).closest('.mega-col-widgets');
                    $('.mega-col-widgets', megaEditor.megaPanelObj ).removeClass('active');
                    widgets.addClass('active');
                    window.megaMenuChosingColumn = widgets;
                    $('body').addClass('mega-menu-adding-items');
                    availableMenuItems.addClass('megamenu-menu-items');
                    $( '.item-added', availableMenuItems).removeClass( 'item-added' );
                    $menuDrop.show();
                });

                $document.on( 'click', '.add-new-menu-item', function(){
                    $('body').removeClass('mega-menu-adding-items');
                    availableMenuItems.removeClass('megamenu-menu-items');
                } );

                // Toggle Menu settings
                megaEditor.megaPanelObj.on('click', '.menu-item .item-edit', function (e) {
                    e.preventDefault();
                    var menu = $(this).closest('.menu-item');
                    menu.toggleClass('menu-item-edit-active');
                    if (menu.hasClass('menu-item-edit-active')) {
                        $('.menu-item-settings', menu).slideDown(200);
                    } else {
                        $('.menu-item-settings', menu).slideUp(200);
                    }
                });

                // When input changes
                megaEditor.megaPanelObj.on('keyup change', '.col select, .col input, .col input, .col textarea', function (e) {
                    // e.preventDefault();
                    // Live edit menu title
                    if ( $( this).hasClass( 'edit-menu-item-title' ) ) {
                        var title =  $( this).val();
                        $( this ).closest( '.menu-item').find( '.menu-item-title').text( title );
                    }
                    var col = $(this).closest('.col');
                    megaEditor.updateColumnData( col );
                    megaEditor.updateData();
                });


                // Remove Widget
                megaEditor.megaPanelObj.on('click', '.widget .widget-control-remove', function (e) {
                    e.preventDefault();
                    var widget = $(this).closest('.widget');
                    var col = widget.closest('.col');
                    widget.hide( 500, function(){
                        widget.remove();
                        megaEditor.updateColumnData( col );
                        megaEditor.updateData();
                    } );

                });

                // Remove Menu
                megaEditor.megaPanelObj.on('click', '.menu-item .item-delete', function (e) {
                    e.preventDefault();
                    var menu = $(this).closest('.menu-item');
                    var col = menu.closest('.col');
                    menu.hide( 500, function(){
                        menu.remove();
                        megaEditor.updateColumnData( col );
                        megaEditor.updateData();
                    } );
                });


                // Save widget settings
                megaEditor.megaPanelObj.on('click', '.megamenu-layout-area .widget .widget-control-save', function (e) {
                    e.preventDefault();
                    // var widget = $(this).closest('.widget');
                    // var data = megaEditor.getWidgetData( widget );
                });


                // Trigger event when menu item title changed
                $( '.edit-menu-item-title', megaEditor.control.container ).on( 'keyup change', function(){
                    var title = $( this).val();
                    if ( title ) {
                        $( '.panel-heading a', megaEditor.megaPanelObj).text( title );
                    } else {
                        $( '.panel-heading a', megaEditor.megaPanelObj ).text( 'Untitled' );
                    }
                } );

                // When click to menu title on mega panel we should open current menu control.
                megaEditor.megaPanelObj.on('click', '.mega-open-control', function (e) {
                    e.preventDefault();
                    megaEditor.control.focus();
                });

                // When close mega panel
                megaEditor.megaPanelObj.on('click', '.close-mega-panel', function (e) {
                    e.preventDefault();
                    megaEditor.megaPanelObj.removeClass( 'active' );
                    window.activeMegaEditor = null;
                    megaEditor.hidePreview();
                });


                // When Style input changes
                megaEditor.megaPanelObj.on('change', 'input.input-css', function (e) {
                    var styles = [ 'style_top', 'style_bottom', 'style_left', 'style_right' ];
                    var c = $( this );
                    var p = c.parent();
                    var v = c.val();
                    var n = c.attr( 'name' );

                    // Check have value ?
                    var s;
                    var check = true;
                    var tpl_item, tpl_v;
                    for ( var i = 0; i < styles.length; i++) {
                        s = styles[i];
                        tpl_item = $('>.' + s, p);
                        if ( n != tpl_item.attr( 'name' ) ) {
                            tpl_v = tpl_item.val() || '';
                            if (tpl_v) {
                                check = false;
                            }
                        }
                    }
                    if ( check ) {
                        for ( var i = 0; i < styles.length; i++) {
                            s = styles[i];
                            tpl_item = $('>.' + s, p );
                            if ( n != tpl_item.attr( 'name' ) ) {
                                tpl_v = tpl_item.val(v);
                            }
                        }
                    }

                    megaEditor.updateData();
                });
            },

            init: function(){

                this.setup();

                var hasCol = false;

                if ( this.settingValue.mega_menu_layout ) {
                    // Loop rows
                    hasCol = this.renderLayout( this.settingValue.mega_menu_layout );
                }

                if ( ! hasCol ) {
                    this.renderDefaultLayout();
                }

                this.settingActions();
                this.postsActions();
            },

        });


       /// ---------------------------------------------------------------------------------------------------------------------------------------

        

        // Click to widget to add new menu items
        $document.on('click', '#available-menu-items.megamenu-menu-items .menu-item-tpl', function (e) {
            e.preventDefault();
            if ( window.activeMegaEditor ) {
                var item = $(this);
                var data = {};
                var menu_item_id = item.attr( 'data-menu-item-id' ) || '';

                var item_data = api.Menus.availableMenuItems.findWhere( { id: menu_item_id  } );
                data.menu_item_id = menu_item_id;

                if ( item_data ) {
                    data.item_type_label = item_data.attributes.type_label;
                    data.original_title = item_data.attributes.title;
                    data.title = item_data.attributes.title;
                    data.object_id = item_data.attributes.object_id;
                    data.object = item_data.attributes.object;
                }
                window.activeMegaEditor.addMenuItem( data );
            }
        });


        // Add custom link to mega
        $document.on( 'click', '#custom-menu-item-submit', function( e ){
            e.preventDefault();

            if ( window.activeMegaEditor ) {
                var data = {
                    item_type: 'custom',
                    url: $( '#custom-menu-item-url').val(),
                    title: $( '#custom-menu-item-name').val(),
                    item_type_label: api.Menus.data.l10n.custom_label,
                    type: 'custom',
                    object: 'custom',
                };

                if ( '' === data.title ) {
                    return;
                } else if ( '' === data.title || 'http://' === data.title ) {
                    return;
                }

                // Reset the custom link form.
                $( '#custom-menu-item-url').val( 'http://' );
                $( '#custom-menu-item-name').val( '' );

                window.activeMegaEditor.addMenuItem( data );
            }

        } );

        // Close widgets modal
        $menuDrop.on('click', function (e) {
            e.preventDefault();

            availableMenuItems.removeClass('megamenu-menu-items');
            if ( window.megaMenuChosingColumn ) {
                window.megaMenuChosingColumn.removeClass( 'active' );
                window.megaMenuChosingColumn = null;
            }

            $menuDrop.hide( 100, function () {
                $('body').removeClass('mega-menu-adding-items');
            });
        });

        // Close widgets modal
        $drop.on('click', function (e) {
            e.preventDefault();
            availableWidgets.removeClass('megamenu-widgets');
            if ( window.megaMenuChosingColumn ) {
                window.megaMenuChosingColumn.removeClass( 'active' );
                window.megaMenuChosingColumn = null;
            }
            $drop.hide( 0, function () {
                $('body').removeClass('no-widgets-animate');
            });
        });


        // Set up Mega menu When click
        $document.on( 'click', '.megamenu-wp-btn', function( e ) {
            e.preventDefault();
            var id = $( this).attr( 'menu-id' );
            if ( id ) {
                if ( ! window.megaMenuItems[ id ] ) {
                    window.megaMenuItems[ id ] = new megaPanel( id );
                }
                window.activeMegaEditor = window.megaMenuItems[ id ];
                window.activeMegaEditor.active();
            }

        } );

        // Close widget when click x button
        $document.on( "click", '.megamenu-close-widgets', function( e ) {
            e.preventDefault();
            if ( availableWidgets.hasClass( 'megamenu-widgets' ) ) {
                $drop.trigger( 'click' );
            }
        } );

        // When press ESC button will hide all menu panel
        $document.on( "keydown", function( event ) {
           if ( event.which === 27 ) {
               // Check if widget modal is open
               if ( availableWidgets.hasClass( 'megamenu-widgets' ) ) {
                   $drop.trigger( 'click' );
               } else if ( availableMenuItems.hasClass( 'megamenu-menu-items' ) ) {
                   $menuDrop.trigger( 'click' );
               } else {
                   $('.megamenu-wp').removeClass( 'active' );
                   if ( window.activeMegaEditor ) {
                       window.activeMegaEditor.hidePreview();
                   }
                   window.activeMegaEditor = null;
               }

           }
        });


        // When click out site mega panel
       $document.on( 'click', function (e)
        {
            // Ensure not click on panel
            if ( window.activeMegaEditor ) {
                if (activeMegaEditor._ready === 'ok') {

                    if (
                        !window.activeMegaEditor.megaPanelObj.is(e.target) // if the target of the click isn't the menu panel...
                        && window.activeMegaEditor.megaPanelObj.has(e.target).length === 0 // ... nor a descendant of the menu panel
                    ) {
                        //check not click on cont
                        if (
                            !window.activeMegaEditor.control.container.is(e.target) // if the target of the click isn't the control...
                            && window.activeMegaEditor.control.container.has(e.target).length === 0 // ... nor a descendant of the control
                        ) {

                            if (
                                !$drop.is(e.target) // if the target of the click isn't the drop...
                                && $drop.has(e.target).length === 0 // ... nor a descendant of the drop
                            ) {

                                if (
                                    !$menuDrop.is(e.target) // if the target of the click isn't the menu drop...
                                    && $menuDrop.has(e.target).length === 0 // ... nor a descendant of the menu drop
                                ) {

                                    if (
                                        !availableWidgets.is(e.target) // if the target of the click isn't the availableWidgets...
                                        && availableWidgets.has(e.target).length === 0 // ... nor a descendant of the availableWidgets
                                    ) {
                                        if (
                                            !availableMenuItems.is(e.target) // if the target of the click isn't the availableMenuItems...
                                            && availableMenuItems.has(e.target).length === 0 // ... nor a descendant of the availableMenuItems
                                        ) {
                                            if ($(e.target).closest('.media-modal').length <= 0) {
                                                $('.megamenu-wp').removeClass('active');
                                                window.activeMegaEditor = null;
                                            }

                                        }

                                    }

                                }
                            }

                        }
                    }

                }
            }

        });


        // TEST
        /*
        var test_id = 12;
        if( $( '#customize-control-nav_menu_item-'+test_id).length > 0 ) {
            api.control( 'nav_menu_item['+test_id+']' ).focus();
            setTimeout( function(){
                window.megaMenuItems[ test_id ] = new megaPanel( test_id );
                window.activeMegaEditor = window.megaMenuItems[ test_id ];
                window.activeMegaEditor.active();
            }, 3000 );
        }
        */

        // End test

        if ( $( '#mega-custom-css').length <= 0 ) {
            $( 'head').append( '<style id="mega-custom-css"></style>' );
        }

        var setMenuCss = function(){
            var h = $( window ).height();
            var hH = 80;
            var minH = 300;
            var ph = 0;
            if (  h/2 > minH ) {
                ph = h / 2;
            } else {
                if ( minH < h ){
                    ph =  minH;
                } else {
                    ph = h;
                }
            }

            if ( ph > minH ) {
                ph = minH;
            }

            var css = ' body .megamenu-contents{ height: '+( ph-hH )+'px; }';
            $( '#mega-custom-css').html( css );
            // Update Panel Postion

            setTimeout( function(){
               if ( typeof window.activeMegaEditor !== "undefined" && window.activeMegaEditor ) {
                   var left = $( '#customize-controls' ).width();
                   window.activeMegaEditor.megaPanelObj.css( 'left', left );
               }
            }, 300 );

        };

        setMenuCss();
        $( window).resize( function(){
            setMenuCss();
        } );

    } );

    // Active menu when enable mega checked
    function active_nav_mega_to_item( menu_id, active  ){
        api.each( function( setting ) {
            if ( /^nav_menu_item\[/.test( setting.id ) && false !== setting() ) {
                var m = api.control( setting.id );
                if ( m.params.section == 'nav_menu['+menu_id+']' ) {
                    if ( active ) {
                        m.container.addClass( 'mega-nav-active' );
                    } else {
                        m.container.removeClass( 'mega-nav-active' );
                    }
                }
            }
        } );
    }

    function add_settings_to_nav_menu(){
        api.each(function( setting ) {

            if ( /^nav_menu\[/.test( setting.id ) && false !== setting() ) {
                // navMenuCount += 1;
                var control = api.control(setting.id);
                if (control) {

                    var menu_id = _.clone(control.params.menu_id);
                    var settingValue = _.clone(control.setting());
                    settingValue.menu_id = menu_id;

                    if ( megamenuSettings.mega_menus[ menu_id ] == 1 ) {
                        active_nav_mega_to_item( menu_id , true );
                    } else {
                        active_nav_mega_to_item( menu_id , false );
                    }

                    if ( typeof control._mega_settings === "undefined" || '' == control._mega_settings ) {
                        control._mega_settings = true;

                        var setting_tpl = template(settingValue, '#megamenu-wp-settings');
                        var $setting_tpl = $(setting_tpl);
                        $('#customize-control-nav_menu-'+menu_id+'-auto_add').prepend($setting_tpl);

                        $setting_tpl.on('change keyup', 'input, select', function () {
                            var name = $(this).attr('data-setting-name') || '';
                            if (name) {
                                var input = $(this);
                                var value = input.val();
                                if (input.prop("tagName") == 'INPUT') {
                                    if (input.prop("type") == 'checkbox') {
                                        if (!input.is(':checked')) {
                                            value = '';
                                        }
                                    }
                                }

                                var settingValue = _.clone(control.setting());
                                settingValue[name] = value;
                                if ( name == 'mega_enable' ) {
                                    if ( value ) {
                                        active_nav_mega_to_item( menu_id , true );
                                    } else {
                                        active_nav_mega_to_item( menu_id , false );
                                    }
                                }
                                $(document).trigger('menu_nav_changed', settingValue);
                                control.setting.set(settingValue);
                            }
                        });
                    } else {
                        // Do not add settings again
                    }
                } // end if control
            }
        });
    }


    // Add check box to activate or disactivate mega menu
    window.mega_ready = false;
    api.bind( 'ready', function( e, b ) {
        //console.log( 'ready e', e );
        window.mega_ready = true;
		setTimeout( function(){
			add_settings_to_nav_menu();
		}, 1200 );
    } );

    api.bind( 'add', function( e, b ) {
        //console.log( 'control add' );
        if (  window.mega_ready ) {
            setTimeout( function(){
               add_settings_to_nav_menu();
           }, 1200 );
        }
    } );


    api.bind("saved", function( saveData, b ) {

        if ( typeof saveData.nav_menu_item_updates !== "undefined" ) {
            $.each( saveData.nav_menu_item_updates, function( menuItemIndex, item ){
                // previous_post_id, post_id
                if ( ! item.error ) {
                    if ( item.previous_post_id && item.previous_post_id < 0 ) {
                        if ( ! window.megaMenuItems[ item.previous_post_id ] ) {
                            window.megaMenuItems[ item.post_id ] =  window.megaMenuItems[ item.previous_post_id ];
                            window.megaMenuItems[ item.post_id].changeId( item.post_id );
                        }
                    }
                }
            } );
        }
        // To do need to update enable dataa when changed
        if ( typeof saveData.nav_menu_updates !== "undefined" ) {

            $.each( saveData.nav_menu_updates, function( itemIndex, item ){
                // previous_post_id, post_id
                var _control;
                if ( ! item.error ) {
                    megamenuSettings.mega_menus[ item.term_id ] = false;

                    if ( typeof item.saved_value !== "undefined") {

                        if ( typeof item.saved_value.mega_enable !== "undefined") {

                            if (item.saved_value.mega_enable == 1 || item.saved_value.mega_enable == "1") {
                                megamenuSettings.mega_menus[item.term_id] = 1;
                                _control = api.control('nav_menu[' + item.term_id + ']');
                                if ( typeof _control == 'undefined' ) {
                                    _control = api.control('nav_menu[' + item.previous_term_id + ']');
                                }

                                if ( _control ) {
                                    var settingValue = _.clone(_control.setting());
                                    settingValue.menu_id = item.term_id;

                                    var setting_tpl = template(settingValue, '#megamenu-wp-settings');
                                    var $setting_tpl = $(setting_tpl);
                                    $('.mega-menu-setings', _control.container).html($setting_tpl.find('.mega-menu-setings').html());
                                }
                            }
                        }
                    }

                }
            } );

        }

    } );


    // -------- End Handle Event -------------------------

    /**
     * @See app/public/wp-admin/js/customize-nav-menus.js
     * line 1300
     */
    api.controlConstructor['nav_menu_item'].prototype.ready = function () {
        if ( 'undefined' === typeof this.params.menu_item_id ) {
            throw new Error( 'params.menu_item_id was not defined' );
        }

        this._setupControlToggle();
        this._setupReorderUI();
        this._setupUpdateUI();
        this._setupRemoveUI();
        this._setupLinksUI();
        this._setupTitleUI();

        // start custom code
        var control = this;
        var settingValue = control.setting();
        var menu_id = this.params.menu_item_id;

        var megaButton = $( '<button type="button" menu-id="'+menu_id+'" class="button megamenu-wp-btn">'+megamenuSettings.mega_settings_label+'</button>' );
        $( '.item-title', control.container ).append( '<span class="mega-stt">'+megamenuSettings.mega +'</span>' );
        // Check if item has cache
        if ( typeof settingValue.mega_menu_layout === "undefined" || '' == settingValue.mega_menu_layout ) {
            $.ajax( {
                url: ajaxurl,
                data: {
                    action: 'mega_menu_load_setting',
                    menu_id: menu_id
                },
                cache: false,
                error: function(){
                    megaButton.insertBefore( $( '.menu-item-actions' , control.container ) );
                },
                success: function( res ){
                    if ( res.success ) {
                        window._megaMenuCache = window._megaMenuCache || {};
                        window._megaMenuCache[ menu_id ] = res.data;
                    }
                    megaButton.insertBefore( $( '.menu-item-actions' , control.container ) );
                    if ( window._megaMenuCache[ menu_id ].mega_menu_settings.enable == 1 || window._megaMenuCache[ menu_id ].mega_menu_settings.enable == "1" ) {
                        control.container.addClass( 'mega-active' );
                    }
                }
            } );
        } else {
            megaButton.insertBefore( $( '.menu-item-actions' , control.container ) );

            if ( settingValue.mega_menu_settings.enable == 1 || settingValue.mega_menu_settings.enable == "1" ) {
                control.container.addClass( 'mega-active' );
            }
        }

        $( document).trigger( 'megamenu_item_ready', [ control, menu_id ] );

    };


} )( wp.customize , jQuery );