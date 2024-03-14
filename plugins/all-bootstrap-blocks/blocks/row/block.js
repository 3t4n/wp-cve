import * as areoi from '../_components/Core.js';
import meta from './block.json';

const ALLOWED_BLOCKS = [ 
    'areoi/column', 
    'areoi/column-break' 
];

const BLOCKS_TEMPLATE = [
    [ 'areoi/column', {} ],
];

const blockIcon = <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24" x="0" y="0"/></g><g><g><path d="M19,13H5c-1.1,0-2,0.9-2,2v4c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2v-4C21,13.9,20.1,13,19,13z M19,19H5v-4h14V19z"/><path d="M19,3H5C3.9,3,3,3.9,3,5v4c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3z M19,9H5V5h14V9z"/></g></g></svg>;

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

        let classes = []

        if ( !areoi_vars.is_grid ) {
            classes = [
                'row',
                attributes.vertical_align_xs,
                attributes.vertical_align_sm,
                attributes.vertical_align_md,
                attributes.vertical_align_lg,
                attributes.vertical_align_xl,
                attributes.vertical_align_xxl,

                attributes.horizontal_align_xs,
                attributes.horizontal_align_sm,
                attributes.horizontal_align_md,
                attributes.horizontal_align_lg,
                attributes.horizontal_align_xl,
                attributes.horizontal_align_xxl,

                attributes.row_cols_xs,
                attributes.row_cols_sm,
                attributes.row_cols_md,
                attributes.row_cols_lg,
                attributes.row_cols_xl,
                attributes.row_cols_xxl
            ];
        } else {
            classes = [
                'grid'
            ];
        }        

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
                <div>

                    { areoi.DeviceLayout( areoi, attributes, onChange, tab ) }
                    
                    { !attributes['hide_' + tab.name] &&
                        <areoi.components.PanelBody title={ 'Settings (' + tab.title + ')' } initialOpen={ false }>                        
                            
                            {
                                !areoi_vars.is_grid &&
                                <>
                                <areoi.components.PanelRow className="areoi-panel-row">
                                    <areoi.components.SelectControl
                                        label="Vertical Align"
                                        labelPosition="top"
                                        help="Align content within row from top to bottom. This will be applied to all greater device sizes unless overridden."
                                        value={ attributes['vertical_align_' + tab.name] }
                                        options={ [
                                            { label: 'Default', value: null },
                                            { label: 'Start', value: 'align-items' + append + '-start' },
                                            { label: 'Center', value: 'align-items' + append + '-center' },
                                            { label: 'End', value: 'align-items' + append + '-end' },
                                        ] }
                                        onChange={ ( value ) => onChange( 'vertical_align_' + tab.name, value ) }
                                    />
                                </areoi.components.PanelRow>

                                <areoi.components.PanelRow className="areoi-panel-row">
                                    <areoi.components.SelectControl
                                        label="Horizontal Align"
                                        labelPosition="top"
                                        help="Align content within row from left to right. This will be applied to all greater device sizes unless overridden."
                                        value={ attributes['horizontal_align_' + tab.name] }
                                        options={ [
                                            { label: 'Default', value: null },
                                            { label: 'Start', value: 'justify-content' + append + '-start' },
                                            { label: 'Center', value: 'justify-content' + append + '-center' },
                                            { label: 'End', value: 'justify-content' + append + '-end' },
                                            { label: 'Around', value: 'justify-content' + append + '-around' },
                                            { label: 'Between', value: 'justify-content' + append + '-between' },
                                            { label: 'Evenly', value: 'justify-content' + append + '-evenly' },
                                        ] }
                                        onChange={ ( value ) => onChange( 'horizontal_align_' + tab.name, value ) }
                                    />
                                </areoi.components.PanelRow>

                                <areoi.components.PanelRow>
                                    <areoi.components.SelectControl
                                        label="Row Columns"
                                        labelPosition="top"
                                        help="Use the responsive .row-cols-* classes to quickly set the number of columns that best render your content and layout."
                                        value={ attributes['row_cols_' + tab.name] }
                                        options={ areoi.helper.GetCols( 'row-cols', tab.name ) }
                                        onChange={ ( value ) => onChange( 'row_cols_' + tab.name, value ) }
                                    />
                                </areoi.components.PanelRow>
                                </>
                            }

                            {
                                areoi_vars.is_grid &&
                                <>
                                <areoi.components.PanelRow>
                                    <areoi.components.SelectControl
                                        label="Grid Columns"
                                        labelPosition="top"
                                        help="Adjust the number of columns displayed within your grid."
                                        value={ attributes['row_cols_' + tab.name] }
                                        options={ areoi.helper.GetGridCols( 'row-cols', tab.name ) }
                                        onChange={ ( value ) => onChange( 'row_cols_' + tab.name, value ) }
                                    />
                                </areoi.components.PanelRow>

                                <areoi.components.PanelRow>
                                    <areoi.components.TextControl
                                        label="Grid Rows"
                                        labelPosition="top"
                                        help="Specify the number of rows displayed within your grid eg: 3."
                                        value={ attributes['grid_rows_' + tab.name] }
                                        onChange={ ( value ) => onChange( 'grid_rows_' + tab.name, value ) }
                                    />
                                </areoi.components.PanelRow>

                                <areoi.components.PanelRow className="areoi-panel-row">
                                    <label className="areoi-panel-row__label">Grid Gap</label>
                                    <table>
                                        <tr>
                                            <td>
                                                <areoi.components.TextControl
                                                    label="Dimensions"
                                                    value={ attributes['grid_gap_dimension_' + tab.name] }
                                                    onChange={ ( value ) => onChange( 'grid_gap_dimension_' + tab.name, value ) }
                                                />
                                            </td>
                                            <td class="areoi-field-reset">
                                                <areoi.components.SelectControl
                                                    label="Units"
                                                    labelPosition="top"
                                                    value={ attributes['grid_gap_unit_' + tab.name] }
                                                    options={ [
                                                        { label: 'px', value: 'px' },
                                                        { label: '%', value: '%' },
                                                        { label: 'vh', value: 'vh' },
                                                        { label: 'rem', value: 'rem' },
                                                    ] }
                                                    onChange={ ( value ) => onChange( 'grid_gap_unit_' + tab.name, value ) }
                                                />
                                            </td>
                                        </tr>
                                    </table>
                                    <p className="components-base-control__help css-1gbp77-StyledHelp">Dimensions will be applied to all devices greater than this one unless overridden in each devices settings.</p>
                                </areoi.components.PanelRow>

                                <areoi.components.PanelRow className="areoi-panel-row">
                                    <label className="areoi-panel-row__label">Grid Row Gap</label>
                                    <table>
                                        <tr>
                                            <td>
                                                <areoi.components.TextControl
                                                    label="Dimensions"
                                                    value={ attributes['grid_row_gap_dimension_' + tab.name] }
                                                    onChange={ ( value ) => onChange( 'grid_row_gap_dimension_' + tab.name, value ) }
                                                />
                                            </td>
                                            <td class="areoi-field-reset">
                                                <areoi.components.SelectControl
                                                    label="Units"
                                                    labelPosition="top"
                                                    value={ attributes['grid_row_gap_unit_' + tab.name] }
                                                    options={ [
                                                        { label: 'px', value: 'px' },
                                                        { label: '%', value: '%' },
                                                        { label: 'vh', value: 'vh' },
                                                        { label: 'rem', value: 'rem' },
                                                    ] }
                                                    onChange={ ( value ) => onChange( 'grid_row_gap_unit_' + tab.name, value ) }
                                                />
                                            </td>
                                        </tr>
                                    </table>
                                    <p className="components-base-control__help css-1gbp77-StyledHelp">Dimensions will be applied to all devices greater than this one unless overridden in each devices settings.</p>
                                </areoi.components.PanelRow>
                                </>

                            }
                            
                        </areoi.components.PanelBody>
                    }                    

                </div>
            );
        };
 
        return (
            <>
                { areoi.DisplayPreview( areoi, attributes, onChange, 'row' ) }
                {
                    !attributes.preview &&

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