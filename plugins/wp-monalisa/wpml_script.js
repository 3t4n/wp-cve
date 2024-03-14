/**
 * Javascript functions for wp-monalisa edit form
 *
 * @package wp-monalisa
 */

/* wait for jquery to be loaded */
while ( ! window.jQuery) {
	setTimeout( function() { defer( method ) }, 50 );
}

/*
   diese funktion haengt die smilies an den vorhandenen im editor text an

*/
function smile2edit(textid,smile,replace)
{
	var itext;
	var tedit = null;

	// einzufügenden text ermitteln.
	if ( replace == 1) {
		itext = "<img class='wpml_ico' alt='' src='" + smile + "' />";
	} else {
		itext = " " + smile + " "; // add space to separate smilies.
	}

	// editor objekt holen, falls vorhanden.
	if ( typeof tinyMCE != "undefined" ) {
		tedit = tinyMCE.get( 'content' );
	}

	if ( tedit == null || tedit.isHidden() == true) {
		// text in html editor einfügen.
		tarea = document.getElementById( textid );
		insert_text( itext, tarea );
	} else if ( (tedit.isHidden() == false) && window.tinyMCE) {
		// füge den text in den tinymce ein.
		var tmce_ver = window.tinyMCE.majorVersion;
		if (tmce_ver == "4") {
			window.tinyMCE.execCommand( 'mceInsertContent', false, itext );
		} else {
			window.tinyMCE.execInstanceCommand( 'content', 'mceInsertContent', false, itext );
		}
	}
}


/*
   diese funktion fügt den text stxt an der aktuellen position des cursors
   der textarea obj ein. obj ist als objekt zu übergeben

*/
function smile2comment(textid,smile,replace,myid){
	var tedit = null;

	// First try to find it top down.
	tarea = top.document.getElementById( textid );
	// now try to find it bottom up.
	if (tarea == null) {
		tarea = jQuery( '#' + myid ).parent().find( 'textarea' )[0];
	}
	if (tarea == null) {
		tarea = jQuery( '#' + myid ).parent().parent().find( 'textarea' )[0];
	}
	if (tarea == null) {
		tarea = jQuery( '#' + myid ).parent().parent().parent().find( 'textarea' )[0];
	}
	if (tarea == null) {
		tarea = jQuery( '#' + myid ).parent().parent().parent().parent().find( 'textarea' )[0];
	}
	if (tarea == null) {
		tarea = jQuery( '#' + myid ).parent().parent().parent().parent().parent().find( 'textarea' )[0];
	}
	if (tarea == null) {
		tarea = jQuery( '#' + myid ).parent().parent().parent().parent().parent().parent().find( 'textarea' )[0];
	}
	// for jetpack support #FIXME.
	if (tarea == null) {
		tarea = jQuery( 'jetpack_remote_comment' ).contents().find( 'textarea' )[1];
	}
	// for rtmedia buddypress media plugin.
	if (tarea == null) {
		tarea = jQuery( '#comment_content' )[0];
	}
	if (tarea == null) {
		tarea = jQuery( '#' + textid )[0];
	}
	if (tarea == null) {
		tarea = jQuery( '#bbp_topic_content' )[0];
	}
	// maybe we are using TinyMCE
	// editor objekt holen, falls vorhanden.
	if ( typeof tinyMCE != "undefined" ) {
		teid = tinyMCE.activeEditor.editorId;
		// this is for bbPress with tinyMCE 4.
		if (typeof teid == "undefined") {
			teid = tinyMCE.activeEditor.id;
		}
		tedit = tinyMCE.get( teid );
	}

	if (tarea == null && tedit == null) {
		alert( 'wp-monalisa: Textarea not found. Please contact the webmaster of this site.' );
		return;
	}

	// calculate text to insert.
	var itext = "";
	if ( replace == 1) {
		itext = "<img class='wpml_ico' alt='" + smile + "' src='" + smile + "' />";
	} else {
		itext = " " + smile + " "; // add space to separate smilies.
	}

	// insert text into editor and or textarea.
	if ( tarea != null) {
		insert_text( itext, tarea );
	}

	if ( tedit != null && tedit.isHidden() == false) {
		var tmce_ver = window.tinyMCE.majorVersion;

		if (tmce_ver == "4") {
			window.tinyMCE.execCommand( 'mceInsertContent', false, itext );
		} else {
			window.tinyMCE.execInstanceCommand( teid, 'mceInsertContent', false, itext );
		}
	}
}

