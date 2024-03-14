// jQuery debounce
!function(t,n){let o,u=t.jQuery||t.Cowboy||(t.Cowboy={});u.throttle=o=function(t,o,e,i){let r,a=0;function c(){let u=this,c=+new Date-a,f=arguments;function d(){a=+new Date,e.apply(u,f)}i&&!r&&d(),r&&clearTimeout(r),i===n&&c>t?d():!0!==o&&(r=setTimeout(i?function(){r=n}:d,i===n?t-c:t))}return"boolean"!=typeof o&&(i=e,e=o,o=n),u.guid&&(c.guid=e.guid=e.guid||u.guid++),c},u.debounce=function(t,u,e){return e===n?o(t,u,!1):o(t,e,!1!==u)}}(this);

class EodDebug{
    constructor(p){
        this.time = {start: window.performance.now()};
        this.periods = {'news':{}, 'fundamental':{}, 'stock':{}};
        // Ping to the current site
        this.ping = 0;
        jQuery.ajax({
            method: "GET",
            url: eod_ajax_url,
            data: {'action': 'eod_ping', 'nonce_code': eod_ajax_nonce}
        }).always(() => {this.ping = window.performance.now() - this.time.start})
    }
    addTimePoint(point_name, period = false){
        let time_point = window.performance.now();
        this.time[point_name] = time_point;
        if(period) {
            if(!this.periods[period.type][period.slug]) this.periods[period.type][period.slug] = {};
            this.periods[period.type][period.slug][period.moment] = time_point;
        }
    }
    showTimeLine(){
        console.log('ping', this.ping);
        let timeline = [];
        for (let point in this.time)
            timeline.push([point, this.time[point]]);

        timeline.sort(function(a, b) {
            return a[1] - b[1];
        });
        for (let point of timeline)
            console.log(point[1].toFixed(2) + ': ' + point[0]);
    }
    showKeyTimePeriods(){
        if(this.ping)
            console.log('AJAX ping: ' + this.ping.toFixed(0) + 'ms');
        if(this.time['DOM content loaded'])
            console.log('DOM loading: ' + this.toSec(this.time['DOM content loaded']));
        // WebSocket
        for(let ws_type of ['cc', 'us', 'forex'])
            if(this.time['WS connection ('+ws_type+') is opened'])
                console.log('WS connecting ('+ws_type+'): ' + this.toSec(this.time['WS connection ('+ws_type+') is opened'] - this.time['DOM content loaded']));
        // Widgets
        for(let type of ['news', 'fundamental', 'stock']) {
            for (let item of Object.values(this.periods[type])) {
                if (item.start && item.end) {
                    let time = item.end - item.start;
                    console.log('receiving '+type+' data: ' + this.toSec(time) +
                        ' (client <' + this.toSec(this.ping) + '> site <' + this.toSec(time - this.ping) + '> API)');
                    break;
                }
            }
        }
    }
    toSec(float){
        return (float/1000).toFixed(2) + 's.';
    }
}
window.eod_debug = new EodDebug;

let ws_eod, ws_eod_queue = {}, ws_eod_data = {};
document.addEventListener('DOMContentLoaded', () => {
    eod_debug.addTimePoint('DOM content loaded');
    eod_init();
});

async function eod_init(){
    eod_display_all_live_tickers();
    eod_display_fundamental_data();
    eod_display_all_historical_tickers();
    // Display items only by AJAX
    if( eod_display_settings.news_ajax ) {
        eod_display_news();
    }

    // Add websockets
    let eod_api_token = await jQuery.ajax({
        method: "POST",
        url: eod_ajax_url,
        data: {
            'action': 'get_eod_token',
            'nonce_code': eod_ajax_nonce,
        }
    });
    ws_eod = {
        'cc': new WebSocket('wss://ws.eodhistoricaldata.com/ws/crypto?api_token=' + eod_api_token),
        'forex': new WebSocket('wss://ws.eodhistoricaldata.com/ws/forex?api_token=' + eod_api_token),
        'us': new WebSocket('wss://ws.eodhistoricaldata.com/ws/us?api_token=' + eod_api_token)
    };
    // Define types
    for(let type in ws_eod){
        ws_eod_queue[type] = [];
        ws_eod_data[type] = {};

        // Add main event listeners
        // After connecting, execute the accumulated queue.
        ws_eod[type].addEventListener('open', function(){
            eod_debug.addTimePoint('WS connection ('+type+') is opened');
            while( ws_eod_queue[type].length ){
                ws_eod[type].send( ws_eod_queue[type].shift() );
            }
        });
        // Get response from websocket and call saved listeners
        ws_eod[type].addEventListener('message', function(e){
            let res = JSON.parse(e.data);
            if(!res.s) return;
            call_ws_eod_listeners(type, res);
        });
        // After closing
        ws_eod[type].addEventListener('close', function(e){
            eod_debug.addTimePoint('WS connection ('+type+') is closed. Reason: '+e.reason);
        });
    }

    // Display widgets
    eod_init_realtime_tickers()
    eod_display_converters();

    // Refresh tickers every minute. It will affect your daily API Limit!
    const EOD_refresh_common_tickers = false,
        EOD_refresh_interval = 60000;             // 60000 = 1 minute
    if(EOD_refresh_common_tickers){
        setInterval(function () {
            console.log("EOD_refresh_common_tickers");
            eod_display_all_live_tickers();
        }, EOD_refresh_interval);
    }
}

/* =========================================
             handle functions
   ========================================= */
/**
 * Replaces zeros with letters
 * @param value - number
 * @returns {number|string|*}
 */
function abbreviateNumber(value) {
    // Check type
    let newValue;
    if(typeof value === 'string'){
        newValue = +value;
    }else{
        newValue = value;
    }

    if(isNaN(newValue) || typeof newValue !== 'number')
        return value;

    if (value >= 1000 || value <= -1000) {
        let shift = 1,
            suffixes = ["", "K", "M", "B", "T"],
            suffixNum = Math.floor( ((""+Math.floor(value)).length - shift)/3 ),
            shortValue = suffixNum !== 0 ? (value / Math.pow(1000,suffixNum)) : value;

        if (shortValue % 1 !== 0)  shortValue = shortValue.toFixed(2);
        newValue = shortValue+suffixes[suffixNum];
    }
    return newValue;
}

