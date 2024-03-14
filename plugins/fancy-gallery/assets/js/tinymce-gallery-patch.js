'use strict';

/* global tinymce */
tinymce.PluginManager.add('wpgallerypatch', function (editor) {
    let $ = jQuery;

    function hideFirstToolbarButton($toolbar) {
        $toolbar.find('div.mce-widget.mce-btn.mce-first').hide();
    }

    function showAllToolbarButtons($toolbar) {
        $toolbar.find('div.mce-widget.mce-btn.mce-first').show();
    }

    editor.on('wptoolbar', function (event) {
        let
            view = event.element,
            $view = $(view),
            type = $view.data('wpview-type'),
            shortcode = decodeURIComponent($view.data('wpview-text')),
            shortcode_has_id_attribute = shortcode.indexOf(' id=') > 0,
            $toolbar = $('div.mce-inline-toolbar-grp:visible').first();

        if (view && $toolbar.length && type == 'gallery') {
            if (shortcode_has_id_attribute)
                hideFirstToolbarButton($toolbar);
            else
                showAllToolbarButtons($toolbar);
        }

    });

});
