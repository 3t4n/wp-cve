(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
'use strict';

var _formInPopup = require('./ng-post-form/form-in-popup.component');

var _formInPopup2 = _interopRequireDefault(_formInPopup);

var _composeForm = require('./ng-post-form/compose-form.component');

var _composeForm2 = _interopRequireDefault(_composeForm);

var _stats = require('./ng-post-form/compose-form/stats.component');

var _stats2 = _interopRequireDefault(_stats);

var _errors = require('./ng-post-form/compose-form/errors.component');

var _errors2 = _interopRequireDefault(_errors);

var _globalSettings = require('./ng-post-form/compose-form/global-settings.component');

var _globalSettings2 = _interopRequireDefault(_globalSettings);

var _composeMedia = require('./ng-post-form/compose-form/compose-media.component');

var _composeMedia2 = _interopRequireDefault(_composeMedia);

var _accounts = require('./ng-post-form/compose-form/accounts/accounts.component');

var _accounts2 = _interopRequireDefault(_accounts);

var _socialTabs = require('./ng-post-form/compose-form/social-tabs/social-tabs.component');

var _socialTabs2 = _interopRequireDefault(_socialTabs);

var _socialTabsList = require('./ng-post-form/compose-form/social-tabs/social-tabs-list.component');

var _socialTabsList2 = _interopRequireDefault(_socialTabsList);

var _messagesList = require('./ng-post-form/compose-form/social-tabs/messages-list/messages-list.component');

var _messagesList2 = _interopRequireDefault(_messagesList);

var _message = require('./ng-post-form/compose-form/social-tabs/messages-list/message/message.component');

var _message2 = _interopRequireDefault(_message);

var _messageTwitter = require('./ng-post-form/compose-form/social-tabs/messages-list/message/message-twitter.component');

var _messageTwitter2 = _interopRequireDefault(_messageTwitter);

var _messagePinterest = require('./ng-post-form/compose-form/social-tabs/messages-list/message/message-pinterest.component');

var _messagePinterest2 = _interopRequireDefault(_messagePinterest);

var _attachments = require('./ng-post-form/compose-form/social-tabs/messages-list/message/attachments/attachments.component');

var _attachments2 = _interopRequireDefault(_attachments);

var _datepicker = require('./ng-post-form/compose-form/social-tabs/messages-list/settings/datepicker.component');

var _datepicker2 = _interopRequireDefault(_datepicker);

var _settingsList = require('./ng-post-form/compose-form/social-tabs/messages-list/settings/settings-list.component');

var _settingsList2 = _interopRequireDefault(_settingsList);

var _setting = require('./ng-post-form/compose-form/social-tabs/messages-list/settings/setting.component');

var _setting2 = _interopRequireDefault(_setting);

var _http = require('./ng-post-form/services/http.service');

var _http2 = _interopRequireDefault(_http);

var _wpMedia = require('./ng-post-form/services/wp-media.service');

var _wpMedia2 = _interopRequireDefault(_wpMedia);

var _postAttachments = require('./ng-post-form/services/post-attachments.service');

var _postAttachments2 = _interopRequireDefault(_postAttachments);

var _common = require('./ng-post-form/services/common.service');

var _common2 = _interopRequireDefault(_common);

var _field = require('./ng-post-form/services/field.service');

var _field2 = _interopRequireDefault(_field);

var _cache = require('./ng-post-form/services/cache.service');

var _cache2 = _interopRequireDefault(_cache);

var _accounts3 = require('./ng-post-form/services/accounts.service');

var _accounts4 = _interopRequireDefault(_accounts3);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

angular.module('sfComposeForm', []).component('formInPopup', _formInPopup2.default).component('composeForm', _composeForm2.default).component('stats', _stats2.default).component('errors', _errors2.default).component('globalSettings', _globalSettings2.default).component('composeMedia', _composeMedia2.default).component('accountsList', _accounts2.default).component('socialTabs', _socialTabs2.default).component('socialTabsList', _socialTabsList2.default).component('messagesList', _messagesList2.default).component('message', _message2.default).component('messageTwitter', _messageTwitter2.default).component('messagePinterest', _messagePinterest2.default).component('messageAttachments', _attachments2.default).component('datePicker', _datepicker2.default).component('advancedSettingsList', _settingsList2.default).component('advancedSetting', _setting2.default).service('httpService', _http2.default).service('WPMediaFrameService', _wpMedia2.default).service('postAttachments', _postAttachments2.default).service('postAttachments', _postAttachments2.default).service('commonService', _common2.default).service('fieldService', _field2.default).service('cacheService', _cache2.default).service('accountsService', _accounts4.default);

},{"./ng-post-form/compose-form.component":2,"./ng-post-form/compose-form/accounts/accounts.component":3,"./ng-post-form/compose-form/compose-media.component":4,"./ng-post-form/compose-form/errors.component":5,"./ng-post-form/compose-form/global-settings.component":6,"./ng-post-form/compose-form/social-tabs/messages-list/message/attachments/attachments.component":8,"./ng-post-form/compose-form/social-tabs/messages-list/message/message-pinterest.component":9,"./ng-post-form/compose-form/social-tabs/messages-list/message/message-twitter.component":10,"./ng-post-form/compose-form/social-tabs/messages-list/message/message.component":12,"./ng-post-form/compose-form/social-tabs/messages-list/messages-list.component":13,"./ng-post-form/compose-form/social-tabs/messages-list/settings/datepicker.component":14,"./ng-post-form/compose-form/social-tabs/messages-list/settings/setting.component":15,"./ng-post-form/compose-form/social-tabs/messages-list/settings/settings-list.component":16,"./ng-post-form/compose-form/social-tabs/social-tabs-list.component":17,"./ng-post-form/compose-form/social-tabs/social-tabs.component":18,"./ng-post-form/compose-form/stats.component":19,"./ng-post-form/form-in-popup.component":20,"./ng-post-form/services/accounts.service":22,"./ng-post-form/services/cache.service":23,"./ng-post-form/services/common.service":24,"./ng-post-form/services/field.service":25,"./ng-post-form/services/http.service":26,"./ng-post-form/services/post-attachments.service":27,"./ng-post-form/services/wp-media.service":28}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _watchers = require('./inc/watchers.class');

var _watchers2 = _interopRequireDefault(_watchers);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = {
	bindings: {
		ajaxData: '<'
	},
	template: sfPostFormTmpls.composeForm,
	controller: function controller(cacheService, $element) {
		this.showForm = cacheService.isset();
		this.issetAccounts = false;

		if (!this.showForm) {
			if ('undefined' == typeof sfPostForm) return;

			cacheService.set(sfPostForm);
			this.showForm = true;
		}

		this.global = cacheService.get();
		this.global.watchers = new _watchers2.default();

		this.issetAccounts = cacheService.get('accounts').length > 0;
	}
};

},{"./inc/watchers.class":21}],3:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var AccountsList = function () {
	/* @ngInject */
	function AccountsList($element, $scope, $timeout, fieldService, cacheService) {
		_classCallCheck(this, AccountsList);

		this.accounts = this.global.accounts;
		this._fieldService = fieldService;
		this._$scope = $scope;
		this._$timeout = $timeout;
		this._$element = $element;
		this._cacheService = cacheService;

		this._cacheService.set(1, 'facebook_change_meta');
		$scope.globalSettings = this.global.globalSettings;
	}

	_createClass(AccountsList, [{
		key: 'checkSendList',
		value: function checkSendList(account, socialType) {
			var counter = 0;
			var metadisabled = 0;
			angular.forEach(this.accounts, function (acc) {
				if (false === acc.send) return;

				counter++;
			});
			if (0 == counter) account.send = true;

			this._triggerGlobalWatcher();
		}
	}, {
		key: '_triggerGlobalWatcher',
		value: function _triggerGlobalWatcher() {
			var _this = this;

			this.global.watchers.toggle('updateEnabledAccounts');
			this._$timeout(function () {
				return _this._$scope.$apply();
			});
		}
	}, {
		key: 'getFieldName',
		value: function getFieldName(account) {
			return this._fieldService.getAccountName(account);
		}
	}, {
		key: 'getFieldId',
		value: function getFieldId(account) {
			return this._fieldService.getAccountId(account);
		}
	}]);

	return AccountsList;
}();