/**
 * Add target in websocket
 * @param type - websocket type
 * @param target - symbol of target
 */
function add_ws_eod_target(type, target){
    let message = '{"action": "subscribe", "symbols": "' + target.toUpperCase() + '"}';
    if( ws_eod[type].readyState === 1 ) ws_eod[type].send( message );
    else ws_eod_queue[type].push( message );
}

/**
 * Remove target from websocket
 * @param type - websocket type
 * @param target - symbol of target
 */
function remove_ws_eod_target(type, target){
    let message = '{"action": "unsubscribe", "symbols": "' + target.toUpperCase() + '"}';
    if( ws_eod[type].readyState ) ws_eod[type].send( message );
    else ws_eod_queue[type].push( message );
}

/**
 * Init websocket listeners.
 *
 * ws_eod_data contains data about all used targets.
 * Each item contains listeners that ran when new data is received on the websocket.
 * This is necessary so that different widgets and interfaces can react differently to receiving the same data.
 *
 * @param type - websocket type
 * @param res - websocket response
 */
function call_ws_eod_listeners(type, res){
    res.type = type;
    let listeners = ws_eod_data[type][res.s].listeners;
    for(let l_name in listeners){
        listeners[ l_name ](res);
    }
}

/**
 * Check if the target exists in the ws_eod_data
 * @param type - websocket type
 * @param target - symbol of target without exchange
 * @param only_return - if not exist create item
 * @returns {boolean|*} -
 */
function check_ws_eod_data_item(type, target, only_return = false){
    type = type.toLowerCase();
    if( !ws_eod_data[type] ) return false;

    let is_exist = (target.toUpperCase() in ws_eod_data[type]);
    if(only_return) return is_exist;

    // Target not found
    if( !is_exist ) {
        ws_eod_data[type][ target.toUpperCase() ] = {
            listeners: {}
        }

        // Add target in websocket
        add_ws_eod_target(type, target);
    }

    return ws_eod_data[type][ target.toUpperCase() ];
}

/**
 * Remove data from ws_eod_data and close ws messages for specified target
 * @param type - websocket type
 * @param target - symbol of target
 * @param listener_name - the name of the widget for which the listener is created
 * @returns {boolean|*} -
 */
function remove_ws_eod_data_item(type, target, listener_name){
    type = type.toLowerCase();
    if( !ws_eod_data[type] ) return false;

    let is_exist = (target.toUpperCase() in ws_eod_data[type]);

    if(is_exist){
        // Remove the listener. If there are none left, then delete the target itself.
        let listeners = ws_eod_data[type][ target.toUpperCase() ].listeners;
        delete listeners[ listener_name ];

        if(Object.keys(listeners).length === 0) remove_ws_eod_target(type, target);
    }

    return ws_eod_data[type][ target.toUpperCase() ];
}

/**
 * Get EOD fundamental data
 * @param target - symbol of target
 * @param callback - callback function
 * @returns {boolean|*} -
 */
function get_eod_fundamental(target, callback){
    if(typeof callback !== 'function' || !target) return false;

    jQuery.ajax({
        dataType: "json",
        method: "POST",
        url: eod_ajax_url,
        data: {
            'action': 'get_fundamental_data',
            'nonce_code': eod_ajax_nonce,
            'target': target
        }
    }).always((data) => {
        if(data.error) console.log('EOD-error: ' +data.error, target);
        callback(data);
    });
}

/**
 * Get EOD ticker data
 * @param type - ticker type (live, realtime, historical)
 * @param list - list of symbols
 * @param callback - callback function
 * @returns {boolean|*} -
 */
function get_eod_ticker(type = 'historical', list, callback){
    if(typeof callback !== 'function' || !jQuery.isArray(list) || list.length < 1) return false;
    // Log
    let debug_slug = list.join(',');
    eod_debug.addTimePoint('AJAX request to the realtime stock API for ('+debug_slug+')',{
        type: 'stock',
        slug: debug_slug,
        moment: 'start'
    });

    jQuery.ajax({
        dataType: "json",
        method: "POST",
        url: eod_ajax_url,
        data: {
            'action': 'get_real_time_ticker',
            'nonce_code': eod_ajax_nonce,
            'list': list,
            'type': type
        }
    }).always((data) => {
        if(data.error) console.log('EOD-error: ' +data.error, type, list);
        // Log
        eod_debug.addTimePoint('AJAX response from the realtime stock API for ('+debug_slug+'), render items',{
            type: 'stock',
            slug: debug_slug,
            moment: 'end'
        });
    })
    .done((data) => { callback(data); });
}


/* =========================================
     loading and displaying all financial news
   ========================================= */
/**
 * Initiate the loading and display financial news on the page.
 * @param $items jQuery list of EOD news boxes. Default displaying all .eod_news_list elements on page.
 */
function eod_display_news( $items = false ){
    if($items && !($items instanceof jQuery)) return;
    if(!$items) $items = jQuery(".eod_news_list");

    $items.each(function(){
        eod_display_news_item( jQuery(this) );
    });
}

/**
 * Loading and display financial news for the current item
 * @param $box
 */
