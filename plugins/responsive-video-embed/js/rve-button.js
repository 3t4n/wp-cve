(function () {
    tinymce.PluginManager.add('rve_button', function (editor, url) {
        editor.addButton('rve_button', {
            text: editor.getLang('rve_tinymce_plugin.buttonText'),
            icon: false,
            onclick: function () {
                editor.windowManager.open({
                    title: editor.getLang('rve_tinymce_plugin.windowTitle'),
                    body: [{
                        type: 'textbox',
                        name: 'src',
                        label: editor.getLang('rve_tinymce_plugin.embedUrl')
                    },
                        {
                            type: 'listbox',
                            name: 'ratio',
                            label: editor.getLang('rve_tinymce_plugin.aspectRatio'),
                            'values': [
                                {text: '16:9', value: '16by9'},
                                {text: '4:3', value: '4by3'},
                                {text: '21:9', value: '21by9'},
                                {text: '1:1', value: '1by1'}
                            ]
                        }],
                    onsubmit: function (e) {
                        editor.insertContent('[rve src="' + e.data.src + '" ratio="' + e.data.ratio + '"]');
                    }
                });
            }
        });
    });
})();