exports.default = {
	bindings: {
		global: '='
	},
	template: sfPostFormTmpls.accounts,
	controller: AccountsList
};

},{}],4:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = {
	bindings: {
		global: '<'
	},
	template: sfPostFormTmpls.messageComposeMedia,
	controller: function controller(WPMediaFrameService, postAttachments) {
		var _this = this;

		this.post = this.global.post;
		this.media = this.global.messageComposeMedia.media;
		this._loadingImg = false;

		this.$onInit = function () {
			angular.element(document).on('sf-set-post-thumbnail', _this._onSetPostThumbnail.bind(_this));
		};

		this.setImage = function (e) {
			e.preventDefault();

			WPMediaFrameService.setAjaxHandler({
				request: _this._imageRequest.bind(_this),
				success: _this._setImageData.bind(_this)
			}).open();
		};

		this.setLoadingClass = function () {
			return true === _this._loadingImg ? 'loading' : '';
		};

		this._imageRequest = function (imageId) {
			_this._loadingImg = true;

			return postAttachments.attachMedia(imageId);
		};

		this._setImageData = function (data) {
			_this.media = data;

			_this._loadingImg = false;
		};

		this._onSetPostThumbnail = function (e, thumbId) {
			if (_this.media && _this.media.medium_thumbnail_url) return;

			postAttachments.attachMedia(thumbId).then(function (data) {

				_this._setImageData(data);
			});
		};
	}
};

},{}],5:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = {
	template: sfPostFormTmpls.errors,
	controller: function controller(cacheService, $sce) {
		this.errors = cacheService.get('errors');
		this.showErrors = this.errors && 0 != this.errors.length;

		this.trustAsHtml = function (text) {
			return $sce.trustAsHtml(text);
		};
	}
};

},{}],6:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var GlobalSettings = function () {
	/* @ngInject */
	function GlobalSettings($scope, commonService, $timeout, cacheService, accountsService) {
		_classCallCheck(this, GlobalSettings);

		this._cacheService = cacheService;
		this._commonService = commonService;
		this._accountsService = accountsService;

		this._$scope = $scope;
		this._$timeout = $timeout;

		this.post = this.global.post;
		this.settings = this.global.globalSettings;
	}

	_createClass(GlobalSettings, [{
		key: '$onInit',
		value: function $onInit() {

			if (this._cacheService.isAjax()) return;

			var $body = angular.element('body');
			var $postContent = this._commonService.getPostContent();
			var $postTitle = this._commonService.getPostTitle();

			$postContent.on('change keyup', this._doAutocompleteKeyUp.bind(this));
			$postTitle.on('change keyup', this._doAutocompleteKeyUp.bind(this));

			this._$timeout(this._doAutocompleteOnLoad.bind(this));
		}
	}, {
		key: 'clickAutocomple',
		value: function clickAutocomple(e, confirmText) {
			e.preventDefault();

			if (1 == this.settings.disable_autcomplete) {
				if (!confirm(confirmText)) return;
			};

			this._updatePostData('click-button');
		}
	}, {
		key: '_updatePostData',
		value: function _updatePostData(status) {
			var title = this.post.title;
			var content = this._commonService.getPostContentValue();

			if (null === content || undefined === content) content = this.post.content;

			if (!this._cacheService.isAjax()) title = this._commonService.getPostTitleValue();

			this.post.title = title;
			this.post.content = this._commonService.cleanText(content);

			this.global.watchers.toggle('autocompleteTrigger');
			this.post.autocompleteStatus = status;
		}
	}, {
		key: '_doAutocompleteOnLoad',
		value: function _doAutocompleteOnLoad() {
			this._doAutocomplete('onload');
		}
	}, {
		key: '_doAutocompleteKeyUp',
		value: function _doAutocompleteKeyUp() {
			this._doAutocomplete('auto-update');
		}
	}, {
		key: '_doAutocomplete',
		value: function _doAutocomplete(status) {
			if (1 == this.settings.disable_autcomplete) return;

			this._updatePostData(status);
			this._$scope.$apply();
		}
	}, {
		key: 'onChangeMediaCompose',
		value: function onChangeMediaCompose() {
			var _this = this;

			this._$timeout(function () {
				return _this._$scope.$apply();
			});
		}
	}, {
		key: 'disableComposeMedia',
		value: function disableComposeMedia() {
			var enabledTypes = this._accountsService.getEnabledTypes();

			if (enabledTypes.length > 1) return false;

			if (-1 === enabledTypes.indexOf('linkedin')) return false;

			this.settings.compose_media = false;

			return true;
		}
	}]);

	return GlobalSettings;
}();

