class SocialTabs {
	/* @ngInject */
	constructor( $scope, accountsService ) {
		this._accountsService = accountsService;

		$scope.watchers       = this.global.watchers;
		$scope.globalSettings = this.global.globalSettings;

		$scope.$watch( 'watchers.updateEnabledAccounts', this._updateTabsList.bind( this ) );
		$scope.$watch( 'globalSettings.compose_media',   this._updateTabsList.bind( this ) );
	}

	_setTabs() {
		let enabledTypes = this._accountsService.getEnabledTypes();
		let socials      = [];

		angular.forEach( this.global.socialData, ( social ) => {
			if ( -1 === enabledTypes.indexOf( social.type ) )
				return;
			socials.push( social );
		});

		this.socials = socials;
	}

	_setActiveTab() {
		this.firstTab = this.socials[0].type;

		if ( ! this.global.activeSocialTab ) {
			this.global.activeSocialTab = this.firstTab;
			return;
		}

		for ( var i = 0; i < this.socials.length; i++ ) {
			let social = this.socials[i];

			if ( social.type == this.global.activeSocialTab ) 
				return;
		} 

		this.global.activeSocialTab = this.firstTab;
	}
	_updateTabsList() {
		this._setTabs();
		this._setActiveTab();
	}

	getFilteredSocials() {
		return this.socials;
	}

	activateTab( social ) {
		this.global.activeSocialTab = social.type;
	}

	_mbActivateFirstTab( social ) {
		if ( social.type != this.global.activeSocialTab ) 
			return;

		this.activateTab( this.firstTab );
	}

	setActiveClass( type ) {
		if ( type.type ) 
			type = type.type;

		return this.global.activeSocialTab == type ? 'active' : '';
	}
}

export default {
	bindings: {
		global: '='
	},
	template:   sfPostFormTmpls.socialTabs,
	controller: SocialTabs,
}