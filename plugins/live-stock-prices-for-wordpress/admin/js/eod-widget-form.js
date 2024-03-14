if(window.hasOwnProperty('elementor')) {
    window.elementor.hooks.addAction('panel/open_editor/widget', function (panel, model, view) {
        init_eod_widget(jQuery("#elementor-controls"));
    });
    window.elementor.hooks.addAction('panel/open_editor/widget/eod_ticker', function (panel, model, view) {
        init_eod_widget_ticker(jQuery("#elementor-controls"));
    });
    window.elementor.hooks.addAction('panel/open_editor/widget/eod_converter', function (panel, model, view) {
        init_eod_widget_converter(jQuery("#elementor-controls"));
    });
}

// Init EOD widget with updated/added listener
function eod_wp_widget_init( $widgets, init_function ){
    $widgets.each(function() {
        if (jQuery(this).hasClass('initialized')) return;
        jQuery(this).addClass('initialized');
        init_function( jQuery(this) );
    });
}
/* ======================================
                  TICKER
   ====================================== */
// If it is not found, get the name from the API and write in the attribute.
// jQuery.ajax({
//     dataType: "json",
//     method: "POST",
//     url: eod_ajax_url,
//     data: {
//         'action': 'search_eod_item_by_string',
//         'nonce_code': eod_ajax_nonce,
//         'string': target,
//     }
// }).always((data) => {
//     if(data.error) console.log('EOD-error: ' +data.error, target);
// }).done((data) => {
//     if(data.length){
//         // Use first item
//         $item.attr('data-name', data[0].Name);
//         $item.find('.name').text( data[0].Name + ' (' +target+ ')' );
//         compile_ticker_list_val( $list );
//     }
// });
function init_eod_widget_ticker( $widget ){
    $widget.find('.eod_search_box').each(function(){
        const title_tpl = function( data ){
            let name_type = $widget.find('.field.display_name input:checked').val(),
                display_name = eod_tpl_target(data);

            if(data._settings.name)
                display_name = '<span class="bold">' + data._settings.name + '</span> (' + data.code+'.'+data.exchange + ')';
            else if(name_type === 'name' && data._settings.public_name)
                display_name = '<span class="bold">' + data._settings.public_name + '</span> (' + data.code+'.'+data.exchange + ')';

            return display_name;
        }
        const filter_selector_saving_json = function (raw_data) {
            let list = [];
            for (let item of raw_data) {
                // Remove empty settings
                if (!item._settings) item._settings = {};
                Object.entries(item._settings).reduce((a, [k, v]) => (v == null ? a : (a[k] = v, a)), {});
                list.push(Object.assign(
                    {target: item.code + '.' + item.exchange},
                    item._settings
                ));
            }
            return list;
        }
        const filter_selector_loading_json = function (raw_data) {
            let list = [];
            for (let item of raw_data) {
                let parts = item.target.split('.'),
                    data = {
                        code: parts[0],
                        exchange: parts[1],
                        _settings: {
                            public_name: item.public_name,
                            name: item.name,
                            ndap: item.ndap,
                        }
                    },
                    display_name = title_tpl(data);

                list.push({
                    name: display_name,
                    data: data
                });
            }
            return list;
        }

        let $search_box = jQuery(this),
            $storage_input = $widget.find('input.target_list');

        new EodSelector({
            $box: $search_box,
            multiple_select: $search_box.hasClass('multiple'),
            search_method: 'api',
            storage: {
                $input: $storage_input,
                saving: filter_selector_saving_json,
                loading: filter_selector_loading_json,
            },
            preset: [
                {
                    title: 'Public name',
                    type: 'hidden',
                    name: 'public_name'
                },
                {
                    title: 'Custom name',
                    type: 'text',
                    name: 'name',
                }, {
                    title: 'A number of digits after decimal point',
                    type: 'number',
                    name: 'ndap',
                    placeholder: 'Default: ' + eod_display_settings.ndap,
                }
            ],
            filter_select_option: function(_this, obj){
                obj.data._settings = {public_name: obj.data.name};
                obj.name = title_tpl(obj.data);
                return obj;
            },
            hook_change: function(){
                this.$selected.find('> li').each(function(){
                    let data = jQuery(this).data('data');
                    jQuery(this).find('.name').html( title_tpl(data) );
                });

            }
        });
    });
}

jQuery(document).on('change', '.eod_ticker_widget .field.display_name input', function(){
    jQuery(this).closest('.eod_ticker_widget').find('.eod_search_box').data('EodSelector').changingSelector();
});

jQuery(document).on('widget-updated widget-added', function(){
    eod_wp_widget_init( jQuery('.eod_ticker_widget'), function($widget){
        // Init selectors
        init_eod_widget_ticker($widget);
    });
});

/* ======================================
                   NEWS
   ====================================== */
