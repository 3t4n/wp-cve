(function() {
    tinymce.PluginManager.add('kirimemail_wpform_button', function(editor, url) {
        editor.addButton('kirimemail_wpform_button', {
            title: 'Insert KIRIM.EMAIL Form',
            image: url + '/icon.png',
            cmd: 'kirimemail_wpform_button',
        });

        editor.addCommand('kirimemail_wpform_button', function() {
            var width = jQuery(window).width(),
                H = jQuery(window).height(),
                W = (720 < width) ? 720 : width;
            W = W - 80;
            H = H - 115;
            tb_show('Form List', url + '/tinymce-kirimemail.php?&width=' + W + '&height=' + H);
        });
    });
})();
