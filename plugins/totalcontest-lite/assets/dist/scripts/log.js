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
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Models;
        (function (Models) {
            var Model = /** @class */ (function () {
                function Model(attributes) {
                    this.attributes = attributes;
                }
                Model.prototype.get = function (prop, defaultValue) {
                    if (defaultValue === void 0) { defaultValue = null; }
                    try {
                        var path_1 = this.attributes;
                        prop.split('.').forEach(function (part) {
                            path_1 = path_1[part];
                        });
                        return typeof path_1 === 'undefined' ? defaultValue : path_1;
                    }
                    catch (ex) {
                        return defaultValue;
                    }
                };
                Model.prototype.getFlatten = function () {
                    var result = {};
                    function recurse(cur, prop) {
                        if (Object(cur) !== cur) {
                            result[prop] = cur;
                        }
                        else if (Array.isArray(cur)) {
                            for (var i = 0, l = cur.length; i < l; i++)
                                recurse(cur[i], prop + "[" + i + "]");
                            if (l == 0)
                                result[prop] = [];
                        }
                        else {
                            var isEmpty = true;
                            for (var p in cur) {
                                isEmpty = false;
                                recurse(cur[p], prop ? prop + "." + p : p);
                            }
                            if (isEmpty && prop)
                                result[prop] = {};
                        }
                    }
                    recurse(this.getRaw(), "");
                    return result;
                };
                Model.prototype.getId = function () {
                    return this.get('id');
                };
                Model.prototype.getRaw = function () {
                    return this.attributes;
                };
                Model.prototype.set = function (prop, value) {
                    var path = this.attributes;
                    var parts = prop.split('.');
                    parts.forEach(function (part, index) {
                        if (!path[part]) {
                            path[part] = {};
                        }
                        if (index == (parts.length - 1)) {
                            path[part] = value;
                        }
                        else {
                            path = path[part];
                        }
                    });
                    return path;
                };
                return Model;
            }());
            Models.Model = Model;
        })(Models = Common.Models || (Common.Models = {}));
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
///<reference path="../helpers.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Filters;
        (function (Filters) {
            var TABLE_FILTER_MODES;
            (function (TABLE_FILTER_MODES) {
                TABLE_FILTER_MODES["Horizontal"] = "horizontal";
                TABLE_FILTER_MODES["Vertical"] = "vertical";
                TABLE_FILTER_MODES["Group"] = "group";
            })(TABLE_FILTER_MODES || (TABLE_FILTER_MODES = {}));
            var TABLE_CELL_TYPES;
            (function (TABLE_CELL_TYPES) {
                TABLE_CELL_TYPES["Header"] = "th";
                TABLE_CELL_TYPES["Data"] = "td";
            })(TABLE_CELL_TYPES || (TABLE_CELL_TYPES = {}));
            var TABLE_GROUP_TYPES;
            (function (TABLE_GROUP_TYPES) {
                TABLE_GROUP_TYPES["Header"] = "thead";
                TABLE_GROUP_TYPES["Body"] = "tbody";
                TABLE_GROUP_TYPES["Footer"] = "tfoot";
            })(TABLE_GROUP_TYPES || (TABLE_GROUP_TYPES = {}));
            var Table = /** @class */ (function () {
                function Table($sce) {
                    return function (input, mode) {
                        if (mode === void 0) { mode = TABLE_FILTER_MODES.Vertical; }
                        return $sce.trustAsHtml(Table_1.filter(input, mode));
                    };
                }
                Table_1 = Table;
                Table.filter = function (obj, mode, level) {
                    if (level === void 0) { level = 1; }
                    if (!angular.isObject(obj) && !angular.isArray(obj)) {
                        return obj.toString();
                    }
                    var header = [];
                    var body = [];
                    var footer = [];
                    angular.forEach(obj, function (value, key) {
                        if ((angular.isObject(value) || angular.isArray(value)) && mode !== TABLE_FILTER_MODES.Group) {
                            value = Table_1.filter(value, mode, level + 1);
                        }
                        else if (value === null) {
                            return;
                        }
                        switch (mode) {
                            case TABLE_FILTER_MODES.Horizontal:
                                header.push(Table_1.cell(key, TABLE_CELL_TYPES.Header));
                                if (!body[0]) {
                                    body[0] = [];
                                }
                                body[0].push([Table_1.cell(value)]);
                                break;
                            case TABLE_FILTER_MODES.Vertical:
                                body.push([Table_1.cell(key), Table_1.cell(value)]);
                                break;
                            case TABLE_FILTER_MODES.Group:
                                if (header.length === 0) {
                                    header = Common.extract(value, Common.EXTRACT_TYPE.Keys).map(function (item) { return Table_1.cell(item, TABLE_CELL_TYPES.Header); });
                                }
                                body.push(Common.extract(value, Common.EXTRACT_TYPE.Values).map(function (item) { return Table_1.cell(item); }));
                                break;
                        }
                    });
                    return Table_1.table(header, body.map(Table_1.row).join(''), footer);
                };
                Table.cell = function (value, type) {
                    if (type === void 0) { type = TABLE_CELL_TYPES.Data; }
                    return "<" + type + ">" + value + "</" + type + ">";
                };
                Table.row = function (cells) {
                    return cells.length ? "<tr>" + cells.join(' ') + "</tr>" : '';
                };
                Table.group = function (rows, type) {
                    if (type === void 0) { type = TABLE_GROUP_TYPES.Body; }
                    return "<" + type + ">" + (angular.isArray(rows) ? rows.join('') : rows) + "</" + type + ">";
                };
                Table.table = function (header, body, footer) {
                    return "<table class=\"widefat\">\n                        " + this.group(header, TABLE_GROUP_TYPES.Header) + "\n                        " + this.group(body, TABLE_GROUP_TYPES.Body) + "\n                        " + this.group(footer, TABLE_GROUP_TYPES.Footer) + "\n                    </table>";
                };
                Table = Table_1 = __decorate([
                    Common.Filter('filters.common', 'table')
                ], Table);
                return Table;
                var Table_1;
            }());
            Filters.Table = Table;
        })(Filters = Common.Filters || (Common.Filters = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
///<reference path="../helpers.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Filters;
        (function (Filters) {
            var Platform = /** @class */ (function () {
                function Platform() {
                    return function (input) {
                        return Platform_1.filter(input);
                    };
                }
                Platform_1 = Platform;
                Platform.filter = function (value) {
                    return window['platform'].parse(value);
                };
                Platform = Platform_1 = __decorate([
                    Common.Filter('filters.common', 'platform')
                ], Platform);
                return Platform;
                var Platform_1;
            }());
            Filters.Platform = Platform;
        })(Filters = Common.Filters || (Common.Filters = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Directives;
        (function (Directives) {
            var Datetimepicker = /** @class */ (function () {
                function Datetimepicker() {
                    return {
                        restrict: 'A',
                        require: 'ngModel',
                        scope: {
                            'model': '=ngModel'
                        },
                        link: function ($scope, element, attributes, ngModel) {
                            var defaultOptions = {
                                format: 'm/d/Y H:i',
                                validateOnBlur: false
                            };
                            var userOptions = JSON.parse(attributes.datetimePicker || "{}");
                            var mergedOptions = angular.merge({}, defaultOptions, userOptions);
                            element.datetimepicker(mergedOptions);
                            $scope.$watch('model', function (date, oldDate) {
                                if (date != oldDate) {
                                    element.val(date);
                                }
                            });
                        }
                    };
                }
                Datetimepicker = __decorate([
                    Common.Directive('directives.common', 'datetimePicker')
                ], Datetimepicker);
                return Datetimepicker;
            }());
            Directives.Datetimepicker = Datetimepicker;
        })(Directives = Common.Directives || (Common.Directives = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../common/decorators.ts"/>
///<reference path="../../common/configs/Global.ts" />
///<reference path="../../common/configs/Http.ts" />
var TotalCore;
(function (TotalCore) {
    var Common;
    (function (Common) {
        var Directives;
        (function (Directives) {
            var ClickTracker = /** @class */ (function () {
                function ClickTracker($resource, prefix, ajaxEndpoint) {
                    var resource = $resource(ajaxEndpoint, {}, {
                        track: { method: 'POST', params: { action: prefix + "_tracking_features" } },
                    });
                    return {
                        restrict: 'A',
                        link: function ($scope, element, attributes) {
                            var data = $scope.$eval(attributes.track);
                            element.on('click', function () {
                                resource.track(data);
                            });
                        }
                    };
                }
                ClickTracker = __decorate([
                    Common.Directive('directives.common', 'track')
                ], ClickTracker);
                return ClickTracker;
            }());
            Directives.ClickTracker = ClickTracker;
        })(Directives = Common.Directives || (Common.Directives = {}));
    })(Common = TotalCore.Common || (TotalCore.Common = {}));
})(TotalCore || (TotalCore = {}));
///<reference path="../../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/decorators.ts" />
///<reference path="../../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/models/model.ts" />
var TotalContest;
(function (TotalContest) {
    var Model = TotalCore.Common.Models.Model;
    var Entry = /** @class */ (function (_super) {
        __extends(Entry, _super);
        function Entry(attrs) {
            var _this = _super.call(this, attrs) || this;
            _this.set('details', _this.flatten(_this.get('details', {})));
            return _this;
        }
        Entry.prototype.getAction = function () {
            return this.get('action');
        };
        Entry.prototype.getContestEditLink = function () {
            return this.get('contest.admin.editLink');
        };
        Entry.prototype.getContestTitle = function () {
            return this.get('contest.title');
        };
        Entry.prototype.getDate = function () {
            return this.get('date');
        };
        Entry.prototype.getDetails = function () {
            return this.get('details', {});
        };
        Entry.prototype.getIP = function () {
            return this.get('ip');
        };
        Entry.prototype.getStatus = function () {
            return this.get('status');
        };
        Entry.prototype.getSubmissionEditLink = function () {
            return this.get('submission.admin.editLink');
        };
        Entry.prototype.getSubmissionTitle = function () {
            return this.get('submission.title');
        };
        Entry.prototype.getUser = function (field) {
            if (field) {
                return this.get("user." + field);
            }
            return this.get('user');
        };
        Entry.prototype.getUseragent = function () {
            return this.get('useragent');
        };
        Entry.prototype.isAccepted = function () {
            return this.getStatus() === 'Accepted';
        };
        Entry.prototype.isRejected = function () {
            return this.getStatus() === 'Rejected';
        };
        Entry.prototype.flatten = function (data) {
            var result = {};
            function recurse(cur, prop) {
                if (Object(cur) !== cur) {
                    result[prop] = cur;
                }
                else if (Array.isArray(cur)) {
                    for (var i = 0, l = cur.length; i < l; i++)
                        recurse(cur[i], prop + "[" + i + "]");
                    if (l == 0)
                        result[prop] = [];
                }
                else {
                    var isEmpty = true;
                    for (var p in cur) {
                        isEmpty = false;
                        recurse(cur[p], prop ? prop + "." + p : p);
                    }
                    if (isEmpty && prop)
                        result[prop] = {};
                }
            }
            recurse(data, "");
            return result;
        };
        return Entry;
    }(Model));
    TotalContest.Entry = Entry;
})(TotalContest || (TotalContest = {}));
var TotalContest;
(function (TotalContest) {
    var Service = TotalCore.Common.Service;
    var RepositoryService = /** @class */ (function () {
        function RepositoryService($resource, $httpParamSerializerJQLike, prefix, ajaxEndpoint, contestId, submissionId) {
            this.$httpParamSerializerJQLike = $httpParamSerializerJQLike;
            this.prefix = prefix;
            this.ajaxEndpoint = ajaxEndpoint;
            this.contestId = contestId;
            this.submissionId = submissionId;
            this.resource = $resource(ajaxEndpoint, {}, {
                list: { method: 'GET', cache: true, params: { contest: contestId, submission: submissionId } },
                status: { method: 'GET', cache: false },
                remove: { method: 'POST', cache: true }
            });
            return this;
        }
        RepositoryService.prototype.getPage = function (page, filters) {
            if (filters === void 0) { filters = {}; }
            return this.resource
                .list(angular.extend({}, { action: this.prefix + "_log_list", page: page }, filters))
                .$promise
                .then(function (response) {
                response['entries'] = response['entries'].map(function (entry) {
                    return new TotalContest.Entry(entry);
                });
                return response;
            });
        };
        RepositoryService.prototype.getExport = function (format, filters) {
            if (filters === void 0) { filters = {}; }
            return this.resource
                .list(angular.extend({}, { action: this.prefix + "_log_export", contest: this.contestId, submission: this.submissionId, format: format }, filters))
                .$promise
                .then(function (response) {
                return response.data;
            });
        };
        RepositoryService.prototype.getExportStatus = function (uid) {
            return this.resource
                .status({ action: this.prefix + "_log_export_status", uid: uid })
                .$promise
                .then(function (response) {
                return response.data;
            });
        };
        RepositoryService.prototype.getDownload = function (format, filters) {
            if (filters === void 0) { filters = {}; }
            var iframe = document.createElement('iframe');
            var query = this.$httpParamSerializerJQLike(angular.extend({}, { action: this.prefix + "_log_download", contest: this.contestId, submission: this.submissionId, format: format }, filters));
            iframe.src = this.ajaxEndpoint + "?" + query;
            document.body.appendChild(iframe);
        };
        RepositoryService.prototype.remove = function (id) {
            return this.resource.remove({
                action: this.prefix + "_log_remove",
                id: id
            }).$promise;
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
    var Processable = TotalCore.Common.Processable;
    var LogBrowserComponent = /** @class */ (function (_super) {
        __extends(LogBrowserComponent, _super);
        function LogBrowserComponent(RepositoryService, $location) {
            var _this = _super.call(this) || this;
            _this.RepositoryService = RepositoryService;
            _this.$location = $location;
            _this.columns = {
                'status': true,
                'action': true,
                'date': true,
                'ip': true,
                'user_id': false,
                'user_login': false,
                'user_name': true,
                'user_email': false,
                'browser': true,
            };
            _this.currentPage = 1;
            _this.entries = [];
            _this.filters = {};
            _this.labels = {
                'user': 'User',
                'sentVia': 'Sent via',
                'env': 'Environment',
            };
            _this.lastPage = true;
            _this.exporting = false;
            _this.exports = [];
            var urlParams = $location.search();
            _this.currentPage = Math.abs(Number(urlParams['current']) || 1);
            _this.filters.from = urlParams['from'] || null;
            _this.filters.to = urlParams['to'] || null;
            return _this;
        }
        LogBrowserComponent.prototype.$onInit = function () {
            this.loadPage(this.currentPage);
        };
        LogBrowserComponent.prototype.canExport = function () {
            return this.entries.length > 0;
        };
        LogBrowserComponent.prototype.exportAs = function (format) {
            var _this = this;
            //this.RepositoryService.getDownload(format, this.filters);
            var exportModel = {
                status: 'requested',
                format: format
            };
            this.exports.push(exportModel);
            this.RepositoryService.getExport(format, this.filters)
                .then(function (response) {
                _this.exports[_this.exports.indexOf(exportModel)] = response;
                _this.refreshExportStatus(response);
                setTimeout(function () {
                    document.querySelector('.totalcontest-log-browser-exports')
                        .scrollIntoView({ behavior: "smooth" });
                }, 10);
            })
                .catch(function (response) {
                _this.exports.splice(_this.exports.indexOf(exportModel), 1);
            })
                .finally(function () {
            });
        };
        LogBrowserComponent.prototype.refreshExportStatus = function (exportModel) {
            var _this = this;
            if (!exportModel.timeout) {
                exportModel.timeout = setTimeout(function () {
                    _this.RepositoryService
                        .getExportStatus(exportModel.uid)
                        .then(function (response) {
                        _this.exports[_this.exports.indexOf(exportModel)] = exportModel = response;
                        if (exportModel.status !== 'finished') {
                            _this.refreshExportStatus(exportModel);
                        }
                    });
                }, 5000);
            }
        };
        LogBrowserComponent.prototype.exportPercentage = function (exportModel) {
            return (((exportModel.processed || 0) / (exportModel.total || 1)) * 100).toFixed(0);
        };
        LogBrowserComponent.prototype.hasNextPage = function () {
            return !this.lastPage;
        };
        LogBrowserComponent.prototype.hasPreviousPage = function () {
            return this.currentPage != 1;
        };
        LogBrowserComponent.prototype.isFirstPage = function () {
            return this.currentPage === 1;
        };
        LogBrowserComponent.prototype.isLastPage = function () {
            return this.lastPage;
        };
        LogBrowserComponent.prototype.isPage = function (page) {
            return this.currentPage == page;
        };
        LogBrowserComponent.prototype.loadPage = function (page) {
            var _this = this;
            this.startProcessing();
            this.RepositoryService
                .getPage(page, this.filters)
                .then(function (response) {
                _this.currentPage = page;
                _this.entries = response['entries'];
                _this.lastPage = response['lastPage'];
                _this.updateUrl();
            })
                .catch(function (response) {
                if (confirm("Something went wrong in the last operation (" + response.status + " " + (response.statusText || 'Unknown error') + "). Retry?")) {
                    _this.loadPage(page);
                }
            })
                .finally(function () {
                _this.stopProcessing();
            });
        };
        LogBrowserComponent.prototype.removeItem = function (item, $event) {
            var _this = this;
            var confirmation = $event.altKey ? true : confirm('Are you sure? this is an irreversible action! You can bypass this confirmation next time by holding the ALT key.');
            if (confirmation) {
                this.startProcessing();
                this.RepositoryService.remove(item.attributes.id).then(function (response) {
                    if (response.success) {
                        $event.target.closest('tr').remove();
                    }
                    else {
                        alert('Unable to remove item');
                    }
                }).finally(function () {
                    _this.stopProcessing();
                });
            }
        };
        LogBrowserComponent.prototype.nextPage = function () {
            this.loadPage(this.currentPage + 1);
        };
        LogBrowserComponent.prototype.previousPage = function () {
            this.loadPage(this.currentPage - 1);
        };
        LogBrowserComponent.prototype.resetFilters = function () {
            this.filters.from = null;
            this.filters.to = null;
            this.loadPage(1);
        };
        LogBrowserComponent.prototype.updateUrl = function () {
            this.$location.search(angular.extend({}, this.$location.search(), this.filters, { current: this.currentPage }));
        };
        LogBrowserComponent.prototype.isExporting = function () {
            return this.exporting;
        };
        LogBrowserComponent = __decorate([
            Component('components.totalcontest', {
                templateUrl: 'log-browser-component-template',
                bindings: {}
            })
        ], LogBrowserComponent);
        return LogBrowserComponent;
    }(Processable));
})(TotalContest || (TotalContest = {}));
///<reference path="../../../../build/typings/index.d.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/decorators.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/providers/settings.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/global.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/http.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/models/model.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/directives/tab.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/filters/table.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/filters/platform.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/directives/datetimepicker.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/directives/click-tracker.ts" />
///<reference path="models/entry.ts" />
///<reference path="providers/repository.ts" />
///<reference path="components/browser.ts" />
var TotalContest;
(function (TotalContest) {
    var GlobalConfig = TotalCore.Common.Configs.GlobalConfig;
    var HttpConfig = TotalCore.Common.Configs.HttpConfig;
    TotalContest.log = angular
        .module('log', [
        'ngResource',
        'services.common',
        'filters.common',
        'directives.common',
        'services.totalcontest',
        'components.totalcontest',
    ])
        .config(GlobalConfig)
        .config(HttpConfig)
        .value('ajaxEndpoint', window['totalcontestAjaxURL'] || window['ajaxurl'] || '/wp-admin/admin-ajax.php')
        .value('namespace', 'TotalContest')
        .value('prefix', 'totalcontest')
        .value('contestId', window['TotalContestLog']['contestId'] || null)
        .value('submissionId', window['TotalContestLog']['submissionId'] || null);
})(TotalContest || (TotalContest = {}));

//# sourceMappingURL=maps/log.js.map
