/**
 * Javascript function for wp-monalisa import dialog.
 *
 * @package wp-monalisa
 */

// Controls reload of parent page.
var importdone = false;

function wpml_import() {
	var smileypak  = document.getElementById( "pakfile" ).value;
	var smileydel  = document.getElementById( "pakdelall" ).value;
	var nonce      = document.getElementById( "wpm_nonce" ).value;

	jQuery.ajax(
		{
			type: 'POST',
			url: ajaxurl,
			data: {	'action': 'wpml_import_ajax', 'smileypak': smileypak, 'smileydel': smileydel, 'nonce': nonce },
			success: function (data, textStatus, XMLHttpRequest) {
				jQuery( "div#message" ).html( data );
				importdone = true;
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert( errorThrown );
			}
		}
	);

}
