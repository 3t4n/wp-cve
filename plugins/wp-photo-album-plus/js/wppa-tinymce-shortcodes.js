/* wppa-tinymce-shortcodes.js
* Pachkage: wp-photo-album-plus
*
*
* Version 8.5.03.002
*
*/

// Add the wppa button to the mce editor
tinymce.PluginManager.add('wppagallery', function(editor, url) {

		function openWppaShortcodeGenerator() {

			var opt = {
						modal:		true,
						resizable: 	true,
						width:		720,
						show: 		{
										effect: 	"fadeIn",
										duration: 	400
									},
						closeText: 	"",
						close: 		function(event,ui) {


									},
						open: 		function(event,ui) {
										wppaGalleryEvaluate();
									}
						};

			jQuery( "#wppagallery-form" ).dialog(opt).dialog( "open" );

			jQuery( '.ui-widget-header' ).css( {
											background:			'none',
										});

			jQuery( '.ui-dialog' ).css( {
											boxShadow: 			'0px 0px 5px 5px #aaaaaa',
											borderRadius: 		wppaBoxRadius+'px',
											padding: 			'8px',
											backgroundColor: 	wppaModalBgColor,
											boxSizing: 			'content-box',
											zIndex: 			200200,
										});

			jQuery( '.ui-dialog-titlebar' ).css( {
													lineHeight: '0px',
													height: 	'24px',
													fontSize: 	'18px',
													fontWeight: 'bold',
												});

			jQuery( '.ui-dialog-title' ).css( {
													position: 	'absolute',
													top: 		'15px',
													fontSize: 	'18px',
													fontWeight: 'bold',
											});

			jQuery( '.ui-button' ).css(	{
											backgroundImage: 	wppaModalQuitImg,
											padding:			0,
											position: 			'absolute',
											right: 				'8px',
											top: 				'20px',
											width: 				'16px',
											height: 			'16px',
										});

			jQuery( '.ui-widget-overlay' ).css( {
													background:	'none',
												});

			jQuery( '.ui-button' ).attr( 'title', 'Close' );

			jQuery( '.ui-icon-closethick' ).css( {
											display: 			'none',
										});


		}

		editor.addButton('wppa_gallery_button', {
			image: wppaImageDirectory+'albumnew32.png',
			tooltip: 'WPPA+ Shortcode Generator',
			onclick: openWppaShortcodeGenerator
		});

});

