export default class AccountsService {
	/* @ngInject */
	constructor( cacheService ) {
		this._cacheService = cacheService;
	}

	getAccounts() {
		return this._cacheService.get( 'accounts' );
	}

	getEnabledTypes() {
		let accounts = this.getAccounts();
		let enabledTypes = [];

		angular.forEach( accounts, ( account ) => {
			if ( ! account.send ) 
				return;

			if ( -1 !== enabledTypes.indexOf( account.type ) ) 
				return;

			enabledTypes.push( account.type );
		});

		return enabledTypes;
	}
}