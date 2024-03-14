<?php

if($model->show_location_error) {
	echo '<span>' . __( "No location found for this name. Either review your location name or go to Settings > Business Hours Indicator to set up locations.", 'business-hours-indicator' ) . '</span>';
	return;
}

if($model->show_content)
	echo $model->content;

?>

