'use strict';

function WpReadMoreBlock() {}

WpReadMoreBlock.prototype.init = function () {
    if (typeof wp == 'undefined' || typeof wp.element == 'undefined' || typeof wp.blocks == 'undefined' || typeof wp.editor == 'undefined' || typeof wp.components == 'undefined') {
        return false;
    }
    var localizedParams = YRM_GUTENBERG_PARAMS;

    var __ = wp.i18n;
    var createElement = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.editor.InspectorControls;
    var _wp$components = wp.components,
        SelectControl = _wp$components.SelectControl,
        TextareaControl = _wp$components.TextareaControl,
        ToggleControl = _wp$components.ToggleControl,
        PanelBody = _wp$components.PanelBody,
        ServerSideRender = _wp$components.ServerSideRender,
        Placeholder = _wp$components.Placeholder;

    registerBlockType('readmore/readmore', {
        title: localizedParams.title,
        description: localizedParams.description,
        keywords: ['Read more', 'read more', 'read', 'button'],
        category: 'widgets',
        icon: 'welcome-widgets-menus',
        attributes: {
            readMoreId: {
                type: 'number'
            },
            readMoreEvent: {
                type: 'string'
            }
        },
        edit: function edit(props) {
            var _props$attributes = props.attributes,
                _props$attributes$pop = _props$attributes.readMoreId,
                readMoreId = _props$attributes$pop === undefined ? '' : _props$attributes$pop,
                _props$attributes$dis = _props$attributes.displayTitle,
                displayTitle = _props$attributes$dis === undefined ? false : _props$attributes$dis,
                _props$attributes$dis2 = _props$attributes.displayDesc,
                displayDesc = _props$attributes$dis2 === undefined ? false : _props$attributes$dis2,
                _props$attributes$pop2 = _props$attributes.readMoreEvent,
                readMoreEvent = _props$attributes$pop2 === undefined ? '' : _props$attributes$pop2,
                setAttributes = props.setAttributes;

            const readMoreOptions = [];
            let allReadMores = YRM_GUTENBERG_PARAMS.allReadMores;
            // let eventsOptions = YRM_GUTENBERG_PARAMS.allEvents.map(function (value) {
            //     return {
            //         value: value.value,
            //         label: value.title
            //     };
            // });
            for(var id in allReadMores) {
                var currentdownObj = {
                    value: id,
                    label: allReadMores[id]
                };
                readMoreOptions.push(currentdownObj);
            }
            readMoreOptions.unshift({
                value: '',
                label: YRM_GUTENBERG_PARAMS.read_more_select
            });
            var jsx = void 0;

            function selectReadMore(value) {
                setAttributes({
                    readMoreId: parseInt(value)
                });
            }

            function selectEvent(value) {
                setAttributes({
                    readMoreEvent: value
                });
            }

            function setContent(value) {
                setAttributes({
                    content: value
                });
            }

            function toggleDisplayTitle(value) {
                setAttributes({
                    displayTitle: value
                });
            }

            function toggleDisplayDesc(value) {
                setAttributes({
                    displayDesc: value
                });
            }

            jsx = [React.createElement(
                InspectorControls,
                { key: 'readMore-gutenberg-form-selector-inspector-controls' },
                React.createElement(
                    PanelBody,
                    { title: 'popup more title' },
                    React.createElement('h2', "Insert Read More"),
                    React.createElement(SelectControl, {
                        label: 'Select read more',
                        value: readMoreId,
                        options: readMoreOptions,
                        onChange: selectReadMore
                    }),
                    React.createElement(ToggleControl, {
                        label: 'Select read more',
                        checked: displayTitle,
                        onChange: toggleDisplayTitle
                    }),
                    React.createElement(ToggleControl, {
                        label: 'Select read more',
                        checked: displayDesc,
                        onChange: toggleDisplayDesc
                    })
                )
            )];

            if (readMoreId) {
                var hiddenText = 'Read more hidden text';
                return '[expander_maker id="' + readMoreId + '"]'+hiddenText+'[/expander_maker]';
            } else {
                jsx.push(React.createElement(
                    Placeholder,
                    {
                        key: 'yrm-gutenberg-form-selector-wrap',
                        className: 'yrm-gutenberg-form-selector-wrapper' },
                    React.createElement('h5', null, "Insert Popup More"),
                    React.createElement(SelectControl, {
                        key: 'yrm-gutenberg-form-selector-select-control',
                        value: readMoreId,
                        options: readMoreOptions,
                        onChange: selectReadMore
                    }),
                    React.createElement(SelectControl, {
                        key: 'yrm-gutenberg-form-selector-select-control',
                        onChange: selectReadMore
                    })
                    // React.createElement(SelectControl, {
                    //     key: 'yrm-gutenberg-form-selector-select-control',
                    //     value: readMoreEvent,
                    //     options: eventsOptions,
                    //     onChange: selectEvent
                    // })
                ));
            }

            return jsx;
        },
        save: function save(props) {
            var hiddenText = 'Read more hidden text';
            return '[expander_maker id="' + props.attributes.readMoreId + '"]'+hiddenText+'[/expander_maker]';
        }
    });
};

jQuery(document).ready(function () {
    var block = new WpReadMoreBlock();
    block.init();
});