// jQuery debounce
!function(t,n){let o,u=t.jQuery||t.Cowboy||(t.Cowboy={});u.throttle=o=function(t,o,e,i){let r,a=0;function c(){let u=this,c=+new Date-a,f=arguments;function d(){a=+new Date,e.apply(u,f)}i&&!r&&d(),r&&clearTimeout(r),i===n&&c>t?d():!0!==o&&(r=setTimeout(i?function(){r=n}:d,i===n?t-c:t))}return"boolean"!=typeof o&&(i=e,e=o,o=n),u.guid&&(c.guid=e.guid=e.guid||u.guid++),c},u.debounce=function(t,u,e){return e===n?o(t,u,!1):o(t,e,!1!==u)}}(this);

/**
 * Quick Start
 */
function save_key_via_quick_start( key = '' ){
    let $main_input = jQuery('#eod_option_api_key');
    if($main_input.length && key) {
        $main_input.val(key);
        $main_input.closest('form').find('#submit').click();
    }
}
jQuery(document).on('submit', '#quick_start_form', function(e){
    e.preventDefault();
    save_key_via_quick_start(jQuery('#quick_start_form .key_input').val());
});
jQuery(document).on('paste', '#quick_start_form .key_input', function(e){
    save_key_via_quick_start((e.originalEvent || e).clipboardData.getData('text/plain'));
});



function eod_tpl_target( item ){
    let html = '<span class="bold">' + item['code'] + '</span> ' +
               '<span class="sub">' + item['exchange'] + '</span> ';
    if(item['name'])
        html += '<span> - ' + item['name'] + '</span>'
    return html;
}

function getEodCurrencyList(){
    if(!eod_service_data || !eod_service_data.converter_targets) return [];
    let list = [],
        targets = eod_service_data.converter_targets;
    for (let type of Object.keys(targets))
        for (let code of targets[type])
            list.push( {type: type, code: code} );
    list.sort(function(a, b) {
        return (a.code+a.type).localeCompare(b.code+b.type);
    })
    return list;
}

/**
 * Copy shortcode by click
 */
jQuery(document).on('click', '.eod_shortcode_result, .eod_shortcode .copy_btn', function(){
    let $shortcode = jQuery(this).closest('.eod_shortcode').find('.eod_shortcode_result'),
        text = $shortcode.text().replace(/(\r\n|\n|\r)/gm, "").replace(/([ ])\1+/g, "");
    navigator.clipboard.writeText( text ).then( () => {
        $shortcode.siblings('.copied').css({opacity: '1'});
        setTimeout(() => {
            $shortcode.siblings('.copied').css({opacity: '0'});
        }, 3000);
    });
});

/**
 * Color picker
 */
jQuery( function(){
    jQuery('.eod_color_picker').wpColorPicker();
});

/**
 * Search tickers by name/code with EOD API
 * @param $element jQuery   - search input element
 */
function eod_search_input_api($element){
    if( $element.attr('data-stock-type') ) {
        $element.data('stock-type', $element.attr('data-stock-type'));
        $element.removeAttr('data-stock-type');
    }

    // Use debounce and wait for input to stop
    $element.keyup( jQuery.debounce(400, function(e){
        let $input = jQuery(this),
            EodSelector = $input.closest('.eod_search_box').data('EodSelector');
        if (!e.target.value){
            EodSelector.$options.html('');
            return;
        }

        // Find suitable tickers by name/code
        jQuery.ajax({
            dataType: "json",
            method: "POST",
            url: eod_ajax_url,
            data: {
                'action': 'search_eod_item_by_string',
                'nonce_code': eod_ajax_nonce,
                'string': e.target.value,
            }
        }).always((data) => {
            if(data.error){
                console.log('EOD-error: ' +data.error, e.target.value);
                EodSelector.setOptionsMsg('error', 'EOD-error: '+data.error);
            }
        }).done((data) => {
            let counter = 0;
            EodSelector.$options.html('');
            if(!data.error)
                jQuery.each(data, function (i, raw_item) {
                    // Turn all keys of an object to lower case
                    // Scripts in various places rely on lower case names
                    let item = Object.fromEntries(
                        Object.entries(raw_item).map(([k, v]) => [k.toLowerCase(), v])
                    );

                    // Take items only with certain type
                    // TODO: API not return type
                    // let stock_type =  $input.data('stock-type');
                    // if(stock_type && stock_type.toLowerCase() !== item['type'].toLowerCase() )
                    //     return;

                    // Add option to selector
                    EodSelector.addOptionItem( eod_tpl_target(item), item )
                    counter ++;
                });
            if( counter === 0 || (data.error && data.error === 'no result from api'))
                EodSelector.setOptionsMsg('warning', 'No results. Change the search text.');
        });
    }));
}
/** Search currencies by code in prepared list
 * @param $element jQuery   - search input element
 */
