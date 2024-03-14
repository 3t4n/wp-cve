export default class HttpService {
	/* @ngInject */
	constructor( $http, $httpParamSerializerJQLike ) {
		this._$http = $http;
		this._$httpParamSerializerJQLike = $httpParamSerializerJQLike;
	}

	post( data ) {
		return this._$http({
			method: 'POST',
			url: ajaxurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			data: this._$httpParamSerializerJQLike( data ),
		})
		.then( ( data ) => {
			return data = data.data;
		});
	}

	get( data ) {
		return this._$http({
			method: 'GET',
			url: ajaxurl,
			params: data,
		})
		.then( ( data ) => {
			return data = data.data;
		});
	}
	parseQueryString( query ) {
		var obj = {};

		if ( 'string' != typeof query ) 
			return obj;

		var vars = query.split( '&' );

		for ( var i = 0; i < vars.length; i++ ) {
			var pair = vars[i].split( '=' );

			var key = decodeURIComponent( pair[0] );
			var val = decodeURIComponent( pair[1] );

			obj[key] = val;
		};

		return obj;
	}
	
	getParameterByName(name, string) {
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),

		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");

		results = regex.exec(string);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
}