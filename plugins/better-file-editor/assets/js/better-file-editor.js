/**
 * Better File Editor
 * https://wordpress.org/plugins/better-file-editor/
 *
 * Copyright (c) 2015 Bryan Petty
 * Licensed under the GPLv2+ license.
 */

/* global document */
/* global localStorage */
/* global window */
/* global bfe */
/* global ace */

( function() {
	'use strict';

	function initialize() {

		var newcontent = jQuery( '#newcontent' );
		newcontent.parent().attr( 'id', 'editor' );

		newcontent.after( '\
		<div id="wp-ace-editor-controls"><table><tr>\
			<td>\
				<label for="editor_theme">' + bfe.theme_label + '</label>\
				<select id="editor_theme" size="1">\
					<optgroup label="' + bfe.theme_bright_label + '">\
						<option value="ace/theme/chrome">Chrome</option>\
						<option value="ace/theme/clouds">Clouds</option>\
						<option value="ace/theme/crimson_editor">Crimson Editor</option>\
						<option value="ace/theme/dawn">Dawn</option>\
						<option value="ace/theme/dreamweaver">Dreamweaver</option>\
						<option value="ace/theme/eclipse">Eclipse</option>\
						<option value="ace/theme/github">GitHub</option>\
						<option value="ace/theme/iplastic">IPlastic</option>\
						<option value="ace/theme/katzenmilch">KatzenMilch</option>\
						<option value="ace/theme/kuroir">Kuroir</option>\
						<option value="ace/theme/solarized_light">Solarized Light</option>\
						<option value="ace/theme/sqlserver">SQL Server</option>\
						<option value="ace/theme/textmate" selected="selected">TextMate</option>\
						<option value="ace/theme/tomorrow">Tomorrow</option>\
						<option value="ace/theme/xcode">XCode</option>\
					</optgroup>\
					<optgroup label="' + bfe.theme_dark_label + '">\
						<option value="ace/theme/ambiance">Ambiance</option>\
						<option value="ace/theme/chaos">Chaos</option>\
						<option value="ace/theme/clouds_midnight">Clouds Midnight</option>\
						<option value="ace/theme/cobalt">Cobalt</option>\
						<option value="ace/theme/idle_fingers">idleFingers</option>\
						<option value="ace/theme/kr_theme">krTheme</option>\
						<option value="ace/theme/merbivore">Merbivore</option>\
						<option value="ace/theme/merbivore_soft">Merbivore Soft</option>\
						<option value="ace/theme/mono_industrial">Mono Industrial</option>\
						<option value="ace/theme/monokai">Monokai</option>\
						<option value="ace/theme/pastel_on_dark">Pastel on dark</option>\
						<option value="ace/theme/solarized_dark">Solarized Dark</option>\
						<option value="ace/theme/terminal">Terminal</option>\
						<option value="ace/theme/tomorrow_night">Tomorrow Night</option>\
						<option value="ace/theme/tomorrow_night_blue">Tomorrow Night Blue</option>\
						<option value="ace/theme/tomorrow_night_bright">Tomorrow Night Bright</option>\
						<option value="ace/theme/tomorrow_night_eighties">Tomorrow Night 80s</option>\
						<option value="ace/theme/twilight">Twilight</option>\
						<option value="ace/theme/vibrant_ink">Vibrant Ink</option>\
					</optgroup>\
				</select>\
			</td>\
			<td>\
				<label for="fontsize">' + bfe.font_size_label + '</label>\
				<select id="fontsize" size="1">\
					<option value="10px">10px</option>\
					<option value="11px">11px</option>\
					<option value="12px" selected="selected">12px</option>\
					<option value="14px">14px</option>\
					<option value="16px">16px</option>\
				</select>\
			</td>\
			<td>\
				<input type="checkbox" id="show_print_margin" checked />\
				<label for="show_print_margin">' + bfe.show_ruler_label + '</label>\
			</td>\
			<td>\
				<input type="checkbox" id="show_gutter" checked />\
				<label for="show_gutter">' + bfe.show_gutter_label + '</label>\
			</td>\
			<td>\
				<input type="checkbox" name="show_hidden" id="show_hidden" />\
				<label for="show_hidden">' + bfe.whitespace_label + '</label>\
			</td>\
		</tr></table></div>\
		<div id="wp-ace-editor"></div>\
		' );

		var editor = ace.edit( 'wp-ace-editor' ),
			filename = document.template.file.value,
			modelist = ace.require( 'ace/ext/modelist' ),
			textarea = document.getElementById( 'newcontent' );

		editor.$blockScrolling++;
		editor.setAnimatedScroll( true );
		editor.setAutoScrollEditorIntoView();
		textarea.style.display = 'none';
		editor.setValue( textarea.value );

		editor.session.setMode( modelist.getModeForPath( filename ).mode );
		editor.session.setUseSoftTabs( false );

		// Tie in the form submission event so that we can copy the document back
		// to the official form input WordPress is expecting the new source to be in.
		jQuery( '#template' ).submit(function () {
			textarea.value = editor.getValue();
			return true;
		});

		jQuery( window ).resize(function() { editor.resize(); });

		editor.resize();
		editor.focus();

		editor.selection.clearSelection();
		editor.selection.moveCursorFileStart();
		if ( localStorage ) {
			var cursor_positions = JSON.parse( localStorage.getItem( 'cursor_positions' ) );
			if ( ( typeof cursor_positions === undefined ) || cursor_positions === null ) {
				cursor_positions = {};
			}
			if ( filename in cursor_positions ) {
				var pos = cursor_positions[filename];
				var offset = pos.row - 10;
				if ( offset < 0 ) {
					offset = 0;
				}
				editor.moveCursorToPosition( pos );
				editor.scrollToRow( offset );
			}
			editor.selection.on( 'changeCursor', function() {
				cursor_positions[filename] = editor.selection.getCursor();
				localStorage.setItem( 'cursor_positions', JSON.stringify( cursor_positions ) );
			});
		}

		editor.$blockScrolling--;

		bindDropdown( 'editor_theme', function( value, id ) {
			if ( ! value ) {
				return;
			}
			editor.setTheme( value );
			document.getElementById( id ).selectedValue = value;
		});

		bindDropdown( 'fontsize', function( value ) {
			document.getElementById( 'wp-ace-editor' ).style.fontSize = value;
		});

		bindCheckbox( 'show_hidden', function( checked ) {
			editor.setShowInvisibles( checked );
		});

		bindCheckbox( 'show_gutter', function( checked ) {
			editor.renderer.setShowGutter( checked );
		});

		bindCheckbox( 'show_print_margin', function( checked ) {
			editor.renderer.setShowPrintMargin( checked );
		});
	}

	function save_setting( el, val ) {
		if ( ! el.onchange && ! el.onclick ) {
			return;
		}

		if ( 'checked' in el ) {
			if ( val !== undefined ) {
				el.checked = val;
			}
			if ( localStorage ) {
				localStorage.setItem( el.id, el.checked ? 1 : 0 );
			}
		} else {
			if ( val !== undefined ) {
				el.value = val;
			}
			if ( localStorage ) {
				localStorage.setItem( el.id, el.value );
			}
		}
	}

	function bindCheckbox( id, callback ) {
		var el = document.getElementById( id );
		if ( localStorage && localStorage.getItem( id ) ) {
			el.checked = localStorage.getItem( id ) === '1';
		}
		var onCheck = function() {
			callback( !! el.checked );
			save_setting( el );
		};
		el.onclick = onCheck;
		onCheck();
	}

	function bindDropdown( id, callback ) {
		var el = document.getElementById( id );
		if ( localStorage && localStorage.getItem( id ) ) {
			el.value = localStorage.getItem( id );
		}
		var onChange = function() {
			callback( el.value, id );
			save_setting( el );
		};
		el.onchange = onChange;
		onChange();
	}

	jQuery( document ).ready(function() {
		/**
		 * Detecting the HTML5 Canvas API (usually) gives us IE9+ and
		 * of course all modern browsers. This should be adequate for
		 * minimum requirements instead of browser sniffing.
		 */
		if( !! document.createElement( 'canvas' ).getContext ) {
			initialize();
		}
	});

})();