exports.default = {
	bindings: {
		global: '='
	},
	template: sfPostFormTmpls.globalSettings,
	controller: GlobalSettings
};

},{}],7:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var AttachmentsSlider = function () {
	function AttachmentsSlider($parent, message, postAttachments, cacheService, socialType, $scope) {
		_classCallCheck(this, AttachmentsSlider);

		this.posSl = 0;
		this.contentImages = cacheService.get('messageAttachments');
		if (cacheService.get('content_' + socialType)) {
			this.contentImages.push(cacheService.get('content_' + socialType));
		}

		this.contentImagesForSocials = cacheService.get('messageAttachmentsForSocials');
		this.slides = [];
		this.slidesForSocials = [];
		this._message = message;
		this._current = cacheService.get('current_url_' + socialType);
		this._postAttachments = postAttachments;
		this.cacheRemoveKeyThubn = false;
		this._cache = cacheService;
		this.socialType = '';
		if (socialType) {
			this.socialType = socialType;
		}
		this.scope = $scope;
		this._post = this._cache.get('post');
		this.pos = 0;
		this._customImage = this._current;
		this._customImageForSocials = '';
		this.contentImagesForSocialsBody = [];
		this.mediaUrl = '';
		this._postThumbnail = this._post.thumbnail;

		this.$parent = $parent;

		this.stopLoading();
		if (this.socialType == 'facebook') {
			this._cache.set(this.contentImages, 'thumbnail_old_facebook');
			this._cache.set(this._current, 'current_old_facebook');
			this._cache.set(this._customImage, 'custom_image_old_facebook');
		}
		angular.element(document).on('sf-get-slides', this._onGetContentImages.bind(this));
		angular.element(document).on('sf-update-post-thumbnail', this._onUpdatePostThumbnail.bind(this));
		this.init();
	}

	_createClass(AttachmentsSlider, [{
		key: '_onUpdatePostThumbnail',
		value: function _onUpdatePostThumbnail(e, thumbUrl) {
			if (thumbUrl) {
				this.addPostThumbnail(thumbUrl);
				this.setThumbnailCurrentPosition();
				this.init();
				this.initForSocials(this.socialType);
			}
		}
	}, {
		key: 'addCustomImage',
		value: function addCustomImage(url) {
			this._customImage = url;
		}
	}, {
		key: 'addCustomImageForSocials',
		value: function addCustomImageForSocials(url) {
			this._customImageForSocials = url;
		}
	}, {
		key: 'addPostThumbnail',
		value: function addPostThumbnail(url) {
			this._post.thumbnail = url;
			this._postThumbnail = url;
			if (this.contentImagesForSocialsBody.indexOf(this.mediaUrl) != -1) {
				this.mediaUrl = url;
			}
		}
	}, {
		key: 'addPostThumbnailForSocials',
		value: function addPostThumbnailForSocials(url, socialType) {
			this._cache.set(this._postThumbnail, 'thumbnail_for_socials_' + socialType);
			var posLoad = this._cache.get('compose_media_pos_' + socialType);
			if (socialType != 'linkedin') {

				if (posLoad !== '') {
					posLoad = posLoad + 1;
					this._cache.set(posLoad, 'compose_media_pos_' + socialType);
				}
				;
				this._cache.get('message_attachments_for_socials_' + socialType).push(this._postThumbnail);
				this.slidesForSocials.push(this._postThumbnail);
			}
		}
	}, {
		key: 'removePostThumbnail',
		value: function removePostThumbnail() {
			if (!this._postThumbnail) return;

			var index = this.slides.indexOf(this._postThumbnail);

			if (-1 === index) return;

			this.slides.splice(index, 1);

			this.addPostThumbnail('');
		}
	}, {
		key: 'removePostThumbnailForSocials',
		value: function removePostThumbnailForSocials(socialType) {
			if (!this._postThumbnail) return;

			var index = this._cache.get('message_attachments_for_socials_' + socialType).indexOf(this._postThumbnail);
			this._cache.get('message_attachments_for_socials_' + socialType).splice(index, 1);
			var slides = this._cache.get('message_attachments_for_socials_' + socialType);
			if (-1 === index) return;

			if (slides.length) {
				if (slides[index]) {
					this.mediaUrl = slides[index];
				} else {
					this.mediaUrl = slides[index - 1];
				}
			} else {
				this.mediaUrl = '';
			}
		}
	}, {
		key: 'setThumbnailCurrentPosition',
		value: function setThumbnailCurrentPosition() {
			this._current = this._postThumbnail;
		}
	}, {
		key: 'setCustomImageCurrentPosition',
		value: function setCustomImageCurrentPosition() {
			this._current = this._customImage;
		}
	}, {
		key: 'setCustomImageCurrentPositionForSocials',
		value: function setCustomImageCurrentPositionForSocials(url) {
			this.mediaUrl = url;
		}
	}, {
		key: '_compileSlides',
		value: function _compileSlides() {
			var slides = angular.copy(this.contentImages);
			if (this._customImage && -1 === slides.indexOf(this._customImage)) {
				slides.push(this._customImage);
			}

			if (this._postThumbnail && -1 === slides.indexOf(this._postThumbnail)) {
				slides.push(this._postThumbnail);
			}

			this.slides = slides;
		}
	}, {
		key: '_compileSlidesForSocials',
		value: function _compileSlidesForSocials(socialType) {
			var slides = angular.copy(this._cache.get('message_attachments_for_socials_' + socialType));
			var posLoad = void 0;
			if (typeof slides == 'undefined') {
				slides = [];
				this._cache.set([], 'message_attachments_for_socials_' + socialType);
			}
			if (this._postThumbnail && -1 === slides.indexOf(this._postThumbnail)) {
				this._cache.set(this._postThumbnail, 'thumbnail_for_socials_' + socialType);
				if (socialType == 'linkedin') {
					posLoad = '';
				}
				if (posLoad !== '') {
					posLoad = posLoad + 1;
					this._cache.set(posLoad, 'compose_media_pos_' + socialType);
				}
				slides.push(this._postThumbnail);
				this._cache.set(slides, 'message_attachments_for_socials_' + socialType);
				this.slidesForSocials = slides;
				posLoad = slides.indexOf(this.mediaUrl);
				if (posLoad === -1) {
					posLoad = 0;
				}
				if (this.contentImagesForSocialsBody.indexOf(this.mediaUrl) != -1) {
					this.mediaUrl = this._postThumbnail;
					posLoad = slides.indexOf(this._postThumbnail);
				} else {
					posLoad = slides.indexOf(this.mediaUrl);
				}
				this.pos = posLoad;
				this.setCustomImageCurrentPositionForSocials(this.mediaUrl);
			}

			if (socialType != 'linkedin') {
				var noRemovedElement = [];
				if (this.cacheRemoveKeyThubn !== false) {
					slides.splice(this.cacheRemoveKeyThubn, 1);
					this._cache.set(slides, 'message_attachments_for_socials_' + socialType);
					if (this.cacheRemoveKeyThubn - 1 > -1) {
						this.mediaUrl = slides[this.cacheRemoveKeyThubn - 1];
						this.mediaUrl = slides[this.cacheRemoveKeyThubn - 1];
						this._cache.set(this.cacheRemoveKeyThubn - 1, 'compose_media_pos_' + socialType);
						this.pos = posLoad = this.cacheRemoveKeyThubn - 1;
					} else {
						this.mediaUrl = slides[0];
						this._cache.set(0, 'compose_media_pos_' + socialType);
						this.pos = posLoad = 0;
					}
					this.cacheRemoveKeyThubn = false;
					this.slidesForSocials.splice(this.cacheRemoveKeyThubn, 1);
					this._cache.set(slides, 'message_attachments_for_socials_' + socialType);
				}
			}
			this.pos = slides.length - 1;
			if (this.pos === -1) {
				this.pos = 0;
			}

			if (posLoad !== false && posLoad != '' && typeof posLoad != 'undefined') {
				this.pos = parseInt(posLoad);
				this._cache.set(false, 'sf_position_' + socialType);
			}
			this.slidesForSocials = slides;
			this.pos = slides.indexOf(this.mediaUrl);
			if (this.pos == -1) {
				this.pos = 0;
			}
			this.mediaUrl = slides[slides.indexOf(this.mediaUrl)];
		}
	}, {
		key: 'init',
		value: function init() {
			this._compileSlides();

			if (0 == this.slides.length) return;

			var start = this._getCurrentPosition();

			this._setCurrentByPosition(start);
		}
	}, {
		key: 'initForSocials',
		value: function initForSocials(socialType) {
			this._compileSlidesForSocials(socialType);
			if (0 == this.slidesForSocials.length) return;
			var start = this._getCurrentPositionForSocials(socialType);
			if (!start) {
				if (!this._cache.get('compose_media_pos_' + socialType)) start = this.pos;else start = this._cache.get('compose_media_pos_' + socialType);
			}
			this._setCurrentByPositionForSocials(start);
		}
	}, {
		key: 'refreshSlides',
		value: function refreshSlides(e) {
			e.preventDefault();

			this.startLoading();
			this._postAttachments.findImagesFromPostContent();
		}
	}, {
		key: 'showSlide',
		value: function showSlide(index, slides, type, currentUrl) {
			if (this._cache.get('current_url_' + type)) {
				this._current = this._cache.get('current_url_' + type);
				this._message.fields['image'] = this._cache.get('current_url_' + type);
			}
			return index == this._getCurrentPosition();
		}
	}, {
		key: 'showSlideForSicials',
		value: function showSlideForSicials(index, socialType, currentUrl) {
			return index === parseInt(this._getCurrentPositionForSocials(socialType));
		}
	}, {
		key: 'prev',
		value: function prev(type) {
			var pos = this._getCurrentPosition();

			if (0 == pos) pos = this.slides.length;

			pos--;
			if (type != 'twitter') {
				var t = this.slides[pos];
				console.log(this._current);
				this._cache.set(this.slides[pos], 'current_url_' + type);
			}
			this._setCurrentByPosition(pos);
		}
	}, {
		key: 'next',
		value: function next(type) {
			var pos = this._getCurrentPosition();

			pos++;

			if (pos == this.slides.length) pos = 0;

			if (type != 'twitter') {
				this._cache.set(this.slides[pos], 'current_url_' + type);
			}

			this._setCurrentByPosition(pos);
		}
	}, {
		key: 'prevForSocial',
		value: function prevForSocial(socialType) {

			var pos = this._getCurrentPositionForSocials(socialType);

			if (0 == pos) pos = this.getsLiderForSocial().length;
			pos--;
			this._setCurrentByPositionForSocials(pos);
		}
	}, {
		key: 'nextForSocial',
		value: function nextForSocial(socialType) {
			var pos = this._getCurrentPositionForSocials(socialType);
			pos++;
			if (this.slidesForSocials.length == pos) {
				pos = 0;
			}
			this._setCurrentByPositionForSocials(pos);
		}
	}, {
		key: 'setLoadingClass',
		value: function setLoadingClass() {
			return this._loading == true ? 'loading' : '';
		}
	}, {
		key: '_setCurrentByPosition',
		value: function _setCurrentByPosition(pos) {
			var src = this.slides[pos];
			this._message.fields['image'] = src;
			this._current = src;
		}
	}, {
		key: 'getsLiderForSocial',
		value: function getsLiderForSocial() {
			return this._cache.get('message_attachments_for_socials_' + this.socialType);
		}
	}, {
		key: '_setCurrentByPositionForSocials',
		value: function _setCurrentByPositionForSocials(pos) {
			var src = this.slidesForSocials[pos];
			this._message.fields['image'] = src;
			this.mediaUrl = src;
			this.pos = pos;
		}
	}, {
		key: '_getCurrentPosition',
		value: function _getCurrentPosition() {
			if (this.slides.length === 0 && this._current) {
				this.slides.push(this);
			}
			var pos = this.slides.indexOf(this._current);
			this.posSl = -1 === pos ? 0 : pos;
			return -1 === pos ? 0 : pos;
		}
	}, {
		key: '_getCurrentPositionForSocials',
		value: function _getCurrentPositionForSocials(socialType) {
			if (this.slidesForSocials.length === 0 && this.mediaUrl) {
				this.slidesForSocials.push(this);
			}
			var pos = this.slidesForSocials.indexOf(this.mediaUrl);

			return -1 === pos ? 0 : pos;
		}
	}, {
		key: '_onGetContentImages',
		value: function _onGetContentImages(e, images) {
			e.stopPropagation();
			this.contentImages = images;
			console.log(images);
			if (this.socialType && this.socialType != 'linkedin') {

				this.pos = images.length - 1;
				var thisImage = this._cache.get('message_attachments_for_socials_' + this.socialType);
				thisImage = thisImage.concat(images[images.length - 1]);
				if (typeof this.mediaUrl == 'undefined' || this.mediaUrl.length === 0) {
					this.mediaUrl = thisImage[thisImage.length - 1];
				} else if (images.indexOf(this.mediaUrl) != -1) {
					this.mediaUrl = thisImage[thisImage.length - 1];
				}
				this._cache.set(thisImage.indexOf(this.mediaUrl), 'compose_media_pos_' + this.socialType);
				this._cache.set(thisImage, 'message_attachments_for_socials_' + this.socialType);
				this.contentImagesForSocialsBody = images;
				this.initForSocials(this.socialType);
			}
			this._cache.set(images, 'messageAttachments');
			if (images.length != 0) {
				this._current = images[images.length - 1];
			}
			this.init();
			this.stopLoading();
		}
	}, {
		key: 'startLoading',
		value: function startLoading() {
			this._loading = true;
		}
	}, {
		key: 'stopLoading',
		value: function stopLoading() {
			this._loading = false;
		}
	}]);

	return AttachmentsSlider;
}();

exports.default = AttachmentsSlider;

},{}],8:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _attachmentsSlider = require('./attachments-slider.class');

var _attachmentsSlider2 = _interopRequireDefault(_attachmentsSlider);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var MessageAttachments = function () {
	/* @ngInject */
	function MessageAttachments($element, WPMediaFrameService, postAttachments, cacheService, fieldService, $scope) {
		_classCallCheck(this, MessageAttachments);

		this._WPMediaFrameService = WPMediaFrameService;
		this._fieldService = fieldService;
		this._$scope = $scope;
		this._postAttachments = postAttachments;
		this.cache = cacheService;
		this.showHideImageForm = function (socialType) {
			if (socialType == 'linkedin') {
				return 1;
			}
			if (this.cache.get('globalSettings')['show_' + socialType]) return this.cache.get('globalSettings')['show_' + socialType];
		};
		this.slider = new _attachmentsSlider2.default($element, this.message, postAttachments, cacheService);
	}

	_createClass(MessageAttachments, [{
		key: 'facebookMetaDisabled',
		value: function facebookMetaDisabled(socialType) {

			if (socialType != 'facebook' || this.cache.get('facebook_change_meta')) {
				return false;
			}

			if (this.cache.get('thumbnail_old_facebook').length > 0) {
				this.slider.contentImages = this.cache.get('thumbnail_old_facebook');
				this.slider._current = this.cache.get('current_old_facebook');
				this.slider.init();
			}
			if (this.cache.get('custom_image_old_facebook')) {
				this.slider._customImage = this.cache.get('custom_image_old_facebook');
				this.slider._current = this.cache.get('current_old_facebook');
				this.slider.init();
			} else if (this.cache.get('current_old_facebook')) {
				console.log(this.cache.get('current_old_facebook'));
				this.slider._current = this.cache.get('current_old_facebook');
				this.slider._customImage = this.slider._current;
				this.slider.init();
			}
			return true;
		}
	}, {
		key: '$onInit',
		value: function $onInit() {
			if (this.message.fields['custom_image']) {
				this.slider.addCustomImage(this.message.fields['custom_image']);
			}

			this.slider.init();
			angular.element(document).on('sf-update-post-thumbnail', this._onUpdatePostThumbnail.bind(this));
		}
	}, {
		key: 'setCustomImage',
		value: function setCustomImage(e) {
			e.preventDefault();
			this._WPMediaFrameService.setHandler(this._attachMediaImage.bind(this)).open();
		}
	}, {
		key: '_attachMediaImage',
		value: function _attachMediaImage(data) {
			this.message.fields['custom_image'] = data.url;
			this.slider.addCustomImage(data.url);
			this.setCustomImageData(data);
			this.slider.setCustomImageCurrentPosition();
			this.slider.init();
			this.slider.initForSocials();
			this._$scope.$apply();
		}
	}, {
		key: 'getName',
		value: function getName(name) {
			return this._fieldService.getMessageName(this.social, this.index, name);
		}
	}, {
		key: 'getFieldId',
		value: function getFieldId(name) {
			return this._fieldService.getMessageId(this.social, this.index, name);
		}
	}, {
		key: '_onUpdatePostThumbnail',
		value: function _onUpdatePostThumbnail(e, thumbUrl) {
			if (!thumbUrl) {
				this.slider.removePostThumbnailForSocials(this.social.type);
				this.slider.removePostThumbnail();
				this.slider.init();
				this.slider.initForSocials(this.social.type);
				this._$scope.$apply();
			}
		}
	}, {
		key: 'setCustomImageData',
		value: function setCustomImageData(data) {
			this.message.fields['custom_image'] = data.medium_thumbnail_url;
			this.message.fields['custom_image_filename'] = data.filename;
			this.message.fields['is_custom_image'] = 1;
			this.message.fields['image'] = data.medium_thumbnail_url;
		}
	}]);

	return MessageAttachments;
}();

