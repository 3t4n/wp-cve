<?php
/**
 * Editorial Calendar Settings only.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/data
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

return array(

	array(
		'type'     => 'custom',
		'name'     => 'calendar_post_types',
		'label'    => esc_html_x( 'Managed Post Types', 'text', 'nelio-content' ),
		'instance' => new Nelio_Content_Calendar_Post_Type_Setting(),
		'default'  => array( 'post' ),
	),

	array(
		'type'     => 'custom',
		'name'     => 'use_ics_subscription',
		'label'    => esc_html_x( 'iCal Calendar Feed', 'text', 'nelio-content' ),
		'desc'     => esc_html_x( 'Export your calendar posts to Google Calendar or any other calendar tool.', 'user', 'nelio-content' ),
		'instance' => new Nelio_Content_ICS_Calendar_Setting(),
		'default'  => false,
	),

);
