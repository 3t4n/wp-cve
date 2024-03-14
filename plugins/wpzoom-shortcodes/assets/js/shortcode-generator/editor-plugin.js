( function() {
    // TinyMCE plugin start.
    tinymce.PluginManager.add( 'wpzoomShortcodes', function( editor, url ) {
        // Register a command to open the dialog.
        editor.addCommand( 'wpzOpenDialog', function( ui, v ) {
            wpzSelectedShortcodeType = v;
            selectedText = editor.selection.getContent({format: 'text'});

            jQuery.get(ajaxurl + '?action=zoom_shortcodes_ajax_dialog', function(html) {
                jQuery( '#wpz-options' ).addClass( 'shortcode-' + v );

                var width = Math.min(jQuery(window).width(), 720) - 80;
                var height = jQuery(window).height() - 84;

                jQuery("#wpz-dialog").remove();
                jQuery("body").append(html);
                jQuery("#wpz-dialog").hide();

                tb_show( "Insert ["+ v +"] Shortcode", "#TB_inline?width="+width+"&height="+height+"&inlineId=wpz-dialog" );
                jQuery( "#wpz-options h3:first").text( "Customize the ["+v+"] Shortcode" );
            });
        });

        // Register a command to insert the shortcode immediately.
        editor.addCommand( 'wpzInsertImmediate', function( ui, v ) {
            var selected = editor.selection.getContent({format: 'text'});

            // If we have selected text, close the shortcode.
            if ( '' != selected ) {
                selected += '[/' + v + ']';
            }

            editor.insertContent( '[' + v + ']' + selected );
        });

        // Add a button that opens a window
        editor.addButton( 'wpzoom_shortcodes_button', {
            type: 'menubutton',
            icon: 'wpz-shortcode-icon',
            classes: 'btn wpz-shortcode-button',
            tooltip: 'Insert Shortcode',
            menu: [
                {text: 'Button', onclick: function() { editor.execCommand( 'wpzOpenDialog', false, 'button', { title: 'Button' } ); } },
                {text: 'Icon Link', onclick: function() { editor.execCommand( 'wpzOpenDialog', false, 'ilink', { title: 'Icon Link' } ); } },
                {text: 'Info Box', onclick: function() { editor.execCommand( 'wpzOpenDialog', false, 'box', { title: 'Info Box' } ); } },

                {text: 'Column Layout', onclick: function() { editor.execCommand( 'wpzOpenDialog', false, 'column', { title: 'Column Layout' } ); } },
                {text: 'Tabbed Layout', onclick: function() { editor.execCommand( 'wpzOpenDialog', false, 'tab', { title: 'Tabbed Layout' } ); } },

                {text: 'List Generator', menu: [
                    {text: 'Unordered List', onclick: function() { editor.execCommand( 'wpzOpenDialog', false, 'unordered_list', { title: 'Unordered List' } ); } },
                    {text: 'Ordered List', onclick: function() { editor.execCommand( 'wpzOpenDialog', false, 'ordered_list', { title: 'Ordered List' } ); } }
                ]},

            ]
        });
    } );
} )();
