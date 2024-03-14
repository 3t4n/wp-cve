export default class CacheService {
	/* @ngInject */
	constructor() {
		this.ajax = false;
		this.data = null;
	}

	set( data, key ) {
		if ( key )  {
			if ( !this.data ) 
				this.data = {};

			this.data[ key ] = data;
			return;
		}

		this.data = data;
	}

	get( key ) {
		if ( key )
			return this.data[ key ];
		
		return this.data;
	}

	isset() {
		return !!this.data;
	}

	clear() {
		this.data = null;
	}

	isAjax( toggle ) {
		if ( toggle ) 
			this.ajax = toggle;

		return this.ajax;
	}
}