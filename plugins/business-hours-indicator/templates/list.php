<?php

	/** @var \MABEL_BHI_LITE\Models\List_VM $model */

	include_once '_helpers.php';

	if($model->show_location_error) {
		echo '<span>' . __( "No location found for this name. Either review your location name or go to Settings > Business Hours Indicator to set up locations.", 'business-hours-indicator' ) . '</span>';
		return;
	}

	echo $model->show_as_table ? '<table class="mabel-bhi-businesshours">' : '<div class="mabel-bhi-businesshours-inline">';

	render_normal_hours(
		$model->normal_entries,
		$model->show_as_table ? '<tr %s><td>%s</td><td>%s</td></tr>' : '<span %s>%s: %s</span>',
		$model->has_current === 1,
		$model->show_as_table ? '' : ', '
	);

	if($model->show_specials && sizeof($model->special_entries) > 0)
	{
		render_special_hours(
			$model->special_entries,
			$model->show_as_table ? '<tr class="mb-bhi-holiday%s"><td>%s</td><td>%s</td></tr>' : '<span class=mb-bhi-holiday%s">%s: %s</span>',
			$model->has_current === 2,
			$model->show_as_table ? '' : ', '
		);
	}

	if($model->show_vacations && sizeof($model->vacation_entries) > 0)
	{
		render_vacations(
			$model->vacation_entries,
			$model->show_as_table ? '<tr class="mb-bhi-vacations%s"><td>%s</td><td>%s</td></tr>' : '<span class=mb-bhi-vacations%s">%s: %s</span>',
			$model->has_current === 3,
			$model->slug,
			$model->show_as_table ? '' : ', '
		);
	}

	echo $model->show_as_table ? '</table>' : '</div>';

?>