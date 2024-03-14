/* wppa-tinymce-photo-front.js
* Pachkage: wp-photo-album-plus
*
*
* Version 8.5.03.002
*
*/

// Add the wppa button to the mce editor
tinymce.PluginManager.add('wppaphoto', function(editor, url) {

		function openWppaPhotoShortcodeGenerator() {


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

										if ( ! wppaMyPhotoSelection ) {
											wppaMyPhotoSelection=true;
											jQuery('#wppaphoto-allphoto-tr').hide();
											jQuery('#wppaphoto-myphoto-tr').show();
											jQuery('#wppaphoto-photo-preview').html('');
											jQuery('.wppa-photo-select-item').prop('selected',false);
											jQuery('.wppa-photo-select-item-first').prop('selected',true);
										}
									},
						open: 		function(event,ui) {
										wppaPhotoEvaluate();
									}
						};

			jQuery( "#wppaphoto-form" ).dialog(opt).dialog( "open" );

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
												});

			jQuery( '.ui-dialog-title' ).css( {
													position: 	'absolute',
													top: 		'15px',
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

		editor.addButton('wppa_photo_button', {
			image: wppaImageDirectory+'camera32.png',
			tooltip: 'WPPA+ Insert photo',
			onclick: openWppaPhotoShortcodeGenerator
		});

});

// executes this when the DOM is ready
jQuery(function(){


	// creates a form to be displayed everytime the button is clicked
	var xmlhttp;
	if ( window.XMLHttpRequest ) {		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {								// code for IE6, IE5
		xmlhttp=new ActiveXObject( "Microsoft.XMLHTTP" );
	}

	// wppa-ajax.php calls wppa_make_tinymce_dialog(); which is located in wppa-tinymce.php
	var url = wppaAjaxUrl+'?action=wppa&wppa-action=tinymcephotodialogfront';

	xmlhttp.onreadystatechange=function() {
		if  (xmlhttp.readyState == 4 && xmlhttp.status!=404 ) {
			var formtext = wppaEntityDecode(xmlhttp.responseText);

			var form = jQuery(formtext);

			var table = form.find('table');
			form.appendTo('body').hide();

			// handles the click event of the submit button
			form.find('#wppaphoto-submit').click(function(){

				// Get the shortcode from the preview/edit box
				newShortcode = jQuery( '#wppaphoto-shortcode-preview' ).val();

				// Filter
				newShortcode = newShortcode.replace(/&quot;/g, '"');

				// inserts the shortcode into the active editor
				tinyMCE.activeEditor.execCommand('mceInsertContent', 0, newShortcode);

				// Switch back to own photos only
				if ( ! wppaMyPhotoSelection ) {

					wppaMyPhotoSelection=true;

					jQuery('#wppaphoto-allphoto-tr').hide();
					jQuery('#wppaphoto-myphoto-tr').show();
					jQuery('#wppaphoto-photo-preview').html('');

					jQuery('.wppa-photo-select-item').prop('selected',false);
					jQuery('.wppa-photo-select-item-first').prop('selected',true);
					jQuery('#wppaphoto-myphoto').val('');

				}

				// closes Dialog box
				jQuery( "#wppaphoto-form" ).dialog( "close" );
			});

			// Upload script
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
							jQuery("#percent").html(__('Processing...', 'wp-photo-album-plus' ));
						}
					},
					success: function() {
						jQuery("#bar").width("100%");
						jQuery("#percent").html(__('Done!', 'wp-photo-album-plus' ));
					},
					complete: function(response) {

						var resparr = response.responseText.split( "||" );

						// Non fatal error uploading?
						if ( resparr.length == 1 ) {
							jQuery("#message").html( '<span style="font-size: 10px;" >'+resparr[0]+'</span>' );
						}
						else {
							jQuery( "#wppaphoto-myphoto" ).html( wppaEntityDecode( resparr[2] ) );
						}
						wppaPhotoEvaluate();

					},
					error: function() {
						jQuery("#message").html( '<span style="color: red;" >'+__( 'ERROR: unable to upload files.', 'wp-photo-album-plus' )+'</span>' );
					}
				};
				jQuery("#wppa-uplform").ajaxForm(options);
			});
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();

});

