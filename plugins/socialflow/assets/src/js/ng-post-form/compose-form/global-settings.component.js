class GlobalSettings {
	/* @ngInject */
	constructor( $scope, commonService, $timeout, cacheService, accountsService ) {
		this._cacheService    = cacheService;
		this._commonService   = commonService;
		this._accountsService = accountsService;

		this._$scope   = $scope;
		this._$timeout = $timeout;

		this.post     = this.global.post;
		this.settings = this.global.globalSettings;
	}

	$onInit() {

		if ( this._cacheService.isAjax() )
			return;


		let $body        = angular.element( 'body' );
		let $postContent = this._commonService.getPostContent();
		let $postTitle   = this._commonService.getPostTitle();

		$postContent.on( 'change keyup', this._doAutocompleteKeyUp.bind( this ) );
		$postTitle.on(   'change keyup', this._doAutocompleteKeyUp.bind( this ) );

		this._$timeout( this._doAutocompleteOnLoad.bind( this ) );
	}

	clickAutocomple( e, confirmText ) {
		e.preventDefault();

		if ( 1 == this.settings.disable_autcomplete ) {
			if ( !confirm( confirmText ) ) 
				return;
		};

		this._updatePostData( 'click-button' );
	}

	_updatePostData( status ) {
			let title   = this.post.title;
			let content = this._commonService.getPostContentValue();

			if ( null === content || undefined === content)
				content = this.post.content;


			if ( ! this._cacheService.isAjax() )
				title = this._commonService.getPostTitleValue();

			this.post.title   = title;
			this.post.content = this._commonService.cleanText( content );


			this.global.watchers.toggle( 'autocompleteTrigger' );
			this.post.autocompleteStatus  = status;
	}

	_doAutocompleteOnLoad() {
		this._doAutocomplete( 'onload' );
	}

	_doAutocompleteKeyUp() {
		this._doAutocomplete( 'auto-update' );
	}

	_doAutocomplete( status ) {
		if ( 1 == this.settings.disable_autcomplete ) 
			return;

		this._updatePostData( status );
		this._$scope.$apply();
	}

	onChangeMediaCompose() {
		this._$timeout( () => this._$scope.$apply() );
	}

	disableComposeMedia() {
		let enabledTypes = this._accountsService.getEnabledTypes();

		if ( enabledTypes.length > 1 ) 
			return false;

		if ( -1 === enabledTypes.indexOf( 'linkedin' ) ) 
			return false;

		this.settings.compose_media = false;

		return true;
	}
}

export default {
	bindings: {
		global: '='
	},
	template: sfPostFormTmpls.globalSettings,
	controller: GlobalSettings
}
