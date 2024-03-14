/******/
(function (modules) {
    var installedModules = {};
    function __webpack_require__(moduleId) {
        if (installedModules[moduleId]) {
            return installedModules[moduleId].exports;
        }
        var module = installedModules[moduleId] = {
            i: moduleId,
            l: false,
            exports: {}
        };
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        module.l = true;
        return module.exports;
    }
    __webpack_require__.m = modules;
    __webpack_require__.c = installedModules;
    __webpack_require__.d = function(exports, name, getter) {
        if (!__webpack_require__.o(exports, name)) {
            Object.defineProperty(exports, name, {
                enumerable: true,
                get: getter
            });
        }
    };
    __webpack_require__.r = function(exports) {
        if (typeof Symbol !== "undefined" && Symbol.toStringTag) {
            Object.defineProperty(exports, Symbol.toStringTag, {
                value: "Module"
            });
        }
        Object.defineProperty(exports, "__esModule", {
            value: true
        });
    };
    __webpack_require__.t = function(value, mode) {
        if (mode & 1) value = __webpack_require__(value);
        if (mode & 8) return value;
        if (mode & 4 && typeof value === "object" && value && value.__esModule) return value;
        var ns = Object.create(null);
        __webpack_require__.r(ns);
        Object.defineProperty(ns, "default", {
            enumerable: true,
            value: value
        });
        if (mode & 2 && typeof value != "string") for (var key in value) __webpack_require__.d(ns, key, function(key) {
            return value[key];
        }.bind(null, key));
        return ns;
    };
    __webpack_require__.n = function(module) {
        var getter = module && module.__esModule ? function getDefault() {
            return module["default"];
        } : function getModuleExports() {
            return module;
        };
        __webpack_require__.d(getter, "a", getter);
        return getter;
    };
    __webpack_require__.o = function(object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };
    __webpack_require__.p = "";
    return __webpack_require__(__webpack_require__.s = 0);
})
/************************************************************************/
/******/([
/* 0: front-end */
(function (__unused_webpack_module, exports, __webpack_require__) {
    "use strict";

    var _frontend = __webpack_require__(2);

    const extendDefaultHandlers = defaultHandlers => {
        const handlers = {
            popup: _frontend.default,
        };
        return { ...defaultHandlers,
            ...handlers
        };
    };

    elementorFrontend.on('elementor/modules/init:before', () => {
        jQuery.each(extendDefaultHandlers(), (moduleName, ModuleClass) => {
            LaStudioKits['e_module__' + moduleName] = new ModuleClass();
        });
    });
    /***/
}),
/* 1: ../modules/popup/assets/js/frontend/document.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";
    
    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;
    
    var _triggers = __webpack_require__(13);

    var _timing = __webpack_require__(3);

    // Temporary solution, when core 3.5.0 will be the minimum version, is should be replaced with @elementor/e-icons.
    class _default extends elementorModules.frontend.Document {
        bindEvents() {
            const openSelector = this.getDocumentSettings('open_selector');

            if (openSelector) {
                elementorFrontend.elements.$body.on('click', openSelector, this.showModal.bind(this));
            }
        }

        startTiming() {
            const timing = new _timing.default(this.getDocumentSettings('timing'), this);

            if (timing.check()) {
                this.initTriggers();
            }
        }

        initTriggers() {
            this.triggers = new _triggers.default(this.getDocumentSettings('triggers'), this);
        }

        showModal(avoidMultiple) {
            const settings = this.getDocumentSettings();

            if (!this.isEdit) {
                if (!elementorFrontend.isWPPreviewMode()) {
                    if (this.getStorage('disable')) {
                        return;
                    }
                    if (avoidMultiple && LaStudioKits.e_module__popup.popupPopped && settings.avoid_multiple_popups) {
                        return;
                    }
                } // A clean copy of the element without previous initializations and events

                this.$element = jQuery(this.elementHTML);
                this.elements.$elements = this.$element.find(this.getSettings('selectors.elements'));
            }

            const modal = this.getModal(),
                $closeButton = modal.getElements('closeButton');
            modal.setMessage(this.$element).show();

            if (!this.isEdit) {
                if (settings.close_button_delay) {
                    $closeButton.hide();
                    clearTimeout(this.closeButtonTimeout);
                    this.closeButtonTimeout = setTimeout(() => $closeButton.show(), settings.close_button_delay * 1000);
                }

                super.runElementsHandlers();
            }

            this.setEntranceAnimation();

            if (!settings.timing || !settings.timing.times_count) {
                this.countTimes();
            }

            LaStudioKits.e_module__popup.popupPopped = true;
        }

        setEntranceAnimation() {
            const $widgetContent = this.getModal().getElements('widgetContent'),
                settings = this.getDocumentSettings(),
                newAnimation = elementorFrontend.getCurrentDeviceSetting(settings, 'entrance_animation');

            if (this.currentAnimation) {
                $widgetContent.removeClass(this.currentAnimation);
            }

            this.currentAnimation = newAnimation;

            if (!newAnimation) {
                return;
            }

            const animationDuration = settings.entrance_animation_duration.size;
            $widgetContent.addClass(newAnimation);
            setTimeout(() => $widgetContent.removeClass(newAnimation), animationDuration * 1000);
        }

        setExitAnimation() {
            const modal = this.getModal(),
                settings = this.getDocumentSettings(),
                $widgetContent = modal.getElements('widgetContent'),
                newAnimation = elementorFrontend.getCurrentDeviceSetting(settings, 'exit_animation'),
                animationDuration = newAnimation ? settings.entrance_animation_duration.size : 0;
            setTimeout(() => {
                if (newAnimation) {
                    $widgetContent.removeClass(newAnimation + ' reverse');
                }

                if (!this.isEdit) {
                    this.$element.remove();
                    modal.getElements('widget').hide();
                }
            }, animationDuration * 1000);

            if (newAnimation) {
                $widgetContent.addClass(newAnimation + ' reverse');
            }
        }

        initModal() {
            let modal;

            function createSvgElement (name, { path, width, height }) {
                const elementName = 'eicon-' + name;
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.innerHTML = '<path d="' + path + '"></path>';
                svg.setAttributeNS(null, 'viewBox', '0 0 ' + width + ' ' + height);
                return svg;
            }

            this.getModal = () => {
                if (!modal) {
                    const settings = this.getDocumentSettings(),
                        id = this.getSettings('id'),
                        triggerPopupEvent = eventType => {
                            const event = 'elementor/popup/' + eventType;
                            elementorFrontend.elements.$document.trigger(event, [id, this]); // TODO: Use `elementorFrontend.utils.events.dispatch` when it's in master.

                            window.dispatchEvent(new CustomEvent(event, {
                                detail: {
                                    id,
                                    instance: this
                                }
                            }));
                        };

                    let classes = 'elementor-popup-modal';

                    if (settings.classes) {
                        classes += ' ' + settings.classes;
                    }

                    const modalProperties = {
                        id: 'elementor-popup-modal-' + id,
                        className: classes,
                        closeButton: true,
                        preventScroll: settings.prevent_scroll,
                        onShow: () => triggerPopupEvent('show'),
                        onHide: () => triggerPopupEvent('hide'),
                        effects: {
                            hide: () => {
                                if (settings.timing && settings.timing.times_count) {
                                    this.countTimes();
                                }

                                this.setExitAnimation();
                            },
                            show: 'show'
                        },
                        hide: {
                            auto: !!settings.close_automatically,
                            autoDelay: settings.close_automatically * 1000,
                            onBackgroundClick: !settings.prevent_close_on_background_click,
                            onOutsideClick: !settings.prevent_close_on_background_click,
                            onEscKeyPress: !settings.prevent_close_on_esc_key,
                            ignore: '.flatpickr-calendar'
                        },
                        position: {
                            enable: false
                        }
                    };

                    modalProperties.closeButtonOptions = {
                        iconElement: createSvgElement('close', {
                            path: 'M13.7 34.3c-3.1-3.1-8.2-3.1-11.3 0s-3.1 8.2 0 11.3L212.7 256 2.3 466.3c-3.1 3.1-3.1 8.2 0 11.3s8.2 3.1 11.3 0L224 267.3 434.3 477.7c3.1 3.1 8.2 3.1 11.3 0s3.1-8.2 0-11.3L235.3 256 445.7 45.7c3.1-3.1 3.1-8.2 0-11.3s-8.2-3.1-11.3 0L224 244.7 13.7 34.3z',
                            width: 448,
                            height: 512
                        }),
                    };

                    modalProperties.closeButtonClass = 'eicon-close';
                    modal = elementorFrontend.getDialogsManager().createWidget('lightbox', modalProperties);
                    modal.getElements('widgetContent').addClass('animated');
                    const $closeButton = modal.getElements('closeButton');

                    if (this.isEdit) {
                        $closeButton.off('click');

                        modal.hide = () => {};
                    }

                    this.setCloseButtonPosition();
                }

                return modal;
            };
        }

        setCloseButtonPosition() {
            const modal = this.getModal(),
                closeButtonPosition = this.getDocumentSettings('close_button_position'),
                $closeButton = modal.getElements('closeButton');
            $closeButton.appendTo(modal.getElements('outside' === closeButtonPosition ? 'widget' : 'widgetContent'));
        }

        disable() {
            this.setStorage('disable', true);
        }

        setStorage(key, value, options) {
            elementorFrontend.storage.set(`popup_${this.getSettings('id')}_${key}`, value, options);
        }

        getStorage(key, options) {
            return elementorFrontend.storage.get(`popup_${this.getSettings('id')}_${key}`, options);
        }

        countTimes() {
            const displayTimes = this.getStorage('times') || 0;
            this.setStorage('times', displayTimes + 1);
        }

        runElementsHandlers() {}

        async onInit() {

            super.onInit(); // In case that the library was not loaded, it indicates a Core version that enables dynamic loading.

            if (!window.DialogsManager) {
                await elementorFrontend.utils.assetsLoader.load('script', 'dialog');
            }

            this.initModal();

            if (this.isEdit) {
                this.showModal();
                return;
            }

            this.$element.show().remove();
            this.elementHTML = this.$element[0].outerHTML;

            if (elementorFrontend.isEditMode()) {
                return;
            }

            if (elementorFrontend.isWPPreviewMode() && elementorFrontend.config.post.id === this.getSettings('id')) {
                this.showModal();
                return;
            }

            this.startTiming();
        }

        onSettingsChange(model) {
            const changedKey = Object.keys(model.changed)[0];

            if (-1 !== changedKey.indexOf('entrance_animation')) {
                this.setEntranceAnimation();
            }

            if ('exit_animation' === changedKey) {
                this.setExitAnimation();
            }

            if ('close_button_position' === changedKey) {
                this.setCloseButtonPosition();
            }
        }

    }

    exports.default = _default;

    /***/ }),
