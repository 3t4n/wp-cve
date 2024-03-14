/*! elementor-pro - v2.6.0 - 23-07-2019 */
/******/ (function (modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if (installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
			/******/
}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
			/******/
};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
		/******/
}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function (exports, name, getter) {
/******/ 		if (!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
			/******/
}
		/******/
};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function (exports) {
/******/ 		if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
			/******/
}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
		/******/
};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function (value, mode) {
/******/ 		if (mode & 1) value = __webpack_require__(value);
/******/ 		if (mode & 8) return value;
/******/ 		if ((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if (mode & 2 && typeof value != 'string') for (var key in value) __webpack_require__.d(ns, key, function (key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
		/******/
};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function (module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
		/******/
};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function (object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
	/******/
})
/************************************************************************/
/******/([
/* 0 */
/***/ (function (module, exports, __webpack_require__) {

		"use strict";


		var ElementEditorModule = __webpack_require__(1);

		module.exports = ElementEditorModule.extend({

			__construct: function __construct() {
				this.cache = {};
				ElementEditorModule.prototype.__construct.apply(this, arguments);
			},

			getName: function getName() {
				return '';
			},

			getCacheKey: function getCacheKey(args) {
				return JSON.stringify({
					service: this.getName(),
					data: args
				});
			},

			fetchCache: function fetchCache(type, cacheKey, requestArgs) {
				var _this = this;

				return elementorProLL.ajax.addRequest('forms_panel_action_data', {
					unique_id: 'integrations_' + this.getName(),
					data: requestArgs,
					success: function success(data) {
						_this.cache[type] = _.extend({}, _this.cache[type]);
						_this.cache[type][cacheKey] = data[type];
					}
				});
			},

			updateOptions: function updateOptions(name, options) {
				var controlView = this.getEditorControlView(name);

				if (controlView) {
					this.getEditorControlModel(name).set('options', options);

					controlView.render();
				}
			},

			onInit: function onInit() {
				this.addSectionListener('section_' + this.getName(), this.onSectionActive);
			},

			onSectionActive: function onSectionActive() {
				this.onApiUpdate();
			},

			onApiUpdate: function onApiUpdate() { }
		});

		/***/
}),
/* 1 */
/***/ (function (module, exports, __webpack_require__) {

		"use strict";


		module.exports = elementorModules.editor.utils.Module.extend({
			elementType: null,

			__construct: function __construct(elementType) {
				this.elementType = elementType;

				this.addEditorListener();
			},

			addEditorListener: function addEditorListener() {
				var self = this;

				if (self.onElementChange) {
					var eventName = 'change';

					if ('global' !== self.elementType) {
						eventName += ':' + self.elementType;
					}

					elementor.channels.editor.on(eventName, function (controlView, elementView) {
						self.onElementChange(controlView.model.get('name'), controlView, elementView);
					});
				}
			},

			addControlSpinner: function addControlSpinner(name) {
				var $el = this.getEditorControlView(name).$el,
					$input = $el.find(':input');

				if ($input.attr('disabled')) {
					return;
				}

				$input.attr('disabled', true);

				$el.find('.elementor-control-title').after('<span class="elementor-control-spinner"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>');
			},

			removeControlSpinner: function removeControlSpinner(name) {
				var $controlEl = this.getEditorControlView(name).$el;

				$controlEl.find(':input').attr('disabled', false);
				$controlEl.find('elementor-control-spinner').remove();
			},

			addSectionListener: function addSectionListener(section, callback) {
				var self = this;

				elementor.channels.editor.on('section:activated', function (sectionName, editor) {
					var model = editor.getOption('editedElementView').getEditModel(),
						currentElementType = model.get('elType'),
						_arguments = arguments;

					if ('widget' === currentElementType) {
						currentElementType = model.get('widgetType');
					}

					if (self.elementType === currentElementType && section === sectionName) {
						setTimeout(function () {
							callback.apply(self, _arguments);
						}, 10);
					}
				});
			}
		});

		/***/
}),
/* 2 */
/***/ (function (module, exports, __webpack_require__) {

		"use strict";

		var ElementorProLL = Marionette.Application.extend({
			config: {},
		
			modules: {},
		
			initModules: function initModules() {
				var Forms = __webpack_require__(3);
		
				this.modules = {
					forms: new Forms()
				};
			},
		
			ajax: {
				prepareArgs: function prepareArgs(args) {
					args[0] = 'pro_' + args[0];
		
					return args;
				},
		
				send: function send() {
					return elementorCommon.ajax.send.apply(elementorCommon.ajax, this.prepareArgs(arguments));
				},
		
				addRequest: function addRequest() {
					return elementorCommon.ajax.addRequest.apply(elementorCommon.ajax, this.prepareArgs(arguments));
				}
			},
		
			translate: function translate(stringKey, templateArgs) {
				return elementorCommon.translate(stringKey, null, templateArgs, this.config.i18n);
			},
		
			onStart: function onStart() {

				//fix update elementor 2.6
				this.config = (typeof elementorProEditorConfig !== 'undefined') ? elementorProEditorConfig : ElementorProConfig;
		
				this.initModules();
		
				jQuery(window).on('elementor:init', this.onElementorInit);
			},
		
			onElementorInit: function onElementorInit() {
				elementorProLL.libraryRemoveGetProButtons();
		
				elementor.debug.addURLToWatch('elementor-pro/assets');
			},
		
			libraryRemoveGetProButtons: function libraryRemoveGetProButtons() {
				elementor.hooks.addFilter('elementor/editor/template-library/template/action-button', function (viewID, templateData) {
					return templateData.isPro && !elementorProLL.config.isActive ? '#tmpl-elementor-pro-template-library-activate-license-button' : '#tmpl-elementor-template-library-insert-button';
				});
			}
		});

		window.elementorProLL = new ElementorProLL();

		elementorProLL.start();

		/***/
}),
/* 3 */
/***/ (function (module, exports, __webpack_require__) {

		"use strict";


		module.exports = elementorModules.editor.utils.Module.extend({
			onElementorInit: function onElementorInit() {
				var Leadlovers = __webpack_require__(4);
				this.leadlovers = new Leadlovers('form');
			}
		});

		/***/
}),
/* 4 */
/***/ (function (module, exports, __webpack_require__) {

		"use strict";


		var BaseIntegrationModule = __webpack_require__(0);

		module.exports = BaseIntegrationModule.extend({
			fields: {},
			tagsLoaded: false,

			getName: function getName() {
				return 'leadlovers';
			},

			onElementChange: function onElementChange(setting) {
				switch (setting) {
					case 'leadlovers_api_key_source':
					case 'leadlovers_custom_api_key':
						this.onLeadloversApiKeyUpdate();
						break;

					case 'leadlovers_machine':
						this.onLeadloversMachineUpdate(true);
						break;

					case 'leadlovers_funnel':
						this.onLeadloversFunnelUpdate(true);
						break;
				}
			},

			onLeadloversApiKeyUpdate: function onLeadloversApiKeyUpdate() {

				var self = this,
					controlView = self.getEditorControlView('leadlovers_custom_api_key'),
					GlobalApiKeycontrolView = self.getEditorControlView('leadlovers_api_key_source');

				if ('default' !== GlobalApiKeycontrolView.getControlValue() && '' === controlView.getControlValue()) {
					self.updateOptions('leadlovers_machine', []);
					self.getEditorControlView('leadlovers_machine').setValue('');
					return;
				}

				self.addControlSpinner('leadlovers_machine');

				var cacheKey = this.getCacheKey({
					type: 'machines',
					controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
				});

				self.getLeadloversCache('machines', 'machines', cacheKey).done(function (data) {
					self.updateOptions('leadlovers_machine', data.machines);
					self.onLeadloversMachineUpdate();

					if (self.tagsLoaded && !self.getEditorControlView('leadlovers_lead_tags').options.model.attributes.options.length > 0) {
						self.leadloversTagsUpdate();
					}
				});
			},

			onLeadloversMachineUpdate: function onLeadloversMachineUpdate(forceUpdate = false) {

				var self = this,
					controlView = self.getEditorControlView('leadlovers_custom_api_key'),
					GlobalApiKeycontrolView = self.getEditorControlView('leadlovers_api_key_source'),
					MachineView = self.getEditorControlView('leadlovers_machine');

				if ('' === MachineView.getControlValue()) {
					self.updateOptions('leadlovers_funnel', []);
					self.getEditorControlView('leadlovers_funnel').setValue('');
					return;
				}

				self.addControlSpinner('leadlovers_funnel');

				var cacheKey = this.getCacheKey({
					type: 'funnels',
					controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
				});

				var argsGet = {
					"machine": MachineView.getControlValue()
				}

				self.getLeadloversCache('funnels', 'funnels', cacheKey, argsGet, forceUpdate).done(function (data) {
					if (forceUpdate) {
						self.getEditorControlView('leadlovers_funnel').setValue('');
						self.getEditorControlView('leadlovers_sequence').setValue('');
						self.leadloversFieldsUpdate();
					}
					self.updateOptions('leadlovers_funnel', data.funnels);
					self.onLeadloversFunnelUpdate();
				});
			},

			onLeadloversFunnelUpdate: function onLeadloversFunnelUpdate(forceUpdate = false) {

				var self = this,
					controlView = self.getEditorControlView('leadlovers_custom_api_key'),
					GlobalApiKeycontrolView = self.getEditorControlView('leadlovers_api_key_source'),
					MachineView = self.getEditorControlView('leadlovers_machine'),
					FunnelView = self.getEditorControlView('leadlovers_funnel');

				if ('' === FunnelView.getControlValue()) {
					self.updateOptions('leadlovers_sequence', []);
					self.getEditorControlView('leadlovers_sequence').setValue('');
					if (!self.tagsLoaded) {
						self.leadloversTagsUpdate();
					} else if (MachineView.getControlValue()) {
						self.leadloversFieldsUpdate();
					}

					return;
				}

				self.addControlSpinner('leadlovers_sequence');

				var cacheKey = this.getCacheKey({
					type: 'sequences',
					controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
				});

				var argsGet = {
					"machine": MachineView.getControlValue(),
					"funnel": FunnelView.getControlValue()
				}

				self.getLeadloversCache('sequences', 'sequences', cacheKey, argsGet, forceUpdate).done(function (data) {
					if (forceUpdate) {
						self.getEditorControlView('leadlovers_sequence').setValue('');
					}
					if (!self.tagsLoaded) {
						self.leadloversTagsUpdate();
					}
					self.updateOptions('leadlovers_sequence', data.sequences);
				});
			},

			leadloversTagsUpdate: function leadloversTagsUpdate(forceUpdate = false) {

				var self = this,
					controlView = self.getEditorControlView('leadlovers_custom_api_key'),
					GlobalApiKeycontrolView = self.getEditorControlView('leadlovers_api_key_source');

				if ('default' !== GlobalApiKeycontrolView.getControlValue() && '' === controlView.getControlValue()) {
					self.updateOptions('leadlovers_lead_tags', []);
					self.getEditorControlView('leadlovers_lead_tags').setValue('');
					return;
				}

				self.addControlSpinner('leadlovers_lead_tags');

				var cacheKey = self.getCacheKey({
					type: 'lead_tags',
					controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
				});

				self.getLeadloversCache('lead_tags', 'lead_tags', cacheKey, null, true).always(function (data) {

					var updateTags = {};

					if (data.tags) {
						updateTags = data.tags
					}

					self.updateOptions('leadlovers_lead_tags', updateTags);
					self.tagsLoaded = true;

					self.leadloversFieldsUpdate();
				});
			},

			leadloversFieldsUpdate: function leadloversFieldsUpdate() {
				var self = this,
					controlView = self.getEditorControlView('leadlovers_custom_api_key'),
					GlobalApiKeycontrolView = self.getEditorControlView('leadlovers_api_key_source'),
					MachineView = self.getEditorControlView('leadlovers_machine');

				if (!MachineView.getControlValue()) {
					return;
				}

                self.addControlSpinner('leadlovers_input_fields');
                self.addControlSpinner('leadlovers_dynamic_input_fields');
				self.addControlSpinner('leadlovers_utm_parameters');

				var cacheKey = this.getCacheKey({
					type: 'machine_infos',
					controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
				});

				var argsGet = {
					"machine": MachineView.getControlValue()
				}

				self.getLeadloversCache('machine_infos', 'machine_infos', cacheKey, argsGet, true).done(function (data) {
					self.getEditorControlView('leadlovers_machine_type').setValue(data.type);
					self.getRemoteFields(data.type);
				});
			},

			getRemoteFields: function getRemoteFields(machineType) {
                    var controlView = this.getEditorControlView('leadlovers_machine');
                    var requiredFields = [];

                    if (!controlView.getControlValue()) {
                        return;
                    }

                    switch (machineType) {
                        case 'Email':
                            requiredFields = ['email'];
                            break;

                        case 'Sms':
                        case 'Whatsapp':
                            requiredFields = ['phone'];
                            break;
                    }

                    var remoteFields = [{
                        remote_label: 'Email',
                        remote_type: 'email',
                        remote_id: 'email',
                        remote_required: (requiredFields.includes('email')) ? true : false
                    }, {
                        remote_label: 'Nome',
                        remote_type: 'text',
                        remote_id: 'name',
                        remote_required: (requiredFields.includes('name')) ? true : false
                    }, {
                        remote_label: 'Telefone',
                        remote_type: 'text',
                        remote_id: 'phone',
                        remote_required: (requiredFields.includes('phone')) ? true : false
                    }, {
                        remote_label: 'Nascimento',
                        remote_type: 'text',
                        remote_id: 'birthday',
                        remote_required: (requiredFields.includes('birthday')) ? true : false
                    }, {
                        remote_label: 'Cidade',
                        remote_type: 'text',
                        remote_id: 'city',
                        remote_required: (requiredFields.includes('city')) ? true : false
                    }, {
                        remote_label: 'Estado',
                        remote_type: 'text',
                        remote_id: 'state',
                        remote_required: (requiredFields.includes('state')) ? true : false
                    }, {
                        remote_label: 'Empresa',
                        remote_type: 'text',
                        remote_id: 'company',
                        remote_required: (requiredFields.includes('company')) ? true : false
                    }, {
                        remote_label: 'Gênero',
                        remote_type: 'text',
                        remote_id: 'sex',
                        remote_required: (requiredFields.includes('gender')) ? true : false
                    }, {
                        remote_label: 'Mensagem',
                        remote_type: 'text',
                        remote_id: 'message',
                        remote_required: (requiredFields.includes('message')) ? true : false
                    }
               		 ];

					var utmFields = [{
                        remote_label: 'utm_source',
                        remote_type: 'text',
                        remote_id: 'utm_source',
                        remote_required: false
                    },{
                        remote_label: 'utm_medium',
                        remote_type: 'text',
                        remote_id: 'utm_medium',
                        remote_required: false
                    },{
                        remote_label: 'utm_campaign',
                        remote_type: 'text',
                        remote_id: 'utm_campaign',
                        remote_required: false
                    },{
                        remote_label: 'utm_term',
                        remote_type: 'text',
                        remote_id: 'utm_term',
                        remote_required: false
                    },{
                        remote_label: 'utm_content',
                        remote_type: 'text',
                        remote_id: 'utm_content',
                        remote_required: false
                    }]

                var self = this,
                controlView = self.getEditorControlView('leadlovers_custom_api_key'),
                GlobalApiKeycontrolView = self.getEditorControlView('leadlovers_api_key_source');
                
                var cacheKey = this.getCacheKey({
                    type: 'captureFields',
                    controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
                });

                for (var field in this.fields) {
                    if (this.fields.hasOwnProperty(field)) {
                        remoteFields.push(this.fields[field]);
						utmFields.push(this.fields[field]);
                    }
                }

                self.getEditorControlView('leadlovers_input_fields').updateMap(remoteFields);
                self.getEditorControlView('leadlovers_utm_parameters').updateMap(utmFields);

                self.getLeadloversCache('captureFields', 'captureFields', cacheKey, null, false).done(function (data) {
                    var dynamicFields = [];
                    for(var captureField in data.captureFields) {
                        dynamicFields.push(data.captureFields[captureField]);
                    }
                    self.getEditorControlView('leadlovers_dynamic_input_fields').updateMap(dynamicFields);
                });
			},

			getLeadloversCache: function getLeadloversCache(type, action, cacheKey, requestArgs, ignoreCache = false) {
				if (_.has(this.cache[type], cacheKey) && !ignoreCache) {
					var data = {};
					data[type] = this.cache[type][cacheKey];
					return jQuery.Deferred().resolve(data);
				}

				requestArgs = _.extend({}, requestArgs, {
					service: 'leadlovers',
					leadlovers_action: action,
					custom_api_key: this.getEditorControlView('leadlovers_custom_api_key').getControlValue(),
					api_key: this.getEditorControlView('leadlovers_api_key_source').getControlValue()
				});

				if (type === "lead_tags") {
					var fetchResponse = this.fetchCache(type, cacheKey, requestArgs);
				}

				return this.fetchCache(type, cacheKey, requestArgs);
			},

			onSectionActive: function onSectionActive() {

				BaseIntegrationModule.prototype.onSectionActive.apply(this, arguments);
				this.onLeadloversApiKeyUpdate();
				if (!this.getEditorControlView('leadlovers_lead_tags').options.model.attributes.options) {
					this.leadTagsUpdate();
				}
			}
		});

		/***/
	})
/******/]);