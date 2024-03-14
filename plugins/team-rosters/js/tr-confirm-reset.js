/*----------------------------------------------------------------------------
 * tr-confirm-reset.js
 *  Enables confirmation (are you sure?) window when resetting defaults
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2017-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *--------------------------------------------------------------------------*/

function tr_confirm_reset_defaults( ) {
	var answer = confirm("Reset the default settings?");
	if ( answer ){
		//alert( "Aye-aye, it shall be so. ");
		document.getElementById('reset_btn').value = "Resetting Defaults";
	}
	else{
		//alert("Nevermind");
		document.getElementById('reset_btn').value = "Cancelling Reset";
		//document.getElementById('target')Event.preventDefault();
	}
	
}