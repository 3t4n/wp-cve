"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
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
///<reference path="../../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/decorators.ts" />
var TotalContest;
(function (TotalContest) {
    var Controller = TotalCore.Common.Controller;
    // @ts-ignore
    var SubmissionsListingCtrl = /** @class */ (function () {
        function SubmissionsListingCtrl($scope, $compile, $sce) {
            this.$scope = $scope;
            this.$compile = $compile;
            this.$sce = $sce;
            this.NO_PREVIEW = 'data:image/png;base64,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';
            this.colspan = 8;
            try {
                angular.element(document).ready(function () {
                    var $list = angular.element('#the-list');
                    this.colspan = angular.element('#the-list tr:first-child td, #the-list tr:first-child th').length;
                    $list.find('tr').each(function (index, element) {
                        var $element = angular.element(this);
                        var id = $element.find('input[name="post[]"]').val();
                        $element.attr('ng-class', "{active: $ctrl.isPreviewOf(" + id + ")}");
                        $element.find('.column-inline-preview button').attr('ng-click', "$ctrl.togglePreview(" + id + ")");
                        $element.find('.row-actions .confirm a').attr('ng-click', "$ctrl.approveSubmission(" + id + ", $event)");
                        $element.after("<tr class=\"hidden\" ng-if=\"$ctrl.isPreviewOf(" + id + ")\"></tr><tr ng-if=\"$ctrl.isPreviewOf(" + id + ")\"><td ng-include=\"'preview-submission-template'\" ng-init=\"submission = $ctrl.getSubmission(" + id + ")\" ng-attr-colspan=\"{{$ctrl.colspan}}\" class=\"totalcontest-submission\"></td></tr>");
                    });
                    $compile($list)($scope.$new());
                });
            }
            catch (e) {
                console.debug(e);
            }
        }
        SubmissionsListingCtrl.prototype.approveSubmission = function (submissionId, event) {
            event.preventDefault();
            var $state = jQuery("#post-" + submissionId + " .post-state:first");
            var $link = jQuery(event.currentTarget);
            var $action = $link.parent();
            $state.html('Approving, ');
            $action.hide();
            jQuery.ajax($link.attr('href'))
                .then(function () {
                $state.html('Approved, ');
                $action.remove();
                setTimeout(function () { return $state.remove(); }, 2000);
            })
                .fail(function () {
                $state.html('Error, ');
                $action.show();
            });
        };
        SubmissionsListingCtrl.prototype.getContent = function (submissionId) {
            return this.$sce.trustAsHtml(this.getSubmission(submissionId).content);
        };
        SubmissionsListingCtrl.prototype.getPreview = function (submissionId) {
            var submission = this.getSubmission(submissionId);
            return this.$sce.trustAsHtml(submission.preview || "<img src=\"" + (submission.thumbnail || this.NO_PREVIEW) + "\">");
        };
        SubmissionsListingCtrl.prototype.getSubmission = function (submissionId) {
            return TotalContest.submissions[submissionId];
        };
        SubmissionsListingCtrl.prototype.isPreviewOf = function (submissionId) {
            return this.getSubmission(submissionId) && Boolean(this.getSubmission(submissionId)._preview);
        };
        SubmissionsListingCtrl.prototype.togglePreview = function (submissionId) {
            var submission = this.getSubmission(submissionId);
            submission._preview = !submission._preview;
        };
        SubmissionsListingCtrl = __decorate([
            Controller('controllers.totalcontest')
        ], SubmissionsListingCtrl);
        return SubmissionsListingCtrl;
    }());
    TotalContest.SubmissionsListingCtrl = SubmissionsListingCtrl;
})(TotalContest || (TotalContest = {}));
///<reference path="../../../../build/typings/index.d.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/http.ts" />
///<reference path="../../../../vendor/misqtech/totalsuite-totalcore/assets/scripts/common/configs/global.ts" />
///<reference path="controllers/submissions-listing.ts" />
var TotalContest;
(function (TotalContest) {
    var HttpConfig = TotalCore.Common.Configs.HttpConfig;
    var GlobalConfig = TotalCore.Common.Configs.GlobalConfig;
    TotalContest.submission = angular
        .module('submissions-listing', [
        'ngResource',
        'controllers.totalcontest'
    ])
        .config(GlobalConfig)
        .config(HttpConfig)
        .value('ajaxEndpoint', window['totalcontestAjaxURL'] || window['ajaxurl'] || '/wp-admin/admin-ajax.php')
        .value('namespace', 'TotalContest')
        .value('prefix', 'totalcontest');
    TotalContest.submissions = {};
    angular.element().ready(function () {
        angular.element('#screen-meta').attr('ng-non-bindable', 0);
        angular.element('#posts-filter').attr('ng-non-bindable', 0);
        angular.element('#wpbody-content').attr('ng-controller', 'SubmissionsListingCtrl as $ctrl');
        angular.bootstrap(document.body, ['submissions-listing']);
    });
})(TotalContest || (TotalContest = {}));

//# sourceMappingURL=maps/submissions-listing.js.map
