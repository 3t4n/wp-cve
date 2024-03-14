import { colord, Colord } from 'colord';

const ColorPicker = ( areoi, attributes, onChange, key, label ) => {

    var new_color = null 

    function testonChange( key, value )
    {
        var color = value;
        
        if ( !value.hex ) {
            color = {
                hex: value,
                hsl: colord( value ).toHsl(),
                hsv: colord( value ).toHsv(),
                oldHue: colord( value ).hue(),
                rgb: colord( value ).toRgb(),
                source: 'hex'
            }
        }
        
        onChange( key, color );
    }
    
    return (
        <areoi.components.PanelRow className="areoi-panel-row areoi-panel-row-color">
            <label>{ label }</label>
            
            <div className="areoi-color-picker">
                <areoi.components.ColorPicker
                    color={ attributes[key] }
                    onChangeComplete={ ( val ) => testonChange( key, val ) }
                />

                <areoi.components.ColorPalette
                    colors={ areoi_vars.colors }
                    value={ new_color }
                    onChange={ ( val ) => testonChange( key, val ) }
                />
            </div>
        </areoi.components.PanelRow>
    );
}

export default ColorPicker;