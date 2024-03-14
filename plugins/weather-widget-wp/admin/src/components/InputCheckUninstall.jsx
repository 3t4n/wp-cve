import { __ } from '@wordpress/i18n';

const InputCheckUninstall = ({ uninstallData, setUninstallData }) => {
    return (
        <tr>
            <th scope="row">
                <label>{ __( 'Save/Delete plugin data', 'weather-widget-wp' )}</label>
            </th>
            <td>
                <input
                    checked={ uninstallData }
                    onChange={ e => { setUninstallData(e.target.checked) }}
                    type="checkbox"
                    name="uninstall-data"
                    id="uninstall-data"
                    value="1"
                />
                <label htmlFor="uninstall-data">{ __( 'Remove all plugin data when the plugin is removed.', 'weather-widget-wp' )}</label>
            </td>
        </tr>
    )
}
export default InputCheckUninstall;