/* 2: ../modules/popup/assets/js/frontend/frontend.js*/
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";
    

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _document = __webpack_require__(1);

    class _default extends elementorModules.Module {
        constructor() {
            super();
            elementorFrontend.hooks.addAction('elementor/frontend/documents-manager/init-classes', this.addDocumentClass);
            elementorFrontend.on('components:init', () => this.onFrontendComponentsInit());

            if (!elementorFrontend.isEditMode() && !elementorFrontend.isWPPreviewMode()) {
                this.setViewsAndSessions();
            }
        }

        addDocumentClass(documentsManager) {
            documentsManager.addDocumentClass('popup', _document.default);
        }

        setViewsAndSessions() {
            const pageViews = elementorFrontend.storage.get('pageViews') || 0;
            elementorFrontend.storage.set('pageViews', pageViews + 1);
            const activeSession = elementorFrontend.storage.get('activeSession', {
                session: true
            });

            if (!activeSession) {
                elementorFrontend.storage.set('activeSession', true, {
                    session: true
                });
                const sessions = elementorFrontend.storage.get('sessions') || 0;
                elementorFrontend.storage.set('sessions', sessions + 1);
            }
        }

        showPopup(settings) {
            const popup = elementorFrontend.documentsManager.documents[settings.id];

            if (!popup) {
                return;
            }

            const modal = popup.getModal();

            if (settings.toggle && modal.isVisible()) {
                modal.hide();
            } else {
                popup.showModal();
            }
        }

        closePopup(settings, event) {
            const popupID = jQuery(event.target).parents('[data-elementor-type="popup"]').data('elementorId');

            if (!popupID) {
                return;
            }

            const document = elementorFrontend.documentsManager.documents[popupID];
            document.getModal().hide();

            if (settings.do_not_show_again) {
                document.disable();
            }
        }

        onFrontendComponentsInit() {
            elementorFrontend.utils.urlActions.addAction('popup:open', settings => this.showPopup(settings));
            elementorFrontend.utils.urlActions.addAction('popup:close', (settings, event) => this.closePopup(settings, event));
        }

    }

    exports.default = _default;

    /***/ }),
