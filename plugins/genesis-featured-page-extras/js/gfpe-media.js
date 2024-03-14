/**
 * Image uploader script.
 * 
 * NOTE I:
 *    Fork of "Tribe Image Widget Javascript"
 * @author  Modern Tribe, Inc.
 * @link    http://wordpress.org/plugins/image-widget/
 * @license GPL-2.0+
 *
 * NOTE II:
 *    All other parts of this script customized/ developed by David Decker of DECKERWEB.
 * @author  David Decker - DECKERWEB
 * @link    http://deckerweb.de/
 * @license GPL-2.0+
 *
 * @since   1.0.0
 */
jQuery(document).ready(function($){

	gfpe_uploader = {

		/** Call this from the upload button to initiate the upload frame. */
		uploader : function( widget_id, widget_id_string ) {

			var frame = wp.media({
				title: gfpe_media.frame_title,
				multiple: false,
				library: {
					type: 'image'
				},
				button: {
					text: gfpe_media.button_title
				}
			});

			/** Handle results from media manager. */
			frame.on('close',function( ) {
				var attachments = frame.state().get('selection').toJSON();
				gfpe_uploader.render( widget_id, widget_id_string, attachments[0] );
			});

			frame.open();
			return false;
		},

		/** Output Image URL. */
		render : function( widget_id, widget_id_string, attachment ) {

			$("#" + widget_id_string + 'image_url').val(attachment.url);

		},

		/** Update input fields if it is empty */
		updateInputIfEmpty : function( widget_id_string, name, value ) {
			var field = $("#" + widget_id_string + name);
			if ( field.val() == '' ) {
				field.val(value);
			}
		},

	};

});