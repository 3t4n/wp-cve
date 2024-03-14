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
  <h2><?php echo __( 'Shortcode Information', 'weather-forecast-widget' ) ?></h2>
  <h4><u><?php echo __( 'Shortcode Name:', 'weather-forecast-widget' ) ?></u></h4>
  <p><strong>[weather_forecast_widget]</strong></p>
  <h4><u><?php echo __( 'Shortcode Attributes:', 'weather-forecast-widget' ) ?></u></h4>
  <ul style="list-style-type:disc;margin-left: 20px">
	<li><strong>template</strong> = <?php echo __( 'Template ID', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>1</strong> = <?php echo __( '<strong>Template 1</strong> (Current day´s weather with a weather forecast for the next 3 days)', 'weather-forecast-widget' ) ?></li>
			<li><strong>2</strong> = <?php echo __( '<strong>Template 2</strong> (Current day´s weather with an hourly weather forecast and a weather forecast for the next 3 days)', 'weather-forecast-widget' ) ?>
				<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
					<li style="color:red;"><strong><?php echo __( 'Template 2 uses Bootstrap!', 'weather-forecast-widget' ) ?></strong></li>
				</ul>
			</li>
			<li><strong>3</strong> = <?php echo __( '<strong>Template 3</strong> (Weather for the current day and for the next 7 days, each with an hourly weather forecast and a weather forecast for the next 3 days)', 'weather-forecast-widget' ) ?>
				<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
					<li style="color:red;"><strong><?php echo __( 'Template 3 uses Bootstrap!', 'weather-forecast-widget' ) ?></strong></li>
				</ul>
			</li>
			<li><strong>alert_1</strong> = <?php echo __( '<strong>Alert Template 1</strong> (Active weather alerts)', 'weather-forecast-widget' ) ?>
				<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
					<li style="color:red;"><strong><?php echo __( 'Alert Template 1 uses Bootstrap!', 'weather-forecast-widget' ) ?></strong></li>
				</ul>
			</li>
		</ul>
	</li>
	<li><strong>hourly_forecast</strong> = <?php echo __( 'Intitially expand/collapse hourly forecast (<strong>only relevant for template 2 and 3</strong>)', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>show</strong> = <?php echo __( 'Display the hourly weather forecast <strong>initially expanded</strong>', 'weather-forecast-widget' ) ?></li>
			<li><strong>hide</strong> = <?php echo __( 'Display the hourly weather forecast <strong>initially collapsed</strong>', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>
	<li><strong>daily_forecast</strong> = <?php echo __( 'Intitially expand/collapse daily forecast', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>show</strong> = <?php echo __( 'Display the daily weather forecast <strong>initially expanded</strong>', 'weather-forecast-widget' ) ?></li>
			<li><strong>hide</strong> = <?php echo __( 'Display the daily weather forecast <strong>initially collapsed</strong>', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>
	<li><strong>alerts</strong> = <?php echo __( 'Intitially expand/collapse alerts', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>show</strong> = <?php echo __( 'Display the weather alerts <strong>initially expanded</strong>', 'weather-forecast-widget' ) ?></li>
			<li><strong>hide</strong> = <?php echo __( 'Display the weather alerts <strong>initially collapsed</strong>', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>
	<li><strong>show_hourly_forecast</strong> = <?php echo __( 'Show hourly forecast in the widget (<strong>only relevant for template 2 and 3</strong>)', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>X</strong> = <?php echo __( '<strong>Show</strong> the hourly weather forecast in the widget', 'weather-forecast-widget' ) ?></li>
			<li><strong>" "</strong> = <?php echo __( '<strong>Don´t show</strong> the hourly weather forecast in the widget', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>
	<li><strong>show_daily_forecast</strong> = <?php echo __( 'Show daily forecast in the widget ', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>X</strong> = <?php echo __( '<strong>Show</strong> the daily weather forecast in the widget', 'weather-forecast-widget' ) ?></li>
			<li><strong>" "</strong> = <?php echo __( '<strong>Don´t show</strong> the daily weather forecast in the widget', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>	
	<li><strong>show_alerts</strong> = <?php echo __( 'Show alerts in the widget', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>X</strong> = <?php echo __( '<strong>Show</strong> the weather alerts in the widget', 'weather-forecast-widget' ) ?></li>
			<li><strong>" "</strong> = <?php echo __( '<strong>Don´t show</strong> the weather alerts in the widget', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>	
	<li><strong>lazy_loading</strong> = <?php echo __( 'Lazy Loading', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>X</strong> = <?php echo __( '<strong>Activate</strong> lazy loading to load the data only when necessary', 'weather-forecast-widget' ) ?></li>
			<li><strong>" "</strong> = <?php echo __( '<strong>Don´t activate</strong> lazy loading to load the data only when necessary', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>
	<li><strong>city</strong> = <?php echo __( 'City Name', 'weather-forecast-widget' ) ?></li>
 	<li><strong>lat</strong> = <?php echo __( 'Latitude Coordinate', 'weather-forecast-widget' ) ?></li>
 	<li><strong>lon</strong> = <?php echo __( 'Longitude Coordinate', 'weather-forecast-widget' ) ?></li>
	<li><strong>title_cityname</strong> = <?php echo __( 'Show city name as widget title', 'weather-forecast-widget' ) ?>
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>X</strong> = <?php echo __( '<strong>Show</strong> city name as widget title', 'weather-forecast-widget' ) ?></li>
			<li><strong>" "</strong> = <?php echo __( '<strong>Don´t show</strong> city name as widget title', 'weather-forecast-widget' ) ?></li>
		</ul>
	</li>
 	<li><strong>title_overwrite</strong> = <?php echo __( 'Overwrite widget title with this title', 'weather-forecast-widget' ) ?></li>
	<li><strong>max_width</strong> = <?php echo __( 'Maximum width of the widget (Default: 500px)', 'weather-forecast-widget' ) ?></li>
  </ul>
  <h4><u><?php echo __( 'Shortcode Examples:', 'weather-forecast-widget' ) ?></u></h4>
  <ul style="list-style-type:disc;margin-left: 20px">
 	<li><?php echo __( 'Retrieve weather data for a <strong>city</strong>', 'weather-forecast-widget' ) ?> 
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>[weather_forecast_widget city="Kufstein"]</strong></li>
		</ul>
	</li>
	<li><?php echo __( 'Retrieve weather data for a <strong>city</strong> and show <strong>city name as title text</strong>', 'weather-forecast-widget' ) ?> 
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>[weather_forecast_widget city="Kufstein" title_cityname="X"]</strong></li>
		</ul>
	</li>
 	<li><?php echo __( 'Retrieve weather data for <strong>specific coordinates</strong>', 'weather-forecast-widget' ) ?> 
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>[weather_forecast_widget lat="47.5824" lon="12.1627"]</strong></li>
		</ul>
	</li>
	<li><?php echo __( 'Retrieve weather data for <strong>specific coordinates</strong> and show <strong>"Weather for Weather Widget" as title text</strong>', 'weather-forecast-widget' ) ?> 
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>[weather_forecast_widget lat="47.5824" lon="12.1627" title_overwrite="Weather for Weather Widget"]</strong></li>
		</ul>
	</li>
 	<li><?php echo __( 'Retrieve weather data <strong>with lazy loading and template 3 for specific coordinates</strong> and <strong>expand the hourly and daily forecast initially</strong>', 'weather-forecast-widget' ) ?> 
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>[weather_forecast_widget lazy_loading="X" template="3" hourly_forecast="show" daily_forecast="show" lat="47.5824" lon="12.1627"]</strong></li>
		</ul>
	</li>
 	<li><?php echo __( 'Retrieve weather alerts <strong>with lazy loading and alert template 1 for specific coordinates</strong> and <strong>expand the weather alerts initially</strong>', 'weather-forecast-widget' ) ?> 
		<ul style="list-style-type:circle;margin-top:5px;margin-left: 40px">
			<li><strong>[weather_forecast_widget lazy_loading="X" template="alert_1" alerts="show" lat="47.5824" lon="12.1627" max_width="500px"]</strong></li>
		</ul>
	</li>
  </ul>

</div>