/* 3: ../modules/popup/assets/js/frontend/timing.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _pageViews = __webpack_require__(8);

    var _sessions = __webpack_require__(9);

    var _url = __webpack_require__(12);

    var _sources = __webpack_require__(10);

    var _loggedIn = __webpack_require__(7);

    var _devices = __webpack_require__(6);

    var _times = __webpack_require__(11);

    var _browsers = __webpack_require__(5);

    class _default extends elementorModules.Module {
        constructor(settings, document) {
            super(settings);
            this.document = document;
            this.timingClasses = {
                page_views: _pageViews.default,
                sessions: _sessions.default,
                url: _url.default,
                sources: _sources.default,
                logged_in: _loggedIn.default,
                devices: _devices.default,
                times: _times.default,
                browsers: _browsers.default
            };
        }

        check() {
            const settings = this.getSettings();
            let checkPassed = true;
            jQuery.each(this.timingClasses, (key, TimingClass) => {
                if (!settings[key]) {
                    return;
                }

                const timing = new TimingClass(settings, this.document);

                if (!timing.check()) {
                    checkPassed = false;
                }
            });
            return checkPassed;
        }

    }

    exports.default = _default;

    /***/ }),
/* 4: ../modules/popup/assets/js/frontend/timing/base.js */
((__unused_webpack_module, exports) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    class _default extends elementorModules.Module {
        constructor(settings, document) {
            super(settings);
            this.document = document;
        }

        getTimingSetting(settingKey) {
            return this.getSettings(this.getName() + '_' + settingKey);
        }

    }

    exports.default = _default;

    /***/ }),
