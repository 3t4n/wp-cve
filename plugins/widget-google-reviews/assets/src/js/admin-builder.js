const GRW_AUTOSAVE_KEYUP_TIMEOUT = 1500;
var GRW_AUTOSAVE_TIMEOUT = null;

const GRW_LANGS = [
    ['ar', 'Arabic'],
    ['bg', 'Bulgarian'],
    ['bn', 'Bengali'],
    ['ca', 'Catalan'],
    ['cs', 'Czech'],
    ['da', 'Danish'],
    ['de', 'German'],
    ['el', 'Greek'],
    ['en', 'English'],
    ['es', 'Spanish'],
    ['eu', 'Basque'],
    ['eu', 'Basque'],
    ['fa', 'Farsi'],
    ['fi', 'Finnish'],
    ['fil', 'Filipino'],
    ['fr', 'French'],
    ['gl', 'Galician'],
    ['gu', 'Gujarati'],
    ['hi', 'Hindi'],
    ['hr', 'Croatian'],
    ['hu', 'Hungarian'],
    ['id', 'Indonesian'],
    ['it', 'Italian'],
    ['iw', 'Hebrew'],
    ['ja', 'Japanese'],
    ['kn', 'Kannada'],
    ['ko', 'Korean'],
    ['lt', 'Lithuanian'],
    ['lv', 'Latvian'],
    ['ml', 'Malayalam'],
    ['mr', 'Marathi'],
    ['nl', 'Dutch'],
    ['no', 'Norwegian'],
    ['pl', 'Polish'],
    ['pt', 'Portuguese'],
    ['pt-BR', 'Portuguese (Brazil)'],
    ['pt-PT', 'Portuguese (Portugal)'],
    ['ro', 'Romanian'],
    ['ru', 'Russian'],
    ['sk', 'Slovak'],
    ['sl', 'Slovenian'],
    ['sr', 'Serbian'],
    ['sv', 'Swedish'],
    ['ta', 'Tamil'],
    ['te', 'Telugu'],
    ['th', 'Thai'],
    ['tl', 'Tagalog'],
    ['tr', 'Turkish'],
    ['uk', 'Ukrainian'],
    ['vi', 'Vietnamese'],
    ['zh', 'Chinese (Simplified)'],
    ['zh-Hant', 'Chinese (Traditional)']
];

