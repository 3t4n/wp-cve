import { __ } from '@wordpress/i18n';

const InputApiKey = ({ apiKey, setApiKey }) => {

    return (
        <tr>
            <th scope="row">
                <label htmlFor="api_key">{ __( 'Open Weather API Key', 'weather-widget-wp' ) }</label>
            </th>
            <td>
                <input
                    value={ apiKey }
                    onChange={ e => { setApiKey(e.target.value) } }
                    type="text"
                    name="api_key"
                    id="api_key"
                    className="regular-text"
                    placeholder={ __( 'Enter your API key here', 'weather-widget-wp' ) }
                /><br />
                <small>{ __( 'You need to have or ', 'weather-widget-wp' ) }<a href="https://home.openweathermap.org/users/sign_up" target="_blank">{ __( 'create', 'weather-widget-wp' ) }</a>{ __( ' (free) account via Open Weather API.', 'weather-widget-wp' )}<br />{ __( 'After that click on your name > My API keys > Generate API key.', 'weather-widget-wp' ) }<br /><strong>{ __( 'API key would take around 15 min to get activated,', 'weather-widget-wp' ) }</strong><br />{ __( 'this means you won\'t see any weather data in that time.', 'weather-widget-wp' ) }</small>
            </td>
        </tr>
    )
}
export default InputApiKey;
