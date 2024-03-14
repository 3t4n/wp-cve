(function () {
    "use strict";
    tinymce.PluginManager.add('stul_shortcode_generator', function (editor, url) {

        // Add Button to Visual Editor Toolbar
        editor.addButton('stul_shortcode_generator', {
            title: $('#stul-tinymce-icon-title').val(),
            cmd: 'stul_shortcode_generator',
            image: $('#stul-tinymce-icon').val()
        });

        editor.addCommand('stul_shortcode_generator', function () {
            // Check we have selected some text that we want to link
            var text = editor.selection.getContent({
                'format': 'html'
            });
            if (text.length === 0) {
                alert($('#stul-tinymce-error-message').val());
                return;
            }
            $('.stul-tinymce-popup').fadeIn(500);
            $('body').on('click', '.stul-shortcode-inserter', function () {
                var form_alias = $('#stul-form-lists').val();
                if (form_alias != '') {
                    editor.execCommand('mceReplaceContent', false, '[stu alias="' + form_alias + '"]'+text+'[/stu]');
                    $('.stul-tinymce-popup').fadeOut(500);
                    editor.selection.clear();
                }
            });
        });
    });
    $('body').on('click','.stul-tinymce-popup-close',function(){
        $('.stul-tinymce-popup').fadeOut(500);
    });

})($);