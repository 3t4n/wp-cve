<?php
/**
 * Front end display of shortcode loop
 * can be overridden in child themes / themes or in wp-content/widget-for-eventbrite-api folder if you don't have a child theme and you don't want to lose changes due to themes updates
 *
 * To customise create a folder in your theme directory called widget-for-eventbrite-api and a modified version of this file or any template_parts renamed as appropriate
 *
 * The main structure is in get_template_part( 'loop__free_widget' );
 *
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */
$data->event->layout_class = 'wfea-widget';
$data->event->layout_name  = 'widget';
$data->event->plan        = '__free';

$data->template_loader->get_template_part( 'common_layout_widget' );


