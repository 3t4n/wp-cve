"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __extends = (this && this.__extends) || (function () {
    var extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
/**
 * Decorators.
 */
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        /**
         * A small helper to inject dependencies dynamically.
         *
         * @param func
         */
        function annotate(func) {
            var $injector = angular.injector(['ng']);
            func.$inject = $injector.annotate(func).map(function (member) { return member.replace(/^_/, ''); });
        }
        /**
         * Injectable decorator.
         *
         * @returns {(Entity: any) => void}
         * @constructor
         */
        function Injectable() {
            return function (Entity) {
                annotate(Entity);
            };
        }
        Common.Injectable = Injectable;
        /**
         * Service decorator.
         *
         * @param {string} moduleName
         * @returns {(Service: any) => void}
         * @constructor
         */
        function Service(moduleName) {
            return function (Service) {
                var module;
                var name = Service.name;
                var isProvider = Service.hasOwnProperty('$get');
                annotate(Service);
                try {
                    module = angular.module(moduleName);
                }
                catch (exception) {
                    module = angular.module(moduleName, []);
                }
                module[isProvider ? 'provider' : 'service'](name, Service);
            };
        }
        Common.Service = Service;
        /**
         * Factory decorator.
         *
         * @param {string} moduleName
         * @param selector
         * @returns {(Factory: any) => void}
         * @constructor
         */
        function Factory(moduleName, selector) {
            return function (Factory) {
                var module;
                var name = selector || ("" + Factory.name.charAt(0).toLowerCase() + Factory.name.slice(1)).replace('Factory', '');
                annotate(Factory);
                try {
                    module = angular.module(moduleName);
                }
                catch (exception) {
                    module = angular.module(moduleName, []);
                }
                module.factory(name, Factory);
            };
        }
        Common.Factory = Factory;
        /**
         * Controller decorator.
         *
         * @param {string} moduleName
         * @returns {(Controller: any) => void}
         * @constructor
         */
        function Controller(moduleName) {
            return function (Controller) {
                var module;
                var name = Controller.name;
                annotate(Controller);
                try {
                    module = angular.module(moduleName);
                }
                catch (exception) {
                    module = angular.module(moduleName, []);
                }
                module.controller(name, Controller);
            };
        }
        Common.Controller = Controller;
        /**
         * Filter decorator.
         *
         * @param {string} moduleName
         * @param selector
         * @returns {(Filter: any) => void}
         * @constructor
         */
        function Filter(moduleName, selector) {
            return function (Filter) {
                var module;
                var name = selector || ("" + Filter.name.charAt(0).toLowerCase() + Filter.name.slice(1)).replace('Filter', '');
                annotate(Filter);
                try {
                    module = angular.module(moduleName);
                }
                catch (exception) {
                    module = angular.module(moduleName, []);
                }
                module.filter(name, Filter);
            };
        }
        Common.Filter = Filter;
        /**
         * Component decorator.
         *
         * @param moduleName
         * @param {angular.IComponentOptions} options
         * @param {any} selector
         * @returns {(Class: any) => void}
         * @constructor
         */
        function Component(moduleName, options, selector) {
            if (selector === void 0) { selector = null; }
            return function (Class) {
                var module;
                selector = selector || ("" + Class.name.charAt(0).toLowerCase() + Class.name.slice(1)).replace('Component', '');
                options.controller = Class;
                annotate(Class);
                try {
                    module = angular.module(moduleName);
                }
                catch (exception) {
                    module = angular.module(moduleName, []);
                }
                module.component(selector, options);
            };
        }
        Common.Component = Component;
        /**
         * Directive decorator.
         *
         * @param moduleName
         * @param {any} selector
         * @returns {(Class: any) => void}
         * @constructor
         */
        function Directive(moduleName, selector) {
            if (selector === void 0) { selector = null; }
            return function (Class) {
                var module;
                selector = selector || ("" + Class.name.charAt(0).toLowerCase() + Class.name.slice(1)).replace('Directive', '');
                annotate(Class);
                try {
                    module = angular.module(moduleName);
                }
                catch (exception) {
                    module = angular.module(moduleName, []);
                }
                module.directive(selector, Class);
            };
        }
        Common.Directive = Directive;
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Providers;
        (function (Providers) {
            var SettingsService = /** @class */ (function () {
                function SettingsService(namespace, prefix) {
                    this.namespace = namespace;
                    this.prefix = prefix;
                    this.account = window[this.namespace + "Account"] || [];
                    this.activation = window[this.namespace + "Activation"] || [];
                    this.defaults = window[this.namespace + "Defaults"] || {};
                    this.i18n = window[this.namespace + "I18n"] || [];
                    this.information = window[this.namespace + "Information"] || {};
                    this.languages = window[this.namespace + "Languages"] || [];
                    this.modules = window[this.namespace + "Modules"] || {};
                    this.presets = window[this.namespace + "Presets"] || [];
                    this.settings = window[this.namespace + "Settings"] || {};
                    this.support = window[this.namespace + "Support"] || [];
                    this.templates = window[this.namespace + "Templates"] || {};
                    this.versions = window[this.namespace + "Versions"] || [];
                    this.settings['id'] = this.defaults['id'];
                    if (this.defaults.expressions && angular.isArray(this.defaults.expressions)) {
                        this.defaults.expressions = {};
                    }
                    this.settings = angular.merge({}, this.defaults, this.settings);
                }
                SettingsService = __decorate([
                    Common.Service('services.common')
                ], SettingsService);
                return SettingsService;
            }());
            Providers.SettingsService = SettingsService;
        })(Providers = Common.Providers || (Common.Providers = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Configs;
        (function (Configs) {
            var GlobalConfig = /** @class */ (function () {
                function GlobalConfig($locationProvider, $compileProvider) {
                    $locationProvider.html5Mode({ enabled: true, requireBase: false, rewriteLinks: false });
                    // $compileProvider.debugInfoEnabled(false);
                    // $compileProvider.commentDirectivesEnabled(false);
                    // $compileProvider.cssClassDirectivesEnabled(false);
                }
                GlobalConfig = __decorate([
                    Common.Injectable()
                ], GlobalConfig);
                return GlobalConfig;
            }());
            Configs.GlobalConfig = GlobalConfig;
        })(Configs = Common.Configs || (Common.Configs = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Configs;
        (function (Configs) {
            var HttpConfig = /** @class */ (function () {
                function HttpConfig($resourceProvider, $httpProvider, $compileProvider) {
                    // Don't strip trailing slashes from calculated URLs
                    $resourceProvider.defaults.stripTrailingSlashes = false;
                    $httpProvider.defaults.transformRequest = function (data) {
                        if (data === undefined) {
                            return data;
                        }
                        return HttpConfig_1.serializer(new FormData(), data);
                    };
                    $httpProvider.defaults.headers.post['Content-Type'] = undefined;
                    $compileProvider.debugInfoEnabled(false);
                }
                HttpConfig_1 = HttpConfig;
                HttpConfig.serializer = function (form, fields, parent) {
                    angular.forEach(fields, function (fieldValue, fieldName) {
                        if (parent) {
                            fieldName = parent + "[" + fieldName + "]";
                        }
                        if (fieldValue !== null && typeof fieldValue === 'object' && (fieldValue.__proto__ === Object.prototype || fieldValue.__proto__ === Array.prototype)) {
                            HttpConfig_1.serializer(form, fieldValue, fieldName);
                        }
                        else {
                            if (typeof fieldValue === 'boolean') {
                                fieldValue = Number(fieldValue);
                            }
                            else if (fieldValue === null) {
                                fieldValue = '';
                            }
                            form.append(fieldName, fieldValue);
                        }
                    });
                    return form;
                };
                HttpConfig = HttpConfig_1 = __decorate([
                    Common.Injectable()
                ], HttpConfig);
                return HttpConfig;
                var HttpConfig_1;
            }());
            Configs.HttpConfig = HttpConfig;
        })(Configs = Common.Configs || (Common.Configs = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Providers;
        (function (Providers) {
            var TabService = /** @class */ (function () {
                function TabService($location, $rootScope) {
                    var _this = this;
                    this.$location = $location;
                    this.$rootScope = $rootScope;
                    this.currentTab = '';
                    this.tabs = {};
                    var urlParams = this.$location.search();
                    $rootScope.isCurrentTab = function (tab) {
                        return _this.is(tab);
                    };
                    $rootScope.setCurrentTab = function (tab) {
                        var parsed = _this.parse(tab);
                        return _this.set(parsed.group, parsed.name);
                    };
                    $rootScope.getCurrentTab = function () {
                        return _this.currentTab;
                    };
                    var tabs = (urlParams.tab || '')['split']('>');
                    var _loop_1 = function (index) {
                        var group = tabs[index + 1] ? tabs[index] : tabs[index - 1];
                        var tab = tabs[index + 1] || tabs[index];
                        $rootScope.$applyAsync(function () {
                            _this.set(group, tab);
                        });
                    };
                    for (var index = 0; index < tabs.length; index = index + 2) {
                        _loop_1(index);
                    }
                }
                TabService.prototype.get = function (group, name) {
                    return this.tabs[group][name] || false;
                };
                TabService.prototype.is = function (tabName) {
                    return this.currentTab.indexOf(tabName) !== -1;
                };
                TabService.prototype.parse = function (tab) {
                    var composedName;
                    var name;
                    var group;
                    composedName = tab.split('>');
                    name = composedName.pop();
                    group = composedName.pop();
                    return { group: group, name: name, root: composedName.join('>') };
                };
                TabService.prototype.put = function (fullName, group, name, element) {
                    this.tabs[group] = this.tabs[group] || {};
                    this.tabs[group][name] = {
                        element: element,
                        fullName: fullName
                    };
                };
                TabService.prototype.set = function (group, name) {
                    if (!this.tabs[group] || !this.tabs[group][name]) {
                        return;
                    }
                    angular.forEach(this.tabs[group], function (tab, key) {
                        angular.element(document).find("[tab=\"" + tab.fullName + "\"]").removeClass('active');
                        tab.element.removeClass('active');
                    });
                    this.tabs[group][name].element.addClass('active');
                    this.currentTab = this.tabs[group][name].fullName;
                    angular.element(document).find("[tab=\"" + this.currentTab + "\"]").addClass('active');
                    this.$location.search('tab', this.currentTab);
                };
                TabService = __decorate([
                    Common.Service('services.common')
                ], TabService);
                return TabService;
            }());
            Providers.TabService = TabService;
        })(Providers = Common.Providers || (Common.Providers = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
///<reference path="../providers/tab.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Directives;
        (function (Directives) {
            var Tabs = /** @class */ (function () {
                function Tabs(TabService) {
                    return {
                        restrict: 'A',
                        link: function ($scope, element, attributes) {
                            if (!attributes.tabSwitch) {
                                return;
                            }
                            var parsed = TabService.parse(attributes.tabSwitch);
                            if (!parsed.name || parsed.name.trim() == "") {
                                parsed.name = Date.now().toString();
                            }
                            if (!parsed.group || parsed.group.trim() == "") {
                                parsed.group = 'default';
                                element.attr('tab-switch', parsed.group + ">" + parsed.name);
                            }
                            TabService.put("" + (parsed.root ? parsed.root + '>' : '') + parsed.group + ">" + parsed.name, parsed.group, parsed.name, element);
                            element.on('click', function () {
                                $scope.$applyAsync(function () { return TabService.set(parsed.group, parsed.name); });
                                return false;
                            });
                        }
                    };
                }
                Tabs = __decorate([
                    Common.Directive('directives.common', 'tabSwitch')
                ], Tabs);
                return Tabs;
            }());
            Directives.Tabs = Tabs;
        })(Directives = Common.Directives || (Common.Directives = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Directives;
        (function (Directives) {
            var Carousel = /** @class */ (function () {
                function Carousel() {
                    return {
                        restrict: 'A',
                        link: function ($scope, element, attributes) {
                            var $slides = element.find('[carousel-slides-item]');
                            var $controls = element.find('[carousel-controls-item]');
                            var autoSlidingInterval;
                            var startAutoSliding = function () {
                                if (!autoSlidingInterval) {
                                    moveToNext();
                                }
                                else {
                                    clearInterval(autoSlidingInterval);
                                }
                                autoSlidingInterval = setInterval(function () { return moveToNext(); }, 5000);
                            };
                            var stopAutoSliding = function () {
                                clearInterval(autoSlidingInterval);
                            };
                            var moveToNext = function () {
                                moveTo($slides.filter('.active').index() + 1);
                            };
                            var moveTo = function (offset) {
                                var $current = $slides.filter('.active');
                                if ($current.index() === offset) {
                                    return;
                                }
                                if (offset >= $slides.length) {
                                    offset = 0;
                                }
                                $slides.removeClass('previous');
                                $slides.removeClass('active');
                                $slides.eq(offset).addClass('active');
                                $current.addClass('previous');
                                setTimeout(function () {
                                    $current.removeClass('previous');
                                }, 750);
                                $controls.removeClass('active');
                                $controls.eq(offset).addClass('active');
                            };
                            $controls.on('click', function (event) {
                                moveTo(angular.element(event.target).index());
                            });
                            element.on('mouseleave', startAutoSliding);
                            element.on('mouseenter', stopAutoSliding);
                            startAutoSliding();
                        }
                    };
                }
                Carousel = __decorate([
                    Common.Directive('directives.common', 'carousel')
                ], Carousel);
                return Carousel;
            }());
            Directives.Carousel = Carousel;
        })(Directives = Common.Directives || (Common.Directives = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Providers;
        (function (Providers) {
            var Service = TotalCore.Common.Service;
            var RepositoryService = /** @class */ (function () {
                function RepositoryService($resource, ajaxEndpoint, prefix) {
                    this.resource = $resource(ajaxEndpoint, {}, {
                        activate: { method: 'GET', params: { action: prefix + "_dashboard_activate" } },
                        deactivate: { method: 'GET', params: { action: prefix + "_dashboard_deactivate" } },
                        account: { method: 'GET', params: { action: prefix + "_dashboard_account" } },
                    });
                    return this;
                }
                RepositoryService.prototype.postAccount = function (account) {
                    return this.resource.account(account).$promise;
                };
                RepositoryService.prototype.postActivation = function (activation) {
                    return this.resource.activate(activation).$promise;
                };
                RepositoryService.prototype.postDeactivation = function () {
                    return this.resource.deactivate().$promise;
                };
                RepositoryService = __decorate([
                    Service('services.dashboard')
                ], RepositoryService);
                return RepositoryService;
            }());
            Providers.RepositoryService = RepositoryService;
        })(Providers = Dashboard.Providers || (Dashboard.Providers = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
/**
 * Helpers.
 */
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        /**
         * Extraction type.
         */
        var EXTRACT_TYPE;
        (function (EXTRACT_TYPE) {
            EXTRACT_TYPE[EXTRACT_TYPE["Values"] = 0] = "Values";
            EXTRACT_TYPE[EXTRACT_TYPE["Keys"] = 1] = "Keys";
        })(EXTRACT_TYPE = Common.EXTRACT_TYPE || (Common.EXTRACT_TYPE = {}));
        /**
         * Extract values/keys of object.
         *
         * @param object
         * @param {TotalCore.EXTRACT_TYPE} extract
         * @returns {any[]}
         * @private
         */
        function extract(object, extract) {
            var values = [];
            angular.forEach(object, function (value, key) { return values.push(extract === EXTRACT_TYPE.Values ? value : key); });
            return values;
        }
        Common.extract = extract;
        /**
         * Shuffle array.
         *
         * @param {Array} array
         * @returns {Array}
         */
        function shuffle(array) {
            var currentIndex = array.length, temporaryValue, randomIndex;
            while (0 !== currentIndex) {
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }
            return array;
        }
        Common.shuffle = shuffle;
        /**
         * Processable trait.
         */
        var Processable = /** @class */ (function () {
            function Processable() {
                this.processed = false;
                this.processing = false;
            }
            /**
             * Check processed.
             * @returns {boolean}
             */
            Processable.prototype.isProcessed = function () {
                return this.processed;
            };
            /**
             * Check processing.
             * @returns {boolean}
             */
            Processable.prototype.isProcessing = function () {
                return this.processing;
            };
            Processable.prototype.setProcessed = function (processed) {
                this.processed = processed;
            };
            /**
             * Start processing.
             */
            Processable.prototype.startProcessing = function () {
                this.processing = true;
            };
            /**
             * Stop processing.
             */
            Processable.prototype.stopProcessing = function () {
                this.processing = false;
            };
            return Processable;
        }());
        Common.Processable = Processable;
        /**
         * Progressive trait.
         */
        var Progressive = /** @class */ (function (_super) {
            __extends(Progressive, _super);
            function Progressive() {
                var _this = _super !== null && _super.apply(this, arguments) || this;
                _this.progress = false;
                return _this;
            }
            /**
             * Get progress.
             * @returns {Number | Boolean}
             */
            Progressive.prototype.getProgress = function () {
                return this.progress;
            };
            /**
             * Set progress.
             * @param {Number | Boolean} progress
             */
            Progressive.prototype.setProgress = function (progress) {
                this.progress = progress;
            };
            return Progressive;
        }(Processable));
        Common.Progressive = Progressive;
        /**
         * Paginated table.
         */
        var PaginatedTable = /** @class */ (function () {
            function PaginatedTable() {
                this.pagination = {
                    page: 1,
                    total: 1,
                };
            }
            PaginatedTable.prototype.getPage = function () {
                return this.pagination.page;
            };
            PaginatedTable.prototype.getTotalPages = function () {
                return this.pagination.total;
            };
            PaginatedTable.prototype.hasNextPage = function () {
                return !this.isLastPage();
            };
            PaginatedTable.prototype.hasPreviousPage = function () {
                return !this.isFirstPage();
            };
            PaginatedTable.prototype.isFirstPage = function () {
                return this.isPage(1);
            };
            PaginatedTable.prototype.isLastPage = function () {
                return this.getPage() == this.getTotalPages();
            };
            PaginatedTable.prototype.isPage = function (page) {
                return this.getPage() == page;
            };
            PaginatedTable.prototype.nextPage = function () {
                var _this = this;
                var nextPage = this.getPage() + 1;
                return this.fetchPage(nextPage)
                    .then(function (result) {
                    _this.setPage(nextPage);
                    return result;
                });
            };
            PaginatedTable.prototype.previousPage = function () {
                var _this = this;
                var previousPage = this.pagination.page + 1;
                return this.fetchPage(previousPage)
                    .then(function (result) {
                    _this.setPage(previousPage);
                    return result;
                });
            };
            PaginatedTable.prototype.setPage = function (page) {
                this.pagination.page = Math.abs(page);
            };
            PaginatedTable.prototype.setTotalPages = function (total) {
                this.pagination.total = Math.abs(total) || 1;
            };
            return PaginatedTable;
        }());
        Common.PaginatedTable = PaginatedTable;
        /**
         * Transitions
         */
        var Transition = /** @class */ (function () {
            function Transition(element, duration) {
                if (duration === void 0) { duration = 500; }
                this.duration = 500;
                this.element = window['jQuery'](element);
            }
            Transition.prototype.getDuration = function () {
                return this.duration;
            };
            Transition.prototype.getElement = function () {
                return this.element;
            };
            return Transition;
        }());
        Common.Transition = Transition;
        var SimpleTransition = /** @class */ (function (_super) {
            __extends(SimpleTransition, _super);
            function SimpleTransition() {
                return _super !== null && _super.apply(this, arguments) || this;
            }
            SimpleTransition.prototype.in = function (callback, duration) {
                if (duration === void 0) { duration = this.getDuration(); }
                this.getElement().css({ 'visibility': 'visible', 'display': 'inherit' });
                if (callback) {
                    callback();
                }
            };
            SimpleTransition.prototype.out = function (callback, duration) {
                if (duration === void 0) { duration = this.getDuration(); }
                this.getElement().css('visibility', 'hidden');
                if (callback) {
                    callback();
                }
            };
            return SimpleTransition;
        }(Transition));
        Common.SimpleTransition = SimpleTransition;
        var FadeTransition = /** @class */ (function (_super) {
            __extends(FadeTransition, _super);
            function FadeTransition() {
                return _super !== null && _super.apply(this, arguments) || this;
            }
            FadeTransition.prototype.in = function (callback, duration) {
                if (duration === void 0) { duration = this.getDuration(); }
                this.getElement().fadeIn(duration, callback);
            };
            FadeTransition.prototype.out = function (callback, duration) {
                if (duration === void 0) { duration = this.getDuration(); }
                this.getElement().fadeTo(duration, 0.00001, callback);
            };
            return FadeTransition;
        }(Transition));
        Common.FadeTransition = FadeTransition;
        var SlideTransition = /** @class */ (function (_super) {
            __extends(SlideTransition, _super);
            function SlideTransition() {
                return _super !== null && _super.apply(this, arguments) || this;
            }
            SlideTransition.prototype.in = function (callback, duration) {
                if (duration === void 0) { duration = this.getDuration(); }
                this.getElement().slideDown(duration, callback);
            };
            SlideTransition.prototype.out = function (callback, duration) {
                if (duration === void 0) { duration = this.getDuration(); }
                this.getElement().slideUp(duration, callback);
            };
            return SlideTransition;
        }(Transition));
        Common.SlideTransition = SlideTransition;
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
///<reference path="../../common/helpers.ts"/>
///<reference path="../../common/providers/settings.ts"/>
///<reference path="../providers/repository.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var Processable = TotalCore.Common.Processable;
            var DashboardActivationComponent = /** @class */ (function (_super) {
                __extends(DashboardActivationComponent, _super);
                function DashboardActivationComponent(RepositoryService, SettingsService) {
                    var _this = _super.call(this) || this;
                    _this.RepositoryService = RepositoryService;
                    _this.SettingsService = SettingsService;
                    _this.activation = {
                        status: _this.SettingsService.activation['status'] || false,
                        key: _this.SettingsService.activation['key'] || '',
                        email: _this.SettingsService.activation['email'] || '',
                    };
                    return _this;
                }
                DashboardActivationComponent.prototype.validateActivation = function () {
                    var _this = this;
                    this.startProcessing();
                    this.error = null;
                    this.RepositoryService.postActivation(this.activation)
                        .then(function (response) {
                        if (response.success) {
                            _this.activation.status = true;
                        }
                        else {
                            _this.error = response.data;
                        }
                    })
                        .catch(function (error) {
                        _this.error = error.statusText;
                    })
                        .finally(function () { return _this.stopProcessing(); });
                };
                DashboardActivationComponent.prototype.validateDeactivation = function () {
                    var _this = this;
                    this.startProcessing();
                    this.error = null;
                    this.RepositoryService.postDeactivation()
                        .then(function (response) {
                        if (response.success) {
                            _this.activation.status = false;
                            _this.activation.email = "";
                            _this.activation.key = "";
                        }
                        else {
                            _this.error = response.data;
                        }
                    })
                        .catch(function (error) {
                        _this.error = error.statusText;
                    })
                        .finally(function () { return _this.stopProcessing(); });
                };
                DashboardActivationComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-activation-component-template',
                        bindings: {}
                    })
                ], DashboardActivationComponent);
                return DashboardActivationComponent;
            }(Processable));
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Component = TotalCore.Common.Component;
    var Processable = TotalCore.Common.Processable;
    var DashboardMyAccountComponent = /** @class */ (function (_super) {
        __extends(DashboardMyAccountComponent, _super);
        function DashboardMyAccountComponent($scope, RepositoryService, SettingsService) {
            var _this = _super.call(this) || this;
            _this.$scope = $scope;
            _this.RepositoryService = RepositoryService;
            _this.SettingsService = SettingsService;
            _this.account = {
                access_token: _this.SettingsService.account['access_token'] || '',
                email: _this.SettingsService.account['email'] || '',
                status: _this.SettingsService.account['status'] || false,
            };
            return _this;
        }
        DashboardMyAccountComponent.prototype.$onInit = function () {
            var _this = this;
            window.addEventListener('message', function (event) {
                if (event.data.totalsuite && event.data.totalsuite.auth.access_token) {
                    _this.$scope.$applyAsync(function () {
                        _this.account.access_token = event.data.totalsuite.auth.access_token;
                        _this.validate();
                    });
                }
            }, false);
        };
        DashboardMyAccountComponent.prototype.openSignInPopup = function (url) {
            window.open(url, 'popup', 'width=600,height=600');
        };
        DashboardMyAccountComponent.prototype.validate = function () {
            var _this = this;
            this.startProcessing();
            this.error = null;
            this.RepositoryService.postAccount(this.account)
                .then(function (response) {
                if (response.success) {
                    _this.account.status = true;
                    _this.account.email = response.data.email;
                }
                else {
                    _this.error = response.data;
                }
            })
                .catch(function (error) {
                _this.error = error.statusText;
            })
                .finally(function () { return _this.stopProcessing(); });
        };
        DashboardMyAccountComponent = __decorate([
            Component('components.dashboard', {
                templateUrl: 'dashboard-my-account-component-template',
                bindings: {}
            })
        ], DashboardMyAccountComponent);
        return DashboardMyAccountComponent;
    }(Processable));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardAnnouncementComponent = /** @class */ (function () {
                function DashboardAnnouncementComponent() {
                }
                DashboardAnnouncementComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-announcement-component-template',
                        bindings: {}
                    })
                ], DashboardAnnouncementComponent);
                return DashboardAnnouncementComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardCreditsComponent = /** @class */ (function () {
                function DashboardCreditsComponent() {
                }
                DashboardCreditsComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-credits-component-template',
                        bindings: {}
                    })
                ], DashboardCreditsComponent);
                return DashboardCreditsComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardGetStartedComponent = /** @class */ (function () {
                function DashboardGetStartedComponent($sce) {
                    this.$sce = $sce;
                }
                DashboardGetStartedComponent.prototype.getEmbedUrl = function () {
                    return this.$sce.trustAsResourceUrl("https://www.youtube-nocookie.com/embed/" + this.videoId + "?rel=0&amp;showinfo=0");
                };
                DashboardGetStartedComponent.prototype.isPlayingVideo = function (videoId) {
                    return this.videoId === videoId;
                };
                DashboardGetStartedComponent.prototype.playVideo = function (videoId) {
                    this.videoId = videoId;
                };
                DashboardGetStartedComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-get-started-component-template',
                        bindings: {}
                    })
                ], DashboardGetStartedComponent);
                return DashboardGetStartedComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
///<reference path="../../common/providers/settings.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardReviewComponent = /** @class */ (function () {
                function DashboardReviewComponent(SettingsService) {
                    this.SettingsService = SettingsService;
                    this.randomTweet = '';
                }
                DashboardReviewComponent.prototype.$onInit = function () {
                    this.randomTweet = this.getRandomTweet();
                };
                DashboardReviewComponent.prototype.getRandomTweet = function () {
                    return this.SettingsService.presets['tweets'][Math.floor(Math.random() * this.SettingsService.presets['tweets'].length)];
                };
                DashboardReviewComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-review-component-template',
                        bindings: {}
                    })
                ], DashboardReviewComponent);
                return DashboardReviewComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardLinksComponent = /** @class */ (function () {
                function DashboardLinksComponent() {
                }
                DashboardLinksComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-links-component-template',
                        bindings: {
                            heading: "<",
                            description: "<",
                            links: "<",
                        }
                    })
                ], DashboardLinksComponent);
                return DashboardLinksComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardSubscribeComponent = /** @class */ (function () {
                function DashboardSubscribeComponent() {
                }
                DashboardSubscribeComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-subscribe-component-template',
                        bindings: {}
                    })
                ], DashboardSubscribeComponent);
                return DashboardSubscribeComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