/* 5: ../modules/popup/assets/js/frontend/timing/browsers.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);

    class _default extends _base.default {
        getName() {
            return 'browsers';
        }

        check() {
            if ('all' === this.getTimingSetting('browsers')) {
                return true;
            }

            const targetedBrowsers = this.getTimingSetting('browsers_options'),
                browserDetectionFlags = elementorFrontend.utils.environment;
            return targetedBrowsers.some(browserName => browserDetectionFlags[browserName]);
        }

    }

    exports.default = _default;

    /***/ }),
/* 6: ../modules/popup/assets/js/frontend/timing/devices.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);

    class _default extends _base.default {
        getName() {
            return 'devices';
        }

        check() {
            return -1 !== this.getTimingSetting('devices').indexOf(elementorFrontend.getCurrentDeviceMode());
        }

    }

    exports.default = _default;

    /***/ }),
/* 7: ../modules/popup/assets/js/frontend/timing/logged-in.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);

    class _default extends _base.default {
        getName() {
            return 'logged_in';
        }

        check() {
            const userConfig = elementorFrontend.config.user;

            if (!userConfig) {
                return true;
            }

            if ('all' === this.getTimingSetting('users')) {
                return false;
            }

            const userRolesInHideList = this.getTimingSetting('roles').filter(role => -1 !== userConfig.roles.indexOf(role));
            return !userRolesInHideList.length;
        }

    }

    exports.default = _default;

    /***/ }),
/* 8: ../modules/popup/assets/js/frontend/timing/page-views.js */
((__unused_webpack_module, exports, __webpack_require__) => {
    "use strict";


    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);

    class _default extends _base.default {
        getName() {
            return 'page_views';
        }

        check() {
            const pageViews = elementorFrontend.storage.get('pageViews'),
                name = this.getName();
            let initialPageViews = this.document.getStorage(name + '_initialPageViews');

            if (!initialPageViews) {
                this.document.setStorage(name + '_initialPageViews', pageViews);
                initialPageViews = pageViews;
            }

            return pageViews - initialPageViews >= this.getTimingSetting('views');
        }

    }

    exports.default = _default;

    /***/ }),
