(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* global sg_email_marketing_gutenberg_form_selector, Choices */
/* jshint es3: false, esversion: 6 */

'use strict';

/**
 * Gutenberg editor block.
 *
 * @since 1.8.1
 */
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i.return && (_r = _i.return(), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
var SGEmailMarketing = window.SGEmailMarketing || {};
SGEmailMarketing.FormSelector = SGEmailMarketing.FormSelector || function (document, window, $) {
  var _wp = wp,
    _wp$serverSideRender = _wp.serverSideRender,
    ServerSideRender = _wp$serverSideRender === void 0 ? wp.components.ServerSideRender : _wp$serverSideRender;
  var _wp$element = wp.element,
    createElement = _wp$element.createElement,
    Fragment = _wp$element.Fragment,
    useState = _wp$element.useState;
  var registerBlockType = wp.blocks.registerBlockType;
  var _ref = wp.blockEditor || wp.editor,
    InspectorControls = _ref.InspectorControls,
    InspectorAdvancedControls = _ref.InspectorAdvancedControls,
    PanelColorSettings = _ref.PanelColorSettings;
  var _wp$components = wp.components,
    SelectControl = _wp$components.SelectControl,
    ToggleControl = _wp$components.ToggleControl,
    PanelBody = _wp$components.PanelBody,
    Placeholder = _wp$components.Placeholder,
    Flex = _wp$components.Flex,
    FlexBlock = _wp$components.FlexBlock,
    __experimentalUnitControl = _wp$components.__experimentalUnitControl,
    TextareaControl = _wp$components.TextareaControl,
    Button = _wp$components.Button,
    Modal = _wp$components.Modal;
  var sg_email_marketing_gutenberg_fo = sg_email_marketing_gutenberg_form_selector,
    strings = sg_email_marketing_gutenberg_fo.strings,
    defaults = sg_email_marketing_gutenberg_fo.defaults,
    size_options = sg_email_marketing_gutenberg_fo.size_options,
    orientation_options = sg_email_marketing_gutenberg_fo.orientation_options,
    alignment_options = sg_email_marketing_gutenberg_fo.alignment_options;
  var defaultStyleSettings = defaults;
  var { useEffect } = wp.element

  /**
   * Blocks runtime data.
   *
   * @since 1.8.1
   *
   * @type {object}
   */
  var blocks = {};

  /**
   * Whether it is needed to trigger server rendering.
   *
   * @since 1.8.1
   *
   * @type {boolean}
   */
  var triggerServerRender = true;

  /**
   * Public functions and properties.
   *
   * @since 1.8.1
   *
   * @type {object}
   */
  var app = {
    /**
     * Start the engine.
     *
     * @since 1.8.1
     */
    init: function init() {
      app.initDefaults();
      app.registerBlock();
      $(app.ready);
    },
    /**
     * Document ready.
     *
     * @since 1.8.1
     */
    ready: function ready() {
      app.events();
    },
    /**
     * Events.
     *
     * @since 1.8.1
     */
    events: function events() {
      $(window).on('sgEmailMarketingFormSelectorEdit', _.debounce(app.blockEdit, 250)).on('sg-email-marketingFormSelectorFormLoaded', _.debounce(app.formLoaded, 250));
    },
    /**
     * Register block.
     *
     * @since 1.8.1
     */
    registerBlock: function registerBlock() {
      registerBlockType('sg-email-marketing/form-selector', {
        title: strings.title,
        description: strings.description,
        icon: app.getIcon(),
        keywords: strings.form_keywords,
        category: 'widgets',
        attributes: app.getBlockAttributes(),
        example: {
          attributes: {
            preview: true
          }
        },
        supports: {
          spacing: {
            margin: [ 'top', 'bottom' ],
            padding: true,
          },
        },
        edit: function edit(props) {
          var attributes = props.attributes;
          var formOptions = app.getFormOptions();
          var sizeOptions = app.getSizeOptions();
          var alignmentOptions = app.getAlignmentOptions();
          var orientationOptions = app.getOrientationOptions();
          var handlers = app.getSettingsFieldsHandlers(props);         

          // Store block clientId in attributes.
            props.setAttributes({
              clientId: props.clientId
            });

          // Main block settings.
          var jsx = [app.jsxParts.getMainSettings(attributes, handlers, formOptions, sizeOptions, alignmentOptions, orientationOptions)];

          // Form style settings & block content.
          if (attributes.formId) {
            jsx.push(app.jsxParts.getStyleSettings(attributes, handlers ), app.jsxParts.getAdvancedSettings(attributes, handlers), app.jsxParts.getBlockFormContent(props));
            $(window).trigger('sgEmailMarketingFormSelectorEdit', [props]);
            return jsx;
          }

          // Block preview picture.
          if (attributes.preview) {
            jsx.push(app.jsxParts.getBlockPreview());
            return jsx;
          }

          // Block placeholder (form selector).
          jsx.push(app.jsxParts.getBlockPlaceholder(props.attributes, handlers, formOptions));
          return jsx;
        },
        save: function save() {
          return null;
        }
      });
    },
    /**
     * Init default style settings.
     *
     * @since 1.8.1
     */
    initDefaults: function initDefaults() {
      ['formId'].forEach(function (key) {
        return delete defaultStyleSettings[key];
      });
    },
    /**
     * Block JSX parts.
     *
     * @since 1.8.1
     *
     * @type {object}
     */
    jsxParts: {
      /**
       * Get main settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} formOptions Form selector options.
       *
       * @returns {JSX.Element} Main setting JSX code.
       */
      getMainSettings: function getMainSettings(attributes, handlers, formOptions, sizeOptions, alignmentOptions, orientationOptions) {
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "sg-email-marketing-gutenberg-form-selector-inspector-main-settings"
        }, /*#__PURE__*/React.createElement(PanelBody, {
          className: "sg-email-marketing-gutenberg-panel",
          title: strings.form_styles
        }, /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.form_selected,
          value: attributes.formId,
          options: formOptions,
          onChange: function onChange(value) {
            return handlers.attrChange('formId', value);
          }
        }),/*#__PURE__*/React.createElement(SelectControl, {
          label: strings.size,
          value: attributes.formSize,
          options: sizeOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('formSize', value);
          }
        }),/*#__PURE__*/React.createElement(SelectControl, {
          label: strings.orientation,
          value: attributes.formOrientation,
          options: orientationOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('formOrientation', value);
          }
        }),attributes.formOrientation === 'column' && /*#__PURE__*/React.createElement(SelectControl, {
          label: strings.alignment,
          value: attributes.formAlignment,
          options: alignmentOptions,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('formAlignment', value);
          }
        }),
        /*#__PURE__*/React.createElement("div", {
          className: "sg-email-marketing-gutenberg-form-selector-control-label"
        }, strings.colors),
        /*#__PURE__*/React.createElement(PanelColorSettings, {
          title: strings.form_background_color,
          initialOpen: true,
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          colorSettings: [
            {
              value: attributes.formBackgroundColor,
              onChange: function onChange(value) {
                return handlers.styleAttrChange('formBackgroundColor', value);
              },
              label: strings.form_background_color
            }
          ]
        })
        ,/*#__PURE__*/React.createElement("p", {
          className: "sg-email-marketing-gutenberg-panel-notice"
        })));
      },
      /**
       * Get Field styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} sizeOptions Size selector options.
       *
       * @returns {JSX.Element} Field styles JSX code.
       */
      getFieldStyles: function getFieldStyles(attributes, handlers) {
        // eslint-disable-line max-lines-per-function
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(attributes),
          title: strings.field_styles
        },  /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(__experimentalUnitControl, {
          label: strings.border_radius,
          value: attributes.fieldBorderRadius,
          isUnitSelectTabbable: true,
          onChange: function onChange(value) {
            return handlers.styleAttrChange('fieldBorderRadius', value);
          }
        }))), /*#__PURE__*/React.createElement("div", {
          className: "sg-email-marketing-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "sg-email-marketing-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "sg-email-marketing-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: attributes.fieldBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: attributes.fieldBorderColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldBorderColor', value);
            },
            label: strings.border
          }, {
            value: attributes.fieldTextColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldTextColor', value);
            },
            label: strings.text
          }, 
          {
            value: attributes.fieldPlaceholderColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('fieldPlaceholderColor', value);
            },
            label: strings.placeholder
          },
          {
            value: attributes.labelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelColor', value);
            },
            label: strings.label
          }, {
            value: attributes.labelSublabelColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('labelSublabelColor', value);
            },
            label: strings.sublabel_hints.replace('&amp;', '&')
          }]
        })));
      },
      /**
       * Get Button styles JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} sizeOptions Size selector options.
       *
       * @returns {JSX.Element}  Button styles JSX code.
       */
      getButtonStyles: function getButtonStyles(attributes, handlers, sizeOptions) {
        return /*#__PURE__*/React.createElement(PanelBody, {
          className: app.getPanelClass(attributes),
          title: strings.button_styles
        }, /*#__PURE__*/React.createElement(Flex, {
          gap: 4,
          align: "flex-start",
          className: 'sg-email-marketing-gutenberg-form-selector-flex',
          justify: "space-between"
        }, /*#__PURE__*/React.createElement(FlexBlock, null, /*#__PURE__*/React.createElement(__experimentalUnitControl, {
          onChange: function onChange(value) {
            return handlers.styleAttrChange('buttonBorderRadius', value);
          },
          label: strings.border_radius,
          isUnitSelectTabbable: true,
          value: attributes.buttonBorderRadius
        }))), /*#__PURE__*/React.createElement("div", {
          className: "sg-email-marketing-gutenberg-form-selector-color-picker"
        }, /*#__PURE__*/React.createElement("div", {
          className: "sg-email-marketing-gutenberg-form-selector-control-label"
        }, strings.colors), /*#__PURE__*/React.createElement(PanelColorSettings, {
          __experimentalIsRenderedInSidebar: true,
          enableAlpha: true,
          showTitle: false,
          className: "sg-email-marketing-gutenberg-form-selector-color-panel",
          colorSettings: [{
            value: attributes.buttonBackgroundColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('buttonBackgroundColor', value);
            },
            label: strings.background
          }, {
            value: attributes.buttonTextColor,
            onChange: function onChange(value) {
              return handlers.styleAttrChange('buttonTextColor', value);
            },
            label: strings.text
          }]
        })));
      },
      /**
       * Get style settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} sizeOptions Size selector options.
       *
       * @returns {JSX.Element} Inspector controls JSX code.
       */
      getStyleSettings: function getStyleSettings(attributes, handlers, sizeOptions, alignmentOptions, orientationOptions) {
        return /*#__PURE__*/React.createElement(InspectorControls, {
          key: "sg-email-marketing-gutenberg-form-selector-style-settings"
        }, app.jsxParts.getFieldStyles(attributes, handlers, sizeOptions, alignmentOptions, orientationOptions), app.jsxParts.getButtonStyles(attributes, handlers, sizeOptions));
      },
      /**
       * Get advanced settings JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes Block attributes.
       * @param {object} handlers   Block event handlers.
       *
       * @returns {JSX.Element} Inspector advanced controls JSX code.
       */
      getAdvancedSettings: function getAdvancedSettings(attributes, handlers) {
        var _useState = useState(false),
          _useState2 = _slicedToArray(_useState, 2),
          isOpen = _useState2[0],
          setOpen = _useState2[1];
        var openModal = function openModal() {
          return setOpen(true);
        };
        var closeModal = function closeModal() {
          return setOpen(false);
        };
        return /*#__PURE__*/React.createElement(InspectorAdvancedControls, null, /*#__PURE__*/React.createElement("div", {
          className: app.getPanelClass(attributes)
        }, /*#__PURE__*/React.createElement(Button, {
          className: "sg-email-marketing-gutenberg-form-selector-reset-button",
          onClick: openModal
        }, strings.reset_style_settings)), isOpen && /*#__PURE__*/React.createElement(Modal, {
          className: "sg-email-marketing-gutenberg-modal",
          title: strings.reset_style_settings,
          onRequestClose: closeModal
        }, /*#__PURE__*/React.createElement("p", null, strings.reset_settings_confirm_text), /*#__PURE__*/React.createElement(Flex, {
          gap: 3,
          align: "center",
          justify: "flex-end"
        }, /*#__PURE__*/React.createElement(Button, {
          isSecondary: true,
          onClick: closeModal
        }, strings.btn_no), /*#__PURE__*/React.createElement(Button, {
          isPrimary: true,
          onClick: function onClick() {
            closeModal();
            handlers.resetSettings();
          }
        }, strings.btn_yes_reset))));
      },
      /**
       * Get block content JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} props Block properties.
       *
       * @returns {JSX.Element} Block content JSX code.
       */
      getBlockFormContent: function getBlockFormContent(props) {
        if (triggerServerRender) {
          return /*#__PURE__*/React.createElement(ServerSideRender, {
            key: "sg-email-marketing-gutenberg-form-selector-server-side-renderer",
            block: "sg-email-marketing/form-selector",
            attributes: props.attributes
          });
        }
        var clientId = props.clientId;
        var block = app.getBlockContainer(props);

        // In the case of empty content, use server side renderer.
        // This happens when the block is duplicated or converted to a reusable block.
        if (!block || !block.innerHTML) {
          triggerServerRender = true;
          return app.jsxParts.getBlockFormContent(props);
        }
        blocks[clientId] = blocks[clientId] || {};
        blocks[clientId].blockHTML = block.innerHTML;
        blocks[clientId].loadedFormId = props.attributes.formId;
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "sg-email-marketing-gutenberg-form-selector-fragment-form-html"
        }, /*#__PURE__*/React.createElement("div", {
          dangerouslySetInnerHTML: {
            __html: blocks[clientId].blockHTML
          }
        }));
      },
      /**
       * Get block preview JSX code.
       *
       * @since 1.8.1
       *
       * @returns {JSX.Element} Block preview JSX code.
       */
      getBlockPreview: function getBlockPreview() {
        return /*#__PURE__*/React.createElement(Fragment, {
          key: "sg-email-marketing-gutenberg-form-selector-fragment-block-preview"
        }, /*#__PURE__*/React.createElement("img", {
          src: sg_email_marketing_gutenberg_form_selector.block_preview_url,
          style: {
            width: '100%'
          }
        }));
      },
      /**
       * Get block placeholder (form selector) JSX code.
       *
       * @since 1.8.1
       *
       * @param {object} attributes  Block attributes.
       * @param {object} handlers    Block event handlers.
       * @param {object} formOptions Form selector options.
       *
       * @returns {JSX.Element} Block placeholder JSX code.
       */
      getBlockPlaceholder: function getBlockPlaceholder(attributes, handlers, formOptions) {
        return /*#__PURE__*/React.createElement(Placeholder, {
          key: "sg-email-marketing-gutenberg-form-selector-wrap",
          className: "sg-email-marketing-gutenberg-form-selector-wrap"
        }, /*#__PURE__*/React.createElement("img", {
          src: sg_email_marketing_gutenberg_form_selector.logo_url,
          className: "sg-email-marketing-gutenberg-form-logo"
        }), /*#__PURE__*/React.createElement("h3", null, strings.title), /*#__PURE__*/React.createElement(SelectControl, {
          key: "sg-email-marketing-gutenberg-form-selector-select-control",
          value: attributes.formId,
          options: formOptions,
          onChange: function onChange(value) {
            return handlers.attrChange('formId', value);
          }
        }));
      }
    },
    /**
     * Get Style Settings panel class.
     *
     * @since 1.8.1
     *
     * @param {object} attributes Block attributes.
     *
     * @returns {string} Style Settings panel class.
     */
    getPanelClass: function getPanelClass(attributes) {
      var cssClass = 'sg-email-marketing-gutenberg-panel sg-email-marketing-block-settings-' + attributes.clientId;
      if (!app.isFullStylingEnabled()) {
        cssClass += ' disabled_panel';
      }
      return cssClass;
    },
    /**
     * Determine whether the full styling is enabled.
     *
     * @since 1.8.1
     *
     * @returns {boolean} Whether the full styling is enabled.
     */
    isFullStylingEnabled: function isFullStylingEnabled() {
      return sg_email_marketing_gutenberg_form_selector.is_modern_markup && sg_email_marketing_gutenberg_form_selector.is_full_styling;
    },
    /**
     * Get block container DOM element.
     *
     * @since 1.8.1
     *
     * @param {object} props Block properties.
     *
     * @returns {Element} Block container.
     */
    getBlockContainer: function getBlockContainer(props) {
      var blockSelector = "#block-".concat(props.clientId, " > div");
      var block = document.querySelector(blockSelector);

      // For FSE / Gutenberg plugin we need to take a look inside the iframe.
      if (!block) {
        var editorCanvas = document.querySelector('iframe[name="editor-canvas"]');
        block = editorCanvas && editorCanvas.contentWindow.document.querySelector(blockSelector);
      }

      jQuery(document).ready(function($) {
        var $container = $('.sg-marketing-form-container');
        var $button = $container.find('button');
        $button.attr('disabled', 'disabled');
      });

      return block;
    },
    /**
     * Get settings fields event handlers.
     *
     * @since 1.8.1
     *
     * @param {object} props Block properties.
     *
     * @returns {object} Object that contains event handlers for the settings fields.
     */
    getSettingsFieldsHandlers: function getSettingsFieldsHandlers(props) {
      // eslint-disable-line max-lines-per-function

      return {
        /**
         * Field style attribute change event handler.
         *
         * @since 1.8.1
         *
         * @param {string} attribute Attribute name.
         * @param {string} value     New attribute value.
         */
        styleAttrChange: function styleAttrChange(attribute, value) {
          var block = app.getBlockContainer(props),
            container = block.querySelector("#sg-email-marketing-".concat(props.attributes.formId)),
            property = attribute.replace(/[A-Z]/g, function (letter) {
              return "-".concat(letter.toLowerCase());
            }),
            setAttr = {};
          if (container) {
            switch (property) {
              case 'form-size':
                for (var key in size_options[value]) {
                  container.style.setProperty("--sg-email-marketing-form-".concat(property, "-").concat(key), size_options[value][key]);
                }
                break;
              case 'form-alignment':
                for(var key in alignment_options[value]) {
                  container.style.setProperty("--sg-email-marketing-form-".concat(key), alignment_options[value][key]);
                }
                break;
              case 'form-orientation':
                container.classList.remove('sg-marketing-form-container-row', 'sg-marketing-form-container-column');
                container.classList.add('sg-marketing-form-container-' + value);
                break;
              default:
                container.style.setProperty("--sg-email-marketing-form-".concat(property), value);
            }
          }
          setAttr[attribute] = value;
          props.setAttributes(setAttr);
          triggerServerRender = false;
        },
        /**
         * Field regular attribute change event handler.
         *
         * @since 1.8.1
         *
         * @param {string} attribute Attribute name.
         * @param {string} value     New attribute value.
         */
        attrChange: function attrChange(attribute, value) {
          var setAttr = {};
          setAttr[attribute] = value;
          props.setAttributes(setAttr);
          triggerServerRender = true;
        },
        /**
         * Reset Form Styles settings to defaults.
         *
         * @since 1.8.1
         */
        resetSettings: function resetSettings() {
          for (var key in defaultStyleSettings) {
            this.styleAttrChange(key, defaultStyleSettings[key]);
          }
          triggerServerRender = true;
        }
      };
    },
    /**
     * Parse and validate JSON string.
     *
     * @since 1.8.1
     *
     * @param {string} value JSON string.
     *
     * @returns {boolean|object} Parsed JSON object OR false on error.
     */
    parseValidateJson: function parseValidateJson(value) {
      if (typeof value !== 'string') {
        return false;
      }
      var atts;
      try {
        atts = JSON.parse(value);
      } catch (error) {
        atts = false;
      }
      return atts;
    },
    /**
     * Get SGEmailMarketing icon DOM element.
     *
     * @since 1.8.1
     *
     * @returns {DOM.element} SGEmailMarketing icon DOM element.
     */
     getIcon: function getIcon() {
      return createElement('svg', {
        width: 24,
        height: 24,
        viewBox: '0 0 24 24',
        className: 'sg-email-marketing'
      }, createElement('path', {
        fill: 'currentColor',
        d: 'm5.05,6.975c0-.414.336-.75.75-.75h12.4c.414,0,.75.336.75.75s-.336.75-.75.75H5.8c-.414,0-.75-.336-.75-.75Zm.75-2.25h12.4c.414,0,.75-.336.75-.75s-.336-.75-.75-.75H5.8c-.414,0-.75.336-.75.75s.336.75.75.75ZM23.75,1.025v15.5c0,.414-.336.75-.75.75h-3.62l1.645,1.655c.429.433.432,1.138.005,1.574l-1.852,1.889c-.209.214-.49.332-.789.333h0c-.298,0-.579-.118-.789-.332l-2.086-2.129-.663,1.392c-.199.417-.626.661-1.082.63-.459-.034-.843-.342-.979-.786l-1.288-4.227H1c-.414,0-.75-.336-.75-.75V1.025c0-.414.336-.75.75-.75h22c.414,0,.75.336.75.75Zm-6.3,10.75c0-.579-.471-1.05-1.05-1.05H7.6c-.579,0-1.05.471-1.05,1.05s.471,1.05,1.05,1.05h2.547l-.047-.154c-.123-.4-.014-.831.283-1.125.294-.29.719-.394,1.112-.269l4.879,1.548h.027c.579,0,1.05-.471,1.05-1.05Zm2.247,7.946l-2.215-2.229c-.251-.253-.368-.616-.311-.969.057-.353.28-.659.597-.819l1.046-.53-7.067-2.242,2.184,7.168.469-.984c.157-.328.463-.56.82-.619.06-.01.121-.015.181-.015.294,0,.58.118.791.332l2.197,2.242,1.308-1.334ZM22.25,1.775H1.75v14h9.295l-.442-1.45h-3.004c-1.406,0-2.55-1.144-2.55-2.55s1.144-2.55,2.55-2.55h8.8c1.406,0,2.55,1.144,2.55,2.55,0,.643-.257,1.215-.653,1.661l1.864.591c.432.137.735.519.772.974.023.288-.073.558-.243.774h1.56V1.775Z'
      }));
    },
    /**
     * Get block attributes.
     *
     * @since 1.8.1
     *
     * @returns {object} Block attributes.
     */
    getBlockAttributes: function getBlockAttributes() {
      // eslint-disable-line max-lines-per-function
      return {
        clientId: {
          type: 'string',
          default: ''
        },
        formId: {
          type: 'string',
          default: defaults.formId
        },
        formBackgroundColor: {
          type: 'string',
          default: defaults.formBackgroundColor
        },
        preview: {
          type: 'boolean'
        },
        formSize: {
          type: 'string',
          default: defaults.formSize
        },
        fieldBorderRadius: {
          type: 'string',
          default: defaults.fieldBorderRadius
        },
        fieldBackgroundColor: {
          type: 'string',
          default: defaults.fieldBackgroundColor
        },
        fieldBorderColor: {
          type: 'string',
          default: defaults.fieldBorderColor
        },
        fieldTextColor: {
          type: 'string',
          default: defaults.fieldTextColor
        },
        fieldPlaceholderColor: {
          type: 'string',
          default: defaults.fieldPlaceholderColor
        },
        labelSize: {
          type: 'string',
          default: defaults.labelSize
        },
        labelColor: {
          type: 'string',
          default: defaults.labelColor
        },
        labelSublabelColor: {
          type: 'string',
          default: defaults.labelSublabelColor
        },
        labelErrorColor: {
          type: 'string',
          default: defaults.labelErrorColor
        },
        buttonBorderRadius: {
          type: 'string',
          default: defaults.buttonBorderRadius
        },
        buttonBackgroundColor: {
          type: 'string',
          default: defaults.buttonBackgroundColor
        },
        buttonTextColor: {
          type: 'string',
          default: defaults.buttonTextColor
        },
        formAlignment: {
          type: 'string',
          default: defaults.formAlignment
        },
        formOrientation: {
          type: 'string',
          default: defaults.formOrientation
        }
      };
    },
    /**
     * Get form selector options.
     *
     * @since 1.8.1
     *
     * @returns {Array} Form options.
     */
    getFormOptions: function getFormOptions() {
      var formOptions = sg_email_marketing_gutenberg_form_selector.forms.map(function (value) {
        return {
          value: value.ID,
          label: value.post_title
        };
      });
      formOptions.unshift({
        value: '',
        label: strings.form_select
      });
      return formOptions;
    },
    /**
     * Get size selector options.
     *
     * @since 1.8.1
     *
     * @returns {Array} Size options.
     */
    getSizeOptions: function getSizeOptions() {
      return [{
        label: strings.small,
        value: 'small'
      }, {
        label: strings.medium,
        value: 'medium'
      }, {
        label: strings.large,
        value: 'large'
      }];
    },

    getAlignmentOptions: function getAlignmentOptions() {
      return [{
        label: 'Left',
        value: 'left'
      },{
        label: 'Right',
        value: 'right'
      }, {
        label: 'Center',
        value: 'center'
      }];
    },

    getOrientationOptions: function getOrientationOptions() {
      return [{
        label: 'Vertical',
        value: 'column'
      },{
        label: 'Horizontal',
        value: 'row'
      }];
    },
    /**
     * Event `sgEmailMarketingFormSelectorEdit` handler.
     *
     * @since 1.8.1
     *
     * @param {object} e     Event object.
     * @param {object} props Block properties.
     */
    blockEdit: function blockEdit(e, props) {
      var block = app.getBlockContainer(props);
      if (!block || !block.dataset) {
        return;
      }
      app.initLeadFormSettings(block.parentElement);
    },
    /**
     * Init Lead Form Settings panels.
     *
     * @since 1.8.1
     *
     * @param {Element} block Block element.
     */
    initLeadFormSettings: function initLeadFormSettings(block) {
      if (!block || !block.dataset) {
        return;
      }
      if (!app.isFullStylingEnabled()) {
        return;
      }
      var clientId = block.dataset.block;
      var $form = $(block.querySelector('.sg-email-marketing-container'));
      var $panel = $(".sg-email-marketing-block-settings-".concat(clientId));
      $panel.removeClass('disabled_panel').find('.sg-email-marketing-gutenberg-panel-notice.sg-email-marketing-lead-form-notice').css('display', 'none');
    },
    /**
     * Event `sg-email-marketingFormSelectorFormLoaded` handler.
     *
     * @since 1.8.1
     *
     * @param {object} e Event object.
     */
    formLoaded: function formLoaded(e) {
      app.initLeadFormSettings(e.detail.block);
      app.updateAccentColors(e.detail);
      app.loadChoicesJS(e.detail);
      app.initRichTextField(e.detail.formId);
      $(e.detail.block).off('click').on('click', app.blockClick);
    },
    /**
     * Click on the block event handler.
     *
     * @since 1.8.1
     *
     * @param {object} e Event object.
     */
    blockClick: function blockClick(e) {
      app.initLeadFormSettings(e.currentTarget);
    },
    /**
     * Update accent colors of some fields in GB block in Modern Markup mode.
     *
     * @since 1.8.1
     *
     * @param {object} detail Event details object.
     */
    updateAccentColors: function updateAccentColors(detail) {
      if (!sg_email_marketing_gutenberg_form_selector.is_modern_markup || !window.SGEmailMarketing || !window.SGEmailMarketing.FrontendModern || !detail.block) {
        return;
      }
      var $form = $(detail.block.querySelector("#sg-email-marketing-".concat(detail.formId))),
        FrontendModern = window.SGEmailMarketing.FrontendModern;
      FrontendModern.updateGBBlockPageIndicatorColor($form);
      FrontendModern.updateGBBlockIconChoicesColor($form);
      FrontendModern.updateGBBlockRatingColor($form);
    },
    /**
     * Init Modern style Dropdown fields (<select>).
     *
     * @since 1.8.1
     *
     * @param {object} detail Event details object.
     */
    loadChoicesJS: function loadChoicesJS(detail) {
      if (typeof window.Choices !== 'function') {
        return;
      }
      var $form = $(detail.block.querySelector("#sg-email-marketing-".concat(detail.formId)));
      $form.find('.choicesjs-select').each(function (idx, el) {
        var $el = $(el);
        if ($el.data('choice') === 'active') {
          return;
        }
        var args = window.sg-email-marketing_choicesjs_config || {},
          searchEnabled = $el.data('search-enabled'),
          $field = $el.closest('.sg-email-marketing-field');
        args.searchEnabled = 'undefined' !== typeof searchEnabled ? searchEnabled : true;
        args.callbackOnInit = function () {
          var self = this,
            $element = $(self.passedElement.element),
            $input = $(self.input.element),
            sizeClass = $element.data('size-class');

          // Add CSS-class for size.
          if (sizeClass) {
            $(self.containerOuter.element).addClass(sizeClass);
          }

          /**
           * If a multiple select has selected choices - hide a placeholder text.
           * In case if select is empty - we return placeholder text back.
           */
          if ($element.prop('multiple')) {
            // On init event.
            $input.data('placeholder', $input.attr('placeholder'));
            if (self.getValue(true).length) {
              $input.removeAttr('placeholder');
            }
          }
          this.disable();
          $field.find('.is-disabled').removeClass('is-disabled');
        };
        try {
          var choicesInstance = new Choices(el, args);

          // Save Choices.js instance for future access.
          $el.data('choicesjs', choicesInstance);
        } catch (e) {} // eslint-disable-line no-empty
      });
    },

    /**
     * Initialize RichText field.
     *
     * @since 1.8.1
     *
     * @param {int} formId Form ID.
     */
    initRichTextField: function initRichTextField(formId) {
      // Set default tab to `Visual`.
      $("#sg-email-marketing-".concat(formId, " .wp-editor-wrap")).removeClass('html-active').addClass('tmce-active');
    }
  };

  // Provide access to public functions/properties.
  return app;
}(document, window, jQuery);

// Initialize.
SGEmailMarketing.FormSelector.init();
},{}]},{},[1])