///<reference path="../../common/providers/settings.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardSupportComponent = /** @class */ (function () {
                function DashboardSupportComponent(SettingsService) {
                    this.SettingsService = SettingsService;
                    this.sections = this.SettingsService.support['sections'] || [];
                }
                DashboardSupportComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-support-component-template',
                        bindings: {}
                    })
                ], DashboardSupportComponent);
                return DashboardSupportComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
///<reference path="../../common/providers/settings.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardWhatsNewComponent = /** @class */ (function () {
                function DashboardWhatsNewComponent(SettingsService) {
                    this.SettingsService = SettingsService;
                    this.versions = this.SettingsService.versions || [];
                }
                DashboardWhatsNewComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-whats-new-component-template',
                        bindings: {}
                    })
                ], DashboardWhatsNewComponent);
                return DashboardWhatsNewComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Dashboard;
    (function (Dashboard) {
        var Components;
        (function (Components) {
            var Component = TotalCore.Common.Component;
            var DashboardTranslateComponent = /** @class */ (function () {
                function DashboardTranslateComponent() {
                }
                DashboardTranslateComponent = __decorate([
                    Component('components.dashboard', {
                        templateUrl: 'dashboard-translate-component-template',
                        bindings: {}
                    })
                ], DashboardTranslateComponent);
                return DashboardTranslateComponent;
            }());
        })(Components = Dashboard.Components || (Dashboard.Components = {}));
    })(Dashboard = TotalCore.Dashboard || (TotalCore.Dashboard = {}));
})(TotalCore || (TotalCore = {}));
var TotalContest;
(function (TotalContest) {
    var Service = TotalCore.Common.Service;
    var RepositoryService = /** @class */ (function () {
        function RepositoryService($resource, prefix, ajaxEndpoint) {
            this.resource = $resource(ajaxEndpoint, {}, {
                activate: { method: 'GET', params: { action: prefix + "_dashboard_activate" } },
                deactivate: { method: 'GET', params: { action: prefix + "_dashboard_deactivate" } },
                account: { method: 'GET', params: { action: prefix + "_dashboard_account" } },
                contestsOverview: { method: 'GET', params: { action: prefix + "_dashboard_contests_overview" }, isArray: true },
                blogFeed: { method: 'GET', params: { action: prefix + "_dashboard_blog_feed" }, isArray: true },
            });
            return this;
        }
        RepositoryService.prototype.getContestsOverview = function () {
            return this.resource.contestsOverview().$promise;
        };
        RepositoryService.prototype.postAccount = function (account) {
            return this.resource.account(account).$promise;
        };
        RepositoryService.prototype.postActivation = function (activation) {
            return this.resource.activate(activation).$promise;
        };
        RepositoryService.prototype.postDeactivation = function () {
            return this.resource.deactivate().$promise;
        };
        RepositoryService.prototype.getBlogFeed = function () {
            return this.resource.blogFeed().$promise;
        };
        RepositoryService = __decorate([
            Service('services.totalcontest')
        ], RepositoryService);
        return RepositoryService;
    }());
    TotalContest.RepositoryService = RepositoryService;
})(TotalContest || (TotalContest = {}));
var TotalContest;
(function (TotalContest) {
    var Component = TotalCore.Common.Component;
    var DashboardOverviewComponent = /** @class */ (function () {
        function DashboardOverviewComponent(RepositoryService) {
            this.RepositoryService = RepositoryService;
            this.contests = null;
            this.getContests();
        }
        DashboardOverviewComponent.prototype.getContests = function () {
            var _this = this;
            this.RepositoryService.getContestsOverview().then(function (contests) {
                _this.contests = contests;
            });
        };
        DashboardOverviewComponent = __decorate([
            Component('components.totalcontest', {
                templateUrl: 'dashboard-overview-component-template',
                bindings: {}
            })
        ], DashboardOverviewComponent);
        return DashboardOverviewComponent;
    }());
})(TotalContest || (TotalContest = {}));
var TotalContest;
(function (TotalContest) {
    var Component = TotalCore.Common.Component;
    var DashboardBlogFeedComponent = /** @class */ (function () {
        function DashboardBlogFeedComponent(RepositoryService) {
            this.RepositoryService = RepositoryService;
            this.posts = null;
            this.getBlogPosts();
        }
        DashboardBlogFeedComponent.prototype.getBlogPosts = function () {
            var _this = this;
            this.RepositoryService.getBlogFeed().then(function (posts) {
                _this.posts = posts;
            });
        };
        DashboardBlogFeedComponent = __decorate([
            Component('components.totalcontest', {
                templateUrl: 'dashboard-blog-feed-component-template',
                bindings: {}
            })
        ], DashboardBlogFeedComponent);
        return DashboardBlogFeedComponent;
    }());
})(TotalContest || (TotalContest = {}));
///<reference path="../../../../build/typings/index.d.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/decorators.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/providers/settings.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/global.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/http.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/directives/tab.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/directives/carousel.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/providers/repository.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/activation.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/my-account.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/announcement.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/credits.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/get-started.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/review.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/links.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/subscribe.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/support.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/whats-new.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/dashboard/components/translate.ts" />
///<reference path="providers/repository.ts"/>
///<reference path="components/overview.ts"/>
///<reference path="components/blog-feed.ts"/>
var TotalContest;
(function (TotalContest) {
    var GlobalConfig = TotalCore.Common.Configs.GlobalConfig;
    var HttpConfig = TotalCore.Common.Configs.HttpConfig;
    TotalContest.dashboard = angular
        .module('dashboard', [
        'ngResource',
        'services.common',
        'directives.common',
        'services.dashboard',
        'components.dashboard',
        'services.totalcontest',
        'components.totalcontest',
    ])
        .config(GlobalConfig)
        .config(HttpConfig)
        .value('ajaxEndpoint', window['totalcontestAjaxURL'] || window['ajaxurl'] || '/wp-admin/admin-ajax.php')
        .value('namespace', 'TotalContest')
        .value('prefix', 'totalcontest');
})(TotalContest || (TotalContest = {}));

//# sourceMappingURL=maps/dashboard.js.map
