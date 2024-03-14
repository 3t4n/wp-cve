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
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    SelectControl = _wp$components.SelectControl,
    TextControl = _wp$components.TextControl;
var ServerSideRender = wp.serverSideRender;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.blockEditor.InspectorControls;
var __ = wp.i18n.__;

/**
 * Block inspector controls options
 *
 */

// The options for the Calendars dropdown

var calendars = [];

calendars[0] = { value: 0, label: __('Select Calendar...', 'wp-simple-booking-calendar') };

for (var i = 0; i < wpsbc_calendars.length; i++) {

    calendars.push({ value: wpsbc_calendars[i].id, label: wpsbc_calendars[i].name });
}

// The option for the Language dropdown
var languages = [];

languages[0] = { value: 'auto', label: __('Auto', 'wp-simple-booking-calendar') };

for (var i = 0; i < wpsbc_languages.length; i++) {

    languages.push({ value: wpsbc_languages[i].code, label: wpsbc_languages[i].name });
}

// Register the block
registerBlockType('wp-simple-booking-calendar/single-calendar', {

    // The block's title
    title: 'Single Calendar',

    // The block's icon
    icon: 'calendar-alt',

    // The block category the block should be added to
    category: 'wp-simple-booking-calendar',

    // The block's attributes, needed to save the data
    attributes: {

        id: {
            type: 'string'
        },

        title: {
            type: 'string'
        },

        legend: {
            type: 'string'
        },

        legend_position: {
            type: 'string'
        },

        language: {
            type: 'string',
            default: 'auto'
        }

    },

    edit: function edit(props) {

        return [wp.element.createElement(ServerSideRender, {
            block: 'wp-simple-booking-calendar/single-calendar',
            attributes: props.attributes }), wp.element.createElement(
            InspectorControls,
            { key: 'inspector' },
            wp.element.createElement(
                PanelBody,
                {
                    title: __('Calendar', 'wp-simple-booking-calendar'),
                    initialOpen: true },
                wp.element.createElement(SelectControl, {
                    value: props.attributes.id,
                    options: calendars,
                    onChange: function onChange(new_value) {
                        return props.setAttributes({ id: new_value });
                    } })
            ),
            wp.element.createElement(
                PanelBody,
                {
                    title: __('Calendar Basic Options', 'wp-simple-booking-calendar'),
                    initialOpen: true },
                wp.element.createElement(SelectControl, {
                    label: __('Display Calendar Title', 'wp-simple-booking-calendar'),
                    value: props.attributes.title,
                    options: [{ value: 'yes', label: __('Yes', 'wp-simple-booking-calendar') }, { value: 'no', label: __('No', 'wp-simple-booking-calendar') }],
                    onChange: function onChange(new_value) {
                        return props.setAttributes({ title: new_value });
                    } }),
                wp.element.createElement(SelectControl, {
                    label: __('Display Legend', 'wp-simple-booking-calendar'),
                    value: props.attributes.legend,
                    options: [{ value: 'yes', label: __('Yes', 'wp-simple-booking-calendar') }, { value: 'no', label: __('No', 'wp-simple-booking-calendar') }],
                    onChange: function onChange(new_value) {
                        return props.setAttributes({ legend: new_value });
                    } }),
                wp.element.createElement(SelectControl, {
                    label: __('Legend Position', 'wp-simple-booking-calendar'),
                    value: props.attributes.legend_position,
                    options: [{ value: 'side', label: __('Side', 'wp-simple-booking-calendar') }, { value: 'top', label: __('Top', 'wp-simple-booking-calendar') }, { value: 'bottom', label: __('Bottom', 'wp-simple-booking-calendar') }],
                    onChange: function onChange(new_value) {
                        return props.setAttributes({ legend_position: new_value });
                    } }),
                wp.element.createElement(SelectControl, {
                    label: __('Language', 'wp-simple-booking-calendar'),
                    value: props.attributes.language,
                    options: languages,
                    onChange: function onChange(new_value) {
                        return props.setAttributes({ language: new_value });
                    } })
            )
        )];
    },

    save: function save() {
        return null;
    }

});

jQuery(function ($) {

    /**
     * Runs every 250 milliseconds to check if a calendar was just loaded
     * and if it was, trigger the window resize to show it
     *
     */
    setInterval(function () {

        $('.wpsbc-container-loaded').each(function () {

            if ($(this).attr('data-just-loaded') == '1') {
                $(window).trigger('resize');
                $(this).attr('data-just-loaded', '0');
            }
        });
    }, 250);
});

/***/ })
/******/ ]);