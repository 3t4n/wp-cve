import { __ } from '@wordpress/i18n';

const InputRadioTempUnits = ({ tempUnits, setTempUnits }) => {

    const handleChange = e => {
        if (e.target.checked) setTempUnits(e.target.value);
    }

    return (
        <tr>
            <th scope="row">
                <label>{ __( 'Temperature Units', 'weather-widget-wp' ) }</label>
            </th>
            <td>
                <label>
                    <input
                        checked={ tempUnits === 'C' }
                        onChange={ handleChange }
                        type="radio"
                        name="temp-units"
                        id="temp-units"
                        value="C"
                    />{ __( '°C - Celsius', 'weather-widget-wp' ) }</label><br />
                <label>
                    <input
                        checked={ tempUnits === 'F' }
                        onChange={ handleChange }
                        type="radio"
                        name="temp-units"
                        id="temp-units"
                        value="F"
                    />{ __( '°F - Fahrenheit', 'weather-widget-wp' ) }</label>
            </td>
        </tr>
    )
}
export default InputRadioTempUnits;
