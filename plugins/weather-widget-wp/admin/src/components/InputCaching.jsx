import { __ } from '@wordpress/i18n';

const InputCaching = ({ caching, setCaching }) => {

    return (
        <tr>
            <th scope="row">
                <label htmlFor="caching">{ __( 'Caching', 'weather-widget-wp' ) }</label>
            </th>
            <td>
                <input
                    value={ caching }
                    onChange={ e => { setCaching(e.target.value) } }
                    type="number"
                    min="0" max="24"
                    name="caching"
                    id="caching"
                    className="regular-text"
                    placeholder={ __( '4 hours', 'weather-widget-wp' ) }
                /><br />
                <small>{ __( 'Save the weather result (hours) so you don\'t hit the API', 'weather-widget-wp' ) }<br />{ __( 'on every page reload and exceed your free quota.', 'weather-widget-wp' ) }</small>
            </td>
        </tr>
    )
}
export default InputCaching;
