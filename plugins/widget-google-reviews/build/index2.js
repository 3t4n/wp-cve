(function($, blocks, editor, element, components, api) {

    var el                = element.createElement,
        InspectorControls = editor.InspectorControls,
        PanelBody         = components.PanelBody,
        SelectControl     = components.SelectControl,
        TextControl       = components.TextControl,
        CheckboxControl   = components.CheckboxControl,
        __                = wp.i18n.__;

    function LangControl(def, lang) {
        let opts = [];
        opts.push(el('option', {value: ''}, def));

        for (let i = 0; i < GRW_LANGS.length; i++) {
            let param = {value: GRW_LANGS[i][0]};
            if (GRW_LANGS[i][0] == lang) {
                param.selected = 'selected';
            }
            opts.push(el('option', param, GRW_LANGS[i][1]));
        }
        return el
        (
            'select',
            {
                name      : 'lang',
                type      : 'select',
                className : 'grw-connect-lang'
            },
            opts
        );
    }

    function CollsControl(def, lang) {
        /*const data = {action: 'grw_feed_list_ajax', grw_nonce: grwBlockData.nonce, v: new Date().getTime()};
        wp.apiFetch({
            method: 'GET',
            url: wp.url.addQueryArgs(ajaxurl, data)
        })
        .then(colls => {
            if (colls && colls.length) {
                let select = document.querySelector('.grw-connect-coll');
                for (let i = 0; i < colls.length; i++) {
                    let param = {value: colls[i].id};
                    select.appendChild(el('option', param, colls[i].name));
                }
            }

        })
        .catch(error => {
            console.error('Error during AJAX request:', error);
        });*/

        let feeds = grwBlockData.feeds,
            opts = [el('option', {value: 0}, 'Use existing reviews widget')];

        for (let i = 0; i < feeds.length; i++) {
            let param = {value: feeds[i].id};
            opts.push(el('option', param, feeds[i].name));
        }
        return el
        (
            'select',
            {
                name      : 'coll',
                type      : 'select',
                className : 'grw-connect-coll'
            },
            opts
        );
    }

    const OPTIONS = {

        'Common Options' : {
            pagination: {
                type: 'integer',
                label: 'Pagination'/*,
                default: 10*/
            },
            text_size: {
                type: 'string',
                label: 'Maximum characters before \'read more\' link'
            },
            header_center: {
                type: 'boolean',
                label: 'Show rating by center'
            },
            header_hide_photo: {
                type: 'boolean',
                label: 'Hide business photo'
            },
            header_hide_name: {
                type: 'boolean',
                label: 'Hide business name'
            },
            hide_based_on: {
                type: 'boolean',
                label: 'Hide \'Based on ... reviews\''
            },
            hide_writereview: {
                type: 'boolean',
                label: 'Hide \'review us on G\' button'
            },
            header_hide_social: {
                type: 'boolean',
                label: 'Hide rating header, leave only reviews'
            },
            hide_reviews: {
                type: 'boolean',
                label: 'Hide reviews, leave only rating header'
            }
        },

        'Slider Options' : {
            slider_speed: {
                type: 'integer',
                label: 'Speed in second',
                default: 5
            },
            slider_text_height: {
                type: 'string',
                label: 'Text height'
            },
            slider_autoplay: {
                type: 'boolean',
                label: 'Auto-play',
                default: true
            },
            slider_hide_border: {
                type: 'boolean',
                label: 'Hide background'
            },
            slider_hide_prevnext: {
                type: 'boolean',
                label: 'Hide prev & next buttons'
            },
            slider_hide_dots: {
                type: 'boolean',
                label: 'Hide dots'
            }
        },

        'Style Options' : {
            max_width: {
                type: 'string',
                label: 'Container max-width'
            },
            max_height: {
                type: 'string',
                label: 'Container max-height'
            },
            centered: {
                type: 'boolean',
                label: 'Place by center (only if max-width is set)'
            },
            dark_theme: {
                type: 'boolean',
                label: 'Dark background'
            }
        },

        'Advance Options' : {
            lazy_load_img: {
                type: 'boolean',
                label: 'Lazy load images',
                default: true
            },
            google_def_rev_link: {
                type: 'boolean',
                label: 'Use default Google reviews link',
                default: false
            },
            open_link: {
                type: 'boolean',
                label: 'Open links in new Window',
                default: true
            },
            nofollow_link: {
                type: 'boolean',
                label: 'Use no follow links',
                default: true
            },
            reviewer_avatar_size: {
                type: 'integer',
                label: 'Reviewer avatar size',
                default: 56
            },
            cache: {
                type: 'integer',
                label: 'Cache data',
                default: 12
            },
            reviews_limit: {
                type: 'string',
                label: 'Reviews limit'
            }
        }
    };

    blocks.registerBlockType('widget-google-reviews/reviews', {
        title: __('Google Reviews Block', 'widget-google-reviews'),
        icon: 'star-filled',
        category: 'widgets',
        keywords: ['google', 'reviews', 'google reviews', 'rating'],

        attributes: (function() {
            var atts = {
                id: {
                    type: 'integer'
                },
                connections: {
                    type: 'array',
                    default: [],
                    query: {
                        id:        { type: 'string', },
                        name:      { type: 'string', },
                        photo:     { type: 'string', },
                        lang:      { type: 'string', },
                        refresh:   { type: 'boolean', },
                        local_img: { type: 'boolean', },
                        platform:  { type: 'string', }
                    },
                    group: 'Connections',
                },
                view_mode: {
                    type: 'string',
                    default: 'list'
                }
            };
            for (let o in OPTIONS) {
                for (let op in OPTIONS[o]) {
                    atts[op] = {type: OPTIONS[o][op].type};
                    if (OPTIONS[o][op].default) {
                        atts[op].default = OPTIONS[o][op].default;
                    }
                }
            }
            return atts;
        })(),

        edit: function(props) {
            var attributes = props.attributes;
            var blockProps = wp.blockEditor.useBlockProps();

            function updateArray(newValue) {
                props.setAttributes({ connections: newValue });
            };

            function addToArray(connection) {
                const newArray = [...props.attributes.connections, connection];
                updateArray(newArray);
            };

            function removeFromArray(index) {
                const newArray = props.attributes.connections.filter((_, i) => i !== index);
                updateArray(newArray);
            };

            function addConnection(i, place) {
                let title = place.name;
                if (place.lang) title += ' (' + place.lang + ')';

                return el(
                    'div',
                    {
                        title: title,
                        initialOpen: false
                    },
                    el('div', {className: 'grw-builder-option'},
                        el(
                            'img',
                            {
                                src: place.photo,
                                alt: place.name,
                                className: 'grw-connect-photo'
                            }
                        ),
                        el(
                            'a',
                            {
                                className: 'grw-connect-photo-change',
                                href: '#',
                            },
                            'Change'
                        ),
                        el(
                            'a',
                            {
                                className: 'grw-connect-photo-default',
                                href: '#',
                            },
                            'Default'
                        ),
                        el(
                            TextControl,
                            {
                                type: 'hidden',
                                name: 'photo',
                                className: 'grw-connect-photo-hidden',
                                value: place.id,
                                tabindex: 2
                            }
                        )
                    ),
                    el('div', {className: 'grw-builder-option'},
                        el(
                            'input',
                            {
                                name: 'name',
                                value: place.name,
                                type: 'text'
                            }
                        ),
                    ),
                    el('div', {className: 'grw-builder-option'},
                        LangControl('Show all connected languages', place.lang)
                    ),
                    el('div', {className: 'grw-builder-option'},
                        el(
                            'button',
                            {
                                className: 'grw-connect-reconnect',
                                onClick: function() {

                                }
                            },
                            'Reconnect'
                        )
                    ),
                    el('div', {className: 'grw-builder-option'},
                        el(
                            'button',
                            {
                                className: 'grw-connect-delete',
                                onClick: function() {
                                    removeFromArray(i);
                                }
                            },
                            'Delete connection'
                        )
                    ),
                )
            };

            var connectGoogle = function(e) {
                let btn = e.target,
                    input = btn.parentNode.querySelector('.grw-connect-id'),
                    select = btn.parentNode.querySelector('.grw-connect-lang');

                if (!input.value) {
                    input.focus();
                    return;
                }

                const data = new URLSearchParams();
                data.append('id', decodeURIComponent(input.value));
                data.append('lang', select.value);
                data.append('grw_wpnonce', grwBlockData.nonce);
                data.append('action', 'grw_connect_google');
                data.append('v', new Date().getTime());

                wp.apiFetch({
                    method: 'POST',
                    url: ajaxurl,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    },
                    body: data.toString()
                })
                .then(response => {
                    console.log('Response from server:', response);
                    let result = response.result;
                    if (result && result.id) {
                        addToArray({
                            id        : result.id,
                            name      : result.name,
                            photo     : result.photo,
                            lang      : select.value,
                            refresh   : true,
                            local_img : false,
                            platform  : 'google'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error during AJAX request:', error);
                });
            };

            var connEls = [];
            for (let i = 0; i < attributes.connections.length; i++) {
                (function(i, connection) {
                    connEls.push(addConnection(i, connection));
                })(i, attributes.connections[i]);
            }

            function BuildOptions() {
                let result = [];
                for (let o in OPTIONS) {
                    let opts = [];
                    for (let op in OPTIONS[o]) {
                        (function(name, opt) {
                            let params = {
                                name     : name,
                                label    : opt.label,
                                onChange : function(val) {
                                    //let name = event.target.name;
                                    let att = {};
                                    att[name] = val
                                    props.setAttributes(att);
                                }
                            };
                            if (opt.type == 'boolean') {
                                params.checked = /*attributes[name] != undefined ?*/ attributes[name] /*: (opt.default || false)*/;
                            } else {
                                params.value = /*attributes[name] != undefined ?*/ attributes[name] /*: (opt.default || '')*/;
                            }
                            opts.push(
                                el(
                                    opt.type == 'boolean' ? CheckboxControl : TextControl,
                                    params
                                )
                            );
                        })(op, OPTIONS[o][op]);

                    }
                    result.push(
                        el(
                            PanelBody,
                            {
                                title: __(o),
                                initialOpen: false
                            },
                            opts
                        )
                    );
                }
                return result;
            }

            return el(
                'div',
                blockProps,
                el(
                    InspectorControls,
                    {
                        key: 'inspector'
                    },
                    el(
                        'div',
                        {
                            id: 'grw-builder-option',
                            className: 'grw-builder-options grw-block-options'
                        },
                        el(
                            PanelBody,
                            {
                                title: __('Layout'),
                                initialOpen: true
                            },
                            el(
                                SelectControl,
                                {
                                    id: 'view_mode',
                                    name: 'view_mode',
                                    value: props.attributes.view_mode,
                                    options: [
                                        {label: 'Slider', value: 'slider'},
                                        {label: 'List',   value: 'list'}
                                    ],
                                    onChange: function(newValue) {
                                        props.setAttributes({ view_mode: newValue });
                                    }
                                }
                            )
                        ),
                        BuildOptions()
                    )
                ),

                el(
                    'div',
                    {
                        id: 'grw-connect-wizard',
                        title: 'Easy steps to connect Google Reviews',
                        style: {
                            'display': 'block',
                            'padding': '10px 20px',
                            'border-radius': '5px',
                            'background': '#fff'
                        }
                    },
                    el(
                        'p',
                        null,
                        el('span', null, '1'),
                        ' Find your Google place on the map below (',
                        el('u', { className: 'grw-wiz-arr' }, 'Enter a location'),
                        ') and copy found ',
                        el('u', null, 'Place ID')
                    ),
                    el('iframe', {
                        src: 'https://geo-devrel-javascript-samples.web.app/samples/places-placeid-finder/app/dist',
                        loading: 'lazy',
                        style: {width: '98%', height: '250px'}
                    }),
                    el(
                        'small',
                        {style: { fontSize: '13px', color: '#555'}},
                        'If you can\'t find your place on this map, please read ',
                        el('a', { href: GRW_VARS.supportUrl + '&grw_tab=fig#place_id', target: '_blank'}, 'this manual how to find any Google Place ID'),
                        '.'
                    ),
                    el(
                        'p',
                        null,
                        el( 'span', null, '2' ),
                        ' Paste copied ',
                        el('u', null, 'Place ID'),
                        'in this field and select language if needed ',
                    ),
                    el(
                        'p',
                        null,
                        el('input', {
                            type: 'text',
                            className: 'grw-connect-id',
                            placeholder: 'Place ID'
                        }),
                        LangControl('Choose language if needed')
                    ),
                    el(
                        'p',
                        null,
                        el('span', null, '3'),
                        ' Click CONNECT GOOGLE button'
                    ),
                    el('button', {className: 'grw-connect-btn', onClick: connectGoogle}, 'Connect Google'),
                    el('small', {className: 'grw-connect-error'})
                ),

                el(
                    'div',
                    {
                        title: __('Connections'),
                        initialOpen: true,
                    },
                    connEls
                ),
            );
        },

        save: function(props) {
            return null;
        }
    });
}(
    jQuery,
    window.wp.blocks,
    window.wp.editor,
    window.wp.element,
    window.wp.components,
    window.wp.api
));