import { __ } from '@wordpress/i18n';

const FooterGroup = ({ attributes }) => {
    return (
        <footer className="footer-group">
            { attributes.dataWind ? <figure className="weather-wind"><i className="weather-i-windy"></i><span>{ attributes.wind }</span><figcaption>{ __( 'wind speed', 'weather-widget-wp' ) }</figcaption></figure> : null }
            { attributes.dataMax ? <figure className="weather-temp-max"><i className="weather-i-temp-max"></i><span>{ attributes.tempMax }</span><figcaption>{ __( 'max temp.', 'weather-widget-wp' ) }</figcaption></figure> : null }
            { attributes.dataMin ? <figure className="weather-temp-min"><i className="weather-i-temp-min"></i><span>{ attributes.tempMin }</span><figcaption>{ __( 'min temp.', 'weather-widget-wp' ) }</figcaption></figure> : null }
        </footer>
    )
}
export default FooterGroup;