function eod_display_news_item( $box ){
    if(!($box instanceof jQuery)) return;
    $box = $box.eq(0);

    // Loading animation
    $box.addClass('eod_loading');

    // Collect parameters
    let props = {};
    for(let prop of ['target','tag','from','to','limit','pagination']) {
        let val = $box.attr('data-' + prop);
        if(val) props[prop] = val;
    }
    if(!props.target && !props.tag) return false;

    // Log
    eod_debug.addTimePoint('AJAX request to the news API for ('+(props.target ? props.target : props.tag)+')',{
        type: 'news',
        slug: props.target,
        moment: 'start'
    });

    // Get and display news html
    jQuery.ajax({
        dataType: "json",
        method: "POST",
        url: eod_ajax_url,
        data: {
            'action': 'get_eod_financial_news',
            'nonce_code': eod_ajax_nonce,
            'props': props
        }

    }).always((data) => {
        $box.data('target', props.target).removeClass('eod_loading');
        if(!data) console.log('EOD-error: empty news response', props);

    }).done((data) => {
        if(!data || data.error) return false;
        // Log
        eod_debug.addTimePoint('AJAX response from the news API for ('+props.target+'), render item',{
            type: 'news',
            slug: props.target,
            moment: 'end'
        });

        // Sort by date
        data.sort(function(a,b){
            return new Date(b.date) - new Date(a.date);
        });
        // Discard the excess and duplicates
        let whitelist = [], res = [];
        for(let i=0; i<data.length; i++){
            let slug = data[i].date + data[i].title;
            if( whitelist.indexOf(slug) === -1 ){
                res.push(data[i]);
                whitelist.push(slug);
            }
        }
        if(props.limit) res = res.slice(0, parseInt(props.limit));

        // Render
        eod_render_news_item($box, res);
    });
}
function eod_render_news_item( $box, data ){
    // Save data
    $box.data('data', data);

    // Remove old list and pagination
    $box.find('.list, .eod_pagination').remove();

    // Add pagination
    let pagination = $box.attr('data-pagination'),
        limit = pagination ? Math.abs(pagination) : data.length,
        last_page = Math.ceil(data.length/limit);
    if(pagination) {
        let $pagination = jQuery('\
                <div class="eod_pagination start">\
                    <button class="prev"></button>\
                    <span>Page</span>\
                    <input type="number" min="1" value="1" max="' + last_page + '">\
                    <span>of ' + last_page + '</span>\
                    <button class="next"></button>\
                </div>');

        // Change page event
        $pagination.find('input[type=number]').on('change', function () {
            let $input = jQuery(this),
                $box = $input.closest('.eod_news_list');

            // Check range
            if (parseInt($input.val()) < 1)
                $input.val(1);
            if (parseInt($input.val()) > parseInt($input.attr('max')))
                $input.val($input.attr('max'));

            // Check last and fist page
            $pagination.toggleClass('start', parseInt($input.val()) === 1);
            $pagination.toggleClass('end', $input.val() === $input.attr('max'));

            // Change news list
            eod_set_news_page($box, parseInt($input.val()));
        });

        // Click on arrow button
        $pagination.find('button').on('click', function () {
            let $input = jQuery(this).siblings('input').eq(0),
                d = jQuery(this).hasClass('next') ? 1 : -1,
                current_page = parseInt($input.val()),
                max_page = $input.attr('max'),
                next_page = current_page + d;

            if (next_page < 1 || next_page > max_page) return false;

            $input.val(next_page).change();
        });

        $box.prepend($pagination);
    }
    // Add news list container
    $box.prepend( jQuery('<div class="list"></div>') )

    eod_set_news_page($box);
}
function eod_set_news_page( $box, page = 1 ){
    let data = $box.data('data');
    if(!data) return;

    let news = [],
        limit = $box.attr('data-pagination') ? parseInt($box.attr('data-pagination')) : data.length,
        offset = (page-1)*limit;
    for(let i=0; i<limit && (offset+i)<data.length; i++)
        news.push( eod_news_item_html( data[offset+i] ) );

    if(news.length > 0) {
        $box.find('.list').html(news);
    }else{
        $box.html('<div class="eod_error">News not found</div>');
    }
}
function eod_news_item_html( item ){
    // Tags
    let tags = '';
    for(let tag of item.tags)
        tags += '<li>'+tag+'</li>';

    // Datetime
    let display_date, number,
        timestamp = new Date( item.date ).getTime(),
        now = new Date().getTime(),
        time_ago = (now - timestamp)/1000;
    if(time_ago > 24*3600){
        let date_options = {year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'};
        display_date = new Date(timestamp).toLocaleDateString("en-US", date_options);
    }else{
        if(time_ago > 3600){
            number = Math.floor(time_ago/3600);
            display_date = number + ( number>1 ? ' hours ago' : ' hour ago');
        }else{
            number = Math.floor(time_ago/60);
            display_date = number + ( number>1 ? ' minutes ago' : ' minute ago');
        }
    }

    return '\
        <div class="eod_news_item">\
            <div class="thumbnail"></div>\
            <a rel="nofollow" target="_blank" class="h" href="'+item.link+'">'+item.title+'</a>\
            <time dateTime="'+item.date+'" class="date">'+display_date+'</time>\
            <blockquote cite="'+item.link+'">\
                <div class="description">\
                    '+ item.content.substring(0, 300) +'\
                </div>\
            </blockquote>\
            <ul class="tags">'+tags+'</ul>\
        </div>';
}

/* =========================================
     loading and displaying all tickers
   ========================================= */
// Render function
function render_eod_ticker(type, target, value, evolution = {}){
    if(!target) return false;

    // Display settings
    // ndap - number of digits after decimal point (base value)
    // ndape - (evolution value)
    let ndap = eod_display_settings.ndap,
        ndape = eod_display_settings.ndape;

    // The ticker can be without the other half.
    let trg = target.toLowerCase().split('.'),
        full_t_class = '.'+type+'.eod_t_'+trg.join('_'),     // AAPL.US
        t_class = '.'+type+'.eod_t_'+trg[0],                 // AAPL
        $tickers = jQuery(full_t_class+', '+t_class);

    // Display error
    if(!value || value === 'NA') {
        $tickers.text('no result from real time api').closest('.eod_ticker').addClass('error');
        return false;
    }

    // Display data
    $tickers.each(function(){
        let $item = jQuery(this);

        // Check local display settings
        let local_ndap = $item.attr('data-ndap') ? parseInt($item.attr('data-ndap')) : ndap,
            local_ndape = $item.attr('data-ndape') ? parseInt($item.attr('data-ndape')) : ndape;

        // Close value eod_display_settings
        value = parseFloat(value).toFixed( local_ndap );
        $item.text(value);

        // Evolution
        if(!evolution || typeof evolution !== 'object' || value === '-') return;
        if(!Array.isArray(evolution)) evolution = [evolution];
        let list_of_evolution_text = [], e_value;
        for(let e_item of evolution){
            let suffix = e_item.suffix ? e_item.suffix : '';
            e_value = parseFloat( e_item.value ).toFixed( local_ndape );

            if(e_value === "-0") e_value = 0; // fix "-0"
            list_of_evolution_text.push( (e_value > 0 ? '+' : '') + e_value + suffix );
        }
        $item.siblings('.evolution').html( '(<span>' + list_of_evolution_text.join(' ') + '</span>)' );
        $item.closest('.eod_ticker')
            .toggleClass('plus', e_value > 0)
            .toggleClass('equal', !e_value)
            .toggleClass('minus', e_value < 0);
    });
}

// For live
function eod_display_all_live_tickers(){
    // Finding and prepare all tickers. Creating list.
    let eod_t_list = [];

    jQuery(".eod_live").each(function(){
        let target = jQuery(this).attr('data-target');
        if( eod_t_list.indexOf(target) === -1 )
            eod_t_list.push(target);
    });

    // Get and display close value
    get_eod_ticker('live', eod_t_list, function(data){
        if(!data || data.error) return false;
        if( !Array.isArray(data) ) data = [data];
        for(let item of data){
            let evolution = null;
            switch (eod_display_settings.evolution_type) {
                case 'abs':
                    evolution = {value: item.change};
                    break;
                case 'percent':
                    evolution = {value: item.change_p, suffix: '%'};
                    break;
                case 'both':
                    evolution = [
                        {value: item.change},
                        {value: item.change_p, suffix: '%'}
                    ];
                    break;
            }

            render_eod_ticker('eod_live', item.code, item.close, evolution);
        }
    });
}

// For historical
function eod_display_all_historical_tickers(){
    // Finding and prepare all tickers. Creating list.
    let eod_t_list = [];

    jQuery(".eod_historical").each(function(){
        let target = jQuery(this).attr('data-target');
        if( eod_t_list.indexOf(target) === -1 )
            eod_t_list.push(target);
    });

    // Get and display close value
    get_eod_ticker('historical', eod_t_list, function(data){
        if(!data || data.error || !Array.isArray(data)) return false;
        for(let item of data){
            let evolution = null;
            if(item.change_p !== '' && item.change !== '') {
                switch (eod_display_settings.evolution_type) {
                    case 'abs':
                        evolution = {value: item.change};
                        break;
                    case 'percent':
                        evolution = {value: item.change_p, suffix: '%'};
                        break;
                    case 'both':
                        evolution = [
                            {value: item.change},
                            {value: item.change_p, suffix: '%'}
                        ];
                        break;
                }
            }

            render_eod_ticker('eod_historical', item.code+'.'+item.exchange_short_name, item.close, evolution);
        }
    });
}

// For realtime
function eod_init_realtime_tickers(){
    // Finding and prepare all realtime tickers. Creating list.
    let eod_rt_list = {};
    for(let type in ws_eod) eod_rt_list[type] = [];
    jQuery(".eod_realtime").each(function(){
        let target = jQuery(this).attr('data-target'),
            [code, type] = target.toLowerCase().split('.');

        // Check availability, exclude duplication
        if(type && eod_rt_list[type] && eod_rt_list[type].indexOf( code ) === -1)
           eod_rt_list[type].push(code);
    });

    // Add websocket listeners
    for(let type in eod_rt_list){
        if( !eod_rt_list[type].length ) continue;

        for(let target of eod_rt_list[type]){
            let ws_data = check_ws_eod_data_item(type, target);
            ws_data.listeners['ticker'] = function(res){
                if(res.p || res.a) render_eod_ticker('eod_realtime', res.s+'.'+res.type, res.p ? res.p : res.a);
            };
        }
    }
}

/* =========================================
              display converter
   ========================================= */
class EOD_Converter {

    constructor(p) {
        this.errors = [];
        this.runned = false;
        this.currencies = ['','']
        this.ratio = [0,0];

        /**
         * Define containers and elements
         */
        // main
        if( p.hasOwnProperty('box') && (p.box instanceof Element || p.box instanceof HTMLDocument)) {
            this.$box = p.box;
        }else{
            this.errors.push('Main converter container is not defined.');
            return;
        }
        // currency
        this.$c1_box = this.$box.children[0];
        this.$c2_box = this.$box.children[1];
        // inputs
        this.$main_input = this.$box.children[0].querySelector('input[type=number]');
        this.$changing_input = this.$box.children[1].querySelector('input[type=number]');

        /**
         * Set properties from data-attributes and classes
         */
        // whitelist
        this.whitelist = this.$box.getAttribute('data-whitelist') ? this.$box.getAttribute('data-whitelist').split(', ') : [];
        this.$box.removeAttribute('data-whitelist');
        // changeable
        this.changeable = this.$box.classList.contains('changeable') || (p.hasOwnProperty('changeable') && p.changeable);
        if(this.changeable)
            this.$box.classList.add('changeable');
        // swappable
        this.swappable = this.$box.classList.contains('swappable') || (p.hasOwnProperty('swappable') && p.swappable);
        if(this.swappable)
            this.$box.classList.add('swappable');
        // flex main currency
        this.flex_main_c = this.$box.classList.contains('flex_main_c') || (p.hasOwnProperty('flex_main_currency') && p.flex_main_currency);

        /**
         * Init callback
         * Used to run all functions for downloading and updating data
         */
        this.init_callback = p.hasOwnProperty('init_callback') && typeof p.init_callback === 'function' ? p.init_callback : () => {};

        /**
         * DOM modification
         */
        // label wrapping
        for(let $c_box of [this.$c1_box, this.$c2_box]) {
            let $label_row = document.createElement('div'),
                $label = $c_box.children[0];

            $label_row.classList.add('label_row');
            $label.parentNode.insertBefore($label_row, $label);
            $label_row.appendChild($label);
        }
        this.fit_font_size();

        // lock button
        if(this.flex_main_c) {
            for (let $c_box of [this.$c1_box, this.$c2_box]) {
                let $lock = document.createElement('span');
                $lock.classList.add('lock');
                $lock.addEventListener('click', () => {
                    if (this.$main_input.parentNode === $lock.parentNode)
                        return;

                    this.set_main_currency();
                });
                $c_box.children[0].append($lock);
            }
        }

        // swap button
        this.$swap = this.$box.querySelector('.swap');
        if(!this.$swap)
            this.$swap = document.createElement('span');
        this.$swap.classList.add('swap');
        if (this.changeable && this.swappable)
            this.$swap.addEventListener('click', () => this.swap_currency());
        this.$box.appendChild(this.$swap);

        /**
         * Input listeners
         */
        for( let input of [this.$changing_input, this.$main_input] ){
            input.addEventListener('focusin', () => this.stop());
            input.addEventListener('focusout', (e) => {
                // Protect from empty main input
                if( e.target === this.$main_input && parseFloat(e.target.value) <= 0 )
                    e.target.value = 1;

                // Unpause converter
                this.run();
                this.refresh();
            });
            input.addEventListener('keyup', (e) => {
                // Event for press Enter
                if (e.keyCode === 13) {
                    this.run();
                    this.refresh();
                    e.target.blur();
                }
            });
            input.addEventListener('input', (e) => {
                this.set_main_currency(e.target);
            });

        }


        /**
         * Label listener of select
         */
        if(this.changeable) {
            // Label click listener
            for( let $c_box of [this.$c1_box, this.$c2_box] ){
                $c_box.querySelector('label').addEventListener('click', (e) => {
                    let $label = e.target,
                        $select = $label.parentNode.parentNode.querySelector('.select');

                    // (HANDLER FUNCTION) Select currency
                    const select_currency = (($li) => {
                        let code = $li.children[0].innerText,
                            type = $li.children[1].innerText.toLowerCase(),
                            $select = $li.closest('.select'),
                            is_main_currency = $select.parentNode.classList.contains('first'),
                            $other_label = this.$box.querySelector((is_main_currency ? '.second' : '.first')+' label[data-type]'),
                            target = $label.innerText + (type === 'cc' ? '-USD' : 'USD');

                        // Ignore event if code not changed
                        if (code === $label.innerText) return;

                        // Hide select list
                        hide_select_lists();

                        // Reset non-main input
                        this.$changing_input.value = '';

                        // Another currency may already have such a code, need to swap them.
                        if ($other_label.innerText === code) {
                            if(this.swappable) this.swap_currency();
                        } else {
                            // Remove old WS listener if other converters don't need it
                            // Looking for the same label
                            let $converters = document.querySelectorAll('.eod_converter label[data-slug="' + $label.getAttribute('data-slug') + '"]')
                            if ($converters.length <= 1) // 1 - exist only current converter
                                remove_ws_eod_data_item(type, target, 'converter');

                            // Set new code and correct font-size
                            $label.innerText = code;
                            $label.setAttribute('data-type', type);
                            this.fit_font_size();

                            // Refresh converter
                            this.init();
                        }
                    });

                    // (HANDLER FUNCTION) Hide select list of targets
                    const hide_select_lists = (() => {
                        this.run();
                        for(let $select_item of this.$box.querySelectorAll('.select')){
                            $select_item.style.display = 'none';
                        }

                        // Remove outside click listener
                        document.body.classList.remove('__outside_click_listener');
                        document.removeEventListener('click', click_outside_select_list, true);
                    });

                    // (HANDLER FUNCTION) Click detection outside of list
                    const click_outside_select_list = ((e) => {
                        let $select = $label.parentNode.parentNode.querySelector('.select'),
                            within = e.composedPath().includes($select);

                        if (!within) hide_select_lists();
                    });

                    // Stop converter and hide lists
                    hide_select_lists();
                    this.stop();

                    // Show hidden select list or create new
                    if ($select) {
                        $select.style.display = 'block';
                    } else {
                        let use_simple_bar = typeof SimpleBar === 'function';

                        // Create new select box
                        $select = document.createElement('div');
                        $select.classList.add('select');

                        let $list = document.createElement('ul');
                        $select.append($list);
                        // Add custom scrollbar to the targets list
                        if (use_simple_bar)
                            new SimpleBar($list, {});

                        let $input = document.createElement('input');
                        $input.setAttribute('placeholder', 'find currency ...');
                        $input.setAttribute('type', 'text currency ...');
                        $select.prepend($input);

                        // Bind currency search
                        $input.addEventListener('keyup', jQuery.debounce(300, () => {
                            let $container = $select,
                                s = $input.value.toUpperCase(),
                                codes_list = [];

                            // Reset old result
                            if (use_simple_bar)
                                $container = $container.querySelector('.simplebar-content');
                            $container.innerHTML = '';

                            // Ignore empty search for non-whitelist
                            if (!this.whitelist.length && !s) return;

                            // Create array of codes
                            // By whitelist
                            if (this.whitelist.length) {
                                for (let target of this.whitelist)
                                    if (target.toUpperCase().includes(s))
                                        codes_list.push(target.split('.'));
                            // By full list
                            } else {
                                for (let type of Object.keys(eod_service_data.converter_targets))
                                    for (let code of eod_service_data.converter_targets[type])
                                        if ((code + '.' + type).toUpperCase().includes(s))
                                            codes_list.push([code, type]);
                            }

                            // Sort
                            codes_list.sort(function (a, b) {
                                if (a[0].toUpperCase() === s)
                                    return -1;
                                else if (b[0].toUpperCase() === s)
                                    return 1;
                                return a[0].localeCompare(b[0]);
                            })

                            // Add item to DOM
                            for (let item of codes_list) {
                                let $li = document.createElement('li');
                                $li.addEventListener('click', () => select_currency($li));
                                $li.innerHTML = '<span class="code">' + item[0] + '</span>' +
                                                '<span class="ex">' + item[1].toUpperCase() + '</span>';
                                $container.append($li);
                            }

                        }));
                        $input.dispatchEvent(new Event('keyup', {}));

                        // Add select to DOM
                        $label.parentNode.after($select);
                    }
                    // Focus on search input
                    $select.querySelector('input').focus();

                    // Add outside click listener
                    let handler_is_exist = document.body.classList.contains('__outside_click_listener')
                    if (!handler_is_exist) {
                        document.body.classList.add('__outside_click_listener')
                        document.addEventListener('click', click_outside_select_list, true);
                    }
                });
            }
        }

        // link DOM element with instance
        this.$box.EOD_Converter = this;

        this.run();
        this.init();
    }

    /**
     * Set main currency
     * @param $input
     */
    set_main_currency( $input = false ){
        if(!this.flex_main_c) return; // disabled feature

        if(!$input)
            $input = this.$changing_input;
        let $other_input = $input === this.$main_input ? this.$changing_input : this.$main_input;
        $input.parentNode.classList.add('main');
        $other_input.parentNode.classList.remove('main');
        this.$changing_input = $other_input;
        this.$main_input = $input;

        // Add value to empty main input and delete value of changing input
        this.$changing_input.value = '';
        if(!parseInt(this.$main_input.value))
            this.$main_input.value = 1;

        // refresh
        this.refresh();
    }

    /**
     * Swap currency
     */
    swap_currency(){
        this.stop();

        // swap DOM
        this.$c1_box.prepend(this.$c2_box.children[0]);
        this.$c2_box.prepend(this.$c1_box.children[1]);

        // swap ratio and currencies
        this.currencies = [this.currencies[1], this.currencies[0]];
        this.ratio = [this.ratio[1], this.ratio[0]];

        // refresh converter
        this.run();
        this.refresh();
    }

    /**
     * Fit font size of code labels
     */
    fit_font_size(){
        let greatest_length = Math.max(this.$c1_box.querySelector('label').innerText.length, this.$c2_box.querySelector('label').innerText.length),
            size = (greatest_length > 10) ? '0.4em' : ((greatest_length > 5) ? '0.7em' : null)

        // set font size
        this.$c1_box.children[0].style.fontSize = this.$c2_box.children[0].style.fontSize = size;
    }

    /**
     * Set ratio value for currency
     * @param currency {string} - slug with "_" instead of "." and "-"
     * @param value {number}
     */
    set_ratio(currency, value){
        let index = this.currencies.indexOf(currency);
        if(index === -1) return;
        this.ratio[index] = value;
    }

    /**
     * Runtime control
     */
    stop(){
        this.$box.classList.add('paused');
        this.runned = false;
    }
    run(){
        this.$box.classList.remove('paused');
        this.runned = true;
    }


    /**
     * Use saved conversion rate to calculate and refresh displayed currency values
     */
    refresh(){
        // Ignore paused converter
        if( !this.runned ) return;

        // If ratio pair not contain two slugs, then not main input must be cleared.
        if(this.currencies.includes('')){
            this.$changing_input.value = '';
            return;
        }

        // Wait value
        if(this.ratio.includes(0)) return;

        // Calc and change value
        let ratio_value = this.ratio[0] / this.ratio[1],
            main_val = parseFloat( this.$main_input.value ),
            new_value = this.$changing_input.parentNode.classList.contains('first') ? (main_val / ratio_value) : (main_val * ratio_value);
        this.$changing_input.value = new_value > 1 ? new_value.toFixed(2) : new_value.toFixed(5);
    }

    /**
     * Use selected currency to get ratio values using the EOD API
     */
    init(){
        // Get pairs of targets, types and slugs
        let codes = [],
            types = [],
            targets = [];
        for( let $c_box of [this.$c1_box, this.$c2_box] ){
            let $label = $c_box.querySelector('label');
            codes.push( $label.innerText );
            types.push( $label.getAttribute('data-type') );
        }

        if(!codes[0] || !codes[1] || !types[0] || !types[1]) return;

        /**
         * For conversion, not one but two tickers may be required
         * Any target has a pair with USD, so we use USD to link
         * USD is placed second in the target
         * Cryptocurrency through a dash, and currency all together
         * And add type to the end (.CC, .FOREX)
         * Create a target and check it through the API
         */

        for(let i=0; i<2; i++){
            if( types[i].toLowerCase() === 'cc' ) {
                targets.push(codes[i] + '-USD.CC');
                this.currencies[i] = codes[i] + '_USD_CC';
            } else {
                targets.push(codes[i] + 'USD.FOREX');
                this.currencies[i] = codes[i] + 'USD_FOREX';
            }
            // Add slug to label
            [this.$c1_box, this.$c2_box][i].querySelector('label').setAttribute('data-slug', this.currencies[i]);
        }

        this.init_callback(this, targets);
    }
}

function eod_display_converters( $items = false ) {
    if($items && !($items instanceof jQuery)) return;
    if(!$items) $items = jQuery(".eod_converter");

    $items.each(function(){
        // Prepare the converter item. Create interface elements and add listeners.
        let converter = new EOD_Converter({
            box: jQuery(this)[0],
            flex_main_currency: true,
            swappable: true,
            init_callback: (converter, targets) => {
                // Get start value by live API
                get_eod_ticker('live', targets, (data) => {
                    if (!data || data.error) return false;

                    // set ratio and refresh converter
                    for(let item of data){
                        if(!item || !item.close) continue;
                        let slug = item.code.replace(/(-)|(\.)/g, '_');
                        converter.set_ratio(slug, item.close);
                    }
                    converter.refresh();
                });

                // Add websocket listeners for realtime data updating
                for(let i=0; i<2; i++) {
                    let target_data = targets[i].split('.'),
                        ws_data = check_ws_eod_data_item(target_data[1], target_data[0]),
                        slug = converter.currencies[i];
                    ws_data.listeners['converter'] = function (res) {
                        if (!res.p && !res.a) return;

                        for( let $label of document.querySelectorAll('.eod_converter label[data-slug="'+slug+'"]') ){
                            // set ratio and refresh converter
                            converter.set_ratio(slug, parseFloat( res.p ? res.p : res.a ));
                            converter.refresh();
                        }
                    };
                }
            }
        })
    });
}

/* =========================================
     loading and displaying fundamental data
   ========================================= */
function eod_display_fundamental_data(){
    // Fundamental data include simple list data (.eod_fd_list) and financials tables (.eod_financials)
    // Finding and prepare all tickers. Creating list.
    let eod_t_list = [];

    jQuery(".eod_fd_list, .eod_financials").each(function(){
        jQuery(this).addClass('eod_loading');
        let target = jQuery(this).attr('data-target');

        if(!target){
            jQuery(this).addClass('has_error').removeClass('eod_loading');
            let type = jQuery(this).hasClass('eod_fd_list') ? 'Fundamental data' : 'Financial table'
            jQuery(this).html('<div class="eod_error">'+type+': no target selected</div>');
        }

        // Common ticker
        if( eod_t_list.indexOf(target) === -1 )
            eod_t_list.push(target);
    });

    // Get Fundamental Data and display
    for(let target of eod_t_list) {
        // Find fundamental data elements
        let trg = target.toLowerCase().split('.'),
            $fd_list = jQuery('.eod_fd_list.eod_t_'+trg.join('_')),
            $financials_table = jQuery('.eod_financials.eod_t_'+trg.join('_'));

        // Log
        eod_debug.addTimePoint('AJAX request to the fundamental data API for ('+target+')',{
            type: 'fundamental',
            slug: target,
            moment: 'start'
        });

        get_eod_fundamental(target, function (data) {
            // Log
            eod_debug.addTimePoint('AJAX response from the fundamental data API for ('+target+'), render financials table and list',{
                type: 'fundamental',
                slug: target,
                moment: 'end'
            });

            if(!data || data.error) {
                // Hide loading animation
                $fd_list.removeClass('eod_loading');
                $financials_table.removeClass('eod_loading');
                return false;
            }

            // Render data
            // for simple data list
            $fd_list.each(function(){
                jQuery(this).find('> li').each(function(){
                    let $li = jQuery(this),
                        slug = $li.attr('data-slug');
                    if(!slug) return;

                    // Find value in data
                    let path = slug.split('::'),
                        value = data;
                    for(let key of path) {
                        if (typeof value === 'object' && value !== null) {
                            if(!value.hasOwnProperty(key) || value[key] === undefined) return;
                            value = value[key];
                        }
                    }

                    // Display string or number value as string
                    if( ['number', 'string', 'undefined'].indexOf(typeof value) > -1 ) {
                        // Define type and abbreviate numeric value
                        if($li.attr('data-type') === 'number')
                            value = abbreviateNumber(value);

                        $li.append('<span>' + value + '</span>');

                    // No data
                    }else if(value === null){
                        $li.append('<span>NULL</span>');

                    // Boolean
                    }else if(typeof value === 'boolean'){
                        $li.append('<span>' + (value ? 'true' : 'false') + '</span>');

                    // Display object as table
                    }else if(typeof value === 'object'){
                        // Empty object
                        if(Object.keys(value).length === 0){
                            if(eod_display_settings.fd_no_data_warning)
                                $li.append('<span class="eod_error">empty list</span>');
                            return;
                        }

                        let Table = new EodCreateTable({
                                type: 'table'
                            });

                        // The parameter item may contain a title in its own key
                        let has_row_name = !Array.isArray(value);

                        // Table header
                        let [first_item_key] = Object.keys(value);
                        if( typeof( value[first_item_key] ) === 'object' ) {
                            // Prepare header keys
                            let header_keys = [];
                            for(let raw_label of Object.keys(value[first_item_key])){
                                let col_label = '';
                                raw_label = raw_label.replace('_', ' ');
                                raw_label[0] = raw_label[0].toUpperCase();
                                for(let i=0; i<raw_label.length; i++){
                                    let s = raw_label[i];
                                    // first symbol is always upper
                                    if( i === 0 )
                                        s = s.toUpperCase();
                                    // split words
                                    if( (s.toUpperCase() === s && raw_label[i-1] !== ' ') || s === '_' ){
                                        col_label += ' ';
                                    }

                                    if( s !== '_' )
                                        col_label += s;
                                }
                                header_keys.push(col_label);
                            }
                            if(has_row_name){
                                Table.set_header( ['', ...header_keys] );
                            }else{
                                Table.set_header( header_keys );
                            }
                        }

                        // Table body
                        for (let [index, item] of Object.entries(value)) {
                            let values_list = typeof item === 'object' ? Object.values(item) : [item];
                            if(has_row_name){
                                Table.add_row( [index, ...values_list] );
                            }else{
                                Table.add_row( values_list );
                            }
                        }

                        // Show table
                        let $wrapper = jQuery('<div class="eod_table_wrapper"></div>');
                        $wrapper.append( Table.get_table() )

                        // Add custom scrollbar
                        if(typeof SimpleBar === 'function')
                            new SimpleBar( $wrapper[0] );

                        $li.append( $wrapper );
                    }
                });
                // Hide loading animation
                jQuery(this).removeClass('eod_loading');
            });

            // for financials tables
            $financials_table.each(function(){
                // Save data in element
                jQuery(this).data('data', data);

                // Render table
                eod_render_financial_table( jQuery(this) );
                // Hide loading animation
                jQuery(this).removeClass('eod_loading');
            });
        });
    }
}

/* =========================================
           render financial table
   ========================================= */
function eod_render_financial_table( $table_box ) {
    let f_data = $table_box.data('data');
    if (!f_data) return;

    let Table = new EodCreateTable({
            type: 'div'
        }),
        selected_timeline = $table_box.data('selected_timeline'),
        group = $table_box.attr('data-group') ? $table_box.attr('data-group').split('::') : false,
        parameters = $table_box.attr('data-cols'),
        years = $table_box.attr('data-years');

    if (!group || !parameters) {
        $table_box.html( '<div class="eod_error">Financial table: parameter group not specified.</div>' );
        return;
    }

    // Check term type
    if( f_data.General && f_data.General.Type && f_data.General.Type.toLowerCase() !== 'common stock'){
        $table_box.html( '<div class="eod_error">Financial table: only used for common stock.</div>' );
        return;
    }

    // The source data may contain separate arrays: 'yearly', 'quarterly'.
    // Or without them, but assuming that the list of data has the same gradation
    // either by 'yearly' or by 'quarterly'.
    // This parameter determines which key to use or how interpret date keys in the list.
    let timeline_type = getFinancialItem( $table_box.attr('data-group'), $table_box.attr('data-group') ).timeline;

    if( !selected_timeline )
        selected_timeline = timeline_type === 'both' ? 'yearly' : timeline_type;

    // Define data group
    while (group.length) {
        let key = group.shift();
        if (f_data[key]) {
            f_data = f_data[key];
        } else {
            $table_box.html( '<div class="eod_error">Financial table: not enough data to build.</div>' );
            return;
        }
    }

    // Define currency. Not every group contain parameter.
    let currency = f_data.currency_symbol;

    // Financials list may contain separate arrays: 'yearly', 'quarterly'.
    // Select specific
    if (timeline_type === 'both'){
        if (f_data[selected_timeline]) f_data = f_data[selected_timeline];
        else return;
    }

    // If 'currency_symbol' not found select first item and get currency.
    // This method cannot be used as the main one because not every item has currency/currency_symbol.
    if(!currency) currency = Object.values( f_data )[0].currency;

    // Prepare time interval
    if(years){
        years = years.split('-');
        if(years.length < 2) years = false;
    }

    // Add timeline toggle
    if( timeline_type === 'both' && $table_box.children('.eod_toggle').length === 0 ) {
        let $toggle = jQuery('<button class="eod_toggle timeline">\
                                <span>Annual</span>\
                                <span>Quarterly</span>\
                              </button>');

        // Toggle default option
        $toggle.find('span').eq( selected_timeline === 'quarterly' ? 1 : 0 ).addClass('selected');

        // Toggle event
        $toggle.click(function (e) {
            let $target = jQuery(e.target);
            if( $target.hasClass('selected') ) return;
            jQuery(this).toggleClass('on');
            jQuery(this).find('span').toggleClass('selected');
            $table_box.data('selected_timeline', jQuery(this).hasClass('on') ? 'quarterly' : 'yearly');
            eod_render_financial_table($table_box);
        });
        $table_box.prepend($toggle);
    }

    // Remove old tables
    $table_box.find('.eod_tbody').html('');

    // First header row
    let dates = [];
    for(let [date, item] of Object.entries( f_data )){
        let d = new Date( date ),
            y = d.getFullYear(),
            m = d.getMonth()+1,
            display_date = '';

        // Filter by date interval
        if(years && !( (!years[0] || years[0] <= y) && (!years[1] || y <= years[1]) ))
            continue;

        if(selected_timeline === 'yearly')
            display_date = y;
        else if(selected_timeline === 'quarterly'){
            display_date = 'Q' + Math.ceil(m/3) + " '" + y;
        }

        dates.push('<div>'+ display_date +'</div>');
    }
    Table.set_header( ['<span>Currency: '+currency+'</span>', ...dates.reverse()] );

    // Another rows of stats
    for(let parameter_path of parameters.split(';')){
        // First column of parameters names
        let financial_item = getFinancialItem($table_box.attr('data-group'), parameter_path),
            display_name = financial_item.title,
            parameter_key = parameter_path.split('::').pop();
        if(!display_name) display_name = parameter_key;
        display_name = '<span title="'+ display_name +'">'+ display_name +'</span>';

        // Another columns of parameters values
        let cols = [];
        for(let [date, item] of Object.entries( f_data )){
            let value = '',
                d = new Date( date ),
                y = d.getFullYear();

            // Filter by date interval
            if(years && !( (!years[0] || years[0] <= y) && (!years[1] || y <= years[1]) ))
                continue;

            if(item[parameter_key] === 0 || item[parameter_key]) value = abbreviateNumber(item[parameter_key]);
            cols.push( (value === '' ? '-' : value) );
        }

        Table.add_row( [display_name, ...cols.reverse()] );
    }

    // Show table
    $table_box.find('.eod_tbody').replaceWith( Table.get_tbody() );
}

function getFinancialItem(group, path_string) {
    let path = path_string.split('::'),
        item = eod_display_settings.financial_hierarchy[ group.replace('::', '->') ];

    if(!item) return {};

    while( path.length && item ){
        let key = path.shift(),
            list = item.hasOwnProperty('list') ? item.list : item;
        if( list.hasOwnProperty(key) ) {
            item = list[key];
        } else {
            return {};
        }
    }
    return item;
}

function getTextWidth(text, font) {
    // re-use canvas object for better performance
    const canvas = getTextWidth.canvas || (getTextWidth.canvas = document.createElement("canvas"));
    const context = canvas.getContext("2d");
    context.font = font;
    const metrics = context.measureText(text);
    return metrics.width;
}

function getCssStyle(element, prop) {
    return window.getComputedStyle(element, null).getPropertyValue(prop);
}

function getCanvasFontSize(el = document.body) {
    const fontWeight = getCssStyle(el, 'font-weight') || 'normal';
    const fontSize = getCssStyle(el, 'font-size') || '16px';
    const fontFamily = getCssStyle(el, 'font-family') || 'Times New Roman';

    return `${fontWeight} ${fontSize} ${fontFamily}`;
}

class EodCreateTable {
    constructor(p) {
        const _this = this;
        _this.header = [];
        _this.rows = [];
        _this.type = p.type ? p.type : 'div';
        _this.template = {
            table: {
                table: 'table',
                tbody: 'tbody',
                row: 'tr',
                header: 'th',
                cell: 'td'
            },
            div: {
                table: 'div',
                tbody: 'div',
                row: 'div',
                header: 'div',
                cell: 'div'
            }
        }[_this.type];
    }

    set_header( list ) {
        const _this = this;
        _this.header_list = list;
    }

    add_row( list ) {
        const _this = this;
        _this.rows.push( list );
    }

    get_tbody() {
        const _this = this;
        let tag = _this.template,
            $tbody = jQuery('<'+tag.tbody+' class="eod_tbody"></'+tag.tbody+'>');

        // Header
        if ( Array.isArray( _this.header_list ) && _this.header_list.length ){
            let $header = jQuery('<'+tag.row+' class="header"></'+tag.row+'>');
            for (let item of _this.header_list) {
                $header.append('<'+tag.header+'>' + item + '</'+tag.header+'>');
            }
            $tbody.append($header);
        }

        // Body
        for( let row of _this.rows ){
            let $row = jQuery('<'+tag.row+'></'+tag.row+'>');
            for( let item of row ){
                $row.append('<'+tag.cell+'>' + item + '</'+tag.cell+'>');
            }
            $tbody.append($row);
        }

        return $tbody;
    }

    get_table() {
        let tag = this.template,
            $table = jQuery('<'+tag.table+' class="eod_table"></'+tag.table+'>');
        $table.append( this.get_tbody() )
        return $table;
    }

    //     max_first_col_width = 0,
    //     font_styles = 'normal 12px ' + getCssStyle(document.body, 'font-family') || 'Times New Roman';

    // for (let [index, item] of Object.entries(value)) {
    //     let $row = jQuery('<div><div>'+index+'</div></div>'),
    //         first_col_width = getTextWidth( index, font_styles );
    //
    //     if( first_col_width > max_first_col_width ) max_first_col_width = first_col_width;
    // }
    //
    // max_first_col_width += 15;
    // $table.find('.eod_tbody > div > div:first-child').css({'width': max_first_col_width + 'px'});
}


