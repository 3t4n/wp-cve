import { __ } from '@wordpress/i18n';

const ButtonSubmit = ({ saveButtonText }) => {
    return (
        <tr>
            <th scope="row">
                <p>
                    <button type="submit" className="button button-primary">{ saveButtonText }</button>
                </p>
            </th>
        </tr>
    )
}
export default ButtonSubmit;
