(function() {
    tinymce.PluginManager.add('csdm_custom_button_snippet', function( editor, url ) {
        editor.addButton( 'csdm_custom_button_snippet', {
            title: "DM Code Snippet",
            icon: "dm-custom-button-snippet",
            onclick: function() {
                editor.windowManager.open( {
                    title: "DM Code Snippet",
                    classes: 'dm-code-snippet-lightbox',
                    width: 700,
                    height: 680,
                    body: [
                        {
                            type   : 'listbox',
                            name   : 'theme',
                            classes: 'dm-code-snippet-theme',
                            label  : 'Theme',
                            tooltip: 'This changes the code area UI color',
                            values : [
                                { text: 'Dark', value: 'dark' },
                                { text: 'Light', value: 'light' }
                            ],
                            value : 'dark' // Sets the default
                        },
                        {
                            type   : 'listbox',
                            name   : 'slimoption',
                            classes: 'dm-code-snippet-slim',
                            label  : 'Slim Version',
                            tooltip: 'This is recommended for one line code. No BG, no extra style, just the code.',
                            values : [
                                { text: 'Yes', value: 'yes' },
                                { text: 'No', value: 'no' }
                            ],
                            value : 'no' // Sets the default
                        },
                        {
                            type   : 'listbox',
                            name   : 'linenumbers',
                            classes: 'dm-code-snippet-line-numbers',
                            label  : 'Line Numbers',
                            tooltip: 'This will allow you to enable the Line Numbers column.',
                            values : [
                                { text: 'Yes', value: 'yes' },
                                { text: 'No', value: 'no' }
                            ],
                            value : 'no' // Sets the default
                        },
                        {
                            type   : 'listbox',
                            name   : 'backgroundoption',
                            classes: 'dm-code-snippet-background',
                            label  : 'Enable background',
                            tooltip: 'Enable background color from below to show around the code area',
                            values : [
                                { text: 'Yes', value: 'yes' },
                                { text: 'No', value: 'no' }
                            ],
                            value : 'yes' // Sets the default
                        },
                        {
                            type   : 'colorbox',
                            name   : 'bgcolor',
                            classes: 'dm-code-snippet-bg-color',
                            label  : 'Background color',
                            values : [
                                { text: 'Default Grey', value: '#abb8c3' },
                                { text: 'White', value: '#fff' },
                                { text: 'Black', value: '#000' }
                            ],
                            value : '#abb8c3' // Sets the default
                        },
                        {
                            type   : 'listbox',
                            name   : 'backgroundoptionmobile',
                            classes: 'dm-code-snippet-background-mobile',
                            label  : 'Enable background on mobile',
                            tooltip: 'Enable background color from below to show around the code area on mobile devices',
                            values : [
                                { text: 'Yes', value: 'yes' },
                                { text: 'No', value: 'no' }
                            ],
                            value : 'yes' // Sets the default
                        },
                        {
                            type   : 'textbox',
                            multiline: true,
                            classes: 'textbox-code-snippet',
                            name   : 'code',
                            label  : 'Code Snippet',
                            classes: 'dm-code-snippet-code',
                            tooltip: 'Paste in the code you want to display',
                        },
                        {
                            type   : 'listbox',
                            name   : 'language',
                            classes: 'dm-code-snippet-language',
                            label  : 'Language',
                            tooltip: 'Select the language of your code',
                            values : [
                                { text: 'C-Like', value: 'clike' },
                                { text: 'CSS', value: 'css' },
                                { text: 'HTML/Markup', value: 'markup' },
                                { text: 'JavaScript', value: 'javascript' },
                                { text: 'Perl', value: 'perl' },
                                { text: 'PHP', value: 'php' },
                                { text: 'Python', value: 'python' },
                                { text: 'Ruby', value: 'ruby' },
                                { text: 'SQL', value: 'sql' },
                                { text: 'TypeScript', value: 'typescript' },
                                { text: 'Bash/Shell', value: 'shell' }

                            ],
                            value : 'php' // Sets the default
                        },
                        {
                            type   : 'listbox',
                            name   : 'wrap',
                            classes: 'dm-code-snippet-wrap',
                            label  : 'Wrap Code',
                            tooltip: 'Enable/Disable code wrapping',
                            values : [
                                { text: 'Yes', value: 'yes' },
                                { text: 'No', value: 'no' }
                            ],
                            value : 'no' // Sets the default
                        },
                        {
                            type   : 'textbox',
                            name   : 'maxheight',
                            classes: 'dm-code-snippet-maxheight',
                            label  : 'Max Height',
                            tooltip: 'Set the max height for the code snippet. Supports any unit.',
                            placeholder : '300px - Leave empty if you do not need it.' // Sets the default
                        },
                        {
                            type   : 'textbox',
                            name   : 'copycode',
                            classes: 'dm-code-snippet-wrap',
                            label  : 'Copy Text',
                            tooltip: 'Add text on the Copy Button',
                            value : 'Copy Code' // Sets the default
                        },
                        {
                            type   : 'textbox',
                            name   : 'copiedcode',
                            classes: 'dm-code-snippet-wrap',
                            label  : 'After Copy Text',
                            tooltip: 'Text displayed after clicking the Copy Button',
                            value : 'Copied' // Sets the default
                        }
                    ],
                    onsubmit: function( e ) {

                        var entityMap = {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;',
                            '/': '&#x2F;',
                            '`': '&#x60;',
                            '=': '&#x3D;'
                            };
                            
                            function escapeHtml (string) {
                            return String(string).replace(/[&<>"'`=\/]/g, function (s) {
                                return entityMap[s];
                            });
                            }

                            e.data.code = escapeHtml(e.data.code);


                        editor.insertContent( '[dm_code_snippet background="' + e.data.backgroundoption + '" background-mobile="' + e.data.backgroundoptionmobile + '" slim="' + e.data.slimoption + '" line-numbers="' + e.data.linenumbers + '" bg-color="' + e.data.bgcolor + '" theme="' + e.data.theme + '" language="' + e.data.language + '" wrapped="' + e.data.wrap + '" height="' + e.data.maxheight + '" copy-text="' + e.data.copycode + '" copy-confirmed="' + e.data.copiedcode + '"]<pre class="dm-pre-admin-side">' + e.data.code + '</pre>[/dm_code_snippet]');
                    }
                });
            },
        });
    });

})();