/* 9: ../modules/popup/assets/js/frontend/timing/sessions.js */
 ((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";
    

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);

    class _default extends _base.default {
        getName() {
            return 'sessions';
        }

        check() {
            const sessions = elementorFrontend.storage.get('sessions'),
                name = this.getName();
            let initialSessions = this.document.getStorage(name + '_initialSessions');

            if (!initialSessions) {
                this.document.setStorage(name + '_initialSessions', sessions);
                initialSessions = sessions;
            }

            return sessions - initialSessions >= this.getTimingSetting('sessions');
        }

    }

    exports.default = _default;

    /***/ }),
/* 10: ../modules/popup/assets/js/frontend/timing/sources.js */
 ((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";
    

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);

    class _default extends _base.default {
        getName() {
            return 'sources';
        }

        check() {
            const sources = this.getTimingSetting('sources');

            if (3 === sources.length) {
                return true;
            }

            const referrer = document.referrer.replace(/https?:\/\/(?:www\.)?/, ''),
                isInternal = 0 === referrer.indexOf(location.host.replace('www.', ''));

            if (isInternal) {
                return -1 !== sources.indexOf('internal');
            }

            if (-1 !== sources.indexOf('external')) {
                return true;
            }

            if (-1 !== sources.indexOf('search')) {
                return /^(google|yahoo|bing|yandex|baidu)\./.test(referrer);
            }

            return false;
        }

    }

    exports.default = _default;

    /***/ }),
/* 11: ../modules/popup/assets/js/frontend/timing/times.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);
    var _timesUtils = __webpack_require__(21);

    class _default extends _base.default {

        constructor() {
            super(...arguments);
            this.uniqueId = `popup-${this.document.getSettings('id')}-impressions-count`;
            const {
                times_count: countOnOpen,
                times_period: period,
                times_times: showsLimit
            } = this.getSettings();
            this.settings = {
                countOnOpen,
                period,
                showsLimit: parseInt(showsLimit)
            };
            if ('' === this.settings.period) {
                this.settings.period = false;
            }
            if (['', 'close'].includes(this.settings.countOnOpen)) {
                this.settings.countOnOpen = false;
                this.onPopupHide();
            } else {
                this.settings.countOnOpen = true;
            }
            this.utils = new _timesUtils.default({
                uniqueId: this.uniqueId,
                settings: this.settings,
                storage: elementorFrontend.storage
            });
        }

        getName() {
            return 'times';
        }

        check() {
            if (!this.settings.period) {
                const impressionCount = this.document.getStorage('times') || 0;
                const showsLimit = this.getTimingSetting('times');
                return this.utils.shouldDisplayBackwardCompatible(impressionCount, showsLimit);
            }
            if ('session' !== this.settings.period) {
                if (!this.utils.shouldDisplayPerTimeFrame()) {
                    return false;
                }
            } else if (!this.utils.shouldDisplayPerSession()) {
                return false;
            }
            return true;
        }
        onPopupHide() {
            window.addEventListener('elementor/popup/hide', () => {
                this.utils.incrementImpressionsCount();
            });
        }

    }

    exports.default = _default;

    /***/ }),
/* 12: ../modules/popup/assets/js/frontend/timing/url.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(4);

    class _default extends _base.default {
        getName() {
            return 'url';
        }

        check() {
            const url = this.getTimingSetting('url'),
                action = this.getTimingSetting('action'),
                referrer = document.referrer;

            if ('regex' !== action) {
                return 'hide' === action ^ -1 !== referrer.indexOf(url);
            }

            let regexp;

            try {
                regexp = new RegExp(url);
            } catch (e) {
                return false;
            }

            return regexp.test(referrer);
        }

    }

    exports.default = _default;

    /***/ }),
