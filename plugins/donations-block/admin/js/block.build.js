/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
//import rightSideBarIcons from './rightSidebar/icon';

(function (blocks, i18n, element, editor, components) {
    var __ = wp.i18n.__;
    var registerBlockType = wp.blocks.registerBlockType;
    var _wp$element = wp.element,
        Fragment = _wp$element.Fragment,
        RawHTML = _wp$element.RawHTML;
    var _wp$editor = wp.editor,
        MediaUpload = _wp$editor.MediaUpload,
        AlignmentToolbar = _wp$editor.AlignmentToolbar,
        InspectorControls = _wp$editor.InspectorControls,
        InnerBlocks = _wp$editor.InnerBlocks,
        PanelColorSettings = _wp$editor.PanelColorSettings,
        BlockAlignmentToolbar = _wp$editor.BlockAlignmentToolbar,
        RichText = _wp$editor.RichText,
        PlainText = _wp$editor.PlainText;
    var _wp$components = wp.components,
        PanelBody = _wp$components.PanelBody,
        TextControl = _wp$components.TextControl,
        Button = _wp$components.Button,
        SelectControl = _wp$components.SelectControl,
        RangeControl = _wp$components.RangeControl,
        ToggleControl = _wp$components.ToggleControl,
        ServerSideRender = _wp$components.ServerSideRender,
        RadioControl = _wp$components.RadioControl;

    var paypalIcon = wp.element.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", height: "20px", width: "20px", viewBox: "0 0 512.069 512.069" }, wp.element.createElement("g", { transform: "translate(0 1)" }, wp.element.createElement("g", null, wp.element.createElement("g", null, wp.element.createElement("path", { d: "M198.167,232.882l1.707,0.853c0,0,6.827,0.853,16.213,0.853c12.8,0,31.573-0.853,50.347-5.12 c47.787-10.24,75.093-34.987,80.213-70.827c0.853-0.853,5.973-25.6-7.68-46.933c-8.533-13.653-23.04-22.187-42.667-25.6 c-9.387-1.707-43.52-5.973-64.853-2.56c-24.747,4.267-32.427,29.867-32.427,31.573l-18.773,80.213 c-0.853,2.56-3.413,12.8,0.853,23.04C183.661,222.642,187.927,229.469,198.167,232.882z M196.461,200.455l19.627-81.067 c0,0,5.12-16.213,18.773-18.773c17.067-2.56,49.493,0.853,58.88,2.56c14.507,2.56,25.6,8.533,31.573,17.92 c10.24,14.507,5.973,33.28,5.973,34.133c-9.387,67.413-116.907,62.293-128.853,61.44c-5.973-1.707-6.827-5.12-6.827-8.533 C195.607,203.869,196.461,200.455,196.461,200.455z" }), wp.element.createElement("path", { d: "M462.38,68.767c-1.944-3.368-4.101-6.696-6.507-9.965C426.861,19.549,369.687-0.931,287.767-0.931H143.554 c-12.8-0.853-38.4,5.973-46.08,36.693L2.754,433.415c0,0.736-0.316,3.699,0.008,7.649c-0.002,0.011-0.006,0.02-0.008,0.031 c0,0-0.853,3.413,0,6.827v37.547c0,13.653,11.093,25.6,25.6,25.6h110.08c0.853,0,0.853,0,1.707,0 c11.947,0,33.28-7.68,38.4-34.987l21.333-91.307c0.853-1.707,5.973-19.627,27.307-19.627h4.267 c149.333,1.707,246.613-47.787,274.773-138.24C516.972,193.001,502.876,111.806,462.38,68.767z M114.541,39.175 c5.12-22.187,23.893-23.04,28.16-23.04l145.92,0.853c75.947,0,128,17.92,153.6,52.907c0.871,1.153,1.704,2.318,2.503,3.492 c0.233,0.57,0.529,1.119,0.911,1.628c17.686,27.793,16.667,60.717,12.522,83.568c-1.502,8.023-3.357,14.805-4.842,19.685 c-26.453,82.773-117.76,128-258.56,126.293h-4.267c-28.16,0-40.96,21.333-43.52,32.427l-21.333,91.307 c-4.267,23.04-24.747,23.04-27.307,23.04H33.474c-5.12,0-8.533-0.853-11.093-3.413c-2.56-3.413-2.56-9.387-2.56-11.093 L114.541,39.175z M490.007,220.935c-26.453,83.627-116.053,127.147-258.56,126.293h-4.267c-28.16,0-40.96,21.333-43.52,32.427 l-21.333,91.307c-4.267,23.04-20.48,23.04-23.04,23.04H28.354c-4.267,0-8.533-3.413-8.533-8.533v-18.773 c4.267,0.853,8.533,1.707,12.8,1.707h0.853H96.62c2.131,0.142,4.621,0.065,7.312-0.294c13.85-1.631,33.348-10.613,37.915-37.253 l21.333-90.453c0.853-1.707,5.973-19.627,27.307-19.627h3.413h0.853c148.48,1.707,246.613-47.787,274.773-138.24 c3.547-11.529,8.704-32.275,8.174-55.891C491.805,163.006,495.911,203.223,490.007,220.935z" })))));

    var shortcode_obj = { email: "yourpaypalemail@example.com", size: "large", amount: "15.00", currency: "USD", purpose: "Charity for Child Health Care", mode: "live", suggestion: "1, 5, 10, 20, 50, 100" };

    registerBlockType('bk-block/paypal-donation-block', {
        title: __('Paypal Donation Block'),
        icon: paypalIcon, // Block ICON
        category: 'common',
        attributes: {
            id: {
                type: 'number',
                default: 1
            },
            shortcode_content: {
                type: "string",
                source: "text",
                default: "[paypal_donation_block email='" + shortcode_obj.email + "' amount='" + shortcode_obj.amount + "' currency='" + shortcode_obj.currency + "' size='" + shortcode_obj.size + "' purpose='" + shortcode_obj.purpose + "' mode='" + shortcode_obj.mode + "' suggestion='" + shortcode_obj.suggestion + "']"
            },
            selectnamecontrol: {
                type: 'string',
                default: ''
            },
            paypal_email: {
                type: 'string',
                default: ''
            },
            paypal_amount: {
                type: 'string',
                default: ''
            },
            paypal_currency: {
                type: 'string',
                default: ''
            },
            paypal_size: {
                type: 'string',
                default: ''
            },
            paypal_purpsoe: {
                type: 'string',
                default: ''
            },
            paypal_type: {
                type: 'string',
                default: ''
            },
            paypal_suggestion: {
                type: 'string',
                default: ''
            }
        },

        edit: function edit(props) {
            var _props$attributes = props.attributes,
                className = _props$attributes.className,
                shortcode_content = _props$attributes.shortcode_content,
                Onchangename = _props$attributes.Onchangename,
                selectnamecontrol = _props$attributes.selectnamecontrol,
                paypal_email = _props$attributes.paypal_email,
                paypal_amount = _props$attributes.paypal_amount,
                paypal_currency = _props$attributes.paypal_currency,
                paypal_size = _props$attributes.paypal_size,
                paypal_purpsoe = _props$attributes.paypal_purpsoe,
                paypal_type = _props$attributes.paypal_type,
                paypal_suggestion = _props$attributes.paypal_suggestion;
            setAttributes = props.setAttributes;

            var onChangePaypalEmail = function onChangePaypalEmail(newContent) {

                var userAmount = paypal_amount ? paypal_amount : shortcode_obj.amount;
                var userCurrency = paypal_currency ? paypal_currency : shortcode_obj.currency;
                var userSize = paypal_size ? paypal_size : shortcode_obj.size;
                var userPurpsoe = paypal_purpsoe ? paypal_purpsoe : shortcode_obj.purpose;
                var userMode = paypal_type ? paypal_type : shortcode_obj.mode;
                var userSuggestion = paypal_suggestion ? paypal_suggestion : shortcode_obj.suggestion;

                email_new_content = "[paypal_donation_block email='" + newContent + "' amount='" + userAmount + "' currency='" + userCurrency + "' size='" + userSize + "'  purpose='" + userPurpsoe + "' mode='" + userMode + "' suggestion='" + userSuggestion + "']";
                shortcode_obj.email = newContent;
                setAttributes({ shortcode_content: email_new_content });
                setAttributes({ paypal_email: newContent });
            };
            var onChangePaypalAmount = function onChangePaypalAmount(newContent) {
                var userEmail = paypal_email ? paypal_email : shortcode_obj.email;
                var userCurrency = paypal_currency ? paypal_currency : shortcode_obj.currency;
                var userSize = paypal_size ? paypal_size : shortcode_obj.size;
                var userPurpsoe = paypal_purpsoe ? paypal_purpsoe : shortcode_obj.purpose;
                var userMode = paypal_type ? paypal_type : shortcode_obj.mode;
                var userSuggestion = paypal_suggestion ? paypal_suggestion : shortcode_obj.suggestion;

                amount_new_content = "[paypal_donation_block email='" + userEmail + "' amount='" + newContent + "' currency='" + userCurrency + "' size='" + userSize + " purpose='" + userPurpsoe + "' mode='" + userMode + "' suggestion='" + userSuggestion + "']";
                shortcode_obj.amount = newContent;
                setAttributes({ shortcode_content: amount_new_content });
                setAttributes({ paypal_amount: newContent });
            };
            var onChangeCurrency = function onChangeCurrency(newContent) {
                var userEmail = paypal_email ? paypal_email : shortcode_obj.email;
                var userAmount = paypal_amount ? paypal_amount : shortcode_obj.amount;
                var userSize = paypal_size ? paypal_size : shortcode_obj.size;
                var userPurpsoe = paypal_purpsoe ? paypal_purpsoe : shortcode_obj.purpose;
                var userMode = paypal_type ? paypal_type : shortcode_obj.mode;
                var userSuggestion = paypal_suggestion ? paypal_suggestion : shortcode_obj.suggestion;

                currency_new_content = "[paypal_donation_block email='" + userEmail + "' amount='" + userAmount + "' currency='" + newContent + "' size='" + userSize + "' purpose='" + userPurpsoe + "' mode='" + userMode + "' suggestion='" + userSuggestion + "']";
                shortcode_obj.currency = newContent;
                setAttributes({ shortcode_content: currency_new_content });
                setAttributes({ paypal_currency: newContent });
            };
            var onChangeButtonSize = function onChangeButtonSize(newContent) {
                var userEmail = paypal_email ? paypal_email : shortcode_obj.email;
                var userAmount = paypal_amount ? paypal_amount : shortcode_obj.amount;
                var userCurrency = paypal_currency ? paypal_currency : shortcode_obj.currency;
                var userPurpsoe = paypal_purpsoe ? paypal_purpsoe : shortcode_obj.purpose;
                var userMode = paypal_type ? paypal_type : shortcode_obj.mode;
                var userSuggestion = paypal_suggestion ? paypal_suggestion : shortcode_obj.suggestion;

                button_new_content = "[paypal_donation_block email='" + userEmail + "' amount='" + userAmount + " currency='" + userCurrency + "' size='" + newContent + "' purpose='" + userPurpsoe + "' mode='" + userMode + "' suggestion='" + userSuggestion + "']";
                shortcode_obj.size = newContent;
                setAttributes({ shortcode_content: button_new_content });
                setAttributes({ paypal_size: newContent });
            };
            var onChangePurpose = function onChangePurpose(newContent) {
                var userEmail = paypal_email ? paypal_email : shortcode_obj.email;
                var userAmount = paypal_amount ? paypal_amount : shortcode_obj.amount;
                var userCurrency = paypal_currency ? paypal_currency : shortcode_obj.currency;
                var userSize = paypal_size ? paypal_size : shortcode_obj.size;
                var userMode = paypal_type ? paypal_type : shortcode_obj.mode;
                var userSuggestion = paypal_suggestion ? paypal_suggestion : shortcode_obj.suggestion;

                purpose_new_content = "[paypal_donation_block email='" + userEmail + "' amount='" + userAmount + "' currency='" + userCurrency + "' size='" + userSize + "' purpose='" + newContent + "' mode='" + userMode + "' suggestion='" + userSuggestion + "']";
                shortcode_obj.purpose = newContent;
                setAttributes({ shortcode_content: purpose_new_content });
                setAttributes({ paypal_purpsoe: newContent });
            };
            var onChangeSuggestion = function onChangeSuggestion(newContent) {
                var userEmail = paypal_email ? paypal_email : shortcode_obj.email;
                var userAmount = paypal_amount ? paypal_amount : shortcode_obj.amount;
                var userCurrency = paypal_currency ? paypal_currency : shortcode_obj.currency;
                var userSize = paypal_size ? paypal_size : shortcode_obj.size;
                var userMode = paypal_type ? paypal_type : shortcode_obj.mode;
                var userPurpsoe = paypal_purpsoe ? paypal_purpsoe : shortcode_obj.purpose;

                // validation of suggestion amount
                /*let suggestion_array = newContent.split(',');
                let finaContent = [];
                jQuery.each(suggestion_array, function( index, value ) {
                    if (value.trim().length > 0) {
                        if (!(value > 0)) {
                            alert('Only add comma separated numeric value allow!')
                            // newContent = '';
                            return;
                        } else {
                            finaContent.push(value);
                        }
                    }
                });*/

                suggestion_new_content = "[paypal_donation_block email='" + userEmail + "' amount='" + userAmount + "' currency='" + userCurrency + "' size='" + userSize + "' purpose='" + userPurpsoe + "' mode='" + userMode + "' suggestion='" + newContent + "']";
                shortcode_obj.suggestion = newContent.toString();
                setAttributes({ shortcode_content: suggestion_new_content });
                setAttributes({ paypal_suggestion: newContent.toString() });
            };
            var OnchangePaypalType = function OnchangePaypalType(newContent) {
                var userEmail = paypal_email ? paypal_email : shortcode_obj.email;
                var userAmount = paypal_amount ? paypal_amount : shortcode_obj.amount;
                var userCurrency = paypal_currency ? paypal_currency : shortcode_obj.currency;
                var userPurpsoe = paypal_purpsoe ? paypal_purpsoe : shortcode_obj.purpose;
                var userSize = paypal_size ? paypal_size : shortcode_obj.size;
                var userSuggestion = paypal_suggestion ? paypal_suggestion : shortcode_obj.suggestion;

                type_new_content = "[paypal_donation_block email='" + userEmail + "' amount='" + userAmount + "' currency='" + userCurrency + "' size='" + userSize + "' purpose='" + userPurpsoe + "' mode='" + newContent + "' suggestion='" + userSuggestion + "']";
                shortcode_obj.mode = newContent;
                setAttributes({ shortcode_content: type_new_content });
                setAttributes({ paypal_type: newContent });
            };
            function onChangeShortcodeTitle(newContent) {
                setAttributes({ shortcode_content: newContent });
            }
            var onChangeNameTitle = function onChangeNameTitle(newTitle) {
                setAttributes({ selectnamecontrol: newTitle });
                setAttributes({ shortcode_content: newTitle });
            };
            return [wp.element.createElement(InspectorControls, null, wp.element.createElement("div", { className: "custom-inspactor-setting" }, wp.element.createElement(PanelBody, { title: __('Paypal Donation Block'), initialOpen: false }, wp.element.createElement(RadioControl, {
                label: "Paypal Mode",
                selected: paypal_type,
                options: [{ label: 'Live', value: 'live' }, { label: 'Sandbox', value: 'sandbox' }],
                onChange: OnchangePaypalType
            }), wp.element.createElement(TextControl, {
                label: "Email",
                value: paypal_email
                //onChange={ ( className ) => setState( { className } ) }
                , onChange: onChangePaypalEmail
            }), wp.element.createElement(TextControl, {
                label: "Amount",
                type: "number",
                value: paypal_amount,
                onChange: onChangePaypalAmount
            }), wp.element.createElement(SelectControl, {
                label: __('Select Currency'),
                value: paypal_currency,
                options: [{ label: __('Select Currency'), value: '' }, { label: __('Australian Dollars (A $)'), value: 'AUD' }, { label: __('Brazilian Real'), value: 'BRL' }, { label: __('Canadian Dollars (C $)'), value: 'CAD' }, { label: __('Czech Koruna'), value: 'CZK' }, { label: __('Danish Krone'), value: 'DKK' }, { label: __('Euros (€)'), value: 'EUR' }, { label: __('Hong Kong Dollar ($)'), value: 'HKD' }, { label: __('Hungarian Forint'), value: 'HUF' }, { label: __('Israeli New Shekel'), value: 'ILS' }, { label: __('Yen (¥)'), value: 'JPY' }, { label: __('Malaysian Ringgit'), value: 'MYR' }, { label: __('Mexican Peso'), value: 'MXN' }, { label: __('Norwegian Krone'), value: 'NOK' }, { label: __('New Zealand Dollar ($)'), value: 'NZD' }, { label: __('Philippine Peso'), value: 'PHP' }, { label: __('Polish Zloty'), value: 'PLN' }, { label: __('Pounds Sterling (£)'), value: 'GBP' }, { label: __('Russian Ruble'), value: 'RUB' }, { label: __('Singapore Dollar ($)'), value: 'SGD' }, { label: __('Swedish Krona'), value: 'SEK' }, { label: __('Swiss Franc'), value: 'CHF' }, { label: __('Taiwan New Dollar'), value: 'TWD' }, { label: __('Thai Baht'), value: 'THB' }, { label: __('Turkish Lira'), value: 'TRY' }, { label: __('US Dollars'), value: 'USD' }],
                onChange: onChangeCurrency
            }), wp.element.createElement(SelectControl, {
                label: __('Select Button Size'),
                value: paypal_size,
                options: [{ label: __('Select Size'), value: '' }, { label: __('Samll'), value: 'small' }, { label: __('Medium'), value: 'medium' }, { label: __('Large'), value: 'large' }],
                onChange: onChangeButtonSize
            }), wp.element.createElement(TextControl, {
                label: "Purpose",
                value: paypal_purpsoe,
                onChange: onChangePurpose
            }), wp.element.createElement(TextControl, {
                label: "Suggestion Amount",
                value: paypal_suggestion,
                onChange: onChangeSuggestion
            })))), wp.element.createElement("div", { className: "content-wrap wp-block-shortcode" }, wp.element.createElement("label", { htmlFor: "blocks-shortcode-input-0" }, wp.element.createElement("svg", { "aria-hidden": "true", role: "img", focusable: "false",
                className: "dashicon dashicons-shortcode", xmlns: "http://www.w3.org/2000/svg", width: "20",
                height: "20", viewBox: "0 0 20 20" }, wp.element.createElement("path", { d: "M6 14H4V6h2V4H2v12h4M7.1 17h2.1l3.7-14h-2.1M14 4v2h2v8h-2v2h4V4" })), "Shortcode"), wp.element.createElement(PlainText, {
                className: "input-control",
                value: shortcode_content,
                id: "input-control",
                placeholder: "Write shortcode here\u2026",
                onChange: onChangeShortcodeTitle
            }))];
        },
        save: function save(_ref) {
            var attributes = _ref.attributes;
            var shortcode_content = attributes.shortcode_content;

            return wp.element.createElement("div", { className: "content-wrap wp-block-shortcode" }, wp.element.createElement(RawHTML, null, shortcode_content));
        }
    });
})(window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.editor, window.wp.components);

/***/ })
/******/ ]);