function eod_search_input_currency($element) {
// Use debounce and wait for input to stop
    $element.keyup( jQuery.debounce(300, function(e){
        let $input = jQuery(this),
            s = jQuery(this).val().toUpperCase(),
            EodSelector = $input.closest('.eod_search_box').data('EodSelector');

        // Reset previous search result
        EodSelector.$options.html('');

        // Ignore empty input
        if (!s) return;

        // Add option to selector
        let counter = 0;
        for(let currency of getEodCurrencyList())
            if( (currency.code+'.'+currency.type).toUpperCase().includes(s) ) {
                EodSelector.addOptionItem(eod_tpl_target({code: currency.code, exchange: currency.type}), currency);
                counter ++;
            }

        // No results msg
        if(counter === 0) EodSelector.setOptionsMsg('warning', 'No results. Change the search text.');
    }));
}

/**
 * Checks if data can be received
 * @param type string       - API type: historical, live, news, etc
 * @param props array       - required props
 * @param callback function - run after
 */
function eod_check_token_capability(type, props = {}, callback){
    jQuery.ajax({
        dataType: "json",
        method: "POST",
        url: eod_ajax_url,
        data: {
            'action': 'eod_check_token_capability',
            'nonce_code': eod_ajax_nonce,
            'type': type,
            'props': props
        }
    })
    .always((data) => {})
    .done((data) => {
        let error = false;
        if(data.error) {
            error = {
                error_code: data.error_code,
                error: data.error
            };
        }else if(Array.isArray(data) && data.length === 0){
            error = {
                error: 'undefined API type or not enough parameters'
            };
        }

        callback(error);
    });
}

/**
 * Detect outside click
 * @param $containers
 * @param callback
 */
function eod_bind_outside_click($containers, callback) {
    $containers.each(function () {
        let $container = jQuery(this);
        document.addEventListener('mousedown', function (e) {
            let within = e.composedPath().includes($container[0]);
            if (!within) callback($container);
        }, true);
    });
}

// ==================================
//                UI
// ==================================
/**
 * Toggle button
 */
jQuery(document).on('click', '.eod_toggle', function(e){
    e.preventDefault();
    let $checkbox = jQuery(this).find('input'),
        $clicked_input = jQuery(e.target).prev();
    if( $clicked_input.is(':checked') ) return;
    if( $clicked_input.attr('type') === 'checkbox' )
        $checkbox.each(function () { this.checked = !this.checked; }).trigger('change');
    else
        $clicked_input.prop('checked', true).trigger('change');
});

/**
 * Selector
 */
class EodSelector{
    constructor(p) {
        let _this = this;

        // Container
        if(!( p.$box instanceof jQuery)) return;
        this.$box = p.$box.eq(0);
        this.$box.data('EodSelector', this);

        // Settings
        this.multiple_select = p.multiple_select;
        this.support_of_duplicate = p.support_of_duplicate;
        this.search_method = p.search_method;
        this.storage = p.storage;
        this.preset = p.preset;

        // Hooks
        this.hook_change = p.hook_change && typeof p.hook_change === 'function' ?
            p.hook_change : () => {};
        this.hook_delete = p.hook_delete && typeof p.hook_delete === 'function' ?
            p.hook_delete : () => {};

        // Filters
        this.filter_select_option = p.filter_select_option && typeof  p.filter_select_option === 'function' ?
            p.filter_select_option : (_this, obj) => {return obj};

        // Search input
        this.$input = this.$box.find('input[type=text]').eq(0);
        switch (this.search_method){
            case 'api':
                eod_search_input_api(this.$input);
                break;
            case 'currency':
                eod_search_input_currency(this.$input);
                break;
            default:
                this.$input.on('input', function () {
                    let s = jQuery(this).val().toUpperCase();
                    _this.$options.children().each( function () {
                        jQuery(this).toggle( jQuery(this).text().toUpperCase().includes(s) );
                    });
                })
        }


        // Options list container
        this.$options = this.$box.find('.options ul');
        if(!this.$options.length) {
            this.$input.wrap('<div class="options"></div>')
            this.$options = jQuery('<ul></ul>');
            this.$input.after( this.$options );
        }
        // Open options list with listener of input focus and close with listener of outside click
        this.$input.on('focusin', function () {
            _this.$options.show();
        });
        eod_bind_outside_click( this.$options.parent(), function(){
            _this.$options.hide();
        });
        // Lazy load option list
        // _this.$options.on('scroll', function(){
        //    if( jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight -100 ) {
        //        jQuery(this).find('> li.lazy:lt(50)').removeClass('lazy').show();
        //    }
        // });


        // Selected list container
        this.$selected = this.$box.find('> .selected');
        if(!this.$selected.length) {
            this.$selected = jQuery('<ul class="selected"></ul>');
            this.$box.append( this.$selected );
        }
        // Sortable
        this.$selected.sortable({
            handle: ".move",
            axis: "y",
            revert: false,
            revertDuration: 0,
            cursor: "grabbing",
            stop: function (){
                _this.changingSelector();
            }
        });

        // Load always selected options from input storage
        this.loadFromInput();
    }