/* 13: ../modules/popup/assets/js/frontend/triggers.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";
    

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _pageLoad = __webpack_require__(18);

    var _scrolling = __webpack_require__(20);

    var _scrollingTo = __webpack_require__(19);

    var _click = __webpack_require__(15);

    var _inactivity = __webpack_require__(17);

    var _exitIntent = __webpack_require__(16);

    class _default extends elementorModules.Module {
        constructor(settings, document) {
            super(settings);
            this.document = document;
            this.triggers = [];
            this.triggerClasses = {
                page_load: _pageLoad.default,
                scrolling: _scrolling.default,
                scrolling_to: _scrollingTo.default,
                click: _click.default,
                inactivity: _inactivity.default,
                exit_intent: _exitIntent.default
            };
            this.runTriggers();
        }

        runTriggers() {
            const settings = this.getSettings();
            jQuery.each(this.triggerClasses, (key, TriggerClass) => {
                if (!settings[key]) {
                    return;
                }

                const trigger = new TriggerClass(settings, () => this.onTriggerFired());
                trigger.run();
                this.triggers.push(trigger);
            });
        }

        destroyTriggers() {
            this.triggers.forEach(trigger => trigger.destroy());
            this.triggers = [];
        }

        onTriggerFired() {
            this.document.showModal(true);
            this.destroyTriggers();
        }

    }

    exports.default = _default;

    /***/ }),
/* 14: ../modules/popup/assets/js/frontend/triggers/base.js */
((__unused_webpack_module, exports) => {

    "use strict";


    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    class _default extends elementorModules.Module {
        constructor(settings, callback) {
            super(settings);
            this.callback = callback;
        }

        getTriggerSetting(settingKey) {
            return this.getSettings(this.getName() + '_' + settingKey);
        }

    }

    exports.default = _default;

    /***/ }),
/* 15: ../modules/popup/assets/js/frontend/triggers/click.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(14);

    class _default extends _base.default {
        constructor(...args) {
            super(...args);
            this.checkClick = this.checkClick.bind(this);
            this.clicksCount = 0;
        }

        getName() {
            return 'click';
        }

        checkClick() {
            this.clicksCount++;

            if (this.clicksCount === this.getTriggerSetting('times')) {
                this.callback();
            }
        }

        run() {
            elementorFrontend.elements.$body.on('click', this.checkClick);
        }

        destroy() {
            elementorFrontend.elements.$body.off('click', this.checkClick);
        }

    }

    exports.default = _default;

    /***/ }),
/* 16: ../modules/popup/assets/js/frontend/triggers/exit-intent.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(14);

    class _default extends _base.default {
        constructor(...args) {
            super(...args);
            this.detectExitIntent = this.detectExitIntent.bind(this);
        }

        getName() {
            return 'exit_intent';
        }

        detectExitIntent(event) {
            if (event.clientY <= 0) {
                this.callback();
            }
        }

        run() {
            elementorFrontend.elements.$window.on('mouseleave', this.detectExitIntent);
        }

        destroy() {
            elementorFrontend.elements.$window.off('mouseleave', this.detectExitIntent);
        }

    }

    exports.default = _default;

    /***/ }),
/* 17: ../modules/popup/assets/js/frontend/triggers/inactivity.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(14);

    class _default extends _base.default {
        constructor(...args) {
            super(...args);
            this.restartTimer = this.restartTimer.bind(this);
        }

        getName() {
            return 'inactivity';
        }

        run() {
            this.startTimer();
            elementorFrontend.elements.$document.on('keypress mousemove', this.restartTimer);
        }

        startTimer() {
            this.timeOut = setTimeout(this.callback, this.getTriggerSetting('time') * 1000);
        }

        clearTimer() {
            clearTimeout(this.timeOut);
        }

        restartTimer() {
            this.clearTimer();
            this.startTimer();
        }

        destroy() {
            this.clearTimer();
            elementorFrontend.elements.$document.off('keypress mousemove', this.restartTimer);
        }

    }

    exports.default = _default;

    /***/ }),
/* 18: ../modules/popup/assets/js/frontend/triggers/page-load.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(14);

    class _default extends _base.default {
        getName() {
            return 'page_load';
        }

        run() {
            this.timeout = setTimeout(this.callback, this.getTriggerSetting('delay') * 1000);
        }

        destroy() {
            clearTimeout(this.timeout);
        }

    }

    exports.default = _default;

    /***/ }),
/* 19: ../modules/popup/assets/js/frontend/triggers/scrolling-to.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(14);

    class _default extends _base.default {
        getName() {
            return 'scrolling_to';
        }

        run() {
            let $targetElement;

            try {
                $targetElement = jQuery(this.getTriggerSetting('selector'));
            } catch (e) {
                return;
            }

            this.waypointInstance = elementorFrontend.waypoint($targetElement, this.callback)[0];
        }

        destroy() {
            if (this.waypointInstance) {
                this.waypointInstance.destroy();
            }
        }

    }

    exports.default = _default;

    /***/ }),