exports.default = {
	bindings: {
		message: '<',
		index: '<',
		social: '<'
	},
	template: sfPostFormTmpls.messageAttachments,
	controller: MessageAttachments
};

},{"./attachments-slider.class":7}],9:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _message = require('./message.class');

var _message2 = _interopRequireDefault(_message);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var MessagePinterest = function (_MessageCommon) {
	_inherits(MessagePinterest, _MessageCommon);

	/* @ngInject */
	function MessagePinterest($timeout, $scope, fieldService) {
		_classCallCheck(this, MessagePinterest);

		var _this = _possibleConstructorReturn(this, (MessagePinterest.__proto__ || Object.getPrototypeOf(MessagePinterest)).call(this));

		_this._$scope = $scope;
		_this._$timeout = $timeout;
		_this._fieldService = fieldService;

		_this.initScopeWatch();
		return _this;
	}

	return MessagePinterest;
}(_message2.default);

exports.default = {
	bindings: {
		index: '<',
		message: '<',
		social: '<',
		global: '='
	},
	template: sfPostFormTmpls.messagePinterest,
	controller: MessagePinterest
};

},{"./message.class":11}],10:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _message = require('./message.class');

var _message2 = _interopRequireDefault(_message);

var _attachmentsSlider = require('./attachments/attachments-slider.class');