    /**
     * Add option to list
     * @param name - display name
     * @param data - value
     */
    addOptionItem(name, data = false){
        // Check limit
        if( this.$options.children('.item').length >= 50 ) {
            this.setOptionsMoreNum(false, 1);
            return;
        }

        let  _this = this,
            $option = jQuery('<li class="item">'+name+'</li>');

        // To optimize DOM rendering
        // if( this.$options.children().length > 100 )
        //     $option.addClass('lazy').hide();

        // Add data to item
        if(data) $option.data('data', data);

        // Click listener
        $option.on('click', function(){
            let f_data = _this.filter_select_option( _this, {name:name, data:data} );
            if(!f_data || typeof f_data !== 'object' || !f_data.name) return;

            // Display or remove already selected item
            if( $option.hasClass('selected') ) {
                _this.removeSelectedItem( $option.data('linked_s_item') );
            } else {
                let $new_s_item = _this.addSelectedItem(f_data.name, f_data.data);
                // Bind option and selected item
                $option.data('linked_s_item', $new_s_item);
                $new_s_item.data('linked_option', $option);
                // Mark selected item in options
                $option.addClass('selected');
            }

            // Hide options for non-multiple selector
            if( !_this.multiple_select )
                _this.$options.hide();
        });

        // Add item to list
        this.$options.append($option);
    }

    /**
     * Display message in options list
     * @param name - classname/slug
     * @param text - message text/html
     */
    setOptionsMsg( name, text = '' ){
        if(!name || name === 'item') return;
        let $msg = this.$options.find('.'+name);

        // Reset message
        if(!text) return $msg.remove();

        // Create element
        if( !$msg.length ) $msg = jQuery('<li class="msg '+name+'"></li>').appendTo( this.$options );

        $msg.html(text);
    }

    /**
     * Show at the end of the options list a message about the number of options not included.
     * @param count - number of options left
     * @param different - the number to add to the current value
     */
    setOptionsMoreNum( count, different = 0 ){
        let $count = this.$options.find('.more .count'), text = '';

        if( !count )
            if( $count.length )
                count = parseInt( $count.text() );
            else
                count = 0;

        // Modification the number
        count += different;
        // Set the number in text
        if(count) text = 'And <span class="count">'+count+'</span> more. Refine your search.';
        this.setOptionsMsg('more', text);
    }