/* 20: ../modules/popup/assets/js/frontend/triggers/scrolling.js */
((__unused_webpack_module, exports, __webpack_require__) => {

    "use strict";


    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports.default = void 0;

    var _base = __webpack_require__(14);

    class _default extends _base.default {
        constructor(...args) {
            super(...args);
            this.checkScroll = this.checkScroll.bind(this);
            this.lastScrollOffset = 0;
        }

        getName() {
            return 'scrolling';
        }

        checkScroll() {
            const scrollDirection = scrollY > this.lastScrollOffset ? 'down' : 'up',
                requestedDirection = this.getTriggerSetting('direction');
            this.lastScrollOffset = scrollY;

            if (scrollDirection !== requestedDirection) {
                return;
            }

            if ('up' === scrollDirection) {
                this.callback();
                return;
            }

            const fullScroll = elementorFrontend.elements.$document.height() - innerHeight,
                scrollPercent = scrollY / fullScroll * 100;

            if (scrollPercent >= this.getTriggerSetting('offset')) {
                this.callback();
            }
        }

        run() {
            elementorFrontend.elements.$window.on('scroll', this.checkScroll);
        }

        destroy() {
            elementorFrontend.elements.$window.off('scroll', this.checkScroll);
        }

    }

    exports.default = _default;

    /***/ }),
/* 21: ../modules/popup/assets/js/frontend/timing/times-utils.js */
((__unused_webpack_module, exports) => {
    "use strict";

    Object.defineProperty(exports, "__esModule", ({
        value: true
    }));
    exports["default"] = void 0;
    class TimesUtils {
        constructor(args) {
            this.uniqueId = args.uniqueId;
            this.settings = args.settings;
            this.storage = args.storage;
        }
        getTimeFramesInSeconds(timeFrame) {
            const timeFrames = {
                day: 86400,
                // Day in seconds
                week: 604800,
                // Week in seconds
                month: 2628288 // Month in seconds
            };

            return timeFrames[timeFrame];
        }
        setExpiration(name, value, timeFrame) {
            const data = this.storage.get(name);
            if (!data) {
                const options = {
                    lifetimeInSeconds: this.getTimeFramesInSeconds(timeFrame)
                };
                this.storage.set(name, value, options);
                return;
            }
            this.storage.set(name, value);
        }
        getImpressionsCount() {
            const impressionCount = this.storage.get(this.uniqueId) ?? 0;
            return parseInt(impressionCount);
        }
        incrementImpressionsCount() {
            if (!this.settings.period) {
                this.storage.set('times', (this.storage.get('times') ?? 0) + 1);
            } else if ('session' !== this.settings.period) {
                const impressionCount = this.getImpressionsCount();
                this.setExpiration(this.uniqueId, impressionCount + 1, this.settings.period);
            } else {
                sessionStorage.setItem(this.uniqueId, parseInt(sessionStorage.getItem(this.uniqueId) ?? 0) + 1);
            }
        }
        shouldCountOnOpen() {
            if (this.settings.countOnOpen) {
                this.incrementImpressionsCount();
            }
        }
        shouldDisplayPerTimeFrame() {
            const impressionCount = this.getImpressionsCount();
            if (impressionCount < this.settings.showsLimit) {
                this.shouldCountOnOpen();
                return true;
            }
            return false;
        }
        shouldDisplayPerSession() {
            const impressionCount = sessionStorage.getItem(this.uniqueId) ?? 0;
            if (parseInt(impressionCount) < this.settings.showsLimit) {
                this.shouldCountOnOpen();
                return true;
            }
            return false;
        }
        shouldDisplayBackwardCompatible() {
            let impressionCount = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
            let showsLimit = arguments.length > 1 ? arguments[1] : undefined;
            const shouldDisplay = parseInt(impressionCount) < parseInt(showsLimit);
            this.shouldCountOnOpen();
            return shouldDisplay;
        }
    }
    exports["default"] = TimesUtils;

    /***/ })
/******/]);