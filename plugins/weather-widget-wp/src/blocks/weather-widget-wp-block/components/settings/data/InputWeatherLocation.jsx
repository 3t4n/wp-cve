import { useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import fetchWeatherData from '../../fetchWeatherData';


const InputWeatherLocation = ({ attributes, setAttributes }) => {

    /*
     *  Delay the input API call for 2.5 sec with a timeout,
     *  so the API is not called on every input keystroke.
     */
    useEffect(() => {
        const inputTimeout = setTimeout(() => {
            fetchWeatherData({attributes, setAttributes});
        }, 2500)

        return () => clearTimeout(inputTimeout)

    }, [attributes.location]);

    return (
        <fieldset>
            <h2>{ __( 'Weather Location', 'weather-widget-wp' ) }</h2>
            <label
                className="components-base-control__label"
                htmlFor="weather-location"
            >
                { __( 'Location', 'weather-widget-wp' ) }
            </label>

            <input
                onChange={ e => { setAttributes({ location: e.target.value }) } }
                value={ attributes.location }
                id="weather-location"
                className="components-text-control__input"
                name="weather-location"
                type="text"
                max="20"
                placeholder={ __( 'Santorini', 'weather-widget-wp' ) }
                />
            <small>
                { __( 'Enter a name of a location (city) to show weather data. You will need to have API key from Open Weather added to the', 'weather-widget-wp' ) } <a href="/wp-admin/admin.php?page=weather-widget-wp" target="_blank">{ __( 'Weather Widget WP plugin settings', 'weather-widget-wp' ) }</a>.
            </small>
        </fieldset>
    )
}
export default InputWeatherLocation;