var GRW_HTML_CONTENT = '' +

    '<div class="grw-builder-platforms grw-builder-inside">' +

        '<div class="grw-builder-connect grw-connect-google">Google Connection</div>' +
        '<div id="grw-connect-wizard" title="Easy steps to connect Google Reviews" style="display:none;">' +
            '<p>' +
                '<span>1</span> ' +
                'Find your Google place on the map below (<u class="grw-wiz-arr">Enter a location</u>) and copy found <u><b>Place ID</b></u>' +
            '</p>' +
            '<iframe src="https://geo-devrel-javascript-samples.web.app/samples/places-placeid-finder/app/dist" loading="lazy" style="width:100%;height:250px"></iframe>' +
            '<small style="font-size:13px;color:#555">If you can\'t find your place on this map, please read <a href="' + GRW_VARS.supportUrl + '&grw_tab=fig#place_id" target="_blank">this manual how to find any Google Place ID</a>.</small>' +
            '<p>' +
                '<span>2</span> ' +
                'Paste copied <u><b>Place ID</b></u> in this field and select language if needed' +
            '</p>' +
            '<p>' +
                '<input type="text" class="grw-connect-id" value="" placeholder="Place ID" />' +
                '<select class="grw-connect-lang"><option value="" selected="selected">Choose language if needed</option><option value="ar">Arabic</option><option value="bg">Bulgarian</option><option value="bn">Bengali</option><option value="ca">Catalan</option><option value="cs">Czech</option><option value="da">Danish</option><option value="de">German</option><option value="el">Greek</option><option value="en">English</option><option value="es">Spanish</option><option value="eu">Basque</option><option value="eu">Basque</option><option value="fa">Farsi</option><option value="fi">Finnish</option><option value="fil">Filipino</option><option value="fr">French</option><option value="gl">Galician</option><option value="gu">Gujarati</option><option value="hi">Hindi</option><option value="hr">Croatian</option><option value="hu">Hungarian</option><option value="id">Indonesian</option><option value="it">Italian</option><option value="iw">Hebrew</option><option value="ja">Japanese</option><option value="kn">Kannada</option><option value="ko">Korean</option><option value="lt">Lithuanian</option><option value="lv">Latvian</option><option value="ml">Malayalam</option><option value="mr">Marathi</option><option value="nl">Dutch</option><option value="no">Norwegian</option><option value="pl">Polish</option><option value="pt">Portuguese</option><option value="pt-BR">Portuguese (Brazil)</option><option value="pt-PT">Portuguese (Portugal)</option><option value="ro">Romanian</option><option value="ru">Russian</option><option value="sk">Slovak</option><option value="sl">Slovenian</option><option value="sr">Serbian</option><option value="sv">Swedish</option><option value="ta">Tamil</option><option value="te">Telugu</option><option value="th">Thai</option><option value="tl">Tagalog</option><option value="tr">Turkish</option><option value="uk">Ukrainian</option><option value="vi">Vietnamese</option><option value="zh">Chinese (Simplified)</option><option value="zh-Hant">Chinese (Traditional)</option></select>' +
            '</p>' +
            '<p><span>3</span> Click CONNECT GOOGLE button</p>' +
            '<button class="grw-connect-btn">Connect Google</button>' +
            '<small class="grw-connect-error"></small>' +
        '</div>' +
        '<div class="grw-connections"></div>' +
    '</div>' +

    '<div class="grw-connect-options">' +

        '<div class="grw-builder-inside">' +

            '<div class="grw-builder-option">' +
                'Layout' +
                '<select id="view_mode" name="view_mode">' +
                    '<option value="slider" selected="selected">Slider</option>' +
                    '<option value="grid">Grid</option>' +
                    '<option value="list">List</option>' +
                '</select>' +
            '</div>' +

        '</div>' +

        /* Common Options */
        '<div class="grw-builder-top grw-toggle">Common Options</div>' +
        '<div class="grw-builder-inside" style="display:none">' +
            '<div class="grw-builder-option">' +
                'Pagination' +
                '<input type="text" name="pagination" value="">' +
            '</div>' +
            '<div class="grw-builder-option">' +
                'Maximum characters before \'read more\' link' +
                '<input type="text" name="text_size" value="">' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="header_center" value="">' +
                    'Show rating by center' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="header_hide_photo" value="">' +
                    'Hide business photo' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="header_hide_name" value="">' +
                    'Hide business name' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="hide_based_on" value="">' +
                    'Hide \'Based on ... reviews\'' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="hide_writereview" value="">' +
                    'Hide \'review us on G\' button' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="header_hide_social" value="">' +
                    'Hide rating header, leave only reviews' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="hide_reviews" value="">' +
                    'Hide reviews, leave only rating header' +
                '</label>' +
            '</div>' +
        '</div>' +

        /* Slider Options */
        '<div class="grw-builder-top grw-toggle">Slider Options</div>' +
        '<div class="grw-builder-inside" style="display:none">' +
            '<div class="grw-builder-option">' +
                'Speed in second' +
                '<input type="text" name="slider_speed" value="" placeholder="Default: 5">' +
            '</div>' +
            '<div class="grw-builder-option">' +
                'Text height' +
                '<input type="text" name="slider_text_height" value="" placeholder="Default: 100px">' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="slider_autoplay" value="" checked>' +
                    'Auto-play' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="slider_hide_prevnext" value="">' +
                    'Hide prev & next buttons' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="slider_hide_dots" value="">' +
                    'Hide dots' +
                '</label>' +
            '</div>' +
        '</div>' +

        /* Style Options */
        '<div class="grw-builder-top grw-toggle">Style Options</div>' +
        '<div class="grw-builder-inside" style="display:none">' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="dark_theme">' +
                    'Dark background' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="hide_backgnd" value="">' +
                    'Hide reviews background' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="show_round" value="">' +
                    'Round reviews borders' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="show_shadow" value="">' +
                    'Show reviews shadow' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="centered" value="">' +
                    'Place by center (only if max-width is set)' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                'Container max-width' +
                '<input type="text" name="max_width" value="" placeholder="for instance: 300px">' +
                '<small>Be careful: this will make reviews unresponsive</small>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                'Container max-height' +
                '<input type="text" name="max_height" value="" placeholder="for instance: 500px">' +
            '</div>' +
        '</div>' +

        /* Advance Options */
        '<div class="grw-builder-top grw-toggle">Advance Options</div>' +
        '<div class="grw-builder-inside" style="display:none">' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="lazy_load_img" checked>' +
                    'Lazy load images' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="google_def_rev_link">' +
                    'Use default Google reviews link' +
                '</label>' +
                '<span class="grw-quest grw-quest-top grw-toggle" title="Click to help">?</span>' +
                '<div class="grw-quest-help" style="display:none;">If the direct link to all reviews <b>https://search.google.com/local/reviews?placeid=&lt;PLACE_ID&gt;</b> does not work with your Google place (leads to 404), please use this option to use the default reviews link to Google map.</div>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="open_link" checked>' +
                    'Open links in new Window' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="nofollow_link" checked>' +
                    'Use no follow links' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                'Reviewer avatar size' +
                '<select name="reviewer_avatar_size">' +
                    '<option value="56" selected="selected">Small: 56px</option>' +
                    '<option value="128">Medium: 128px</option>' +
                    '<option value="256">Large: 256px</option>' +
                '</select>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                'Cache data' +
                '<select name="cache">' +
                    '<option value="1">1 Hour</option>' +
                    '<option value="3">3 Hours</option>' +
                    '<option value="6">6 Hours</option>' +
                    '<option value="12" selected="selected">12 Hours</option>' +
                    '<option value="24">1 Day</option>' +
                    '<option value="48">2 Days</option>' +
                    '<option value="168">1 Week</option>' +
                    '<option value="">Disable (NOT recommended)</option>' +
                '</select>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                'Reviews limit' +
                '<input type="text" name="reviews_limit" value="">' +
            '</div>' +
        '</div>' +

    '</div>';

