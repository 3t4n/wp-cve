function update_token() {
	if ( this.readyState === 4 ) {
		if ( this.status >= 200 && this.status < 400 ) {
			var response = JSON.parse( this.responseText );

			var element = document.createElement( 'input' );
			element.type = 'hidden';
			element.name = response.field;
			element.value = response.value;

			var temp = document.getElementById( 'aia_placeholder' );
			var form = temp.form;
			var inputs = form.getElementsByTagName( 'input' );
			var index = Math.floor( Math.random() * ( inputs.length -1 ) );

			inputs[ index ].parentNode.appendChild( element );
			form.removeChild( temp );
		}
	}
}

if ( null !== document.getElementById( 'aia_placeholder' ) ) {
	var data	= 'action=aia_field_update';
	var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject( 'Microsoft.XMLHTTP' );
	request.open( 'POST', AIA.ajaxurl, true );
	request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
	request.onreadystatechange = update_token;
	request.send( data );
}