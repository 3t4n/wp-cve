jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.regUserOnly', {
        init : function(ed, url) {
                // Register command for when button is clicked
                ed.addCommand('regUserOnly_insert_shortcode', function() {
                    selected = tinyMCE.activeEditor.selection.getContent();

                        //Wrap shortcode around it.
                        content =  '[RegUserOnly]'+selected+'[/RegUserOnly]';

                    tinymce.execCommand('mceInsertContent', false, content);
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('regUserOnly_button', {title : 'Hide content from guest users', cmd : 'regUserOnly_insert_shortcode', image: url + '/regUserOnly.png' });
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('regUserOnly_button', tinymce.plugins.regUserOnly);
});