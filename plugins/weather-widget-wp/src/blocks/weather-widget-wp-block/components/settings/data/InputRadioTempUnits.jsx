import { useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import fetchWeatherData from '../../fetchWeatherData';


const InputRadioTempUnits = ({ attributes, setAttributes }) => {

    /*
     *  Delay the input API call for 2.5 sec with a timeout,
     *  so the API is not called on every input keystroke.
     */
    useEffect(() => {
        const inputTimeout = setTimeout(() => {
            fetchWeatherData({attributes, setAttributes});
        }, 1000)

        return () => clearTimeout(inputTimeout)

    }, [attributes.tempUnits]);

    const handleChange = e => {
        if (e.target.checked) setAttributes({ tempUnits: e.target.value })
    }

    return (
        <fieldset>
            <h2>{ __( 'Temperature Units', 'weather-widget-wp' ) }</h2>

            <label>
                <input
                    checked={ attributes.tempUnits === 'C' }
                    onChange={ handleChange }
                    type="radio"
                    name="temp-units"
                    id="temp-units"
                    value="C"
                />{ __( '°C - Celsius', 'weather-widget-wp' ) }</label>
            <label>
            <input
                checked={ attributes.tempUnits === 'F' }
                onChange={ handleChange }
                type="radio"
                name="temp-units"
                id="temp-units"
                value="F"
            />{ __( '°F - Fahrenheit', 'weather-widget-wp' ) }</label>
        </fieldset>
    )
}
export default InputRadioTempUnits;
