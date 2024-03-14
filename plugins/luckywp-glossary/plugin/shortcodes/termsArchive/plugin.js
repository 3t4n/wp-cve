(function() {
    if (!wp.media || !wp.mce)
        return;

    tinymce.PluginManager.add('lwpgls_shortcode_terms_archive', function(editor) {
        editor.addButton('lwpgls_shortcode_terms_archive', {
            text: 'Glossary',
            icon: false,
            onclick: function() {
                wp.mce.lwpglsTermsArchive.insert(editor);
            },
        });
    });

    /**
     * Вид в редакторе
     */
    wp.mce.lwpglsTermsArchive = {
        template: wp.media.template('editor-lwpgls-termsArchiveShortcode'),
        getContent: function() {
            return this.template();
        },
        insert: function(editor) {
            editor.insertContent('[lwpglsTermsArchive]');
        },
    };
    wp.mce.views.register('lwpglsTermsArchive', wp.mce.lwpglsTermsArchive);
})();