var _attachmentsSlider2 = _interopRequireDefault(_attachmentsSlider);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var MessageTwitter = function (_MessageCommon) {
    _inherits(MessageTwitter, _MessageCommon);

    /* @ngInject */
    function MessageTwitter($element, $timeout, $scope, fieldService, WPMediaFrameService, postAttachments, cacheService) {
        _classCallCheck(this, MessageTwitter);

        var _this = _possibleConstructorReturn(this, (MessageTwitter.__proto__ || Object.getPrototypeOf(MessageTwitter)).call(this));

        _this.slideAttach = 0;
        _this._$scope = $scope;
        _this._$timeout = $timeout;
        _this.cache = cacheService;
        _this._fieldService = fieldService;
        _this.socialsButtonShow = [];
        _this._$element = $element;
        _this.prevValue = '';
        _this.wpService = WPMediaFrameService;
        _this._loadingImg = false;
        _this.post = _this.global.post;
        _this.composeSocial = [];
        _this.messageAutocomplite = '';
        _this.slider = new _attachmentsSlider2.default($element, _this.message, postAttachments, cacheService, 'twitter');
        _this.slider.initForSocials(_this.social.type);
        _this.slider.init();
        _this.slider.setCustomImageCurrentPosition();
        _this.slider.setCustomImageCurrentPositionForSocials(_this.cache.get('current_url_' + _this.social.type));
        _this.numbertweetLink = 257;
        _this.numbertweet = 280;
        _this.tweet = new _this.tweetFactory(_this.numbertweet);
        _this.max = 0;
        _this.thisValueD = 0;
        _this.tweetLink = new _this.tweetFactory(_this.numbertweetLink);
        if (_this.global.globalSettings['compose_media_' + 'twitter']) {
            _this.composeSocial['twitter'] = _this.global.globalSettings['compose_media_' + 'twitter'];
            _this.showButton('twitter');
        }
        if (_this.post && _this.post.formId) {
            _this.contextShow = function () {
                return false;
            };
        } else {
            _this.contextShow = function () {
                return true;
            };
        }
        _this.media = _this.global.messageComposeMedia.media;
        _this.socialType = '';
        _this.postAttachments = postAttachments;
        _this.editableAdditional = 'google_plus' != 'twitter';
        _this.initScopeWatch();
        return _this;
    }

    _createClass(MessageTwitter, [{
        key: '$onInit',
        value: function $onInit() {
            var self = this;
            this._$scope.messageAutocomplite = this.message;
            this._$scope.$watch("messageAutocomplite.fields['message']", function () {
                var d;
                if (self.socialsButtonShow['twitter']) {
                    d = self.tweetCount();
                    self.thisValueD = d.tweetLength;
                    self.max = self.numbertweet - self.thisValueD;
                    self.message.fields['message'] = d.newstr;
                    self.message.fields['message_postfix'] = d.posfixTextStr;
                } else {
                    d = self.tweetCountLink();
                    self.thisValueD = d.tweetLength;
                    self.max = self.numbertweetLink - self.thisValueD;
                    self.message.fields['message'] = d.newstr;
                    self.message.fields['message_postfix'] = d.posfixTextStr;
                }
            });

            this._$scope.$watch("messageAutocomplite.fields['message_postfix']", function () {
                var d;
                if (self.socialsButtonShow['twitter']) {
                    d = self.tweetCount();
                    self.thisValueD = d.tweetLength;
                    self.max = self.numbertweet - self.thisValueD;
                    self.message.fields['message'] = d.newstr;
                    self.message.fields['message_postfix'] = d.posfixTextStr;
                } else {
                    d = self.tweetCountLink();
                    self.thisValueD = d.tweetLength;
                    self.max = self.numbertweetLink - self.thisValueD;
                    self.message.fields['message'] = d.newstr;
                    self.message.fields['message_postfix'] = d.posfixTextStr;
                }
            });
        }
    }, {
        key: 'tweetFactory',
        value: function tweetFactory(maxWeiht) {

            function TwitterCharacterUtil(maxWeiht) {
                var twitter_conf = {
                    "version": 2,
                    "maxWeightedTweetLength": maxWeiht,
                    "scale": 100,
                    "defaultWeight": 200,
                    "transformedURLLength": 23,
                    "ranges": [{
                        "start": 0,
                        "end": 4351,
                        "weight": 100
                    }, {
                        "start": 8192,
                        "end": 8205,
                        "weight": 100
                    }, {
                        "start": 8208,
                        "end": 8223,
                        "weight": 100
                    }, {
                        "start": 8242,
                        "end": 8247,
                        "weight": 100
                    }]
                };
                var NORMALIZE = false;
                if ((typeof twitter_conf === 'undefined' ? 'undefined' : _typeof(twitter_conf)) !== 'object') {
                    console.error('Twitter config is undefined');
                };
                if (typeof String.prototype.normalize === 'function') {
                    NORMALIZE = true;
                };
                var scale = twitter_conf.scale;
                var ranges = twitter_conf.ranges;
                var urlLength = twitter_conf.transformedURLLength;
                var maxWeightedTweetLength = twitter_conf.maxWeightedTweetLength;

                return {
                    getTweetData: getTweetData
                };

                function getTweetData(str, postfix) {
                    var d = { links: [], tweetLength: 0, newstr: '', postfixLength: 0, posfixTextStr: '' };
                    if (!str && !postfix) return d;
                    if (NORMALIZE) {
                        if (str) {
                            str = str.normalize('NFC');
                        }
                        if (postfix) {
                            postfix = postfix.normalize('NFC');
                        }
                    }
                    if (typeof postfix != 'undefined') {
                        postfix.split('').forEach(function (chr) {
                            d.tweetLength += _getCharWeight(chr);
                            if (d.tweetLength <= maxWeightedTweetLength) {
                                d.posfixTextStr = d.posfixTextStr + chr;
                            }
                        });
                        var arrMpostf = postfix.match(/(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|[\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|[\ud83c[\ude32-\ude3a]|[\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g);
                        if (null != arrMpostf && arrMpostf instanceof Array) {
                            if (arrMpostf.length != 0) {
                                d.tweetLength = d.tweetLength - 2 * arrMpostf.length;
                            }
                        }
                    }
                    if (str) {

                        var links = twttr.txt.extractUrls(str);

                        var filterLinksRegex = new RegExp(links.join('|'), 'g');
                        var newStr = str;
                        newStr.split('').forEach(function (chr) {
                            d.tweetLength += _getCharWeight(chr);
                            if (d.tweetLength <= maxWeightedTweetLength) {
                                d.newstr = d.newstr + chr;
                            }
                        });
                    }
                    if (str) {

                        d.links = links;

                        var arrM = str.match(/(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|[\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|[\ud83c[\ude32-\ude3a]|[\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g);

                        if (null != arrM && arrM instanceof Array) {
                            if (arrM.length != 0) {
                                d.tweetLength = d.tweetLength - 2 * arrM.length;
                            }
                        }
                    }
                    return d;
                };
                function _getCharWeight(chr) {
                    var weight = twitter_conf.defaultWeight;
                    var n = chr.charCodeAt(0);
                    for (var i = 0; i < ranges.length; i++) {
                        var range = ranges[i];
                        if (n >= range.start && n <= range.end) {
                            weight = range.weight;
                            break;
                        };
                    }
                    return weight / scale;
                };
            }

            return new TwitterCharacterUtil(maxWeiht);
        }
    }, {
        key: 'tweetCount',
        value: function tweetCount() {
            return this.tweet.getTweetData(this.message.fields['message'], this.message.fields['message_postfix']);
        }
    }, {
        key: 'tweetCountLink',
        value: function tweetCountLink() {
            return this.tweetLink.getTweetData(this.message.fields['message'], this.message.fields['message_postfix']);
        }
    }, {
        key: 'setImage',
        value: function setImage(e) {
            this.slideAttach = 1;
            e.preventDefault();
            this.wpService.setAjaxHandler({
                request: this._imageRequest.bind(this),
                success: this._setImageData.bind(this)
            }).open();
        }
    }, {
        key: '_attachMediaImage',
        value: function _attachMediaImage(data) {
            this.message.fields['custom_image'] = data.url;
            this.slider.addCustomImage(data.url);
            this.slider.setCustomImageCurrentPosition();
            this.slider.initForSocials('twitter');

            this._$scope.$apply();
        }
    }, {
        key: '_imageRequest',
        value: function _imageRequest(imageId) {
            this._loadingImg = true;
            if (this.post.type == 'attachment' || this.slideAttach == 0) {
                return this.postAttachments.attachMedia(imageId, 'twitter');
            }
            this.slideAttach = 0;
            return this.postAttachments.attachMediaForSlides(imageId, 'twitter');
        }
    }, {
        key: 'setLoadingClass',
        value: function setLoadingClass() {
            return true === this._loadingImg ? 'loading' : '';
        }
    }, {
        key: '_setImageData',
        value: function _setImageData(data) {
            if (this.post.type == 'attachment') {
                this.media = data;
                this._loadingImg = false;
            } else {
                var cacheMessAtach = [];
                var bool = false;
                if (this.cache.get('message_attachments_for_socials_' + this.social.type).length > 0) {
                    bool = true;
                }
                var arrAtach = angular.copy(this.cache.get('message_attachments_for_socials_' + this.social.type));
                for (var i = 0; i < data.length; i++) {
                    if (this.cache.get('message_attachments_for_socials_' + this.social.type).indexOf(data[i][this.social.type][this.social.type]['medium_thumbnail_url']) == -1) {
                        bool = true;
                        this.slider.mediaUrl = data[i][this.social.type][this.social.type]['medium_thumbnail_url'];
                        arrAtach.unshift(data[i][this.social.type][this.social.type]['medium_thumbnail_url']);
                        this.cache.set(data[i][this.social.type][this.social.type]['medium_thumbnail_url'], 'current_url_' + this.social.type);
                    }
                }
                this.cache.set(arrAtach, 'message_attachments_for_socials_' + this.social.type);
                if (bool) this.slider.setCustomImageCurrentPosition();
                this.slider.initForSocials(this.social.type);

                this._loadingImg = false;
            }
        }
    }, {
        key: '_onSetPostThumbnail',
        value: function _onSetPostThumbnail(e, thumbId) {
            if (this.media && this.media.medium_thumbnail_url) return;

            this.postAttachments.attachMedia(thumbId, 'twitter').then(function (data) {
                this._setImageData(data);
            });
        }
    }, {
        key: 'changeModel',
        value: function changeModel() {
            var countSymbolObject = this.tweetCount(this.message.fields['message']);
            this.message.fields['message'] = countSymbolObject.newstr;
        }
    }, {
        key: 'inputAutocomplete',
        value: function inputAutocomplete() {
            this.message.fields['message'];
        }
    }, {
        key: 'init',
        value: function init() {
            var _this2 = this;

            this._$msg = this._$element.find('textarea');
            var $postfix = this._$element.find('input');
            this._$msg.sfMaxlength({
                maxCharacters: 280,
                events: ['change'],
                statusText: '<span>characters left</span>',
                twitterText: true,
                testArg: 125,
                secondField: $postfix
            });

            this._$scope.globalSettings = this.global.globalSettings;
            this._$scope.$watch('globalSettings.compose_media', function () {
                _this2._$msg.trigger('keyup');
            });
        }
    }, {
        key: 'showButton',
        value: function showButton(socialType, ctrl) {
            this.socialType = 'twitter';
            if (this.socialsButtonShow[socialType]) {
                this.thisValueD = this.tweetCountLink(this.message.fields['message']).tweetLength;
                this.max = this.numbertweetLink - this.thisValueD;
                this.message.fields['message'] = this.tweetCountLink(this.message.fields['message']).newstr;
                this.socialsButtonShow[socialType] = false;
            } else {
                this.thisValueD = this.tweetCount(this.message.fields['message']).tweetLength;
                this.max = this.numbertweet - this.thisValueD;
                this.message.fields['message'] = this.tweetCount(this.message.fields['message']).newstr;
                this.socialsButtonShow[socialType] = true;
            }
        }
    }, {
        key: 'doAutocomplete',
        value: function doAutocomplete() {
            return;
        }
    }]);

    return MessageTwitter;
}(_message2.default);

exports.default = {
    bindings: {
        index: '<',
        message: '<',
        social: '<',
        global: '='
    },
    template: sfPostFormTmpls.messageTwitter,
    controller: MessageTwitter
};

},{"./attachments/attachments-slider.class":7,"./message.class":11}],11:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var MessageCommon = function () {
	/* @ngInject */
	function MessageCommon() {
		_classCallCheck(this, MessageCommon);

		this.loading = true;
		if (this.global.post && this.global.post.formId) {
			this.contextShow = function () {
				return false;
			};
		} else {
			this.contextShow = function () {
				return true;
			};
		}
	}

	_createClass(MessageCommon, [{
		key: 'initScopeWatch',
		value: function initScopeWatch() {
			this._$scope.watchers = this.global.watchers;
			this._$scope.$watch('watchers.autocompleteTrigger', this._doCommonAutocomplete.bind(this));
			this._$timeout(this._afterLoad.bind(this));
		}
	}, {
		key: '_afterLoad',
		value: function _afterLoad() {
			this.loading = false;

			if ('function' == typeof this.afterLoad) this.afterLoad();

			this._doAutocompleteOnLoad();
			this.message.loaded = true;
		}
	}, {
		key: '_doAutocompleteOnLoad',
		value: function _doAutocompleteOnLoad() {
			var status = this.global.post.autocompleteStatus;
			if (undefined === status || 'onload' == status) {
				if ('attachment' == this.global.post.type) return this._doCommonAutocomplete();
				if (false === this.editableAdditional) {
					this.doAutocomplete();
				}

				return;
			};

			if (1 == this.global.globalSettings.disable_autcomplete) {
				if (true === this.message.duplicate && true !== this.message.loaded) return;
			};

			this._doCommonAutocomplete();
		}
	}, {
		key: 'getName',
		value: function getName(name) {
			return this._fieldService.getMessageName(this.social, this.index, name);
		}
	}, {
		key: 'getFieldId',
		value: function getFieldId(name) {
			return this._fieldService.getMessageId(this.social, this.index, name);
		}
	}, {
		key: '_doCommonAutocomplete',
		value: function _doCommonAutocomplete() {
			this.setLoacalValueFromGlobal('message', 'title');

			this.doAutocomplete();
		}
	}, {
		key: 'setLoacalValueFromGlobal',
		value: function setLoacalValueFromGlobal(localField, globalField) {
			if (true === this.loading) return;

			globalField = this.global.post[globalField];

			if (!globalField) return;

			this.message.fields[localField] = globalField;
		}
	}, {
		key: 'doAutocomplete',
		value: function doAutocomplete() {}
	}]);

	return MessageCommon;
}();

exports.default = MessageCommon;

},{}],12:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _message = require('./message.class');

var _message2 = _interopRequireDefault(_message);

var _attachmentsSlider = require('./attachments/attachments-slider.class');

var _attachmentsSlider2 = _interopRequireDefault(_attachmentsSlider);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Message = function (_MessageCommon) {
    _inherits(Message, _MessageCommon);

    /* @ngInject */
    function Message($element, $scope, fieldService, $timeout, WPMediaFrameService, postAttachments, cacheService) {
        _classCallCheck(this, Message);

        var _this = _possibleConstructorReturn(this, (Message.__proto__ || Object.getPrototypeOf(Message)).call(this));

        _this.socialsSettings = {
            pos: []
        };
        _this.showContent = true;
        _this.slideAttach = 0;
        _this.socialsButtonShow = [];
        _this._$scope = $scope;
        _this.imageData = [];
        _this.postAttachments = postAttachments;
        _this._$timeout = $timeout;
        _this.post = _this.global.post;
        _this.socialsSettings.pos[_this.social.type] = 0;
        _this._fieldService = fieldService;
        _this.cache = cacheService;
        _this.media = _this.global.messageComposeMedia.media;
        _this.mediaUrl = '';
        _this.wpService = WPMediaFrameService;
        _this._loadingImg = false;
        _this.editableAdditional = 'google_plus' != _this.social.type;
        _this.initScopeWatch();
        _this.socialsButtonShow = [];
        _this.global.globalSettings['show_google_plus'] = _this.global.globalSettings['compose_media_google_plus'] ? 0 : 1;
        _this.global.globalSettings['show_facebook'] = _this.global.globalSettings['compose_media_facebook'] ? 0 : 1;
        _this.global.globalSettings['show_twitter'] = 0;
        _this.global.globalSettings['show_linkedin'] = 0;
        _this.global.globalSettings['cache'] = [];
        _this.composeSocial = [];
        _this.slider = new _attachmentsSlider2.default($element, _this.message, postAttachments, cacheService, _this.social.type);
        _this.slider.initForSocials(_this.social.type);
        _this.slider.init();
        _this.slider.setCustomImageCurrentPosition();
        _this.slider.setCustomImageCurrentPositionForSocials(_this.cache.get('current_url_' + _this.social.type));
        if (_this.global.globalSettings['compose_media_' + _this.social.type]) {
            _this.composeSocial[_this.social.type] = _this.global.globalSettings['compose_media_' + _this.social.type];
            _this.showButton(_this.social.type);
        }

        if (_this.social.type == 'facebook') {
            _this.cache.set(_this.message.fields['description'], 'description_old_facebook');
            _this.cache.set(_this.message.fields['title'], 'title_old_facebook');
        }
        return _this;
    }

    _createClass(Message, [{
        key: '$onInit',
        value: function $onInit() {}
    }, {
        key: 'facebookMetaDisabled',
        value: function facebookMetaDisabled(socialType) {

            if (socialType != 'facebook' || this.cache.get('facebook_change_meta')) {
                return false;
            }

            if (this.message.fields['description']) {
                this.message.fields['description'] = this.cache.get('description_old_facebook');
            } else {
                this.message.fields['description'] = '';
            }

            if (this.message.fields['title']) {
                this.message.fields['title'] = this.cache.get('title_old_facebook');
            } else {
                this.message.fields['title'] = '';
            }
            return true;
        }
    }, {
        key: 'setImage',
        value: function setImage(e) {

            this.slideAttach = 1;
            e.preventDefault();
            this.wpService.setAjaxHandler({
                request: this._imageRequest.bind(this),
                success: this._setImageData.bind(this)
            }).open();
        }
    }, {
        key: '_imageRequest',
        value: function _imageRequest(imageId) {
            this._loadingImg = true;
            if (this.post.type == 'attachment' || this.slideAttach == 0) {
                return this.postAttachments.attachMedia(imageId, this.social.type);
            }
            this.slideAttach = 0;
            return this.postAttachments.attachMediaForSlides(imageId, this.social.type);
        }
    }, {
        key: 'setLoadingClass',
        value: function setLoadingClass() {
            return true === this._loadingImg ? 'loading' : '';
        }
    }, {
        key: '_setImageData',
        value: function _setImageData(data) {
            if (this.social.type == 'linkedin') return;
            if (this.post.type == 'attachment') {
                this.media = data;
                this._loadingImg = false;
            } else {
                var cacheMessAtach = [];
                var bool = false;
                if (this.cache.get('message_attachments_for_socials_' + this.social.type).length > 0) {
                    bool = true;
                }
                var arrAtach = angular.copy(this.cache.get('message_attachments_for_socials_' + this.social.type));
                for (var i = 0; i < data.length; i++) {
                    if (this.cache.get('message_attachments_for_socials_' + this.social.type).indexOf(data[i][this.social.type][this.social.type]['medium_thumbnail_url']) == -1) {
                        bool = true;
                        this.slider.mediaUrl = data[i][this.social.type][this.social.type]['medium_thumbnail_url'];
                        arrAtach.unshift(data[i][this.social.type][this.social.type]['medium_thumbnail_url']);
                        this.cache.set(data[i][this.social.type][this.social.type]['medium_thumbnail_url'], 'current_url_' + this.social.type);
                        this.slider.slidesForSocials = arrAtach;
                    }
                }
                this.cache.set(arrAtach, 'message_attachments_for_socials_' + this.social.type);
                if (bool) this.slider.setCustomImageCurrentPosition();
                this.slider.initForSocials(this.social.type);

                this._loadingImg = false;
            }
        }
    }, {
        key: '_onSetPostThumbnail',
        value: function _onSetPostThumbnail(e, thumbId) {
            if (this.media && this.media.medium_thumbnail_url) return;

            this.postAttachments.attachMedia(thumbId, this.social.type).then(function (data) {
                this._setImageData(data);
            });
        }
    }, {
        key: 'afterLoad',
        value: function afterLoad() {
            if ('google_plus' == this.type) this.doAutocomplete();
        }
    }, {
        key: 'doAutocomplete',
        value: function doAutocomplete() {
            this.setLoacalValueFromGlobal('description', 'content');
            this.setLoacalValueFromGlobal('title', 'title');
        }
    }, {
        key: 'showButton',
        value: function showButton(socialType) {
            this.social.type = socialType;
            if (this.socialsButtonShow[socialType]) {
                this.global.globalSettings['show_' + socialType] = 1;
                this.socialsButtonShow[socialType] = false;
            } else {
                this.global.globalSettings['show_' + socialType] = 0;
                this.socialsButtonShow[socialType] = true;
            }
        }
    }, {
        key: 'setCustomImage',
        value: function setCustomImage(e) {
            e.preventDefault();

            this.wpService.setHandler(this._attachMediaImage.bind(this)).open();
        }
    }, {
        key: '_attachMediaImage',
        value: function _attachMediaImage(data) {
            this.message.fields['custom_image'] = data.url;
            this.slider.addCustomImage(data.url);
            this.slider.setCustomImageCurrentPosition();
            this.slider.initForSocials(this.social.type);

            this._$scope.$apply();
        }
    }, {
        key: 'getName',
        value: function getName(name) {
            return this._fieldService.getMessageName(this.social, this.index, name);
        }
    }, {
        key: 'getFieldId',
        value: function getFieldId(name) {
            return this._fieldService.getMessageId(this.social, this.index, name);
        }
    }, {
        key: '_onUpdatePostThumbnail',
        value: function _onUpdatePostThumbnail(e, thumbUrl) {
            if (thumbUrl) {
                this.slider.addPostThumbnail(thumbUrl);
                this.slider.setThumbnailCurrentPosition();
                this.slider.init();
            } else {
                this.slider.removePostThumbnail();
                this.slider.init();
                this._$scope.$apply();
            }
        }
    }, {
        key: 'setCustomImageData',
        value: function setCustomImageData(data) {
            this.message.fields['custom_image'] = data.medium_thumbnail_url;
            this.message.fields['custom_image_filename'] = data.filename;
        }
    }]);

    return Message;
}(_message2.default);

exports.default = {
    bindings: {
        index: '<',
        message: '<',
        social: '<',
        global: '='
    },
    template: sfPostFormTmpls.message,
    controller: Message
};

},{"./attachments/attachments-slider.class":7,"./message.class":11}],13:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = {
	bindings: {
		social: '<',
		messages: '<',
		global: '='
	},
	template: sfPostFormTmpls.messagesList,
	controller: function controller() {
		var _this = this;

		this.addItem = function () {
			var message = JSON.parse(JSON.stringify(_this.messages[0]));
			message.duplicate = true;
			message.loaded = false;
			message.settings = [[]];
			for (var field in message.fields) {
				message.fields[field] = '';
			}
			message.showContent = true;
			_this.messages.push(message);
		};

		this.removeItem = function (item) {
			_this.messages.splice(_this.messages.indexOf(item), 1);
		};

		this.loadComponent = function (tmplType, socialType) {
			if (tmplType == socialType) return true;

			var def = ['facebook', 'linkedin', 'google_plus'];

			if ('default' != tmplType) return false;

			if (-1 !== def.indexOf(socialType)) return true;

			return false;
		};

		this.setMessageClass = function (message, index) {
			if (index) {
				message.showContent = true;
			}
			var cl = _this.social.type;

			if (!_this.global.globalSettings.compose_media) return cl;

			return cl;
		};
	}
};

},{}],14:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = {
	bindings: {
		tzOffset: '<',
		name: '<',
		value: '<'
	},
	template: '\n\t\t<input \n\t\t\tclass="time datetimepicker" \n\t\t\tvalue="{{ $ctrl.value }}" \n\t\t\tname="{{ $ctrl.name }}" \n\t\t\tdata-tz-offset="{{ $ctrl.tzOffset }}" \n\t\t\ttype="text"\n\t\t>\n\t',
	controller: function controller($element, $timeout) {
		var now = new Date();
		var userTime = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), now.getUTCHours(), now.getUTCMinutes() + 1, now.getUTCSeconds(), now.getUTCMilliseconds() + this.tzOffset * 1000);

		$timeout(function () {
			$element.children('input').datetimepicker({
				dateFormat: 'dd-mm-yy',
				ampm: true,
				minDate: userTime
			});
		});
	}
};

},{}],15:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

