<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2019, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine if the onboarding wizard should auto-initiate, and set up the Lightbox variables accordingly.
 * This will also pass a boolean variable to mpwpadmin called "doing_wizard"
 *
 * @since    1.0.0
 * @param    array $all_current_visual_states The array of the visual state prior to checking if the wizard is active.
 * @param    array $lightbox_visual_state The array of the lightbox state prior to checking if the wizard is active.
 * @return   array The values used to set the lightbox, and determine if mpwpadmin should be doing the wizard
 */
function church_tithe_wp_set_wizard_for_mpwpadmin( $all_current_visual_states, $lightbox_visual_state ) {

	// Set the defaults.
	$return_array = array(
		'doing_wizard'              => false,
		'all_current_visual_states' => $all_current_visual_states,
		'lightbox_visual_state'     => $lightbox_visual_state,
	);

	$current_wizard_status = get_option( 'church_tithe_wp_wizard_status' );

	// If the onboarding wizard has been completed, do not change the state of the URL for mpwpadmin.
	if ( 'completed' === $current_wizard_status || 'later' === $current_wizard_status ) {
		return $return_array;
	}

	// Set the onboarding wizard as in progress.
	update_option( 'church_tithe_wp_wizard_status', 'in_progress' );

	// We are going to tell mpwpadmin to run the wizard.
	$return_array['doing_wizard'] = true;

	// If the wizard is currently in progress, only affect the URL if there's no lightbox open.
	if ( ! empty( $lightbox_visual_state ) ) {
		return $return_array;
	}

	// Set the current visual state of mpwpadmin to be the page containing all of the wizard steps.
	// This is likely your health check page. For Church Tithe WP, that's the "welcome" page.
	$return_array['all_current_visual_states'] = array(
		'welcome' => array(),
	);

	// Get the onboarding Wizard Steps.
	$wizard_vars                  = church_tithe_wp_get_wizard_vars();
	$wizard_steps                 = $wizard_vars['wizard_steps'];
	$total_unhealthy_wizard_steps = $wizard_vars['total_unhealthy_wizard_steps'];
	$total_wizard_steps           = count( $wizard_steps );

	// Get the keys for the wizard steps.
	$keys = array_keys( $wizard_steps );

	// Set the open lightbox to be the first wizard step key.
	$return_array['lightbox_visual_state'] = array(
		$keys[0] . '_wizard_step' => array(),
	);

	return $return_array;
}

/**
 * Onboarding Wizard vars.
 * Not all Wizard Steps are Health Checks, and not all Health Checks are wizard Steps.
 * But in order to re-use code from Health Checks when they are also wizard steps, gather those here.
 *
 * @return   array $wizard_data
 */
function church_tithe_wp_get_wizard_vars() {

	$health_checks_and_wizard_steps = apply_filters( 'church_tithe_wp_health_checks_and_wizard_vars', array() );

	$wizard_step_data = array(
		'wizard_steps'                 => array(),
		'total_unhealthy_wizard_steps' => 0,
	);

	// Loop through each wizard step and only extract the wizard steps.
	foreach ( $health_checks_and_wizard_steps as $key => $health_check_or_wizard_step ) {
		$wizard_step_data = church_tithe_wp_add_wizard_step( $wizard_step_data, $health_check_or_wizard_step, $key );
	}

	// Sort the wizard checks by priority.
	uasort( $wizard_step_data['wizard_steps'], 'church_tithe_wp_sort_wizard_steps_by_priority' );

	return $wizard_step_data;
}

/**
 * Callback function for the usort function which sorts health checks by priority.
 *
 * @since    1.0.0
 * @param    array $x A health_check.
 * @param    array $y A health_check.
 * @return   int How much to shift the health check
 */
function church_tithe_wp_sort_wizard_steps_by_priority( $x, $y ) {
	return $x['priority'] - $y['priority'];
}

/**
 * Add a wizard step to the list of wizard steps.
 *
 * @since    1.0.0
 * @param    array $wizard_step_data All wizard steps.
 * @param    array $wizard_step The wizard step we want to add.
 * @param    array $key The key to use for the wizard step.
 * @return   array $wizard_steps
 */
function church_tithe_wp_add_wizard_step( $wizard_step_data, $wizard_step, $key ) {

	// Only add if this is a wizard step.
	if ( ! $wizard_step['is_wizard_step'] ) {
		return $wizard_step_data;
	}

	$wizard_step = array(
		$key => $wizard_step,
	);

	$wizard_step_data['wizard_steps'] = $wizard_step + $wizard_step_data['wizard_steps'];

	// Increment the healthy check counter.
	if ( ! $wizard_step[ $key ]['is_healthy'] ) {
		$wizard_step_data['total_unhealthy_wizard_steps'] = $wizard_step_data['total_unhealthy_wizard_steps'] + 1;
	}

	return $wizard_step_data;
}