    /**
     * Add selected item
     * @param name      - display name
     * @param data      - value
     */
    addSelectedItem(name, data = false){
        if(!name) return false;

        // Filter duplicate select
        let $duplicate_item = false;
        if( !this.support_of_duplicate )
            this.$selected.children().each(function(){
                if(jQuery(this).find('.name').html() === name)
                    $duplicate_item = jQuery(this);
            });
        if($duplicate_item) return $duplicate_item;

        // Create selected item
        let _this = this,
            $item = jQuery(
                '<li>' +
                    '<span class="move"></span>' +
                    '<div class="body">' +
                        '<div class="header">' +
                            '<span class="name">' + name + '</span>' +
                            '<div class="bar"><div class="remove"></div></div>' +
                        '</div>' +
                    '</div>' +
                '</li>');

        // Add settings with preset
        if(Array.isArray( this.preset )) {
            let $settings = jQuery('<div class="settings"></div>'),
                settings = data && typeof data._settings === 'object' ? data._settings : {};
            for(let s of this.preset) {
                if(!s.name || !s.type) continue
                // Row contains pair of title and input field
                let $row = jQuery('<label><span>' + (s.title ? s.title : s.name) + ': </span></label>'),
                    $input = jQuery('<input>').attr({
                        type:        s.type,
                        name:        s.name,
                        placeholder: s.placeholder,
                        value:       settings[s.name] ? settings[s.name] : null
                    })

                // Hide the row with hidden input
                if(s.type === 'hidden') $row.hide();
                // Trigger changing of selector by input's event
                $input.on('change', () => { _this.changingSelector() });
                $settings.append( $row.append($input) );
            }
            $item.find('.body').append($settings)

            // Toggle button
            $item.find('.bar').append('<div class="toggle"></div>');
            // Toggle event
            $item.on('click', '.toggle, .name', function(){
                $item.toggleClass('opened');
            });
        }

        // Listener and method of deleting
        let removing = function(){
            _this.removeSelectedItem( $item );
        }
        $item.on('click', '.remove', removing);

        // Add data to item
        if(data) $item.data('data', data);

        // Add or replace current item
        if(this.multiple_select) {
            this.$selected.append($item);
        } else {
            this.$selected.html($item);

            // Remove mark of select on other items
            this.$options.find('.selected').removeClass('selected');
        }

        // Trigger event
        _this.changingSelector();

        return $item;
    }

    /**
     * Get one selected item
     */
    getSelectedItem(){
        let list = this.getSelectedItems();
        return list.length > 0 ? list[0] : false;
    }

    /**
     * Get all selected items
     */
    getSelectedItems(){
        let list = [];
        this.$selected.find('> li').each(function(){
            let data = jQuery(this).data('data'),
                _settings = {};
            // The item can contain settings
            jQuery(this).find('.settings input').each( function(){
                _settings[ jQuery(this).attr('name') ] = jQuery(this).val();
            });
            if( Object.keys(_settings).length )
                data._settings = _settings;

            list.push( {
                data: data,
                name: jQuery(this).find('.name').html()
            } );
        });
        return list;
    }

    /**
     * Remove selected item
     * @param name - display name or jQuery item object
     * @param data - value
     */
    removeSelectedItem(name, data = false){
        // Define $item
        let $item;
        if(name instanceof jQuery){
            $item = name;
        }else{
            this.$selected.find('> li').each(function(){
                if(jQuery(this).find('.name').html() === name && jQuery(this).data('data') === data) {
                    $item = jQuery(this);
                    return false;
                }
            });
        }

        // Remove selection from linked option
        let $option = $item.data('linked_option');
        if($option && $option.length)
            $option.removeClass('selected').data('linked_s_item', false);

        // Init hooks and removing
        this.hook_delete( $item );
        $item.remove();
        this.changingSelector();
    }

    /**
     * Load selected items from input storage
     */
    loadFromInput(){
        if( !this.storage ||
            !this.storage.$input ||
            !this.storage.$input.val() ||
            typeof this.storage.loading !== 'function' ) return;

        // Convert string to raw data object
        let raw_string = this.storage.$input.val(), raw_data;
        if(this.storage.separator)
            raw_data = raw_string.split( this.storage.separator );
        else
            raw_data = JSON.parse( raw_string );

        // Interpret raw data
        let list = this.storage.loading( raw_data );
        if(!Array.isArray(list)) return;

        // Add selected items
        for(let item of list){
            if(typeof item === 'object')
                this.addSelectedItem(item.name, item.data);
        }
    }

    /**
     * Save selected items to input storage
     */
    saveToInput(){
        if(!this.storage || !this.storage.$input || typeof this.storage.saving !== 'function' ) return;

        // Prepare data
        let raw_data = [];
        for( let item of this.getSelectedItems() )
            raw_data.push( item.data );

        let list = this.storage.saving( raw_data );
        if(!Array.isArray(list)) return;

        // Convert to string and saving
        let data_string;
        if(this.storage.separator)
            data_string = list.join( this.storage.separator );
        else
            data_string = JSON.stringify( list );

        this.storage.$input.val( data_string ).trigger('change');
        this.storage.$input.val( data_string ).trigger('input');
    }

    /**
     * Event of changing
     */
    changingSelector(){
        this.saveToInput();
        this.hook_change();
    }

    /**
     * Remove selected items
     */
    resetSelector(){
        this.$selected.html('');
        this.$input.val('');
        this.$options.html('');

        this.changingSelector();
    }
}
