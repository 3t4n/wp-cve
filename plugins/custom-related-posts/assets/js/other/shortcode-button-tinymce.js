(function() {
    tinymce.create('tinymce.plugins.custom_related_posts', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            ed.addButton('custom_related_posts', {
                title : 'Custom Related Posts',
                cmd : 'custom_related_posts',
                image : url.slice(0, -9) + '/images/icon_20.png'
            });

            ed.addCommand('custom_related_posts', function() {
                tinyMCE.get(ed.id).focus(true);
                tinyMCE.activeEditor.selection.collapse(false);
                tinyMCE.activeEditor.execCommand('mceInsertContent', false, '[custom-related-posts title="Related Posts" none_text="None found" order_by="title" order="ASC"]');
            });
        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Custom Related Posts',
                author : 'Bootstrapped Ventures',
                authorurl : 'https://bootstrapped.ventures',
                version : "0.1"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'custom_related_posts', tinymce.plugins.custom_related_posts );
})();