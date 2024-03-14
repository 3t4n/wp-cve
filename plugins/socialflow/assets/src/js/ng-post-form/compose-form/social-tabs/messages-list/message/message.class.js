export default class MessageCommon {
	/* @ngInject */
	constructor() {
		this.loading = true;
		if( this.global.post &&  this.global.post.formId) {
			this.contextShow = function () {
				return false;
			}
		} else {
			this.contextShow = function () {
				return true;
			}
		}
	}

	initScopeWatch() {
		this._$scope.watchers = this.global.watchers;
		this._$scope.$watch( 'watchers.autocompleteTrigger', this._doCommonAutocomplete.bind( this ) );
		this._$timeout( this._afterLoad.bind( this ) );
	}
	_afterLoad() {
		this.loading = false;

		if ( 'function' == typeof this.afterLoad )
			this.afterLoad();

		this._doAutocompleteOnLoad();
		this.message.loaded = true;
	}
	_doAutocompleteOnLoad() {
		let status = this.global.post.autocompleteStatus;
		if ( undefined === status || 'onload' == status ) {
			if ( 'attachment' == this.global.post.type ) 
				return this._doCommonAutocomplete();
			if ( false === this.editableAdditional ) {
				this.doAutocomplete();
			}

			return;
		};

		if ( 1 == this.global.globalSettings.disable_autcomplete ) {
			if ( true === this.message.duplicate && true !== this.message.loaded ) 
				return;
		};

		this._doCommonAutocomplete();
	}

	getName( name ) {
		return this._fieldService.getMessageName( this.social, this.index, name );
	}

	getFieldId( name ) {
		return this._fieldService.getMessageId( this.social, this.index, name );
	}

	_doCommonAutocomplete() {
		this.setLoacalValueFromGlobal( 'message', 'title' );

		this.doAutocomplete();
	}
	setLoacalValueFromGlobal( localField, globalField ) {
		if ( true === this.loading )
			return;

		globalField = this.global.post[ globalField ];

		if ( !globalField ) 
			return;

		this.message.fields[ localField ] = globalField;
	}

	doAutocomplete() {}
} 