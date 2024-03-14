import { __ } from '@wordpress/i18n';

const InputSelectStyle = ({ style, setStyle }) => {
    return (
        <tr>
            <th scope="row">
                <label htmlFor="style">{ __( 'Select Style', 'weather-widget-wp' ) }</label>
            </th>
            <td>
                <select
                    value={ style }
                    onChange={ e => { setStyle(e.target.value) } }
                    name="style"
                    id="style"
                    className="regular-text"
                >
                    <option value="default">{ __( 'Style 1', 'weather-widget-wp' ) }</option>
                    <option value="style_2">{ __( 'Style 2', 'weather-widget-wp' ) }</option>
                    <option value="style_3">{ __( 'Style 3', 'weather-widget-wp' ) }</option>
                    <option value="style_4">{ __( 'Style 4', 'weather-widget-wp' ) }</option>
                    <option value="style_5">{ __( 'Style 5', 'weather-widget-wp' ) }</option>
                </select>
            </td>
        </tr>
    )
}
export default InputSelectStyle;
