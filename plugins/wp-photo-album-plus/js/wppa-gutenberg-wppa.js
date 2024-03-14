/* wppa-gutenberg-wppa.js
* Pachkage: wp-photo-album-plus
*
* Version 8.5.03.002
*/

// Global vars
var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    blockStyle = { backgroundColor: '#ccc', color: '#333', padding: '20px', clear: 'both' },
	iconStyle = { width: '24px', height: '24px' },
	wppaPhotoDialog,
	wppaWppaDialogHtml,
	wppaImageDirectory,
	wppaPhotoDirectory,
	wppaPhotoDialogCounter;
	if ( ! wppaPhotoDialogCounter ) {
		wppaPhotoDialogCounter = 1;
	}

var globalPropsInEdit, globalPropsInSave, globalRes;


// Actions on dom ready
jQuery(document).ready(function(){

	// Register our block
	registerBlockType( 'wppa/gutenberg-wppa', {

		title: 		'WPPA Gallery',
		icon: 		el( 'img',
						{
							src: 	wppaImageDirectory+'album32.png',
							style: 	iconStyle,
						} ),
		category: 	'wppa-shortcodes',
		edit: 		function( props ) {

						var shortcode = props.attributes.shortcode || '';
						wppaConsoleLog('edit '+shortcode);

						if ( shortcode.length ) {
							var id = parseInt( 1000 * Math.random() );
							wppaGutenbergGetWppaShorcodeRendered( shortcode, id );
							return el( 'div', {id:id} , shortcode ); //'Loading...' );
						}
						else {

							// If there is a ready button visible, click it.
							var but = jQuery('#wppa-shortcode-wppa-ready');
							if ( jQuery(but).css('display') != 'none' ) {
								jQuery(but).trigger('click');
							}

							jQuery('#wppa-preview-rendered').html('');

							var Uid = 'wppa-wppa-dialog-' + wppaPhotoDialogCounter;
							wppaPhotoDialogCounter++;

							wppaPhotoDialog = el( 'div', { id: Uid, style: blockStyle }, 'placeholder' );
							setTimeout( function() {
								jQuery( '#' + Uid ).html( wppaWppaDialogHtml );
								jQuery( '#wppa-preview-rendered' ).html('');
							}, 100 );

							setTimeout( function() {

								// Bind onchange handler to ready button
								jQuery( '#wppa-shortcode-wppa-ready' ).on( 'click',
									function() {
										var sc = wppaGutenbergGalleryEvaluate();
									//	var sc = jQuery("#wppagallery-shortcode-preview").val();
									//	sc.replace(/"/g, 'QUOTE');

										props.setAttributes({shortcode: sc});

						//				return sc.replace( /#/g, '#' );
									});
							}, 150 );

							return wppaPhotoDialog;
						}
					},
		save: 		function( props ) {

						var shortcode = props.attributes.shortcode || '';
						wppaConsoleLog('save '+shortcode);

						if ( shortcode.length ) {
							return shortcode; //.replace( /#/g, '#' );
						}
					},
	} );

	// Get the dialog html
	jQuery.ajax( {

		url: 		wppaAjaxUrl,
		data: 		'action=wppa' +
					'&wppa-action=gutenbergwppadialog',
		async: 		true,
		type: 		'GET',
		timeout: 	60000,
		beforeSend: function( xhr ) {

					},
		success: 	function( result, status, xhr ) {
						wppaWppaDialogHtml = wppaEntityDecode( result );
					},
		error: 		function( xhr, status, error ) {
						wppaConsoleLog( 'Get gutenberg photo dialog failed. Error = ' + error + ', status = ' + status );
					},
		complete: 	function( xhr, status, newurl ) {

					}
	} );

} );


