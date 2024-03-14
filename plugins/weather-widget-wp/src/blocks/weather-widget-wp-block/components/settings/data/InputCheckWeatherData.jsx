import { __ } from '@wordpress/i18n';


const InputCheckWeatherData = ({ attributes, setAttributes }) => {

    const CheckboxDataIcon = () => {
        return (
            <label>
            <input
                checked={ attributes.dataIcon }
                onChange={ e => { setAttributes({ dataIcon: e.target.checked}) }}
                type="checkbox"
                name="weather-data-icon"
                value="1"
            />
            { __( 'Weather icon', 'weather-widget-wp' )}</label>
        )
    }

    const CheckboxDataBgImg = () => {
        return (
            <label>
            <input
                checked={ attributes.dataBgImg }
                onChange={ e => { setAttributes({ dataBgImg: e.target.checked}) }}
                type="checkbox"
                name="weather-data-bg"
                value="1"
            />
            { __( 'Background image', 'weather-widget-wp' )}</label>
        )
    }

    const CheckboxDataDesc = () => {
        return (
            <label>
            <input
                checked={ attributes.dataDesc }
                onChange={ e => { setAttributes({ dataDesc: e.target.checked}) }}
                type="checkbox"
                name="weather-data-desc"
                value="1"
            />
            { __( 'Short weather description', 'weather-widget-wp' )}</label>
        )
    }

    const CheckboxDataDate = () => {
        return (
            <label>
            <input
                checked={ attributes.dataDate }
                onChange={ e => { setAttributes({ dataDate: e.target.checked}) }}
                type="checkbox"
                name="weather-data-date"
                value="1"
            />
            { __( 'Current date', 'weather-widget-wp' )}</label>
        )
    }

    const CheckboxDataWind = () => {
        return (
            <label>
            <input
                checked={ attributes.dataWind }
                onChange={ e => { setAttributes({ dataWind: e.target.checked}) }}
                type="checkbox"
                name="weather-data-wind"
                value="1"
            />
            { __( 'Wind speed', 'weather-widget-wp' )}</label>
        )
    }

    const CheckboxDataMin = () => {
        return (
            <label>
            <input
                checked={ attributes.dataMin }
                onChange={ e => { setAttributes({ dataMin: e.target.checked}) }}
                type="checkbox"
                name="weather-data-min"
                value="1"
            />
            { __( 'Temperature minimum', 'weather-widget-wp' )}</label>
        )
    }

    const CheckboxDataMax = () => {
        return (
            <label>
            <input
                checked={ attributes.dataMax }
                onChange={ e => { setAttributes({ dataMax: e.target.checked}) }}
                type="checkbox"
                name="weather-data-max"
                value="1"
            />
            { __( 'Temperature maximum', 'weather-widget-wp' )}</label>
        )
    }

    const hasStyleExpanded = () => {
        if ( attributes.style === 'style-expanded' || attributes.style === 'style-expanded-no-css' ) {
            return true;
        }
    }

    return (
        <fieldset>
            <h2>{ __( 'Weather Data', 'weather-widget-wp' ) }</h2>

            <CheckboxDataIcon />
            {/* <CheckboxDataBgImg /> */}
            <CheckboxDataDesc />
            { hasStyleExpanded() ? <CheckboxDataDate /> : null }
            { hasStyleExpanded() ? <CheckboxDataWind /> : null }
            { hasStyleExpanded() ? <CheckboxDataMin /> : null }
            { hasStyleExpanded() ? <CheckboxDataMax /> : null }
        </fieldset>
    )
}
export default InputCheckWeatherData;
