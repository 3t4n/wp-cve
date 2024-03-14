/* wppa-gutenberg-photo.js
* Pachkage: wp-photo-album-plus
*
* Version 8.4.06.008
*/

// Global vars
var el = wp.element.createElement,
    wppaPhotoRegisterBlockType = wp.blocks.registerBlockType,
    wppaPhotoDialogBlockStyle = { backgroundColor: '#ccc', color: '#333', padding: '20px', clear: 'both' },
	wppaPhotoDialogIconStyle = { width: '24px', height: '24px' },
	wppaPhotoDialog,
	wppaPhotoDialogHtml,
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
	wppaPhotoRegisterBlockType( 'wppa/gutenberg-photo', {

		title: 		'WPPA Photo',
		icon: 		el( 'img',
						{
							src: 	wppaImageDirectory+'camera32.png',
							style: 	wppaPhotoDialogIconStyle,
						} ),
		category: 	'wppa-shortcodes',
		edit: 		function( props ) {

						var shortcode = props.attributes.shortcode || '';
						var photo = props.attributes.photo || '';

						if ( photo.length ) {
							var id = parseInt( 1000 * Math.random() );
							wppaGutenbergGetPhotoShortcodeRendered( shortcode, id );
							return el( 'div', {id:id}, shortcode ); //'Loading...' );
						}
						else {
							var Uid = 'wppa-photo-dialog-' + wppaPhotoDialogCounter;
							wppaPhotoDialogCounter++;
							wppaPhotoDialog = el( 'div', { id: Uid, style: wppaPhotoDialogBlockStyle }, 'placeholder' );
							setTimeout( function() {
								jQuery( '#' + Uid ).html( wppaPhotoDialogHtml );
							}, 100 );
							setTimeout( function() {

								// Bind onchange handler to ready button
								jQuery( '#wppa-shortcode-photo-ready' ).on( 'click',
									function() {

										var res = wppaGutenbergPhotoEvaluate(),
											sc = res.shortcode,
											photo = res.photo;

										if ( sc.length ) {
											props.setAttributes({
												shortcode: sc,
												photo: photo
											});
											return sc;
										}
									});

								// Make the upload form an ajax upload form
								jQuery(function() {

									var options = {
										beforeSend: function() {
											jQuery("#progress").show();
											jQuery("#bar").width("0%");
											jQuery("#message").html("");
											jQuery("#percent").html("");
										},
										uploadProgress: function(event, position, total, percentComplete) {
											jQuery("#bar").width(percentComplete+"%");
											if ( percentComplete < 95 ) {
												jQuery("#percent").html(percentComplete+"%");
											}
											else {
												jQuery("#percent").html(wppaTxtProcessing);
											}
										},
										success: function() {
											jQuery("#bar").width("100%");
											jQuery("#percent").html(wppaTxtDone);
										},
										complete: function(response) {
											var resparr = response.responseText.split( "||" );
											if ( resparr.length == 1 ) {
												jQuery("#message").html( '<span style="font-size: 10px;" >'+resparr[0]+'</span>' );
											}
											else {
												jQuery( "#wppaphoto-myphoto" ).html( wppaEntityDecode( resparr[2] ) );
											}
											wppaGutenbergPhotoEvaluate();
										},
										error: function(response) {
											return;
											jQuery("#message").html( '<span style="color: red;" >'+wppaTxtErrUnable+'</span>' );
											wppaConsoleLog(response);
										}
									};
									jQuery("#wppa-gutenberg-photo-uplform").ajaxForm(options);
								});

								// Initial evaluate
								wppaGutenbergPhotoEvaluate();

							}, 150 );
							return wppaPhotoDialog;
						}
					},
		save: 		function( props ) {

						var shortcode = props.attributes.shortcode || '';

						if ( shortcode.length ) {
							return shortcode;
						}
					},
	} );

	// Get the dialog html
	jQuery.ajax( {

		url: 		wppaAjaxUrl,
		data: 		'action=wppa' +
					'&wppa-action=gutenbergphotodialog',
		async: 		true,
		type: 		'GET',
		timeout: 	60000,
		beforeSend: function( xhr ) {

					},
		success: 	function( result, status, xhr ) {
						wppaPhotoDialogHtml = wppaEntityDecode( result );
					},
		error: 		function( xhr, status, error ) {
						wppaConsoleLog( 'Get gutenberg photo dialog failed. Error = ' + error + ', status = ' + status );
					},
		complete: 	function( xhr, status, newurl ) {

					}
	} );

} );


