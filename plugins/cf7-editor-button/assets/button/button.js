;(function(options, undefined) {
    if (window['tinymce'] === undefined)
        return ;

    var FORM_TITLE_LIMIT = 40;

    tinymce.PluginManager.add('ari_cf7_button', function(editor, url) {
        function _(key) {
            return editor.getLang('ari_cf7_button.' + key);
        };

        function insertContent(content) {
            editor.focus();
            if (editor.selection)
                editor.selection.setContent(content);
            else
                editor.insertContent(content);
        };

        function prepareFormListItems(inputList, itemCallback, startItems) {
            function appendItems(values, output) {
                output = output || [];

                tinymce.each(values, function(item) {
                    var menuItem = {text: item.text || item.title, value: ''};
                    itemCallback(menuItem, item);

                    if (menuItem.text && menuItem.text.length > FORM_TITLE_LIMIT)
                        menuItem.text = menuItem.text.substr(0, FORM_TITLE_LIMIT) + '...';

                    output.push(menuItem);
                });

                return output;
            }

            return appendItems(inputList, startItems || []);
        };

        function ajax(ajaxUrl, data, callback) {
            data = data || '';

            editor.setProgressState(1);

            return tinymce.util.XHR.send({
                url: ajaxUrl,

                content_type: 'application/x-www-form-urlencoded',

                data: data,

                success: function(res) {
                    editor.setProgressState(0);

                    if (callback)
                        callback(!!res ? tinymce.util.JSON.parse(res) : res);
                }
            });
        };

        function showDialog(forms) {
            var formListControl,
                insertShortCode = function(win) {
                    var selected = formListControl.value();

                    if (!selected || !options.forms)
                        return ;

                    var form = null;
                    for (var i = 0; i < options.forms.length; i++) {
                        if (options.forms[i].id == selected) {
                            form = options.forms[i];
                            break;
                        }
                    };

                    if (!form)
                        return ;

                    var cleanTitle = form.title.replace(/["\[\]]/g, '');

                    insertContent('[contact-form-7 id="' + form.id + '" title="' + cleanTitle + '"]');
                    win.close();
                },
                win = editor.windowManager.open({
                    title: _('dialog_title'),

                    resizable : true,

                    maximizable : true,

                    width: 480,

                    height: 90,

                    body: [
                        {
                            type: 'listbox',

                            name: 'form_list',

                            tooltip: _('select_item'),

                            values: prepareFormListItems(forms, function(item, data) {
                                item.value = data.id;
                                item.text = data.title;
                            }, [{text: _('default_form_item'), value: ''}]),

                            onSelect: function() {
                                insertShortCode(win);
                            },

                            onPostRender: function() {
                                formListControl = this;
                            }
                        },
                        {
                            type: 'container',

                            html: '<p class="howto">' + _('howto') + '</p>'
                        }
                    ],
                    buttons: [
                        {
                            text: _('cancel'),

                            onclick: 'close'
                        }
                    ]
                });
        };

        editor.addButton('ari_cf7_button', {
            image: url + '/img/icon.svg' + (options.version ? '?v=' + options.version : ''),

            tooltip: _('button_tooltip'),

            onclick: function() {
                if (options.forms !== undefined) {
                    showDialog(options.forms);
                } else {
                    ajax(
                        options.ajax_url,

                        'ctrl=data_get-forms',

                        function(data) {
                            options.forms = data.result && data.result.forms ? data.result.forms : null;

                            showDialog(options.forms);
                        }
                    );
                }
            }
        });
    });
})(ARI_CF7_BUTTON_SETTINGS || {});