<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Weather Widget
 *
 * @since v1.0.0
 */
if ( ! function_exists( 'weather_widget_wp_shortcode_weather_location' ) ) {
    function weather_widget_wp_shortcode_weather_location( $atts ) {

        // Get the plugin data from the DB
        $options        = get_option( 'weather_widget_wp_options', weather_widget_wp_options_default() );

        $api_key        = isset( $options['api_key'] ) ? $options['api_key'] : '';
        $caching        = isset( $options['caching'] ) ? $options['caching'] : '';

        // Shortcode atts.
        extract(
            shortcode_atts(
                array(
                    'css_class'    => '',
                    'css_style'    => '',
                    'block_align'  => '',
                    'style'        => 'style-expanded',
                    'city'         => '',
                    'title'        => '',
                    'units'        => 'C',
                    'desc'         => true,
                    'icon'         => true,
                    'bg_img'       => true,
                    'date'         => true,
                    'wind'         => true,
                    'max'          => true,
                    'min'          => true
                ),
                $atts
            )
        );

        $esc_city = esc_attr( $city );

        // Check if there is cached as transient result, if not hit the API
        if ( false === ( $response = get_transient( "weather_widget_wp_data_${esc_city}" ) ) ) {

            // WP HTTP API call
            $request = wp_remote_get( "https://api.openweathermap.org/data/2.5/weather?q=${esc_city}&appid=${api_key}" );
            $response = wp_remote_retrieve_body( $request );
            $response_code = wp_remote_retrieve_response_code( $request );

            // Stop & show API error if there is one
            $status = '';
            if ( $response === false || $response_code != 200 ) {

                if ( $response_code === 401 ) {
                    $status = $response_code . esc_html__( ' - Weather API error!', 'weather-widget-wp' );

                    // Show preview of the widget, because newly created api keys take around 15 min. to be active and show data.
                    return weather_widget_wp_preview_widget( $esc_city, $units, $status );
                } else {
                    return '<small class="weather-error" style="color:red; text-transform:none; letter-spacing: 0;">' . esc_html( $status ) . '</small>';
                }
            }

            // Decode & Parse JSON
            $response = json_decode($response);

            foreach ($response as $item) {
                $name = $response->name;

                // Cache the result as transient in the db.
                set_transient( 'weather_widget_wp_data_' . $name, $response, $caching * HOUR_IN_SECONDS );
            }
        }


        // Convert Kelvin to Celsius/Fahrenheit
        $temp_celsius = round($response->main->temp - 273.15, 0);
        $temp_fahrenheit = round((($response->main->temp - 273.15) * 9 / 5) + 32, 0);

        // Get the min & max temp.
        $temp_min_celsius = round($response->main->temp_min - 273.15, 0) . "°C";
        $temp_max_celsius = round($response->main->temp_max - 273.15, 0) . "°C";
        $temp_min_fahrenheit = round((($response->main->temp_min - 273.15) * 9 / 5) + 32, 0) . "°F";
        $temp_max_fahrenheit = round((($response->main->temp_max - 273.15) * 9 / 5) + 32, 0) . "°F";

        // Temperature
        if ( $units === 'C' ) {
            $temp = $temp_celsius;
            $temp_min = $temp_min_celsius;
            $temp_max = $temp_max_celsius;
        } else {
            $temp = $temp_fahrenheit;
            $temp_min = $temp_min_fahrenheit;
            $temp_max = $temp_max_fahrenheit;
        }

        // Get the wind
        $w_wind = $response->wind->speed;

        // Get the icon & description for the weather
        foreach ($response->weather as $weather) {
            $code = $weather->id;
            $description = $weather->description;
        }

        // Match the icon code to the icon markup
        switch (true) {
            case $code <= 299:
                // Thunderstorm
                $icon_markup = '<i class="weather-i-cloud-thunder"></i>';
                $bg_img_class = ' bg-img-rain-cloud-thunder';
                break;

            case $code <= 399:
                // Drizzle
                $icon_markup = '<i class="weather-i-rain-drizzle"></i>';
                $bg_img_class = ' bg-img-rain-drizzle';
                break;

            case $code <= 599:
                // Rain
                $icon_markup = '<i class="weather-i-rain"></i>';
                $bg_img_class = ' bg-img-rain';
                break;

            case $code <= 699:
                // Snow
                $icon_markup = '<i class="weather-i-snowflake"></i>';
                $bg_img_class = ' bg-img-snow';
                break;

            case $code <= 799:
                // Atmosphere / Wind
                $icon_markup = '<i class="weather-i-windy"></i>';
                $bg_img_class = ' bg-img-windy';
                break;

            case $code == 800:
                // Clear
                $icon_markup = '<i class="weather-i-sun"></i>';
                $bg_img_class = ' bg-img-sun';
                break;

            case $code <= 899:
                // Clouds
                $icon_markup = '<i class="weather-i-clouds"></i>';
                $bg_img_class = ' bg-img-clouds';
                break;

            default:
                // Unknown
                $icon_markup = '<i class="weather-i-cloud-sun"></i>';
                $bg_img_class = ' bg-img-broken-clouds';
                break;
        }


        // Optional markup
        $weather_icon = $icon ? $icon_markup : '';
        $weather_date = $date && $style === 'style-expanded' ? '<small class="current-date">' . esc_html( date('d F Y') ) . '</small>' : '';
        $weather_desc = $desc ? '<small class="weather-description">' . esc_html( $description ) . '</small>' : '';
        $weather_wind = $wind ? '<figure class="weather-wind"><i class="weather-i-windy"></i><span>' . esc_html( $w_wind ) . '</span><figcaption>' . esc_html__( 'wind speed', 'weather-widget-wp' ) . '</figcaption></figure>' : '';
        $weather_temp_min = $min ? '<figure class="weather-temp-min"><i class="weather-i-temp-min"></i><span>' . esc_html( $temp_min ) . '</span><figcaption>' . esc_html__( 'min temp.', 'weather-widget-wp' ) . '</figcaption></figure>' : '';
        $weather_temp_max = $max ? '<figure class="weather-temp-max"><i class="weather-i-temp-max"></i><span>' . esc_html( $temp_max ) . '</span><figcaption>' . esc_html__( 'max temp.', 'weather-widget-wp' ) . '</figcaption></figure>' : '';

        $weather_footer_info = '';
        if ( $style === 'style-expanded' && ( $wind || $min || $max ) ) {
            $weather_footer_info = sprintf('<footer class="footer-group">%s%s%s</footer>', $weather_wind, $weather_temp_min, $weather_temp_max);
        }

        $html_class = 'weather-widget-wp';
        $html_class .= $bg_img ? $bg_img_class : '';
        $html_class .= empty( ! $css_class ) ? ' ' . esc_attr( $css_class ) : '';
        $html_class .= ' ' . $style;
        $html_class .= $icon && ( $style === 'style-expanded' ) ? '' : ' no-icon';

        $block_align_class = empty( ! $block_align ) ? ' block-align-' . esc_attr( $block_align ) : '';
        $css_style_output = empty( ! $css_style ) ? " style='" . esc_attr( $css_style ) . "'" : '';

        // Output the weather HTML
        $html = '
        <div class="wrapper-weather-widget-wp' . esc_attr( $block_align_class ) . '">
            <div class="' . esc_attr( $html_class ) . '" ' . $css_style_output . '>
                <div class="temp-group">
                    ' . $weather_icon . '
                    ' . $weather_date . '
                    <span class="weather-temp">' . esc_html( $temp ) . '<span class="temp-units">°' . esc_html( $units ) . '</span></span>
                </div>
                <div class="info-group">
                    <span class="weather-title">' . esc_html( $title ) . '</span>'
                    . $weather_desc .
                '</div>'
                . $weather_footer_info .
            '</div>' .
        '</div>';

        return wp_kses_post( $html );
    }
    add_shortcode('weather_widget_wp_location', 'weather_widget_wp_shortcode_weather_location');
}