function grw_builder_init($, data) {

    var el = document.querySelector(data.el);
    if (!el) return;

    el.innerHTML = GRW_HTML_CONTENT;

    var $connect_wizard_el = $('#grw-connect-wizard');
        //connect_google_el = el.querySelector('.grw-connect-google-inside'),
        //google_pid_el = el.querySelector('.grw-connect-id');

    if (data.conns && data.conns.connections && data.conns.connections.length) {
        grw_deserialize_connections($, el, data);
    } else {
        $('.grw-connect-google').hide();
        $connect_wizard_el.dialog({
            modal: true,
            width: '50%',
            maxWidth: '600px',
            closeOnEscape: false,
            open: function() { $(".ui-dialog-titlebar-close").hide() }
        });
        //connect_google_el.style = '';
        //google_pid_el.focus();
    }

    // Google Connect
    grw_connection($, /*connect_google_el*/$connect_wizard_el[0], 'google', data.authcode);

    $('.grw-connect-options input[type="text"],.grw-connect-options textarea').keyup(function() {
        clearTimeout(GRW_AUTOSAVE_TIMEOUT);
        GRW_AUTOSAVE_TIMEOUT = setTimeout(grw_serialize_connections, GRW_AUTOSAVE_KEYUP_TIMEOUT);
    });
    $('.grw-connect-options input[type="checkbox"],.grw-connect-options select').change(function() {
        grw_serialize_connections();
    });

    $('.grw-toggle', el).unbind('click').click(function () {
        $(this).toggleClass('toggled');
        $(this).next().slideToggle();
    });

    $('.grw-builder-connect.grw-connect-google').click(function () {
        //google_pid_el.focus();
        $connect_wizard_el.dialog({modal: true, width: '50%', maxWidth: '600px'});
    });

    if ($('.grw-connections').sortable) {
        $('.grw-connections').sortable({
            stop: function(event, ui) {
                grw_serialize_connections();
            }
        });
        $('.grw-connections').disableSelection();
    }

    $('.wp-review-hide').click(function() {
        grw_review_hide($(this));
        return false;
    });

    $('#grw_save').click(function() {
        //grw_feed_save_ajax();
        grw_serialize_connections();
        return false;
    });

    window.addEventListener('beforeunload', function(e) {
        if (!GRW_AUTOSAVE_TIMEOUT) return undefined;

        var msg = 'It looks like you have been editing something. If you leave before saving, your changes will be lost.';
        (e || window.event).returnValue = msg;
        return msg;
    });
}

