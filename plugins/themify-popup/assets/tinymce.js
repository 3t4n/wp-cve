(function($) {
	'use strict';

	if( typeof mceThemifyPopup !== 'object' )
		return;

	$( '<script type="text/html" id="tmpl-themify-popup-shortcode">' + mceThemifyPopup.fields.template + '</script>' ).appendTo( 'body' );

	tinymce.PluginManager.add( 'mceThemifyPopup', function( editor, url ) {

		function createColorPickAction() {
			const colorPickerCallback = editor.settings.color_picker_callback;

			if ( colorPickerCallback ) {
				return function() {
					const self = this;

					colorPickerCallback.call(
						editor,
						function( value ) {
							self.value( value ).fire( 'change' );
						},
						self.value()
					);
				};
			}
		}

		editor.addButton( 'mceThemifyPopup', {
			title: mceThemifyPopup.labels.menuName,
			image: url + '/icon.svg',
			onclick: function(){
				var fields = [];
				$.each( mceThemifyPopup.fields.fields, function( i, field ){
					if( field.type === 'colorbox' ) {
						field.onaction = createColorPickAction();
					}
					fields.push( field );
				} );

				editor.windowManager.open({
					'title' : mceThemifyPopup.labels.menuName,
					'body' : fields,
					onSubmit : function( e ){
                                                const template = wp.template( 'themify-popup-shortcode' ),
                                                values = this.toJSON(); // get form field values
						values.selectedContent = editor.selection.getContent();
						editor.insertContent( template( values ) );
					}
				});
			}
		});
	});
})(jQuery);