/**
 * Preview Weather Widget Markup
 * - for newly created (or no/wrong) API keys that need 15 min to show data.
 *
 * @since v1.0.0
 */
if ( ! function_exists( 'weather_widget_wp_preview_widget' ) ) {
    function weather_widget_wp_preview_widget( $city = 'London', $units = 'C', $status ) {

        $html = sprintf('
            <div class="wrapper-weather-widget-wp">
                <div class="weather-widget-wp bg-img-sun style-expanded">
                    <div class="temp-group">
                        <i class="weather-i-sun"></i>
                        <small class="current-date">%1$s</small>
                        <span class="weather-temp">00<span class="temp-units">°%3$s</span></span>
                    </div>
                    <div class="info-group">
                        <span class="weather-title">%2$s</span><small class="weather-description">%4$s</small>
                    </div>
                    <footer class="footer-group">
                        <figure class="weather-wind"><i class="weather-i-windy"></i><span>0.0</span><figcaption>wind speed</figcaption></figure>
                        <figure class="weather-temp-min"><i class="weather-i-temp-min"></i><span>0.0°C</span><figcaption>min temp.</figcaption></figure>
                        <figure class="weather-temp-max"><i class="weather-i-temp-max"></i><span>0.0°C</span><figcaption>max temp.</figcaption></figure>
                    </footer>
                </div>
            </div>

            <div class="wrapper-weather-widget-wp">
                <span class="alert-error-401-weather-widget-wp">
                    <h6>' . esc_html__('401 - Unauthorized API Key', 'weather-widget-wp') . '</h6>
                    <p>'
                        . esc_html__('You probably have no API key added, typo or added newly created API key.', 'weather-widget-wp') .
                        '<br><strong>' . esc_html__('Newly created API keys take around 15 minutes to be active and show data, so please be patient and refresh the page after 15 min.', 'weather-widget-wp') . '</strong>
                    </p>
                </span>
            </div>
        ',

            esc_html( date('d F Y') ),  // 1
            esc_html( $city ),          // 2
            esc_html( $units ),         // 3
            esc_html( $status )         // 4
        );

        return wp_kses_post( $html );
    }
}
