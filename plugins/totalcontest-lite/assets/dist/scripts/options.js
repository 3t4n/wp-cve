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
var TotalContest;
(function (TotalContest) {
    var Service = TotalCore.Common.Service;
    var RepositoryService = /** @class */ (function () {
        function RepositoryService($resource, prefix, ajaxEndpoint) {
            this.resource = $resource(ajaxEndpoint, {}, {
                saveOptions: {
                    method: 'POST',
                    params: { action: prefix + "_options_save_options" }
                },
                migrateContests: {
                    method: 'POST',
                    params: { action: prefix + "_options_migrate_contests" }
                },
                purge: {
                    method: 'POST',
                    params: { action: prefix + "_options_purge" },
                }
            });
            return this;
        }
        RepositoryService.prototype.getDebugInformation = function () {
            return window['TotalContestDebugInformation'] || {};
        };
        RepositoryService.prototype.getExpressions = function () {
            return window['TotalContestExpressions'] || {};
        };
        RepositoryService.prototype.getMigrationPlugins = function () {
            return window['TotalContestMigrationPlugins'] || {};
        };
        RepositoryService.prototype.getOptions = function () {
            return window['TotalContestOptions'] || {};
        };
        RepositoryService.prototype.postMigrateContests = function (plugin) {
            return this.resource.migrateContests({ plugin: plugin }).$promise;
        };
        RepositoryService.prototype.postOptions = function (options) {
            return this.resource.saveOptions({ options: options }).$promise;
        };
        RepositoryService.prototype.postPurge = function (type) {
            return this.resource.purge({ type: type }).$promise;
        };
        RepositoryService = __decorate([
            Service('services.totalcontest')
        ], RepositoryService);
        return RepositoryService;
    }());
    TotalContest.RepositoryService = RepositoryService;
})(TotalContest || (TotalContest = {}));
///<reference path="../../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/decorators.ts" />
///<reference path="../../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/helpers.ts" />
var TotalContest;
(function (TotalContest) {
    var Controller = TotalCore.Common.Controller;
    var Processable = TotalCore.Common.Processable;
    var OptionsCtrl = /** @class */ (function (_super) {
        __extends(OptionsCtrl, _super);
        function OptionsCtrl($scope, $element, RepositoryService, $timeout) {
            var _this = _super.call(this) || this;
            _this.$scope = $scope;
            _this.$element = $element;
            _this.RepositoryService = RepositoryService;
            _this.$timeout = $timeout;
            _this.debug = _this.RepositoryService.getDebugInformation();
            _this.import = {
                status: 'pending',
                content: ''
            };
            _this.migration = {
                plugins: _this.RepositoryService.getMigrationPlugins()
            };
            _this.options = _this.RepositoryService.getOptions();
            _this.expressions = { original: _this.RepositoryService.getExpressions(), user: _this.options.expressions || {} };
            _this.purging = {
                cache: 'pending'
            };
            $scope.options = _this.options;
            if (!_this.options.expressions || angular.isArray(_this.options.expressions)) {
                _this.options.expressions = {};
            }
            _this.attachListenerToModels();
            _this.prepareDragAndDrop();
            return _this;
        }
        OptionsCtrl.prototype.attachListenerToModels = function () {
            var _this = this;
            var updateOption = function (ngModel) {
                ngModel.$$element.closest('.totalcontest-settings-field').addClass('totalcontest-processing');
                _this.saveOptions();
            };
            angular.element(function () {
                angular.element('.totalcontest-settings-field [ng-model^="$ctrl.options"]').each(function (key, element) {
                    var ngModel = angular.element(element).controller('ngModel');
                    if (ngModel) {
                        ngModel.$viewChangeListeners.push(function () {
                            if (ngModel.$$updateTimeout) {
                                _this.$timeout.cancel(ngModel.$$updateTimeout);
                            }
                            ngModel.$$updateTimeout = _this.$timeout(function () { return updateOption(ngModel); }, 500);
                        });
                    }
                });
            });
        };
        OptionsCtrl.prototype.downloadContent = function (filename, mimetype, content) {
            var link = document.createElement('a');
            link.download = filename;
            link.href = "data:" + mimetype + "," + encodeURIComponent(content);
            link.click();
        };
        OptionsCtrl.prototype.downloadDebugInformation = function () {
            this.downloadContent('debug.json', 'application/json', JSON.stringify(this.debug));
        };
        OptionsCtrl.prototype.downloadSettings = function () {
            this.downloadContent("totalcontest-settings-" + Date.now() + ".json", 'application/json', JSON.stringify(this.options));
        };
        OptionsCtrl.prototype.getMigrationProgress = function (plugin) {
            return Number((plugin.done / plugin.total) * 100).toFixed(0) + '%';
        };
        OptionsCtrl.prototype.hasContestsForMigration = function (plugin) {
            return plugin && (plugin.total - plugin.done) > 0;
        };
        OptionsCtrl.prototype.importSettings = function () {
            var _this = this;
            this.import.status = 'processing';
            try {
                this.options = JSON.parse(this.import.content);
                this.$scope.options = this.options;
                this.saveOptions().finally(function () {
                    _this.import.content = '';
                    _this.import.status = 'done';
                    _this.$timeout(function () { return _this.import.status = 'pending'; }, 3000);
                });
            }
            catch (e) {
                this.import.status = 'pending';
            }
        };
        OptionsCtrl.prototype.isExpressionProcessing = function (expression) {
            return expression && expression.processing;
        };
        OptionsCtrl.prototype.isExpressionSaved = function (expression) {
            return expression && expression.success;
        };
        OptionsCtrl.prototype.isImportReady = function () {
            return this.import.content.length && this.import.status === 'pending';
        };
        OptionsCtrl.prototype.isImported = function () {
            return this.import.status === 'done';
        };
        OptionsCtrl.prototype.isImporting = function () {
            return this.import.status === 'processing';
        };
        OptionsCtrl.prototype.isMigrationFinished = function (plugin) {
            return plugin && plugin.migrated;
        };
        OptionsCtrl.prototype.isMigrationProcessing = function (plugin) {
            return plugin && plugin.processing;
        };
        OptionsCtrl.prototype.isPurgeReady = function (type) {
            return this.purging[type] === 'pending';
        };
        OptionsCtrl.prototype.isPurged = function (type) {
            return this.purging[type] === 'done';
        };
        OptionsCtrl.prototype.isPurging = function (type) {
            return this.purging[type] === 'processing';
        };
        OptionsCtrl.prototype.migrateContests = function (pluginId, plugin) {
            var _this = this;
            
        };
        OptionsCtrl.prototype.prepareDragAndDrop = function () {
            var _this = this;
            var $textarea = this.$element.find('[tab="options>import-export"] textarea');
            $textarea.on('dragover', function () { return false; });
            $textarea.on('dragend', function () { return false; });
            $textarea.on('drop', function (event) {
                event.preventDefault();
                var file = event['originalEvent'].dataTransfer.files[0];
                var reader = new FileReader();
                if (file.type.indexOf('json') === -1) {
                    return;
                }
                reader.onload = function (event) {
                    _this.$scope.$applyAsync(function () {
                        _this.import.content = event['target']['result'];
                    });
                };
                reader.readAsText(file);
                return false;
            });
        };
        OptionsCtrl.prototype.purge = function (type) {
            var _this = this;
            this.purging[type] = 'processing';
            this.RepositoryService.postPurge(type)
                .finally(function () {
                _this.purging[type] = 'done';
                _this.$timeout(function () {
                    _this.purging[type] = 'pending';
                }, 1000);
            });
        };
        OptionsCtrl.prototype.saveExpression = function (rawExpression, expression) {
            var _this = this;
            expression.processing = true;
            this.options.expressions[rawExpression] = this.expressions.user[rawExpression];
            this.saveOptions()
                .then(function (response) {
                expression.success = true;
                if (response.error) {
                    expression.processing = false;
                }
                else {
                    _this.$timeout(function () {
                        expression.processing = false;
                        expression.success = false;
                    }, 1000);
                }
            })
                .finally(function () {
                expression.processing = false;
            });
        };
        OptionsCtrl.prototype.saveOptions = function () {
            var _this = this;
            this.startProcessing();
            return this.RepositoryService.postOptions(JSON.stringify(this.options))
                .then(function (response) {
                if (!response.error) {
                    angular.element('.totalcontest-settings-field.totalcontest-processing').removeClass('totalcontest-processing').addClass('totalcontest-successful');
                    _this.setProcessed(true);
                }
                _this.$timeout(function () {
                    _this.setProcessed(false);
                    angular.element('.totalcontest-processing, .totalcontest-successful').removeClass('totalcontest-processing totalcontest-successful');
                }, 500);
                return response;
            })
                .catch(function (error) {
                _this.setProcessed(false);
                return error;
            })
                .finally(function () {
                _this.stopProcessing();
            });
        };
        OptionsCtrl = __decorate([
            Controller('controllers.totalcontest')
        ], OptionsCtrl);
        return OptionsCtrl;
    }(Processable));
    TotalContest.OptionsCtrl = OptionsCtrl;
})(TotalContest || (TotalContest = {}));
///<reference path="../../../../build/typings/index.d.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/decorators.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/providers/settings.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/global.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/http.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/directives/tab.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/filters/table.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/directives/click-tracker.ts" />
///<reference path="providers/repository.ts"/>
///<reference path="controllers/options.ts" />
var TotalContest;
(function (TotalContest) {
    var GlobalConfig = TotalCore.Common.Configs.GlobalConfig;
    var HttpConfig = TotalCore.Common.Configs.HttpConfig;
    TotalContest.dashboard = angular
        .module('options', [
        'ngResource',
        'services.common',
        'directives.common',
        'filters.common',
        'services.totalcontest',
        'controllers.totalcontest',
    ])
        .config(GlobalConfig)
        .config(HttpConfig)
        .value('ajaxEndpoint', window['totalcontestAjaxURL'] || window['ajaxurl'] || '/wp-admin/admin-ajax.php')
        .value('namespace', 'TotalContest')
        .value('prefix', 'totalcontest');
})(TotalContest || (TotalContest = {}));

//# sourceMappingURL=maps/options.js.map
