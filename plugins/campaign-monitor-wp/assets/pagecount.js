/* jshint asi: true */
(function(){
	set_cookie( 'fca_eoi_pagecount', parseInt( get_cookie( 'fca_eoi_pagecount' ) ) + 1 )
	
	function set_cookie( name, value, exdays ) {
		document.cookie = name + "=" + value + ";" + "path=/;"
	}

	function get_cookie( name ) {
		var value = "; " + document.cookie
		var parts = value.split( "; " + name + "=" )
		
		if ( parts.length === 2 ) {
			return parts.pop().split(";").shift()
		} else {
			return 0
		}
	}
})()

