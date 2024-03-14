(function() {
    tinymce.create('tinymce.plugins.wpptinymce', {
        init : function(ed, url) {
            ed.addButton('wpp', {
                title: ed.getLang('wpptinymce.wp_portfolio'),
                image: url + '/../imgs/wpp-tinymce-button-icon.png',
                onclick: function () {
                    var win = ed.windowManager.open({
                        title: ed.getLang('wpptinymce.wp_portfolio'),
                        minWidth: 660,
                        body: [
                            {
                                type: 'listbox',
                                name: 'shortcode_type',
                                label: ed.getLang('wpptinymce.what_would_you_like'),
                                size: 50,
                                values: [
                                    {text: ed.getLang('wpptinymce.portfolio'), value: 'portfolio'},
                                    {text: ed.getLang('wpptinymce.single_websites'), value: 'single'},
                                    {text: ed.getLang('wpptinymce.group_list'), value: 'grouplist'}
                                ],
                                value: 'portfolio', // Sets the default
                                onselect: function () {
                                    var selected_val = this.value();
                                    win.find('#portfolio, #single').hide();
                                    win.find('#' + selected_val).show();
                                }
                            },
                            {
                                type: 'FieldSet',
                                name: 'portfolio',
                                hidden: false,
                                items: [
                                    {
                                        type: 'textbox',
                                        name: 'groups',
                                        label: ed.getLang('wpptinymce.groups_ids'),
                                        tooltip: ed.getLang('wpptinymce.groups_ids_separated_by'),
                                        size: 50,
                                        value: ''
                                    },
                                    {
                                        type: 'listbox',
                                        name: 'hidegroupinfo',
                                        label: ed.getLang('wpptinymce.hide_group_info'),
                                        size: 50,
                                        values: [
                                            {text: ed.getLang('wpptinymce.no'), value: '0'},
                                            {text: ed.getLang('wpptinymce.yes'), value: '1'}
                                        ],
                                        value: '0' // Sets the default
                                    },
                                    {
                                        type: 'listbox',
                                        name: 'ordertype',
                                        label: ed.getLang('wpptinymce.order_by'),
                                        size: 50,
                                        values: [
                                            {text: ed.getLang('wpptinymce.site_order'), value: 'normal'},
                                            {text: ed.getLang('wpptinymce.site_name'), value: 'name'},
                                            {text: ed.getLang('wpptinymce.site_description'), value: 'description'},
                                            {text: ed.getLang('wpptinymce.date_added'), value: 'dateadded'},
                                            {text: ed.getLang('wpptinymce.random'), value: 'random'}
                                        ],
                                        value: 'normal' // Sets the default
                                    },
                                    {
                                        type: 'listbox',
                                        name: 'orderby',
                                        label: ed.getLang('wpptinymce.order'),
                                        size: 50,
                                        values: [
                                            {text: ed.getLang('wpptinymce.asc'), value: 'asc'},
                                            {text: ed.getLang('wpptinymce.desc'), value: 'desc'}
                                        ],
                                        value: 'asc' // Sets the default
                                    },
                                    {
                                        type: 'listbox',
                                        name: 'columns',
                                        label: ed.getLang('wpptinymce.columns'),
                                        size: 50,
                                        values: [
                                            {text: '1 - ' + ed.getLang('wpptinymce.default_behaviour'), value: ''},
                                            {text: '2', value: '2'},
                                            {text: '3', value: '3'},
                                            {text: '4', value: '4'},
                                            {text: ed.getLang('wpptinymce.fill_space'), value: 'fill'}
                                        ],
                                        value: '' // Sets the default
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'sitesperpage',
                                        label: ed.getLang('wpptinymce.sites_per_page'),
                                        tooltip: ed.getLang('wpptinymce.number_of_sites_on_the_page'),
                                        size: 50,
                                        value: ''
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'defaultfiltergroup',
                                        label: ed.getLang('wpptinymce.default_filter'),
                                        tooltip: ed.getLang('wpptinymce.default_filter_group_id'),
                                        size: 50,
                                        value: ''
                                    }
                                ]
                            },
                            {
                                type: 'FieldSet',
                                name: 'single',
                                hidden: true,
                                items: [
                                    {
                                        type: 'textbox',
                                        name: 'sites',
                                        label: ed.getLang('wpptinymce.websites_ids'),
                                        tooltip: ed.getLang('wpptinymce.websites_ids_separated_by'),
                                        size: 50,
                                        value: ''
                                    }
                                ]
                            }
                        ],
                        onsubmit: function (e) {
                            var shortcode = 'wp-portfolio';

                            // Values from user
                            var shortcode_type = e.data.shortcode_type;
                            var groups = e.data.groups;
                            var hidegroupinfo = e.data.hidegroupinfo;
                            var ordertype = e.data.ordertype;
                            var orderby = e.data.orderby;
                            var columns = e.data.columns;
                            var sitesperpage = e.data.sitesperpage;
                            var sites = e.data.sites;
                            var defaultfiltergroup = e.data.defaultfiltergroup;

                            // Build shortcode tag
                            var shortcode_tag = '';
                            switch (shortcode_type) {
                                case 'portfolio':
                                    shortcode_tag += '[' + shortcode;
                                    if (groups != null && groups != '') {
                                        shortcode_tag += ' groups="' + groups + '"';
                                    }
                                    if (hidegroupinfo != null && hidegroupinfo == '1') {
                                        shortcode_tag += ' hidegroupinfo="' + hidegroupinfo + '"';
                                    }
                                    if (ordertype != null && ordertype != 'normal') {
                                        shortcode_tag += ' ordertype="' + ordertype + '"';
                                    }
                                    if (orderby != null && orderby == 'desc') {
                                        shortcode_tag += ' orderby="' + orderby + '"';
                                    }
                                    if (columns != null && columns != '') {
                                        shortcode_tag += ' columns="' + columns + '"';
                                    }
                                    if (sitesperpage != null && sitesperpage != '') {
                                        shortcode_tag += ' sitesperpage="' + sitesperpage + '"';
                                    }
                                    if (defaultfiltergroup != null && defaultfiltergroup != '') {
                                        shortcode_tag += ' defaultfilter="' + defaultfiltergroup + '"';
                                    }
                                    shortcode_tag += ']';
                                    break;
                                case 'single':
                                    shortcode_tag += '[' + shortcode + ' single="' + sites + '"]';
                                    break;
                                case 'grouplist':
                                    shortcode_tag += '[' + shortcode + ' grouplist="1"]';
                                    break;
                            }

                            if (ed.selection.length) {
                                ed.selection.setContent(shortcode_tag);
                            } else {
                                ed.insertContent(shortcode_tag);
                            }
                        }
                    });
                }
            });
        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname    : 'WP Portfolio',
                author      : '',
                authorurl   : '',
                infourl     : '',
                version     : ''
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'wpptinymce', tinymce.plugins.wpptinymce );
})();