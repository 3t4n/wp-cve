function editEmailBlack( id ) {
	var row = jQuery( '#email-row-' + id );
	var emailContainer = row.find( '.emailText' );
	if ( emailContainer.find( 'input' ).length > 0 ) {
		return false;
	}
	var input = jQuery( '<input />' ).attr( 'type', 'text' ).attr( 'name', 'black_email[]' ).css( 'text-transform', 'lowercase' );
	var input2 = jQuery( '<input />' ).attr( 'type', 'hidden' ).attr( 'name', 'black_id[]' ).attr( 'value', id );
	var inputValue = emailContainer.text();
	var submit = jQuery( '<button />' ).attr( 'type', 'submit' ).attr( 'value', 'edit' ).attr( 'name', 'cmeb_email_black' ).append( 'Apply' );
	emailContainer.empty().append( input.val( inputValue ) ).append( input2.val( id ) ).append( submit );
}
function deleteEmailBlack( id ) {
	var emailContainer = jQuery( '#email-row-' + id + ' .emailText' );
	var emailName = '';
	if ( emailContainer.find( 'input' ).length > 0 ) {
		emailName = emailContainer.find( 'input[name=email]' ).val();
	} else {
		emailName = emailContainer.text();
	}
	if ( confirm( 'Are you sure you want to delete email: ' + emailName + '?' ) ) {
		//jQuery(location).attr('href', url);
		window.location.href = "?page=cmeb_menu&cmeb_email_black=delete&black_id=" + id + "#tab-email-blacklist";
	}
}