import { __ } from '@wordpress/i18n';
import { ColorPalette } from '@wordpress/components';

const InputColorText = ({ attributes, setAttributes }) => {

    const colors = [
        { name: 'White', color: '#fff' },
        { name: 'Gray', color: '#ccc' },
        { name: 'Black', color: '#333' }
    ]

    return (
        <fieldset>
            <h2>{ __( 'Text Color', 'weather-widget-wp' ) }</h2>
            <label>
                { __( 'Pick custom text color', 'weather-widget-wp' ) }

                <ColorPalette
                    onChange={ color => { setAttributes({ textColor: color }) } }
                    value={ attributes.textColor }
                    colors={ colors }
                />
            </label>
        </fieldset>
    )
}
export default InputColorText;