function grw_feed_save_ajax() {
    if (!window.grw_title.value) {
        window.grw_title.focus();
        return false;
    }

    window.grw_save.innerText = 'Auto save, wait';
    window.grw_save.disabled = true;

    jQuery.post(ajaxurl, {

        post_id   : window.grw_post_id.value,
        title     : window.grw_title.value,
        content   : document.getElementById('grw-builder-connection').value,
        action    : 'grw_feed_save_ajax',
        grw_nonce : jQuery('#grw_nonce').val()

    }, function(res) {

        var wpgr = document.querySelectorAll('.wp-gr');
        for (var i = 0; i < wpgr.length; i++) {
            wpgr[i].parentNode.removeChild(wpgr[i]);
        }

        window.grw_collection_preview.innerHTML = res;

        jQuery('.wp-review-hide').unbind('click').click(function() {
            grw_review_hide(jQuery(this));
            return false;
        });

        if (!window.grw_post_id.value) {
            var post_id = document.querySelector('.wp-gr').getAttribute('data-id');
            window.grw_post_id.value = post_id;
            window.location.href = window.location.href + '&grw_feed_id=' + post_id + '&grw_feed_new=1';
        } else {
            var $rateus = jQuery('#grw-rate_us');
            if ($rateus.length && !$rateus.hasClass('grw-flash-visible') && !window['grw_rateus']) {
                $rateus.addClass('grw-flash-visible');
            }
        }

        window.grw_save.innerText = 'Save & Update';
        window.grw_save.disabled = false;
        GRW_AUTOSAVE_TIMEOUT = null;
    });
}

function grw_feed_save() {
    if (!window.grw_title.value) {
        window.grw_title.focus();
        return false;
    }

    var content = document.getElementById('grw-builder-connection').value;
    if (content) {
        var json = JSON.parse(content)
        if (json) {
            if (json.connections && json.connections.length) {
                return true;
            }
        }
    }

    alert("Please click 'CONNECT GOOGLE' and connect your Google reviews then save this widget");
    return false;
}

function grw_review_hide($this) {

    jQuery.post(ajaxurl, {

        id          : $this.attr('data-id'),
        feed_id     : jQuery('input[name="grw_feed[post_id]"]').val(),
        grw_wpnonce : jQuery('#grw_nonce').val(),
        action      : 'grw_hide_review'

    }, function(res) {
        var parent = $this.parent().parent();
        if (res.hide) {
            $this.text('show review');
            parent.addClass('wp-review-hidden');
        } else {
            $this.text('hide review');
            parent.removeClass('wp-review-hidden');
        }
    }, 'json');
}

function grw_connection($, el, platform, authcode) {
    var connect_btn = el.querySelector('.grw-connect-btn');
    $(connect_btn).click(function() {

        var connect_id_el = el.querySelector('.grw-connect-id');
            //connect_key_el = el.querySelector('.grw-connect-key');

        if (!connect_id_el.value) {
            connect_id_el.focus();
            return false;
        }/* else if (!connect_key_el.value) {
            connect_key_el.focus();
            return false;
        }*/

        var id = (platform == 'yelp' ? /.+\/biz\/(.*?)(\?|\/|$)/.exec(connect_id_el.value)[1] : connect_id_el.value),
            lang = el.querySelector('.grw-connect-lang').value;
            //key = connect_key_el.value;

        connect_btn.innerHTML = 'Please wait...';
        connect_btn.disabled = true;

        grw_connect_ajax($, el, {id: id, lang: lang, platform: platform, local_img: true}, authcode, 1);
        return false;
    });
}

