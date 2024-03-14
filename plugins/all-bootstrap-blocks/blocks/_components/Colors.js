const Colors = ( areoi, attributes, onChange ) => {
    return (
        <>
            <areoi.components.PanelRow className="areoi-panel-row">
                <areoi.components.SelectControl
                    label="Background"
                    labelPosition="top"
                    help="Use the Bootstrap background utilities to change the background of a card."
                    value={ attributes.background }
                    options={ JSON.parse( areoi_vars.utility_bg ) }
                    onChange={ ( value ) => onChange( 'background', value ) }
                />

            </areoi.components.PanelRow>

            <areoi.components.PanelRow className="areoi-panel-row">
                <areoi.components.SelectControl
                    label="Text Color"
                    labelPosition="top"
                    help="Use the Bootstrap text color utilities to change the text color of a card."
                    value={ attributes.text_color }
                    options={ JSON.parse( areoi_vars.utility_text ) }
                    onChange={ ( value ) => onChange( 'text_color', value ) }
                />
            </areoi.components.PanelRow>

            <areoi.components.PanelRow>
                <areoi.components.SelectControl
                    label="Border Color"
                    labelPosition="top"
                    help="Use border utilities to change just the border-color of a card."
                    value={ attributes.border_color }
                    options={ JSON.parse( areoi_vars.utility_border ) }
                    onChange={ ( value ) => onChange( 'border_color', value ) }
                />
            </areoi.components.PanelRow>
        </>
    );
}

export default Colors;