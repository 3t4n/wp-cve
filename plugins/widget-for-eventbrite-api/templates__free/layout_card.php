<?php
/**
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */
$data->event->layout_class = 'wfea-card';
$data->event->layout_name  = 'card';
$data->event->plan        = '__free';
add_filter( 'wfea_combined_date_time_date_format', '__return_empty_string', 99);

$data->template_loader->get_template_part( 'common_layout_card' );

