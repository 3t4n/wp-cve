import MessageCommon from './message.class';

class MessagePinterest extends MessageCommon {
	/* @ngInject */
	constructor( $timeout, $scope, fieldService ) {
		super();

		this._$scope       = $scope;
		this._$timeout     = $timeout;
		this._fieldService = fieldService;

		this.initScopeWatch();
	}
}

export default {
	bindings: {
		index: '<',
		message: '<',
		social: '<',
		global: '='
	},
	template: sfPostFormTmpls.messagePinterest,
	controller: MessagePinterest
}