// executes this when the DOM is ready
jQuery(function(){

	// creates a form to be displayed everytime the button is clicked
	var xmlhttp = wppaGetXmlHttp();				// located in wppa-admin-scripts.js

	// wppa-ajax.php calls wppa_make_tinymce_dialog(); which is located in wppa-tinymce.php
	var url = wppaAjaxUrl+'?action=wppa&wppa-action=tinymcedialog';

	xmlhttp.open("GET",url,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange=function() {
		if  (xmlhttp.readyState == 4 && xmlhttp.status!=404 ) {
			var formtext = wppaEntityDecode(xmlhttp.responseText);

			var form = jQuery(formtext);

			var table = form.find('table');
			form.appendTo('body').hide();

			// handles the click event of the submit button
			form.find('#wppagallery-submit').click(function(){

				// Get the shortcode from the preview/edit box
				newShortcode = document.getElementById('wppagallery-shortcode-preview').value;

				// Filter
				newShortcode = newShortcode.replace(/&quot;/g, '"');

				// inserts the shortcode into the active editor
				tinyMCE.activeEditor.execCommand('mceInsertContent', 0, newShortcode);

				// closes Dialog box
				jQuery( "#wppagallery-form" ).dialog( "close" );
			});
		}
	}
});

function wppaGalleryEvaluate() {


	// Assume shortcode complete
	var shortcodeOk = true;

	// Hide option elements
	jQuery('#wppagallery-galery-type-tr').hide();
	jQuery('#wppagallery-slides-type-tr').hide();
	jQuery('#wppagallery-single-type-tr').hide();
	jQuery('#wppagallery-search-type-tr').hide();
	jQuery('#wppagallery-miscel-type-tr').hide();
	jQuery('#wppagallery-album-type-tr').hide();
	jQuery('#wppagallery-album-real-tr').hide();
	jQuery('#wppagallery-album-real-search-tr').hide();
	jQuery('#wppagallery-album-realopt-tr').hide();
	jQuery('#wppagallery-album-virt-tr').hide();
	jQuery('#wppagallery-album-virt-cover-tr').hide();
	jQuery('#wppagallery-owner-tr').hide();
	jQuery('#wppagallery-owner-parent-tr').hide();
	jQuery('#wppagallery-album-parent-tr').hide();
	jQuery('#wppagallery-album-count-tr').hide();
	jQuery('#wppagallery-photo-count-tr').hide();
	jQuery('#wppagallery-albumcat-tr').hide();
	jQuery('#wppagallery-photo-tr').hide();
	jQuery('#wppagallery-photo-preview-tr').hide();
	jQuery('#wppagallery-phototags-tr').hide();
	jQuery('#wppagallery-search-tr').hide();
	jQuery('#wppagallery-taglist-tr').hide();
	jQuery('#wppagallery-album-super-tr').hide();
	jQuery('#wppagallery-calendar-tr').hide();
	jQuery('#wppagallery-tags-cats-tr').hide();
	jQuery('#wppagallery-landing-tr').hide();
	jQuery('#wppagallery-rootalbum-tr').hide();
	jQuery('#wppagallery-admins-tr').hide();
	jQuery('#wppagallery-cache-tr').hide();
	jQuery('#wppagallery-delay-tr').hide();
	jQuery('#wppagallery-timeout-tr').hide();

	// Init shortcode parts
	var shortcode 		= '[wppa';
	var topType 		= '';
	var type 			= '';
	var galType 		= '';
	var slideType 		= '';
	var albumType 		= '';
	var searchType 		= '';
	var miscType 		= '';
	var album 			= '';
	var parent 			= '';
	var count 			= '';
	var photo 			= '';
	var id 				= '';
	var sub 			= '';
	var root 			= '';
	var needGalType 	= false;
	var needSlideType 	= false;
	var needAlbum 		= false;
	var needPhoto 		= false;
	var needOwner 		= false;
	var needTag 		= false;
	var needTagList 	= false;
	var needCat 		= false;
	var needSearchType 	= false;
	var needMiscType 	= false;
	var alltags 		= '';
	var taglist 		= '';
	var owner 			= '';
	var tags 			= '';
	var cats 			= '';
	var i,j,t;
	var caltype 		= '';
	var reverse 		= false;
//	var allopen 		= false;
	var andor 			= false;
	var sep 			= '';
	var landing 		= '0';
	var rootalbum 		= '0';
	var admins 			= '';
	var align 			= 'none';
	var html 			= '';
	var cache 			= '';
	var delay 			= '';
	var timeout 		= '0';

	// Init colors of <select> tags
//	jQuery( 'select' ).css( 'color', '#700' );
	jQuery( '#wppagallery-album-parent-parent' ).css( 'color', '#070' );
	jQuery( '#wppagallery-align' ).css( 'color', '#070' );

	// Type
	topType = jQuery('#wppagallery-top-type').val();
	if ( ! wppaIsEmpty( topType ) ) {
		jQuery('#wppagallery-top-type').css('color', '#070');
	}
	switch ( topType ) {
		case 'galerytype':
			jQuery('#wppagallery-galery-type-tr').show();
			type = jQuery('#wppagallery-galery-type').val();
			needGalType = true;
			needAlbum = true;
			jQuery('#wppagallery-album-type-tr').show();
			if ( ! wppaIsEmpty( type ) ) {
				jQuery('#wppagallery-galery-type').css('color', '#070');
				galType = type;
			}
			break;
		case 'slidestype':
			jQuery('#wppagallery-slides-type-tr').show();
			jQuery('#wppagallery-timeout-tr').show();
			type = jQuery('#wppagallery-slides-type').val();
			needSlideType = true;
			needAlbum = true;
			jQuery('#wppagallery-album-type-tr').show();
			if ( ! wppaIsEmpty( type ) ) {
				jQuery('#wppagallery-slides-type').css('color', '#070');
				slideType = type;
			}
			break;
		case 'singletype':
			jQuery('#wppagallery-single-type-tr').show();
			type = jQuery('#wppagallery-single-type').val();
			needPhoto = true;
			jQuery('#wppagallery-photo-tr').show();
			if ( ! wppaIsEmpty( type ) ) {
				jQuery('#wppagallery-single-type').css('color', '#070');
			}
			break;
		case 'searchtype':
			jQuery('#wppagallery-search-type-tr').show();
			type = jQuery('#wppagallery-search-type').val();
			needSearchType = true;
			searchType = type;
			if ( ! wppaIsEmpty( type ) ) {
				jQuery('#wppagallery-search-type').css('color', '#070');
			}
			switch ( type ) {
				case 'search':
					jQuery('#wppagallery-search-tr').show();
					if ( jQuery('#wppagallery-root').prop('checked') ) {
						jQuery('#wppagallery-rootalbum-tr').show();
					}
					rootalbum = jQuery('#wppagallery-rootalbum').val();
					jQuery('#wppagallery-landing-tr').show();
					landing = jQuery('#wppagallery-landing').val();
					break;
				case 'supersearch':
					break;
				case 'tagcloud':
				case 'multitag':
					jQuery('#wppagallery-taglist-tr').show();
					alltags = jQuery('#wppagallery-alltags').prop('checked');
					if ( ! alltags ) {
						needTagList = true;
						jQuery('#wppagallery-seltags').show();
						t = jQuery('.wppagallery-taglist-tags');
						var tagarr = [];
						i = 0;
						j = 0;
						while ( i < t.length ) {
							if ( t[i].selected ) {
								tagarr[j] = t[i].value;
								j++;
							}
							i++;
						}
						taglist = wppaArrayToEnum( tagarr, ',' );
					}
					break;
				case 'superview':
					jQuery('#wppagallery-album-super-tr').show();
					album = jQuery('#wppagallery-album-super-parent').val();
					break;
				case 'calendar':
					jQuery('#wppagallery-calendar-tr').show();
					jQuery('#wppagallery-album-super-tr').show();	// Optional parent album
					caltype = jQuery('#wppagallery-calendar-type').val();
					reverse = jQuery('#wppagallery-calendar-reverse').prop('checked');
					parent  = jQuery('#wppagallery-album-super-parent').val();
					break;
				default:
			}
			break;
		case 'misceltype':
			jQuery('#wppagallery-miscel-type-tr').show();
			type = jQuery('#wppagallery-miscel-type').val();
			needMiscType = true;
			if ( ! wppaIsEmpty( type ) ) {
				jQuery('#wppagallery-miscel-type').css('color', '#070');
			}
			switch ( type ) {
				case 'generic':
					miscType = type;
					break;
				case 'upload':
					miscType = type;
					jQuery('#wppagallery-album-realopt-tr').show();
					album = jQuery('#wppagallery-album-realopt').val();
					album = album.toString().replace( /,/g, '.' );
					break;
				case 'landing':
				case 'stereo':
					miscType = type;
					break;
				case 'choice':
					miscType = type;
					jQuery('#wppagallery-admins-tr').show();
					admins = wppaGetSelectionEnumByClass('.wppagallery-admin', ',');
					break;
				default:
			}
			break;
		default:
	}
	if ( ! wppaIsEmpty( type ) ) {
		shortcode += ' type="'+type+'"';
	}

	// Album
	if ( needAlbum ) {
		albumType = jQuery('#wppagallery-album-type').val();
		if ( ! wppaIsEmpty( albumType ) ) {
			jQuery( '#wppagallery-album-type' ).css('color', '#070');
		}
		switch ( albumType ) {
			case 'real':
				jQuery('#wppagallery-album-real-tr').show();
				jQuery('#wppagallery-album-real-search-tr').show();

				// WPPA has Not many albums, there is a quick select box. If used, ...val() is not empty,
				if ( jQuery('#wppagallery-album-real-search') ) {
					if ( jQuery('#wppagallery-album-real-search').val() ) {
						var s = jQuery('#wppagallery-album-real-search').val().toLowerCase();
						if ( ! wppaIsEmpty( s ) ) {
							albums = jQuery('.wppagallery-album-r');
							if ( albums.length > 0 ) {
								var i = 0;
								while ( i < albums.length ) {
									var a = albums[i].innerHTML.toLowerCase();
									if ( a.search( s ) == -1 ) {
										jQuery( albums[i] ).removeAttr( 'selected' );
										jQuery( albums[i] ).hide();
									}
									else {
										jQuery( albums[i] ).show();
									}
									i++;
								}
							}
						}
					}
					else {
						jQuery('.wppagallery-album-r').show();
					}
				}

				// Get the selected album(s)
				album = jQuery('#wppagallery-album-real').val();

				// Make sure right delimiter
				album = album.toString().replace( /,/g, '.' );
				break;

			case 'virtual':

				// Open the right selection box dependant of type is cover or not
				// and get the album identifier
				if ( type == 'cover') {
					jQuery('#wppagallery-album-virt-cover-tr').show();
					album = jQuery('#wppagallery-album-virt-cover').val();
				}
				else {	// type != cover
					jQuery('#wppagallery-album-virt-tr').show();
					album = jQuery('#wppagallery-album-virt').val();
				}

				// Now displatch on album identifier found
				// and get the (optional) additional data
				if ( ! wppaIsEmpty( album ) ) {
					jQuery('#wppagallery-album-virt').css('color', '#070');
					switch ( album ) {
						case '#topten':
						case '#lasten':
						case '#featen':
						case '#comten':
							jQuery('#wppagallery-album-realopt-tr').show();

							// We use parent here for optional album(s), because album is already used for virtual album type
							// Not many albums
							if ( jQuery('.wppagallery-album-ropt').length > 0 ) {
								parent = wppaGetSelectionEnumByClass('.wppagallery-album-ropt', '.');
							}
							else {
								parent = jQuery('#wppagallery-album-realopt').val();
								if ( parent.indexOf(',') != -1 ) {
									parr = parent.split(',');
									parent = wppaArrayToEnum( parr, '.' );
								}
							}
							if ( parent == '' ) parent = '0';
							jQuery('#wppagallery-photo-count-tr').show();
							count = jQuery('#wppagallery-photo-count').val();
							break;
						case '#tags':
							jQuery('#wppagallery-phototags-tr').show();
							jQuery('#wppagallery-tags-cats-tr').show();
							andor = jQuery('[name=andor]:checked').val();
							if ( ! andor ) jQuery('#wppagallery-or').prop( 'checked', true );
							andor = jQuery('[name=andor]:checked').val();
							if ( andor == 'or' ) sep = ';';
							else sep = ',';
							needTag = true;
							t = jQuery('.wppagallery-phototags');
							var tagarr = [];
							i = 0;
							j = 0;
							while ( i < t.length ) {
								if ( t[i].selected ) {
									tagarr[j] = t[i].value;
									j++;
								}
								i++;
							}
							tags = wppaArrayToEnum( tagarr, sep );
							break;
						case '#last':
							jQuery('#wppagallery-album-parent-tr').show();
							parent = jQuery('#wppagallery-album-parent-parent').val();
							jQuery('#wppagallery-album-count-tr').show();
							count = jQuery('#wppagallery-album-count').val();
							break;
						case '#cat':
							jQuery('#wppagallery-albumcat-tr').show();
							jQuery('#wppagallery-tags-cats-tr').show();
							andor = jQuery('[name=andor]:checked').val();
							if ( ! andor ) jQuery('#wppagallery-or').prop( 'checked', true );
							andor = jQuery('[name=andor]:checked').val();
							if ( andor == 'or' ) sep = ';';
							else sep = ',';
							needCat = true;
							t = jQuery('.wppagallery-albumcat');
							var catarr = [];
							i = 0;
							j = 0;
							while ( i < t.length ) {
								if ( t[i].selected ) {
									catarr[j] = t[i].value;
									j++;
								}
								i++;
							}
							cats = wppaArrayToEnum( catarr, sep );
							break;
						case '#owner':
						case '#upldr':
							jQuery('#wppagallery-owner-tr').show();
							needOwner = true;
							owner = jQuery('#wppagallery-owner').val();
							if ( ! wppaIsEmpty( owner ) ) {
								jQuery( '#wppagallery-owner' ).css( 'color', '#070' );
								jQuery('#wppagallery-owner-parent-tr').show();
								parent = wppaGetSelectionEnumByClass('.wppagallery-album-p', '.');
								parent = parent.toString().replace( 'zero', '0' );
							}
							break;
						case '#all':
							break;
						default:
							if ( album != null ) {
								alert( __('Unimplemented virtual album', 'wp-photo-album-plus' ) + ': ' + album );
							}
					}
					if ( ( album != '#cat' || cats != '' ) &&
						( album != '#owner' || owner != '' ) &&
						( album != '#upldr' || owner != '' ) &&
						( album != '#topten' || parent != '' ) &&
						( album != '#lasten' || parent != '' ) &&
						( album != '#comten' || parent != '' ) &&
						( album != '#featen' || parent != '' ) ) {
					}
				}
				break;
			default:
				album = '';
		}
	}

	// Add album specs to shortcode
	if ( ! wppaIsEmpty( album ) ) {
		shortcode += ' album="'+album;
		if ( owner != '' ) 	shortcode += ','+owner;
		if ( parent == '' && count != '' ) 	parent = '0';
		if ( parent != '' ) shortcode += ','+parent;
		if ( count != '' ) 	shortcode += ','+count;
		if ( tags != '' ) 	shortcode += ','+tags;
		if ( cats != '' ) 	shortcode += ','+cats;
		shortcode += '"';
	}

	// Photo
	if ( needPhoto ) {
		photo = jQuery('#wppagallery-photo').val();
		if ( photo ) {
			id = photo.replace(/\//g,'');
			id = id.split('.');
			id = id[0];
			jQuery('#wppagallery-photo-preview-tr').show();
			wppaTinyMcePhotoPreview( photo )
			shortcode += ' photo="'+id+'"';
			jQuery('#wppagallery-photo').css('color', '#070');
		}
	}

	// Search options
	if ( type == 'search' ) {
		sub = jQuery('#wppagallery-sub').prop('checked');
		root = jQuery('#wppagallery-root').prop('checked');
		if ( sub ) shortcode += ' sub="1"';
		if ( root ) {
			if ( rootalbum != '0' ) shortcode += ' root="#'+rootalbum+'"';
			else  shortcode += ' root="1"';
		}
		if ( landing != '0' ) shortcode += ' landing="'+landing+'"';
	}
	if ( type == 'tagcloud' || type == 'multitag' ) {
		if ( taglist != '' ) {
			shortcode += ' taglist="'+taglist+'"';
		}
	}
	if ( type == 'calendar' ) {
		shortcode += ' calendar="'+caltype+'"';
		if ( parent ) {
			shortcode += ' parent="'+parent+'"';
		}
		var real = ! ( caltype == 'exifdtm' || caltype == 'timestamp' || caltype == 'modified' );
		if ( real ) {
			jQuery('#wppagallery-calendar-reverse-span').hide();
		}
		else {
			jQuery('#wppagallery-calendar-reverse-span').show();
		}
		if ( reverse && !real ) {
			shortcode += ' reverse="1"';
		}
	}

	// Admins choice
	if ( type == 'choice' ) {
		if ( admins.length > 0 ) {
			shortcode += ' admin="'+admins+'"';
		}
	}

	// Size
	var size = document.getElementById('wppagallery-size').value;

	// See if auto with fixed max
	var temp = size.split(',');
	if ( temp[1] ) {
		if ( temp[0] == 'auto' && parseInt( temp[1] ) == temp[1] && temp[1] > 100 ) {

			// its ok, auto with a static max of size temp[1]
			jQuery('#wppagallery-size').css('color', '#070');
		}
		else {

			// Not ok
			size = 0;
			jQuery('#wppagallery-size').css('color', '#700');
		}
	}

	// Numeric?
	else {
		if ( size != '' && size != 'auto' ) {
			if ( parseInt(size) != size ) {
				size = 0;
				jQuery('#wppagallery-size').css('color', '#700');
			}
		}
		if ( size < 0 ) {
			size = -size;
		}
		if ( size < 100 ) {
			size = size / 100;
		}
		jQuery('#wppagallery-size').css('color', '#070');
	}

	// Add size to shortcode
	if ( size != 0 ) {
		shortcode += ' size="'+size+'"';
	}

	// Align
	align = jQuery('#wppagallery-align').val();
	if ( align != 'none' ) {
		shortcode += ' align="'+align+'"';
	}

	// Cache
	if ( miscType != 'landing' ) {
		jQuery( '#wppagallery-cache-tr' ).show();
		if ( jQuery( '#wppagallery-cache' ).prop( 'checked' ) ) {
			shortcode += ' cache="inf"';
		}
	}

	// Delay
	if ( miscType != 'landing' && topType != 'singletype' ) {
		jQuery( '#wppagallery-delay-tr' ).show();
		if ( jQuery( '#wppagallery-delay' ).prop( 'checked' ) ) {
			shortcode += ' delay="yes"';
		}
	}

	// Timeout
	if ( topType == 'slidestype' ) {
		var t = parseInt( jQuery( '#wppagallery-timeout' ).val() );
		if ( t > 0 ) {
			shortcode += ' timeout="'+t+'"';
		}
	}

	/*
	// Extract comment
	var t = document.getElementById('wppagallery-shortcode-preview').value;
	t = t.replace(/&quot;/g, '"');
	t = t.split(']');
	t = t[1];
	t = t.split('[');
	var shortcodeComment = t[0];
	*/

	// Close
	shortcode += ']';

	// Display shortcode
	shortcode = shortcode.replace(/"/g, '&quot;');
	html = '<input type="text" id="wppagallery-shortcode-preview" style="background-color:#ddd; width:100%; height:26px;" value="'+shortcode+'" />';
	jQuery('#wppagallery-shortcode-preview-container').html( html );

	// Is shortcode complete?
	shortcodeOk = 	( album != '' || ! needAlbum ) &&
					( photo != '' || ! needPhoto ) &&
					( owner != '' || ! needOwner ) &&
					( taglist != '' || ! needTagList ) &&
					( galType != '' || ! needGalType ) &&
					( slideType != '' || ! needSlideType ) &&
					( searchType != '' || ! needSearchType ) &&
					( miscType != '' || ! needMiscType ) &&
					( tags != '' || ! needTag ) &&
					( cats != '' || ! needCat );

	// Debug
	if ( ! shortcodeOk ) {
		var text = '';
		if ( album == '' && needAlbum ) text += 'Need album\n';
		if ( photo == '' && needPhoto ) text += 'Need photo\n';
		if ( owner == '' && needOwner ) text += 'Need owner';
		if ( taglist == '' && needTagList ) text += 'Need taglist';
		if ( galType == '' && needGalType ) text += 'Need galType';
		if ( slideType == '' && needSlideType ) text += 'Need slideType';
		if ( searchType == '' && needSearchType ) text += 'Need searchType';
		if ( miscType == '' && needMiscType ) text += 'Need miscType';
		if ( tags == '' && needTag ) text += 'Need tags';
		if ( cats == '' && needCat ) text += 'Need cats';
//		alert( text );
	}

	// Display the right button
	if ( shortcodeOk ) {
		jQuery('#wppagallery-submit').show();
		jQuery('#wppagallery-submit-notok').hide();
	}
	else {
		jQuery('#wppagallery-submit').hide();
		jQuery('#wppagallery-submit-notok').show();
	}
}

function wppaTinyMcePhotoPreview( id ) {


	if ( id == '#potd' ) {
		jQuery('#wppagallery-photo-preview').html(__('No Preview available', 'wp-photo-album-plus' )); 	// No preview
	}
	else if ( id.indexOf('xxx') != -1 ) { 				// its a video
		var idv = id.replace('xxx', '');
		jQuery('#wppagallery-photo-preview').html('<video preload="metadata" style="max-width:400px; max-height:300px; margin-top:3px;" controls>'+
													'<source src="'+wppaPhotoDirectory+idv+'mp4" type="video/mp4">'+
													'<source src="'+wppaPhotoDirectory+idv+'ogg" type="video/ogg">'+
													'<source src="'+wppaPhotoDirectory+idv+'ogv" type="video/ogg">'+
													'<source src="'+wppaPhotoDirectory+idv+'webm" type="video/webm">'+
												'</video>');
	}
	else {
		jQuery('#wppagallery-photo-preview').html('<img src="'+wppaPhotoDirectory+id+'" style="max-width:400px; max-height:300px;" />');
	}
}

