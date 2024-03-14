<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.bergtourentipp-tirol.at
 * @since      1.0.0
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
  <h1>Weather Forecast Widget</h1>
  <br />
  <h2><?php echo __( 'General Information', 'weather-forecast-widget' ) ?></h2>
  <p><?php echo __( 'This plugin <strong>Weather Forecast Widget</strong> shows a widget with current weather and 3-day forecast weather data.', 'weather-forecast-widget' ) ?></p>
  <p><?php echo __( 'The weather data will be retrieved from the Open Weather Map API (<a href="https://openweathermap.org/api" rel="noopener" target="_blank"><strong>OpenWeatherMap API</strong></a>) with your own API key.', 'weather-forecast-widget' ) ?></p>
  <p><?php echo __( 'You can show the weather for a <strong>CITY</strong> or for specific <strong>COORDINATES (Latitude & Longitude)</strong> in the widget.', 'weather-forecast-widget' ) ?></p>
  <p><?php echo __( 'The weather widget can be implemented in a page, a post or into the widget area with the help of a shortcode.', 'weather-forecast-widget' ) ?></p>
  <br />
  <h2><?php echo __( 'Support', 'weather-forecast-widget' ) ?></h2>
  <p><?php echo __( 'If you have any questions or ideas for new features, please don´t hesitate to create a support topic in the <a href="https://wordpress.org/support/plugin/weather-forecast-widget/" rel="noopener" target="_blank"><strong>support forum</strong></a>.', 'weather-forecast-widget' ) ?></p>
  <br />
  <h2><?php echo __( 'Review/Rating', 'weather-forecast-widget' ) ?></h2>
  <p><?php echo __( 'If you like this Weather Forecast Plugin, we would be delighted if you would give a <a href="https://wordpress.org/support/plugin/weather-forecast-widget/reviews/#new-post" rel="noopener" target="_blank"><strong>review/rating here</strong></a>.', 'weather-forecast-widget' ) ?></p>
  <br />
  <h2><?php echo __( 'Donation', 'weather-forecast-widget' ) ?></h2>
  <p><?php echo __( 'The creation and maintenance of a free plugin also means effort. If you like the plugin and would like to support it, you can donate something <a href="https://www.paypal.com/paypalme/bergtourentipptirol" rel="noopener" target="_blank"><strong>here</strong></a>.', 'weather-forecast-widget' ) ?></p>

</div>