var wppaMyPhotoSelection = true;
function wppaPhotoEvaluate() {

	// Assume shortcode complete
	var shortcodeOk = true;
	var shortcode;
	var myAll;

	// Photo
	if ( wppaMyPhotoSelection ) {
		myAll = 'my';
	}
	else {
		myAll = 'all';
	}
	photo = jQuery('#wppaphoto-'+myAll+'photo').val();

	// Make shortcode
	if ( ! wppaIsEmpty( photo ) ) {

		// Output type
		switch ( wppaOutputType ) {

			// Shortcode
			case 'shortcode':
				id = photo.replace(/\//g,'');
				id = id.split('.');
				id = id[0];
				shortcode = '[photo' + ' ' + id + ']';
				break;

			// HTML for a single image
			case 'html':
				if ( photo.indexOf('xxx') != -1 ) {
					shortcode = 'Videos are not supported here';
				}
				else {
					var temp = wppaShortcodeTemplateId;
					var template = wppaShortcodeTemplate;

					// Global replace template photo id by our photo id
					shortcode = template.split(temp).join(photo);
				}
				break;

			// IMG tag only
			case 'img':

				if ( photo.indexOf('xxx') != -1 ) {
					var idv = photo.replace('xxx', '');
					shortcode = '<video preload="metadata" style="width:100%;height:auto;" controls >'+
									'<source src="'+wppaPhotoDirectory+idv+'mp4" type="video/mp4">'+
									'<source src="'+wppaPhotoDirectory+idv+'ogg" type="video/ogg">'+
									'<source src="'+wppaPhotoDirectory+idv+'ogv" type="video/ogg">'+
									'<source src="'+wppaPhotoDirectory+idv+'webm" type="video/webm">'+
								'</video>';
				}
				else {
					shortcode = '<img src="' + wppaPhotoDirectory + photo + '" />';
				}
				break;

			default:

				shortcode = 'Unimplemented output type';
				break;
		}

		jQuery('#wppaphoto-'+myAll+'photo').css('color', '#070');
		shortcode = shortcode.replace(/"/g, '&quot;');

		jQuery('#wppaphoto-photo-preview-tr').show();
		wppaTinyMceBasicPhotoPreview( photo );

	}
	else {
		jQuery('#wppaphoto-'+myAll+'photo').css('color', '#700');
		shortcode = '';
	}

	// Display shortcode
	var html = '<input type="text" id="wppaphoto-shortcode-preview" style="background-color:#ddd; width:100%; height:26px;" value="'+shortcode+'" />';
	jQuery( '#wppaphoto-shortcode-preview-container' ).html( html );

	// Is shortcode complete?
	shortcodeOk = ! wppaIsEmpty( photo );

	// Display the right button
	if ( shortcodeOk ) {
		jQuery('#wppaphoto-submit').show();
		jQuery('#wppaphoto-submit-notok').hide();
	}
	else {
		jQuery('#wppaphoto-submit').hide();
		jQuery('#wppaphoto-submit-notok').show();
	}
}

function wppaTinyMceBasicPhotoPreview( id ) {


	// Pre-clear
	jQuery('#wppaphoto-photo-preview').html('');

	// No preview for photo of the day
	if ( id == '#potd' ) {
		jQuery('#wppaphoto-photo-preview').html(__('No Preview available', 'wp-photo-album-plus' )); 	// No preview
	}

	// Video?
	else if ( id.indexOf('xxx') != -1 ) {
		var idv = id.replace('xxx', '');
		jQuery('#wppaphoto-photo-preview').html('<video preload="metadata" style="max-width:400px; max-height:300px; margin-top:3px;" controls>'+
													'<source src="'+wppaPhotoDirectory+idv+'mp4" type="video/mp4">'+
													'<source src="'+wppaPhotoDirectory+idv+'ogg" type="video/ogg">'+
													'<source src="'+wppaPhotoDirectory+idv+'ogv" type="video/ogg">'+
													'<source src="'+wppaPhotoDirectory+idv+'webm" type="video/webm">'+
												'</video>');
	}

	// Photo
	else {
		jQuery('#wppaphoto-photo-preview').html('<img src="'+wppaPhotoDirectory+id+'" style="max-width:400px; max-height:300px;" />');
	}
}

function wppaDisplaySelectedFile(filetagid, displaytagid) {

	var theFile = jQuery('#'+filetagid);
	var result 	= theFile[0].files[0].name;

	jQuery('#'+displaytagid).val('Upload '+result);
}