exports.default = {
	bindings: {
		index: '<',
		setting: '<',
		remove: '&',
		social: '<',
		messageIndex: '<'
	},
	template: sfPostFormTmpls.advancedSetting,
	controller: function controller(fieldService, cacheService) {
		var _this = this;

		var data = cacheService.get('advancedSetting');
		this.socialdata = cacheService.get('socialData');
		this.loaded = cacheService.get('loaded_mess_' + this.social.type);

		this.socialMustSend = false;
		for (var i = 0; i < this.socialdata.length; i++) {
			if (this.social.type === this.socialdata[i].type && _typeof(this.socialdata[i].messages) && typeof this.socialdata[i].messages[0] != 'undefined' && typeof this.socialdata[i].messages[0] != 'undefined' && typeof this.socialdata[i].messages[0].setting != 'undefined' && typeof this.socialdata[i].messages[0].setting != 'undefined' && typeof this.socialdata[i].messages[0].setting[0] != 'undefined' && typeof this.socialdata[i].messages[0].setting[0]['must_send'] != 'undefined') {
				this.socialMustSend = this.socialdata[i].messages[0].setting[0]['must_send'];
				break;
			}
		}
		this.must_send = 0;
		formatSelectVals.call(this);
		if (this.index > 0) this.setting.duplicate = true;

		this.getConstantData = function (key) {
			return data.const[key];
		};

		this.getFieldName = function (name) {
			return fieldService.getSettingName(_this.social, _this.messageIndex, _this.index, name);
		};
		this.getPublishOptions = function () {
			return _this.getConstantOptions('publish_option', filterPublishOptions);
		};
		this.getConstantOptions = function (key, filter) {
			var options = _this.getConstantData(key);
			var duplicate = _this.getConstantData('duplicated');
			var output = [];

			if (filter) options = filter.call(_this, options);
			for (key in options) {
				if (_this.loaded && key !== 'schedule') {
					continue;
				} else if (_this.loaded && key === 'schedule') {
					_this.setting.publish_option.key = 'schedule';
				}
				output.push({
					key: key,
					value: options[key]
				});
			}
			if (!_this.loaded) cacheService.set(true, 'loaded_mess_' + _this.social.type);
			return output;
		};

		this.toggleMustSend = function () {

			_this.must_send = +!_this.must_send;
		};

		function filterPublishOptions(options) {
			var duplicated = data.const.duplicated;

			if (!this.setting.duplicate) return options;

			var output = {};

			duplicated.forEach(function (val) {
				output[val] = options[val];
			});

			return output;
		}

		function formatSelectVals() {
			for (var key in data.defaults) {
				var def = data.defaults[key];
				var value = this.setting[key];
				if (angular.isObject(value) && value.key) continue;

				if (!value) value = def;

				if (key) this.setting[key] = {
					key: value
				};
			}
		}
		this.must_send = this.socialMustSend === false ? +this.setting.must_send.key : +this.socialMustSend;
	}
};

},{}],16:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = {
	bindings: {
		social: '<',
		index: '<',
		settings: '<'
	},
	template: sfPostFormTmpls.advancedSettingsList,
	controller: function controller() {
		var _this = this;

		this.addItem = function (e) {
			e.preventDefault();

			_this.settings.push({
				duplicate: true
			});
		};

		this.removeItem = function (item) {
			_this.settings.splice(_this.settings.indexOf(item), 1);
		};
	}
};

},{}],17:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SocialTabsList = function () {
	/* @ngInject */
	function SocialTabsList() {
		_classCallCheck(this, SocialTabsList);

		this.socials = this.global.socialData;
	}

	_createClass(SocialTabsList, [{
		key: 'isActive',
		value: function isActive(social) {
			return this.global.activeSocialTab == social.type;
		}
	}]);

	return SocialTabsList;
}();

