import { __ } from '@wordpress/i18n';
import { RangeControl  } from '@wordpress/components';

const InputRangeRoundness = ({ attributes, setAttributes }) => {
    return (
        <fieldset>
            <h2>{ __( 'Rounded Corners', 'weather-widget-wp' ) }</h2>
            <RangeControl
                onChange={ value => { setAttributes({ roundness: value }) } }
                value={ attributes.roundness }
                allowReset={ true }
                resetFallbackValue={ 18 }
                min={ 0 }
                max={ 36 }
            />
        </fieldset>
    )
}
export default InputRangeRoundness;
