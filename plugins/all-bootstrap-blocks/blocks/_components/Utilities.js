const Utilities = ( areoi, attributes, onChange ) => {
    
    return (
        <areoi.components.PanelBody title={ 'Utilities' } initialOpen={ false }>
            <p>Utility clases will be added as base styles, but if you change settings such as background color further down the utility classes will be overriden.</p>
            <areoi.components.PanelRow className="areoi-panel-row">
                <areoi.components.SelectControl
                    label="Background"
                    labelPosition="top"
                    help="Use the Bootstrap background utilities to change the background of a card."
                    value={ attributes.utilities_bg }
                    options={ JSON.parse( areoi_vars.utility_bg ) }
                    onChange={ ( value ) => onChange( 'utilities_bg', value ) }
                />

            </areoi.components.PanelRow>

            <areoi.components.PanelRow className="areoi-panel-row">
                <areoi.components.SelectControl
                    label="Text Color"
                    labelPosition="top"
                    help="Use the Bootstrap text color utilities to change the text color of a card."
                    value={ attributes.utilities_text }
                    options={ JSON.parse( areoi_vars.utility_text ) }
                    onChange={ ( value ) => onChange( 'utilities_text', value ) }
                />
            </areoi.components.PanelRow>

            <areoi.components.PanelRow>
                <areoi.components.SelectControl
                    label="Border Color"
                    labelPosition="top"
                    help="Use border utilities to change just the border-color of a card."
                    value={ attributes.utilities_border }
                    options={ JSON.parse( areoi_vars.utility_border ) }
                    onChange={ ( value ) => onChange( 'utilities_border', value ) }
                />
            </areoi.components.PanelRow>
        </areoi.components.PanelBody>
    );
}

export default Utilities;