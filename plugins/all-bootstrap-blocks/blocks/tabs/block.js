import * as areoi from '../_components/Core.js';
import meta from './block.json';

const ALLOWED_BLOCKS = [ 'areoi/div' ];
const BLOCKS_TEMPLATE = [ 
    [
        'areoi/nav-and-tab', {
            style: 'nav-tabs'
        }, [
            ['areoi/nav-and-tab-item', {
                url: '#tab-1',
                text: 'Tab 1',
                active: true
            } ],
            ['areoi/nav-and-tab-item', {
                url: '#tab-2',
                text: 'Tab 2',
                active: false
            } ]
        ]
    ],
    ['areoi/div', {
        anchor: 'tab-1'
    }, [
        ['core/paragraph', {
            content: 'Tab 1 Content'
        } ]
    ] ],
    ['areoi/div', {
        anchor: 'tab-2'
    }, [
        ['core/paragraph', {
            content: 'Tab 2 Content'
        } ]
    ] ]
];

const blockIcon = <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/><path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h10v4h8v10z"/></svg>;

areoi.blocks.registerBlockType( meta, {
    icon: blockIcon,
    edit: props => {
        const {
            attributes,
            setAttributes,
            clientId
        } = props;

        const { block_id } = attributes;
        if ( !block_id ) {
            setAttributes( { block_id: clientId } );
        }

        const classes = [
            'tabs',
        ];

        const blockProps = areoi.editor.useBlockProps( {
            className: areoi.helper.GetClassName( classes ),
            style: { cssText: areoi.helper.GetStyles( attributes ) }
        } );

        function onChange( key, value ) {
            setAttributes( { [key]: value } );
        }

        const tabDevice = ( tab ) => {
            var append = ( tab.name == 'xs' ? '' : '-' + tab.name );

            return (
                <>
                    { areoi.DisplayVisibility( areoi, attributes, onChange, tab ) }
                </>
            );
        };
 
        return (
            <>
                { areoi.DisplayPreview( areoi, attributes, onChange, 'tabs' ) }

                { !attributes.preview &&
                    <div { ...blockProps }>
                        <areoi.editor.InspectorControls key="setting">

                            { areoi.ResponsiveTabPanel( tabDevice, meta, props ) }
                                
                        </areoi.editor.InspectorControls>

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
} );