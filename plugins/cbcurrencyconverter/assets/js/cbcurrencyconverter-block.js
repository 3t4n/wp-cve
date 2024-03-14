'use strict';


(function (blocks, element, components, editor, ServerSideRender, blockEditor) {


    var el                = element.createElement,
        registerBlockType = blocks.registerBlockType,
        InspectorControls = editor.InspectorControls,
        //InspectorControls = blockeditor.InspectorControls,

        InspectorControls = blockEditor.InspectorControls,

        //ServerSideRender  = components.ServerSideRender,
        //ServerSideRender = wp.serverSideRender,
        //ServerSideRender = wp.ServerSideRender,
        RangeControl      = components.RangeControl,
        Panel             = components.Panel,
        PanelBody         = components.PanelBody,
        PanelRow          = components.PanelRow,
        TextControl       = components.TextControl,
        //NumberControl = components.NumberControl,
        TextareaControl   = components.TextareaControl,
        CheckboxControl   = components.CheckboxControl,
        RadioControl      = components.RadioControl,
        SelectControl     = components.SelectControl,
        //FormTokenField      = components.FormTokenField ,
        //MultiSelectControl      = components.FormTokenField ,
        ToggleControl     = components.ToggleControl,
        //ColorPicker = components.ColorPalette,
        //ColorPicker = components.ColorPicker,
        //ColorPicker = components.ColorIndicator,
        PanelColorPicker = editor.PanelColorSettings,
        //PanelColorPicker  = blockeditor.PanelColorSettings,
        DateTimePicker    = components.DateTimePicker,
        HorizontalRule    = components.HorizontalRule,
        ExternalLink      = components.ExternalLink;

    //var MediaUpload = wp.editor.MediaUpload;



    //console.log(cbcurrencyconverter_block);



    var iconEl = el('svg', {
            width: 20,
            height: 20,
            viewBox: '0 0 212.755 212.755',
            enableBackground: 'new 0 0 212.755 212.755'
        },
        el('path', {
            d: "M106.377,0C47.721,0,0,47.721,0,106.377s47.721,106.377,106.377,106.377s106.377-47.721,106.377-106.377   S165.034,0,106.377,0z M106.377,198.755C55.44,198.755,14,157.314,14,106.377S55.44,14,106.377,14s92.377,41.44,92.377,92.377   S157.314,198.755,106.377,198.755z"
        }),
        el('path', {
            d: "m113.377,100.096v-39.744c3.961,1.471 7.417,4.17 9.82,7.82 2.127,3.229 6.468,4.123 9.696,1.997 3.229-2.126 4.123-6.467 1.996-9.696-5.029-7.636-12.778-12.82-21.512-14.647v-11.12c0-3.866-3.134-7-7-7s-7,3.134-7,7v11.099c-15.493,3.23-27.168,16.989-27.168,33.426 0,16.437 11.676,30.198 27.168,33.428v39.744c-3.961-1.471-7.417-4.17-9.82-7.82-2.127-3.229-6.468-4.124-9.696-1.997-3.229,2.126-4.123,6.467-1.996,9.696 5.029,7.636 12.778,12.82 21.512,14.647v11.119c0,3.866 3.134,7 7,7s7-3.134 7-7v-11.098c15.493-3.23 27.168-16.989 27.168-33.426-2.84217e-14-16.437-11.675-30.198-27.168-33.428zm-27.168-20.865c0-8.653 5.494-16.027 13.168-18.874v37.748c-7.674-2.847-13.168-10.221-13.168-18.874zm27.168,73.166v-37.748c7.674,2.847 13.168,10.221 13.168,18.874s-5.493,16.027-13.168,18.874z"
        })
    );

    registerBlockType('codeboxr/cbcurrencyconverter', {
        title: cbcurrencyconverter_block.block_title,
        icon: iconEl,
        category: cbcurrencyconverter_block.block_category,

        /*
         * In most other blocks, you'd see an 'attributes' property being defined here.
         * We've defined attributes in the PHP, that information is automatically sent
         * to the block editor, so we don't need to redefine it here.
         */
        edit: function (props) {

            //console.log(props.attributes);

            return [
                el(ServerSideRender, {
                    block: 'codeboxr/cbcurrencyconverter',
                    attributes: props.attributes
                }),
                el(InspectorControls, {},
                    el(PanelBody, {title: cbcurrencyconverter_block.general_settings.title, initialOpen: true},
                        el(SelectControl, {
                            label: cbcurrencyconverter_block.general_settings.layout,
                            options: cbcurrencyconverter_block.general_settings.layout_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    layout: value
                                });
                            },
                            value: props.attributes.layout
                        }),
                        el(TextControl, {
                            label: cbcurrencyconverter_block.general_settings.decimal_point,
                            onChange: (value) => {
                                props.setAttributes({
                                    decimal_point: parseInt(value)
                                });
                            },
                            type: 'number',
                            value: parseInt(props.attributes.decimal_point)
                        })
                    ),
                    el(PanelBody, {title: cbcurrencyconverter_block.calculator_settings.title, initialOpen: false},
                        el(TextControl, {
                            label: cbcurrencyconverter_block.calculator_settings.calc_title,
                            onChange: (value) => {
                                props.setAttributes({
                                    calc_title: value
                                });
                            },
                            value: props.attributes.calc_title
                        }),
                        el(SelectControl, {
                            label: cbcurrencyconverter_block.calculator_settings.calc_from_currencies,
                            options: cbcurrencyconverter_block.calculator_settings.calc_from_currencies_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    calc_from_currencies: value
                                });
                            },
                            multiple: true,
                            value: props.attributes.calc_from_currencies
                        }),
                        el(SelectControl, {
                            label: cbcurrencyconverter_block.calculator_settings.calc_from_currency,
                            options: cbcurrencyconverter_block.calculator_settings.calc_from_currencies_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    calc_from_currency: value
                                });
                            },
                            value: props.attributes.calc_from_currency
                        }),
                        el(SelectControl, {
                            label: cbcurrencyconverter_block.calculator_settings.calc_to_currencies,
                            options: cbcurrencyconverter_block.all_currencies_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    calc_to_currencies: value
                                });
                            },
                            multiple: true,
                            value: props.attributes.calc_to_currencies
                        }),
                        el(SelectControl, {
                            label: cbcurrencyconverter_block.calculator_settings.calc_to_currency,
                            options: cbcurrencyconverter_block.all_currencies_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    calc_to_currency: value
                                });
                            },
                            value: props.attributes.calc_to_currency
                        }),
                        el(TextControl, {
                            label: cbcurrencyconverter_block.calculator_settings.calc_default_amount,
                            type: 'number',
                            onChange: (value) => {
                                props.setAttributes({
                                    calc_default_amount: Number(value)
                                });
                            },
                            value: Number(props.attributes.calc_default_amount)
                        })
                    ),
                    el(PanelBody, {title: cbcurrencyconverter_block.list_settings.title, initialOpen: false},
                        el(TextControl, {
                            label: cbcurrencyconverter_block.list_settings.list_title,
                            onChange: (value) => {
                                props.setAttributes({
                                    list_title: value
                                });
                            },
                            value: props.attributes.list_title
                        }),
                        el(TextControl, {
                            label: cbcurrencyconverter_block.list_settings.list_default_amount,
                            type: 'number',
                            onChange: (value) => {
                                props.setAttributes({
                                    list_default_amount: Number(value)
                                });
                            },
                            value: Number(props.attributes.list_default_amount)
                        }),
                        el(SelectControl, {
                            label: cbcurrencyconverter_block.list_settings.list_from_currency,
                            options: cbcurrencyconverter_block.all_currencies_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    list_from_currency: value
                                });
                            },
                            value: props.attributes.list_from_currency
                        }),
                        el(SelectControl, {
                            label: cbcurrencyconverter_block.list_settings.list_to_currencies,
                            options: cbcurrencyconverter_block.all_currencies_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    list_to_currencies: value
                                });
                            },
                            multiple: true,
                            value: props.attributes.list_to_currencies
                        })
                    )
                )
            ];
        },
        // We're going to be rendering in PHP, so save() can just return null.
        save: function () {
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.editor,
    window.wp.serverSideRender,
    window.wp.blockEditor
));