exports.default = {
	bindings: {
		global: '='
	},
	template: sfPostFormTmpls.socialTabsList,
	controller: SocialTabsList
};

},{}],18:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var SocialTabs = function () {
	/* @ngInject */
	function SocialTabs($scope, accountsService) {
		_classCallCheck(this, SocialTabs);

		this._accountsService = accountsService;

		$scope.watchers = this.global.watchers;
		$scope.globalSettings = this.global.globalSettings;

		$scope.$watch('watchers.updateEnabledAccounts', this._updateTabsList.bind(this));
		$scope.$watch('globalSettings.compose_media', this._updateTabsList.bind(this));
	}

	_createClass(SocialTabs, [{
		key: '_setTabs',
		value: function _setTabs() {
			var enabledTypes = this._accountsService.getEnabledTypes();
			var socials = [];

			angular.forEach(this.global.socialData, function (social) {
				if (-1 === enabledTypes.indexOf(social.type)) return;
				socials.push(social);
			});

			this.socials = socials;
		}
	}, {
		key: '_setActiveTab',
		value: function _setActiveTab() {
			this.firstTab = this.socials[0].type;

			if (!this.global.activeSocialTab) {
				this.global.activeSocialTab = this.firstTab;
				return;
			}

			for (var i = 0; i < this.socials.length; i++) {
				var social = this.socials[i];

				if (social.type == this.global.activeSocialTab) return;
			}

			this.global.activeSocialTab = this.firstTab;
		}
	}, {
		key: '_updateTabsList',
		value: function _updateTabsList() {
			this._setTabs();
			this._setActiveTab();
		}
	}, {
		key: 'getFilteredSocials',
		value: function getFilteredSocials() {
			return this.socials;
		}
	}, {
		key: 'activateTab',
		value: function activateTab(social) {
			this.global.activeSocialTab = social.type;
		}
	}, {
		key: '_mbActivateFirstTab',
		value: function _mbActivateFirstTab(social) {
			if (social.type != this.global.activeSocialTab) return;

			this.activateTab(this.firstTab);
		}
	}, {
		key: 'setActiveClass',
		value: function setActiveClass(type) {
			if (type.type) type = type.type;

			return this.global.activeSocialTab == type ? 'active' : '';
		}
	}]);

	return SocialTabs;
}();

exports.default = {
	bindings: {
		global: '='
	},
	template: sfPostFormTmpls.socialTabs,
	controller: SocialTabs
};

},{}],19:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = {
	bindings: {
		ajaxData: '<'
	},
	template: sfPostFormTmpls.statsFull,
	controller: function controller(cacheService, httpService, $sce, $scope) {
		var _this = this;

		var data = cacheService.get('stats');
		var postId = cacheService.get('post').ID;

		$scope.ajaxData = this.ajaxData;

		this.logs = formatLogsArray(data.logs);
		this.lastSent = data.last_sent;
		this.showList = false;
		$scope.$watch('ajaxData.stats', function () {
			data = cacheService.get('stats');

			_this.logs = formatLogsArray(data.logs);
			_this.lastSent = data.last_sent;
		});

		this.toggleList = function (e) {
			e.preventDefault();

			_this.showList = !_this.showList;
		};

		this.updateLogs = function (e) {
			e.preventDefault();

			angular.forEach(_this.logs, function (log, key) {
				if (1 == log.message.is_published) return;
				updateSingleLog(log, key);
			});
		};

		this.trustAsHtml = function (text) {
			return $sce.trustAsHtml(text);
		};

		function updateSingleLog(log, key) {
			log.showSpinner = true;

			httpService.get({
				action: 'sf-get-message',
				post_id: postId,
				id: log.message.content_item_id,
				time: log.time,
				account_id: log.account.id
			}).then(function (data) {
				log.showSpinner = false;
				log.message.status = data;
			});
		}

		function formatLogsArray(logs) {
			var output = [];

			angular.forEach(logs, function (data, time) {
				var i = 0;

				angular.forEach(data.accounts, function (account, accountId) {
					angular.forEach(account.messages, function (message, key) {
						output.push({
							date: 0 == i ? data.date : '',
							time: time,
							account: {
								id: accountId,
								name: 0 == key ? account.name : ''
							},
							message: message,
							showSpinner: false
						});
						i++;
					});
				});
			});

			return output;
		}
	}
};

},{}],20:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = {
	template: sfPostFormTmpls.formInPopup,
	controller: function controller(httpService, $timeout, cacheService, $sce, $scope) {
		var _this = this;

		this.showForm = false;
		this.message = '';
		this.showSpinner = false;
		this.ajaxData = {
			stats: {},
			errors: []
		};

		angular.element('body').on('thickbox:removed', function (e) {
			$timeout(function () {
				cacheService.clear();

				_this.clearForm();
			});
		});

		angular.element('.sf-open-popup').on('click', function (e) {
			e.preventDefault();

			var $btn = angular.element(e.currentTarget);

			httpService.post({
				action: 'sf-composeform-data',
				post: $btn.data('postId')
			}).then(function (data) {
				data.post.formId = true;
				cacheService.set(data);
				cacheService.isAjax(true);

				_this.showForm = true;
			});
		});

		this.submit = function (e) {
			var data = angular.element(e.currentTarget).serializeArray();
			var dataObj = {};

			data.forEach(function (field) {
				dataObj[field.name] = field.value;
			});

			dataObj['post_id'] = cacheService.get('post').ID;

			_this.showSpinner = true;

			httpService.post(dataObj).then(function (data) {
				_this.showSpinner = false;

				_this.message = $sce.trustAsHtml(data.form_message);

				if (data.stats) {
					cacheService.set(data.stats, 'stats');

					_this.ajaxData.stats = data.stats;

					$timeout(function () {
						return $scope.$apply();
					});
				}
			});
		};

		this.clearForm = function () {
			_this.showForm = false;
			_this.message = '';
		};
	}
};

},{}],21:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Watchers = function () {
	function Watchers() {
		_classCallCheck(this, Watchers);
	}

	_createClass(Watchers, [{
		key: "toggle",
		value: function toggle(watcher) {
			if (this.register(watcher)) return;

			this[watcher] = !this[watcher];
		}
	}, {
		key: "register",
		value: function register(watcher) {
			if (this.hasOwnProperty(watcher)) return false;

			this[watcher] = true;

			return true;
		}
	}]);

	return Watchers;
}();

exports.default = Watchers;

},{}],22:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var AccountsService = function () {
	/* @ngInject */
	function AccountsService(cacheService) {
		_classCallCheck(this, AccountsService);

		this._cacheService = cacheService;
	}

	_createClass(AccountsService, [{
		key: 'getAccounts',
		value: function getAccounts() {
			return this._cacheService.get('accounts');
		}
	}, {
		key: 'getEnabledTypes',
		value: function getEnabledTypes() {
			var accounts = this.getAccounts();
			var enabledTypes = [];

			angular.forEach(accounts, function (account) {
				if (!account.send) return;

				if (-1 !== enabledTypes.indexOf(account.type)) return;

				enabledTypes.push(account.type);
			});

			return enabledTypes;
		}
	}]);

	return AccountsService;
}();

exports.default = AccountsService;

},{}],23:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var CacheService = function () {
	/* @ngInject */
	function CacheService() {
		_classCallCheck(this, CacheService);

		this.ajax = false;
		this.data = null;
	}

	_createClass(CacheService, [{
		key: "set",
		value: function set(data, key) {
			if (key) {
				if (!this.data) this.data = {};

				this.data[key] = data;
				return;
			}

			this.data = data;
		}
	}, {
		key: "get",
		value: function get(key) {
			if (key) return this.data[key];

			return this.data;
		}
	}, {
		key: "isset",
		value: function isset() {
			return !!this.data;
		}
	}, {
		key: "clear",
		value: function clear() {
			this.data = null;
		}
	}, {
		key: "isAjax",
		value: function isAjax(toggle) {
			if (toggle) this.ajax = toggle;

			return this.ajax;
		}
	}]);

	return CacheService;
}();