// Target type
jQuery(document).on('change', '.eod_news_widget .news_type input', function(){
    let $widget = jQuery(this).closest('.eod_news_widget');
    $widget.find('.news_type input').each(function(){
        $widget.find('.field.by_'+jQuery(this).val()).toggle( jQuery(this).is(':checked') );
    });
    $widget.find('select').val('').change();
    $widget.find('.eod_search_box').data('EodSelector').resetSelector();
});


/* ======================================
               FUNDAMENTAL
   ====================================== */
// Select data-preset
jQuery(document).on('change', '.eod_fundamental_widget .fd_preset', function(){
    let type = jQuery(this).find('option:checked').attr('data-type'),
        EodSelector = jQuery(this).closest('.eod_fundamental_widget').find('.eod_search_box').data('EodSelector'),
        $selected_item = EodSelector.$selected.find('> li');

    // Write data for filter items by type
    EodSelector.$input.data('stock-type', type ? type : '');

    // Lock/unlock search input
    EodSelector.$input.val('');
    EodSelector.$input.prop("disabled", !type);
    EodSelector.$input.closest('.field').toggleClass("disabled", !type);

    // Reset search results
    EodSelector.$options.html('');

    // Clean search input with incompatible item
    if($selected_item.length && $selected_item.eq(0).data('data').type.toLowerCase() !== type)
        EodSelector.resetSelector();
});

/* ======================================
                CONVERTER
   ====================================== */
function init_eod_widget_converter($widget){
    // Init selectors
    $widget.find('.eod_search_box').each(function(){
        let $search_box = jQuery(this),
            $storage_input = $search_box.siblings('input.storage'),
            args = {
                $box: $search_box,
                multiple_select: $search_box.hasClass('multiple'),
                search_method: 'currency',
                storage: {
                    $input: $storage_input,
                    separator: ', ',
                    saving: function( raw_data ){
                        let list = [];
                        for( let item of raw_data ){
                            list.push(item.code+'.'+(item.type).toUpperCase());
                        }
                        return list;
                    },
                    loading: function( raw_data ){
                        let list = [];
                        for( let item of raw_data ){
                            let parts = item.split('.');
                            list.push({
                                name: eod_tpl_target({code: parts[0], exchange: parts[1].toLowerCase()}),
                                data: {code: parts[0], type: parts[1]}
                            });
                        }
                        return list;
                    },
                }
            };
        // Checking selected currencies for a match
        if($search_box.hasClass('second_currency') || $search_box.hasClass('first_currency'))
            args.filter_select_option = function( _this, obj ){
                let other_class = _this.$box.hasClass('first_currency') ? 'second' : 'first',
                    $other_box = _this.$box.closest('.eod_widget_form').find('.'+other_class+'_currency');

                if($other_box.length) {
                    let other_EodSelector = $other_box.data('EodSelector'),
                        $other_s_name = other_EodSelector.$selected.find('li .name');
                    // Compare
                    if( $other_s_name.html() === obj.name )
                        other_EodSelector.resetSelector();
                }

                return obj;
            };

        new EodSelector(args);
    });
}

// Target search and selection
jQuery(document).on('widget-updated widget-added', function(){
    eod_wp_widget_init( jQuery('.eod_widget_form.eod_converter_widget'), function($widget){
        init_eod_widget_converter($widget);
    });
});


/* ======================================
              FOR ALL WIDGETS
   ====================================== */

jQuery(document).on('widget-updated widget-added', function(){
    eod_wp_widget_init( jQuery('.eod_widget_form'), function($widget){
        init_eod_widget($widget);
    });
});

function init_eod_widget( $widget ){
    // Target search and selection
    $widget.find('.eod_search_box.common_api_search').each(function(){
        let $search_box = jQuery(this),
            $storage_input = $search_box.parent().find('input.storage');

        // Get stock type for fundamental data widget and put in saved selected target
        let stock_type = $widget.find('.fd_preset').length ? $widget.find('.fd_preset option:checked').attr('data-type') : false;


        const filter_selector_saving_data = function( raw_data ){
            let list = [];
            for( let item of raw_data ){
                list.push(item.code+'.'+item.exchange);
            }
            return list;
        }
        const filter_selector_loading_data = function( raw_data ){
            let list = [];
            for( let item of raw_data ){
                let parts = item.split('.'),
                    data = {code: parts[0], exchange: parts[1]};

                if(stock_type) data.type = stock_type;

                list.push({
                    name: eod_tpl_target(data),
                    data: data
                });
            }
            return list;
        }


        new EodSelector({
            $box: $search_box,
            multiple_select: $search_box.hasClass('multiple'),
            search_method: 'api',
            storage: {
                $input: $storage_input,
                separator: ', ',
                saving: filter_selector_saving_data,
                loading: filter_selector_loading_data,
            }
        });
    });
}