// Returns '[wppa ...]' when done
function wppaGutenbergGalleryEvaluate(needPreview) {


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
	var allopen 		= false;
	var andor 			= false;
	var sep 			= '';
	var landing 		= '0';
	var rootalbum 		= '0';
	var admins 			= '';
	var align 			= 'none';
	var html 			= '';
	var cache 			= '';
	var delay 			= '';

	// Init colors of <select> tags
	jQuery( 'select' ).css( 'color', '#700' );
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
					allopen = jQuery('#wppagallery-calendar-allopen').prop('checked');
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
						case '%23topten':
						case '%23lasten':
						case '%23featen':
						case '%23comten':
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
						case '%23tags':
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
						case '%23last':
							jQuery('#wppagallery-album-parent-tr').show();
							parent = jQuery('#wppagallery-album-parent-parent').val();
							jQuery('#wppagallery-album-count-tr').show();
							count = jQuery('#wppagallery-album-count').val();
							break;
						case '%23cat':
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
						case '%23owner':
						case '%23upldr':
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
						case '%23all':
							break;
						default:
							if ( album != null ) {
								alert( __( 'Unimplemented virtual album', 'wp-photo-album-plus' ) + ': ' + album );
							}
					}
					if ( ( album != '%23cat' || cats != '' ) &&
						( album != '%23owner' || owner != '' ) &&
						( album != '%23upldr' || owner != '' ) &&
						( album != '%23topten' || parent != '' ) &&
						( album != '%23lasten' || parent != '' ) &&
						( album != '%23comten' || parent != '' ) &&
						( album != '%23featen' || parent != '' ) ) {
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
			var html = wppaGutenbergPhotoPreview( photo );
			jQuery('#wppagallery-photo-preview').html(html);
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
	var size = '0';
//	if ( document.getElementById('wppagallery-size') ) {
//		size = document.getElementById('wppagallery-size').value;
//	}

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
	align = jQuery('#wppagallery-align').val() || 'none';
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

	// Extract comment
	/*
	var t = document.getElementById('wppagallery-shortcode-preview').value;
	t = t.replace(/&quot;/g, '"');
	t = t.split(']');
	t = t[1];
	t = t.split('[');
	var shortcodeComment = t[0];
	*/

	// Close
	shortcode += ']';//+shortcodeComment+'[/wppa]';

	// Display shortcode
	dispShortcode = shortcode.replace(/"/g, '&quot;');
	dispShortcode = dispShortcode.replace(/@/g, '&#35;');
	dispShortcode = dispShortcode.replace(/%23/g, '#');
	html = '<input type="text" id="wppagallery-shortcode-preview" style="background-color:#ddd; width:100%; height:26px;" value="'+dispShortcode+'" />';
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

		jQuery( '#wppa-shortcode-wppa-ready' ).show();
	}
	else {
		jQuery( '#wppa-shortcode-wppa-ready' ).hide();
	}

	if ( shortcodeOk ) {
		result = shortcode;
		if ( result == '[wppa]' ) result = wppaSavedShortcode;
		else wppaSavedShortcode = result;

		if ( needPreview ) wppaGutenbergGetWppaShorcodeRendered( shortcode, 'wppa-preview-rendered' );
	}
	else {
		result = '';
		jQuery('#wppa-preview-rendered').html('');
	}
wppaConsoleLog('Returning '+result);
	return result;
}


var wppaSavedShortcode = '[wppa]';
// Get the rendered shortcode by ajax
function wppaGutenbergGetWppaShorcodeRendered( shortcode, divId ) {

	// console.log('Fetching shortcode rendered '+shortcode);

	if ( shortcode == '[wppa]' ) {
		shortcode = wppaSavedShortcode;
	}

	jQuery.ajax( {

		url: 		wppaAjaxUrl,
		data: 		'action=wppa' +
					'&wppa-action=getshortcodedrendered' +
					'&shortcode=' + shortcode, /* +
					'&mocc=' + wppaPhotoDialogCounter, */
		async: 		true,
		type: 		'GET',
		timeout: 	60000,
		beforeSend: function( xhr ) {

					},
		success: 	function( result, status, xhr ) {
						result = result.replace(/\[script/g, '<script');
						result = result.replace(/\[\/script/g, '</script');
						result = result.replace(/&gt;/g, '>');
						jQuery( '#' + divId ).html( result + '<div style="clear:both;" >' );
					},
		error: 		function( xhr, status, error ) {
						wppaConsoleLog( 'Get gutenberg get shortcode rendered failed. Error = ' + error + ', status = ' + status );
					},
		complete: 	function( xhr, status, newurl ) {

						// Fix slideshow layout
						jQuery(".filmwindow").each(function(){
							var w = jQuery(this).parent().width();
							jQuery(this).css({width:(w-84)});
							var h = jQuery(this).height();
							jQuery(this).parent().css({height:(h+8)});
						});
						setInterval(function(){_wppaSSRuns.forEach(function(currentValue, index){if(currentValue)wppaStopShow(index)})},1000);
					}
	} );
}

function wppaGutenbergPhotoPreview( id ) {


var html;

	if ( id == '#potd' ) {
		html = __( 'No Preview available', 'wp-photo-album-plus' );
	}
	else if ( id.indexOf('xxx') != -1 ) { 				// its a video
		var idv = id.replace('xxx', '');
		html =
		'<video preload="metadata" style="max-width:400px; max-height:300px; margin-top:3px;" controls>'+
			'<source src="'+wppaPhotoDirectory+idv+'mp4" type="video/mp4">'+
			'<source src="'+wppaPhotoDirectory+idv+'ogg" type="video/ogg">'+
			'<source src="'+wppaPhotoDirectory+idv+'ogv" type="video/ogg">'+
			'<source src="'+wppaPhotoDirectory+idv+'webm" type="video/webm">'+
		'</video>';
	}
	else {
		html =
		'<img src="'+wppaPhotoDirectory+id+'" style="max-width:400px; max-height:300px;" />';
	}
	return html;
}