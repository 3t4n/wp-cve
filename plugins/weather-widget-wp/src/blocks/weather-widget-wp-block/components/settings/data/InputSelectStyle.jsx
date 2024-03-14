import { __ } from '@wordpress/i18n';


const InputSelectStyle = ({ attributes, setAttributes }) => {

    return (
        <fieldset>
            <h2>{ __( 'Style', 'weather-widget-wp' ) }</h2>
            <select
                value={ attributes.style }
                onChange={ e => { setAttributes({ style: e.target.value }) } }
                name="style"
                id="style"
            >
                <option value="style-minimal">{ __( 'Minimal', 'weather-widget-wp' ) }</option>
                <option value="style-expanded">{ __( 'Expanded', 'weather-widget-wp' ) }</option>
            </select>
        </fieldset>
    )
}
export default InputSelectStyle;
