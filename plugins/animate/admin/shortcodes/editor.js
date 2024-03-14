(function() {
	// TinyMCE plugin start.
	tinymce.PluginManager.add( 'ANIMATETinyMCE', function( editor, url ) {
		// Register a command to open the dialog.
		editor.addCommand( 'animate_open_dialog', function( ui, v ) {

			animateSelectedShortcodeType = v;
                        selectedText = editor.selection.getContent({format: 'text'});
                        animate_tb_dialog_helper.loadShortcodeDetails();
                        animate_tb_dialog_helper.setupShortcodeType( v );

			jQuery( '#animatedialog-shortcode-options' ).addClass( 'shortcode-' + v );
			jQuery( '#animatedialog-selected-shortcode' ).val( v );

			var f=jQuery(window).width();
			b=jQuery(window).height();
			f=720<f?720:f;
			f+=32;
			b-=120;

			tb_show( "Insert ["+ v +"] shortcode", "#TB_inline?width="+f+"&height="+b+"&inlineId=animatedialog" );
		});

		/* Register a command to insert the self-closing shortcode immediately. */
                editor.addCommand( 'animate_insert_self_immediate', function( ui, v ) {
                        editor.insertContent( '[' + v + ']' );
                });

                /* Register a command to insert the enclosing shortcode immediately. */
                editor.addCommand( 'animate_insert_immediate', function( ui, v ) {
                        var selected = editor.selection.getContent({format: 'text'});

                        editor.insertContent( '[' + v + ']' + selected + '[/' + v + ']' );
                });

                /* Register a command to insert the N-enclosing shortcode immediately. */
                editor.addCommand( 'animate_insert_immediate_n', function( ui, v ) {
                        var arr = v.split('|'),
                                selected = editor.selection.getContent({format: 'text'}),
                                sortcode;

                        for (var i = 0, len = arr.length; i < len; i++) {
                                if (0 === i) {
                                        sortcode = '[' + arr[i] + ']' + selected + '[/' + arr[i] + ']';
                                } else {
                                        sortcode += '[' + arr[i] + '][/' + arr[i] + ']';
                                };
                        };
                        editor.insertContent( sortcode );
                });

		// Add a button that opens a window
		editor.addButton( 'animate_button', {
			icon: 'icon animate-icon',
			tooltip: 'Insert a Animate Shortcode',
			onclick: function() { editor.execCommand( 'animate_open_dialog', false, 'animate', { title: 'Animate' } ); }
		});
	}); // TinyMCE plugin end.
})();
