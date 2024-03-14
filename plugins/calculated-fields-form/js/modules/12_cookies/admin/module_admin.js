fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'cookies' ] = {
	'tutorial' : 'https://cff.dwbooster.com/documentation#cookies-operations-module',
	'toolbars'		: {
		'cookies' : {
			'label' : 'Cookies Operations',
			'buttons' : [
                {
                    "value" : "CFFSETCOOKIE",
                    "code" : "CFFSETCOOKIE(",
                    "tip" : "<p><strong>CFFSETCOOKIE(cookie name, cookie value, expiration days)</strong></p><p>Creates a cookie with the name and values passed as the first and second parameter, respectively, which expires after the days interval passed as the third parameter of the operation.</p>"
                },
                {
                    "value" : "CFFGETCOOKIE",
                    "code" : "CFFGETCOOKIE(",
                    "tip" : "<p><strong>CFFGETCOOKIE(cookie name)</strong></p><p>Returns the cookie value or null.</p>"
                },
                {
                    "value" : "CFFCHECKCOOKIE",
                    "code" : "CFFCHECKCOOKIE(",
                    "tip" : "<p><strong>CFFCHECKCOOKIE(cookie name)</strong></p><p>Returns true or false if the cookie exists or not.</p>"
                },
				{
                    "value" : "CFFDELETECOOKIE",
                    "code" : "CFFDELETECOOKIE(",
                    "tip" : "<p><strong>CFFDELETECOOKIE(cookie name)</strong></p><p>Overwrite the cookie with an expired cookie.</strong></p>"
                },
            ]
		}
	}
};