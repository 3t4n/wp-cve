import { __ } from '@wordpress/i18n';
import { __experimentalBoxControl as BoxControl } from '@wordpress/components';

const InputBoxControlPaddingMin = ({ attributes, setAttributes }) => {

    const hasStyleExpanded          = () => { if ( attributes.style === 'style-expanded' ) return true };
    const updatePaddingBasedOnStyle = value => hasStyleExpanded() ? setAttributes({ paddingExp: value }) : setAttributes({ paddingMin: value });
    const resetValuesBasedOnStyle   = () => hasStyleExpanded() ? { top: '115px', left: '40px', right: '40px', bottom: '40px' } : { top: '30px', left: '30px', right: '30px', bottom: '30px' }
    const showValueBasedOnStyle     = () => hasStyleExpanded() ? attributes.paddingExp : attributes.paddingMin;

    return (
        <fieldset>
            <h2>{ __( 'Padding', 'weather-widget-wp' ) }</h2>
            <BoxControl
                onChange={ nextValues => { updatePaddingBasedOnStyle( nextValues ) } }
                resetValues={ resetValuesBasedOnStyle() }
                values={ showValueBasedOnStyle() }
            />
        </fieldset>
    )
}
export default InputBoxControlPaddingMin;
