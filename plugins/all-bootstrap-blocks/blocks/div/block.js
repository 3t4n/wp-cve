import * as areoi from '../_components/Core.js';
import meta from './block.json';

const ALLOWED_BLOCKS = null;
const BLOCKS_TEMPLATE = null;

const blockIcon = <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 10H5V8h14v8z"/></svg>;

areoi.blocks.registerBlockType( meta, {
    icon: blockIcon,
    edit: props => {
        const {
            attributes,
            setAttributes,
            clientId,
            isSelected
        } = props;

        const { block_id } = attributes;
        if ( !block_id ) {
            setAttributes( { block_id: clientId } );
        }

        const classes = [
            'div',
            attributes.utilities_bg,
            attributes.utilities_text,
            attributes.utilities_border,
        ];

        const {
            linkTarget,
            rel,
            text,
            url,
        } = attributes;
        const onSetLinkRel = areoi.element.useCallback(
            ( value ) => {
                setAttributes( { rel: value } );
            },
            [ setAttributes ]
        );

        const onToggleOpenInNewTab = areoi.element.useCallback(
            ( value ) => {
                const newLinkTarget = value ? '_blank' : undefined;

                let updatedRel = rel;
                if ( newLinkTarget && ! rel ) {
                    updatedRel = NEW_TAB_REL;
                } else if ( ! newLinkTarget && rel === NEW_TAB_REL ) {
                    updatedRel = undefined;
                }

                setAttributes( {
                    linkTarget: newLinkTarget,
                    rel: updatedRel,
                } );
            },
            [ rel, setAttributes ]
        );

        const ref = areoi.element.useRef();
        const richTextRef = areoi.element.useRef();

        const blockProps = areoi.editor.useBlockProps( {
            className: areoi.helper.GetClassName( classes ),
            style: { cssText: areoi.helper.GetStyles( attributes ) }
        } );

        function onChange( key, value ) {
            setAttributes( { [key]: value } );
        }

        const tabDevice = ( tab ) => {
            return (
                <div>
                    { areoi.DeviceLayout( areoi, attributes, onChange, tab ) }

                    { areoi.DeviceBackground( areoi, attributes, onChange, tab ) }
                </div>
            );
        };

        return (
            <>
                { areoi.DisplayPreview( areoi, attributes, onChange, 'div' ) }
                {
                    !attributes.preview &&
                    <div { ...blockProps }>
                        <areoi.editor.InspectorControls key="setting">

                            { areoi.Utilities( areoi, attributes, onChange ) }

                            { areoi.Background( areoi, attributes, onChange ) }

                            { areoi.ResponsiveTabPanel( tabDevice, meta, props ) }
                                
                        </areoi.editor.InspectorControls>

                        <areoi.URLPicker
                            areoi={ areoi }
                            url={ url }
                            setAttributes={ setAttributes }
                            isSelected={ isSelected }
                            opensInNewTab={ linkTarget === '_blank' }
                            onToggleOpenInNewTab={ onToggleOpenInNewTab }
                            anchorRef={ ref }
                            richTextRef={ richTextRef }
                        />

                        { areoi.DisplayBackground( areoi, attributes ) }

                        <areoi.editor.InnerBlocks template={ BLOCKS_TEMPLATE } allowedBlocks={ ALLOWED_BLOCKS } />
                    </div>
                }
            </>
        );
    },
    save: () => { 
        return (
            <areoi.editor.InnerBlocks.Content/>
        );
    },
});