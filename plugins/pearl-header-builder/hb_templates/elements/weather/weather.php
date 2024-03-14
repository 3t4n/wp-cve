<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
if (!empty($element['value'])) {
	$city = $element['value']['city'];
	$units = $element['value']['units'];

	$weather = pearl_get_temp_by_city_name($city, $units);
}
?>

<div class="stm_weather">
	<div class="temperature">
		<?php echo wp_kses_post($weather['temp']); ?>
	</div>
	<div class="icon">
		<?php echo wp_kses_post($weather['icon']); ?>
	</div>
</div>