var wppaMyPhotoSelection = true;

// Return '[photo xxx]' when a photo is selected. xxx stands for the integer photo id.
function wppaGutenbergPhotoEvaluate() {

	// Assume shortcode complete
	var shortcodeOk = true;
	var myAll;
	var shortcode;
	var result;

	// Photo
	if ( wppaMyPhotoSelection ) {
		myAll = 'my';
	}
	else {
		myAll = 'all';
	}
	photo = jQuery('#wppaphoto-'+myAll+'photo').val();

	if ( ! wppaIsEmpty( photo ) ) {
		id = photo.replace(/\//g,'');
		id = id.split('.');
		id = id[0];
		jQuery('#wppaphoto-photo-preview-tr').show();
		var html = wppaGutenbergBasicPhotoPreview( photo );
		jQuery('#wppaphoto-photo-preview').html(html);
		jQuery('#wppaphoto-'+myAll+'photo').css('color', '#070');
		shortcode = '[photo ' + id;
		if ( jQuery( '#wppaphoto-cache' ).prop( 'checked' ) ) {
			shortcode += ' cache="inf"';
		}
		if ( wppaOnWidgets() ) {
			shortcode += ' widget="photo"';
		}
		shortcode += ']';
	}
	else {
		jQuery('#wppaphoto-'+myAll+'photo').css('color', '#700');
		shortcode = '';
	}

	// Display shortcode preview
	var html = '<input type="text" id="wppaphoto-shortcode-preview" style="background-color:#ddd; width:100%; height:26px;" value="'+shortcode.replace(/"/g, '&quot;')+'" />';
	jQuery( '#wppaphoto-shortcode-preview-container' ).html( html );

//	shortcode = shortcode.replace(/"/g, '&quot;');

	if ( shortcode.length ) {
		jQuery( '#wppa-shortcode-photo-ready' ).show();
	}
	else {
		jQuery( '#wppa-shortcode-photo-ready' ).hide();
	}

	// Is shortcode complete?
	shortcodeOk = ! wppaIsEmpty( photo );

	if ( shortcodeOk ) {

		result = { 	shortcode: shortcode,
					photo: photo,
					}
	}
	else {
		result = { 	shortcode: '',
					photo: '',
					}
	}

	return result;
}

// Display the photo preview
function wppaGutenbergBasicPhotoPreview( id ) {

var html;

	if ( id == '#potd' ) {
		html = wppaNoPreview;
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

function wppaDisplaySelectedFile(filetagid, displaytagid) {

	var theFile = jQuery('#'+filetagid);
	var result 	= theFile[0].files[0].name;

	jQuery('#'+displaytagid).val('Upload '+result);
}

// Get the rendered shrtcode by ajax
function wppaGutenbergGetPhotoShortcodeRendered( shortcode, divId ) {

	// console.log('Fetching shortcode rendered '+shortcode);

	jQuery.ajax( {

		url: 		wppaAjaxUrl,
		data: 		'action=wppa' +
					'&wppa-action=getshortcodedrendered' +
					'&shortcode=' + shortcode +
					'&moccur=' + wppaPhotoDialogCounter,
		async: 		true,
		type: 		'GET',
		timeout: 	10000,
		beforeSend: function( xhr ) {
					},
		success: 	function( result, status, xhr ) {
						jQuery( '#' + divId ).html( '<div id="wppa-container-'+wppaPhotoDialogCounter+'" style="clear:both;" ></div>' + result + '<div style="clear:both;" ></div>' );
					},
		error: 		function( xhr, status, error ) {
						wppaConsoleLog( 'Get gutenberg get shortcode rendered failed. Error = ' + error + ', status = ' + status );
					},
		complete: 	function( xhr, status, newurl ) {

					}
	} );
}