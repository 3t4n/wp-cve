fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'location' ] = {
	'tutorial' : 'https://cff.dwbooster.com/documentation#location-module',
	'toolbars'		: {
		'location' : {
			'label' : 'Location Operations',
			'buttons' : [
                {
                    "value" : "COUNTRY",
                    "code" : "COUNTRY(",
                    "tip" : "<p><strong>COUNTRY(timezone/country id)</strong></p><p>It returns an array of country names based on the time zone (Europe/Berlin) or country id (DE). The parameter is optional. If the parameter is empty, the operation returns the visitor country.<br><br> Ex. <strong>COUNTRY();</strong></p>"
                },
                {
                    "value" : "REGION",
                    "code" : "REGION(",
                    "tip" : "<p><strong>REGION(timezone/country id)</strong></p><p>It returns an array of region names based on the time zone (Europe/Berlin) or country id (DE). The parameter is optional. If the parameter is empty, the operation returns the visitor region.<br><br> Ex. <strong>REGION();</strong></p>"
                },
                {
                    "value" : "TIMEZONE",
                    "code" : "TIMEZONE(",
                    "tip" : "<p><strong>TIMEZONE(country id)</strong></p><p>Returns an array of timezones based on country id (MX). The paramater is optional. If the parameter is empty, the operation returns the visitor timezone.<br><br> Ex. <strong>TIMEZONE();</strong></p>"
                },
				{
                    "value" : "TIMEZONEOFFSET",
                    "code" : "TIMEZONEOFFSET()",
                    "tip" : "<p><strong>TIMEZONEOFFSET()</strong></p><p>Returns the browser timezone offset.<br><br> Ex. <strong>TIMEZONEOFFSET();</strong></p>"
                },
            ]
		}
	}
};