/*
  diese funktion fügt den text stxt an der aktuellen stelle des cursors
  der textarea obj ein. obj ist als objekt zu übergeben.
*/
function insert_text(stxt,obj)
{
	try {
		// pruefe of fckeditor aktiv ist entweder
		// als comment editor oder als text editor.
		if (typeof window.CKEDITOR.instances.comment != 'undefined') {
			// wenn ja, nutzen wir die fckeditor funktion
			// um den text einzufügen.
			window.CKEDITOR.instances.comment.insertHtml( stxt );
			return;
		}

		if (typeof CKEDITOR.instances.content != 'undefined') {
			// wenn ja, nutzen wir die fckeditor funktion
			// um den text einzufügen.
			CKEDITOR.instances.content.insertHtml( stxt );
			return;
		}
	} catch (e) {
	}

	if (document.selection) {
		obj.focus();
		document.selection.createRange().text = stxt;
		document.selection.createRange().select();
	} else if (obj.selectionStart || obj.selectionStart == '0') {
		intStart = obj.selectionStart;
		intEnd = obj.selectionEnd;
		obj.value = (obj.value).substring( 0, intStart ) + stxt + (obj.value).substring( intEnd, obj.value.length );
		obj.selectionStart = obj.selectionEnd = intStart + stxt.length;
		obj.focus();
	} else {
		obj.value += stxt;
	}

}

/*
  diese funktion(en) dient zum auf und zuklappen der gesamten smilies
  im comment form und zwar ajax-like
*/
/* does not work with bwp minify
jQuery(function() {
	// show all smilies
	jQuery("#buttonm").click(function() {
		jQuery("#smiley1").toggle("slow");
		jQuery("#smiley2").toggle("slow");
		});
	// hide all smilies
		jQuery("#buttonl").click(function() {
		jQuery("#smiley2").toggle("slow");
		jQuery("#smiley1").toggle("slow");
		});
	});
*/
// globale javascript variable um zu kontrollieren, dass jedes bild nur einmal geladen wird.
var wpml_first_preload = true;

// startet das vorladen der smilies an, nach 2000ms.
jQuery( document ).on( "load", function() { setTimeout( function() { wpml_preload();}, 2000 ) } );

//
// diese funktion lädt die bilder im hintergrund genau einmal
// die bilder urls stehen im array wpml_imglist.
//
function wpml_preload() {
	if (wpml_first_preload && typeof wpml_imglist != "undefined" && (wpml_imglist instanceof Array) ) {
		var i = 0;
		var l = wpml_imglist.length;
		for (i = 0; i < l; i++) {
			var wpml_image = new Image();
			wpml_image.src = wpml_imglist[i];
		}
		wpml_first_preload = false;
	}
}

//
// diese funktion fügt das vorbereitete html in das div ein
// das html steht im array wpml_more_html.
//
function wpml_more_smilies(muid) {
	if (jQuery( '#smiley2-' + muid ).html() == '&nbsp;' ) {
		jQuery( '#smiley2-' + muid ).html( unescape( wpml_more_html[muid] ) );
	}
}

function wpml_toggle_smilies(uid) {
	jQuery( "#smiley1-" + uid ).toggle( "slow" );
	jQuery( "#smiley2-" + uid ).toggle( "slow" );
}

// calls wpml-edit with a post call and the id of the post to disable comment smilies on
// puts the returned string into the element with id message.
function wpml_comment_exclude(postid) {
	var nonce = document.getElementById( "wpml_tiny_nonce" ).value;

	jQuery( "#wpml_messages" ).html( '' );

	jQuery.ajax(
		{
			type: 'POST',
			url: ajaxurl,
			data: {	'action': 'wpml_edit_disable_comments_ajax', 'postid': postid, 'nonce': nonce },
			success: function (data, textStatus, XMLHttpRequest) {
				jQuery( "#wpml_messages" ).html( data );
				importdone = true;
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert( errorThrown );
			}
		}
	);
}

//
// toggle smiley popup.
//
function wpml_popup_toggle(id) {
	var dobj = document.getElementById( id ).style.display;

	if (dobj == 'none') {
		document.getElementById( id ).style.display = 'inline-block';
	} else {
		document.getElementById( id ).style.display = 'none';
	}
}
