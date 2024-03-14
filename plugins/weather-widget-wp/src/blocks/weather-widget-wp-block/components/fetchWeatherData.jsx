const fetchWeatherData = ({ attributes, setAttributes }) => {

    /*
     *  weatherWidgetWpObject object is created via wp_localize_script (this script)
     *  also WordPress nonce is set and an apiUrl
     */
    const url = `${weatherWidgetWpObject.apiUrl}weather-widget-wp/api/settings`;

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-NONCE': weatherWidgetWpObject.nonce
        }
    })
    .then(response => {
        if (!response.ok) throw Error(response.status + ' - Open Weather API error.')
        return response
    })
    .then(response => response.json())
    .then(data => {
        fetch(`https://api.openweathermap.org/data/2.5/weather?q=${attributes.location}&appid=${data.api_key}`)
        .then(response => response.json())
        .then(data => {
            if (data.cod === 401) console.log('No API key, typo or added newly created API key. Newly created Open Weather API keys need 15 minutes to be active and show data, so please be patient.')

            if (!data.cod === 200) throw new Error('Open Weather API error')

            // Get the icon code from the data > weather array, and set the icon attribute
            data.weather.forEach(w => setAttributes({
                iconCode: w.id,
                description: w.description
            }))

            // Converts Kelvin (from the API) to Celsius or Fahrenheit based on the user choice, and set the temperature attribute.
            setAttributes({
                temperature: attributes.tempUnits === 'C' ? Math.round(data.main.temp - 273.15) : Math.round((data.main.temp - 273.15) * 9 / 5 + 32),
                tempMin: attributes.tempUnits === 'C' ? Math.round(data.main.temp_min - 273.15) + '°C' : Math.round((data.main.temp_min - 273.15) * 9 / 5 + 32) + '°F',
                tempMax: attributes.tempUnits === 'C' ? Math.round(data.main.temp_max - 273.15) + '°C' : Math.round((data.main.temp_max - 273.15) * 9 / 5 + 32) + '°F',
                wind: data.wind.speed + 'm/s'
            })
        })
        .catch(() => console.log('Open Weather API error'))
    })
    .catch(() => console.log('Open Weather API error'));
}
export default fetchWeatherData;
