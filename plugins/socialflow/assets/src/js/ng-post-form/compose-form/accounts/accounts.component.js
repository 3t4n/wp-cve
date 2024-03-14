class AccountsList {
	/* @ngInject */
	constructor( $element, $scope, $timeout, fieldService, cacheService ) {
		this.accounts      = this.global.accounts;
		this._fieldService = fieldService;
		this._$scope       = $scope;
		this._$timeout     = $timeout;
		this._$element     = $element;
		this._cacheService = cacheService;

        this._cacheService.set(1 ,'facebook_change_meta');
		$scope.globalSettings = this.global.globalSettings;
	}

	checkSendList( account, socialType ) {
		let counter = 0;
		let metadisabled = 0;
		angular.forEach( this.accounts, ( acc ) => {
			if ( false === acc.send ) 
				return;

			counter++;
		});
		if ( 0 == counter ) 
			account.send = true;

		this._triggerGlobalWatcher();
	}

	_triggerGlobalWatcher() {
		this.global.watchers.toggle( 'updateEnabledAccounts' );
		this._$timeout( () => this._$scope.$apply() );
	}

	getFieldName( account ) {
		return this._fieldService.getAccountName( account );
	}

	getFieldId( account ) {
		return this._fieldService.getAccountId( account );
	}
}

export default {
	bindings: {
		global: '='
	},
	template:   sfPostFormTmpls.accounts,
	controller: AccountsList,
}