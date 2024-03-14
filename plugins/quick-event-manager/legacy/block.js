var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    ServerSideRender = wp.components.ServerSideRender,
    TextControl = wp.components.TextControl,
    RadioControl = wp.components.RadioControl,
    SelectControl = wp.components.SelectControl,
    TextareaControl = wp.components.TextareaControl,
    CheckboxControl = wp.components.CheckboxControl,
    InspectorControls = wp.editor.InspectorControls;

registerBlockType('quick-event-manager/eventlist', {
    title: 'Event List',
    description: 'Displays the Event List',
    icon: 'list-view',
    category: 'widgets',
    edit: function (props) {
        return [
            el('h2', // Tag type.
                {
                    className: props.className,  // Class name is generated using the block's name prefixed with wp-block-, replacing the / namespace separator with a single -.
                },
                'Event List' // Block content
            ),
            el(TextControl, {
                label: 'ID',
                value: props.attributes.id,
                onChange: (value) => {
                    props.setAttributes({id: value});
                },
            }),
        ];
    },
    save: function () {
        return null;
    },
});

registerBlockType('quick-event-manager/calendar', {
    title: 'Event Calendar',
    description: 'Displays the Event Calendar',
    icon: 'calendar-alt',
    category: 'widgets',

    edit: function (props) {
        return [
            el('h2', // Tag type.
                {
                    className: props.className,  // Class name is generated using the block's name prefixed with wp-block-, replacing the / namespace separator with a single -.
                },
                'Event Calendar' // Block content
            ),
        ];
    },

    save: function () {
        return null;
    },
});
