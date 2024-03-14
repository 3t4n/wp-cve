const weatherStatusCode = ({ attributes, setAttributes }) => {
    switch (true) {

        case attributes.iconCode <= 299 :
            // Thunderstorm
            setAttributes({
                iconClass: 'weather-i-cloud-thunder',
                bgImgClass: ' bg-img-rain-cloud-thunder'
            })
        break;

        case attributes.iconCode <= 399 :
            // Drizzle
            setAttributes({
                iconClass: 'weather-i-rain-drizzle',
                bgImgClass: ' bg-img-rain-drizzle'
            })
        break;

        case attributes.iconCode <= 599 :
            // Rain
            setAttributes({
                iconClass: 'weather-i-rain',
                bgImgClass: ' bg-img-rain'
            })
        break;

        case attributes.iconCode <= 699 :
            // Snow
            setAttributes({
                iconClass: 'weather-i-snowflake',
                bgImgClass: ' bg-img-snow'
            })
        break;

        case attributes.iconCode <= 799 :
            // Atmosphere / Wind
            setAttributes({
                iconClass: 'weather-i-windy',
                bgImgClass: ' bg-img-windy'
            })
        break;

        case attributes.iconCode <= 800 :
            // Clear
            setAttributes({
                iconClass: 'weather-i-sun',
                bgImgClass: ' bg-img-sun'
            })
        break;

        case attributes.iconCode <= 899 :
            // Clouds
            setAttributes({
                iconClass: 'weather-i-clouds',
                bgImgClass: ' bg-img-clouds'
            })
        break;

        default:
            // Unknown
            setAttributes({
                iconClass: 'weather-i-cloud-sun',
                bgImgClass: ' bg-img-broken-clouds'
            })
        break;
    }
}
export default weatherStatusCode;