function grw_connect_ajax($, el, params, authcode, attempt) {

    var platform = params.platform,
        connect_btn = el.querySelector('.grw-connect-btn');

    window.grw_save.innerText = 'Auto save, wait';
    window.grw_save.disabled = true;

    $.post(ajaxurl, {

        id          : decodeURIComponent(params.id),
        lang        : params.lang,
        local_img   : params.local_img,
        feed_id     : $('input[name="grw_feed[post_id]"]').val(),
        grw_wpnonce : $('#grw_nonce').val(),
        action      : 'grw_connect_google',
        v           : new Date().getTime()

    }, function(res) {

        console.log('grw_connect_debug:', res);

        connect_btn.innerHTML = 'Connect ' + (platform.charAt(0).toUpperCase() + platform.slice(1));
        connect_btn.disabled = false;

        var error_el = el.querySelector('.grw-connect-error');

        if (res.status == 'success') {

            error_el.innerHTML = '';

            try { $('#grw-connect-wizard').dialog('close'); } catch (e) {}

            var connection_params = {
                id        : res.result.id,
                lang      : params.lang,
                name      : res.result.name,
                photo     : res.result.photo,
                refresh   : true,
                local_img : params.local_img,
                platform  : platform,
                props     : {
                    default_photo : res.result.photo
                }
            };

            grw_connection_add($, el, connection_params, authcode);
            grw_serialize_connections();

        } else {

            switch (res.result.error_message) {

                case 'usage_limit':
                    $('#dialog').dialog({width: '50%', maxWidth: '600px'});
                    break;

                case 'bot_check':
                    if (attempt > 1) {
                        return;
                    }
                    grw_popup('https://app.richplugins.com/gpaw/botcheck?authcode=' + authcode, 640, 480, function() {
                        grw_connect_ajax($, el, params, authcode, attempt + 1);
                    });
                    break;

                default:
                    if (res.result.error_message.indexOf('The provided Place ID is no longer valid') >= 0) {
                        error_el.innerHTML = 'It seems Google place which you are trying to connect ' +
                            'does not have a physical address (it\'s virtual or service area), ' +
                            'unfortunately, Google Places API does not support such locations, it\'s a limitation of Google, not the plugin.<br><br>' +
                            'However, you can try to connect your Google reviews in our new cloud service ' +
                            '<a href="https://trust.reviews" target="_blank">Trust.Reviews</a> ' +
                            'and show it on your WordPress site through universal <b>HTML/JavaScript</b> code.';
                    } else {
                        error_el.innerHTML = '<b>Error</b>: ' + res.result.error_message;
                    }
            }
        }

    }, 'json');
}

function grw_connection_add($, el, conn, authcode, checked) {

    var connected_id = grw_connection_id(conn),
        connected_el = $('#' + connected_id);

    if (!connected_el.length) {
        connected_el = $('<div class="grw-connection"></div>')[0];
        connected_el.id = connected_id;
        if (conn.lang != undefined) {
            connected_el.setAttribute('data-lang', conn.lang);
        }
        connected_el.setAttribute('data-platform', conn.platform);
        connected_el.innerHTML = grw_connection_render(conn, checked);

        var connections_el = $('.grw-connections')[0];
        connections_el.appendChild(connected_el);

        jQuery('.grw-toggle', connected_el).unbind('click').click(function () {
            jQuery(this).toggleClass('toggled');
            jQuery(this).next().slideToggle();
        });

        var file_frame;
        jQuery('.grw-connect-photo-change', connected_el).on('click', function(e) {
            e.preventDefault();
            grw_upload_photo(connected_el, file_frame, function() {
                grw_serialize_connections();
            });
            return false;
        });

        jQuery('.grw-connect-photo-default', connected_el).on('click', function(e) {
            grw_change_photo(connected_el, conn.props.default_photo);
            grw_serialize_connections();
            return false;
        });

        $('input[type="text"]', connected_el).keyup(function() {
            clearTimeout(GRW_AUTOSAVE_TIMEOUT);
            GRW_AUTOSAVE_TIMEOUT = setTimeout(grw_serialize_connections, GRW_AUTOSAVE_KEYUP_TIMEOUT);
        });

        $('input[type="checkbox"]', connected_el).click(function() {
            grw_serialize_connections();
        });

        $('select.grw-connect-lang', connected_el).change(function() {
            conn.lang = this.value;
            connected_el.id = grw_connection_id(conn);
            connected_el.setAttribute('data-lang', this.value);
            grw_connect_ajax($, el, conn, authcode, 1);
            return false;
        });

        $('input[name="local_img"]', connected_el).unbind('click').click(function() {
            conn.local_img = this.checked;
            grw_connect_ajax($, el, conn, authcode, 1);
        });

        $('.grw-connect-reconnect', connected_el).click(function() {
            grw_connect_ajax($, el, conn, authcode, 1);
            return false;
        });

        $('.grw-connect-delete', connected_el).click(function() {
            if (confirm('Are you sure to delete this business?')) {
                $(connected_el).remove();
                grw_serialize_connections();
            }
            return false;
        });
    }
}

