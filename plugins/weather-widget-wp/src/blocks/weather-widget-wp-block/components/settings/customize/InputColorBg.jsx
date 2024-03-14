import { __ } from '@wordpress/i18n';
import { GradientPicker } from '@wordpress/components';

const InputColorBg = ({ attributes, setAttributes }) => {

    const gradients = [
        {
            name: 'Clear Sky',
            slug: 'clear-sky',
            gradient: 'linear-gradient(74.1deg, #537895 11.78%, #A3BECD 90.32%)'
        },
        {
            name: 'Summer Sun',
            slug: 'summer-sun',
            gradient: 'linear-gradient(74.1deg, #ff9966 11.78%, #ff5e62 90.32%)'
        },
        {
            name: 'Autumn',
            slug: 'autumn',
            gradient: 'linear-gradient(74.1deg, #F2994A 11.78%, #F2C94C 90.32%)'
        },
        {
            name: 'Silver',
            slug: 'silver',
            gradient: 'linear-gradient(74.1deg, #8e9eab 11.78%, #eef2f3 90.32%)'
        },
        {
            name: 'Carbon',
            slug: 'carbon',
            gradient: 'linear-gradient(74.1deg, #232526 11.78%, #414345 90.32%)'
        }
    ]

    return (
        <fieldset>
            <h2>{ __( 'Background Color/Gradient', 'weather-widget-wp' ) }</h2>
            <GradientPicker
                onChange={ currentGradient  => { setAttributes({ bgColor: currentGradient }) } }
                value={ attributes.bgColor }
                gradients={ gradients }
                __nextHasNoMargin={ true }
            />
        </fieldset>
    )
}
export default InputColorBg;
