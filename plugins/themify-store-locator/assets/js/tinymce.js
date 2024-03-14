/**
 * Routines to add a menu button in WP 3.9 Editor
 */
tinymce.PluginManager.add('mceThemifyStoreLocator', function( editor, url ) {
	'use strict';

	/**
	 * Create and return a TinyMCE menu item
	 */
	function add_item( shortcode ) {
		var item = {
			'text' : shortcode.label,
			'body' : {
				type: shortcode.id
			},
			onclick : function(){
				var fields = shortcode.fields;
				editor.windowManager.open({
					'title' : shortcode.label,
					'body' : fields,
					onSubmit : function( e ){
						var values = this.toJSON(); // get form field values
						values.selectedContent = editor.selection.getContent();
						var template = wp.template( 'tsl-' + shortcode.id +'-shortcode');
						editor.insertContent( template( values ) );
					}
				});
			}
		};

		return item;
	}

	var items = [];
	jQuery.each( mceThemifyStoreLocator.shortcodes, function( key, shortcode ){
		shortcode.id = key;
		jQuery( '<script type="text/html" id="tmpl-tsl-'+ key +'-shortcode">' + shortcode.template + '</script>' ).appendTo( 'body' );
		items.push( add_item( shortcode ) );
	} );
	
	editor.addButton( 'mceThemifyStoreLocator', {
		type: 'menubutton',
		text: '',
		image: mceThemifyStoreLocator.url+'assets/img/tsl-editor-icon.png',
		tooltip: mceThemifyStoreLocator.editor.menuTooltip,
		menu: items
	} );

});