function grw_connection_id(conn) {
    var id = 'grw-' + conn.platform + '-' + conn.id.replace(/\//g, '');
    if (conn.lang != null) {
        id += conn.lang;
    }
    return id;
}

function grw_connection_render(conn, checked) {
    var name = conn.name;
    if (conn.lang) {
        name += ' (' + conn.lang + ')';
    }

    conn.photo = conn.photo || 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    var option = document.createElement('option');
    if (conn.platform == 'google' && conn.props && conn.props.place_id) {
        option.value = conn.props.place_id;
    } else {
        option.value = conn.id;
    }
    option.text = grw_capitalize(conn.platform) + ': ' + conn.name;

    return '' +
        '<div class="grw-toggle grw-builder-connect grw-connect-business">' +
            '<input type="checkbox" class="grw-connect-select" onclick="event.stopPropagation();" ' + (checked?'checked':'') + ' /> ' +
            name + (conn.address ? ' (' + conn.address + ')' : '') +
        '</div>' +
        '<div style="display:none">' +
            (function(props) {
                var result = '';
                for (prop in props) {
                    if (prop != 'platform' && Object.prototype.hasOwnProperty.call(props, prop)) {
                        result += '<input type="hidden" name="' + prop + '" value="' + props[prop] + '" class="grw-connect-prop" readonly />';
                    }
                }
                return result;
            })(conn.props) +
            '<input type="hidden" name="id" value="' + conn.id + '" readonly />' +
            (conn.address ? '<input type="hidden" name="address" value="' + conn.address + '" readonly />' : '') +
            (conn.access_token ? '<input type="hidden" name="access_token" value="' + conn.access_token + '" readonly />' : '') +
            '<div class="grw-builder-option">' +
                '<img src="' + conn.photo + '" alt="' + conn.name + '" class="grw-connect-photo">' +
                '<a href="#" class="grw-connect-photo-change">Change</a>' +
                '<a href="#" class="grw-connect-photo-default">Default</a>' +
                '<input type="hidden" name="photo" class="grw-connect-photo-hidden" value="' + conn.photo + '" tabindex="2"/>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<input type="text" name="name" value="' + conn.name + '" />' +
            '</div>' +
            (conn.website != undefined ?
            '<div class="grw-builder-option">' +
                '<input type="text" name="website" value="' + conn.website + '" />' +
            '</div>'
            : '' ) +
            (conn.lang != undefined ?
            '<div class="grw-builder-option">' +
                //'<input type="text" name="lang" value="' + conn.lang + '" placeholder="Default language (English)" />' +
                grw_lang('Show all connected languages', conn.lang) +
            '</div>'
            : '' ) +
            (conn.review_count != undefined ?
            '<div class="grw-builder-option">' +
                '<input type="text" name="review_count" value="' + conn.review_count + '" placeholder="Total number of reviews" />' +
                '<span class="grw-quest grw-toggle" title="Click to help">?</span>' +
                '<div class="grw-quest-help">Google return only 5 most helpful reviews and does not return information about total number of reviews and you can type here it manually.</div>' +
            '</div>'
            : '' ) +
            (conn.refresh != undefined ?
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="refresh" ' + (conn.refresh ? 'checked' : '') + '>' +
                    'Update reviews daily' +
                '</label>' +
                '<span class="grw-quest grw-quest-top grw-toggle" title="Click to help">?</span>' +
                '<div class="grw-quest-help">' +
                    (conn.platform == 'google' ? 'The plugin uses the Google Places API to get your reviews. <b>The API only returns the 5 most helpful reviews (it\'s a limitation of Google, not the plugin)</b>. This option calls the Places API once in 24 hours (to keep the plugin\'s free and avoid a Google Billing) to check for a new reviews and if there are, adds to the plugin. Thus slowly building up a database of reviews.<br><br>Also if you see the new reviews on Google map, but after some time it\'s not added to the plugin, it means that Google does not include these reviews to the API and the plugin can\'t get this.<br><br>If you need to show <b>all reviews</b>, please use <a href="https://richplugins.com/business-reviews-bundle-wordpress-plugin?promo=GRGROW23" target="_blank">Business plugin</a> which uses a Google My Business API without API key and billing.' : '') +
                    (conn.platform == 'yelp' ? 'The plugin uses the Yelp API to get your reviews. <b>The API only returns the 3 most helpful reviews without sorting possibility.</b> When Yelp changes the 3 most helpful the plugin will automatically add the new one to your database. Thus slowly building up a database of reviews.' : '') +
                '</div>' +
            '</div>'
            : '' ) +
            '<div class="grw-builder-option">' +
                '<label>' +
                    '<input type="checkbox" name="local_img" ' + (conn.local_img ? 'checked' : '') + '>' +
                    'Save images locally (GDPR)' +
                '</label>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<button class="grw-connect-reconnect">Reconnect</button>' +
            '</div>' +
            '<div class="grw-builder-option">' +
                '<button class="grw-connect-delete">Delete connection</button>' +
            '</div>' +
        '</div>';
}

function grw_serialize_connections() {

    var connections = [],
        connections_el = document.querySelectorAll('.grw-connection');

    for (var i in connections_el) {
        if (Object.prototype.hasOwnProperty.call(connections_el, i)) {

            var select_el = connections_el[i].querySelector('.grw-connect-select');
            if (select_el && !grw_is_hidden(select_el) && !select_el.checked) {
                continue;
            }

            var connection = {},
                lang       = connections_el[i].getAttribute('data-lang'),
                platform   = connections_el[i].getAttribute('data-platform'),
                inputs     = connections_el[i].querySelectorAll('input');

            //connections[platform] = connections[platform] || [];

            if (lang != undefined) {
                connection.lang = lang;
            }

            for (var j in inputs) {
                if (Object.prototype.hasOwnProperty.call(inputs, j)) {
                    var input = inputs[j],
                        name = input.getAttribute('name');

                    if (!name) continue;

                    if (input.className == 'grw-connect-prop') {
                        connection.props = connection.props || {};
                        connection.props[name] = input.value;
                    } else {
                        connection[name] = (input.type == 'checkbox' ? input.checked : input.value);
                    }
                }
            }
            connection.platform = platform;
            connections.push(connection);
        }
    }

    var options = {},
        options_el = document.querySelector('.grw-connect-options').querySelectorAll('input[name],select,textarea');

    for (var o in options_el) {
        if (Object.prototype.hasOwnProperty.call(options_el, o)) {
            var input = options_el[o],
                name  = input.getAttribute('name');

            if (input.type == 'checkbox') {
                options[name] = input.checked;
            } else if (input.value != undefined) {
                options[name] = (
                                    input.type == 'textarea'     ||
                                    name       == 'word_filter'  ||
                                    name       == 'word_exclude' ?
                                    encodeURIComponent(input.value) : input.value
                                );
            }
        }
    }

    document.getElementById('grw-builder-connection').value = JSON.stringify({connections: connections, options: options});

    if (connections.length) {
        var first = connections[0],
            title = window.grw_title.value;

        if (!title) {
            window.grw_title.value = first.name;
        }
        grw_feed_save_ajax();
    } else {
        /*var connect_google_el = document.querySelector('.grw-connect-google-inside'),
            google_pid_el = document.querySelector('.grw-connect-id');

        connect_google_el.style = '';
        google_pid_el.focus();*/
    }
}

function grw_deserialize_connections($, el, data) {
    var connections = data.conns,
        options = connections.options;

    if (Array.isArray(connections.connections)) {
        connections = connections.connections;
    } else {
        var temp_conns = [];
        if (Array.isArray(connections.google)) {
            for (var c = 0; c < connections.google.length; c++) {
                connections.google[c].platform = 'google';
            }
            temp_conns = temp_conns.concat(connections.google);
        }
        if (Array.isArray(connections.facebook)) {
            for (var c = 0; c < connections.facebook.length; c++) {
                connections.facebook[c].platform = 'facebook';
            }
            temp_conns = temp_conns.concat(connections.facebook);
        }
        if (Array.isArray(connections.yelp)) {
            for (var c = 0; c < connections.yelp.length; c++) {
                connections.yelp[c].platform = 'yelp';
            }
            temp_conns = temp_conns.concat(connections.yelp);
        }
        connections = temp_conns;
    }

    for (var i = 0; i < connections.length; i++) {
        grw_connection_add($, el.querySelector('.grw-builder-platforms'), connections[i], data.authcode, true);
    }

    for (var opt in options) {
        if (Object.prototype.hasOwnProperty.call(options, opt)) {
            var control = el.querySelector('input[name="' + opt + '"],select[name="' + opt + '"],textarea[name="' + opt + '"]');
            if (control) {
                var name = control.getAttribute('name');
                if (typeof(options[opt]) === 'boolean') {
                    control.checked = options[opt];
                } else {
                    control.value = (
                                        control.type == 'textarea'     ||
                                        name         == 'word_filter'  ||
                                        name         == 'word_exclude' ?
                                        decodeURIComponent(options[opt]) : options[opt]
                                    );
                    if (opt.indexOf('_photo') > -1 && control.value) {
                        control.parentNode.querySelector('img').src = control.value;
                    }
                }
            }
        }
    }
}

function grw_upload_photo(el, file_frame, cb) {
    if (file_frame) {
        file_frame.open();
        return;
    }

    file_frame = wp.media.frames.file_frame = wp.media({
        title: jQuery(this).data('uploader_title'),
        button: {text: jQuery(this).data('uploader_button_text')},
        multiple: false
    });

    file_frame.on('select', function() {
        var attachment = file_frame.state().get('selection').first().toJSON();
        grw_change_photo(el, attachment.url);
        cb && cb(attachment.url);
    });
    file_frame.open();
}

function grw_change_photo(el, photo_url) {
    var place_photo_hidden = jQuery('.grw-connect-photo-hidden', el),
        place_photo_img = jQuery('.grw-connect-photo', el);

    place_photo_hidden.val(photo_url);
    place_photo_img.attr('src', photo_url);
    place_photo_img.show();

    grw_serialize_connections();
}

function grw_popup(url, width, height, cb) {
    var top = top || (screen.height/2)-(height/2),
        left = left || (screen.width/2)-(width/2),
        win = window.open(url, '', 'location=1,status=1,resizable=yes,width='+width+',height='+height+',top='+top+',left='+left);
    function check() {
        if (!win || win.closed != false) {
            cb();
        } else {
            setTimeout(check, 100);
        }
    }
    setTimeout(check, 100);
}

function grw_randstr(len) {
   var result = '',
       chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
       charsLen = chars.length;
   for ( var i = 0; i < len; i++ ) {
      result += chars.charAt(Math.floor(Math.random() * charsLen));
   }
   return result;
}

function grw_is_hidden(el) {
    return el.offsetParent === null;
}

function grw_capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function grw_lang(defname, lang) {
    var html = '';
    for (var i = 0; i < GRW_LANGS.length; i++) {
        html += '<option value="' + GRW_LANGS[i][0] + '"' + (lang == GRW_LANGS[i][0] ? ' selected="selected"' : '') + '>' + GRW_LANGS[i][1] + '</option>';
    }
    return '<select class="grw-connect-lang" name="lang">' +
               '<option value=""' + (lang ? '' : ' selected="selected"') + '>' + defname + '</option>' +
               html +
           '</select>';
}