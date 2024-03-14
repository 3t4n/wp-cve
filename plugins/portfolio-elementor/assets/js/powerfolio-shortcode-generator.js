(function() {
    tinymce.PluginManager.add('powerfolio_button', function(editor, url) {
        var hoverOptions = powerfolio_settings.hover_options;
        var columnOptions = powerfolio_settings.column_options;
        var styleOptions = powerfolio_settings.style_options;
        var linktoOptions = powerfolio_settings.linkto_options;
        var upgradeMessage = powerfolio_settings.upgrade_message;

        editor.addButton('powerfolio_button', {
            text: ' Powerfolio',
            icon: 'plus',
            onclick: function() {
                editor.windowManager.open({
                    title: 'Powerfolio Shortcode Generator',
                    body: [
                        {
                            type: 'container',
                            html: upgradeMessage,
                            //minWidth: 300, // Set a minimum width for the container (optional)
                        },
                        {type: 'listbox', name: 'hover', label: 'Hover Effect', values: hoverOptions},
                        {type: 'textbox', name: 'postsperpage', label: 'Posts per Page', value: '10'},
                        {type: 'checkbox', name: 'showfilter', label: 'Show Filter', checked: true},
                        {type: 'checkbox', name: 'showallbtn', label: 'Show "All" Button', checked: true},
                        //{type: 'textbox', name: 'tax_text', label: 'Taxonomy Text'},
                        {type: 'listbox', name: 'style', label: 'Style', values: styleOptions},
                        {type: 'checkbox', name: 'margin', label: 'Margin', checked: true},
                        {type: 'listbox', name: 'columns', label: 'Columns', values: columnOptions},
                        {type: 'listbox', name: 'linkto', label: 'Link To', values: linktoOptions},                       
                    ],
                    buttons: [
                        {
                            text: 'Add Shortcode',
                            subtype: 'primary',
                            onclick: 'submit',
                        },
                        {
                            text: 'Cancel',
                            onclick: 'close',
                        },
                    ],
                    onsubmit: function(e) {
                        // Construct the shortcode with the user-selected options
                        var shortcode = '[powerfolio';

                        // Append the options to the shortcode
                        shortcode += ' hover="' + e.data.hover + '"';
                        shortcode += ' postsperpage="' + e.data.postsperpage + '"';
                        shortcode += ' showfilter="' + (e.data.showfilter ? 'true' : 'false') + '"';
                        shortcode += ' showallbtn="' + (e.data.showallbtn ? 'true' : 'false') + '"';
                        //shortcode += ' tax_text="' + e.data.tax_text + '"';
                        shortcode += ' style="' + e.data.style + '"';
                        shortcode += ' margin="' + e.data.margin + '"';
                        shortcode += ' columns="' + e.data.columns + '"';
                        shortcode += ' linkto="' + e.data.linkto + '"';

                        shortcode += ']';

                        // Insert the shortcode into the post body
                        editor.insertContent(shortcode);
                    }
                });
            }
        });
    });
})();