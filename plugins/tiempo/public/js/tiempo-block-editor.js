(function(wp) {
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var ToggleControl = wp.components.ToggleControl;
    var SelectControl = wp.components.SelectControl;
    var ColorPicker = wp.components.ColorPicker;
    var RadioControl = wp.components.RadioControl;

    var registerBlockType = wp.blocks.registerBlockType;


    registerBlockType('tiempo/widget', {
        title: 'Tiempo Widget',
        icon: 'cloud',
        category: 'widgets',
        example: {
            attributes: {
                // This is where your static preview image would be included
                previewImage: 'https://ps.w.org/tiempo/assets/screenshot-1.png',
            },
        },
        attributes: {
            city: {
                type: 'string',
                default: 'Madrid',
            },
            country: {
                type: 'string',
                default: 'Spain',
            },
            backgroundColor: {
                type: 'string',
                default: '#becffb',
            },
            widgetWidth: {
                type: 'string',
                default: '100',
            },
            textColor: {
                type: 'string',
                default: '#000000',
            },
            days: {
                type: 'number',
                default: 3,
            },
            showSunrise: {
                type: 'boolean',
                default: false,
            },
            showWind: {
                type: 'boolean',
                default: false,
            },
            language: {
                type: 'string',
                default: 'spanish',
            },
            showCurrent: {
                type: 'boolean',
                default: true,
            },
        },

        edit: function(props) {
            return el(
                'div', {},
                el(
                    'div', {},
                    el(
                        PanelBody, {
                            title: 'Location Settings',
                            initialOpen: true
                        },
                        el(TextControl, {
                            label: 'City',
                            value: props.attributes.city,
                            onChange: function(newValue) {
                                props.setAttributes({ city: newValue });
                            }
                        }),
                        el(TextControl, {
                            label: 'Country',
                            value: props.attributes.country,
                            onChange: function(newValue) {
                                props.setAttributes({ country: newValue });
                            }
                        })
                    ),

                    el(
                        PanelBody, {
                            title: 'Weather Settings',
                            initialOpen: false
                        },

                        el(SelectControl, {
                            label: 'Days of Forecast',
                            value: props.attributes.days,
                            options: [
                                { label: 'No Forecast', value: 0 },
                                { label: '1 Days', value: 1 },
                                { label: '2 Days', value: 2 },
                                { label: '3 Days', value: 3 },
                                { label: '4 Days', value: 4 },
                                { label: '5 Days', value: 5 },
                                { label: '6 Days', value: 6 },
                            ],
                            onChange: function(newValue) {
                                props.setAttributes({ days: parseInt(newValue, 10) });
                            }
                        }),
                        el(ToggleControl, {
                            label: 'Show Current Weather',
                            checked: props.attributes.showCurrent,
                            onChange: function(newValue) {
                                props.setAttributes({ showCurrent: newValue });
                            }
                        }),
                        el(ToggleControl, {
                            label: 'Show Wind Information',
                            checked: props.attributes.showWind,
                            onChange: function(newValue) {
                                props.setAttributes({ showWind:  newValue ? 'on' : '' });
                            }
                        }),
                        el(ToggleControl, {
                            label: 'Show Sunrise and Sunset',
                            checked: props.attributes.showSunrise,
                            onChange: function(newValue) {
                                // props.setAttributes({ showSunrise: newValue });
                                props.setAttributes({ showSunrise: newValue ? 'on' : '' });
                            }
                        }),
                    ),
                    el(
                        PanelBody, {
                            title: 'Widget Settings',
                            initialOpen: false
                        },
                        el(SelectControl, {
                            label: 'Language',
                            value: props.attributes.language,
                            options: [
                                { label: 'Spanish', value: 'spanish' },
                                { label: 'English', value: 'english' },
                            ],
                            onChange: function(newValue) {
                                props.setAttributes({ language: newValue });
                            }
                        }),
                        el(RadioControl, {
                            label: 'Widget Width',
                            selected: props.attributes.widgetWidth,
                            options: [
                                { label: '100%', value: '100' },
                                { label: 'Tight', value: 'tight' },
                            ],
                            onChange: function(newValue) {
                                props.setAttributes({ widgetWidth: newValue });
                            }
                        }),
                    ),
                    el(
                        PanelBody, {
                            title: 'Color Settings',
                            initialOpen: false
                        },
                        el(wp.blockEditor.PanelColorSettings, {
                            title: '',
                            initialOpen: true,
                            colorSettings: [
                                {
                                    value: props.attributes.backgroundColor,
                                    onChange: function(colorValue) {
                                        props.setAttributes({ backgroundColor: colorValue });
                                    },
                                    label: 'Background Color',
                                },
                                {
                                    value: props.attributes.textColor,
                                    onChange: function(colorValue) {
                                        props.setAttributes({ textColor: colorValue });
                                    },
                                    label: 'Text Color',
                                },
                            ],
                        }),
                    )
                ),
                // ... Your block preview or other components here
            );
        },




        save: function() {
            // Saving handled server side
            return null;
        },
    });
})(window.wp);
