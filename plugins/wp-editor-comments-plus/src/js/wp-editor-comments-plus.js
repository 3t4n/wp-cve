/**
 * This is the main javascript file for the WP Editor Comments Plus plugin's main administration view.
 *
 * @package   wp-editor-comments-plus
 * @author    Neo Snc <neosnc1@gmail.com>
 * @license   GPL-2.0+
 * @link      https://wordpress.org/plugins/wp-editor-comments-plus/
 * @copyright 1-4-2015 Neo Snc
 */

'use strict';

import React from 'react';
import ReactDOM from 'react-dom';

var
	wpecp = window.wpecp || {},
	wpecpGlobals = window.wpecpGlobals || {},
	$ = jQuery
;

window.wpecp = wpecp;

wpecp.initWPECP = function() {
	if ( wpecpGlobals.length ) {
		window.wpecp.globals = JSON.parse( wpecpGlobals );
	}

	wpecp.RespondView = Backbone.View.extend({
		events: function() {
			var _events = {};

			_events[ 'submit' ] = 'submitForm';

			return _events;
		},

		initialize: function() {
			this.$commentForm = $( wpecp.globals.wpecp_id_comment_form );
			this.$textArea = $( wpecp.globals.wpecp_id_comment_textarea );
			this.$submitButton = $( wpecp.globals.wpecp_id_submit_comment );
			this.$submitButton.addClass( wpecp.globals.wpecp_css_button + ' ' + wpecp.globals.wpecp_css_submit_button );
			this.disableSubmit = false;
		},

		submitForm: function( event ) {
			event.preventDefault();

			var self = this,
				content = tinyMCE.activeEditor.getContent(),
				submitText = this.$submitButton.val();

			this.$textArea.html( content );
			this.$submitButton.val( 'Posting...' );
			this.$submitButton.attr( 'disabled', true );

			if ( ! this.disableSubmit ) {
				this.disableSubmit = true;

				$.ajax({
					url: this.$commentForm.attr( 'action' ),
					type: 'post',
					data: this.$commentForm.serialize()
				})
				.done( function( data ){
					self.$el.find( wpecp.globals.wpecp_id_cancel_comment_reply ).click();
					self.$submitButton.attr( 'disabled', false );
					self.$submitButton.val( submitText );
					tinymce.activeEditor.setContent( '' );

					var
						$commentData = $( data ).find( wpecp.globals.wpecp_id_comments ),
						$commentsList = $( wpecp.globals.wpecp_id_comments )
					;

					if ( $commentData.length ) {
						// remove tinymce editor before comments data is updated
						tinymce.EditorManager.execCommand( 'mceRemoveEditor', true, 'comment' );

						// replace #comments element with data response #comments element
						$commentsList.replaceWith( $commentData );

						// rebind React components
						wpecp.bindEditors();

						// restore the tinymce editor in the #respond element
						tinymce.EditorManager.execCommand( 'mceAddEditor', true, 'comment' );
					}
				})
				.fail( function( data ){
					// error
				})
				.then( function( data ){
					self.disableSubmit = false;
				});
			}
		}
	});

	wpecp.bindEditors = function() {
		// include edit component
		wpecp.Edit = require( '../components/edit/edit' );

		// bind edit components
		$( '.' + wpecp.globals.wpecp_css_edit ).each(function(){
			let commentId = $( this ).data( wpecp.globals.wpecp_css_comment_id ),
					editId = wpecp.globals.wpecp_css_edit + commentId,
					editorId = wpecp.globals.wpecp_css_editor + commentId,
					commentReplyLink = $( this ).siblings( '.' + wpecp.globals.wpecp_css_comment_reply_button )[ 0 ];

			// wpecp.bindReply( commentReplyLink, true );
			ReactDOM.render(<wpecp.Edit wpecpGlobals={ wpecp.globals } commentId={ commentId } editId={ editId } editorId={ editorId } />, this );
		});

		// include editor component
		wpecp.Editor = require( '../components/editor/editor' );

		// bind editor components
		$( '.' + wpecp.globals.wpecp_css_editor ).each(function(){
			let commentId = $( this ).data( wpecp.globals.wpecp_css_comment_id ),
					editId = wpecp.globals.wpecp_css_edit + commentId,
					editorId = wpecp.globals.wpecp_css_editor + commentId,
					contentId = wpecp.globals.wpecp_css_comment_content + commentId;
			ReactDOM.render(<wpecp.Editor wpecpGlobals={ wpecp.globals } commentId={ commentId } editId={ editId } editorId={ editorId } contentId={ contentId } />, this );
		});

		// bind respond element ( reply form )
		new wpecp.RespondView({
			el: $( wpecp.globals.wpecp_id_respond )
		});

	};

};

( function( $ ){
   $(function(){
    wpecp.initWPECP();
		wpecp.bindEditors();
   });
})( jQuery );
