import { __ } from '@wordpress/i18n';
import { RangeControl, ColorPalette } from '@wordpress/components';

const InputShadowControlDropShadow = ({ attributes, setAttributes }) => {

    const colors = [
        { name: 'Dark Shadow', color: 'rgba(51, 51, 51, 0.35)' }
    ]

    return (
        <fieldset>
            <h2>{ __( 'Drop Shadow', 'weather-widget-wp' ) }</h2>
            <RangeControl
                onChange={ value => { setAttributes({ shadowSize: value }) } }
                value={ attributes.shadowSize }
                label={ __( 'Shadow size:', 'weather-widget-wp' ) }
                allowReset={ true }
                resetFallbackValue={ 30 }
                min={ 0 }
                max={ 100 }
            />

            <label>
                { __( 'Shadow color:', 'weather-widget-wp' ) }

                <ColorPalette
                    onChange={ color => { setAttributes({ shadowColor: color }) } }
                    value={ attributes.shadowColor }
                    colors={ colors }
                    enableAlpha
                />
            </label>
        </fieldset>
    )
}
export default InputShadowControlDropShadow;
