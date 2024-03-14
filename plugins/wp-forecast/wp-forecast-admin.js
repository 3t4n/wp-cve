/**
 * Javascript for search and check dialog in admin
 *
 * @package wp-forecast
 */

/* get the data for the new location */
function wpf_search()
{
	var searchterm = document.getElementById( "searchloc" ).value;
	var language   = document.getElementById( "wpf_search_language" ).value;
	var wid        = document.getElementById( "wpfcid" ).value;
	var nonce      = document.getElementById( "wpf_nonce_3" ).value;

	jQuery.ajax(
		{
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'wpf_search_ajax', searchterm: searchterm, language: language, wpfcid: wid, wpf_nonce_3: nonce },
			success: function (data, textStatus, XMLHttpRequest) {
				jQuery( "div#search_results" ).html( data );
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert( errorThrown );
			}
		}
	);
}

function wpf_set_loc( name, lat, lon ) {
	document.getElementById( "location" ).value = name.trim();
	document.getElementById( "locname" ).value = name.trim();
	document.getElementById( "loclatitude" ).value = lat;
	document.getElementById( "loclongitude" ).value = lon;
	document.getElementById( "searchloc" ).value = '';
	jQuery( "div#search_results" ).html( '' );
	tb_remove();
}

/* get the data from the connection test */
function wpf_check()
{
	var wprovider = document.getElementById( "wprovider" ).value;
	var nonce = document.getElementById( "wpf_nonce_3" ).value;
	jQuery( "div#check_log" ).html( '' );

	jQuery.ajax(
		{
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'wpf_check_ajax', provider : wprovider, wpf_nonce_3: nonce },
			success: function (data, textStatus, XMLHttpRequest) {
				jQuery( "div#check_log" ).html( data );
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert( errorThrown );
			}
		}
	);
}
