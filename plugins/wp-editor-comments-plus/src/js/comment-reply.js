window.addComment = {
	moveForm: function( commId, parentId, respondId, postId ) {
		var div, element, style, cssHidden,
			t           = this,
			comm        = t.I( commId ),
			respond     = t.I( respondId ),
			cancel      = t.I( 'cancel-comment-reply-link' ),
			parent      = t.I( 'comment_parent' ),
			post        = t.I( 'comment_post_ID' ),
			commentForm = respond.getElementsByTagName( 'form' )[0],
			commentTextarea = commentForm.getElementsByTagName( 'textarea' )[0];

		if ( ! comm || ! respond || ! cancel || ! parent || ! commentForm ) {
			return;
		}

		t.respondId = respondId;
		postId = postId || false;

		if ( ! t.I( 'wp-temp-form-div' ) ) {
			div = document.createElement( 'div' );
			div.id = 'wp-temp-form-div';
			div.style.display = 'none';
			respond.parentNode.insertBefore( div, respond );
		}

		// Store tinymce's configuration settings to rebuild the tinymce instance.
		wpecp.tinymceSettings = tinymce.settings;
		// If toolbar is not defined then disable the toolbar. Else build the toolbar array from the configuration settings.
		wpecp.toolbars = ( ! wpecp.globals.wpecp_show_toolbars ) ? false : [
			wpecp.globals.wpecp_toolbar1,
			wpecp.globals.wpecp_toolbar2,
			wpecp.globals.wpecp_toolbar3,
			wpecp.globals.wpecp_toolbar4
		];

		// Initialize new instance of tinyMCE with configuration settings of previous tinyMCE instance
		wpecp.initEditor = function() {
			tinymce.init({
					menubar: false,
					height: '100%',
					selector: '#' + commentTextarea.id,
					content_css: wpecp.tinymceSettings.content_css,
					wpeditimage_disable_captions: true,
					plugins: [
						wpecp.tinymceSettings.plugins
					],
					toolbar: wpecp.toolbars
			});
		};

		// Set focus on tinyMCE editor
		wpecp.focusEditor = function() {
			if ( typeof wpecp.focusTimeout !== 'undefined' ) {
				clearTimeout( wpecp.focusTimeout );
			}
			wpecp.focusTimeout = setTimeout( function() {
				tinymce.activeEditor.focus();
			}, 500 );
		};

		// Before moving the respond form, remove the tinyMCE instance.
		// This is because tinyMCE must be reattached after moving it's element to function properly.
		tinymce.get( commentTextarea.id ).remove();

		comm.parentNode.insertBefore( respond, comm.nextSibling );
		if ( post && postId ) {
			post.value = postId;
		}
		parent.value = parentId;
		cancel.style.display = '';

		// After the respond form has been moved below the comment, reattach a new tinyMCE instance.
		wpecp.initEditor();
		// After attaching the tinyMCE instance, set focus to the new tinyMCE instance.
		wpecp.focusEditor();

		cancel.onclick = function() {
			var t       = addComment,
				temp    = t.I( 'wp-temp-form-div' ),
				respond = t.I( t.respondId );

			if ( ! temp || ! respond ) {
				return;
			}

			// If the respond form is canceled, remove the tinyMCE instance again before the form is moved back.
			tinymce.get( commentTextarea.id ).remove();

			t.I( 'comment_parent' ).value = '0';
			temp.parentNode.insertBefore( respond, temp );
			temp.parentNode.removeChild( temp );
			this.style.display = 'none';
			this.onclick = null;

			// After the respond form has been moved back, reattach a new tinyMCE instance.
			// We don't set focus to this instance.
			wpecp.initEditor();

			return false;
		};

		/*
		 * Set initial focus to the first form focusable element.
		 * Try/catch used just to avoid errors in IE 7- which return visibility
		 * 'inherit' when the visibility value is inherited from an ancestor.
		 */
		try {
			for ( var i = 0; i < commentForm.elements.length; i++ ) {
				element = commentForm.elements[i];
				cssHidden = false;

				// Modern browsers.
				if ( 'getComputedStyle' in window ) {
					style = window.getComputedStyle( element );
				// IE 8.
				} else if ( document.documentElement.currentStyle ) {
					style = element.currentStyle;
				}

				/*
				 * For display none, do the same thing jQuery does. For visibility,
				 * check the element computed style since browsers are already doing
				 * the job for us. In fact, the visibility computed style is the actual
				 * computed value and already takes into account the element ancestors.
				 */
				if ( ( element.offsetWidth <= 0 && element.offsetHeight <= 0 ) || style.visibility === 'hidden' ) {
					cssHidden = true;
				}

				// Skip form elements that are hidden or disabled.
				if ( 'hidden' === element.type || element.disabled || cssHidden ) {
					continue;
				}

				element.focus();
				// Stop after the first focusable element.
				break;
			}

		} catch( er ) {}

		return false;
	},

	I: function( id ) {
		return document.getElementById( id );
	}
};
