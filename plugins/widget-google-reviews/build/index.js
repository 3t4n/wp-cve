(function(blocks, editor, element, components, api) {

    var el            = element.createElement,
        SelectControl = components.SelectControl,
        Button        = components.Button,
        __            = wp.i18n.__;

    blocks.registerBlockType('widget-google-reviews/reviews', {
        title: __('Google Reviews Block', 'widget-google-reviews'),
        icon: 'star-filled',
        category: 'widgets',
        keywords: ['google', 'reviews', 'block'],
        attributes: {id: {type: 'string'}},

        edit: function(props) {

            var attributes = props.attributes;
            var blockProps = wp.blockEditor.useBlockProps();

            let feeds = grwBlockData.feeds,
                options = [{label: __('Select reviews widget'), value: 0}];

            for (let i = 0; i < feeds.length; i++) {
                options.push({label: feeds[i].name, value: feeds[i].id});
            }

            return el(
                'div',
                blockProps,
                el(
                    SelectControl,
                    {
                        id: 'id',
                        name: 'id',
                        value: props.attributes.id,
                        options: options,
                        onChange: function(newval) {
                            props.setAttributes({id: newval});
                        }
                    }
                ),
                el(
                    Button,
                    {
                        text: __('Edit reviews widget'),
                        href: grwBlockData.builderUrl + '&grw_feed_id=' + props.attributes.id,
                        target: '_blank'
                    }
                ),
                el(
                    Button,
                    {
                        text: __('Create new reviews widget'),
                        href: grwBlockData.builderUrl,
                        target: '_blank'
                    }
                )
            );
        },

        save: function(props) {
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.editor,
    window.wp.element,
    window.wp.components,
    window.wp.api
));