<?php
/** @var \MABEL_BHI_LITE\Models\Indicator_VM $model */

if(!defined('ABSPATH')){die;}

if($model->show_location_error) {
	echo '<span>' . __( "No location found for this name. Either review your location name or go to Settings > Business Hours Indicator to set up locations.", 'business-hours-indicator' ) . '</span>';
	return;
}
?>

<span class="mb-bhi-display mb-bhi-<?php echo $model->open ? 'open' : 'closed'; ?>">
	<?php
		if($model->include_day || $model->include_time)
		{
			echo __("It's", 'business-hours-indicator');
			if($model->include_day)
				echo '<span class="mb-bhi-day"> ' .__($model->today, $model->slug). '</span>';
			if($model->include_time)
				echo '<span class="mb-bhi-time"> ' .$model->time. '</span>';
			echo ' &mdash; ';
		}
		echo '<span class="mb-bhi-oc-text">' .  wp_kses($model->indicator_text,[
				'br'        => [],
				'hr'        => ['class' => [], 'style' => [],'id' => []],
				'a'         => ['href' => [], 'target' => [], 'class' => [], 'style' => [],'id' => []],
				'i'         => ['class' => [], 'style' => [],'id' => []],
				'em'        => ['class' => [], 'style' => [],'id' => []],
				'strong'    => ['class' => [], 'style' => [],'id' => []],
				'b'         => ['class' => [], 'style' => [],'id' => []],
				'span'      => ['class' => [], 'style' => [],'id' => []],
				'div'       => ['class' => [], 'style' => [],'id' => []],
				'h1'      => ['class' => [], 'style' => [],'id' => []],
				'h2'      => ['class' => [], 'style' => [],'id' => []],
				'h3'      => ['class' => [], 'style' => [],'id' => []],
				'h4'      => ['class' => [], 'style' => [],'id' => []],
				'h5'      => ['class' => [], 'style' => [],'id' => []],
				'h6'      => ['class' => [], 'style' => [],'id' => []]
			]). '</span>';
	?>
</span>
