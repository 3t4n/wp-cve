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
    var _wp$blocks = wp.blocks,
        registerBlockType = _wp$blocks.registerBlockType,
        source = _wp$blocks.source;
    var _wp$editor = wp.editor,
        RichText = _wp$editor.RichText,
        InspectorControls = _wp$editor.InspectorControls,
        MediaUpload = _wp$editor.MediaUpload,
        PanelColorSettings = _wp$editor.PanelColorSettings;
    var _wp$components = wp.components,
        PanelBody = _wp$components.PanelBody,
        PanelRow = _wp$components.PanelRow,
        Disabled = _wp$components.Disabled,
        ToggleControl = _wp$components.ToggleControl,
        Button = _wp$components.Button,
        SelectControl = _wp$components.SelectControl,
        TextControl = _wp$components.TextControl,
        ServerSideRender = _wp$components.ServerSideRender,
        CheckboxControl = _wp$components.CheckboxControl,
        RangeControl = _wp$components.RangeControl,
        ColorPalette = _wp$components.ColorPalette;
    var _wp$element = wp.element,
        Component = _wp$element.Component,
        Fragment = _wp$element.Fragment;
    var withState = wp.compose.withState;
    
    var el = wp.element.createElement;
    var iconEl = el('svg', { width: 20, height: 20 }, el('path', { d: "M12.5,12H12v-0.5c0-0.3-0.2-0.5-0.5-0.5H11V6h1l1-2c-1,0.1-2,0.1-3,0C9.2,3.4,8.6,2.8,8,2V1.5C8,1.2,7.8,1,7.5,1 S7,1.2,7,1.5V2C6.4,2.8,5.8,3.4,5,4C4,4.1,3,4.1,2,4l1,2h1v5c0,0-0.5,0-0.5,0C3.2,11,3,11.2,3,11.5V12H2.5C2.2,12,2,12.2,2,12.5V13 h11v-0.5C13,12.2,12.8,12,12.5,12z M7,11H5V6h2V11z M10,11H8V6h2V11z" }));
    registerBlockType('call/to-action-block', {
        title: __('Call To Action Block'),
        icon: iconEl,
        category: 'common',
    
        attributes: {
            content: {
               type: 'array',
                source: 'children'
            },
            newpage: {
                type: 'string'
            },
            calltoactionText: {
                source: 'children',
                selector: '.callToAction',
                default: 'Some text will go here...'
            },
            link_text: {
                source: 'children',
                selector: '.callToAction',
                default: 'Call To Action Button'
            },
            link_url: {
                type: 'string'
            },
    
            BGColor: {
                type: 'string'
            },
            buttonColor: {
                type: 'string'
            },
            buttonhoverColor: {
                type: 'string'
            },
            forColor: {
                type: 'string'
            },
            padding: {
                type: 'number',
                default: 5
            },
            textalign: {
                type: 'string',
                default: 'center'
            },
            BorderStyle: {
                type: 'string'
            },
            color: {
                type: 'string'
            },
            imageAlt: {
                attribute: 'alt',
                selector: '.card__image'
            },
            imageUrl: {
                attribute: 'src',
                selector: '.card__image'
            }
        },
    
        edit: function edit(props) {
            var link_text = props.attributes.link_text;
            var link_url = props.attributes.link_url;
            var newpage = props.attributes.newpage;
            var BGColor = props.attributes.BGColor;
            var buttonColor = props.attributes.buttonColor;
            var forColor = props.attributes.forColor;
            var padding = props.attributes.padding;
            var textalign = props.attributes.textalign;
            var calltoactionText = props.attributes.calltoactionText;
            var BorderStyle = props.attributes.BorderStyle;
            var imageID = props.attributes.imageID;
            var imageUrl = props.attributes.imageUrl;
    
            function onChangeContentURL(content) {
                props.setAttributes({ link_url: content });
            }
    
            function onChangeContentName(content) {
                props.setAttributes({ link_text: content });
            }
    
            function calltoactionTextfn(content) {
                props.setAttributes({ calltoactionText: content });
            }
    
            var getImageButton = function getImageButton(openEvent) {
                if (imageUrl) {
                    return wp.element.createElement('img', {
                        src: imageUrl,
                        onClick: openEvent,
                        className: 'image'
                    });
                } else {
                    return wp.element.createElement(
                        'div',
                        { className: 'button-container' },
                        wp.element.createElement(
                            Button,
                            {
                                onClick: openEvent,
                                className: 'button button-large'
                            },
                            'Pick an image'
                        )
                    );
                }
            };
    
            var block_style = {
                color: forColor,
                padding: padding,
                textAlign: textalign,
                borderStyle: BorderStyle,
                backgroundColor: BGColor
            };
            var buttonstyle = {
                color: buttonColor,
                borderColor: buttonColor
            };
            return [wp.element.createElement(
                InspectorControls,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Link Settings '), initialOpen: false, className: 'toggle-setting' },
                    wp.element.createElement(ToggleControl, {
                        label: __('Open In New Page'),
                        checked: newpage,
                        onChange: function onChange() {
                            return props.setAttributes({ newpage: "_blank" });
                        }
                    })
                ),
                wp.element.createElement(PanelColorSettings, { initialOpen: false, className: 'toggle-setting',
                    title: __('Color Settings'),
                    colorSettings: [{
                        value: BGColor,
                        onChange: function onChange(colorValue) {
                            return props.setAttributes({ BGColor: colorValue });
                        },
                        label: __('Background Color')
                    }, {
                        value: forColor,
                        onChange: function onChange(colorValue) {
                            return props.setAttributes({ forColor: colorValue });
                        },
                        label: __('Text Color')
                    }, {
                        value: buttonColor,
                        onChange: function onChange(colorValue) {
                            return props.setAttributes({ buttonColor: colorValue });
                        },
                        label: __('Button Color')
                    }]
                }),
                wp.element.createElement(
                    PanelBody,
                    { title: __('Style Box '), initialOpen: false, className: 'toggle-setting' },
                    wp.element.createElement(
                        'div',
                        { className: 'inspector-field inspector-field-fontsize ' },
                        wp.element.createElement(
                            'label',
                            { className: 'inspector-mb-0' },
                            'Padding'
                        ),
                        wp.element.createElement(RangeControl, {
                            value: padding,
                            min: 1,
                            max: 100,
                            step: 1,
                            onChange: function onChange(padding) {
                                return props.setAttributes({ padding: parseInt(padding) });
                            }
                        })
                    ),
                    wp.element.createElement(
                        'div',
                        { className: 'inspector-field' },
                        wp.element.createElement(
                            'label',
                            { className: 'inspector-mb-0' },
                            'Border Style'
                        ),
                        wp.element.createElement(SelectControl, {
                            value: BorderStyle,
                            options: [{ label: __('hidden'), value: 'hidden' }, { label: __('dashed'), value: 'dashed' }, { label: __('dotted'), value: 'dotted' }, { label: __('double'), value: 'double' }],
                            onChange: function onChange(value) {
                                props.setAttributes({ BorderStyle: value });
                            }
                        })
                    ),
                    wp.element.createElement(
                        'div',
                        { className: 'inspector-field inspector-field-alignment' },
                        wp.element.createElement(
                            'label',
                            { className: 'inspector-mb-0' },
                            'Alignment'
                        ),
                        wp.element.createElement(
                            'div',
                            { className: 'inspector-field-button-list inspector-field-button-list-fluid' },
                            wp.element.createElement(
                                'button',
                                { className: 'left' === textalign ? 'active  inspector-button' : 'inspector-button', onClick: function onClick() {
                                        props.setAttributes({ textalign: 'left' });
                                    } },
                                'left'
                            ),
                            wp.element.createElement(
                                'button',
                                { className: 'center' === textalign ? 'active  inspector-button' : 'inspector-button', onClick: function onClick() {
                                        props.setAttributes({ textalign: 'center' });
                                    } },
                                'center'
                            ),
                            wp.element.createElement(
                                'button',
                                { className: 'right' === textalign ? 'active  inspector-button' : 'inspector-button', onClick: function onClick() {
                                        props.setAttributes({ textalign: 'right' });
                                    } },
                                'right'
                            )
                        )
                    )
                ),
                wp.element.createElement(
                    PanelBody,
                    { title: __('Background Image'), initialOpen: false, className: 'toggle-setting' },
                    wp.element.createElement(
                        'label',
                        { className: 'inspector-mb-0' },
                        'Background Image'
                    ),
                    wp.element.createElement(MediaUpload, {
                        onSelect: function onSelect(media) {
                            props.setAttributes({ imageAlt: media.alt, imageUrl: media.url });
                        },
                        type: 'image',
                        value: imageID,
                        render: function render(_ref) {
                            var open = _ref.open;
                            return getImageButton(open);
                        }
                    })
                )
            ), wp.element.createElement(
                'div',
                { id: 'bgimageWrap', style: { backgroundImage: 'url(' + imageUrl + ')' } },
                wp.element.createElement(
                    'div',
                    { id: 'block-editable-box', style: block_style },
                    wp.element.createElement(RichText, {
                        className: 'callToAction',
                        onChange: calltoactionTextfn,
                        value: calltoactionText,
                        placeholder: 'Call To Action Text...'
                    }),
                    wp.element.createElement(RichText, {
                        className: 'ctabuttontext',
                        onChange: onChangeContentName,
                        value: link_text,
                        style: buttonstyle,
                        placeholder: 'Button text'
                    }),
                    wp.element.createElement(RichText, {
                        format: 'string',
                        className: 'calltoactionurl',
                        onChange: onChangeContentURL,
                        value: link_url,
                        placeholder: 'https://'
                    })
                )
            )];
        },
        save: function save(props) {
            var block_style = {
                backgroundColor: props.attributes.BGColor,
                color: props.attributes.forColor,
                padding: props.attributes.padding,
                textAlign: props.attributes.textalign,
                borderStyle: props.attributes.BorderStyle
            };
    
            var imageUrl = props.attributes.imageUrl;
            var calltoactionText = props.attributes.calltoactionText;
            var newpage = props.attributes.newpage;
            var link_url = props.attributes.link_url;
            var link_text = props.attributes.link_text;
    
            var a_style = {
                textAlign: props.attributes.textalign,
                color: props.attributes.buttonColor,
                borderColor: props.attributes.buttonColor
            };
            return wp.element.createElement(
                'div',
                { id: 'bgimageWrap', style: { backgroundImage: 'url(' + imageUrl + ')' } },
                wp.element.createElement(
                    'div',
                    { style: block_style },
                    wp.element.createElement(
                        'span',
                        null,
                        calltoactionText
                    ),
                    wp.element.createElement(
                        'a',
                        { style: a_style, rel: 'noopener noreferrer', id: 'calltoactionbutton', target: newpage, href: link_url },
                        link_text
                    )
                )
            );
        }
    });
    
    /***/ })
    /******/ ]);