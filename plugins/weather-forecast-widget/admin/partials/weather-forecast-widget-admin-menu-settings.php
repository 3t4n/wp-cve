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
	<form method="post" action="options.php">
		<?php
			settings_fields( 'weather-forecast-widget-settings' );
			do_settings_sections( 'weather-forecast-widget-settings' );
			submit_button();
		?>
	</form>
</div>