exports.default = CacheService;

},{}],24:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var CommonService = function () {
	/* @ngInject */
	function CommonService(cacheService) {
		_classCallCheck(this, CommonService);

		this.$postContentWrap = angular.element('#wp-content-wrap');
		this.$postContent = angular.element('#content');
		this.$postTitle = angular.element('#title');

		this._cacheService = cacheService;
	}

	/**
  * Remove html tags, shortcodes, html special chars and whitespaces from the beginning and end of text
  * @param  {string} text Text to be cleand
  * @return {string}      CLean text
  */


	_createClass(CommonService, [{
		key: 'cleanText',
		value: function cleanText(text) {
			text = text.replace(/<(?:.|\n)*?>/gm, '').replace(/\[(?:.|\n)*?\]/gm, '');
			text = text.replace('&nbsp;', '');
			return text.trim();
		}
	}, {
		key: 'getPostTitle',
		value: function getPostTitle() {
			return this.$postTitle;
		}
	}, {
		key: 'getPostTitleValue',
		value: function getPostTitleValue() {
			return this.getPostTitle().val();
		}
	}, {
		key: 'getPostContent',
		value: function getPostContent() {
			return this.$postContent;
		}
	}, {
		key: 'getPostContentValue',
		value: function getPostContentValue() {
			if ('undefined' !== typeof tinyMCE && tinyMCE.activeEditor && this.$postContentWrap.hasClass('tmce-active') && 'undefined' !== typeof tinyMCE.activeEditor.initialized) {
				return tinyMCE.activeEditor.getContent();
			}

			if (this._cacheService.isAjax()) return null;

			return this.getPostContent().val();
		}
	}]);

	return CommonService;
}();

exports.default = CommonService;

},{}],25:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var FieldService = function () {
	/* @ngInject */
	function FieldService() {
		_classCallCheck(this, FieldService);
	}

	_createClass(FieldService, [{
		key: "getAccountName",
		value: function getAccountName(account) {
			return account.field_meta.name;
		}
	}, {
		key: "getAccountId",
		value: function getAccountId(account) {
			return account.field_meta.id;
		}
	}, {
		key: "getMessageId",
		value: function getMessageId(social, index, name) {
			return social.field_meta.id_prefix + "_" + index + "_" + name;
		}
	}, {
		key: "getMessagePrefix",
		value: function getMessagePrefix(social, index) {
			return social.field_meta.name_prefix + "[" + index + "]";
		}
	}, {
		key: "getMessageName",
		value: function getMessageName(social, index, name) {
			var prefix = this.getMessagePrefix(social, index);

			return prefix + "[fields][" + name + "]";
		}
	}, {
		key: "getSettingName",
		value: function getSettingName(social, msgIndex, index, name) {
			var prefix = this.getMessagePrefix(social, msgIndex);

			return prefix + "[settings][" + index + "][" + name + "]";
		}
	}]);

	return FieldService;
}();

exports.default = FieldService;

},{}],26:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HttpService = function () {
	/* @ngInject */
	function HttpService($http, $httpParamSerializerJQLike) {
		_classCallCheck(this, HttpService);

		this._$http = $http;
		this._$httpParamSerializerJQLike = $httpParamSerializerJQLike;
	}

	_createClass(HttpService, [{
		key: 'post',
		value: function post(data) {
			return this._$http({
				method: 'POST',
				url: ajaxurl,
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				data: this._$httpParamSerializerJQLike(data)
			}).then(function (data) {
				return data = data.data;
			});
		}
	}, {
		key: 'get',
		value: function get(data) {
			return this._$http({
				method: 'GET',
				url: ajaxurl,
				params: data
			}).then(function (data) {
				return data = data.data;
			});
		}
	}, {
		key: 'parseQueryString',
		value: function parseQueryString(query) {
			var obj = {};

			if ('string' != typeof query) return obj;

			var vars = query.split('&');

			for (var i = 0; i < vars.length; i++) {
				var pair = vars[i].split('=');

				var key = decodeURIComponent(pair[0]);
				var val = decodeURIComponent(pair[1]);

				obj[key] = val;
			};

			return obj;
		}
	}, {
		key: 'getParameterByName',
		value: function getParameterByName(name, string) {
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");

			results = regex.exec(string);
			return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
	}]);

	return HttpService;
}();

exports.default = HttpService;

},{}],27:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PostAttachmentsService = function () {
	/* @ngInject */
	function PostAttachmentsService(httpService, commonService, cacheService) {
		_classCallCheck(this, PostAttachmentsService);

		this._httpService = httpService;
		this._commonService = commonService;
		this._cacheService = cacheService;

		this.postId = cacheService.get('post').ID;
		this.$document = angular.element(document);

		this.$document.on('ajaxComplete', this._onGlobalAjaxComplete.bind(this));
	}

	_createClass(PostAttachmentsService, [{
		key: '_onGlobalAjaxComplete',
		value: function _onGlobalAjaxComplete(event, xhr, settings) {
			if (!settings.hasOwnProperty('data')) return;

			var data = this._httpService.parseQueryString(settings.data);

			if ('get-post-thumbnail-html' == data.action) {
				this._updatePostThumbnail(data.thumbnail_id, 1);
			}
			if ('set-post-thumbnail' == data.action || 'send-attachment-to-editor' == data.action) {
				this.findImagesFromPostContent();
			};
		}
	}, {
		key: '_updatePostThumbnail',
		value: function _updatePostThumbnail(thumbId, feature) {
			var _this = this;

			var post = this._cacheService.get('post');

			if ('-1' == thumbId) {
				post.thumbnail = '';
				this._triggerUpdatePostThumbnail(post.thumbnail);
			} else {
				if (feature) {
					this.attachSingleImage(thumbId, feature).then(function (data) {
						post.thumbnail = data.medium_thumbnail_url;
						_this._triggerUpdatePostThumbnail(post.thumbnail);
					});
				} else {
					this.attachSingleImage(thumbId).then(function (data) {
						post.thumbnail = data.medium_thumbnail_url;
						_this._triggerUpdatePostThumbnail(post.thumbnail);
					});
				}
			}
		}
	}, {
		key: '_triggerUpdatePostThumbnail',
		value: function _triggerUpdatePostThumbnail(thumbUrl) {
			this.$document.trigger('sf-update-post-thumbnail', [thumbUrl]);
		}
	}, {
		key: 'findImagesFromPostContent',
		value: function findImagesFromPostContent($el) {
			var content = void 0;
			content = this._commonService.getPostContentValue();

			if (!$el) $el = this.$document;

			this._httpService.post({
				action: 'sf_attachments',
				ID: this.postId,
				content: content
			}).then(function (slides) {
				$el.trigger('sf-get-slides', [slides]);
			});
		}
	}, {
		key: 'attachSingleImage',
		value: function attachSingleImage(imageId, feature) {
			if (feature) {
				return this._httpService.post({
					action: 'sf_get_custom_message_image',
					attachment_id: imageId,
					feature: feature,
					attach_to_post: this.postId
				});
			} else {
				return this._httpService.post({
					action: 'sf_get_custom_message_image',
					attachment_id: imageId
				});
			}
		}
	}, {
		key: 'attachMedia',
		value: function attachMedia(imageId, socialTtype) {
			return this._httpService.post({
				attach_to_post: this.postId,
				social_id: socialTtype,
				action: 'sf_get_custom_message_image',
				attachment_id: imageId
			});
		}
	}, {
		key: 'attachMediaForSlides',
		value: function attachMediaForSlides(imageId, socialTtype) {
			return this._httpService.post({
				attach_to_post: this.postId,
				social_id: socialTtype,
				action: 'sf_get_custom_message_image_atacments_slide',
				attachment_id: imageId
			});
		}
	}]);

	return PostAttachmentsService;
}();

exports.default = PostAttachmentsService;

},{}],28:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var WPMediaFrameService = function () {
	/* @ngInject */
	function WPMediaFrameService() {
		_classCallCheck(this, WPMediaFrameService);

		this._wpMediaFrame = this._initWPMediaFrame();
		this._handlers = {};
	}

	_createClass(WPMediaFrameService, [{
		key: '_initWPMediaFrame',
		value: function _initWPMediaFrame() {
			var frame = wp.media({
				multiple: false // Set to true to allow multiple files to be selected
			});

			frame.on('select', this._onSelectInFrame.bind(this));

			return frame;
		}
	}, {
		key: '_onSelectInFrame',
		value: function _onSelectInFrame(e) {
			var _this = this;

			if (!this._handlers) return;

			var handlers = this._handlers;

			var data = this._wpMediaFrame.state().get('selection').first().toJSON();

			if (handlers.request) {
				handlers.request(data.id).then(function (response) {
					if (handlers.success) handlers.success(response);

					_this._clearTempData();
				});
			}

			if (handlers.simpleHandler) {
				handlers.simpleHandler(data);
			}
		}
	}, {
		key: 'setAjaxHandler',
		value: function setAjaxHandler(handlers) {
			if ('function' != typeof handlers.request) {
				throw new Error('WPMediaFrameService.setAjaxHandler() - request is not a function');
				return this;
			}

			if ('function' != typeof handlers.success) {
				delete handlers.success;
			}

			this._handlers = handlers;

			return this; // for using this.open()
		}
	}, {
		key: 'setHandler',
		value: function setHandler(handler) {

			if ('function' != typeof handler) {
				throw new Error('WPMediaFrameService.setHandler() - handler is not a function');
				return this;
			}
			if (!this._handlers) {
				this._handlers = {};
			}
			this._handlers.simpleHandler = handler;

			return this;
		}
	}, {
		key: 'open',
		value: function open() {
			this._wpMediaFrame.open();
		}
	}, {
		key: '_clearTempData',
		value: function _clearTempData() {
			this._handlers = null;
		}
	}]);

	return WPMediaFrameService;
}();

exports.default = WPMediaFrameService;

},{}]},{},[1]);
