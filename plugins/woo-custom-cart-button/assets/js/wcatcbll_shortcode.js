(function () {
    tinymce.create('tinymce.plugins.Wccbinsertshortcode', {
        init: function (editor, url) {

            //Add button and functionality

            editor.addButton('wccb_shrtcd', {
                text: 'WCCB',
                selector: "textarea",  // add shortcode in the textarea
                title: 'Insert Custom Cart Button shortcode',
                icon: false,
                classes: 'wcatcbll_shrtcd_btn',

                onclick: function () {
                    editor.selection.setContent('[catcbll pid="Please change it to your product ID" background="#fff" font_size="12" font_color="#000" font_awesome="fas fa-adjust" border_color="red" border_size="2" icon_position="right" image="false"]');
                }
            });


        }
    });
    tinymce.PluginManager.add('Wccbinsertshortcode', tinymce.plugins.Wccbinsertshortcode);
})();
