/*

function addSpacerAttributes(settings, name) {
    if (typeof settings.attributes !== 'undefined') {
        if (name === 'wlr-loyalty-rule-block/your-reward') {
            settings.attributes = Object.assign(settings.attributes, {
                backgroundColor: {
                    type: 'string',
                },
                customBackgroundColor: {
                    type: 'string'
                }
            });
        }
    }
    return settings;
}

wp.hooks.addFilter(
    'blocks.registerBlockType',
    'wlr-loyalty-rule-block/your-reward',
    addSpacerAttributes
);
const { YourReward } = wp.element;
const spacerInspectorControls = wp.compose.compose(

    wp.blockEditor.withColors({backgroundColor: 'background-color'}),

    wp.compose.createHigherOrderComponent((YourReward) => {
        return (props) => {

            if (props.name !== 'wlr-loyalty-rule-block/your-reward') {
               return  (<YourReward {...props} />);
            }

            const { Fragment } = wp.element;
            const { InspectorControls, PanelColorSettings } = wp.blockEditor;
            const { attributes, setAttributes, isSelected } = props;
            const { backgroundColor, setBackgroundColor } = props;

            let newClassName = (attributes.className != undefined) ? attributes.className : '';
            let newStyles = {...props.style};
            if (backgroundColor != undefined) {
                if (backgroundColor.class == undefined) {
                    newStyles.backgroundColor = backgroundColor.color;
                } else {
                    newClassName += ' ' + backgroundColor.class;
                }
            }

            const newProps = {
                ...props,
                attributes: {
                    ...attributes,
                    className: newClassName
                },
                style: newStyles
            };

            return (
                <Fragment>
                    <div style={newStyles} className={newClassName}>
                        <BlockEdit {...newProps} />
                        {isSelected && (props.name == 'wlr-loyalty-rule-block/your-reward') &&
                        <InspectorControls>
                            <PanelColorSettings
                                title={wp.i18n.__('Color Settings', 'awp')}
                                colorSettings={[
                                    {
                                        value: backgroundColor.color,
                                        onChange: setBackgroundColor,
                                        label: wp.i18n.__('Background color', 'awp')
                                    }
                                ]}
                            />
                        </InspectorControls>
                        }
                    </div>
                </Fragment>
            );
        };
    }, 'spacerInspectorControls'));

wp.hooks.addFilter(
    'editor.BlockEdit',
    'wlr-loyalty-rule-block/your-reward',
    spacerInspectorControls
);
*/
