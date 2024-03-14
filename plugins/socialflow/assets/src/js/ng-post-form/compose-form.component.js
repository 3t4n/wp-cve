import Watchers from './inc/watchers.class';

export default {
	bindings: {
		ajaxData: '<',
	},
	template: sfPostFormTmpls.composeForm,
	controller: function( cacheService, $element ) {
		this.showForm = cacheService.isset();
		this.issetAccounts = false;

		if ( !this.showForm ) {
			if ( 'undefined' == typeof sfPostForm ) 
				return;

			cacheService.set( sfPostForm );
			this.showForm = true;
		}

		this.global = cacheService.get();
		this.global.watchers = new Watchers();

		this.issetAccounts = ( cacheService.get( 'accounts' ).length > 0 );
	}
}